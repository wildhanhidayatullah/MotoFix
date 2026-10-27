# Security

> Status: Approved for implementation
> Audience: Engineering, operators, and security reviewers

## 1. Security goals

- Only authorized users can access data for their assigned branches.
- Financial and inventory operations cannot be manipulated by the client.
- Customer, vehicle, and financial data remain inside the installation.
- A compromised browser session has limited impact.
- Operators can install and maintain the system without introducing obvious
  security weaknesses.
- Security defects can be reported and handled responsibly.

## 2. Assets

| Asset                               | Sensitivity | Notes                      |
| ----------------------------------- | ----------- | -------------------------- |
| User credentials and sessions       | Critical    | Account takeover           |
| Customer identity and phone numbers | High        | Personal data              |
| Vehicle identifiers                 | High        | Personal and business data |
| Transactions and payments           | Critical    | Financial integrity        |
| Inventory and stock movements       | High        | Business integrity         |
| Audit logs                          | High        | Non-repudiation            |
| Backups                             | Critical    | Full data copy             |
| Configuration and secrets           | Critical    | Infrastructure access      |
| Application source and dependencies | Medium      | Supply chain               |

## 3. Actors and trust boundaries

| Actor              | Trust         | Capabilities                      |
| ------------------ | ------------- | --------------------------------- |
| Anonymous visitor  | Untrusted     | Login page only                   |
| Authenticated user | Limited trust | Role and branch-scoped operations |
| Owner              | High trust    | Organization-wide administration  |
| Admin              | High trust    | Operational administration        |
| Branch manager     | Medium trust  | Assigned branch operations        |
| Cashier            | Medium trust  | Sales and customer operations     |
| Mechanic           | Low trust     | Assigned work orders              |
| Operator           | High trust    | Server and CLI access             |
| External attacker  | Untrusted     | Network and application attacks   |

Trust boundaries:

- Browser to Caddy.
- Caddy to PHP-FPM.
- Application to MariaDB.
- Application to local storage.
- Operator to CLI and Docker.
- Backup destination to installation.

## 4. Threat model

The project uses a lightweight STRIDE analysis.

| Threat                 | Example                                             | Control                                                                  |
| ---------------------- | --------------------------------------------------- | ------------------------------------------------------------------------ |
| Spoofing               | Stolen session cookie or credential stuffing        | Secure cookies, session rotation, rate limiting, password hashing        |
| Tampering              | Client changes price, total, or branch ID           | Server-authoritative pricing, branch checks, signed or server-held state |
| Repudiation            | User denies voiding a transaction                   | Immutable audit log with actor and timestamps                            |
| Information disclosure | Cross-branch access, error leakage, backup exposure | Authorization checks, generic errors, backup permissions                 |
| Denial of service      | Login flood, expensive reports, large imports       | Rate limiting, pagination, input limits, timeouts                        |
| Elevation of privilege | Cashier changes roles or voids transactions         | Permission checks, role separation, audit                                |
| Supply chain           | Malicious dependency                                | Lockfile, review, `composer audit`, pinned images                        |
| Operator error         | Public database, weak password, no backup           | Secure defaults, installer checks, documentation                         |

## 5. Authentication

### Password storage

- Passwords are hashed with Argon2id when available.
- If Argon2id is unavailable, use the configured memory-hard fallback.
- Password hashes are never logged or returned by the API.
- Rehashing occurs on successful login when parameters change.

### Password policy

- Minimum length: 12 characters.
- Must not be identical to the email or username.
- Must not be a known common password if a local list is available.
- Password strength feedback is shown without revealing policy bypasses.
- Password reset tokens are random, hashed at rest, single-use, and time-limited.

### Login

- Login identifier is email.
- Invalid credentials return a generic message.
- Inactive users cannot sign in.
- Failed attempts are recorded in `login_attempts`.
- Rate limiting applies per account and per IP.
- Progressive delays or temporary lockout are applied after repeated failures.
- Successful login regenerates the session ID.
- The old session data is destroyed.
- The user's locale and branch selection are initialized safely.

### Logout

- Logout is a POST request with CSRF protection.
- Session data is destroyed.
- The session cookie is expired.
- Logout is audited.

### Session management

- Session cookies use `HttpOnly`, `SameSite=Lax`, and `Secure` in production.
- Session name is application-specific.
- Session lifetime is configurable.
- Idle timeout is configurable.
- Sessions are regenerated after privilege changes.
- Session files are stored outside the web root.
- Shared hosting or multi-server deployments require a shared session store; this
  is not part of the pilot.

## 6. Authorization

- Authorization is enforced server-side on every request.
- Permissions are defined in the PRD and seeded in the database.
- Roles are assigned globally or per branch.
- Branch context is resolved before authorization.
- Object-level checks verify ownership and branch membership.
- Controllers never trust a branch ID from the request without validation.
- Views may hide actions, but hiding is not authorization.
- Denied access returns 403 for known resources and 404 where existence must not
  be revealed.
- The last active owner cannot be removed.
- Users cannot deactivate themselves.
- Role changes take effect on the next request.
- Customers and vehicles are readable by owner, admin, manager, and cashier
  across the installation.
- A mechanic can read only customers and vehicles linked to assigned work orders.
- A cashier may create a customer or vehicle. A cashier can edit one only when it
  was created in an assigned branch or has a work order or transaction in an
  assigned branch.
- Mobile web uses the same authentication, session, and authorization rules as
  desktop.
- Authorization decisions are covered by automated tests.

See [ADR-0003](decisions/ADR-0003-branch-authorization.md).

## 7. Input validation

- All input is validated server-side.
- Validation rules are explicit per endpoint.
- Unknown fields are ignored or rejected according to the endpoint contract.
- Numeric internal identifiers must be positive integers.
- External public identifiers must match the ULID format.
- Monetary values must be non-negative integers within supported bounds.
- Quantities must be positive integers.
- Tax rates are bounded between 0 and 10000 basis points.
- Dates and times are parsed strictly.
- Enumerations are allow-listed.
- File imports have size, row count, encoding, and MIME restrictions.
- Rich text is not accepted in the pilot.

## 8. Output encoding

- Twig autoescaping is enabled.
- Raw HTML output requires an explicit, reviewed decision.
- JavaScript receives data through JSON encoding with safe flags, never string concatenation.
- Inline event handlers are avoided.
- Dynamic HTML is built with DOM APIs, not `innerHTML` with user data.
- URL parameters are encoded.
- CSV exports protect against formula injection by prefixing dangerous leading characters.
- Print views escape all displayed data.

## 9. SQL injection

- All queries use prepared statements.
- Table and column names come from code, never from request input.
- Sort and filter fields are allow-listed.
- `LIMIT` and `OFFSET` are cast to integers.
- Repositories are the only place that builds SQL.
- Raw SQL requires review and tests.

## 10. Cross-site request forgery

- All unsafe methods require a CSRF token.
- Tokens are per session and compared with `hash_equals`.
- Tokens are rotated after sign-in and sign-out.
- Missing tokens are rejected with a 419 or 403 response, not a PHP error.
- GET requests must not change state.
- Logout is POST.
- Deletions and voids are POST or DELETE with CSRF.

## 11. Cross-site scripting

- Autoescaping is the default.
- Content Security Policy is set with a nonce or hash policy where inline scripts
  are unavoidable.
- Third-party scripts are not loaded from CDNs.
- User-generated content is treated as text.
- Stored XSS tests cover customer names, item names, notes, and receipt fields.
- Error pages and flash messages are escaped.

## 12. Insecure direct object references

- Every resource lookup is scoped by branch and permission.
- IDs from URLs are never trusted.
- Customers and vehicles use a non-sequential ULID `public_id` in external URLs;
  internal integer IDs remain private.
- Work orders and transactions also use `public_id` in external URLs. Invoice
  numbers remain human-readable business identifiers.
- `public_id` reduces casual enumeration but is not an authorization control.
  Every lookup still checks the user, permission, and branch scope.
- A transaction, work order, stock record, or payment must belong to an
  authorized branch.
- The API returns 404 for resources the user cannot access where appropriate.
- Tests attempt cross-branch access for every branch-scoped resource and for
  customer/vehicle access by mechanics.

See [ADR-0009](decisions/ADR-0009-identifier-strategy.md).

## 13. File import security

- CSV imports are accepted only from authenticated users with `import.manage`.
- File size and row count are limited.
- MIME type and extension are checked; content is parsed as CSV, not executed.
- Formulas are not evaluated.
- Imported data is validated and escaped on display.
- Import errors do not expose server paths.
- Temporary files are removed after processing.
- Import runs are audited.
- XLSX export uses a library; imported XLSX files are not accepted in the pilot.

## 14. File uploads

The pilot does not accept arbitrary file uploads. The mobile mechanic flow does
not request camera or photo permissions. Pilot receipts use the organization
name as a text wordmark; an image logo is a v1.0.0 feature (see
[10 Hardware Integration](10-hardware-integration.md)). If the logo feature is
implemented, uploads must be:

- restricted to image types;
- size-limited;
- re-encoded if possible;
- stored outside the web root or served through a controlled route;
- randomized in filename;
- stripped of metadata where practical.

In the pilot `logo_path` stays `NULL` or references a pre-configured asset path;
logo uploads are an ORG-03 v1.0.0 feature and the rules above apply to them.

## 15. Security headers

Caddy or the application sets:

- `Content-Security-Policy`
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY` or CSP `frame-ancestors 'none'`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy` with unused features disabled
- `Strict-Transport-Security` in production
- `Cache-Control` appropriate to the response

Headers are tested in feature tests.

## 16. Secrets and configuration

- `.env` is never committed.
- `.env.example` contains placeholders only.
- The installer generates database passwords and application secrets.
- Secrets are not displayed in logs, support bundles, or error pages.
- Production refuses to start with debug enabled.
- Database ports are not published.
- Caddy is the only public entry point.
- Backups are protected by filesystem permissions and operator access.

## 17. Audit logging

Audited actions include:

- sign-in, sign-out, failed sign-in, password change, password reset;
- user creation, role change, deactivation;
- branch creation, update, deactivation;
- organization settings and tax changes;
- catalogue price changes and archival;
- stock adjustment and transfer;
- work order creation, assignment, status change, cancellation;
- transaction creation, payment, void;
- import run and result;
- backup, restore, and maintenance mode changes.

Audit records include actor, branch, action, entity, before/after values, IP, user
agent, and UTC timestamp. Sensitive values are redacted. Audit records are
append-only and not editable through the UI.

## 18. Error handling and logging

- Production errors show a generic page with a correlation ID.
- Stack traces and SQL errors are never shown to users.
- Logs include context but not passwords, tokens, or full payment references.
- Log injection is prevented by structured logging.
- The support bundle redacts secrets and personal data where possible.
- Health checks do not expose sensitive configuration.

## 19. Rate limiting and abuse controls

- Login attempts are rate limited.
- Password reset requests are rate limited.
- Import endpoints have file and row limits.
- Report exports have row limits or streaming.
- Public endpoints are minimal.
- Expensive operations are authenticated and authorized.

A distributed rate limiter is not required for a single-server installation. A
database or file-based limiter is sufficient, with a documented limitation.

## 20. Dependency and supply chain security

- `composer.lock` is committed.
- `composer audit` runs in CI once CI workflows exist.
- Docker images are pinned by version or digest.
- GitHub Actions use pinned versions.
- Dependabot or Renovate is enabled when repository automation is configured.
- Release artifacts are built by CI, not on an operator workstation.
- Release notes identify security-relevant changes.
- The project publishes a security policy and supported versions.

## 21. Operator security

The installation guide must cover:

- using a supported Linux distribution;
- keeping the OS and Docker updated;
- restricting SSH access;
- using key-based SSH authentication;
- enabling a firewall;
- exposing only ports 80 and 443;
- not publishing the database port;
- using strong owner credentials;
- enabling automatic HTTPS;
- testing backups and offsite copies;
- monitoring disk usage;
- rotating secrets after suspected compromise.

The application cannot protect an operator who exposes the database or disables
HTTPS. Secure defaults and documentation are therefore part of the security model.

## 22. Privacy and data protection

- No telemetry.
- No external analytics.
- Customer data remains in the installation.
- Logs avoid unnecessary personal data.
- Backups may contain personal data and must be protected.
- Data retention follows the organization's policy.
- Data export is available through backups and reports.
- Data deletion is limited by financial and audit retention requirements.

## 23. Security testing

The test suite must include:

| Test                                               | Expected result                |
| -------------------------------------------------- | ------------------------------ |
| Cross-branch transaction access                    | 403 or 404                     |
| Cross-branch work order access                     | 403 or 404                     |
| Manager/cashier read cross-branch customer         | Allowed                        |
| Cashier edits customer unlinked to assigned branch | 403                            |
| Mechanic reads unassigned customer/vehicle         | 403 or 404                     |
| Mechanic reads financial data                      | 403 or absent                  |
| Mechanic cancels work order on mobile              | 403                            |
| Public ID guesses another branch resource          | 403 or 404                     |
| Internal integer ID in external URL                | Not exposed                    |
| Cashier attempts void                              | 403                            |
| Mechanic attempts price change                     | 403                            |
| CSRF missing or invalid                            | 419 or 403                     |
| Stored XSS in customer name                        | Escaped output                 |
| Stored XSS in item name                            | Escaped output                 |
| Price tampering in checkout                        | Server price wins              |
| Negative stock checkout                            | Rejected                       |
| Duplicate invoice concurrency                      | Unique numbers                 |
| Session fixation                                   | Session ID changes             |
| Login brute force                                  | Rate limited                   |
| Inactive user login                                | Rejected                       |
| Role removed mid-session                           | Access removed on next request |
| CSV formula injection                              | Neutralized on export          |
| Import path traversal                              | Rejected                       |
| Error page leakage                                 | No stack trace                 |

Additional checks:

- `composer audit`;
- dependency review;
- container image scan where available;
- manual review of authentication, authorization, payment, and import changes.

## 24. OWASP Top 10 mapping

| Risk                                       | Primary controls                                                   |
| ------------------------------------------ | ------------------------------------------------------------------ |
| Broken access control                      | Server-side authorization, branch context, object checks, tests    |
| Cryptographic failures                     | HTTPS, secure cookies, password hashing, secrets management        |
| Injection                                  | Prepared statements, autoescaping, allow-lists, CSV safety         |
| Insecure design                            | Threat model, ADRs, server-authoritative money and stock           |
| Security misconfiguration                  | Secure defaults, installer checks, headers, no debug in production |
| Vulnerable components                      | Lockfile, audit, pinned images, dependency updates                 |
| Identification and authentication failures | Rate limiting, session rotation, password policy                   |
| Software and data integrity failures       | Signed releases, CI builds, checksums, audit logs                  |
| Logging and monitoring failures            | Structured logs, audit log, health, support bundle                 |
| Server-side request forgery                | No outbound user-controlled requests in the pilot                  |

## 25. Vulnerability disclosure

- Security reports use the private vulnerability reporting channel of the repository.
- Public issues must not contain exploit details.
- Reports should include affected version, reproduction steps, impact, and any
  suggested fix.
- The maintainer acknowledges reports and coordinates a fix and disclosure.
- Supported versions and disclosure expectations are documented in `SECURITY.md`.

## 26. Security acceptance criteria

A release is not approved if any of the following is true:

- a known critical or high vulnerability is unpatched;
- server-side price or stock validation is missing;
- cross-branch access tests fail;
- CSRF protection is missing on an unsafe endpoint;
- authentication or authorization can be bypassed;
- error pages expose stack traces or SQL errors in production;
- backups cannot be restored;
- secrets are committed to the repository;
- the database is publicly exposed by the default deployment.

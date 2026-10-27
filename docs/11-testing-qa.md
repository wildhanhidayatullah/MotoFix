# Testing and QA

> Status: Approved for implementation
> Audience: Engineering, QA, reviewers

## 1. Quality goals

- Financial and inventory behavior is correct under normal and concurrent use.
- Branch isolation is enforced on every endpoint.
- Critical user flows work on a clean installation.
- Releases are reproducible and verifiable.
- Tests document intended behavior and protect against regressions.

## 2. Test strategy

```text
             UAT and smoke tests
          Feature / HTTP tests
       Integration / database tests
            Unit tests
```

| Layer          | Scope                                                            | Tools                 |
| -------------- | ---------------------------------------------------------------- | --------------------- |
| Unit           | Money, tax, validation, policies, value objects                  | PHPUnit               |
| Integration    | Repositories, migrations, transactions, stock, invoice sequences | PHPUnit + MariaDB     |
| Feature / HTTP | Auth, routing, CSRF, authorization, CRUD, checkout, import       | PHPUnit               |
| Security       | Cross-branch, tampering, XSS, CSRF, session                      | PHPUnit               |
| Hardware       | Browser print layout, HID scanner behavior                       | Manual checklist      |
| Operational    | Install, backup, restore, upgrade, health                        | Scripted smoke tests  |
| UAT            | End-to-end operational scenarios                                 | Manual with demo data |

Tests are deterministic. Tests do not depend on external network services.

## 3. Environments

| Environment | Purpose                                                       |
| ----------- | ------------------------------------------------------------- |
| Local       | Development and unit tests                                    |
| CI          | Formatting, static analysis, unit, integration, feature tests |
| Staging     | Install, upgrade, backup, restore, and UAT                    |
| Production  | Smoke checks only; no destructive tests                       |

Integration tests use a dedicated MariaDB database. Feature tests reset the
database to a known state.

## 4. Test data

- Reference seeds: roles, permissions, role mappings.
- Fixture factories for users, branches, customers, vehicles, items, services, and
  transactions.
- Demo seed for UAT: one organization, two branches, users for each role, sample
  stock, sample work orders, paid and void transactions.
- No production data is used in tests.
- Tests never rely on execution order.

## 5. Static analysis and formatting

- PHPStan at the agreed level, targeting level 8.
- PHP-CS-Fixer with PSR-12 rules.
- No unreviewed ignores.
- New code must not increase the error baseline.
- CI fails on formatting differences and new static analysis errors.

## 6. Unit tests

Required coverage:

- money formatting and arithmetic;
- tax calculation, rounding, and zero-rate behavior;
- discount bounds;
- percentage discount rounding and basis-point limits (v1.0.0);
- invoice number formatting;
- price resolution with and without branch overrides;
- validation rules;
- permission and policy decisions;
- locale and number formatting;
- CSV formula neutralization.

Unit tests do not touch the database.

## 7. Integration tests

Required coverage:

- migrations from an empty database;
- rollback of the last migration batch in development;
- repository branch scoping;
- unique constraints for email, username, SKU, barcode, plate, invoice;
- duplicate global role assignment is rejected by the generated-column
  uniqueness on `user_roles`;
- unique and immutable ULID `public_id` for customers, vehicles, work orders, and
  transactions;
- stock movement creation;
- conditional stock decrement;
- invoice sequence allocation under concurrency;
- transaction creation and rollback;
- audit log append-only behavior;
- settings retrieval and caching boundaries;
- import run and error persistence.

Integration tests use a real MariaDB instance because behavior depends on
transactions, locks, and constraints.

## 8. Feature and HTTP tests

Required coverage:

- login success, failure, inactive user, rate limit;
- session rotation;
- logout via POST and CSRF;
- CSRF rejection on unsafe methods;
- role and branch authorization;
- branch selector contents;
- customer and vehicle CRUD;
- global customer/vehicle read for manager and cashier;
- cashier edit scope for linked and unlinked customer/vehicle records;
- cashier assigns a mechanic and the assignment is audited;
- organization settings (`organization.settings`) are editable by the owner only;
  branch settings enforce `branch.manage`;
- mutations use POST or DELETE; GET and unsupported methods are rejected;
- security headers are present on responses;
- mechanic customer/vehicle visibility limited to assigned work orders;
- catalogue CRUD;
- inventory adjustment;
- work order lifecycle;
- mechanic mobile work order list and status updates;
- checkout and payment;
- receipt rendering;
- transaction void;
- CSV import and export;
- XLSX export;
- public_id route binding for customers, vehicles, work orders, and transactions;
- public_id uniqueness and immutability;
- language switching;
- audit creation for critical actions;
- error pages do not leak stack traces.

## 9. Security test matrix

| Test                                               | Expected result                       |
| -------------------------------------------------- | ------------------------------------- |
| Cross-branch transaction detail                    | 403 or 404                            |
| Cross-branch work order detail                     | 403 or 404                            |
| Cross-branch payment                               | 403 or 404                            |
| Cross-branch stock adjustment                      | 403 or 404                            |
| Branch filter manipulation                         | Only authorized branches returned     |
| Cashier voids transaction                          | 403                                   |
| Cashier assigns mechanic                           | Allowed and audited                   |
| Non-owner edits organization settings              | 403                                   |
| State change via GET or unsupported method         | Rejected                              |
| Cashier changes catalogue price                    | 403                                   |
| Mechanic changes transaction                       | 403                                   |
| Mechanic views unassigned work order               | 403 or 404                            |
| Manager reads cross-branch customer                | Allowed                               |
| Cashier reads cross-branch customer                | Allowed                               |
| Cashier edits customer unlinked to assigned branch | 403                                   |
| Cashier edits customer linked to assigned branch   | Allowed                               |
| Mechanic views unassigned customer/vehicle         | 403 or 404                            |
| Mechanic views financial data                      | 403 or absent                         |
| Mechanic cancels work order from mobile            | 403                                   |
| Public ID resolves authorized resource             | Allowed                               |
| Internal integer ID exposed in external URL        | Rejected by design/tests              |
| Duplicate public_id                                | Rejected by unique constraint         |
| Missing CSRF token                                 | 419 or 403                            |
| Invalid CSRF token                                 | 419 or 403                            |
| Stored XSS in customer name                        | Escaped                               |
| Stored XSS in item name                            | Escaped                               |
| Stored XSS in notes                                | Escaped                               |
| Price tampering                                    | Server price wins or request rejected |
| Negative quantity                                  | Rejected                              |
| Negative stock                                     | Rejected                              |
| Duplicate invoice                                  | Rejected by unique constraint         |
| Concurrent checkout                                | No oversell, unique invoices          |
| Session fixation                                   | Session ID changes after login        |
| Inactive user session                              | Access denied on next request         |
| Role removal                                       | Access denied on next request         |
| Last owner removal                                 | Rejected                              |
| CSV formula export                                 | Neutralized                           |
| Import path traversal                              | Rejected                              |
| Security headers on responses                      | Present                               |
| Login brute force                                  | Rate limited                          |
| Error response                                     | No stack trace or SQL text            |

## 10. Financial tests

- Subtotal is the sum of line totals.
- Discount cannot exceed subtotal.
- Percentage discount rounds half up to whole rupiah and applies before tax (v1.0.0).
- Tax uses the documented formula.
- Rounding is half up.
- Grand total is never negative.
- Cash change is correct.
- Payment total matches the transaction.
- Voided transactions are excluded from net sales.
- Historical transactions keep price and tax snapshots.
- Invoice numbers are never reused.

## 11. Inventory tests

- Opening stock creates a movement.
- Adjustment creates a movement and cannot make stock negative.
- Sale creates a negative movement.
- Void creates a positive movement.
- Stock is per branch.
- Cross-branch stock cannot be sold.
- Low-stock threshold is respected.
- Concurrent sales cannot oversell.

## 12. Import and export tests

- Valid CSV imports all rows.
- Invalid rows are reported with row numbers.
- Duplicate business keys are handled according to the template.
- Import is atomic or clearly reports partial success policy.
- Import run is recorded.
- CSV export neutralizes formulas.
- XLSX export opens correctly and preserves numeric types.
- Large files respect size and row limits.
- Malformed encoding is rejected with a clear message.
- Path traversal in import file names is rejected.

## 13. Internationalization tests

- Every key exists in both `en` and `id`.
- Missing keys fall back to English.
- Language switch persists.
- Dates and numbers use locale formatting.
- Status labels are translated.
- No hardcoded user-facing string in templates.

## 14. Hardware tests

Manual checklist:

- Print 58 mm receipt.
- Print 80 mm receipt.
- Long names wrap correctly.
- Discount and tax render correctly.
- Reprint marker appears.
- HID scanner finds item barcode.
- HID scanner finds SKU.
- HID scanner finds invoice.
- HID scanner finds plate and phone.
- Unknown code shows a clear error.
- No double-add on duplicate Enter.
- Application works without a printer.

### 14.1 Mobile tests

Manual and automated checks:

- Android Chrome at 360×640, 390×844, and 412×915.
- Bottom navigation is reachable and does not overlap safe areas.
- Work order cards show status, customer, vehicle, and updated time.
- Mechanic can start, mark waiting for parts, complete, and add notes.
- Mechanic cannot cancel a work order.
- No price, payment, checkout, or financial history is visible.
- Core actions require no horizontal scrolling at 360 px.
- Touch targets are at least 44 px.
- Session and branch authorization match desktop behavior.
- Camera permissions are not requested.

## 15. Operational tests

- Clean install with the documented installer.
- Install with an invalid domain fails safely.
- Install with weak configuration is rejected.
- Health endpoint returns expected status.
- Doctor reports known-good state.
- Backup creates a valid archive.
- Restore on a clean installation succeeds.
- Restore requires confirmation.
- Upgrade from the previous supported release succeeds.
- Failed upgrade rollback path is documented and tested.
- Disk-full behavior shows a clear error.
- Misconfigured database fails with a safe message.

## 16. UAT scenarios

The pilot uses demo data because a design-partner garage is not yet available.

Scenarios:

1. Owner signs in, reviews consolidated dashboard, switches to a branch.
2. Owner creates a branch and assigns a manager.
3. Cashier registers a customer and vehicle.
4. Cashier creates a work order, adds a service and a part, assigns a mechanic.
5. Mechanic signs in on Android Chrome, opens an assigned job, moves it to in
   progress, waiting for parts, and completed, and adds a note.
6. Mechanic attempts to cancel a job and is denied.
7. Mechanic confirms no price, payment, or checkout data is visible on mobile.
8. Cashier checks out, applies a discount, posts cash payment, prints receipt.
9. Cashier voids the transaction with a reason.
10. Manager adjusts stock and reviews the movement.
11. Owner imports customers and items from CSV.
12. Owner exports sales and inventory to XLSX.
13. Owner switches the UI to Indonesian and back to English.
14. Owner backs up, restores on staging, and verifies data.
15. Unauthorized user attempts cross-branch access and unassigned customer access.
16. Operator installs from scratch and completes the go-live checklist.

Each scenario records expected result, actual result, and defects.

## 17. Release regression checklist

Before a release:

- CI is green.
- Static analysis passes.
- Formatting passes.
- Unit, integration, and feature tests pass.
- Security matrix passes.
- UAT scenarios pass.
- Hardware checklist passes for supported devices.
- Clean install passes.
- Upgrade from the previous release passes.
- Backup and restore pass.
- No open severity-1 or severity-2 defects.
- Documentation is updated.
- Changelog is updated.
- Release artifacts are built by CI.

## 18. Coverage targets

- Core services and financial logic: at least 80% line coverage.
- Overall application: at least 70% line coverage.
- Security and authorization paths: every branch-scoped endpoint covered.
- Critical flows: covered by feature tests or UAT.
- Coverage is a signal, not a substitute for meaningful assertions.

## 19. CI pipeline

Stages:

1. Validate composer lock and install dependencies.
2. Run PHP-CS-Fixer in dry-run mode.
3. Run PHPStan.
4. Run unit tests.
5. Start MariaDB and run migrations.
6. Run integration and feature tests.
7. Run `composer audit`.
8. Build the Docker image.
9. Publish images on release tags.

CI artifacts include test reports and build logs.

## 20. Defect severity

| Severity | Definition                                                            | Release impact                     |
| -------- | --------------------------------------------------------------------- | ---------------------------------- |
| S1       | Data loss, security breach, cross-branch access, financial corruption | Blocks release                     |
| S2       | Major workflow broken, incorrect totals, failed backup/restore        | Blocks release                     |
| S3       | Workaround exists, minor data issue                                   | Release with documented workaround |
| S4       | Cosmetic or low-impact                                                | Can defer                          |

## 21. Bug report requirements

A bug report should include:

- version and environment;
- expected and actual behavior;
- reproduction steps;
- relevant logs or support bundle;
- browser and hardware where relevant;
- mobile device, Android version, and viewport for mobile issues;
- whether data was changed.

Security issues use the private reporting channel, not public issues.

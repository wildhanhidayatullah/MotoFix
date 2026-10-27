# Contributing

> Status: Approved for implementation
> Audience: Contributors and maintainers

## 1. Before you start

Read:

- [01 Project Charter](01-project-charter.md)
- [02 Product Requirements](02-prd.md)
- [03 Architecture](03-architecture.md)
- [05 Security](05-security.md)
- [06 UI and UX](06-ui-ux.md)
- [Design System](../DESIGN.md)
- [11 Testing and QA](11-testing-qa.md)

AI coding agents must also follow [AGENTS.md](../AGENTS.md).

By participating, you agree to follow [CODE_OF_CONDUCT.md](../CODE_OF_CONDUCT.md).

Security vulnerabilities must be reported through the private channel described in
[SECURITY.md](../SECURITY.md), not through a public issue.

## 2. Ways to contribute

- Report reproducible bugs.
- Propose features with a clear problem statement.
- Improve documentation and translations.
- Add or improve tests.
- Implement approved backlog items.
- Review pull requests.
- Test hardware and report compatibility.

Feature work should map to an issue or backlog item. Large changes require a
design discussion and, when structural, an ADR.

## 3. Development setup

The project targets PHP 8.4, MariaDB, and Docker.

Expected local workflow:

1. Clone the repository.
2. Copy `.env.example` to `.env`.
3. Start the development containers.
4. Install PHP dependencies.
5. Run migrations and seeds.
6. Run the test suite.

Frontend assets are vendored locally. There is no Node.js build step for normal
development or installation.

The exact commands will be documented in the README once the foundation is
implemented.

## 4. Branching and commits

- Default branch: `main`.
- Work in a short-lived feature branch.
- Use Conventional Commits, for example:
  - `feat: add branch context middleware`
  - `fix: prevent negative stock on checkout`
  - `docs: clarify backup retention`
  - `test: cover cross-branch transaction access`
  - `refactor: extract invoice number service`
- Keep commits focused and reviewable.
- Do not commit secrets, generated credentials, or local environment files.
- Do not rewrite shared history without maintainer approval.

## 5. Pull requests

A pull request should include:

- the problem and the chosen solution;
- the related issue or backlog ID;
- the user-visible impact;
- screenshots for UI changes;
- migration notes if the schema changes;
- test evidence;
- security considerations for auth, authorization, money, stock, or imports;
- documentation updates.

Review checklist:

- The change is in scope.
- The design follows the architecture and ADRs.
- Controllers do not contain business logic or SQL.
- Repositories are branch-scoped.
- Unsafe requests require CSRF.
- Output is autoescaped.
- Authorization is server-side and tested, including customer/vehicle scope.
- Internal IDs are not exposed; external URLs use `public_id`.
- Money and stock calculations are server-side.
- Mobile mechanic changes are checked on Android Chrome at 360 px.
- Mechanic mobile does not expose financial data.
- Migrations are reversible and tested.
- Tests cover the new behavior and failure modes.
- PHPStan and formatting pass.
- No new secrets or debug output.
- Documentation and translations are updated.

## 6. Coding standards

- PSR-12 formatting.
- Strict types where practical.
- Descriptive names.
- Small functions and classes with one responsibility.
- No comments that merely restate code.
- Comments explain why, not what.
- No dead code or commented-out blocks.
- No direct `$_GET`, `$_POST`, `$_SESSION`, or `$_ENV` access in domain code.
- No SQL outside repositories.
- No business logic in views.
- No hardcoded user-facing text.
- No `var_dump`, `dd`, or debug output in committed code.
- Errors are handled explicitly; no silent catch blocks.

## 7. Architecture rules

- HTTP layer: request parsing, validation, response.
- Service layer: business rules and transactions.
- Repository layer: SQL and row mapping.
- View layer: presentation only.
- Policies and authorization services: access decisions.
- Audit service: append-only records.

New dependencies must be justified. Do not add a library for a trivial task. Do not
upgrade dependencies unrelated to the change.

## 8. Database changes

- Every schema change uses a Phinx migration.
- Migrations are reviewed for locking, data volume, and rollback.
- Foreign keys and indexes are explicit.
- Money remains integer.
- Branch-scoped tables include `branch_id`.
- Customer, vehicle, work order, and transaction tables include an immutable
  unique ULID `public_id`.
- Unique constraints include the correct business scope.
- Seed data is separate from schema migrations.
- Destructive changes require a backup and release note.

## 9. Security requirements

Every change that touches authentication, authorization, money, stock, imports, or
backups must consider:

- cross-branch access;
- customer/vehicle access scope for manager, cashier, and mechanic;
- public ID enumeration and internal ID exposure;
- privilege escalation;
- mobile mechanic access to financial data;
- CSRF;
- XSS;
- SQL injection;
- mass assignment;
- insecure file handling;
- audit logging;
- error leakage;
- rate limiting where relevant.

Security-sensitive changes need a reviewer who is not the author.

## 10. Internationalization

- All user-facing strings use translation keys.
- Add keys to both `en` and `id`.
- Do not translate database keys or code identifiers.
- Use locale-aware formatting.
- Test the change in both languages.
- Keep translations concise and consistent with the glossary.

## 11. Tests

- Add unit tests for business rules.
- Add integration tests for persistence and concurrency.
- Add feature tests for HTTP behavior.
- Add security tests for authorization and tampering.
- Do not modify tests to make a failing implementation pass.
- A bug fix includes a regression test.
- A feature includes happy-path and failure-path tests.

## 12. Documentation

- Update the relevant document when behavior or decisions change.
- Record structural decisions as ADRs.
- Keep the README accurate.
- Update the changelog for user-visible changes.
- Use English for developer documentation.
- Use clear, tested screenshots for operator documentation where needed.

## 13. ADR process

Create an ADR when a decision:

- changes architecture or layering;
- adds or removes a dependency with long-term impact;
- changes the data model in a non-trivial way;
- changes authorization or security posture;
- changes the release or deployment model;
- introduces a scope boundary.

ADR format:

```text
# ADR-NNNN: Title
- Status: Proposed | Accepted | Superseded | Rejected
- Context
- Decision
- Consequences
- Alternatives considered
- References
```

ADRs are never deleted. A superseded decision links to its replacement.

## 14. Review and merge

- At least one maintainer review is required.
- Security-sensitive changes require a focused review.
- CI must pass.
- The branch must be up to date with `main`.
- Squash or rebase according to repository policy.
- The merge commit message should be meaningful.
- Delete the feature branch after merge.

## 15. Releases

Only maintainers publish releases.

Release steps:

1. Confirm the release scope.
2. Update the changelog.
3. Verify migrations and upgrade notes.
4. Run the full regression checklist.
5. Create an annotated `vMAJOR.MINOR.PATCH` tag only for the release commit.
6. Let CI build and publish images.
7. Verify a clean install and an upgrade.
8. Publish the release notes.

No release from an uncommitted working tree. Git tags are reserved for released
versions; do not tag baselines, documentation snapshots, or work in progress.

## 16. Definition of Done

A change is done when:

- acceptance criteria are met;
- tests are added and pass;
- static analysis and formatting pass;
- security implications are reviewed;
- migrations are tested;
- translations are complete;
- documentation is updated;
- the changelog is updated for user-visible changes;
- no secrets or debug artifacts are included;
- the pull request is reviewed and merged.

## 17. Communication

- Be specific and respectful.
- Include reproduction steps and logs where possible.
- Separate observations from assumptions.
- Disagree with ideas, not people.
- Report security issues privately.
- Keep discussions focused on the project.

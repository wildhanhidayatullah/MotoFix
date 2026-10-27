# AGENTS.md

This file contains repository-specific instructions for AI coding agents working
on MotoFix. Follow it in addition to the user's instructions and the repository
documentation.

## 1. Current repository state

MotoFix has an approved documentation baseline and is entering implementation.
The product, architecture, data model, security model, UI system, and delivery
plan are defined in `docs/` and `DESIGN.md`.

The legacy learning implementation is archived on the `legacy-archive` branch and
in Git history. It is not present in the current working tree. Do not re-import or
extend it. Build the target architecture from the approved documentation.

There is no runnable target application or test suite yet. Do not claim that tests,
static analysis, or builds passed unless the commands actually exist and were run.

## 2. Source of truth

Read these before making non-trivial changes:

1. [Documentation index](docs/00-index.md)
2. [Project charter](docs/01-project-charter.md)
3. [Product requirements](docs/02-prd.md)
4. [Architecture](docs/03-architecture.md)
5. [Data model](docs/04-data-model.md)
6. [Security](docs/05-security.md)
7. [UI and UX](docs/06-ui-ux.md)
8. [Design system](DESIGN.md)
9. [Multi-branch model](docs/07-multi-branch.md)
10. [Self-hosted operations](docs/08-self-hosted-operations.md)
11. [Finance](docs/09-finance.md)
12. [Hardware integration](docs/10-hardware-integration.md)
13. [Testing and QA](docs/11-testing-qa.md)
14. [Contributing](docs/12-contributing.md)
15. [Roadmap](docs/13-roadmap.md)
16. [Backlog](docs/14-backlog.md)
17. Architecture decision records under `docs/decisions/`

If documentation conflicts with an ad-hoc request, surface the conflict and ask
for a decision. Do not silently choose one.

## 3. Product scope guardrails

MotoFix is open-source, self-hosted software.

- One installation serves exactly one organization.
- One organization may have many branches.
- Operational data is scoped by `branch_id`.
- Customers and vehicles are shared across branches inside one installation.
- Each garage runs its own server, database, and backups.
- There is no offline mode or branch-to-branch synchronization.
- There is no central management service and no telemetry.

Do not add a second data-isolation layer above branches. Do not add identifiers or
administration concepts that imply multiple organizations inside one installation.
Do not add product lines that are not in the approved PRD.

If a request requires a scope change, stop and ask for an ADR-level decision.

## 4. Stack

- PHP 8.4
- MariaDB 11.4
- Caddy
- Docker Compose
- FastRoute
- Twig with autoescaping
- Phinx
- Symfony Console
- symfony/uid
- Monolog
- PHPUnit
- PHPStan
- PHP-CS-Fixer
- PDO

Do not replace these choices without an approved ADR. Do not add a dependency for
a trivial task. Do not upgrade unrelated dependencies.

## 5. Architecture rules

Use this flow:

```text
Middleware -> Controller -> Service -> Repository -> PDO/MariaDB
                                      -> Twig view
```

- Controllers handle HTTP concerns only.
- Services own business rules, transactions, and audit.
- Repositories own SQL and row mapping.
- Views present data only.
- Policies and authorization services make access decisions.
- Enforce branch context before authorization.
- Resolve all money, tax, discount, stock, and invoice values on the server.
- Record critical changes in the audit log.
- Keep operational records branch-scoped.
- Keep customers and vehicles shared within the installation.
- Use `BIGINT UNSIGNED` internal IDs for operational tables; `roles` and
  `permissions` may use `SMALLINT UNSIGNED` and the singleton `organization` may
  use `TINYINT UNSIGNED` (per ADR-0009). Use ULID `public_id` for customers,
  vehicles, work orders, and transactions.
- Never expose internal integer IDs in URLs or APIs.
- Never let a controller or view query the database directly.
- Never put business logic in Twig templates.

Structural changes require an ADR. This includes layering changes, new domain
boundaries, and data-model changes that affect more than one module.

## 6. Security rules

Security-sensitive changes need focused review.

- Never trust client-supplied prices, totals, tax, discounts, branch IDs, or stock values.
- Enforce authorization server-side on every request.
- Check both permission and branch access for every branch-scoped resource.
- Customers and vehicles are globally readable by owner, admin, manager, and
  cashier. Mechanics can read only records linked to assigned work orders.
- A cashier may create a customer or vehicle. Edit is limited to records created
  in an assigned branch or linked to a work order or transaction in an assigned
  branch.
- ULID `public_id` prevents casual enumeration but is not an authorization
  control.
- Mobile uses the same authentication and authorization as desktop.
- Use CSRF protection for all unsafe methods.
- Use POST or DELETE for state changes; never use GET for mutations.
- Autoescape output. Raw HTML requires an explicit, reviewed decision.
- Use prepared statements. Never build SQL from request input.
- Allow-list sort and filter columns.
- Validate all input server-side.
- Never log passwords, tokens, secrets, or full payment references.
- Never commit `.env`, credentials, keys, dumps, or customer data.
- Use generic production error pages with a correlation ID.
- Add audit records for financial, stock, authorization, and settings changes.
- Add regression tests for security fixes.

If a change introduces a security tradeoff, stop and ask.

## 7. UI and design rules

- `DESIGN.md` is the visual source of truth.
- `docs/06-ui-ux.md` is the UX, flow, and screen-behavior source of truth.
- Use Bootstrap 5, Bootstrap Icons, and Chart.js from vendored assets.
- Inter is vendored locally with its OFL license.
- Do not use a CDN.
- Do not add a Node.js build step to the runtime or installation.
- All user-facing strings use translation keys for `en` and `id`.
- Use locale-aware date, number, and currency formatting.
- Money uses integer rupiah and tabular numerals.
- Status uses text plus color, never color alone.
- Destructive actions require confirmation; void requires a reason.
- Keep the cashier flow keyboard- and scanner-friendly.
- Mechanic mobile is responsive, uses bottom navigation, and targets Android
  Chrome at 360 px and above.
- Mechanic mobile must not show prices, payments, checkout, invoices, or financial
  history.
- Mechanic mobile cannot cancel work orders.
- Camera scanning and photo upload are not part of the pilot mobile flow.
- Test 58 mm and 80 mm receipt layouts when receipt code changes.
- Do not use inline CSS or JavaScript for user data.
- Do not modify vendored asset files directly.

## 8. Testing and quality gates

Current state: the target application and its Composer scripts do not exist yet.
Do not invent commands or claim results.

When the foundation is implemented, this section must be updated with the real
commands. The intended gates are:

- formatting check;
- static analysis;
- unit tests;
- integration tests against MariaDB;
- feature tests;
- dependency audit;
- container build.

Non-negotiable test coverage:

- cross-branch authorization;
- price and total tampering;
- negative stock;
- invoice uniqueness under concurrency;
- CSRF and XSS;
- session fixation;
- inactive user and role-change behavior;
- import validation;
- backup and restore smoke tests;
- customer/vehicle access scope for manager, cashier, and mechanic;
- public ID generation, uniqueness, route binding, and internal ID exposure;
- Android Chrome mobile viewport checks for the mechanic flow.

A bug fix includes a regression test. A feature includes happy-path and
failure-path tests.

## 9. Documentation rules

- Developer documentation and code are in English.
- UI strings are translated for English and Indonesian.
- Planning documents use phases and exit criteria, not calendar dates.
- Do not add dates to roadmap, phase, backlog, or schedule documents.
- Update the relevant document when behavior or decisions change.
- Add or revise an ADR for structural decisions.
- Keep `docs/00-index.md`, `README.md`, and `DESIGN.md` links accurate.
- Update `CHANGELOG.md` for user-visible changes.
- Do not create documentation that contradicts `DESIGN.md` or the PRD.

## 10. Git and workflow rules

- Do not commit, amend, push, or open a pull request unless the user asks.
- Keep commits focused and meaningful.
- Use Conventional Commits, for example:
  - `feat: add branch context middleware`
  - `fix: prevent negative stock on checkout`
  - `docs: clarify backup retention`
  - `test: cover cross-branch transaction access`
- Do not rewrite shared history.
- Do not force-push unless explicitly asked.
- Do not create tags except for released versions. Use branches or commit SHAs for
  baselines and snapshots.
- Do not add generated credentials or local environment files.
- Stage only intended files.
- Inspect `git status` and the diff before committing.

## 11. Stop and ask

Stop and ask before:

- changing the database schema across modules;
- adding a new dependency;
- changing authentication or authorization behavior;
- changing money, tax, stock, or invoice logic;
- deleting or rewriting large parts of the codebase;
- introducing a new external service;
- making a security tradeoff;
- changing the product scope or release boundaries;
- resolving conflicting requirements by assumption.

When asking, state the options, the tradeoffs, and your recommendation.

## 12. Definition of Done

A change is done when:

- acceptance criteria are met;
- tests are added and pass;
- static analysis and formatting pass;
- security implications are reviewed;
- migrations are tested;
- translations are complete;
- documentation and ADRs are updated;
- `CHANGELOG.md` is updated for user-visible changes;
- no secrets, customer data, or debug output are included;
- the change is reviewed.

## 13. Reporting

When finishing work, report:

- what changed;
- which files were touched;
- which commands were run and their results;
- what could not be verified;
- any remaining risk or follow-up.

Do not claim verification that did not happen.

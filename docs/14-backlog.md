# Backlog

> Status: Approved for implementation
> Audience: Product, engineering, QA

## 1. How to use this backlog

- Every item has a stable ID.
- Priority uses MoSCoW.
- Release is `Pilot`, `v1.0.0`, or `Post-v1.0.0`.
- Acceptance criteria are summarized here and expanded in issues.
- Pilot and v1.0.0 requirements trace to [02 PRD](02-prd.md); post-v1.0.0
  candidates are exempt until a PRD update and ADR are approved.
- Calendar dates and time estimates are intentionally excluded.
- The pilot is scope-frozen; new ideas go to v1.0.0 or later unless they block a
  pilot acceptance criterion.

## 2. Epic map

| Epic | Name                                | Release     |
| ---- | ----------------------------------- | ----------- |
| E01  | Foundation and bootstrap            | Pilot       |
| E02  | Authentication and session security | Pilot       |
| E03  | Authorization and branch context    | Pilot       |
| E04  | Organization and branches           | Pilot       |
| E05  | Users and roles                     | Pilot       |
| E06  | Customers and vehicles              | Pilot       |
| E07  | Catalogue                           | Pilot       |
| E08  | Inventory and stock movements       | Pilot       |
| E09  | Work orders and mechanics           | Pilot       |
| E10  | Checkout, payments, and invoices    | Pilot       |
| E11  | Receipts and hardware               | Pilot       |
| E12  | Dashboard and reports               | Pilot       |
| E13  | Import and export                   | Pilot       |
| E14  | Audit and settings                  | Pilot       |
| E15  | Internationalization                | Pilot       |
| E16  | Self-hosted operations and release  | Pilot       |
| E17  | v1.0.0 operational depth            | v1.0.0      |
| E19  | Responsive mobile mechanic          | Pilot       |
| E20  | Identifier strategy                 | Pilot       |
| E18  | Post-v1.0.0 candidates              | Post-v1.0.0 |

## 3. E01 Foundation and bootstrap

| ID     | Story                                                   | Priority | Release | Acceptance summary                                       |
| ------ | ------------------------------------------------------- | -------- | ------- | -------------------------------------------------------- |
| E01-01 | Bootstrap application, configuration, and error handler | Must     | Pilot   | Clean boot, typed config, generic production errors      |
| E01-02 | Implement routing and middleware pipeline               | Must     | Pilot   | Named routes, 404/405, middleware order                  |
| E01-03 | Integrate Twig with autoescaping and helpers            | Must     | Pilot   | Escaped output, layout, translation helpers              |
| E01-04 | Integrate Phinx migrations and seeders                  | Must     | Pilot   | Migrate and seed from empty database                     |
| E01-05 | Integrate Monolog structured logging                    | Must     | Pilot   | Request ID and context, redaction                        |
| E01-06 | Implement secure session foundation                     | Must     | Pilot   | Cookie flags, rotation support, storage outside web root |
| E01-07 | Implement CLI console and installer skeleton            | Must     | Pilot   | Commands run, installer validates environment            |
| E01-08 | Create Docker development and production foundations    | Must     | Pilot   | Caddy, app, database, volumes, health checks             |
| E01-09 | Create CI for formatting, static analysis, and tests    | Must     | Pilot   | Pipeline fails on regressions                            |
| E01-10 | Create test harness with MariaDB                        | Must     | Pilot   | Integration tests run in isolation                       |

## 4. E02 Authentication and session security

| ID     | Story                               | Priority | Release | Acceptance summary                      |
| ------ | ----------------------------------- | -------- | ------- | --------------------------------------- |
| E02-01 | Sign in with email and password     | Must     | Pilot   | Generic failure, inactive user rejected |
| E02-02 | Sign out with POST and CSRF         | Must     | Pilot   | Session destroyed, cookie expired       |
| E02-03 | Rotate session on sign-in           | Must     | Pilot   | New session ID, old data cleared        |
| E02-04 | Enforce password policy and hashing | Must     | Pilot   | Argon2id, minimum length, rehash        |
| E02-05 | Rate limit login attempts           | Must     | Pilot   | Per account and IP                      |
| E02-06 | Change own password                 | Should   | Pilot   | Requires current password, audited      |
| E02-07 | Owner/admin resets another password | Must     | Pilot   | Temporary password or reset flow        |
| E02-08 | Set user language                   | Should   | Pilot   | Persisted and applied                   |
| E02-09 | Email password reset                | Could    | v1.0.0  | Token expiry, single use                |

## 5. E03 Authorization and branch context

| ID     | Story                                            | Priority | Release | Acceptance summary                             |
| ------ | ------------------------------------------------ | -------- | ------- | ---------------------------------------------- |
| E03-01 | Seed roles and permissions                       | Must     | Pilot   | Matches PRD matrix                             |
| E03-02 | Assign roles globally and per branch             | Must     | Pilot   | Global and branch roles enforced               |
| E03-03 | Resolve active branch                            | Must     | Pilot   | Session, single branch, selector, deny         |
| E03-04 | Enforce permissions in middleware                | Must     | Pilot   | Deny by default                                |
| E03-05 | Enforce object-level branch access               | Must     | Pilot   | Cross-branch access denied                     |
| E03-06 | Prevent last owner removal and self-deactivation | Must     | Pilot   | Clear error                                    |
| E03-07 | Recalculate access on every request              | Must     | Pilot   | Role change effective immediately              |
| E03-08 | Cross-branch authorization test suite            | Must     | Pilot   | Every branch-scoped route covered              |
| E03-09 | Enforce customer/vehicle access scope            | Must     | Pilot   | Global read, cashier linked edit, mechanic own |
| E03-10 | Test mechanic customer/vehicle access            | Must     | Pilot   | Unassigned records denied                      |

## 6. E04 Organization and branches

| ID     | Story                                        | Priority | Release | Acceptance summary                      |
| ------ | -------------------------------------------- | -------- | ------- | --------------------------------------- |
| E04-01 | Organization settings page                   | Must     | Pilot   | Identity, tax, locale, invoice, receipt |
| E04-02 | Create and edit branches                     | Must     | Pilot   | Unique code, audit                      |
| E04-03 | Activate and deactivate branches             | Must     | Pilot   | Inactive excluded from new operations   |
| E04-04 | Branch switcher                              | Must     | Pilot   | Only accessible branches listed         |
| E04-05 | Branch receipt identity                      | Must     | Pilot   | Name, address, phone, timezone          |
| E04-06 | Branch code immutability after first invoice | Must     | Pilot   | Change rejected                         |
| E04-07 | Branch price and availability overrides      | Should   | v1.0.0  | Per branch settings                     |

## 7. E05 Users and roles

| ID     | Story                             | Priority | Release | Acceptance summary                 |
| ------ | --------------------------------- | -------- | ------- | ---------------------------------- |
| E05-01 | List and search users             | Must     | Pilot   | Paginated, permission-aware        |
| E05-02 | Create user                       | Must     | Pilot   | Unique email and username, audited |
| E05-03 | Edit user identity and status     | Must     | Pilot   | Cannot deactivate self             |
| E05-04 | Assign roles and branches         | Must     | Pilot   | Matrix enforced                    |
| E05-05 | Deactivate user and revoke access | Must     | Pilot   | Next request denied                |
| E05-06 | Reset user password               | Must     | Pilot   | Audited, no plaintext storage      |
| E05-07 | Invite user by email              | Could    | v1.0.0  | Expiring invitation                |

## 8. E06 Customers and vehicles

| ID     | Story                       | Priority | Release | Acceptance summary                  |
| ------ | --------------------------- | -------- | ------- | ----------------------------------- |
| E06-01 | Customer list and search    | Must     | Pilot   | Name, phone, plate                  |
| E06-02 | Create and edit customer    | Must     | Pilot   | Required fields, audit              |
| E06-03 | Customer detail and history | Must     | Pilot   | Cross-branch work and sales history |
| E06-04 | Archive customer            | Should   | Pilot   | History preserved                   |
| E06-05 | Vehicle list and search     | Must     | Pilot   | Plate, customer                     |
| E06-06 | Create and edit vehicle     | Must     | Pilot   | Unique plate, validation            |
| E06-07 | Vehicle detail and history  | Must     | Pilot   | Service and transaction history     |
| E06-08 | Archive vehicle             | Should   | Pilot   | History preserved                   |
| E06-09 | Duplicate plate handling    | Must     | Pilot   | Inline error                        |
| E06-10 | Cashier linked edit scope   | Must     | Pilot   | Created or used in assigned branch  |
| E06-11 | Vehicle created branch link | Must     | Pilot   | Supports cashier edit scope         |

## 9. E07 Catalogue

| ID     | Story                        | Priority | Release | Acceptance summary                |
| ------ | ---------------------------- | -------- | ------- | --------------------------------- |
| E07-01 | Category management          | Must     | Pilot   | Item and service categories       |
| E07-02 | Item management              | Must     | Pilot   | SKU, barcode, prices, stock alert |
| E07-03 | Service management           | Must     | Pilot   | Price, duration, active status    |
| E07-04 | Unique SKU and barcode       | Must     | Pilot   | Clear conflict error              |
| E07-05 | Catalogue search and filters | Must     | Pilot   | Paginated                         |
| E07-06 | Price change audit           | Must     | Pilot   | Before/after recorded             |
| E07-07 | Archive with history         | Must     | Pilot   | No hard delete when referenced    |
| E07-08 | Branch overrides             | Should   | v1.0.0  | Price, alert, availability        |

## 10. E08 Inventory and stock movements

| ID     | Story                          | Priority | Release | Acceptance summary                  |
| ------ | ------------------------------ | -------- | ------- | ----------------------------------- |
| E08-01 | Stock list per branch          | Must     | Pilot   | Quantity, low-stock indicator       |
| E08-02 | Stock movement ledger          | Must     | Pilot   | Type, delta, reference, actor       |
| E08-03 | Manual adjustment with reason  | Must     | Pilot   | No negative stock, audited          |
| E08-04 | Opening stock import           | Must     | Pilot   | CSV, validation, movement           |
| E08-05 | Low-stock dashboard and report | Must     | Pilot   | Threshold respected                 |
| E08-06 | Stock movement search          | Must     | Pilot   | Date, item, type                    |
| E08-07 | Stock transfer workflow        | Should   | v1.0.0  | Request, approve, dispatch, receive |
| E08-08 | Transfer approval audit        | Should   | v1.0.0  | Full trail                          |
| E08-09 | Stock opname                   | Could    | v1.0.0  | Count sheet and adjustment          |

## 11. E09 Work orders and mechanics

| ID     | Story                            | Priority | Release | Acceptance summary                 |
| ------ | -------------------------------- | -------- | ------- | ---------------------------------- |
| E09-01 | Create work order                | Must     | Pilot   | Branch, customer, vehicle          |
| E09-02 | Add item and service lines       | Must     | Pilot   | Server prices, quantity validation |
| E09-03 | Assign mechanic                  | Must     | Pilot   | Branch membership enforced         |
| E09-04 | Work order status transitions    | Must     | Pilot   | Allowed transitions, audit         |
| E09-05 | Mechanic view                    | Must     | Pilot   | Assigned work orders only          |
| E09-06 | Mechanic status and notes update | Must     | Pilot   | Own assignments only               |
| E09-07 | Complete work order              | Must     | Pilot   | Ready for checkout                 |
| E09-08 | Cancel work order                | Must     | Pilot   | Reason required, audited           |
| E09-09 | Convert to transaction once      | Must     | Pilot   | Duplicate conversion rejected      |
| E09-10 | Work order search and filters    | Must     | Pilot   | Status, date, mechanic             |
| E09-11 | Appointments                     | Could    | v1.0.0  | Scheduling and reminders           |

## 12. E10 Checkout, payments, and invoices

| ID     | Story                                   | Priority | Release | Acceptance summary                      |
| ------ | --------------------------------------- | -------- | ------- | --------------------------------------- |
| E10-01 | Checkout from completed work order      | Must     | Pilot   | One transaction per work order          |
| E10-02 | Server-side price and total calculation | Must     | Pilot   | Client cannot override                  |
| E10-03 | Discount handling                       | Must     | Pilot   | Bounded, audited                        |
| E10-04 | Tax calculation                         | Must     | Pilot   | Configurable, default 0                 |
| E10-05 | Atomic stock decrement                  | Must     | Pilot   | No oversell                             |
| E10-06 | Invoice number allocation               | Must     | Pilot   | Per branch and period, unique           |
| E10-07 | Payment methods                         | Must     | Pilot   | Cash, transfer, QRIS, debit, credit     |
| E10-08 | Cash change calculation                 | Must     | Pilot   | Correct and stored                      |
| E10-09 | Transaction detail                      | Must     | Pilot   | Immutable snapshot                      |
| E10-10 | Transaction search                      | Must     | Pilot   | Invoice, customer, plate, date          |
| E10-11 | Void transaction                        | Must     | Pilot   | Reason, stock restore, audit            |
| E10-12 | Partial payments and deposits           | Should   | v1.0.0  | Multiple payments                       |
| E10-13 | Refunds                                 | Could    | v1.0.0  | Recorded and audited                    |
| E10-14 | Percentage discount                     | Could    | v1.0.0  | Basis points, half-up rounding, audited |

## 13. E11 Receipts and hardware

| ID     | Story                  | Priority | Release | Acceptance summary          |
| ------ | ---------------------- | -------- | ------- | --------------------------- |
| E11-01 | Receipt view           | Must     | Pilot   | Transaction snapshot        |
| E11-02 | 58 mm print layout     | Must     | Pilot   | Fits and readable           |
| E11-03 | 80 mm print layout     | Must     | Pilot   | Fits and readable           |
| E11-04 | Reprint                | Must     | Pilot   | Marker, no data change      |
| E11-05 | HID barcode scanning   | Must     | Pilot   | Item, invoice, plate, phone |
| E11-06 | Scan error handling    | Must     | Pilot   | Clear message, no record    |
| E11-07 | Raw ESC/POS printing   | Could    | v1.0.0  | Documented path             |
| E11-08 | Cash drawer control    | Could    | v1.0.0  | Documented path             |
| E11-09 | Network printer agent  | Could    | v1.0.0  | Configuration and test      |
| E11-10 | Camera scanning        | Could    | v1.0.0  | Browser support             |
| E11-11 | Barcode label printing | Could    | v1.0.0  | Label layout                |

## 14. E12 Dashboard and reports

| ID     | Story                  | Priority | Release | Acceptance summary          |
| ------ | ---------------------- | -------- | ------- | --------------------------- |
| E12-01 | Branch dashboard       | Must     | Pilot   | Sales, count, low stock     |
| E12-02 | Consolidated dashboard | Must     | Pilot   | Owner/admin only            |
| E12-03 | Sales report           | Must     | Pilot   | Date range, branch, payment |
| E12-04 | Inventory report       | Must     | Pilot   | Stock and low stock         |
| E12-05 | Work order report      | Should   | Pilot   | Status and workload         |
| E12-06 | CSV export             | Must     | Pilot   | Formula-safe                |
| E12-07 | XLSX export            | Must     | Pilot   | Numeric types preserved     |
| E12-08 | Profit and cost report | Should   | v1.0.0  | Uses cost snapshots         |
| E12-09 | PDF report             | Could    | v1.0.0  | Printable                   |
| E12-10 | Scheduled report       | Could    | v1.0.0  | No mandatory email          |

## 15. E13 Import and export

| ID     | Story                           | Priority | Release | Acceptance summary    |
| ------ | ------------------------------- | -------- | ------- | --------------------- |
| E13-01 | CSV templates                   | Must     | Pilot   | Downloadable          |
| E13-02 | Customer import                 | Must     | Pilot   | Row validation        |
| E13-03 | Vehicle import                  | Must     | Pilot   | Unique plate rules    |
| E13-04 | Item import                     | Must     | Pilot   | Unique SKU/barcode    |
| E13-05 | Service import                  | Must     | Pilot   | Validation            |
| E13-06 | Opening stock import            | Must     | Pilot   | Branch required       |
| E13-07 | Import error report             | Must     | Pilot   | Row number and reason |
| E13-08 | Import run audit                | Must     | Pilot   | Totals and actor      |
| E13-09 | CSV export for master data      | Must     | Pilot   | Consistent columns    |
| E13-10 | XLSX export for reports         | Must     | Pilot   | Locale-aware          |
| E13-11 | Native XLSX import with mapping | Should   | v1.0.0  | Column mapping UI     |

## 16. E14 Audit and settings

| ID     | Story                    | Priority | Release | Acceptance summary            |
| ------ | ------------------------ | -------- | ------- | ----------------------------- |
| E14-01 | Audit schema and service | Must     | Pilot   | Append-only                   |
| E14-02 | Audit critical actions   | Must     | Pilot   | Actor, branch, before/after   |
| E14-03 | Organization settings    | Must     | Pilot   | Tax, locale, invoice, receipt |
| E14-04 | System page              | Must     | Pilot   | Version, disk, DB, backup     |
| E14-05 | Audit log filter         | Should   | v1.0.0  | Date, user, branch, action    |
| E14-06 | Audit export             | Should   | v1.0.0  | CSV/XLSX                      |
| E14-07 | Support bundle           | Must     | Pilot   | Redacted diagnostics          |

## 17. E15 Internationalization

| ID     | Story                         | Priority | Release | Acceptance summary           |
| ------ | ----------------------------- | -------- | ------- | ---------------------------- |
| E15-01 | Translation infrastructure    | Must     | Pilot   | `en` and `id`                |
| E15-02 | Locale middleware             | Must     | Pilot   | User, organization, fallback |
| E15-03 | Language switcher             | Must     | Pilot   | Persists                     |
| E15-04 | Locale formatting             | Must     | Pilot   | Date, number, currency       |
| E15-05 | Translation completeness test | Must     | Pilot   | No missing keys              |
| E15-06 | English developer docs        | Must     | Pilot   | Repository docs in English   |

## 18. E16 Self-hosted operations and release

| ID     | Story                      | Priority | Release | Acceptance summary          |
| ------ | -------------------------- | -------- | ------- | --------------------------- |
| E16-01 | Installer                  | Must     | Pilot   | Clean server install        |
| E16-02 | Preflight checks           | Must     | Pilot   | Domain, ports, Docker, disk |
| E16-03 | Caddy HTTPS                | Must     | Pilot   | Automatic certificate       |
| E16-04 | Daily backups              | Must     | Pilot   | Database and storage        |
| E16-05 | Backup retention           | Must     | Pilot   | Configurable                |
| E16-06 | Restore command and drill  | Must     | Pilot   | Clean restore               |
| E16-07 | Upgrade script             | Must     | Pilot   | Backup, migrate, health     |
| E16-08 | Rollback guidance          | Must     | Pilot   | Documented                  |
| E16-09 | Doctor command             | Must     | Pilot   | Runtime checks              |
| E16-10 | Health endpoint            | Must     | Pilot   | App and DB                  |
| E16-11 | System page                | Must     | Pilot   | Version, disk, backup       |
| E16-12 | Support bundle             | Must     | Pilot   | Redacted                    |
| E16-13 | Docker image publication   | Must     | Pilot   | Versioned image             |
| E16-14 | GitHub Release             | Must     | Pilot   | Changelog and notes         |
| E16-15 | Offsite backup integration | Could    | v1.0.0  | S3-compatible               |
| E16-16 | Monitoring guide           | Should   | Pilot   | External uptime and disk    |
| E16-17 | Operator user guide        | Must     | Pilot   | Indonesian and English      |

## 19. E17 v1.0.0 operational depth

E17 is a release roll-up for v1.0.0, not a second set of stories. Each row points
to the canonical story in its domain epic. Work is tracked there; new work is
not created with an E17 ID except for E17-14 and E17-15, which have no domain
epic owner and are owned by this epic.

| Capability                             | Canonical story        | Priority | Release |
| -------------------------------------- | ---------------------- | -------- | ------- |
| Stock transfers with approval          | E08-07, E08-08         | Should   | v1.0.0  |
| Branch overrides                       | E04-07, E07-08         | Should   | v1.0.0  |
| Partial payments and deposits          | E10-12                 | Should   | v1.0.0  |
| Refunds                                | E10-13                 | Could    | v1.0.0  |
| Percentage discounts                   | E10-14                 | Could    | v1.0.0  |
| Stock opname                           | E08-09                 | Could    | v1.0.0  |
| Scheduled reports                      | E12-10                 | Could    | v1.0.0  |
| Appointments                           | E09-11                 | Could    | v1.0.0  |
| Raw ESC/POS and network printing       | E11-07, E11-08, E11-09 | Could    | v1.0.0  |
| Camera barcode scanning                | E11-10, E19-09         | Could    | v1.0.0  |
| Native XLSX import                     | E13-11                 | Should   | v1.0.0  |
| Advanced reports and PDF               | E12-08, E12-09         | Should   | v1.0.0  |
| Audit UI and export                    | E14-05, E14-06         | Should   | v1.0.0  |
| Offsite backup integration             | E16-15                 | Could    | v1.0.0  |
| Email notifications and password reset | E02-09, E05-07         | Could    | v1.0.0  |
| Performance and security review        | E17-14                 | Must     | v1.0.0  |
| Upgrade from pilot                     | E17-15                 | Must     | v1.0.0  |

## 20. E19 Responsive mobile mechanic

| ID     | Story                             | Priority | Release | Acceptance summary                   |
| ------ | --------------------------------- | -------- | ------- | ------------------------------------ |
| E19-01 | Responsive mechanic shell         | Must     | Pilot   | Android Chrome, 360 px and above     |
| E19-02 | Mechanic bottom navigation        | Must     | Pilot   | My jobs, Search, Profile             |
| E19-03 | Assigned work order card list     | Must     | Pilot   | Status, customer, vehicle, updated   |
| E19-04 | Mobile work order detail          | Must     | Pilot   | Contact, vehicle, items, notes       |
| E19-05 | Mobile status actions             | Must     | Pilot   | Start, waiting, complete; no cancel  |
| E19-06 | Mobile notes                      | Must     | Pilot   | Add note to assigned job             |
| E19-07 | Hide financial data from mechanic | Must     | Pilot   | No price, payment, checkout, invoice |
| E19-08 | Mobile viewport and device tests  | Must     | Pilot   | 360/390/412 px, Android Chrome       |
| E19-09 | Camera scan and photo upload      | Could    | v1.0.0  | Deferred from pilot                  |

## 21. E20 Identifier strategy

| ID     | Story                                 | Priority | Release | Acceptance summary                             |
| ------ | ------------------------------------- | -------- | ------- | ---------------------------------------------- |
| E20-01 | ULID generator with symfony/uid       | Must     | Pilot   | Format, uniqueness, and monotonicity tests     |
| E20-02 | public_id for four resources          | Must     | Pilot   | customers, vehicles, work orders, transactions |
| E20-03 | Public ID route binding               | Must     | Pilot   | External URLs use public_id                    |
| E20-04 | Internal ID exposure tests            | Must     | Pilot   | No numeric IDs in URLs/APIs                    |
| E20-05 | Public ID uniqueness and immutability | Must     | Pilot   | Unique index and no updates                    |
| E20-06 | Demo seeds with public IDs            | Must     | Pilot   | Reproducible seed data                         |

## 22. E18 Post-v1.0.0 candidates

| ID     | Story                         | Priority | Release     |
| ------ | ----------------------------- | -------- | ----------- |
| E18-01 | Multi-currency                | Could    | Post-v1.0.0 |
| E18-02 | Accounting integration        | Could    | Post-v1.0.0 |
| E18-03 | Customer portal               | Could    | Post-v1.0.0 |
| E18-04 | WhatsApp or SMS notifications | Could    | Post-v1.0.0 |
| E18-05 | Public API                    | Could    | Post-v1.0.0 |
| E18-06 | Native mobile companion       | Could    | Post-v1.0.0 |
| E18-07 | Offline branch operation      | Could    | Post-v1.0.0 |
| E18-08 | Marketplace integrations      | Could    | Post-v1.0.0 |
| E18-09 | Suppliers and purchase orders | Could    | Post-v1.0.0 |

Supplier and purchase order management is a post-v1.0.0 candidate. It is not part
of the approved PRD scope; a PRD update and an ADR are required before it enters
implementation.

## 23. Explicitly deferred from the pilot

- Raw ESC/POS, cash drawer, network printer agent.
- Camera barcode scanning and photo upload on mobile.
- Barcode label printing.
- Native XLSX import.
- Stock transfers.
- Branch overrides.
- Partial payments, deposits, refunds.
- Appointments.
- Advanced reports and PDF.
- Audit UI and export.
- Offsite backup integration.
- Email notifications.
- Real garage UAT.

These are not forgotten; they are scheduled after the pilot by scope decision.

## 24. Backlog hygiene

- Every item has an owner before work starts.
- Every item links to a PRD requirement, a canonical domain story, or an
  approved decision.
- Acceptance criteria are testable.
- Duplicate items are merged.
- Deferred items remain visible.
- Closed items link to the pull request and release.
- New scope during the pilot requires a tradeoff decision.

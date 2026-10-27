# Project Charter

> Status: Approved for implementation
> Audience: Maintainers, contributors, and reviewers

## 1. Project identity

**Name:** MotoFix
**Type:** Open-source, self-hosted garage management system
**License:** MIT
**Primary deployment:** A single central server per organization, accessed by
browser from every branch.

## 2. Problem statement

Independent garages and small multi-branch garage businesses often manage daily
operations with paper notes, spreadsheets, chat messages, and disconnected
point-of-sale tools. This causes:

- lost or incomplete service history;
- inaccurate stock and unclear stock ownership between branches;
- difficulty producing reliable daily and per-branch reports;
- manual invoice and receipt creation;
- weak access control over customer and financial data;
- high dependency on a single person who understands the spreadsheet;
- poor backup and recovery practices.

Most garage operators do not have in-house IT support. They need software that is
simple to install, simple to maintain, and safe by default. They also need control
over where their data lives and who can access it.

## 3. Vision

MotoFix is a dependable, self-hosted operating system for garage businesses. A
garage can install it on its own server, run all branches from one system, and
keep full control of its data.

The system should be:

- **usable**: a cashier can complete a transaction quickly;
- **trustworthy**: money and stock calculations are correct and auditable;
- **simple to operate**: installation, backup, upgrade, and diagnostics require
  minimal technical knowledge;
- **multi-branch**: owners see the whole business, staff see only their branches;
- **open**: the source code, architecture, and decisions are public;
- **maintainable**: a small team or a single maintainer can continue the project.

## 4. Target users

### Primary users

| Persona            | Responsibilities                      | Needs                                                  |
| ------------------ | ------------------------------------- | ------------------------------------------------------ |
| Garage owner       | Owns the business and all branches    | Consolidated reports, user control, data safety        |
| Branch manager     | Runs one or more branches             | Branch operations, stock, work orders, staff oversight |
| Cashier            | Handles customers, payments, receipts | Fast checkout, search, printing, minimal clicks        |
| Mechanic           | Performs service work                 | Clear assignment, status updates, service history      |
| Installer/operator | Installs and maintains the server     | One-command install, backup, upgrade, diagnostics      |

### Secondary users

- Open-source contributors.
- Technical reviewers evaluating the project as a portfolio artifact.
- Support volunteers helping a garage troubleshoot an installation.

## 5. Goals

| ID  | Goal                                                   | Measure                                                                         |
| --- | ------------------------------------------------------ | ------------------------------------------------------------------------------- |
| G1  | Run daily garage operations in one system              | Customer, vehicle, work order, transaction, payment, and receipt flows pass UAT |
| G2  | Support multiple branches with correct data boundaries | Cross-branch authorization suite passes with no failures                        |
| G3  | Protect financial and inventory integrity              | Server-side pricing, stock locking, and invoice uniqueness tests pass           |
| G4  | Make self-hosted operation manageable without IT staff | Clean-server install, backup, restore, and upgrade drills pass                  |
| G5  | Provide a credible open-source portfolio project       | Documentation, tests, CI, releases, and architecture decisions are public       |
| G6  | Keep data under the garage's control                   | No telemetry, no mandatory cloud service, documented backup ownership           |

## 6. Success criteria

### Product success

- A new installation can be completed on a clean server using the documented
  installer and a domain with automatic HTTPS.
- An owner can create branches, users, customers, vehicles, catalogue entries,
  work orders, transactions, payments, and receipts.
- A cashier can print a receipt on a supported thermal printer through the browser.
- A barcode scanner can find an item, invoice, or customer without manual typing.
- A mechanic can update assigned jobs from Android Chrome without seeing financial
  data.
- CSV import/export and XLSX export complete with validation feedback.
- The interface can be switched between English and Indonesian.
- A backup can be restored on a clean installation and the data is usable.

### Engineering success

- PHPStan passes at the agreed level.
- PHP-CS-Fixer reports no violations.
- Unit, integration, feature, and security tests pass in CI.
- No open severity-1 or severity-2 security defects at release.
- Migrations run from an empty database and from the previous supported release.
- External URLs use non-sequential public identifiers; internal IDs are not
  exposed.
- The repository contains a license, security policy, contribution guide,
  changelog, and architecture decision records.

### Portfolio success

- README explains the problem, architecture, tradeoffs, and how to run the system.
- Screenshots and demo seed data demonstrate the main workflows.
- The project shows production practices: tests, CI, migrations, backups,
  observability, and release automation.
- The documentation explains the self-hosted architecture and data ownership model.

## 7. Scope

### In scope

- One organization per installation.
- Multiple branches per organization.
- Role-based access with branch scoping.
- Customer and vehicle records.
- Item and service catalogue.
- Inventory per branch with immutable stock movements.
- Work orders and mechanic assignment.
- Transactions, payments, invoices, and receipts.
- Configurable tax with a default of 0%.
- Payment methods: cash, bank transfer, QRIS, debit, and credit.
- Browser-based thermal receipt printing.
- HID barcode scanner support.
- Responsive mobile web access for mechanics on Android Chrome.
- CSV import/export and XLSX export for the pilot.
- Dashboard and operational reports.
- Audit log for critical actions.
- English and Indonesian user interface.
- Installer, backup, restore, diagnostics, and upgrade tooling.
- Docker-based self-hosted deployment with Caddy and automatic HTTPS.
- Open-source project documentation and release process.

### Out of scope

- Offline operation and multi-server synchronization.
- Native mobile applications.
- Full accounting, general ledger, or tax filing.
- E-commerce or customer self-service portal.
- Native raw ESC/POS printing, cash drawer control, or network printer agents in the pilot.
- Camera-based barcode scanning in the pilot.
- Vehicle photo upload in the pilot.
- Native XLSX import in the pilot.
- Multi-currency.
- Telemetry or analytics collection.

Out-of-scope items may return through the backlog and a new ADR.

## 8. Constraints

- The project is maintained by a solo developer working part-time.
- The codebase is a custom PHP MVC application, not a framework application.
- The runtime is PHP 8.4 with MariaDB and Docker.
- Caddy is the web server and TLS terminator.
- The first release must remain simple enough for a garage operator without IT staff.
- The pilot is validated with demo data because a design-partner garage is not yet available.
- The project is open source under the MIT license.
- There is no central service or telemetry endpoint.

## 9. Principles

1. **Correctness before features.** Money, stock, and authorization must be right.
2. **Server is authoritative.** The client never decides price, tax, total, or stock.
3. **Least privilege.** Users see only the branches and actions they need.
4. **Deny by default.** Authorization failures are explicit and tested.
5. **Auditability.** Critical changes leave an immutable trail.
6. **Simple operations.** One install path, one upgrade path, one backup path.
7. **No hidden dependencies.** The runtime does not require a CDN, external service,
   or telemetry endpoint.
8. **Documentation is part of the product.** A feature is not done until it is
   documented and tested.
9. **No silent data loss.** Deleting or voiding data must be reversible or auditable.
10. **Boring technology.** Prefer proven components over novelty.

## 10. Stakeholders

| Stakeholder              | Interest                                  | Responsibility                            |
| ------------------------ | ----------------------------------------- | ----------------------------------------- |
| Project owner/maintainer | Product direction, architecture, delivery | Final decisions, implementation, releases |
| Garage owner             | Business visibility and data ownership    | Requirements, pilot feedback, acceptance  |
| Branch manager           | Daily branch operations                   | UAT feedback, workflow validation         |
| Cashier and mechanic     | Usability and speed                       | UAT feedback                              |
| Operator/installer       | Install, backup, upgrade                  | Follow runbooks, report issues            |
| Contributors             | Code quality and clear process            | Follow contributing guide                 |
| Security reviewers       | Safe defaults and disclosure process      | Review and report vulnerabilities         |

## 11. Release approach

The project uses two product milestones:

- **Pilot**: the first usable release for demo and pilot validation. It includes
  the core operating loop and deliberately simplified hardware and import features.
- **v1.0.0**: the production-intended release. It adds the deferred hardware,
  import, transfer, reporting, and operational hardening work.

Progress is measured by exit criteria in the roadmap, not by calendar dates.
Scope changes during the pilot require an explicit tradeoff and are recorded in
the backlog.

## 12. High-level risks

| Risk                          | Impact                                 | Mitigation                                                        |
| ----------------------------- | -------------------------------------- | ----------------------------------------------------------------- |
| Scope growth during the pilot | Core features are unfinished           | Scope freeze; new ideas go to the v1.0.0 backlog                  |
| No real garage for pilot UAT  | "Usable" is not validated              | Demo scenarios and structured review; real feedback in v1.0.0     |
| Hardware diversity            | Printing or scanning fails at a garage | Support browser printing and HID scanners; document tested models |
| Operator lacks IT skills      | Failed install or lost data            | One-command scripts, automatic HTTPS, backup drill, screenshots   |
| Domain/DNS setup              | HTTPS cannot be issued                 | Step-by-step guide and a preflight check                          |
| Local-only backups            | Data loss after server failure         | Offsite backup guidance and restore drill                         |
| Solo maintenance              | Slow response and burnout              | Documentation, ADRs, tests, and small releases                    |
| Security regression           | Data leak or financial loss            | Security tests, least privilege, audit log, review checklist      |

## 13. Approval

This charter is part of the approved implementation baseline. Changes to goals,
scope, or constraints require an explicit decision and an update to the relevant
documents or ADRs.

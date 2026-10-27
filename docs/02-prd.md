# Product Requirements Document

> Status: Approved for implementation
> Audience: Product, engineering, QA, and reviewers

## 1. Purpose

This document defines the functional and non-functional requirements for MotoFix.
It is the source of truth for scope. Requirements are grouped by module and
identified so that tests, backlog items, and architecture decisions can reference
them.

## 2. Product summary

MotoFix is a self-hosted garage management system for one organization with one or
more branches. It covers customer and vehicle records, work orders, inventory,
transactions, payments, receipts, reporting, and administration. It is installed
by the garage on its own server and accessed through a browser.

## 3. Roles

| Role           | Scope             | Summary                                                                            |
| -------------- | ----------------- | ---------------------------------------------------------------------------------- |
| Owner          | All branches      | Owns the installation, organization settings, users, and consolidated reporting    |
| Admin          | All branches      | Runs daily operations and user administration without owning the business identity |
| Branch manager | Assigned branches | Runs operations for assigned branches                                              |
| Cashier        | Assigned branches | Handles customers, work orders, transactions, payments, and receipts               |
| Mechanic       | Assigned branches | Works on assigned work orders and updates progress                                 |

A user may have different roles in different branches. Owner and admin are global
roles. Branch roles require at least one branch assignment.

## 4. Permission model

Permissions are checked in addition to branch context. A user with the correct
permission for the wrong branch is denied.

### Scope terms

| Term       | Meaning                                                                                                    |
| ---------- | ---------------------------------------------------------------------------------------------------------- |
| `Global`   | Applies to all branches and to global records in the installation                                          |
| `Assigned` | Applies only to branches assigned to the user                                                              |
| `Own`      | Applies only to records assigned to the user; for customer/vehicle, records linked to assigned work orders |
| `—`        | Not permitted                                                                                              |

### Permission matrix

| Permission              | Owner  | Admin  | Branch manager | Cashier  | Mechanic |
| ----------------------- | ------ | ------ | -------------- | -------- | -------- |
| `organization.view`     | Global | Global | Global         | Global   | Global   |
| `organization.manage`   | Global | —      | —              | —        | —        |
| `branch.view`           | Global | Global | Assigned       | Assigned | Assigned |
| `branch.manage`         | Global | Global | —              | —        | —        |
| `user.view`             | Global | Global | Assigned       | —        | —        |
| `user.manage`           | Global | Global | —              | —        | —        |
| `customer.view`         | Global | Global | Global         | Global   | Own      |
| `customer.manage`       | Global | Global | Global         | Assigned | —        |
| `vehicle.view`          | Global | Global | Global         | Global   | Own      |
| `vehicle.manage`        | Global | Global | Global         | Assigned | —        |
| `catalog.view`          | Global | Global | Global         | Global   | Global   |
| `catalog.manage`        | Global | Global | —              | —        | —        |
| `inventory.view`        | Global | Global | Assigned       | Assigned | Assigned |
| `inventory.adjust`      | Global | Global | Assigned       | —        | —        |
| `inventory.transfer`    | Global | Global | Assigned       | —        | —        |
| `work_order.view`       | Global | Global | Assigned       | Assigned | Own      |
| `work_order.manage`     | Global | Global | Assigned       | Assigned | —        |
| `work_order.assign`     | Global | Global | Assigned       | Assigned | —        |
| `work_order.update_own` | Global | Global | Assigned       | Assigned | Own      |
| `transaction.view`      | Global | Global | Assigned       | Assigned | —        |
| `transaction.create`    | Global | Global | Assigned       | Assigned | —        |
| `transaction.void`      | Global | Global | Assigned       | —        | —        |
| `payment.create`        | Global | Global | Assigned       | Assigned | —        |
| `report.view`           | Global | Global | Assigned       | Assigned | —        |
| `report.export`         | Global | Global | Assigned       | —        | —        |
| `import.manage`         | Global | Global | —              | —        | —        |
| `audit.view`            | Global | Global | Assigned       | —        | —        |
| `organization.settings` | Global | —      | —              | —        | —        |
| `system.view`           | Global | Global | —              | —        | —        |
| `system.manage`         | Global | —      | —              | —        | —        |

- `organization.settings` governs organization-wide settings such as identity,
  tax rate, invoice prefix, and the receipt footer. It is owner-only.
- Branch and branch-specific settings are edited under `branch.manage`.

### Customer and vehicle access

- Customers and vehicles are global records within the installation.
- Owner, admin, manager, and cashier may read any customer or vehicle.
- Mechanic may read only customers and vehicles linked to a work order assigned
  to that mechanic.
- Owner, admin, and manager may edit any customer or vehicle.
- Cashier may create a customer or vehicle. Cashier may edit a customer or
  vehicle only when it was created in an assigned branch or has at least one work
  order or transaction in an assigned branch.
- Mechanic cannot create or edit customers or vehicles.
- Every create, update, and archive action is audited.

## 5. Functional requirements

Priority uses MoSCoW. Release indicates the first milestone that must include the
requirement.

### 5.1 Authentication and session

| ID      | Requirement                                                    | Priority | Release |
| ------- | -------------------------------------------------------------- | -------- | ------- |
| AUTH-01 | A user can sign in with email and password                     | Must     | Pilot   |
| AUTH-02 | A user can sign out using a POST request with CSRF protection  | Must     | Pilot   |
| AUTH-03 | Sessions are regenerated after sign-in and cleared on sign-out | Must     | Pilot   |
| AUTH-04 | Inactive users cannot sign in                                  | Must     | Pilot   |
| AUTH-05 | Failed sign-in attempts are rate limited per account and IP    | Must     | Pilot   |
| AUTH-06 | Passwords are hashed with a memory-hard algorithm              | Must     | Pilot   |
| AUTH-07 | A user can change their own password                           | Should   | Pilot   |
| AUTH-08 | An owner or admin can reset another user's password            | Must     | Pilot   |
| AUTH-09 | A user can set their preferred language                        | Should   | Pilot   |
| AUTH-10 | Optional password reset by email                               | Could    | v1.0.0  |

### 5.2 Organization and branches

| ID     | Requirement                                                                                                                                | Priority | Release |
| ------ | ------------------------------------------------------------------------------------------------------------------------------------------ | -------- | ------- |
| ORG-01 | The installer creates the organization identity                                                                                            | Must     | Pilot   |
| ORG-02 | An owner can edit organization name, address, phone, email, tax ID, timezone, default locale, invoice prefix, tax rate, and receipt footer | Must     | Pilot   |
| ORG-03 | An owner can upload an organization logo used on receipts                                                                                  | Could    | v1.0.0  |
| BR-01  | An owner or admin can create, edit, activate, and deactivate branches                                                                      | Must     | Pilot   |
| BR-02  | Every branch has a unique short code used in invoices                                                                                      | Must     | Pilot   |
| BR-03  | Inactive branches cannot be selected for new operations                                                                                    | Must     | Pilot   |
| BR-04  | Branch-specific receipt header data comes from the branch and organization                                                                 | Must     | Pilot   |
| BR-05  | Users can switch between assigned branches                                                                                                 | Must     | Pilot   |
| BR-06  | A user with no branch assignment cannot perform branch operations                                                                          | Must     | Pilot   |

### 5.3 Users and roles

| ID      | Requirement                                          | Priority | Release |
| ------- | ---------------------------------------------------- | -------- | ------- |
| USER-01 | An owner or admin can create users                   | Must     | Pilot   |
| USER-02 | An owner or admin can assign roles and branches      | Must     | Pilot   |
| USER-03 | An owner or admin can activate and deactivate users  | Must     | Pilot   |
| USER-04 | A user cannot deactivate or delete their own account | Must     | Pilot   |
| USER-05 | Role changes take effect on the next request         | Must     | Pilot   |
| USER-06 | The system prevents removal of the last active owner | Must     | Pilot   |
| USER-07 | User invitations by email                            | Could    | v1.0.0  |

### 5.4 Customers and vehicles

| ID      | Requirement                                                                                                                                               | Priority | Release |
| ------- | --------------------------------------------------------------------------------------------------------------------------------------------------------- | -------- | ------- |
| CUST-01 | A cashier, manager, admin, or owner can create customers; edit scope follows the permission matrix                                                        | Must     | Pilot   |
| CUST-02 | Customers can be searched by name, phone, or vehicle plate within the user's access scope                                                                 | Must     | Pilot   |
| CUST-03 | Customer records include name, phone, optional email, address, and notes                                                                                  | Must     | Pilot   |
| CUST-04 | Customers are shared across all branches and readable by owner, admin, manager, and cashier                                                               | Must     | Pilot   |
| CUST-05 | Customer history shows transactions and work orders across branches to owner, admin, manager, and cashier; mechanic sees only assigned work order history | Must     | Pilot   |
| CUST-06 | A mechanic can read only customers linked to assigned work orders                                                                                         | Must     | Pilot   |
| VEH-01  | A customer can have multiple vehicles                                                                                                                     | Must     | Pilot   |
| VEH-02  | A vehicle records plate number, brand, model, year, color, VIN, engine number, and odometer                                                               | Must     | Pilot   |
| VEH-03  | Plate numbers are unique within the organization                                                                                                          | Must     | Pilot   |
| VEH-04  | A vehicle can be archived without deleting history                                                                                                        | Should   | Pilot   |
| VEH-05  | A vehicle profile shows service and transaction history to authorized users; mechanic sees only assigned work order history                               | Must     | Pilot   |
| VEH-06  | A mechanic can read only vehicles linked to assigned work orders                                                                                          | Must     | Pilot   |
| VEH-07  | A cashier, manager, admin, or owner can create vehicles; edit scope follows the permission matrix                                                         | Must     | Pilot   |
| VEH-08  | A mechanic cannot create or edit vehicles                                                                                                                 | Must     | Pilot   |

### 5.5 Catalogue

| ID     | Requirement                                                                                                 | Priority | Release |
| ------ | ----------------------------------------------------------------------------------------------------------- | -------- | ------- |
| CAT-01 | Items and services are grouped by category                                                                  | Must     | Pilot   |
| CAT-02 | Items have SKU, optional barcode, name, unit, buy price, sell price, minimum stock alert, and active status | Must     | Pilot   |
| CAT-03 | Services have name, optional code, description, duration, price, and active status                          | Must     | Pilot   |
| CAT-04 | SKU and barcode are unique within the organization                                                          | Must     | Pilot   |
| CAT-05 | Items and services are available to all branches by default                                                 | Must     | Pilot   |
| CAT-06 | A branch can override item price, minimum stock alert, and availability                                     | Should   | v1.0.0  |
| CAT-07 | A branch can override service price and availability                                                        | Should   | v1.0.0  |
| CAT-08 | Price changes are audited                                                                                   | Must     | Pilot   |

### 5.6 Inventory

| ID     | Requirement                                                                                         | Priority | Release |
| ------ | --------------------------------------------------------------------------------------------------- | -------- | ------- |
| INV-01 | Stock is tracked per branch and item                                                                | Must     | Pilot   |
| INV-02 | Stock changes are recorded as immutable movements                                                   | Must     | Pilot   |
| INV-03 | Stock cannot become negative                                                                        | Must     | Pilot   |
| INV-04 | A manager, admin, or owner can record an adjustment with a reason                                   | Must     | Pilot   |
| INV-05 | Low-stock items are shown on the dashboard and inventory page                                       | Must     | Pilot   |
| INV-06 | Opening stock can be imported from CSV                                                              | Must     | Pilot   |
| INV-07 | Stock movements show type, quantity, reference, user, and time                                      | Must     | Pilot   |
| INV-08 | Stock can be transferred between branches with a tracked status                                     | Should   | v1.0.0  |
| INV-09 | Transfer approval and receipt are audited                                                           | Should   | v1.0.0  |
| INV-10 | Stock opname: a manager, admin, or owner can record a physical count and create adjustments from it | Could    | v1.0.0  |

### 5.7 Work orders

| ID    | Requirement                                                                                                                                                              | Priority | Release |
| ----- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | -------- | ------- |
| WO-01 | A cashier, manager, admin, or owner can create a work order for a customer vehicle                                                                                       | Must     | Pilot   |
| WO-02 | A work order records complaint, diagnosis, odometer, notes, and assigned mechanic                                                                                        | Must     | Pilot   |
| WO-03 | A work order can contain item and service lines                                                                                                                          | Must     | Pilot   |
| WO-04 | A work order status can move through draft, in progress, waiting for parts, completed, and cancelled                                                                     | Must     | Pilot   |
| WO-05 | Only authorized users can change status                                                                                                                                  | Must     | Pilot   |
| WO-06 | A mechanic can update assigned work orders to in progress, waiting for parts, and completed, and can add notes; cancellation is restricted to manager, admin, or cashier | Must     | Pilot   |
| WO-07 | A completed work order can be converted into a transaction                                                                                                               | Must     | Pilot   |
| WO-08 | A work order cannot be converted twice                                                                                                                                   | Must     | Pilot   |
| WO-09 | Work order history is visible to authorized branch users                                                                                                                 | Must     | Pilot   |
| WO-10 | Appointments and scheduling                                                                                                                                              | Could    | v1.0.0  |

### 5.8 Transactions, payments, and invoices

| ID     | Requirement                                                          | Priority | Release |
| ------ | -------------------------------------------------------------------- | -------- | ------- |
| TXN-01 | A transaction is created from a completed work order                 | Must     | Pilot   |
| TXN-02 | The server resolves item and service prices from the catalogue       | Must     | Pilot   |
| TXN-03 | The server computes subtotal, discount, tax, and grand total         | Must     | Pilot   |
| TXN-04 | The client cannot set or override prices and totals                  | Must     | Pilot   |
| TXN-05 | Stock is decremented atomically during checkout                      | Must     | Pilot   |
| TXN-06 | Checkout fails if stock is insufficient                              | Must     | Pilot   |
| TXN-07 | Invoice numbers are unique per branch and period                     | Must     | Pilot   |
| TXN-08 | Invoice numbers are generated atomically and are never reused        | Must     | Pilot   |
| TXN-09 | A paid transaction can be voided by an authorized user with a reason | Must     | Pilot   |
| TXN-10 | Voiding restores stock and is audited                                | Must     | Pilot   |
| TXN-11 | Transactions can be searched by invoice, customer, plate, or date    | Must     | Pilot   |
| TXN-12 | Partial payments and deposits                                        | Should   | v1.0.0  |
| TXN-13 | Refunds after payment                                                | Could    | v1.0.0  |
| TXN-14 | Transactions support a percentage discount with explicit rounding    | Could    | v1.0.0  |

### 5.9 Payments

| ID     | Requirement                                                        | Priority | Release |
| ------ | ------------------------------------------------------------------ | -------- | ------- |
| PAY-01 | Supported methods are cash, bank transfer, QRIS, debit, and credit | Must     | Pilot   |
| PAY-02 | The pilot supports full payment at checkout                        | Must     | Pilot   |
| PAY-03 | Cash payments calculate change from tendered amount                | Must     | Pilot   |
| PAY-04 | Payment method, amount, reference, cashier, and time are recorded  | Must     | Pilot   |
| PAY-05 | Payment records are immutable after posting; corrections use void  | Must     | Pilot   |

### 5.10 Receipts and hardware

| ID      | Requirement                                                                                                              | Priority | Release |
| ------- | ------------------------------------------------------------------------------------------------------------------------ | -------- | ------- |
| RCPT-01 | A receipt can be printed from the browser in 58 mm or 80 mm layout                                                       | Must     | Pilot   |
| RCPT-02 | The receipt shows organization and branch identity, invoice, customer, vehicle, items, payments, tax, total, and cashier | Must     | Pilot   |
| RCPT-03 | A receipt can be reprinted                                                                                               | Must     | Pilot   |
| RCPT-04 | A barcode scanner can search item SKU/barcode, invoice, or customer phone/plate                                          | Must     | Pilot   |
| RCPT-05 | Raw ESC/POS printing, cash drawer control, and network printer agent                                                     | Could    | v1.0.0  |
| RCPT-06 | Camera-based barcode scanning                                                                                            | Could    | v1.0.0  |
| RCPT-07 | Barcode label printing                                                                                                   | Could    | v1.0.0  |

### 5.11 Reporting and dashboard

| ID     | Requirement                                                                           | Priority | Release |
| ------ | ------------------------------------------------------------------------------------- | -------- | ------- |
| RPT-01 | Dashboard shows branch and consolidated daily sales, transaction count, and low stock | Must     | Pilot   |
| RPT-02 | Sales can be filtered by date range, branch, and payment method                       | Must     | Pilot   |
| RPT-03 | Inventory report shows stock per branch and low-stock items                           | Must     | Pilot   |
| RPT-04 | Work order report shows status and mechanic workload                                  | Should   | Pilot   |
| RPT-05 | Reports can be exported to CSV and XLSX                                               | Must     | Pilot   |
| RPT-06 | Profit and cost reporting                                                             | Should   | v1.0.0  |
| RPT-07 | Printable PDF reports                                                                 | Could    | v1.0.0  |
| RPT-08 | Scheduled daily, weekly, or monthly reports                                           | Could    | v1.0.0  |

### 5.12 Import and export

| ID     | Requirement                                                                                  | Priority | Release |
| ------ | -------------------------------------------------------------------------------------------- | -------- | ------- |
| IMP-01 | Downloadable CSV templates exist for customers, vehicles, items, services, and opening stock | Must     | Pilot   |
| IMP-02 | CSV import validates every row and reports row-level errors                                  | Must     | Pilot   |
| IMP-03 | Import runs are recorded with totals and errors                                              | Must     | Pilot   |
| IMP-04 | Master data and reports can be exported as CSV                                               | Must     | Pilot   |
| IMP-05 | Master data and reports can be exported as XLSX                                              | Must     | Pilot   |
| IMP-06 | Native XLSX import with column mapping                                                       | Should   | v1.0.0  |

### 5.13 Audit and settings

| ID     | Requirement                                                                                                                                | Priority | Release |
| ------ | ------------------------------------------------------------------------------------------------------------------------------------------ | -------- | ------- |
| AUD-01 | Sign-in, sign-out, user, role, master data, price, stock, work order, transaction, payment, void, import, and settings changes are audited | Must     | Pilot   |
| AUD-02 | Audit entries include actor, branch, action, entity, before/after values, IP, and user agent                                               | Must     | Pilot   |
| AUD-03 | Audit entries cannot be edited or deleted from the application                                                                             | Must     | Pilot   |
| AUD-04 | Audit log filtering and export                                                                                                             | Should   | v1.0.0  |
| SET-01 | Organization settings are editable by the owner                                                                                            | Must     | Pilot   |
| SET-02 | Tax rate is configurable and defaults to 0%                                                                                                | Must     | Pilot   |
| SET-03 | Default language and per-user language are configurable                                                                                    | Must     | Pilot   |
| SET-04 | A system page shows version, disk, database health, last backup, and support bundle                                                        | Must     | Pilot   |

### 5.14 System and operations

| ID     | Requirement                                                                                                                                         | Priority | Release |
| ------ | --------------------------------------------------------------------------------------------------------------------------------------------------- | -------- | ------- |
| SYS-01 | A CLI installer creates environment, database, migrations, owner, and first branch                                                                  | Must     | Pilot   |
| SYS-02 | The installer checks PHP extensions, permissions, database connectivity, and timezone                                                               | Must     | Pilot   |
| SYS-03 | The application runs behind Caddy with automatic HTTPS                                                                                              | Must     | Pilot   |
| SYS-04 | Daily backups can be created and downloaded                                                                                                         | Must     | Pilot   |
| SYS-05 | A backup can be restored on a clean installation using a documented command                                                                         | Must     | Pilot   |
| SYS-06 | A doctor command checks runtime health                                                                                                              | Must     | Pilot   |
| SYS-07 | A health endpoint is available for monitoring                                                                                                       | Must     | Pilot   |
| SYS-08 | An upgrade command or script backs up, migrates, and verifies health                                                                                | Must     | Pilot   |
| SYS-09 | Release images are published for self-hosted installation                                                                                           | Must     | Pilot   |
| SYS-10 | Offsite backup integration                                                                                                                          | Could    | v1.0.0  |
| SYS-11 | Customers, vehicles, work orders, and transactions expose an immutable ULID public identifier; internal integer IDs are not exposed in URLs or APIs | Must     | Pilot   |

### 5.15 Internationalization

| ID      | Requirement                                                      | Priority | Release |
| ------- | ---------------------------------------------------------------- | -------- | ------- |
| I18N-01 | The UI supports English and Indonesian                           | Must     | Pilot   |
| I18N-02 | The user can switch language without losing context              | Must     | Pilot   |
| I18N-03 | Dates, numbers, and currency are formatted per locale            | Must     | Pilot   |
| I18N-04 | Status values are stored as stable keys and translated in the UI | Must     | Pilot   |
| I18N-05 | All user-facing strings are translatable; no hardcoded UI text   | Must     | Pilot   |

### 5.16 Mobile mechanic access

| ID     | Requirement                                                                                           | Priority | Release |
| ------ | ----------------------------------------------------------------------------------------------------- | -------- | ------- |
| MOB-01 | The mechanic workflow is usable on a mobile web browser                                               | Must     | Pilot   |
| MOB-02 | The mechanic mobile shell uses bottom navigation for My jobs, Search, and Profile                     | Must     | Pilot   |
| MOB-03 | A mechanic can list and open assigned work orders                                                     | Must     | Pilot   |
| MOB-04 | A mechanic can move an assigned work order to in progress, waiting for parts, and completed           | Must     | Pilot   |
| MOB-05 | A mechanic can add notes to an assigned work order                                                    | Must     | Pilot   |
| MOB-06 | A mechanic can view customer and vehicle contact details and work order items, without financial data | Must     | Pilot   |
| MOB-07 | The pilot mobile target is Android Chrome                                                             | Must     | Pilot   |
| MOB-08 | Camera scanning and photo upload are not included in the pilot mobile flow                            | Could    | v1.0.0  |
| MOB-09 | Mobile uses the same authentication, session, and branch authorization as desktop                     | Must     | Pilot   |
| MOB-10 | Mobile requires network access to the central server; offline mode is not supported                   | Must     | Pilot   |

## 6. Key user stories

### US-01 Sign in and select a branch

**As** a branch user, **I want** to sign in and work in my assigned branch **so
that** I cannot accidentally change another branch.

Acceptance criteria:

- Invalid credentials return a generic error.
- A user with one branch is taken directly to that branch.
- A user with multiple branches sees a branch selector.
- A user without branch access is denied branch operations.
- The session is regenerated after sign-in.
- The selected branch is visible in the header.

### US-02 Create a customer and vehicle

**As** a cashier, **I want** to register a customer and vehicle **so that** I can
create a work order.

Acceptance criteria:

- Name and phone are required.
- Plate number is required and unique.
- Duplicate plate numbers are rejected with a clear message.
- The new customer and vehicle are immediately searchable.
- The action is audited.

### US-03 Create and assign a work order

**As** a cashier, **I want** to create a work order and assign a mechanic **so
that** the work is tracked.

Acceptance criteria:

- The work order belongs to the active branch.
- Only mechanics assigned to that branch can be selected.
- Items and services are resolved from the catalogue.
- The status starts as draft.
- The mechanic can see the work order after assignment.
- The action is audited.

### US-04 Checkout and payment

**As** a cashier, **I want** to convert a completed work order into a paid
transaction **so that** the customer receives an invoice.

Acceptance criteria:

- The server recalculates all prices and totals.
- Changing a price in the request is rejected or ignored.
- Stock is checked and decremented atomically.
- A negative stock result aborts the transaction.
- The invoice number is unique.
- Payment method and amount are recorded.
- Cash change is calculated correctly.
- The transaction and payment are audited.

### US-05 Print a receipt

**As** a cashier, **I want** to print a receipt **so that** the customer has proof
of service.

Acceptance criteria:

- The receipt fits 58 mm and 80 mm paper.
- The receipt shows the correct branch and organization identity.
- The receipt can be reprinted without changing the transaction.
- The browser print dialog is the only printing path in the pilot.

### US-06 Void a transaction

**As** a branch manager, **I want** to void a paid transaction **so that** a
mistaken sale can be corrected.

Acceptance criteria:

- Only authorized users can void.
- A reason is required.
- Stock is restored through stock movements.
- The transaction remains visible with void status.
- Payments remain recorded for audit.
- The action is audited.

### US-07 Adjust stock

**As** a branch manager, **I want** to adjust stock with a reason **so that** the
physical count matches the system.

Acceptance criteria:

- Adjustments create stock movements.
- Negative resulting stock is rejected.
- A reason is required.
- The action is audited.

### US-08 Import master data

**As** an owner, **I want** to import customers, vehicles, items, services, and
opening stock from CSV **so that** onboarding is fast.

Acceptance criteria:

- Templates are downloadable.
- Every row is validated.
- Invalid rows are reported with row numbers and reasons.
- Valid rows can be imported without partial silent failure.
- The import run is recorded.
- Duplicate business keys are handled according to the template rules.

### US-09 Scan a barcode

**As** a cashier, **I want** to scan a barcode **so that** I can find an item or
invoice quickly.

Acceptance criteria:

- The scanner works as a keyboard input device.
- Scanning an item barcode adds or selects the item.
- Scanning an invoice opens the transaction.
- Scanning a customer phone or plate opens the customer.
- Unknown codes show a clear message.

### US-10 Back up and restore

**As** an operator, **I want** to back up and restore the system **so that** data
can survive a server failure.

Acceptance criteria:

- A backup includes the database and stored files.
- A backup can be downloaded.
- Restore works on a clean installation.
- Restore requires explicit confirmation.
- The restore drill is documented and tested.

### US-11 Switch language

**As** a user, **I want** to switch between English and Indonesian **so that** I
can work in my preferred language.

Acceptance criteria:

- The language switch persists for the user.
- All visible UI strings change.
- Dates and numbers follow the locale.
- The current page remains usable after switching.

### US-12 Deny cross-branch access

**As** a branch user, **I want** the system to deny access to other branches **so
that** branch data stays private.

Acceptance criteria:

- Direct URL access to another branch's transaction returns 403 or 404.
- Requests to internal endpoints with another branch ID are denied.
- Lists only show assigned branches.
- The denial is covered by automated tests.

### US-13 Update a job from a phone

**As** a mechanic, **I want** to view and update my assigned work orders from my
phone **so that** I do not have to return to a desk during a job.

Acceptance criteria:

- The mechanic can sign in on Android Chrome.
- Only assigned work orders are visible.
- The mobile shell exposes My jobs, Search, and Profile.
- The mechanic can set in progress, waiting for parts, and completed.
- The mechanic cannot cancel a work order.
- The mechanic can add notes.
- Customer and vehicle contact details and work order items are visible.
- Financial data and checkout actions are not visible.
- Core actions work at a 360 px viewport without horizontal scrolling.
- Unauthorized work orders return 403 or 404.

## 7. Non-functional requirements

### 7.1 Security

- All state-changing requests use POST or DELETE with CSRF protection; GET must
  never change state.
- All output is autoescaped; raw HTML is exceptional and reviewed.
- All queries use prepared statements or safe query builders.
- Authorization is deny-by-default and enforced server-side.
- Passwords use a memory-hard hash and a minimum policy.
- Sessions use `HttpOnly`, `SameSite=Lax`, and `Secure` in production.
- Login attempts are rate limited.
- Sensitive actions are audited.
- Security headers are set by the application or Caddy.
- No secrets are committed to the repository.
- Dependency vulnerabilities are checked in CI.

### 7.2 Performance

- Common pages should render in under 500 ms at p95 on the reference server with
  a normal data set.
- Checkout should complete in under 1 second excluding printer time.
- Lists must be paginated and indexed.
- CSV import of 5,000 rows should complete within a reasonable interactive
  window and provide progress or a clear completion result.
- The system should remain responsive with 20 concurrent users on the reference server.

### 7.3 Reliability and data integrity

- Database writes that span multiple tables use transactions.
- Stock and invoice sequence updates are concurrency-safe.
- The system never silently discards a failed write.
- A failed request returns a clear error and a correlation ID.
- Backups have a recovery point objective of at most 24 hours and a recovery time
  objective of at most 2 hours for a small installation.
- The application tolerates database restarts without corrupting data.

### 7.4 Usability

- The cashier flow is keyboard-friendly.
- The barcode field is reachable without a mouse.
- Forms preserve input after validation errors.
- Error messages are actionable and translated.
- Destructive actions require confirmation.
- The UI is responsive on desktop and tablet screens used at a cashier desk.
- The mechanic flow is usable on Android Chrome at 360 px width and above.
- Mobile touch targets are at least 44 px.
- The mobile flow uses the same server-rendered application, authentication, and
  branch authorization; the pilot has no public API and no separate mobile app.
  Internal JSON endpoints exist only where operations require them, such as
  health checks.
- Mobile does not require camera permissions in the pilot.
- The UI does not depend on a CDN.

### 7.5 Accessibility

- Form fields have labels.
- Focus states are visible.
- Color is not the only indicator of status.
- Tables have headers and captions where appropriate.
- The main flows are usable with a keyboard.
- Target WCAG 2.1 AA for core flows.

### 7.6 Internationalization

- Locale files exist for `en` and `id`.
- Every user-facing string uses translation keys.
- Dates, numbers, and currency are locale-aware.
- Database enum values are stable English keys.
- The fallback locale is English.
- Right-to-left languages are out of scope.

### 7.7 Maintainability

- PSR-12 formatting.
- PHPStan at the agreed level with no unexplained ignores.
- Unit, integration, and feature tests for critical behavior.
- Architecture decisions recorded in ADRs.
- No business logic in controllers or views.
- No direct SQL in controllers.
- Migrations are reversible and reviewed.

### 7.8 Portability and operations

- Docker is the only runtime dependency on the host.
- The application runs on Linux with PHP 8.4 and MariaDB.
- The installer works on a clean supported server.
- Caddy provides automatic HTTPS.
- Health, diagnostics, backup, and upgrade paths are documented.
- The application does not require telemetry or an external service account.

### 7.9 Observability

- Structured logs include request ID, user ID, branch ID, and route.
- Errors are logged with context and a correlation ID.
- A health endpoint reports application and database status.
- A support bundle collects redacted logs and environment information.
- Audit logs are separate from application logs.

### 7.10 Privacy

- No telemetry is sent anywhere.
- Customer data stays in the installation's database.
- Backups are controlled by the operator.
- Logs avoid sensitive values such as passwords and tokens.
- Data export is available through backups and reports.

## 8. Data requirements

- Money is stored as integer rupiah, never floating point.
- Timestamps are stored in UTC and displayed in the organization or branch timezone.
- Invoice numbers are unique and never reused.
- Stock movements are immutable.
- Transaction items snapshot price, cost, and description.
- Audit records are immutable.
- Customers and vehicles are shared across branches within the organization.
- Operational records belong to exactly one branch.
- Customers, vehicles, work orders, and transactions have an immutable ULID
  `public_id` for external URLs and APIs.
- Internal integer identifiers are never exposed in URLs or APIs.
- Invoice numbers remain human-readable and separate from `public_id`.

## 9. Assumptions and dependencies

- Each installation serves one organization.
- Branches have network access to the central server.
- The operator can provide a domain name and DNS access.
- The server runs a supported Linux distribution with Docker.
- Cashier computers run a current browser and have the printer driver installed.
- Barcode scanners operate in HID keyboard mode.
- The organization uses IDR as its currency in the pilot.
- Tax is configurable and defaults to 0%.
- Email is optional; the owner can reset passwords.
- A real design-partner garage is not required for the pilot; demo data is used.
- The pilot mobile target is Android Chrome; iOS support is evaluated later.

## 10. Release criteria

### Pilot release criteria

- All `Must` requirements marked `Pilot` are implemented.
- Clean-server installation succeeds.
- Core UAT scenarios pass with demo data.
- Cross-branch authorization, CSRF, XSS, price tampering, negative stock, and
  invoice collision tests pass.
- Backup and restore drill passes.
- The mechanic mobile flow passes UAT on Android Chrome at 360 px and above.
- Cross-branch customer/vehicle access and cashier edit scope tests pass.
- ULID `public_id` generation, uniqueness, and route binding tests pass.
- CI is green.
- User-facing documentation and screenshots exist.
- No open severity-1 or severity-2 defects.

### v1.0.0 release criteria

- All `Must` and `Should` requirements marked `v1.0.0` are implemented or explicitly
  deferred with an approved decision.
- Real garage feedback has been reviewed.
- Raw ESC/POS, cash drawer, and network printing are supported or explicitly deferred.
- XLSX import, stock transfers, branch overrides, and advanced reporting are
  available or explicitly deferred.
- Performance and security reviews are complete.
- Upgrade from the pilot release is tested.
- Offsite backup guidance or integration is available.

## 11. Out of scope

- Offline mode and multi-server synchronization.
- Native mobile application.
- Full accounting and tax filing.
- Customer self-service portal.
- Native printer agent in the pilot.
- Camera barcode scanning in the pilot.
- Mechanic photo upload in the pilot.
- Native XLSX import in the pilot.
- Multi-currency.
- Telemetry.

## 12. Traceability

Requirements in this document are mapped to epics and stories in
[14 Backlog](14-backlog.md). Tests in [11 Testing and QA](11-testing-qa.md) must
reference requirement IDs where practical.

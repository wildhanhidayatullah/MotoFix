# Multi-Branch Model

> Status: Approved for implementation
> Audience: Product, engineering, QA

## 1. Model

Each MotoFix installation serves exactly one organization. The organization may
operate one or more branches. Each installation is independent.

```text
Organization (one per installation)
├── Branch A
├── Branch B
└── Branch C
```

All branches access the same application and database through a browser. There is
no branch-to-branch synchronization and no offline mode.

## 2. Operating boundaries

Each garage installs and operates its own MotoFix instance. Installations do not
share an application, database, or backup set. Inside one installation, branch
isolation is enforced by the application and covered by tests.

See [ADR-0002](decisions/ADR-0002-single-organization-multi-branch.md).

## 3. Branch-scoped data

| Data                  | Scope              | Notes                                           |
| --------------------- | ------------------ | ----------------------------------------------- |
| Organization settings | Global             | One per installation                            |
| Branches              | Global list        | Managed by owner/admin                          |
| Users                 | Global identities  | Access assigned per branch                      |
| Roles                 | Global definitions | Owner/admin are global roles                    |
| Customers             | Global             | Shared; read roles follow the permission matrix |
| Vehicles              | Global             | Shared; owned by a customer                     |
| Categories            | Global             |                                                 |
| Items                 | Global             | Branch overrides in v1.0.0                      |
| Services              | Global             | Branch overrides in v1.0.0                      |
| Inventory stock       | Branch             | One row per branch and item                     |
| Stock movements       | Branch             | Immutable                                       |
| Work orders           | Branch             |                                                 |
| Transactions          | Branch             |                                                 |
| Payments              | Branch             |                                                 |
| Invoice sequences     | Branch             | Per period                                      |
| Audit logs            | Global or branch   | Branch when applicable                          |
| Import runs           | Global or branch   | Branch for opening stock                        |

Rules:

- A branch-scoped record must never be read or written outside an authorized
  branch context.
- A global record may be read only by a user whose permission grants global or
  record-specific access. Editing is restricted by the same matrix.
- A global customer may have history in multiple branches.
- Owner, admin, manager, and cashier may read any customer or vehicle in the
  installation.
- A mechanic may read only customers and vehicles linked to assigned work orders.
- Owner, admin, and manager may edit any customer or vehicle.
- A cashier may create a customer or vehicle. Edit is limited to records created
  in an assigned branch or linked to a work order or transaction in an assigned
  branch.
- A mechanic cannot create or edit customers or vehicles.
- A work order and its transaction must belong to the same branch.
- A payment must belong to the same branch as its transaction.
- External URLs for customers, vehicles, work orders, and transactions use ULID
  `public_id`; internal integer IDs remain private.
- Public IDs do not replace branch or permission checks.

## 4. Branch context

The active branch is part of the request context.

Resolution order:

1. Explicit branch selection by the user.
2. Last selected branch stored in the session, if still accessible.
3. The user's only assigned branch.
4. For owner/admin, the first active branch or a consolidated view where allowed.
5. Otherwise, deny access.

A `BranchContext` object exposes:

- active branch, if any;
- accessible branches;
- whether the user is in consolidated mode;
- helper methods to assert branch access.

The branch selector only shows branches the user can access. Inactive branches are
excluded from new operations but remain visible in historical reports where
permitted.

## 5. Roles and branch access

Global roles:

- `owner`: all branches;
- `admin`: all branches for operations, without organization ownership controls.

Branch roles:

- `branch_manager`: assigned branches;
- `cashier`: assigned branches;
- `mechanic`: assigned branches.

A user may hold different roles in different branches. For example, a user can be
a cashier in Branch A and a branch manager in Branch B.

Constraints:

- A branch role requires at least one branch assignment.
- A global role does not require a branch assignment.
- Removing the last owner is forbidden.
- Removing a user's last branch role prevents branch operations until reassigned.
- Role and branch changes are audited.
- Authorization is recalculated on each request; it is not cached in the session.

See [ADR-0003](decisions/ADR-0003-branch-authorization.md).

## 6. Branch lifecycle

### Creation

- Owner or admin creates a branch.
- Code, name, address, and phone are required.
- Code is unique and used in invoice numbers.
- The first branch is created by the installer.

### Update

- Name, address, phone, timezone, and status are editable.
- Code is immutable after the first invoice is issued.
- Changes are audited.

### Deactivation

- Inactive branches cannot be selected for new work orders or transactions.
- Existing records remain accessible to authorized users.
- Users assigned only to an inactive branch cannot perform branch operations.
- Reactivation is allowed.

### No deletion

Branches are never hard-deleted because they own financial and stock history.

## 7. Branch-specific settings

Pilot:

- Branch code and identity for receipts.
- Branch timezone, inheriting the organization when empty.
- Stock per branch.
- Invoice sequence per branch.

v1.0.0:

- Item sell price override.
- Item minimum stock alert override.
- Item availability override.
- Service price override.
- Service availability override.

Post-v1.0.0 candidates:

- Branch-level tax override if required by local rules.
- Branch-level receipt header and footer overrides.

Organization settings remain the default. Branch-specific settings are edited by
the owner or admin under `branch.manage`.

## 8. Consolidated reporting

Owner and admin can choose:

- a single branch;
- a consolidated view across all branches.

Consolidated reports must:

- label each branch;
- avoid double counting;
- respect date and timezone boundaries;
- show per-branch subtotals;
- be exportable.

Branch managers and cashiers only see assigned branches.

Reports include:

- daily sales;
- sales by payment method;
- transaction count and average ticket;
- low stock;
- work order status;
- mechanic workload;
- tax and discount totals.

## 9. Stock ownership

- Stock belongs to a branch.
- Selling an item consumes stock from the transaction branch.
- A work order can only use stock from its branch.
- A branch cannot sell stock owned by another branch.
- v1.0.0 introduces transfers between branches with explicit status and audit.
- Stock movement records identify the branch.
- Consolidated stock reports aggregate per branch and show the total.

### Transfer workflow (v1.0.0)

```text
draft -> requested -> approved -> in_transit -> received
                         \-> rejected
```

Rules:

- Source and destination branches must be active.
- The requesting user must have access to the source branch.
- The approving user must have permission for both branches or be an owner/admin.
- Dispatch creates `transfer_out`; receipt creates `transfer_in`.
- Quantities cannot exceed available source stock.
- Cancellation after dispatch requires a reverse movement and audit.
- All steps are audited.

## 10. Cross-branch authorization

Every branch-scoped endpoint must enforce:

1. authenticated user;
2. required permission;
3. active branch context;
4. branch membership;
5. object membership in the active branch.

Tests must cover:

- direct URL access to another branch's transaction;
- direct URL access to another branch's work order;
- list endpoints that accept a branch filter;
- API endpoints that accept a branch ID;
- reports with a manipulated branch parameter;
- stock adjustment in another branch;
- payment posting to another branch's transaction;
- branch selector hiding inaccessible branches.

## 11. Branch data integrity

- Work order and transaction share the same branch.
- Transaction customer and vehicle are global but the transaction itself is
  branch-owned.
- Payment and transaction share the same branch.
- Stock movement and inventory stock share the same branch.
- Invoice sequence and transaction share the same branch.
- Foreign keys and service-level checks enforce these rules.
- Database triggers are not used as the primary enforcement mechanism.

## 12. User experience

- The active branch is always visible.
- Switching branches reloads the current context or returns to the dashboard.
- A user with one branch sees no unnecessary selector.
- A user with multiple branches can switch quickly.
- Consolidated mode is visually distinct and unavailable to branch roles.
- Creating a record uses the active branch by default.
- Forms that reference a branch show it as read-only context.

## 13. Operating model

- All branches share one server and database.
- Branches require network access to the server.
- There is no local branch server and no sync.
- If the server or network is unavailable, operations stop.
- This limitation is documented for operators and garage owners.
- Backups cover the whole organization, not individual branches.
- Restore restores the whole organization.

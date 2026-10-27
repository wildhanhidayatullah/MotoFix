# Changelog

All notable changes to this project are documented in this file.

The format is based on Keep a Changelog and the project uses Semantic Versioning.

## [Unreleased]

### Added

- Initial product documentation set.
- Project charter, product requirements, architecture, and data model.
- Security, UI/UX, multi-branch, operations, finance, and hardware documentation.
- Testing and QA strategy.
- Contribution guide, security policy, code of conduct, and backlog.
- Architecture decision records for core decisions.
- README, license, and GitHub issue and pull request templates.
- UI design system reference (`DESIGN.md`).
- Repository instructions for AI coding agents (`AGENTS.md`).
- Responsive mobile web specification for mechanics.
- Identifier strategy ADR (`ADR-0009`).
- PRD requirements TXN-14 percentage discount, INV-10 stock opname,
  RPT-08 scheduled reports, and ORG-03 organization logo.

### Changed

- Marked the documentation baseline as approved for implementation.
- Reserved Git tags for versioned releases; baseline references use branches and
  commit SHAs.
- Documentation aligned around the self-hosted, single-organization multi-branch product direction.
- Permission matrix: cashiers can assign mechanics (`work_order.assign`);
  `settings.manage` was replaced by owner-only `organization.settings`, with
  branch settings governed by `branch.manage`.
- State-changing requests are restricted to POST and DELETE with CSRF protection.
- ADR-0009 clarified: route binding validates `public_id`, and branch and
  permission checks apply before record data is returned; `SMALLINT UNSIGNED`
  and `TINYINT UNSIGNED` are allowed for bounded lookup tables and the
  singleton organization.
- Supplier and purchase order management moved to post-v1.0.0 candidates and
  removed from v1.0.0 scope.
- Item-level discounts, discount reasons, branch tax override, and branch
  receipt header/footer overrides moved to post-v1.0.0 candidates.
- Organization image logo moved to the ORG-03 v1.0.0 requirement; pilot receipts
  use a text wordmark only.
- Branch address and phone are required in the data model, matching the branch
  lifecycle rules.
- `user_roles` uniqueness now specifies a generated-column unique index so
  duplicate global roles are impossible.
- Backlog E17 became a release roll-up of canonical domain stories instead of a
  duplicate story list.
- Branch-level settings editing is scoped to `branch.manage` (owner and admin).
- Web server direction changed from nginx to Caddy for automatic HTTPS.
- Hardware direction set to browser printing and HID scanning for the pilot.
- ADR-0002 and ADR-0005 revised to reflect the current scope.
- Permission matrix now uses explicit `Global`, `Assigned`, `Own`, and `—` scope terms.
- Customer and vehicle access rules clarified for manager, cashier, and mechanic.
- Customers, vehicles, work orders, and transactions now use ULID `public_id` for external identifiers.
- Mechanic mobile web is included in the pilot with Android Chrome as the target.

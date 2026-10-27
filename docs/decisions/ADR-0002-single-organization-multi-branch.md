# ADR-0002: One Organization per Installation, Many Branches

- Status: Accepted
- Context: MotoFix is open-source, self-hosted software for independent garages.
  Each garage installs and operates its own instance. A garage may operate
  multiple physical branches that need shared customers, shared catalogue
  defaults, and consolidated reporting. Installing a separate application for
  every branch would fragment customer history, stock visibility, and owner
  reporting.
- Decision: Each installation serves exactly one organization. The organization
  may have many branches. Operational data is scoped by `branch_id`. Customers
  and vehicles are shared across branches within the installation. All branches
  use one central server and one database.
- Consequences:
  - Branch isolation remains mandatory and must be tested.
  - Owners can produce consolidated reports across branches.
  - Distribution, backups, and upgrades happen per installation.
  - A garage with one branch uses the same model without extra configuration.
  - Data is not shared between independent installations.
- Alternatives considered:
  - One installation per branch: simpler isolation, but no shared customers,
    consolidated reporting, or central administration.
  - One installation per garage with branch support disabled: workable for a
    single location, but cannot model multi-branch operations.
  - Separate applications per branch connected by integrations: complex,
    duplicates data, and creates reconciliation work.
- References:
  - [07 Multi-Branch](../07-multi-branch.md)
  - [04 Data Model](../04-data-model.md)

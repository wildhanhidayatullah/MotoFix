# ADR-0003: Role-Based Access with Branch Context

- Status: Accepted
- Context: The system serves one organization with many branches. Owners and
  admins need organization-wide access, while managers, cashiers, and mechanics
  must be restricted to assigned branches. The existing application checked roles
  during route registration and in views, which is fragile and not sufficient for
  a production system.
- Decision: Enforce authorization server-side using permissions, roles, branch
  context, and object-level checks. Resolve the active branch before
  authorization. Deny by default. Recalculate access on every request. Keep view
  checks only as UI affordances. Cover branch isolation with automated tests.
- Consequences:
  - Every branch-scoped endpoint must declare and test its permission.
  - Controllers and services must not trust branch IDs from the request.
  - Role and branch changes take effect immediately.
  - The last owner cannot be removed and users cannot deactivate themselves.
  - Cross-branch access failures are explicit and audited where appropriate.
- Alternatives considered:
  - Route-only role checks: simple but easy to bypass and hard to audit.
  - Role checks in views: not security.
  - A third-party RBAC package: most packages target a full framework and do not
    model branch scope cleanly.
  - Attribute-based access control: more flexible, but unnecessary complexity for
    the pilot.
- References:
  - [05 Security](../05-security.md)
  - [07 Multi-Branch](../07-multi-branch.md)
  - [02 PRD](../02-prd.md)

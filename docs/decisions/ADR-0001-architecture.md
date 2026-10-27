# ADR-0001: Custom MVC with Focused Components

- Status: Accepted
- Context: The repository holds legacy scaffolding from an earlier custom PHP MVC
  learning and portfolio iteration. It does not represent the target
  architecture and is replaced during implementation, so this decision applies
  to the rebuilt application. The product must become a self-hosted,
  production-intended garage system while remaining understandable to a solo
  maintainer. Rebuilding on a full framework would add a large learning and
  migration effort.
- Decision: Continue with a custom MVC architecture and adopt focused,
  framework-agnostic components for routing, templating, migrations, logging,
  console commands, testing, static analysis, and formatting. Keep PDO and add an
  explicit service and repository layer.
- Consequences:
  - The project retains control over the request lifecycle and domain model.
  - Security, validation, authorization, and conventions must be implemented and
    documented by the project rather than inherited from a framework.
  - The component set reduces reinvention for common infrastructure.
  - Contributors must follow strict layering and security rules.
  - If the project grows into a larger product, the decision may be revisited.
- Alternatives considered:
  - Laravel: faster feature development and stronger ecosystem, but a large
    rewrite and a different learning goal.
  - Symfony: strong architecture and components, but heavier than the project
    needs.
  - Slim plus Doctrine: a middle ground, but still requires assembling many
    concerns.
  - Pure custom code without components: maximum learning, but higher risk and
    slower delivery.
- References:
  - [03 Architecture](../03-architecture.md)
  - [02 Product Requirements](../02-prd.md)

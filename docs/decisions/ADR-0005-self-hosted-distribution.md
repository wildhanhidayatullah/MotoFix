# ADR-0005: Self-Hosted Distribution and Release Model

- Status: Accepted
- Context: MotoFix targets many independent garages that will install the software
  on their own server. The product is open source under the MIT license. Garages
  often lack IT staff, so installation and upgrades must be predictable and
  supportable. The project does not provide a central management service or a
  telemetry endpoint.
- Decision:
  - Distribute the application as versioned container images.
  - Provide a CLI-first installer and Docker Compose deployment.
  - Use Caddy for automatic HTTPS.
  - Publish releases through the repository with changelogs and upgrade notes.
  - Use Semantic Versioning.
  - Build and publish images from CI, not from operator workstations.
  - Do not include telemetry or a mandatory cloud dependency.
  - Provide backup, restore, doctor, and support bundle commands.
  - Do not auto-update.
- Consequences:
  - Each installation is independent and must be upgraded individually.
  - Support relies on documentation and reproducible diagnostics.
  - Release engineering and upgrade testing are part of the product, not an
    afterthought.
  - Operators retain data ownership and responsibility for backups.
  - Security releases require a clear upgrade path.
- Alternatives considered:
  - Auto-update: convenient, but unsafe without a staging and rollback process.
  - Manual source deployment: flexible, but too fragile for non-technical
    operators.
  - Package-manager-only distribution: useful later, but insufficient for a
    complete application with a database and web server.
- References:
  - [08 Self-Hosted Operations](../08-self-hosted-operations.md)
  - [ADR-0007](ADR-0007-caddy.md)

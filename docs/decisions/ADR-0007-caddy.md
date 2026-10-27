# ADR-0007: Caddy as the Web Server and TLS Terminator

- Status: Accepted
- Context: The legacy scaffolding used nginx and expected an operator to
  configure TLS. The target operators are garages without IT staff. Certificate
  management is a common failure point for self-hosted applications.
- Decision: Use Caddy as the web server and TLS terminator in development and
  production. Caddy serves static files, proxies PHP requests to PHP-FPM, applies
  security headers, and obtains and renews certificates automatically.
- Consequences:
  - The stack becomes Linux + Caddy + PHP-FPM + MariaDB.
  - Operators only need a domain pointing to the server and open ports 80/443.
  - Caddy data must be persisted in a volume.
  - Caddy configuration becomes part of the release and must be tested.
  - Existing nginx configuration is replaced.
- Alternatives considered:
  - nginx with certbot: proven and familiar, but adds certificate operations.
  - Traefik: powerful, but more configuration surface than needed for a single
    application.
  - Caddy with a local agent or custom certificate workflow: unnecessary for the
    pilot.
- References:
  - [03 Architecture](../03-architecture.md)
  - [08 Self-Hosted Operations](../08-self-hosted-operations.md)
  - [ADR-0005](ADR-0005-self-hosted-distribution.md)

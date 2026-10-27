# ADR-0009: BIGINT Internal IDs with ULID Public IDs

- Status: Accepted
- Context: MotoFix uses a relational database with many internal joins. Primary
  keys are currently planned as `BIGINT UNSIGNED` auto-increment. External URLs
  and APIs should not expose sequential integers because they reveal record volume
  and make casual enumeration easier. At the same time, replacing every primary
  key with a random identifier would increase index size, reduce insert locality,
  and complicate debugging.
- Decision:
  - Keep `BIGINT UNSIGNED` as the default internal primary key and foreign key
    type for operational tables. Small bounded lookup tables such as `roles` and
    `permissions` may use `SMALLINT UNSIGNED`, and the singleton `organization`
    row may use `TINYINT UNSIGNED`; see [04 Data Model](../04-data-model.md).
  - Add an immutable ULID `public_id` to `customers`, `vehicles`, `work_orders`,
    and `transactions`.
  - Generate ULIDs with `symfony/uid`.
  - Use `public_id` in external URLs and APIs. Never expose internal integer IDs
    in URLs or APIs.
  - Keep invoice numbers as human-readable business identifiers, separate from
    `public_id`.
  - Enforce uniqueness with a database index.
  - Treat `public_id` as an identifier, not an authorization control. Every
    lookup still requires permission and branch checks.
- Consequences:
  - Internal joins remain fast and compact.
  - External URLs are non-sequential.
  - ULIDs are time-ordered and contain a timestamp; they are not secrets.
  - Public IDs must be generated consistently in web requests, CLI commands,
    imports, and seeds.
  - Route binding parses and validates the `public_id` format. Branch and
    permission checks are applied before any record data is returned; a
    `public_id` lookup never bypasses authorization.
  - Internal IDs may still appear in logs and database tooling; that is acceptable
    because logs are access-controlled.
- Alternatives considered:
  - Keep only `BIGINT`: simplest and fastest, but exposes sequential identifiers
    and volume.
  - Replace all primary keys with UUIDv4: opaque, but causes index fragmentation
    and complicates every foreign key.
  - Replace all primary keys with ULID or UUIDv7: time-ordered, but larger indexes
    and more migration complexity than the hybrid approach.
  - Use invoice numbers as the only external transaction identifier: acceptable
    for staff-facing documents, but insufficient for APIs and other resources.
- References:
  - [04 Data Model](../04-data-model.md)
  - [05 Security](../05-security.md)
  - [03 Architecture](../03-architecture.md)

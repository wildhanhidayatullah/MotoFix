# ADR-0004: Integer Money, Configurable Tax, and Invoice Numbering

- Status: Accepted
- Context: The legacy scaffolding trusts client-supplied prices and totals, uses
  a timestamp-based invoice number, and has inconsistent stock validation; the
  rebuilt application must not inherit those behaviors. The product must produce
  correct financial records for real garage operations.
  The default tax rate is 0%, but tax must be configurable.
- Decision:
  - Store money as integer rupiah in `BIGINT` columns.
  - Never use floating point for money.
  - Store tax rates in basis points.
  - Default tax to 0% and allow the owner to configure it.
  - Use exclusive tax in the pilot with half-up rounding.
  - Resolve all prices and totals server-side.
  - Snapshot prices, costs, tax rate, and totals on the transaction.
  - Allocate invoice numbers per branch and period with a row lock.
  - Never reuse invoice numbers, including after a void.
- Consequences:
  - Client price tampering is rejected or overridden.
  - Historical transactions remain stable when catalogue prices or tax change.
  - Multi-currency and inclusive tax require a future change.
  - Invoice generation adds a database lock inside checkout.
  - Reporting must use stored snapshots and explicit void handling.
- Alternatives considered:
  - Floating point money: rejected due to rounding and integrity risk.
  - Client-side totals: rejected due to tampering risk.
  - Global invoice sequence: simpler, but less useful per branch and harder to
    reconcile.
  - Timestamp invoice numbers: rejected due to collision and readability.
- References:
  - [09 Finance](../09-finance.md)
  - [04 Data Model](../04-data-model.md)

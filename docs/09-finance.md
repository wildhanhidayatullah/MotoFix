# Finance

> Status: Approved for implementation
> Audience: Product, engineering, QA

## 1. Scope

This document defines how MotoFix handles money, tax, discounts, payments,
invoices, receipts, voids, and basic financial reporting.

It does not define a general ledger, double-entry accounting, tax filing, payroll,
or accounts payable. MotoFix is an operational garage system, not a full
accounting package.

## 2. Currency

- The pilot supports Indonesian Rupiah only.
- Currency code is stored as `IDR`.
- Amounts are stored as integer rupiah (`BIGINT`).
- Floating point is never used for money.
- Multi-currency is out of scope.

Rupiah is treated as having no minor unit. If a future requirement introduces
fractional amounts, the data model must change through a new ADR.

## 3. Tax

### Configuration

- Default tax rate is `0%`.
- The tax rate is configurable by the owner.
- The rate is stored in basis points: `0` to `10000`.
- Example: `1100` means 11.00%.
- The transaction snapshots the tax rate at the time of sale.

### Calculation mode

The pilot uses **exclusive tax**:

```text
taxable_base = subtotal - discount_total
tax_total    = round_half_up(taxable_base * tax_rate_bp / 10000)
grand_total  = taxable_base + tax_total
```

Inclusive tax may be added in v1.0.0 if required. It must be explicitly configured
and documented, because changing the mode changes the meaning of displayed prices.

### Rounding

- All calculations use integer arithmetic.
- Rounding is half up to the nearest rupiah.
- Each transaction stores the final computed values.
- Recalculation must produce the same values from the stored snapshot.
- Rounding differences must never be hidden by adjusting line totals silently.

### Tax display

The receipt and transaction detail show:

- subtotal;
- discount;
- taxable base;
- tax rate and tax amount;
- grand total.

If tax is 0%, the tax line may be hidden or shown as zero according to the
organization setting.

## 4. Discounts

Pilot:

- One transaction-level fixed discount.
- Discount cannot be negative.
- Discount cannot exceed subtotal.
- Discount is applied before tax.
- Discount is stored as `discount_total`.
- The cashier must have permission to apply a discount.
- Discounts are audited.

v1.0.0:

- Percentage discounts with explicit rounding.

Post-v1.0.0 candidates (not part of the approved v1.0.0 scope):

- Item-level discounts.
- Discount reasons.

Percentage discount rule:

- A percentage discount is chosen in basis points at checkout, like the tax
  rate.
- `discount_total = round(subtotal * discount_bp / 10000)` with half-up
  rounding to whole rupiah.
- Only the computed `discount_total` is persisted and snapshot; the
  basis-point input is validated server-side and audited.
- The discount is applied before tax, like a fixed discount.

## 5. Prices

### Catalogue price

- Item price comes from `items.sell_price`.
- Service price comes from `services.price`.
- Branch overrides may replace prices in v1.0.0.
- The server resolves prices; the client cannot submit a price.
- The transaction item snapshots `unit_price` and `description`.
- Item cost snapshots `buy_price` at sale time into `unit_cost`.
- Service cost is null unless a cost model is added.

### Price changes

- Price changes are audited.
- Historical transactions keep their snapshots.
- Changing a price does not change past transactions.

## 6. Work order to transaction

A work order contains planned lines. Checkout converts the completed work order
into a transaction.

Rules:

- Only completed work orders can be checked out.
- A work order can produce at most one transaction.
- The transaction copies line descriptions, quantities, and server-resolved prices.
- Stock items are validated and decremented in the same database transaction.
- Services do not affect stock.
- The cashier can review the lines before posting.
- The cashier cannot edit posted transaction lines.

## 7. Payments

### Methods

- `cash`
- `bank_transfer`
- `qris`
- `debit`
- `credit`

The method list is fixed in the pilot. Per-branch enablement is a v1.0.0 candidate.

### Pilot behavior

- Full payment at checkout.
- A transaction is `paid` when the applied payment total equals the grand total.
- A transaction is `unpaid` until payment is posted.
- A transaction with no payment remains `unpaid` and is visible to authorized
  users.
- Partial payment is not exposed in the pilot UI.
- The data model supports multiple payments to avoid a breaking change later.

### Cash

```text
amount_applied = grand_total
tendered_amount >= grand_total
change_amount = tendered_amount - amount_applied
```

- Cash tendered must be at least the grand total.
- Change is calculated and displayed.
- Tendered and change are stored for audit.

### Non-cash

- Amount applied should equal the grand total in the pilot.
- A reference is optional but recommended for transfer and QRIS.
- Debit and credit are recorded as payment methods; card settlement is outside the
  system.

### Payment immutability

- Payments cannot be edited after posting.
- A correction requires voiding the transaction and creating a new one.
- Refunds are a v1.0.0 feature.
- Payments are audited.

## 8. Invoice numbering

Format:

```text
INV/{branch_code}/{YYYYMM}/{sequence}
```

Example:

```text
INV/JKT/202601/000123
```

Rules:

- Prefix comes from organization settings.
- Branch code comes from the branch.
- Period is `YYYYMM` in the organization or branch timezone as configured.
- Sequence is per branch and period.
- Sequence starts at 1.
- Allocation uses a row lock inside the transaction.
- The invoice number is unique in the database.
- A failed checkout does not consume a number.
- A voided invoice number is never reused.
- The sequence is not reset when a branch is renamed, but branch code is immutable
  after the first invoice.
- Manual invoice numbers are not allowed in the pilot.
- Transactions also have an immutable ULID `public_id` for external URLs and
  APIs. The invoice number remains the human-readable financial identifier.
- `public_id` is not a replacement for the invoice number and is not used as an
  authorization control.

## 9. Receipt

The receipt is a presentation of the transaction. It is not a separate financial
record.

Required content:

- organization name (text wordmark in the pilot; an image logo is a v1.0.0 feature, if configured);
- branch name, address, and phone;
- invoice number;
- transaction date and time;
- cashier;
- customer name;
- vehicle plate, brand, and model;
- line items with quantity, unit price, and line total;
- subtotal;
- discount;
- tax rate and tax amount;
- grand total;
- payment method and amount;
- cash tendered and change for cash;
- receipt footer;
- reprint marker when reprinted.

Rules:

- Receipt data comes from stored snapshots.
- The receipt never recalculates prices from the current catalogue.
- A reprint does not change the transaction.
- The pilot uses browser printing.
- Raw ESC/POS printing is a v1.0.0 feature.

See [10 Hardware Integration](10-hardware-integration.md).

## 10. Void

A void cancels a paid transaction while preserving the audit trail.

Rules:

- Only users with `transaction.void` can void.
- A void reason is required.
- The transaction status becomes `void`.
- Stock movements are reversed with `sale_void` movements.
- Payments remain recorded.
- The invoice number remains used.
- The transaction remains visible in lists and reports with void status.
- Reports exclude voided amounts from net sales but show them separately.
- The action is audited with actor, reason, time, and before/after status.

Refunds of money to the customer are handled outside the system in the pilot. A
refund record is a v1.0.0 feature.

## 11. Reporting

Pilot reports:

- daily sales;
- sales by payment method;
- transaction count and average ticket;
- tax totals;
- discount totals;
- voided transactions;
- inventory value at cost and at selling price;
- low stock;
- work order status and mechanic workload.

Rules:

- Reports respect branch authorization.
- Consolidated reports are available to owner/admin.
- Voided transactions are shown separately.
- Timezone boundaries use the organization or branch setting.
- Exports use the same filters as the screen.
- CSV exports neutralize formula injection.
- XLSX exports preserve numeric types.

Not included in the pilot:

- general ledger;
- accounts receivable aging;
- supplier payables (suppliers and purchases are post-v1.0.0 candidates);
- payroll;
- full profit and loss;
- tax filing.

## 12. Worked examples

### Example 1: No tax

```text
Line 1: 2 x 50,000 = 100,000
Line 2: 1 x 150,000 = 150,000
Subtotal: 250,000
Discount: 10,000
Tax rate: 0%
Tax: 0
Grand total: 240,000
Cash tendered: 250,000
Change: 10,000
```

### Example 2: Exclusive tax

```text
Subtotal: 250,000
Discount: 10,000
Taxable base: 240,000
Tax rate: 11.00% (1100 bp)
Tax: round(240,000 * 1100 / 10000) = 26,400
Grand total: 266,400
```

### Example 3: Void

```text
Paid transaction: 266,400
Void reason: duplicate entry
Result:
- transaction status: void
- net sales: 0
- stock restored
- payment retained for audit
- invoice number retained
```

## 13. Data integrity rules

- The client never sends authoritative prices or totals.
- The server recomputes every amount.
- Subtotal equals the sum of line totals before discount.
- Line total equals quantity times unit price. A line-discount term is reserved
  for a future feature and stays zero until it is approved.
- Discount total cannot exceed subtotal.
- Grand total cannot be negative.
- Paid total equals the sum of applied payments.
- A paid transaction has a nonzero grand total unless explicitly allowed for
  warranty or internal work.
- Stock consumption and transaction creation happen atomically.
- Invoice uniqueness is enforced by a database constraint.
- Payment and transaction branch must match.
- All financial mutations are audited.

## 14. Permissions

| Action                 | Permission                                                                                        |
| ---------------------- | ------------------------------------------------------------------------------------------------- |
| View transactions      | `transaction.view`                                                                                |
| Create transaction     | `transaction.create`                                                                              |
| Post payment           | `payment.create`                                                                                  |
| Apply discount         | `transaction.create`; a dedicated permission is considered only if discount limits are introduced |
| Void transaction       | `transaction.void`                                                                                |
| View reports           | `report.view`                                                                                     |
| Export reports         | `report.export`                                                                                   |
| Change tax settings    | `organization.settings`                                                                           |
| Change catalogue price | `catalog.manage`                                                                                  |

## 15. Acceptance criteria

- Server-side totals match the documented formulas.
- Price tampering is rejected.
- Negative stock is rejected.
- Invoice numbers are unique under concurrency.
- Cash change is correct.
- Void restores stock and preserves audit.
- Reports exclude voided sales from net totals.
- Receipts use stored snapshots.
- Tax changes do not alter historical transactions.
- All financial mutations are audited.

# MotoFix Design System

> Status: Approved for implementation
> Audience: Product, design, engineering

## 1. Purpose

This document is the visual source of truth for the MotoFix user interface.
It defines the design direction, tokens, layout, components, page patterns,
interaction rules, print design, and implementation constraints.

- UX flows, sitemap, and screen behavior live in
  [docs/06-ui-ux.md](docs/06-ui-ux.md).
- Hardware behavior lives in
  [docs/10-hardware-integration.md](docs/10-hardware-integration.md).
- Architecture and service boundaries live in
  [docs/03-architecture.md](docs/03-architecture.md).

When a UI decision conflicts with this document, this document wins until it is
updated through review.

## 2. Design direction

**Utilitarian workshop console.**

MotoFix is a working tool for cashiers, mechanics, and garage owners.
The interface must feel fast, calm, and predictable. It is not a marketing dashboard
and not a consumer application.

Priorities, in order:

1. Speed for repetitive cashier work.
2. Clarity of money, stock, and status.
3. Low cognitive load under interruption.
4. Reliable keyboard and scanner use.
5. Clean visual hierarchy.
6. Low-bandwidth assets served locally.

Non-goals for the pilot:

- dark mode;
- decorative animation;
- custom illustrations;
- marketing landing pages;
- mobile-first layouts for cashier and administration roles;
- experimental navigation.

## 3. Brand

- Product name: **MotoFix**
- Wordmark: text only, Inter 700, tight tracking.
- No custom logo mark in the pilot.
- Tone: professional, direct, practical, respectful.
- No playful copy in financial or destructive flows.
- Organization and branch names are user content and appear as-is.

## 4. Layout shell

```text
┌──────────────────────────────────────────────────────────────┐
│ Sidebar (navy) │ Topbar (white)                              │
│                ├─────────────────────────────────────────────┤
│ MotoFix        │ Page header                                 │
│                │                                             │
│ Operations     │ Filters / actions                           │
│  Dashboard     │                                             │
│  Work orders   │ Content                                     │
│  Transactions  │                                             │
│  Customers     │                                             │
│  Inventory     │                                             │
│  Catalogue     │                                             │
│  Reports       │                                             │
│                │                                             │
│ Administration │                                             │
│  Branches      │                                             │
│  Users         │                                             │
│  Settings      │                                             │
│  Audit         │                                             │
│  System        │                                             │
└──────────────────────────────────────────────────────────────┘
```

| Region             | Specification                                       |
| ------------------ | --------------------------------------------------- |
| Sidebar width      | 248 px expanded, 72 px icon-only on compact desktop |
| Sidebar background | `#0B1F33`                                           |
| Sidebar text       | `#E2E8F0`                                           |
| Sidebar muted      | `#94A3B8`                                           |
| Sidebar active     | Teal `#0F766E` background, white text               |
| Sidebar hover      | `#12324D`                                           |
| Topbar height      | 56 px                                               |
| Topbar background  | `#FFFFFF`                                           |
| Topbar border      | 1 px `#D8E1E8`                                      |
| Content background | `#F4F7F9`                                           |
| Content padding    | 24 px desktop, 16 px tablet and phone               |
| Content max width  | 1600 px, centered                                   |
| Card radius        | 8 px                                                |
| Control radius     | 6 px                                                |

Responsive behavior:

- Desktop at 992 px and above uses the full sidebar and topbar.
- Tablet at 576–991 px uses an offcanvas sidebar and single-column content.
- Phone below 576 px uses a compact topbar and a role-specific navigation pattern.
- Mechanics on phones use bottom navigation: My jobs, Search, and Profile.
- Other roles on phones use an offcanvas sidebar.
- Mechanic work order lists use cards on phones, not horizontal tables.
- Touch targets are at least 44 px on tablet and phone.
- Mobile content respects safe-area insets.
- Core actions must not require horizontal scrolling at 360 px.

## 5. Design tokens

### 5.1 Color

| Token                  | Value                      | Use                                |
| ---------------------- | -------------------------- | ---------------------------------- |
| `--mf-navy-900`        | `#0B1F33`                  | Sidebar, strong brand surfaces     |
| `--mf-navy-800`        | `#12324D`                  | Sidebar hover                      |
| `--mf-teal-700`        | `#0F766E`                  | Primary actions, links, active nav |
| `--mf-teal-800`        | `#115E59`                  | Primary hover                      |
| `--mf-teal-50`         | `#F0FDFA`                  | Subtle selected or info surface    |
| `--mf-bg`              | `#F4F7F9`                  | Application background             |
| `--mf-surface`         | `#FFFFFF`                  | Cards, tables, topbar              |
| `--mf-border`          | `#D8E1E8`                  | Dividers and control borders       |
| `--mf-text`            | `#0F172A`                  | Primary text                       |
| `--mf-text-muted`      | `#52606D`                  | Secondary text                     |
| `--mf-success`         | `#15803D`                  | Success text and icons             |
| `--mf-warning`         | `#B45309`                  | Warning text and icons             |
| `--mf-warning-surface` | `#FEF3C7`                  | Warning badge background           |
| `--mf-warning-text`    | `#92400E`                  | Warning text on warning surface    |
| `--mf-danger`          | `#B91C1C`                  | Danger text, destructive actions   |
| `--mf-info`            | `#0E7490`                  | Informational text and icons       |
| `--mf-focus`           | `rgba(15, 118, 110, 0.30)` | Focus ring                         |

Rules:

- Use the semantic token, not raw hex values, in CSS.
- Do not use teal for large text blocks or decorative backgrounds.
- Do not convey status with color alone.
- White text is required on teal, navy, success, warning, danger, and info surfaces.
- Warning badges use `#FEF3C7` as the surface with `#92400E` text.
- Disabled controls may use reduced opacity but must remain readable.

### 5.2 Typography

| Token       | Value                                                             |
| ----------- | ----------------------------------------------------------------- |
| Font family | Inter variable, vendored                                          |
| Fallback    | `system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif` |
| Weights     | 400, 500, 600, 700                                                |
| Body        | 14 px / 1.5                                                       |
| Table       | 14 px / 1.4                                                       |
| Input       | 16 px / 1.4                                                       |
| Caption     | 12 px / 1.4                                                       |
| H1          | 32 px / 1.2, 700                                                  |
| H2          | 24 px / 1.3, 700                                                  |
| H3          | 20 px / 1.35, 600                                                 |
| H4          | 16 px / 1.4, 600                                                  |
| Sidebar nav | 14 px / 1.4, 500                                                  |
| Button      | 14 px / 1.2, 600                                                  |
| Receipt     | System monospace, 11–12 px / 1.25                                 |

Rules:

- Use tabular numerals for money, quantities, dates, and tables: `font-variant-numeric: tabular-nums`.
- Headings use `letter-spacing: -0.01em`.
- Do not use all caps for long labels; reserve it for short section labels.
- Do not use external font CDNs.
- Include `public/assets/vendor/inter/OFL.txt` with the font files.

### 5.3 Spacing

Scale: `4, 8, 12, 16, 20, 24, 32, 40, 48` px.

Common usage:

| Context         | Spacing                          |
| --------------- | -------------------------------- |
| Card padding    | 20–24 px                         |
| Table cell      | 12 px vertical, 16 px horizontal |
| Form field gap  | 16 px                            |
| Section gap     | 24 px                            |
| Page header gap | 16 px                            |
| Button gap      | 8 px                             |

### 5.4 Shape and elevation

| Token          | Value                                |
| -------------- | ------------------------------------ |
| Control radius | 6 px                                 |
| Card radius    | 8 px                                 |
| Modal radius   | 12 px                                |
| Badge radius   | 999 px                               |
| Border         | 1 px `#D8E1E8`                       |
| Card shadow    | `0 1px 2px rgba(15, 23, 42, 0.06)`   |
| Modal shadow   | `0 20px 40px rgba(15, 23, 42, 0.18)` |

Avoid heavy shadows and gradients. Use borders and spacing for hierarchy.

### 5.5 Breakpoints

| Name      | Width       | Behavior                                 |
| --------- | ----------- | ---------------------------------------- |
| `phone`   | `< 576 px`  | Compact topbar, bottom nav for mechanic  |
| `tablet`  | 576–991 px  | Offcanvas sidebar, single-column content |
| `desktop` | `>= 992 px` | Full sidebar, full layout                |
| `xl`      | 1200 px     | Full density                             |
| `xxl`     | 1400 px     | Content max-width applies                |

## 6. Navigation

### Sidebar

- Wordmark at the top.
- Group labels: **Operations**, **Administration**.
- Items filtered by permission.
- Active item: teal background, white text, left indicator optional.
- Icons: Bootstrap Icons, 18 px, aligned with labels.
- Groups collapse only if needed; default is expanded.
- Bottom area: application version and environment indicator in non-production.
- Inactive branches are not shown in selectors.

Operations:

- Dashboard
- Work orders
- Transactions
- Customers
- Inventory
- Catalogue
- Reports

Administration:

- Branches
- Users
- Organization settings
- Audit log
- System

### Topbar

- Branch selector (left).
- Global search / scan field (center or left-center).
- Language switcher and user menu (right).
- On tablet, hamburger opens the sidebar.
- On phone, the topbar is compact; branch and language controls remain reachable.
- No breadcrumb in the pilot; page header provides context.

### Mobile mechanic navigation

- Bottom navigation is visible only for mechanics on phones.
- Items: My jobs, Search, Profile.
- Active item uses teal text and icon.
- Bottom navigation height is 64 px plus safe-area inset.
- Main content reserves space for the bottom navigation.
- Search covers customers, vehicles, and work orders within the mechanic's access
  scope.
- Profile contains language, branch context where applicable, password, and
  sign-out.
- No financial modules appear in mechanic mobile navigation.

### Branch selector

- Shows active branch name and code.
- Only accessible branches are listed.
- Owner/admin can choose consolidated mode where supported.
- Switching branch reloads the current context safely.

## 7. Components

### 7.1 Buttons

| Variant   | Use                                 |
| --------- | ----------------------------------- |
| Primary   | Main action, teal                   |
| Secondary | Secondary action, white with border |
| Outline   | Tertiary action                     |
| Danger    | Destructive action, red             |
| Ghost     | Toolbar or icon action              |
| Link      | Inline navigation                   |

Rules:

- Use verb labels: "Save work order", "Post payment", "Void transaction".
- Minimum height 40 px; 44 px on touch.
- Icon plus label for primary actions; icon-only buttons need accessible labels.
- Loading state disables the button and shows a spinner.
- Destructive actions require confirmation.
- Never use more than one primary button per section.

### 7.2 Forms

- Label above the field.
- Required fields marked with a visible indicator.
- Help text below the label or field.
- Validation summary at the top and inline error below the field.
- Preserve input after validation failure.
- Submit button disabled while processing.
- Group related fields with `fieldset` and legend.
- Money fields show a currency prefix and format on blur.
- Quantity fields are numeric with clear minimum and maximum.
- Do not use placeholder text as a label.

### 7.3 Tables

- Header background `#F4F7F9`, sticky where useful.
- Row height 40 px.
- Hover background subtle, no zebra by default.
- Numeric columns right-aligned with tabular numerals.
- Status column uses a badge with text.
- Row actions use compact buttons or a menu.
- Pagination below the table; show total count.
- Empty state explains the next action.
- Horizontal scroll on tablet; do not hide primary columns.
- On phones, mechanic work order tables become card lists.
- Bulk actions only when explicitly designed and tested.

### 7.4 Badges

- Shape: pill.
- Text plus color.
- Work order: `draft`, `in_progress`, `waiting_parts`, `completed`, `cancelled`.
- Transaction: `unpaid`, `paid`, `void`.
- Status colors follow the semantic mapping.
- Do not use a badge for long text.

### 7.5 Cards

- White surface, 1 px border, 8 px radius, subtle shadow.
- Header optional, 16 px semibold.
- KPI card: label, value, optional delta or context.
- No nested cards.

### 7.6 Alerts and feedback

- Inline alert for page-level information.
- Toast for success after an action.
- Modal confirmation for destructive actions.
- Error summary for forms.
- Loading skeleton for slow tables; spinner for actions.
- Empty state includes a short explanation and primary action.

### 7.7 Search and scan

- Global search finds customers, vehicles, invoices, and work orders.
- Scan field is clearly labeled and reachable by keyboard.
- Scan result is immediate; no page reload for item scan inside a work order.
- Unknown code shows a non-blocking error and keeps the input ready.
- Do not auto-create records from an unknown scan.

### 7.8 Modal and dialog

- Used for confirmation and short focused tasks.
- Maximum width 520 px.
- Title, body, cancel, confirm.
- Destructive confirm uses danger button.
- Focus trap and focus restore.
- `Esc` closes unless the action is irreversible.

### 7.9 Pagination

- Previous, page numbers, next.
- Show "Showing X–Y of Z".
- Preserve filters in links.
- Page size default 25; options 25, 50, 100 where appropriate.

### 7.10 Import and export

- Import uses a stepper: upload, validate, confirm, result.
- Show row-level errors in a compact table.
- Provide template download.
- Export actions are secondary unless the page is a report.
- XLSX export is labeled clearly; CSV export is available for compatibility.

### 7.11 Mobile components

- Bottom navigation: My jobs, Search, Profile.
- Work order card: number, customer, vehicle, status, updated time.
- Sticky action bar: Start, Waiting for parts, Complete.
- Bottom sheet: status confirmation and note entry.
- Compact topbar with back navigation.
- No data table on mechanic phone screens; use cards.
- No camera or photo control in the pilot.

## 8. Page patterns

### 8.1 Dashboard

- Page title and branch context.
- KPI row: today's sales, transactions, average ticket, low stock.
- Sales chart for the selected period.
- Recent work orders table.
- Low-stock list with link to inventory.
- Owner/admin may switch to consolidated mode.

### 8.2 List page

- Page header with title, subtitle, and primary action.
- Filter bar with search, status, date range, and reset.
- Table with sortable columns where useful.
- Pagination and result count.
- Empty state.

### 8.3 Detail page

- Header: title, status, key identifiers, primary actions.
- Summary cards for customer, vehicle, totals, or stock.
- Tabs only when there is real secondary content.
- Activity or audit summary at the bottom where relevant.
- Sticky action bar is allowed for checkout or long forms.

### 8.4 Form page

- Two columns on desktop for related fields; one column on tablet.
- Grouped sections with clear headings.
- Save and cancel at the bottom; optional sticky footer.
- Unsaved changes warning.
- Validation summary at top.

### 8.5 Checkout

- Left: line review and totals.
- Right: payment panel and summary.
- Discount and tax are clearly separated.
- Cash tendered and change are prominent.
- Primary action: "Post payment".
- Confirmation states the final amount and irreversible nature.
- Success state offers print and reprint.

### 8.6 Receipt

- Dedicated print layout, no navigation.
- 58 mm and 80 mm variants.
- Classic monospace.
- Black text on white.
- No external assets.
- Reprint marker when applicable.

### 8.7 Settings

- Section navigation or tabs.
- Save per section.
- Show effective values and inherited defaults.
- Destructive settings require confirmation.

### 8.8 System

- Cards for version, database, disk, last backup, and health.
- Backup now and download actions.
- Support bundle action.
- No database administration UI.

### 8.9 Mobile mechanic

- Bottom navigation: My jobs, Search, Profile.
- My jobs: card list with status, customer, vehicle, and updated time.
- Filters open in a bottom sheet; active filters are visible as chips.
- Job detail: compact header, contact actions, vehicle details, items, notes.
- Sticky action bar: Start, Waiting for parts, Complete.
- Status change opens a bottom sheet with an optional note.
- No price, payment, checkout, invoice, or financial history.
- No cancel action; cancellation is handled by manager, admin, or cashier.
- No camera or photo upload control.
- Empty state explains that no jobs are assigned.
- Pull-to-refresh is not required; a visible refresh action is allowed.

## 9. Interaction patterns

### Keyboard shortcuts

Proposed for pilot; validate for browser conflicts during implementation:

| Shortcut         | Action                              |
| ---------------- | ----------------------------------- |
| `/`              | Focus global search when not typing |
| `Alt+B`          | Focus barcode scan field            |
| `Alt+N`          | New work order                      |
| `Ctrl/Cmd+Enter` | Submit active form or checkout      |
| `Esc`            | Close dialog, dropdown, or search   |
| `?`              | Open shortcut help when not typing  |

Rules:

- Shortcuts must not fire while the user is typing in an input.
- Shortcut help is discoverable.
- Scanner input must not trigger global shortcuts.

### Feedback

- Success: short toast, no blocking modal.
- Validation: inline plus summary.
- Permission denial: clear message and safe return path.
- Network/server error: correlation ID and retry guidance.
- Destructive action: confirmation with consequence.
- Void: reason is required.

### Unsaved changes

- Warn before leaving a dirty form.
- Do not warn after a successful save.
- Do not use the warning for search/filter changes.

## 10. Data display

- Money: `Rp1.234.567`, tabular numerals, right-aligned in tables.
- Dates: locale-aware, `dd MMM yyyy` for tables, full date-time in detail.
- Timezone: organization or branch setting.
- Quantity: integer, right-aligned.
- Long text: truncate with tooltip or expandable row.
- Empty values: use a muted dash, not blank cells.
- Status: badge plus text.
- IDs and invoice numbers: monospace where it improves scanning.
- Negative or void amounts: show clearly, never hide from audit views.

## 11. Internationalization

- Locales: English (`en`) and Indonesian (`id`).
- All UI strings use translation keys.
- Allow up to 30% text expansion.
- Avoid fixed-width buttons for translated labels.
- Status keys are stored in English and translated in the UI.
- Number, date, and currency formatting follow the locale.
- The language switcher preserves the current page.
- User preference overrides organization default.
- Missing translations fall back to English.

## 12. Accessibility

Target WCAG 2.1 AA for core flows.

- Every input has a programmatic label.
- Errors are associated with fields.
- Focus is visible and follows a logical order.
- Color is not the only status indicator.
- Text contrast meets AA.
- Touch targets are at least 44 px on tablet and phone.
- Mobile content respects safe-area insets and text zoom.
- Modals and bottom sheets trap focus and restore it.
- Tables have header cells.
- Keyboard users can complete the cashier flow.
- Mobile mechanic flows are usable one-handed where practical.
- Motion respects `prefers-reduced-motion`.
- Icons that carry meaning have accessible labels.

## 13. Print design

### Common rules

- Print only the receipt content.
- Hide navigation, buttons, and screen chrome.
- Use black text on white.
- No background colors or images.
- No external fonts or assets.
- Page margin: 3 mm.
- Line height: 1.25.
- Test on 58 mm and 80 mm paper.

### Receipt structure

1. MotoFix wordmark.
2. Organization name and branch identity.
3. Invoice number and date.
4. Cashier.
5. Customer and vehicle.
6. Item lines with quantity, unit price, and total.
7. Subtotal.
8. Discount.
9. Tax rate and tax amount.
10. Grand total.
11. Payment method and amount.
12. Cash tendered and change.
13. Footer.
14. Reprint marker.

### 58 mm

- Font size: 11 px.
- Margins: 2–3 mm.
- Avoid long labels; wrap naturally.
- Keep totals compact.

### 80 mm

- Font size: 12 px.
- Margins: 3 mm.
- More room for item descriptions.

## 14. Implementation mapping

### Assets

```text
public/assets/
├── css/
│   └── app.css
├── js/
│   └── app.js
└── vendor/
    ├── bootstrap/
    ├── bootstrap-icons/
    ├── chartjs/
    └── inter/
        ├── InterVariable.woff2
        └── OFL.txt
```

- No CDN.
- No Node.js build step for runtime or installation.
- Vendor licenses are recorded.
- Asset updates are reviewed as dependency changes.

### Font face

```css
@font-face {
  font-family: "Inter";
  src: url("../vendor/inter/InterVariable.woff2") format("woff2");
  font-weight: 100 900;
  font-style: normal;
  font-display: swap;
}
```

### CSS tokens

```css
:root {
  --mf-navy-900: #0b1f33;
  --mf-navy-800: #12324d;
  --mf-teal-700: #0f766e;
  --mf-teal-800: #115e59;
  --mf-teal-50: #f0fdfa;
  --mf-bg: #f4f7f9;
  --mf-surface: #ffffff;
  --mf-border: #d8e1e8;
  --mf-text: #0f172a;
  --mf-text-muted: #52606d;
  --mf-success: #15803d;
  --mf-warning: #b45309;
  --mf-danger: #b91c1c;
  --mf-info: #0e7490;
  --mf-focus: rgba(15, 118, 110, 0.3);
}
```

### Bootstrap overrides

- Override Bootstrap CSS variables in `app.css` after the vendored Bootstrap stylesheet.
- Map primary actions to teal.
- Use Bootstrap grid, forms, and utilities; avoid duplicating them.
- Custom component classes use the `mf-` prefix.
- Do not modify vendored Bootstrap files directly.

### Twig components

Use partials under `resources/views/components/` for:

- buttons;
- form fields;
- status badges;
- page header;
- filter bar;
- empty state;
- confirmation dialog;
- pagination;
- money display;
- scan field;
- receipt line items.

Components receive explicit data and do not query the database.

## 15. Design review checklist

Before a UI change is approved:

- [ ] It follows the layout and tokens in this document.
- [ ] All strings are translated in `en` and `id`.
- [ ] Status is conveyed by text and color.
- [ ] Money uses tabular numerals and correct formatting.
- [ ] Forms have labels, help text, and inline errors.
- [ ] Destructive actions require confirmation.
- [ ] Keyboard and scanner flows work.
- [ ] Focus states are visible.
- [ ] Contrast meets AA.
- [ ] Tables remain usable on tablet.
- [ ] Mechanic mobile is usable on Android Chrome at 360 px and above.
- [ ] Mechanic mobile uses bottom navigation and card lists.
- [ ] No financial data appears on mechanic mobile.
- [ ] No CDN or external font is introduced.
- [ ] No inline CSS or JavaScript handles user data.
- [ ] Receipt layout is tested on 58 mm and 80 mm if changed.
- [ ] Screenshots are attached to the pull request.

## 16. References

- [UI and UX](docs/06-ui-ux.md)
- [Hardware Integration](docs/10-hardware-integration.md)
- [Architecture](docs/03-architecture.md)
- [Contributing](docs/12-contributing.md)

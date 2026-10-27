# Hardware Integration

> Status: Approved for implementation
> Audience: Engineering, support, operators

## 1. Scope

The pilot supports the two hardware categories most garages already use:

- thermal receipt printers with an operating-system driver;
- USB or Bluetooth barcode scanners in HID keyboard mode.

The pilot also supports a mobile web mechanic flow on Android Chrome. It does not
access raw USB devices, network printers, cash drawers, cameras, or photo uploads.
Those are v1.0.0 candidates.

## 2. Design principles

1. Prefer hardware that already works with the operating system.
2. Keep the server out of the printing path.
3. Do not require a local print agent in the pilot.
4. Never request raw device permissions from the browser.
5. Make failures understandable to a non-technical cashier.
6. Document tested hardware and known limitations.

## 3. Thermal printer support

### Supported path

- Printer is installed on the cashier computer with its vendor driver.
- Browser print dialog is used to print the receipt.
- Receipt page provides 58 mm and 80 mm layouts.
- Page setup uses CSS `@page` and a dedicated print stylesheet.
- The cashier can print or reprint from the transaction detail.

### Receipt layout requirements

The visual specification for receipts is defined in
[DESIGN.md](../DESIGN.md#13-print-design).

- Width: 58 mm or 80 mm.
- System monospace font.
- No external fonts.
- Black text on white.
- Text wordmark in the pilot; an image logo is a future option.
- Long item names wrap without overlapping columns.
- Quantities and prices align.
- Totals are visually distinct.
- Footer is configurable.
- A reprint marker is shown when applicable.

### Print CSS

- Hide navigation, buttons, and screen-only chrome.
- Set page margins appropriate to the paper width.
- Avoid background colors that waste thermal paper.
- Use `print-color-adjust` carefully.
- Test in Chrome and Edge on Windows.
- Test in Firefox where practical.

### Limitations

The pilot does not support:

- raw ESC/POS commands;
- cash drawer kick;
- printing without a driver;
- direct printing to a network printer from the server;
- silent printing without the browser dialog;
- images on receipts at all. Pilot receipts use a text wordmark; image logos are
  an ORG-03 v1.0.0 feature, and dithering limits will be addressed when it ships.

These limitations are documented for the operator. If a garage requires them, the
v1.0.0 path is a local print agent or a server-side ESC/POS integration with explicit
network configuration.

## 4. Barcode scanner support

### Supported path

- Scanner is configured as a USB or Bluetooth HID keyboard.
- Scanner sends the code followed by Enter.
- Application provides a global scan field.
- Scan field is focused by a keyboard shortcut or by clicking the scan icon.
- The application resolves the code against item barcode, item SKU, invoice number,
  vehicle plate, and customer phone.

### Scanner behavior

- Do not require special drivers.
- Do not depend on a specific scanner brand.
- Tolerate different keyboard layouts where possible.
- Ignore empty scans.
- Prevent duplicate submission when the scanner sends Enter twice.
- Show a clear success or not-found message.
- Keep focus predictable after a scan.
- Do not submit a form accidentally when scanning outside the scan field.

### Recommended scan targets

| Context            | Code                | Result                   |
| ------------------ | ------------------- | ------------------------ |
| Work order line    | Item barcode or SKU | Add or select item       |
| Transaction search | Invoice number      | Open transaction         |
| Customer search    | Phone or plate      | Open customer or vehicle |
| Inventory          | Item barcode or SKU | Open stock detail        |

### Unknown code

- Show a translated message.
- Do not create a record automatically.
- Keep the entered code visible for correction.
- Log the failed lookup only at debug level, without personal data.

## 5. Cashier workflow

```text
Open work order
  -> Focus scan field
  -> Scan item barcode
  -> Item is added to the work order
  -> Repeat for more items
  -> Complete work
  -> Checkout
  -> Post payment
  -> Print receipt
```

Keyboard expectations:

- Scan field shortcut does not conflict with browser shortcuts.
- Enter in the scan field does not submit an unrelated form.
- The cashier can return focus to the scan field quickly.
- The active branch and work order remain visible.

## 5.1 Mobile mechanic hardware

- Mobile uses the phone browser, not a native application.
- No camera permission is requested in the pilot.
- No photo upload is available in the pilot.
- Mechanic search is manual by customer, plate, or work order.
- HID scanners are uncommon on phones; camera scanning is a v1.0.0 candidate.
- Mobile printing is not required; receipts are printed at the cashier station.
- Mobile requires network access to the central server.

## 6. Operator setup

For thermal printing:

1. Install the printer driver on the cashier computer.
2. Set the printer as the default or select it in the browser dialog.
3. Load the correct paper width.
4. Print a test page from the browser.
5. Adjust margins only through documented settings.

For barcode scanning:

1. Connect the scanner as a keyboard device.
2. Configure the scanner to send Enter after the code.
3. Test in a text field.
4. Open MotoFix and use the scan field.
5. Confirm item and invoice lookup.

No server-side configuration is required for the pilot.

## 7. Testing checklist

Before a release:

- Print a receipt on 58 mm paper.
- Print a receipt on 80 mm paper.
- Print a receipt with a long customer name and long item names.
- Print a receipt with a discount and tax.
- Reprint a receipt and confirm the reprint marker.
- Scan an item barcode into a work order.
- Scan an item SKU.
- Scan an invoice number.
- Scan a vehicle plate.
- Scan an unknown code and confirm the error message.
- Confirm no double item is added when the scanner sends Enter twice.
- Confirm the application remains usable when no printer is installed.
- Confirm the receipt page does not require internet access.
- Confirm mobile mechanic works on Android Chrome at 360 px and above.
- Confirm mobile does not request camera or photo permissions.
- Confirm mobile search finds only assigned customers, vehicles, and work orders.

## 8. Error handling

| Problem                 | User-facing behavior                                           |
| ----------------------- | -------------------------------------------------------------- |
| Printer not installed   | Browser print dialog shows no printer; receipt remains visible |
| Wrong paper width       | Layout selection lets the cashier choose 58 or 80 mm           |
| Print dialog cancelled  | No transaction change; cashier can retry                       |
| Scanner not in HID mode | No input; setup guide explains keyboard mode                   |
| Unknown barcode         | Clear translated message; no record created                    |
| Duplicate scan          | Debounced; one line added                                      |
| Scanner sends no Enter  | Cashier can press Enter manually                               |

## 9. Security

- The browser is not granted raw USB or serial access.
- The server does not connect to printers in the pilot.
- Scanner input is treated as untrusted text and validated.
- Barcode values are escaped on display.
- Print views escape all data.
- No device identifiers are stored unless needed for v1.0.0 printer configuration.

## 10. v1.0.0 candidates

- Raw ESC/POS over network or local agent.
- Cash drawer kick.
- Network printer configuration per branch.
- Silent printing through a local agent.
- Camera-based barcode scanning.
- Photo upload for vehicle condition.
- Barcode label printing.
- Tested-hardware compatibility list.

## 11. Acceptance criteria

- A receipt prints correctly on 58 mm and 80 mm paper using the browser.
- A HID scanner can find items, invoices, plates, and customer phones.
- Unknown scans do not create or modify records.
- The cashier can complete a sale without a mouse after sign-in.
- Printing does not require the server to reach the printer.
- The application remains functional when hardware is unavailable.
- Setup instructions are sufficient for a non-technical operator.

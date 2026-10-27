# ADR-0008: Browser Printing and HID Scanning for the Pilot

- Status: Accepted
- Context: Garages commonly use thermal receipt printers and USB or Bluetooth
  barcode scanners. Raw ESC/POS printing, cash drawer control, camera scanning,
  and local print agents add significant platform-specific complexity and cannot
  be validated reliably across many garage environments in the pilot.
- Decision:
  - Use browser printing with dedicated 58 mm and 80 mm receipt layouts.
  - Support HID keyboard-mode barcode scanners.
  - Do not access raw USB or network devices from the server in the pilot.
  - Defer raw ESC/POS, cash drawer, network printer agents, camera scanning, and
    label printing to v1.0.0.
- Consequences:
  - The pilot works with printers that have an operating-system driver.
  - The pilot does not support cash drawer kick or silent printing.
  - Scanner support is broad because HID scanners behave like keyboards.
  - Setup depends on the cashier computer's printer driver and scanner mode.
  - Hardware limitations must be documented clearly.
- Alternatives considered:
  - Raw ESC/POS from the server: powerful, but requires printer network access and
    per-site configuration.
  - Local print agent: reliable for USB printers, but adds installation and
    support burden.
  - Camera scanning: useful on mobile, but unnecessary for a cashier desk with a
    dedicated scanner.
  - Deferring all hardware support: rejected because receipt printing and scanning
    are core to daily garage operations.
- References:
  - [10 Hardware Integration](../10-hardware-integration.md)
  - [06 UI and UX](../06-ui-ux.md)

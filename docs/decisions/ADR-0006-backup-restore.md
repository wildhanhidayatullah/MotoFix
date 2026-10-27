# ADR-0006: Backup and Restore Strategy

- Status: Accepted
- Context: A self-hosted garage system holds financial, customer, and inventory
  data that cannot be reconstructed easily. The operator may not have IT staff.
  Local backups alone do not protect against server loss.
- Decision:
  - Back up the database and application storage together.
  - Create a daily automatic backup and a manual backup before upgrades.
  - Apply configurable retention with a protected last-known-good backup.
  - Provide a restore command that requires confirmation and maintenance mode.
  - Verify backups through a documented restore drill.
  - Document offsite copy guidance for the pilot.
  - Add automated offsite integration as a v1.0.0 candidate.
- Consequences:
  - Operators must copy backups off the server; the application cannot guarantee
    disaster recovery by itself.
  - Restore is a deliberate, manual operation.
  - Backup failures must be visible in the System page and logs.
  - Upgrades always take a backup first.
  - Restore must be tested against a clean installation.
- Alternatives considered:
  - Database dump only: misses uploaded files and configuration.
  - Local backup only: insufficient for server loss.
  - Automatic restore on startup: dangerous and rejected.
  - Full disk snapshot only: useful at the infrastructure level, but not a
    portable application backup.
- References:
  - [08 Self-Hosted Operations](../08-self-hosted-operations.md)
  - [04 Data Model](../04-data-model.md)

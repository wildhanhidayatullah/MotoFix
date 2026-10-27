# Self-Hosted Operations

> Status: Approved for implementation
> Audience: Operators, maintainers, support

## 1. Operating model

MotoFix is installed and operated by the garage or by someone acting on its
behalf. The project does not run a central service or telemetry endpoint.

Each installation has:

- one organization;
- one database;
- one domain;
- one set of backups;
- one upgrade responsibility.

Desktop and mobile clients require network access to the server. There is no
offline mode.

The operator is responsible for the server, domain, backups, and updates. The
application must make those tasks as simple and safe as possible.

## 2. Supported environment

Recommended:

- Linux server with a current LTS release;
- 2 vCPU;
- 4 GB RAM;
- 40 GB SSD;
- Docker Engine and Docker Compose plugin;
- a domain name with DNS control;
- ports 80 and 443 reachable from the internet.

Minimum for a small pilot:

- 1 vCPU;
- 2 GB RAM with swap;
- 20 GB SSD.

The application does not require PHP, Composer, a web server, MariaDB, or Node.js
on the host. PHP and MariaDB run in containers, Caddy terminates HTTP, and all
frontend assets are vendored. There is no asset build step on the server.

## 3. Runtime topology

```text
Internet
  -> Caddy (80/443, automatic HTTPS)
      -> app (PHP-FPM, port 9000 internal)
          -> db (MariaDB, port 3306 internal)
```

Persistent volumes:

- database data;
- application storage;
- Caddy certificates and configuration;
- backups.

Only Caddy publishes ports. The application and database are reachable only on the
internal Docker network.

## 4. Domain and DNS

Automatic HTTPS requires a DNS name pointing to the server.

Before installation:

1. Create an `A` record for the chosen domain pointing to the server IPv4 address.
2. Create an `AAAA` record if IPv6 is used and supported.
3. Wait for DNS propagation.
4. Ensure the firewall allows inbound TCP 80 and 443.
5. Ensure no other service is using those ports.

The installer performs a preflight check and reports DNS or port problems.

A local-only installation can use HTTP on localhost for evaluation, but production
must use HTTPS.

## 5. Installation

### Prerequisites

- A clean supported server.
- Docker installed.
- SSH access.
- Domain and DNS configured.
- A secure owner email and password.

### Installer flow

The installer is a CLI command exposed through a wrapper script.

```text
./install.sh
```

Steps:

1. Check Docker and Docker Compose.
2. Check ports, DNS, disk, and permissions.
3. Prompt for domain, organization name, owner name, owner email, and password.
4. Generate database password and application secrets.
5. Write `.env` with restrictive permissions.
6. Start Caddy, application, and database containers.
7. Run migrations.
8. Seed roles and permissions.
9. Create the organization and first branch.
10. Create the owner account.
11. Run health checks.
12. Print the application URL and next steps.

The installer must be idempotent where possible and must stop on unsafe
conditions.

### Post-install checklist

- Sign in as owner.
- Change the owner password if it was generated.
- Configure organization identity, tax, locale, and receipt footer.
- Create branches.
- Create users and assign roles.
- Configure items, services, and opening stock.
- Run a test work order, transaction, and receipt.
- Create a backup.
- Restore the backup into a staging installation.
- Document the server and domain owner.

## 6. Configuration

Configuration lives in `.env` and is validated at boot.

Groups:

| Group            | Examples                                                                     |
| ---------------- | ---------------------------------------------------------------------------- |
| Application      | `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_TIMEZONE`                            |
| Database         | `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`                    |
| Session          | `SESSION_NAME`, `SESSION_LIFETIME`, `SESSION_IDLE_TIMEOUT`, `SESSION_SECURE` |
| Logging          | `LOG_LEVEL`, `LOG_CHANNEL`                                                   |
| Backup           | `BACKUP_PATH`, `BACKUP_RETENTION_DAYS`                                       |
| Mail (optional)  | `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`                   |
| Proxy (optional) | `TRUSTED_PROXIES`                                                            |

Rules:

- `.env` is never committed.
- `.env.example` documents all keys.
- Production rejects `APP_DEBUG=true`.
- Secrets are generated, not chosen from examples.
- Changing configuration requires a container restart unless documented otherwise.

## 7. Caddy

Caddy provides:

- automatic HTTPS;
- HTTP to HTTPS redirect;
- static file serving;
- FastCGI proxy to the application;
- security headers;
- access logs.

Caddy data is stored in a persistent volume so certificates survive restarts.

Local development uses HTTP. Production uses a real domain and automatic
certificates.

See [ADR-0007](decisions/ADR-0007-caddy.md).

## 8. Backup

### What is backed up

- MariaDB database dump;
- application storage, including generated files;
- configuration metadata needed to identify the installation;
- backup metadata.

Secrets are not embedded in the backup. The operator must retain the environment
secrets separately and provide them during a clean restore.

### Backup schedule

- Daily automatic backup. Scheduling runs inside the application containers; no
  operator cron configuration is required.
- Manual backup before every upgrade.
- Manual backup available from the System page and CLI.

### Retention

- Default: 7 daily backups and 4 weekly backups.
- Retention is configurable.
- Retention must not delete the last known good backup.
- Backups are stored with restrictive permissions.

### Offsite copies

Local backups protect against application mistakes, not server loss. The operator
must copy backups off the server.

Recommended options:

- encrypted object storage;
- external drive stored separately;
- a second server.

The pilot documents a manual offsite procedure. Automated S3-compatible backup is
a v1.0.0 candidate.

### Verification

- A backup is not considered valid until it has been restored successfully at
  least once.
- The restore drill is part of the go-live checklist.
- Backup failures are visible on the System page and in logs.

### Commands

```text
bin/console backup
bin/console restore --file=backup.zip
```

Restore requires explicit confirmation and should run with the application in
maintenance mode.

## 9. Restore

Restore procedure:

1. Put the application in maintenance mode.
2. Stop application writes.
3. Verify the backup checksum.
4. Restore the database.
5. Restore application storage.
6. Run migrations if the backup is from an older supported release.
7. Clear caches.
8. Run health checks.
9. Exit maintenance mode.
10. Perform a smoke test.

Restore must never run automatically on startup.

## 10. Upgrade

### Release model

- Semantic Versioning with `vMAJOR.MINOR.PATCH` release tags.
- Git tags are reserved for released versions; baselines, documentation snapshots,
  and experiments use branches or commit SHAs.
- Immutable container images.
- GitHub Releases with changelog and upgrade notes.
- Database migrations included in the image.
- No auto-update.

### Upgrade procedure

```text
./upgrade.sh
```

Steps:

1. Check the target version.
2. Create a backup.
3. Enable maintenance mode.
4. Pull the new images.
5. Start the new application.
6. Run migrations.
7. Run health checks.
8. Disable maintenance mode.
9. Print success or rollback instructions.

### Rollback

If health checks fail:

1. Stop the new application.
2. Restore the pre-upgrade backup.
3. Start the previous image version.
4. Verify health.
5. Record the incident.

Rollback must be documented for every release that changes the schema.

### Compatibility

- Migrations must support the previous supported release.
- Breaking changes are called out in release notes.
- Skipping major versions is not supported unless the release notes say so.

## 11. Monitoring and health

- `/health` reports application and database status.
- Health output is minimal and does not expose secrets.
- Logs are structured.
- Disk usage is visible on the System page.
- Failed backups are visible.
- The operator may use an external uptime monitor.
- No telemetry is sent to the project.

Recommended external checks:

- HTTPS availability;
- health endpoint;
- disk space;
- backup age.

## 12. Diagnostics

### Doctor command

```text
bin/console doctor
```

Checks:

- PHP version and extensions;
- database connectivity;
- migration status;
- file permissions;
- storage and backup paths;
- disk space;
- timezone;
- configuration values;
- Caddy and application reachability where possible.

### Support bundle

```text
bin/console support:bundle
```

Collects:

- application version;
- container versions;
- redacted configuration;
- recent application logs;
- health results;
- disk and database size;
- migration status.

It must exclude:

- passwords;
- application secrets;
- session data;
- full customer records;
- payment references beyond what is necessary.

## 13. Logging and retention

- Application logs rotate.
- Audit logs remain in the database.
- Caddy access logs rotate.
- Log retention is configurable.
- Logs are not stored in the web root.
- Logs are readable by the application user only.

## 14. Security maintenance

Operator checklist:

- keep the OS updated;
- keep Docker updated;
- apply MotoFix security releases promptly;
- restrict SSH to key authentication;
- use a firewall;
- expose only 80 and 443;
- never publish the database port;
- use strong owner credentials;
- keep backups protected;
- rotate secrets after suspected compromise;
- review audit logs for unusual activity.

Security advisories are published through the repository's security policy.

## 15. Troubleshooting

Common issues:

| Symptom                   | Likely cause                            | Action                                     |
| ------------------------- | --------------------------------------- | ------------------------------------------ |
| HTTPS not issued          | DNS or port problem                     | Check DNS, ports 80/443, Caddy logs        |
| Database connection error | Wrong credentials or database not ready | Check container health and `.env`          |
| White page or 500         | Application error or permissions        | Check logs and support bundle              |
| Receipt does not print    | Printer driver or browser dialog        | Check OS printer and browser print preview |
| Barcode not found         | Scanner mode or unknown code            | Verify HID mode and item barcode           |
| Backup fails              | Disk or permissions                     | Check disk, backup path, and logs          |
| Upgrade fails             | Migration or health check               | Restore backup and follow rollback         |
| Disk full                 | Logs, backups, or database growth       | Rotate logs, prune backups, expand disk    |

The operator should not edit the database manually. Use the application, CLI, or a
documented recovery procedure.

## 16. Uninstall and data export

Before uninstalling:

1. Create a final backup.
2. Download the backup.
3. Export required reports.
4. Remove containers and volumes only after verification.

The backup is the primary data export mechanism. Report exports are available for
operational data.

## 17. Operator simplicity requirements

The product must minimize maintenance work:

- one install script;
- automatic HTTPS;
- no manual database maintenance;
- no mandatory email server;
- daily backups with retention;
- upgrade script with backup and rollback guidance;
- health and diagnostics in one place;
- clear logs;
- documentation with screenshots;
- no phpMyAdmin in production;
- no cron configuration required from the operator;
- no host PHP or web server configuration.

If a task cannot be made simple, it must be documented as a support task with a
clear procedure.

## 18. Release operations

Release pipeline:

1. Run CI: formatting, static analysis, tests, dependency audit.
2. Build the application image.
3. Include the vendored frontend assets in the image; no asset build step exists.
4. Run integration tests against MariaDB.
5. Publish images to the container registry.
6. Create a GitHub Release with changelog.
7. Publish checksums and upgrade notes.
8. Verify a clean install from the published image.
9. Verify an upgrade from the previous supported release.

No release is published from an uncommitted working tree.

## 19. Service levels

MotoFix is community-supported open source. There is no guaranteed service level.
The project should still provide:

- a security reporting channel;
- documented support information;
- reproducible diagnostics;
- clear release notes;
- a supported-version policy.

Commercial support, if introduced later, is outside the current scope.

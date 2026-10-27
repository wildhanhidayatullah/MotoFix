# Security Policy

## Supported versions

MotoFix is in the implementation phase. There is no stable release yet.

| Version                  | Supported                                  |
| ------------------------ | ------------------------------------------ |
| `main` branch            | Yes, during pre-release development        |
| Latest published release | Yes, after the first release               |
| Older releases           | Best effort unless stated in release notes |

Security fixes are released for the latest supported line. Operators should
upgrade promptly when a security release is published.

## Reporting a vulnerability

Do not open a public issue for a security vulnerability.

Report through GitHub Private Vulnerability Reporting on this repository. If
that channel is unavailable, contact the project maintainer directly through the
private channel listed in the repository profile.

Include:

- affected version or commit;
- environment and deployment type;
- steps to reproduce;
- expected and actual behavior;
- impact assessment;
- proof of concept, if safe to share;
- suggested fix, if available;
- whether the issue is already public anywhere.

Do not include real customer data, production credentials, or destructive payloads.

## What to expect

- The maintainer acknowledges the report.
- The report is triaged by severity and exploitability.
- A fix is prepared and tested.
- A release and advisory are coordinated.
- Credit is given unless the reporter requests anonymity.

The project is community-supported open source and does not provide a paid
incident response service.

## In scope

- authentication and session management;
- authorization and cross-branch access;
- transaction, payment, tax, and invoice integrity;
- inventory integrity;
- CSRF, XSS, SQL injection, and injection through imports;
- file handling and import/export;
- installer, upgrade, backup, and restore paths;
- secrets exposure;
- dependency vulnerabilities that affect the application;
- default deployment misconfiguration with security impact.

## Out of scope

- vulnerabilities that require physical access to the server;
- denial of service through extreme resource exhaustion without a practical fix;
- issues in unsupported browsers or operating systems;
- social engineering;
- vulnerabilities in third-party services not controlled by the project;
- reports without a reproducible security impact;
- spam or automated low-quality scanner output.

## Safe harbor

Good-faith security research is welcome. The project will not pursue legal action
for research that:

- stays within the reporter's own installation or test environment;
- avoids accessing other people's data;
- avoids disrupting production systems;
- reports findings privately and gives reasonable time to fix;
- does not exploit the issue beyond what is necessary to demonstrate impact.

## Security expectations for operators

- Use HTTPS in production.
- Do not expose the database port.
- Keep the operating system, Docker, and MotoFix updated.
- Use strong owner credentials.
- Restrict SSH access.
- Keep backups protected and copy them off the server.
- Review audit logs for unusual activity.
- Report suspected compromise promptly.

## Secure development

- Security-sensitive changes require focused review.
- Cross-branch authorization tests are mandatory.
- Financial and stock calculations are server-side.
- Secrets are never committed.
- Dependencies are locked and audited in CI.
- Release images are built by CI.
- No telemetry is included.

## Disclosure

The project coordinates disclosure with the reporter. Public details are
published after a fix is available, or sooner if the issue is already public and
users need to act.

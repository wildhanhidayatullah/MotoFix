# MotoFix Documentation

> Status: Approved for implementation
> Audience: Maintainers, contributors, and technical reviewers

MotoFix is an open-source, self-hosted garage management system for independent
garages and multi-branch operations. This documentation set defines the product,
architecture, security model, data model, operating procedures, and delivery
plan as the approved implementation baseline.

## Documentation conventions

- Developer documentation is written in English.
- Code, identifiers, comments, commit messages, and database names are in English.
- The user interface supports English (`en`) and Indonesian (`id`).
- RFC 2119 keywords (`MUST`, `SHOULD`, `MAY`) carry their standard meaning.
- Planning documents use phases and exit criteria, not calendar dates.
- Decisions that affect architecture or long-term maintenance are recorded as ADRs.
- Diagrams use Mermaid so they render directly in the repository.

## Document map

| Document                                                  | Purpose                                                       | Primary audience             |
| --------------------------------------------------------- | ------------------------------------------------------------- | ---------------------------- |
| [01 Project Charter](01-project-charter.md)               | Problem, vision, goals, scope, success criteria               | Everyone                     |
| [02 Product Requirements](02-prd.md)                      | Functional and non-functional requirements                    | Product, engineering, QA     |
| [03 Architecture](03-architecture.md)                     | System structure, request lifecycle, components               | Engineering                  |
| [04 Data Model](04-data-model.md)                         | ERD, tables, constraints, indexes                             | Engineering, QA              |
| [05 Security](05-security.md)                             | Threat model, authentication, authorization, controls         | Engineering, operators       |
| [06 UI and UX](06-ui-ux.md)                               | Sitemap, flows, i18n, accessibility                           | Product, design, engineering |
| [DESIGN](../DESIGN.md)                                    | Visual design system, components, page patterns, print design | Product, design, engineering |
| [07 Multi-Branch](07-multi-branch.md)                     | Organization model, branch access, reporting                  | Product, engineering         |
| [08 Self-Hosted Operations](08-self-hosted-operations.md) | Install, upgrade, backup, restore, diagnostics                | Operators, engineering       |
| [09 Finance](09-finance.md)                               | Money, tax, discounts, payments, invoices                     | Product, engineering         |
| [10 Hardware Integration](10-hardware-integration.md)     | Thermal printing and barcode scanning                         | Engineering, support         |
| [11 Testing and QA](11-testing-qa.md)                     | Test strategy, security matrix, UAT                           | Engineering, QA              |
| [12 Contributing](12-contributing.md)                     | Workflow, standards, reviews, releases                        | Contributors                 |
| [13 Roadmap](13-roadmap.md)                               | Phases, dependencies, exit criteria, risks                    | Everyone                     |
| [14 Backlog](14-backlog.md)                               | Epics, stories, priorities, acceptance criteria               | Product, engineering         |

## Architecture decision records

| ADR                                                                | Decision                                           |
| ------------------------------------------------------------------ | -------------------------------------------------- |
| [ADR-0001](decisions/ADR-0001-architecture.md)                     | Custom MVC with focused components                 |
| [ADR-0002](decisions/ADR-0002-single-organization-multi-branch.md) | One organization per installation, many branches   |
| [ADR-0003](decisions/ADR-0003-branch-authorization.md)             | Role-based access with branch context              |
| [ADR-0004](decisions/ADR-0004-money-tax.md)                        | Integer money, configurable tax, invoice numbering |
| [ADR-0005](decisions/ADR-0005-self-hosted-distribution.md)         | Self-hosted distribution and release model         |
| [ADR-0006](decisions/ADR-0006-backup-restore.md)                   | Local backups with documented offsite copies       |
| [ADR-0007](decisions/ADR-0007-caddy.md)                            | Caddy as the web server and TLS terminator         |
| [ADR-0008](decisions/ADR-0008-hardware-integration.md)             | Browser printing and HID scanning for the pilot    |
| [ADR-0009](decisions/ADR-0009-identifier-strategy.md)              | BIGINT internal IDs with ULID public IDs           |

## Repository policies

| Policy                           | Document                                 |
| -------------------------------- | ---------------------------------------- |
| Project overview and quick start | [README](../README.md)                   |
| Contribution workflow            | [CONTRIBUTING](../CONTRIBUTING.md)       |
| AI agent instructions            | [AGENTS](../AGENTS.md)                   |
| Security reporting               | [SECURITY](../SECURITY.md)               |
| Community behavior               | [CODE_OF_CONDUCT](../CODE_OF_CONDUCT.md) |
| Changes                          | [CHANGELOG](../CHANGELOG.md)             |
| License                          | [LICENSE](../LICENSE)                    |

## Product boundaries

MotoFix is self-hosted software. Each installation serves exactly one
organization. The organization may operate multiple branches, and all branches
access a single central server.

The system does not include:

- offline-first operation or branch-to-branch synchronization;
- a mobile application;
- a full accounting suite.

These boundaries are revisited only through a new ADR.

## Release naming

- **Pilot**: the first usable release for demo and pilot validation.
- **v1.0.0**: the first release intended for production use by independent garages.
- **Post-v1.0.0**: enhancements that are explicitly outside the v1.0.0 scope.

Calendar dates are intentionally excluded from planning documents. Progress is
measured by the exit criteria of each phase.

## Glossary

| Term            | Meaning                                                      |
| --------------- | ------------------------------------------------------------ |
| Organization    | The garage business that owns this installation              |
| Branch          | A physical garage location within the organization           |
| Branch context  | The active branch selected for the current request           |
| Work order      | A service job for a customer vehicle                         |
| Transaction     | The financial record created from completed work             |
| Invoice         | The human-readable financial document for a transaction      |
| Public ID       | Immutable ULID used in external URLs instead of internal IDs |
| Mobile mechanic | Responsive web flow for mechanics on Android Chrome          |
| Stock movement  | An immutable record of inventory change                      |
| Import run      | A tracked CSV import with validation results                 |
| Support bundle  | Redacted diagnostics collected for troubleshooting           |

## How to use this documentation

1. Read the charter and PRD first.
2. Use the architecture and data model as the implementation reference.
3. Apply the security, multi-branch, finance, and operations rules to every change.
4. Record new decisions as ADRs and update the relevant documents.
5. Changes that alter approved scope or architecture require an explicit decision
   before implementation.

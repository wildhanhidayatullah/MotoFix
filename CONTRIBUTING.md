# Contributing

Thanks for considering a contribution to MotoFix.

## Read first

- [Code of Conduct](CODE_OF_CONDUCT.md)
- [Security Policy](SECURITY.md)
- [Contributing guide](docs/12-contributing.md)
- [Testing and QA](docs/11-testing-qa.md)
- [Architecture](docs/03-architecture.md)

## Quick rules

- Report security issues privately, never in a public issue.
- Discuss large changes before implementing them.
- Keep pull requests focused.
- Add tests for behavior changes.
- Update documentation and translations.
- Do not commit secrets, credentials, or local environment files.
- Follow PSR-12 and the project architecture rules.

## Workflow

1. Open or find an issue or backlog item.
2. Create a short-lived branch.
3. Implement the change with tests.
4. Run formatting, static analysis, and tests.
5. Open a pull request using the template.
6. Address review feedback.
7. Merge after approval and green CI.

## Commit messages

Use Conventional Commits:

```text
feat: add branch context middleware
fix: prevent negative stock on checkout
docs: clarify backup retention
test: cover cross-branch transaction access
refactor: extract invoice number service
```

## Pull requests

Include:

- the problem and approach;
- related issue or backlog ID;
- user-visible impact;
- screenshots for UI changes;
- migration notes for schema changes;
- test evidence;
- security considerations for auth, money, stock, and imports.

## Development setup

Exact commands will be documented when the foundation is implemented.
The target stack is PHP 8.4, MariaDB, and Docker. Frontend assets (Bootstrap,
Bootstrap Icons, Chart.js, Inter) are vendored under `public/assets/vendor` and
require no build step.

## License

By contributing, you agree that your contribution is licensed under the MIT
license used by this project.

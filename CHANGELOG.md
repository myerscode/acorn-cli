# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Calendar Versioning](https://calver.org/).

## [Unreleased]

### Changed
- `make:event`: the duplicate `--event` option is renamed to `--alias` (the optional dispatch name) and the mislabelled `--description` is removed.
- `NewAppCommand` exposes a testable `createProjectCommand()` argv builder and an overridable `makeProcess()` factory.
- The `hello` example command is hidden from the published command list.
- Documentation now covers every `make:*` command and its options, plus a README quick-start.

### Fixed
- `NewAppCommand` no longer builds a shell command string from user input; it runs `composer create-project` with array arguments and removes an existing directory in PHP (behind a confirmation) instead of `rm -rf`. The project name and `--from` version are validated.
- `make:*` honours `--force` correctly (it previously overwrote existing files unconditionally) and returns a non-zero exit code when refusing to overwrite.
- `make:listener` renders `--events` as a validated `::class` reference or a quoted string, preventing code injection into the generated listener; the listener stub now matches the framework contract (`handle()` + `listensFor()`).

### Security
- Closed a shell-injection vector in `new` and an unconditional-overwrite data-loss bug in the generators.

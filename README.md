[![Latest Stable Version](https://poser.pugx.org/myerscode/acorn-cli/v/stable)](https://packagist.org/packages/myerscode/acorn-cli)
[![Total Downloads](https://poser.pugx.org/myerscode/acorn-cli/downloads)](https://packagist.org/packages/myerscode/acorn-cli)
[![PHP Version Require](http://poser.pugx.org/myerscode/acorn-cli/require/php)](https://packagist.org/packages/myerscode/acorn-cli)
[![License](https://poser.pugx.org/myerscode/acorn-cli/license)](https://github.com/myerscode/acorn-cli/blob/main/LICENSE)
[![Tests](https://github.com/myerscode/acorn-cli/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/myerscode/acorn-cli/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/myerscode/acorn-cli/graph/badge.svg)](https://codecov.io/gh/myerscode/acorn-cli)

# Acorn CLI

> A CLI tool for working with Acorn apps

Use this tool to create new Acorn apps or scaffold resources (commands, events,
listeners, providers) inside an existing one.

## Requirements

- PHP 8.5 or higher

## Quick start

```bash
# create a new app
acorn-cli new my-app

# scaffold resources inside it
cd my-app
acorn-cli make:command send-report --description="Send the daily report"
acorn-cli make:event report-sent
acorn-cli make:listener notify-admin --events=ReportSentEvent::class
acorn-cli make:provider reporting
```

See the [commands reference](./docs/commands.md) for every command and option.

## [Commands](./docs/commands.md)

View the list of all available commands [here](./docs/commands.md)

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/myerscode/acorn-cli/issues).

## Contributing

Contributions are welcome! Please see [contributing.md](https://github.com/myerscode/docs/blob/master/contributing.md) for guidelines on how to contribute to this project.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

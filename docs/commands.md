# Commands

The Acorn CLI creates new apps and scaffolds resources inside an existing one.
All generator commands write a file into the matching `app/` directory and
accept `--force` to overwrite an existing file.

> The resource `name` is normalised to a PascalCase class name (with the type
> suffix appended), so `make:command send-report` produces `SendReportCommand`.
> Names cannot traverse outside the target directory.

## `new` — create a new Acorn app

```bash
acorn-cli new <project> [location]
```

| Parameter  | Required | Description                                           | Default      |
|------------|----------|-------------------------------------------------------|--------------|
| `project`  | yes      | Name of the project (letters, numbers, `.`, `_`, `-`) | n/a          |
| `location` | no       | Where to create the project                           | `./<project>`|
| `--from`   | no       | Which tagged version to create from                   | `dev-main`   |
| `--force`  | no       | Remove an existing target directory first (prompts)   | `false`      |

`new` runs `composer create-project` with array arguments (never a shell
string), and with `--force` it removes the existing directory in PHP behind a
confirmation prompt — there is no `rm -rf`.

## `make:command` — create a command

```bash
acorn-cli make:command <name>
```

| Option          | Description                                   | Default        |
|-----------------|-----------------------------------------------|----------------|
| `-c, --command` | The name used to invoke the command           | lowercase name |
| `-d, --description` | The command's description                 | empty          |
| `-f, --force`   | Overwrite an existing file                    | `false`        |

## `make:event` — create an event

```bash
acorn-cli make:event <name>
```

| Option        | Description                                              | Default |
|---------------|----------------------------------------------------------|---------|
| `-a, --alias` | Optional dispatch name/alias (defaults to the class name)| empty   |
| `-f, --force` | Overwrite an existing file                               | `false` |

## `make:listener` — create a listener

```bash
acorn-cli make:listener <name> --events=SomeEvent::class
```

| Option         | Description                                             | Default |
|----------------|---------------------------------------------------------|---------|
| `-e, --events` | Event(s) the listener listens for (a `::class` reference or an event name) | empty |
| `-f, --force`  | Overwrite an existing file                              | `false` |

The `--events` value is rendered as a validated `::class` reference or a quoted
string literal, so it can never inject code into the generated listener.

## `make:provider` — create a service provider

```bash
acorn-cli make:provider <name>
```

| Option        | Description                | Default |
|---------------|----------------------------|---------|
| `-f, --force` | Overwrite an existing file | `false` |

# Commands

### New Project `new`

Use this command when you want to create a new Acorn app!

```bash
acorn-cli new <project> [location]
```

| Parameter | Required | Description                                           | Default    |
|-----------|----------|-------------------------------------------------------|------------|
| project   | yes      | Name of the project                                   | n/a        |
| location  | no       | Where to create the project                           | ./$project |
| --from    | no       | Which tagged version to create from                   | dev-main   |
| --force   | no       | Force creation (removes directory with existing name) | false      |

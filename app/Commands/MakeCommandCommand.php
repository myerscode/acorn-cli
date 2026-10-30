<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Override;

class MakeCommandCommand extends GeneratorCommand
{
    #[Override]
    protected string $configLocationKey = 'executing.dir.commands';

    #[Override]
    protected string $description = 'Create a new command.';

    #[Override]
    protected string $resourceType = 'command';

    #[Override]
    protected string $signature = 'make:command
                                   {name : The name of the command}
                                   {--c|command= : What will call the command (defaults to the name)}
                                   {--d|description= : The description of the command}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    /** @return array<string, string> */
    protected function placeholderMap(): array
    {
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        return [
            'StubCommand' => (string) TextService::make(strtolower($name) . 'Command')->toPascalCase()->value(),
            'CommandName' => $this->option('command') ?? strtolower($name),
            'CommandDescription' => $this->option('description') ?? '',
        ];
    }
}

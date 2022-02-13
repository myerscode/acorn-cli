<?php

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;

class MakeCommandCommand extends GeneratorCommand
{
    protected string $signature = 'make:command 
                                   {name : The name of the command}
                                   {--c|command= : What will call the command (defaults to the name)}
                                   {--d|description= : The description of the command}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    protected string $description = 'Create a new command.';

    protected string $resourceType = 'command';

    protected string $configLocationKey = 'executing.dir.commands';

    protected function placeHolderMap(): array
    {
        return [
            'StubCommand' => TextService::make(strtolower($this->argument('name')).'Command')->toPascalCase()->value(),
            'CommandName' => $this->option('command') ?? strtolower($this->argument('name')),
            'CommandDescription' => $this->option('description') ?? '',
        ];
    }
}

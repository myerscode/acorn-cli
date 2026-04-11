<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Override;

class MakeEventCommand extends GeneratorCommand
{
    #[Override]
    protected string $configLocationKey = 'executing.dir.events';

    #[Override]
    protected string $description = 'Create a new event.';

    #[Override]
    protected string $resourceType = 'event';

    #[Override]
    protected string $signature = 'make:event
                                   {name : The name of the event}
                                   {--e|event= : The name of the event}
                                   {--d|description= : The description of the command}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    /** @return array<string, string> */
    protected function placeholderMap(): array
    {
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        return [
            'StubEvent' => (string) TextService::make($name)->toPascalCase()->append('Event')->value(),
            'EventName' => (string) ($this->option('event') ?? ''),
        ];
    }
}

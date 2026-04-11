<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Override;

class MakeListenerCommand extends GeneratorCommand
{
    #[Override]
    protected string $configLocationKey = 'executing.dir.listeners';

    #[Override]
    protected string $description = 'Create a new listener.';

    #[Override]
    protected string $resourceType = 'listener';

    #[Override]
    protected string $signature = 'make:listener
                                   {name : The name of the listener}
                                   {--e|events= : The name of the events to listen for}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    /** @return array<string, string> */
    protected function placeholderMap(): array
    {
        $events = (string) ($this->option('events') ?? '""');
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        if (!TextService::make($events)->endsWith(['::class', '""'])) {
            $events = '"' . $events . '"';
        }

        return [
            'StubListener' => (string) TextService::make($name)->toPascalCase()->append('Listener')->value(),
            'ListensFor' => $events,
        ];
    }
}

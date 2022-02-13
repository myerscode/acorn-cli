<?php

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;

class MakeListenerCommand extends GeneratorCommand
{
    protected string $signature = 'make:listener 
                                   {name : The name of the listener}
                                   {--e|events= : The name of the events to listen for}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    protected string $description = 'Create a new listener.';

    protected string $resourceType = 'listener';

    protected string $configLocationKey = 'executing.dir.listeners';

    protected function placeHolderMap(): array
    {
        $events = $this->option('events', '""');

        if (!TextService::make($events)->endsWith(['::class', '""'])) {
            $events = '"'.$events.'"';
        }

        return [
            'StubListener' => TextService::make($this->argument('name'))->toPascalCase()->append('Listener')->value(),
            'ListensFor' => $events,
        ];
    }
}

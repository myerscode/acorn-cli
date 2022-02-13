<?php

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Symfony\Component\Console\Input\InputOption;

class MakeEventCommand extends GeneratorCommand
{
    protected string $signature = 'make:event 
                                   {name : The name of the event}
                                   {--e|event= : The name of the event}
                                   {--d|description= : The description of the command}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    protected string $description = 'Create a new event.';

    protected string $resourceType = 'event';

    protected string $configLocationKey = 'executing.dir.events';

    protected function placeHolderMap(): array
    {
        return [
            'StubEvent' => TextService::make($this->argument('name'))->toPascalCase()->append('Event')->value(),
            'EventName' => $this->option('event', ''),
        ];
    }
}

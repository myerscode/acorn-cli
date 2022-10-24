<?php

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Symfony\Component\Console\Input\InputOption;

class MakeProviderCommand extends GeneratorCommand
{
    protected string $signature = 'make:provider 
                                   {name : The name of the service provider}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    protected string $description = 'Create a new event.';

    protected string $resourceType = 'provider';

    protected string $configLocationKey = 'executing.dir.providers';

    protected function placeHolderMap(): array
    {
        return [
            'StubProvider' => TextService::make($this->argument('name'))->toPascalCase()->append('Provider')->value(),
        ];
    }
}

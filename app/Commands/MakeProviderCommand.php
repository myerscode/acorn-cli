<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Utilities\Strings\Utility as TextService;
use Override;

class MakeProviderCommand extends GeneratorCommand
{
    #[Override]
    protected string $configLocationKey = 'executing.dir.providers';

    #[Override]
    protected string $description = 'Create a new service provider.';

    #[Override]
    protected string $resourceType = 'provider';

    #[Override]
    protected string $signature = 'make:provider
                                   {name : The name of the service provider}
                                   {--f|force : Overwrite existing file when creating a new one}
                                   ';

    /** @return array<string, string> */
    protected function placeholderMap(): array
    {
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        return [
            'StubProvider' => (string) TextService::make($name)->toPascalCase()->append('Provider')->value(),
        ];
    }
}

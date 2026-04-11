<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Templex\Templex;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as TextService;

use function Myerscode\Acorn\Foundation\config;

abstract class GeneratorCommand extends Command
{
    /** @var array<string, mixed> */
    protected array $commandArguments = [];

    protected string $configLocationKey;

    protected string $resourceType;

    /** @return array<string, string> */
    abstract protected function placeholderMap(): array;

    public function handle(): void
    {
        $resource = strtolower($this->resourceType);

        $utility = TextService::make(is_string($this->argument('name')) ? $this->argument('name') : '')
            ->toPascalCase()
            ->append((string) TextService::make($resource)->toPascalCase());

        $fileName = $utility->append('.php')->value();

        $newCommandFile = FileService::make($this->storeIn() . '/' . $fileName);

        if ($this->hasOption('force') || !$newCommandFile->exists()) {
            $placeholders = $this->placeholderMap();
            $templex = new Templex($this->stubDirectory());
            $newCommandFile->touch()->setContent($templex->render($resource, $placeholders));
            $this->info('Created ' . $utility);
        } else {
            $this->error($utility . ' already exists.');
        }
    }

    public function stubDirectory(): string
    {
        $basePath = config('base');

        return $basePath . '/Resources/Stubs/';
    }

    protected function storeIn(): string
    {
        return (string) config($this->configLocationKey);
    }
}

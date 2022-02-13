<?php

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Templex\Templex;
use Myerscode\Utilities\Files\Utility as FileService;
use Myerscode\Utilities\Strings\Utility as TextService;

use function Myerscode\Acorn\Foundation\config;

abstract class GeneratorCommand extends Command
{
    protected string $resourceType;

    protected string $configLocationKey;

    protected array $commandArguments = [];

    public function handle(): void
    {
        $resource = strtolower($this->resourceType);

        $className = TextService::make($this->argument('name'))
            ->toPascalCase()
            ->append(TextService::make($resource)->toPascalCase());

        $fileName = $className->append('.php')->value();

        $newCommandFile = FileService::make($this->storeIn().'/'.$fileName);

        if ($this->hasOption('force') || !$newCommandFile->exists() && !$this->hasOption('force')) {
            $placeholders = $this->placeHolderMap();
            $templex = new Templex($this->stubDirectory());
            $newCommandFile->touch()->setContent($templex->render($resource, $placeholders));
            $this->info("Created $className");
        } else {
            $this->error("$className already exists.");
        }
    }

    /**
     * @return string
     */
    public function stubDirectory(): string
    {
        $basePath = config('base');

        return $basePath.'/Resources/Stubs/';
    }

    abstract protected function placeholderMap(): array;

    protected function storeIn(): string
    {
        return config($this->configLocationKey);
    }

}

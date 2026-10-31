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
        $name = is_string($this->argument('name')) ? $this->argument('name') : '';

        return [
            'StubListener' => (string) TextService::make($name)->toPascalCase()->append('Listener')->value(),
            'ListensFor' => $this->listensForLiteral($this->option('events')),
        ];
    }

    /**
     * Build the PHP literal written into the generated listener's
     * $listensFor. The --events value is rendered verbatim into the stub, so
     * it must be emitted as a safe literal, never interpolated as raw PHP: a
     * fully-qualified class reference becomes a `::class` constant, anything
     * else is exported as a quoted string. This prevents a value such as
     * `[]; system($x); //` from being written as executable code.
     */
    private function listensForLiteral(?string $events): string
    {
        $events = $events ?? '';

        if ($events === '') {
            return '[]';
        }

        // A bare fully-qualified class name (optionally already suffixed with
        // ::class) is emitted as a ::class constant.
        $class = preg_replace('/::class$/', '', $events);
        if (is_string($class) && preg_match('/^\\\\?[A-Za-z_][A-Za-z0-9_]*(\\\\[A-Za-z_][A-Za-z0-9_]*)*$/', $class) === 1) {
            return $class . '::class';
        }

        // Otherwise treat it as a plain event-name string, safely quoted.
        return var_export($events, true);
    }
}

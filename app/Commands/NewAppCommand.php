<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Override;
use Symfony\Component\Process\Process;

class NewAppCommand extends Command
{
    #[Override]
    protected string $description = 'Create a new Acorn application.';

    #[Override]
    protected string $signature = 'new {project : Name of the project}
                                       {location= : Where to create the project}
                                       {--from=dev-main : Which tagged version to create from}
                                       {--force : Force the installation by removing existing directory}';

    public function handle(): void
    {
        $name = is_string($this->argument('project')) ? $this->argument('project') : '';
        $location = $this->argument('location');
        $directory = is_string($location) ? $location : (string) getcwd() . '/' . $name;

        $this->info('Creating project in ' . $directory);

        $version = (string) ($this->option('from') ?? 'dev-main');

        $commands = array_filter([
            ($directory !== '.' && $this->option('force')) ? $this->forceCommand($directory) : '',
            sprintf('composer create-project myerscode/acorn "%s" "%s" --prefer-dist --remove-vcs', $directory, $version),
        ]);

        $process = Process::fromShellCommandline(implode(' && ', $commands), null, [], null, null);

        $output = $this->output;

        $process->run(function ($type, string $line) use ($output): void {
            $output->writeln($line);
        });
    }

    protected function forceCommand(string $directory): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return sprintf('rd /s /q "%s"', $directory);
        }

        return sprintf('rm -rf "%s"', $directory);
    }
}

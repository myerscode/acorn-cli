<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Utilities\Files\Utility as FileService;
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

    public function handle(): int
    {
        $name = is_string($this->argument('project')) ? $this->argument('project') : '';

        if (!$this->isValidProjectName($name)) {
            $this->error('Invalid project name. Use letters, numbers, dots, underscores and hyphens only.');

            return 1;
        }

        $location = $this->argument('location');
        $directory = is_string($location) && $location !== '' ? $location : getcwd() . DIRECTORY_SEPARATOR . $name;

        // The version is passed as a literal argv element below, so it is
        // never shell-interpreted; validate it anyway to fail fast on typos.
        $version = $this->option('from') ?? 'dev-main';
        if (!$this->isValidVersion($version)) {
            $this->error('Invalid --from version constraint.');

            return 1;
        }

        if (FileService::make($directory)->exists()) {
            if (!$this->flag('force')) {
                $this->error(sprintf('Directory "%s" already exists. Pass --force to overwrite it.', $directory));

                return 1;
            }

            if (!$this->confirm(sprintf('This will permanently delete "%s" and everything in it. Continue?', $directory), false)) {
                $this->info('Aborted.');

                return 1;
            }

            // Remove in PHP rather than shelling out to rm -rf / rd.
            FileService::make($directory)->delete();
        }

        $this->info('Creating project in ' . $directory);

        // Array argv: arguments are passed directly to the process with no
        // shell interpretation, so a project name or version containing shell
        // metacharacters cannot inject commands.
        $process = new Process([
            'composer',
            'create-project',
            'myerscode/acorn:' . $version,
            $directory,
            '--prefer-dist',
            '--remove-vcs',
        ], null, null, null, null);

        $output = $this->output;
        $process->run(function ($type, string $line) use ($output): void {
            $output->writeln($line);
        });

        return $process->getExitCode() ?? 1;
    }

    /**
     * A project name must be a single safe path segment: no separators, no
     * traversal, no leading dot, limited to a conservative character set.
     */
    private function isValidProjectName(string $name): bool
    {
        return $name !== '' && preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]*$/', $name) === 1;
    }

    /**
     * Accept a tagged version, a branch alias (dev-*), or a semver-ish
     * constraint; reject anything with shell metacharacters or whitespace.
     */
    private function isValidVersion(string $version): bool
    {
        return preg_match('/^[A-Za-z0-9._*^~>=<|\- ]+$/', $version) === 1 && trim($version) !== '';
    }
}

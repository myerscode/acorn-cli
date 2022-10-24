<?php

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Symfony\Component\Process\Process;

class NewAppCommand extends Command
{
    protected string $signature = 'new {project : Name of the project} 
                                       {location= : Where to create the project} 
                                       {--from=dev-main : Which tagged version to create from} 
                                       {--force : Force the installation by removing existing directory}';

    protected string $description = 'Create a new Acorn application.';

    public function handle(): void
    {

        $name = $this->argument('project');

        $location = $this->argument('location');

        $directory = is_null($location) ? getcwd() . '/' . $name  : $location;

        $this->info("Creating project in $directory");

        $version = $this->option('from');

        $commands = array_filter([
            ($directory != '.' && $this->option('force')) ? $this->forceCommand($directory) : '',
            "composer create-project myerscode/acorn \"$directory\" \"$version\" --prefer-dist --remove-vcs",
        ]);

        $process = Process::fromShellCommandline(implode(' && ', $commands), null, [ ], null, null);

        $output = $this->output;

        $process->run(function ($type, $line) use ($output) {
            $output->writeln($line);
        });
    }

    /**
     * Get command for forcing creation of new project
     *
     * @param  string  $directory
     *
     * @return string
     */
    protected function forceCommand(string $directory): string
    {
        if (PHP_OS_FAMILY == 'Windows') {
            return "rd /s /q \"$directory\"";
        } else {
            return "rm -rf \"$directory\"";
        }
    }

}

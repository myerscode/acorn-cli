<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Myerscode\Acorn\Framework\Console\Display\DisplayOutputInterface;
use Override;

class HelloCommand extends Command
{
    #[Override]
    protected string $description = 'Say hello';

    #[Override]
    protected string $signature = 'hello';

    public function handle(): int
    {
        // Command::$output is typed DisplayOutputInterface|OutputInterface, but
        // at runtime it is always the styled DisplayOutputInterface. Narrow it
        // so the styled text() helper type-checks (phpstan level).
        $output = $this->output;
        if ($output instanceof DisplayOutputInterface) {
            $output->text('Hello ' . get_current_user());
        }

        return 0;
    }
}

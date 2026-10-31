<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Override;

class HelloCommand extends Command
{
    #[Override]
    protected string $description = 'Say hello';

    // Example command, kept out of the published command list for 1.0.
    protected bool $hide = true;

    #[Override]
    protected string $signature = 'hello';

    public function handle(): int
    {
        $this->output->text('Hello ' . get_current_user());

        return 0;
    }
}

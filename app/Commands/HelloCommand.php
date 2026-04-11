<?php

declare(strict_types=1);

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;
use Override;

class HelloCommand extends Command
{
    #[Override]
    protected string $description = 'Say hello';

    #[Override]
    protected string $signature = 'hello';

    public function handle(): void
    {
        $this->output->text('Hello ' . get_current_user());
    }
}

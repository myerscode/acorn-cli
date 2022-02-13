<?php

namespace App\Commands;

use Myerscode\Acorn\Framework\Console\Command;

class HelloCommand extends Command
{

    protected string $signature = 'hello';

    protected string $description = 'Say hello';

    public function handle(): void
    {
        $this->output->text("Hello " . get_current_user());
    }
}

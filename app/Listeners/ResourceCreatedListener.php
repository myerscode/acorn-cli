<?php

declare(strict_types=1);

namespace App\Listeners;

use Myerscode\Acorn\Framework\Events\Listener;
use Override;

class ResourceCreatedListener extends Listener
{
    public function __invoke(): void
    {
        //
    }

    #[Override]
    public function listensFor(): array|string
    {
        return '';
    }
}

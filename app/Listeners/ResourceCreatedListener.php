<?php

declare(strict_types=1);

namespace App\Listeners;

use Myerscode\Acorn\Framework\Events\Listener;
use Override;

class ResourceCreatedListener extends Listener
{
    /**
     * @var string[]|string
     */
    #[Override]
    protected array|string $listensFor = '';

    public function __invoke(): void
    {
        //
    }
}

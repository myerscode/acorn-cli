<?php

namespace App\Listeners;

use Myerscode\Acorn\Framework\Events\Listener;

class ResourceCreatedListener extends Listener
{
    /**
     * @var string[]|string
     */
    protected array|string $listensFor = "";

    public function __invoke(): void
    {
        //
    }
}

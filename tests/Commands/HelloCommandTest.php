<?php

declare(strict_types=1);

namespace Tests\Commands;

use App\Commands\HelloCommand;
use Myerscode\Acorn\Testing\Interactions\InteractsWithCommands;
use Tests\BaseTestCase;

final class HelloCommandTest extends BaseTestCase
{
    use InteractsWithCommands;

    public function testHelloCommand(): void
    {
        $response = $this->call(HelloCommand::class);
        $this->assertSame('Hello ' . get_current_user(), $response);

        $response = $this->call('hello');
        $this->assertSame('Hello ' . get_current_user(), $response);
    }
}

<?php

namespace Tests\Commands;

use App\Commands\HelloCommand;
use Myerscode\Acorn\Testing\Interactions\InteractsWithCommands;
use Tests\BaseTestCase;

class HelloCommandTest extends BaseTestCase
{
    use InteractsWithCommands;

    public function testHelloCommand(): void
    {
        $response = $this->call(HelloCommand::class);
        $this->assertEquals("Hello " . get_current_user(), $response);

        $response = $this->call('hello');
        $this->assertEquals("Hello " . get_current_user(), $response);
    }
}

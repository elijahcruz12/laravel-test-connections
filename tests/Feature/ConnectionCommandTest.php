<?php

namespace Elijahcruz\LaravelTestConnections\Tests\Feature;

use Elijahcruz\LaravelTestConnections\Tests\TestCase;

class ConnectionCommandTest extends TestCase
{
    public function test_command()
    {
        $command = $this->artisan('test:connections', );

        $command->assertExitCode(0);
    }
}
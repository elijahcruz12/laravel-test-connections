<?php

namespace Elijahcruz\LaravelTestConnections\Tests;

use Elijahcruz\LaravelTestConnections\LaravelTestConnectionsServiceProvider;
use \Orchestra\Testbench\TestCase as BaseTestCase;
class TestCase extends BaseTestCase {

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelTestConnectionsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

}
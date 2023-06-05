<?php

namespace Elijahcruz\LaravelTestConnections\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestConnectionsPackage extends Command
{
    protected $signature = 'test:connections
        {--group= : The group of connections to test}
        {--log : Log to your logs if any connection fails}';

    protected $description = 'Test your database, redis, and cache connections';

    public function handle()
    {
        $group = $this->option('group') ?? config('test-connections.default');

        if(!is_array($group)){
            $this->error('The group must be an array');
            return Command::FAILURE;
        }

        // Array can only contain 'database', 'redis', and 'cache' as values
        $group = array_filter($group, function($value){
            return in_array($value, ['database', 'redis', 'cache']);
        });

        if(empty($group)){
            $this->error('The group must contain at least one of the following values: database, redis, cache');
            return Command::FAILURE;
        }

        $this->info('Testing connections...');

        if(array_key_exists('database', $group)){
            $dbTest = $this->testDatabaseConnection();
        }

        if(array_key_exists('redis', $group)){
            $redisTest = $this->testRedisConnection();
        }
    }

    /**
     * Checks the Database connection
     *
     * @return bool
     */
    private function testDatabaseConnection(): bool
    {
        return DB::connection()->getDatabaseName() ? true : false;
    }

    private function testRedisConnection()
    {
        if(Redis::command('ping')){
            return true;
        }
    }
}
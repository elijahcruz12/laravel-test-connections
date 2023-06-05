<?php

namespace Elijahcruz\LaravelTestConnections\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestConnectionsCommand extends Command
{
    protected $signature = 'test:connections
        {--group= : The group of connections to test}
        {--log : Log to your logs if any connection fails}';

    protected $description = 'Test your database, redis, and cache connections';

    public function handle()
    {
        $groupName = $this->option('group') ?? config('test-connections.default');

        if(!config('test-connections.connections.'.$groupName)){
            $this->error('The group '.$groupName.' does not exist');
            return Command::FAILURE;
        }

        $group = config('test-connections.connections.'.$groupName);

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
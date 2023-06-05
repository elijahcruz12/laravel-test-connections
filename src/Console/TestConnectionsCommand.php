<?php

namespace Elijahcruz\LaravelTestConnections\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class TestConnectionsCommand extends Command
{
    protected $signature = 'test:connections
        {--group= : The group of connections to test}
        {--log : Log to your logs if any connection fails}
        {--fail-fast : Stop testing connections after the first failure}';

    protected $description = 'Test your database, redis, and cache connections';

    /**
     * Tests the connections
     *
     * @return int
     */
    public function handle(): int
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

        foreach ($group as $item){
            $this->info('Prepping '.$item.' connection...');
        }

        if(array_key_exists('database', $group)){
            $dbTest = $this->testDatabaseConnection();

            if($dbTest){
                $this->info('Database connection successful');
            }
            else{
                if($this->option('log') ?? config('test-connections.log', false)){
                    Log::error('Database connection failed');
                }
                $this->error('Database connection failed');
                if($this->option('fail-fast')){
                    return Command::FAILURE;
                }
            }
        }

        if(array_key_exists('redis', $group)){
            $redisTest = $this->testRedisConnection();

            if($redisTest){
                $this->info('Redis connection successful');
            }
            else{
                if($this->option('log') ?? config('test-connections.log', false)){
                    Log::error('Redis connection failed');
                }
                $this->error('Redis connection failed');
                if($this->option('fail-fast')){
                    return Command::FAILURE;
                }
            }
        }

        if(array_key_exists('cache', $group)){
            $cacheTest = $this->testCacheConnection();

            if($cacheTest){
                $this->info('Cache connection successful');
            }
            else{
                if($this->option('log') ?? config('test-connections.log', false)){
                    Log::error('Cache connection failed');
                }
                $this->error('Cache connection failed');
                if($this->option('fail-fast')){
                    return Command::FAILURE;
                }
            }
        }

        $this->info('All connections tested.');

        return Command::SUCCESS;
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

    /**
     * Checks the Redis connection
     *
     * @return bool
     */
    private function testRedisConnection(): bool
    {
        return Redis::command('PING') ? true : false;
    }

    /**
     * Tests the Cache connection
     *
     * @return bool
     */
    private function testCacheConnection(): bool
    {
        Cache::put('test-connections', true, 60);

        return (bool)Cache::get('test-connections', false);
    }
}
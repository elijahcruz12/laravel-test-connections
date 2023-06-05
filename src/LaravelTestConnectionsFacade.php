<?php

namespace Elijahcruz\LaravelTestConnections;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elijahcruz\LaravelTestConnections\Skeleton\SkeletonClass
 */
class LaravelTestConnectionsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-test-connections';
    }
}

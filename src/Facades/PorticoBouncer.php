<?php

namespace Weward\PorticoBouncer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Weward\PorticoBouncer\PorticoBouncer
 */
class PorticoBouncer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Weward\PorticoBouncer\PorticoBouncer::class;
    }
}

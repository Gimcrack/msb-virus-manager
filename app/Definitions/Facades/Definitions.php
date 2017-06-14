<?php

namespace App\Definitions\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DummyTarget
 */
class Definitions extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'definitions';
    }
}
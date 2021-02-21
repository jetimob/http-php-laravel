<?php

namespace Jetimob\Http\Facades;

use Illuminate\Support\Facades\Facade;

class Http extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jetimob.http';
    }
}

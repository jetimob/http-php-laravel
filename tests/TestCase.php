<?php

namespace Jetimob\Http\Tests;

use Jetimob\Http\HttpServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            HttpServiceProvider::class,
        ];
    }
}

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

    protected function assertArrayHasStructure($expected, $actual): void
    {
        foreach ($expected as $k => $v) {
            $this->assertArrayHasKey($k, $actual);

            if (is_array($v)) {
                $this->assertArrayHasStructure($v, $actual[$k]);
            } else {
                $this->assertSame($v, $actual[$k]);
            }
        }
    }
}

<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Traits\Serializable;

class JsonPlaceholderAddressGeo
{
    use Serializable;

    protected string $lat;
    protected string $lng;

    public function getLat(): string
    {
        return $this->lat;
    }

    public function getLng(): string
    {
        return $this->lng;
    }
}

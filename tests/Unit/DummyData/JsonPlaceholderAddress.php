<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Traits\Serializable;

class JsonPlaceholderAddress
{
    use Serializable;

    protected string $street;
    protected string $suite;
    protected string $city;
    protected string $zipcode;
    protected JsonPlaceholderAddressGeo $geo;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getSuite(): string
    {
        return $this->suite;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function getGeo(): JsonPlaceholderAddressGeo
    {
        return $this->geo;
    }
}

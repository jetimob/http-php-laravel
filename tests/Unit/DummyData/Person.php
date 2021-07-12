<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Traits\Serializable;

class Person
{
    use Serializable;

    protected string $id;
    protected string $name;
    protected ?Address $address = null;
    protected ?Person $related = null;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Person
     */
    public function setId(string $id): Person
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Person
     */
    public function setName(string $name): Person
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     * @return Person
     */
    public function setAddress(Address $address): Person
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getRelated(): ?Person
    {
        return $this->related;
    }

    /**
     * @param Person|null $related
     * @return Person
     */
    public function setRelated(?Person $related): Person
    {
        $this->related = $related;
        return $this;
    }
}

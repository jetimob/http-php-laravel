<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Traits\Serializable;

class People
{
    use Serializable;

    protected string $id;
    /** @var Person[] $people */
    protected array $people;

    public function peopleItemType(): string
    {
        return Person::class;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return People
     */
    public function setId(string $id): People
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Person[]
     */
    public function getPeople(): array
    {
        return $this->people;
    }

    /**
     * @param Person[] $people
     * @return People
     */
    public function setPeople(array $people): People
    {
        $this->people = $people;
        return $this;
    }
}

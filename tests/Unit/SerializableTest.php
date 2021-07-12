<?php

namespace Jetimob\Http\Tests\Unit;

use Faker\Factory;
use Jetimob\Http\Tests\TestCase;
use Jetimob\Http\Tests\Unit\DummyData\Address;
use Jetimob\Http\Tests\Unit\DummyData\People;
use Jetimob\Http\Tests\Unit\DummyData\Person;

class SerializableTest extends TestCase
{
    /** @test */
    public function serializedArrShouldContainSameValuesAsObject(): void
    {
        $people = new People();
        $faker = Factory::create();
        $peopleId = $faker->uuid;

        $p0 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name())
            ->setAddress(
                (new Address())
                    ->setCity($faker->city)
                    ->setState($faker->state)
            );

        $p1 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name());

        $peopleArr = [$p0, $p1];

        $people->setId($peopleId);
        $people->setPeople($peopleArr);

        $this->assertArrayHasStructure([
            'id' => $peopleId,
            'people' => [
                [
                    'id' => $p0->getId(),
                    'name' => $p0->getName(),
                    'address' => [
                        'city' => $p0->getAddress()->getCity(),
                        'state' => $p0->getAddress()->getState(),
                    ]
                ],
                [
                    'id' => $p1->getId(),
                    'name' => $p1->getName(),
                ]
            ],
        ], $people->toArray());
    }

    /** @test */
    public function nestedObjectsShouldSerialize(): void
    {
        $faker = Factory::create();

        $p0 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name);

        $p1 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name);

        $p2 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name);

        $p3 = (new Person())
            ->setId($faker->uuid)
            ->setName($faker->name);

        $p0->setRelated($p1);
        $p1->setRelated($p2);
        $p2->setRelated($p3);

        $this->assertArrayHasStructure([
            'id' => $p0->getId(),
            'name' => $p0->getName(),
            'related' => [
                'id' => $p1->getId(),
                'name' => $p1->getName(),
                'related' => [
                    'id' => $p2->getId(),
                    'name' => $p2->getName(),
                    'related' => [
                        'id' => $p3->getId(),
                        'name' => $p3->getName(),
                    ]
                ]
            ]
        ], $p0->toArray());
    }
}

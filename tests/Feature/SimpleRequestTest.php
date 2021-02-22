<?php

namespace Jetimob\Http\Tests\Feature;

use Jetimob\Http\Facades\Http;
use Jetimob\Http\Request;
use Jetimob\Http\Response;
use Jetimob\Http\Tests\TestCase;
use Jetimob\Http\Traits\Serializable;

class SimpleRequestTest extends TestCase
{
    /** @test */
    public function simpleResponseExpected(): void
    {
        $response = Http::send(new Request('GET', 'http://google.com'));
        self::assertNotEmpty($response);
    }

    /** @test */
    public function complexResponseWithArrayTyping(): void
    {
        /** @var JsonPlaceholderUsersResponse $response */
        $response = Http::sendExpectingResponseClass(
            new Request('get', 'https://jsonplaceholder.typicode.com/users'),
            JsonPlaceholderUsersResponse::class,
        );

        self::assertNotEmpty($response);
        self::assertInstanceOf(JsonPlaceholderUsersResponse::class, $response);

        $container = $response->getContainer();
        self::assertNotEmpty($container);

        $user = $container[0];
        self::assertNotEmpty($user);
        self::assertInstanceOf(JsonPlaceholderUser::class, $user);

        $address = $user->getAddress();
        self::assertNotEmpty($address);
        self::assertInstanceOf(JsonPlaceholderAddress::class, $address);

        $geoLocation = $address->getGeo();
        self::assertNotEmpty($geoLocation);
        self::assertInstanceOf(JsonPlaceholderAddressGeo::class, $geoLocation);
        self::assertNotEmpty($geoLocation->getLat());
        self::assertNotEmpty($geoLocation->getLng());
    }
}

class JsonPlaceholderUsersResponse extends Response
{
    protected array $container;

    protected function containerItemType(): string
    {
        return JsonPlaceholderUser::class;
    }

    public function getContainer(): array
    {
        return $this->container;
    }
}

class JsonPlaceholderUser
{
    use Serializable;

    protected int $id;
    protected string $name;
    protected string $username;
    protected string $email;
    protected JsonPlaceholderAddress $address;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): JsonPlaceholderAddress
    {
        return $this->address;
    }
}

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



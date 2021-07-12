<?php

namespace Jetimob\Http\Tests\Feature;

use Jetimob\Http\Facades\Http;
use Jetimob\Http\Request;
use Jetimob\Http\Tests\TestCase;
use Jetimob\Http\Tests\Unit\DummyData\JsonPlaceholderAddress;
use Jetimob\Http\Tests\Unit\DummyData\JsonPlaceholderAddressGeo;
use Jetimob\Http\Tests\Unit\DummyData\JsonPlaceholderUser;
use Jetimob\Http\Tests\Unit\DummyData\JsonPlaceholderUsersResponse;

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


<?php

namespace Jetimob\Http\Tests\Feature;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Jetimob\Http\Http;
use Jetimob\Http\Request;
use Jetimob\Http\Tests\TestCase;

class MockResponseTest extends TestCase
{
    /** @test
     */
    public function singleResponse(): void
    {
        $json = json_encode(['from_mock' => true], JSON_THROW_ON_ERROR);

        $http = new Http();
        $http->mockClientWithResponses([
            new Response(201, [], $json),
        ]);

        $res = $http->send(new Request('GET', 'https://foo.bar'));
        $responseBody = $res->getBody()->getContents();

        self::assertEquals(201, $res->getStatusCode());
        self::assertJson($responseBody);
        self::assertJsonStringEqualsJsonString($responseBody, $json);
    }

    /** @test
     */
    public function queueMultipleResponses(): void
    {
        $json = json_encode(['foo' => true], JSON_THROW_ON_ERROR);

        $http = new Http();
        $http->mockClientWithResponses([
            new Response(201, [], $json),
            new Response(400)
        ]);

        $res = $http->send(new Request('GET', 'https://foo.bar'));
        $responseBody = $res->getBody()->getContents();

        self::assertEquals(201, $res->getStatusCode());
        self::assertJson($responseBody);
        self::assertJsonStringEqualsJsonString($responseBody, $json);

        try {
            $http->send(new Request('GET', 'https://foo.bar'));
        } catch (ClientException $clientException) {
            self::assertEquals(400, $clientException->getCode());
        }
    }
}

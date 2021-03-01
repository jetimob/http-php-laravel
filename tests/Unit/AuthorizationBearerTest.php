<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Authorization\Bearer\BearerTokenResolverContract;
use Jetimob\Http\Http;
use Jetimob\Http\Middlewares\AuthorizationBearerRequestMiddleware;
use Jetimob\Http\Request;
use Jetimob\Http\Tests\TestCase;

class AuthorizationBearerTest extends TestCase
{
    protected Http $httpWithBearerString;
    protected Http $httpWithBearerClass;

    protected function setUp(): void
    {
        parent::setUp();
        $config = config('http');
        $config['guzzle']['middlewares'] = [
            AuthorizationBearerRequestMiddleware::class,
        ];
        $config['authorization_header_bearer_token'] = 'BEARER_TOKEN';
        $this->httpWithBearerString = new Http($config);

        $config['authorization_header_bearer_token'] = AuthorizationBearerClass::class;
        $this->httpWithBearerClass = new Http($config);
    }

    /** @test */
    public function authorizationBearerStringShouldBePresentOnRequest(): void
    {
        $request = new Request('get', 'https://google.com');
        $response = $this->httpWithBearerString->send($request);
        $headers = $this->httpWithBearerString->getLastRequest()->getHeaders();

        self::assertNotEmpty($response);
        self::assertEquals(200, $response->getStatusCode());
        self::assertNotEmpty($headers);
        self::assertArrayHasKey('Authorization', $headers);
        self::assertIsArray($headers['Authorization']);
        self::assertNotEmpty($headers['Authorization']);
        self::assertNotEmpty($headers['Authorization'][0]);
        self::assertSame($headers['Authorization'][0], 'Bearer BEARER_TOKEN');
    }

    /** @test */
    public function authorizationBearerClassShouldResolveInMiddleware(): void
    {
        $request = new Request('get', 'https://google.com');
        $response = $this->httpWithBearerClass->send($request);
        $headers = $this->httpWithBearerClass->getLastRequest()->getHeaders();

        self::assertNotEmpty($response);
        self::assertEquals(200, $response->getStatusCode());
        self::assertNotEmpty($headers);
        self::assertArrayHasKey('Authorization', $headers);
        self::assertIsArray($headers['Authorization']);
        self::assertNotEmpty($headers['Authorization']);
        self::assertNotEmpty($headers['Authorization'][0]);
        self::assertSame($headers['Authorization'][0], 'Bearer BEARER_FROM_RESOLVER_CLASS');
    }
}

class AuthorizationBearerClass implements BearerTokenResolverContract
{
    public function resolveToken(array $options): string
    {
        return 'BEARER_FROM_RESOLVER_CLASS';
    }
}

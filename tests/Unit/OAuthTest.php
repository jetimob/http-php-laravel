<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Facades\Http;
use Jetimob\Http\OAuth\OAuth;
use Jetimob\Http\Tests\TestCase;

class OAuthTest extends TestCase
{
    protected OAuth $oAuth;

    protected function setUp(): void
    {
        parent::setUp();
        $this->oAuth = Http::oAuth();
    }

    /** @test */
    public function instanceCorrectlySetUp(): void
    {
        self::assertNotEmpty($this->oAuth);
        self::assertSame(OAuth::class, get_class($this->oAuth));
    }

    /** @test */
    public function clientIsAutomaticallyConfigured(): void
    {
        $client = $this->oAuth->getClient();

        self::assertNotEmpty($client);
        self::assertNotEmpty($client->getClientId());
        self::assertNotEmpty($client->getClientSecret());
        self::assertNotEmpty($client->getTokenEndpoint());
        self::assertNotEmpty($client->getAuthorizationEndpoint());
    }
}

<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Facades\Http;
use Jetimob\Http\Authorization\OAuth\AccessToken;
use Jetimob\Http\Authorization\OAuth\OAuth;
use Jetimob\Http\Authorization\OAuth\OAuthClient;
use Jetimob\Http\Tests\TestCase;

class CacheTest extends TestCase
{
    protected AccessToken $accessToken;
    protected OAuthClient $client;
    protected OAuth $oAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessToken = new AccessToken([
            'access_token' => 'ACCESS_TOKEN',
            'refresh_token' => 'REFRESH_TOKEN',
            'expires_in' => '2400',
            'generated_at' => time(),
        ]);

        $this->client = new OAuthClient(
            'OAUTH_CLIENT_ID',
            'OAUTH_CLIENT_SECRET',
            'OAUTH_TOKEN_URI',
            'OAUTH_AUTHORIZATION_URI',
        );

        $this->oAuth = Http::oAuth();
        $this->oAuth->storeAccessToken($this->accessToken, $this->client);
    }

    /** @test */
    public function accessTokenShouldBeCached(): void
    {
        $accessToken = $this->oAuth->getCachedAccessToken($this->client);
        self::assertNotEmpty($accessToken);
        self::assertSame('ACCESS_TOKEN', $accessToken->getAccessToken());
        self::assertGreaterThan(0, $accessToken->getRemainingTime());
        self::assertFalse($accessToken->hasExpired());
    }
}

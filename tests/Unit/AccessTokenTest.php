<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\OAuth\AccessToken;
use Jetimob\Http\Tests\TestCase;

class AccessTokenTest extends TestCase
{
    protected AccessToken $accessToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessToken = new AccessToken([
            'access_token' => 'ACCESS_TOKEN',
            'refresh_token' => 'REFRESH_TOKEN',
            'expires_in' => '2400',
            'generated_at' => time(),
            'extra_01' => '_',
            'extra_02' => '_',
        ]);
    }

    /** @test */
    public function tokenShouldHaveValidLifetime(): void
    {
        self::assertFalse($this->accessToken->hasExpired());
        self::assertGreaterThan(0, $this->accessToken->getExpiresIn());
    }

    /** @test */
    public function extraDataShouldNotBeEmpty(): void
    {
        self::assertNotEmpty($this->accessToken->getExtraData());
        self::assertArrayHasKey('extra_01', $this->accessToken->getExtraData());
        self::assertArrayHasKey('extra_02', $this->accessToken->getExtraData());
        self::assertCount(2, $this->accessToken->getExtraData());
    }

    /** @test */
    public function serializationShouldNotFail(): void
    {
        $serialized = serialize($this->accessToken);
        $deserialized = unserialize($serialized, ['allowed_classes' => [AccessToken::class]]);

        self::assertNotNull($deserialized);
        self::assertInstanceOf(AccessToken::class, $deserialized);
        self::assertEquals($this->accessToken, $deserialized);
    }
}

<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Authorization\OAuth\AccessToken;
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

    /** @test */
    public function serializationWithinDifferentVersionsShouldSucceed(): void
    {
        $serializedATWOScope = 'C:44:"Jetimob\Http\Authorization\OAuth\AccessToken":139:{a:5:{i:0;s:12:"ACCESS_TOKEN";i:1;s:13:"REFRESH_TOKEN";i:2;i:2400;i:3;i:1638297953;i:4;a:2:{s:8:"extra_01";s:1:"_";s:8:"extra_02";s:1:"_";}}}';

        /** @var AccessToken $accessToken */
        $accessToken = unserialize(
            $serializedATWOScope,
            ['allowed_classes' => [AccessToken::class]]
        );
        self::assertNotNull($accessToken);
        self::assertInstanceOf(AccessToken::class, $accessToken);
        self::assertSame($this->accessToken->getAccessToken(), $accessToken->getAccessToken());
        self::assertSame($this->accessToken->getRefreshToken(), $accessToken->getRefreshToken());
        self::assertSame($this->accessToken->getExpiresIn(), $accessToken->getExpiresIn());
        self::assertSame(1638297953, $accessToken->getGeneratedAt());
        self::assertIsArray($accessToken->getScope());
        self::assertEmpty($accessToken->getScope());
        $this->assertArrayHasStructure($this->accessToken->getExtraData(), $accessToken->getExtraData());
    }
}

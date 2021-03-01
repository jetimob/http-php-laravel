<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Facades\Http;
use Jetimob\Http\Authorization\OAuth\AccessToken;
use Jetimob\Http\Authorization\OAuth\Storage\CacheRepositoryContract;
use Jetimob\Http\Tests\TestCase;

class CustomCacheRepositoryTest extends TestCase
{
    protected \Jetimob\Http\Http $http;

    protected function setUp(): void
    {
        parent::setUp();
        $this->http = Http::overwriteConfig('oauth_access_token_repository', CustomCacheRepository::class);
    }

    /** @test */
    public function customCacheIsCorrectlyInstantiated(): void
    {
        $oAuth = $this->http->oAuth();
        self::assertNotEmpty($oAuth);
        self::assertInstanceOf(CustomCacheRepository::class, $oAuth->getCacheRepository());
    }

    /** @test */
    public function customCacheWorksSeamlessly(): void
    {
        $token = new AccessToken(['access_token' => 'access_token_value']);
        $cache = $this->http->oAuth()->getCacheRepository();
        $cache->put('at', $token);
        $cachedValue = $cache->get('at');
        self::assertNotEmpty($cachedValue);
        self::assertInstanceOf(AccessToken::class, $token);
        self::assertEquals($token, $cachedValue);
    }
}

class CustomCacheRepository implements CacheRepositoryContract
{
    protected array $data = [];

    public function put(string $key, AccessToken $accessToken): void
    {
        $this->data[$key] = serialize($accessToken);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key): ?AccessToken
    {
        $cached = $this->data[$key] ?? null;

        if (is_null($cached)) {
            return null;
        }

        return unserialize($cached, ['allowed_classes' => [AccessToken::class]]);
    }

    public function forget(string $key): bool
    {
        if ($this->has($key)) {
            return false;
        }

        unset($this->data[$key]);
        return true;
    }
}


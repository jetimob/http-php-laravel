<?php

namespace Jetimob\Http\Authorization\OAuth\Storage;

use Exception;
use Illuminate\Contracts\Cache\Repository;
use Jetimob\Http\Authorization\OAuth\AccessToken;
use Psr\SimpleCache\InvalidArgumentException;

class CacheRepository implements CacheRepositoryContract
{
    protected Repository $cache;

    /**
     * CacheRepository constructor.
     * @param Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @param AccessToken $accessToken
     * @throws Exception
     */
    public function put(string $key, AccessToken $accessToken): void
    {
        $this->cache->put(
            $key,
            serialize($accessToken),
        );
    }

    /**
     * @param string $key
     * @return bool
     * @throws InvalidArgumentException
     */
    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    /**
     * @param string $key
     * @return AccessToken|null
     * @throws InvalidArgumentException
     */
    public function get(string $key): ?AccessToken
    {
        $cached = $this->cache->get($key);

        if (is_null($cached)) {
            return null;
        }

        return unserialize($cached, ['allowed_classes' => [AccessToken::class]]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->cache->forget($key);
    }
}

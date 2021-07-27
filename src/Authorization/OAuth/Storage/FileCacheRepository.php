<?php

namespace Jetimob\Http\Authorization\OAuth\Storage;

use Illuminate\Support\Facades\File;
use Jetimob\Http\Authorization\OAuth\AccessToken;

class FileCacheRepository implements CacheRepositoryContract
{
    private const CACHE_FILE_PATH = '/tmp/at.cache.json';
    private array $cache = [];

    public function __construct()
    {
        if (File::exists(self::CACHE_FILE_PATH)) {
            $this->cache = json_decode(File::get(self::CACHE_FILE_PATH), true);
        }
    }

    private function save(): void
    {
        File::put(self::CACHE_FILE_PATH, json_encode($this->cache, JSON_PRETTY_PRINT));
    }

    public function put(string $key, AccessToken $accessToken): void
    {
        $this->cache[$key] = serialize($accessToken);
        $this->save();
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->cache);
    }

    public function get(string $key): ?AccessToken
    {
        $cached = $this->cache[$key] ?? null;

        if (!is_null($cached)) {
            $cached = unserialize($cached, ['allowed_classes' => [AccessToken::class]]);
        }

        return $cached;
    }

    public function forget(string $key): bool
    {
        unset($this->cache[$key]);
        $this->save();
        return true;
    }
}

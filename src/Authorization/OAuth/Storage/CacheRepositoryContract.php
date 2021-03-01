<?php

namespace Jetimob\Http\Authorization\OAuth\Storage;

use Jetimob\Http\Authorization\OAuth\AccessToken;

interface CacheRepositoryContract
{
    public function put(string $key, AccessToken $accessToken): void;
    public function has(string $key): bool;
    public function get(string $key): ?AccessToken;
    public function forget(string $key): bool;
}

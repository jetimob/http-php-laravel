<?php

namespace Jetimob\Http\Authorization\OAuth\Storage;

use Jetimob\Http\Authorization\OAuth\OAuthClient;

class AccessTokenCacheKeyResolver implements AccessTokenCacheKeyResolverInterface
{
    public function resolveCacheKey(OAuthClient $client): string
    {
        return hash('sha256', $client->getClientId());
    }
}

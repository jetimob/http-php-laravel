<?php

namespace Jetimob\Http\OAuth\Storage;

use Jetimob\Http\OAuth\OAuthClient;

class AccessTokenCacheKeyResolver implements AccessTokenCacheKeyResolverInterface
{
    public function resolveCacheKey(OAuthClient $client): string
    {
        return hash('sha256', $client->getClientId());
    }
}

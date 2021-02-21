<?php

namespace Jetimob\Http\OAuth\Storage;

use Jetimob\Http\OAuth\OAuthClient;

interface AccessTokenCacheKeyResolverInterface
{
    public function resolveCacheKey(OAuthClient $client): string;
}

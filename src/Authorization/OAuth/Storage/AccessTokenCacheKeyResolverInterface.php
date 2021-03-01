<?php

namespace Jetimob\Http\Authorization\OAuth\Storage;

use Jetimob\Http\Authorization\OAuth\OAuthClient;

interface AccessTokenCacheKeyResolverInterface
{
    public function resolveCacheKey(OAuthClient $client): string;
}

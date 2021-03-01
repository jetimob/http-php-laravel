<?php

namespace Jetimob\Http\Authorization\OAuth\ClientProviders;

use Jetimob\Http\Authorization\OAuth\OAuthClient;

interface OAuthClientResolverInterface
{
    /**
     *
     * @param array $config
     * @return OAuthClient
     */
    public function resolveClient(array $config): OAuthClient;
}

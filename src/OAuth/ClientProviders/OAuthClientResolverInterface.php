<?php

namespace Jetimob\Http\OAuth\ClientProviders;

use Jetimob\Http\OAuth\OAuthClient;

interface OAuthClientResolverInterface
{
    /**
     *
     * @param array $config
     * @return OAuthClient
     */
    public function resolveClient(array $config): OAuthClient;
}

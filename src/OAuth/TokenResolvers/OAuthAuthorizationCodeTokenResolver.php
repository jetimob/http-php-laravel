<?php

namespace Jetimob\Http\OAuth\TokenResolvers;

use Jetimob\Http\OAuth\AccessToken;
use Jetimob\Http\OAuth\OAuthClient;
use Jetimob\Http\OAuth\OAuthFlow;

/**
 * Resolves an access token based on the authorization_code flow
 * Class OAuthAuthorizationCodeTokenResolver
 * @package Jetimob\Http\OAuth\TokenResolvers
 * @link https://tools.ietf.org/html/rfc6749#section-4.1
 */
class OAuthAuthorizationCodeTokenResolver extends OAuthTokenResolver
{
    /**
     * @param OAuthClient $client
     * @param string|null $credentials
     * @return AccessToken
     * @throws \JsonException
     */
    public function resolveAccessToken(OAuthClient $client, ?string $credentials = null): AccessToken
    {
        return $this->issueAccessTokenRequest($client, OAuthFlow::AUTHORIZATION_CODE, $credentials);
    }
}

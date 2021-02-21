<?php

namespace Jetimob\Http\OAuth\ClientProviders;

use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\OAuth\OAuthClient;

class OAuthClientResolver implements OAuthClientResolverInterface
{
    public function resolveClient(array $config): OAuthClient
    {
        $extract = static function (string $key, ?string $envKey = null) use ($config) {
            if (is_null($envKey)) {
                $envKey = $key;
            }

            $value = $config[$key] ?? env($envKey);

            if (empty($value)) {
                throw new InvalidArgumentException("Missing required configuration key \"$key\"");
            }

            return $value;
        };

        $clientId = $extract('oauth_client_id', 'OAUTH_CLIENT_ID');
        $clientSecret = $extract('oauth_client_secret', 'OAUTH_CLIENT_SECRET');
        $urlAuthorize = $extract('oauth_authorization_uri', 'OAUTH_AUTHORIZATION_URI');
        $urlAccessToken = $extract('oauth_token_uri', 'OAUTH_TOKEN_URI');

        return new OAuthClient(
            $clientId,
            $clientSecret,
            $urlAccessToken,
            $urlAuthorize,
        );
    }
}

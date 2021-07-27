<?php

namespace Jetimob\Http\Authorization\OAuth\ClientProviders;

use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\Authorization\OAuth\OAuthClient;

class OAuthClientResolver implements OAuthClientResolverInterface
{
    public function resolveClient(array $config): OAuthClient
    {
        $extract = static function (
            string $key,
            ?string $envKey = null,
            $required = true,
            $default = null,
            $normalizer = null
        ) use ($config) {
            if (is_null($envKey)) {
                $envKey = $key;
            }

            $value = $config[$key] ?? env($envKey);

            if (empty($value)) {
                if ($required) {
                    throw new InvalidArgumentException("Missing required configuration key \"$key\"");
                }

                return $default;
            }

            if (!is_null($normalizer)) {
                $value = $normalizer($value);
            }

            return $value;
        };

        $clientId = $extract('oauth_client_id', 'OAUTH_CLIENT_ID');
        $clientSecret = $extract('oauth_client_secret', 'OAUTH_CLIENT_SECRET');
        $urlAuthorize = $extract('oauth_authorization_uri', 'OAUTH_AUTHORIZATION_URI', false);
        $urlAccessToken = $extract('oauth_token_uri', 'OAUTH_TOKEN_URI');
        $redirectUri = $extract('oauth_redirect_uri', 'OAUTH_REDIRECT_URI', false);
        $scopes = $extract(
            'oauth_scopes',
            'OAUTH_SCOPES',
            false,
            [],
            static fn ($value) => is_string($value) ? explode(',', $value) : $value,
        );

        return new OAuthClient(
            $clientId,
            $clientSecret,
            $urlAccessToken,
            $urlAuthorize,
            $redirectUri,
            $scopes,
        );
    }
}

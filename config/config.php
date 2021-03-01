<?php

return [
    // how many retries before we actually throw an exception?
    'retries' => 5,

    // on which status code we should consider retrying the request?
    'retry_on_status_code' => [],

    // before retrying a failed request, wait for the specified amount of time, in milliseconds
    'retry_delay' => 2000,

    // guzzle configuration, given to Guzzle instance as is
    'guzzle' => [
        'base_uri' => '',

        // Number of seconds to wait while trying to connect to a server. 0 waits indefinitely.
        'connect_timeout' => 0.0,

        // Time needed to throw a timeout exception after a request is made.
        'timeout' => 0.0,

        // Set this to true if you want to debug the request/response.
        'debug' => false,

        'middlewares' => [
            \Jetimob\Http\Middlewares\OAuthRequestMiddleware::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth
    |--------------------------------------------------------------------------
    |
    */

    // Repository responsible for storing and retrieving access tokens.
    'oauth_access_token_repository' => \Jetimob\Http\Authorization\OAuth\Storage\CacheRepository::class,

    // Class responsible to give an access token its unique key to be cached in Laravel`s
    // Illuminate\Contracts\Cache\Repository
    // This given class MUST implement AccessTokenCacheKeyResolverInterface
    'oauth_token_cache_key_resolver' => \Jetimob\Http\Authorization\OAuth\Storage\AccessTokenCacheKeyResolver::class,

    // Class responsible to resolve an OAuthClient
    // MUST implement OAuthClientResolverInterface
    'oauth_client_resolver' => \Jetimob\Http\Authorization\OAuth\ClientProviders\OAuthClientResolver::class,

    // MUST inherit from OAuthTokenResolver
    'oauth_access_token_resolver' => [
        \Jetimob\Http\Authorization\OAuth\OAuthFlow::CLIENT_CREDENTIALS =>
            \Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthClientCredentialsTokenResolver::class,
        \Jetimob\Http\Authorization\OAuth\OAuthFlow::AUTHORIZATION_CODE =>
            \Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthAuthorizationCodeTokenResolver::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Bearer Authorization
    |--------------------------------------------------------------------------
    |
    */

    'authorization_header_bearer_token' => null,
];

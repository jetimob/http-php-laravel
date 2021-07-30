<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Retries
    |--------------------------------------------------------------------------
    |
    | Quantas vezes uma requisição pode ser reexecutada (em caso de falha) antes de gerar uma exceção.
    |
    */

    'retries' => 5,

    /*
    |--------------------------------------------------------------------------
    | Retry On Status Code
    |--------------------------------------------------------------------------
    |
    | Em quais códigos HTTP de uma resposta falha podemos tentar reexecutar a requisição.
    |
    */

    'retry_on_status_code' => [],

    /*
    |--------------------------------------------------------------------------
    | Retry Delay
    |--------------------------------------------------------------------------
    |
    | Antes de tentar reexecutar uma requisição falha, aguardar em ms.
    |
    */

    'retry_delay' => 2000,

    /*
    |--------------------------------------------------------------------------
    | Guzzle
    |--------------------------------------------------------------------------
    |
    | Configurações passadas à instância do Guzzle.
    | @link https://docs.guzzlephp.org/en/stable/request-options.html
    |
    */

    'guzzle' => [
        'base_uri' => '',

        /*
        |--------------------------------------------------------------------------
        | Connect Timeout
        |--------------------------------------------------------------------------
        |
        | Quantos segundos esperar por uma conexão com o servidor da Juno. 0 significa sem limite de espera.
        | https://docs.guzzlephp.org/en/stable/request-options.html#connect-timeout
        |
        */

        'connect_timeout' => 0.0,

        /*
        |--------------------------------------------------------------------------
        | Timeout
        |--------------------------------------------------------------------------
        |
        | Quantos segundos esperar pela resposta do servidor. 0 significa sem limite de espera.
        | @link https://docs.guzzlephp.org/en/stable/request-options.html#timeout
        |
        */
        'timeout' => 0.0,

        /*
        |--------------------------------------------------------------------------
        | Debug
        |--------------------------------------------------------------------------
        |
        | Usar true para ativar o modo debug do Guzzle.
        | @link https://docs.guzzlephp.org/en/stable/request-options.html#debug
        |
        */
        'debug' => false,

        /*
        |--------------------------------------------------------------------------
        | Middlewares
        |--------------------------------------------------------------------------
        |
        | Middlewares de autenticação e autorização disponíveis em: \Jetimob\Http\Middlewares
        | @link https://docs.guzzlephp.org/en/stable/handlers-and-middleware.html#middleware
        |
        */

        'middlewares' => [],

        /*
        |--------------------------------------------------------------------------
        | Headers
        |--------------------------------------------------------------------------
        |
        | Headers de requisição.
        | @link https://docs.guzzlephp.org/en/stable/request-options.html#headers
        |
        */

        'headers' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | OAuth Access Token Repository
    |--------------------------------------------------------------------------
    |
    | Essa classe é responsável por gerenciar os AccessTokens. Por padrão ela utiliza o repositório de cache padrão.
    |
    | PRECISA implementar \Jetimob\Http\Authorization\OAuth\Storage\CacheRepositoryContract
    */

    'oauth_access_token_repository' => \Jetimob\Http\Authorization\OAuth\Storage\CacheRepository::class,

    /*
    |--------------------------------------------------------------------------
    | OAuth Token Cache Key Resolver
    |--------------------------------------------------------------------------
    |
    | Classe responsável por gerar uma chave de identificação única para o cliente OAuth.
    |
    | PRECISA implementar \Jetimob\Http\Authorization\OAuth\Storage\AccessTokenCacheKeyResolverInterface
    */

    'oauth_token_cache_key_resolver' => \Jetimob\Http\Authorization\OAuth\Storage\AccessTokenCacheKeyResolver::class,

    /*
    |--------------------------------------------------------------------------
    | OAuth Client Resolver
    |--------------------------------------------------------------------------
    |
    | Classe responsável por resolver o client OAuth.
    |
    | PRECISA implementar \Jetimob\Http\Authorization\OAuth\ClientProviders\OAuthClientResolverInterface
    */

    'oauth_client_resolver' => \Jetimob\Http\Authorization\OAuth\ClientProviders\OAuthClientResolver::class,

    // MUST inherit from OAuthTokenResolver
    'oauth_access_token_resolver' => [
        \Jetimob\Http\Authorization\OAuth\OAuthFlow::CLIENT_CREDENTIALS =>
            \Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthClientCredentialsTokenResolver::class,
        \Jetimob\Http\Authorization\OAuth\OAuthFlow::AUTHORIZATION_CODE =>
            \Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthAuthorizationCodeTokenResolver::class,
    ],

    'oauth_scopes' => [],

    /*
    |--------------------------------------------------------------------------
    | Bearer Authorization
    |--------------------------------------------------------------------------
    |
    */

    /*
     |--------------------------------------------------------------------------
     | Authorization Header Bearer Token
     |-------------------------------------------------------------------------
     |
     | Utilizar esta opção quando a requisição a ser autorizada necessita de um Bearer Token.
     |
     | Esta configuração é utilizada junto do middleware \Jetimob\Http\Middlewares\AuthorizationBearerRequestMiddleware.
     | O middleware injeta a autorização do tipo Bearer no header das requisições com o valor desta configuração, ou, se
     | o valor for uma classe que implemente \Jetimob\Http\Authorization\Bearer\BearerTokenResolverContract, a classe
     | será utilizada para obter o bearer.
     */

    'authorization_header_bearer_token' => null,
];

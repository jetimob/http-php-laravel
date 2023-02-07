<?php

namespace Jetimob\Http\Authorization\OAuth;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Jetimob\Http\Exceptions\HttpException;
use Jetimob\Http\Exceptions\RuntimeException;
use Jetimob\Http\Authorization\OAuth\ClientProviders\OAuthClientResolverInterface;
use Jetimob\Http\Authorization\OAuth\Exceptions\AccessTokenExpiredException;
use Jetimob\Http\Authorization\OAuth\Storage\AccessTokenCacheKeyResolverInterface;
use Jetimob\Http\Authorization\OAuth\Storage\CacheRepositoryContract;
use Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthAuthorizationCodeTokenResolver;
use Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthTokenResolver;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class OAuth
 *
 * @package Jetimob\Http\Authorization\OAuth
 * {@link} https://tools.ietf.org/html/rfc6749
 */
class OAuth
{
    /** @var CacheRepositoryContract $cacheRepository Access Token storage handler */
    private CacheRepositoryContract $cacheRepository;

    /** @var OAuthClient $client The OAuth client identifier */
    private OAuthClient $client;

    /** @var AccessTokenCacheKeyResolverInterface $cacheKeyResolver Class responsible to resolve the cache`s key for an access token */
    private AccessTokenCacheKeyResolverInterface $cacheKeyResolver;

    private array $config;

    /**
     * OAuth constructor.
     *
     * @param CacheRepositoryContract              $cacheRepository
     * @param OAuthClientResolverInterface         $clientResolver
     * @param AccessTokenCacheKeyResolverInterface $cacheKeyResolver
     * @param array                                $config
     * @param OAuthClient|null                     $client
     *
     * @see OAuthClient
     * @see OAuthClientResolverInterface
     * @see AccessToken
     * @see CacheRepositoryContract
     */
    public function __construct(
        CacheRepositoryContract              $cacheRepository,
        OAuthClientResolverInterface         $clientResolver,
        AccessTokenCacheKeyResolverInterface $cacheKeyResolver,
        array                                $config,
        ?OAuthClient                         $client = null
    )
    {
        $this->cacheRepository = $cacheRepository;
        $this->cacheKeyResolver = $cacheKeyResolver;
        $this->config = $config;
        $this->client = $client ?? $clientResolver->resolveClient($config);
    }

    /**
     * Returns the key to be used to store an access token to the given client.
     *
     * @param OAuthClient $client
     *
     * @return string
     */
    public function getCacheKeyForClient(OAuthClient $client): string
    {
        return $this->cacheKeyResolver->resolveCacheKey($client);
    }

    /**
     * Stores an access token attached to is`s client.
     *
     * @param AccessToken      $accessToken
     * @param OAuthClient|null $client If client is not provided, the one defined in this instance will be used.
     *
     * @throws Exception
     */
    public function storeAccessToken(AccessToken $accessToken, ?OAuthClient $client = null): void
    {
        $client ??= $this->client;

        $this->cacheRepository->put(
            $this->getCacheKeyForClient($client),
            $accessToken
        );
    }

    /**
     * Forgets an access token. Be aware that if the token is still valid, we'll lose the credentials as there is no
     * other way to get the refresh token stored with the AccessToken object.
     *
     * @param OAuthClient|null $client If client is not provided, the one defined in this instance will be used.
     */
    public function forgetAccessToken(?OAuthClient $client = null): void
    {
        $client ??= $this->client;
        $this->cacheRepository->forget($this->getCacheKeyForClient($client));
    }

    /**
     * Retrieves and caches (if specified to) an Access Token.
     *
     * @param OAuthTokenResolver $tokenResolver Class responsible to return an access token based on a given client and
     * credentials (authorization_code, refresh_token,...).
     * @param string|null        $credentials Credentials can be the authorization_code or the refresh_token,
     * depending on the $tokenResolver class context.
     * @param OAuthClient|null   $client
     * @param bool               $retrieveFromCache If set to true, a cached access token will be retrieved if there is
     *     one.
     * @param bool               $cacheResult Caches a successful access token request.
     * @param bool               $autoRefresh Automatically issues a request to refresh an expired access token if set
     *     to true.
     *
     * @return AccessToken
     * @throws AccessTokenExpiredException|Exceptions\OAuthException|HttpException|JsonException
     * @throws Exception
     * @see AccessToken
     * @see OAuthClient
     * @see OAuthTokenResolver
     */
    public function getAccessToken(
        OAuthTokenResolver $tokenResolver,
        ?string            $credentials = null,
        ?OAuthClient       $client = null,
        bool               $retrieveFromCache = true,
        bool               $cacheResult = true,
        bool               $autoRefresh = true
    ): AccessToken
    {
        // use the resolved client if none is provided
        $client ??= $this->client;
        $cacheKey = $this->getCacheKeyForClient($client);

        if ($retrieveFromCache && $this->cacheRepository->has($cacheKey)) {
            /** @var AccessToken $accessToken */
            $accessToken = $this->cacheRepository->get($cacheKey);

            if (is_null($accessToken)) {
                throw new RuntimeException('Could not deserialize the cached access token');
            }

            if (!$accessToken->hasExpired()) {
                return $accessToken;
            }

            if (!$autoRefresh) {
                throw new AccessTokenExpiredException('The access token has expired and will not be auto refreshed');
            }

            if ($accessToken->hasRefreshToken()) {
                $accessToken = $tokenResolver->refreshAccessToken($client, $accessToken);
            } else {
                $accessToken = $tokenResolver->resolveAccessToken($client, $credentials);
            }

            // clear the expired access token
            $this->forgetAccessToken($client);
        } else {
            $accessToken = $tokenResolver->resolveAccessToken($client, $credentials);
        }

        if ($cacheResult) {
            $this->storeAccessToken($accessToken, $client);
        }

        return $accessToken;
    }

    /**
     * Returns an access token if there is one cached for the given class. Returns null if the access token is not
     * cached.
     *
     * @param OAuthClient|null $client
     *
     * @return AccessToken|null
     */
    public function getCachedAccessToken(?OAuthClient $client = null): ?AccessToken
    {
        $client ??= $this->client;
        $cacheKey = $this->getCacheKeyForClient($client);

        if (!$this->cacheRepository->has($cacheKey)) {
            return null;
        }

        return $this->cacheRepository->get($cacheKey);
    }

    /**
     * Exchanges an authorization code for an access token using the OAuth client defined in this class instance.
     *
     * @param string $authorizationCode
     *
     * @return AccessToken
     * @throws AccessTokenExpiredException|Exceptions\OAuthException|HttpException|JsonException
     * @throws InvalidArgumentException|BindingResolutionException
     * @see OAuth::$client
     * @link https://tools.ietf.org/html/rfc6749#section-4.1
     */
    public function handleAuthorizationCodeExchange(string $authorizationCode): AccessToken
    {
        return $this->getAccessToken(app()->make(
            $this->getConfig()['oauth_access_token_resolver'][OAuthFlow::AUTHORIZATION_CODE] ??
                OAuthAuthorizationCodeTokenResolver::class
        ), $authorizationCode);
    }

    /**
     * @return OAuthClient
     */
    public function getClient(): OAuthClient
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return CacheRepositoryContract
     */
    public function getCacheRepository(): CacheRepositoryContract
    {
        return $this->cacheRepository;
    }
}

<?php

namespace Jetimob\Http\Middlewares;

use Closure;
use Jetimob\Http\Exceptions\RuntimeException;
use Jetimob\Http\Http;
use Jetimob\Http\Authorization\OAuth\OAuthConfig;
use Jetimob\Http\Authorization\OAuth\TokenResolvers\OAuthTokenResolver;
use Psr\Http\Message\RequestInterface;

class OAuthRequestMiddleware
{
    private Http $http;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function __invoke(callable $handler): Closure
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $oAuthGrantType = $options[OAuthConfig::OAUTH_GRANT_TYPE] ?? null;

            // the request doesn't require oauth authorization
            if (is_null($oAuthGrantType)) {
                return $handler($request, $options);
            }

            $resolverClass = $this->http->getConfig('oauth_access_token_resolver', [])[$oAuthGrantType] ?? null;

            if (is_null($resolverClass)) {
                throw new RuntimeException(
                    "No class defined in \"oauth_access_token_resolver\" for grant_type \"$oAuthGrantType\""
                );
            }

            if (!class_exists($resolverClass)) {
                throw new RuntimeException("\"$resolverClass\" is not a valid class");
            }

            /** @var OAuthTokenResolver $resolverInstance */
            $resolverInstance = app()->make($resolverClass);

            if (!($resolverInstance instanceof OAuthTokenResolver)) {
                throw new RuntimeException("\"$$resolverClass\" does not inherit from OAuthTokenResolver");
            }

            $accessToken = $this->http->oAuth()->getAccessToken($resolverInstance);

            /** {@link} https://tools.ietf.org/html/rfc6749#section-7.1 */
            if ($accessToken->getTokenType() !== 'bearer') {
                throw new RuntimeException('Currently the only supported token type is the "bearer" one');
            }

            $newRequest = $request->withAddedHeader('Authorization', "Bearer {$accessToken->getToken()}");
            $this->http->setLastRequest($newRequest);

            return $handler($newRequest, $options);
        };
    }
}

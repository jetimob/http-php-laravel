<?php

namespace Jetimob\Http\Middlewares;

use Closure;
use Jetimob\Http\Authorization\Bearer\BearerTokenResolverContract;
use Jetimob\Http\Exceptions\RuntimeException;
use Jetimob\Http\Http;
use Psr\Http\Message\RequestInterface;

class AuthorizationBearerRequestMiddleware
{
    private Http $http;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function __invoke(callable $handler): Closure
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $bearerToken = $this->http->getConfig('authorization_header_bearer_token');

            if (is_null($bearerToken)) {
                throw new RuntimeException(
                    'There is no "authorization_header_bearer_token" defined in the configuration array'
                );
            }

            if (!is_string($bearerToken)) {
                if (!class_exists($bearerToken)) {
                    throw new RuntimeException(
                        '"authorization_header_bearer_token" MUST be an string or a class'
                    );
                }

                $instance = new $bearerToken();

                if (!($instance instanceof BearerTokenResolverContract)) {
                    throw new RuntimeException(
                        '"authorization_header_bearer_token" class MUST implement BearerTokenResolverContract'
                    );
                }

                $bearerToken = $instance->resolveToken($options);
            }

            return $handler($request->withAddedHeader('Authorization', "Bearer $bearerToken"), $options);
        };
    }
}

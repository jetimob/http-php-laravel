<?php

namespace Jetimob\Http\Middlewares;

use Closure;
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
                    'There is no "authorization_header_bearer_token" defined in the configuration array '
                );
            }

            return $handler($request->withAddedHeader('Authorization', "Bearer $bearerToken"), $options);
        };
    }
}

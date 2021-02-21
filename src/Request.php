<?php

namespace Jetimob\Http;

use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\OAuth\OAuthFlow;

class Request extends \GuzzleHttp\Psr7\Request
{
    private ?string $oAuthGrantType;

    protected ?string $responseClass = null;

    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        ?string $oAuthGrantType = null,
        $version = '1.1'
    ) {
        parent::__construct($method, $uri, $headers, $body, $version);
        $this->oAuthGrantType = $oAuthGrantType;

        if (!is_null($oAuthGrantType)
            && !in_array($oAuthGrantType, [OAuthFlow::CLIENT_CREDENTIALS, OAuthFlow::AUTHORIZATION_CODE], true)
        ) {
            throw new InvalidArgumentException(
                'available grant types are: "authorization_code" and "client_credentials"'
            );
        }
    }

    public function requiresOAuthAuthorization(): bool
    {
        return !is_null($this->oAuthGrantType);
    }

    /**
     * @return string|null
     */
    public function getOAuthGrantType(): ?string
    {
        return $this->oAuthGrantType;
    }

    public static function withAuthorizationCode($method, $uri, array $headers = [], $body = null): self
    {
        return new self($method, $uri, $headers, $body, OAuthFlow::AUTHORIZATION_CODE);
    }

    public static function withClientCredentials($method, $uri, array $headers = [], $body = null): self
    {
        return new self($method, $uri, $headers, $body, OAuthFlow::CLIENT_CREDENTIALS);
    }

    public static function get($uri, array $headers = [], $body = null): self
    {
        return new self('get', $uri, $headers, $body);
    }

    public static function post($uri, array $headers = [], $body = null): self
    {
        return new self('post', $uri, $headers, $body);
    }

    public static function put($uri, array $headers = [], $body = null): self
    {
        return new self('put', $uri, $headers, $body);
    }

    public static function delete($uri, array $headers = [], $body = null): self
    {
        return new self('delete', $uri, $headers, $body);
    }
}

<?php

namespace Jetimob\Http\Authorization\OAuth;

class OAuthClient
{
    private string $clientId;
    private string $clientSecret;
    private string $tokenEndpoint;
    private ?string $authorizationEndpoint;
    private array $scope;

    /**
     * OAuthClient constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $tokenEndpoint
     * @param string|null $authorizationEndpoint
     * @param array $scope
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        string $tokenEndpoint,
        string $authorizationEndpoint = null,
        array $scope = []
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenEndpoint = $tokenEndpoint;
        $this->authorizationEndpoint = $authorizationEndpoint;
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return OAuthClient
     */
    public function setClientId(string $clientId): OAuthClient
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     * @return OAuthClient
     */
    public function setClientSecret(string $clientSecret): OAuthClient
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenEndpoint(): string
    {
        return $this->tokenEndpoint;
    }

    /**
     * @param string $tokenEndpoint
     * @return OAuthClient
     */
    public function setTokenEndpoint(string $tokenEndpoint): OAuthClient
    {
        $this->tokenEndpoint = $tokenEndpoint;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationEndpoint(): ?string
    {
        return $this->authorizationEndpoint;
    }

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    /**
     * @param string $authorizationEndpoint
     * @return OAuthClient
     */
    public function setAuthorizationEndpoint(string $authorizationEndpoint): OAuthClient
    {
        $this->authorizationEndpoint = $authorizationEndpoint;
        return $this;
    }
}

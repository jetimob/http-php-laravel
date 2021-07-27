<?php

namespace Jetimob\Http\Authorization\OAuth\TokenResolvers;

use GuzzleHttp\RequestOptions;
use Jetimob\Http\Authorization\OAuth\Exceptions\AuthorizationCodeRequiredException;
use Jetimob\Http\Exceptions\HttpException;
use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\Http;
use Jetimob\Http\Authorization\OAuth\AccessToken;
use Jetimob\Http\Authorization\OAuth\Exceptions\AccessTokenExpiredException;
use Jetimob\Http\Authorization\OAuth\Exceptions\OAuthException;
use Jetimob\Http\Authorization\OAuth\OAuthClient;
use Jetimob\Http\Authorization\OAuth\OAuthFlow;
use Jetimob\Http\Request;
use JsonException;

abstract class OAuthTokenResolver
{
    protected Http $http;

    /**
     * OAuthTokenResolver constructor.
     * @param Http $http
     */
    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    /**
     * The authorization server MUST support the use of the HTTP "GET" method [RFC2616] for the authorization endpoint
     * and MAY support the use of the "POST" method as well.
     *
     * {@link} https://tools.ietf.org/html/rfc6749#section-3.1
     *
     * @param OAuthClient $client
     * @param string|null $credentials
     * @return AccessToken
     * @throws OAuthException|HttpException
     */
    abstract public function resolveAccessToken(OAuthClient $client, ?string $credentials = null): AccessToken;

    /**
     * @param OAuthClient   $client
     * @param string        $grantType
     * @param string|null   $credentials
     * @param callable|null $buildRequestOptions
     *
     * @return AccessToken
     *
     * @throws JsonException
     * @throws AuthorizationCodeRequiredException
     */
    public function issueAccessTokenRequest(
        OAuthClient $client,
        string $grantType,
        ?string $credentials = null,
        ?callable $buildRequestOptions = null
    ): AccessToken {
        if (($grantType === 'refresh_token' || $grantType === OAuthFlow::AUTHORIZATION_CODE) && empty($credentials)) {
            if ($grantType === OAuthFlow::AUTHORIZATION_CODE) {
                throw new AuthorizationCodeRequiredException($client->getAuthorizationEndpoint());
            }

            throw new InvalidArgumentException(
                "\"$grantType\" grant type requires the \$credentials parameter to be defined"
            );
        }

        $requestBody = [
            'grant_type' => $grantType,
        ];

        if (!empty($scopes = $client->getScope())) {
            $requestBody['scope'] = implode(' ', $scopes);
        }

        if ($grantType === 'refresh_token') {
            $requestBody['refresh_token'] = $credentials;
        } elseif ($grantType === OAuthFlow::AUTHORIZATION_CODE) {
            $requestBody += [
                'code' => $credentials,
                'client_id' => $client->getClientId(),
            ];

            if (!is_null($redirectUri = $client->getRedirectUri())) {
                $requestBody['redirect_uri'] = $redirectUri;
            }
        }

        $requestOptions = [
            RequestOptions::FORM_PARAMS => $requestBody,
            RequestOptions::HEADERS => [
                'Authorization' => 'Basic ' . base64_encode(
                    sprintf('%s:%s', $client->getClientId(), $client->getClientSecret())
                ),
            ],
        ];

        $requestOptions = !is_null($buildRequestOptions) ? $buildRequestOptions($requestOptions) : $requestOptions;
        $response = $this->http->send(new Request('post', $client->getTokenEndpoint()), $requestOptions);

        return new AccessToken(
            json_decode(
                $response->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            ),
        );
    }

    /**
     * {@link} https://tools.ietf.org/html/rfc6749#section-6
     *
     * @param OAuthClient $client
     * @param AccessToken $accessToken
     * @return AccessToken
     * @throws AccessTokenExpiredException|JsonException
     */
    public function refreshAccessToken(OAuthClient $client, AccessToken $accessToken): AccessToken
    {
        if (!$accessToken->hasRefreshToken()) {
            throw new AccessTokenExpiredException('The access token has expired and cannot be refreshed!');
        }

        return $this->issueAccessTokenRequest($client, 'refresh_token', $accessToken->getRefreshToken());
    }
}

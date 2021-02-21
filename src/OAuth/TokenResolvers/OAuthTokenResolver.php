<?php

namespace Jetimob\Http\OAuth\TokenResolvers;

use GuzzleHttp\RequestOptions;
use Jetimob\Http\Exceptions\HttpException;
use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\Http;
use Jetimob\Http\OAuth\AccessToken;
use Jetimob\Http\OAuth\Exceptions\AccessTokenExpiredException;
use Jetimob\Http\OAuth\Exceptions\OAuthException;
use Jetimob\Http\OAuth\OAuthClient;
use Jetimob\Http\OAuth\OAuthFlow;
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
     * @param OAuthClient $client
     * @param string $grantType
     * @param string|null $credentials
     * @return AccessToken
     * @throws InvalidArgumentException | JsonException
     */
    public function issueAccessTokenRequest(
        OAuthClient $client,
        string $grantType,
        ?string $credentials = null
    ): AccessToken {
        $requestBody = [
            'grant_type' => $grantType,
        ];

        if (($grantType === 'refresh_token' || $grantType === OAuthFlow::AUTHORIZATION_CODE) && empty($credentials)) {
            throw new InvalidArgumentException(
                "\"$grantType\" grant type requires the \$credentials parameter to be defined"
            );
        }

        if ($grantType === 'refresh_token') {
            $requestBody['refresh_token'] = $credentials;
        } else {
            if ($grantType === OAuthFlow::AUTHORIZATION_CODE) {
                $requestBody += [
                    'code' => $credentials,
                ];
            }

            $requestBody += [
                'client_id' => $client->getClientId(),
                'client_secret' => $client->getClientSecret(),
            ];
        }

        $response = $this->http->send(new Request('post', $client->getTokenEndpoint()), [
            RequestOptions::FORM_PARAMS => $requestBody,
        ]);

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

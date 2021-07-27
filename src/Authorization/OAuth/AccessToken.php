<?php

namespace Jetimob\Http\Authorization\OAuth;

use Jetimob\Http\Exceptions\InvalidArgumentException;
use Serializable;

/**
 * Class AccessToken
 * @package Jetimob\Http\Authorization\OAuth
 * {@link} https://tools.ietf.org/html/rfc6749#page-10
 */
class AccessToken implements Serializable
{
    private string $accessToken;
    private ?string $refreshToken;
    private array $scope;

    private int $expiresIn;
    private int $generatedAt;

    private array $extraData;

    /** @var string $tokenType {@link} https://tools.ietf.org/html/rfc6749#section-7.1 */
    private string $tokenType = 'bearer';

    /**
     * AccessToken constructor.
     * @param array $options
     * {@link} https://tools.ietf.org/html/rfc6749#section-5.1
     */
    public function __construct(array $options)
    {
        if (empty($options['access_token'])) {
            throw new InvalidArgumentException('Missing required option "access_token".');
        }

        // as per the RFC, ONLY access_token and token_type are required

        $this->accessToken = $options['access_token'];
        $this->refreshToken = $options['refresh_token'] ?? null;
        $this->scope = explode(' ', ($options['scope'] ?? ''));
        $this->expiresIn = (int) ($options['expires_in'] ?? 3600);

        $this->generatedAt = (int) ($options['generated_at'] ?? time());

        $this->extraData = array_reduce(
            array_diff(array_keys($options), ['access_token', 'refresh_token', 'expires_in', 'generated_at', 'scope']),
            static fn ($acc, $el) => $acc + [$el => $options[$el]],
            [],
        );
    }

    public function getToken(): string
    {
        return $this->accessToken;
    }

    public function getExtraData(): array
    {
        return $this->extraData;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @return mixed|string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return int
     */
    public function getGeneratedAt(): int
    {
        return $this->generatedAt;
    }

    public function getRemainingTime(): int
    {
        return $this->getGeneratedAt() + $this->getExpiresIn() - time();
    }

    public function hasExpired(): bool
    {
        return $this->getRemainingTime() <= 0;
    }

    public function hasRefreshToken(): bool
    {
        return !is_null($this->refreshToken);
    }

    /**
     * Only the type "bearer" is currently implemented.
     * {@link} https://tools.ietf.org/html/rfc6749#section-7.1
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    public function hasScope(string $scope): bool
    {
        return in_array($scope, $this->scope, true);
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize([
            $this->accessToken,
            $this->refreshToken,
            $this->expiresIn,
            $this->generatedAt,
            $this->extraData,
            $this->scope,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->accessToken,
            $this->refreshToken,
            $this->expiresIn,
            $this->generatedAt,
            $this->extraData,
            $this->scope,
        ) = unserialize($serialized, ['allowed_classes' => [self::class]]);
    }
}

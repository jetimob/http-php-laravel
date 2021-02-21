<?php

namespace Jetimob\Http\OAuth;

abstract class OAuthFlow
{
    public const AUTHORIZATION_CODE = 'authorization_code';
    public const CLIENT_CREDENTIALS = 'client_credentials';

    public static function requiresResourceOwnerInteraction(string $flow): bool
    {
        return $flow === self::AUTHORIZATION_CODE;
    }
}

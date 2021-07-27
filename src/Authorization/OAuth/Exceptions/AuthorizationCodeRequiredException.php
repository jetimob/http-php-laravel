<?php

namespace Jetimob\Http\Authorization\OAuth\Exceptions;

use Throwable;

class AuthorizationCodeRequiredException extends \Exception implements OAuthException
{
    protected string $authorizationUri;

    public function __construct($authorizationUri = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            "You MUST obtain an authorization code to exchange for an access token at: $authorizationUri",
            $code,
            $previous
        );

        $this->authorizationUri = $authorizationUri;
    }

    /**
     * @return string
     */
    public function getAuthorizationUri()
    {
        return $this->authorizationUri;
    }
}

<?php

namespace Jetimob\Http\Tests\Feature;

use Jetimob\Http\Exceptions\InvalidArgumentException;
use Jetimob\Http\Facades\Http;
use Jetimob\Http\Request;
use Jetimob\Http\Tests\TestCase;

class OAuthClientCredentialsTest extends TestCase
{
    /** @test */
    public function requestWithoutAuthorizationCodeShouldFail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Http::send(Request::withAuthorizationCode('get', 'http://google.com'));
    }
}

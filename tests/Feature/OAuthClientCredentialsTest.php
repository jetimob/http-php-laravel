<?php

namespace Jetimob\Http\Tests\Feature;

use Jetimob\Http\Exceptions\RuntimeException;
use Jetimob\Http\Facades\Http;
use Jetimob\Http\Request;
use Jetimob\Http\Tests\TestCase;

class OAuthClientCredentialsTest extends TestCase
{
    /** @test */
    public function requestWithoutAuthorizationCodeShouldFail(): void
    {
        $this->expectException(RuntimeException::class);
        Http::send(Request::withAuthorizationCode('get', 'https://google.com'));
    }
}

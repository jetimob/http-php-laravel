<?php

namespace Jetimob\Http\Contracts;

use Jetimob\Http\Http;

interface HttpProviderContract
{
    public function getHttpInstance(): Http;
}

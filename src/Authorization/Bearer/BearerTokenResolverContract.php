<?php

namespace Jetimob\Http\Authorization\Bearer;

interface BearerTokenResolverContract
{
    public function resolveToken(array $options): string;
}

<?php

namespace Jetimob\Http\Contracts;

interface HydratableContract
{
    public function hydrate(array $dataObject): self;
}

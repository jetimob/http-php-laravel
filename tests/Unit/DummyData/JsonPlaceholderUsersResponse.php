<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Response;

class JsonPlaceholderUsersResponse extends Response
{
    protected array $container;

    protected function containerItemType(): string
    {
        return JsonPlaceholderUser::class;
    }

    public function getContainer(): array
    {
        return $this->container;
    }
}

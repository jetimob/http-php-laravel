<?php

namespace Jetimob\Http\Tests\Unit;

use Jetimob\Http\Tests\TestCase;

class DocblockReflectionTest extends TestCase
{
    /**
     * @var \Jetimob\Http\Response[] $responseArray
     */
    protected array $responseArray = [];

    /** @test */
    public function canRetrieveTypingFromDocBlock(): void
    {
        $refl = new \ReflectionClass($this);
        $prop = $refl->getProperty('responseArray');
        self::assertNotEmpty($prop);
        $docComment = $prop->getDocComment();
        self::assertNotEmpty($docComment);
        preg_match_all('/@var\s+([\w\\\]+)\[].*/', $docComment, $matches, PREG_PATTERN_ORDER);
        self::assertNotEmpty($matches[1]); // group
        $class = $matches[1][0];
        self::assertTrue(class_exists($class));
        self::assertTrue(method_exists($class, 'fromGuzzleResponse'));
    }
}

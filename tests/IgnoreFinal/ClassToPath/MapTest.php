<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal\Test\IgnoreFinal\ClassToPath;

use Robier\Tests\IgnoreFinal\ClassToPath\Map;
use PHPUnit\Framework\TestCase;
use Robier\Tests\IgnoreFinal\Test\FooBar;

/**
 * @covers \Robier\Tests\IgnoreFinal\ClassToPath\Map
 */
final class MapTest extends TestCase
{
    public function testMatching(): void
    {
        $map = new Map(
            [
                FooBar::class => 'test.php'
            ]
        );

        self::assertSame('test.php', $map->get(FooBar::class));
    }

    public function testNotMatching(): void
    {
        $map = new Map(
            [
                FooBar::class => 'test.php'
            ]
        );

        self::assertNull($map->get(\stdClass::class));
    }
}

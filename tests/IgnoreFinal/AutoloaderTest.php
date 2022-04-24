<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal\Test\IgnoreFinal;

use Robier\Tests\IgnoreFinal\Autoloader;
use PHPUnit\Framework\TestCase;
use Robier\Tests\IgnoreFinal\ClassToPath\Map;
use Robier\Tests\IgnoreFinal\Test\FooBar;

/**
 * @covers \Robier\Tests\IgnoreFinal\Autoloader
 */
final class AutoloaderTest extends TestCase
{
    public function testClassMatched(): void
    {
        $autoloader = new Autoloader(
            new Map(
                [
                    FooBar::class => dirname(__DIR__) . '/FooBar.php',
                ]
            ),
            FooBar::class
        );

        $autoloader(FooBar::class);
        $fooBar = new FooBar();
        $reflection = new \ReflectionClass($fooBar);

        self::assertInstanceOf(FooBar::class, $fooBar);
        self::assertFalse($reflection->isFinal());
    }

    public function testClassNotMatched(): void
    {
        $autoloader = new Autoloader(
            new Map(
                [
                    FooBar::class => dirname(__DIR__) . '/FooBar.php',
                ]
            )
        );

        $autoloader(FooBar::class);
        $fooBar = new FooBar();
        $reflection = new \ReflectionClass($fooBar);

        self::assertInstanceOf(FooBar::class, $fooBar);
        self::assertTrue($reflection->isFinal());
    }

    public function testGetClasses(): void
    {
        $autoloader = new Autoloader(
            new Map(
                [
                    FooBar::class => dirname(__DIR__) . '/FooBar.php',
                ]
            ),
            FooBar::class
        );

        self::assertSame([FooBar::class => FooBar::class], $autoloader->classes());
    }
}

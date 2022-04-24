<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal\Test;

use Robier\Tests\IgnoreFinal;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Robier\Tests\IgnoreFinal
 */
final class IgnoreFinalTest extends TestCase
{
    public function testCreatingFinalInstance(): void
    {
        $test = new FooBar();

        $reflection = new \ReflectionClass($test);

        self::assertTrue($reflection->isFinal());
    }

    public function testCreatingWithoutFinalInstance(): void
    {
        IgnoreFinal::map(
            [
                FooBar::class => __DIR__ . '/FooBar.php',
            ],
            FooBar::class
        );

        $test = new FooBar();

        $reflection = new \ReflectionClass($test);

        self::assertFalse($reflection->isFinal());
    }

    public function testIsAppliedOnClass(): void
    {
        $ignoreFinal = IgnoreFinal::map(
            [
                FooBar::class => __DIR__ . '/FooBar.php',
            ],
            FooBar::class
        );

        self::assertTrue($ignoreFinal->isAppliedOn(FooBar::class));
    }

    public function testIsNotAppliedOnClass(): void
    {
        $ignoreFinal = IgnoreFinal::map(
            [
                FooBar::class => __DIR__ . '/FooBar.php',
            ],
            stdClass::class
        );

        self::assertFalse($ignoreFinal->isAppliedOn(FooBar::class));
    }

    public function testCreatingClassBeforeIgnoringDoesNothing(): void
    {
        $test = new FooBar();

        $reflection = new \ReflectionClass($test);

        IgnoreFinal::map(
            [
                FooBar::class => __DIR__ . '/FooBar.php',
            ],
            FooBar::class
        );

        self::assertTrue($reflection->isFinal());
    }
}

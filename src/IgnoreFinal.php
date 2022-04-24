<?php

declare(strict_types=1);

namespace Robier\Tests;

use Robier\Tests\IgnoreFinal\Autoloader;
use Robier\Tests\IgnoreFinal\ClassToPath\Composer;
use Robier\Tests\IgnoreFinal\ClassToPath\Map;

final class IgnoreFinal
{
    private $classes;

    public function __construct(Autoloader $autoloader)
    {
        $this->classes = $autoloader->classes();

        // we need this to be loaded before all other autoloads as we want to change
        // php code before it's loaded
        spl_autoload_register(
            $autoloader,
            true,
            true
        );
    }

    /**
     * Used when you use composer in your project
     */
    public static function composer(string $class, string ...$classes): self
    {
        return new static(
            new Autoloader(
                new Composer(),
                $class,
                ...$classes
            )
        );
    }

    /**
     * Used usually for testing
     */
    public static function map(array $map, string $class, string ...$classes): self
    {
        return new static(
            new Autoloader(
                new Map($map),
                $class,
                ...$classes
            )
        );
    }

    public function isAppliedOn(string $class): bool
    {
        return isset($this->classes[$class]);
    }
}

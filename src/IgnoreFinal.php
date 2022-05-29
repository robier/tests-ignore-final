<?php

declare(strict_types=1);

namespace Robier\Tests;

use Robier\Tests\IgnoreFinal\Autoloader;
use Robier\Tests\IgnoreFinal\ClassToPath\Composer;
use Robier\Tests\IgnoreFinal\ClassToPath\Map;

final class IgnoreFinal
{
    private $classes;

    private $globally;

    public function __construct(Autoloader $autoloader)
    {
        $this->classes = $autoloader->classes();
        $this->globally = $autoloader->isGloballyEnabled();

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
                false,
                false,
                $class,
                ...$classes
            )
        );
    }

    /**
     * To ignore tests globally
     */
    public static function enableGlobally(bool $inplace = false): self
    {
        return new static(
            new Autoloader(
                new Composer(),
                true,
                $inplace,
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
                false,
                false,
                $class,
                ...$classes
            )
        );
    }

    public function isAppliedOn(string $class): bool
    {
        return $this->globally || isset($this->classes[$class]);
    }

    public static function preloadClass(string $class): void
    {
        class_exists($class);
    }
}

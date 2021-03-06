<?php

declare(strict_types=1);

namespace Robier\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Robier\Tests\IgnoreFinal\Autoloader;
use Robier\Tests\IgnoreFinal\ClassToPath\Composer;
use Robier\Tests\IgnoreFinal\ClassToPath\Map;

final class IgnoreFinal
{
    private $classes;

    /**
     * @var bool
     */
    private $globally;

    /**
     * @var Autoloader
     */
    private $autoloader;

    public function __construct(Autoloader $autoloader)
    {
        $this->autoloader = $autoloader;
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

    /**
     * @throws IgnoreFinal\Exception
     */
    public function preloadClass(string $class, string ...$classes): void
    {
        array_unshift($classes, $class);

        $autoloader = $this->autoloader;
        foreach ($classes as $classToPreload) {
            $autoloader($classToPreload);
        }
    }

    /**
     * @throws IgnoreFinal\Exception
     */
    public function preloadFile(string $path, string ...$paths): void
    {
        array_unshift($paths, $path);
        foreach ($paths as $pathToPreload) {
            $this->autoloader->loadFile($pathToPreload);
        }
    }

    public function preloadFiles(string $rootPath, string $fileRegex): void
    {
        $fileIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath));
        $filteredFileIterator = new RegexIterator($fileIterator, $fileRegex);

        foreach ($filteredFileIterator as $file) {
            if ($file->isDir()){
                continue;
            }
            $this->preloadFile($file->getPathname());
        }

    }
}

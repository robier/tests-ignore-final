<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal;

use Robier\Tests\IgnoreFinal\ClassToPath\Contract;

/**
 * @internal
 */
final class Autoloader
{
    /**
     * @var Contract
     */
    private $classLoader;

    /**
     * @var string[]
     */
    private $classes;

    /**
     * @var bool
     */
    private $enableGlobally;

    /**
     * @var bool
     */
    private $inplace;

    public function __construct(Contract $classLoader, bool $enableGlobal, bool $inplace, string ...$classes)
    {
        $this->classLoader = $classLoader;
        $this->enableGlobally = $enableGlobal;
        $this->inplace = $inplace;
        $this->classes = array_combine($classes, $classes);
    }

    public function classes(): array
    {
        return $this->classes;
    }

    public function isGloballyEnabled(): bool
    {
        return $this->enableGlobally;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function __invoke(string $class)
    {
        if (!$this->enableGlobally && !isset($this->classes[$class])) {
            return;
        }

        $path = $this->classLoader->get($class);

        if (null === $path) {
            if ($this->enableGlobally) {
                return;
            }
            throw Exception::classNotFound($class);
        }

        $this->loadFile($path);
    }

    /**
     * @throws Exception
     */
    public function loadFile(string $path): void
    {
        if (!file_exists($path)) {
            throw Exception::fileNotFound($path);
        }

        $fileContents = file_get_contents($path);

        $final = false;
        $newCode = '';
        foreach (token_get_all($fileContents) as $item) {
            if (is_array($item)) {
                if ($item[0] === T_FINAL) {
                    $item[1] = '';
                    $final = true;
                }

                $item = $item[1];
            }
            $newCode .= $item;
        }

        if (!$final) {
            return;
        }

        $hash = sha1_file($path);
        $newName = $this->inplace ? $path : dirname(realpath($path)) . '/' . $hash . '.php';

        file_put_contents($newName, $newCode);

        require_once $newName;

        if ($this->inplace) {
            file_put_contents($path, $fileContents);
        } else {
            unlink($newName);
        }
    }
}

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

    public function __construct(Contract $classLoader, string ...$classes)
    {
        $this->classLoader = $classLoader;
        $this->classes = array_combine($classes, $classes);
    }

    public function classes(): array
    {
        return $this->classes;
    }

    /**
     * @return void
     */
    public function __invoke(string $class)
    {
        if (!isset($this->classes[$class])) {
            return;
        }

        $path = $this->classLoader->get($class);

        if (null === $path) {
            throw Exception::classNotFound($class);
        }

        $fileContents = file_get_contents($path);
        $hash = sha1_file($path);

        $newName = $hash . '.php';

        $newCode = '';
        foreach (token_get_all($fileContents) as $item) {
            if (is_array($item)) {
                if ($item[0] === T_FINAL) {
                    $item[1] = '';
                }

                $item = $item[1];
            }
            $newCode .= $item;
        }

        file_put_contents($newName, $newCode);

        require_once $newName;
        unlink($newName);
    }
}

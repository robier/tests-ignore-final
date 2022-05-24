<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal\ClassToPath;

use Composer\Autoload\ClassLoader;
use Robier\Tests\IgnoreFinal\Exception;

/**
 * @internal
 */
final class Composer implements Contract
{
    /**
     * @var ClassLoader
     */
    private $loader;

    public function __construct()
    {
        $path = dirname(__DIR__, 4) . '/autoload.php';

        if (!is_file($path)) {
            throw Exception::fileNotFound($path);
        }

        $this->loader = require $path;
    }

    public function get(string $class): ?string
    {
        $response = $this->loader->findFile($class);

        if (false === $response) {
            return null;
        }

        return $response;
    }
}

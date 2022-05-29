<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal;

final class Exception extends \Exception
{
    static function fileNotFound(string $path): self
    {
        return new static('File not find: ' . $path);
    }

    static function classNotFound(string $class): self
    {
        return new static(sprintf('Class %s path not find in autoloader', $class));
    }

    static function pathNotFound(string $path): self
    {
        return new static(sprintf('Path %s does not exist', $path));
    }
}

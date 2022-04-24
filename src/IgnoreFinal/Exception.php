<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal;

final class Exception extends \Exception
{
    static function fileNotFound(string $path): self
    {
        return new static('File not find: ' . $path);
    }
}

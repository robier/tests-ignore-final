<?php

declare(strict_types=1);

namespace Robier\Tests\IgnoreFinal\ClassToPath;

/**
 * @internal
 */
final class Map implements Contract
{
    /**
     * @var array
     */
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function get(string $class): ?string
    {
        if (isset($this->map[$class])) {
            return $this->map[$class];
        }

        return null;
    }
}

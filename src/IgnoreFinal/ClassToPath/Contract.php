<?php

namespace Robier\Tests\IgnoreFinal\ClassToPath;

/**
 * @internal
 */
interface Contract
{
    /**
     * Returns full path of the class
     */
    public function get(string $class): ?string;
}

Mock final classes 
------------------

This library allows you to mock classes that have `final` keyword in their signature. This
is accomplished by hooking up into autoload process and changing source code of the class on
the fly.

This library was inspired by [dg/bypass-finals](https://github.com/dg/bypass-finals) library.
I wanted to have same functionally but with different approach. I hope that this different
approach will not have the same problems as dg/bypass-finals have with infection for example.

### Usage

Let's say you have this class that you want to mock:
```php
<?php
# ./FooBar.php
final class FooBar {

}
```

Only thing what you need to do in your test is this:
```php
<?php

use PHPUnit\Framework\TestCase;
use Robier\Tests\IgnoreFinals;

class FooBarTest extends TestCase
{
    public function testSomething(): void
    {
        IgnoreFinal::composer(FooBar::class);
    }
}
```

Class `IgnoreFinal` will find the source code of the class we want to mock, and it will remove all `final` keywords
from source code and load that modified source code instead of real one.

### Tests

First run `docker/build` to build a container and then `docker/run composer run test` for running all tests.

### Contribution

Feel free to contribute!

<?php

namespace Test;

use Nette;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';

class BaseTest extends Tester\TestCase
{
    private $container;

    function __construct(Nette\DI\Container $container)
    {
        $this->container = $container;
    }

}

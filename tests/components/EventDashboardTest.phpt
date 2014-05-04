<?php
namespace Test\Components;

use Nette;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../bootstrap.php';

class EventDashboardTest extends Tester\TestCase
{
    private $container;

    /* @var $variable \Sandra\Components\EventDashboard */
    private $eventDashboard;
    
    function __construct(Nette\DI\Container $container)
    {
        $this->container = $container;
        $presenterFactory = $this->container->getByType('Nette\Application\PresenterFactory');
        $presenter = $presenterFactory->createPresenter('Default');
        $this->eventDashboard = $presenter['eventDashboard'];
    }

    
}


$test = new EventDashboardTest($container);
$test->run();
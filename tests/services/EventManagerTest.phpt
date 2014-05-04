<?php
namespace Test\Components;

use Nette;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/../bootstrap.php';

class EventManagerTest extends Tester\TestCase
{
    private $container;

    /* @var $variable \Sandra\Services\EventManager */
    private $eventManager;
    
    function __construct(Nette\DI\Container $container)
    {
        $this->container = $container;
        $this->eventManager = $this->container->getByType('Sandra\Services\EventManager');
    }

    function testGetDateTypeOfReturnValue() 
    {
        $fromDate = new \DateTime('2014-10-05');
        $result = $this->eventManager->getDateOfPayment($fromDate, 13);
        
        Assert::same('DateTime', get_class($result));
    } 
    
    function testGetDateOfPaymentEarlier() 
    {
        $fromDate = new \DateTime('2014-10-05');
        $result = $this->eventManager->getDateOfPayment($fromDate, 13);
        
        Assert::same('2014-10-13', $result->format('Y-m-d'));        
    }
    
    function testGetDateOfPaymentEqualDates() 
    {
        $fromDate = new \DateTime('2014-10-05');
        $result = $this->eventManager->getDateOfPayment($fromDate, 5);

        Assert::same('2014-10-05', $result->format('Y-m-d'));        
    }
    
    function testGetDateOfPaymentLater() 
    {
        $fromDate = new \DateTime('2014-10-14');
        $result = $this->eventManager->getDateOfPayment($fromDate, 13);
        
        Assert::same('2014-11-13', $result->format('Y-m-d'));        
    }
    
}


$test = new EventManagerTest($container);
$test->run();
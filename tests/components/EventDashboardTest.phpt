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

    function testBillablePeriodIsArray() 
    {
        $refDate = new \DateTime('2014-10-05');
        $dates = $this->eventDashboard->getBillablePeriod(10, $refDate);
        
        Assert::true(is_array($dates));
        Assert::same(2, count($dates));        
    } 
    
    /**
     * Ref date 5.10.2014
     * Period start date = 10
     * 
     * Billing period should be:
     * - from 10.9.2014
     * - to   9.10.2014
     */
    function testBillablePeriodWithEarlierRefDate() 
    {
        $refDate = new \DateTime('2014-10-05');
        $dates = $this->eventDashboard->getBillablePeriod(10, $refDate);
                
        Assert::same('2014-09-10', $dates[0]->format('Y-m-d'));
        Assert::same('2014-10-09', $dates[1]->format('Y-m-d'));
    }
    
    /**
     * Ref date 10.10.2014
     * Period start date = 10
     * 
     * Billing period should be:
     * - from 10.10.2014
     * - to   9.11.2014
     */
    function testBillablePeriodWithEqualRefDate() 
    {
        $refDate = new \DateTime('2014-10-10');
        $dates = $this->eventDashboard->getBillablePeriod(10, $refDate);
                
        Assert::same('2014-10-10', $dates[0]->format('Y-m-d'));
        Assert::same('2014-11-09', $dates[1]->format('Y-m-d'));
    }
    
    /**
     * Ref date 12.10.2014
     * Period start date = 10
     * 
     * Billing period should be:
     * - from 10.10.2014
     * - to   9.11.2014
     */
    function testBillablePeriodWithLaterRefDate() 
    {
        $refDate = new \DateTime('2014-10-12');
        $dates = $this->eventDashboard->getBillablePeriod(10, $refDate);
                
        Assert::same('2014-10-10', $dates[0]->format('Y-m-d'));
        Assert::same('2014-11-09', $dates[1]->format('Y-m-d'));
    }
    
    /**
     * Ref date 24.12.2014
     * Period start date = 20
     * 
     * Billing period should be:
     * - from 20.12.2014
     * - to   19.1.2015
     */
    function testBillablePeriodNewYear() 
    {
        $refDate = new \DateTime('2014-12-24');
        $dates = $this->eventDashboard->getBillablePeriod(20, $refDate);
                
        Assert::same('2014-12-20', $dates[0]->format('Y-m-d'));
        Assert::same('2015-01-19', $dates[1]->format('Y-m-d'));
    }
    
    /**
     * Ref date 1.1.2015
     * Period start date = 6
     * 
     * Billing period should be:
     * - from 6.12.2014
     * - to   5.1.2015
     */
    function testBillablePeriodPreviousYear() 
    {
        $refDate = new \DateTime('2015-01-01');
        $dates = $this->eventDashboard->getBillablePeriod(6, $refDate);
                
        Assert::same('2014-12-06', $dates[0]->format('Y-m-d'));
        Assert::same('2015-01-05', $dates[1]->format('Y-m-d'));
    }
    
    function thisShouldThrowAnException() 
    {
        $refDate = new \DateTime('2014-02-01');
        $this->eventDashboard->getBillablePeriod(29, $refDate);
    }
    
    function testBillablePeriodMaxDate() 
    {
        Assert::exception(
            [$this, 'thisShouldThrowAnException'],
            'Nette\InvalidArgumentException'
        );
    }
}


$test = new EventDashboardTest($container);
$test->run();
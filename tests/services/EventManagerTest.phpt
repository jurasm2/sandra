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
    
    
    function testBillingPeriodIsArray() 
    {
        $refDate = new \DateTime('2014-10-05');
        $dates = $this->eventManager->getBillingPeriod(10, $refDate);
        
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
    function testBillingPeriodWithEarlierRefDate() 
    {
        $refDate = new \DateTime('2014-10-05');
        $dates = $this->eventManager->getBillingPeriod(10, $refDate);
                
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
    function testBillingPeriodWithEqualRefDate() 
    {
        $refDate = new \DateTime('2014-10-10');
        $dates = $this->eventManager->getBillingPeriod(10, $refDate);
                
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
    function testBillingPeriodWithLaterRefDate() 
    {
        $refDate = new \DateTime('2014-10-12');
        $dates = $this->eventManager->getBillingPeriod(10, $refDate);
                
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
    function testBillingPeriodNewYear() 
    {
        $refDate = new \DateTime('2014-12-24');
        $dates = $this->eventManager->getBillingPeriod(20, $refDate);
                
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
    function testBillingPeriodPreviousYear() 
    {
        $refDate = new \DateTime('2015-01-01');
        $dates = $this->eventManager->getBillingPeriod(6, $refDate);
                
        Assert::same('2014-12-06', $dates[0]->format('Y-m-d'));
        Assert::same('2015-01-05', $dates[1]->format('Y-m-d'));
    }
    
    function thisShouldThrowAnException() 
    {
        $refDate = new \DateTime('2014-02-01');
        $this->eventManager->getBillingPeriod(29, $refDate);
    }
    
    function testBillingPeriodMaxDate() 
    {
        Assert::exception(
            [$this, 'thisShouldThrowAnException'],
            'Nette\InvalidArgumentException'
        );
    }
    
}


$test = new EventManagerTest($container);
$test->run();
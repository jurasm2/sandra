<?php
namespace Sandra\Components;

use Sandra;
use Sandra\Services\EventManager;
use Nette;
use Nette\ComponentModel\IContainer;
use \DateTime;

/**
 * Description of EventDashboard
 *
 * @author jurasm2
 */
class EventDashboard extends BaseComponent
{

    /**
     *
     * @var array
     */
    protected $config;
    
    /**
     *
     * @var Sandra\Services\EventManager
     */
    protected $eventManager;
    
    /**
     * Constructor
     * @param Nette\ComponentModel\IContainer $parent
     * @param type $name
     */
    public function __construct(IContainer $parent, $name, array $config, EventManager $eventManager)
    {
        parent::__construct($parent, $name);
        $this->config = $config;        
        $this->eventManager = $eventManager;
    }
    

    /**
     * @param int $startDayOfPeriod
     * @param DateTime $refDateTime
     * @return DateTime[]
     */
    public function getBillablePeriod($startDayOfPeriod, DateTime $refDateTime) 
    {
        if ($startDayOfPeriod > 28) {
            throw new \Nette\InvalidArgumentException("Maximum value for start day of period is '28'");
        }
        
        $periodFrom = $this->eventManager->getClosestInPast($refDateTime, $startDayOfPeriod);
        
        $periodEnd = clone $periodFrom;
        $periodEnd
            ->modify('+1 month')
            ->modify('-1 day');
        
        return [$periodFrom, $periodEnd];
    }       
        
    
    
    public function render()
    {
        $dateTime = new DateTime();
        $firstDayOfBillableMonth = $this->config['first_day_of_billable_month'];
        $billblePeriod = $this->getBillablePeriod($firstDayOfBillableMonth, $dateTime);
        
        // get billable period and add missing reports
        $this->eventManager->addMissingReports(
            $billblePeriod[0],
            $billblePeriod[1]
        );
        
        $this->template->setFile(__DIR__ . '/EventDashboard/currentEvents.latte');
        
        $this->template->reports = $reports = $this->eventManager->getReports(
            $billblePeriod[0],
            $billblePeriod[1]
        );
        
        echo $this->template;
    }
    
}

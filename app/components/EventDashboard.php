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

    public function handleRestoreEvent($eventId)
    {
        // TODO add confirmation
        $this->eventManager->restoreEvent($eventId);
        $this->presenter->redirect('default');
    }

    public function handleDeleteEvent($eventId)
    {
        // TODO add confirmation
        $this->eventManager->deleteEvent($eventId);
        $this->presenter->redirect('showTrash');
    }


    public function render()
    {
        $dateTime = new DateTime();
        $firstDayOfBillingablePeriod = $this->config['first_day_of_billing_period'];
        $billablePeriod = $this->eventManager->getBillingPeriod($firstDayOfBillingablePeriod, $dateTime);

        // get billable period and add missing reports
        $this->eventManager->addMissingReports(
            $billablePeriod[0],
            $billablePeriod[1]
        );

        $this->template->setFile(__DIR__ . '/EventDashboard/currentEvents.latte');

        $this->template->reports = $reports = $this->eventManager->getReports(
            $billablePeriod[0],
            $billablePeriod[1]
        );

        $this->template->startPeriod = $billablePeriod[0];
        $this->template->endPeriod = $billablePeriod[1];

        echo $this->template;
    }

    public function renderTrash()
    {
        $this->template->setFile(__DIR__ . '/EventDashboard/trashedEvents.latte');
        $this->template->events = $this->eventManager->getTrashedEvents();

        echo $this->template;
    }

}


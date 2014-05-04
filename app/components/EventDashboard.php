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

    protected function getReportState(array $report)
    {
        $reportState = 'ok';

        if (!$report['paid']) {
            if ($report['date_of_payment'] < new DateTime) {
                $reportState = 'passed';
            } else {
                $reportState = 'pending';
            }
        }

        return $reportState;
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

        $reports = $this->eventManager->getReports(
            $billablePeriod[0],
            $billablePeriod[1]
        );


        $templateReports = [];
        foreach ($reports as $report) {
            $templateReport = (array) $report;
            // add icon
            // paid nad past -> ok
            $templateReport['state'] = $this->getReportState($templateReport);
            $templateReports[] = $templateReport;
        }
        $this->template->reports = $templateReports;
        $this->template->tb = $this->config['twitter_bootstrap'];

        $this->template->startPeriod = $billablePeriod[0];
        $this->template->endPeriod = $billablePeriod[1];

        $this->template->progressBarStatus = $this->getProgressBarStatus(
            $billablePeriod[0],
            $billablePeriod[1],
            new DateTime);

        echo $this->template;
    }

    public function getProgressBarStatus(
        DateTime $startDateTime,
        DateTime $endDateTime,
        DateTime $currentDateTime
    ) {

        $period = $startDateTime->diff($endDateTime);
        $current = $startDateTime->diff($currentDateTime);

        return round(100 * ($current->days / $period->days));


    }

    public function renderTrash()
    {
        $this->template->setFile(__DIR__ . '/EventDashboard/trashedEvents.latte');
        $this->template->events = $this->eventManager->getTrashedEvents();

        echo $this->template;
    }

}


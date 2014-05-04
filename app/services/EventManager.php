<?php
namespace Sandra\Services;

use Sandra\Model\EventModel;
use DateTime;

/**
 * Description of EventManager
 *
 * @author jurasm2
 */
class EventManager extends BaseService
{
    
    /**
     * @var EventModel
     */
    protected $eventModel;
    
    public function __construct(EventModel $events) 
    {
        $this->eventModel = $events;
    }
    
    public function monthize(DateTime $dateTime, $dayInMonth) 
    {
        return new DateTime(
            sprintf(
                '%s-%s', 
                $dateTime->format('Y-m'),
                $dayInMonth
            )
        );
    }
    
    /**
     * Returns closest datetime in the past relatively to $reference
     * if DATE($reference) == $dayInMonth -> return $reference
     * @param DateTime $reference
     * @param int $dayInMonth
     * @return DateTime
     */
    public function getClosestInPast(DateTime $reference, $dayInMonth) 
    {
        $date = $this->monthize($reference, $dayInMonth);        
        if ($date > $reference) {
            $date->modify('-1 month');
        }        
        return $date;
    }
    
    /**
     * Returns closest datetime in the past relatively to $reference
     * if DATE($reference) == $dayInMonth -> return $reference
     * @param DateTime $reference
     * @param type $dayInMonth
     * @return DateTime
     */
    public function getClosestInFuture(DateTime $reference, $dayInMonth) 
    {
        $date = $this->monthize($reference, $dayInMonth);        
        if ($date < $reference) {
            $date->modify('+1 month');
        }        
        return $date;
    }
    
    
    /**
     * 
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @param int $dayInMonth
     * @return DateTime
     */
    public function getDateOfPayment(DateTime $fromDate, $dayInMonth) 
    {
        return $this->getClosestInFuture($fromDate, $dayInMonth);
    }
    
    public function insertEventToReports(DateTime $fromDate, array $event) 
    {
        $reportData = [
            'event_id' => $event['id'],
            'date_of_payment' => $this->getClosestInFuture($fromDate, $event['day_in_month']),
        ];
       
        $this->eventModel->createReport($reportData);
    }
    
    public function addMissingReports(DateTime $fromDate, DateTime $toDate) 
    {
        $allEvents = $this->eventModel->getAllActiveEvents($fromDate, $toDate);
        $currentReports = $this->eventModel->getReportsInInterval($fromDate, $toDate);
               
        dump($currentReports);
        
//        if ($allEvents) {
//            foreach ($allEvents as $event) {
//                if (!isset($currentReports[$event->id])) {
//                    // add missing event
//                    dump('adding missing event because the list of current reports does not contain event id: ' . $event->id);
//                    dump($currentReports);
//                    $this->insertEventToReports($fromDate, $event->toArray());
//                }
//            }
//        }
    }
        
    public function createEvent(array $data) 
    {
        return $this->eventModel->createEvent($data);
    }
    
    public function updateEvent(array $data) 
    {
        $reportId = $data['report_id'];
        $report = $this->getReport($reportId);
        
        $eventId = $report['event_id'];
        
    
        // update event
        $eventData = [
            'title' => $data['title'],
            'day_in_month' => $data['day_in_month'],
            'amount' => $data['amount'],
            'trashed' => $data['trashed'],
        ];
        $this->eventModel->updateEvent($eventData, $eventId);
        
        // update report
        
        
        // date of payment modification
        
        
        $reportData = [
            'paid' => $data['paid'],
            'date_of_payment' => $this->getDateOfPayment(new \DateTime, $data['day_in_month']),  
        ];
        $this->eventModel->updateReport($reportData, $reportId);
    }
    
    
    public function getDefaultValuesForEditForm($reportId) 
    {
        return $this->eventModel->getDefaultValuesForEditForm($reportId);
    }
    
    public function getReport($reportId) 
    {
        return $this->eventModel->getReport($reportId);
    }
        
    
    public function getReports(DateTime $fromDate, DateTime $toDate) 
    {
        return $this->eventModel->getReportsInInterval($fromDate, $toDate);
    }
    
}

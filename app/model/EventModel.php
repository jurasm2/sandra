<?php
namespace Sandra\Model;

use Nette;

use DateTime;

/**
 * Event model
 */
class EventModel extends Nette\Object
{

    /** @var Nette\Database\Context */
    private $database;

    /**
     * Constructor
     * @param Nette\Database\Context $database
     */
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    /**
     * Created resource
     * @param array $data
     * @return int Primary key of inserted resource
     */
    public function createEvent(array $data)
    {
        return $this->database->table('events')->insert($data);
    }

    public function getEvent($eventId)
    {
        return $this->database->table('events')->select('*')->where('id = ?', $eventId)->fetch();
    }

    public function createReport(array $data)
    {
        return $this->database->table('reports')->insert($data);
    }

    public function getAllEvents()
    {
        return $this->database->table('events')->select('*')->fetchAll();
    }

    public function getAllActiveEvents()
    {
        return $this->database->table('events')->select('*')->where('trashed = ?', 0)->fetchAll();
    }

    public function getReportsInInterval(DateTime $from, DateTime $to)
    {
        return $this->database->query(
            'SELECT r.*, e.id as eid, e.trashed, e.title, e.payment_method '
            . 'FROM reports r '
            . 'JOIN events e '
            . 'ON r.event_id = e.id '
            . 'WHERE DATE(date_of_payment) >= ? AND DATE(date_of_payment) <= ? AND trashed = 0 '
            . 'ORDER BY date_of_payment ASC',
            $from, $to)->fetchPairs('event_id');
    }

    public function getReport($reportId)
    {
        return $this->database->table('reports')->select('*')->where('id = ?', $reportId)->fetch();
    }


    public function updateEvent(array $data, $eventId)
    {
        $this->database->table('events')->where('id = ?', $eventId)->update($data);
    }

    public function updateReport(array $data, $reportId)
    {
        return $this->database->table('reports')->where('id = ?', $reportId)->update($data);
    }


    public function getDefaultValuesForEditForm($reportId)
    {
        $report = $this->database
            ->table('reports')
            ->select('event_id,paid')
            ->where('id = ?', $reportId)
            ->fetch();
        $reportData = $report->toArray();
        $eventData = $report->ref('event_id')->toArray();
        unset($eventData['id']);
        return $reportData + $eventData;
    }

    public function getTrashedEvents()
    {
        return $this->database->table('events')->select('*')->where('trashed = ?', 1)->fetchAll();
    }

}


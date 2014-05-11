<?php
namespace Sandra\Presenters;

use Sandra\Services\EmailManager;

/**
 * Description of CronPresenter
 *
 * @author jurasm2
 */
class CronPresenter extends BasePresenter
{

    /**
     *
     * @var EmailManager;
     */
    protected $emailManager;

    public function injectEmailManager(EmailManager $emailManager)
    {
        $this->emailManager = $emailManager;
        $this->emailManager->setPresenter($this);
    }

    public function actionDefault()
    {
        // get all reports with actual day of payment
        $events = $this->eventManager->getAllCurrentEvents();

        $interestingEvents = array_filter($events, function($event) {
            return $event->paid == 0 && $event->payment_method == 'manual';
        });

        $this->emailManager->sendEventMessages($interestingEvents);
    }

    public function actionSetEventAsPaid($id)
    {
        $result = $this->eventManager->setEventAsPaid($id);
        if ($result) {
            $this->flashMessage('Platba byla označena jako zaplacena.');
        } else {
            $this->flashMessage('Platbu se nepovedlo označenit jako zaplacenou.', 'warning');
        }
        $this->redirect('Default:default');
    }


}

<?php
namespace Sandra\Components\Forms;

use Sandra\Services\EventManager;

/**
 * Description of EditEventForm
 *
 * @author jurasm2
 */
class EditEventForm extends BaseForm
{
    /**
     * @var \Sandra\Services\EventManager
     */
    protected $eventManager;

    public function __construct(
        $parent,
        $name,
        EventManager $eventManager,
        $firstDayOfBillingPeriod
    ) {
        parent::__construct($parent, $name);
        $this->eventManager = $eventManager;

        $reportId = $this->presenter->request->isPost()
            ? $this->presenter->request->post['report_id']
            : $this->presenter->request->parameters['report_id'];

        $defaultValues = $this->eventManager->getDefaultValuesForEditForm($reportId);


        $this->addText('title', 'Title');
        $this->addText('day_in_month', 'Day in month');
        $this->addText('amount', 'Amount');

        $this->addCheckbox('trashed', 'Trashed');
        $this->addCheckbox('paid', 'Paid');
        $this->addHidden('report_id', $reportId);
        $this->addHidden('first_day_of_billing_period', $firstDayOfBillingPeriod);
        $this->addHidden('update_reference', date('Y-m-d'));

        $this->addSubmit('submit', 'Update event');

        $this->setDefaults($defaultValues);
        $this->onSuccess[] = array($this, 'formSubmitted');
    }

    public function formSubmitted(EditEventForm $form)
    {
        /* @var $formValues ArrayHash */
        $formValues = $form->getValues();
        $this->eventManager->updateEvent((array) $formValues);

        $this->presenter->redirect('default');
    }
}

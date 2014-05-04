<?php
namespace Sandra\Components\Forms;

use Sandra\Services\EventManager;

/**
 * Description of AddForm
 *
 * @author jurasm2
 */
class AddEventForm extends BaseForm
{
    /**
     * @var \Sandra\Services\EventManager;
     */
    protected $eventManager;

    public function __construct($parent, $name, EventManager $eventManager)
    {
        parent::__construct($parent, $name);
        $this->eventManager = $eventManager;

        $this->addText('title', 'Title');
        $this->addText('day_in_month', 'Day in month');
        $this->addText('amount', 'Amount');

        $this->addCheckbox('trashed', 'Trashed');

        $this->addSubmit('submit', 'Create event');

        $this->onSuccess[] = array($this, 'formSubmitted');
    }

    public function formSubmitted(AddEventForm $form)
    {
        /* @var $formValues ArrayHash */
        $formValues = $form->getValues();
        $result = $this->eventManager->createEvent((array) $formValues);

        $this->presenter->redirect('default');
    }
}

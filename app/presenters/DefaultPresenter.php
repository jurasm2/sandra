<?php
namespace Sandra\Presenters;

use Sandra;
use Sandra\Components\EventDashboard;
use Sandra\Components\Forms\AddEventForm;
use Sandra\Components\Forms\EditEventForm;
use Sandra\Services\EventManager;

/**
 * Homepage presenter.
 */
class DefaultPresenter extends BasePresenter
{
    /**
     * @inject
     * @var Sandra\Services\EventManager
     */
    protected $eventManager;

    /**
     *
     * @param \Sandra\Services\EventManager $eventManager
     */
    public function injectEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     *
     * @param type $name
     * @return \Sandra\Components\EventDashboard
     */
    protected function createComponentEventDashboard($name)
    {
        return new EventDashboard(
            $this,
            $name,
            $this->context->parameters['event_dashboard_config'],
            $this->eventManager
        );
    }

    /**
     *
     * @param type $name
     * @return \Sandra\Components\Forms\AddEventForm
     */
    protected function createComponentAddEventForm($name)
    {
        return new AddEventForm($this, $name, $this->eventManager);
    }

    /**
     *
     * @param type $name
     * @return \Sandra\Components\Forms\EditEventForm
     */
    protected function createComponentEditEventForm($name)
    {
        return new EditEventForm(
            $this,
            $name,
            $this->eventManager,
            $this->context->parameters['event_dashboard_config']['first_day_of_billing_period']);
    }
}

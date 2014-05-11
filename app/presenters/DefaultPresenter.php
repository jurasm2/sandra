<?php
namespace Sandra\Presenters;

use Sandra\Components\EventDashboard;
use Sandra\Components\Forms\AddEventForm;
use Sandra\Components\Forms\EditEventForm;

/**
 * Homepage presenter.
 */
class DefaultPresenter extends BasePresenter
{
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
        return new AddEventForm(
            $this,
            $name,
            $this->eventManager,
            $this->context->parameters['event_dashboard_config']['payment_methods']
        );
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
            $this->context->parameters['event_dashboard_config']['first_day_of_billing_period'],
            $this->context->parameters['event_dashboard_config']['payment_methods']);
    }

    public function actionDefault()
    {
//        dump($this->template);
//        die();
    }
}

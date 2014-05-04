<?php
namespace Sandra\Presenters;

use Sandra;
use Sandra\Components\EventDashboard;
use Sandra\Components\Forms\AddEventForm;
use Sandra\Components\Forms\EditEventForm;
use Sandra\Model\EventModel;
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
    
    public function injectEventManager(EventManager $eventManager) 
    {
        $this->eventManager = $eventManager;
    }
    
    protected function createComponentEventDashboard($name) 
    {
        return new EventDashboard(
            $this, 
            $name,
            $this->context->parameters['event_dashboard_config'],
            $this->eventManager
        );
    }
    
    protected function createComponentAddEventForm($name) 
    {
        return new AddEventForm($this, $name, $this->eventManager);
    }
    
    protected function createComponentEditEventForm($name) 
    {
        return new EditEventForm($this, $name, $this->eventManager);
    }
}

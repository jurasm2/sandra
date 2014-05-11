<?php

namespace Sandra\Presenters;

use Sandra\Services\EventManager;

use Nette;


/**
 * Base presenter for all application presenters.
 * @property EventManager $eventManager Event manager
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    /**
     * @inject
     * @var EventManager
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

}

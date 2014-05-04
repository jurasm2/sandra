<?php
namespace Sandra\Components;

use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;

/**
 *
 */
abstract class BaseComponent extends Control
{
    /**
     *
     * @var \Nette\Templating\Template
     */
    protected $template;

    /**
     * Constructor
     * @param \Nette\ComponentModel\IContainer $parent
     * @param type $name
     */
    public function __construct(IContainer $parent, $name)
    {
        parent::__construct($parent, $name);
        $this->template = $this->createTemplate();
        $this->template->registerHelper('timeAgeInWords', 'Sandra\Utils\Helpers::timeAgoInWords');
    }

}

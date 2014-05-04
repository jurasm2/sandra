<?php
namespace Sandra\Components\Forms;

use Nette;

/**
 *
 */
class BaseForm extends Nette\Application\UI\Form
{
    public function __construct($parent, $name)
    {
        parent::__construct($parent, $name);

        // ajaxify the form
        // $this->getElementPrototype()->class = 'ajax';
    }
}

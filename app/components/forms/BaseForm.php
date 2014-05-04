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

        $this->getElementPrototype()->class = 'form-inline';

        // ajaxify the form
        // $this->getElementPrototype()->class = 'ajax';
    }

    public function addText($name, $label = NULL, $cols = NULL, $maxLength = NULL) {
        $text = parent::addText($name, $label, $cols, $maxLength);

        $controlPrototype = $text->getControlPrototype();
        $controlPrototype->class = "form-control";
        $controlPrototype->placeholder = $label;

        $text->getLabelPrototype()->class = "sr-only";
        return $text;
    }

    public function addSubmit($name, $caption = NULL) {
        $submit = parent::addSubmit($name, $caption);
        $submit->getControlPrototype()->class = 'btn btn-default';
        return $submit;
    }

}

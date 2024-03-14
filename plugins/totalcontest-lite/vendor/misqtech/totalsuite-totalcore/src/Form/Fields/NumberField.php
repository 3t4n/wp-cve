<?php
namespace TotalContestVendors\TotalCore\Form\Fields;

use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;

class NumberField extends FieldAbstract
{
    public function getValidationsRules()
    {
        return ['number' => ['enabled' => true]] + parent::getValidationsRules();
    }


    /**
     * @return Html
     */
    public function getInputHtmlElement()
    {
        $field = new Html('input', $this->getAttributes());
        $field->appendToAttribute('class', Application::getInstance()->env('slug').'-form-field-input');

        return $field;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['value'] = $this->getValue();
        $attributes['type'] = $this->getType();

        return $attributes;
    }
    public function getType() {
        return 'number';
    }
}
<?php
namespace TotalContest\Form\Fields;


use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Form\Field;
use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;

class EmbedField extends FieldAbstract
{
    /**
     * @inheritDoc
     */
    public function getInputHtmlElement()
    {
        $rules = $this->getOption( 'validations', [] );

        $field = new Html('input', $this->getAttributes());
        $field->appendToAttribute('class', Application::getInstance()->env('slug') . '-form-field-input');

        if ( ! empty( $rules['uploadedVia']['enabled'] ) ):
            $acceptedServices = empty( $rules['uploadedVia']['services'] ) ? [] : (array) $rules['uploadedVia']['services'];
            $acceptedServices = array_filter( $acceptedServices );
            $field->setAttribute('placeholder', implode( ', ', array_map( 'ucfirst', array_keys( $acceptedServices ) ) ));
        endif;

        return $field;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $attributes['value'] = $this->getValue();

        return $attributes;
    }
}

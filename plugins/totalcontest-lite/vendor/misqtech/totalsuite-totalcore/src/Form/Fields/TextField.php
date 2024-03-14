<?php

namespace TotalContestVendors\TotalCore\Form\Fields;


use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;

/**
 * Class TextField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class TextField extends FieldAbstract {

	/**
	 * @return Html
	 */
	public function getInputHtmlElement() {
		$field = new Html( 'input', $this->getAttributes() );
		$field->appendToAttribute( 'class', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) . '-form-field-input' );

		if ( $field->getAttribute( 'type' ) === 'hidden' ):
			$this->template = '<div class="' . \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) . "-form-field-hidden\">{$this->template}</div>";
		endif;

		return $field;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		$attributes          = parent::getAttributes();
		$attributes['value'] = $this->getValue();
		$attributes['type']  = empty( $attributes['type'] ) ? 'text' : $attributes['type'];

		return $attributes;
	}
}
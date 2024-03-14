<?php

namespace TotalContestVendors\TotalCore\Form\Fields;


use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;
use TotalContestVendors\TotalCore\Http\File;

/**
 * Class FileField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class FileField extends FieldAbstract {

	/**
	 * @return Html
	 */
	public function getInputHtmlElement() {
		/**
		 * @var Html $field
		 */
		$field = new Html( 'input', $this->getAttributes() );
		$field->setAttribute( 'type', 'file' );
		$field->appendToAttribute( 'class', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) . '-form-field-input' );

		return $field;
	}

	/**
	 * @return null|File
	 */
	public function getValue() {
		return $this->value;
	}
}

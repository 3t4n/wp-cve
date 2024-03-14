<?php

namespace TotalContestVendors\TotalCore\Form\Fields;


use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;

/**
 * Class SelectField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class SelectField extends FieldAbstract {

	/**
	 * @return Html
	 */
	public function getInputHtmlElement() {
		/**
		 * @var Html $field
		 */
		$field        = new Html( 'select', $this->getAttributes() );
		$currentValue = (array) $this->getValue();
		$options      = (array) $this->getOption( 'options', [] );

		if ( ! array_key_exists( 'multiple', $this->getAttributes() ) ):
			$options = [ '' => __( 'Choose', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) ) ] + $options;
		endif;

		if ( ! empty( $options ) ):
			foreach ( $options as $value => $caption ):
				$valueSanitized = sanitize_title_with_dashes( $value );

				$optionElement = new Html(
					'option',
					[
						'value' => $value,
						'class' => "option-{$valueSanitized}",
					],
					esc_html( $caption )
				);
				if ( in_array( $value, $currentValue ) ):
					$optionElement->setAttribute( 'selected', 'selected' );
				endif;

				$field->appendToInner( $optionElement );
			endforeach;
		endif;

		$field->appendToAttribute( 'class', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) . '-form-field-input' );

		return $field;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		$attributes = array_diff_key( parent::getAttributes(), array_flip( [ 'value' ] ) );

		return $attributes;
	}
}
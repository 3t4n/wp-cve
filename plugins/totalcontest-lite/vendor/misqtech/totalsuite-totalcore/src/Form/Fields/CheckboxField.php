<?php

namespace TotalContestVendors\TotalCore\Form\Fields;


use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;

/**
 * Class CheckboxField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class CheckboxField extends FieldAbstract {

	/**
	 * @return mixed
	 */
	public function getLabelHtmlElement() {
		return isset( $this->options['options'] ) ? parent::getLabelHtmlElement() : '';
	}

	/**
	 * @return mixed
	 */
	public function getInputHtmlElement() {
		$slug      = \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' );
		$container = new \TotalContestVendors\TotalCore\Helpers\Html(
			'div',
			[ 'class' => "{$slug}-form-field-checkbox" ]
		);

		$options = (array) $this->getOption( 'options', [] );

		if ( ! empty( $options ) ):
			$currentValue = (array) $this->getValue() ?: [];
			foreach ( $options as $value => $caption ):
				$valueSanitized = sanitize_title_with_dashes( $value );
				$id             = sanitize_title_with_dashes( "{$this->getName()}-checkbox-{$valueSanitized}" );

				$checkBoxContainer = new \TotalContestVendors\TotalCore\Helpers\Html(
					'div',
					[ 'class' => "{$slug}-form-field-checkbox-item" ]
				);
				$label             = new \TotalContestVendors\TotalCore\Helpers\Html(
					'label',
					[
						'for'   => $id,
						'class' => "{$slug}-form-field-label",
					],
					$caption
				);
				$checkBox          = new \TotalContestVendors\TotalCore\Helpers\Html(
					'input',
					[
						'type'  => 'checkbox',
						'name'  => $this->getOption( 'name' ),
						'id'    => $id,
						'value' => $value,
						'class' => "{$slug}-form-field-input option-{$valueSanitized}",
					],
					$label
				);

				if ( in_array( $value, $currentValue ) ):
					$checkBox->setAttribute( 'checked', 'checked' );
				endif;

				$checkBoxContainer->appendToInner( $checkBox );
				$container->appendToInner( $checkBoxContainer );
			endforeach;
		endif;

		return $container;
	}
}

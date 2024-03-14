<?php

namespace TotalContestVendors\TotalCore\Form\Fields;


use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;
use TotalContestVendors\TotalCore\Helpers\Html;

/**
 * Class RadioField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class RadioField extends FieldAbstract {

	/**
	 * @return Html
	 */
	public function getInputHtmlElement() {
		$slug      = \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' );
		$container = new Html(
			'div',
			[ 'class' => "{$slug}-form-field-radio" ]
		);

		$options = (array) $this->getOption( 'options', [] );

		if ( ! empty( $options ) ):
			$currentValue = $this->getValue();
			foreach ( $options as $value => $caption ):
				$valueSanitized = sanitize_title_with_dashes( $value );
				$id             = sanitize_title_with_dashes( "{$this->getName()}-radio-{$valueSanitized}" );

				$radioBoxContainer = new \TotalContestVendors\TotalCore\Helpers\Html(
					'div',
					[ 'class' => "{$slug}-form-field-checkbox-item" ]
				);
				$label             = new Html(
					'label',
					[ 'for' => $id, 'class' => "{$slug}-form-field-label" ],
					$caption
				);
				$radioBox          = new Html(
					'input',
					[
						'type'  => 'radio',
						'name'  => $this->getOption( 'name' ),
						'id'    => $id,
						'value' => $value,
						'class' => "{$slug}-form-field-input option-{$valueSanitized}",
					],
					$label
				);

				if ( $currentValue !== null && $currentValue === $value ):
					$radioBox->setAttribute( 'checked', true );
				endif;

				$radioBoxContainer->appendToInner( $radioBox );
				$container->appendToInner( $radioBox );
			endforeach;
		endif;

		return $container;
	}
}

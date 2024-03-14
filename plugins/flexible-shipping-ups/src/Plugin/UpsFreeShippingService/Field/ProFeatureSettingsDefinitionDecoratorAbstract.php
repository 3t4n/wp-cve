<?php
/**
 * Class ProFeatureSettingsDefinitionDecoratorAbstract
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\DefinitionModifier\SettingsDefinitionModifierAfter;

/**
 * .
 */
abstract class ProFeatureSettingsDefinitionDecoratorAbstract extends SettingsDefinitionModifierAfter {

	/**
	 * @param string $label .
	 *
	 * @return string
	 */
	protected function get_label( string $label ): string {
		return sprintf( '%s<br/><small>%s</small>', $label, __( '(PRO feature)', 'flexible-shipping-ups' ) );
	}

	/**
	 * @return string
	 */
	protected function get_pro_field_class(): string {
		return 'js--pro-option';
	}

	/**
	 * @return string
	 */
	protected function get_pro_title_class(): string {
		return 'js--pro-title';
	}

	/**
	 * @return string[]
	 */
	protected function get_pro_attributes(): array {
		return [
			'disabled' => 'disabled',
		];
	}
}

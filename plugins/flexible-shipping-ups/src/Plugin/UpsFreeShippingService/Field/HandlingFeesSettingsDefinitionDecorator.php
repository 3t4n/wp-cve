<?php
/**
 * Class HandlingFeesSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

class HandlingFeesSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_HANDLING_FEES = 'handling_fees';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_HANDLING_FEES, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'             => $this->get_label( __( 'Handling Fees', 'flexible-shipping-ups' ) ),
			'type'              => 'select',
			'description'       => __( 'Use this option to apply the handling fees to the rates. You can use either fixed or percentage values, including the negative ones for discounts.', 'flexible-shipping-ups' ),
			'custom_attributes' => $this->get_pro_attributes(),
			'class'             => $this->get_pro_field_class(),
			'default'           => '',
			'options'           => [
				__( 'Percentage', 'flexible-shipping-ups' ),
				__( 'Fixed value', 'flexible-shipping-ups' ),
			],
			'desc_tip'          => true,
		];
	}
}

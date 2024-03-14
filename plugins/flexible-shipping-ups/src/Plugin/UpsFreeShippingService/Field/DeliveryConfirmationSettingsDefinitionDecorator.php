<?php
/**
 * Class DeliveryConfirmationSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *.
 */
class DeliveryConfirmationSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_DELIVERY_CONFIRMATION = 'delivery_confirmation';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_DELIVERY_CONFIRMATION, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'             => $this->get_label( __( 'Delivery Confirmation', 'flexible-shipping-ups' ) ),
			'type'              => 'select',
			'default'           => '',
			'custom_attributes' => $this->get_pro_attributes(),
			'class'             => $this->get_pro_field_class(),
			'options'           => [
				__( 'Signature Required', 'flexible-shipping-ups' ),
				__( 'Adult Signature Required', 'flexible-shipping-ups' ),
			],
			'desc_tip'          => __( 'Select if you want the rates to include the additional UPS Signature Delivery Confirmation service. Choosing the \'Signature Required\' or \'Adult Signature Required\' option here may affect the live rates returned by the UPS API.', 'flexible-shipping-ups' ),
		];
	}
}

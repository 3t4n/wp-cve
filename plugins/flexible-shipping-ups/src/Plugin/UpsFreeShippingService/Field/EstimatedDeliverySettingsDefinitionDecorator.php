<?php
/**
 * Class EstimatedDeliverySettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *.
 */
class EstimatedDeliverySettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_DELIVERY_DATES = 'delivery_dates';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_DELIVERY_DATES, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'             => $this->get_label( __( 'Estimated Delivery', 'flexible-shipping-ups' ) ),
			'options'           => [
				__( 'None', 'flexible-shipping-ups' ),
				__( 'Show estimated days to delivery date', 'flexible-shipping-ups' ),
				__( 'Show estimated delivery date', 'flexible-shipping-ups' ),
			],
			'custom_attributes' => $this->get_pro_attributes(),
			'class'             => $this->get_pro_field_class(),
			'type'              => 'select',
			'description'       => __( 'You can show customers an estimated delivery date or time in transit. The information will be added to the service name in the checkout.', 'flexible-shipping-ups' ),
			'desc_tip'          => true,
			'default'           => '',
		];
	}
}

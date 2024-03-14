<?php
/**
 * Class DestinationAddressTypeSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *
 */
class DestinationAddressTypeSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_DESTINATION_ADDRESS_TYPE = 'destination_address_type';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_DESTINATION_ADDRESS_TYPE, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'             => $this->get_label( __( 'Destination Address Type', 'flexible-shipping-ups' ) ),
			'type'              => 'select',
			'description'       => __( 'The recipient\'s address is validated by UPS. You can select the type of address for which the rate will be calculated in case of unsuccessful validation.', 'flexible-shipping-ups' ),
			'custom_attributes' => $this->get_pro_attributes(),
			'class'             => $this->get_pro_field_class(),
			'default'           => '',
			'options'           => [
				__( 'Business', 'flexible-shipping-ups' ),
				__( 'Residential', 'flexible-shipping-ups' ),
			],
			'desc_tip'          => true,
		];
	}
}

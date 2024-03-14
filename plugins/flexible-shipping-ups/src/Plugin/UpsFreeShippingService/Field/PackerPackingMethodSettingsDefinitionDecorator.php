<?php
/**
 * Class PackerPackingMethodSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *.
 */
class PackerPackingMethodSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const OPTION_PACKAGING_METHOD = 'packing_method';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::OPTION_PACKAGING_METHOD, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'             => $this->get_label( __( 'Parcel Packing Method', 'flexible-shipping-ups' ) ),
			'type'              => 'select',
			'default'           => '',
			'custom_attributes' => $this->get_pro_attributes(),
			'class'             => $this->get_pro_field_class(),
			'options'           => [
				__( 'Pack into custom boxes', 'flexible-shipping-ups' ),
				__( 'Pack into one box by weight', 'flexible-shipping-ups' ),
				__( 'Pack items separately', 'flexible-shipping-ups' ),
			],
			'desc_tip'          => __( 'Define the way how the ordered products should be packed. Changing your choice here may affect the rates.', 'flexible-shipping-ups' ),
		];
	}
}

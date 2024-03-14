<?php
/**
 * Class DatesAndTimesSettingsDefinitionDecorator
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService\Field;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;

/**
 *.
 */
class DatesAndTimesSettingsDefinitionDecorator extends ProFeatureSettingsDefinitionDecoratorAbstract {
	public const DATES_AND_TIMES_TITLE = 'dates_and_times_title';

	/**
	 * @param SettingsDefinition $decorated_settings_definition
	 * @param string             $field_id_after
	 */
	public function __construct( SettingsDefinition $decorated_settings_definition, $field_id_after ) {
		parent::__construct( $decorated_settings_definition, $field_id_after, self::DATES_AND_TIMES_TITLE, $this->get_field() );
	}

	/**
	 * Get field settings.
	 *
	 * @return array .
	 */
	private function get_field(): array {
		return [
			'title'       => $this->get_label( __( 'Dates & Time', 'flexible-shipping-ups' ) ),
			'description' => __( 'Manage services\' dates information.', 'flexible-shipping-ups' ),
			'type'        => 'title',
			'class'       => $this->get_pro_title_class(),
		];
	}
}

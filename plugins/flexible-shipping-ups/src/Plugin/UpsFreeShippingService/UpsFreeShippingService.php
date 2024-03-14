<?php
/**
 * Class UpsFreeShippingService
 */

namespace WPDesk\FlexibleShippingUps\UpsFreeShippingService;

use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;

class UpsFreeShippingService extends UpsShippingService {
	/**
	 * Get settings
	 *
	 * @return UpsFreeSettingsDefinition
	 */
	public function get_settings_definition(): UpsFreeSettingsDefinition { // @phpstan-ignore-line
		return new UpsFreeSettingsDefinition( parent::get_settings_definition() );
	}
}

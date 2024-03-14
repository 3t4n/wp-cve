<?php

namespace Dropp\Utility;

use WC_Shipping_Zone;
use WC_Shipping_Zones;

class Zone_Utility
{
	public static function get_zones (): array
	{
		$zones = WC_Shipping_Zones::get_zones();
		$root_zone = new WC_Shipping_Zone(0);
		$zones[ $root_zone->get_id() ]            = $root_zone->get_data();
		$zones[ $root_zone->get_id() ]['zone_id'] = $root_zone->get_id();
		$zones[ $root_zone->get_id() ]['formatted_zone_location'] = $root_zone->get_formatted_location();
		$zones[ $root_zone->get_id() ]['shipping_methods']        = $root_zone->get_shipping_methods( false, 'admin' );
		return $zones;
	}
}

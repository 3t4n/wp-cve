<?php

namespace Dropp\Actions;

use Dropp\Data\Shipping_Instance_Data;
use Dropp\Shipping_Method\Shipping_Method;
use Dropp\Utility\Zone_Utility;
use WC_Shipping_Zone;
use WC_Shipping_Zones;

class Get_Shipping_Instance_Data_Action
{
	public function __invoke( int $instance_id ): ?Shipping_Instance_Data
	{
		$zones = Zone_Utility::get_zones();
		$zone  = false;
		$shipping_method = null;
		foreach ( $zones as $zone_data ) {
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				if ( $instance_id !== $shipping_method->instance_id ) {
					continue;
				}
				$zone = WC_Shipping_Zones::get_zone( $zone_data['zone_id'] );
				break 2;
			}
		}
		if (! $zone || ! $shipping_method instanceof Shipping_Method) {
			return null;
		}
		return new Shipping_Instance_Data($shipping_method, $zone);
	}
}

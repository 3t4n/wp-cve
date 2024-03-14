<?php
/**
 * File responsible for overriding various Code Star Framework functionality.
 *
 * Ideally only filters should be used.
 *
 * Author:          Uriahs Victor
 * Created on:      27/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\CSF;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Controllers\BaseController;

/**
 * Class Overrides.
 *
 * @package Lpac_DPS\Controllers\CSF
 */
class Overrides extends BaseController {

	/**
	 * Automatically change the default order type based on which switches are on/off.
	 *
	 * If delivery is disabled but the default order type is set to delivery, we're automatically setting the default order type to pickup and vice versa.
	 * This is necessary so that when the switcher is hidden on the checkout page, the correct fields are shown.
	 *
	 * @param array  $data Data to save to DB.
	 * @param object $instance Codestar Framework instance.
	 * @return array
	 */
	public function handle_order_type_switchers( array $data, object $instance ): array {

		$delivery_switcher = (bool) $data['order_type__enable_delivery'];
		$pickup_switcher   = (bool) $data['order_type__enable_pickup'];

		if ( false === $delivery_switcher ) {
			$data['order_type__default'] = 'pickup';
		}

		if ( false === $pickup_switcher ) {
			$data['order_type__default'] = 'delivery';
		}

		return $data;
	}
}

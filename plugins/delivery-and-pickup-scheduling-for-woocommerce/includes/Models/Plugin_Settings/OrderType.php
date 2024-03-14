<?php
/**
 * Class responsible for grabbing Order Type settings.
 *
 * Author:          Uriahs Victor
 * Created on:      29/12/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Models
 */

namespace Lpac_DPS\Models\Plugin_Settings;

use Lpac_DPS\Models\BaseModel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class OrderType
 *
 * Class responsible for getting Order Type settings.
 *
 * @package Lpac_DPS\Models\Plugin_Settings
 * @since 1.0.0
 */
class OrderType extends BaseModel {

	/**
	 * Get selector type to show to customers
	 *
	 * @return string
	 * @since 1.2.2
	 */
	public static function getOrderTypeSelector(): string {
		return self::get_setting( 'order_type__selector_type' );
	}

	/**
	 * Get the default Order Type that should be selected at checkout.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function getDefaultOrderType(): string {
		return self::get_setting( 'order_type__default' );
	}

	/**
	 * Check if delivery is enabled on the website.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function isDeliveryEnabled(): bool {
		return (bool) self::get_setting( 'order_type__enable_delivery' );
	}

	/**
	 * Check if pickup is enabled on the website.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function isPickupEnabled(): bool {
		return (bool) self::get_setting( 'order_type__enable_pickup' );
	}

	/**
	 * Get option for whether to drop shipping methods that are not applicable to order type.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function filterShippingMethods(): bool {
		return (bool) self::get_setting( 'order_type__filter_shipping_methods', true );
	}
}

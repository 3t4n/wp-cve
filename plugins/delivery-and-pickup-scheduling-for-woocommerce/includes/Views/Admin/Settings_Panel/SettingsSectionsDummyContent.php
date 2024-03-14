<?php
/**
 * File responsible for defining methods for dummy/upsell sections.
 *
 * Author:          Uriahs Victor
 * Created on:      27/08/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.1.2
 * @package Views
 */

namespace Lpac_DPS\Views\Admin\Settings_Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class responsible for definining dummy sections content.
 *
 * For upsells
 *
 * @package Lpac_DPS\Views\Admin\Settings_Panel
 * @since 1.1.2
 */
class SettingsSectionsDummyContent {

	/**
	 * Dummy capacity feature fields.
	 *
	 * @return array
	 * @since 1.1.2
	 */
	public static function createDummyCapacityFields(): array {

		return array(

			array(
				'type'    => 'submessage',
				'style'   => 'info',
				'content' => sprintf( __( '%1$sOnly accepting 30 orders on Mondays? 20 on Tuesdays and 100 on Fridays? No problem! Set the maximum number of orders you accept per day. But wait, you can also set the maximum number of orders for a timeslot! (e.g Max 10 orders on Wednesdays 11AM-12PM).%2$s %3$sUNLOCK IN PRO%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<span style="font-size: 18px; line-height: 1.5">', '</span>', '<br/><br/><a href="https://chwazidatetime.com/pricing/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell" target="_blank" style="font-size: 20px">', '&nbsp<i class="fas fa-external-link-alt"></i></a>' ),
			),

		);
	}

	/**
	 * Dummy Off Days feature fields.
	 *
	 * @return array
	 * @since 1.1.2
	 */
	public static function createDummyOffDaysFields(): array {

		return array(

			array(
				'type'    => 'submessage',
				'style'   => 'info',
				'content' => sprintf( __( '%1$sDid something come up? Are you going to be unavailable for a week? Create your “Off Days” so that customers can’t place an order while you’re unavailable! Bon voyage!%2$s %3$sUNLOCK IN PRO%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<span style="font-size: 18px; line-height: 1.5">', '</span>', '<br/><br/><a href="https://chwazidatetime.com/pricing/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell" target="_blank" style="font-size: 20px">', '&nbsp<i class="fas fa-external-link-alt"></i></a>' ),
			),

		);
	}

	/**
	 * Dummy User Roles feature fields.
	 *
	 * @return array
	 * @since 1.1.2
	 */
	public static function createDummyUserRolesFields(): array {

		return array(

			array(
				'type'    => 'submessage',
				'style'   => 'info',
				'content' => sprintf( __( '%1$sTake advantage of the User Roles feature to hide the delivery or pickup option based on the customer’s user role in WordPress.%2$s %3$sUNLOCK IN PRO%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<span style="font-size: 18px; line-height: 1.5">', '</span>', '<br/><br/><a href="https://chwazidatetime.com/pricing/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell" target="_blank" style="font-size: 20px">', '&nbsp<i class="fas fa-external-link-alt"></i></a>' ),
			),

		);
	}

	/**
	 * Dummy User Roles feature fields.
	 *
	 * @return array
	 * @since 1.1.2
	 */
	public static function createDummyLocationsFields(): array {

		return array(

			array(
				'type'    => 'submessage',
				'style'   => 'info',
				'content' => sprintf( __( '%1$sCreate Delivery or Pickup locations where customers can receive or pickup their order. Customers will be able to select their desired delivery or pickup location during checkout.%2$s %3$sUNLOCK IN PRO%4$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<span style="font-size: 18px; line-height: 1.5">', '</span>', '<br/><br/><a href="https://chwazidatetime.com/pricing/?utm_source=dps_settings&utm_medium=cta&utm_campaign=upsell" target="_blank" style="font-size: 20px">', '&nbsp<i class="fas fa-external-link-alt"></i></a>' ),
			),

		);
	}
}

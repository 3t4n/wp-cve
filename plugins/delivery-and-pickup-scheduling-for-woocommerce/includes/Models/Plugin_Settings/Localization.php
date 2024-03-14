<?php
/**
 * Class responsible for grabbing Localization settings.
 *
 * Author:          Uriahs Victor
 * Created on:      25/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Models
 */

namespace Lpac_DPS\Models\Plugin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Models\BaseModel;
/**
 * Class Localization.
 *
 * Allow admin to directly translate various public facing text example calendar text.
 *
 * @package Lpac_DPS\Models\Plugin_Settings
 * @since 1.0.0
 */
class Localization extends BaseModel {

	/**
	 * Get weekday names, whether shorthand or longhand.
	 *
	 * @return array
	 * @param string $type Either the shorthand or longhand version of the week day.
	 * @since 1.0.0
	 */
	public function get_weekdays( string $type = 'shorthand' ): array {

		if ( 'shorthand' !== $type && 'longhand' !== $type ) {
			return array();
		}

		$weekdays = self::get_setting( 'localization__weekdays', array() );
		$chunks   = array_chunk( $weekdays, 7 );

		$shorthand = $chunks[0] ?? ''; // Shorthand days is always in first chunck due to how settings are organized and saved.
		$longhand  = $chunks[1] ?? ''; // Longhand days is always in second chunck due to how settings are organized and saved.

		/**
		 * The order of the days of the week inside our saved array is important!
		 * The week always needs to start on sunday (index 0) in code because flatpickr expects it to be so.
		 * Not having sunday as the index 0 in our array would cause flatpickr to show the wrong order of days in the calendar
		 * It would also affect the disabling of dates in flatpickr
		 *
		 * @see prepareDisabledDates() in checkout-page.js script
		 */
		if ( empty( $shorthand ) ) {
			$shorthand = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );
		}

		if ( empty( $longhand ) ) {
			$longhand = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
		}

		return ( 'shorthand' === $type ) ? $shorthand : $longhand;
	}

	/**
	 * Get month names, whether shorthand or longhand.
	 *
	 * @return array
	 * @param string $type Either the 'shorthand' or 'longhand' version of the week day.
	 * @since 1.0.0
	 */
	public function get_months( string $type ): array {

		if ( 'shorthand' !== $type && 'longhand' !== $type ) {
			return array();
		}

		$months = self::get_setting( 'localization__months', array() );

		$chunks = array_chunk( $months, 12 );

		$shorthand = $chunks[0] ?? ''; // Shorthand months is always in first chunck due to how settings are organized and saved.
		$longhand  = $chunks[1] ?? ''; // Longhand months is always in second chunck due to how settings are organized and saved.

		if ( empty( $shorthand ) ) {
			$shorthand = array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
		}

		if ( empty( $longhand ) ) {
			$longhand = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
		}

		return ( 'shorthand' === $type ) ? $shorthand : $longhand;
	}

	/**
	 * Get the text that should show for "Delivery" on the checkout page.
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getCheckoutDeliveryText(): string {
		return self::get_setting( 'localization__checkout_delivery_text', esc_html__( 'Delivery', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Pickup" on the checkout page.
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getCheckoutPickupText(): string {
		return self::get_setting( 'localization__checkout_pickup_text', esc_html__( 'Pickup', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Change order type to:" on the checkout page.
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getCheckoutChangeOrderToText(): string {
		return self::get_setting( 'localization__checkout_change_order_to_text', esc_html__( 'Change order type to:', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Order Type" on the checkout page.
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getCheckoutOrderTypeText(): string {
		return self::get_setting( 'localization__checkout_order_type_text', esc_html__( 'Order Type:', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Delivery details" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPageDeliveryDetailsText(): string {
		return self::get_setting( 'localization__order_details_page_delivery_details_text', esc_html__( 'Delivery details', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Pickup details" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPagePickupDetailsText(): string {
		return self::get_setting( 'localization__order_details_page_pickup_details_text', esc_html__( 'Pickup details', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Date" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPageDeliveryDateText(): string {
		return self::get_setting( 'localization__order_details_page_delivery_date_text', esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Time" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPageDeliveryTimeText(): string {
		return self::get_setting( 'localization__order_details_page_delivery_time_text', esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Date" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPagePickupDateText(): string {
		return self::get_setting( 'localization__order_details_page_pickup_date_text', esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the text that should show for "Time" on the Order details page (Thank You page and order details in my account).
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getOrderDetailsPagePickupTimeText(): string {
		return self::get_setting( 'localization__order_details_page_pickup_time_text', esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Order Type" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsOrderTypeText(): string {
		return self::get_setting( 'localization__emails_order_type_text', esc_html__( 'Order Type', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Delivery" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsDeliveryText(): string {
		return self::get_setting( 'localization__emails_delivery_text', esc_html__( 'Delivery', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Pickup" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsPickupText(): string {
		return self::get_setting( 'localization__emails_pickup_text', esc_html__( 'Pickup', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Date" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsDateText(): string {
		return self::get_setting( 'localization__emails_date_text', esc_html__( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Time" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsTimeText(): string {
		return self::get_setting( 'localization__emails_time_text', esc_html__( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}

	/**
	 * Get the "Location" text that shows in emails.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getEmailsLocationText(): string {
		return self::get_setting( 'localization__emails_location_text', esc_html__( 'Location', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
	}
}

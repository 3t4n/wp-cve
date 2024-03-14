<?php
/**
 * Orchestrate Checkout Page related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      18/10/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Helpers\Functions;
use Lpac_DPS\Models\Plugin_Settings\GeneralSettings;
use Lpac_DPS\Models\Plugin_Settings\OrderType as OrderTypeSettings;
use Lpac_DPS\Models\Plugin_Settings\Scheduling as SchedulingSettings;
use Lpac_DPS\Models\Plugin_Settings\Localization as LocalizationSettings;
use Lpac_DPS\Models\Plugin_Settings\Misc;

/**
 * Class responsible for setting up frontend fields.
 *
 * @package Lpac_DPS\Controllers\Checkout_Page
 * @since 1.0.0
 */
class SetupFields extends BaseCheckoutPageController {

	/**
	 * Bring our calendar settings together for usage in frontend.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getJSConfig(): string {

		$delivery_settings = new SchedulingSettings( 'delivery' );
		$pickup_settings   = new SchedulingSettings( 'pickup' );

		$general_settings      = new GeneralSettings();
		$localization_settings = new LocalizationSettings();

		// Delivery.
		$available_delivery_days         = $delivery_settings->get_available_days();
		$delivery_minimum_days_in_future = $delivery_settings->get_minimum_days_in_future();
		$delivery_maximum_days_in_future = $delivery_settings->get_maximum_days_in_future();
		$delivery_off_days               = $delivery_settings->get_off_days();
		$delivery_maxed_dates            = $delivery_settings->get_maxed_dates();

		// Pickup.
		$available_pickup_days         = $pickup_settings->get_available_days();
		$pickup_minimum_days_in_future = $pickup_settings->get_minimum_days_in_future();
		$pickup_maximum_days_in_future = $pickup_settings->get_maximum_days_in_future();
		$pickup_off_days               = $pickup_settings->get_off_days();
		$pickup_maxed_dates            = $pickup_settings->get_maxed_dates();

		// Locale.
		$weekdays_shorthand = $localization_settings->get_weekdays( 'shorthand' );
		$weekdays_longhand  = $localization_settings->get_weekdays( 'longhand' );
		$months_shorthand   = $localization_settings->get_months( 'shorthand' );
		$months_longhand    = $localization_settings->get_months( 'longhand' );

		// Misc.
		$first_day_of_week = $general_settings->get_first_day_of_week();

		$date_format         = GeneralSettings::get_preferred_date_format();
		$general_time_format = GeneralSettings::get_preferred_time_format();
		$default_order_type  = OrderTypeSettings::getDefaultOrderType();

		$order_type = array(
			'default'  => $default_order_type,
			'delivery' => LocalizationSettings::getCheckoutDeliveryText(),
			'pickup'   => LocalizationSettings::getCheckoutPickupText(),
		);

		$calendar_config = array(
			'delivery'       => array(
				'availableDays'   => $available_delivery_days,
				'minDaysInFuture' => $delivery_minimum_days_in_future,
				'maxDaysInFuture' => $delivery_maximum_days_in_future,
				'offDays'         => $delivery_off_days,
				'maxedDates'      => $delivery_maxed_dates,
			),
			'pickup'         => array(
				'availableDays'   => $available_pickup_days,
				'minDaysInFuture' => $pickup_minimum_days_in_future,
				'maxDaysInFuture' => $pickup_maximum_days_in_future,
				'offDays'         => $pickup_off_days,
				'maxedDates'      => $pickup_maxed_dates,
			),
			'firstDayOfWeek' => $first_day_of_week,
			'dateFormat'     => $date_format,
			'locale'         => array(
				'weekdaysShorthand' => $weekdays_shorthand,
				'weekdaysLonghand'  => $weekdays_longhand,
				'monthsShorthand'   => $months_shorthand,
				'monthsLonghand'    => $months_longhand,
			),
		);

		$general_config = array(
			'time' => array(
				'displayCurrentTime' => false, // Bug in feature causing time to not be right: https://github.com/UVLabs/delivery-and-pickup-scheduling-for-woocommerce-pro/issues/90
				// 'displayCurrentTime' => Misc::showCurrentTime(),
				'currentTimestamp'   => Functions::getCurrentTimestamp(),
				'currentMeridiem'    => Functions::getCurrentTime( 'A' ),
				'timeFormat'         => $general_time_format,
			),
		);

		$calendar_config   = wp_json_encode( $calendar_config );
		$order_type_config = wp_json_encode( $order_type );
		$general_config    = wp_json_encode( $general_config );

		$globals = "
		var lpacDPSCalendarConfig = '$calendar_config';
		var lpacDPSOrderTypeConfig = '$order_type_config';
		var lpacDPSGeneralConfig = '$general_config';
		";

		return $globals;
	}
}

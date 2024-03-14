<?php
/**
 * Class responsible for grabbing General settings.
 *
 * Author:          Uriahs Victor
 * Created on:      25/11/2022 (d/m/y)
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
 * Class GeneralSettings.
 *
 * @package Lpac_DPS\Models\Plugin_Settings
 * @since 1.0.0
 */
class GeneralSettings extends BaseModel {

	// TODO make these methods static

	/**
	 * Get the first day of the week set by the admin.
	 *
	 * @return int|string|false
	 * @since 1.0.0
	 */
	public function get_first_day_of_week(): int {
		$first_day_of_week = self::get_setting( 'general__first_day_of_week', array() );
		return (int) array_search( $first_day_of_week, $this->days_of_the_week, true );
	}

	/**
	 * Get the selected delivery date format.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_preferred_date_format(): string {
		return self::get_setting( 'general__date_format', 'F j, Y' );
	}

	/**
	 * Get the time format to use for the timepicker.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_preferred_time_format(): string {
		return self::get_setting( 'general__datetime_format', '12hr' );
	}

	/**
	 * Get the timezone to use for the website.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_site_timezone(): string {
		$timezone = self::get_setting( 'general__site_timezone', wp_timezone_string() );
		return $timezone;
	}

	/**
	 * Get the switcher setting on whether to include the date and time inside emails.
	 *
	 * @return bool
	 * @since 1.0.6
	 */
	public static function getIncludeDateTimeInEmailsSetting(): bool {
		return (bool) self::get_setting( 'general__enable_datetime_in_emails', false );
	}

	/**
	 * Get the where the date and time fields should be displayed in emails.
	 *
	 * @return string
	 * @since 1.0.6
	 */
	public static function getDateTimeInEmailsLocation(): string {
		return self::get_setting( 'general__datetime_location_in_email', 'woocommerce_email_customer_details' );
	}

	/**
	 * Get the emails where date and time fields should be added.
	 *
	 * @return array
	 * @since 1.0.6
	 */
	public static function getDateTimeIncludedEmails(): array {
		return self::get_setting( 'general__datetime_included_emails', array() );
	}
}

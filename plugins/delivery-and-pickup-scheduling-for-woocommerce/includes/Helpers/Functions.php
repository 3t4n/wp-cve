<?php
/**
 * File responsible for creating helper functions.
 *
 * Author:          Uriahs Victor
 * Created on:      23/03/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Helpers
 */

namespace Lpac_DPS\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon\Carbon;
use Lpac_DPS\Models\Plugin_Settings\GeneralSettings;
use DateTimeZone;
use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Class responsible for creating commonly used helper functions.
 *
 * @package Lpac_DPS\Helpers
 * @since 1.0.0
 */
class Functions {

	/**
	 * Get the current time zone for the site. Timezones options are filterable if user wants to set a custom timezone. See Timezones::getTimezones()
	 *
	 * @return DateTimeZone
	 * @since 1.0.8
	 */
	public static function getTimezone(): DateTimeZone {
		return new DateTimeZone( GeneralSettings::get_site_timezone() );
	}

	/**
	 * Get all available user roles on a website.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_available_user_roles(): array {
		global $wp_roles;
		$roles = array_keys( $wp_roles->roles );
		return array_combine( $roles, $roles );
	}

	/**
	 * Get the roles assigned to the current user.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_current_user_roles(): array {
		$user  = wp_get_current_user();
		$roles = (array) $user->roles;
		return $roles;
	}

	/**
	 * Format a date based on user preference.
	 *
	 * @param mixed $date The date to format.
	 * @return string
	 * @since 1.0.6
	 */
	public static function getFormattedDate( $date ): string {
		return date_format( date_create( $date ), GeneralSettings::get_preferred_date_format() );
	}

	/**
	 * Check if the user is using 24hr time on their website.
	 *
	 * @return bool
	 * @since 1.1.0
	 */
	public static function using24hrTime(): bool {
		$format = GeneralSettings::get_preferred_time_format();
		return ( $format === '24hr' );
	}

	/**
	 * Get the current date of the store.
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public static function getCurrentStoreDate(): string {
		return Carbon::now( self::getTimezone() )->format( 'Y-m-d' );
	}

	/**
	 * Get the current day.
	 *
	 * @return string
	 * @since 1.0.8
	 */
	public static function getCurrentDay(): string {
		return Carbon::now( self::getTimezone() )->format( 'l' );
	}

	/**
	 * Get the current timestamp.
	 *
	 * @return int
	 * @since 1.1.0
	 */
	public static function getCurrentTimestamp(): int {
		return date_create( 'now', self::getTimezone() )->getTimestamp();
	}

	/**
	 * Get the current time based on the time format selected by the user.
	 *
	 * @return string
	 * @since 1.0.8
	 */
	public static function getCurrentTime( string $format = '' ): string {

		$time_format = GeneralSettings::get_preferred_time_format();
		$time_format = ( '24hr' === $time_format ) ? 'H:i' : 'h:i A';

		// If we're passing a specific format then lets use it instead.
		$time_format = $format ?: $time_format;

		return Carbon::now( self::getTimezone() )->format( $time_format );
	}

	/**
	 * Get the current UTC Date and Time.
	 *
	 * @return string
	 * @since 1.2.0
	 */
	public static function getCurrentUTCDateTime(): string {
		return Carbon::now( 'UTC' )->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get the current date/time based on format provided.
	 *
	 * @param mixed $return_format The format to return the data in.
	 * @return string
	 * @since 1.0.8
	 */
	public static function getCurrentDateTime( $return_format = 'Y-m-d H:i:s' ): string {
		return Carbon::now( self::getTimezone() )->format( $return_format );
	}

	/**
	 * Get a formatted datetime string given the original string and it's format.
	 *
	 * @param string $datetime The string to convert.
	 * @param string $format It's current format.
	 * @param string $return_format The format to convert it to.
	 * @return string
	 * @since 1.2.2
	 */
	public static function getDateTimeFromFormat( string $datetime, string $format, string $return_format ): string {
		return Carbon::createFromFormat( $format, $datetime, self::getTimezone() )->format( $return_format );
	}

	/**
	 * Add days to a date.
	 *
	 * @param string $datetime
	 * @param string $format
	 * @param int    $num_days
	 * @param string $return_format
	 * @return string
	 */
	public static function addDaysToDateTime( string $datetime, string $format, int $num_days, string $return_format ) {
		return Carbon::createFromFormat( $format, $datetime, self::getTimezone() )->addDays( $num_days )->format( $return_format );
	}

	/**
	 * Get the difference between two datetimes. Both must match the format passed.
	 *
	 * @param string $datetime_start
	 * @param string $datetime_end
	 * @param string $format
	 * @param string $diff_in Whether `hours`, `minutes` or `seconds`.
	 * @return int
	 */
	public static function getDateTimeDifference( string $datetime_start, string $datetime_end, string $format, string $diff_in ): int {
		$start_date = Carbon::createFromFormat( $format, $datetime_start, self::getTimezone() );
		$end_date   = Carbon::createFromFormat( $format, $datetime_end, self::getTimezone() );

		if ( $diff_in === 'hours' ) {
			return $start_date->diffInRealHours( $end_date );
		} elseif ( $diff_in === 'minutes' ) {
			return $start_date->diffInRealMinutes( $end_date );
		} else {
			return $start_date->diffInRealSeconds( $end_date );
		}
	}

	/**
	 * Check whether a datetime is in the past.
	 *
	 * @param string $datetime
	 * @param string $format
	 * @return bool
	 * @since 1.2.2
	 */
	public static function datetimeisPast( string $datetime, string $format ): bool {
		return Carbon::createFromFormat( $format, $datetime, self::getTimezone() )->isPast();
	}

	/**
	 * Check whether a datetime is in the future.
	 *
	 * @param string $datetime
	 * @param string $format
	 * @return bool
	 * @since 1.2.2
	 */
	public static function datetimeisFuture( string $datetime, string $format ): bool {
		return Carbon::createFromFormat( $format, $datetime, self::getTimezone() )->isFuture();
	}

	/**
	 * Check to see if Kikote plugin is active.
	 *
	 * @param string $version
	 * @return bool
	 * @since 1.0.8
	 */
	public static function isKikoteActive( string $plan = 'any' ): bool {

		$free_version = in_array( 'map-location-picker-at-checkout-for-woocommerce/lpac.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );
		$pro_version  = in_array( 'map-location-picker-at-checkout-for-woocommerce-pro/lpac.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true );

		if ( 'any' === $plan ) {
			return ( $free_version || $pro_version );
		} elseif ( 'pro' === $plan ) {
			return $pro_version;
		} else {
			return $free_version;
		}
	}

	/**
	 * Check if store using High Performance Order Storage.
	 *
	 * @return bool
	 * @since 1.2.0
	 */
	public static function usingHPOS(): bool {
		return OrderUtil::custom_orders_table_usage_is_enabled();
	}
}

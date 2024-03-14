<?php
/**
 * File responsible for defining utility class and methods.
 *
 * Author:          Uriahs Victor
 * Created on:      09/06/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.8
 * @package Helpers
 */

namespace Lpac_DPS\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Carbon\Exceptions\InvalidTimeZoneException;
use DateTime;

/**
 * Class that defines Utility methods.
 *
 * @package Lpac_DPS\Helpers
 * @since 1.0.8
 */
class Utilities {

	/**
	 * Convert time to 24 hour format.
	 *
	 * @param string $time
	 * @return string
	 * @since 1.2.4
	 */
	public static function convertTo24HourFormat( string $time ): string {
		return Carbon::parse( $time )->format( 'H:i:s' );
	}

	/**
	 * Convert a date_time string to UTC.
	 *
	 * @param string $date_time In format Y-m-d H:i:s
	 * @return null|string
	 * @throws InvalidTimeZoneException
	 * @since 1.2.0
	 */
	public static function convertDateTimeToUTC( string $date_time ): ?string {
		return Carbon::createFromFormat( 'Y-m-d H:i:s', $date_time, Functions::getTimezone() )->setTimezone( 'UTC' )->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Check if a time is greater than or equal to another.
	 *
	 * @param string $time1 The time to start the comparison.
	 * @param string $time2 The time to compare against.
	 * @return bool
	 * @since 1.2.0
	 */
	public static function timeIsGreaterThanOrEqualTo( string $time1, string $time2 ): bool {
		$first  = Carbon::createFromFormat( 'Y-m-d H:i:s', $time1, 'UTC' );
		$second = Carbon::createFromFormat( 'Y-m-d H:i:s', $time2, 'UTC' );
		return $first->greaterThanOrEqualTo( $second );
	}

	/**
	 * Check if datetime1 is less than $datetime2
	 *
	 * @param string $datetime1 The time to start the comparison.
	 * @param string $datetime2 The time to compare against.
	 * @return bool
	 * @since 1.2.0
	 */
	public static function timeIsLessThan( string $datetime1, string $datetime2 ): bool {
		$first  = Carbon::createFromFormat( 'Y-m-d H:i:s', $datetime1, 'UTC' );
		$second = Carbon::createFromFormat( 'Y-m-d H:i:s', $datetime2, 'UTC' );
		return $first->lessThan( $second );
	}

	/**
	 * Check if a time is less than or equal to another.
	 *
	 * @param string $time1 The time to start the comparison.
	 * @param string $time2 The time to compare against.
	 * @return bool
	 * @since 1.2.0
	 */
	public static function timeIsLessThanOrEqualTo( string $time1, string $time2 ): bool {
		$first  = Carbon::createFromFormat( 'Y-m-d H:i:s', $time1, 'UTC' );
		$second = Carbon::createFromFormat( 'Y-m-d H:i:s', $time2, 'UTC' );
		return $first->lessThanOrEqualTo( $second );
	}

	/**
	 * Get the current time with a number of minutes added.
	 *
	 * @param int    $minutes
	 * @param string $return_format
	 * @return string
	 * @since 1.2.0
	 */
	public static function addMinutesToCurrentUTCDateTime( int $minutes, string $return_format = 'Y-m-d H:i:s' ) {
		$time = Carbon::now( 'UTC' ); // This defaults to the current date.
		return $time->addMinutes( $minutes )->format( $return_format );
	}

	/**
	 * Get the UTC version of a time.
	 *
	 * @param string $time
	 * @param string $return_format
	 * @return string
	 * @throws InvalidFormatException
	 * @throws InvalidTimeZoneException
	 * @since 1.2.0
	 */
	public static function getUTCFromTime( string $time, string $return_format = 'H:i:s' ): string {

		$local_time = Carbon::createFromTimeString( $time, Functions::getTimezone() );
		$utc_time   = $local_time->setTimezone( 'UTC' );

		return $utc_time->format( $return_format );
	}

	/**
	 * Check if a time is between two times.
	 *
	 * @param string $from
	 * @param string $to
	 * @param string $input
	 * @return bool
	 * @since 1.0.8
	 * @link https://stackoverflow.com/a/27134087/4484799
	 */
	public static function timeIsBetween( string $from, string $to, string $input ): bool {
		$from  = DateTime::createFromFormat( '!H:i', $from );
		$to    = DateTime::createFromFormat( '!H:i', $to );
		$input = DateTime::createFromFormat( '!H:i', $input );

		if ( $from > $to ) {
			$to->modify( '+1 day' );
		}

		return ( $from <= $input && $input <= $to ) || ( $from <= $input->modify( '+1 day' ) && $input <= $to );
	}

	/**
	 * Get the starting time from a time slot.
	 *
	 * @param string $time_slot
	 * @return string
	 * @since 1.1.0
	 */
	public static function getStartTimeFromTimeSlot( string $time_slot ): string {
		$parts = explode( '-', $time_slot );

		if ( empty( $parts[1] ) ) {
			return $time_slot; // Not a time slot.
		}

		return trim( $parts[0] ); // Be sure to remove the trailing space as it can cause errors when working with DateTime
	}

	/**
	 * Get a timestamp after subtracting a defined number of minutes from it.
	 *
	 * @param int $timestamp
	 * @param int $minutes
	 * @return int
	 * @since 1.1.0
	 */
	public static function subtractMinutesFromTimestamp( int $timestamp, int $minutes ): int {
		return $timestamp - ( $minutes * 60 );
	}

	/**
	 * Replace magic tags in content with the relevant data.
	 *
	 * @param array  $tags_values
	 * @param string $content
	 * @return string
	 * @since 1.1.0
	 */
	public static function replaceMagicTags( array $tags_values, string $content ): string {
		return str_replace( array_keys( $tags_values ), array_values( $tags_values ), $content );
	}

	/**
	 * Normalize string $_POST data.
	 *
	 * When checking $_POST on certain WC hooks we get a string with the checkout form data.
	 * This method helps normalize it to easier work with.
	 *
	 * WooCommerce most likely sanitizes the html value attribute causing a time like 10:00 - 12:00 to be parsed as 1000-1200 times to co
	 *
	 * @param string $post_data
	 * @return array
	 * @since 1.2.2
	 */
	public static function normalizePostString( string $post_data ): array {
		$data   = array();
		$fields = explode( '&', $post_data );

		foreach ( $fields as $key => $value ) {
			$parts = explode( '=', sanitize_text_field( $value ) );
			if ( empty( $parts[1] ) ) {
				// This is important as in a rare case it was noticed that the array was later overriden.
				continue;
			}
			$data[ $parts[0] ] = $parts[1];
		}

		return $data;
	}

	/**
	 * Normalize the timeslot received from the $_POST data.
	 *
	 * @param string $time
	 * @return array
	 * @since 1.2.2
	 */
	public static function normalizePostTimeslot( string $time ): array {

		$time_parts = explode( '-', $time );

		$from = $time_parts[0] ?? '';
		$to   = $time_parts[1] ?? '';

		$from_normalized = '';
		$to_normalized   = '';

		if ( Functions::using24hrTime() ) {

			$minutes         = substr( $from, 2 );
			$hours           = substr( $from, 0, 2 );
			$from_normalized = $hours . ':' . $minutes;

			if ( ! empty( $to ) ) {
				$minutes       = substr( $to, 2 );
				$hours         = substr( $to, 0, 2 );
				$to_normalized = $hours . ':' . $minutes;
			}
		} else {

			// Start from end of string to help easier normalize it.
			// There would always be 2 characters at the end (AM/PM).
			$am_pm         = substr( $from, -2 );
			$minutes       = substr( $from, -4, 2 );
			$minutes_am_pm = $minutes . $am_pm;

			$hours           = str_replace( $minutes_am_pm, '', $from );
			$from_normalized = $hours . ':' . $minutes . ' ' . $am_pm;

			if ( ! empty( $to ) ) {
				$hours         = str_replace( $minutes_am_pm, '', $to );
				$to_normalized = $hours . ':' . $minutes . ' ' . $am_pm;
			}
		}

		$time_slot = array(
			'from' => $from_normalized,
			'to'   => $to_normalized,
		);

		if ( ! empty( $to ) ) {
			$time_slot['formatted'] = $from_normalized . ' - ' . $to_normalized;
		}

		return $time_slot;
	}

	/**
	 * Replace dashes in a date with underscores. Example: 2022_11_27.
	 *
	 * @param string $date
	 * @return string
	 * @since 1.2.2
	 */
	public static function underscoreDate( string $date ): string {
		return str_replace( '-', '_', $date );
	}

	/**
	 * Replace spaces in a time slot with underscores. Example: 12:00_AM_-_12:00_PM.
	 *
	 * @param string $time_slot The timeslot.
	 * @return string
	 * @since 1.0.0
	 */
	public static function underscoreTimeSlot( string $time_slot ): string {
		return str_replace( ' ', '_', $time_slot );
	}

	/**
	 * Create a range text given the from and to. Example: 12:00 AM - 12:00 PM.
	 *
	 * @param string $from The from time.
	 * @param string $to The to time.
	 * @return string
	 * @since 1.0.0
	 */
	public static function createTimeSlotDisplayText( string $from, string $to ): string {
		return $from . ' - ' . $to;
	}
}

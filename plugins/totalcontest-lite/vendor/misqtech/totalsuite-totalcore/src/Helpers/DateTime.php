<?php

namespace TotalContestVendors\TotalCore\Helpers;

use DateTimeZone;
use TotalContestVendors\TotalCore\Contracts\Helpers\DateTime as DateTimeContract;

/**
 * Class DateTime
 * @package TotalContestVendors\TotalCore\Helpers
 */
class DateTime extends \DateTime {

	/**
	 * Get JSON.
	 * @return array|mixed
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return [
			'timezone'  => $this->getTimezone(),
			'timestamp' => $this->getTimestamp(),
			'offset'    => $this->getOffset(),
			'date'      => $this->format( $this->getDateFormat() ),
			'time'      => $this->format( $this->getTimeFormat() ),
			'datetime'  => $this->format( $this->getDateTimeFormat() ),
		];
	}

	/**
	 * Format date.
	 *
	 * @param string $format
	 *
	 * @return bool|int|string
	 */
	#[\ReturnTypeWillChange]
	public function format( $format ) {
		return mysql2date( $format, parent::format( DATE_ATOM ) );
	}

	/**
	 * Get date format.
	 *
	 * @return string
	 */
	public function getDateFormat() {
		return get_option( 'date_format', 'F j, Y' );
	}

	/**
	 * Get time format.
	 *
	 * @return string
	 */
	public function getTimeFormat() {
		return get_option( 'time_format', 'g:i a' );
	}

	/**
	 * Get date and time format.
	 * @return string
	 */
	public function getDateTimeFormat() {
		return $this->getDateFormat() . ' ' . $this->getTimeFormat();
	}

	/**
	 * Sets the current time of the DateTime object to a different time.
	 *
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 * @param int $microseconds
	 *
	 * @return static|false
	 * @since 7.1.0 $microseconds parameter added.
	 * @link  http://php.net/manual/en/datetime.settime.php
	 */
	#[\ReturnTypeWillChange]
	public function setTime( $hour, $minute, $second = 0, $microseconds = 0 ) {
		return parent::setTime( $hour, $minute, $second, $microseconds );
	}

	/**
	 * String representation.
	 *
	 * @return string
	 */
	public function __toString() {
		return (string) $this->format( $this->getDateTimeFormat() );
	}

	/**
	 * Get formatted date.
	 *
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function getFormattedDate( $format = null ) {
		return $this->format( $format ?: $this->getDateFormat() );
	}

	/**
	 * Get formatted time.
	 *
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function getFormattedTime( $format = null ) {
		return $this->format( $format ?: $this->getTimeFormat() );
	}

	/**
	 * Parse a string into a new DateTime object according to the specified format
	 *
	 * @param string       $format   Format accepted by date().
	 * @param string       $time     String representing the time.
	 * @param DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
	 *
	 * @return DateTime|boolean
	 */
	#[\ReturnTypeWillChange]
	public static function createFromFormat( $format, $time, $timezone = null ) {
		if ( version_compare( PHP_VERSION, '7.0-dev', '<=' ) ):
			return parent::createFromFormat( $format, $time, $timezone->getName() );
		endif;

		return parent::createFromFormat( $format, $time, $timezone );
	}
}

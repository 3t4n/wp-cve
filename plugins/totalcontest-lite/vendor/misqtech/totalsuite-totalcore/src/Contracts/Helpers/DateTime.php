<?php

namespace TotalContestVendors\TotalCore\Contracts\Helpers;

use DateTimeZone;

/**
 * Interface DateTime
 * @package TotalContestVendors\TotalCore\Contracts\Helpers
 */
interface DateTime extends Arrayable, \JsonSerializable {
	/**
	 * Returns an array of warnings and errors found while parsing a date/time string
	 * @return array
	 */
	public static function getLastErrors();

	/**
	 * Get date format.
	 *
	 * @return string
	 */
	public function getDateFormat();

	/**
	 * Get time format.
	 *
	 * @return string
	 */
	public function getTimeFormat();

	/**
	 * Get date and time format.
	 * @return string
	 */
	public function getDateTimeFormat();

	/**
	 * Get formatted date.
	 *
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function getFormattedDate( $format = null );

	/**
	 * Get formatted time.
	 *
	 * @param string|null $format
	 *
	 * @return string
	 */
	public function getFormattedTime( $format = null );

	/**
	 * Format date.
	 *
	 * @param null|string $format
	 *
	 * @return bool|int|string
	 */
	public function format( $format );

	/**
	 * Alter the timestamp of a DateTime object by incrementing or decrementing
	 * in a format accepted by strtotime().
	 *
	 * @param string $modify A date/time string. Valid formats are explained in <a href="http://www.php.net/manual/en/datetime.formats.php">Date and Time Formats</a>.
	 *
	 * @return static Returns the DateTime object for method chaining or FALSE on failure.
	 */
	public function modify( $modify );

	/**
	 * Adds an amount of days, months, years, hours, minutes and seconds to a DateTime object
	 *
	 * @param $interval
	 *
	 * @return static
	 */
	public function add( $interval );

	/**
	 * Subtracts an amount of days, months, years, hours, minutes and seconds from a DateTime object
	 *
	 * @param $interval
	 *
	 * @return static
	 */
	public function sub( $interval );

	/**
	 * Get the TimeZone associated with the DateTime
	 * @return
	 */
	public function getTimezone();

	/**
	 * Set the TimeZone associated with the DateTime
	 *
	 * @param $timezone
	 *
	 * @return static
	 */
	public function setTimezone( $timezone );

	/**
	 * Returns the timezone offset
	 * @return int
	 */
	public function getOffset();

	/**
	 * Sets the current time of the DateTime object to a different time.
	 *
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 * @param int $microseconds
	 *
	 * @return static|false
	 */
	public function setTime( $hour, $minute, $second = 0, $microseconds = 0 );

	/**
	 * Sets the current date of the DateTime object to a different date.
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 *
	 * @return static
	 */
	public function setDate( $year, $month, $day );

	/**
	 * Set a date according to the ISO 8601 standard - using weeks and day offsets rather than specific dates.
	 *
	 * @param int $year
	 * @param int $week
	 * @param int $day
	 *
	 * @return static
	 */
	public function setISODate( $year, $week, $day = 1 );

	/**
	 * Sets the date and time based on a Unix timestamp.
	 *
	 * @param int $unixtimestamp
	 *
	 * @return static
	 */
	public function setTimestamp( $unixtimestamp );

	/**
	 * Gets the Unix timestamp.
	 * @return int
	 */
	public function getTimestamp();

	/**
	 * Returns the difference between two DateTime objects represented as a.
	 *
	 * @param \DateTimeInterface $datetime2 The date to compare to.
	 * @param boolean            $absolute  [optional] Whether to return absolute difference.
	 *
	 * @return|boolean The object representing the difference between the two dates or FALSE on failure.
	 */
	public function diff( $datetime2, $absolute = false );
}
<?php
/**
 * EverAccounting Wrapper for PHP DateTime which adds support for gmt/utc offset when a timezone is absent.
 *
 * @since   1.0.2
 *
 * @package EverAccounting
 */

namespace EverAccounting;

use DateTime as DT;

defined( 'ABSPATH' ) || exit;

/**
 * Datetime class.
 *
 * @since   1.0.2
 */
class DateTime extends DT {
	/**
	 * UTC Offset, if needed. Only used when a timezone is not set. When
	 * timezones are used this will equal 0.
	 *
	 * @since   1.0.2
	 *
	 * @var integer
	 */
	protected $utc_offset = 0;

	/**
	 * Output an ISO 8601 date string in local (WordPress) timezone.
	 *
	 * @since  1.0.2
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->date_mysql();
	}

	/**
	 * Clone the current object.
	 *
	 * @since 1.0.2
	 *
	 * @return \EverAccounting\DateTime
	 */
	public function copy() {
		return clone $this;
	}

	/**
	 * Set UTC offset - this is a fixed offset instead of a timezone.
	 *
	 * @param int $offset Offset.
	 *
	 * @since   1.0.2
	 */
	public function set_utc_offset( $offset ) {
		$this->utc_offset = intval( $offset );
	}

	/**
	 * Get UTC offset if set, or default to the DateTime object's offset.
	 *
	 * @since   1.0.2
	 *
	 * @return int
	 */
	public function getOffset() {
		return $this->utc_offset ? $this->utc_offset : parent::getOffset();
	}

	/**
	 * Set timezone.
	 *
	 * @param \DateTimeZone $timezone DateTimeZone instance.
	 *
	 * @since   1.0.2
	 *
	 * @return bool
	 */
	public function setTimezone( $timezone ) {
		$this->utc_offset = 0;

		return parent::setTimezone( $timezone );
	}

	/**
	 * Adds year.
	 *
	 * @param int $number number of the years to add.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function addYear( $number = 1 ) {
		$this->add( new \DateInterval( "P{$number}Y" ) );

		return $this;
	}

	/**
	 * Add months.
	 *
	 * @param int $number Number of the months to add.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function addMonth( $number = 1 ) {
		$this->add( new \DateInterval( "P{$number}M" ) );

		return $this;
	}

	/**
	 * Adds day.
	 *
	 * @param int $number Number of the days to add.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function addDay( $number = 1 ) {
		$this->add( new \DateInterval( "P{$number}D" ) );

		return $this;
	}

	/**
	 * Adds hour.
	 *
	 * @param int $number Number of the hours to add.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function subYear( $number = 1 ) {
		$this->sub( new \DateInterval( "P{$number}Y" ) );

		return $this;
	}

	/**
	 * Subtracts months.
	 *
	 * @param int $number Number of the months to subtract.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function subMonth( $number = 1 ) {
		$this->sub( new \DateInterval( "P{$number}M" ) );

		return $this;
	}

	/**
	 * Subtracts day.
	 *
	 * @param int $number Number of the days to subtract.
	 *
	 * @since 1.0.2
	 *
	 * @return object
	 */
	public function subDay( $number = 1 ) {
		$this->sub( new \DateInterval( "P{$number}D" ) );

		return $this;
	}

	/**
	 * Missing in PHP 5.2 so just here so it can be supported consistently.
	 *
	 * @since  1.0.2
	 * @return int
	 */
	public function getTimestamp() {
		return method_exists( 'DateTime', 'getTimestamp' ) ? parent::getTimestamp() : $this->format( 'U' );
	}

	/**
	 * Get the timestamp with the WordPress timezone offset added or subtracted.
	 *
	 * @since  1.0.2
	 * @return int
	 */
	public function getOffsetTimestamp() {
		return $this->getTimestamp() + $this->getOffset();
	}

	/**
	 * Format a date based on the offset timestamp.
	 *
	 * @param string $format Date format.
	 *
	 * @since  1.0.2
	 *
	 * @return string
	 */
	public function date( $format ) {
		return gmdate( $format, $this->getOffsetTimestamp() );
	}

	/**
	 * Return a localised date based on offset timestamp. Wrapper for date_i18n function.
	 *
	 * @param string $format Date format.
	 *
	 * @since  1.0.2
	 *
	 * @return string
	 */
	public function date_i18n( $format = 'Y-m-d' ) {
		return date_i18n( $format, $this->getOffsetTimestamp() );
	}

	/**
	 * Return mysql date time.
	 *
	 * @since 1.0.2
	 *
	 * @return string date time
	 */
	public function date_mysql() {
		return wp_date( 'Y-m-d H:i:s', $this->getOffsetTimestamp() );
	}

	/**
	 * Get quarter
	 *
	 * @since 1.0.2
	 *
	 * @return int
	 */
	public function quarter() {
		return ceil( $this->format( 'm' ) / 3 );
	}

	/**
	 * Alias self::quarter()
	 *
	 * @since 1.1.0
	 *
	 * @return false|float
	 */
	public function get_quarter() {
		return ceil( $this->format( 'm' ) / 3 );
	}
}

<?php
/**
 * Class DateTimeConverter.
 *
 * @file class-datetimeconverter.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Util;

use Exception;

/**
 * Convert unix timestamp values to DOS date/time values and vice versa.
 *
 * The DOS date/time format is a bitmask:
 *
 * 24                16                 8                 0
 * +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+
 * |Y|Y|Y|Y|Y|Y|Y|M| |M|M|M|D|D|D|D|D| |h|h|h|h|h|m|m|m| |m|m|m|s|s|s|s|s|
 * +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+ +-+-+-+-+-+-+-+-+
 * \___________/\________/\_________/ \________/\____________/\_________/
 * year        month       day      hour       minute        second
 *
 * The year is stored as an offset from 1980.
 * Seconds are stored in two-second increments.
 * (So if the "second" value is 15, it actually represents 30 seconds.)
 *
 * @internal
 */
class DateTimeConverter {

	/**
	 * Smallest supported DOS date/time value in a ZIP file, which is January 1st, 1980 AD 00:00:00 local time.
	 *
	 * @var int MIN_DOS_TIME
	 */
	const MIN_DOS_TIME = ( 1 << 21 ) | ( 1 << 16 );

	/**
	 * Converts a UNIX timestamp value to a DOS date/time value.
	 *
	 * @param int $unix_timestamp The number of seconds since midnight, January 1st, 1970 AD UTC.
	 * @return int a DOS date/time value reflecting the local time zone and
	 *             rounded down to even seconds
	 *             and is in between DateTimeConverter::MIN_DOS_TIME and DateTimeConverter::MAX_DOS_TIME
	 * @throws Exception Throws Exception.
	 */
	public static function unix_to_ms_dos( int $unix_timestamp ): int {
		if ( 0 > $unix_timestamp ) {
			throw new Exception( 'Negative unix timestamp: ' . $unix_timestamp );
		}

		$date     = getdate( $unix_timestamp );
		$dos_time = (
			( ( $date['year'] - 1980 ) << 25 )
			| ( $date['mon'] << 21 )
			| ( $date['mday'] << 16 )
			| ( $date['hours'] << 11 )
			| ( $date['minutes'] << 5 )
			| ( $date['seconds'] >> 1 )
		);

		if ( $dos_time <= self::MIN_DOS_TIME ) {
			$dos_time = 0;
		}

		return $dos_time;
	}
}

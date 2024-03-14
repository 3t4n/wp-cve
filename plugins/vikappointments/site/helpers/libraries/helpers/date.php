<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Helper class used to handle dates.
 *
 * @since 1.7
 */
class VAPDateHelper
{
	/**
	 * Checks whether the specified date is NULL or
	 * can be represented as such.
	 *
	 * @param 	mixed 	 $date  Either a timestamp or a db date string.
	 *
	 * @return 	boolean  True if null, false otherwise.
	 */
	public static function isNull($date)
	{
		if (is_null($date) || strlen($date) == 0)
		{
			// we received a null|empty value
			return true;
		}

		// look for a JDate instance
		if ($date instanceof JDate)
		{
			// we have for sure a valid date
			return false;
		}

		// look for a timestamp
		if (is_numeric($date))
		{
			// null only in case the number is "-1"
			return (int) $date === -1;
		}

		$dbo = JFactory::getDbo();

		// get database NULL DATE representation
		$null_date = $dbo->getNullDate();

		// Just check whether NULL date starts with the given string.
		// Needed because NULL date always include the time.
		return strpos($null_date, $date) === 0;
	}

	/**
	 * Creates a UNIX timestamp starting from a string date.
	 *
	 * The date string must be formatted using the format defined
	 * by the configuration of the program. Otherwise rely on
	 * PHP strtotime for any other standard format.
	 *
	 * The date string may optionally contain a time string too
	 * with hours, minutes and seconds. In that case, those values
	 * will replace the specified ones as argument only if NULL.
	 *
	 * @param 	string 	 $date 	The date to parse.
	 * @param 	integer  $hour 	The hours to use.
	 * @param 	integer  $min 	The minutes to use.
	 * @param 	integer  $sec 	The seconds to use.
	 *
	 * @return 	integer  The resulting UNIX timestamp.
	 */
	public static function getTimestamp($date, $hour = null, $min = null, $sec = null)
	{
		if (preg_match("/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}/", $date))
		{
			// resulting Y-m-d given
			$format	= 'Y-m-d';
		}
		else
		{
			// use system format
			$format = VAPFactory::getConfig()->get('dateformat');
		}

		// return invalid timestamp in case a NULL DB date was passed
		if (static::isNull($date))
		{
			return -1;
		}

		// check if we have the time contained within the date string
		if (preg_match("/[0-9]{2,2}:[0-9]{2,2}/", $date))
		{
			// separate date from time
			$tmp = explode(' ', $date);

			// keep date string
			$date = $tmp[0];

			// explode time string
			$tmp   = explode(':', $tmp[1]);
			$count = count($tmp);

			// fetch hours, minutes and seconds
			$hour = is_null($hour) && $count > 0 ? $tmp[0] : $hour;
			$min  = is_null($min)  && $count > 1 ? $tmp[1] : $min;
			$sec  = is_null($sec)  && $count > 2 ? $tmp[2] : $sec;
		}

		// second char of date format can be only a separator [/.-]
		$separator = $format[1];

		// get chunks of date format, such as [Y,m,d]
		$format_chunks = explode($separator, $format);
		// get chunks of date string, such as [2021,01,01]
		$date_chunks = explode($separator, $date);
		
		if (count($date_chunks) != 3 || count($format_chunks) != 3)
		{
			// invalid date or format, return null timestamp
			return -1;
		}
		
		$lookup = array();

		// create lookup to generate the timestamp
		for ($i = 0, $n = count($format_chunks); $i < $n; $i++)
		{
			$lookup[$format_chunks[$i]] = $date_chunks[$i];
		}
		
		// create timestamp from given lookup
		return mktime(
			(int)        $hour, (int)         $min, (int)         $sec,
			(int) $lookup['m'], (int) $lookup['d'], (int) $lookup['Y']
		);
	}

	/**
	 * Creates a JDate instance starting from a string date.
	 *
	 * The date string must be formatted using the format defined
	 * by the configuration of the program. Otherwise directly use
	 * JDate class for any other standard format.
	 *
	 * The date string may optionally contain a time string too
	 * with hours, minutes and seconds. In that case, those values
	 * will replace the specified ones as argument only if NULL.
	 *
	 * @param 	string 	 $date 	The date to parse.
	 * @param 	integer  $hour 	The hours to use.
	 * @param 	integer  $min 	The minutes to use.
	 * @param 	integer  $sec 	The seconds to use.
	 *
	 * @return 	mixed    A JDate instance in case of valid date, null otherwise.
	 */
	public static function getDate($date, $hour = null, $min = null, $sec = null)
	{
		// create a timestamp for the given arguments
		$timestamp = static::getTimestamp($date, $hour, $min, $sec);

		if (static::isNull($timestamp))
		{
			// invalid timestamp, return null
			return null;
		}

		// format timestamp to be displayed in a PHP-standard format
		$date = date('Y-m-d H:i:s', $timestamp);

		// create and return a new date instance
		return JDate::getInstance($date);
	}

	/**
	 * Returns a SQL representation of the given date. In case of null
	 * or invalid date, a NULL DATE string will be returned.
	 *
	 * The date string must be formatted using the format defined
	 * by the configuration of the program. Otherwise directly use
	 * JDate class for any other standard format.
	 *
	 * The date string may optionally contain a time string too
	 * with hours, minutes and seconds. In that case, those values
	 * will replace the specified ones as argument only if NULL.
	 *
	 * @param 	string 	 $date 	The date to parse.
	 * @param 	integer  $hour 	The hours to use.
	 * @param 	integer  $min 	The minutes to use.
	 * @param 	integer  $sec 	The seconds to use.
	 *
	 * @return 	string   The resulting SQL date
	 */
	public static function getSqlDate($date, $hour = null, $min = null, $sec = null)
	{
		// create date instance first
		$date = static::getDate($date, $hour, $min, $sec);

		if (static::isNull($date))
		{
			// invalid date, return db NULL date
			return JFactory::getDbo()->getNullDate();
		}

		// format date for query
		return $date->toSql();
	}

	/**
	 * Returns a SQL representation of the given date. In case of null
	 * or invalid date, a NULL DATE string will be returned. The date will
	 * be assumed to be formatted into the specified timezone.
	 *
	 * The date string must be formatted using the format defined
	 * by the configuration of the program. Otherwise directly use
	 * JDate class for any other standard format.
	 *
	 * The date string may optionally contain a time string too
	 * with hours, minutes and seconds. In that case, those values
	 * will replace the specified ones as argument only if NULL.
	 *
	 * @param 	string 	 $date 	The date to parse.
	 * @param 	integer  $hour 	The hours to use.
	 * @param 	integer  $min 	The minutes to use.
	 * @param 	integer  $sec 	The seconds to use.
	 * @param 	mixed    $tz    The timezone to use. It will be loaded from
	 *                          the user configuration when not specified.
	 *
	 * @return 	string   The resulting SQL date
	 */
	public static function getSqlDateLocale($date, $hour = null, $min = null, $sec = null, $tz = null)
	{
		// create date instance first
		$date = static::getDate($date, $hour, $min, $sec);

		if (static::isNull($date))
		{
			// invalid date, return db NULL date
			return JFactory::getDbo()->getNullDate();
		}

		if (is_null($tz))
		{
			// get user timezone
			$tz = JFactory::getUser()->getTimezone();
		}

		// rebuild date to be localised for the given timezone
		$date = new JDate($date->toSql(), $tz);

		// format date for query
		return $date->toSql();
	}

	/**
	 * Shortcut for {@see getSqlDateLocale} method, which do not require
	 * to specify hours, minutes and seconds.
	 *
	 * @param 	string 	$date  The date (and time) to parse.
	 * @param 	mixed   $tz    The timezone to use. It will be loaded from
	 *                         the user configuration when not specified.
	 *
	 * @return 	string   The resulting SQL date.
	 */
	public static function date2sql($date, $tz = null)
	{
		return static::getSqlDateLocale($date, null, null, null, $tz);
	}

	/**
	 * Instantiates a JDate object according to the specified
	 * SQL date string and timezone.
	 *
	 * @param 	string 	$date  The date to parse.
	 * @param 	mixed   $tz    The timezone to use. It will be loaded from
	 *                         the user configuration when not specified.
	 *                         Use false to use the date in UTC.
	 *
	 * @return 	JDate   A new date instance.
	 */
	public static function sql2date($sql, $tz = null)
	{
		if (static::isNull($sql))
		{
			// invalid date, return null
			return null;
		}

		// create date instance
		$date = new JDate($sql);

		// adjust timezone only in case it hasn't
		// been explicitly disabled
		if ($tz !== false)
		{
			if (!$tz)
			{
				// get user timezone
				$tz = JFactory::getUser()->getTimezone();
			}
			
			if (!$tz instanceof DateTimeZone)
			{
				$tz = new DateTimeZone($tz);
			}

			// adjust date to given timezone
			$date->setTimezone($tz);
		}

		return $date;
	}

	/**
	 * Calculates the interval between 2 dates and adjusts, if needed,
	 * the difference to the specified unit.
	 *
	 * @param 	JDate|string  $a     The first date to compare.
	 * @param 	JDate|string  $b     The second date to compare (current date time by default).
	 * @param 	string|null   $unit  The optional unit of measurement to return.
	 *
	 * @return  DateInterval|integer  
	 */
	public static function diff($a, $b = 'now', $unit = null)
	{
		if (!$a instanceof JDate)
		{
			// string given, create date instance
			$a = new JDate($a);
		}

		if (!$b instanceof JDate)
		{
			// string given, create date instance
			$b = new JDate($b);
		}

		// get dates interval
		$interval = $a->diff($b);

		if (!$unit)
		{
			// return DateInterval object
			return $interval;
		}

		$diff = $interval->days;

		if ($unit == 'days')
		{
			// return difference in days
			return $diff;
		}

		// convert days in hours and sum exceeding hours
		$diff = $diff * 24 + $interval->h;

		if ($unit == 'hours')
		{
			// return difference in hours
			return $diff;
		}

		// convert hours in minutes and sum exceeding minutes
		$diff = $diff * 60 + $interval->i;

		if ($unit == 'minutes')
		{
			// return difference in minutes
			return $diff;
		}

		// convert minutes in seconds and sum exceeding seconds
		return $diff * 60 + $interval->s;
	}
}

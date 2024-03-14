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
 * Helper class used to format distances.
 *
 * @since 1.6
 * @since 1.7  Renamed from DistanceHelper
 */
class VAPDistanceHelper
{
	/**
	 * Lookup array to obtain the meters conversion rate.
	 *
	 * @var array
	 */
	protected static $lookup = array(
		// kilometers
		'km'	=> 1000.0,
		// miles
		'mi' 	=> 1609.344,
		// yards
		'yd'	=> 0.9144,
		// feet
		'ft' 	=> 0.3048,
	);

	/**
	 * Converts the specified value with the specified unit.
	 *
	 * @param 	float 	$value 	The amount to convert.
	 * @param 	string 	$unit 	The unit to use for conversion.
	 * @param 	float 	$from 	The current unit of the amount (meters by default).
	 *
	 * @return 	float 	The converted amount.
	 */
	public static function convert($value, $unit, $from = null)
	{
		// check if the current unit exists
		if (isset(static::$lookup[$from]))
		{
			// pass from the current unit to meters
			$value *= static::$lookup[$from];
		}

		// check if the resulting unit is supported
		if (isset(static::$lookup[$unit]))
		{
			// convert the current amount using the specified unit
			$value /= static::$lookup[$unit];
		}

		return $value;
	}

	/**
	 * Formats the specified distance to the closest unit.
	 *
	 * @param 	float 	$value 	The value to format.
	 * @param 	string 	$unit 	The length measure to use.
	 * @param 	float 	$from 	The unit of the value we are trying to format.
	 *
	 * @return 	string 	The formatted distance.
	 *
	 * @uses 	convert()
	 */
	public static function format($value, $unit = null, $from = null)
	{
		// obtain the name of the method to use to format the distance
		switch ($unit)
		{
			case self::MILE:
				$method = 'Miles';
				break;
			
			case self::YARD:
				$method = 'Yards';
				break;

			case self::FOOT:
				$method = 'Feet';
				break;

			default:
				$method = 'Meters';
		}

		// converts the distance to the specified unit
		$value  = static::convert($value, $unit, $from);
		$method = 'formatDistance' . $method;

		// dispatch child method to format the distance
		return static::$method($value);
	}

	/**
	 * Helper method used to format the distance in meters and kilometers.
	 *
	 * @param 	float 	$distance 	The distance to format.
	 *
	 * @return 	string 	The formatted distance.
	 */
	protected static function formatDistanceMeters($distance)
	{
		$labels = array(
			'VAPFORMATDISTMETERS',
			'VAPFORMATDISTKILOMETERS',
			'VAPFORMATDISTKILOMETERSTHO',
		);

		$iter = 0;

		while ($distance >= 1000 && $iter < count($labels) - 1)
		{
			$distance /= 1000;
			$iter++;
		}

		return JText::sprintf($labels[$iter], round($distance, 0));
	}

	/**
	 * Helper method used to format the distance in miles.
	 * In case the distance is lower than 0.1, it will be
	 * automatically formatted in yards.
	 *
	 * @param 	float 	$distance 	The distance to format.
	 *
	 * @return 	string 	The formatted distance.
	 *
	 * @uses 	format()
	 */
	protected static function formatDistanceMiles($distance)
	{
		if ($distance < 0.1)
		{
			return static::format($distance, self::YARD, self::MILE);
		}

		$labels = array(
			'VAPFORMATDISTLESSMILES',
			'VAPFORMATDISTMILE',
			'VAPFORMATDISTMILES',
			'VAPFORMATDISTKMILES',
		);

		$iter  = 0;

		if ($distance >= 1)
		{
			// use singular form
			$iter++;
			
			if ($distance >= 2)
			{
				// use plural form
				$iter++;

				while ($distance >= 1000 && $iter < count($labels) - 1)
				{
					$distance /= 1000;
					$iter++;
				}
			}
		}

		return JText::sprintf($labels[$iter], round($distance, 1));
	}

	/**
	 * Helper method used to format the distance in yards.
	 * In case the distance is lower than 1, it will be
	 * automatically formatted in feet.
	 *
	 * @param 	float 	$distance 	The distance to format.
	 *
	 * @return 	string 	The formatted distance.
	 *
	 * @uses 	format()
	 */
	protected static function formatDistanceYards($distance)
	{
		if ($distance < 1)
		{
			return static::format($distance, self::FOOT, self::YARD);
		}

		return JText::sprintf('VAPFORMATDISTYARDS', round($distance, 0));
	}

	/**
	 * Helper method used to format the distance in feet.
	 *
	 * @param 	float 	$distance 	The distance to format.
	 *
	 * @return 	string 	The formatted distance.
	 */
	protected static function formatDistanceFeet($distance)
	{
		$labels = array(
			'VAPFORMATDISTLESSFEET',
			'VAPFORMATDISTFEET',
		);

		$iter  = 0;

		if ($distance > 1)
		{
			$iter++;
		}

		return JText::sprintf($labels[$iter], round($distance, 1));
	}

	/**
	 * Identifier for meters.
	 *
	 * @var string
	 */
	const METER = 'm';

	/**
	 * Identifier for kilometers.
	 *
	 * @var string
	 */
	const KILOMETER = 'km';

	/**
	 * Identifier for miles.
	 *
	 * @var string
	 */
	const MILE = 'mi';

	/**
	 * Identifier for yards.
	 *
	 * @var string
	 */
	const YARD = 'yd';

	/**
	 * Identifier for feet.
	 *
	 * @var string
	 */
	const FOOT = 'ft';
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPDistanceHelper', 'DistanceHelper');

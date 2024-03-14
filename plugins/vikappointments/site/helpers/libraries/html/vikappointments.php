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
 * VikAppointments HTML global helper.
 *
 * @since 1.7
 */
abstract class JHtmlVikAppointments
{
	/**
	 * Converts a time expressed in minutes into hours and minutes.
	 * For example, the number '570' represents '9:30'.
	 *
	 * @param 	integer  $ts      The time in minutes.
	 * @param 	boolean  $string  True to return a formatted time.
	 * 							  Otherwise an object will be returned.
	 * @param 	string   $format  The time format. If not specified, the
	 * 							  default one will be used.
	 *
	 * @return 	mixed    A formatted time.
	 */
	public static function min2time($ts, $string = true, $format = null)
	{
		$time = new stdClass;

		// extract hour and min from TS
		$time->hour = floor($ts / 60);
		$time->min  = floor($ts % 60);

		// fetch time format
		$format = $format ? $format : VAPFactory::getConfig()->get('timeformat');

		// format time
		$time->format = date($format, mktime($time->hour, $time->min, 0, 1, 1, 2000));

		if ($string)
		{
			// return formatted time
			return $time->format;
		}

		// return detailed object
		return $time;
	}

	/**
	 * Converts a time object in minutes.
	 * For example, the time '9:30' represents the number '570'.
	 *
	 * @param 	mixed     $time      The time object|string.
	 * @param 	property  $property  The object property to look for.
	 *
	 * @return 	integer  The time in minutes.
	 */
	public static function time2min($time, $property = 'value')
	{
		if (!is_scalar($time))
		{
			// extract time from object
			$time = (object) $time;
			$time = $time->{$property};
		}

		// extract hours and minutes from time
		$hm = explode(':', $time);

		return (int) $hm[0] * 60 + (int) $hm[1];
	}

	/**
	 * Returns an array of week days.
	 *
	 * @param 	boolean  $short  True to use the short text.
	 *
	 * @return 	array 	 A list of days.
	 */
	public static function days($short = false)
	{
		$options = array();

		// create date
		$date = new JDate;

		// iterate week days
		for ($day = 0; $day < 7; $day++)
		{
			// use JDate to extract the day name
			$dayName = $date->dayToString($day, $short);
			// push day within the list
			$options[] = JHtml::fetch('select.option', $day, $dayName);
		}

		return $options;
	}

	/**
	 * Returns an array of years.
	 *
	 * @param 	integer  $start  The initial year.
	 * @param 	integer  $end    The ending year.
	 *
	 * @return 	array 	 A list of years.
	 */
	public static function years($start, $end)
	{
		$options = array();

		if ($start <= 0)
		{
			$year = (int) date('Y');

			$start = $year + $start;
			$end   = $year + $end;
		}

		// iterate years days
		for ($start; $start <= $end; $start++)
		{
			// push year within the list
			$options[] = JHtml::fetch('select.option', $start, $start);
		}

		return $options;
	}

	/**
	 * Returns an array of months.
	 *
	 * @param 	boolean  $short  True to use the short text.
	 *
	 * @return 	array 	 A list of months.
	 */
	public static function months($short = false)
	{
		$options = array();

		// create date
		$date = new JDate;

		// iterate months
		for ($month = 1; $month <= 12; $month++)
		{
			// use JDate to extract the month name
			$monthName = $date->monthToString($month, $short);
			// push month within the list
			$options[] = JHtml::fetch('select.option', $month, $monthName);
		}

		return $options;
	}

	/**
	 * Returns a list of day hours.
	 *
	 * @return 	array 	A list of hours.
	 */
	public static function hours()
	{
		$hours = array();

		$format = VAPFactory::getConfig()->get('timeformat');
		$format = preg_replace("/:i/", '', $format);

		for ($h = 0; $h < 24; $h++)
		{
			$hf = self::min2time($h * 60, $string = true, $format);

			$hours[] = JHtml::fetch('select.option', $h, $hf);
		}

		return $hours;
	}

	/**
	 * Returns a list of hour minutes.
	 *
	 * @param 	mixed  $step  The number of steps to use.
	 * 						  If not specified, the number of steps will be retrieved from
	 *                        the configuration of the program.
	 *
	 * @return 	array  A list of minutes.
	 */
	public static function minutes($step = null)
	{
		$minutes = array();

		if (!$step)
		{
			// retrieve step from configuration
			$step = VAPFactory::getConfig()->getUint('minuteintervals');
		}

		// prevent infinite loop
		$step = max(array($step, 1));

		for ($m = 0; $m < 60; $m += $step)
		{
			$minutes[] = JHtml::fetch('select.option', $m, ($m < 10 ? '0' : '') . $m);
		}

		return $minutes;
	}

	/**
	 * Returns a list of times.
	 *
	 * @param 	array  $options  A configuration array. Here's a list of supports options:
	 *                           - step    int     The number of steps to use. If not specified,
	 *                                             it will be retrieved from the configuration.
	 *                           - from    mixed   The initial time (in minutes). Use true to load
	 *                                             the system opening time. Use false for 00:00.
	 *                           - to      mixed   The ending time (in minutes). Use true to load
	 *                                             the system closing time. Use false for 00:00.
	 *                           - format  string  The time format to use. Leave empty to use
	 *                                             the global one.
	 *                           - value   string  Use 'string' to use a 24h time as value.
	 *                                             Use 'int' to represent the time in minutes.
	 *
	 * @return 	array  A list of times.
	 */
	public static function times($options = array())
	{
		$config = VAPFactory::getConfig();

		if (isset($options['step']))
		{
			// prevent infinite loop
			$options['step'] = max(array($options['step'], 1));
		}
		else
		{
			// retrieve step from configuration
			$options['step'] = $config->getUint('minuteintervals');
		}

		if (isset($options['from']))
		{
			if ($options['from'] === true)
			{
				// get opening time from config
				$from = VikAppointments::getOpeningTime();
				
				// convert opening time in minutes
				$options['from'] = $from['hour'] * 60 + $from['min'];
			}
			else
			{
				// validate bounds
				$options['from'] = min(array(abs($options['from']), 1440));
			}
		}
		else
		{
			// start from midnight
			$options['from'] = 0;
		}

		if (isset($options['to']))
		{
			if ($options['to'] === true)
			{
				// get closing time to config
				$to = VikAppointments::getClosingTime();
				
				// convert closing time in minutes
				$options['to'] = $to['hour'] * 60 + $to['min'];
			}
			else
			{
				// validate bounds
				$options['to'] = min(array(abs($options['to']), 1440));
			}
		}
		else
		{
			// end at midnight
			$options['to'] = 1440;
		}

		if ($options['from'] > $options['to'])
		{
			// from time higher then end time, invert them
			$tmp = $options['from'];
			$options['from'] = $options['to'];
			$options['to'] = $tmp;
		}

		if (empty($options['format']))
		{
			// use global one
			$options['format'] = null;
		}

		$times = array();

		for ($hm = $options['from']; $hm <= $options['to']; $hm += $options['step'])
		{
			// get time object
			$time = static::min2time($hm, $string = false, $options['format']);

			if (empty($options['value']) || $options['value'] == 'string')
			{
				// use 24h format as value
				$value = $time->hour . ':' . $time->min;
			}
			else
			{
				// use minutes representation
				$value = $hm;
			}

			// add time
			$times[] = JHtml::fetch('select.option', $value, $time->format);
		}

		return $times;
	}

	/**
	 * Displays the rating of a review by printing
	 * the matched starts as image or through FontAwesome.
	 *
	 * FontAwesome is NOT loaded here.
	 *
	 * @param 	float 	 $rating   The rating amount (0-5).
	 * @param 	mixed  	 $image    True to use the images, false to use FA (v4).
	 * 							   In case of a string, it will match FA version.
	 * @param 	boolean  $missing  True to display the missing stars, false to hide them.
	 *
	 * @return 	string 	 The resulting HTML.
	 */
	public static function rating($rating, $image = true, $missing = true)
	{
		$html = '';

		if (!$image)
		{
			// in case of missing FontAwesome version, use the default one
			$image = '5';
		}

		// display filled stars
		for ($i = 1; $i <= $rating; $i++)
		{
			if ($image === true)
			{
				$html .= '<img src="' . VAPASSETS_URI . 'css/images/rating-star.png" class="vap-rating-star" />';
			}
			else
			{
				// look for a specific FontAwesome version
				if (version_compare((string) $image, '5', '>='))
				{
					// use FA 5
					$class = 'fas fa-star';
				}
				else
				{
					// use FA 4
					$class = 'fa fa-star';
				}

				$html .= '<i class="' . $class . '"></i>';
			}
		}
		
		// display half star
		if (round($rating) != $rating)
		{
			if ($image === true)
			{
				$html .= '<img src="' . VAPASSETS_URI . 'css/images/rating-star-middle.png" class="vap-rating-star" />';
			}
			else
			{
				// look for a specific FontAwesome version
				if (version_compare((string) $image, '5', '>='))
				{
					// use FA 5
					$class = 'fas fa-star-half-alt';
				}
				else
				{
					// use FA 4
					$class = 'fa fa-star-half-o';
				}
				
				$html .= '<i class="' . $class . '"></i>';
			}
		}
		
		// display missing stars
		if ($missing)
		{
			for ($i = round($rating) + 1; $i <= 5; $i++)
			{
				if ($image === true)
				{
					$html .= '<img src="' . VAPASSETS_URI . 'css/images/rating-star-no.png" class="vap-rating-star" />';
				}
				else
				{
					// look for a specific FontAwesome version
					if (version_compare((string) $image, '5', '>='))
					{
						// use FA 5
						$class = 'far fa-star';
					}
					else
					{
						// use FA 4
						$class = 'fa fa-star-o';
					}
					
					$html .= '<i class="' . $class . '"></i>';
				}
			}
		}

		return $html;
	}

	/**
	 * Returns the HTML used to display a tag.
	 *
	 * @param 	mixed 	$tag     Either a ID or a tag name or the object/array itself.
	 * @param 	string  $layout  The type of layout to use.
	 * @param 	array 	$attrs   A list of tag attributes.
	 *
	 * @return  string  The HTML of the tag.
	 */
	public static function tag($tag, $layout = 'badge', array $attrs = array())
	{
		if (is_scalar($tag))
		{
			$model = JModelVAP::getInstance('tag');

			if (preg_match("/^\d+$/", $tag))
			{
				// load tag by ID, to take advantage of the cache
				$tag = $model->readTags((int) $tag);
			}
			else
			{
				// load tag by name
				$tag = $model->getItem(array('name' => $tag));
			}

			if (!$tag)
			{
				// tag not found
				return '';
			}
		}
		else
		{
			// cast to object
			$tag = (object) $tag;
		}

		// instantiate layout file
		$layout = new JLayoutFile('tag.' . $layout);

		// build layout data
		$data = array(
			'tag'   => $tag,
			'attrs' => $attrs,
		);

		// display layout
		return $layout->render($data);
	}

	/**
	 * Calculates the maximum upload file size and returns string with unit or the size in bytes.
	 *
	 * @param   bool          $unitOutput  This parameter determines whether the return value
	 *                                     should be a string with a unit.
	 *
	 * @return  float|string  The maximum upload size of files with the appropriate unit or in bytes.
	 * 
	 * @since 	1.7.3
	 */
	public static function maxuploadsize($unitOutput = true)
	{
		static $max_size = false;
		
		if ($max_size === false)
		{
			$max_size   = self::parseSize(ini_get('post_max_size'));
			$upload_max = self::parseSize(ini_get('upload_max_filesize'));

			// check what is the highest value between post and upload max sizes
			if ($upload_max > 0 && ($upload_max < $max_size || $max_size == 0))
			{
				$max_size = $upload_max;
			}
		}

		if (!$unitOutput)
		{
			// return numerical max size
			return $max_size;
		}

		// format max size
		return JHtml::fetch('number.bytes', $max_size, 'auto', 0);
	}

	/**
	 * Returns the size in bytes without the unit for the comparison.
	 *
	 * @param   string  $size  The size which is received from the PHP settings.
	 *
	 * @return  float   The size in bytes without the unit.
	 * 
	 * @since 	1.7.3
	 */
	private static function parseSize($size)
	{
		// extract the size unit
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
		// take only the size numbers
		$size = preg_replace('/[^0-9\.]/', '', $size);

		$return = round($size);

		if ($unit)
		{
			// calculate the correct size according to the specified unit
			$return = round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
		}

		return $return;
	}
}

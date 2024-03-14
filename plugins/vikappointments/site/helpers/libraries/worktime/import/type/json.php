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

VAPLoader::import('libraries.worktime.import.type');

/**
 * Imports the working days from a JSON format.
 * 
 * @since 1.7.1
 */
class VAPWorktimeImportTypeJson implements VAPWorktimeImportType
{
	/**
	 * Processes the given buffer and imports the working days.
	 * 
	 * @param 	string  $buffer  The contents to parse.
	 * 
	 * @return 	array   An array containing all the fetched working times.
	 */
	public function process($buffer)
	{
		// try to decode JSON contents
		$data = json_decode($buffer);

		if (!is_countable($data))
		{
			// invalid data
			return [];
		}

		return $this->doProcess($data);
	}

	/**
	 * Helper method used to process the array extracted from the JSON string.
	 * 
	 * @param 	array   $data  The JSON array.
	 * 
	 * @return 	array   An array containing all the fetched working times.
	 */
	final protected function doProcess($data)
	{
		$list = [];

		// iterate all array elements
		foreach ($data as $elem)
		{
			if (isset($elem->date))
			{
				$ret = $this->createDate($elem);
			}
			else if (isset($elem->dates))
			{
				$ret = $this->createDates($elem);
			}
			else if (isset($elem->range))
			{
				$ret = $this->createRange($elem);
			}

			$list = array_merge($list, $ret);
		}

		return $list;
	}

	/**
	 * Helper method used to create a working day.
	 * 
	 * @param 	object  The import directive.
	 * 
	 * @return 	array   An array of working times.
	 */
	final protected function createDate($elem)
	{
		$ts = strtotime($elem->date);

		if ($ts === false)
		{
			// invalid time
			return null;
		}

		$wd = new stdClass;
		$wd->ts     = $ts;
		$wd->tsdate = $elem->date;
		$wd->day    = JFactory::getDate($elem->date)->format('w');
		$wd->closed = 0;

		// normalize times
		if (empty($elem->times))
		{
			// no times, mark as closed
			$wd->closed = 1;

			return [$wd];
		}

		$list = [];

		// iterate all times
		foreach ((array) $elem->times as $time)
		{
			$wd->fromts = JHtml::fetch('vikappointments.time2min', @$time->from);
			$wd->endts  = JHtml::fetch('vikappointments.time2min', @$time->to);

			$list[] = clone $wd;
		}

		return $list;
	}

	/**
	 * Helper method used to create a list of working days.
	 * 
	 * @param 	object  The import directive.
	 * 
	 * @return 	array   An array of working times.
	 */
	final protected function createDates($elem)
	{
		$list = [];

		foreach ((array) $elem->dates as $date)
		{
			// create temporary object
			$tmp = new stdClass;
			$tmp->date  = $date;
			$tmp->times = $elem->times;

			$list = array_merge($list, $this->createDate($tmp));
		}

		return $list;
	}

	/**
	 * Helper method used to create a range of working days.
	 * 
	 * @param 	object  The import directive.
	 * 
	 * @return 	array   An array of working times.
	 */
	final protected function createRange($elem)
	{
		$list = [];

		$start = JFactory::getDate(@$elem->range->start);
		$end   = JFactory::getDate(@$elem->range->end);

		while ($start->format('Y-m-d') <= $end->format('Y-m-d'))
		{
			// create temporary object
			$tmp = new stdClass;
			$tmp->date  = $start->format('Y-m-d');
			$tmp->times = $elem->times;

			$list = array_merge($list, $this->createDate($tmp));

			$start->modify('+1 day');
		}

		return $list;
	}

	/**
	 * Returns a string describing how the file content should be built.
	 * 
	 * @return 	string  An example of usage.
	 */
	public function getSample()
	{
		$json = [
			[
				"date" => JFactory::getDate('+1 day')->format('Y-m-d'),
				"times" => [
					[
						"from" => "10:00",
						"to"   => "13:00",
					]
				],
			],
			[
				"dates" => [
					JFactory::getDate('+2 days')->format('Y-m-d'),
					JFactory::getDate('+3 days')->format('Y-m-d'),
					JFactory::getDate('+4 days')->format('Y-m-d'),
				],
				"times" => [
					[
						"from" => "10:00",
						"to"   => "12:00",
					],
					[
						"from" => "14:00",
						"to"   => "19:00",
					],
				],
			],
			[
				"range" => [
					"start" => JFactory::getDate('+5 days')->format('Y-m-d'),
					"end"   => JFactory::getDate('+15 days')->format('Y-m-d'),
				],
				"times" => [],
			],
		];

		if (defined('JSON_PRETTY_PRINT'))
		{
			$mask = JSON_PRETTY_PRINT;
		}
		else
		{
			$mask = 0;
		}

		return json_encode($json, $mask);
	}
}

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
 * Imports the working days from a plain text format.
 * 
 * @since 1.7.1
 */
class VAPWorktimeImportTypeTxt extends VAPWorktimeImportTypeJson
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
		$data = array_filter(preg_split("/\R/", $buffer));

		$lookup = [];
		$seek   = null;

		foreach ($data as $line)
		{
			$line = trim($line);

			// check if we have a date
			if (preg_match("/[0-9]{2,4}[.\-\/][0-9]{2,4}[.\-\/][0-9]{2,4}/", $line))
			{
				// create current seek
				$seek = $line;

				// init date cell
				$lookup[$seek] = [];
			}
			else if ($seek && $line)
			{
				// push time into the seek
				$lookup[$seek][] = $line;
			}
		}

		$json = [];

		foreach ($lookup as $type => $times)
		{
			$tmp = new stdClass;

			// check if we have a range of dates
			if (preg_match("/^\[\s*(.*?)\s*\]$/", $type, $match))
			{
				// extract dates list
				$arr = array_values(array_filter(preg_split("/\s*,\s*/", $match[1])));

				$tmp->range = new stdClass;
				$tmp->range->start = array_shift($arr);
				$tmp->range->end   = array_pop($arr);
			}
			// check if we have a list of dates
			else if (preg_match("/,/", $type))
			{
				// extract dates list
				$tmp->dates = array_values(array_filter(preg_split("/\s*,\s*/", $type)));
			}
			// fallback to a single date
			else
			{
				$tmp->date = $type;
			}

			$tmp->times = [];

			foreach ($times as $time)
			{
				// extract from and to time
				$chunks = preg_split("/\s*-\s*/", $time);

				// validate times
				$chunks = array_filter($chunks, function($t)
				{
					return preg_match("/^[0-9]{1,2}:[0-9]{1,2}$/", $t);
				});

				if (count($chunks) !== 2)
				{
					// invalid string
					continue;
				}

				$t = new stdClass;
				$t->from = $chunks[0];
				$t->to   = $chunks[1];

				// register time
				$tmp->times[] = $t;
			}

			$json[] = $tmp;
		}

		// complete process through parent method
		return $this->doProcess($json);
	}

	/**
	 * Returns a string describing how the file content should be built.
	 * 
	 * @return 	string  An example of usage.
	 */
	public function getSample()
	{
		$list = [
			JFactory::getDate('+2 days')->format('Y-m-d'),
			JFactory::getDate('+3 days')->format('Y-m-d'),
			JFactory::getDate('+4 days')->format('Y-m-d'),
		];

		$range = [
			JFactory::getDate('+5 days')->format('Y-m-d'),
			JFactory::getDate('+15 days')->format('Y-m-d'),
		];

		return JFactory::getDate('+1 day')->format('Y-m-d') . "\n"
			. "10:00 - 13:00\n"
			. implode(',', $list) . "\n"
			. "10:00 - 12:00\n"
			. "14:00 - 19:00\n"
			. "[" . implode(',', $range) . "]";
	}
}

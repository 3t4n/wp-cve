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
 * Imports the working days from a XML format.
 * 
 * @since 1.7.1
 */
class VAPWorktimeImportTypeXml extends VAPWorktimeImportTypeJson
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
		$json = [];

		$xml = new SimpleXMLElement($buffer);

		foreach ($xml->workingday as $wd)
		{
			$elem = new stdClass;

			// extract date from attributes
			$date = (string) $wd->attributes()->date;

			if ($date)
			{
				if (preg_match("/,/", $date))
				{
					// convert dates into an array
					$elem->dates = array_filter(preg_split("/\s*,\s*/", $date));
				}
				else
				{
					// use date as is
					$elem->date = $date;
				}
			}
			else
			{
				$elem->range = new stdClass;

				// extract range from attributes
				$elem->range->start = (string) $wd->attributes()->start;
				$elem->range->end   = (string) $wd->attributes()->end;
			}

			$elem->times = [];

			foreach ($wd->time as $time)
			{
				$tmp = new stdClass;
				$tmp->from = (string) $time->attributes()->from;
				$tmp->to   = (string) $time->attributes()->to;

				$elem->times[] = $tmp;
			}

			$json[] = $elem;
		}

		return $this->doProcess($json);
	}

	/**
	 * Returns a string describing how the file content should be built.
	 * 
	 * @return 	string  An example of usage.
	 */
	public function getSample()
	{
		$dates = [
			JFactory::getDate('+1 day')->format('Y-m-d'),
			JFactory::getDate('+2 days')->format('Y-m-d'),
			JFactory::getDate('+3 days')->format('Y-m-d'),
			JFactory::getDate('+4 days')->format('Y-m-d'),
			JFactory::getDate('+5 days')->format('Y-m-d'),
			JFactory::getDate('+15 days')->format('Y-m-d'),
		];

		return
<<<XML
<worktable>
	<workingday date="{$dates[0]}">
		<time from="10:00" to="13:00" />
	</workingday>
	<workingday date="{$dates[1]},{$dates[2]},{$dates[3]}">
		<time from="10:00" to="12:00" />
		<time from="14:00" to="19:00" />
	</workingday>
	<workingday start="{$dates[4]}" end="{$dates[5]}">
	</workingday>
</worktable>
XML
		;
	}
}

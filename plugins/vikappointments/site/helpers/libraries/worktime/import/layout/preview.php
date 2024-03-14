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

VAPLoader::import('libraries.worktime.import.layout');

/**
 * Groups the times by date, in order to have a better preview.
 * 
 * @since 1.7.1
 */
class VAPWorktimeImportLayoutPreview implements VAPWorktimeImportLayout
{
	/**
	 * Refactor the times array into a specific layout.
	 * 
	 * @param 	array   $times  An array of working times.
	 * 
	 * @return 	mixed   The resulting layout.
	 */
	public function build($times)
	{
		$lookup = [];

		$config = VAPFactory::getConfig();

		foreach ($times as $time)
		{
			$time = (object) $time;

			$date = $time->tsdate;

			if (!isset($lookup[$date]))
			{
				$dt = JFactory::getDate($date);

				$dateElem = new stdClass;
				$dateElem->date  = $dt->format($config->get('dateformat'));
				$dateElem->ymd   = $dt->format('Ymd');
				$dateElem->times = [];

				$lookup[$date] = $dateElem;
			}

			if (isset($time->fromts))
			{
				$time->fromTime = JHtml::fetch('vikappointments.min2time', $time->fromts);
				$time->toTime   = JHtml::fetch('vikappointments.min2time', $time->endts);

				$lookup[$date]->times[] = $time;
			}
		}
		
		return $lookup;
	}
}

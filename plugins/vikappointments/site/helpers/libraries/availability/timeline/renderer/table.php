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

VAPLoader::import('libraries.availability.timeline.renderer');

/**
 * Employees list times-table renderer.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineRendererTable extends VAPAvailabilityTimelineRenderer
{
	/**
	 * Prepares the data to display.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array   The resulting display data.
	 */
	public function getDisplayData(array $data = array())
	{
		// register times-table
		$data['table'] = $this->timeline;

		// create previous date
		$prev = new JDate(key($this->timeline) . ' 00:00:00');
		$prev->modify('-' . count($this->timeline) . ' day');
		$data['prev_day'] = $prev->format('Y-m-d');

		if ($prev < JFactory::getDate('today 00:00:00'))
		{
			// day in the past, disable button
			$data['prev_day'] = null;
		}

		// create next date
		$next = new JDate(key($this->timeline) . ' 00:00:00');
		$next->modify('+' . count($this->timeline) . ' day');
		$data['next_day'] = $next->format('Y-m-d');

		/**
		 * This value is used to calculate the maximum 
		 * number of times available for a certain day.
		 *
		 * @var integer
		 */
		$max_rows = 0;

		// iterate all the supported times
		foreach ($this->timeline as $timeline)
		{
			if (!$timeline)
			{
				// invalid timeline, go ahead
				continue;
			}

			// register service ID
			$data['id_ser'] = $timeline->getSearch()->get('id_service');

			// convert timeline into a single level array
			$arr = $timeline->toArray($flatten = true);

			// take only the available time-slots
			$arr = array_filter($arr, function($status)
			{
				return $status == 1;
			});

			// get maximum number of rows
			$max_rows = max(array(count($arr), $max_rows));
		}

		// register maximum rows as display data
		$data['max_rows'] = $max_rows;

		return $data;
	}

	/**
	 * Renders the layout of the specified timeline.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	string  The timeline HTML.
	 */
	protected function render(array $data = array())
	{
		$layout = new JLayoutFile('timeline.table');

		// the layout is available only for the front-end

		return $layout->render($data);
	}
}

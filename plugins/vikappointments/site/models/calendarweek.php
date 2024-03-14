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

VAPLoader::import('libraries.mvc.model');
VAPLoader::import('libraries.availability.manager');
VAPLoader::import('libraries.availability.timeline.factory');

/**
 * VikAppointments weekly calendar generator model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCalendarweek extends JModelVAP
{
	/**
	 * Returns the structure of the calendar to display
	 * as tables of months.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	object 	The resulting calendar.
	 */
	public function getCalendar(array $options = array())
	{
		$config = VAPFactory::getConfig();

		if (empty($options['numdays']))
		{
			// use default amount specified from the configuration
			$options['numdays'] = $config->getUint('calendarweekdays', 7);
		}

		$now = JDate::getInstance()->format('Y-m-d');

		if (empty($options['start']) || $options['start'] < $now)
		{
			// start from current date
			$options['start'] = $now;
		}

		// get service details
		$assocModel = JModelVAP::getInstance('serempassoc');
		$service = $assocModel->getOverrides($options['id_ser'], $options['id_emp']);

		// fetch employee/system timezone
		$emp_tz = JModelVAP::getInstance('employee')->getTimezone($options['id_emp']);

		$prevThreshold = $now;

		if (!VAPDateHelper::isNull($service->start_publishing))
		{
			// adjust start publishing to given timezone
			$startPub = new JDate($service->start_publishing);
			$startPub->setTimezone(new DateTimeZone($emp_tz));

			$start_publishing = $startPub->format('Y-m-d', $local = true);

			// compare service start publishing with start date
			if ($start_publishing > $options['start'])
			{
				// use service start publishing
				$options['start'] = $start_publishing;
			}

			if ($start_publishing > $now)
			{
				// use the start publishing as minimum date
				$prevThreshold = $start_publishing;
			}
		}

		if (!isset($options['admin']))
		{
			// flag as customer when not specified
			$options['admin'] = false;
		}

		// set initial date at midnight
		$date = new JDate($options['start']);
		$date->modify('00:00:00');

		// create availability search object
		$search = VAPAvailabilityManager::getInstance($options['id_ser'], $options['id_emp'], $options);

		$calendar = new stdClass;

		// fetch head
		$calendar->days = array();

		$calendar->prev = clone $date;
		$calendar->prev->modify('-' . $options['numdays'] . ' days');
		$calendar->prev = $calendar->prev->format('Y-m-d');

		if ($calendar->prev < $prevThreshold)
		{
			if ($date->format('Y-m-d') <= $prevThreshold)
			{
				// date in the past, cannot go behind
				$calendar->prev = null;
			}
			else
			{
				$calendar->prev = $prevThreshold;
			}
		}

		// get employee search model to generate the timeline
		$empSearchModel = JModelVAP::getInstance('employeesearch');

		for ($i = 0; $i < $options['numdays']; $i++)
		{
			$day = new stdClass;
			// get the day of the week that should stay at this position
			$day->wday = (int) $date->format('w');
			$day->day  = (int) $date->format('d');
			$day->mon  = (int) $date->format('m');
			$day->year = (int) $date->format('Y');
			$day->date = $date->format('Y-m-d');

			$day->week = new stdClass;
			$day->week->name = new stdClass;
			$day->week->name->long  = $date->dayToString($day->wday, false);
			$day->week->name->short = $date->dayToString($day->wday, true);

			$day->month = new stdClass;
			$day->month->name = new stdClass;
			$day->month->name->long  = $date->monthToString($day->mon, $abbr = false);
			$day->month->name->short = $date->monthToString($day->mon, $abbr = true);

			// calculate the availability of this day
			$avail = $this->checkDayStatus($day->date, $search);

			// set open/closed status
			$day->closed = !$avail;
			// register availability flag
			$day->open = (bool) $avail;

			if ($day->open)
			{
				// prepare timeline options
				$timelineOptions = $options;
				$timelineOptions['date'] = $day->date;

				// get timeline details
				$day->timeline = $empSearchModel->getTimeline($timelineOptions);

				if (!$day->timeline)
				{
					// register fetched error
					$day->timelineError = $empSearchModel->getError($last = null, $string = true);
				}
			}
			else
			{
				// day closed or expired, use an empty timeline
				$day->timeline = array();
			}

			$calendar->days[] = $day;

			$date->modify('+1 day');
		}

		$calendar->next = $date->format('Y-m-d');

		if (!VAPDateHelper::isNull($service->end_publishing))
		{
			// adjust end publishing to given timezone
			$endPub = new JDate($service->end_publishing);
			$endPub->setTimezone(new DateTimeZone($emp_tz));

			$end_publishing = $endPub->format('Y-m-d', $local = true);

			if ($calendar->next > $end_publishing)
			{
				// already reached the maximum threshold
				$calendar->next = null;
			}
		}

		return $calendar;
	}

	/**
	 * Checks whether the selected date is closed or open.
	 *
	 * @param 	string 	 $date    The UTC date string (military format).
	 * @param 	mixed    $search  An availability search instance.
	 *
	 * @return 	boolean  False if closed, true if open.
	 */
	public function checkDayStatus($date, $search)
	{
		if (!$search->isAdmin())
		{
			// make sure the date is not in the past if we
			// are checking the availability as customers
			if ($search->isPastTime($date))
			{
				// date in the past
				return false;
			}
		}

		// check whether there's at least an employee offering
		// the given service on the specified day
		return (bool) $search->isDayOpen($date);
	}
}

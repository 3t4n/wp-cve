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
 * VikAppointments calendar model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCalendar extends JModelVAP
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

		// get first day of the week
		$firstday = $config->getUint('firstday');

		if (empty($options['numcal']))
		{
			// use default amount (12 months)
			$options['numcal'] = 12;
		}

		if (!empty($options['date']))
		{
			// start the calendar from the first day of the month of the provided date
			$options['start'] = JDate::getInstance($options['date'])->format('Y-m-01');
		}

		if (empty($options['start']))
		{
			$year = !empty($options['year']) ? (int) $options['year'] : 'Y';

			// start from first day of this year
			$options['start'] = JDate::getInstance()->format($year . '-01-01');
		}

		$days_shift = array();

		for ($i = 0; $i < 7; $i++)
		{
			$days_shift[$i] = (6 - ($firstday - $i) + 1) % 7;
			/**
			 *	DAY = ( (NUM_DAYS - 1) - (SHIFT - DAY_INDEX) + 1 ) % NUM_DAYS 
			 *
			 * 	SATURDAY
			 * 	0				1				2				3				4				5				6
			 * 	6-(6-0)+1%7=1	6-(6-1)+1%7=2	6-(6-2)+1%7=3	6-(6-3)+1%7=4	6-(6-4)+1%7=5	6-(6-5)+1%7=6	6-(6-6)+1%7=0
			 * 
			 * 	SUNDAY
			 * 	0				1				2				3				4				5				6
			 * 	6-(0-0)+1%7=0	6-(0-1)+1%7=1	6-(0-2)+1%7=2	6-(0-3)+1%7=3	6-(0-4)+1%7=4	6-(0-5)+1%7=5	6-(0-6)+1%7=6
			 * 
			 * 	MONDAY
			 * 	0				1				2				3				4				5				6
			 * 	6-(1-0)+1%7=6 	6-(1-1)+1%7=0	6-(1-2)+1%7=1	6-(1-3)+1%7=2	6-(1-4)+1%7=3	6-(1-5)+1%7=4	6-(1-6)+1%7=5
			 * 
			 * 	WEDNESDAY
			 * 	0				1				2				3				4				5				6
			 * 	6-(3-0)+1%7=4 	6-(3-1)+1%7=5	6-(3-2)+1%7=6	6-(3-3)+1%7=0	6-(3-4)+1%7=1	6-(3-5)+1%7=2	6-(3-6)+1%7=3
			 */
		}

		// set initial date at midnight
		$date = new JDate($options['start']);
		$date->modify('00:00:00');

		$calendar = new stdClass;

		// fetch head
		$calendar->head = array();

		for ($i = 0; $i < 7; $i++)
		{
			$day = new stdClass;
			
			// get the day of the week that should stay at this position
			$day->wday = VikAppointments::getShiftedDay($i, $firstday);	

			$day->name = new stdClass;
			$day->name->long  = $date->dayToString($day->wday, false);
			$day->name->short = $date->dayToString($day->wday, true);

			$calendar->head[] = $day;
		}

		$calendar->months = array();

		if (!isset($options['admin']))
		{
			// flag as administrator when not specified
			$options['admin'] = true;
		}

		// create availability search object
		$search = VAPAvailabilityManager::getInstance($options['id_ser'], $options['id_emp'], $options);

		// iterate by the number of requested calendars
		for ($cal = 0; $cal < $options['numcal']; $cal++)
		{
			$month = new stdClass;
			$month->mon  = (int) $date->format('n');
			$month->year = (int) $date->format('Y');

			$month->name = new stdClass;
			$month->name->long  = $date->monthToString($month->mon, $abbr = false);
			$month->name->short = $date->monthToString($month->mon, $abbr = true);

			$month->days = array();
			$month->days[0] = array();
			
			// iterate until we find the first day of the month
			for ($i = 0, $n = $days_shift[(int) $date->format('w')]; $i < $n; $i++)
			{
				// add null element 
				$month->days[0][] = null;
			}

			// get number of days of this month
			$mon_num_days = (int) $date->format('t');

			$seek = 0;

			for ($day = 1; $day <= $mon_num_days; $day++)
			{
				if (count($month->days[$seek]) == 7)
				{
					// add a new line
					$month->days[] = array();
					$seek++;
				}

				$d = new stdClass;
				$d->day  = $day;
				$d->date = $date->format('Y-m-d');
				$d->wday = (int) $date->format('w');

				// calculate the availability of this day
				$avail = $this->checkDayAvailability($d->date, $search);

				// set open/closed status
				$d->closed = $avail === false;
				// register availability flag
				$d->available = (int) $avail;

				// add day
				$month->days[$seek][] = $d;

				// go to next day
				$date->modify('+1 day');
			}

			// iterate until we fill the last row
			while (count($month->days[$seek]) < 7)
			{
				// add null element 
				$month->days[$seek][] = null;
			}

			$calendar->months[] = $month;
		}

		return $calendar;
	}

	/**
	 * Calculates an approximative availability for the specified
	 * date, service and employee.
	 *
	 * @param 	string 	 $date    The UTC date string (military format).
	 * @param 	mixed    $search  An availability search instance.
	 *
	 * @return 	mixed    False if closed, or an integer matching the availability
	 *                   status (0: closed, 1: available, 2: partially available).
	 */
	public function checkDayAvailability($date, $search)
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
		$open = $search->isDayOpen($date);
		
		if (!$open)
		{
			// seems to be closed on this day
			return false;
		}

		// calculate the availability status on this date
		return $search->isDayAvailable($date);
	}

	/**
	 * Calculates the resulting availability timeline according
	 * to the specified search options.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	mixed 	The resulting renderer.
	 */
	public function getTimeline($options)
	{
		// create availability search instance
		$search = VAPAvailabilityManager::getInstance($options['id_ser'], $options['id_emp'], $options);
		// grant administrator access
		$search->set('admin', true);

		// define default options
		$options['people'] = isset($options['people']) ? $options['people'] : 1;
		$options['id_res'] = isset($options['id_res']) ? $options['id_res'] : 0;

		// number of people cannot be lower than 1
		$options['people'] = max(array(1, (int) $options['people']));

		try
		{
			if ($search->get('id_employee'))
			{
				// let the factory fetches the most-appropriate parser
				$parser = null;
			}
			else
			{
				// for find reservation, use a different parser to access a
				// list of timelines (one for each employee)
				$parser = 'allemployees';
			}

			// create timeline parser instance
			$parser = VAPAvailabilityTimelineFactory::getParser($search, $parser);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		// elaborate timeline
		$timeline = $parser->getTimeline($options['date'], $options['people'], $options['id_res']);

		if (!$timeline)
		{
			// propagate error message
			$this->setError($parser->getError());

			return false;
		}

		try
		{
			// create timeline renderer instance
			$renderer = VAPAvailabilityTimelineFactory::getRenderer($timeline);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		return $renderer; 
	}
}

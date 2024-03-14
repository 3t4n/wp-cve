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

VAPLoader::import('libraries.availability.timeline.parser');

/**
 * Employee timeline parser.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineParserEmployee extends VAPAvailabilityTimelineParser
{
	/**
	 * Internal method used by children classes to implement their own logic
	 * to fetch the availability timeline.
	 *
	 * @param 	string 	 $date    The UTC date in military format.
	 * @param 	integer  $people  The number of participants.
	 * @param 	integer  $id      The selected appointment ID.
	 *
	 * @return 	mixed    The resulting timeline on success, false otherwise.
	 */
	protected function buildTimeline($date, $people = 1, $id = 0)
	{
		// look for a closing day/period
		if ($this->search->isClosingDay($date))
		{
			// the day is closed
			$this->setError(JText::translate('VAPFINDRESCLOSINGDAY'));
			
			return false;
		}

		// check whether the service is published for the given date
		if (!$this->search->isServicePublished($date))
		{
			// service not published
			$this->setError(JText::translate('VAPFINDRESNODAYSERVICE'));

			return false;
		}

		// get employee working times
		$worktimes = $this->search->getWorkingTimes($date);

		if (!$worktimes)
		{
			// the employee doesn't work on the specified date
			$this->setError(JText::translate('VAPFINDRESNODAYEMPLOYEE'));

			return false;
		}

		// extract service and employee IDs from search object
		$id_service  = (int) $this->search->get('id_service');
		$id_employee = (int) $this->search->get('id_employee');

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// get service details
		$service = $model->getOverrides($id_service, $id_employee);

		// validate selected number of people (front-end only)
		if (!$this->search->isAdmin() && $people > $service->max_capacity)
		{
			// number of people not supported
			$this->setError(JText::translate('VAPFINDRESPEOPLENOTVALID'));

			return false;
		}

		/**
		 * We are looking for the appointments for the current day.
		 * Extend this bounds in order to support midnight reservations.
		 *
		 * Instead having:
		 * 2021-07-09 @ 00:00:00 - 2021-07-09 23:59:59
		 * we need to have:
		 * 2021-07-08 @ 00:00:00 - 2021-07-10 23:59:59
		 *
		 * @since 1.6
		 */
		$start = JDate::getInstance($date);
		$start->modify('-1 day 00:00:00');

		$end = JDate::getInstance($date);
		$end->modify('+1 day 23:59:59');

		// load existing bookings
		$bookings = $this->search->getReservations($start->toSql(), $end->toSql(), $id);

		// use helper method to elaborate the timeline
		$arr = $this->elaborateTimeLine($date, $people, $worktimes, $bookings, $service);

		// create timeline
		$timeline = new VAPAvailabilityTimeline($date, $this->search);

		for ($i = 0; $i < count($arr); $i++)
		{
			// create new level
			$timeline->addLevel();

			foreach ($arr[$i] as $k => $v)
			{
				// create check-in date time
				$checkin = new JDate($date);
				$checkin->modify(JHtml::fetch('vikappointments.min2time', $k, $string = true, $format = 'H:i:s'));

				// check whether the time is in the past (preserve them in case of admin)
				if ($arr[$i][$k] && $this->search->isPastTime($checkin))
				{
					// unset block if it is in the past
					unset($arr[$i][$k]);
				}
				else if ($k >= 1440)
				{
					/**
					 * Unset the time slots that exceed the midnight.
					 *
					 * @since 1.6
					 */
					if (!$service->checkout_selection)
					{
						/**
						 * Time-slots that exceed the midnight are mandatory for
						 * the checkout selection. Unset them only whether the
						 * "checkout_selection" parameter is turned off.
						 *
						 * @since 1.6.2
						 */
						unset($arr[$i][$k]);
					}
				}

				if (isset($arr[$i][$k]))
				{
					$trace = array();

					// add time
					$timeBlock = $timeline->addTime($k, $arr[$i][$k], $service->duration);

					// calculate rate for the current time block
					$price = VAPSpecialRates::getRate($id_service, $id_employee, $timeBlock->checkin(), $people, $trace);

					// multiply by the number of guests (if enabled)
					if ($service->priceperpeople)
					{
						$price *= $people;
					}

					// set time block price
					$timeBlock->setPrice($price);

					if ($trace && !empty($trace['rates']))
					{
						// register rate trace
						$timeBlock->setRatesTrace($trace['rates']);
					}
				}
			}
		}

		// make sure all the times are not in the past
		if (!$timeline->hasTimes())
		{
			// all times are in the past
			$this->setError(JText::translate('VAPFINDRESNOLONGERAVAILABLE'));

			return false;
		}

		return $timeline;
	}

	/**
	 * Helper method used to elaborate the resulting timeline.
	 *
	 * @param 	string 	 $date      The UTC date in military format.
	 * @param 	integer  $people    The number of participants.
	 * @param 	array    $worktime  A list of working times.
	 * @param 	array    $bookings  A list of booked appointments.
	 * @param 	object   $service   The service details.
	 *
	 * @return 	array    The resulting timeline.
	 */
	protected function elaborateTimeLine($date, $people, $worktime, $bookings, $service)
	{
		$arr = array();

		if ($service->interval == 1)
		{
			// same as service duration
			$min_int = 5;
		}
		else
		{
			// default one specified by the configuration
			$min_int = VAPFactory::getConfig()->getUint('minuteintervals');
		}
		
		for ($i = 0; $i < count($worktime); $i++)
		{
			for ($j = $worktime[$i]->fromts; ($j + $min_int) <= $worktime[$i]->endts; $j += $min_int)
			{
				$arr[$i][$j] = 1;
			}
		}

		// fetch default system timezone
		$tz = JFactory::getApplication()->get('offset', 'UTC');

		// re-format check-in date for correct comparison
		$ymd = JDate::getInstance($date)->format('Y-m-d');
		
		foreach ($bookings as $b)
		{
			if (!$b->timezone)
			{
				// use default system timezone
				$b->timezone = $tz;
			}

			// create check-in and adjust it to the employee timezone
			$checkin = new JDate($b->checkin_ts);
			$checkin->setTimezone(new DateTimeZone($b->timezone));

			// get check-in time adjusted to local timezone
			$from = $checkin->format('H:i', $local = true);
			// convert time string in minutes
			$from = JHtml::fetch('vikappointments.time2min', $from);

			/**
			 * Check the day factor of a reservation to check if it is referring
			 * to the current day or if it close to the bounds of this working time.
			 * Used to support midnight reservations.
			 *
			 * @since 1.6
			 */

			if ($checkin->format('Y-m-d', $local = true) > $ymd)
			{
				// we are evaluating a reservation for the next day, so we need to increase the 
				// initial time by 1440 minutes (24 hours * 60).
				$from += 1440;
			}
			else if ($checkin->format('Y-m-d', $local = true) < $ymd)
			{
				// we are evaluating a reservation for the previous day, so we need to decrease the 
				// initial time by 1440 minutes (24 hours * 60).
				$from -= 1440;
			}

			// calculate end time by adding total duration to start time
			$to = $from + $b->duration + $b->sleep;

			for ($i = $from; $i < $to; $i += $min_int)
			{
				$found = false;
				for ($j = 0; $j < count($arr) && !$found; $j++)
				{
					if (!empty($arr[$j][$i]))
					{
						$found = true;
						$arr[$j][$i] = 0;
					}
				}
			}
		}
		
		if ($service->interval != 1)
		{
			$n_step = $service->duration + $service->sleep;
			
			for ($i = 0; $i < count($arr); $i++)
			{
				$step = 0;
				for ($j = $worktime[$i]->fromts; ($j + $min_int) <= $worktime[$i]->endts; $j += $min_int)
				{
					if ($arr[$i][$j] == 1)
					{
						$step += $min_int;
						if ($step >= $n_step)
						{
							$step-=$min_int;
						}
					}
					else
					{
						if ($step != 0 && $step < $n_step)
						{
							for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
							{
								$arr[$i][$back] = 2;
							}
						}
						
						$step = 0;
					}
				}
				
				if ($step != 0 && $step < $n_step)
				{
					for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
					{
						$arr[$i][$back] = 2;
					}
				}
			}
		}

		$mod = round(($service->duration + $service->sleep) / $min_int);

		if ($service->interval == 1 && $mod != 1)
		{
			$new_arr = array();
			
			for ($i = 0; $i < count($arr); $i++)
			{
				$new_arr[$i] = array();
				$value = 1;
				$start = 0;
				$all_free = true;
				
				$count = 0;
				
				for ($j = $worktime[$i]->fromts; $j < $worktime[$i]->endts; $j += $min_int, $count++)
				{
					if ($count % $mod == 0)
					{
						$start = $j;
						$value = 1;
						$all_free = true;
					}
					
					$hourmin = intval($j / 60) . ' : ' . ($j % 60);
					if ($arr[$i][$j] == 0)
					{
						$all_free = false;
					}

					$value &= ($arr[$i][$j] == 2 ? 0 : $arr[$i][$j]); 
					
					if ((($count + 1) % $mod == 0 || $j + $min_int == $worktime[$i]->endts))
					{
						// LAST TIME SLOTS is not enough length
						if (($count+1) % $mod != 0)
						{
							$value = 0;
						}
						
						if ($value == 0 && $all_free)
						{
							$value = 2;
						}

						$new_arr[$i][$start] = $value;
					}
				}
			}
			
			$arr = $new_arr;
		}
		
		return $arr;
	}
}

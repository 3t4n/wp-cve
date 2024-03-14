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
 * Group timeline parser.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineParserGroup extends VAPAvailabilityTimelineParser
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
			$this->setError(JText::translate('VAPFINDRESNODAYEMPLOYEE'));

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

		// register seats trace
		$seats = array();

		// use helper method to elaborate the timeline
		$arr = $this->elaborateTimeLine($date, $people, $worktimes, $bookings, $service, $seats);

		// create timeline
		$timeline = new VAPAvailabilityTimeline($date, $this->search);

		// create new level
		$timeline->addLevel();

		foreach ($arr as $k => $v)
		{
			// create check-in date time
			$checkin = new JDate($date);
			$checkin->modify(JHtml::fetch('vikappointments.min2time', $k, $string = true, $format = 'H:i:s'));

			// check whether the time is in the past (preserve them in case of admin)
			if ($arr[$k] && $this->search->isPastTime($checkin))
			{
				// unset block if it is in the past
				unset($arr[$k]);
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
					unset($arr[$k]);
				}
			}

			if (isset($arr[$k]))
			{
				// add time
				$timeBlock = $timeline->addTime($k, $arr[$k], $service->duration);

				$trace = array();

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

				// set occupancy
				$timeBlock->setOccupancy(isset($seats[$k]) ? $service->max_capacity - $seats[$k] : 0, $service->max_capacity);
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
	 * @param 	mixed    &$seats    An array containing the remaining seats for each time.
	 *
	 * @return 	array    The resulting timeline.
	 */
	protected function elaborateTimeLine($date, $people, $worktime, $bookings, $service, &$seats = null)
	{
		$arr = array();

		if ($service->interval == 1)
		{
			// same as service duration
			$min_int = $service->duration + $service->sleep;
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

		$cont_people = 0;
		
		foreach ($bookings as $k => $b)
		{
			if (!$b->timezone)
			{
				// use default system timezone
				$b->timezone = $tz;
			}
			
			// increase people count
			$cont_people += $b->people_count;

			if ($k == count($bookings) - 1 || $bookings[$k + 1]->checkin_ts != $b->checkin_ts || $bookings[$k + 1]->timezone != $b->timezone)
			{
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
						/**
						 * Try to block appointments that come from a different service or
						 * if the number of people exceeds the total capacity.
						 *
						 * @since 1.6 	check if the services are different only if $arr[$j][$i] is set
						 */
						if (!empty($arr[$j][$i]) && ($cont_people + $people > $service->max_capacity || $b->id_service != $service->id || $b->closure))
						{
							$found = true;
							$arr[$j][$i] = 0;
						}

						/**
						 * If $seats argument is an array, push the remaining seats.
						 * 
						 * @since 1.6
						 */
						if (is_array($seats))
						{
							if ($b->id_service == $service->id && !$b->closure)
							{
								// same service, we can display the remaining seats
								$seats[$i] = $service->max_capacity - $cont_people;
							}
							else
							{
								// booked for a different service, unset the remaining seats
								$seats[$i] = 0;
							}
						}
					}

					/**
					 * We may have different services that display shifted
					 * timelines. This would cause an issue as previous check
					 * ignores the times that don't match the evaluated slots.
					 *
					 * We need to unset here all the times that intersect with
					 * an existing reservation, which might have been created for
					 * a different service.
					 *
					 * @since 1.6.2
					 */
					if (!$found)
					{
						// find all slots that intersect this one
						for ($j = 0; $j < count($arr); $j++)
						{
							foreach ($arr[$j] as $arr_hm => &$v)
							{
								if (($from < $arr_hm && $arr_hm < $to)
									|| ($arr_hm < $from && $from < $arr_hm + $service->duration + $service->sleep))
								{
									/**
									 * @todo evaluate to enhance this statement, because in case
									 *       an appointment uses an increased duration, the exceeding
									 *       slot are entirely turned off
									 */
									$v = 0;
								}
							}
						}
					}
				}
				
				$cont_people = 0;
			}
		}
		
		$n_step = $service->duration + $service->sleep;

		// array deep : elaborate each timeline 
		for ($level = 0; $level < count($arr); $level++)
		{
			// get all the times in the current timeline
			$keys = array_keys($arr[$level]);
			// insert the end working time to evaluate properly the last available time
			$keys[] = $worktime[$level]->endts;

			for ($i = 0; $i < count($keys) - 1; $i++)
			{
				$last_index = -1;

				for ($j = $i + 1; $j < count($keys) && $last_index == -1; $j++)
				{
					/**
					 * If index is last or if current time is not available.
					 *
					 * @since 1.6 	Use empty($arr[$level][$keys[$j]]) to avoid "Undefined Index" notices.
					 * 				These notices may be raised when the reservations were stored for certain
					 * 				times that don't exist anymore.
					 */
					// if ($keys[$j] == count($keys) -1 || $arr[$level][$keys[$j]] == 0)
					if ($keys[$j] == count($keys) -1 || empty($arr[$level][$keys[$j]]))
					{
						// store last index found and stop for statement
						$last_index = $j;
					}
				}

				// if subtraction of last index found with current index is not enough
				if ($keys[$last_index] - $keys[$i] < $n_step)
				{
					// if current time is still available
					if ($arr[$level][$keys[$i]] == 1)
					{
						// mark current time as no more available
						$arr[$level][$keys[$i]] = 2;
					}
				}
			}
		}
		
		$timeline = array();

		foreach ($arr as $a)
		{
			foreach ($a as $hour => $val)
			{
				$timeline[$hour] = $val;
			}
		}
		
		return $timeline;
	}
}

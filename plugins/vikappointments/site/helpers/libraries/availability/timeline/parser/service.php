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
 * Service timeline parser (missing employee selection).
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineParserService extends VAPAvailabilityTimelineParser
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
		$id_service = (int) $this->search->get('id_service');

		// get service details
		$service = JModelVAP::getInstance('serempassoc')->getOverrides($id_service);

		// load all supported employees
		$employees = JModelVAP::getInstance('service')->getEmployees($id_service);

		$timelines = array();

		// iterate employees
		foreach ($employees as $employee)
		{
			// temporarily set the employee
			$this->search->set('id_employee', (int) $employee->id);

			// get proper parser with updated searcher
			$parser = VAPAvailabilityTimelineFactory::getParser($this->search);

			// obtain timeline from parent
			$tmp = $parser->getTimeline($date, $people, $id);

			if ($tmp)
			{
				// register timeline only if existing
				$timelines[] = $tmp;
			}
		}

		// reset employee from search
		$this->search->set('id_employee', 0);

		// merge all the timelines found into a single one
		$arr = $this->mergeTimelines($timelines);

		// create timeline
		$timeline = new VAPAvailabilityTimeline($date, $this->search);

		foreach ($arr as $k => $block)
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
				$trace = array();

				// add time
				$timeBlock = $timeline->addTime($k, $arr[$k]['status'], $service->duration);

				// calculate rate for the current time block
				$price = VAPSpecialRates::getRate($id_service, 0, $timeBlock->checkin(), $people, $trace);

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

				// set overall occupancy
				$timeBlock->setOccupancy(array($block['occupancy'], $block['capacity']));
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
	 * Parses the timelines in the array to fetch a single timeline.
	 *
	 * @param 	array  $timelines  The fetched timelines.
	 *
	 * @return 	array  The resulting timeline.
	 */
	protected function mergeTimelines($timelines)
	{	
		// convert timelines into array for a better ease of use
		foreach ($timelines as $i => $timeline)
		{
			// convert the timeline into an array by keeping all the time details
			$timelines[$i] = $timeline->toArray($flatten = true, $statusOnly = false);
		}

		$arr = array();
		
		foreach ($timelines as $timeline)
		{
			foreach ($timeline as $time => $block)
			{
				// get time slot status
				$res = $block['status'];

				$is_pending = false;
				
				for ($i = 0; $i < count($timelines) && $res != 1; $i++) 
				{
					$res = (!empty($timelines[$i][$time])) ? $timelines[$i][$time]['status'] : 0;

					if ($res == 2)
					{
						$is_pending = true;
					}
				}
				
				if ($res == 0 && $is_pending)
				{
					$res = 2;
				}

				// refresh status
				$block['status'] = $res;
				
				if (!isset($arr[$time]))
				{
					$arr[$time] = $block;
				}
				else
				{
					/**
					 * The timeline now sums the total occupancy and capacity of
					 * 2+ matching time slots.
					 *
					 * NOTE: it is definitely incorrect to have a services able
					 * to host multiple guests that it is also assigned to multiple
					 * employees (with selection turned off). This because, even if
					 * the total capacity is correct, the available times might be
					 * split. Lets take as example 2 employees with capacity 4 and
					 * both them own a reservation for 2 guests. The real remaining
					 * availability should be 2 for the first service, 2 for the
					 * second one. However, with the summed capacity, the resulting
					 * availability will be equals to 4.
					 * 
					 * @since 1.7
					 */
					$arr[$time]['occupancy'] += $block['occupancy'];
					$arr[$time]['capacity']  += $block['capacity'];
				}
			}
		}

		/**
		 * Sort employees working days to be listed in ascending ordering.
		 *
		 * @since 1.6.1
		 */
		ksort($arr);
		
		return $arr;
	}
}

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

/**
 * VikAppointments make recurrence model.
 *
 * @since 1.7
 */
class VikAppointmentsModelMakerecurrence extends JModelVAP
{
	/**
	 * Returns an array containing all the (probable) appointments
	 * that match the specified recurrence instructions.
	 *
	 * @param 	string  $datetime    A date time string in military format.
	 * @param 	array   $recurrence  The recurrence instructions.
	 *
	 * @return 	array   The resulting recurrence array.
	 */
	public function getRecurrence($datetime, $recurrence)
	{
		if ($datetime instanceof JDate)
		{
			$start = $datetime;
		}
		else
		{
			// create initial date
			$start = new JDate($datetime);
		}

		// create ending date
		$end = clone $start;

		if ($recurrence['for'] == 1)
		{
			// +[N] days
			$end->modify('+' . abs($recurrence['amount']) . ' days');
		}
		else if ($recurrence['for'] == 2)
		{
			// +[N] weeks
			$end->modify('+' . abs($recurrence['amount']) . ' weeks');
		}
		else if ($recurrence['for'] == 3)
		{
			// +[N] months
			$end->modify('+' . abs($recurrence['amount']) . ' months');
		}

		// fetch modifier role
		if ($recurrence['by'] == 1)
		{
			$modifier = '+1 day';
		}
		else if ($recurrence['by'] == 2)
		{
			$modifier = '+1 week';
		}
		else if ($recurrence['by'] == 3)
		{
			$modifier = '+1 month';
		}
		else if ($recurrence['by'] == 4)
		{
			$modifier = '+2 weeks';
		}
		else if ($recurrence['by'] == 5)
		{
			$modifier = '+2 months';
		}

		$result = array();
		
		// iterate until the start date exceeds the end date
		while ($start < $end)
		{
			// update date
			$start->modify($modifier);

			// register new date
			$result[] = $start->format('Y-m-d H:i:s');
		}

		return $result;
	}

	/**
	 * Checks the availability for the specified appointment, but with the new
	 * date for recurrence.
	 *
	 * @param 	mixed    $appointment  The appointment details.
	 * @param 	string   $date         A date string in military format.
	 * @param 	array    $force        An associative array used to overwrite the
	 *                                 default details of the appointment.
	 *
	 * @return 	boolean  True if available, false otherwise.
	 */
	public function checkAvailability($appointment, $date, $force = array())
	{
		// get reservation model
		$reservation = JModelVAP::getInstance('reservation');

		// prepare search data
		if (is_object($appointment))
		{
			$data = array(
				'id_service'  => $appointment->service->id,
				'id_employee' => $appointment->employee->id,
				'duration'    => $appointment->duration,
				'sleep'       => $appointment->sleep,
				'people'      => $appointment->people,
				'checkin_ts'  => $date,
			);
		}
		else
		{
			// use array data as provided
			$data = (array) $appointment;
		}

		// inject force data to override the appointment details
		$data = array_merge($data, $force);

		// check availability
		if (!$reservation->isAvailable($data))
		{
			// propagate error message
			$this->setError($reservation->getError());

			return false;
		}

		// seems available
		return true;
	}

	/**
	 * Checks the availability for the specified appointment, but with the new date
	 * for recurrence. Any available employee will be accepted here.
	 *
	 * @param 	mixed    $appointment  The appointment details.
	 * @param 	string   $date         A date string in military format.
	 * @param 	boolean  $exclude      True to exclude the validation for the employee
	 *                                 already assigned to the appointment. Useful in
	 *                                 case a first validation has been already made.
	 *
	 * @return 	array    An associative array containing all the available employees.
	 */
	public function checkRandomAvailability($appointment, $date, $exclude = true)
	{
		if (is_object($appointment))
		{
			// extract service and employee IDs from object
			$id_service  = $appointment->service->id;
			$id_employee = $appointment->employee->id;
		}
		else
		{
			// extract service and employee IDs from array
			$id_service  = $appointment['id_service'];
			$id_employee = $appointment['id_employee'];
		}

		// get service model
		$service = JModelVAP::getInstance('service');
		// get assigned employees
		$employees = $service->getEmployees($id_service);

		$results = array();

		foreach ($employees as $emp)
		{
			if ($exclude && $emp->id == $id_employee)
			{
				// exclude the validation for the employee currently
				// assigned to the appointment
				continue;
			}

			// check availability for this new employee
			if ($this->checkAvailability($appointment, $date, array('id_employee' => $emp->id)))
			{
				// register employee as available
				$results[$emp->id] = $emp->nickname;
			}
		}

		return $results;
	}

	/**
	 * Checks the availability for the specified appointment, but with the new date
	 * for recurrence. The system will search for any other available time slot for
	 * the current date and any other nearby date.
	 *
	 * @param 	mixed    $appointment  The appointment details.
	 * @param 	string   $date         A date string in military format.
	 * @param 	integer  $next         Indicates the maximum number of days after the
	 *                                 default check-in for which we should go ahead.
	 * @param 	integer  $prev         Indicates the maximum number of days before the
	 *                                 default check-in for which we should go behind.
	 *
	 * @return 	array    An associative array containing all the available times.
	 */
	public function checkNearbyAvailability($appointment, $date, $next = 1, $prev = 1)
	{
		// get reservation model
		$reservation = JModelVAP::getInstance('reservation');

		// create initial range date
		$start = new JDate($date);
		$start->modify('-' . abs($prev) . ' days');

		// create ending range date
		$end = new JDate($date);
		$end->modify('+' . abs($next) . ' days');

		$config = VAPFactory::getConfig();

		$results = array();

		// repeat until the initial date exceeds the ending date
		while ($start <= $end)
		{
			// prepare search data
			if (is_object($appointment))
			{
				$data = array(
					'id_service'  => $appointment->service->id,
					'id_employee' => $appointment->employee->id,
					'duration'    => $appointment->duration,
					'sleep'       => $appointment->sleep,
					'people'      => $appointment->people,
					'checkin_ts'  => $start->format('Y-m-d H:i:s'),
				);
			}
			else
			{
				// use array data as provided
				$data = (array) $appointment;
			}

			// get available timeline for the current date
			$timeline = $reservation->getAvailableTimes($data);

			if ($timeline)
			{
				foreach ($timeline as $level)
				{
					foreach ($level as $time)
					{
						if ($time->isAvailable())
						{
							// military date as key
							$k = $time->checkin('Y-m-d H:i:s', 'UTC');
							// formatted date as value
							$v = $time->checkin($config->get('dateformat') . ' ' . $config->get('timeformat'));

							// register time within the list
							$results[$k] = $v;
						}
					}
				}
			}

			// go to next day
			$start->modify('+1 day');
		}

		return $results;
	}

	/**
	 * Creates a recurrence for the given appointment according to the specified
	 * recurrence rules and specified hints.
	 *
	 * @param 	string   $datetime    A date time string in military format.
	 * @param 	array    $recurrence  The recurrence instructions.
	 * @param 	array    $hints       An associative array of hints that aim to fix
	 *                                the availability of those appointments that could
	 *                                not be booked with the default calculated check-in.
	 *
	 * @return 	integer  The number of created appointments.
	 */
	public function createRecurrence($appointment, array $recurrence, array $hints = array())
	{
		// create date by using the local timezone of the employee, because the DST
		// might change over time
		$tz = new DateTimeZone(JModelVAP::getInstance('employee')->getTimezone($appointment->employee->id));
		$empDate = new JDate($appointment->checkin->utc);
		$empDate->setTimezone($tz);

		// compose dates recurrence
		$arr = $this->getRecurrence($empDate, $recurrence);
		
		if (!$arr)
		{
			// invalid recurrence
			$this->setError(JText::translate('VAPMAKERECNOROWS'));

			return 0;
		}

		// get reservation model
		$reservation = JModelVAP::getInstance('reservation');

		$count = 0;

		// iterate all dates found
		foreach ($arr as $date)
		{
			$src = array();
			
			// use default date
			$src['checkin_ts'] = $date;

			// check if we have a hint for this date
			if (isset($hints[$date]))
			{
				if (is_numeric($hints[$date]))
				{
					// numeric hint, a different employee was specified
					$src['id_employee'] = $hints[$date];
				}
				else
				{
					// different check-in was specified
					$src['checkin_ts'] = $hints[$date];
				}
			}

			// check availability of the new appointment
			$avail = $this->checkAvailability($appointment, $date, $src);

			if (!$avail)
			{
				// still not available, skip date
				continue;
			}

			// register a status to understand that the appointments has been created with recurrence
			$src['status_comment'] = JText::sprintf('VAP_STATUS_CREATED_RECURRENCE', $appointment->id);

			// duplicate appointment with new data
			if ($reservation->duplicate(array($appointment->id), $src))
			{
				$count++;
			}
		}

		return $count;
	}
}

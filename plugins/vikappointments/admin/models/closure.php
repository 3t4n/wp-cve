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
 * VikAppointments closure model.
 *
 * @since 1.7
 */
class VikAppointmentsModelClosure extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		if (!empty($data['fromdate']))
		{
			if (!is_numeric($data['fromtime']))
			{
				// convert time in minutes for later use
				$data['fromtime'] = JHtml::fetch('vikappointments.time2min', $data['fromtime']);
			}

			// convert minutes in time to properly create a date object
			$hms = JHtml::fetch('vikappointments.min2time', $data['fromtime'], $string = true, $format = 'H:i:s');
			// create date object coming from the user timezone
			$checkin = new JDate($data['fromdate'] . ' ' . $hms, JFactory::getUser()->getTimezone());

			// register check-in date time
			$data['checkin_ts'] = $checkin->toSql();

			if (!is_numeric($data['totime']))
			{
				// convert time in minutes for later use
				$data['totime'] = JHtml::fetch('vikappointments.time2min', $data['totime']);
			}

			// init check-out by cloning the check-in object
			$checkout = clone $checkin;

			// convert minutes in time to properly create a date object
			$hms = JHtml::fetch('vikappointments.min2time', $data['totime'], $string = true, $format = 'H:i:s');

			if ($data['totime'] <= $data['fromtime'])
			{
				// go to next day
				$checkout->modify('+1 day');
			}

			// set end time
			$checkout->modify($hms);

			// get interval of time between the check-out and the check-in
			$interval = $checkout->diff($checkin);
			
			// since we can have at most 24 hours of interval, we can calculate
			// the duration by summing the resulting days, hours and minutes
			$data['duration'] = $interval->days * 1440 + $interval->h * 60 + $interval->i;
		}

		// always flag as closure
		$data['closure'] = 1;

		/**
		 * Always flag the closure as CONFIRMED, otherwise the system might change the default
		 * PENDING status to REMOVED after the time defined by "Keep App. Locked for" has passed.
		 * 
		 * @since 1.7.4
		 */
		$data['status'] = JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code', $strict = false);

		$id = 0;

		if (!empty($data['id_employees']))
		{
			foreach ($data['id_employees'] as $id_emp)
			{
				// reset ID and set current employee
				$data['id']          = 0;
				$data['id_employee'] = (int) $id_emp;

				// attempt to save the closure
				$id = parent::save($data);
			}
		}
		else
		{
			// attempt to save the closure as it is
			$id = parent::save($data);
		}
		
		return $id;
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = '', $prefix = '', $options = array())
	{
		if (!$name)
		{
			// force reservation table
			$name = 'reservation';
		}

		if (!$prefix)
		{
			// use default system prefix
			$prefix = 'VAPTable';
		}

		// invoke parent
		return parent::getTable($name, $prefix, $options);
	}
}

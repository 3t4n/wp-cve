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

/**
 * Availability search abstract interface.
 *
 * @since 1.7
 */
abstract class VAPAvailabilitySearch extends JObject
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $id_ser   The service ID.
	 * @param 	integer  $id_emp   The employee ID (optional).
	 * @param 	array    $options  An array of options.
	 */
	public function __construct($id_ser, $id_emp = null, array $options = array())
	{
		// construct with given options
		parent::__construct($options);

		// manually set the specified service and employee
		$this->set('id_service',  (int) $id_ser);
		$this->set('id_employee', (int) $id_emp);
	}

	/**
	 * Checks whether the availability is fetched by an administrator.
	 *
	 * @return  boolean  True in case of admin, false otherwise.
	 */
	public function isAdmin()
	{
		return (bool) $this->get('admin', false);
	}

	/**
	 * Checks whether there's at least an employee offering the
	 * requested service for the given day. In case an employee
	 * has been specified while constructing this object, then
	 * the availability will be restricted to the latter.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if open, false otherwise.
	 */
	public function isDayOpen($date)
	{
		// look for a closing day/period
		if ($this->isClosingDay($date))
		{
			// the day is closed
			return false;
		}

		// check whether the service is published for the given date
		if (!$this->isServicePublished($date))
		{
			// service not published
			return false;
		}

		// check whether there's at least a working day
		return $this->hasWorkingDay($date);
	}

	/**
	 * Approximative check to determine whether the specified day
	 * is available or not by returning the related availability
	 * status (0: full, 1: fully available, 2: partially available).
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	integer  The availability status.
	 */
	abstract public function isDayAvailable($date);

	/**
	 * Returns the employee working times for the given day.
	 * In case of 24h working days, the system will extend the ending
	 * time of the last working day in order to support midnight appointments.
	 *
	 * @param 	string 	$date  The UTC date in military format.
	 *
	 * @return 	array   A list containing the matching working days.
	 */
	abstract public function getWorkingTimes($date);

	/**
	 * Returns a list of appointments that stays between 2 dates.
	 * This method returns only the appointments that might alter
	 * the availability of the registered service/employee.
	 *
	 * @param 	string 	 $date  The UTC start date in military format.
	 * @param 	mixed 	 $end   The UTC end date in military format. Leave empty to
	 *                          auto-set the end date at midnight of start date.
	 * @param 	integer  $id    The selected appointment ID, which will be excluded.
	 *
	 * @return 	array 	 A list of appointments.
	 */
	abstract public function getReservations($date, $end = null, $id = 0);

	/**
	 * Checks whether there's at least an open working time on the given
	 * day for the specified service-employee relation.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if available, false if missing or closed.
	 */
	abstract public function hasWorkingDay($date);

	/**
	 * Checks whether the specified employee is able to host an appointment at
	 * the specified date and for the given duration.
	 *
	 * This method should simply check the intersection between this search and
	 * the existing appointments. It is assumed that the seleceted check-in is
	 * already supported by the employee.
	 *
	 * @param 	string 	 $date      The UTC start date in military format.
	 * @param 	mixed 	 $duration  The appointment duration.
	 * @param 	integer  $people    The number of participants.
	 * @param 	integer  $id        The selected appointment ID, which will be excluded.
	 *
	 * @return 	boolean  True if available, false otherwise.
	 */
	abstract public function isEmployeeAvailable($date, $duration = null, $people = 1, $id = 0);

	/**
	 * Checks whether the specified service is able to host an appointment at
	 * the specified date and for the given duration.
	 *
	 * This method should simply check the intersection between this search and
	 * the existing appointments. The system will iterate all the employees
	 * assigned to the selected service to find the first one available.
	 *
	 * @param 	string 	 $date      The UTC start date in military format.
	 * @param 	mixed 	 $duration  The appointment duration.
	 * @param 	integer  $people    The number of participants.
	 * @param 	integer  $id        The selected appointment ID, which will be excluded.
	 *
	 * @return 	mixed    The ID of the available employee, false otherwise.
	 */
	abstract public function isServiceAvailable($date, $duration = null, $people = 1, $id = 0);

	/**
	 * Checks whether there's a closing day/period on the given
	 * day and for the specified service.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if closed, false otherwise.
	 */
	abstract public function isClosingDay($date);

	/**
	 * Checks whether the service is published on the given date.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if closed, false otherwise.
	 */
	abstract public function isServicePublished($date);

	/**
	 * Checks if the specified date is in the past or doesn't follow the
	 * booking minutes restriction of the service.
	 *
	 * @param 	string   $datetime  The check-in date time (military format).
	 *
	 * @return 	boolean  True if in the past, false otherwise.
	 */
	abstract public function isPastTime($datetime);
}

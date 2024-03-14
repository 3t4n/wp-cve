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

VAPLoader::import('libraries.availability.search');

/**
 * Class manager of the availability search.
 *
 * @since 1.7
 */
class VAPAvailabilityImplementor extends VAPAvailabilitySearch
{
	/**
	 * Approximative check to determine whether the specified day
	 * is available or not by returning the related availability
	 * status (0: full, 1: fully available, 2: partially available).
	 * 
	 * @todo Introduce some hooks here to allow the plugins to override the
	 *       availability status of the days. There should be a "before" event
	 *       to prevent useless queries and an "after" event to apply additional
	 *       queries after the default ones. Maybe it could be helpful to run
	 *       the same hooks also while checking the availability of a single
	 *       employee through the `_isDayAvailable` method.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	integer  The availability status.
	 */
	public function isDayAvailable($date)
	{
		if ((int) $this->get('id_employee'))
		{
			// directly use helper method to fetch the
			// availability of the selected employee
			return $this->_isDayAvailable($date);
		}

		$model = JModelVAP::getInstance('service');

		// We do not have a specific employee, so we need to retrieve all the employees
		// assigned to this service. Always take any assigned employee.
		$employees = $model->getEmployees((int) $this->get('id_service'), $strict = false);

		if (!$employees)
		{
			// no employees found...
			return 0;
		}

		$list = array();

		// fetch the employee availability one by one
		foreach ($employees as $employee)
		{
			// temporarily inject employee ID
			$this->set('id_employee', (int) $employee->id);
			// fetch availability and register status
			$list[] = $this->_isDayAvailable($date);
		}

		// unset employee ID
		$this->set('id_employee', 0);

		// look for a fully available status first
		if (in_array(1, $list))
		{
			return 1;
		}

		// then look for a partially available status
		if (in_array(2, $list))
		{
			return 2;
		}

		// not available
		return 0;
	}

	/**
	 * Helper used to approximatively determine whether the specified day
	 * is available or not by returning the related availability status.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	integer  The availability status.
	 */
	protected function _isDayAvailable($date)
	{
		// get working times
		$worktime = $this->getWorkingTimes($date);

		if (!$worktime)
		{
			// not available on this date
			return 0;
		}

		$tmp = array();

		foreach ($worktime as $wt)
		{
			// get a clone of the working times to allow their
			// manipulation without compromising the cache
			$tmp[] = clone $wt;
		}

		$worktime = $tmp;

		// get reservations
		$reservations = $this->getReservations($date);

		if (!$reservations)
		{
			// fully available, no reservations on this date yet
			return 1;
		}

		$id_service  = (int) $this->get('id_service');
		$id_employee = (int) $this->get('id_employee');

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// get service-employee overrides
		$override = $model->getOverrides($id_service, $id_employee);

		if (!$override)
		{
			// invalid relation
			throw new Exception('Service-employee relation not found.', 404);
		}

		// fetch default system timezone
		$tz = JFactory::getApplication()->get('offset', 'UTC');

		$max_capacity = 1;

		// use service maximum capacity in case it is able to host
		// multiple appointments at the same time slot
		if ($override->app_per_slot)
		{
			$max_capacity = $override->max_capacity;
		}
		
		foreach ($reservations as $r)
		{
			if (!$r->timezone)
			{
				// use default system timezone
				$r->timezone = $tz;
			}

			// create check-in and adjust it to the employee timezone
			$checkin = new JDate($r->checkin_ts);
			$checkin->setTimezone(new DateTimeZone($r->timezone));

			// get check-in time adjusted to local timezone
			$from = $checkin->format('H:i', $local = true);
			// convert time string in minutes
			$from = JHtml::fetch('vikappointments.time2min', $from);

			// calculate end time by adding total duration to start time
			$to = $from + $r->duration + $r->sleep;

			$idx = 0;
			
			/**
			 * Go ahead in the loop also in case the checkin time of the appointments
			 * is equals to the end time of the working shift.
			 *
			 * For example, in case the checkin starts at 9:00 and we have a working
			 * shift ending at the same time, we should go ahead as it is not the
			 * record we need.
			 *
			 * This is a common issue that may occur in case of contiguous shifts,
			 * such as 14:00 - 15:00 and 15:00 - 16:00.
			 *
			 * @since 1.6.4
			 */
			for ($idx; $idx < count($worktime) && ($worktime[$idx]->fromts > $from || $from >= $worktime[$idx]->endts); $idx++);

			// make sure we found a matching working time			
			if ($idx < count($worktime))
			{
				if (!isset($worktime[$idx]->_counter))
				{
					// create counter
					$worktime[$idx]->_counter = 0;
				}

				/**
				 * The number of people cannot exceed the maximum capacity of the service,
				 * so that appointments booked for other services with higher capacity
				 * won't fetch a wrong availability.
				 *
				 * @since 1.7
				 */
				$people = min(array($r->people_count, $max_capacity));

				/**
				 * In case the booked appointment doesn't match the ID of the current service,
				 * use the service capacity to automatically turn off the whole slot(s).
				 *
				 * @since 1.7
				 */
				if ($id_service != $r->id_service)
				{
					$people = $max_capacity;
				}

				/**
				 * Multiply the occupied time slots by the selected number of people.
				 *
				 * @since 1.6.5
				 */
				$worktime[$idx]->_counter += ($to - $from) * $people;
			}
		}
		
		foreach ($worktime as $w)
		{
			/**
			 * Check whether the total duration of the working time still own some space.
			 * Multiply the whole shift by the maximum capacity of the service.
			 *
			 * @since 1.6.5
			 * 
			 * In case the current working time ends at midnight and the working time for the
			 * next day starts at midnight, they are merged together for a correct availability check.
			 * In this case, the total length of the working shift might exceed the limit of 24 hours.
			 * For this reason, we should always take shifts that do not last more than 1440 minutes.
			 * 
			 * @since 1.7.4
			 */
			if (empty($w->_counter) || $w->_counter < (min(1440, $w->endts - $w->fromts)) * $max_capacity)
			{
				// seems to be (partially) available
				return 2;
			}
		}
		
		// all working times are full	
		return 0;
	}

	/**
	 * Returns the employee working times for the given day.
	 * In case of 24h working days, the system will extend the ending
	 * time of the last working day in order to support midnight appointments.
	 *
	 * @param 	string 	$date  The UTC date in military format.
	 *
	 * @return 	array   A list containing the matching working days.
	 */
	public function getWorkingTimes($date)
	{
		// get working times for the given day
		$worktimes = $this->_getWorkingTimes($date);

		$date = JDate::getInstance($date);

		// update specified day by one
		$date->modify('+1 day 00:00:00');

		// fallback to obtain the working times for the next day
		$next = $this->_getWorkingTimes($date->format('Y-m-d'));

		if ($worktimes && $next && $next[0]->fromts == 0)
		{
			// We have probably a 24H working time.
			// Extend the last working time with the first
			// one of the next day
			$last = &$worktimes[count($worktimes) - 1];

			$last->endts += $next[0]->endts;
		}

		return $worktimes;
	}

	/**
	 * Returns the employee working times for the given day.
	 *
	 * @param 	string 	$date  The UTC date in military format.
	 *
	 * @return 	array   A list containing the matching working days.
	 */
	protected function _getWorkingTimes($date)
	{
		// cache working days grouped by service/employee relation
		static $worktimes = array();

		// create cache signature
		$sign = serialize(array((int) $this->get('id_service'), (int) $this->get('id_employee')));

		// load the all the working days available for the specified employee/service
		// and cache them within an internal property to avoid several accesses to
		// the database while loading the calendar availability
		if (!isset($worktimes[$sign]))
		{
			$worktimes = array();

			$dbo = JFactory::getDbo();
			
			// obtain all the working days for the given employee/service
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . (int) $this->get('id_employee'),
					$dbo->qn('id_service') . ' = ' . (int) $this->get('id_service'),
				))
				->order(array(
					$dbo->qn('closed') . ' DESC',
					$dbo->qn('ts') . ' DESC',
					$dbo->qn('fromts') . ' ASC',
				));

			// look for any specified locations
			$locations = (array) $this->get('locations', array());

			if (count($locations))
			{
				// filter by location
				$q->andWhere(array(
					$dbo->qn('id_location') . ' <= 0',
					$dbo->qn('id_location') . ' IN (' . implode(',', array_map('intval', $locations)) . ')',
				), 'OR');
			}
			
			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $wd)
			{
				if (VAPDateHelper::isNull($wd->tsdate))
				{
					// register by day of the week
					$k = (int) $wd->day;
				}
				else
				{
					// register by date
					$k = $wd->tsdate;
				}

				if (!isset($worktimes[$sign][$k]))
				{
					// init pool
					$worktimes[$sign][$k] = array();
				}

				// add working time
				$worktimes[$sign][$k][] = $wd;
			}
		}

		// check whether there is a working time for the specified date
		if (!empty($worktimes[$sign][$date]))
		{
			// make sure the date is not closed
			if ($worktimes[$sign][$date][0]->closed)
			{
				// the employee is closed on this date
				return array();
			}

			// return working times
			return $worktimes[$sign][$date];
		}

		// get day of the week
		$week = (int) JDate::getInstance($date)->format('w');

		// check whether there is a working time for the specified week day
		if (!empty($worktimes[$sign][$week]))
		{
			// make sure the day is not closed
			if ($worktimes[$sign][$week][0]->closed)
			{
				// the employee is closed on this day
				return array();
			}

			// return working times
			return $worktimes[$sign][$week];
		}
		
		// no specified working days
		return array();
	}

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
	public function getReservations($date, $end = null, $id = 0)
	{
		// set initial date time at midnight
		$start = JDate::getInstance($date);
		$start->modify('00:00:00');
		$start = $start->toSql();

		if (!$end)
		{
			// use midnight of current date
			$end = JDate::getInstance($date);
			$end->modify('23:59:59');
			$end = $end->toSql();
		}

		$id_ser = (int) $this->get('id_service', 0);
		$id_emp = (int) $this->get('id_employee', 0);

		/**
		 * Check if the service owns a private calendar, so
		 * that we can exclude all the reservations that belong
		 * to the same employee for different services.
		 *
		 * @since 1.6.5
		 */
		$serModel = JModelVAP::getInstance('service');
		$has_own_cal = $serModel->hasOwnCalendar($id_ser);

		// get all status codes that locks the appointments
		$statuses = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('r.checkin_ts'));
		$q->select($dbo->qn('r.duration'));
		$q->select($dbo->qn('r.sleep'));
		$q->select($dbo->qn('r.id_employee'));
		$q->select($dbo->qn('r.id_service'));
		$q->select($dbo->qn('r.closure'));

		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		// load check-in timezone
		$q->select($dbo->qn('e.timezone'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'));

		// take only the appointments between the specified range
		// $q->where($dbo->qn('r.checkin_ts') . ' >= ' . $dbo->q($start));
		// $q->where($dbo->qn('r.checkin_ts') . ' < ' . $dbo->q($end));

		// filter by check-in date
		$q->where(sprintf(
			'CONVERT_TZ(%s, \'+00:00\', IFNULL(%s, \'%s\')) BETWEEN %s AND %s',
			// take checkin-date time
			$dbo->qn('r.checkin_ts'),
			// adjust it to the related timezone
			$dbo->qn('r.tz_offset'),
			// or use the current one if not specified
			JHtml::fetch('date', 'now', 'P'),
			// set initial delimiter
			$dbo->q($start),
			// set ending delimiter
			$dbo->q($end)
		));

		if ($statuses)
		{
			// take only the reserved appointments
			$q->andWhere([
				$dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $statuses)) . ')',
				$dbo->qn('r.closure') . ' = 1',
			], 'OR');
		}

		$q->order($dbo->qn('r.checkin_ts') . ' ASC');

		if ($id_emp)
		{
			// count the total number of users per time slot
			$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('r.people'), $dbo->qn('people_count')));
			// take only the appointments of the specified employee
			$q->where($dbo->qn('r.id_employee') . ' = ' . $id_emp);
			// group by check-in
			$q->group($dbo->qn('r.checkin_ts'));
		}
		else
		{
			$q->select($dbo->qn('r.people', 'people_count'));
		}

		if ($has_own_cal)
		{
			// take only the reservations for this service
			$q->where($dbo->qn('r.id_service') . ' = ' . $id_ser);
		}
		else if (!$id_emp)
		{
			// take any reservation that belong to any employee assigned to this service
			$q->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_employee') . ' = ' . $dbo->qn('r.id_employee'));
			$q->where($dbo->qn('a.id_service') . ' = ' . $id_ser);
		}

		if ($id)
		{
			$q->where($dbo->qn('r.id') . ' <> ' . (int) $id);
		}

		$dbo->setQuery($q);
		$app = $dbo->loadObjectList();

		if (!$app)
		{
			// no appointments found on the given range
			return array();
		}

		if (!$has_own_cal)
		{
			$lookup = array();

			/**
			 * In case the specified service owns a shared calendar we should
			 * ignore any other appointments with private calendar.
			 *
			 * @since 1.6.5
			 */
			$app = array_values(array_filter($app, function($r) use ($serModel)
			{
				// check whether the service owns a private calendar
				$has_own_cal = $serModel->hasOwnCalendar($r->id_service);

				// accept appointment if belongs to a service with shared calendar
				return $has_own_cal == 0;
			}));
		}
		
		return $app;
	}

	/**
	 * Checks whether there's at least an open working time on the given
	 * day for the specified service-employee relation.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if available, false if missing or closed.
	 */
	public function hasWorkingDay($date)
	{
		$id_service  = (int) $this->get('id_service', 0);
		$id_employee = (int) $this->get('id_employee', 0);

		// create date helper
		$date = new JDate($date);

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('closed'));
		$q->from($dbo->qn('#__vikappointments_emp_worktime'));
		$q->where($dbo->qn('id_service') . ' = ' . $id_service);

		// take working times for the days of the year first
		$q->order($dbo->qn('ts') . ' DESC');

		if ($id_employee)
		{
			// filter by employee ID
			$q->where($dbo->qn('id_employee') . ' = ' . $id_employee);
			// take closing working times first
			$q->order($dbo->qn('closed') . ' DESC');
		}
		else
		{
			$q->select($dbo->qn('id_employee'));
			// take opening working times first
			$q->order($dbo->qn('closed') . ' ASC');
		}

		// filter by date/day
		$q->andWhere(array(
			$dbo->qn('day') . ' = ' . (int) $date->format('w') . ' AND ' . $dbo->qn('ts') . ' <= 0',
			$dbo->qn('tsdate') . ' = ' . $dbo->q($date->toSql()),
		));

		// look for any specified locations
		$locations = (array) $this->get('locations', array());

		if (count($locations))
		{
			// filter by location
			$q->andWhere(array(
				$dbo->qn('closed') . ' = 1',
				$dbo->qn('id_location') . ' <= 0',
				$dbo->qn('id_location') . ' IN (' . implode(',', array_map('intval', $locations)) . ')',
			), 'OR');
		}

		// take only one result in case of a single employee
		$dbo->setQuery($q, 0, $id_employee ? 1 : null);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no working days found
			return false;
		}

		if ($id_employee)
		{
			// make sure the returned value is 0 (= open)
			return $rows[0]->closed == 0;
		}

		$lookup = array();

		// split the working days found and group them under the related employee
		foreach ($rows as $wd)
		{
			if (!isset($lookup[$wd->id_employee]))
			{
				// register only the closure status of the first working day
				// found for each employee, which must be not closed
				$lookup[$wd->id_employee] = $wd->closed;
			}

			// check whether the first working time of the employee
			// is not a closure, meaning that it is open
			if (!$lookup[$wd->id_employee])
			{
				return true;
			}
		}

		// no open employees
		return false;
	}

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
	public function isEmployeeAvailable($date, $duration = null, $people = 1, $id = 0)
	{
		// get service-employee assoc model
		$assocModel = JModelVAP::getInstance('serempassoc');
		// get service details
		$service = $assocModel->getOverrides($this->get('id_service'), $this->get('id_employee'));

		if (!$service)
		{
			// service not found...
			return false;
		}

		if (!$duration)
		{
			// use service duration+sleep time
			$duration = $service->duration + $service->sleep;
		}

		// get employee timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($this->get('id_employee'));

		// create check-in date
		$checkin = new JDate($date);
		$checkin->setTimezone(new DateTimeZone($tz));

		// create check-out date by adding the duration
		$checkout = clone $checkin;
		$checkout->modify('+' . $duration . ' minutes');

		/**
		 * We need to make sure that the selected check-in and check-out are not going
		 * to exceeds the bounds of the employee working times.
		 * 
		 * Without this security check, it would be possible to exceed the bounds by adding
		 * an "extra-time" option to the last available time block.
		 * 
		 * @since 1.7.4
		 */
		$workingShifts = $this->getWorkingTimes($checkin->format('Y-m-d', true));

		$checkinTime  = JHtml::fetch('vikappointments.time2min', $checkin->format('H:i', true));
		$checkoutTime = JHtml::fetch('vikappointments.time2min', $checkout->format('H:i', true));

		$shiftAvailable = false;

		foreach ($workingShifts as $shift)
		{
			// make sure the check-in and check-out are between the shift bounds
			if ($shift->fromts <= $checkinTime && $checkoutTime <= $shift->endts)
			{
				// time ok
				$shiftAvailable = true;
			}
		}

		if (!$shiftAvailable)
		{
			// invalid time, abort
			return false;
		}

		// check if the service owns a private calendar, so
		// that we can exclude all the reservations that belong
		// to the same employee for different services
		$serModel = JModelVAP::getInstance('service');
		$has_own_cal = $serModel->hasOwnCalendar($this->get('id_service'));

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('COUNT(' . $dbo->qn('r.people') . ') AS ' . $dbo->qn('count'));
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		if ($id)
		{
			// exclude selected appointment
			$q->where($dbo->qn('r.id') . ' <> ' . (int) $id);
		}

		// filter reservations by employee
		$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $this->get('id_employee'));

		// get all status codes that locks the appointments
		$statuses = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		if ($statuses)
		{
			// take only the reserved appointments
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $statuses)) . ')');
		}

		if ($has_own_cal)
		{
			// we have a service with private calendatr, so we need to take only the
			// reservations assigned to this service
			$q->where($dbo->qn('r.id_service') . ' = ' . (int) $this->get('id_service'));
		}
		else
		{
			// take any reservation for those services that don't own a private calendar
			$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('r.id_service'));
			$q->where($dbo->qn('s.has_own_cal') . ' = 0');
		}

		// create expression to calculate the check-out date via SQL
		$out = sprintf(
			'DATE_ADD(%s, INTERVAL (%s + %s) MINUTE)',
			$dbo->qn('r.checkin_ts'),
			$dbo->qn('r.duration'),
			$dbo->qn('r.sleep')
		);

		$collision = array();

		// check whether the check-in is contained between the appointment range
		$collision[] = sprintf(
			'%1$s <= %2$s AND %2$s < %3$s',
			$dbo->qn('r.checkin_ts'),
			$dbo->q($checkin->toSql()),
			$out
		);

		// check whether the check-out is contained between the appointment range
		$collision[] = sprintf(
			'%1$s < %2$s AND %2$s <= %3$s',
			$dbo->qn('r.checkin_ts'),
			$dbo->q($checkout->toSql()),
			$out
		);

		// check whether our range entirely wraps the appointment
		$collision[] = sprintf(
			'%s < %s AND %s < %s',
			$dbo->q($checkin->toSql()),
			$dbo->qn('r.checkin_ts'),
			$out,
			$dbo->q($checkout->toSql())
		);

		// fetch intersections
		$q->andWhere($collision);

		$dbo->setQuery($q);
		$count = (int) $dbo->loadResult();

		if (!$count)
		{
			// no intersection between the appointments
			return true;
		}

		if (!$service->app_per_slot && $count > 0)
		{
			// Even if the service supports a maximum capacity higher than 1, 
			// it doesn't allow simultaneous bookings. For this reason, since
			// we found a colliding reservation, the employee is not available.
			return false;
		}

		// make sure the current people count plus the specified number of
		// participants doesn't exceed the maximum capacity of the service
		return ($count + $people) <= $service->max_capacity;
	}

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
	public function isServiceAvailable($date, $duration = null, $people = 1, $id = 0)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$employees = array();

		/**
		 * Trigger hook to use a custom method to load the supported employees
		 * without having to use a direct query. The plugins will have to return
		 * an array of employee IDs.
		 *
		 * @param 	self    $search  The availability search instance.
		 * @param 	string  $date    The check-in date (UTC).
		 *
		 * @return 	array   An array of employee IDs.
		 *
		 * @since 	1.7
		 */
		$results = $dispatcher->trigger('onFetchServiceAvailableEmployees', array($this, $date));

		// iterate all results
		foreach ($results as $result)
		{
			if (is_array($result))
			{
				// in case of an array, merge with the existing employees
				$employees = array_merge($employees, array_map('intval', $result));
			}
			else
			{
				// append given employee
				$employees[] = (int) $result;
			}
		}

		// check whether the plugins already filled the array of employees
		if (!$employees)
		{
			// nope, fallback to default query
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('a.id_employee'))
				->select('COUNT(' . $dbo->qn('r.id') . ') AS ' . $dbo->qn('count'))
				->from($dbo->qn('#__vikappointments_ser_emp_assoc', 'a'))
				->leftjoin($dbo->qn('#__vikappointments_reservation', 'r') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('a.id_employee'))
				->where($dbo->qn('a.id_service') . ' = ' . (int) $this->get('id_service'))
				->group($dbo->qn('a.id_employee'))
				->order($dbo->qn('count') . ' ASC');

			/**
			 * Trigger hook to allow the plugins to manipulate the default query used
			 * to load the employees assigned to the selected service. The query loads
			 * first the employees with the lowest count of overall reservations, so
			 * that we can have a correct balance.
			 *
			 * It is possible to use this hook to improve this algorithm, in example by
			 * counting only the reservations of the last quarter.
			 *
			 * @param 	mixed   &$query  Either a query string or a builder instance.
			 * @param 	self    $search  The availability search instance.
			 * @param 	string  $date    The check-in date (UTC).
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$results = $dispatcher->trigger('onQueryServiceAvailableEmployees', array(&$q, $this, $date));
			
			$dbo->setQuery($q);

			// get employees list
			$employees = $dbo->loadColumn();

			if (!$employees)
			{
				// no employees assigned to this service
				return false;
			}
		}
		else
		{
			// avoid duplicates
			$employees = array_unique($employees);
		}

		// get list of excluded employees, if any
		$excluded = (array) $this->get('exclude_employees', array());

		// get rid of the employees that should be excluded
		$employees = array_diff($employees, $excluded);

		// load reservation model
		$resModel = JModelVAP::getInstance('reservation');
		// load employee model
		$empModel = JModelVAP::getInstance('employee');

		// iterate all the employees found
		foreach ($employees as $id_employee)
		{
			// temporarily set employee ID
			$this->set('id_employee', $id_employee);

			/**
			 * Before to check whether an employees was available on a specific day,
			 * we need to make sure that it actually works for the specified check-in
			 * date and time, otherwise a reservation might be assigned to an employee
			 * that doesn't work for that day.
			 * 
			 * @since 1.7.1
			 */
			$timeline = $resModel->getAvailableTimes([
				'checkin_ts'  => $date,
				'people'      => $people,
				'id'          => $id,
				'id_service'  => $this->get('id_service'),
				'id_employee' => $this->get('id_employee'),
			]);

			if (!$timeline)
			{
				// not available for the current day, go to the next employee
				continue;
			}

			// convert times into an array
			$times = $timeline->toArray($flatten = true);

			// get employee timezone
			$tz = $empModel->getTimezone($this->get('id_employee'));

			// create check-in date and adjust it to the employee timezone
			$checkin = JDate::getInstance($date);
			$checkin->setTimezone(new DateTimeZone($tz));

			// extract time
			$hm = $checkin->format('H:i', $local = true);
			// convert time to minutes
			$hm = JHtml::fetch('vikappointments.time2min', $hm);

			// make sure the time is available
			if (!isset($times[$hm]) || $times[$hm] != 1)
			{
				// doesn't work or unavailable for the selected date and time,
				// go to the next employee
				continue;
			}

			// validate availability for this employee
			if ($this->isEmployeeAvailable($date, $duration, $people, $id))
			{
				// Yes, first available one.
				// Clear employee field before returning the ID
				$this->set('id_employee', 0);
				return $id_employee;
			}
		}

		// no available employee...
		$this->set('id_employee', 0);
		return false;
	}

	/**
	 * Checks whether there's a closing day/period on the given
	 * day and for the specified service.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if closed, false otherwise.
	 */
	public function isClosingDay($date)
	{
		$id_service = (int) $this->get('id_service', 0);

		// get supported global closing periods
		$closingPeriods = VikAppointments::getClosingPeriods($id_service);

		foreach ($closingPeriods as $period)
		{
			// check whether the date stays between the closing period
			if ($period['start'] <= $date && $date <= $period['end'])
			{
				// closed
				return true;
			}
		}

		// get supported global closing days
		$closingDays = VikAppointments::getClosingDays($id_service);

		$dt = JDate::getInstance($date);

		// get date chunks
		list($y, $m, $d) = explode('-', $dt->format('Y-m-d'));
		// get day of the week
		$w = (int) $dt->format('w');

		foreach ($closingDays as $day)
		{
			if ($day['freq'] == 0)
			{
				// look for single day
				if ($date == $day['ts'])
				{
					// closed
					return true;
				}
			}
			else if ($day['freq'] == 1)
			{
				// look for weekly frequency
				if ($w == JDate::getInstance($day['ts'])->format('w'))
				{
					// closed
					return true;
				}
			}
			else if ($day['freq'] == 2)
			{
				// get date chunks
				$app = explode('-', $day['ts']);

				// look for monthly frequency (same day)
				if ($d == $app[2])
				{
					return true;
				}
			}
			else if ($day['freq'] == 3)
			{
				// get date chunks
				$app = explode('-', $day['ts']);

				// look for yearly frequency (same day and month)
				if ($d == $app[2] && $m == $app[1])
				{
					return true;
				}
			}
		}

		// not a closing day
		return false;
	}

	/**
	 * Checks whether the service is published on the given date.
	 *
	 * @param 	string 	 $date  The UTC date in military format.
	 *
	 * @return 	boolean  True if closed, false otherwise.
	 */
	public function isServicePublished($date)
	{
		// get employee timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($this->get('id_employee'));

		$date = JDate::getInstance($date)->format('Y-m-d H:i:s');

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// get service-employee overrides
		$override = $model->getOverrides((int) $this->get('id_service'), (int) $this->get('id_employee'));

		if (!$override)
		{
			// missing relation
			return false;
		}

		if (!$override->published)
		{
			// we have an unpublished service, we don't need to go ahead
			return false;
		}

		// make sure the start publishing have been specified
		if (!VAPDateHelper::isNull($override->start_publishing))
		{
			// create start publishing date, which we should assume that it has been
			// specified by using the employee timezone
			$start = new JDate($override->start_publishing);
			$start->setTimezone(new DateTimeZone($tz));

			if ($date < $start->format('Y-m-d H:i:s', $local = true))
			{
				// the selected check-in is prior than the publishing date
				return false;
			}
		}

		// make sure the end publishing have been specified
		if (!VAPDateHelper::isNull($override->end_publishing))
		{
			// create end publishing date, which we should assume that it has been
			// specified by using the employee timezone
			$end = new JDate($override->end_publishing);
			$end->setTimezone(new DateTimeZone($tz));

			if ($date >= $end->format('Y-m-d H:i:s', $local = true))
			{
				// the selected check-in is after the end publishing date
				return false;
			}
		}

		// make sure the user is capable to access this service
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels && !in_array($override->level, $levels))
		{
			// not allowed to access this service
			return false;
		}

		// validate days restrictions only in the front-end
		if (!$this->isAdmin())
		{
			$config = VAPFactory::getConfig();
			
			/**
			 * The booking is allowed only in case the selected check-in date is higher than the
			 * current date plus the number of days set in configuration. In example, by specifying
			 * "1 day", the first available date will be one day after the current one (tomorrow).
			 *
			 * @since 1.7
			 */
			if ($override->mindate == -1)
			{
				// used global setting
				$override->mindate = $config->getUint('mindate');
			}

			if ($override->mindate > 0)
			{
				// create minimum date from now on
				$mindate = JFactory::getDate('+' . $override->mindate . ' days 00:00:00');

				if ($date < $mindate->format('Y-m-d H:i:s'))
				{
					// check-in date lower than the minimum allowed date
					return false;
				}
			}

			/**
			 * The booking is allowed only in case the selected check-in date is lower than the
			 * current date plus the number of days set in configuration. In example, by specifying
			 * "7 days", the first available date will be one week after the current one.
			 *
			 * @since 1.7
			 */
			if ($override->maxdate == -1)
			{
				// used global setting
				$override->maxdate = $config->getUint('maxdate');
			}

			if ($override->maxdate > 0)
			{
				// create minimum date from now on
				$maxdate = JFactory::getDate('+' . $override->maxdate . ' days 23:59:59');

				if ($date > $maxdate->format('Y-m-d H:i:s'))
				{
					// check-in date higher than the maximum allowed date
					return false;
				}
			}
		}

		/**
		 * This event can be used to apply additional conditions while checking whether
		 * the specified service is published or not. When this event is triggered, the
		 * system already validated the standard conditions and the service is going
		 * to be used by the website.
		 *
		 * @param 	object 	 $service  The service to check.
		 * @param 	string   $checkin  The check-in date (UTC).
		 *
		 * @return 	boolean  Return false to hide the service.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onCheckServiceVisibility', array($override, $date)))
		{
			// a plugin decided to hide the service
			return false;
		}

		return true;
	}

	/**
	 * Checks if the specified date is in the past or doesn't follow the
	 * booking minutes restriction of the service.
	 *
	 * @param 	string   $datetime  The check-in date time (military format).
	 *
	 * @return 	boolean  True if in the past, false otherwise.
	 */
	public function isPastTime($datetime)
	{
		if ($this->isAdmin())
		{
			// never look for times in the past for admin
			return false;
		}

		// get service-employee association model
		$model = JModelVAP::getInstance('serempassoc');
		// load service overrides or default details in case of missing employee
		$service = $model->getOverrides($this->get('id_service'), $this->get('id_employee'));

		// get default booking minutes restrictions
		$advance = VAPFactory::getConfig()->getUint('minrestr');

		/**
		 * Check if we should use different restrictions
		 * depending on the selected service.
		 *
		 * @since 1.6.5
		 */
		if (isset($service->minrestr) && $service->minrestr != -1)
		{
			// use specified service restrictions
			$advance = (int) $service->minrestr;
		}

		// make sure the time was set within the date time
		if (preg_match("/^[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}$/", $datetime))
		{
			// time is missing, use the end of the day as threshold
			$datetime = JDate::getInstance($datetime . ' 23:59:59')->format('Y-m-d H:i:s');
		}

		/**
		 * Trigger event to let the plugins can calculate their own advance time,
		 * also known as "Booking Minutes Restrictions".
		 * Only the highest returned value will be used, also compared to the
		 * default one.
		 *
		 * @param 	integer  $advance   The default "advance" amount.
		 * @param 	string   $datetime  The check-in date (@since 1.7 changed from timestamp).
		 * @param 	object   $service   The details of the booked service (@since 1.7 changed from array).
		 *
		 * @return 	integer  The overwritten "advance" amount (in minutes).
		 *
		 * @since 	1.6.6
		 */
		$results = VAPFactory::getEventDispatcher()->trigger('onCalculateAdvanceTime', array($advance, $datetime, $service));
		
		if ($results)
		{
			// keep only the highest amount
			$advance = max($results);
		}

		// get the correct timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($this->get('id_employee'));

		// create threshold by adding the advance minutes to the current time
		$threshold = new JDate('+' . $advance . ' minutes');
		$threshold->setTimezone(new DateTimeZone($tz));

		// check whether the check-in is prior than the fetched threshold
		return $datetime < $threshold->format('Y-m-d H:i:s', $local = true);
	}
}

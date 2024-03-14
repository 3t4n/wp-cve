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
VAPLoader::import('libraries.calendar.wrapper');

/**
 * VikAppointments weekly calendar model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCaldays extends JModelVAP
{
	/**
	 * A lookup of services per employee.
	 *
	 * @var array
	 */
	protected $_services = array();

	/**
	 * Returns the structure of the weekly calendar to display.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	object 	The resulting calendar.
	 */
	public function getCalendar(array $options = array())
	{
		if (!isset($options['layout']))
		{
			// use default layout if not specified
			$options['layout'] = 'default';
		}

		if (!isset($options['date']) || VAPDateHelper::isNull($options['date']))
		{
			// get current day
			$options['begin'] = JDate::getInstance('today 00:00:00')->toSql();
		}
		else
		{
			// get selected day (always treat as UTC to obtain the correct date at midnight)
			$options['begin'] = VAPDateHelper::date2sql($options['date'], new DateTimeZone('UTC'));
		}

		$dt = new JDate($options['begin']);

		if ($options['layout'] == 'day')
		{
			// get all reservations for the specified date
			$dt->modify('23:59:59');
		}
		else
		{
			// get all reservations between the specified day and the next 7 days
			$dt->modify('+7 days');
		}

		// fetch ending dates range
		$options['end'] = $dt->toSql();

		$calendar = new stdClass;
		$calendar->start = $options['begin'];
		$calendar->end   = $options['end'];

		// fetch appointments
		$rows = $this->getAppointments($options);

		if (!empty($options['layout']) && $options['layout'] == 'day')
		{
			// create a calendar wrapper for each employee
			$calendar->employees = $this->groupReservationsByEmployee($rows);
		}
		
		// create a global calendar wrapper
		$calendar->calendar = $this->groupReservations($rows);

		return $calendar;
	}

	/**
	 * Returns a list of supported services.
	 *
	 * @param 	array  $options  An array of options.
	 *
	 * @return 	array  An array of services.
	 */
	public function getServices(array $options = array())
	{
		// fetch employee ID
		$id_employee = !empty($options['employee']) ? (int) $options['employee'] : 0;

		if (!isset($this->_services[$id_employee]))
		{
			// get services
			$this->_services[$id_employee] = array();

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('s.id', 's.name', 's.color', 's.id_group')))
				->from($dbo->qn('#__vikappointments_service', 's'))
				->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'))
				->where(1)
				->order($dbo->qn('g.ordering') . ' ASC')
				->order($dbo->qn('s.ordering') . ' ASC');

			// if the employee is set, obtain only the related services

			if ($id_employee)
			{
				$q->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('a.id_service'));
				$q->where($dbo->qn('a.id_employee') . ' = ' . $id_employee);
			}

			$dbo->setQuery($q);
			$list = $dbo->loadObjectList();

			// check whether the first element of the list has no group
			if ($list && $list[0]->id_group <= 0)
			{
				// check whether the last element of the list has a group
				$last = end($list);

				if ($last->id_group > 0)
				{
					// always move services without group at the end of the list
					while ($list[0]->id_group <= 0)
					{
						$list[] = array_shift($list);
					}
				}
			}

			$this->_services[$id_employee] = $list;
		}

		// return cached services
		return $this->_services[$id_employee];
	}

	/**
	 * Returns a calendar wrapper or an array of wrappers, depending
	 * on the provided options.
	 *
	 * @param 	array  $options  An array of options.
	 *
	 * @return 	mixed  A calendar wrapper in case of weekly layout,
	 *                 an array of wrappers in case of daily layout.
	 */
	public function getAppointments(array $options = array())
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn(array(
			'r.id',
			'r.id_service',
			'r.id_employee',
			'r.checkin_ts',
			'r.duration',
			'r.sleep',
			'r.people',
			'r.total_cost',
			'r.purchaser_nominative',
		)));
		$q->select($dbo->qn('e.nickname', 'employee_name'));
		$q->select($dbo->qn('s.name', 'service_name'));
		$q->select($dbo->qn('s.color', 'service_color'));

		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('r.id_employee'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('r.id_service'));

		// get all status codes that locks the appointments
		$statuses = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		if ($statuses)
		{
			// take only the reserved appointments
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $statuses)) . ')');
		}

		/**
		 * @todo consider to display the closures
		 */

		// exclude closures
		$q->where($dbo->qn('r.closure') . ' = 0');

		// exclude parent orders
		$q->where($dbo->qn('r.id_parent') . ' > 0');

		if (!empty($options['employee']))
		{
			// filter by selected employee
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $options['employee']);
		}

		// get all supported services
		$services = $this->getServices($options);

		$options['services'] = !empty($options['services']) ? (array) $options['services'] : array();

		// check whether we have at least a service and not all them are selected
		if (($count = count($options['services'])) && $count != count($services))
		{
			// filter by selected services
			$q->where($dbo->qn('r.id_service') . ' IN (' . implode(', ', $options['services']) . ')');
		}

		// check whether we should filter by employee
		if (!empty($options['employees']))
		{
			// filter by selected services
			$q->where($dbo->qn('r.id_employee') . ' IN (' . implode(', ', array_map('intval', (array) $options['employees'])) . ')');
		}

		// adjust dates range to UTC
		$tz = JFactory::getUser()->getTimezone();
		$begin = new JDate($options['begin'], $tz);
		$end   = new JDate($options['end'], $tz);

		/**
		 * It is needed to intersect the delimiters with checkin and checkout in order
		 * to retrieve also the appointments that start on a day and ends on the next one.
		 */
		$q->andWhere(array(
			$dbo->qn('r.checkin_ts') . ' BETWEEN ' . $dbo->q($begin->toSql()) . ' AND ' . $dbo->q($end->toSql()),
			sprintf(
				'DATE_ADD(%s, INTERVAL (%s + %s) MINUTE) BETWEEN %s AND %s',
				$dbo->qn('r.checkin_ts'),
				$dbo->qn('r.duration'),
				$dbo->qn('r.sleep'),
				$dbo->q($begin->toSql()),
				$dbo->q($end->toSql())
			),
		));

		$q->order($dbo->qn('r.checkin_ts') . ' ASC');

		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Groups the reservations depending on their checkin.
	 *
	 * @param 	array  $rows  The reservations to group.
	 *
	 * @return 	array  The grouped reservations.
	 */
	protected function groupReservations(array $rows)
	{
		$wrapper = new CalendarWrapper();

		foreach ($rows as $row)
		{
			$row->checkout_ts = VikAppointments::getCheckout($row->checkin_ts, $row->duration);

			$app = $wrapper->getIntersection($row->checkin_ts, $row->checkout_ts);

			if ($app !== false)
			{
				$app->extendBounds($row->checkin_ts, $row->checkout_ts, $row);
			}
			else
			{
				$wrapper->push(new CalendarRect($row->checkin_ts, $row->checkout_ts, $row));
			}
		}

		return $wrapper;
	}

	/**
	 * Groups the reservations by employee depending on their checkin.
	 *
	 * @param 	array  $rows  The reservations to group.
	 *
	 * @return 	array  The grouped reservations.
	 *
	 * @uses 	groupReservations()
	 */
	protected function groupReservationsByEmployee(array $rows)
	{
		$employees = array();

		// create employees list
		foreach (JHtml::fetch('vaphtml.admin.employees') as $emp)
		{
			$tmp = new stdClass;
			$tmp->id       = $emp->value;
			$tmp->nickname = $emp->text;
			$tmp->calendar = array();

			$employees[$emp->value] = $tmp;
		}

		// group reservations by employee
		foreach ($rows as $row)
		{
			$id_emp = $row->id_employee;

			if (isset($employees[$id_emp]))
			{
				$employees[$id_emp]->calendar[] = $row;
			}
		}

		foreach ($employees as $id_emp => $data)
		{
			// replace employee reservations with calendar wrapper
			$employees[$id_emp]->calendar = $this->groupReservations($data->calendar);
		}

		return $employees;
	}
}

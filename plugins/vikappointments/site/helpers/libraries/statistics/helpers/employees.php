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

VAPLoader::import('libraries.statistics.helpers.commons.query');

/**
 * Helper class used to calculate employees statistics.
 *
 * @since 1.7
 */
abstract class VAPStatisticsHelperEmployees
{
	/**
	 * Use methods defined by query trait for a better reusability.
	 *
	 * @see VAPStatisticsHelperCommonQuery
	 */
	use VAPStatisticsHelperCommonQuery;

	/**
	 * Loads the trend of the employees.
	 *
	 * @param 	mixed   $from      The from date object or string.
	 * @param 	mixed   $to        The to date object or string.
	 * @param 	string  $column    The column to fetch.
	 * @param 	mixed   $employee  Either an employee ID or an array.
	 *
	 * @return 	mixed
	 */
	public static function getTrend($from, $to, $column = 'total', $employee = null)
	{
		if (is_string($from))
		{
			// create date instance
			$from = JFactory::getDate($from);
		}

		if (is_string($to))
		{
			// create date instance
			$to = JFactory::getDate($to);
		}

		// check if we are filtering by weeks/last month
		if (VAPDateHelper::diff($from, $to, 'days') <= 31)
		{
			// use the format set from the configuration
			$label_format = VAPFactory::getConfig()->get('dateformat');
			// get rid of year
			$label_format = preg_replace("/[^a-z]?Y[^a-z]?/", '', $label_format);

			// group by day in SQL query
			$sql_format = '%Y-%m-%d';
			// iterate day by day
			$modifier = '+1 day';
		}
		else
		{
			// Check whether the specified dates are in different years.
			// In case they are, the labels format should be "M Y", otherwise just "M" could be used.
			$label_format = $from->format('Y', true) == $to->format('Y', true) ? 'M' : 'M Y';

			// group by month in SQL query
			$sql_format = '%Y-%m';
			// iterate month by month
			$modifier = '+1 month';
		}

		$dt = clone $from;

		$data = array();

		// iterate as long as the date is lower than the ending date
		while ($dt < $to)
		{
			// format label
			$label = JHtml::fetch('date', $dt->format('Y-m-d H:i:s', true), $label_format);

			// init chart data
			$data[$label] = array();

			// increase date by the fetched modifier
			$dt->modify($modifier);
		}

		// get list of preset colors
		$colors = JHtml::fetch('vaphtml.color.preset', $list = true, $group = false);

		$dbo = JFactory::getDbo();

		// load all the supported employees
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->select($dbo->qn('nickname', 'name'))
			->from($dbo->qn('#__vikappointments_employee'))
			->order($dbo->qn('id') . ' ASC');

		if ($employee)
		{
			// take only the specified employees
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $employee)) . ')');
		}

		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if (!$rows)
		{
			// no supported employees
			return false;
		}

		$employees = array();

		// create employees lookup
		foreach ($rows as $i => $employee)
		{
			// get progressive color
			$employee['color'] = $colors[$i % count($colors)];

			$employees[$employee['id']] = $employee;
		}

		// keep track of all the used employees, so that we can normalize each dataset
		$fetched = array();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = false, $checkin = true);

		// then group orders by employee
		$q->select($dbo->qn('o.id_employee'));
		$q->group($dbo->qn('o.id_employee'));
		// take only the employees found with the previous query
		$q->where($dbo->qn('o.id_employee') . ' IN (' . implode(',', array_keys($employees)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			// convert query date into our date format
			$key = JHtml::fetch('date', $row->date, $label_format);

			if (!isset($data[$key]) || !isset($employees[$row->id_employee]))
			{
				// something went wrong, label not found...
				continue;
			}

			// extract employee details from lookup
			$obj = $employees[$row->id_employee];

			// track used employee
			$fetched[$row->id_employee] = $obj;

			if (!isset($data[$key][$row->id_employee]))
			{
				// init total for thi employee
				$obj['total'] = 0;
				$data[$key][$row->id_employee] = $obj;
			}

			// increase the total of the specified employee
			$data[$key][$row->id_employee]['total'] += property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		// normalize datasets by creating a null value for each missing employee
		foreach ($data as $key => $employees)
		{
			foreach ($fetched as $id_emp => $emp)
			{
				if (!isset($employees[$id_emp]))
				{
					$emp['total'] = 0;
					$data[$key][$id_emp] = $emp;
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the revenue count of the employees.
	 *
	 * @param 	mixed   $from      The from date object or string.
	 * @param 	mixed   $to        The to date object or string.
	 * @param 	string  $column    The column to fetch.
	 * @param 	mixed   $employee  Either an employee ID or an array.
	 *
	 * @return 	mixed
	 */
	public static function getCount($from, $to, $column = 'total', $employee = null)
	{
		if (!VAPDateHelper::isNull($from) && is_string($from))
		{
			// create date instance
			$from = JFactory::getDate($from);
		}

		if (!VAPDateHelper::isNull($to) && is_string($to))
		{
			// create date instance
			$to = JFactory::getDate($to);
		}

		// get list of preset colors
		$colors = JHtml::fetch('vaphtml.color.preset', $list = true, $group = false);

		$dbo = JFactory::getDbo();

		// load all the supported employees
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->select($dbo->qn('nickname', 'name'))
			->from($dbo->qn('#__vikappointments_employee'))
			->order($dbo->qn('id') . ' ASC');

		if ($employee)
		{
			// take only the specified employees
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $employee)) . ')');
		}

		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if (!$rows)
		{
			// no supported employees
			return false;
		}

		$employees = array();

		// create employees lookup
		foreach ($rows as $i => $employee)
		{
			// get progressive color
			$employee['color'] = $colors[$i % count($colors)];
			$employee['total'] = 0;

			$data[$employee['id']] = $employee;
		}

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false, $checkin = true);

		// then group orders by employee
		$q->select($dbo->qn('o.id_employee'));
		$q->group($dbo->qn('o.id_employee'));
		// take only the employees found with the previous query
		$q->where($dbo->qn('o.id_employee') . ' IN (' . implode(',', array_keys($data)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_employee]))
			{
				// something went wrong, employee not found...
				continue;
			}

			// set total count
			$data[$row->id_employee]['total'] = property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		return $data;
	}

	/**
	 * Loads the trend of the services assigned to the selected employee.
	 *
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	mixed    $column    Either the column to fetch or an array.
	 * @param 	integer  $employee  The employee ID.
	 * @param 	boolean  $checkin   True to filter the appointments by check-in date.
	 * @param 	mixed    $id_ser    An optional array to filter the services.
	 * @param 	boolean  $extended  True to use an extended date format.
	 *
	 * @return 	mixed
	 */
	public static function getServicesTrend($from, $to, $column = 'total', $employee = null, $checkin = true, $id_ser = null, $extended = false)
	{
		if (is_string($from))
		{
			// create date instance
			$from = JFactory::getDate($from);
		}

		if (is_string($to))
		{
			// create date instance
			$to = JFactory::getDate($to);
		}

		// check if we are filtering by weeks/last month
		if (VAPDateHelper::diff($from, $to, 'days') <= 31)
		{
			if ($extended)
			{
				// use extended date format
				$label_format = JText::translate('DATE_FORMAT_LC3');
			}
			else
			{
				// use the format set from the configuration
				$label_format = VAPFactory::getConfig()->get('dateformat');
			}

			// get rid of year
			$label_format = preg_replace("/[^a-z]?Y[^a-z]?/", '', $label_format);

			// group by day in SQL query
			$sql_format = '%Y-%m-%d';
			// iterate day by day
			$modifier = '+1 day';
		}
		else
		{
			if ($extended)
			{
				// use extended date format
				$label_format = JText::translate('DATE_FORMAT_LC3');
				// get rid of month day
				$label_format = preg_replace("/[^a-z]?d[^a-z]?/", '', $label_format);
			}
			else
			{
				// Check whether the specified dates are in different years.
				// In case they are, the labels format should be "M Y", otherwise just "M" could be used.
				$label_format = $from->format('Y', true) == $to->format('Y', true) ? 'M' : 'M Y';
			}

			// group by month in SQL query
			$sql_format = '%Y-%m';
			// iterate month by month
			$modifier = '+1 month';
		}

		$dt = clone $from;

		$data = array();

		// iterate as long as the date is lower than the ending date
		while ($dt < $to)
		{
			// format label
			$label = JHtml::fetch('date', $dt->format('Y-m-d H:i:s', true), $label_format);

			// init chart data
			$data[$label] = array();

			// increase date by the fetched modifier
			$dt->modify($modifier);
		}

		// get employee model
		$employeeModel = JModelVAP::getInstance('employee');

		$services = array();

		// load all the supported services
		foreach ($employeeModel->getServices((int) $employee) as $service)
		{
			$services[$service->id] = array(
				'name'  => $service->name,
				'color' => $service->color,
			);

			foreach ((array) $column as $colName)
			{
				$services[$service->id][$colName] = 0;
			}
		}

		// keep track of all the used services, so that we can normalize each dataset
		$fetched = array();

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = false, $checkin);

		// then group orders by service
		$q->select($dbo->qn('o.id_service'));
		$q->group($dbo->qn('o.id_service'));
		// take only the specified employee
		$q->where($dbo->qn('o.id_employee') . ' = ' . (int) $employee);

		if ($id_ser)
		{
			// take only the specified services
			$q->where($dbo->qn('o.id_service') . ' IN (' . implode(',', array_map('intval', (array) $id_ser)) . ')');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($services[$row->id_service]))
			{
				// we probably have an appointment assigned to an services that
				// do not perform the selected service anymore
				continue;
			}

			$ser = $services[$row->id_service];

			// convert query date into our date format
			$key = JHtml::fetch('date', $row->date, $label_format);

			if (!isset($data[$key]))
			{
				$data[$key] = array();
			}

			foreach ((array) $column as $colName)
			{
				$ser[$colName] = property_exists($row, $colName) ? $row->{$colName} : $row->total;
			}

			// register total
			$data[$key][$row->id_service] = $ser;

			// track used employee
			$fetched[$row->id_service] = 1;
		}

		// normalize datasets by creating a null value for each missing employee
		foreach ($data as $key => $list)
		{
			foreach ($fetched as $id_ser => $ser)
			{
				if (!isset($list[$id_ser]))
				{
					$data[$key][$id_ser] = $services[$id_ser];
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the revenue count of the employees.
	 *
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	string   $column    The column to fetch.
	 * @param 	integer  $employee  The employee ID.
	 *
	 * @return 	mixed
	 */
	public static function getServicesCount($from, $to, $column = 'total', $employee = null)
	{
		if (!VAPDateHelper::isNull($from) && is_string($from))
		{
			// create date instance
			$from = JFactory::getDate($from);
		}

		if (!VAPDateHelper::isNull($to) && is_string($to))
		{
			// create date instance
			$to = JFactory::getDate($to);
		}

		// get employee model
		$employeeModel = JModelVAP::getInstance('employee');

		$data = array();

		// load all the supported services
		foreach ($employeeModel->getServices((int) $employee) as $service)
		{
			$data[$service->id] = array(
				'name'  => $service->name,
				'color' => $service->color,
				'total' => 0,
			);
		}

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false, $checkin = true);

		// then group orders by service
		$q->select($dbo->qn('o.id_service'));
		$q->group($dbo->qn('o.id_service'));
		// take only the specified employee
		$q->where($dbo->qn('o.id_employee') . ' = ' . (int) $employee);

		$dbo->setQuery($q);

		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_service]))
			{
				// we probably have an appointment assigned to a service that
				// do not belong the selected employee anymore
				continue;
			}

			// set total count
			$data[$row->id_service]['total'] = property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		return $data;
	}
}

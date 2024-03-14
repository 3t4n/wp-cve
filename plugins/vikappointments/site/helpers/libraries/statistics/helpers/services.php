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
 * Helper class used to calculate services statistics.
 *
 * @since 1.7
 */
abstract class VAPStatisticsHelperServices
{
	/**
	 * Use methods defined by query trait for a better reusability.
	 *
	 * @see VAPStatisticsHelperCommonQuery
	 */
	use VAPStatisticsHelperCommonQuery;

	/**
	 * Loads the trend of the services.
	 *
	 * @param 	mixed   $from     The from date object or string.
	 * @param 	mixed   $to       The to date object or string.
	 * @param 	string  $column   The column to fetch.
	 * @param 	mixed   $service  Either a service ID or an array.
	 *
	 * @return 	mixed
	 */
	public static function getTrend($from, $to, $column = 'total', $service = null)
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

		$dbo = JFactory::getDbo();

		// load all the supported services
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name', 'color')))
			->from($dbo->qn('#__vikappointments_service'))
			->order($dbo->qn('ordering') . ' ASC');

		if ($service)
		{
			// take only the specified services
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $service)) . ')');
		}

		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if (!$rows)
		{
			// no supported services
			return false;
		}

		$services = array();

		// create services lookup
		foreach ($rows as $service)
		{
			$services[$service['id']] = $service;
		}

		// keep track of all the used services, so that we can normalize each dataset
		$fetched = array();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = false, $checkin = true);

		// then group orders by service
		$q->select($dbo->qn('o.id_service'));
		$q->group($dbo->qn('o.id_service'));
		// take only the services found with the previous query
		$q->where($dbo->qn('o.id_service') . ' IN (' . implode(',', array_keys($services)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			// convert query date into our date format
			$key = JHtml::fetch('date', $row->date, $label_format);

			if (!isset($data[$key]) || !isset($services[$row->id_service]))
			{
				// something went wrong, label not found...
				continue;
			}

			// extract service details from lookup
			$obj = $services[$row->id_service];

			// track used service
			$fetched[$row->id_service] = $obj;

			if (!isset($data[$key][$row->id_service]))
			{
				// init total for thi service
				$obj['total'] = 0;
				$data[$key][$row->id_service] = $obj;
			}

			// increase the total of the specified service
			$data[$key][$row->id_service]['total'] += property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		// normalize datasets by creating a null value for each missing service
		foreach ($data as $key => $services)
		{
			foreach ($fetched as $id_ser => $ser)
			{
				if (!isset($services[$id_ser]))
				{
					$ser['total'] = 0;
					$data[$key][$id_ser] = $ser;
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the revenue count of the services.
	 *
	 * @param 	mixed   $from     The from date object or string.
	 * @param 	mixed   $to       The to date object or string.
	 * @param 	string  $column   The column to fetch.
	 * @param 	mixed   $service  Either a service ID or an array.
	 *
	 * @return 	mixed
	 */
	public static function getCount($from, $to, $column = 'total', $service = null)
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

		$dbo = JFactory::getDbo();

		// load all the supported services
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name', 'color')))
			->from($dbo->qn('#__vikappointments_service'))
			->order($dbo->qn('ordering') . ' ASC');

		if ($service)
		{
			// take only the specified services
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', (array) $service)) . ')');
		}

		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if (!$rows)
		{
			// no supported services
			return false;
		}

		$data = array();

		// create services lookup
		foreach ($rows as $service)
		{
			$data[$service['id']] = $service;
			$data[$service['id']]['total'] = 0;
		}

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false, $checkin = true);

		// then group orders by service
		$q->select($dbo->qn('o.id_service'));
		$q->group($dbo->qn('o.id_service'));
		// take only the services found with the previous query
		$q->where($dbo->qn('o.id_service') . ' IN (' . implode(',', array_keys($data)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_service]))
			{
				// something went wrong, service not found...
				continue;
			}

			// set total count
			$data[$row->id_service]['total'] = property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		return $data;
	}

	/**
	 * Loads the trend of the employees assigned to the selected service.
	 *
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	mixed    $column    Either the column to fetch or an array.
	 * @param 	integer  $service   The service ID.
	 * @param 	boolean  $checkin   True to filter the appointments by check-in date.
	 * @param 	mixed    $id_emp    An optional array to filter the employees.
	 * @param 	boolean  $extended  True to use an extended date format.
	 *
	 * @return 	mixed
	 */
	public static function getEmployeesTrend($from, $to, $column = 'total', $service = null, $checkin = true, $id_emp = null, $extended = false)
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

		// get service model
		$serviceModel = JModelVAP::getInstance('service');

		$employees = array();

		// load all the supported employees
		foreach ($serviceModel->getEmployees((int) $service) as $employee)
		{
			$employees[$employee->id] = array(
				'name'  => $employee->nickname,
			);

			foreach ((array) $column as $colName)
			{
				$employees[$employee->id][$colName] = 0;
			}
		}

		// keep track of all the used employees, so that we can normalize each dataset
		$fetched = array();

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = false, $checkin);

		// then group orders by employee
		$q->select($dbo->qn('o.id_employee'));
		$q->group($dbo->qn('o.id_employee'));
		// take only the specified service
		$q->where($dbo->qn('o.id_service') . ' = ' . (int) $service);

		if ($id_emp)
		{
			// take only the specified employees
			$q->where($dbo->qn('o.id_employee') . ' IN (' . implode(',', array_map('intval', (array) $id_emp)) . ')');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($employees[$row->id_employee]))
			{
				// we probably have an appointment assigned to an employees that
				// do not perform the selected service anymore
				continue;
			}

			$emp = $employees[$row->id_employee];

			// convert query date into our date format
			$key = JHtml::fetch('date', $row->date, $label_format);

			if (!isset($data[$key]))
			{
				$data[$key] = array();
			}

			foreach ((array) $column as $colName)
			{
				$emp[$colName] = property_exists($row, $colName) ? $row->{$colName} : $row->total;
			}

			// register total
			$data[$key][$row->id_employee] = $emp;

			// track used employee
			$fetched[$row->id_employee] = 1;
		}

		// normalize datasets by creating a null value for each missing service
		foreach ($data as $key => $list)
		{
			foreach ($fetched as $id_emp => $emp)
			{
				if (!isset($list[$id_emp]))
				{
					$data[$key][$id_emp] = $employees[$id_emp];
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the revenue count of the services.
	 *
	 * @param 	mixed    $from     The from date object or string.
	 * @param 	mixed    $to       The to date object or string.
	 * @param 	string   $column   The column to fetch.
	 * @param 	integer  $service  The service ID.
	 *
	 * @return 	mixed
	 */
	public static function getEmployeesCount($from, $to, $column = 'total', $service = null)
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

		// get service model
		$serviceModel = JModelVAP::getInstance('service');

		$data = array();

		// load all the supported employees
		foreach ($serviceModel->getEmployees((int) $service) as $employee)
		{
			$data[$employee->id] = array(
				'name'  => $employee->nickname,
				'total' => 0,
			);
		}

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false, $checkin = true);

		// then group orders by employee
		$q->select($dbo->qn('o.id_employee'));
		$q->group($dbo->qn('o.id_employee'));
		// take only the specified service
		$q->where($dbo->qn('o.id_service') . ' = ' . (int) $service);

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_employee]))
			{
				// we probably have an appointment assigned to an employees that
				// do not perform the selected service anymore
				continue;
			}

			// set total count
			$data[$row->id_employee]['total'] = property_exists($row, $column) ? $row->{$column} : $row->total;
		}

		return $data;
	}
}

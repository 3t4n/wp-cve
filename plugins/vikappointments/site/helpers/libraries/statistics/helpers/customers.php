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
 * Helper class used to calculate customers statistics.
 *
 * @since 1.7
 */
abstract class VAPStatisticsHelperCustomers
{
	/**
	 * Use methods defined by query trait for a better reusability.
	 *
	 * @see VAPStatisticsHelperCommonQuery
	 */
	use VAPStatisticsHelperCommonQuery;

	/**
	 * Loads the reservations trend of the selected customers.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string. 
	 *
	 * @return 	mixed
	 */
	public static function getTrend($customers, $from, $to)
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		// inject each customer within the resulting data
		foreach ($rows as $cust)
		{
			foreach ($data as $k => $pool)
			{
				$data[$k][$cust->id] = array(
					'name'  => $cust->billing_name,
					'total' => 0,
				);
			}
		}

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = false, $checkin = true);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			// convert query date into our date format
			$key = JHtml::fetch('date', $row->date, $label_format);

			if (!isset($data[$key]) || !isset($data[$key][$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// increase the total of the specified user
			$data[$key][$row->id_user]['total'] += $row->count;
		}

		return $data;
	}

	/**
	 * Loads the total revenue data coming from the selected customers.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string.
	 * @param 	mixed  $column     Either a column or an array of columns to fetch.
	 *
	 * @return 	mixed
	 */
	public static function getTotalRevenue($customers, $from = null, $to = null, $column = 'total')
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		$data = array();

		// inject each customer within the resulting data
		foreach ($rows as $i => $cust)
		{
			$data[$cust->id] = array(
				'name'  => $cust->billing_name,
				'color' => $colors[$i % count($colors)],
			);

			// init columns
			foreach ((array) $column as $colName)
			{
				$data[$cust->id][$colName] = 0;
			}
		}

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $sql_format = null, $parent = false, $checkin = true);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// increase specified columns
			foreach ((array) $column as $colName)
			{
				$data[$row->id_user][$colName] = property_exists($row, $colName) ? $row->{$colName} : $row->total;
			}
		}

		return $data;
	}

	/**
	 * Calculates a few statistics about the most booked days of the week by
	 * the selected customers.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getWeekdaysReport($customers, $from, $to)
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$dt = new JDate();

		$data = array();

		// iterate week days
		for ($day = 1; $day <= 7; $day++)
		{
			// get name of the day
			$dayName = $dt->dayToString($day % 7, $short = true);

			// init chart data
			$data[$dayName] = array();
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		// inject each customer within the resulting data
		foreach ($rows as $cust)
		{
			foreach ($data as $k => $pool)
			{
				// init chart data
				$data[$k][$cust->id] = array(
					'name'  => $cust->billing_name,
					'total' => 0,
				);
			}
		}

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, '%w', $parent = false, $checkin = true);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			// convert query date into our date format
			$key = $dt->dayToString($row->date, $short = true);

			if (!isset($data[$key]) || !isset($data[$key][$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// increase the total of the specified user
			$data[$key][$row->id_user]['total'] = $row->count;
		}

		return $data;
	}

	/**
	 * Calculates a few statistics about the status codes of each selected customer.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getStatusesCount($customers, $from = null, $to = null)
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$codes = array();

		// create lookup of status codes
		foreach (JHtml::fetch('vaphtml.status.find', array('code', 'name', 'color'), array('appointments' => 1)) as $code)
		{
			$code = (array) $code;

			// init total
			$code['total'] = 0;

			$codes[$code['code']] = $code;
		}

		$data = array();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		// inject each customer within the resulting data
		foreach ($rows as $cust)
		{
			$data[$cust->id] = array(
				'name'     => $cust->billing_name,
				'statuses' => $codes,
			);
		}

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildStatusCountQuery('appointments', $from, $to);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// register count
			$data[$row->id_user]['statuses'][$row->status]['total'] = (int) $row->count;
		}

		return $data;
	}

	/**
	 * Returns a list of preferred services for each selected customer.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getPreferredServices($customers, $from = null, $to = null)
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$data = array();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		// inject each customer within the resulting data
		foreach ($rows as $cust)
		{
			$data[$cust->id] = array(
				'name'     => $cust->billing_name,
				'services' => array(),
			);
		}

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// join appointments to services to obtain names too
		$q->select($dbo->qn('s.name', 'service'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('o.id_service'));
		$q->group($dbo->qn('s.name'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// register record
			$data[$row->id_user]['services'][$row->service] = (array) $row;
		}

		return $data;
	}

	/**
	 * Returns a list of preferred employees for each selected customer.
	 *
	 * @param 	mixed  $customers  Either an array or a comma-separated list.
	 * @param 	mixed  $from       The from date object or string.
	 * @param 	mixed  $to         The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getPreferredEmployees($customers, $from = null, $to = null)
	{
		if (!$customers)
		{
			return false;
		}

		if (!is_array($customers))
		{
			// extract IDs from comma-separated list
			$customers = array_values(array_filter(preg_split("/\s*,\s*/", $customers)));
		}

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

		$data = array();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'billing_name')))
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return false;
		}

		// inject each customer within the resulting data
		foreach ($rows as $cust)
		{
			$data[$cust->id] = array(
				'name'      => $cust->billing_name,
				'employees' => array(),
			);
		}

		$dbo = JFactory::getDbo();

		// build query to fetch appointments total
		$q = static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = false);

		// then group orders by customer
		$q->select($dbo->qn('o.id_user'));
		$q->group($dbo->qn('o.id_user'));

		// join appointments to employees to obtain names too
		$q->select($dbo->qn('e.nickname', 'employee'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('o.id_employee'));
		$q->group($dbo->qn('e.nickname'));

		// take only the orders matching the specified customers
		$q->where($dbo->qn('o.id_user') . ' IN (' . implode(',', array_map('intval', $customers)) . ')');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			if (!isset($data[$row->id_user]))
			{
				// something went wrong, label not found...
				continue;
			}

			// register record
			$data[$row->id_user]['employees'][$row->employee] = (array) $row;
		}

		return $data;
	}
}

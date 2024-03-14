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
 * Helper class used to calculate finance statistics.
 *
 * @since 1.7
 */
abstract class VAPStatisticsHelperFinance
{
	/**
	 * Use methods defined by query trait for a better reusability.
	 *
	 * @see VAPStatisticsHelperCommonQuery
	 */
	use VAPStatisticsHelperCommonQuery;

	/**
	 * Loads the revenue data coming from the appointments, the packages and the subscriptions
	 * and group them by date.
	 *
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	mixed    $column    Either an array of columns or a single one.
	 * @param 	boolean  $extended  True to use an extended date format.
	 *
	 * @return 	mixed
	 */
	public static function getRevenue($from, $to, $column = 'total', $extended = false)
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

			if (is_array($column))
			{
				// init chart data with sub-array
				$data[$label] = array();

				foreach ($column as $colName)
				{
					$data[$label][$colName] = 0;
				}
			}
			else
			{
				// init chart data
				$data[$label] = 0;
			}

			// increase date by the fetched modifier
			$dt->modify($modifier);
		}

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $sql_format),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to, $sql_format),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to, $sql_format),
		);

		$dbo = JFactory::getDbo();

		foreach ($queries as $q)
		{
			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				// convert query date into our date format
				$key = JHtml::fetch('date', $row->date, $label_format);

				if (!isset($data[$key]))
				{
					// something went wrong, label not found...
					continue;
				}

				// check whether we have to map one column or more
				if (is_array($column))
				{
					// iterate each column to map
					foreach ($column as $colName)
					{
						// make sure the specified column exists
						if (isset($row->{$colName}))
						{
							// increase the specified column
							$data[$key][$colName] += $row->{$colName};
						}
					}
				}
				else
				{
					// increase the specified column
					$data[$key] += $row->{$column};
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the total revenue data coming from the appointments, the packages and the subscriptions.
	 *
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	mixed    $column    Either an array of columns or a single one.
	 *
	 * @return 	mixed
	 */
	public static function getTotalRevenue($from = null, $to = null, $column = 'total')
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

		if (is_array($column))
		{
			// init chart data with sub-array
			$data = array();

			foreach ($column as $colName)
			{
				$data[$colName] = 0;
			}
		}
		else
		{
			// init chart data
			$data = 0;
		}

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to),
		);

		$dbo = JFactory::getDbo();

		foreach ($queries as $q)
		{
			$dbo->setQuery($q);

			if ($row = $dbo->loadObject())
			{
				// check whether we have to map one column or more
				if (is_array($column))
				{
					// iterate each column to map
					foreach ($column as $colName)
					{
						// make sure the specified column exists
						if (isset($row->{$colName}))
						{
							// increase the specified column
							$data[$colName] += $row->{$colName};
						}
					}
				}
				else
				{
					// increase the specified column
					$data += $row->{$column};
				}
			}
		}

		return $data;
	}

	/**
	 * Calculates the rate of growth between the specified months.
	 *
	 * @param 	mixed    $month1        The first month to compare (built as Y-m).
	 * @param 	mixed    $month2        The second month to compare (built as Y-m).
	 * @param 	boolean  $proportional  Calculates a proportional earning for the current month.
	 *
	 * @return 	mixed
	 */
	public static function getRog($month1, $month2, $proportional = false)
	{
		$dbo = JFactory::getDbo();

		// get user timezone
		$tz = JFactory::getUser()->getTimezone();

		$data = array();

		foreach ([$month1, $month2] as $ym)
		{
			// prepare first delimiter
			$from = JFactory::getDate($ym . '-01 00:00:00', $tz);

			// prepare second delimiter
			$to = clone $from;
			$to->modify($to->format('Y-m-t', true) . ' 23:59:59');

			$queries = array(
				// build query to fetch appointments total
				static::buildRevenueQuery('appointments', $from, $to),
				// build query to fetch packages total
				static::buildRevenueQuery('packages', $from, $to),
				// build query to fetch subscriptions total
				static::buildRevenueQuery('subscriptions', $from, $to),
			);

			// get rid of month day
			$label_format = preg_replace("/[^a-z]?d[^a-z]?/", '', JText::translate('DATE_FORMAT_LC3'));

			// init data
			$data[$ym] = array(
				'date'  => JHtml::fetch('date', $from->format('Y-m-d', true), $label_format),
				'total' => 0,
			);

			foreach ($queries as $q)
			{
				$dbo->setQuery($q);

				if ($row = $dbo->loadObject())
				{
					// increase total gross
					$data[$ym]['total'] += $row->total;
				}
			}
		}

		if ($data[$month2]['total'] == 0)
		{
			// mark as "not enough data"
			$data['nodata'] = true;

			return $data;
		}

		// check if we should proportionally extend the total earning of the current month
		if ($proportional && $month1 == JHtml::fetch('date', 'now', 'Y-n'))
		{
			// get current day and last day in month
			$curr_day = (int) JHtml::fetch('date', 'now', 'j');
			$last_day = (int) JHtml::fetch('date', 'now', 't');

			// CURRENT_DAY : TOTAL_EARNED = LAST_DAY : PROP_EARNING
			// PROP_EARNING = TOTAL_EARNED * LAST_DAY / CURRENT_DAY
			$data[$month1]['total'] = round((float) $data[$month1]['total'] * $last_day / $curr_day, 2);
		}

		// calculate RoG
		$data['rog'] = ($data[$month1]['total'] - $data[$month2]['total']) / $data[$month2]['total'];
		$data['rogPercent'] = round($data['rog'] * 100, 2);

		return $data;
	}

	/**
	 * Calculates a few statistics about the supported payment methods.
	 *
	 * @param 	mixed    $from  The from date object or string.
	 * @param 	mixed    $to    The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getPaymentsData($from = null, $to = null)
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

		// load all the supported (global) payment gateways
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where($dbo->qn('id_employee') . ' <= 0')
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no supported payment gateways
			return false;
		}

		$data = array();

		// init payment data
		foreach ($rows as $payment)
		{
			$data[$payment->id] = array(
				'name' => $payment->name,
			);
		}

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = true),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to),
		);

		foreach ($queries as $q)
		{
			// group orders by payment
			$q->select($dbo->qn('o.id_payment'));
			$q->group($dbo->qn('o.id_payment'));
			// take only the payments found with the previous query
			$q->where($dbo->qn('o.id_payment') . ' IN (' . implode(',', array_keys($data)) . ')');

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				if (!isset($data[$row->id_payment]))
				{
					// payment not found
					continue;
				}

				// iterate each column to map
				foreach ($row as $colName => $colValue)
				{
					if ($colName === 'id_payment')
					{
						continue;
					}

					// make sure the specified column exists
					if (!isset($data[$row->id_payment][$colName]))
					{
						// init value
						$data[$row->id_payment][$colName] = 0;
					}

					// increase the specified column
					$data[$row->id_payment][$colName] += $colValue;
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the trend of the payment gateways.
	 *
	 * @param 	mixed    $from  The from date object or string.
	 * @param 	mixed    $to    The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getPaymentsTrend($from, $to)
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

		// load all the supported (global) payment gateways
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name')))
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where($dbo->qn('id_employee') . ' <= 0')
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no supported payment gateways
			return false;
		}

		$payments = array();

		// create payments id-name lookup
		foreach ($rows as $payment)
		{
			$payments[$payment->id] = $payment->name;
		}

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = true),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to, $sql_format),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to, $sql_format),
		);

		// keep track of all the used payments, so that we can normalize each dataset
		$fetched = array();

		foreach ($queries as $q)
		{
			// then group orders by payment
			$q->select($dbo->qn('o.id_payment'));
			$q->group($dbo->qn('o.id_payment'));
			// take only the payments found with the previous query
			$q->where($dbo->qn('o.id_payment') . ' IN (' . implode(',', array_keys($payments)) . ')');

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				// convert query date into our date format
				$key = JHtml::fetch('date', $row->date, $label_format);

				if (!isset($data[$key]))
				{
					// something went wrong, label not found...
					continue;
				}

				// extract payment name from lookup
				$payname = isset($payments[$row->id_payment]) ? $payments[$row->id_payment] : '/';

				// track used payment
				$fetched[$row->id_payment] = $payname;

				if (!isset($data[$key][$payname]))
				{
					// init total for this payment
					$data[$key][$payname] = 0;
				}

				// increase the total of the specified payment
				$data[$key][$payname] += $row->total;
			}
		}

		// normalize datasets by creating a null value for each missing payment
		foreach ($data as $key => $payments)
		{
			foreach ($fetched as $payname)
			{
				if (!isset($payments[$payname]))
				{
					$data[$key][$payname] = 0;
				}
			}
		}

		return $data;
	}

	/**
	 * Calculates a few statistics about the used coupon codes.
	 *
	 * @param 	mixed    $from  The from date object or string.
	 * @param 	mixed    $to    The to date object or string.
	 *
	 * @return 	mixed
	 */
	public static function getCouponsData($from = null, $to = null)
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

		$data = array();

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $format = null, $parent = true),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to),
		);

		$dbo = JFactory::getDbo();

		foreach ($queries as $i => $q)
		{
			// use the correct column according to the selected group
			$k = $i == 0 ? 'coupon_str' : 'coupon';

			// group orders by coupon
			$q->select($dbo->qn('o.' . $k));
			$q->group($dbo->qn('o.' . $k));

			// exclude empty coupon codes
			$q->where($dbo->qn('o.' . $k) . ' <> ' . $dbo->q(''));
			$q->where($dbo->qn('o.' . $k) . ' IS NOT NULL');

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				// get coupon string chunks and sanitize them for a correct comparison, because
				// certain coupon codes might store the amount value with the decimals and other
				// without them
				list($coupon_code, $coupon_type, $coupon_amount) = explode(';;', $row->{$k});
				$coupon = implode(';;', [$coupon_code, (int) $coupon_type, (float) $coupon_amount]);

				if (!isset($data[$coupon]))
				{
					// init coupon if not set yet
					$data[$coupon] = array(
						'code'  => $coupon_code,
						'type'  => (int) $coupon_type,
						'value' => (float) $coupon_amount,
					);
				}

				// iterate each column to map
				foreach ($row as $colName => $colValue)
				{
					if ($colName === $k)
					{
						continue;
					}

					// make sure the specified column exists
					if (!isset($data[$coupon][$colName]))
					{
						// init value
						$data[$coupon][$colName] = 0;
					}

					// increase the specified column
					$data[$coupon][$colName] += $colValue;
				}
			}
		}

		return $data;
	}

	/**
	 * Loads the trend of the coupon codes.
	 *
	 * @param 	mixed    $from    The from date object or string.
	 * @param 	mixed    $to      The to date object or string.
	 * @param 	mixed    $coupon  Either a coupon code or an array.
	 *
	 * @return 	mixed
	 */
	public static function getCouponsTrend($from, $to, $coupon = null)
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

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $sql_format, $parent = true),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to, $sql_format),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to, $sql_format),
		);

		foreach ($queries as $i => $q)
		{
			// use the correct column according to the selected group
			$k = $i == 0 ? 'coupon_str' : 'coupon';

			// take only the orders with a used coupon code
			$q->where($dbo->qn('o.' . $k) . ' <> ' . $dbo->q(''));
			$q->where($dbo->qn('o.' . $k) . ' IS NOT NULL');

			if ($coupon)
			{
				// then group orders by coupon
				$q->select(sprintf(
					'SUBSTRING_INDEX(%s, \';;\', 1) AS %s',
					$dbo->qn('o.' . $k),
					$dbo->qn('coupon')
				));
				$q->group($dbo->qn('coupon'));

				// take only the selected coupon codes
				$q->having($dbo->qn('coupon') . ' IN (' . implode(',', array_map(array($dbo, 'q'), (array) $coupon)) . ')');
			}

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				// convert query date into our date format
				$key = JHtml::fetch('date', $row->date, $label_format);

				if (!isset($data[$key]))
				{
					// something went wrong, label not found...
					continue;
				}

				$cpn = empty($row->coupon) ? 0 : $row->coupon;

				if (!isset($data[$key][$cpn]))
				{
					// init total for this coupon
					$data[$key][$cpn] = 0;
				}

				// increase the total of the specified coupon
				$data[$key][$cpn] += $row->discount;
			}
		}

		if ($coupon)
		{
			$fetched = (array) $coupon;
		}
		else
		{
			$fetched = array(0);
		}

		// normalize datasets by creating a null value for each missing coupon
		foreach ($data as $key => $coupons)
		{
			foreach ($fetched as $cpn)
			{
				if (!isset($coupons[$cpn]))
				{
					$data[$key][$cpn] = 0;
				}
			}
		}

		return $data;
	}

	/**
	 * Calculates the average number of received orders.
	 *
	 * @param 	string   $group     How the orders should be grouped.
	 * @param 	mixed    $from      The from date object or string.
	 * @param 	mixed    $to        The to date object or string.
	 * @param 	mixed    $column    Either an array of columns or a single one.
	 *
	 * @return 	mixed
	 */
	public static function getAvg($group, $from = null, $to = null, $column = 'total')
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

		$data = array();
		
		if ($group == 'month')
		{
			// group by month in SQL query
			$sql_format = '%Y-%m';
		}
		else
		{
			// group by day in SQL query
			$sql_format = '%Y-%m-%d';
		}

		$queries = array(
			// build query to fetch appointments total
			static::buildRevenueQuery('appointments', $from, $to, $sql_format),
			// build query to fetch packages total
			static::buildRevenueQuery('packages', $from, $to, $sql_format),
			// build query to fetch subscriptions total
			static::buildRevenueQuery('subscriptions', $from, $to, $sql_format),
		);

		$dbo = JFactory::getDbo();

		foreach ($queries as $q)
		{
			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $row)
			{
				// group by date
				$key = $row->date;

				if (!isset($data[$key]))
				{
					$data[$key] = array();
				}

				// iterate each column to map
				foreach ($row as $colName => $colValue)
				{
					if (!is_numeric($colValue))
					{
						continue;
					}

					// make sure the specified column exists
					if (!isset($data[$key][$colName]))
					{
						$data[$key][$colName] = 0;
					}

					// increase the specified column
					$data[$key][$colName] += $colValue;
				}
			}
		}

		if ($group == 'month')
		{
			$curr_month = JHtml::fetch('date', 'now', 'Y-m');

			// check whether the current month is set
			if (array_key_exists($curr_month, $data))
			{
				// get current day and last day in month
				$curr_day = (int) JHtml::fetch('date', 'now', 'j');
				$last_day = (int) JHtml::fetch('date', 'now', 't');

				// proportionally recalculate the totals for the current month
				foreach ($data[$curr_month] as $colName => $colValue)
				{
					// CURRENT_DAY : TOTAL_EARNED = LAST_DAY : PROP_EARNING
					// PROP_EARNING = TOTAL_EARNED * LAST_DAY / CURRENT_DAY
					$data[$curr_month][$colName] = round($colValue * $last_day / $curr_day, 2);
				}
			}
		}
		else
		{
			$curr_day = JHtml::fetch('date', 'now', 'Y-m-d');

			// check whether the current day is set
			if (array_key_exists($curr_day, $data))
			{
				// get current hour
				$curr_hour = (int) JHtml::fetch('date', 'now', 'G');

				// proportionally recalculate the totals for the current day
				foreach ($data[$curr_day] as $colName => $colValue)
				{
					// CURRENT_HOUR : TOTAL_EARNED = LAST_HOUR : PROP_EARNING
					// PROP_EARNING = TOTAL_EARNED * LAST_HOUR / CURRENT_HOUR
					$data[$curr_day][$colName] = round($colValue * 24 / $curr_hour, 2);
				}
			}
		}

		$result = array();

		// sum group sub-totals
		foreach ($data as $key => $totals)
		{
			// iterate columns
			foreach ((array) $column as $colName)
			{
				if (!isset($totals[$colName]))
				{
					// column not found, skip
					continue;
				}

				// init group if not created yet
				if (!isset($result[$colName]))
				{
					$result[$colName] = 0;
				}

				$result[$colName] += $totals[$colName];
			}
		}

		// calculate average for each total
		foreach ($result as $k => $v)
		{
			$result[$k] = $v / count($data);
		}

		if (!is_array($column))
		{
			// not an array, just take the specified column value
			$result = reset($result);
		}

		return $result;
	}
}

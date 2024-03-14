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
 * Trait used to share some query across different classes.
 *
 * @since 1.7
 */
trait VAPStatisticsHelperCommonQuery
{
	/**
	 * Builds the query used to fetch the revenue coming from a specific section.
	 *
	 * @param 	string   $group    The group name (appointments, packages, subscriptions).
	 * @param 	mixed    $from     The from date object or null to ignore this filter.
	 * @param 	mixed    $to       The to date object or null to ignore this filter.
	 * @param 	string   $format   The format to use to group the dates.
	 * @param 	boolean  $parent   True to take the parent orders in place of the children.
	 *                             Applies only for appointments group.
	 * @param 	boolean  $checkin  True to take the check-in date in place of the creation date.
	 *                             Applies only for appointments group.
	 *
	 * @return 	mixed    Either a query builder object or a SQL string.
	 */
	public static function buildRevenueQuery($group, $from = null, $to = null, $format = null, $parent = false, $checkin = false)
	{
		$dbo = JFactory::getDbo();

		// build query
		$q = $dbo->getQuery(true);

		if (!is_null($format))
		{
			// Convert creation date into a format compatible with the selected range for a correct grouping.
			// We also need to adjust the UTC dates into the current timezone, otherwise certain orders might
			// belong to a wrong group.

			if ($checkin && $group == 'appointments')
			{
				// filter by check-in date
				$q->select(sprintf(
					'DATE_FORMAT(CONVERT_TZ(%s, \'+00:00\', IFNULL(%s, \'%s\')), \'%s\') AS %s',
					// take checkin-date time
					$dbo->qn('o.checkin_ts'),
					// adjust it to the related timezone
					$dbo->qn('o.tz_offset'),
					// or use the current one if not specified
					JHtml::fetch('date', 'now', 'P'),
					// use the specified date format
					$format,
					// and create alias
					$dbo->qn('date')
				));
			}
			else
			{
				// filter by creation date
				$q->select(sprintf(
					'DATE_FORMAT(CONVERT_TZ(%s, \'+00:00\', \'%s\'), \'%s\') AS %s',
					$dbo->qn('o.createdon'),
					JHtml::fetch('date', 'now', 'P'),
					$format,
					$dbo->qn('date')
				));
			}

			// group records by date
			$q->group($dbo->qn('date'));
		}
		
		// count number of records
		$q->select(sprintf('COUNT(%s) AS %s', $dbo->qn('o.id'), $dbo->qn('count')));

		// sum totals
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.total_cost'), $dbo->qn('total')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.total_tax'), $dbo->qn('tax')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.total_net'), $dbo->qn('net')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.discount'), $dbo->qn('discount')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.payment_charge'), $dbo->qn('payment')));

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array($group => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		if ($from)
		{
			if ($checkin && $group == 'appointments')
			{
				// take orders with check-in higher than the specified starting date
				$q->where($dbo->qn('o.checkin_ts') . ' >= ' . $dbo->q($from->toSql()));
			}
			else
			{
				// take orders with creation date higher than the specified starting date
				$q->where($dbo->qn('o.createdon') . ' >= ' . $dbo->q($from->toSql()));
			}
		}

		if ($to)
		{
			if ($checkin && $group == 'appointments')
			{
				// take orders with check-in lower than the specified ending date
				$q->where($dbo->qn('o.checkin_ts') . ' <= ' . $dbo->q($to->toSql()));
			}
			else
			{
				// take orders with creation date lower than the specified ending date
				$q->where($dbo->qn('o.createdon') . ' <= ' . $dbo->q($to->toSql()));
			}
		}

		if ($group == 'appointments')
		{
			// load appointments
			$q->from($dbo->qn('#__vikappointments_reservation', 'o'));

			// always exclude closures
			$q->where($dbo->qn('o.closure') . ' = 0');

			if ($parent)
			{
				// exclude children
				$q->andWhere(array(
					$dbo->qn('o.id_parent') . ' <= 0',
					$dbo->qn('o.id_parent') . ' = ' . $dbo->qn('o.id'),
				), 'OR');
			}
			else
			{
				// exclude parent orders
				$q->where($dbo->qn('o.id_parent') . ' > 0');
			}
		}
		else if ($group == 'packages')
		{
			// load packages
			$q->from($dbo->qn('#__vikappointments_package_order', 'o'));
		}
		else if ($group == 'subscriptions')
		{
			// load subscriptions
			$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
		}
		else
		{
			// the specified group is not supported
			throw new UnexpectedValueException(sprintf('Group [%s] not supported', $group), 400);
		}

		return $q;
	}

	/**
	 * Builds the query used to fetch the total number of status codes.
	 *
	 * @param 	string   $group   The group name (appointments, packages, subscriptions).
	 * @param 	mixed    $from    The from date object or null to ignore this filter.
	 * @param 	mixed    $to      The to date object or null to ignore this filter.
	 *
	 * @return 	mixed    Either a query builder object or a SQL string.
	 */
	public static function buildStatusCountQuery($group, $from = null, $to = null)
	{
		$dbo = JFactory::getDbo();

		// build query
		$q = $dbo->getQuery(true);

		// select status code
		$q->select($dbo->qn('o.status'));

		// group records by status
		$q->group($dbo->qn('o.status'));
		
		// count number of records
		$q->select(sprintf('COUNT(%s) AS %s', $dbo->qn('o.id'), $dbo->qn('count')));

		if ($from)
		{
			// take orders higher than the specified starting date
			$q->where($dbo->qn('o.createdon') . ' >= ' . $dbo->q($from->toSql()));
		}

		if ($to)
		{
			// take orders lower than the specified ending date
			$q->where($dbo->qn('o.createdon') . ' <= ' . $dbo->q($to->toSql()));
		}

		if ($group == 'appointments')
		{
			// load appointments
			$q->from($dbo->qn('#__vikappointments_reservation', 'o'));

			// always exclude closures
			$q->where($dbo->qn('o.closure') . ' = 0');

			// exclude parent orders
			$q->where($dbo->qn('o.id_parent') . ' > 0');
		}
		else if ($group == 'packages')
		{
			// load packages
			$q->from($dbo->qn('#__vikappointments_package_order', 'o'));
		}
		else if ($group == 'subscriptions')
		{
			// load subscriptions
			$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
		}
		else
		{
			// the specified group is not supported
			throw new UnexpectedValueException(sprintf('Group [%s] not supported', $group), 400);
		}

		return $q;
	}

	/**
	 * Builds the query used to fetch the most booked times for the appointments.
	 *
	 * @param 	mixed    $from  The from date object or null to ignore this filter.
	 * @param 	mixed    $to    The to date object or null to ignore this filter.
	 *
	 * @return 	mixed    Either a query builder object or a SQL string.
	 */
	public static function buildCheckinTimesQuery($from = null, $to = null)
	{
		$dbo = JFactory::getDbo();

		// build query
		$q = $dbo->getQuery(true);

		// Convert check-in date into a format compatible with the selected range for a correct grouping.
		// We also need to adjust the UTC dates into the current timezone, otherwise certain orders might
		// belong to a wrong group.
		$q->select(sprintf(
			'DATE_FORMAT(CONVERT_TZ(%s, \'+00:00\', IFNULL(%s, \'%s\')), \'%%H\') AS %s',
			// take checkin-date time
			$dbo->qn('o.checkin_ts'),
			// adjust it to the related timezone
			$dbo->qn('o.tz_offset'),
			// or use the current one if not specified
			JHtml::fetch('date', 'now', 'P'),
			// and create alias
			$dbo->qn('hour')
		));

		// group records by hour
		$q->group($dbo->qn('hour'));
		
		// sum number of participants
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('o.people'), $dbo->qn('count')));

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		if ($from)
		{
			// take orders higher than the specified starting date
			$q->where($dbo->qn('o.checkin_ts') . ' >= ' . $dbo->q($from->toSql()));
		}

		if ($to)
		{
			// take orders lower than the specified ending date
			$q->where($dbo->qn('o.checkin_ts') . ' <= ' . $dbo->q($to->toSql()));
		}

		// load appointments
		$q->from($dbo->qn('#__vikappointments_reservation', 'o'));

		// always exclude closures
		$q->where($dbo->qn('o.closure') . ' = 0');

		// exclude parent orders
		$q->where($dbo->qn('o.id_parent') . ' > 0');

		// sort by hours
		$q->order($dbo->qn('hour') . ' ASC');

		return $q;
	}

	/**
	 * Builds the query used to fetch the revenue coming from the specified packages.
	 *
	 * @param 	mixed   $from      The from date object or null to ignore this filter.
	 * @param 	mixed   $to        The to date object or null to ignore this filter.
	 * @param 	mxied   $packages  Either a package ID or an array.
	 * @param 	string  $format    The format to use to group the dates.
	 *
	 * @return 	mixed   Either a query builder object or a SQL string.
	 */
	public static function buildPackagesRevenueQuery($from = null, $to = null, $packages = null, $format = null)
	{
		$dbo = JFactory::getDbo();

		// build query
		$q = $dbo->getQuery(true);

		if (!is_null($format))
		{
			// Convert creation date into a format compatible with the selected range for a correct grouping.
			// We also need to adjust the UTC dates into the current timezone, otherwise certain orders might
			// belong to a wrong group.
			$q->select(sprintf(
				'DATE_FORMAT(CONVERT_TZ(%s, \'+00:00\', \'%s\'), \'%s\') AS %s',
				$dbo->qn('o.createdon'),
				JHtml::fetch('date', 'now', 'P'),
				$format,
				$dbo->qn('date')
			));

			// group records by date
			$q->group($dbo->qn('date'));
		}

		// select packages
		$q->select($dbo->qn('i.id_package'));
		// group by package ID
		$q->group($dbo->qn('i.id_package'));
		
		// count number of records
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('i.quantity'), $dbo->qn('count')));

		// sum totals
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('i.gross'), $dbo->qn('total')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('i.tax'), $dbo->qn('tax')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('i.net'), $dbo->qn('net')));
		$q->select(sprintf('SUM(%s) AS %s', $dbo->qn('i.discount'), $dbo->qn('discount')));

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		if ($from)
		{
			// take orders with creation date higher than the specified starting date
			$q->where($dbo->qn('o.createdon') . ' >= ' . $dbo->q($from->toSql()));
		}

		if ($to)
		{
			// take orders with creation date lower than the specified ending date
			$q->where($dbo->qn('o.createdon') . ' <= ' . $dbo->q($to->toSql()));
		}

		// load packages
		$q->from($dbo->qn('#__vikappointments_package_order', 'o'));
		$q->leftjoin($dbo->qn('#__vikappointments_package_order_item', 'i') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('i.id_order'));

		if ($packages)
		{
			// take only the specified packages
			$q->where($dbo->qn('i.id_package') . ' IN (' . implode(',', array_map('intval', (array) $packages)) . ')' );
		}

		return $q;
	}

	/**
	 * Builds the query used to fetch the revenue coming from a specific section.
	 *
	 * @param 	string   $group    The group name (appointments, packages, subscriptions).
	 * @param 	mixed    $from     The from date object or null to ignore this filter.
	 * @param 	mixed    $to       The to date object or null to ignore this filter.
	 * @param 	string   $format   The format to use to group the dates.
	 * @param 	boolean  $parent   True to take the parent orders in place of the children.
	 *                             Applies only for appointments group.
	 * @param 	boolean  $checkin  True to take the check-in date in place of the creation date.
	 *                             Applies only for appointments group.
	 *
	 * @return 	mixed    Either a query builder object or a SQL string.
	 */
	public static function buildNewCustomersQuery($group, $from = null, $to = null, $format = null)
	{
		$dbo = JFactory::getDbo();

		// build query
		$q = $dbo->getQuery(true);

		if (!is_null($format))
		{
			// Convert creation date into a format compatible with the selected range for a correct grouping.
			// We also need to adjust the UTC dates into the current timezone, otherwise certain orders might
			// belong to a wrong group.
			$q->select(sprintf(
				'DATE_FORMAT(CONVERT_TZ(%s, \'+00:00\', \'%s\'), \'%s\') AS %s',
				$dbo->qn('sub.firstPurchaseDate'),
				JHtml::fetch('date', 'now', 'P'),
				$format,
				$dbo->qn('date')
			));

			// group records by date
			$q->group($dbo->qn('date'));
		}
		
		// count number of records
		$q->select(sprintf('COUNT(%s) AS %s', $dbo->qn('sub.id_user'), $dbo->qn('count')));

		// create INNER query
		$inner = $dbo->getQuery(true);

		// find first purchase date
		$inner->select(sprintf('MIN(%s) AS %s', $dbo->qn('o.createdon'), $dbo->qn('firstPurchaseDate')));
		$inner->select($dbo->qn('o.id_user'));

		// exclude guest users
		$inner->where($dbo->qn('o.id_user') . ' > 0');

		// group records by customer
		$inner->group($dbo->qn('o.id_user'));

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array($group => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$inner->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		if ($from)
		{
			// take orders with creation date higher than the specified starting date
			$inner->having($dbo->qn('firstPurchaseDate') . ' >= ' . $dbo->q($from->toSql()));
		}

		if ($to)
		{
			// take orders with creation date lower than the specified ending date
			$inner->having($dbo->qn('firstPurchaseDate') . ' <= ' . $dbo->q($to->toSql()));
		}

		if ($group == 'appointments')
		{
			// load appointments
			$inner->from($dbo->qn('#__vikappointments_reservation', 'o'));

			// always exclude closures
			$inner->where($dbo->qn('o.closure') . ' = 0');

			// exclude parent orders
			$inner->where($dbo->qn('o.id_parent') . ' > 0');
		}
		else if ($group == 'packages')
		{
			// load packages
			$inner->from($dbo->qn('#__vikappointments_package_order', 'o'));
		}
		else if ($group == 'subscriptions')
		{
			// load subscriptions
			$inner->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
		}
		else
		{
			// the specified group is not supported
			throw new UnexpectedValueException(sprintf('Group [%s] not supported', $group), 400);
		}

		// load inner table records
		$q->from(sprintf('(%s) AS %s', $inner, $dbo->qn('sub')));

		return $q;
	}
}

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
 * Helper class used to fetch dashboard data.
 *
 * @since 1.7
 */
abstract class VAPStatisticsHelperDashboard
{
	/**
	 * Returns a list containing the latest registered appointments.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getLatestAppointments($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('r.*')
			->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('r.status'),
				$dbo->qn('e.timezone'),
			))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'))
			->where(array(
				$dbo->qn('r.id_parent') . ' > 0',
				$dbo->qn('r.closure') . ' = 0',
			))
			->order($dbo->qn('r.id') . ' DESC');

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the next incoming appointments.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getIncomingAppointments($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('r.*')
			->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('r.status'),
				$dbo->qn('e.timezone'),
			))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'))
			->where(array(
				$dbo->qn('r.id_parent') . ' <> -1',
				$dbo->qn('r.closure') . ' = 0',
				$dbo->qn('r.checkin_ts') . ' > ' . $dbo->q(JFactory::getDate()->toSql()),
			))
			->order($dbo->qn('r.checkin_ts') . ' ASC');

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the current appointments.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getCurrentAppointments($limit = 5)
	{
		$dbo = JFactory::getDbo();

		// create expression to calculate the check-out date via SQL
		$out = sprintf(
			'DATE_ADD(%s, INTERVAL (%s + %s) MINUTE)',
			$dbo->qn('r.checkin_ts'),
			$dbo->qn('r.duration'),
			$dbo->qn('r.sleep')
		);

		$now = JFactory::getDate()->toSql();

		$q = $dbo->getQuery(true)
			->select('r.*')
			->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('r.status'),
				$dbo->qn('e.timezone'),
			))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'))
			->where(array(
				$dbo->qn('r.id_parent') . ' <> -1',
				$dbo->qn('r.closure') . ' = 0',
				$dbo->qn('r.checkin_ts') . ' <= ' . $dbo->q($now),
				$dbo->q($now) . ' < ' . $out,
			))
			->order($dbo->qn('r.checkin_ts') . ' ASC');

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the latest customers that subscribed into
	 * a waiting list.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getLatestWaitlistSubscriptions($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('w.*')
			->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('u.name', 'user_name'),
			))
			->from($dbo->qn('#__vikappointments_waitinglist', 'w'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('w.jid') . ' = ' . $dbo->qn('u.id'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('w.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('e.id'))
			->order($dbo->qn('w.id') . ' DESC');

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the closest customers subscribed into
	 * a waiting list.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getIncomingWaitlistSubscriptions($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$today = JFactory::getDate();

		$q = $dbo->getQuery(true)
			->select('w.*')
			->select(array(
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('u.name', 'user_name'),
			))
			->from($dbo->qn('#__vikappointments_waitinglist', 'w'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('w.jid') . ' = ' . $dbo->qn('u.id'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('w.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('e.id'))
			->where($dbo->qn('w.timestamp') . ' >= ' . $dbo->q($today->format('Y-m-d')))
			->order($dbo->qn('w.timestamp') . ' ASC');

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the latest purchased packages (orders).
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getLatestPurchasedPackages($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('o.*')
			->from($dbo->qn('#__vikappointments_package_order', 'o'))
			->order($dbo->qn('o.id') . ' DESC');

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the latest redeemed packages.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getLatestUsedPackages($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('o.*')
			->select(array(
				$dbo->qn('p.name', 'package_name'),
				$dbo->qn('a.modifiedon'),
				$dbo->qn('a.used_app'),
				$dbo->qn('a.num_app'),
			))
			->from($dbo->qn('#__vikappointments_package_order', 'o'))
			->leftjoin($dbo->qn('#__vikappointments_package_order_item', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_order'))
			->leftjoin($dbo->qn('#__vikappointments_package', 'p') . ' ON ' . $dbo->qn('p.id') . ' = ' . $dbo->qn('a.id_package'))
			->where($dbo->qn('a.used_app') . ' > 0')
			->order($dbo->qn('a.modifiedon') . ' DESC');

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1)); 

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the latest customers that registered
	 * an appointment for the first time.
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getLatestRegisteredCustomers($limit = 5)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('u.*')
			->from($dbo->qn('#__vikappointments_users', 'u'))
			->where($dbo->qn('u.billing_name') . ' <> ' . $dbo->q(''))
			->order($dbo->qn('u.id') . ' DESC');

		$dbo->setQuery($q, 0, (int) $limit);
		return $dbo->loadAssocList();
	}

	/**
	 * Returns a list containing the currently logged in users. 
	 *
	 * @param 	integer  $limit  The maximum number of records to fetch.
	 *
	 * @return 	array
	 */
	public static function getCurrentLoggedUsers($limit = 5)
	{
		return VAPApplication::getInstance()->getLoggedUsers($limit);
	}
}

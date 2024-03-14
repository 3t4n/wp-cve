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
 * VikAppointments subscriptions class handler.
 *
 * @since 1.7
 */
abstract class VAPSubscriptions
{
	/**
	 * A list of cached subscriptions, grouped by section:
	 * - 0 for customers;
	 * - 1 for employees.
	 *
	 * @var array
	 */
	protected static $subscriptions = array();

	/**
	 * Returns the group that whould be immediately highlighted when
	 * the user starts a new session. The preferred group is always
	 * based on the latest ordered subscription.
	 *
	 * @return 	integer  The preferred group (0: customers, 1: employees).
	 */
	public static function getPreferredGroup()
	{
		static $preferred = null;

		// fetche preferred group only once
		if (is_null($preferred))
		{
			// use the default customers group
			$preferred = 0;

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_employee'))
				->from($dbo->qn('#__vikappointments_subscr_order'))
				->order($dbo->qn('id') . ' DESC');

			$dbo->setQuery($q, 0, 1);
			
			// check whether the latest order is assigned to an employee
			if ((int) $dbo->loadResult() > 0)
			{
				// use the employees group
				$preferred = 1;
			}
		}

		// return cached version
		return $preferred;
	}

	/**
	 * Checks if there is at least a published subscription for the given group.
	 *
	 * @param 	integer  $group  The group to use (0: customers, 1: employees).
	 *
	 * @return 	boolean  True if any, false otherwise.
	 *
	 * @uses 	search()
	 */
	public static function has($group = 0)
	{
		return (bool) static::search(array('published' => 1, 'group' => (int) $group), $limit = 1, $translate = false);
	}
	
	/**
	 * Returns the trial subscription (if any).
	 *
	 * @param 	integer  $group      The group to use (0: customers, 1: employees).
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	mixed    The trial subscription array if exists, false otherwise.
	 *
	 * @uses 	search()
	 */
	public static function getTrial($group = 0, $translate = true)
	{
		return static::search(array('published' => 1, 'trial' => 1, 'group' => (int) $group), $limit = 1, $translate);
	}
	
	/**
	 * Returns a list of active subscriptions.
	 *
	 * @param 	integer  $group      The group to use (0: customers, 1: employees).
	 * @param 	boolean  $trial      True to include the trial subscription, false otherwise.
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array    A list of published subscriptions.
	 *
	 * @uses 	search()
	 */
	public static function getList($group = 0, $trial = false, $translate = true)
	{
		return static::search(array('published' => 1, 'trial' => (int) $trial, 'group' => (int) $group), $limit = null, $translate);
	}

	/**
	 * Returns the details of the given subscription.
	 *
	 * @param 	integer  $group      The group to use (0: customers, 1: employees).
	 * @param 	integer  $id 		 The subscription ID.
	 * @param 	boolean  $strict 	 True to get the subscription only if it is published.
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The subscription array if exists, false otherwise.
	 *
	 * @uses 	search()
	 */
	public static function get($id, $group = 0, $strict = true, $translate = true)
	{
		$where = array(
			'id'    => (int) $id,
			'group' => (int) $group,
		);

		if ($strict)
		{
			$where['published'] = 1;
		}

		return static::search($where, $limit = 1, $translate);
	}

	/**
	 * Returns a list of subscriptions matching the given query.
	 *
	 * @param 	array  	 $where 	 An associative array containing the query terms.
	 * @param 	integer  $lim 		 The number of records to retrieve. Null to ignore this value.
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The subscriptions list. The associative array of the subscription in case
	 *                   $lim is equals to 1 (false in case the subscription does not exist).
	 */
	public static function search(array $where = array(), $lim = null, $translate = false)
	{
		// in case the group is not specified, use the default one,
		// because it doesn't make sense to load both them
		$group = 0;

		if (isset($where['group']))
		{
			$group = (int) $where['group'];
			// unset from query to avoid redundant comparisons
			unset($where['group']);
		}

		if (!isset(static::$subscriptions[$group]))
		{
			static::$subscriptions[$group] = array();

			// lazy load all the subscriptions
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_subscription'))
				->where($dbo->qn('group') . ' = ' . (int) $group)
				->order($dbo->qn('ordering') . ' ASC');
			
			$dbo->setQuery($q);
			
			foreach ($dbo->loadAssocList() as $subscr)
			{
				// decode services list
				$subscr['services'] = $subscr['services'] ? array_values(array_filter(explode(',', $subscr['services']))) : [];

				// register subscriptions
				static::$subscriptions[$group][] = $subscr;
			}
		}

		$matching = array();

		// find all subscriptions that match the query
		foreach (static::$subscriptions[$group] as $subscr)
		{
			// validate subscription against search query
			if (static::match($subscr, $where))
			{
				// matching record, register it
				$matching[] = $subscr;
			}
		}

		if (!$matching)
		{
			if ($lim == 1)
			{
				// return false in case the limit is equals to 1
				return false;
			}

			// return an empty array
			return array();
		}

		if ($lim)
		{
			// splice the array to keep only the records between the given limit
			$matching = array_splice($matching, 0, $lim);
		}

		if ($translate)
		{
			// translate subscriptions
			VikAppointments::translateSubscriptions($matching);
		}

		if ($lim == 1)
		{
			// return the first element of the list
			return $matching[0];
		}

		// return list of subscriptions
		return $matching;
	}

	/**
	 * Checks whether the specified subscription matches the query.
	 * 
	 * @param 	array    $subscription  The subscription details.
	 * @param 	array    $where         An associative array containing the query terms.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	protected static function match($subscription, $where)
	{
		foreach ($where as $k => $v)
		{
			// compare only in case the subscription array owns the current property
			if (isset($subscription[$k]) && $subscription[$k] != $v)
			{
				// the values do not match
				return false;
			}
		}

		// all the values seem to match
		return true;
	}
}

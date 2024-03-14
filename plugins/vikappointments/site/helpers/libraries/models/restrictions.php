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
 * VikAppointments special restrictions class handler.
 *
 * @since 1.6.5
 */
abstract class VAPSpecialRestrictions
{
	/**
	 * Map used to cache the user appointments.
	 *
	 * @var array
	 */
	protected static $orders = array();

	/**
	 * Checks whether the current user is still allowed
	 * to book the selected service.
	 *
	 * @param 	integer  $id 	   The service ID.
	 * @param 	integer  $checkin  The check-in UNIX timestamp.
	 * @param 	mixed    &$fail    It is possible to use this reference
	 * 							   to obtain the restriction that failed.
	 * @param 	boolean  $add      True if the service has to be added, otherwise
	 * 							   false is the service is already in the cart.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public static function canBookService($id, $checkin, &$fail = null, $add = true)
	{
		// get compatible restrictions
		$restrictions = static::getRestrictions($id);

		if (!$restrictions)
		{
			// no restrictions available, booking allowed
			return true;
		}

		// iterate restriction
		foreach ($restrictions as $restr)
		{
			// make sure the user didn't reach yet the thereshold
			if (!static::validateRestriction($restr, $checkin, $add))
			{
				// assign restriction to $fail argument
				$fail = $restr;

				// threshold reached, not allowed to book
				return false;
			}
		}

		// booking allowed
		return true;
	}

	/**
	 * Validates the restriction against the user details.
	 *
	 * @param 	object 	 $restr    The restriction object.
	 * @param 	string   $checkin  The check-in in military format.
	 * @param 	boolean  $add      True if the service has to be added, otherwise
	 * 							   false is the service is already in the cart.
	 *
	 * @return 	boolean  True in case the booking is allowed, false otherwise.
	 */
	public static function validateRestriction($restr, $checkin, $add = true)
	{
		if ($restr->mode == 1)
		{
			// use current date
			$checkin = 'now';
			$column  = 'createdon';
		}
		else
		{
			// use check-in date
			$column = 'checkin_ts';
		}

		// get checkin date details
		$date = JFactory::getDate($checkin);

		// get current user
		$user = JFactory::getUser();

		if ($user->guest)
		{
			// in case of guest user, immediately abort
			return false;
		}

		// get appointments of current user
		$orders = static::getAppointments($user->id);

		$count = 0;

		// count orders that match the specified interval
		foreach ($orders as $o)
		{
			// make sure the service should be considered by this restriction
			if (!$restr->services || in_array($o->id_service, $restr->services))
			{
				// service ok, make sure the checkin is within the
				// interval defined by the restriction

				$range = new stdClass;
				$range->start = clone $date;
				$range->end   = clone $date;

				switch ($restr->interval)
				{
					case 'day':
						$range->start->modify('00:00:00');
						$range->end->modify('23:59:59');
						break;

					case 'week':
						// week starts from Monday
						$w = (int) $date->format('w');
						$w = ($w == 0 ? 7 : $w) - 1;

						$range->start->modify('-' . $w . ' days 00:00:00');
						$range->end->modify('-' . $w . ' days 23:59:59');
						$range->end->modify('+6 days');
						break;

					case 'week2':
						// week starts from Monday
						$w = (int) $date->format('w');
						$w = ($w == 0 ? 7 : $w) - 1;

						// get week of the year and check if even/odd
						if ((int) $date->format('W') % 2 == 0)
						{
							// subtract 7 days in case of even week (go to previous week)
							$sub = 7;
							// extend to the end of the current week
							$add = 6;
						}
						else
						{
							// go to the beginning of the current week
							$sub = 0;
							// exetend to the end of the next week
							$add = 13;
						}

						$range->start->modify('-' . ($w + $sub) . ' days 00:00:00');
						$range->end->modify('-' . $w . ' days 23:59:59');
						$range->end->modify('+' . $add . ' days');
						break;

					case 'month':
						$range->start->modify($date->format('Y-m-01') . ' 00:00:00');
						$range->end->modify($date->format('Y-m-t') . ' 23:59:59');
						break;

					case 'month2':
					case 'month3':
					case 'month4':
					case 'month6':
						// extract index from name
						$index = (int) preg_replace("/^month/", '', $restr->interval);
						// find initial month
						$mon = (int) $date->format('m');
						$mon = $mon - (($mon - 1) % $index);

						$range->start->modify($date->format("Y-{$mon}-01") . ' 00:00:00');
						$range->end = clone $range->start;
						$range->end->modify('+' . ($mon + $index) . ' months');
						$range->end->modify('-1 day 23:59:59');
						break;

					case 'year':
						$range->start->modify('Y-01-01 00:00:00');
						$range->end->modify('Y-12-31 23:59:59');
						break;

					default:
						// abort, restriction interval not supported
						return false;
				}

				// get timestamp
				$ts = $o->{$column};

				if ($range->start->format('Y-m-d H:i:s') <= $ts && $ts <= $range->end->format('Y-m-d H:i:s'))
				{
					// increase counter in the the appointment stays
					// between the fatched interval
					$count++;
				}
			}
		}

		if (!$add)
		{
			// decrease count by one in case the service checked
			// is already in the cart
			$count--;
		}

		// make sure the total count of appointments doesn't
		// exceed the maximum threshold defined by the restriction
		return $count < (int) $restr->maxapp;
	}

	/**
	 * Returns a list of special restrictions to be applied
	 * to the specified service. The method also filters the
	 * restrictions according to the allowed user groups.
	 *
	 * @param 	integer  $id  The service ID.
	 *
	 * @return 	array 	 A list of restrictions.
	 */
	public static function getRestrictions($id)
	{
		static $restrictions = null;

		// load restrictions only once
		if (is_null($restrictions))
		{
			$restrictions = [];

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('r.*')
				->select($dbo->qn('a.id_service'))
				->from($dbo->qn('#__vikappointments_special_restrictions', 'r'))
				->leftjoin($dbo->qn('#__vikappointments_ser_restr_assoc', 'a') . ' ON ' . $dbo->qn('a.id_restriction') . ' = ' . $dbo->qn('r.id'))
				->where($dbo->qn('r.published') . ' = 1')
				->order($dbo->qn('r.maxapp') . ' DESC');

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $r)
			{
				if (!isset($restrictions[$r->id]))
				{
					// convert the usergroups (separated by a comma) into an array
					$r->usergroups = strlen($r->usergroups) ? explode(',', $r->usergroups) : array();
					// init services list
					$r->services = array();

					$restrictions[$r->id] = $r;
				}

				if ($r->id_service)
				{
					$restrictions[$r->id]->services[] = $r->id_service;
				}
			}

			// get rid of keys
			$restrictions = array_values($restrictions);
		}

		$user = JFactory::getUser();

		// filter restrictions by service and user group
		return array_filter($restrictions, function($r) use ($id, $user)
		{
			// make sure the restriction support the service
			if ($r->services && !in_array($id, $r->services))
			{
				// service not supported
				return false;
			}

			// check if the intersection between the arrays returns
			// at least an element (in common)
			if ($r->usergroups && !array_intersect($user->groups, $r->usergroups))
			{
				// user groups not supported
				return false;
			}

			// restriction compatible
			return true;
		});
	}

	/**
	 * Returns the list of all the appointments already
	 * booked by the customer.
	 *
	 * @param 	integer  $id  The user ID.
	 *
	 * @return 	array 	 A list of orders.
	 */
	public static function getAppointments($id_user = null)
	{
		if (is_null($id_user))
		{
			$user = JFactory::getUser();
			// get ID of current user
			$id_user = $user->guest ? 0 : $user->id;
		}

		if (!$id_user)
		{
			// return empty list in case of guest user
			return array();
		}

		if (!isset(static::$orders[$id_user]))
		{
			$dbo = JFactory::getDbo();

			// get any reserved codes
			$reserved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

			$q = $dbo->getQuery(true)
				->select($dbo->qn('r.id'))
				->select($dbo->qn('r.checkin_ts'))
				->select($dbo->qn('r.createdon'))
				->select($dbo->qn('r.id_service'))
				->from($dbo->qn('#__vikappointments_reservation', 'r'))
				->leftjoin($dbo->qn('#__vikappointments_users', 'c') . ' ON ' . $dbo->qn('r.id_user') . ' = ' . $dbo->qn('c.id'))
				// exclude multi-orders (parent)
				->where($dbo->qn('id_parent') . ' <> -1');

			// take only reserved appointments
			if ($reserved)
			{
				// filter by reserved status
				$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $reserved)) . ')');
			}

			// search by author or assigned customer
			$q->andWhere(array(
				$dbo->qn('c.jid') . ' = ' . (int) $id_user,
				'(' . $dbo->qn('r.createdby') . ' = ' . (int) $id_user . ' AND ' . $dbo->qn('c.jid') . ' IS NULL)',
			), 'OR');

			$dbo->setQuery($q);
			static::$orders[$id_user] = $dbo->loadObjectList();
		}

		// load cart instance
		$cart = JModelVAP::getInstance('cart')->getCart();

		$cart_list = array();

		// extract appointments from cart
		foreach ($cart->getItemsList() as $item)
		{
			$tmp = new stdClass;
			$tmp->id_service = $item->getServiceID();
			$tmp->checkin_ts = $item->getCheckinDate();
			$tmp->createdon  = JFactory::getDate();

			$cart_list[] = $tmp;
		}

		// merge customer orders and appointments in cart
		return array_merge(static::$orders[$id_user], $cart_list);
	}
}

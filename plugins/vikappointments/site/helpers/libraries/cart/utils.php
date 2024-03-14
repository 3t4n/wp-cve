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
 * Helper class used to manipulate the items within the cart.
 *
 * @since 1.6
 */
abstract class VAPCartUtils
{
	/**
	 * Sorts the items by service and checkin date.
	 *
	 * @param 	array 	$items 	The items to sort.
	 *
	 * @return 	array 	The sorted items.
	 *
	 * @uses 	quicksort() 
	 */
	public static function sortItemsByServiceDate($items)
	{
		return self::quicksort($items);
	}
	
	/**
	 * Returns the total cost of all the appointments for the given service.
	 *
	 * @param 	array 	 $items  The items list.
	 * @param 	integer  $id 	 The service ID.
	 *
	 * @return 	float 	 The resulting total cost.
	 */
	public static function getServiceTotalCost($items, $id)
	{
		$price = 0;

		for ($i = 0; $i < count($items); $i++)
		{
			if ($items[$i]->getServiceID() == $id)
			{
				$price += $items[$i]->getTotalCost();
			} 			
		}
		
		return $price;
	}

	/**
	 * Categorizes the items array by service.
	 *
	 * @param 	array 	$items 	The items list.
	 *
	 * @return 	array 	The grouped items.
	 *
	 * @since 	1.6
	 */
	public static function groupItemsByService(array $items)
	{
		$groups = array();

		foreach ($items as $item)
		{
			$id_service = $item->getServiceID();

			if (!isset($groups[$id_service]))
			{
				$groups[$id_service] = array(
					'id'	=> $id_service,
					'name' 	=> $item->getName(),
					'total' => self::getServiceTotalCost($items, $id_service),
					'items' => array(),
				);
			}

			$groups[$id_service]['items'][] = $item;
		}

		return $groups;
	}

	/**
	 * Scans the cart items to check whether the booked appointments
	 * have been assigned to the same employee.
	 *
	 * @param 	array    $items  A list of items.
	 *
	 * @return 	boolean  True if the same employee, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function isSameEmployee($items)
	{
		if (!$items)
		{
			// no specified items
			return false;
		}

		// make sure the employee has been selected
		$same = $items[0]->getEmployeeID() > 0;

		// iterate for all the items length and automatically break when
		// we find an employee not equals to the first one
		for ($i = 1; $i < count($items) && $same; $i++)
		{
			$same = $items[$i]->getEmployeeID() == $items[0]->getEmployeeID();
		}

		return $same;
	}

	/**
	 * Extracts all the employees IDs that have been booked.
	 *
	 * @param 	array  $items  A list of items.
	 *
	 * @return 	array  A list of employees.
	 *
	 * @since 	1.7
	 */
	public static function getEmployees($items)
	{
		$arr = array();

		foreach ($items as $item)
		{
			$id_emp = $item->getEmployeeID();

			// include employee ID only if not yet specified and not empty
			if ($id_emp > 0 && !in_array($id_emp, $arr))
			{
				$arr[] = $id_emp;
			}
		}

		return $arr;
	}

	/**
	 * Extracts all the services IDs that have been booked.
	 *
	 * @param 	array  $items  A list of items.
	 *
	 * @return 	array  A list of services.
	 *
	 * @since 	1.7
	 */
	public static function getServices($items)
	{
		$arr = array();

		foreach ($items as $item)
		{
			// include service ID only if not yet specified
			if (!in_array($item->getServiceID(), $arr))
			{
				$arr[] = $item->getServiceID();
			}
		}

		return $arr;
	}

	/**
	 * Returns the total number of participants. The correct number
	 * will be returned only in case all the appointments within the
	 * cart contains the same number of attendees.
	 *
	 * @param 	array    $items  A list of items.
	 *
	 * @return 	integer  The attendees count.
	 *
	 * @since 	1.7
	 */
	public static function getAttendees($items)
	{
		if (!$items)
		{
			return 1;
		}

		for ($i = 1; $i < count($items); $i++)
		{
			if ($items[$i]->getPeople() != $items[$i - 1]->getPeople())
			{
				// at least 2 appointments have different attendees
				return 1;
			}
		}

		// all the appointments share the same number of attendees
		return $items[0]->getPeople();
	}

	/**
	 * Sorts the cart items using QuickSort method.
	 *
	 * @param 	array 	$items 	The items to sort.
	 *
	 * @return 	array 	The sorted items.
	 *
	 * @since 	1.6
	 */
	private static function quicksort($items)
	{
		usort($items, function($a, $b)
		{
			if ($a->getServiceID() > $b->getServiceID())
			{
				return 1;
			}
			else if ($a->getServiceID() < $b->getServiceID())
			{
				return -1;
			}

			if ($a->getCheckinTimestamp() > $b->getCheckinTimestamp())
			{
				return 1;
			}
			else if ($a->getCheckinTimestamp() < $b->getCheckinTimestamp())
			{
				return -1;
			}

			return 0;
		});

		return $items;
	}
}

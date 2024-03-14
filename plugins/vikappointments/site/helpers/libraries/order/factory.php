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
 * Factory class used to retrieve the details of the order belonging
 * to the specified group, such as appointments or packages.
 *
 * @since 1.7
 */
abstract class VAPOrderFactory
{
	/**
	 * A list of cached orders.
	 *
	 * @var array
	 */
	protected static $cache = array();

	/**
	 * Returns the order instance, containing a list of appointments.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The order instance.
	 */
	public static function getAppointments($id, $langtag = null, array $options = array())
	{
		return static::get('appointment', $id, $langtag, $options);
	}

	/**
	 * Returns the package order instance.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The order instance.
	 */
	public static function getPackages($id, $langtag = null, array $options = array())
	{
		return static::get('package', $id, $langtag, $options);
	}

	/**
	 * Returns the employee subscription order instance.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The order instance.
	 */
	public static function getEmployeeSubscription($id, $langtag = null, array $options = array())
	{
		return static::get('empsubscr', $id, $langtag, $options);
	}

	/**
	 * Returns the customer subscription order instance.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The order instance.
	 */
	public static function getCustomerSubscription($id, $langtag = null, array $options = array())
	{
		return static::get('subscr', $id, $langtag, $options);
	}

	/**
	 * Unset cache every time the order details change.
	 *
	 * @param 	string   $group  The group.
	 * @param 	integer  $id     The order ID.
	 *
	 * @return 	void
	 */
	public static function changed($group, $id)
	{
		if (isset(static::$cache[$group][$id]))
		{
			unset(static::$cache[$group][$id]);
		}
	}

	/**
	 * Returns the order instance.
	 *
	 * @param 	string   $group    The group.
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The order instance.
	 *
	 * @throws 	Exception
	 */
	protected static function get($group, $id, $langtag = null, array $options = array())
	{
		$key = $langtag ? $langtag : 'auto';

		// make sure the group is set in the cache pool
		if (!isset(static::$cache[$group]))
		{
			static::$cache[$group] = array();
		}

		// load handler class
		if (!VAPLoader::import('libraries.order.classes.' . $group))
		{
			throw new Exception(sprintf('Order driver [%s] not found', $group), 404);
		}

		// create class name
		$classname = 'VAPOrder' . ucfirst($group);

		// make sure the class handler exists
		if (!class_exists($classname))
		{
			throw new Exception(sprintf('Order class [%s] does not exist', $classname), 404);
		}

		// Check if the instance already exists in the cache pool.
		// Skip cache in case the configuration contains the "ignore_cache" attribute.
		if (!isset(static::$cache[$group][$id][$key]) || !empty($options['ignore_cache']))
		{
			// create a space for the given order to support multiple languages
			if (!isset(static::$cache[$group][$id]))
			{
				static::$cache[$group][$id] = array();
			}

			// retrieve the order for the given language
			$obj = new $classname($id, $langtag, $options);

			if (!empty($options['ignore_cache']))
			{
				// return the object before caching it
				return $obj;
			}

			// retrieve the order for the given language
			static::$cache[$group][$id][$key] = $obj;
		}

		// return a clone of the instance to allow the management
		// of its data without affecting the cached reference
		return clone static::$cache[$group][$id][$key];
	}
}

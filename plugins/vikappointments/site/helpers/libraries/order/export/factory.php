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

VAPLoader::import('libraries.order.export.driver');

/**
 * Factory class used to export the orders by using the specifications 
 * of the requested driver.
 *
 * @since 1.7
 */
abstract class VAPOrderExportFactory
{
	/**
	 * Returns a list of supported drivers.
	 *
	 * @param 	string   $group   The section to which the orders belong.
	 * @param 	boolean  $object  True to return the instances instead of
	 * 							  the title of the drivers.
	 *
	 * @return 	array 	 An associative array of drivers.
	 *
	 * @uses 	getInstance()
	 */
	public static function getSupportedDrivers($group, $object = false)
	{
		$drivers = array();

		// get default drivers
		$files = glob(VAPLIB . DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'drivers' . DIRECTORY_SEPARATOR . '*.php');

		// iterate files
		foreach ($files as $i => $file)
		{
			// keep the filename only
			$files[$i] = basename($file);
		}

		/**
		 * Trigger event to let the plugins register external drivers
		 * as supported exporters.
		 *
		 * @return 	array 	A list of supported drivers.
		 *
		 * @since 	1.7
		 */
		$list = VAPFactory::getEventDispatcher()->trigger('onFetchSupportedExportDrivers');

		foreach ($list as $chunk)
		{
			// merge default files with specified ones
			$files = array_merge($files, (array) $chunk);
		}

		// iterate files
		foreach ($files as $file)
		{
			try
			{
				// try to instantiate the driver
				$driver = static::getInstance($file, $group);

				// make sure the driver supports the group
				if ($driver->isSupported($group))
				{
					if ($object === true)
					{
						// register instance
						$drivers[$driver->getName()] = $driver;
					}
					else
					{
						// register driver title only
						$drivers[$driver->getName()] = $driver->getTitle();
					}
				}
			}
			catch (Exception $e)
			{
				// driver not supported
			}
		}

		// sort drivers alphabetically
		uasort($drivers, function($a, $b)
		{
			if ($a instanceof VAPOrderExportDriver)
			{
				// use title in case of driver instance
				$a = $a->getTitle();
			}
			
			if ($b instanceof VAPOrderExportDriver)
			{
				// use title in case of driver instance
				$b = $b->getTitle();
			}

			// compare as normal strings
			return strcasecmp($a, $b);
		});

		return $drivers;
	}

	/**
	 * Returns the export driver instance, ready for the usage.
	 * Searches for any arguments set in the request required
	 * by the export driver.
	 *
	 * @param 	string 	$driver   The export driver name.
	 * @param 	string  $group    The section to which the orders belong.
	 * @param 	mixed 	$options  Either an array or an object of options to be passed 
	 * 							  to the order instance.
	 *
	 * @return 	VAPOrderExportDriver
	 *
	 * @throws 	Exception
	 *
	 * @uses 	getInstance()
	 */
	public static function getDriver($driver, $group, $options = array())
	{
		// get driver first
		$driver = static::getInstance($driver, $group, $options);

		$input = JFactory::getApplication()->input;

		// iterate form arguments
		foreach ($driver->getForm() as $k => $field)
		{
			// use registry for ease of use
			$field = new JRegistry($field);

			// validate field filter
			if ($field->get('multiple') == 1)
			{
				// retrieve an array
				$filter = 'array';
			}
			else
			{
				// retrieve a string otherwise
				$filter = 'string';
			}

			// get value from request (use the "export_" prefix)
			$value = $input->get('export_' . $k, null, $filter);

			// cast to integer in case of checkbox, 
			// so that we can use "0" instead of NULL
			if ($field->get('type') == 'checkbox')
			{
				$value = (int) $value;
			}

			// make sure we have a value in the request
			if ($value !== null)
			{
				// Push value within the options.
				// Any value previously set will be overwritten.
				$driver->setOption($k, $value);
			}

			// check if the field was mandatory
			if ($field->get('required') == 1)
			{
				// get value from driver configuration
				$value = $driver->getOption($k, null);

				// make sure the value exists
				if ($value === null || $value === array())
				{
					// missing field, throw exception
					throw new Exception(sprintf('Export driver missing required [%s] field', $k), 400);
				}
			}
		}

		return $driver;
	}

	/**
	 * Returns the export driver instance.
	 *
	 * @param 	string 	$driver   The export driver name.
	 * @param 	string  $group    The section to which the orders belong.
	 * @param 	mixed 	$options  Either an array or an object of options to be passed 
	 * 							  to the order instance.
	 *
	 * @return 	VAPOrderExportDriver
	 *
	 * @throws 	Exception
	 */
	public static function getInstance($driver, $group, $options = array())
	{
		// remove file extension if provided
		$driver = preg_replace("/\.php$/", '', $driver);

		/**
		 * Trigger event to let the plugins include external drivers.
		 * The plugins MUST include the resources needed, otherwise
		 * it wouldn't be possible to instantiate the returned classes.
		 *
		 * @param 	string  $driver  The name of the driver to include.
		 *
		 * @return 	string 	The classname of the driver.
		 *
		 * @since 	1.7
		 */
		$classname = VAPFactory::getEventDispatcher()->triggerOnce('onFetchExportDriverClassname', array($driver));

		if (!$classname)
		{
			// load handler class from default folder
			if (!VAPLoader::import('libraries.order.export.drivers.' . $driver))
			{
				throw new Exception(sprintf('Order export driver [%s] not found', $driver), 404);
			}

			// create class name
			$classname = 'VAPOrderExportDriver' . ucfirst($driver);
		}

		// make sure the class handler exists
		if (!class_exists($classname))
		{
			throw new Exception(sprintf('Order export class [%s] does not exist', $classname), 404);
		}

		// instantiate handler
		$driver = new $classname($group, $options);

		// make sure the driver is a valid instance
		if (!$driver instanceof VAPOrderExportDriver)
		{
			throw new Exception(sprintf('Order export class [%s] is not a valid instance', $classname), 500);
		}

		return $driver;
	}
}

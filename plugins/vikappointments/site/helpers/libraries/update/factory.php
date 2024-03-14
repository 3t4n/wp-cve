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

VAPLoader::import('libraries.update.adapter');

/**
 * Factory used to handle the update adapters.
 *
 * ------------------------------------------------------------------------------------
 *
 * Update adapters CLASS name must have the following structure:
 * 
 * "VAPUpdateAdapter" + VERSION (replace dots with underscores)
 * eg. VAPUpdateAdapter1_2_5 (com_vikappointments 1.2.5)
 *
 * ------------------------------------------------------------------------------------
 *
 * Update adapters FILE name must have the following structure:
 * 
 * VERSION (replace dots with underscores) + ".php"
 * eg. 1_2_5.php (com_vikappointments 1.2.5)
 *
 * @since 1.7
 */
class VAPUpdateFactory
{
	/**
	 * Executes the requested method.
	 *
	 * @param 	string 	 $method   The method name to launch.
	 * @param 	string 	 $version  The version to consider.
	 * @param 	mixed 	 $caller   The object that invoked this method.
	 * 
	 * @return 	boolean  True on success, false otherwise.
	 */
	public static function run($method, $version, $caller = null)
	{
		// get all adapters
		$adapters = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'adapters' . DIRECTORY_SEPARATOR . '*.php');

		// iterate each supported version
		foreach ($adapters as $file)
		{
			// get filename
			$filename = preg_replace("/\.php$/i", '', basename($file));

			// get class name of update adapter for current loop version
			$classname = 'VAPUpdateAdapter' . $filename;

			// get version from filename
			$v = preg_replace("/_+/", '.', $filename);

			// in case the software version is lower than loop version
			if (version_compare($version, $v, '<'))
			{
				// load updater adapter file
				$loaded = VAPLoader::import('libraries.update.adapters.' . $filename);

				if ($loaded)
				{
					try
					{
						// check whether the requested class exists
						$reflection = new ReflectionClass($classname);

						// Get method details.
						// In case the method doesn't exist, an exception will be thrown.
						$methodData = $reflection->getMethod($method);

						if ($methodData->isStatic())
						{
							// use static class
							$object = $classname;
						}
						else
						{
							// instantiate object
							$object = new $classname();
						}

						// then run update callback function
						$success = call_user_func(array($object, $method), $caller);

						if ($success === false)
						{
							// stop adapters in case something gone wrong
							return false;
						}
					}
					catch (Exception $e)
					{
						if (!$e instanceof ReflectionException)
						{
							// prompt error message
							JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
						}

						// One of the following errors occurred:
						// - the class does not exist;
						// - the method does not exist;
						// - the launched method thrown an exception.
						$success = false;
					}
				}
			}
		}

		// no error found
		return true;
	}
}

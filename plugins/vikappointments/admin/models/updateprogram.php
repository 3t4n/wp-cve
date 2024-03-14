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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments update program model.
 *
 * @since 1.7
 */
class VikAppointmentsModelUpdateprogram extends JModelVAP
{
	/**
	 * Checks whether there's a registered system able to
	 * handle the updates of the program.
	 *
	 * @return 	boolean
	 */
	public function isSupported()
	{
		/**
		 * Get internal event dispatcher to automatically
		 * include the parameters array, which will be used
		 * to fetch the version of the program.
		 *
		 * @see   VAPFactory
		 *
		 * @since 1.7
		 */
		$dispatcher = VAPFactory::getEventDispatcher();

		// try to get a cached response
		return $dispatcher->is('onUpdaterSupported');
	}

	/**
	 * Checks whether the system registered some details about
	 * the latest version of the program.
	 *
	 * @return 	object   An object containing the resulting details.
	 */
	public function getVersionDetails()
	{
		/**
		 * Get internal event dispatcher to automatically
		 * include the parameters array, which will be used
		 * to fetch the version of the program.
		 *
		 * @see   VAPFactory
		 *
		 * @since 1.7
		 */
		$dispatcher = VAPFactory::getEventDispatcher();

		// try to get a cached response
		return $dispatcher->triggerOnce('onGetVersionContents');
	}

	/**
	 * Validates the current version of VikAppointments against the
	 * latest one stored on the manufacturer servers.
	 *
	 * @param 	boolean  $cache  True to look for a cached response.
	 *
	 * @return 	object   An object containing the resulting details.
	 */
	public function checkVersion($cache = false)
	{
		if ($cache)
		{
			// try to get a cached response
			$result = $this->getVersionDetails();

			if ($result)
			{
				// cached result, return it
				return $result;
			}
		}

		/**
		 * Get internal event dispatcher to automatically
		 * include the parameters array, which will be used
		 * to fetch the version of the program.
		 *
		 * @see   VAPFactory
		 *
		 * @since 1.7
		 */
		$dispatcher = VAPFactory::getEventDispatcher();

		// make request
		$result = $dispatcher->triggerOnce('onCheckVersion');

		if (!$result)
		{
			// use empty "failure" placeholder
			$result = new stdClass;
			$result->status = 0;
		}

		return $result;
	}

	/**
	 * Launches the update to the latest available version.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function doUpdate()
	{	
		/**
		 * Get internal event dispatcher to automatically
		 * include the parameters array, which will be used
		 * to fetch the version of the program.
		 *
		 * @see   VAPFactory
		 *
		 * @since 1.7
		 */
		$dispatcher = VAPFactory::getEventDispatcher();

		$status = false;

		try
		{
			// trigger update and search for a positive result
			$status = $dispatcher->is('onDoUpdate');

			if (!$status)
			{
				// The plugin never returns FALSE, because
				// in case of errors an exception is thrown.
				// For this reason, if we are here, probably
				// the plugin hasn't been enabled.
				$this->setError('Updater plugin disabled.');
			}
		}
		catch (Exception $e)
		{
			// an error occurred while updating
			$this->setError($e->getMessage());
		}

		return $status;
	}
}

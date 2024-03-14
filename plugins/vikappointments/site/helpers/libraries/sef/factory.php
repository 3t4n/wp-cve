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
 * Factory class used to load the correct router handler
 * according to the platform version.
 *
 * @since 1.7
 */
final class VAPSefFactory
{
	/**
	 * Loads the router instance.
	 * It is not needed to return or instantiate the class,
	 * since it is enough to let Joomla accesses it.
	 *
	 * @return void
	 */
	public static function loadRouter()
	{
		if (VersionListener::isJoomla4x())
		{
			// load router compatible with Joomla 4 or higher
			VAPLoader::import('libraries.sef.router.j40');
		}
		else
		{
			// load default router
			VAPLoader::import('libraries.sef.router.default');
		}
	}
}

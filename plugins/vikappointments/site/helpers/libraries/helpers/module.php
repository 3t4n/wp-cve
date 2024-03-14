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
 * Provides helpful methods to be used by modules helpers.
 *
 * @since 1.7
 */
trait VAPModuleHelper
{
	/**
	 * Returns a unique ID to be used for the module tags.
	 *
	 * @param 	mixed    $module  The module object.
	 *
	 * @return 	integer  The module ID.
	 */
	public static function getID($module)
	{
		static $id = 1;

		if (isset($module) && is_object($module) && property_exists($module, 'id'))
		{
			// use the module ID
			return (int) $module->id;
		}

		// missing module ID, use an incremental value
		return $id++;
	}
}

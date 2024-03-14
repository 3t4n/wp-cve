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
 * Class used to manage the employees area.
 *
 * @since 1.7
 */
class VAPEmployeeAreaManager
{
	/**
	 * Checks if a user can register a new account.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public static function canRegister()
	{
		$setting = VAPFactory::getConfig()->getBool('empsignup');

		// allow plugins to override this setting
		return (bool) static::override('config.register', $setting);
	}
	
	/**
	 * Returns the default status of an employee after its registration.
	 *
	 * @return 	string  The default status.
	 */
	public static function getSignUpStatus()
	{
		$setting = VAPFactory::getConfig()->getString('empsignstatus');

		// allow plugins to override this setting
		return (string) static::override('config.signup.status', $setting);
	}
	
	/**
	 * Returns the default user group assigned to the employee.
	 *
	 * @return 	string  The default user group.
	 */
	public static function getSignUpUserGroup()
	{
		$setting = VAPFactory::getConfig()->getString('empsignrule');

		// allow plugins to override this setting
		return (string) static::override('config.signup.group', $setting);
	}

	/**
	 * Returns the list of all the services to auto-assign to the employee.
	 *
	 * @return 	array  The default assigned services.
	 */
	public static function getServicesToAssign()
	{
		$setting = VAPFactory::getConfig()->getString('empassignser');
		$setting = array_filter(array_map('intval', explode(',', $setting)));

		// allow plugins to override this setting
		return (array) static::override('config.services', $setting);
	}

	/**
	 * Returns the toolbar instance.
	 *
	 * @return 	VAPEmployeeAreaToolbar
	 */
	public static function getToolbar()
	{
		VAPLoader::import('libraries.employee.area.toolbar');
		return VAPEmployeeAreaToolbar::getInstance();
	}

	/**
	 * Helper method used to extend the roles that an employee can perform.
	 *
	 * @param 	string 	$role     The role to check.
	 * @param 	mixed   $setting  The default setting value.
	 * @param 	mixed   $handler  The employee wrapper instance or null.
	 *
	 * @return 	mixed   The value to return.
	 */
	public static function override($role, $setting, $handler = null)
	{
		/**
		 * Trigger event to allow external plugins to override a specific setting
		 * of the employees area.
		 *
		 * @param 	string   $role     The role to check.
		 * @param 	mixed    $setting  The default setting value.
		 * @param 	mixed    $handler  The employee wrapper instance or null.
		 *
		 * @return 	mixed    The value to return.
		 *
		 * @since 	1.7
		 */
		$result = VAPFactory::getEventDispatcher()->triggerOnce('onOverrideEmployeesAreaSetting', array($role, $setting, $handler));

		if (!is_null($result))
		{
			return $result;
		}

		return $setting;
	}
}

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
 * The helper.php file of the modules is automatically loaded by Joomla before an update.
 * Since the helper file uses VAPLoader, which is not autoloaded in that case, a fatal error
 * occurs every time we try to update a module.
 * 
 * This rule is used to temporarily delete the helper.php file to allow the update of the modules.
 *
 * @since 1.7.2
 */
class VAPUpdateRuleFixModulesUpdateError1_7_2 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$this->fix();

		return true;
	}

	/**
	 * Fixes the closing periods.
	 *
	 * @return 	void
	 */
	private function fix()
	{
		// get all modules that starts with "mod_vikappointments"
		$modules = JFolder::folders(JPATH_SITE . '/modules', '^mod_vikappointments_', $recurse = false, $full = true);

		$status = false;

		foreach ($modules as $mod)
		{
			// get path of helper.php file
			$helper = JPath::clean($mod . '/helper.php');

			// check whether the helper file exists
			if (JFile::exists($helper))
			{
				// delete the helper file
				$status = JFile::delete($helper) || $status;
			}
		}

		if ($status)
		{
			// inform the user that all the modules should be updated too
			JFactory::getApplication()->enqueueMessage('The structure of the installed modules has changed. Now you MUST update them all from the Joomla updates section!', 'warning');
		}

		return true;
	}
}

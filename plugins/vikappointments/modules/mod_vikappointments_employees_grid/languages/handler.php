<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_employees_grid
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Employees Grid widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_Employees_GridLanguageHandler extends VikAppointmentsLanguageWidget
{
	/**
	 * Checks if exists a translation for the given string.
	 *
	 * @param 	string 	$string  The string to translate.
	 *
	 * @return 	string 	The translated string, otherwise null.
	 */
	public function translate($string)
	{
		$result = null;

		/**
		 * Translations go here.
		 * @tip Use 'TRANSLATORS:' comment to attach a description of the language.
		 */

		switch ($string)
		{
			/**
			 * Employees grid module.
			 */

			case 'VIKAPPOINTMENTS_EMPLOYEES_GRID_MODULE_TITLE':
				$result = __('VikAppointments Employees Grid', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_EMPLOYEES_GRID_MODULE_DESCRIPTION':
				$result = __('Displays a grid of some employees.', 'vikappointments');
				break;

			case 'VAGROUPFILTER':
				$result = __('Group Filter', 'vikappointments');
				break;

			case 'VAGROUPFILTERDESC':
				$result = __('By selecting one or more groups, only the related employees will be used. Otherwise all the employees will be taken.', 'vikappointments');
				break;

			case 'VAEMPLOYEEFILTER':
				$result = __('Employees Filter', 'vikappointments');
				break;

			case 'VAEMPLOYEEFILTERDESC':
				$result = __('Select all the employees that should be display. Leave empty to display all the employees.', 'vikappointments');
				break;

			case 'VAEMPLOYEESIMAGE':
				$result = __('Show Image', 'vikappointments');
				break;

			case 'VAEMPLOYEESGROUP':
				$result = __('Show Group', 'vikappointments');
				break;

			case 'VAEMPLOYEESDESC':
				$result = __('Show Description', 'vikappointments');
				break;

			case 'VAMODEMPLOYEESBUTTON':
				$result = __('Show Details Button', 'vikappointments');
				break;

			case 'VAMODEMPLOYEESBUTTONDESC':
				$result = __('Display the button used to access the details of the employees.', 'vikappointments');
				break;

			case 'VAMODEMPLOYEESWIDTH':
				$result = __('Box Width', 'vikappointments');
				break;

			case 'VAMODEMPLOYEESWIDTHDESC':
				$result = __('Width of the employees boxes (specify px or % after the number).', 'vikappointments');
				break;

			case 'VAHEIGHTIMAGE':
				$result = __('Image Height', 'vikappointments');
				break;

			case 'VAHEIGHTIMAGEDESC':
				$result = __('Height of the images (specify px or % after the number). Leave empty if you don\'t want to force a height.', 'vikappointments');
					break;

			case 'VAMODEMPLOYEESCONTINUE':
				$result = __('View Details', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

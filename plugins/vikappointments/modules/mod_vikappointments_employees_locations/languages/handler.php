<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_employees_locations
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Employees Locations widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_Employees_LocationsLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * Employees locations module.
			 */

			case 'VIKAPPOINTMENTS_EMPLOYEES_LOCATIONS_MODULE_TITLE':
				$result = __('VikAppointments Employees Locations', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_EMPLOYEES_LOCATIONS_MODULE_DESCRIPTION':
				$result = __('Displays a map with all the locations in which the employees work.', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_HEIGHT':
				$result = __('Map Height (px or %)', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_HEIGHT_DESC':
				$result = __('The height of the map in pixel or percentage.', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_GEOLOCATION':
				$result = __('Enable Geolocation', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_GEOLOCATION_DESC':
				$result = __('Geolocation asks to the users to detect their current position.', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_GEOLOCATION_NEVER':
				$result = __('Never', 'vikappointments');
				break;

			case 'VAP_EMPLOCATIONS_MAP_GEOLOCATION_ALWAYS':
				$result = __('Always', 'vikappointments');
				break;

			case 'VAPHERE':
				$result = __('You are here!', 'vikappointments');
				break;

			case 'VAPDETAILS':
				$result = __('View Details', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

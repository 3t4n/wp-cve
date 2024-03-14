<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Search widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_SearchLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * Search module.
			 */

			case 'VIKAPPOINTMENTS_SEARCH_MODULE_TITLE':
				$result = __('VikAppointments Search', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_SEARCH_MODULE_DESCRIPTION':
				$result = __('Displays a search form to start the booking process.', 'vikappointments');
				break;

			case 'VAP_SEARCH_ORIENTATION':
				$result = __('Orientation', 'vikappointments');
				break;

			case 'VAP_SEARCH_ORIENTATION_DESC':
				$result = __('Choose <b>vertical</b> to display one field per line. Choose <b>horizontal</b> to display all the fields within the same line.', 'vikappointments');
				break;

			case 'VAP_SEARCH_ORI_VERTICAL':
				$result = __('Vertical', 'vikappointments');
				break;

			case 'VAP_SEARCH_ORI_HORIZONTAL':
				$result = __('Horizontal', 'vikappointments');
				break;

			case 'VAP_SEARCH_ADVANCED_SELECT':
				$result = __('Advanced Dropdown', 'vikappointments');
				break;

			case 'VAP_SEARCH_ADVANCED_SELECT_DESC':
				$result = __('Turn on this option to use select2 jQuery plugin to render the widget dropdowns.', 'vikappointments');
				break;

			case 'VAPFINDAPPOINTMENT':
				$result = __('Book Now', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

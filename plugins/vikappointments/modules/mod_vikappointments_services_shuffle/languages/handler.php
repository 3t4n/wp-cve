<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_services_shuffle
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Services Shuffle widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_Services_ShuffleLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * Services shuffle module.
			 */

			case 'VIKAPPOINTMENTS_SERVICES_SHUFFLE_MODULE_TITLE':
				$result = __('VikAppointments Services Shuffle', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_SERVICES_SHUFFLE_MODULE_DESCRIPTION':
				$result = __('Displays a grid of services, which can be shuffled.', 'vikappointments');
				break;

			case 'VAPFIELDORDFILT':
				$result = __('Ordering and Filtering', 'vikappointments');
				break;

			case 'VAPFIELDORDFILTDESC':
				$result = __('Choose how the services should be sorted.', 'vikappointments');
				break;

			case 'VAPORDFILTOPT1':
				$result = __('Default Ordering', 'vikappointments');
				break;

			case 'VAPORDFILTOPT2':
				$result = __('By Name', 'vikappointments');
				break;

			case 'VAPFIELDSHOWGN':
				$result = __('Show Group Name', 'vikappointments');
				break;

			case 'VAPFIELDGROUPS':
				$result = __('Groups', 'vikappointments');
				break;

			case 'VAPFIELDGROUPSDESC':
				$result = __('Leave this field empty to select all the groups.', 'vikappointments');
				break;

			case 'VAPFIELDSERVICES':
				$result = __('Services', 'vikappointments');
				break;

			case 'VAPFIELDSERVICESDESC':
				$result = __('Leave this field empty to select all the services.', 'vikappointments');
				break;

			case 'VAPGRIDALL':
				$result = __('All Groups', 'vikappointments');
				break;

			case 'VAPCONTINUE':
				$result = __('Continue', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

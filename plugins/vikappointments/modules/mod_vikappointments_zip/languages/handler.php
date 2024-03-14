<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_zip
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments ZIP Checker widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_ZipLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * ZIP Checker module.
			 */

			case 'VIKAPPOINTMENTS_ZIP_CHECKER_MODULE_TITLE':
				$result = __('VikAppointments ZIP Checker', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_ZIP_CHECKER_MODULE_DESCRIPTION':
				$result = __('Widget used to validate the ZIP codes of the customers (for home delivery services only).', 'vikappointments');
				break;

			case 'VAP_ZIPCHECK_DISPLAY_SERVICE':
				$result = __('Display Services', 'vikappointments');
				break;

			case 'VAP_ZIPCHECK_DISPLAY_SERVICE_DESC':
				$result = __('Choose the first option to display all the services, otherwise choose the second one to display only the services with the <b>ZIP Restriction</b> parameter turned on.', 'vikappointments');
				break;

			case 'VAP_ZIPCHECK_DISPLAY_SERVICE_OPTION_ALL':
				$result = __('All', 'vikappointments');
				break;

			case 'VAP_ZIPCHECK_DISPLAY_SERVICE_OPTION_RESTRICTED':
				$result = __('Only with ZIP restriction', 'vikappointments');
				break;

			case 'VAPZIPVALID1':
				$result = __('This service is offered in your ZIP code area!', 'vikappointments');
				break;

			case 'VAPZIPVALID0':
				$result = __('We don\'t offer this service for the area with that ZIP Code.', 'vikappointments');
				break;

			case 'VAPFINDBUTTON':
				$result = __('Validate ZIP', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

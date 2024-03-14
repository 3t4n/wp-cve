<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_services
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Services widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_ServicesLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * Services module.
			 */

			case 'VIKAPPOINTMENTS_SERVICES_MODULE_TITLE':
				$result = __('VikAppointments Services', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_SERVICES_MODULE_DESCRIPTION':
				$result = __('Displays a slideshow of some services.', 'vikappointments');
				break;

			case 'VAGROUPFILTER':
				$result = __('Groups Filter', 'vikappointments');
				break;

			case 'VAGROUPFILTERDESC':
				$result = __('By selecting one or more groups, only the related services will be used. Otherwise all the services will be taken.', 'vikappointments');
				break;

			case 'VASERVICEFILTER':
				$result = __('Services Filter', 'vikappointments');
				break;

			case 'VASERVICEFILTERDESC':
				$result = __('Select all the services that should be display. Leave empty to display all the services.', 'vikappointments');
				break;

			case 'VASERVICESNUMBROW':
				$result = __('Number of Items', 'vikappointments');
				break;

			case 'VASERVICESNUMBROWDESC':
				$result = __('The maximum number of services to display per slide.', 'vikappointments');
				break;

			case 'VASERVICESIMAGE':
				$result = __('Show Image', 'vikappointments');
				break;

			case 'VASERVICESDESC':
				$result = __('Show Description', 'vikappointments');
				break;

			case 'VASERVICESDURATION':
				$result = __('Show Duration', 'vikappointments');
				break;

			case 'VASERVICESPRICE':
				$result = __('Show Price', 'vikappointments');
				break;

			case 'VASERVICESPRICELABEL':
				$result = __('Show Price label', 'vikappointments');
				break;

			case 'VASERVICESPRICELABELDESC':
				$result = __('Used to display the "Price" label before the amount.', 'vikappointments');
				break;

			case 'VASERVICESBUTTON':
				$result = __('Show Details Button', 'vikappointments');
				break;

			case 'VASERVICESBUTTONDESC':
				$result = __('Display the button used to access the details of the services.', 'vikappointments');
				break;

			case 'VASERVICESDOTNAV':
				$result = __('Dotted Navigation', 'vikappointments');
				break;

			case 'VASERVICESDOTNAVDESC':
				$result = __('Choose if you want to show the dotted navigation.', 'vikappointments');
				break;

			case 'VASERVICESARROWS':
				$result = __('Pagination', 'vikappointments');
				break;

			case 'VASERVICESARROWSDESC':
				$result = __('Choose if you want to enable the pagination buttons.', 'vikappointments');
				break;

			case 'VASERVICESAUTOPLAY':
				$result = __('Autoplay', 'vikappointments');
				break;

			case 'VASERVICESAUTOPLAYDESC':
				$result = __('Enable automatic horizontal scrolling.', 'vikappointments');
				break;

			case 'VASERVICESTIMESCROLL':
				$result = __('Time Scrolling', 'vikappointments');
				break;

			case 'VASERVICESTIMESCROLLDESC':
				$result = __('Autoplay time scrolling in milliseconds.', 'vikappointments');
				break;

			case 'VASERVICESCONTINUE':
				$result = __('View Details', 'vikappointments');
				break;

			case 'VASERVICESDURATIONTEXT':
				$result = __('Duration:', 'vikappointments');
				break;

			case 'VASERVICESDURATIONMIN':
				$result = __('min', 'vikappointments');
				break;

			case 'VASERVICESPRICELABELTEXT':
				$result = __('Price', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

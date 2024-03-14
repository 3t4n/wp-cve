<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  language
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikAppointments widgets languages.
 *
 * @since 	1.0
 */
class VikAppointmentsLanguageWidget implements JLanguageHandler
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
			 * Commons.
			 */

			case 'TITLE':
				$result = __('Title');
				break;

			case 'VAPDATE':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPGROUP':
				$result = __('Group', 'vikappointments');
				break;

			case 'VAPSERVICE':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAPEMPLOYEE':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPPRICE':
				$result = __('Price Range', 'vikappointments');
				break;

			case 'VAPCOUNTRY':
				$result = __('Country', 'vikappointments');
				break;

			case 'VAPSTATE':
				$result = __('State', 'vikappointments');
				break;

			case 'VAPCITY':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPZIP':
				$result = __('ZIP Code', 'vikappointments');
				break;

			case 'VAP_CART_MENU_ITEM':
			case 'VAP_EMPLOYEES_MENU_ITEM':
			case 'VAP_EMPFILTER_MENU_ITEM':
			case 'VAP_EMPGRID_MENU_ITEM':
			case 'VAP_EMPLOCATIONS_MENU_ITEM':
			case 'VAP_SEARCH_MENU_ITEM':
			case 'VAP_SERVICES_MENU_ITEM':
			case 'VAP_SERSHUFFLE_MENU_ITEM':
				$result = __('Menu Item', 'vikappointments');
				break;

			case 'VAP_CART_MENU_ITEM_DESC':
			case 'VAP_EMPLOYEES_MENU_ITEM_DESC':
			case 'VAP_EMPFILTER_MENU_ITEM_DESC':
			case 'VAP_EMPGRID_MENU_ITEM_DESC':
			case 'VAP_EMPLOCATIONS_MENU_ITEM_DESC':
			case 'VAP_SEARCH_MENU_ITEM_DESC':
			case 'VAP_SERVICES_MENU_ITEM_DESC':
			case 'VAP_SERSHUFFLE_MENU_ITEM_DESC':
				$result = __('Select the menu item that will be used after submitting the form.', 'vikappointments');
				break;

			case 'COM_MODULES_FILTERS_FIELDSET_LABEL':
				$result = __('Filters', 'vikappointments');
				break;

			case 'COM_MODULES_ITEMS_FIELDSET_LABEL':
				$result = __('Items', 'vikappointments');
				break;

			case 'COM_MODULES_SETTINGS_FIELDSET_LABEL':
				$result = __('Settings', 'vikappointments');
				break;

			case 'COM_MODULES_SLIDE_FIELDSET_LABEL':
				$result = __('Slide', 'vikappointments');
				break;
		}

		return $result;
	}
}

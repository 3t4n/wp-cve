<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_employees_filter
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VikAppointmentsLoader::import('language.widget');

/**
 * Switcher class to translate the VikAppointments Employees Filter widget languages.
 *
 * @since 	1.0
 */
class Mod_VikAppointments_Employees_FilterLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * Employees filter module.
			 */

			case 'VIKAPPOINTMENTS_EMPLOYEES_FILTER_MODULE_TITLE':
				$result = __('VikAppointments Employees Filter', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_EMPLOYEES_FILTER_MODULE_DESCRIPTION':
				$result = __('This widget allows the users to filters the employees with different search parameters.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_GROUPS':
				$result = __('Enable Groups Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_GROUPS_DESC':
				$result = __('Shows the dropdown with the available groups that can be filtered.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_DEFAULT_GROUP':
				$result = __('Default Group', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_SERVICES':
				$result = __('Enable Services Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_SERVICES_DESC':
				$result = __('Shows the dropdown with the available services that can be filtered.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_DEFAULT_SERVICE':
				$result = __('Default Service', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_NEARBY':
				$result = __('Enable Nearby Search', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_NEARBY_DESC':
				$result = __('Allow the customers to search for the nearest services/employees.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_DISTANCE':
				$result = __('Available Distances', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_DISTANCE_DESC':
				$result = __('The available distances to search nearby (one or more values separated by a comma). Take a look at the parameter below to select the unit to choose (km or miles).', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_DISTANCE_UNIT':
				$result = __('Distance Unit', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_DISTANCE_UNIT_DESC':
				$result = __('Select the unit that will be used for the distances.', 'vikappointments');
				break;

			case 'VAP_DISTANCE_UNIT_KM':
				$result = __('kilometers', 'vikappointments');
				break;

			case 'VAP_DISTANCE_UNIT_MILES':
				$result = __('miles', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_PRICE':
				$result = __('Enable Prices Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_PRICE_DESC':
				$result = __('Shows the slider to choose a specific range of prices.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_MIN':
				$result = __('Minimum Price Range', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_MIN_DESC':
				$result = __('The minimum value accepted by the price slider filter.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_MAX':
				$result = __('Maximum Price Range', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_MAX_DESC':
				$result = __('The maximum value accepted by the price slider filter.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_STEP':
				$result = __('Price Steps', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_STEP_DESC':
				$result = __('The amount of each interval the slider takes between the min and max. The full specified value range of the slider (max - min) should be evenly divisible by the step.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_DEF':
				$result = __('Default Price Range', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_PRICE_RANGE_DEF_DESC':
				$result = __('The default range in the price filter (2 values separated by a comma).', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_COUNTRIES':
				$result = __('Enable Countries Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_COUNTRIES_DESC':
				$result = __('Shows the dropdown with the available countries that can be filtered.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_DEFAULT_COUNTRY':
				$result = __('Default Country', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_STATES':
				$result = __('Enable States Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_STATES_DESC':
				$result = __('Shows the dropdown with the available states that can be filtered.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_CITIES':
				$result = __('Enable Cities Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_CITIES_DESC':
				$result = __('Shows the dropdown with the available cities that can be filtered.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_ZIP':
				$result = __('Enable ZIP Filter', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_ENABLE_ZIP_DESC':
				$result = __('Shows an input field to filter the employees by ZIP code.', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_SELECT_CF':
				$result = __('Custom Fields', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_SELECT_CF_DESC':
				$result = __('Select all the custom fields that can be used by the customers to filter the employees. Only the following types are supported: <b>text</b> and <b>select</b>.', 'vikappointments');
				break;

			case 'VAPNEARBY':
				$result = __('Search Nearby', 'vikappointments');
				break;

			case 'VAPDISTANCE':
				$result = __('Maximum Distance', 'vikappointments');
				break;

			case 'VAPSEARCHBTN':
				$result = __('Find Employee', 'vikappointments');
				break;

			case 'VAPGEOLOCATIONERR1':
				$result = __('Impossible to establish your position! Please, try again.', 'vikappointments');
				break;

			case 'VAPGEOLOCATIONERR2':
				$result = __('Your browser doesn\'t support GEOLOCATION!', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_MODE':
				$result = __('Nearby Search Type', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_MODE_DESC':
				$result = __('Choose how the nearby search should be applied. In the first case, the system will ask to the user to share its current position. In the second case, the center of the circle will depend on the selected filters (e.g. City or ZIP).', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_MODE_OPT1':
				$result = __('Applies to user position', 'vikappointments');
				break;

			case 'VAP_EMPFILTER_NEARBY_MODE_OPT2':
				$result = __('Applies to searched parameters', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

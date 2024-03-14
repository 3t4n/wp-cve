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
 * Switcher class to translate the VikAppointments One-Page Booking widget languages.
 *
 * @since 	1.2.5
 */
class Mod_VikAppointments_Onepage_BookingLanguageHandler extends VikAppointmentsLanguageWidget
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
			 * One-Page Booking module.
			 */

			case 'VIKAPPOINTMENTS_ONEPAGE_BOOKING_MODULE_TITLE':
				$result = __('VikAppointments One-Page Booking', 'vikappointments');
				break;

			case 'VIKAPPOINTMENTS_ONEPAGE_BOOKING_MODULE_DESCRIPTION':
				$result = __('Widget used to display a one-page booking form.', 'vikappointments');
				break;

			case 'COM_MODULES_LAYOUT_FIELDSET_LABEL':
				$result = __('Layout', 'vikappointments');
				break;
			
			case 'VAP_ONEPAGE_BOOKING_HIDE_UNAVAILABLE':
				$result = __('Hide Unavailable Times', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_HIDE_UNAVAILABLE_DESC':
				$result = __('Enable this option to display only the times that can be booked.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_PRICE_PER_PERSON':
				$result = __('Show Price per Person', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_PRICE_PER_PERSON_DESC':
				$result = __('Enable this option to show also a label with the service price per person. The label will be displayed only in case the number of participants is higher than 1.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_MENU_ITEM':
				$result = __('Menu Item', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_MENU_ITEM_DESC':
				$result = __('Select the menu item that will be used after completing the booking process.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_MAX_WIDTH':
				$result = __('Max Width', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_MAX_WIDTH_DESC':
				$result = __('The maximum width of the module. It is possible to use any kind of unit, such as <code>px</code>, <code>%</code>, <code>vw</code> and so on.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_SCROLLABLE_TIMELINE':
				$result = __('Scrollable Timeline', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_SCROLLABLE_TIMELINE_DESC':
				$result = __('Enable this option to include the timeline in a scrollable container. Leave it disabled to immediately display all the times.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_LOADING_ANIMATION':
				$result = __('Loading Animation', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_LOADING_ANIMATION_DESC':
				$result = __('Choose whether to display an animation while waiting for the requests completion.', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_ANIMATION_DURATION':
				$result = __('Animation Duration', 'vikappointments');
				break;

			case 'VAP_ONEPAGE_BOOKING_ANIMATION_DURATION_DESC':
				$result = __('The minimum duration of the animation in milliseconds. It is suggested to use a value between 1000 and 3000 as a fair compromise among waiting time and animation appearance.', 'vikappointments');
				break;

			case 'VAP_OPB_FIND_APP_TITLE':
				$result = __('Find an appointment', 'vikappointments');
				break;

			case 'VAP_OPB_DATE_LABEL':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAP_OPB_SERVICE_LABEL':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAP_OPB_EMPLOYEE_LABEL':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAP_OPB_PEOPLE_LABEL':
				$result = __('Attendees', 'vikappointments');
				break;

			case 'VAP_OPB_ANY_PLACEHOLDER':
				$result = __('- Any -', 'vikappointments');
				break;

			case 'VAP_OPB_SEARCH_BUTTON':
				$result = __('Search', 'vikappointments');
				break;

			case 'VAP_OPB_N_SEATS_REMAINING':
				$result = __('%d seats remaining', 'vikappointments');
				break;

			case 'VAP_OPB_N_SEATS_REMAINING_1':
				$result = __('1 seat remaining', 'vikappointments');
				break;

			case 'VAP_OPB_BOOK_NOW_BUTTON':
				$result = __('Book', 'vikappointments');
				break;

			case 'VAP_OPB_ADD_CART_BUTTON':
				$result = __('Book Appointment', 'vikappointments');
				break;

			case 'VAP_OPB_BOOKED_BUTTON':
				$result = __('Booked', 'vikappointments');
				break;

			case 'VAP_OPB_CANCEL_BUTTON':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'VAP_OPB_NEXT_BUTTON':
				$result = __('Next', 'vikappointments');
				break;

			case 'VAP_OPB_NO_GROUP_LEGEND':
				$result = __('Uncategorized', 'vikappointments');
				break;

			case 'VAP_OPB_BILLING_TITLE':
				$result = __('Billing Details', 'vikappointments');
				break;

			case 'VAP_OPB_SUMMARY_TITLE':
				$result = __('Order Summary', 'vikappointments');
				break;

			case 'VAP_OPB_ASK_CANCEL_CONFIRM':
				$result = __('Do you wish to cancel the appointment?', 'vikappointments');
				break;

			case 'VAP_OPB_REDEEM_COUPON_BUTTON':
				$result = __('Redeem', 'vikappointments');
				break;

			case 'VAP_OPB_CONFIRM_BUTTON':
				$result = __('Confirm', 'vikappointments');
				break;

			case 'VAP_OPB_GENERIC_ERROR':
				$result = __('An error has occurred! Please, try again.', 'vikappointments');
				break;

			case 'VAP_OPB_ARIA_NEXT_DAY':
				$result = __('Next day', 'vikappointments');
				break;

			case 'VAP_OPB_ARIA_PREV_DAY':
				$result = __('Previous day', 'vikappointments');
				break;

			default:
				// fallback to parent handler for commons
				$result = parent::translate($string);
		}

		return $result;
	}
}

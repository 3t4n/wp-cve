<?php

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikRentItems Summary widget languages.
 *
 * @since 	1.0
 */
class Mod_VikRentItems_SummaryLanguageHandler implements JLanguageHandler
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
			 * Name, Description and Parameters
			 */

			case 'MOD_VIKRENTITEMS_SUMMARY':
				$result = __('VikRentItems Summary', 'vikrentitems');
				break;
			case 'MOD_VIKRENTITEMS_SUMMARY_DESC':
				$result = __('Shows the summary of the current rental order.', 'vikrentitems');
				break;
			case 'TITLE':
				$result = __('Title', 'vikrentitems');
				break;
			case 'JLAYOUT':
				$result = __('Layout', 'vikrentitems');
				break;
			case 'JLAYOUT_DESC':
				$result = __('The layout of the module to use. The available layouts are contained within the <b>tmpl</b> folder of the module.', 'vikrentitems');
				break;
			case 'JMENUITEM':
				$result = __('Page', 'vikrentitems');
				break;
			case 'JMENUITEM_DESC':
				$result = __('Select a page to start the booking process. The page must use a VikRentItems shortcode.', 'vikrentitems');
				break;
			case 'VIEW_SUMMARY_BTN':
				$result = __('Show Summary button', 'vikrentitems');
				break;
			case 'VIEW_SUMMARY_BTN_DESC':
				$result = __('Choose whether to show or hide the View Summary button when some Items have been selected', 'vikrentitems');
				break;
			case 'SHOW_DATES':
				$result = __('Show Dates', 'vikrentitems');
				break;
			case 'SHOW_DATES_DESC':
				$result = __('Choose whether to show or hide the dates selected', 'vikrentitems');
				break;
			case 'JNO':
				$result = __('No', 'vikrentitems');
				break;
			case 'JYES':
				$result = __('Yes', 'vikrentitems');
				break;
			case 'USEGLOB':
				$result = __('Use Globals', 'vikrentitems');
				break;
			case 'SEARCHD':
				$result = __('Search', 'vikrentitems');
				break;
			case 'VRMPPLACE':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRMPICKUPCAR':
				$result = __('Pickup Date and Time', 'vikrentitems');
				break;
			case 'VRMALLE':
				$result = __('At', 'vikrentitems');
				break;
			case 'VRMRETURNCAR':
				$result = __('Drop Off Date and Time', 'vikrentitems');
				break;
			case 'VRMCARCAT':
				$result = __('Category', 'vikrentitems');
				break;
			case 'VRMALLCAT':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRMPLACERET':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRIMLOCDAYCLOSED':
				$result = __('The location is closed on this day', 'vikrentitems');
				break;
			case 'VRIMFOR':
				$result = __('For', 'vikrentitems');
				break;
			case 'VRIMGOTOSUMMARY':
				$result = __('View Order Summary', 'vikrentitems');
				break;
			case 'VRIMTOTITEMS':
				$result = __('Rented Items', 'vikrentitems');
				break;
			case 'VRIMTMPTOTDUE':
				$result = __('Order Total', 'vikrentitems');
				break;
			case 'VRMDAL':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRMAL':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRIMCARTYOURCART':
				$result = __('Your Cart', 'vikrentitems');
				break;
			case 'VRIMCARTTOTITEMS':
				$result = __('%d Items', 'vikrentitems');
				break;
		}

		return $result;
	}
}

<?php

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikRentItems Search widget languages.
 *
 * @since 	1.0
 */
class Mod_VikRentItems_SearchLanguageHandler implements JLanguageHandler
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

			case 'MOD_VIKRENTITEMS_SEARCH':
				$result = __('VikRentItems Search Form', 'vikrentitems');
				break;
			case 'MOD_VIKRENTITEMS_SEARCH_DESC':
				$result = __('Search Form to start booking items.', 'vikrentitems');
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
			case 'PARAMHEADINGTEXT':
				$result = __('Heading Text', 'vikrentitems');
				break;
			case 'INTROT':
				$result = __('Introducing Text', 'vikrentitems');
				break;
			case 'CLOSET':
				$result = __('Closing Text', 'vikrentitems');
				break;
			case 'SHOWCAT':
				$result = __('Show Categories', 'vikrentitems');
				break;
			case 'SHOWLOC':
				$result = __('Show Pickup-DropOff Locations', 'vikrentitems');
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
			case 'SEARCHT':
				$result = __('Custom Search Button Text', 'vikrentitems');
				break;
			case 'SEARCHHELP':
				$result = __('Custom Search Button Text, Leave empty to use default value', 'vikrentitems');
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
			case 'VRIJQCALDONE':
				$result = __('Done', 'vikrentitems');
				break;
			case 'VRIJQCALPREV':
				$result = __('Prev', 'vikrentitems');
				break;
			case 'VRIJQCALNEXT':
				$result = __('Next', 'vikrentitems');
				break;
			case 'VRIJQCALTODAY':
				$result = __('Today', 'vikrentitems');
				break;
			case 'VRIJQCALSUN':
				$result = __('Sunday', 'vikrentitems');
				break;
			case 'VRIJQCALMON':
				$result = __('Monday', 'vikrentitems');
				break;
			case 'VRIJQCALTUE':
				$result = __('Tuesday', 'vikrentitems');
				break;
			case 'VRIJQCALWED':
				$result = __('Wednesday', 'vikrentitems');
				break;
			case 'VRIJQCALTHU':
				$result = __('Thursday', 'vikrentitems');
				break;
			case 'VRIJQCALFRI':
				$result = __('Friday', 'vikrentitems');
				break;
			case 'VRIJQCALSAT':
				$result = __('Saturday', 'vikrentitems');
				break;
			case 'VRIJQCALWKHEADER':
				$result = __('Wk', 'vikrentitems');
				break;
			case 'VRMONTHONE':
				$result = __('January', 'vikrentitems');
				break;
			case 'VRMONTHTWO':
				$result = __('February', 'vikrentitems');
				break;
			case 'VRMONTHTHREE':
				$result = __('March', 'vikrentitems');
				break;
			case 'VRMONTHFOUR':
				$result = __('April', 'vikrentitems');
				break;
			case 'VRMONTHFIVE':
				$result = __('May', 'vikrentitems');
				break;
			case 'VRMONTHSIX':
				$result = __('June', 'vikrentitems');
				break;
			case 'VRMONTHSEVEN':
				$result = __('July', 'vikrentitems');
				break;
			case 'VRMONTHEIGHT':
				$result = __('August', 'vikrentitems');
				break;
			case 'VRMONTHNINE':
				$result = __('September', 'vikrentitems');
				break;
			case 'VRMONTHTEN':
				$result = __('October', 'vikrentitems');
				break;
			case 'VRMONTHELEVEN':
				$result = __('November', 'vikrentitems');
				break;
			case 'VRMONTHTWELVE':
				$result = __('December', 'vikrentitems');
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
			case 'VRIMGLOBDAYCLOSED':
				$result = __('We are closed on this day', 'vikrentitems');
				break;
			case 'FORCESINGLECATEGORYSEARCH':
				$result = __('Force Specific Category', 'vikrentitems');
				break;
			case 'FORCESINGLECATEGORYSEARCHHELP':
				$result = __('If not disabled, the search module will check the availability only for the items belonging to the selected category. If disabled, the system will display a drop down menu for selecting the category filter (the above parameter Show Categories must be enabled in this case, or disabled for forcing a hidden Category).', 'vikrentitems');
				break;
			case 'FORCESINGLECATEGORYSEARCHDISABLED':
				$result = __('-- Disabled --', 'vikrentitems');
				break;
		}

		return $result;
	}
}

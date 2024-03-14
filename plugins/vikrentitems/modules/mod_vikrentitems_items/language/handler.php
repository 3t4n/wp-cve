<?php

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikRentItems Items widget languages.
 *
 * @since 	1.0
 */
class Mod_VikRentItems_ItemsLanguageHandler implements JLanguageHandler
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

			case 'MOD_VIKRENTITEMS_ITEMS':
				$result = __('VikRentItems Items Carousel', 'vikrentitems');
				break;
			case 'MOD_VIKRENTITEMS_ITEMS_DESC':
				$result = __('Displays a carousel of specific items', 'vikrentitems');
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
			case 'VRIMODITEMSTARTFROM':
				$result = __('from', 'vikrentitems');
				break;
			case 'VRIMODITEMSCONTINUE':
				$result = __('Details', 'vikrentitems');
				break;
			case 'VRIITEMSCONFIGURATION':
				$result = __('Item Configuration', 'vikrentitems');
				break;
			case 'VRIITEMSSLIDERCONF':
				$result = __('Carousel Configuration', 'vikrentitems');
				break;
			case 'VRIITEMSWIDTH':
				$result = __('Item Width', 'vikrentitems');
				break;
			case 'VRIITEMSWIDTHDESC':
				$result = __('With of the single item displayed', 'vikrentitems');
				break;
			case 'VRIITEMSNUMB':
				$result = __('Maximum Items Displayed', 'vikrentitems');
				break;
			case 'VRIITEMSNUMBDESC':
				$result = __('Maximum Items Displayed', 'vikrentitems');
				break;
			case 'VRIITEMSNUMBROW':
				$result = __('Items per row', 'vikrentitems');
				break;
			case 'VRIITEMSDESCLABEL':
				$result = __('Description', 'vikrentitems');
				break;
			case 'VRIITEMSDOTNAV':
				$result = __('Dotted Navigation', 'vikrentitems');
				break;
			case 'VRIITEMSDOTNAVDESC':
				$result = __('Show Dotted Navigation', 'vikrentitems');
				break;
			case 'VRIITEMSARROWS':
				$result = __('Display Navigation', 'vikrentitems');
				break;
			case 'VRIITEMSARROWSDESC':
				$result = __('Display Navigation', 'vikrentitems');
				break;
			case 'VRIITEMSAUTOPLAY':
				$result = __('Autoplay', 'vikrentitems');
				break;
			case 'VRIITEMSAUTOPLAYDESC':
				$result = __('Enable automatic scrolling', 'vikrentitems');
				break;
			case 'VRIITEMSTIMESCROLL':
				$result = __('Time Scrolling', 'vikrentitems');
				break;
			case 'VRIITEMSTIMESCROLLDESC':
				$result = __('Autoplay Time Scrolling', 'vikrentitems');
				break;
			case 'VRIITEMSORDERFILTER':
				$result = __('Ordering and Filtering', 'vikrentitems');
				break;
			case 'VRIITEMSORDERFILTERDESC':
				$result = __('Ordering and Filtering', 'vikrentitems');
				break;
			case 'ORDERING':
				$result = __('Ordering', 'vikrentitems');
				break;
			case 'BYPRICE':
				$result = __('By Price', 'vikrentitems');
				break;
			case 'BYNAME':
				$result = __('By Name', 'vikrentitems');
				break;
			case 'LOADJQ':
				$result = __('Load jQuery', 'vikrentitems');
				break;
			case 'LOADJQDESC':
				$result = __('Load jQuery', 'vikrentitems');
				break;
			case 'BYCATEGORY':
				$result = __('By Category', 'vikrentitems');
				break;
			case 'VRIITEMSORDERTYPE':
				$result = __('Order Type', 'vikrentitems');
				break;
			case 'VRIITEMSORDERTYPEDESC':
				$result = __('Ascending or Descending', 'vikrentitems');
				break;
			case 'TYPEASC':
				$result = __('Ascending', 'vikrentitems');
				break;
			case 'TYPEDESC':
				$result = __('Descending', 'vikrentitems');
				break;
			case 'VRIITEMSCURRENCY':
				$result = __('Currency Symbol', 'vikrentitems');
				break;
			case 'VRIITEMSCURRENCYDESC':
				$result = __('The Currency Symbol to display', 'vikrentitems');
				break;
			case 'VRIITEMSCATEGORY':
				$result = __('Show Category Name', 'vikrentitems');
				break;
			case 'VRIITEMSCATEGORYDESC':
				$result = __('Show Category Name', 'vikrentitems');
				break;
			case 'VRIITEMSFILTERCAT':
				$result = __('Filtering by Category', 'vikrentitems');
				break;
			case 'VRIITEMSSELECTCAT':
				$result = __('Select a Category', 'vikrentitems');
				break;
			case 'VRIITEMSSHOWDETAILSBTN':
				$result = __('Show Details button', 'vikrentitems');
				break;
			case 'SHOWITEMDESC':
				$result = __('Show description', 'vikrentitems');
				break;
			case 'SHOWCARATS':
				$result = __('Show Characteristics', 'vikrentitems');
				break;
			case 'SHOWCARATSDESC':
				$result = __('Show the Vik Rent Items Characteristics for each item', 'vikrentitems');
				break;
			case 'JYES':
				$result = __('yes', 'vikrentitems');
				break;
			case 'JNO':
				$result = __('no', 'vikrentitems');
				break;
			case 'VRIITEMSLOOP':
				$result = __('Autoplay Loop', 'vikrentitems');
				break;
			case 'VRIITEMSLOOPDESC':
				$result = __('Autoplay Loop', 'vikrentitems');
				break;
			case 'VRIMODITEMSPREV':
				$result = __('Prev', 'vikrentitems');
				break;
			case 'VRIMODITEMSNEXT':
				$result = __('Next', 'vikrentitems');
				break;
		}

		return $result;
	}
}

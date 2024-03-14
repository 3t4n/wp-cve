<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	language
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

JLoader::import('adapter.language.handler');

/**
 * Switcher class to translate the VikRentItems plugin admin languages.
 *
 * @since 	1.0
 */
class VikRentItemsLanguageAdmin implements JLanguageHandler
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
			 * System definitions for toolbar
			 */
			case 'JSEARCH_TOOLS':
				$result = __('Search Tools', 'vikrentitems');
				break;

			/**
			 * Definitions
			 */
			case 'VRSAVE':
				$result = __('Save', 'vikrentitems');
				break;
			case 'VRANNULLA':
				$result = __('Cancel', 'vikrentitems');
				break;
			case 'VRELIMINA':
				$result = __('Delete', 'vikrentitems');
				break;
			case 'VRBACK':
				$result = __('Back', 'vikrentitems');
				break;
			case 'VRIONFIRMED':
				$result = __('Confirmed', 'vikrentitems');
				break;
			case 'VRSTANDBY':
				$result = __('Standby', 'vikrentitems');
				break;
			case 'VRLEFT':
				$result = __('Left', 'vikrentitems');
				break;
			case 'VRRIGHT':
				$result = __('Right', 'vikrentitems');
				break;
			case 'VRBOTTOMCENTER':
				$result = __('Bottom, Center', 'vikrentitems');
				break;
			case 'VRSTATUS':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRMAINDEAFULTTITLE':
				$result = __('Vik Rent Items - Items List', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTDEL':
				$result = __('Delete Item', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTEDITC':
				$result = __('Edit Item', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTEDITT':
				$result = __('Edit/View Fares', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTCAL':
				$result = __('Items Calendar', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTNEW':
				$result = __('New Item', 'vikrentitems');
				break;
			case 'VRMAINPLACETITLE':
				$result = __('Vik Rent Items - Pickup/Drop Off Locations', 'vikrentitems');
				break;
			case 'VRMAINPLACEDEL':
				$result = __('Delete Location', 'vikrentitems');
				break;
			case 'VRMAINPLACEEDIT':
				$result = __('Edit Location', 'vikrentitems');
				break;
			case 'VRMAINPLACENEW':
				$result = __('New Location', 'vikrentitems');
				break;
			case 'VRMAINIVATITLE':
				$result = __('Vik Rent Items - Tax Rates List', 'vikrentitems');
				break;
			case 'VRMAINIVADEL':
				$result = __('Delete Tax Rate', 'vikrentitems');
				break;
			case 'VRMAINIVAEDIT':
				$result = __('Edit Tax Rate', 'vikrentitems');
				break;
			case 'VRMAINIVANEW':
				$result = __('New Tax Rate', 'vikrentitems');
				break;
			case 'VRMAINCATTITLE':
				$result = __('Vik Rent Items - Categories List', 'vikrentitems');
				break;
			case 'VRMAINCATDEL':
				$result = __('Delete Categories', 'vikrentitems');
				break;
			case 'VRMAINCATEDIT':
				$result = __('Edit Category', 'vikrentitems');
				break;
			case 'VRMAINCATNEW':
				$result = __('New Category', 'vikrentitems');
				break;
			case 'VRMAINCARATTITLE':
				$result = __('Vik Rent Items - Characteristics List', 'vikrentitems');
				break;
			case 'VRMAINCARATDEL':
				$result = __('Delete Characteristics', 'vikrentitems');
				break;
			case 'VRMAINCARATEDIT':
				$result = __('Edit Characteristic', 'vikrentitems');
				break;
			case 'VRMAINCARATNEW':
				$result = __('New Characteristic', 'vikrentitems');
				break;
			case 'VRMAINOPTTITLE':
				$result = __('Vik Rent Items - Options List', 'vikrentitems');
				break;
			case 'VRMAINOPTDEL':
				$result = __('Delete Options', 'vikrentitems');
				break;
			case 'VRMAINOPTEDIT':
				$result = __('Edit Option', 'vikrentitems');
				break;
			case 'VRMAINOPTNEW':
				$result = __('New Option', 'vikrentitems');
				break;
			case 'VRMAINPRICETITLE':
				$result = __('Vik Rent Items - Types of Price', 'vikrentitems');
				break;
			case 'VRMAINPRICEDEL':
				$result = __('Delete Prices', 'vikrentitems');
				break;
			case 'VRMAINPRICEEDIT':
				$result = __('Edit Price', 'vikrentitems');
				break;
			case 'VRMAINPRICENEW':
				$result = __('New Price', 'vikrentitems');
				break;
			case 'VRMAINPLACETITLENEW':
				$result = __('Vik Rent Items - New Pickup/Drop Off Location', 'vikrentitems');
				break;
			case 'VRMAINPLACETITLEEDIT':
				$result = __('Vik Rent Items - Edit Location', 'vikrentitems');
				break;
			case 'VRMAINSTATSTITLE':
				$result = __('Vik Rent Items - Search Statistics', 'vikrentitems');
				break;
			case 'VRMAINIVATITLENEW':
				$result = __('Vik Rent Items - New Tax Rate', 'vikrentitems');
				break;
			case 'VRMAINIVATITLEEDIT':
				$result = __('Vik Rent Items - Edit Tax Rate', 'vikrentitems');
				break;
			case 'VRMAINPRICETITLENEW':
				$result = __('Vik Rent Items - New Price', 'vikrentitems');
				break;
			case 'VRMAINPRICETITLEEDIT':
				$result = __('Vik Rent Items - Edit Price', 'vikrentitems');
				break;
			case 'VRMAINCATTITLENEW':
				$result = __('Vik Rent Items - New Category', 'vikrentitems');
				break;
			case 'VRMAINCATTITLEEDIT':
				$result = __('Vik Rent Items - Edit Category', 'vikrentitems');
				break;
			case 'VRMAINCARATTITLENEW':
				$result = __('Vik Rent Items - New Characteristic', 'vikrentitems');
				break;
			case 'VRMAINCARATTITLEEDIT':
				$result = __('Vik Rent Items - Edit Characteristic', 'vikrentitems');
				break;
			case 'VRMAINOPTTITLENEW':
				$result = __('Vik Rent Items - New Option', 'vikrentitems');
				break;
			case 'VRMAINOPTTITLEEDIT':
				$result = __('Vik Rent Items - Edit Option', 'vikrentitems');
				break;
			case 'VRMAINITEMTITLENEW':
				$result = __('Vik Rent Items - New Item', 'vikrentitems');
				break;
			case 'VRMAINITEMTITLEEDIT':
				$result = __('Vik Rent Items - Edit Item', 'vikrentitems');
				break;
			case 'VRMAINTARIFFETITLE':
				$result = __('Vik Rent Items - Items Fares', 'vikrentitems');
				break;
			case 'VRMAINTARIFFEDEL':
				$result = __('Delete Fares', 'vikrentitems');
				break;
			case 'VRMAINTARIFFEBACK':
				$result = __('Quit Inserting', 'vikrentitems');
				break;
			case 'VRMAINORDERTITLE':
				$result = __('Vik Rent Items - Rental Orders', 'vikrentitems');
				break;
			case 'VRMAINORDERDEL':
				$result = __('Delete Orders', 'vikrentitems');
				break;
			case 'VRMAINORDEREDIT':
				$result = __('View Order', 'vikrentitems');
				break;
			case 'VRMAINOLDORDERTITLE':
				$result = __('Vik Rent Items - Removed Rental Orders', 'vikrentitems');
				break;
			case 'VRMAINOLDORDERDEL':
				$result = __('Permanently Delete', 'vikrentitems');
				break;
			case 'VRMAINOLDORDEREDIT':
				$result = __('View Removed Order', 'vikrentitems');
				break;
			case 'VRMAINORDERTITLEEDIT':
				$result = __('Vik Rent Items - Rental Order', 'vikrentitems');
				break;
			case 'VRMAINOLDORDERTITLEEDIT':
				$result = __('Vik Rent Items - Removed Rental Order', 'vikrentitems');
				break;
			case 'VRMAINCALTITLE':
				$result = __('Vik Rent Items - Booking Calendar', 'vikrentitems');
				break;
			case 'VRMAINCHOOSEBUSY':
				$result = __('Reservations for', 'vikrentitems');
				break;
			case 'VRMAINEBUSYTITLE':
				$result = __('Vik Rent Items - Edit Reservation', 'vikrentitems');
				break;
			case 'VRMAINEBUSYDEL':
				$result = __('Delete Reservation', 'vikrentitems');
				break;
			case 'VRMAINCONFIGTITLE':
				$result = __('Vik Rent Items - Global Configuration', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTSTITLE':
				$result = __('Vik Rent Items - Payment Methods', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTSDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTSEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTSNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTTITLENEW':
				$result = __('Vik Rent Items - New Payment Method', 'vikrentitems');
				break;
			case 'VRMAINPAYMENTTITLEEDIT':
				$result = __('Vik Rent Items - Edit Payment Method', 'vikrentitems');
				break;
			case 'VRMAINOVERVIEWTITLE':
				$result = __('Vik Rent Items - Overview', 'vikrentitems');
				break;
			case 'VRPANELONE':
				$result = __('Shop and Rentals', 'vikrentitems');
				break;
			case 'VRPANELTWO':
				$result = __('Prices and Payments', 'vikrentitems');
				break;
			case 'VRPANELTHREE':
				$result = __('Views and Layout', 'vikrentitems');
				break;
			case 'VRPANELFOUR':
				$result = __('Statistics and Orders', 'vikrentitems');
				break;
			case 'VRMESSDELBUSY':
				$result = __('Reservation Deleted', 'vikrentitems');
				break;
			case 'VRIARNOTCONSTO':
				$result = __('to the', 'vikrentitems');
				break;
			case 'VRIARNOTRIT':
				$result = __('Some Items are not available from the', 'vikrentitems');
				break;
			case 'ERRPREV':
				$result = __('Drop Off time is previous than Pickup', 'vikrentitems');
				break;
			case 'ERRITEMLOCKED':
				$result = __('The item is not available in the days requested. The Item is waiting for the payment to confirm the order', 'vikrentitems');
				break;
			case 'RESUPDATED':
				$result = __('Reservation Updated', 'vikrentitems');
				break;
			case 'VRSETTINGSAVED':
				$result = __('Settings Saved! Click the Renew Session button to immediately apply the changes', 'vikrentitems');
				break;
			case 'VRPAYMENTSAVED':
				$result = __('Payment Method Saved', 'vikrentitems');
				break;
			case 'ERRINVFILEPAYMENT':
				$result = __('File Class is already used in another payment method', 'vikrentitems');
				break;
			case 'VRPAYMENTUPDATED':
				$result = __('Payment Method Updated', 'vikrentitems');
				break;
			case 'VRRENTALORD':
				$result = __('Rental Order', 'vikrentitems');
				break;
			case 'VRIOMPLETED':
				$result = __('Completed', 'vikrentitems');
				break;
			case 'ERRCONFORDERITEMNA':
				$result = __('Error, the following Items are no longer available. Unable to set the reservation as Confirmed.<br/>%s', 'vikrentitems');
				break;
			case 'VRORDERSETASCONF':
				$result = __('Order successfully set to confirmed', 'vikrentitems');
				break;
			case 'VROVERVIEWNOITEMS':
				$result = __('No Items Found', 'vikrentitems');
				break;
			case 'VRIERRNOTAR':
				$result = __('No valid price found for the following items: %s', 'vikrentitems');
				break;
			case 'VRINEWITEMQUANT':
				$result = __('New Quantity', 'vikrentitems');
				break;
			case 'VRIPEDITBUSYTOTPAID':
				$result = __('Total Paid', 'vikrentitems');
				break;
			case 'VRIQUANTITY':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRFOOTER':
				$result = __('VikRent Items v.1.3 - Powered by', 'vikrentitems');
				break;
			case 'VRINSERTFEE':
				$result = __('Insert Fares', 'vikrentitems');
				break;
			case 'VRMSGONE':
				$result = __('No Types of Price Found. Create at least one from', 'vikrentitems');
				break;
			case 'VRHERE':
				$result = __('Here', 'vikrentitems');
				break;
			case 'VRMSGTWO':
				$result = __('Days Field is empty', 'vikrentitems');
				break;
			case 'VRDAYS':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRDAYSFROM':
				$result = __('from', 'vikrentitems');
				break;
			case 'VRDAYSTO':
				$result = __('to', 'vikrentitems');
				break;
			case 'VRDAILYPRICES':
				$result = __('Daily Price(s)', 'vikrentitems');
				break;
			case 'VRDAY':
				$result = __('Day', 'vikrentitems');
				break;
			case 'VRINSERT':
				$result = __('Insert', 'vikrentitems');
				break;
			case 'VRMODRES':
				$result = __('Edit Reservation', 'vikrentitems');
				break;
			case 'VRQUICKBOOK':
				$result = __('Quick Reservation', 'vikrentitems');
				break;
			case 'VRBOOKMADE':
				$result = __('Reservation Saved', 'vikrentitems');
				break;
			case 'VRBOOKNOTMADE':
				$result = __('Unable to save the Reservation, Item not Available', 'vikrentitems');
				break;
			case 'VRMSGTHREE':
				$result = __('Pickup Field is empty', 'vikrentitems');
				break;
			case 'VRMSGFOUR':
				$result = __('Drop Off Field is empty', 'vikrentitems');
				break;
			case 'VRDATEPICKUP':
				$result = __('Pickup Date and Time', 'vikrentitems');
				break;
			case 'VRAT':
				$result = __('At', 'vikrentitems');
				break;
			case 'VRDATERELEASE':
				$result = __('Drop Off Date and Time', 'vikrentitems');
				break;
			case 'VRIUSTINFO':
				$result = __('Customer Information', 'vikrentitems');
				break;
			case 'VRMAKERESERV':
				$result = __('Save Reservation', 'vikrentitems');
				break;
			case 'VRNOFUTURERES':
				$result = __('No Future Reservations', 'vikrentitems');
				break;
			case 'VRVIEW':
				$result = __('View Mode', 'vikrentitems');
				break;
			case 'VRTHREEMONTHS':
				$result = __('3 Months', 'vikrentitems');
				break;
			case 'VRSIXMONTHS':
				$result = __('6 Months', 'vikrentitems');
				break;
			case 'VRTWELVEMONTHS':
				$result = __('1 Year', 'vikrentitems');
				break;
			case 'VRSUN':
				$result = __('Sun', 'vikrentitems');
				break;
			case 'VRMON':
				$result = __('Mon', 'vikrentitems');
				break;
			case 'VRTUE':
				$result = __('Tue', 'vikrentitems');
				break;
			case 'VRWED':
				$result = __('Wed', 'vikrentitems');
				break;
			case 'VRTHU':
				$result = __('Thu', 'vikrentitems');
				break;
			case 'VRFRI':
				$result = __('Fri', 'vikrentitems');
				break;
			case 'VRSAT':
				$result = __('Sat', 'vikrentitems');
				break;
			case 'VRPICKUPAT':
				$result = __('Pickup at', 'vikrentitems');
				break;
			case 'VRRELEASEAT':
				$result = __('Drop Off at', 'vikrentitems');
				break;
			case 'VRNOITEMSFOUND':
				$result = __('No items found', 'vikrentitems');
				break;
			case 'VRJSDELITEM':
				$result = __('Every selected Item will be removed with its own contents. Confirm', 'vikrentitems');
				break;
			case 'VRPVIEWITEMONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPVIEWITEMTWO':
				$result = __('Category', 'vikrentitems');
				break;
			case 'VRPVIEWITEMTHREE':
				$result = __('Characteristics', 'vikrentitems');
				break;
			case 'VRPVIEWITEMFOUR':
				$result = __('Options', 'vikrentitems');
				break;
			case 'VRPVIEWITEMFIVE':
				$result = __('Location', 'vikrentitems');
				break;
			case 'VRPVIEWITEMSIX':
				$result = __('Available', 'vikrentitems');
				break;
			case 'VRPVIEWITEMSEVEN':
				$result = __('Units', 'vikrentitems');
				break;
			case 'VRMAKENOTAVAIL':
				$result = __('Make Not Available', 'vikrentitems');
				break;
			case 'VRMAKEAVAIL':
				$result = __('Make Available', 'vikrentitems');
				break;
			case 'VRNOSTATSFOUND':
				$result = __('No Search Found', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSONE':
				$result = __('Search Date', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSTWO':
				$result = __('IP', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSTHREE':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSFOUR':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSFIVE':
				$result = __('Location', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSSIX':
				$result = __('Category', 'vikrentitems');
				break;
			case 'VRPVIEWSTATSSEVEN':
				$result = __('Results', 'vikrentitems');
				break;
			case 'VRANYTHING':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRNOORDERSFOUND':
				$result = __('No orders found', 'vikrentitems');
				break;
			case 'VRJSDELORDER':
				$result = __('Every selected order will be removed with its reservation. Confirm', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSONE':
				$result = __('Date', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSTWO':
				$result = __('Purchaser', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSTHREE':
				$result = __('Item', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSFOUR':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSFIVE':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSSIX':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSSEVEN':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSEIGHT':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRNOOLDORDERSFOUND':
				$result = __('No removed orders found', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSTSDEL':
				$result = __('Removed on the', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSONE':
				$result = __('Date', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSTWO':
				$result = __('Purchaser', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSTHREE':
				$result = __('Item', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSFOUR':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSFIVE':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSSIX':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSSEVEN':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRPVIEWOLDORDERSEIGHT':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRNOPLACESFOUND':
				$result = __('No Locations found', 'vikrentitems');
				break;
			case 'VRJSDELPLACES':
				$result = __('Remove every selected Location', 'vikrentitems');
				break;
			case 'VRPVIEWPLACESONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRNOIVAFOUND':
				$result = __('No Tax rates Found', 'vikrentitems');
				break;
			case 'VRJSDELIVA':
				$result = __('Remove every selected Tax Rate', 'vikrentitems');
				break;
			case 'VRPVIEWIVAONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPVIEWIVATWO':
				$result = __('tax Rate', 'vikrentitems');
				break;
			case 'VRNOCATEGORIESFOUND':
				$result = __('No Categories found', 'vikrentitems');
				break;
			case 'VRJSDELCATEGORIES':
				$result = __('Remove every selected Category', 'vikrentitems');
				break;
			case 'VRPVIEWCATEGORIESONE':
				$result = __('Category Name', 'vikrentitems');
				break;
			case 'VRNOCARATFOUND':
				$result = __('No Characteristics found', 'vikrentitems');
				break;
			case 'VRJSDELCARAT':
				$result = __('Remove every selected Characteristic', 'vikrentitems');
				break;
			case 'VRPVIEWCARATONE':
				$result = __('Characteristic Name', 'vikrentitems');
				break;
			case 'VRPVIEWCARATTWO':
				$result = __('Icon', 'vikrentitems');
				break;
			case 'VRPVIEWCARATTHREE':
				$result = __('Text', 'vikrentitems');
				break;
			case 'VRPVIEWCARATFOUR':
				$result = __('Text Alignment', 'vikrentitems');
				break;
			case 'VRNOOPTIONALSFOUND':
				$result = __('No Options found', 'vikrentitems');
				break;
			case 'VRJSDELOPTIONALS':
				$result = __('Remove every selected Option', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSTWO':
				$result = __('Description', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSTHREE':
				$result = __('Price', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSFOUR':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSFIVE':
				$result = __('Per Day', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSSIX':
				$result = __('Allowed Quantity', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSSEVEN':
				$result = __('Image', 'vikrentitems');
				break;
			case 'VRPVIEWOPTIONALSEIGHT':
				$result = __('Maximum Cost', 'vikrentitems');
				break;
			case 'VRNOPRICESFOUND':
				$result = __('No Prices Found', 'vikrentitems');
				break;
			case 'VRJSDELPRICES':
				$result = __('Remove every selected Price ? Each Tax Rate with one of these prices will become null.', 'vikrentitems');
				break;
			case 'VRPVIEWPRICESONE':
				$result = __('Price Name', 'vikrentitems');
				break;
			case 'VRPVIEWPRICESTWO':
				$result = __('Price Attributes', 'vikrentitems');
				break;
			case 'VRPVIEWPRICESTHREE':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRJSDELBUSY':
				$result = __('Delete Reservation', 'vikrentitems');
				break;
			case 'VRPEDITBUSYONE':
				$result = __('Order\'s data not found', 'vikrentitems');
				break;
			case 'VRPEDITBUSYTWO':
				$result = __('Order date', 'vikrentitems');
				break;
			case 'VRPEDITBUSYTHREE':
				$result = __('Rental for', 'vikrentitems');
				break;
			case 'VRPEDITBUSYFOUR':
				$result = __('Pickup Date', 'vikrentitems');
				break;
			case 'VRPEDITBUSYFIVE':
				$result = __('At H:M', 'vikrentitems');
				break;
			case 'VRPEDITBUSYSIX':
				$result = __('Drop Off Date', 'vikrentitems');
				break;
			case 'VRPEDITBUSYSEVEN':
				$result = __('Prices', 'vikrentitems');
				break;
			case 'VRPEDITBUSYEIGHT':
				$result = __('Options', 'vikrentitems');
				break;
			case 'VRNEWPLACEONE':
				$result = __('Location Name', 'vikrentitems');
				break;
			case 'VREDITPLACEONE':
				$result = __('Location Name', 'vikrentitems');
				break;
			case 'VREDITORDERONE':
				$result = __('Order Date', 'vikrentitems');
				break;
			case 'VREDITORDERTWO':
				$result = __('Purchaser Info', 'vikrentitems');
				break;
			case 'VREDITORDERTHREE':
				$result = __('Item', 'vikrentitems');
				break;
			case 'VREDITORDERFOUR':
				$result = __('Days of Rental', 'vikrentitems');
				break;
			case 'VREDITORDERFIVE':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VREDITORDERSIX':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VREDITORDERSEVEN':
				$result = __('Rented Items', 'vikrentitems');
				break;
			case 'VREDITORDEREIGHT':
				$result = __('Options', 'vikrentitems');
				break;
			case 'VREDITORDERNINE':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VREDITORDERTEN':
				$result = __('Pickup/Drop Off Fee', 'vikrentitems');
				break;
			case 'VRNEWIVAONE':
				$result = __('Tax Rate Name', 'vikrentitems');
				break;
			case 'VRNEWIVATWO':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRNEWPRICEONE':
				$result = __('Price Name', 'vikrentitems');
				break;
			case 'VRNEWPRICETWO':
				$result = __('Price Attributes', 'vikrentitems');
				break;
			case 'VRNEWPRICETHREE':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRNEWCATONE':
				$result = __('Category Name', 'vikrentitems');
				break;
			case 'VRNEWCARATONE':
				$result = __('Characteristic Name', 'vikrentitems');
				break;
			case 'VRNEWCARATTWO':
				$result = __('Characteristic Icon', 'vikrentitems');
				break;
			case 'VRNEWCARATTHREE':
				$result = __('Tooltip Text', 'vikrentitems');
				break;
			case 'VRNEWCARATFOUR':
				$result = __('Write Text on', 'vikrentitems');
				break;
			case 'VRNEWCARATFIVE':
				$result = __('the Left', 'vikrentitems');
				break;
			case 'VRNEWCARATSIX':
				$result = __('the Right', 'vikrentitems');
				break;
			case 'VRNEWCARATSEVEN':
				$result = __('Bottom, Center', 'vikrentitems');
				break;
			case 'VRNEWOPTONE':
				$result = __('Option Name', 'vikrentitems');
				break;
			case 'VRNEWOPTTWO':
				$result = __('Option Description', 'vikrentitems');
				break;
			case 'VRNEWOPTTHREE':
				$result = __('Option Price', 'vikrentitems');
				break;
			case 'VRNEWOPTFOUR':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRNEWOPTFIVE':
				$result = __('Daily Cost', 'vikrentitems');
				break;
			case 'VRNEWOPTSIX':
				$result = __('Selectable Quantity', 'vikrentitems');
				break;
			case 'VRNEWOPTSEVEN':
				$result = __('Option Image', 'vikrentitems');
				break;
			case 'VRNEWOPTEIGHT':
				$result = __('Maximum Cost', 'vikrentitems');
				break;
			case 'VRNEWOPTNINE':
				$result = __('Resize Image', 'vikrentitems');
				break;
			case 'VRNEWOPTTEN':
				$result = __('If Larger than', 'vikrentitems');
				break;
			case 'VRNEWITEMONE':
				$result = __('Item Category', 'vikrentitems');
				break;
			case 'VRNEWITEMTWO':
				$result = __('Pickup Locations', 'vikrentitems');
				break;
			case 'VRNEWITEMTHREE':
				$result = __('Item Characteristics', 'vikrentitems');
				break;
			case 'VRNEWITEMFOUR':
				$result = __('Item Options', 'vikrentitems');
				break;
			case 'VRNEWITEMFIVE':
				$result = __('Item Name', 'vikrentitems');
				break;
			case 'VRNEWITEMSIX':
				$result = __('Item Image', 'vikrentitems');
				break;
			case 'VRNEWITEMSEVEN':
				$result = __('Item Description', 'vikrentitems');
				break;
			case 'VRNEWITEMEIGHT':
				$result = __('Item Available', 'vikrentitems');
				break;
			case 'VRNEWITEMNINE':
				$result = __('Item Units', 'vikrentitems');
				break;
			case 'VRNOTARFOUND':
				$result = __('No Fares found', 'vikrentitems');
				break;
			case 'VRJSDELTAR':
				$result = __('Remove every selected Fare', 'vikrentitems');
				break;
			case 'VRPVIEWTARONE':
				$result = __('Fare for days', 'vikrentitems');
				break;
			case 'VRPVIEWTARTWO':
				$result = __('Update Fares', 'vikrentitems');
				break;
			case 'VRIONFIGONEONE':
				$result = __('Always Open', 'vikrentitems');
				break;
			case 'VRIONFIGONETWO':
				$result = __('Time', 'vikrentitems');
				break;
			case 'VRIONFIGONETHREE':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRIONFIGONEFOUR':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRIONFIGONEFIVE':
				$result = __('Rentals Enabled', 'vikrentitems');
				break;
			case 'VRIONFIGONESIX':
				$result = __('Rentals Disabled Message', 'vikrentitems');
				break;
			case 'VRIONFIGONESEVEN':
				$result = __('Shop Opening Time', 'vikrentitems');
				break;
			case 'VRIONFIGONEEIGHT':
				$result = __('Hours of Extended Gratuity Period', 'vikrentitems');
				break;
			case 'VRIONFIGONENINE':
				$result = __('Dropped Off item is available after', 'vikrentitems');
				break;
			case 'VRIONFIGONETEN':
				$result = __('Choose Pickup Location', 'vikrentitems');
				break;
			case 'VRIONFIGONEELEVEN':
				$result = __('Pickup/Drop Off Date Format', 'vikrentitems');
				break;
			case 'VRIONFIGONETWELVE':
				$result = __('DD/MM/YYYY', 'vikrentitems');
				break;
			case 'VRIONFIGONETENTHREE':
				$result = __('YYYY/MM/DD', 'vikrentitems');
				break;
			case 'VRIONFIGONETENFOUR':
				$result = __('Choose Item Category', 'vikrentitems');
				break;
			case 'VRIONFIGONETENFIVE':
				$result = __('Token Form Order Submit', 'vikrentitems');
				break;
			case 'VRIONFIGONETENSIX':
				$result = __('Admin e-Mail', 'vikrentitems');
				break;
			case 'VRIONFIGONETENSEVEN':
				$result = __('Minutes of Waiting for the Payment', 'vikrentitems');
				break;
			case 'VRIONFIGONETENEIGHT':
				$result = __('hours', 'vikrentitems');
				break;
			case 'VRIONFIGTWOONE':
				$result = __('Enable Paypal', 'vikrentitems');
				break;
			case 'VRIONFIGTWOTWO':
				$result = __('Payments Account<br/><small>(for Gateways like Paypal)</small>', 'vikrentitems');
				break;
			case 'VRIONFIGTWOTHREE':
				$result = __('Pay Entire Amount', 'vikrentitems');
				break;
			case 'VRIONFIGTWOFOUR':
				$result = __('Leave a deposit of', 'vikrentitems');
				break;
			case 'VRIONFIGTWOFIVE':
				$result = __('Prices Tax Included', 'vikrentitems');
				break;
			case 'VRIONFIGTWOSIX':
				$result = __('Payment Transaction Name', 'vikrentitems');
				break;
			case 'VRIONFIGTHREEONE':
				$result = __('Company Name', 'vikrentitems');
				break;
			case 'VRIONFIGTHREETWO':
				$result = __('Front Title Tag', 'vikrentitems');
				break;
			case 'VRIONFIGTHREETHREE':
				$result = __('Front Title Tag Class', 'vikrentitems');
				break;
			case 'VRIONFIGTHREEFOUR':
				$result = __('Search Button Text', 'vikrentitems');
				break;
			case 'VRIONFIGTHREEFIVE':
				$result = __('Search Button Class', 'vikrentitems');
				break;
			case 'VRIONFIGTHREESIX':
				$result = __('Show VikRentItems Footer', 'vikrentitems');
				break;
			case 'VRIONFIGTHREESEVEN':
				$result = __('Opening Page Text', 'vikrentitems');
				break;
			case 'VRIONFIGTHREEEIGHT':
				$result = __('Closing Page Text', 'vikrentitems');
				break;
			case 'VRIONFIGFOURONE':
				$result = __('Enable Removed Orders Saving', 'vikrentitems');
				break;
			case 'VRIONFIGFOURTWO':
				$result = __('Enable Search Statistics', 'vikrentitems');
				break;
			case 'VRIONFIGFOURTHREE':
				$result = __('Send Searches Notifies to Admin', 'vikrentitems');
				break;
			case 'VRIONFIGFOURFOUR':
				$result = __('Disclaimer', 'vikrentitems');
				break;
			case 'VRIONFIGFOURLOGO':
				$result = __('Company Logo', 'vikrentitems');
				break;
			case 'VRIONFIGFOURORDMAILFOOTER':
				$result = __('Footer Text Order eMail', 'vikrentitems');
				break;
			case 'NESSUNAIVA':
				$result = __('No Tax Rates Found', 'vikrentitems');
				break;
			case 'ASKFISCCODE':
				$result = __('Ask Italian Fiscal Code', 'vikrentitems');
				break;
			case 'VRIONFIGTHREECURNAME':
				$result = __('Currency Name', 'vikrentitems');
				break;
			case 'VRIONFIGTHREECURSYMB':
				$result = __('Currency Symbol', 'vikrentitems');
				break;
			case 'VRIONFIGTHREECURCODEPP':
				$result = __('Transactions Currency Code', 'vikrentitems');
				break;
			case 'VRPCHOOSEBUSYORDATE':
				$result = __('Reservation Date', 'vikrentitems');
				break;
			case 'VRPCHOOSEBUSYCAVAIL':
				$result = __('Units Available', 'vikrentitems');
				break;
			case 'VRNOLOCFEES':
				$result = __('No results', 'vikrentitems');
				break;
			case 'VRJSDELLOCFEE':
				$result = __('Confirm', 'vikrentitems');
				break;
			case 'VRPVIEWPLOCFEEONE':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRPVIEWPLOCFEETWO':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRPVIEWPLOCFEETHREE':
				$result = __('Charge', 'vikrentitems');
				break;
			case 'VRPVIEWPLOCFEEFOUR':
				$result = __('Daily', 'vikrentitems');
				break;
			case 'VRYES':
				$result = __('Yes', 'vikrentitems');
				break;
			case 'VRNO':
				$result = __('No', 'vikrentitems');
				break;
			case 'VRNEWLOCFEEONE':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRNEWLOCFEETWO':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRNEWLOCFEETHREE':
				$result = __('Cost', 'vikrentitems');
				break;
			case 'VRNEWLOCFEEFOUR':
				$result = __('Daily Cost', 'vikrentitems');
				break;
			case 'VRNEWLOCFEEFIVE':
				$result = __('Tax Rate', 'vikrentitems');
				break;
			case 'VRLOCFEESAVED':
				$result = __('Saved', 'vikrentitems');
				break;
			case 'VRLOCFEEUPDATE':
				$result = __('Updated', 'vikrentitems');
				break;
			case 'VRMAINLOCFEESTITLE':
				$result = __('Vik Rent Item - Pickup Drop Off Fees', 'vikrentitems');
				break;
			case 'VRMAINLOCFEESNEW':
				$result = __('Vik Rent Items - Pickup Drop Off Fees', 'vikrentitems');
				break;
			case 'VRMAINLOCFEESEDIT':
				$result = __('Vik Rent Items - Pickup Drop Off Fees', 'vikrentitems');
				break;
			case 'VRMAINLOCFEEDEL':
				$result = __('Delete', 'vikrentitems');
				break;
			case 'VRMAINLOCFEEEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINLOCFEENEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINSEASONSTITLE':
				$result = __('Vik Rent Items - Special Prices', 'vikrentitems');
				break;
			case 'VRMAINSEASONSDEL':
				$result = __('Delete', 'vikrentitems');
				break;
			case 'VRMAINSEASONSEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINSEASONSNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINSEASONTITLENEW':
				$result = __('Vik Rent Items - New Special Price', 'vikrentitems');
				break;
			case 'VRMAINSEASONTITLEEDIT':
				$result = __('Vik Rent Items - Edit Special Price', 'vikrentitems');
				break;
			case 'VRMAINLOCFEETITLENEW':
				$result = __('Vik Rent Items - New Pickup Drop Off Fee', 'vikrentitems');
				break;
			case 'VRMAINLOCFEETITLEEDIT':
				$result = __('Vik Rent Items - Edit Pickup Drop Off Fee', 'vikrentitems');
				break;
			case 'VRSETORDCONFIRMED':
				$result = __('Set to Confirmed', 'vikrentitems');
				break;
			case 'VRPAYMENTMETHOD':
				$result = __('Method of Payment', 'vikrentitems');
				break;
			case 'VRUSEJUTILITY':
				$result = __('Send order emails with JUtility', 'vikrentitems');
				break;
			case 'VRIONFIGTHREENINE':
				$result = __('Show Partly Reserved Days', 'vikrentitems');
				break;
			case 'VRIONFIGTHREETEN':
				$result = __('Number of Months to Show', 'vikrentitems');
				break;
			case 'VRLIBONE':
				$result = __('Order Received on the', 'vikrentitems');
				break;
			case 'VRLIBTWO':
				$result = __('Purchaser Info', 'vikrentitems');
				break;
			case 'VRLIBTHREE':
				$result = __('Rented Items', 'vikrentitems');
				break;
			case 'VRLIBFOUR':
				$result = __('Pickup Date', 'vikrentitems');
				break;
			case 'VRLIBFIVE':
				$result = __('Drop Off Date', 'vikrentitems');
				break;
			case 'VRLIBSIX':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRLIBSEVEN':
				$result = __('Order Status', 'vikrentitems');
				break;
			case 'VRLIBEIGHT':
				$result = __('Order Date', 'vikrentitems');
				break;
			case 'VRLIBNINE':
				$result = __('Personal Details', 'vikrentitems');
				break;
			case 'VRLIBTEN':
				$result = __('Rented Items', 'vikrentitems');
				break;
			case 'VRLIBELEVEN':
				$result = __('Pickup Date', 'vikrentitems');
				break;
			case 'VRLIBTWELVE':
				$result = __('Drop Off Date', 'vikrentitems');
				break;
			case 'VRLIBTENTHREE':
				$result = __('to see your order details, please visit the following link', 'vikrentitems');
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
			case 'VRRITIROITEM':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRRETURNITEMORD':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRNOSEASONS':
				$result = __('No Special Prices found', 'vikrentitems');
				break;
			case 'VRJSDELSEASONS':
				$result = __('Confirm', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSONE':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSTWO':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSTHREE':
				$result = __('Type', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSFOUR':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSFIVE':
				$result = __('Charge', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSSIX':
				$result = __('Discount', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSSEVEN':
				$result = __('Location', 'vikrentitems');
				break;
			case 'VRNOITEMSFOUNDSEASONS':
				$result = __('No Items found', 'vikrentitems');
				break;
			case 'VRNEWSEASONONE':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRNEWSEASONTWO':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRNEWSEASONTHREE':
				$result = __('Type', 'vikrentitems');
				break;
			case 'VRNEWSEASONFOUR':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRNEWSEASONFIVE':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRNEWSEASONSIX':
				$result = __('Charge', 'vikrentitems');
				break;
			case 'VRNEWSEASONSEVEN':
				$result = __('Discount', 'vikrentitems');
				break;
			case 'VRNEWSEASONEIGHT':
				$result = __('Locations', 'vikrentitems');
				break;
			case 'ERRINVDATESEASON':
				$result = __('Invalid Dates', 'vikrentitems');
				break;
			case 'ERRINVDATEITEMSLOCSEASON':
				$result = __('Season with same dates, locations and Items already exists', 'vikrentitems');
				break;
			case 'VRSEASONSAVED':
				$result = __('Special Price Saved', 'vikrentitems');
				break;
			case 'VRSEASONUPDATED':
				$result = __('Updated', 'vikrentitems');
				break;
			case 'VRSEASONANY':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRNOPAYMENTS':
				$result = __('No Payment Methods found', 'vikrentitems');
				break;
			case 'VRJSDELPAYMENTS':
				$result = __('Confirm', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSTWO':
				$result = __('File', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSTHREE':
				$result = __('Note', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSFOUR':
				$result = __('Cost', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSFIVE':
				$result = __('Published', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTONE':
				$result = __('Payment Name', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTTWO':
				$result = __('File Class', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTTHREE':
				$result = __('Published', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTFOUR':
				$result = __('Cost', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTFIVE':
				$result = __('Note', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTSIX':
				$result = __('Yes', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTSEVEN':
				$result = __('No', 'vikrentitems');
				break;
			case 'VRLIBPAYNAME':
				$result = __('Payment Method', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTEIGHT':
				$result = __('Auto-Set Order Confirmed', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTNINE':
				$result = __('Always Show Note', 'vikrentitems');
				break;
			case 'VRLOCFEETOPAY':
				$result = __('Pickup/Drop Off Fee', 'vikrentitems');
				break;
			case 'VRNOFIELDSFOUND':
				$result = __('No Custom Fields Found', 'vikrentitems');
				break;
			case 'VRPVIEWCUSTOMFONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPVIEWCUSTOMFTWO':
				$result = __('Type', 'vikrentitems');
				break;
			case 'VRPVIEWCUSTOMFTHREE':
				$result = __('Required', 'vikrentitems');
				break;
			case 'VRPVIEWCUSTOMFFOUR':
				$result = __('Ordering', 'vikrentitems');
				break;
			case 'VRPVIEWCUSTOMFFIVE':
				$result = __('e-Mail', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFONE':
				$result = __('Field Name', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFTWO':
				$result = __('Type', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFTHREE':
				$result = __('Text', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFFOUR':
				$result = __('Select', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFFIVE':
				$result = __('Checkbox', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFSIX':
				$result = __('Required', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFSEVEN':
				$result = __('is e-Mail', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFEIGHT':
				$result = __('Popup Link', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFNINE':
				$result = __('Add Answer', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFTEN':
				$result = __('Textarea', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMFTITLE':
				$result = __('Vik Rent Items - Custom Fields', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMFDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMFEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMFNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMENUONE':
				$result = __('Rental', 'vikrentitems');
				break;
			case 'VRMENUTWO':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRMENUTHREE':
				$result = __('Orders', 'vikrentitems');
				break;
			case 'VRMENUFOUR':
				$result = __('Global', 'vikrentitems');
				break;
			case 'VRMENUFIVE':
				$result = __('Types of Price', 'vikrentitems');
				break;
			case 'VRMENUSIX':
				$result = __('Categories', 'vikrentitems');
				break;
			case 'VRMENUSEVEN':
				$result = __('Orders List', 'vikrentitems');
				break;
			case 'VRMENUEIGHT':
				$result = __('Search Statistics', 'vikrentitems');
				break;
			case 'VRMENUNINE':
				$result = __('Tax Rates', 'vikrentitems');
				break;
			case 'VRMENUTEN':
				$result = __('Items List', 'vikrentitems');
				break;
			case 'VRMENUELEVEN':
				$result = __('Removed Orders', 'vikrentitems');
				break;
			case 'VRMENUTWELVE':
				$result = __('Configuration', 'vikrentitems');
				break;
			case 'VRMENUTENTHREE':
				$result = __('Pickup/Drop Off Locations', 'vikrentitems');
				break;
			case 'VRMENUTENFOUR':
				$result = __('Characteristics', 'vikrentitems');
				break;
			case 'VRMENUTENFIVE':
				$result = __('Item Options', 'vikrentitems');
				break;
			case 'VRMENUTENSIX':
				$result = __('Pickup/Drop Off Fees', 'vikrentitems');
				break;
			case 'VRMENUTENSEVEN':
				$result = __('Special Prices', 'vikrentitems');
				break;
			case 'VRMENUTENEIGHT':
				$result = __('Payment Methods', 'vikrentitems');
				break;
			case 'VRMENUTENNINE':
				$result = __('Overview', 'vikrentitems');
				break;
			case 'VRMENUTENTEN':
				$result = __('Custom Fields', 'vikrentitems');
				break;
			case 'ORDER_NAME':
				$result = __('Name', 'vikrentitems');
				break;
			case 'ORDER_LNAME':
				$result = __('Last Name', 'vikrentitems');
				break;
			case 'ORDER_EMAIL':
				$result = __('e-Mail', 'vikrentitems');
				break;
			case 'ORDER_PHONE':
				$result = __('Phone', 'vikrentitems');
				break;
			case 'ORDER_ADDRESS':
				$result = __('Address', 'vikrentitems');
				break;
			case 'ORDER_ZIP':
				$result = __('Zip Code', 'vikrentitems');
				break;
			case 'ORDER_CITY':
				$result = __('City', 'vikrentitems');
				break;
			case 'ORDER_STATE':
				$result = __('Country', 'vikrentitems');
				break;
			case 'ORDER_DBIRTH':
				$result = __('Date of Birth', 'vikrentitems');
				break;
			case 'ORDER_FLIGHTNUM':
				$result = __('Flight Number', 'vikrentitems');
				break;
			case 'ORDER_NOTES':
				$result = __('Notes', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_MENU':
				$result = __('VikRentItems', 'vikrentitems');
				break;
			case 'VRNEWITEMDROPLOC':
				$result = __('Drop Off Locations', 'vikrentitems');
				break;
			case 'VRMOREIMAGES':
				$result = __('More Images', 'vikrentitems');
				break;
			case 'VRADDIMAGES':
				$result = __('Add Images', 'vikrentitems');
				break;
			case 'VRRESIZEIMAGES':
				$result = __('Resize Images', 'vikrentitems');
				break;
			case 'VRIONFIGREQUIRELOGIN':
				$result = __('Require Login', 'vikrentitems');
				break;
			case 'VRISEASON':
				$result = __('Season', 'vikrentitems');
				break;
			case 'VRIWEEKDAYS':
				$result = __('Week Days', 'vikrentitems');
				break;
			case 'VRISEASONDAYS':
				$result = __('Days of the Week', 'vikrentitems');
				break;
			case 'VRISUNDAY':
				$result = __('Sunday', 'vikrentitems');
				break;
			case 'VRIMONDAY':
				$result = __('Monday', 'vikrentitems');
				break;
			case 'VRITUESDAY':
				$result = __('Tuesday', 'vikrentitems');
				break;
			case 'VRIWEDNESDAY':
				$result = __('Wednesday', 'vikrentitems');
				break;
			case 'VRITHURSDAY':
				$result = __('Thursday', 'vikrentitems');
				break;
			case 'VRIFRIDAY':
				$result = __('Friday', 'vikrentitems');
				break;
			case 'VRISATURDAY':
				$result = __('Saturday', 'vikrentitems');
				break;
			case 'VRISPRICESHELP':
				$result = __('Insert a starting and an ending date (Season) or select one or more days of the week (Week Days). Only one filter is required. Provide a Season and Week Days to combine the filters', 'vikrentitems');
				break;
			case 'VRISPRICESHELPTITLE':
				$result = __('Seasons and Week Days', 'vikrentitems');
				break;
			case 'VRISPNAME':
				$result = __('Special Price Name', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSPNAME':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPSHOWSEASONSWDAYS':
				$result = __('Week Days', 'vikrentitems');
				break;
			case 'VRIPLACELAT':
				$result = __('Latitude', 'vikrentitems');
				break;
			case 'VRIPLACELNG':
				$result = __('Longitude', 'vikrentitems');
				break;
			case 'VRIPLACEDESCR':
				$result = __('Description', 'vikrentitems');
				break;
			case 'VRIHOURLYFARES':
				$result = __('Hourly Fares', 'vikrentitems');
				break;
			case 'VRIDAILYFARES':
				$result = __('Daily Fares', 'vikrentitems');
				break;
			case 'VRIHOURS':
				$result = __('Hours', 'vikrentitems');
				break;
			case 'VRIHOURLYPRICES':
				$result = __('Hourly Price(s)', 'vikrentitems');
				break;
			case 'VRIPVIEWTARHOURS':
				$result = __('Fare for Hours', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFSEPARATOR':
				$result = __('Separator', 'vikrentitems');
				break;
			case 'VRISEPDRIVERD':
				$result = __('Billing Information', 'vikrentitems');
				break;
			case 'VRIONFIGONEJQUERY':
				$result = __('Load jQuery Library', 'vikrentitems');
				break;
			case 'VRIONFIGONECALENDAR':
				$result = __('Calendar Type', 'vikrentitems');
				break;
			case 'VRIORDERNUMBER':
				$result = __('Order Number', 'vikrentitems');
				break;
			case 'VRIORDERDETAILS':
				$result = __('Order Details', 'vikrentitems');
				break;
			case 'VRNEWCATDESCR':
				$result = __('Description', 'vikrentitems');
				break;
			case 'VRPVIEWCATEGORIESDESCR':
				$result = __('Description', 'vikrentitems');
				break;
			case 'VRIPAYMENTSHELPCONFIRMTXT':
				$result = __('Auto-Set Order as Confirmed', 'vikrentitems');
				break;
			case 'VRIPAYMENTSHELPCONFIRM':
				$result = __('If this setting is enabled, when the user selects this payment, the order status will be set to Confirmed when saving the reservation. This setting should always be disabled for methods of payment that need to validate a server response after a credit card payment', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTCHARGEORDISC':
				$result = __('Charge/Discount', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTCHARGEPLUS':
				$result = __('Charge +', 'vikrentitems');
				break;
			case 'VRNEWPAYMENTDISCMINUS':
				$result = __('Discount -', 'vikrentitems');
				break;
			case 'VRPSHOWPAYMENTSCHARGEORDISC':
				$result = __('Charge/Discount', 'vikrentitems');
				break;
			case 'VRIPLACEOPENTIME':
				$result = __('Opening Time', 'vikrentitems');
				break;
			case 'VRIPLACEOPENTIMETXT':
				$result = __('The opening time for Pickup and-or Drop Off. If empty, the global opening time of the configuration will be applied', 'vikrentitems');
				break;
			case 'VRIPLACEOPENTIMEFROM':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRIPLACEOPENTIMETO':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRISPONLYPICKINCL':
				$result = __('Pickup Date must be<br/>&nbsp;after the begin<br/>&nbsp;of the Season', 'vikrentitems');
				break;
			case 'VRIHOURSCHARGES':
				$result = __('Extra Hours Charges', 'vikrentitems');
				break;
			case 'VRIEXTRARHOURS':
				$result = __('Extra Hours of Rental', 'vikrentitems');
				break;
			case 'VRIHOURLYCHARGES':
				$result = __('Hourly Charge(s)', 'vikrentitems');
				break;
			case 'VRISHCHARGESHELP':
				$result = __('These charges will be applied to the daily fares that are longer than one day. A rental from the 20th of December at 8am to the 22nd at 11am can be charged by those 3 extra hours. The setting of the configuration Hours of Extended Gratuity Period will be considered as well. So in that case, the charge for the 3 extra hours will be applied only if that setting is 0, 1 or 2, not if it is 3 or higher. From and To need an integer value, for example From 3 To 6 hours', 'vikrentitems');
				break;
			case 'VRISELVEHICLE':
				$result = __('Select Item', 'vikrentitems');
				break;
			case 'VRIONFIGEHOURSBASP':
				$result = __('Apply Extra Hours Charges', 'vikrentitems');
				break;
			case 'VRIONFIGEHOURSBEFORESP':
				$result = __('Before the Special Prices', 'vikrentitems');
				break;
			case 'VRIONFIGEHOURSAFTERSP':
				$result = __('After the Special Prices', 'vikrentitems');
				break;
			case 'VRINEWOPTFORCESEL':
				$result = __('Always Selected', 'vikrentitems');
				break;
			case 'VRINEWOPTFORCEVALT':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRINEWOPTFORCEVALTPDAY':
				$result = __('per Day of Rental', 'vikrentitems');
				break;
			case 'VRIONFIGONECOUPONS':
				$result = __('Enable Coupons', 'vikrentitems');
				break;
			case 'VRINOCOUPONSFOUND':
				$result = __('No coupon found', 'vikrentitems');
				break;
			case 'VRIPVIEWCOUPONSONE':
				$result = __('Code', 'vikrentitems');
				break;
			case 'VRIPVIEWCOUPONSTWO':
				$result = __('Type', 'vikrentitems');
				break;
			case 'VRIPVIEWCOUPONSTHREE':
				$result = __('Valid Dates', 'vikrentitems');
				break;
			case 'VRIPVIEWCOUPONSFOUR':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRIPVIEWCOUPONSFIVE':
				$result = __('Min. Order Total', 'vikrentitems');
				break;
			case 'VRICOUPONTYPEPERMANENT':
				$result = __('Permanent', 'vikrentitems');
				break;
			case 'VRICOUPONTYPEGIFT':
				$result = __('Gift', 'vikrentitems');
				break;
			case 'VRICOUPONALWAYSVALID':
				$result = __('Always Valid', 'vikrentitems');
				break;
			case 'VRICOUPONALLVEHICLES':
				$result = __('All Items', 'vikrentitems');
				break;
			case 'VRINEWCOUPONONE':
				$result = __('Coupon Code', 'vikrentitems');
				break;
			case 'VRINEWCOUPONTWO':
				$result = __('Coupon Type', 'vikrentitems');
				break;
			case 'VRINEWCOUPONTHREE':
				$result = __('Percent or Total', 'vikrentitems');
				break;
			case 'VRINEWCOUPONFOUR':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRINEWCOUPONFIVE':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRINEWCOUPONSIX':
				$result = __('Valid Dates', 'vikrentitems');
				break;
			case 'VRINEWCOUPONSEVEN':
				$result = __('Min. Order Total', 'vikrentitems');
				break;
			case 'VRINEWCOUPONEIGHT':
				$result = __('All', 'vikrentitems');
				break;
			case 'VRINEWCOUPONNINE':
				$result = __('If there are no dates, the coupon will be always valid', 'vikrentitems');
				break;
			case 'VRICOUPONEXISTS':
				$result = __('Error, the coupon code already exists', 'vikrentitems');
				break;
			case 'VRICOUPONSAVEOK':
				$result = __('Coupon Successfully Saved', 'vikrentitems');
				break;
			case 'VRIMENUFARES':
				$result = __('Pricing', 'vikrentitems');
				break;
			case 'VRIMENUDASHBOARD':
				$result = __('Dashboard', 'vikrentitems');
				break;
			case 'VRIMENUPRICESTABLE':
				$result = __('Fares Table', 'vikrentitems');
				break;
			case 'VRIMENUQUICKRES':
				$result = __('Calendar', 'vikrentitems');
				break;
			case 'VRIMENUCOUPONS':
				$result = __('Coupons', 'vikrentitems');
				break;
			case 'VRMAINCOUPONTITLE':
				$result = __('Vik Rent Items - Coupons', 'vikrentitems');
				break;
			case 'VRMAINCOUPONNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINCOUPONEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINCOUPONDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINDASHBOARDTITLE':
				$result = __('Vik Rent Items - Dashboard', 'vikrentitems');
				break;
			case 'VRIDASHUPCRES':
				$result = __('Upcoming Rentals', 'vikrentitems');
				break;
			case 'VRIDASHALLPLACES':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRIDASHPICKUPLOC':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRIDASHUPRESONE':
				$result = __('ID', 'vikrentitems');
				break;
			case 'VRIDASHUPRESTWO':
				$result = __('Rented Items', 'vikrentitems');
				break;
			case 'VRIDASHUPRESTHREE':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRIDASHUPRESFOUR':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRIDASHUPRESFIVE':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRIDASHSTATS':
				$result = __('Report', 'vikrentitems');
				break;
			case 'VRIDASHNOPRICES':
				$result = __('Types of Price', 'vikrentitems');
				break;
			case 'VRIDASHNOLOCATIONS':
				$result = __('Pickup - Drop Off Locations', 'vikrentitems');
				break;
			case 'VRIDASHNOCATEGORIES':
				$result = __('Categories', 'vikrentitems');
				break;
			case 'VRIDASHNOITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRIDASHNODAILYFARES':
				$result = __('Daily Fares', 'vikrentitems');
				break;
			case 'VRIDASHTOTRESCONF':
				$result = __('Confirmed Reservations', 'vikrentitems');
				break;
			case 'VRIDASHTOTRESPEND':
				$result = __('Standby Reservations', 'vikrentitems');
				break;
			case 'VRICOUPON':
				$result = __('Coupon', 'vikrentitems');
				break;
			case 'VRIONFIGTHEME':
				$result = __('Theme', 'vikrentitems');
				break;
			case 'VRSPECIALPRICEVALHELP':
				$result = __('This value will be added to or deducted from the cost of every day of rental that is affected by this Special Price', 'vikrentitems');
				break;
			case 'VRNEWSEASONVALUEOVERRIDE':
				$result = __('Value Overrides', 'vikrentitems');
				break;
			case 'VRNEWSEASONNIGHTSOVR':
				$result = __('Days of Rental', 'vikrentitems');
				break;
			case 'VRNEWSEASONVALUESOVR':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRNEWSEASONVALUEOVERRIDEHELP':
				$result = __('The default absolute or percentage value can be different depending on the days of rental. For example you can override the default value of the Special Price for 7 Days of rental and set it to a lower charge or to a higher discount. Do not override the default value for always applying the same charge or discount regardless the length of rental in the days affected by this Special Price.', 'vikrentitems');
				break;
			case 'VRNEWSEASONADDOVERRIDE':
				$result = __('Add Value Override', 'vikrentitems');
				break;
			case 'VRLOCFEEINVERT':
				$result = __('Apply if the Locations are inverted', 'vikrentitems');
				break;
			case 'VRLOCFEECOSTOVERRIDE':
				$result = __('Cost Overrides', 'vikrentitems');
				break;
			case 'VRLOCFEECOSTOVERRIDEHELP':
				$result = __('The default Cost can be overwritten depending on the number of days of rental. Do not create overrides fow always applying the default cost.', 'vikrentitems');
				break;
			case 'VRLOCFEECOSTOVERRIDEADD':
				$result = __('Add Cost Override', 'vikrentitems');
				break;
			case 'VRLOCFEECOSTOVERRIDEDAYS':
				$result = __('Days of Rental', 'vikrentitems');
				break;
			case 'VRLOCFEECOSTOVERRIDECOST':
				$result = __('Cost', 'vikrentitems');
				break;
			case 'VRIUSTSTARTINGFROM':
				$result = __('Custom Starting From Price', 'vikrentitems');
				break;
			case 'VRIUSTSTARTINGFROMHELP':
				$result = __('The View List and the Item Details page will show this price as the Starting From Price. Leave this field empty for making the program automatically calculate the Starting From Price', 'vikrentitems');
				break;
			case 'VRQRCUSTMAIL':
				$result = __('Customer e-Mail', 'vikrentitems');
				break;
			case 'VRIRESENDORDEMAIL':
				$result = __('Re-Send eMail', 'vikrentitems');
				break;
			case 'VRORDERMAILRESENT':
				$result = __('Order eMail re-sent to %s', 'vikrentitems');
				break;
			case 'VRORDERMAILRESENTNOREC':
				$result = __('Error, Customer eMail Address is empty', 'vikrentitems');
				break;
			case 'VRIORDERING':
				$result = __('Ordering', 'vikrentitems');
				break;
			case 'VRNEWPLACECLOSINGDAYS':
				$result = __('Closing Days', 'vikrentitems');
				break;
			case 'VRNEWPLACECLOSINGDAYSHELP':
				$result = __('Insert the dates when this location is closed for Pickup and Drop Off. Right syntax: yyyy-mm-dd,yyyy-mm-dd,..etc..Use the calendar for avoiding syntax errors.', 'vikrentitems');
				break;
			case 'VRNEWPLACECLOSINGDAYSADD':
				$result = __('Add Date', 'vikrentitems');
				break;
			case 'VRIONFIGUSDATEFORMAT':
				$result = __('MM/DD/YYYY', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFDATETYPE':
				$result = __('Date', 'vikrentitems');
				break;
			case 'VRIQUICKRESMOREOPTIONS':
				$result = __('Show More Options', 'vikrentitems');
				break;
			case 'VRIQUICKRESMOREOPTIONSHIDE':
				$result = __('Hide Options', 'vikrentitems');
				break;
			case 'VRIQUICKRESORDSTATUS':
				$result = __('Order Status', 'vikrentitems');
				break;
			case 'VRIQUICKRESMETHODOFPAYMENT':
				$result = __('Payment Method', 'vikrentitems');
				break;
			case 'VRIQUICKRESNONE':
				$result = __('-undefined-', 'vikrentitems');
				break;
			case 'VRIQUICKRESPOPULATECUSTOMINFO':
				$result = __('Populate Customer Information Fields', 'vikrentitems');
				break;
			case 'VRIQUICKRESWARNSTANDBY':
				$result = __('Order Status: Waiting for the payment. Choose one type of price and eventually some of the Options. Then click on Save to complete the Standby - Quick Reservation. ', 'vikrentitems');
				break;
			case 'VRIQUICKRESWARNSTANDBYSENDMAIL':
				$result = __('An email will be sent to the Customer eMail Address with the link for making the payment', 'vikrentitems');
				break;
			case 'VRIQUICKRESNOTIFYCUST':
				$result = __('Notify Customer via eMail', 'vikrentitems');
				break;
			case 'VRIFRONTVIEWSTANDBYORDER':
				$result = __('View Front Site Order Page', 'vikrentitems');
				break;
			case 'VRIQUICKRESNOLOCATION':
				$result = __('-undefined-', 'vikrentitems');
				break;
			case 'VRIQUICKRESPICKUPLOC':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRIQUICKRESDROPOFFLOC':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRIQUICKRESWARNCONFIRMED':
				$result = __('Order Status: Confirmed. Choose one type of price and eventually some of the Options. Then click on Save to complete the Quick Reservation. An email will be sent to the Customer eMail Address with the reservation details', 'vikrentitems');
				break;
			case 'VRISENDPDF':
				$result = __('Attach PDF to the order eMail', 'vikrentitems');
				break;
			case 'VRIDOWNLOADPDF':
				$result = __('Download PDF', 'vikrentitems');
				break;
			case 'VRIRESENDORDEMAILANDPDF':
				$result = __('Re-send Order eMail + PDF', 'vikrentitems');
				break;
			case 'VRICUSTEMAILADDR':
				$result = __('eMail Address', 'vikrentitems');
				break;
			case 'VRPEDITBUSYORDERNUMBER':
				$result = __('Order Number', 'vikrentitems');
				break;
			case 'VRIAGREEMENTTITLE':
				$result = __('Contract/Agreement', 'vikrentitems');
				break;
			case 'VRIAGREEMENTSAMPLETEXT':
				$result = __('This agreement between %s %s and %s was made on the %s and is valid until the %s.', 'vikrentitems');
				break;
			case 'VRIAGREEMENTSAMPLETEXTMORE':
				$result = __('1. Condition of Premises<br/><br/>The lessor shall keep the premises in a good state of repair and fit for habitation during the tenancy and shall comply with any enactment respecting standards of health, safety or housing notwithstanding any state of non-repair that may have existed at the time the agreement was entered into.<br/><br/>2. Services<br/><br/>Where the lessor provides or pays for a service or facility to the lessee that is reasonably related to the lessee\'s continued use and enjoyment of the premises, such as heat, water, electric power, gas, appliances, garbage collection, sewers or elevators, the lessor shall not discontinue providing or paying for that service to the lessee without permission from the Director.<br/><br/>3. Good Behaviour<br/><br/>The lessee and any person admitted to the premises by the lessee shall conduct themselves in such a manner as not to interfere with the possession, occupancy or quiet enjoyment of other lessees.<br/><br/>4. Obligation of the Lessee<br/><br/>The lessee shall be responsible for the ordinary cleanliness of the interior of the premises and for the repair of damage caused by any willful or negligent act of the lessee or of any person whom the lessee permits on the premises, but not for damage caused by normal wear and tear.', 'vikrentitems');
				break;
			case 'VRIPDFDAYS':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRIPDFNETPRICE':
				$result = __('Net Price', 'vikrentitems');
				break;
			case 'VRIPDFTAX':
				$result = __('Tax', 'vikrentitems');
				break;
			case 'VRIPDFTOTALPRICE':
				$result = __('Total Price', 'vikrentitems');
				break;
			case 'VRISPKEEPFIRSTDAYRATE':
				$result = __('Keep First Day Rate', 'vikrentitems');
				break;
			case 'VRISPKEEPFIRSTDAYRATEHELP':
				$result = __('If this setting is enabled, the first day of rental will be the one giving the same rate to all the other days. If the first day of rental is not included in this Special Price and this setting is enabled, then every other day of rental will be considered as not included even if they were. When this setting is enabled just the first day of rental is considered and the week days do not affect this setting.', 'vikrentitems');
				break;
			case 'VRINEWOPTFORCEVALIFDAYS':
				$result = __('If Days of Rental Greater than', 'vikrentitems');
				break;
			case 'VRIPLACEOVERRIDETAX':
				$result = __('Override Tax Rate', 'vikrentitems');
				break;
			case 'VRIPLACEOVERRIDETAXTXT':
				$result = __('If a Tax Rate is specified for this location, when this will be selected as Pickup, the Rental Fare, the Options and the Pickup-Drop Off Fees will take this tax rate instead of the one that they have assigned. Leave this field empty for using the global tax rate of each cost', 'vikrentitems');
				break;
			case 'VRIASKQUANTITY':
				$result = __('Ask for Quantity', 'vikrentitems');
				break;
			case 'VRPCHOOSEBUSYITEMQUANT':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSTOTITEMS':
				$result = __('Number of Items', 'vikrentitems');
				break;
			case 'VRNEWITEMPARAMETERS':
				$result = __('Item Parameters', 'vikrentitems');
				break;
			case 'VRICUSTSTARTINGFROMTEXT':
				$result = __('Custom Starting From Text', 'vikrentitems');
				break;
			case 'VRI_PERDAY':
				$result = __('per Day', 'vikrentitems');
				break;
			case 'VRI_PERHOUR':
				$result = __('per Hour', 'vikrentitems');
				break;
			case 'VRIHOURLYCALENDAR':
				$result = __('Show Hourly Calendar', 'vikrentitems');
				break;
			case 'VRIMENUDISCOUNTS':
				$result = __('Discounts per Quantity', 'vikrentitems');
				break;
			case 'VRIDISCNAME':
				$result = __('Discount Name', 'vikrentitems');
				break;
			case 'VRINEWDISCQUANT':
				$result = __('Items Quantity', 'vikrentitems');
				break;
			case 'VRIDISCOUNTSTITLETXT':
				$result = __('Discounts per Quantity', 'vikrentitems');
				break;
			case 'VRIDISCOUNTSHELP':
				$result = __('The discount value, absolute or percentage, will be deducted from the rental cost of the single item in case the quantity ordered is the same as (or greater than if the setting Apply when more units are requested is ON) the one specified in this discount. The discount will be applied to the final rental rate for the requested period, after appliying the Special Prices', 'vikrentitems');
				break;
			case 'VRNEWDISCVALUE':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRIDISCIFGREATQUANT':
				$result = __('Apply when more units are requested', 'vikrentitems');
				break;
			case 'VRINEWDISCITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRINODISCOUNTS':
				$result = __('No Discounts found', 'vikrentitems');
				break;
			case 'VRPSHOWDISCOUNTSONE':
				$result = __('Discount Name', 'vikrentitems');
				break;
			case 'VRPSHOWDISCOUNTSTWO':
				$result = __('Items Quantity', 'vikrentitems');
				break;
			case 'VRPSHOWDISCOUNTSTHREE':
				$result = __('If more units', 'vikrentitems');
				break;
			case 'VRPSHOWDISCOUNTSFOUR':
				$result = __('Value', 'vikrentitems');
				break;
			case 'VRIDISCOUNTSAVED':
				$result = __('Discount per Quantity Saved Successfully', 'vikrentitems');
				break;
			case 'VRIDISCOUNTUPDATED':
				$result = __('Discount per Quantity Updated Successfully', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTSTITLE':
				$result = __('Vik Rent Items - Discounts per Quantity', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTTITLENEW':
				$result = __('Vik Rent Items - New Discount', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTTITLEEDIT':
				$result = __('Vik Rent Items - Edit Discount', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTSDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTSEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINDISCOUNTSNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRIPARAMITEMSHOWDISCQUANTAB':
				$result = __('Show Discounts per Quantity', 'vikrentitems');
				break;
			case 'VRMENUTIMESLOTS':
				$result = __('Time Slots', 'vikrentitems');
				break;
			case 'VRPSHOWTIMESLOTSONE':
				$result = __('Time Slot Name', 'vikrentitems');
				break;
			case 'VRPSHOWTIMESLOTSTWO':
				$result = __('Pickup Time', 'vikrentitems');
				break;
			case 'VRPSHOWTIMESLOTSTHREE':
				$result = __('Drop Off Time', 'vikrentitems');
				break;
			case 'VRPSHOWTIMESLOTSFOUR':
				$result = __('Global Search', 'vikrentitems');
				break;
			case 'VRINOTIMESLOTS':
				$result = __('No Time Slot found', 'vikrentitems');
				break;
			case 'VRITIMESLOTSTITLETXT':
				$result = __('Time Slots', 'vikrentitems');
				break;
			case 'VRITIMESLOTSHELP':
				$result = __('With the time slots you can create selectable options in the search forms that will determine the length of the rental, Half-Day, Full-Day, 3 Days etc.. for example. The system will automatically set the pick up and drop off dates and times of the rent, to the time of the fields Pickup and Drop Off and will add the number of days of rental to the Pick Up Date to calculate the Drop Off Date. The rental rates will be calculated depending on the time difference between the two times and the number of days of rental so make sure you have a fare in the Fares Table for the length of this rental. Leave the Days of Rental as 0 in case you want to use Hourly Fares.', 'vikrentitems');
				break;
			case 'VRITIMESLOTSAVED':
				$result = __('Time Slot Successfully Saved', 'vikrentitems');
				break;
			case 'VRITIMESLOTUPDATED':
				$result = __('Time Slot Successfully Updated', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTSTITLE':
				$result = __('Vik Rent Items - Time Slots', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTSDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTSEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTSNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTTITLENEW':
				$result = __('Vik Rent Items - New Time Slot', 'vikrentitems');
				break;
			case 'VRMAINTIMESLOTTITLEEDIT':
				$result = __('Vik Rent Items - Edit Time Slot', 'vikrentitems');
				break;
			case 'VRITIMESLOTNAME':
				$result = __('Time Slot Name', 'vikrentitems');
				break;
			case 'VRINEWTIMESLOTFROM':
				$result = __('Pickup Time', 'vikrentitems');
				break;
			case 'VRINEWTIMESLOTTO':
				$result = __('Drop Off Time', 'vikrentitems');
				break;
			case 'VRINEWTIMESLOTITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRINEWTIMESLOTGLOBAL':
				$result = __('Use in Global Search', 'vikrentitems');
				break;
			case 'VRIUSETIMESLOTS':
				$result = __('Use Time Slots', 'vikrentitems');
				break;
			case 'VRIUSETIMESLOTSHELP':
				$result = __('If this setting is disabled the users will have to select the pickup and drop off time, otherwise, if the setting is ON, the Time Slots for this Item will be shown in its details page. In case there aren\'t any, the hours and the minutes will have to be selected', 'vikrentitems');
				break;
			case 'VRIAUTOSETDROPDAY':
				$result = __('Set Drop Off Date to', 'vikrentitems');
				break;
			case 'VRIDAYSAFTERPICKUP':
				$result = __('Days After the Pickup Date', 'vikrentitems');
				break;
			case 'VRMENURELATIONS':
				$result = __('Related Items', 'vikrentitems');
				break;
			case 'VRINORELATIONS':
				$result = __('No Relations Found', 'vikrentitems');
				break;
			case 'VRPSHOWRELATIONSONE':
				$result = __('Relation Name', 'vikrentitems');
				break;
			case 'VRPSHOWRELATIONSTWO':
				$result = __('Number of Items', 'vikrentitems');
				break;
			case 'VRPSHOWRELATIONSTHREE':
				$result = __('Related to Number of Items', 'vikrentitems');
				break;
			case 'VRIRELATIONNAME':
				$result = __('Relation Name', 'vikrentitems');
				break;
			case 'VRINEWRELATIONSEL':
				$result = __('Relations', 'vikrentitems');
				break;
			case 'VRIRELATIONSAVED':
				$result = __('Relations Successfully Saved', 'vikrentitems');
				break;
			case 'VRIRELATIONUPDATED':
				$result = __('Relations Successfully Updated', 'vikrentitems');
				break;
			case 'VRMAINRELATIONSTITLE':
				$result = __('Vik Rent Items - Related Items', 'vikrentitems');
				break;
			case 'VRMAINRELATIONTITLENEW':
				$result = __('Vik Rent Items - New Relation', 'vikrentitems');
				break;
			case 'VRMAINRELATIONTITLEEDIT':
				$result = __('Vik Rent Items - Edit Relation', 'vikrentitems');
				break;
			case 'VRMAINRELATIONSDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINRELATIONSEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINRELATIONSNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRCONFIGFORCEPICKUP':
				$result = __('Force Pick Up Time', 'vikrentitems');
				break;
			case 'VRCONFIGFORCEDROPOFF':
				$result = __('Force Drop Off Time', 'vikrentitems');
				break;
			case 'VRITIMESLOTDAYS':
				$result = __('Days of Rental', 'vikrentitems');
				break;
			case 'VRICONFIGTIMEFORMAT':
				$result = __('Time Format', 'vikrentitems');
				break;
			case 'VRICONFIGTIMEFUSA':
				$result = __('12 Hours AM/PM', 'vikrentitems');
				break;
			case 'VRICONFIGTIMEFEUR':
				$result = __('24 Hours', 'vikrentitems');
				break;
			case 'VRICLOSEITEM':
				$result = __('Block all Units', 'vikrentitems');
				break;
			case 'VRISUBMCLOSEITEM':
				$result = __('Block Item', 'vikrentitems');
				break;
			case 'VRICONFIGGLOBCLOSEDAYS':
				$result = __('Global Closing Days', 'vikrentitems');
				break;
			case 'VRICONFIGCLOSESINGLED':
				$result = __('Single Day', 'vikrentitems');
				break;
			case 'VRICONFIGCLOSEWEEKLY':
				$result = __('Weekly', 'vikrentitems');
				break;
			case 'VRICONFIGADDCLOSEDAY':
				$result = __('Add', 'vikrentitems');
				break;
			case 'VRNEWOPTSISSPECIFICATION':
				$result = __('Selectable Specification', 'vikrentitems');
				break;
			case 'VRNEWOPTSISSPECIFICATIONEXPL':
				$result = __('In case of Specifications of the Item, add some:', 'vikrentitems');
				break;
			case 'VRADDSPECIFICATION':
				$result = __('Add Specification', 'vikrentitems');
				break;
			case 'VRNEWSPECNAME':
				$result = __('Choice Title', 'vikrentitems');
				break;
			case 'VRNEWSPECCOST':
				$result = __('Choice Cost', 'vikrentitems');
				break;
			case 'VRPANELFIVE':
				$result = __('Delivery Service', 'vikrentitems');
				break;
			case 'VRICONFDELBASEADDR':
				$result = __('Company Base Address', 'vikrentitems');
				break;
			case 'VRICONFDELBASEADDREXP':
				$result = __('Specify your complete base address that will be used through the Google Maps APIs to calculate the distance to the Delivery address. A valid address should be formatted like this: 15 example street, city, country. Make sure to have entered your own Google Maps API Key in the Configuration page, and start typing your base address. Then click the Validate Address button to let the system gather the required information. Then click the Save button once done, to update the Configuration settings and enable the Delivery feature.', 'vikrentitems');
				break;
			case 'VRICONFDELBASEADDRVALIDATE':
				$result = __('Validate Address', 'vikrentitems');
				break;
			case 'VRICONFDELCALCUNIT':
				$result = __('Calculation Unit', 'vikrentitems');
				break;
			case 'VRICONFDELCALCUNITKM':
				$result = __('Kilometers', 'vikrentitems');
				break;
			case 'VRICONFDELCALCUNITMILES':
				$result = __('Miles', 'vikrentitems');
				break;
			case 'VRICONFDELCOSTPERUNIT':
				$result = __('Cost per Calculated Unit', 'vikrentitems');
				break;
			case 'VRICONFDELMAXCOST':
				$result = __('Maximum Cost', 'vikrentitems');
				break;
			case 'VRICHECKDELADDGMAPERR1':
				$result = __('Company Base Address is empty! Please close this window and enter one in the Configuration page.', 'vikrentitems');
				break;
			case 'VRICHECKDELADDGMAPERR2':
				$result = __('Error, Invalid Company Base Address. Result: ', 'vikrentitems');
				break;
			case 'VRIUSEDELIVERY':
				$result = __('Delivery Service', 'vikrentitems');
				break;
			case 'VRICONFDELBASELAT':
				$result = __('Latitude', 'vikrentitems');
				break;
			case 'VRICONFDELBASELNG':
				$result = __('Longitude', 'vikrentitems');
				break;
			case 'VRICONFDELMAXDIST':
				$result = __('Maximum Distance', 'vikrentitems');
				break;
			case 'VRICONFDELIVERYNOTES':
				$result = __('Delivery Notes', 'vikrentitems');
				break;
			case 'VRICONFDELROUNDDIST':
				$result = __('Round Distance to Integer', 'vikrentitems');
				break;
			case 'VRICONFDELROUNDCOST':
				$result = __('Round Cost to Integer', 'vikrentitems');
				break;
			case 'VRITEMSHORTDESCR':
				$result = __('Short Description', 'vikrentitems');
				break;
			case 'VRIMAILDELIVERYTO':
				$result = __('Delivery to:', 'vikrentitems');
				break;
			case 'VRIMAILTOTDELIVERY':
				$result = __('Delivery Cost', 'vikrentitems');
				break;
			case 'VRIQUICKRESDELIVERYADDR':
				$result = __('Delivery Address', 'vikrentitems');
				break;
			case 'VRIQUICKRESDELIVERYDIST':
				$result = __('Delivery Distance', 'vikrentitems');
				break;
			case 'VRIOVERDELIVERY':
				$result = __('Override Cost per Distance:', 'vikrentitems');
				break;
			case 'VIKLOADING':
				$result = __('Loading...', 'vikrentitems');
				break;
			case 'VRIPAYMENTPARAMETERS':
				$result = __('Parameters', 'vikrentitems');
				break;
			case 'VRMAINORDERSEXPORT':
				$result = __('Export Orders', 'vikrentitems');
				break;
			case 'VRMAINEXPORTTITLE':
				$result = __('Vik Rent Items - Export Orders', 'vikrentitems');
				break;
			case 'VREXPORTONE':
				$result = __('From Date', 'vikrentitems');
				break;
			case 'VREXPORTTWO':
				$result = __('To Date', 'vikrentitems');
				break;
			case 'VREXPORTTHREE':
				$result = __('Exportation Type', 'vikrentitems');
				break;
			case 'VREXPORTFOUR':
				$result = __('CSV (for Excel or other Software)', 'vikrentitems');
				break;
			case 'VREXPORTFIVE':
				$result = __('ICS (iCalendar, Google Calendar, Hotmail)', 'vikrentitems');
				break;
			case 'VREXPORTSIX':
				$result = __('Orders Status', 'vikrentitems');
				break;
			case 'VREXPORTSEVEN':
				$result = __('Confirmed', 'vikrentitems');
				break;
			case 'VREXPORTEIGHT':
				$result = __('Confirmed and Pending', 'vikrentitems');
				break;
			case 'VREXPORTNINE':
				$result = __('Export Orders', 'vikrentitems');
				break;
			case 'VREXPORTTEN':
				$result = __('Date Format', 'vikrentitems');
				break;
			case 'VREXPORTELEVEN':
				$result = __('Location', 'vikrentitems');
				break;
			case 'VRIEXPORTERRNOREC':
				$result = __('Error, no records to export...', 'vikrentitems');
				break;
			case 'VRIEXPCSVPICK':
				$result = __('Pickup Date', 'vikrentitems');
				break;
			case 'VRIEXPCSVDROP':
				$result = __('Drop Off Date', 'vikrentitems');
				break;
			case 'VRIEXPCSVITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRIEXPCSVPICKLOC':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRIEXPCSVDROPLOC':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRIEXPCSVCUSTINFO':
				$result = __('Customer Info', 'vikrentitems');
				break;
			case 'VRIEXPCSVPAYMETH':
				$result = __('Payment Method', 'vikrentitems');
				break;
			case 'VRIEXPCSVTOT':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRIEXPCSVTOTPAID':
				$result = __('Total Paid', 'vikrentitems');
				break;
			case 'VRIEXPCSVORDSTATUS':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRIICSEXPSUMMARY':
				$result = __('Rental @ %s', 'vikrentitems');
				break;
			case 'VRICHANGEPAYLABEL':
				$result = __('::Change method of payment::', 'vikrentitems');
				break;
			case 'VRICHANGEPAYCONFIRM':
				$result = __('Change method of payment to ', 'vikrentitems');
				break;
			case 'VRIFILTERBYITEMS':
				$result = __('Filter Orders by Item name(s)', 'vikrentitems');
				break;
			case 'VRIFILTERBYITEMSEX':
				$result = __('Item Name, Item Name 2', 'vikrentitems');
				break;
			case 'VRIERRNOITFILTNAME':
				$result = __('No valid Item ID was found with the name(s) -%s-. Separate the names of the items rented out with a comma', 'vikrentitems');
				break;
			case 'VRICONFIGFLUSHSESSION':
				$result = __('Renew Session', 'vikrentitems');
				break;
			case 'VRICONFIGFLUSHSESSIONCONF':
				$result = __('The PHP Session will be renewed and the new settings will be applied but any logged in user will be logged out. Proceed?', 'vikrentitems');
				break;
			case 'VRIPAYMENTLOGTOGGLE':
				$result = __('Payments Log', 'vikrentitems');
				break;
			case 'VRNEWSEASONROUNDCOST':
				$result = __('Round to Integer', 'vikrentitems');
				break;
			case 'VRNEWSEASONROUNDCOSTNO':
				$result = __('- disabled -', 'vikrentitems');
				break;
			case 'VRNEWSEASONROUNDCOSTUP':
				$result = __('Round Up', 'vikrentitems');
				break;
			case 'VRNEWSEASONROUNDCOSTDOWN':
				$result = __('Round Down', 'vikrentitems');
				break;
			case 'VRIVERSION':
				$result = __('VikRent Items v.%s - Powered by', 'vikrentitems');
				break;
			case 'VRSAVECLOSE':
				$result = __('Save & Close', 'vikrentitems');
				break;
			case 'VRMENUTRANSLATIONS':
				$result = __('Translations', 'vikrentitems');
				break;
			case 'VRIMAINTRANSLATIONSTITLE':
				$result = __('Vik Rent Items - Translations', 'vikrentitems');
				break;
			case 'VRIGETTRANSLATIONS':
				$result = __('Load Translations', 'vikrentitems');
				break;
			case 'VRITRANSLATIONERRONELANG':
				$result = __('There is only one content-language enabled for this Joomla-site so translations cannot be created.', 'vikrentitems');
				break;
			case 'VRITANSLATIONSCHANGESCONF':
				$result = __('Some changes were made to the translations. Proceed without Saving?', 'vikrentitems');
				break;
			case 'VRITRANSLATIONSELTABLEMESS':
				$result = __('No Contents Selected for Translation', 'vikrentitems');
				break;
			case 'VRITRANSLATIONDEFLANG':
				$result = __('Default Language', 'vikrentitems');
				break;
			case 'VRITRANSLATIONERRINVTABLE':
				$result = __('Error: Invalid or Empty Table Set for Translation', 'vikrentitems');
				break;
			case 'VRITRANSLSAVEDOK':
				$result = __('Translations Saved!', 'vikrentitems');
				break;
			case 'VRITRANSLATIONINISTATUS':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRIINIMISSINGFILE':
				$result = __('Missing Translation File', 'vikrentitems');
				break;
			case 'VRIINIDEFINITIONS':
				$result = __('Definitions', 'vikrentitems');
				break;
			case 'VRIINIPATH':
				$result = __('Path', 'vikrentitems');
				break;
			case 'VRITEMSEFALIAS':
				$result = __('SEF Alias', 'vikrentitems');
				break;
			case 'VRIDELCONFIRM':
				$result = __('Some records will be removed. Proceed?', 'vikrentitems');
				break;
			case 'VRICONFIGBOOKINGPART':
				$result = __('Booking', 'vikrentitems');
				break;
			case 'VRICONFIGSEARCHPART':
				$result = __('Search/Rental Parameters', 'vikrentitems');
				break;
			case 'VRICONFIGSYSTEMPART':
				$result = __('System', 'vikrentitems');
				break;
			case 'VRICONFENMULTILANG':
				$result = __('Enable Multi-Language', 'vikrentitems');
				break;
			case 'VRICONFSEFROUTER':
				$result = __('SEF Router', 'vikrentitems');
				break;
			case 'VRICONFIGCURRENCYPART':
				$result = __('Currency', 'vikrentitems');
				break;
			case 'VRICONFIGPAYMPART':
				$result = __('Taxes and Payments', 'vikrentitems');
				break;
			case 'VRICONFIGAPPEARPART':
				$result = __('Appearance and Texts', 'vikrentitems');
				break;
			case 'VRISEASONAFFECTEDITEMS':
				$result = __('Affected Items', 'vikrentitems');
				break;
			case 'VRIRATESOVWITEM':
				$result = __('Item', 'vikrentitems');
				break;
			case 'VRIAFFANYITEM':
				$result = __('Any Item', 'vikrentitems');
				break;
			case 'VRISEASONPRICING':
				$result = __('Pricing Modifications', 'vikrentitems');
				break;
			case 'VRISPPROMOTIONLABEL':
				$result = __('Promotion', 'vikrentitems');
				break;
			case 'VRCONFIGEDITTMPLFILE':
				$result = __('Edit Template File', 'vikrentitems');
				break;
			case 'VRCONFIGEMAILTEMPLATE':
				$result = __('Customer Email', 'vikrentitems');
				break;
			case 'VRCONFIGPDFTEMPLATE':
				$result = __('Customer PDF', 'vikrentitems');
				break;
			case 'VRIUPDTMPLFILEERR':
				$result = __('Error: empty or invalid Template File Path', 'vikrentitems');
				break;
			case 'VRIUPDTMPLFILENOBYTES':
				$result = __('Error: 0 bytes written on file', 'vikrentitems');
				break;
			case 'VRIUPDTMPLFILEOK':
				$result = __('Template File Successfully Updated', 'vikrentitems');
				break;
			case 'VRIEDITTMPLFILE':
				$result = __('Edit Template File Source Code', 'vikrentitems');
				break;
			case 'VRITMPLFILENOTREAD':
				$result = __('Error reading the source code of the file', 'vikrentitems');
				break;
			case 'VRISAVETMPLFILE':
				$result = __('Save & Write Source Code', 'vikrentitems');
				break;
			case 'VRIISNOMINATIVE':
				$result = __('Nominative', 'vikrentitems');
				break;
			case 'VRIISPHONENUMBER':
				$result = __('Phone Number', 'vikrentitems');
				break;
			case 'VRIDASHTODAYPICKUP':
				$result = __('Collecting Today', 'vikrentitems');
				break;
			case 'VRIDASHTODAYDROPOFF':
				$result = __('Returning Today', 'vikrentitems');
				break;
			case 'VRIDASHITEMSLOCKED':
				$result = __('Items Locked - Waiting for Confirmation', 'vikrentitems');
				break;
			case 'VRIDASHLOCKUNTIL':
				$result = __('Locked Until', 'vikrentitems');
				break;
			case 'VRIDASHUNLOCK':
				$result = __('Unlock', 'vikrentitems');
				break;
			case 'VRIDRIVERNOMINATIVE':
				$result = __('Customer Name', 'vikrentitems');
				break;
			case 'VRNEWITEMSEFPARAMETERS':
				$result = __('SEF Parameters', 'vikrentitems');
				break;
			case 'VRIPARAMPAGETITLE':
				$result = __('Custom Page Title', 'vikrentitems');
				break;
			case 'VRIPARAMPAGETITLEBEFORECUR':
				$result = __('Add it Before the Current Page Title', 'vikrentitems');
				break;
			case 'VRIPARAMPAGETITLEAFTERCUR':
				$result = __('Add it After the Current Page Title', 'vikrentitems');
				break;
			case 'VRIPARAMPAGETITLEREPLACECUR':
				$result = __('Replace the Current Page Title', 'vikrentitems');
				break;
			case 'VRIPARAMKEYWORDSMETATAG':
				$result = __('Keywords Meta Tag', 'vikrentitems');
				break;
			case 'VRIPARAMDESCRIPTIONMETATAG':
				$result = __('Description Meta Tag', 'vikrentitems');
				break;
			case 'VRIARSEFALIAS':
				$result = __('SEF Alias', 'vikrentitems');
				break;
			case 'VRCONFIGNUMDECIMALS':
				$result = __('Number of Decimals', 'vikrentitems');
				break;
			case 'VRCONFIGNUMDECSEPARATOR':
				$result = __('Decimal Separator', 'vikrentitems');
				break;
			case 'VRCONFIGNUMTHOSEPARATOR':
				$result = __('Thousand Separator', 'vikrentitems');
				break;
			case 'VRCONFIGFIRSTWDAY':
				$result = __('Calendars First Day of the Week', 'vikrentitems');
				break;
			case 'VRIFILTINAME':
				$result = __('Item Name', 'vikrentitems');
				break;
			case 'VRIFILTCATEGORY':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRIFILTCATEGORYANY':
				$result = __('Any Category', 'vikrentitems');
				break;
			case 'VRINEWCUSTOMFCOUNTRY':
				$result = __('Country', 'vikrentitems');
				break;
			case 'VRIFILTCNAMECNUMB':
				$result = __('Customer Name/Order ID', 'vikrentitems');
				break;
			case 'VRLEAVEDEPOSIT':
				$result = __('Leave a deposit of ', 'vikrentitems');
				break;
			case 'VRIAMOUNTPAID':
				$result = __('Amount Paid', 'vikrentitems');
				break;
			case 'VRITOTALREMAINING':
				$result = __('Remaining Balance', 'vikrentitems');
				break;
			case 'VRITODAYBOOKINGS':
				$result = __('Rentals for today at any time', 'vikrentitems');
				break;
			case 'VRICONFIGTIMEFNONE':
				$result = __('Hide Time', 'vikrentitems');
				break;
			case 'VRCONFIGONEDROPDPLUS':
				$result = __('Minimum # Days of Rental', 'vikrentitems');
				break;
			case 'VRCONFIGMINDAYSADVANCE':
				$result = __('Days in Advance for bookings', 'vikrentitems');
				break;
			case 'VRCONFIGMAXDATEFUTURE':
				$result = __('Maximum Date in the Future from today', 'vikrentitems');
				break;
			case 'VRCONFIGMAXDATEDAYS':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRCONFIGMAXDATEWEEKS':
				$result = __('Weeks', 'vikrentitems');
				break;
			case 'VRCONFIGMAXDATEMONTHS':
				$result = __('Months', 'vikrentitems');
				break;
			case 'VRCONFIGMAXDATEYEARS':
				$result = __('Years', 'vikrentitems');
				break;
			case 'VRIMINITEMQUANTITY':
				$result = __('Minimum Quantity Allowed', 'vikrentitems');
				break;
			case 'VRCONFIGTHUMBSIZE':
				$result = __('Thumbnails Size', 'vikrentitems');
				break;
			case 'VRIAPPLYDISCOUNT':
				$result = __('Apply Discount', 'vikrentitems');
				break;
			case 'VRIAPPLYDISCOUNTSAVE':
				$result = __('Save', 'vikrentitems');
				break;
			case 'VRIADMINDISCOUNT':
				$result = __('Discount', 'vikrentitems');
				break;
			case 'VRIICALLINK':
				$result = __('iCal Sync Link', 'vikrentitems');
				break;
			case 'VRIICALKEY':
				$result = __('iCal Secret Key', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATEITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATEOPTIONS':
				$result = __('Options, Taxes, Fees', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATECATEGORIES':
				$result = __('Categories', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATESPECIALPRICES':
				$result = __('Special Prices', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATETYPESPRICE':
				$result = __('Types of Price', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATEPAYMENTS':
				$result = __('Payment Methods', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATECFIELDS':
				$result = __('Custom Fields', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATETIMESLOTS':
				$result = __('Time Slots', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATECHARACTERISTICS':
				$result = __('Characteristics', 'vikrentitems');
				break;
			case 'VRIINIEXPLCOM_VIKRENTITEMS_FRONT':
				$result = __('Component Front-End', 'vikrentitems');
				break;
			case 'VRIINIEXPLCOM_VIKRENTITEMS_ADMIN':
				$result = __('Component Back-End', 'vikrentitems');
				break;
			case 'VRIINIEXPLCOM_VIKRENTITEMS_ADMIN_SYS':
				$result = __('Component Back-End SYS', 'vikrentitems');
				break;
			case 'VRIINIEXPLMOD_VIKRENTITEMS_SEARCH':
				$result = __('Search Module', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATETEXTS':
				$result = __('Texts', 'vikrentitems');
				break;
			case 'VRIXMLCONTENT':
				$result = __('Content', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATELOCATIONS':
				$result = __('Locations', 'vikrentitems');
				break;
			case 'VRIADMINNOTESTOGGLE':
				$result = __('Administrator Notes', 'vikrentitems');
				break;
			case 'VRIADMINNOTESUPD':
				$result = __('Update', 'vikrentitems');
				break;
			case 'VRISHOWUNITSBOOKED':
				$result = __('Show Units Booked', 'vikrentitems');
				break;
			case 'VRISHOWUNITSLEFT':
				$result = __('Show Units Remaining', 'vikrentitems');
				break;
			case 'VRQRCUSTNOMINATIVE':
				$result = __('Customer Name', 'vikrentitems');
				break;
			case 'VRQRCUSTCOUNTRY':
				$result = __('Customer Country', 'vikrentitems');
				break;
			case 'VRMAINDEFAULTCLONE':
				$result = __('Clone Item', 'vikrentitems');
				break;
			case 'VRICLONEITEMCOPY':
				$result = __('(Copy)', 'vikrentitems');
				break;
			case 'VRICLONEITEMOK':
				$result = __('The Item was cloned successfully', 'vikrentitems');
				break;
			case 'VRICONFIGSENDEMAILWHEN':
				$result = __('Send Emails When', 'vikrentitems');
				break;
			case 'VRICONFIGSMSSENDWHENCONFPEND':
				$result = __('Reservation is Pending or Confirmed', 'vikrentitems');
				break;
			case 'VRICONFIGSMSSENDWHENCONF':
				$result = __('Reservation is Confirmed', 'vikrentitems');
				break;
			case 'VRNEWOPTONLYONCE':
				$result = __('Apply once per Order', 'vikrentitems');
				break;
			case 'VRIEXTRAEMAILITEM':
				$result = __('Additional Email Address', 'vikrentitems');
				break;
			case 'VRNEWITEMISGROUP':
				$result = __('Group/Set of Items', 'vikrentitems');
				break;
			case 'VRNEWITEMISGROUPSEL':
				$result = __('Items included in this Set', 'vikrentitems');
				break;
			case 'VRNEWITEMISGROUPUNITS':
				$result = __('Units', 'vikrentitems');
				break;
			case 'VRIUPDITEMNOMOREAGROUP':
				$result = __('This Item used to be a Group/Set of Items but it isn\'t anymore. The relations with the other items have been removed but the availability records haven\'t been modified. If you received orders for this item, it would be better to delete it and to create a new one.', 'vikrentitems');
				break;
			case 'VRIUPDITEMDIFFGROUP':
				$result = __('This Item is now a Group/Set of different Items than before. If you received orders for this item, it would be better to delete it and to create a new one to avoid problems with the availability.', 'vikrentitems');
				break;
			case 'VRITEMISAGROUP':
				$result = __('Group/Set of multiple Items', 'vikrentitems');
				break;
			case 'VRNEWSEASONVALUESOVREMORE':
				$result = __('and more', 'vikrentitems');
				break;
			case 'VRISENDEREMAIL':
				$result = __('Sender e-Mail', 'vikrentitems');
				break;
			case 'VRMAINTITLEUPDATEPROGRAM':
				$result = __('Vik Rent Items - Software Update', 'vikrentitems');
				break;
			case 'VRCHECKINGVERSION':
				$result = __('Checking Version...', 'vikrentitems');
				break;
			case 'VRDOWNLOADUPDATEBTN1':
				$result = __('Download Update & Install', 'vikrentitems');
				break;
			case 'VRDOWNLOADUPDATEBTN0':
				$result = __('Download & Re-Install', 'vikrentitems');
				break;
			case 'VRIPRATTRHELP':
				$result = __('The attribute is an additional information you can pass to the Type of Price for any number of days of rental. It is NOT a mandatory field and it can be left empty. An example of attribute could be &quot;Km Included&quot;. From the page Fares Table, you will be able to specify the value for the attribute for any number of days of rental. For example, from 1 to 7 days: &quot;100Km/day&quot;. From 8 to 14 days: &quot;150Km/day&quot;. The attribute will be visible to the customer during the reservation process.', 'vikrentitems');
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
			case 'VRIVIEWBOOKINGDET':
				$result = __('View Details', 'vikrentitems');
				break;
			case 'VRIQUICKRESLOCATIONS':
				$result = __('Locations', 'vikrentitems');
				break;
			case 'VRIRENTCUSTRATEPLAN':
				$result = __('Rental Cost', 'vikrentitems');
				break;
			case 'VRIRENTCUSTRATEPLANADD':
				$result = __('Set Custom Rate', 'vikrentitems');
				break;
			case 'VRIRENTCUSTRATETAXHELP':
				$result = __('Custom Rates should always be inclusive of taxes', 'vikrentitems');
				break;
			case 'VRFILLCUSTFIELDS':
				$result = __('Assign Customer', 'vikrentitems');
				break;
			case 'VRAPPLY':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRISEARCHEXISTCUST':
				$result = __('Existing Customer', 'vikrentitems');
				break;
			case 'VRISEARCHCUSTBY':
				$result = __('Search by PIN or Name', 'vikrentitems');
				break;
			case 'VRDBTEXTROOMCLOSED':
				$result = __('Item Closed', 'vikrentitems');
				break;
			case 'VRSUBMCLOSEROOM':
				$result = __('Close Item', 'vikrentitems');
				break;
			case 'VRCUSTOMERNOMINATIVE':
				$result = __('Customer Name', 'vikrentitems');
				break;
			case 'VRISPYEARTIED':
				$result = __('Tied to the Year', 'vikrentitems');
				break;
			case 'VRISELECTALL':
				$result = __('Select All', 'vikrentitems');
				break;
			case 'VRISPTYPESPRICE':
				$result = __('Types of Price', 'vikrentitems');
				break;
			case 'VRIISPROMOTION':
				$result = __('Promotion', 'vikrentitems');
				break;
			case 'VRIPROMOVALIDITY':
				$result = __('Valid up to', 'vikrentitems');
				break;
			case 'VRIPROMOVALIDITYDAYSADV':
				$result = __('days in advance from Start Date', 'vikrentitems');
				break;
			case 'VRIPROMOTEXT':
				$result = __('Promotion Details', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTER':
				$result = __('Filter by Location', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTERANY':
				$result = __('Any Location', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTERPICK':
				$result = __('Pick-up', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTERDROP':
				$result = __('Drop-off', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTERPICKDROP':
				$result = __('Pick-up or Drop-off', 'vikrentitems');
				break;
			case 'VRIORDERSLOCFILTERBTN':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRIISCOMPANY':
				$result = __('Company Name', 'vikrentitems');
				break;
			case 'VRIISVAT':
				$result = __('VAT ID', 'vikrentitems');
				break;
			case 'VRIISADDRESS':
				$result = __('Address', 'vikrentitems');
				break;
			case 'VRIISCITY':
				$result = __('City', 'vikrentitems');
				break;
			case 'VRIISZIP':
				$result = __('ZIP', 'vikrentitems');
				break;
			case 'VREXPORTDATETYPE':
				$result = __('Date Filter', 'vikrentitems');
				break;
			case 'VREXPORTDATETYPETS':
				$result = __('Order Date', 'vikrentitems');
				break;
			case 'VREXPORTDATETYPEPICK':
				$result = __('Pickup Date', 'vikrentitems');
				break;
			case 'VREXPORTNUMORDS':
				$result = __('Orders to Export: %d', 'vikrentitems');
				break;
			case 'VRIBOOKDETTABDETAILS':
				$result = __('Order Details', 'vikrentitems');
				break;
			case 'VRIBOOKDETTABADMIN':
				$result = __('Administration', 'vikrentitems');
				break;
			case 'VRIBOOKINGCREATEDBY':
				$result = __('Order created by User ID %s', 'vikrentitems');
				break;
			case 'VRSENDEMAILACTION':
				$result = __('Send Custom Email', 'vikrentitems');
				break;
			case 'VRCUSTOMERPHONE':
				$result = __('Phone', 'vikrentitems');
				break;
			case 'VRIBOOKINGLANG':
				$result = __('Language', 'vikrentitems');
				break;
			case 'VRSENDEMAILCUSTSUBJ':
				$result = __('Subject', 'vikrentitems');
				break;
			case 'VRSENDEMAILCUSTCONT':
				$result = __('Message', 'vikrentitems');
				break;
			case 'VRSENDEMAILCUSTATTCH':
				$result = __('Attachment', 'vikrentitems');
				break;
			case 'VRSENDEMAILCUSTFROM':
				$result = __('From Address', 'vikrentitems');
				break;
			case 'VRSENDEMAILERRMISSDATA':
				$result = __('Missing required data for sending the email message.', 'vikrentitems');
				break;
			case 'VRSENDEMAILOK':
				$result = __('The message was sent successfully', 'vikrentitems');
				break;
			case 'VREMAILCUSTFROMTPL':
				$result = __('- Load text from Template -', 'vikrentitems');
				break;
			case 'VREMAILCUSTFROMTPLUSE':
				$result = __('Use Template', 'vikrentitems');
				break;
			case 'VREMAILCUSTFROMTPLRM':
				$result = __('Remove Template', 'vikrentitems');
				break;
			case 'VRSWITCHCWITH':
				$result = __('Switch Item', 'vikrentitems');
				break;
			case 'VRIMENUMANAGEMENT':
				$result = __('Management', 'vikrentitems');
				break;
			case 'VRIMENUCUSTOMERS':
				$result = __('Customers', 'vikrentitems');
				break;
			case 'VRNOCUSTOMERS':
				$result = __('No Customers found', 'vikrentitems');
				break;
			case 'VRCUSTOMERFIRSTNAME':
				$result = __('First Name', 'vikrentitems');
				break;
			case 'VRCUSTOMERLASTNAME':
				$result = __('Last Name', 'vikrentitems');
				break;
			case 'VRCUSTOMEREMAIL':
				$result = __('eMail', 'vikrentitems');
				break;
			case 'VRCUSTOMERPHONE':
				$result = __('Phone', 'vikrentitems');
				break;
			case 'VRCUSTOMERCOUNTRY':
				$result = __('Country', 'vikrentitems');
				break;
			case 'VRCUSTOMERPIN':
				$result = __('PIN', 'vikrentitems');
				break;
			case 'VRCUSTOMERGENERATEPIN':
				$result = __('Generate PIN', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMERSTITLE':
				$result = __('Vik Rent Items - Customers', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMERNEW':
				$result = __('New', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMEREDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINCUSTOMERDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRMAINMANAGECUSTOMERTITLE':
				$result = __('Vik Rent Items - Customer Details', 'vikrentitems');
				break;
			case 'VRERRCUSTOMEREMAILEXISTS':
				$result = __('Customer with the same email address already exists', 'vikrentitems');
				break;
			case 'VRCUSTOMERSAVED':
				$result = __('Customer Saved Successfully', 'vikrentitems');
				break;
			case 'VRCONFIGENABLECUSTOMERPIN':
				$result = __('Enable Customers PIN Code', 'vikrentitems');
				break;
			case 'VRCUSTOMERTOTBOOKINGS':
				$result = __('Total Bookings', 'vikrentitems');
				break;
			case 'VRYOURPIN':
				$result = __('PIN Code', 'vikrentitems');
				break;
			case 'VRICSVEXPCUSTOMERS':
				$result = __('CSV Export', 'vikrentitems');
				break;
			case 'VRICSVEXPCUSTOMERSGET':
				$result = __('Download CSV Export', 'vikrentitems');
				break;
			case 'VRIANYCOUNTRY':
				$result = __('-- Any Country --', 'vikrentitems');
				break;
			case 'VRICUSTOMEREXPSEL':
				$result = __('Export Information about %d selected Customers', 'vikrentitems');
				break;
			case 'VRICUSTOMEREXPALL':
				$result = __('Export Customers Information', 'vikrentitems');
				break;
			case 'VRIMAINEXPCUSTOMERSTITLE':
				$result = __('Vik Rent Items - Export Customers Information', 'vikrentitems');
				break;
			case 'VRICUSTOMEREXPNOTES':
				$result = __('Include Notes', 'vikrentitems');
				break;
			case 'VRICUSTOMEREXPSCANIMG':
				$result = __('Include ID Image Scan URL', 'vikrentitems');
				break;
			case 'VRICUSTOMEREXPPIN':
				$result = __('Include PIN Code', 'vikrentitems');
				break;
			case 'VRINORECORDSCSVCUSTOMERS':
				$result = __('No customer records to export', 'vikrentitems');
				break;
			case 'VRICUSTOMERDETAILS':
				$result = __('Customer Details', 'vikrentitems');
				break;
			case 'VRCUSTOMERADDRESS':
				$result = __('Address', 'vikrentitems');
				break;
			case 'VRCUSTOMERCITY':
				$result = __('City', 'vikrentitems');
				break;
			case 'VRCUSTOMERZIP':
				$result = __('ZIP', 'vikrentitems');
				break;
			case 'VRCUSTOMERDOCTYPE':
				$result = __('ID Type', 'vikrentitems');
				break;
			case 'VRCUSTOMERDOCNUM':
				$result = __('ID Number', 'vikrentitems');
				break;
			case 'VRCUSTOMERDOCIMG':
				$result = __('ID Scan Image', 'vikrentitems');
				break;
			case 'VRCUSTOMERNOTES':
				$result = __('Notes', 'vikrentitems');
				break;
			case 'VRCUSTOMERCOMPANY':
				$result = __('Company Name', 'vikrentitems');
				break;
			case 'VRCUSTOMERCOMPANYVAT':
				$result = __('VAT ID', 'vikrentitems');
				break;
			case 'VRCUSTOMERGENDER':
				$result = __('Gender', 'vikrentitems');
				break;
			case 'VRCUSTOMERGENDERM':
				$result = __('Male', 'vikrentitems');
				break;
			case 'VRCUSTOMERGENDERF':
				$result = __('Female', 'vikrentitems');
				break;
			case 'VRCUSTOMERBDATE':
				$result = __('Date of Birth', 'vikrentitems');
				break;
			case 'VRCUSTOMERPBIRTH':
				$result = __('Place of Birth', 'vikrentitems');
				break;
			case 'VRILOADFA':
				$result = __('Load Font Awesome', 'vikrentitems');
				break;
			case 'VRITEMFILTER':
				$result = __('Filter by Item', 'vikrentitems');
				break;
			case 'VRFILTERBYPAYMENT':
				$result = __('Filter by Payment', 'vikrentitems');
				break;
			case 'VRFILTERBYSTATUS':
				$result = __('Filter by Status', 'vikrentitems');
				break;
			case 'VRFILTERBYDATES':
				$result = __('Filter by Date', 'vikrentitems');
				break;
			case 'VRPVIEWORDERSSEARCHSUBM':
				$result = __('Filter Orders', 'vikrentitems');
				break;
			case 'VRCANCELLED':
				$result = __('Cancelled', 'vikrentitems');
				break;
			case 'VRSHORTMONTHONE':
				$result = __('Jan', 'vikrentitems');
				break;
			case 'VRSHORTMONTHTWO':
				$result = __('Feb', 'vikrentitems');
				break;
			case 'VRSHORTMONTHTHREE':
				$result = __('Mar', 'vikrentitems');
				break;
			case 'VRSHORTMONTHFOUR':
				$result = __('Apr', 'vikrentitems');
				break;
			case 'VRSHORTMONTHFIVE':
				$result = __('May', 'vikrentitems');
				break;
			case 'VRSHORTMONTHSIX':
				$result = __('Jun', 'vikrentitems');
				break;
			case 'VRSHORTMONTHSEVEN':
				$result = __('Jul', 'vikrentitems');
				break;
			case 'VRSHORTMONTHEIGHT':
				$result = __('Aug', 'vikrentitems');
				break;
			case 'VRSHORTMONTHNINE':
				$result = __('Sep', 'vikrentitems');
				break;
			case 'VRSHORTMONTHTEN':
				$result = __('Oct', 'vikrentitems');
				break;
			case 'VRSHORTMONTHELEVEN':
				$result = __('Nov', 'vikrentitems');
				break;
			case 'VRSHORTMONTHTWELVE':
				$result = __('Dec', 'vikrentitems');
				break;
			case 'VREDITORDERITEMSNUM':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRICONFIRMATIONNUMBER':
				$result = __('Confirmation Number', 'vikrentitems');
				break;
			case 'VRPEDITBUSYEXTRACNAME':
				$result = __('Service Name', 'vikrentitems');
				break;
			case 'VRPEDITBUSYEXTRACOSTS':
				$result = __('Extra Services', 'vikrentitems');
				break;
			case 'VRPEDITBUSYADDEXTRAC':
				$result = __('Add', 'vikrentitems');
				break;
			case 'VRIBOOKCANTADDITEM':
				$result = __('The item cannot be added to the booking', 'vikrentitems');
				break;
			case 'VRIBOOKRMITEMCONFIRM':
				$result = __('Do you want to remove this item from the reservation?', 'vikrentitems');
				break;
			case 'VRIBOOKADDITEM':
				$result = __('Add Item', 'vikrentitems');
				break;
			case 'VRIREMOVEITEM':
				$result = __('Remove Item', 'vikrentitems');
				break;
			case 'VRPEDITBUSYLOCATIONS':
				$result = __('Locations', 'vikrentitems');
				break;
			case 'VRPEDITBUSYPICKPLACE':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRPEDITBUSYDROPPLACE':
				$result = __('Drop off Location', 'vikrentitems');
				break;
			case 'VRPEDITBUSYERRNOFARES':
				$result = __('No Fares found for the items for this duration of rent. Unable to edit the reservation.', 'vikrentitems');
				break;
			case 'VRIMISSPRTYPEITH':
				$result = __('The items of this reservation have no rental costs defined. Make sure to set a rate, or the reservation will be incomplete.', 'vikrentitems');
				break;
			case 'VRISWITCHITERR':
				$result = __('Error: %d unit(s) of the item %s cannot be switched to %s on the selected dates.', 'vikrentitems');
				break;
			case 'VRISWITCHITOK':
				$result = __('The item %s has been switched to the %s. Choose Rates and Options for all the Items then click the Save button again.', 'vikrentitems');
				break;
			case 'VRIPREVITEMMOVED':
				$result = __('Previous item %s was switched on %s', 'vikrentitems');
				break;
			case 'VRIRESRATESUPDATED':
				$result = __('Reservation and Rates Updated', 'vikrentitems');
				break;
			case 'VRIBOOKADDITEMERR':
				$result = __('%s is not available from %s to %s', 'vikrentitems');
				break;
			case 'VRCUSTINFO':
				$result = __('Customer Information', 'vikrentitems');
				break;
			case 'VRCONFIRMED':
				$result = __('Confirmed', 'vikrentitems');
				break;
			case 'VRIPICKONDROP':
				$result = __('Allow Pick Ups on Drop Offs', 'vikrentitems');
				break;
			case 'VRIPICKONDROPHELP':
				$result = __('If enabled, and if the setting \'Dropped Off item is available after N hours\' is set to 0, the system will allow pick ups at times when the same item is being dropped off by another rental order.', 'vikrentitems');
				break;
			case 'VRICHECKDELADDGMAPOK':
				$result = __('You can close this window. The address was validated correctly!', 'vikrentitems');
				break;
			case 'VRNEWOPTONCEPERUNIT':
				$result = __('Apply once per Item', 'vikrentitems');
				break;
			case 'VRNEWOPTONCEPERUNITHELP':
				$result = __('If enabled, the cost for this option will not be multiplied by the number of units booked for the item.', 'vikrentitems');
				break;
			case 'VRICONFDELIVPERORD':
				$result = __('Delivery Cost per Order', 'vikrentitems');
				break;
			case 'VRICONFDELIVPERORDHELP':
				$result = __('If enabled, the delivery costs will only be applied once per order, no matter how many items will be added to the cart. The delivery address will be the same for all items rented that support the delivery service.', 'vikrentitems');
				break;
			case 'VRICONFDELIVPERITUNIT':
				$result = __('Delivery per Item Quantity', 'vikrentitems');
				break;
			case 'VRICONFDELIVPERITUNITHELP':
				$result = __('If enabled, the delivery cost will be multiplied by the number of units booked (quantity) for the item. If disabled, the delivery cost will be calculated per item, no matter how many units are booked for the same item.', 'vikrentitems');
				break;
			case 'VRICONFDELCOSTPERUNITHELP':
				$result = __('This is the cost for the Delivery Service per calculated Kilometer/Mile. This cost will be multiplied by the distance in Km/Mile from the Company Base Address and the Customer Address for delivery. The cost per calculated unit should always be tax included.', 'vikrentitems');
				break;
			case 'VRIPREFCOUNTRIESORD':
				$result = __('Preferred Countries Ordering', 'vikrentitems');
				break;
			case 'VRIPREFCOUNTRIESORDHELP':
				$result = __('The Preferred Countries are used to build input fields to collect phone numbers. These countries are taken from the installed languages on your website, and they will be used to display some countries at the top of the list next to each input field of type phone number. To add custom countries or to remove some, click the edit icon and enter the comma separated alpha-2 country codes (ISO 3166-1).', 'vikrentitems');
				break;
			case 'VRSAVENEW':
				$result = __('Save &amp; New', 'vikrentitems');
				break;
			case 'VRISPWDAYSHELP':
				$result = __('Selecting no week days equals to selecting all 7 week days', 'vikrentitems');
				break;
			case 'VRISPNAMEHELP':
				$result = __('The name of this pricing rule. Visible only if &quot;Promotion&quot; enabled. Can be left empty', 'vikrentitems');
				break;
			case 'VRISPYEARTIEDHELP':
				$result = __('If disabled, the pricing rule will be applied on the selected range of dates regardless of the year', 'vikrentitems');
				break;
			case 'VRISPONLCKINHELP':
				$result = __('If enabled, the rule will be applied only if the pick-up date for the rental is included in the range of dates', 'vikrentitems');
				break;
			case 'VRISPTPROMOHELP':
				$result = __('Make this pricing rule a &quot;Promotion&quot; to display it in the front-end booking process', 'vikrentitems');
				break;
			case 'VRIPROMOTEXTHELP':
				$result = __('The (optional) information/description text of your promotion', 'vikrentitems');
				break;
			case 'VRIPROMOWARNNODATES':
				$result = __('A range of dates is mandatory to create a promotion', 'vikrentitems');
				break;
			case 'VRIPROMOVALIDITYHELP':
				$result = __('If this value is set to a number greater than zero, this promotion will be valid only for early bookings. If you need to apply the promotion only to those who book N days in advance, then you should set the number of days in advance from the apposite input field. Otherwise, you should keep this setting to 0. This setting is not for Last Minute promotions, but rather for Early Bird promotions.', 'vikrentitems');
				break;
			case 'VRIPROMOLASTMINUTE':
				$result = __('Last Minute validity', 'vikrentitems');
				break;
			case 'VRIPROMOLASTMINUTEHELP':
				$result = __('If you are willing to apply discounts only to last minute bookings, then you should provide a number of days and/or hours for the validity of the promotion. If the time remaining to the pickup from the booking date is less than the limit you defined, the promotion will be applied.', 'vikrentitems');
				break;
			case 'VRIPROMOFORCEMINLOS':
				$result = __('Force minimum length of rent', 'vikrentitems');
				break;
			case 'VRIPROMOONFINALPRICE':
				$result = __('Apply on items final cost', 'vikrentitems');
				break;
			case 'VRIPROMOONFINALPRICEHELP':
				$result = __('This setting will determine how the promotion will be applied onto the items costs', 'vikrentitems');
				break;
			case 'VRIPROMOONFINALPRICETXT':
				$result = __('All special pricing rules are applied on the items base costs as a cumulative charge or discount even in case of multiple rules applied on the same rental dates. This algorithm follows the OpenTravel (OTA) standards, and here is an example of how two special pricing rules are typically applied on the bases costs to obtain the final price:<br/><br/><ul><li>Item base cost', 'vikrentitems');
				break;
			case 'VRMENURESTRICTIONS':
				$result = __('Restrictions', 'vikrentitems');
				break;
			case 'VRMAINRESTRICTIONSTITLE':
				$result = __('Vik Rent Items - Restrictions', 'vikrentitems');
				break;
			case 'VRMAINNEWRESTRICTIONTITLE':
				$result = __('Vik Rent Items - New Restriction', 'vikrentitems');
				break;
			case 'VRMAINEDITRESTRICTIONTITLE':
				$result = __('Vik Rent Items - Edit Restriction', 'vikrentitems');
				break;
			case 'VRMAINRESTRICTIONNEW':
				$result = __('New Restriction', 'vikrentitems');
				break;
			case 'VRMAINRESTRICTIONEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRMAINRESTRICTIONDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRNORESTRICTIONSFOUND':
				$result = __('No Restrictions found.', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSONE':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSTWO':
				$result = __('Month', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSTHREE':
				$result = __('Arrival Week Day', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSFOUR':
				$result = __('Min Num of Days', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSFIVE':
				$result = __('Max Num of Days', 'vikrentitems');
				break;
			case 'VRRESTRICTIONSHELPTITLE':
				$result = __('Restrictions', 'vikrentitems');
				break;
			case 'VRRESTRICTIONSSHELP':
				$result = __('With the restrictions you can limit the minimum rental period for a specific month of the Year or for a certain range of dates and optionally force the pickup Day of the Week. For example you can create a restriction for your items in August, forcing the pickup day to Saturday and the minimum rental period to 7 days, 14 days etc.. The minimum number of days will be set to 1 in case it is left empty.', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONONE':
				$result = __('Month', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONWDAY':
				$result = __('Force Arrival Week Day', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONNAME':
				$result = __('Restriction Name', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONMINLOS':
				$result = __('Min Num of Days', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONMULTIPLYMINLOS':
				$result = __('Multiply Min Num of Days', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONMULTIPLYMINLOSHELP':
				$result = __('If this setting is enabled the minimum number of days will be multiplied every time this is passed. For example if you want to force the Pickup day to Saturday and the Drop-off day must still be on Saturday, you have to set the Minimum Number of Days to 7 and if this setting is enabled, 8, 9, 10, 11, 12 and 13 days of rental will not be allowed but only 14, 21, 28 etc. days will be allowed. This is useful if you want to give your items only for weeks. The Maximum number of Days is automatically calculated from the Fares Table of each item, infact, if an item does not have a rate for 28 days, this item will not show up in the results so it will not be available. In case you want the calendar to force the Maximum Number of Days for this month, set a number of MaxLOS below.', 'vikrentitems');
				break;
			case 'VRUSELESSRESTRICTION':
				$result = __('Error, the restriction would be useless without an Arrival Week Day, without the CTA or CTD and the Minimum Num of Days as 1 which is the default MinLOS', 'vikrentitems');
				break;
			case 'VRRESTRICTIONSAVED':
				$result = __('Restriction Saved Successfully', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONALLCOMBO':
				$result = __('Forced Combinations:', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONALLCOMBOHELP':
				$result = __('if none selected, any check-out week day in accordance with the max and min number of days will be accepted', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONALLITEMS':
				$result = __('Apply to all Items', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONITEMSAFF':
				$result = __('Items affected by this Restriction:', 'vikrentitems');
				break;
			case 'VRRESTRLISTITEMS':
				$result = __('Items', 'vikrentitems');
				break;
			case 'VRRESTRALLITEMS':
				$result = __('ALL', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONOR':
				$result = __('or', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONDATERANGE':
				$result = __('Dates Range', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONDFROMRANGE':
				$result = __('From Date', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONDTORANGE':
				$result = __('To Date', 'vikrentitems');
				break;
			case 'VRRESTRICTIONERRDRANGE':
				$result = __('Error: Restrictions must have a month or a dates range, from and to.', 'vikrentitems');
				break;
			case 'VRRESTRICTIONSDRANGE':
				$result = __('Dates Range', 'vikrentitems');
				break;
			case 'VRRESTRICTIONMONTHEXISTS':
				$result = __('Error, a restriction for the selected month already exists.', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONMAXLOS':
				$result = __('Max Num of Days', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONSETCTA':
				$result = __('Set Days Closed to Arrival (CTA)', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONSETCTD':
				$result = __('Set Days Closed to Departure (CTD)', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONWDAYSCTA':
				$result = __('Week days closed to arrival', 'vikrentitems');
				break;
			case 'VRNEWRESTRICTIONWDAYSCTD':
				$result = __('Week days closed to departure', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSCTA':
				$result = __('CTA Week Days', 'vikrentitems');
				break;
			case 'VRPVIEWRESTRICTIONSCTD':
				$result = __('CTD Week Days', 'vikrentitems');
				break;
			case 'VRIRESTRWDAYSCTA':
				$result = __('Week Days Closed to Arrival', 'vikrentitems');
				break;
			case 'VRIRESTRWDAYSCTD':
				$result = __('Week Days Closed to Departure', 'vikrentitems');
				break;
			case 'VRIRESTRMINLOS':
				$result = __('Min. Days', 'vikrentitems');
				break;
			case 'VRIRESTRMAXLOS':
				$result = __('Max. Days', 'vikrentitems');
				break;
			case 'VRIRESTRARRIVWDAY':
				$result = __('Pick up Week Day', 'vikrentitems');
				break;
			case 'VRIRESTRARRIVWDAYS':
				$result = __('Pick up Week Days', 'vikrentitems');
				break;
			case 'VRMDAYFRIST':
				$result = __('st', 'vikrentitems');
				break;
			case 'VRMDAYSECOND':
				$result = __('nd', 'vikrentitems');
				break;
			case 'VRMDAYTHIRD':
				$result = __('rd', 'vikrentitems');
				break;
			case 'VRMDAYNUMGEN':
				$result = __('th', 'vikrentitems');
				break;
			case 'VRIADMINLEGENDDETAILS':
				$result = __('Details', 'vikrentitems');
				break;
			case 'VRIADMINLEGENDSETTINGS':
				$result = __('Settings', 'vikrentitems');
				break;
			case 'VRIITEMSASSIGNED':
				$result = __('Items Assigned', 'vikrentitems');
				break;
			case 'VRIPOSITIONORDERING':
				$result = __('Ordering position', 'vikrentitems');
				break;
			case 'VRIPOSITIONORDERINGHELP':
				$result = __('Leave this field empty for letting the system calculate the ordering position automatically', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFFLAG':
				$result = __('Type Flag', 'vikrentitems');
				break;
			case 'VRNEWCUSTOMFFLAGHELP':
				$result = __('There are several sub-types of fields that tell the system what kind of information was collected from the customer. Choose the appropriate type and remember to only create one field of type eMail that will be used for the notifications.', 'vikrentitems');
				break;
			case 'VRILOCADDRESS':
				$result = __('Location Address', 'vikrentitems');
				break;
			case 'VRIPLACESUGGOPENTIME':
				$result = __('Suggested Time', 'vikrentitems');
				break;
			case 'VRIPLACESUGGOPENTIMETXT':
				$result = __('If not empty, this time will be pre-selected in the drop down menus for selecting the time', 'vikrentitems');
				break;
			case 'VRIPLACEOVROPENTIME':
				$result = __('Override Opening Time', 'vikrentitems');
				break;
			case 'VRIPLACEOVROPENTIMEHELP':
				$result = __('The default Opening Time can be changed on some days of the week. Closing days, for either festivities or weekly closure, can be defined from the apposite parameter Closing Dates. If the Opening Time does not change from one day to another, keep this setting empty.', 'vikrentitems');
				break;
			case 'VRNEWPLACECLOSINGDAYSINGLE':
				$result = __('Single Day', 'vikrentitems');
				break;
			case 'VRNEWPLACECLOSINGDAYWEEK':
				$result = __('Every week', 'vikrentitems');
				break;
			case 'VRISUCCUPDOPTION':
				$result = __('Option updated successfully', 'vikrentitems');
				break;
			case 'VRITOGGLEWIZARD':
				$result = __('Open Wizard', 'vikrentitems');
				break;
			case 'VRIWIZARDTARIFFSMESS':
				$result = __('Please specify the base-cost per day for each rate plan.', 'vikrentitems');
				break;
			case 'VRIWIZARDTARIFFSHELP':
				$result = __('This should be the rental cost applied for the longer period of the year. You will be able to set later any hourly rate, as well as some seasonal pricing or different costs for some dates of the year.', 'vikrentitems');
				break;
			case 'VRIWIZARDTARIFFSWHTC':
				$result = __('What\'s the starting rental cost per day for your item?', 'vikrentitems');
				break;
			case 'VRIWEBSITERATES':
				$result = __('Website Rates', 'vikrentitems');
				break;
			case 'VRICALCRATESITEMNOTAVAILCOMBO':
				$result = __('The Item is not available or has no Rates from %s to %s.', 'vikrentitems');
				break;
			case 'VRICALCRATESTOT':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRICALCRATESSPAFFDAYS':
				$result = __('Days modified by Special Prices:', 'vikrentitems');
				break;
			case 'VRIMENURATESOVERVIEW':
				$result = __('Fares Overview', 'vikrentitems');
				break;
			case 'VRIMAINRATESOVERVIEWTITLE':
				$result = __('Vik Rent Items - Rates Overview', 'vikrentitems');
				break;
			case 'VRIRATESOVWRATESCALCULATOR':
				$result = __('Rates Calculator', 'vikrentitems');
				break;
			case 'VRIRATESOVWNUMNIGHTSACT':
				$result = __('Rental Period', 'vikrentitems');
				break;
			case 'VRIRATESOVWAPPLYLOS':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRIRATESOVWRATESCALCULATORCALC':
				$result = __('Calculate', 'vikrentitems');
				break;
			case 'VRIRATESOVWRATESCALCULATORCALCING':
				$result = __('Calculating...', 'vikrentitems');
				break;
			case 'VRIRATESOVWTABLOS':
				$result = __('Length of Stay Pricing Overview', 'vikrentitems');
				break;
			case 'VRIRATESOVWTABCALENDAR':
				$result = __('Calendar Pricing Overview', 'vikrentitems');
				break;
			case 'VRIROVWSELPERIOD':
				$result = __('Select Period', 'vikrentitems');
				break;
			case 'VRIROVWSELPERIODFROM':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRIROVWSELPERIODTO':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRIROVWSELRPLAN':
				$result = __('Rate Plan', 'vikrentitems');
				break;
			case 'VRIBOOKNOW':
				$result = __('Book Now', 'vikrentitems');
				break;
			case 'VRIRATESOVWSETNEWRATE':
				$result = __('Set New Rate', 'vikrentitems');
				break;
			case 'VRIRATESOVWERRNEWRATE':
				$result = __('Error while setting new rates. Missing data', 'vikrentitems');
				break;
			case 'VRIRATESOVWERRNORATES':
				$result = __('Error while setting new rates. No rates', 'vikrentitems');
				break;
			case 'VRIRATESOVWERRNORATESMOD':
				$result = __('Error: no changes needed for the selected rates', 'vikrentitems');
				break;
			case 'VRIRATESOVWCLOSEOPENRRP':
				$result = __('Close/Open Rate Plan', 'vikrentitems');
				break;
			case 'VRIRATESOVWCLOSERRP':
				$result = __('Close Rate Plan', 'vikrentitems');
				break;
			case 'VRIRATESOVWOPENRRP':
				$result = __('Open Rate Plan', 'vikrentitems');
				break;
			case 'VRIRATESOVWERRMODRPLANS':
				$result = __('Error while modifying rate plans. Missing data', 'vikrentitems');
				break;
			case 'VRIRATESOVWOPENSPL':
				$result = __('Special Price rule #%d', 'vikrentitems');
				break;
			case 'VRISEASONANYYEARS':
				$result = __('Valid any Year', 'vikrentitems');
				break;
			case 'VRISEASONBASEDLOS':
				$result = __('Based on Rental Period', 'vikrentitems');
				break;
			case 'VRISEASONPERDAY':
				$result = __('per day', 'vikrentitems');
				break;
			case 'VRISEASONCALNUMDAY':
				$result = __('%d Day', 'vikrentitems');
				break;
			case 'VRISEASONCALNUMDAYS':
				$result = __('%d Days', 'vikrentitems');
				break;
			case 'VRISEASONSCALOFFSEASONPRICES':
				$result = __('Off-Season Prices', 'vikrentitems');
				break;
			case 'VRIDESCRIPTIONS':
				$result = __('Descriptions', 'vikrentitems');
				break;
			case 'VRWEEKDAYZERO':
				$result = __('Sunday', 'vikrentitems');
				break;
			case 'VRWEEKDAYONE':
				$result = __('Monday', 'vikrentitems');
				break;
			case 'VRWEEKDAYTWO':
				$result = __('Tuesday', 'vikrentitems');
				break;
			case 'VRWEEKDAYTHREE':
				$result = __('Wednesday', 'vikrentitems');
				break;
			case 'VRWEEKDAYFOUR':
				$result = __('Thursday', 'vikrentitems');
				break;
			case 'VRWEEKDAYFIVE':
				$result = __('Friday', 'vikrentitems');
				break;
			case 'VRWEEKDAYSIX':
				$result = __('Saturday', 'vikrentitems');
				break;
			case 'VRNEWCATTWO':
				$result = __('Category Image', 'vikrentitems');
				break;
			case 'VRIMAILSUBJECT':
				$result = __('Your reservation at %s', 'vikrentitems');
				break;
			case 'VRINEWORDERID':
				$result = __('New Order #%s', 'vikrentitems');
				break;
			case 'VRIPREVIEW':
				$result = __('Preview', 'vikrentitems');
				break;
			case 'VRICONFIGLOGOBACKEND':
				$result = __('Back-end Logo (180px)', 'vikrentitems');
				break;
			case 'VRICONFIGATTACHICAL':
				$result = __('Attach iCal Reminder', 'vikrentitems');
				break;
			case 'VRICONFIGATTACHICALHELP':
				$result = __('If enabled, a calendar reminder in iCal format will be attached to the confirmation email for the customer and/or the administrator. This is useful to save the event on any calendar application of any device.', 'vikrentitems');
				break;
			case 'VRICONFIGSENDTOADMIN':
				$result = __('Administrator', 'vikrentitems');
				break;
			case 'VRICONFIGSENDTOCUSTOMER':
				$result = __('Customer', 'vikrentitems');
				break;
			case 'VRIVIEWORDFRONT':
				$result = __('View in front site', 'vikrentitems');
				break;
			case 'VRIMENUCRONS':
				$result = __('Scheduled Cron Jobs', 'vikrentitems');
				break;
			case 'VRIXMLTRANSLATECRONJOBS':
				$result = __('Scheduled Cron Jobs', 'vikrentitems');
				break;
			case 'VRICONFIGCRONKEY':
				$result = __('Cron Jobs Secret Key', 'vikrentitems');
				break;
			case 'VRIMAINCRONSTITLE':
				$result = __('Vik Rent Items - Scheduled Cron Jobs', 'vikrentitems');
				break;
			case 'VRIMAINCRONNEW':
				$result = __('New Cron Job', 'vikrentitems');
				break;
			case 'VRIMAINCRONEDIT':
				$result = __('Edit', 'vikrentitems');
				break;
			case 'VRIMAINCRONDEL':
				$result = __('Remove', 'vikrentitems');
				break;
			case 'VRINOCRONS':
				$result = __('No Cron Jobs currently set up or scheduled.', 'vikrentitems');
				break;
			case 'VRICRONNAME':
				$result = __('Cron Job Name', 'vikrentitems');
				break;
			case 'VRICRONCLASS':
				$result = __('Class File', 'vikrentitems');
				break;
			case 'VRICRONLASTEXEC':
				$result = __('Last Execution', 'vikrentitems');
				break;
			case 'VRICRONPUBLISHED':
				$result = __('Published', 'vikrentitems');
				break;
			case 'VRICRONSAVED':
				$result = __('Cron Job Saved!', 'vikrentitems');
				break;
			case 'VRICRONUPDATED':
				$result = __('Cron Job Updated!', 'vikrentitems');
				break;
			case 'VRICRONLOGS':
				$result = __('Execution Logs', 'vikrentitems');
				break;
			case 'VRICRONACTIONS':
				$result = __('Actions', 'vikrentitems');
				break;
			case 'VRICRONACTION':
				$result = __('Execute', 'vikrentitems');
				break;
			case 'VRICRONEXECRESULT':
				$result = __('Cron Job Result', 'vikrentitems');
				break;
			case 'VRICRONPARAMS':
				$result = __('Parameters', 'vikrentitems');
				break;
			case 'VRICRONGETCMD':
				$result = __('Get Command', 'vikrentitems');
				break;
			case 'VRICRONGETCMDHELP':
				$result = __('This cron job could be executed automatically by your server at regular intervals. The cron can also be executed manually by an administrator, but letting the server do it will be effortless and fully functional. Only servers supporting a Cron utility like crontab will be able of executing this cron job.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTSTEPS':
				$result = __('Installation Steps', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTSTEPONE':
				$result = __('Download the executable PHP file for this cron job onto a local folder of your computer.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTSTEPTWO':
				$result = __('Upload the downloaded file onto a directory of your server, either before, in or after the root directory of the web-server.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTSTEPTHREE':
				$result = __('Log in to your server control panel and add a new job for your Cron Utility. Your hosting company should help you use this tool.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTSTEPFOUR':
				$result = __('Cron Jobs require the execution interval and the command to execute. Set the necessary interval and the proper command to execute this cron job repetitively.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTPATH':
				$result = __('Assuming that the executable PHP file was uploaded onto the root directory of your web-server, the command you should set in the Cron Utility should look similar to the one below. In this example, the path to the PHP interpreter has been set to <em>/usr/bin/php</em> but this may differ for your server.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDINSTURL':
				$result = __('Please be aware that PHP files in or after the root directory of the web-server can be executed at a public URL. This may not be secure if you do not want anyone to be able to launch the cron job except for the server. If the file was in the root directory, it would be callable at the URL below.', 'vikrentitems');
				break;
			case 'VRICRONGETCMDGETFILE':
				$result = __('Download Executable File', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMCTYPE':
				$result = __('Reminder Type', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMCTYPEA':
				$result = __('Pick-up Reminder', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMCTYPEB':
				$result = __('Remaining Balance Payment Reminder', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMCTYPEC':
				$result = __('After Drop-off Message', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMCTYPECHELP':
				$result = __('If type', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMBEFD':
				$result = __('Days in Advance', 'vikrentitems');
				break;
			case 'VRICRONEMAILREMPARAMSUBJECT':
				$result = __('eMail Subject', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMTEXT':
				$result = __('Message', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMTEST':
				$result = __('Test Mode', 'vikrentitems');
				break;
			case 'VRICRONSMSREMPARAMTESTHELP':
				$result = __('if enabled, the cron will not actually send the SMS', 'vikrentitems');
				break;
			case 'VRICRONEMAILREMPARAMTESTHELP':
				$result = __('if enabled, the cron will not actually send the eMail', 'vikrentitems');
				break;
			case 'VRICRONSMSREMHELP':
				$result = __('This cron job should be scheduled to run at regular intervals of one time per day. Executing the cron job once per day, at the preferred time, will guarantee the best result.', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMCWHEN':
				$result = __('Generate Invoices', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMCWHENA':
				$result = __('After the Pick-up date', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMCWHENB':
				$result = __('Whenever the order status is Confirmed', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMCWHENC':
				$result = __('After the Drop-off date', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMDGEN':
				$result = __('Use Generation Date', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMEMAILSEND':
				$result = __('Send Invoices via eMail', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMTEST':
				$result = __('Test Mode', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMTESTHELP':
				$result = __('if enabled, the cron will not actually generate the invoices, nor it will send them via eMail to the customers', 'vikrentitems');
				break;
			case 'VRICRONINVGENPARAMTEXT':
				$result = __('eMail message with PDF attached', 'vikrentitems');
				break;
			case 'VRICRONINVGENHELP':
				$result = __('This cron job should be scheduled to run once per day. Remember to create at least one invoice manually from the back-end before running this cron. This is to set the invoices starting number and other details.', 'vikrentitems');
				break;
			case 'VRICONFIGURETASK':
				$result = __('Configure', 'vikrentitems');
				break;
			case 'VRIRESTRREPEATONWDAYS':
				$result = __('Repeat restriction every %s', 'vikrentitems');
				break;
			case 'VRIRESTRREPEATUNTIL':
				$result = __('Repeat until', 'vikrentitems');
				break;
			case 'ORDER_TERMSCONDITIONS':
				$result = __('I agree to the terms and conditions', 'vikrentitems');
				break;
		}

		return $result;
	}
}

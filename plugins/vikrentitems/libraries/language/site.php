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
 * Switcher class to translate the VikRentItems plugin site languages.
 *
 * @since 	1.0
 */
class VikRentItemsLanguageSite implements JLanguageHandler
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
			case 'VRDATE':
				$result = __('Date', 'vikrentitems');
				break;
			case 'VRIP':
				$result = __('IP', 'vikrentitems');
				break;
			case 'VRPLACE':
				$result = __('Location', 'vikrentitems');
				break;
			case 'VRORDNOL':
				$result = __('Rental Order', 'vikrentitems');
				break;
			case 'VRINATTESA':
				$result = __('Waiting for the Payment', 'vikrentitems');
				break;
			case 'VRIOMPLETED':
				$result = __('Completed', 'vikrentitems');
				break;
			case 'VRIARBOOKEDBYOTHER':
				$result = __('Sorry, the item %s has been booked. Please make a new order.', 'vikrentitems');
				break;
			case 'VRIARISLOCKED':
				$result = __('The item %s is now being ordered by another customer. Please make a new order.', 'vikrentitems');
				break;
			case 'VRINVALIDDATES':
				$result = __('Pickup and Drop Off Dates are wrong', 'vikrentitems');
				break;
			case 'VRINCONGRTOT':
				$result = __('Error, Order Total is wrong', 'vikrentitems');
				break;
			case 'VRINCONGRDATAREC':
				$result = __('Error, Wrong data.', 'vikrentitems');
				break;
			case 'VRINCONGRDATA':
				$result = __('Error, Wrong data.', 'vikrentitems');
				break;
			case 'VRINSUFDATA':
				$result = __('Error, Insufficient Data Received.', 'vikrentitems');
				break;
			case 'VRINVALIDTOKEN':
				$result = __('Error, Invalid Token. Unable to Save the Order', 'vikrentitems');
				break;
			case 'VRERRREPSEARCH':
				$result = __('Error, Item already booked. Please search for another one.', 'vikrentitems');
				break;
			case 'VRORDERNOTFOUND':
				$result = __('Error, Order not found', 'vikrentitems');
				break;
			case 'VRERRCALCTAR':
				$result = __('Error occured processing fares. Please choose new dates', 'vikrentitems');
				break;
			case 'VRTARNOTFOUND':
				$result = __('Error, Not Existing Fare', 'vikrentitems');
				break;
			case 'VRNOTARSELECTED':
				$result = __('No Fares selected', 'vikrentitems');
				break;
			case 'VRIARNOTCONS':
				$result = __('Item is not Returnable from the', 'vikrentitems');
				break;
			case 'VRIARNOTCONSTO':
				$result = __('to the', 'vikrentitems');
				break;
			case 'VRIARNOTRIT':
				$result = __('Item is not available from the', 'vikrentitems');
				break;
			case 'VRIALLUNITSNOTRIT':
				$result = __('%d unit(s) of the Item is not available from the', 'vikrentitems');
				break;
			case 'VRIARNOTFND':
				$result = __('Item not found', 'vikrentitems');
				break;
			case 'VRIARNOTAV':
				$result = __('Item not available', 'vikrentitems');
				break;
			case 'VRNOTARFNDSELO':
				$result = __('No Fares Found. Please select a different date or item', 'vikrentitems');
				break;
			case 'VRSRCHNOTM':
				$result = __('Search Notification', 'vikrentitems');
				break;
			case 'VRIAT':
				$result = __('Category', 'vikrentitems');
				break;
			case 'VRANY':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRPICKUP':
				$result = __('Pickup', 'vikrentitems');
				break;
			case 'VRRETURN':
				$result = __('Drop Off', 'vikrentitems');
				break;
			case 'VRSRCHRES':
				$result = __('Search Results', 'vikrentitems');
				break;
			case 'VRNOITEMSINDATE':
				$result = __('None of the items is available on the requested period.', 'vikrentitems');
				break;
			case 'VRNOITEMAVFOR':
				$result = __('No items is available for rental for', 'vikrentitems');
				break;
			case 'VRDAYS':
				$result = __('Days', 'vikrentitems');
				break;
			case 'VRDAY':
				$result = __('Day', 'vikrentitems');
				break;
			case 'VRPICKBRET':
				$result = __('Drop off date must be after the Pickup date', 'vikrentitems');
				break;
			case 'VRWRONGDF':
				$result = __('Wrong Date Format. Right Format is', 'vikrentitems');
				break;
			case 'VRSELPRDATE':
				$result = __('Please select Pickup and Drop Off Date', 'vikrentitems');
				break;
			case 'VRPPLACE':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRPICKUPITEM':
				$result = __('Pickup Date and Time', 'vikrentitems');
				break;
			case 'VRRETURNITEM':
				$result = __('Drop Off Date and Time', 'vikrentitems');
				break;
			case 'VRALLE':
				$result = __('At', 'vikrentitems');
				break;
			case 'VRIARCAT':
				$result = __('Item Category', 'vikrentitems');
				break;
			case 'VRALLCAT':
				$result = __('Any', 'vikrentitems');
				break;
			case 'VRERRCONNPAYP':
				$result = __('Error while connecting to Paypal.com', 'vikrentitems');
				break;
			case 'VRIMPVERPAYM':
				$result = __('Unable to process the payment of the', 'vikrentitems');
				break;
			case 'VRRENTALORD':
				$result = __('Rental Order', 'vikrentitems');
				break;
			case 'VRVALIDPWSAVE':
				$result = __('Valid Paypal Payment, Error Saving the Order', 'vikrentitems');
				break;
			case 'VRVALIDPWSAVEMSG':
				$result = __('Payment received with Success, Order not Saved. Correct the problem manually.', 'vikrentitems');
				break;
			case 'VRPAYPALRESP':
				$result = __('Paypal Response', 'vikrentitems');
				break;
			case 'VRINVALIDPAYPALP':
				$result = __('Invalid Paypal Payment', 'vikrentitems');
				break;
			case 'ERRSELECTPAYMENT':
				$result = __('Please Select a Payment Method', 'vikrentitems');
				break;
			case 'VRPAYMENTNOTVER':
				$result = __('Payment Not Verified', 'vikrentitems');
				break;
			case 'VRSERVRESP':
				$result = __('Server Response', 'vikrentitems');
				break;
			case 'VRIONFIGONETWELVE':
				$result = __('DD/MM/YYYY', 'vikrentitems');
				break;
			case 'VRIONFIGONETENTHREE':
				$result = __('YYYY/MM/DD', 'vikrentitems');
				break;
			case 'VRIARSFND':
				$result = __('Items Found', 'vikrentitems');
				break;
			case 'VRPROSEGUI':
				$result = __('Book', 'vikrentitems');
				break;
			case 'VRSTARTFROM':
				$result = __('Starting From', 'vikrentitems');
				break;
			case 'VRRENTAL':
				$result = __('Rental', 'vikrentitems');
				break;
			case 'VRFOR':
				$result = __('for', 'vikrentitems');
				break;
			case 'VRPRICE':
				$result = __('Price', 'vikrentitems');
				break;
			case 'VRACCOPZ':
				$result = __('Options', 'vikrentitems');
				break;
			case 'VRBOOKNOW':
				$result = __('Book now', 'vikrentitems');
				break;
			case 'VRDAL':
				$result = __('From', 'vikrentitems');
				break;
			case 'VRAL':
				$result = __('To', 'vikrentitems');
				break;
			case 'VRRIEPILOGOORD':
				$result = __('Order Summary', 'vikrentitems');
				break;
			case 'VRTOTAL':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRIMP':
				$result = __('Taxable Income', 'vikrentitems');
				break;
			case 'VRIVA':
				$result = __('Tax', 'vikrentitems');
				break;
			case 'VRDUE':
				$result = __('Total Due', 'vikrentitems');
				break;
			case 'VRFILLALL':
				$result = __('Please Fill all Fields', 'vikrentitems');
				break;
			case 'VRPURCHDATA':
				$result = __('Purchaser Details', 'vikrentitems');
				break;
			case 'VRNAME':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRLNAME':
				$result = __('Last Name', 'vikrentitems');
				break;
			case 'VRMAIL':
				$result = __('e-Mail', 'vikrentitems');
				break;
			case 'VRPHONE':
				$result = __('Phone', 'vikrentitems');
				break;
			case 'VRADDR':
				$result = __('Address', 'vikrentitems');
				break;
			case 'VRIAP':
				$result = __('Zip Code', 'vikrentitems');
				break;
			case 'VRIITY':
				$result = __('City', 'vikrentitems');
				break;
			case 'VRNAT':
				$result = __('State', 'vikrentitems');
				break;
			case 'VRDOBIRTH':
				$result = __('Date of Birth', 'vikrentitems');
				break;
			case 'VRFISCALCODE':
				$result = __('Fiscal Code', 'vikrentitems');
				break;
			case 'VRORDCONFIRM':
				$result = __('Confirm Order', 'vikrentitems');
				break;
			case 'VRTHANKSONE':
				$result = __('Thanks, Order Successfully Completed', 'vikrentitems');
				break;
			case 'VRTHANKSTWO':
				$result = __('To review your order, please visit', 'vikrentitems');
				break;
			case 'VRTHANKSTHREE':
				$result = __('This Page', 'vikrentitems');
				break;
			case 'VRORDEREDON':
				$result = __('Order Date', 'vikrentitems');
				break;
			case 'VRPERSDETS':
				$result = __('Personal Details', 'vikrentitems');
				break;
			case 'VRIARRENTED':
				$result = __('Rented Item', 'vikrentitems');
				break;
			case 'VROPTS':
				$result = __('Options', 'vikrentitems');
				break;
			case 'VRWAITINGPAYM':
				$result = __('Waiting for the Payment', 'vikrentitems');
				break;
			case 'VRBACK':
				$result = __('Back', 'vikrentitems');
				break;
			case 'ORDDD':
				$result = __('Days', 'vikrentitems');
				break;
			case 'ORDNOTAX':
				$result = __('Net Price', 'vikrentitems');
				break;
			case 'ORDTAX':
				$result = __('Tax', 'vikrentitems');
				break;
			case 'ORDWITHTAX':
				$result = __('Total Price', 'vikrentitems');
				break;
			case 'VRRITIROITEM':
				$result = __('Pickup Location', 'vikrentitems');
				break;
			case 'VRRETURNITEMORD':
				$result = __('Drop Off Location', 'vikrentitems');
				break;
			case 'VRADDNOTES':
				$result = __('Notes', 'vikrentitems');
				break;
			case 'VRIHANGEDATES':
				$result = __('Change Dates', 'vikrentitems');
				break;
			case 'VRLOCFEETOPAY':
				$result = __('Pickup/Drop Off Fee', 'vikrentitems');
				break;
			case 'VRIHOOSEPAYMENT':
				$result = __('Payment Method', 'vikrentitems');
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
			case 'VRLEAVEDEPOSIT':
				$result = __('Leave a deposit of ', 'vikrentitems');
				break;
			case 'VRLIBPAYNAME':
				$result = __('Payment Method', 'vikrentitems');
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
				$result = __('State', 'vikrentitems');
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
			case 'VRILISTSFROM':
				$result = __('Starting From', 'vikrentitems');
				break;
			case 'VRILISTPICK':
				$result = __('View Details', 'vikrentitems');
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
			case 'VRLEGFREE':
				$result = __('Available', 'vikrentitems');
				break;
			case 'VRLEGWARNING':
				$result = __('Partially Reserved', 'vikrentitems');
				break;
			case 'VRLEGBUSY':
				$result = __('Not Available', 'vikrentitems');
				break;
			case 'VRLEGBUSYCHECKH':
				$result = __('Not Available (for the whole day, check hourly availability)', 'vikrentitems');
				break;
			case 'VRIBOOKTHISITEM':
				$result = __('Book Now', 'vikrentitems');
				break;
			case 'VRISELECTPDDATES':
				$result = __('Select a Pickup and Drop Off Date', 'vikrentitems');
				break;
			case 'VRIDETAILCNOTAVAIL':
				$result = __('is not available for the selected days. Please try with different dates', 'vikrentitems');
				break;
			case 'VRINVALIDLOCATIONS':
				$result = __('Pickup and Drop Off is not available for those locations', 'vikrentitems');
				break;
			case 'VRREGSIGNUP':
				$result = __('Sign Up', 'vikrentitems');
				break;
			case 'VRREGNAME':
				$result = __('Name', 'vikrentitems');
				break;
			case 'VRREGLNAME':
				$result = __('Last Name', 'vikrentitems');
				break;
			case 'VRREGEMAIL':
				$result = __('e-Mail', 'vikrentitems');
				break;
			case 'VRREGUNAME':
				$result = __('Username', 'vikrentitems');
				break;
			case 'VRREGPWD':
				$result = __('Password', 'vikrentitems');
				break;
			case 'VRREGCONFIRMPWD':
				$result = __('Confirm Password', 'vikrentitems');
				break;
			case 'VRREGSIGNUPBTN':
				$result = __('Sign Up', 'vikrentitems');
				break;
			case 'VRREGSIGNIN':
				$result = __('Login', 'vikrentitems');
				break;
			case 'VRREGSIGNINBTN':
				$result = __('Login', 'vikrentitems');
				break;
			case 'VRIREGERRINSDATA':
				$result = __('Please fill in all the registration fields', 'vikrentitems');
				break;
			case 'VRIREGERRSAVING':
				$result = __('Error while creating an account, please try again', 'vikrentitems');
				break;
			case 'VRILOCATIONSMAP':
				$result = __('View Locations Map', 'vikrentitems');
				break;
			case 'VRIHOUR':
				$result = __('Hour', 'vikrentitems');
				break;
			case 'VRIHOURS':
				$result = __('Hours', 'vikrentitems');
				break;
			case 'VRISEPDRIVERD':
				$result = __('Billing Information', 'vikrentitems');
				break;
			case 'VRIORDERNUMBER':
				$result = __('Order Number', 'vikrentitems');
				break;
			case 'VRIORDERDETAILS':
				$result = __('Order Details', 'vikrentitems');
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
			case 'VRITOTPAYMENTINVALID':
				$result = __('Invalid Amount Paid', 'vikrentitems');
				break;
			case 'VRITOTPAYMENTINVALIDTXT':
				$result = __('A payment for the order %s has been received. The total amount received is %s instead of %s.', 'vikrentitems');
				break;
			case 'VRILOCLISTLOCOPENTIME':
				$result = __('Opening Time', 'vikrentitems');
				break;
			case 'VRIHAVEACOUPON':
				$result = __('Enter here your coupon code', 'vikrentitems');
				break;
			case 'VRISUBMITCOUPON':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRICOUPONNOTFOUND':
				$result = __('Error, Coupon not found', 'vikrentitems');
				break;
			case 'VRICOUPONINVDATES':
				$result = __('The Coupon is not valid for these rental dates', 'vikrentitems');
				break;
			case 'VRICOUPONINVITEM':
				$result = __('The Coupon is not valid for this item', 'vikrentitems');
				break;
			case 'VRICOUPONINVMINTOTORD':
				$result = __('The Order Total is not enough for this Coupon', 'vikrentitems');
				break;
			case 'VRICOUPON':
				$result = __('Coupon', 'vikrentitems');
				break;
			case 'VRINEWTOTAL':
				$result = __('Total', 'vikrentitems');
				break;
			case 'VRICCCREDITCARDNUMBER':
				$result = __('Credit Card Number', 'vikrentitems');
				break;
			case 'VRICCVALIDTHROUGH':
				$result = __('Valid Through', 'vikrentitems');
				break;
			case 'VRICCCVV':
				$result = __('CVV', 'vikrentitems');
				break;
			case 'VRICCFIRSTNAME':
				$result = __('First Name', 'vikrentitems');
				break;
			case 'VRICCLASTNAME':
				$result = __('Last Name', 'vikrentitems');
				break;
			case 'VRICCBILLINGINFO':
				$result = __('Billing Information', 'vikrentitems');
				break;
			case 'VRICCCOMPANY':
				$result = __('Company', 'vikrentitems');
				break;
			case 'VRICCADDRESS':
				$result = __('Address', 'vikrentitems');
				break;
			case 'VRICCCITY':
				$result = __('City', 'vikrentitems');
				break;
			case 'VRICCSTATEPROVINCE':
				$result = __('State/Province', 'vikrentitems');
				break;
			case 'VRICCZIP':
				$result = __('ZIP Code', 'vikrentitems');
				break;
			case 'VRICCCOUNTRY':
				$result = __('Country', 'vikrentitems');
				break;
			case 'VRICCPHONE':
				$result = __('Phone', 'vikrentitems');
				break;
			case 'VRICCEMAIL':
				$result = __('eMail', 'vikrentitems');
				break;
			case 'VRICCPROCESSPAY':
				$result = __('Process and Pay', 'vikrentitems');
				break;
			case 'VRICCPROCESSING':
				$result = __('Processing...', 'vikrentitems');
				break;
			case 'VRICCOFFLINECCMESSAGE':
				$result = __('Please provide your Credit Card information. Your card will not be charged and the information will be securely kept by us.', 'vikrentitems');
				break;
			case 'VRIOFFLINECCSEND':
				$result = __('Submit Credit Card Information', 'vikrentitems');
				break;
			case 'VRIOFFLINECCSENT':
				$result = __('Processing...', 'vikrentitems');
				break;
			case 'VRIOFFCCMAILSUBJECT':
				$result = __('Credit Card Information Received', 'vikrentitems');
				break;
			case 'VRIOFFCCTOTALTOPAY':
				$result = __('Total to Pay', 'vikrentitems');
				break;
			case 'VRILOCDAYCLOSED':
				$result = __('The location is closed on this day', 'vikrentitems');
				break;
			case 'VRIERRLOCATIONCLOSEDON':
				$result = __('Error, the location %s is closed on the %s. Please select a different date', 'vikrentitems');
				break;
			case 'VRPICKINPAST':
				$result = __('Error, the Pickup date and time is in the past', 'vikrentitems');
				break;
			case 'VRIONFIGUSDATEFORMAT':
				$result = __('MM/DD/YYYY', 'vikrentitems');
				break;
			case 'VRIYOURRESERVATIONS':
				$result = __('Your Reservations', 'vikrentitems');
				break;
			case 'VRIUSERRESDATE':
				$result = __('Date', 'vikrentitems');
				break;
			case 'VRIUSERRESSTATUS':
				$result = __('Status', 'vikrentitems');
				break;
			case 'VRINOUSERRESFOUND':
				$result = __('No reservations were found for this account', 'vikrentitems');
				break;
			case 'VRIONFIRMED':
				$result = __('Confirmed', 'vikrentitems');
				break;
			case 'VRSTANDBY':
				$result = __('Standby', 'vikrentitems');
				break;
			case 'VRILOGINFIRST':
				$result = __('Please Login to access this page', 'vikrentitems');
				break;
			case 'VRIPRINTCONFORDER':
				$result = __('View Order for Printing', 'vikrentitems');
				break;
			case 'VRIORDERNUMBER':
				$result = __('Order Number', 'vikrentitems');
				break;
			case 'VRIREQUESTCANCMOD':
				$result = __('Cancellation/Modification Request', 'vikrentitems');
				break;
			case 'VRIREQUESTCANCMODOPENTEXT':
				$result = __('Click here to request a cancellation or modification of the order', 'vikrentitems');
				break;
			case 'VRIREQUESTCANCMODEMAIL':
				$result = __('e-Mail', 'vikrentitems');
				break;
			case 'VRIREQUESTCANCMODREASON':
				$result = __('Message', 'vikrentitems');
				break;
			case 'VRIREQUESTCANCMODSUBMIT':
				$result = __('Send Request', 'vikrentitems');
				break;
			case 'VRICANCREQUESTEMAILSUBJ':
				$result = __('Order Cancellation-Modification Request', 'vikrentitems');
				break;
			case 'VRICANCREQUESTEMAILHEAD':
				$result = __("A Cancellation-Modification Request has been sent by the customer for the order id %s.\nOrder details: %s", 'vikrentitems');
				break;
			case 'VRICANCREQUESTMAILSENT':
				$result = __('Your request has been sent successfully. Please do not send it again', 'vikrentitems');
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
			case 'VRIAGREEMENTTITLE':
				$result = __('Contract/Agreement', 'vikrentitems');
				break;
			case 'VRIAGREEMENTSAMPLETEXT':
				$result = __('This agreement between %s %s and %s was made on the %s and is valid until the %s.', 'vikrentitems');
				break;
			case 'VRIAGREEMENTSAMPLETEXTMORE':
				$result = __('1. Condition of Premises<br/><br/>The lessor shall keep the premises in a good state of repair and fit for habitation during the tenancy and shall comply with any enactment respecting standards of health, safety or housing notwithstanding any state of non-repair that may have existed at the time the agreement was entered into.<br/><br/>2. Services<br/><br/>Where the lessor provides or pays for a service or facility to the lessee that is reasonably related to the lessee\'s continued use and enjoyment of the premises, such as heat, water, electric power, gas, appliances, garbage collection, sewers or elevators, the lessor shall not discontinue providing or paying for that service to the lessee without permission from the Director.<br/><br/>3. Good Behaviour<br/><br/>The lessee and any person admitted to the premises by the lessee shall conduct themselves in such a manner as not to interfere with the possession, occupancy or quiet enjoyment of other lessees.<br/><br/>4. Obligation of the Lessee<br/><br/>The lessee shall be responsible for the ordinary cleanliness of the interior of the premises and for the repair of damage caused by any willful or negligent act of the lessee or of any person whom the lessee permits on the premises, but not for damage caused by normal wear and tear.', 'vikrentitems');
				break;
			case 'VRIDOWNLOADPDF':
				$result = __('Download PDF', 'vikrentitems');
				break;
			case 'VRIQUANTITYX':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRIEACHUNIT':
				$result = __('(each unit)', 'vikrentitems');
				break;
			case 'VRICONTINUERENTING':
				$result = __('Continue Renting Items in these Dates', 'vikrentitems');
				break;
			case 'VRIEMPTYCART':
				$result = __('Remove all Items', 'vikrentitems');
				break;
			case 'VRIEMPTYCARTCONFIRM':
				$result = __('This will remove all the selected items. Proceed?', 'vikrentitems');
				break;
			case 'VRIYES':
				$result = __('Yes', 'vikrentitems');
				break;
			case 'VRINO':
				$result = __('No', 'vikrentitems');
				break;
			case 'VRICARTISEMPTY':
				$result = __('Error, you haven\'t selected any Item. Please search for some items and add them to the order before saving', 'vikrentitems');
				break;
			case 'VRIERRITEMFARENOTFOUND':
				$result = __('Error, no valid fares found for an item. Unable to add the item to the order. Please try again', 'vikrentitems');
				break;
			case 'VRI_PERDAY':
				$result = __('per Day', 'vikrentitems');
				break;
			case 'VRI_PERHOUR':
				$result = __('per Hour', 'vikrentitems');
				break;
			case 'VRIAVAILSINGLEDAY':
				$result = __('Hourly Availability for the day %s', 'vikrentitems');
				break;
			case 'VRIQUANTITYITEM':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRIDISCSQUANTSQ':
				$result = __('Quantity', 'vikrentitems');
				break;
			case 'VRIDISCSQUANTSSAVE':
				$result = __('Save', 'vikrentitems');
				break;
			case 'VRIDISCSQUANTSORMORE':
				$result = __('(or more)', 'vikrentitems');
				break;
			case 'VRIEMPTYCARTCHANGEDATES':
				$result = __('Remove all Items and Change Dates', 'vikrentitems');
				break;
			case 'VRIGOTOSUMMARY':
				$result = __('View your Order Summary', 'vikrentitems');
				break;
			case 'VRIFOR':
				$result = __('For', 'vikrentitems');
				break;
			case 'VRICHANGEDATES':
				$result = __('Change Dates', 'vikrentitems');
				break;
			case 'VRICHANGEDATESCONFIRM':
				$result = __('For changing the dates you need to remove all the selected items first. Proceed?', 'vikrentitems');
				break;
			case 'VRICONTINUESEARCH':
				$result = __('Search', 'vikrentitems');
				break;
			case 'VRICONTINUECATEGSEARCH':
				$result = __('Category', 'vikrentitems');
				break;
			case 'VRIMIGHTINTEREST':
				$result = __('You might also be interested in:', 'vikrentitems');
				break;
			case 'VRIMIGHTINTERESTBOOK':
				$result = __('Book', 'vikrentitems');
				break;
			case 'VRILEGH':
				$result = __('H', 'vikrentitems');
				break;
			case 'VRILEGU':
				$result = __('Qt.', 'vikrentitems');
				break;
			case 'VRICOMPLETEYOURORDER':
				$result = __('Confirm Order', 'vikrentitems');
				break;
			case 'VRIGLOBDAYCLOSED':
				$result = __('We are closed on this day', 'vikrentitems');
				break;
			case 'VRIERRGLOBCLOSEDON':
				$result = __('Sorry, Pick up and Drop off is not available on the %s', 'vikrentitems');
				break;
			case 'VRIERRGLOBCLOSEDONWDAY':
				$result = __('Sorry, Pick up and Drop off is not available on %s', 'vikrentitems');
				break;
			case 'VRIDELIVERYSERVICEAVLB':
				$result = __('Delivery Service Available', 'vikrentitems');
				break;
			case 'VRIDELIVERYSERVICETITLE':
				$result = __('Delivery Service', 'vikrentitems');
				break;
			case 'VRIDELIVERYIDLIKE':
				$result = __('I\'d like to have this item delivered', 'vikrentitems');
				break;
			case 'VRIDELIVERYADDRESS':
				$result = __('Delivery Address', 'vikrentitems');
				break;
			case 'VRIDELIVERYDISTANCE':
				$result = __('Distance', 'vikrentitems');
				break;
			case 'VRIDELIVERYCOST':
				$result = __('Delivery Cost', 'vikrentitems');
				break;
			case 'VRIDELIVERYADDRESSCHANGE':
				$result = __('Change Address', 'vikrentitems');
				break;
			case 'VRIDELMAPERR1':
				$result = __('Error, unable to proceed because of wrong base address.', 'vikrentitems');
				break;
			case 'VRIDELMAPERR2':
				$result = __('Error, could not find destination address. Please enter a valid delivery address. ', 'vikrentitems');
				break;
			case 'VRIDELMAPERR3':
				$result = __('Error, no route could be found between the origin and destination. Please enter a valid delivery address. ', 'vikrentitems');
				break;
			case 'VRIDELMAPERR4':
				$result = __('Error, destination address is too far away (%destd %u). The maximum distance covered is %maxd %u', 'vikrentitems');
				break;
			case 'VRIDELMAPERR5':
				$result = __('HTTP Error storing the delivery address. Please try again.', 'vikrentitems');
				break;
			case 'VRIDELIVERYVALIDATEADDRESS':
				$result = __('Validate Address', 'vikrentitems');
				break;
			case 'VRIDELMAPDISTANCE':
				$result = __('Distance:', 'vikrentitems');
				break;
			case 'VRIDELMAPCOST':
				$result = __('Delivery Cost:', 'vikrentitems');
				break;
			case 'VRIDELIVERYCONTINUEBTN':
				$result = __('Continue', 'vikrentitems');
				break;
			case 'VRIDELIVERYSESSERR':
				$result = __('Error retrieving the delivery address, please remove this Item from your cart and enter a valid delivery address.', 'vikrentitems');
				break;
			case 'VRISUMMARYDELIVERYTO':
				$result = __('Delivery to:', 'vikrentitems');
				break;
			case 'VRISUMMARYDELIVERYSERVICE':
				$result = __('Total Delivery Service', 'vikrentitems');
				break;
			case 'VRIMAILDELIVERYTO':
				$result = __('Delivery to:', 'vikrentitems');
				break;
			case 'VRIMAILTOTDELIVERY':
				$result = __('Delivery Cost', 'vikrentitems');
				break;
			case 'VRIAMOUNTPAID':
				$result = __('Amount Paid', 'vikrentitems');
				break;
			case 'VRITOTALREMAINING':
				$result = __('Remaining Balance', 'vikrentitems');
				break;
			case 'VRIERRPICKPASSED':
				$result = __('Pick up for today no longer available at this time, please select a different Pick up date and time', 'vikrentitems');
				break;
			case 'VRIADMINDISCOUNT':
				$result = __('Discount', 'vikrentitems');
				break;
			case 'VRIICSEXPSUMMARY':
				$result = __('Rental %s @ %s', 'vikrentitems');
				break;
			case 'VRIERRMINDAYSADV':
				$result = __('Error, the minimum number of days in advance for bookings is %d', 'vikrentitems');
				break;
			case 'VRILISTKITPICK':
				$result = __('View Kit Details', 'vikrentitems');
				break;
			case 'VRIKITITEMSINCL':
				$result = __('Items included in this Set', 'vikrentitems');
				break;
			case 'VRRETURNINGCUSTOMER':
				$result = __('Returning Customer?', 'vikrentitems');
				break;
			case 'VRENTERPINCODE':
				$result = __('Please enter your PIN Code', 'vikrentitems');
				break;
			case 'VRAPPLYPINCODE':
				$result = __('Apply', 'vikrentitems');
				break;
			case 'VRWELCOMEBACK':
				$result = __('Welcome back', 'vikrentitems');
				break;
			case 'VRINVALIDPINCODE':
				$result = __('Invalid PIN Code. Please try again or just enter your information below', 'vikrentitems');
				break;
			case 'VRYOURPIN':
				$result = __('PIN Code', 'vikrentitems');
				break;
			case 'VRINVALIDCONFIRMNUMBER':
				$result = __('Error, Invalid Confirmation Number', 'vikrentitems');
				break;
			case 'VRSEARCHCONFIRMNUMB':
				$result = __('Search Orders', 'vikrentitems');
				break;
			case 'VRSEARCHCONFIRMNUMBBTN':
				$result = __('Search', 'vikrentitems');
				break;
			case 'VRCONFIRMNUMBORPIN':
				$result = __('Confirmation Number or PIN Code', 'vikrentitems');
				break;
			case 'VRCONFIRMNUMB':
				$result = __('Confirmation Number', 'vikrentitems');
				break;
			case 'VRCANCELLED':
				$result = __('Cancelled', 'vikrentitems');
				break;
			case 'VRIRENTCUSTRATEPLAN':
				$result = __('Rental Cost', 'vikrentitems');
				break;
			case 'VRIBOOKNOLONGERPAYABLE':
				$result = __('Error, this order has a pick up date in the past and it was not confirmed on time. The order is now Cancelled.', 'vikrentitems');
				break;
			case 'VRIEXTRASERVICES':
				$result = __('Extra Services', 'vikrentitems');
				break;
			case 'VRIYOURORDCONFAT':
				$result = __('Your confirmed order at %s', 'vikrentitems');
				break;
			case 'VRIYOURORDISCONF':
				$result = __('Order Confirmed', 'vikrentitems');
				break;
			case 'VRIYOURORDISPEND':
				$result = __('Order waiting for the payment', 'vikrentitems');
				break;
			case 'VRIYOURORDISCANC':
				$result = __('Order Cancelled', 'vikrentitems');
				break;
			case 'VRIRENTALFOR':
				$result = __('Rental for', 'vikrentitems');
				break;
			case 'VRICARTCONFRMITEM':
				$result = __('Do you want to remove the selected item from the cart?', 'vikrentitems');
				break;
			case 'VRIDELIVERYADDRESSENTER':
				$result = __('Enter your address for delivery', 'vikrentitems');
				break;
			case 'VRICONFIRMATIONNUMBER':
				$result = __('Confirmation Number', 'vikrentitems');
				break;
			case 'VRIOFFCCINVCC':
				$result = __('Invalid Credit Card Information Received, please try again', 'vikrentitems');
				break;
			case 'VRIOFFCCINVPAY':
				$result = __('The payment was not verified, please try again', 'vikrentitems');
				break;
			case 'VRIOFFCCTHANKS':
				$result = __('Thank you! Credit Card Information Successfully Received', 'vikrentitems');
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
			case 'VRRESTRERRWDAYARRIVAL':
				$result = __('Error, the pick up day in %s must be on a %s. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMAXLOSEXCEEDED':
				$result = __('Error, the Maximum Num of Days in %s is %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMINLOSEXCEEDED':
				$result = __('Error, the Minimum Num of Days in %s is %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMULTIPLYMINLOS':
				$result = __('Error, the Num of Days allowed in %s must be a multiple of %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCOMBO':
				$result = __('Error, the drop off day in %s must be on a %s if picking up on a %s', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYARRIVALRANGE':
				$result = __('Error, the pick up day in these dates must be on a %s. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMAXLOSEXCEEDEDRANGE':
				$result = __('Error, the Maximum Num of Days in these dates is %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMINLOSEXCEEDEDRANGE':
				$result = __('Error, the Minimum Num of Days in these dates is %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRMULTIPLYMINLOSRANGE':
				$result = __('Error, the Num of Days allowed in these dates must be a multiple of %d. Please try again.', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCOMBORANGE':
				$result = __('Error, the drop off day in these dates must be on a %s if picking up on a %s', 'vikrentitems');
				break;
			case 'VRRESTRTIPWDAYARRIVAL':
				$result = __('Some results were excluded: try selecting the pick up day in %s as a %s.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMAXLOSEXCEEDED':
				$result = __('Some results were excluded: the Maximum Num of Days in %s is %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMINLOSEXCEEDED':
				$result = __('Some results were excluded: the Minimum Num of Days in %s is %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMULTIPLYMINLOS':
				$result = __('Some results were excluded: the Num of Days allowed in %s should be a multiple of %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPWDAYCOMBO':
				$result = __('Some results were excluded: the drop off day in %s should be on a %s if picking up on a %s', 'vikrentitems');
				break;
			case 'VRRESTRTIPWDAYARRIVALRANGE':
				$result = __('Some results were excluded: the pick up day in these dates should be on a %s.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMAXLOSEXCEEDEDRANGE':
				$result = __('Some results were excluded: the Maximum Num of Days in these dates is %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMINLOSEXCEEDEDRANGE':
				$result = __('Some results were excluded: the Minimum Num of Days in these dates is %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPMULTIPLYMINLOSRANGE':
				$result = __('Some results were excluded: the Num of Days allowed in these dates should be a multiple of %d.', 'vikrentitems');
				break;
			case 'VRRESTRTIPWDAYCOMBORANGE':
				$result = __('Some results were excluded: the drop off day in these dates should be on a %s if picking up on a %s', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCTAMONTH':
				$result = __('Error, pick ups on %s are not permitted on %s', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCTDMONTH':
				$result = __('Error, drop offs on %s are not permitted on %s', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCTARANGE':
				$result = __('Error, pick ups on %s are not permitted on the selected dates', 'vikrentitems');
				break;
			case 'VRRESTRERRWDAYCTDRANGE':
				$result = __('Error, drop offs on %s are not permitted on the selected dates', 'vikrentitems');
				break;
			case 'VRIMAILSUBJECT':
				$result = __('Your reservation at %s', 'vikrentitems');
				break;
			case 'VRINEWORDERID':
				$result = __('New Order #%s', 'vikrentitems');
				break;
			case 'VRISEARCHBUTTON':
				$result = __('Search', 'vikrentitems');
				break;
			case 'VRCUSTOMERCOMPANY':
				$result = __('Company Name', 'vikrentitems');
				break;
			case 'VRCUSTOMERCOMPANYVAT':
				$result = __('VAT ID', 'vikrentitems');
				break;
			case 'VRICREDITCARDTYPE':
				$result = __('Card Type', 'vikrentitems');
				break;
			case 'VRICCOFFLINECCTOGGLEFORM':
				$result = __('Hide/Show Credit Card Details Submission Form', 'vikrentitems');
				break;
			case 'ORDER_TERMSCONDITIONS':
				$result = __('I agree to the terms and conditions', 'vikrentitems');
				break;
		}

		return $result;
	}
}

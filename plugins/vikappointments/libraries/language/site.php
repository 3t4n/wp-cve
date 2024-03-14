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
 * Switcher class to translate the VikAppointments plugin site languages.
 *
 * @since 	1.0
 */
class VikAppointmentsLanguageSite implements JLanguageHandler
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
			 * Employees list view.
			 */

			case 'VAPEMPQUICKCONTACT':
				$result = __('Quick Contact', 'vikappointments');
				break;

			case 'VAPEMPSENDERNAMELABEL':
				$result = __('Your Name', 'vikappointments');
				break;

			case 'VAPEMPSENDERMAILLABEL':
				$result = __('Your e-mail', 'vikappointments');
				break;

			case 'VAPEMPMAILCONTENTLABEL':
				$result = __('Your questions', 'vikappointments');
				break;

			case 'VAPEMPTALKINGTO':
				$result = __('You are talking to %s', 'vikappointments');
				break;

			case 'VAPEMPSENDMAILOK':
				$result = __('Send', 'vikappointments');
				break;

			case 'VAPEMPSENDMAILCANCEL':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'VAPEMPNOTFOUNDERROR':
				$result = __('The selected employee doesn\'t exist!', 'vikappointments');
				break;

			case 'VAPEMPNOTREACHABLE':
				$result = __('The selected employee cannot be contacted!', 'vikappointments');
				break;

			/**
			 * Services list view.
			 */

			case 'VAPSERQUICKCONTACT':
				$result = __('Ask Info', 'vikappointments');
				break;

			case 'VAPSERSENDERNAMELABEL':
				$result = __('Your Name', 'vikappointments');
				break;

			case 'VAPSERSENDERMAILLABEL':
				$result = __('Your e-mail', 'vikappointments');
				break;

			case 'VAPSERMAILCONTENTLABEL':
				$result = __('Your questions', 'vikappointments');
				break;

			case 'VAPSERTALKINGTO':
				$result = __('You are asking for %s', 'vikappointments');
				break;

			case 'VAPSERSENDMAILOK':
				$result = __('Send', 'vikappointments');
				break;

			case 'VAPSERSENDMAILCANCEL':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'VAPSERNOTFOUNDERROR':
				$result = __('The selected service doesn\'t exist!', 'vikappointments');
				break;

			case 'VAPSERNOTREACHABLE':
				$result = __('You can\'t ask for the selected service!', 'vikappointments');
				break;

			/**
			 * Service details view & employee details view.
			 */

			case 'VAPRESDATETIMENOTAVERR':
				$result = __('The employee at the selected date and time is already occupied.', 'vikappointments');
				break;

			case 'VAPBOOKNOTIMESELECTED':
				$result = __('Please choose a date & time first.', 'vikappointments');
				break;

			case 'VAPBOOKNOWBUTTON':
				$result = __('Book Now', 'vikappointments');
				break;

			case 'VAPFINDRESTIMENOAV':
				$result = __('At this date & time there is already a reservation', 'vikappointments');
				break;

			case 'VAPFINDRESBOOKNOW':
				$result = __('Free Time', 'vikappointments');
				break;

			case 'VAPFINDRESNOENOUGHTIME':
				$result = __('There isn\'t enough time to book this service', 'vikappointments');
				break;

			case 'VAPFINDRESCLOSINGDAY':
				$result = __('This day the company is closed', 'vikappointments');
				break;

			case 'VAPFINDRESNODAYEMPLOYEE':
				$result = __('This day the employee doesn\'t work', 'vikappointments');
				break;

			case 'VAPFINDRESTIMEINTHEPAST':
				$result = __('The selected date and time is in the past!', 'vikappointments');
				break;

			case 'VAPRESNODAYCHAR':
				$result = __(' ', 'vikappointments');
				break;

			case 'VAPSERAVAILOPTIONSTITLE':
				$result = __('Available Options', 'vikappointments');
				break;

			case 'VAPSUMMARYPEOPLE':
				$result = __('People', 'vikappointments');
				break;

			case 'VAPORDERTOTALPAID':
				$result = __('Total Paid', 'vikappointments');
				break;

			case 'VAPFINDRESPEOPLENOTVALID':
				$result = __('Number of people selected is not valid!', 'vikappointments');
				break;

			/**
			 * Appointment confirmation view.
			 */

			case 'VAPOPTIONSHEADTITLE':
				$result = __('Options', 'vikappointments');
				break;

			case 'VAPORDERSUMMARYHEADTITLE':
				$result = __('Order Summary', 'vikappointments');
				break;

			case 'VAPCOMPLETEORDERHEADTITLE':
				$result = __('Complete Order', 'vikappointments');
				break;

			case 'VAPSUMMARYEMPLOYEE':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPSUMMARYSERVICE':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAPSUMMARYCHECKIN':
				$result = __('Date & Time', 'vikappointments');
				break;

			case 'VAPSUMMARYTOTAL':
				$result = __('Total', 'vikappointments');
				break;

			case 'VAPENTERYOURCOUPON':
				$result = __('Enter here your Coupon Code', 'vikappointments');
				break;

			case 'VAPAPPLYCOUPON':
				$result = __('Apply Coupon', 'vikappointments');
				break;

			case 'VAPCOUPONFOUND':
				$result = __('The Coupon code has been applied!', 'vikappointments');
				break;

			case 'VAPCOUPONNOTVALID':
				$result = __('Error, Invalid Coupon', 'vikappointments');
				break;

			case 'VAPMETHODOFPAYMENT':
				$result = __('Methods of Payment', 'vikappointments');
				break;

			case 'VAPCONTINUEBUTTON':
				$result = __('Continue', 'vikappointments');
				break;

			case 'VAPCONFIRMRESBUTTON':
				$result = __('Confirm Appointment', 'vikappointments');
				break;

			case 'VAPERRINSUFFCUSTF':
				$result = __('Error, please fill in all the required fields', 'vikappointments');
				break;

			case 'VAPERRINVPAYMENT':
				$result = __('Error, the selected method of payment doesn\'t exist', 'vikappointments');
				break;

			case 'VAPOPTIONMAXQUANTITYNOTICE':
				$result = __('You have reached the maximum number for this option.', 'vikappointments');
				break;

			case 'VAPCONFAPPZIPERROR':
				$result = __('We do not offer services for areas with that ZIP code.', 'vikappointments');
				break;

			case 'VAPCONFAPPREQUIREDERROR':
				$result = __('Please, fill in all the required (*) fields.', 'vikappointments');
				break;

			case 'VAPCONTINUESHOPPINGLINK':
				$result = __('Continue Shopping', 'vikappointments');
				break;

			case 'VAPSUMMARYCOUPON':
				$result = __('Coupon', 'vikappointments');
				break;

			case 'VAPCFFILEUPLOADED':
				$result = __('(%s Uploaded)', 'vikappointments');
				break;

			case 'VAPCONFAPPREQUIREDMAILERROR':
				$result = __('Please, insert a valid e-mail address.', 'vikappointments');
				break;

			/**
			 * Order summary view.
			 */

			case 'VAPORDERNUM':
				$result = __('Order Number', 'vikappointments');
				break;

			case 'VAPORDERKEY':
				$result = __('Order Key', 'vikappointments');
				break;

			case 'VAPORDERSUBMITBUTTON':
				$result = __('Submit', 'vikappointments');
				break;

			case 'VAPORDERTITLE1':
				$result = __('Your Order', 'vikappointments');
				break;

			case 'VAPORDERTITLE2':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPORDERTITLE3':
				$result = __('Options', 'vikappointments');
				break;

			case 'VAPORDERNUMBER':
				$result = __('Order Number', 'vikappointments');
				break;

			case 'VAPORDERKEY':
				$result = __('Order Key', 'vikappointments');
				break;

			case 'VAPORDERSTATUS':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPORDERBEGINDATETIME':
				$result = __('Begin', 'vikappointments');
				break;

			case 'VAPORDERENDDATETIME':
				$result = __('End', 'vikappointments');
				break;

			case 'VAPORDEREMPLOYEE':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPORDERSERVICE':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAPORDERLINK':
				$result = __('Order Link', 'vikappointments');
				break;

			case 'VAPORDERPAYMENT':
				$result = __('Payment', 'vikappointments');
				break;

			case 'VAPORDERDEPOSIT':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPORDERRESERVATIONCOST':
				$result = __('Reservation Cost', 'vikappointments');
				break;

			case 'VAPORDERCOUPON':
				$result = __('Coupon', 'vikappointments');
				break;

			case 'VAPPERSONALDETAILS':
				$result = __('Personal Details', 'vikappointments');
				break;

			case 'VAPCUSTOMEREMAILSUBJECT':
				$result = __('Your reservation for %s', 'vikappointments');
				break;

			case 'VAPORDERCANCBUTTON':
				$result = __('Cancel Reservation', 'vikappointments');
				break;

			case 'VAPORDERCANCDISABLEDERROR':
				$result = __('Impossible to cancel your reservation. You have not the requirements to do this action.', 'vikappointments');
				break;

			case 'VAPORDERCANCEXPIREDERROR':
				$result = __('Impossible to cancel your reservation. You can do this action at least %d day(s) before the check-in.', 'vikappointments');
				break;

			case 'VAPORDERRESERVATIONERROR':
				$result = __('Reservation not found!', 'vikappointments');
				break;

			case 'VAPCANCELORDERMESSAGE':
				$result = __('Do you want to cancel your reservation?', 'vikappointments');
				break;

			case 'VAPORDERCANCALLBUTTON':
				$result = __('Cancel All Reservations', 'vikappointments');
				break;

			/**
			 * Quick contact form.
			 */

			case 'VAPQUICKCONTACTNOCONTENT':
				$result = __('You cannot leave mail content blank!', 'vikappointments');
				break;

			case 'VAPEMPQUICKCONTACTSUBJECT':
				$result = __('New Quick Contact', 'vikappointments');
				break;

			case 'VAPSERQUICKCONTACTSUBJECT':
				$result = __('New Quick Contact for %s', 'vikappointments');
				break;

			/**
			 * Commons.
			 */

			case 'VAPSTATUSCONFIRMED':
				$result = __('Confirmed', 'vikappointments');
				break;

			case 'VAPSTATUSPENDING':
				$result = __('Pending', 'vikappointments');
				break;

			case 'VAPSTATUSREMOVED':
				$result = __('Removed', 'vikappointments');
				break;

			case 'VAPSHORTCUTMINUTE':
				$result = __('min.', 'vikappointments');
				break;

			case 'VAPFREE':
				$result = __('Free!', 'vikappointments');
				break;

			case 'CUSTOMF_NAME':
				$result = __('First Name', 'vikappointments');
				break;

			case 'CUSTOMF_LNAME':
				$result = __('Last Name', 'vikappointments');
				break;

			case 'CUSTOMF_EMAIL':
				$result = __('E-mail', 'vikappointments');
				break;

			case 'CUSTOMF_PHONE':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPTRANSACTIONNAME':
				$result = __('Order @ %s', 'vikappointments');
				break;

			case 'VAPLOGINRADIOCHOOSE1':
				$result = __('Login', 'vikappointments');
				break;

			case 'VAPLOGINRADIOCHOOSE2':
				$result = __('Create New Account', 'vikappointments');
				break;

			case 'VAPLOGINTITLE':
				$result = __('Login', 'vikappointments');
				break;

			case 'VAPLOGINUSERNAME':
				$result = __('Username', 'vikappointments');
				break;

			case 'VAPLOGINPASSWORD':
				$result = __('Password', 'vikappointments');
				break;

			case 'VAPLOGINSUBMIT':
				$result = __('Log in', 'vikappointments');
				break;

			case 'VAPREGISTRATIONTITLE':
				$result = __('Registration', 'vikappointments');
				break;

			case 'VAPREGNAME':
				$result = __('First Name', 'vikappointments');
				break;

			case 'VAPREGLNAME':
				$result = __('Last Name', 'vikappointments');
				break;

			case 'VAPREGEMAIL':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPREGUNAME':
				$result = __('Username', 'vikappointments');
				break;

			case 'VAPREGPWD':
				$result = __('Password', 'vikappointments');
				break;

			case 'VAPREGCONFIRMPWD':
				$result = __('Confirm Password', 'vikappointments');
				break;

			case 'VAPREGSIGNUPBTN':
				$result = __('Register', 'vikappointments');
				break;

			case 'VAPLOGOUTTITLE':
				$result = __('Logout', 'vikappointments');
				break;

			case 'VAPREGISTRATIONFAILED1':
				$result = __('The registration feature is disabled!', 'vikappointments');
				break;

			case 'VAPREGISTRATIONFAILED2':
				$result = __('Arguments not valid! Please fill all required field.', 'vikappointments');
				break;

			case 'VAPSTATUSCANCELED':
				$result = __('Cancelled', 'vikappointments');
				break;

			case 'VAPCLOSE':
				$result = __('Close', 'vikappointments');
				break;

			case 'VAPSAVEANDCLOSE':
				$result = __('Save & Close', 'vikappointments');
				break;

			case 'VAPSAVE':
				$result = __('Save', 'vikappointments');
				break;

			case 'VAPNEW':
				$result = __('New', 'vikappointments');
				break;

			case 'VAPDELETE':
				$result = __('Delete', 'vikappointments');
				break;

			case 'VAPSMSFAILEDSUBJECT':
				$result = __('SMS Failed!', 'vikappointments');
				break;

			case 'VAPFILEUPLOADERR1':
				$result = __('Impossible to upload the file!', 'vikappointments');
				break;

			case 'VAPFILEUPLOADERR2':
				$result = __('The format of the uploaded file is not allowed!', 'vikappointments');
				break;

			case 'VAPADDCARTBUTTON':
				$result = __('Add Service To Cart', 'vikappointments');
				break;

			case 'VAPCARTITEMADDOK':
				$result = __('Service added to the cart!', 'vikappointments');
				break;

			case 'VAPCARTMULTIITEMSADDOK':
				$result = __('Services added to the cart!', 'vikappointments');
				break;

			case 'VAPCARTITEMADDERR1':
				$result = __('You cannot add any more services to your order. You have reached the maximum number of elements in your cart.', 'vikappointments');
				break;

			case 'VAPCARTITEMADDERR2':
				$result = __('You have already added this service to your order!', 'vikappointments');
				break;

			case 'VAPCARTOPTADDERR1':
				$result = __('Item not found! You cannot add any option to the selected item.', 'vikappointments');
				break;

			case 'VAPCARTITEMDELERR':
				$result = __('An error occurred! It is not possible to remove the item from you cart. Please, try to empty the cart.', 'vikappointments');
				break;

			case 'VAPCARTOPTDELERR':
				$result = __('An error occurred! It is not possible to remove the option from you cart. Please, try to empty the cart.', 'vikappointments');
				break;

			case 'VAPCARTEMPTYERR':
				$result = __('Your cart is empty!', 'vikappointments');
				break;

			case 'VAPCARTITEMNOTAVERR1':
				$result = __('The item %s has been removed from your cart, it is no longer available.', 'vikappointments');
				break;

			case 'VAPCARTITEMNOTAVERR2':
				$result = __('The item %s has been removed from your cart, the end time is out of the employee working shift.', 'vikappointments');
				break;

			case 'VAPCARTITEMNOTAVERR3':
				$result = __('The item %s has been removed from your cart, the check-in date & time is in the past.', 'vikappointments');
				break;

			case 'VAPCARTRECURITEMERR1':
				$result = __('Service @ %s not added! You have reached the maximum number of bookable services.', 'vikappointments');
				break;

			case 'VAPCARTRECURITEMERR2':
				$result = __('Service @ %s not added! The service is already in the cart.', 'vikappointments');
				break;

			case 'VAPCARTRECURITEMERR3':
				$result = __('Service @ %s not added! The service is not available.', 'vikappointments');
				break;

			case 'VAPCARTQUANTITYSUFFIX':
				$result = __('x', 'vikappointments');
				break;

			case 'VAPRECURRENCECONFIRM':
				$result = __('I would like this appointment to be recurring', 'vikappointments');
				break;

			case 'VAPRECURRENCEREPEAT':
				$result = __('Repeat every', 'vikappointments');
				break;

			case 'VAPRECURRENCEFOR':
				$result = __('For the next', 'vikappointments');
				break;

			case 'VAPRECURRENCENONE':
				$result = __('- None -', 'vikappointments');
				break;

			case 'VAPORDERCANCELEDSUBJECT':
				$result = __('%s - Order Cancelled', 'vikappointments');
				break;

			case 'VAPDAY':
				$result = __('Day', 'vikappointments');
				break;

			case 'VAPDAYS':
				$result = __('Days', 'vikappointments');
				break;

			case 'VAPWEEK':
				$result = __('Week', 'vikappointments');
				break;

			case 'VAPWEEKS':
				$result = __('Weeks', 'vikappointments');
				break;

			case 'VAPMONTH':
				$result = __('Month', 'vikappointments');
				break;

			case 'VAPMONTHS':
				$result = __('Months', 'vikappointments');
				break;

			case 'VAPRESTORE':
				$result = __('Restore', 'vikappointments');
				break;

			/**
			 * Payment gateways.
			 */

			case 'VAPINVALIDPAYMENTSUBJECT':
				$result = __('Invalid Payment Received', 'vikappointments');
				break;

			case 'VAPINVALIDPAYMENTCONTENT':
				$result = __('Invalid Payment Log:', 'vikappointments');
				break;

			case 'VAPCCNUMBER':
				$result = __('Credit Card Number', 'vikappointments');
				break;

			case 'VAPEXPIRINGDATE':
				$result = __('Valid Through', 'vikappointments');
				break;

			case 'VAPCVV':
				$result = __('CVC', 'vikappointments');
				break;

			case 'VAPCCPAYNOW':
				$result = __('Pay Now', 'vikappointments');
				break;

			case 'VAPOFFCCMAILSUBJECT':
				$result = __('Offline CC Payment Received', 'vikappointments');
				break;

			case 'VAPPAYNOTVERIFIED':
				$result = __('The payment was not verified, please try again', 'vikappointments');
				break;

			case 'VAPPAYMENTRECEIVED':
				$result = __('Thank you! The payment was verified successfully', 'vikappointments');
				break;

			case 'VAPOFFCCPAYMENTRECEIVED':
				$result = __('Thank you! Credit Card Information Successfully Received.', 'vikappointments');
				break;

			/**
			 * Employees area.
			 */

			case 'VAPEMPPROFILETITLE':
				$result = __('Profile', 'vikappointments');
				break;

			case 'VAPEMPWORKDAYSTITLE':
				$result = __('Working Days', 'vikappointments');
				break;

			case 'VAPEMPSERVICESTITLE':
				$result = __('Services List', 'vikappointments');
				break;

			case 'VAPEMPPAYMENTSTITLE':
				$result = __('Payments', 'vikappointments');
				break;

			/**
			 * Employees area - edit profile.
			 */

			case 'VAPEDITEMPTITLE':
				$result = __('Edit Profile', 'vikappointments');
				break;

			case 'VAPEDITEMPLOYEE17':
				$result = __('Closed', 'vikappointments');
				break;

			/**
			 * Employees area - services list.
			 */

			case 'VAPEMPSERLISTTITLE':
				$result = __('Services List - %s', 'vikappointments');
				break;

			/**
			 * Employees area - service management.
			 */

			case 'VAPEDITSERTITLE':
				$result = __('Edit Service', 'vikappointments');
				break;

			case 'VAPNEWSERTITLE':
				$result = __('New Service', 'vikappointments');
				break;

			case 'VAPEMPSERREMOVED1':
				$result = __('Service removed successfully!', 'vikappointments');
				break;

			case 'VAPSERVICENOGROUP':
				$result = __('- No Group -', 'vikappointments');
				break;

			/**
			 * Employees area - reservation management.
			 */

			case 'VAPEDITRESTITLE':
				$result = __('Edit Reservation', 'vikappointments');
				break;

			case 'VAPNEWRESTITLE':
				$result = __('New Reservation', 'vikappointments');
				break;

			case 'VAPEMPRESREMOVED1':
				$result = __('Reservation removed Successfully!', 'vikappointments');
				break;

			case 'VAPREMRESMAILSUBJECT':
				$result = __('Reservation Removed by Employee #%d', 'vikappointments');
				break;

			case 'VAPREMRESMAILCONT':
				$result = __('<p>The following reservation has been removed.</p><p>%s</p>', 'vikappointments');
				break;

			/**
			 * Employees area - working day management.
			 */

			case 'VAPDAYCUSTOM':
				$result = __('- Custom -', 'vikappointments');
				break;

			/**
			 * Employees area - payments list.
			 */

			case 'VAPEMPPAYLISTTITLE':
				$result = __('Payments List - %s', 'vikappointments');
				break;

			/**
			 * Employees area - payment management.
			 */

			case 'VAPEDITPAYTITLE':
				$result = __('Edit Payment', 'vikappointments');
				break;

			case 'VAPNEWPAYTITLE':
				$result = __('New Payment', 'vikappointments');
				break;

			case 'VAPEMPPAYREMOVED1':
				$result = __('Payment removed Successfully!', 'vikappointments');
				break;

			/**
			 * Employees  area - service working days view.
			 */

			case 'VAPEMPSERWDTITLE':
				$result = __('%s - Working Days', 'vikappointments');
				break;

			/**
			 * Employee mail registration.
			 */

			case 'VAPEMPREGADMINSUBJECT':
				$result = __('New Employee Registered', 'vikappointments');
				break;

			case 'VAPEMPREGADMINCONTENT':
				$result = __('%s has just submitted a registration request as employee.', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.4 - site
			 */

			case 'VAPEMPALLGROUPSOPTION':
				$result = __('- All Groups -', 'vikappointments');
				break;

			case 'VAPEMPLISTRESULTPLUS':
				$result = __('%d employees found with your search', 'vikappointments');
				break;

			case 'VAPEMPLISTRESULT1':
				$result = __('1 employee found with your search', 'vikappointments');
				break;

			case 'VAPEMPLISTRESULT0':
				$result = __('No employee found from your search', 'vikappointments');
				break;

			case 'VAPREVIEWSTITLE':
				$result = __('Reviews', 'vikappointments');
				break;

			case 'VAPREVIEWORDERINGTIMESTAMP':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPREVIEWORDERINGRATING':
				$result = __('Rating', 'vikappointments');
				break;

			case 'VAPREVIEWCOMMENTSHOWMORE':
				$result = __('Show More', 'vikappointments');
				break;

			case 'VAPREVIEWCOMMENTSHOWLESS':
				$result = __('Show Less', 'vikappointments');
				break;

			case 'VAPREVIEWLOADMOREBTN':
				$result = __('Load More Reviews', 'vikappointments');
				break;

			case 'VAPLEAVEREVIEWLINK':
				$result = __('Leave a Review', 'vikappointments');
				break;

			case 'VAPSUBMITREVIEWLINK':
				$result = __('Submit Review', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWLBLTITLE':
				$result = __('Title', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWLBLCOMMENT':
				$result = __('Comment', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWLBLRATING':
				$result = __('Rating', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWCHARSLEFT':
				$result = __('Characters Left:', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWMINCHARS':
				$result = __('Minimum Characters:', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWAUTHERR':
				$result = __('You are not able to leave a review for this element!', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWFILLERR':
				$result = __('Missing Required Fields! Please, fill in all the requird (*) fields.', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWINSERTERR':
				$result = __('An error occurred during the creation of the review. If the problem persists, please try to contact the administrator.', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWCREATEDCONF':
				$result = __('Thank you for your review!', 'vikappointments');
				break;

			case 'VAPPOSTREVIEWCREATEDPEND':
				$result = __('Thanks, your review has been submitted for approval.', 'vikappointments');
				break;

			case 'VAPNOREVIEWSSUBTITLE':
				$result = __('No review', 'vikappointments');
				break;

			case 'VAPREVIEWSSUBTITLE1':
				$result = __('%d reviews', 'vikappointments');
				break;

			case 'VAPREVIEWSSUBTITLE2':
				$result = __('%d votes', 'vikappointments');
				break;

			case 'VAPOPTIONREQUIREDERR':
				$result = __('Please remember to pick all the required (*) options.', 'vikappointments');
				break;

			case 'VAPALLORDERSTITLE':
				$result = __('Hi %s!', 'vikappointments');
				break;

			case 'VAPALLORDERSPROFILEBUTTON':
				$result = __('My Profile', 'vikappointments');
				break;

			case 'VAPALLORDERSVOID':
				$result = __('You haven\'t placed yet any order', 'vikappointments');
				break;

			case 'VAPALLORDERSMULTIPLE':
				$result = __('Multiple Orders', 'vikappointments');
				break;

			case 'VAPALLORDERSBUTTON':
				$result = __('View All Orders', 'vikappointments');
				break;

			case 'VAPUSERPROFILETITLE':
				$result = __('User Profile', 'vikappointments');
				break;

			case 'VAPUSERPROFILEDATASTORED':
				$result = __('Profile information updated correctly!', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD1':
				$result = __('Full Name', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD2':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD3':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD4':
				$result = __('Country', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD5':
				$result = __('State / Province', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD6':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD7':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD8':
				$result = __('Address 2', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD9':
				$result = __('Zip Code', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD10':
				$result = __('Company Name', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD11':
				$result = __('VAT Number', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD12':
				$result = __('SSN / Fiscal Code', 'vikappointments');
				break;

			case 'VAPUSERPROFILEFIELD13':
				$result = __('Profile Image', 'vikappointments');
				break;

			case 'VAPCALENDARLEGENDGREEN':
				$result = __('Available', 'vikappointments');
				break;

			case 'VAPCALENDARLEGENDYELLOW':
				$result = __('Partially Available', 'vikappointments');
				break;

			case 'VAPCALENDARLEGENDRED':
				$result = __('Fully Occupied', 'vikappointments');
				break;

			case 'VAPCALENDARLEGENDBLUE':
				$result = __('Selected', 'vikappointments');
				break;

			case 'VAPCALENDARLEGENDGREY':
				$result = __('Closed', 'vikappointments');
				break;

			case 'VAPLOGINFORGOTPWD':
				$result = __('Forgot your password?', 'vikappointments');
				break;

			case 'VAPLOGINFORGOTUSER':
				$result = __('Forgot your username?', 'vikappointments');
				break;

			case 'VAPEMPSUBSCRTITLE':
				$result = __('Subscriptions', 'vikappointments');
				break;

			case 'VAPACTIVATEPROFILEMSG':
				$result = __('Become visible on our listings', 'vikappointments');
				break;

			case 'VAPACTIVATEPROFILEBTN':
				$result = __('Activate Account', 'vikappointments');
				break;

			case 'VAPSUBSCRTRIALTITLE':
				$result = __('Trial Subscription', 'vikappointments');
				break;

			case 'VAPSUBSCRTRIALMESSAGE':
				$result = __('Activate our %s subscription for free and take part of our listings', 'vikappointments');
				break;

			case 'VAPSUBSCRTRIALBUTTON':
				$result = __('Try Now!', 'vikappointments');
				break;

			case 'VAPSUBSCRPURCHASETITLE':
				$result = __('Purchase Subscription', 'vikappointments');
				break;

			case 'VAPSUBSCRPURCHASEMESSAGE':
				$result = __('1. Choose the subscription plan you prefer', 'vikappointments');
				break;

			case 'VAPSUBSCRPAYMENTMESSAGE':
				$result = __('2. Fill in the billing details', 'vikappointments');
				break;

			case 'VAPSUBSCRCARTHEAD':
				$result = __('Your Cart', 'vikappointments');
				break;

			case 'VAPSUBSCRPAYCHARGE':
				$result = __('Payment Charge', 'vikappointments');
				break;

			case 'VAPSUBSCRPURCHASEBUTTON':
				$result = __('Confirm Order', 'vikappointments');
				break;

			case 'VAPSUBSCRTRANSACTION':
				$result = __('%s Subscription @ %s', 'vikappointments');
				break;

			case 'VAPEMPSUBSCRPURCHTITLE':
				$result = __('Purchases', 'vikappointments');
				break;

			case 'VAPEMPACCOUNTSTATUSTITLE':
				$result = __('Account Status', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS1':
				$result = __('Account Valid Thru', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS2':
				$result = __('Confirmed Reservations', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS3':
				$result = __('Total Reservations', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS4':
				$result = __('Total Earning', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS5':
				$result = __('Unique Customers', 'vikappointments');
				break;

			case 'VAPACCOUNTSTATUS6':
				$result = __('Account Active Since', 'vikappointments');
				break;

			case 'VAPACCOUNTVALIDTHRU1':
				$result = __('Lifetime', 'vikappointments');
				break;

			case 'VAPACCOUNTVALIDTHRU2':
				$result = __('Pending', 'vikappointments');
				break;

			case 'VAPACCOUNTSEARCHCUST':
				$result = __('Search Customers', 'vikappointments');
				break;

			case 'VAPSTARTRANGE':
				$result = __('Start Date', 'vikappointments');
				break;

			case 'VAPENDRANGE':
				$result = __('End Date', 'vikappointments');
				break;

			case 'VAPCHARTSFILTER':
				$result = __('Filter', 'vikappointments');
				break;

			case 'VAPLINECHARTSUBLEG':
				$result = __('Service name (# orders)', 'vikappointments');
				break;

			case 'VAPEMPLOCATIONSTITLE':
				$result = __('Locations', 'vikappointments');
				break;

			case 'VAPEMPLOCWDTITLE':
				$result = __('Assignments', 'vikappointments');
				break;

			case 'VAPEMPLOCATIONSPAGETITLE':
				$result = __('%s - Locations', 'vikappointments');
				break;

			case 'VAPNEWEMPLOCATIONTITLE':
				$result = __('New Location', 'vikappointments');
				break;

			case 'VAPEDITEMPLOCATIONTITLE':
				$result = __('Edit Location', 'vikappointments');
				break;

			case 'VAPEMPLOCATIONREMOVED1':
				$result = __('Location removed Successfully!', 'vikappointments');
				break;

			case 'VAPEMPLOCATION1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPEMPLOCATION2':
				$result = __('Country', 'vikappointments');
				break;

			case 'VAPEMPLOCATION3':
				$result = __('State', 'vikappointments');
				break;

			case 'VAPEMPLOCATION4':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPEMPLOCATION5':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPEMPLOCATION6':
				$result = __('Zip', 'vikappointments');
				break;

			case 'VAPEMPLOCATION7':
				$result = __('Latitude', 'vikappointments');
				break;

			case 'VAPEMPLOCATION8':
				$result = __('Longitude', 'vikappointments');
				break;

			case 'VAPEMPLOCATION9':
				$result = __('Map', 'vikappointments');
				break;

			case 'VAPEMPLOCWDAYSPAGETITLE':
				$result = __('%s - Locations Assignments', 'vikappointments');
				break;

			case 'VAPSUBSCRIPTIONEXTENDED':
				$result = __('Your subscription has been extended to %s.', 'vikappointments');
				break;

			case 'VAPSUBSCRINSERTERR':
				$result = __('Impossible to save your order! Please try to contact the administrator.', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSTITLE':
				$result = __('Settings', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSGLOBAL':
				$result = __('Global', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSUPCOMING':
				$result = __('Upcoming Orders', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSCALENDARS':
				$result = __('Calendars', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSAPPSYNC':
				$result = __('Appointments Sync', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSUPDATED':
				$result = __('Settings updated correctly!', 'vikappointments');
				break;

			case 'VAPEMPSETTING1':
				$result = __('List Limit', 'vikappointments');
				break;

			case 'VAPEMPSETTING2':
				$result = __('List Position', 'vikappointments');
				break;

			case 'VAPEMPSETTING3':
				$result = __('Top', 'vikappointments');
				break;

			case 'VAPEMPSETTING4':
				$result = __('Bottom', 'vikappointments');
				break;

			case 'VAPEMPSETTING5':
				$result = __('List Ordering', 'vikappointments');
				break;

			case 'VAPEMPSETTING6':
				$result = __('Ascending', 'vikappointments');
				break;

			case 'VAPEMPSETTING7':
				$result = __('Descending', 'vikappointments');
				break;

			case 'VAPEMPSETTING8':
				$result = __('Number of Calendars', 'vikappointments');
				break;

			case 'VAPEMPSETTING9':
				$result = __('First Month', 'vikappointments');
				break;

			case 'VAPEMPSETTING10':
				$result = __('- Current -', 'vikappointments');
				break;

			case 'VAPEMPSETTING11':
				$result = __('Sync URL', 'vikappointments');
				break;

			case 'VAPEMPSETTING12':
				$result = __('Sync Password', 'vikappointments');
				break;

			case 'VAPEMPSETTING13':
				$result = __('Timezone', 'vikappointments');
				break;

			case 'VAPCONFORDNOROWS':
				$result = __('The order was not found!', 'vikappointments');
				break;

			case 'VAPCONFORDISCONFIRMED':
				$result = __('The order is already CONFIRMED.', 'vikappointments');
				break;

			case 'VAPCONFORDISREMOVED':
				$result = __('The order was expired!<br/>Please change the status from the administrator.', 'vikappointments');
				break;

			case 'VAPCONFORDCOMPLETED':
				$result = __('The order is now CONFIRMED.', 'vikappointments');
				break;

			case 'VAPCONFIRMATIONLINK':
				$result = __('Confirmation Link', 'vikappointments');
				break;

			case 'VAPCONFIGUPLOADERROR':
				$result = __('Error while uploading image', 'vikappointments');
				break;

			case 'VAPCONFIGFILETYPEERROR':
				$result = __('The selected file is not an image', 'vikappointments');
				break;

			case 'VAPQUICKCONTACTMAILSENT':
				$result = __('Thank You! The e-mail was sent correctly.', 'vikappointments');
				break;

			case 'VAPSMSMESSAGECUSTOMER':
				// @TRANSLATORS: Max 160 character (included service and date)
				$result = _x('Your appointment for {service} @ {checkin} is now CONFIRMED.', 'Max 160 character (included service and date)', 'vikappointments');
				break;

			case 'VAPSMSMESSAGECUSTOMERMULTI':
				// @TRANSLATORS: Max 160 character (included service and date)
				$result = _x('Your appointments booked on {created_on} are now CONFIRMED.', 'Max 160 character (included service and date)', 'vikappointments');
				break;

			case 'VAPSMSMESSAGEADMIN':
				// @TRANSLATORS: Max 160 character (included service and date)
				$result = _x('A new appointment @ {checkin} has been CONFIRMED for {service}.', 'Max 160 character (included service and date)', 'vikappointments');
				break;

			case 'VAPSMSMESSAGEADMINMULTI':
				// @TRANSLATORS: Max 160 character (included service and date)
				$result = _x('The appointments of {customer} have been CONFIRMED.', 'Max 160 character (included service and date)', 'vikappointments');
				break;

			case 'VAPICSURL':
				$result = __('You can use this link to automatically synchronize the appointments on VikAppointments into your favourite calendar provider. You have only to specify this link into the apposite page of your provider and it will download automatically an ICS file to sync each day the appointments stored.', 'vikappointments');
				break;

			/**
			 * Time format labels.
			 */

			case 'VAPFORMATHOUR':
				$result = __('hour', 'vikappointments');
				break;

			case 'VAPFORMATHOURS':
				$result = __('hours', 'vikappointments');
				break;

			case 'VAPFORMATDAY':
				$result = __('day', 'vikappointments');
				break;

			case 'VAPFORMATDAYS':
				$result = __('days', 'vikappointments');
				break;

			case 'VAPFORMATWEEK':
				$result = __('Week', 'vikappointments');
				break;

			case 'VAPFORMATWEEKS':
				$result = __('Weeks', 'vikappointments');
				break;

			case 'VAPFORMATCOMMASEP':
				$result = __(',', 'vikappointments');
				break;

			case 'VAPFORMATANDSEP':
				$result = __('&', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.5
			 */

			case 'VAPWAITLISTADDBUTTON':
				$result = __('Add Me in Waiting List', 'vikappointments');
				break;

			case 'VAPWAITLISTSUMMARY':
				$result = __('You are going to be added in the waiting list of %s for %s', 'vikappointments');
				break;

			case 'VAPWAITLISTADDED1':
				$result = __('You have been added in the waiting list of this service!', 'vikappointments');
				break;

			case 'VAPWAITLISTADDED0':
				$result = __('An error occurred! Please, try again.', 'vikappointments');
				break;

			case 'VAPWAITLISTALREADYIN':
				$result = __('You are already in the waiting list of this service!', 'vikappointments');
				break;

			case 'VAPSUBMIT':
				$result = __('Submit', 'vikappointments');
				break;

			case 'VAPCANCEL':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'VAPWAITLISTEMAILSUBJECT':
				$result = __('New Free Slot @ %s', 'vikappointments');
				break;

			case 'VAPWAITLISTMAILCONTENT':
				$result = __('A new free slot is available for {service} @ {checkin_time} on {checkin_day}.<br />Please, visit the link below to purchase the appointment you requested.<br />Notice that there may be other customers interested in your same service. Please, try to book it as fast as you can.', 'vikappointments');
				break;

			case 'VAPDETAILSLINK':
				$result = __('Details Link', 'vikappointments');
				break;

			case 'VAPUNSUBSCRLINK':
				$result = __('Unsubscribe Link', 'vikappointments');
				break;

			case 'VAPUNSUBSCRWAITLISTTEXT':
				$result = __('If you wish to remove your subscription from the waiting list, please fill in the fields below.', 'vikappointments');
				break;

			case 'VAPUNSUBSCRWAITLISTDONE':
				$result = __('Your subscription has been removed correctly!', 'vikappointments');
				break;

			case 'VAPUNSUBSCRWAITLISTFAIL':
				$result = __('You don\'t have a subscription in the waiting list!', 'vikappointments');
				break;

			case 'VAPEMPORDERINGTITLE':
				$result = __('Ordering Filter', 'vikappointments');
				break;

			case 'VAPEMPORDERING1':
				$result = __('Alphabetically a..Z', 'vikappointments');
				break;

			case 'VAPEMPORDERING2':
				$result = __('Alphabetically Z..a', 'vikappointments');
				break;

			case 'VAPEMPORDERING3':
				$result = __('Newest Employees', 'vikappointments');
				break;

			case 'VAPEMPORDERING4':
				$result = __('Oldest Employees', 'vikappointments');
				break;

			case 'VAPEMPORDERING5':
				$result = __('Most Popular', 'vikappointments');
				break;

			case 'VAPEMPORDERING6':
				$result = __('Highest Rating', 'vikappointments');
				break;

			case 'VAPPACKAGESNUMAPP':
				$result = __('x %d appointments', 'vikappointments');
				break;

			case 'VAPPACKAGEORDERNOW':
				$result = __('Order Now', 'vikappointments');
				break;

			case 'VAPPACKAGESCHECKOUT':
				$result = __('Go To Checkout', 'vikappointments');
				break;

			case 'VAPPACKAGESEMPTYCART':
				$result = __('Empty Cart', 'vikappointments');
				break;

			case 'VAPPACKAGESCONTINUESHOP':
				$result = __('Continue Shopping', 'vikappointments');
				break;

			case 'VAPPACKSCONFIRMORDER':
				$result = __('Confirm Order', 'vikappointments');
				break;

			case 'VAPPACKNOTFOUNDERR':
				$result = __('Error, package not found!', 'vikappointments');
				break;

			case 'VAPPACKLOGINREQERR':
				$result = __('Error, you are not logged in! Please, login to complete your order.', 'vikappointments');
				break;

			case 'VAPCARTPACKADDERR':
				$result = __('You cannot add any more packages to your order. You have reached the maximum number of elements in your cart.', 'vikappointments');
				break;

			case 'VAPPACKAGESADMINEMAILSUBJECT':
				$result = __('%s - New Packages Order Received', 'vikappointments');
				break;

			case 'VAPPACKAGESEMAILSUBJECT':
				$result = __('%s - Your Packages Order', 'vikappointments');
				break;

			case 'VAPPACKAGESMAILAPP':
				$result = __('%d app.', 'vikappointments');
				break;

			case 'VAPPACKAGELASTUSED':
				$result = __('%d/%d appointments used <em><small>(%s)</small></em>', 'vikappointments');
				break;

			case 'VAPPACKAVAILABLESERVICES':
				$result = __('Available Services', 'vikappointments');
				break;

			case 'VAPPACKALLSERVICES':
				$result = __('All Services', 'vikappointments');
				break;

			case 'VAPALLORDERSPACKBUTTON':
				$result = __('Purchased Packages', 'vikappointments');
				break;

			case 'VAPTRANSACTIONNAMEPACK':
				$result = __('Order @ %s', 'vikappointments');
				break;

			case 'VAPEMPCOUPONSPAGETITLE':
				$result = __('%s - Coupons', 'vikappointments');
				break;

			case 'VAPNEWEMPCOUPONTITLE':
				$result = __('New Coupon', 'vikappointments');
				break;

			case 'VAPEDITEMPCOUPONTITLE':
				$result = __('Edit Coupon', 'vikappointments');
				break;

			case 'VAPEMPCOUPONREMOVED1':
				$result = __('Coupon removed Successfully!', 'vikappointments');
				break;

			case 'VAPEMPCOUPONSTITLE':
				$result = __('Coupons', 'vikappointments');
				break;

			case 'VAPEMPCUSTOMFTITLE':
				$result = __('Custom Fields', 'vikappointments');
				break;

			case 'VAPEMPCUSTOMFPAGETITLE':
				$result = __('%s - Custom Fields', 'vikappointments');
				break;

			case 'VAPNEWEMPCUSTOMFTITLE':
				$result = __('New Custom Field', 'vikappointments');
				break;

			case 'VAPEDITEMPCUSTOMFTITLE':
				$result = __('Edit Custom Field', 'vikappointments');
				break;

			case 'VAPEMPCUSTOMFREMOVED1':
				$result = __('Custom Field removed Successfully!', 'vikappointments');
				break;

			case 'VAPEDITEMPWDAYTITLE':
				$result = __('Edit Working Day', 'vikappointments');
				break;

			case 'VAPNEWEMPWDAYTITLE':
				$result = __('New Working Day', 'vikappointments');
				break;

			case 'VAPEMPWDREMOVED1':
				$result = __('Working Days removed Successfully!', 'vikappointments');
				break;

			case 'VAPEMPWDAYSLISTTITLE':
				$result = __('Working Days - %s', 'vikappointments');
				break;

			case 'VAPEDITWD7':
				$result = __('Date From', 'vikappointments');
				break;

			case 'VAPEDITWD8':
				$result = __('Date To', 'vikappointments');
				break;

			case 'VAPEMPSETTINGSZIPRESTR':
				$result = __('ZIP Restrictions', 'vikappointments');
				break;

			case 'VAPEMPSETTING18':
				$result = __('Zip Field', 'vikappointments');
				break;

			case 'VAPEMPSETTING19':
				$result = __('- Use Global -', 'vikappointments');
				break;

			case 'VAPCRONJOBNOTIFYSUBJECT':
				$result = __('%s - Cron Job Log Notification', 'vikappointments');
				break;

			case 'VAPCRONJOBNOTIFYCONTENT':
				$result = __('Created On: %s<br />Status: %s<br /><br /><pre>%s</pre>', 'vikappointments');
				break;

			case 'VAPCRONLOGSTATUS1':
				$result = __('Ok', 'vikappointments');
				break;

			case 'VAPCRONLOGSTATUS0':
				$result = __('Error', 'vikappointments');
				break;

			case 'VAPCLONE':
				$result = __('Clone', 'vikappointments');
				break;

			/**
			 * Administrator mail.
			 */

			case 'VAPADMINEMAILSUBJECT':
				$result = __('%s - New Order Received', 'vikappointments');
				break;

			case 'VAPADMINEMAILHEADTITLE':
				$result = __('New Reservation Received', 'vikappointments');
				break;

			case 'VAPCANCELLATIONLINK':
				$result = __('Cancellation Link', 'vikappointments');
				break;

			case 'VAPORDERCANCELEDCONTENT':
				$result = __('The customer has just cancelled this order.<br />Please, login to see the order details:<br/>', 'vikappointments');
				break;

			case 'VAPORDERCANCELEDCONTENTEMP':
				$result = __('The customer has just cancelled this order.', 'vikappointments');
				break;

			case 'VAPUSERDETAILS':
				$result = __('User Details', 'vikappointments');
				break;

			case 'VAPREGFULLNAME':
				$result = __('Name', 'vikappointments');
				break;

			/**
			 * Invoice template.
			 */

			case 'VAPINVNUM':
				$result = __('Invoice Number', 'vikappointments');
				break;

			case 'VAPINVDATE':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPINVITEMDESC':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPINVITEMPRICE':
				$result = __('Price', 'vikappointments');
				break;

			case 'VAPINVCUSTINFO':
				$result = __('Customer Info', 'vikappointments');
				break;

			case 'VAPINVTOTAL':
				$result = __('Net Price', 'vikappointments');
				break;

			case 'VAPINVTAXES':
				$result = __('Taxes', 'vikappointments');
				break;

			case 'VAPINVGRANDTOTAL':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPINVMAILSUBJECT':
				$result = __('Invoice for order #%s', 'vikappointments');
				break;

			/**
			 * Time format labels 2.
			 */

			case 'VAPDFNOW':
				$result = __('Now', 'vikappointments');
				break;

			case 'VAPDFMINSAGO':
				$result = __('%d min ago', 'vikappointments');
				break;

			case 'VAPDFMINSAFT':
				$result = __('in %d min', 'vikappointments');
				break;

			case 'VAPDFHOURAGO':
				$result = __('1 hour ago', 'vikappointments');
				break;

			case 'VAPDFHOURAFT':
				$result = __('in 1 hour', 'vikappointments');
				break;

			case 'VAPDFHOURSAGO':
				$result = __('%d hours ago', 'vikappointments');
				break;

			case 'VAPDFHOURSAFT':
				$result = __('in %d hours', 'vikappointments');
				break;

			case 'VAPDFDAYAGO':
				$result = __('1 day ago', 'vikappointments');
				break;

			case 'VAPDFDAYAFT':
				$result = __('in 1 day', 'vikappointments');
				break;

			case 'VAPDFDAYSAGO':
				$result = __('%d days ago', 'vikappointments');
				break;

			case 'VAPDFDAYSAFT':
				$result = __('in %d days', 'vikappointments');
				break;

			case 'VAPDFWEEKAGO':
				$result = __('1 week ago', 'vikappointments');
				break;

			case 'VAPDFWEEKAFT':
				$result = __('in 1 week', 'vikappointments');
				break;

			case 'VAPDFWEEKSAGO':
				$result = __('%d weeks ago', 'vikappointments');
				break;

			case 'VAPDFWEEKSAFT':
				$result = __('in %d weeks', 'vikappointments');
				break;

			case 'VAPTODAY':
				$result = __('Today', 'vikappointments');
				break;

			case 'VAPTODAYIN':
				$result = __('in %s', 'vikappointments');
				break;

			case 'VAPTOMORROW':
				$result = __('Tomorrow', 'vikappointments');
				break;

			case 'VAPTOMORROWAT':
				$result = __('Tomorrow @ %s', 'vikappointments');
				break;

			/**
			 * Distance format.
			 */

			case 'VAPFORMATDISTMETERS':
				$result = __('%d meters', 'vikappointments');
				break;

			case 'VAPFORMATDISTKILOMETERS':
				$result = __('%d km', 'vikappointments');
				break;

			case 'VAPFORMATDISTKILOMETERSTHO':
				$result = __('%dK km', 'vikappointments');
				break;

			case 'VAPDISTANCEFROMYOU':
				$result = __('%s from your position', 'vikappointments');
				break;

			case 'VAPFORMATDISTLESSMILES':
				$result = __('%1.f miles', 'vikappointments');
				break;

			case 'VAPFORMATDISTMILE':
				$result = __('1 mile', 'vikappointments');
				break;

			case 'VAPFORMATDISTMILES':
				$result = __('%d miles', 'vikappointments');
				break;

			case 'VAPFORMATDISTKMILES':
				$result = __('%dK miles', 'vikappointments');
				break;

			case 'VAPFORMATDISTYARDS':
				$result = __('%d yards', 'vikappointments');
				break;

			case 'VAPFORMATDISTLESSFEET':
				$result = __('%.2f feet', 'vikappointments');
				break;

			case 'VAPFORMATDISTFEET':
				$result = __('%d feet', 'vikappointments');
				break;

			/**
			 * jQuery calendar.
			 */

			case 'VAPJQCALDONE':
				$result = __('Done', 'vikappointments');
				break;

			case 'VAPJQCALPREV':
				$result = __('Prev', 'vikappointments');
				break;

			case 'VAPJQCALNEXT':
				$result = __('Next', 'vikappointments');
				break;

			case 'VAPJQCALTODAY':
				$result = __('Today', 'vikappointments');
				break;

			case 'VAPJQCALWKHEADER':
				$result = __('Wk', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6
			 */

			case 'VAPINVPAYCHARGE':
				$result = __('Payment Charge', 'vikappointments');
				break;

			case 'VAPFINDRESNOLONGERAVAILABLE':
				$result = __('This day is no longer available for bookings.', 'vikappointments');
				break;

			case 'VAPEDITWD9':
				$result = __('Type', 'vikappointments');
				break;

			case 'VAPEDITWD10':
				$result = __('Location', 'vikappointments');
				break;

			case 'VAPWDTYPEOPT1':
				$result = __('Weekly', 'vikappointments');
				break;

			case 'VAPWDTYPEOPT2':
				$result = __('Day of the Year', 'vikappointments');
				break;

			case 'VAPCONFDIALOGMSG':
				$result = __('Do you want to proceed? This action cannot be undone.', 'vikappointments');
				break;

			case 'VAPMULTIPLE':
				$result = __('Multiple', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE1':
				$result = __('Nominative', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE2':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE3':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE4':
				$result = __('State/Province', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE5':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE6':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE7':
				$result = __('ZIP', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE8':
				$result = __('Company Name', 'vikappointments');
				break;

			case 'VAPEMPCUSTFIELDRULE9':
				$result = __('VAT Number', 'vikappointments');
				break;

			case 'VAPEMPSUBSCRORDERTITLE':
				$result = __('Subscription Order - %s', 'vikappointments');
				break;

			case 'VAPEMPSUBSCRLISTTITLE':
				$result = __('Subscriptions - %s', 'vikappointments');
				break;

			case 'VAPSUBSCRIPTION':
				$result = __('Subscription', 'vikappointments');
				break;

			case 'VAPTRIAL':
				$result = __('Trial', 'vikappointments');
				break;

			case 'VAPEMPATTACHSERTIP':
				$result = __('Select here the services you would like to use.', 'vikappointments');
				break;

			case 'VAPEMPATTACHSERCREATED':
				$result = __('Services created correctly.', 'vikappointments');
				break;

			case 'VAPEMPORDERING7':
				$result = __('Price: Low to High', 'vikappointments');
				break;

			case 'VAPEMPORDERING8':
				$result = __('Price: High to Low', 'vikappointments');
				break;

			case 'VAPORDERPAYFULLDEPOSIT':
				$result = __('I would like to pay immediately the full amount.', 'vikappointments');
				break;

			case 'VAPORDERPAYFULLDEPOSITBACK':
				$result = __('I would like to leave a deposit instead of paying the full amount.', 'vikappointments');
				break;

			case 'VAPORDERPRINTACT':
				$result = __('Print Order', 'vikappointments');
				break;

			case 'VAPORDERINVOICEACT':
				$result = __('Download Invoice', 'vikappointments');
				break;

			case 'VAPUSERCREDITUSED':
				$result = __('You have a credit of %s to use. This order will deduct %s from your credit.', 'vikappointments');
				break;

			case 'VAPUSERCREDITFINISHED':
				$result = __('You have a credit of %s to use. The whole credit will be used to pay the order.', 'vikappointments');
				break;

			case 'VAPSUMMARYDISCOUNT':
				$result = __('Discount', 'vikappointments');
				break;

			case 'VAPVIEWDETAILS':
				$result = __('View Details', 'vikappointments');
				break;

			case 'VAPSHOWMORETIMES':
				$result = __('Show More Times', 'vikappointments');
				break;

			case 'VAPCHECKOUTAT':
				$result = __('check-out @ %s', 'vikappointments');
				break;

			case 'VAPCCBRAND':
				$result = __('Credit Card Brand', 'vikappointments');
				break;

			case 'VAPCCNAME':
				$result = __('Cardholder Name', 'vikappointments');
				break;

			case 'VAPEXPIRINGDATEFMT':
				$result = __('MM / YY', 'vikappointments');
				break;

			case 'VAPOFFCCMAILCONTENT':
				$result = __('The credit card details for the reservation ID %s were partially stored in the database. You can see them from the link below.<br /><br />Remaining Card Number: %s<br /><br />Order Details link:<br />%s', 'vikappointments');
				break;

			case 'GDPR_DISCLAIMER':
				$result = __('The specified data won\'t be stored within our system.', 'vikappointments');
				break;

			case 'GDPR_POLICY_AUTH_LINK':
				$result = __('I hereby authorize the use of my personal data (<a href="%s" onclick="%s">see Privacy Policy</a>)', 'vikappointments');
				break;

			case 'GDPR_POLICY_AUTH_NO_LINK':
				$result = __('I hereby authorize the use of my personal data', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.2
			 */

			case 'VAPEDITWD11':
				$result = __('Services', 'vikappointments');
				break;

			case 'VAPEDITWD11_HELP':
				$result = __('Choose the services that will be assigned to the working day that is going to be created. This function is available only while inserting a new record.', 'vikappointments');
				break;

			case 'VAPUSEALLSERVICES':
				$result = __('- All Services -', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.3
			 */

			case 'VAPEMPMANAGECOUPON19':
				$result = __('Publishing Mode', 'vikappointments');
				break;

			case 'VAPEMPMANAGECOUPON19_DESC':
				$result = __('Choose how the publishing dates should be validated.<br /><b>Current Date</b><br />The coupon will be valid only if the current date (while booking an appointment) is between the specified start and end dates.<br /><b>Check-in Date</b><br />The coupon will be valid only if all the check-in dates in the cart are between the specified start and end dates.', 'vikappointments');
				break;

			case 'VAPEMPCOUPONPUBMODEOPT1':
				$result = __('Current Date', 'vikappointments');
				break;

			case 'VAPEMPCOUPONPUBMODEOPT2':
				$result = __('Check-in Date', 'vikappointments');
				break;

			case 'VAPRESTOREPACKSONCANCEL':
				$result = __('The packages that were used for the purchase have been restored.', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.4
			 */

			case 'VAPFORTNIGHT':
				// @TRANSLATORS: Every 2 weeks
				$result = _x('Fortnight', 'Every 2 weeks', 'vikappointments');
				break;
			
			case 'VAP2MONTHS':
				$result = __('2 Months', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.5
			 */

			case 'VAPSYNCUBSCRICS':
				$result = __('Select the calendar provider that you wish to keep up-to-date.', 'vikappointments');
				break;

			case 'VAPSUBSCRIBE':
				$result = __('Subscribe', 'vikappointments');
				break;

			case 'VAPMAILCUSTOMTEXT':
				$result = __('E-mail Custom Text', 'vikappointments');
				break;

			case 'VAPMAILCUSTOMTEXT_HELP':
				$result = __('It is possible to specify here a custom text to be included within the notification e-mail for the customer.', 'vikappointments');
				break;

			case 'VAPCUSTMAILNAME':
				$result = __('Enter a name for the e-mail custom text...', 'vikappointments');
				break;

			case 'VAPFILTERCREATENEW':
				$result = __('- Create New -', 'vikappointments');
				break;

			case 'VAPRESTRICTIONLIMITREACHED':
				// @TRANSLATORS: %d/%s means: [max number of appointments] per [interval] (e.g. "month")
				$result = _x('You already reached the maximum number of appointments per interval: %d/%s', 'd/%s means: [max number of appointments] per [interval] (e.g. "month")', 'vikappointments');
				break;

			case 'VAPRESTRICTIONLIMITGUEST':
				$result = __('Please log in before to see the available time slots', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALDAY':
				$result = __('Day', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALWEEK':
				$result = __('Week', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALWEEK2':
				$result = __('Fortnight', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALMONTH':
				$result = __('Month', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALMONTH2':
				$result = __('2-Months', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALMONTH3':
				$result = __('Quarter', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALMONTH4':
				$result = __('4-Months', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALMONTH6':
				$result = __('Semester', 'vikappointments');
				break;

			case 'VAPMANAGERESTRINTERVALYEAR':
				$result = __('Year', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.6
			 */

			case 'VAPORDEREXCLUDECUSTMAIL':
				$result = __('Exclude other custom texts', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7
			 */

			case 'VAPCARTITEMNOTAVERR':
				$result = __('The item %s has been removed from your cart. %s', 'vikappointments');
				break;

			case 'VAP_N_APP_EXT':
				$result = __('%d appointments', 'vikappointments');
				break;

			case 'VAP_EMP_ANYONE_OPT':
				$result = __('- Anyone -', 'vikappointments');
				break;

			case 'VAP_USER_CHANGE_TIMEZONE':
				$result = __('Change timezone from the following list if you are booking from a different geographical area', 'vikappointments');
				break;

			case 'VAPALLORDERSSUBSCRBUTTON':
				$result = __('Purchased Subscriptions', 'vikappointments');
				break;
			
			case 'VAPTRANSACTIONNAMESUBSCR':
			    // @TRANSLATORS: [SUBSCRIPTION NAME] @ [COMPANY NAME]
				$result = _x('%s @ %s', '[SUBSCRIPTION NAME] @ [COMPANY NAME]', 'vikappointments');
				break;

			case 'VAPORDERCOUNTDOWN':
				// @TRANSLATORS: %s will be replaced by a formatted countdown
				$result = _x('You still have %s to confirm your appointment.', '%s will be replaced by a formatted countdown', 'vikappointments');
				break;

			case 'VAPPACKAGELASTUSED':
				// @TRANSLATORS: the last modify date is displayed between the brackets
				$result = _x('%d/%d appointments used <em><small>(%s)</small></em>.', 'the last modify date is displayed between the brackets', 'vikappointments');
				break;

			case 'VAPPACKAGEREQERR':
				$result = __('It seems that you don\'t have enough available packages to redeem. You need to purchase a package before to book your appointment. Click <a href="%s">HERE</a> to start the purchase.', 'vikappointments');
					break;

			case 'VAPPACKAGEREQERRGUEST':
				$result = __('Only logged-in users with available purchased packages can book appointments.', 'vikappointments');
				break;

			case 'VAPSUBSCRREQERR':
				$result = __('It seems that you don\'t have an active subscription plan. You need to start a subscription before to book your appointment. Click <a href="%s">HERE</a> to start the purchase.', 'vikappointments');
				break;

			case 'VAPSUBSCRREQERRGUEST':
				$result = __('Only logged-in users with an active subscription plan can book appointments.', 'vikappointments');
				break;

			case 'VAPMAILERR':
				$result = __('An error occurred while trying to send the e-mail. Should the problem persist, try to contact the site administrator.', 'vikappointments');
				break;

			case 'VAPINVPAYTAX':
				$result = __('Payment Tax', 'vikappointments');
				break;

			case 'VAPINVQTY':
				$result = __('Qty', 'vikappointments');
				break;

			case 'VAPAUTHORDATE':
				$result = __('Created by %s on %s', 'vikappointments');
				break;

			case 'VAPWAITLISTSUMMARYCHANGEDATE':
				$result = __('Click here to change date.', 'vikappointments');
				break;

			case 'VAPINVMAILSUBJECT':
				$result = __('%s - Invoice for order #%s', 'vikappointments');
				break;

			case 'VAPINVMAILCONTENT':
				$result = __('This mail was generated automatically from %s.\nYou can find the invoice of your order %s as attachment.\n\nPlease, do not reply to this message.', 'vikappointments');
				break;

			case 'VAP_N_PEOPLE':
				$result = __('%d people', 'vikappointments');
				break;

			case 'VAP_N_PEOPLE_1':
				$result = __('1 person', 'vikappointments');
				break;

			case 'VAP_N_ATTENDEE':
				$result = __('Attendee #%d', 'vikappointments');
				break;

			case 'VAP_DEF_N_SELECTED':
				$result = __('%d selected', 'vikappointments');
				break;

			case 'VAP_DEF_N_SELECTED_1':
				$result = __('1 selected', 'vikappointments');
				break;

			case 'VAP_DEF_N_SELECTED_0':
				$result = __('No selection', 'vikappointments');
				break;

			case 'VAP_AT_DATE_SEPARATOR':
				$result = __('@', 'vikappointments');
				break;

			case 'VAPOFFCCWAITAPPROVE':
				$result = __('Please wait for a manual approval of your order.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_PAID_MSG':
				$result = __('Payment done! The validation may take a few minutes. Please, try to refresh the page.', 'vikappointments');
				break;

			case 'VAP_EMPAREA_SERVICE_CREATE':
				$result = __('Create New', 'vikappointments');
				break;

			case 'VAP_EMPAREA_SERVICE_ASSIGN':
				$result = __('Assign Existing', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD':
				$result = __('Reminder', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.1
			 */

			case 'VAP_ORDER_APPROVE_HELP':
				$result = __('Please confirm the appointment by clicking on the apposite link received at the email address specified during the booking process.', 'vikappointments');
				break;

			case 'VAPEMPACCOUNTSTATUSPAGETITLE':
				$result = __('%s - Account Status', 'vikappointments');
				break;

			case 'VAPEMPACCOUNTSTATUSPIETOTALLEGEND':
				$result = __('Total revenue per service', 'vikappointments');
				break;

			case 'VAPEMPACCOUNTSTATUSPIECOUNTLEGEND':
				$result = __('Total appointments per service', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.2
			 */

			case 'VAP_GET_DIRECTIONS_BTN':
				$result = __('Get Directions', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.3
			 */

			case 'VAPFINDRESNODAYSERVICE':
				$result = __('This day the service is no longer available', 'vikappointments');
				break;

			case 'VAPCARTITEMADDERR3':
				$result = __('The selected appointment is no longer available.', 'vikappointments');
				break;

			case 'VAP_ARIA_FILE_PREVIEW':
				$result = __('See file preview', 'vikappointments');
				break;

			case 'VAP_ARIA_FILE_UPLOAD':
				$result = __('Upload a file', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.4
			 */

			case 'VAP_N_DAYS':
				$result = __('%d days', 'vikappointments');
				break;

			case 'VAP_N_DAYS_1':
				$result = __('1 day', 'vikappointments');
				break;

			case 'VAP_N_WEEKS':
				$result = __('%d weeks', 'vikappointments');
				break;

			case 'VAP_N_WEEKS_1':
				$result = __('1 week', 'vikappointments');
				break;

			case 'VAP_N_MONTHS':
				$result = __('%d months', 'vikappointments');
				break;

			case 'VAP_N_MONTHS_1':
				$result = __('1 month', 'vikappointments');
				break;

			case 'VAP_N_YEARS':
				$result = __('%d years', 'vikappointments');
				break;

			case 'VAP_N_YEARS_1':
				$result = __('1 year', 'vikappointments');
				break;

			case 'VAPACTIVATEPROFILEMSGALT':
				$result = __('An administrator will approve your request as soon as possible.', 'vikappointments');
				break;

			case 'VAPSUBSCRIPTIONEXTENDED_SITE':
				$result = __('Your subscription has been extended to %s.', 'vikappointments');
				break;

			case 'VAPPACKAGEREDEEMED':
				$result = __('%d/%d redeemed packages', 'vikappointments');
				break;

			case 'VAP_PACKAGE_VALIDTHRU_EXPIRED':
				$result = __('You are no longer able to redeem this package since it expired on %s.', 'vikappointments');
				break;

			case 'VAP_PACKAGE_VALIDTHRU_ACTIVE':
				$result = __('You will be able to redeem this package until <strong>%s</strong>. After this date, you won\'t be able to use it any longer.', 'vikappointments');
				break;

			case 'VAP_PACKAGE_VALIDTHRU_DISCLAIMER':
				$result = __('You will be able to redeem this package within %d days since the purchase date.', 'vikappointments');
				break;

			case 'VAP_FOR_DATE_SEPARATOR':
				// @TRANSLATORS: indicates the appointment check-in, used in a context similar to this one: [SERVICE_NAME] - [EMPLOYEE_NAME] for [WEEKDAY], [DAY] [MONTH] [YEAR] [TIME]
				$result = _x('for', 'indicates the appointment check-in, used in a context similar to this one: [SERVICE_NAME] - [EMPLOYEE_NAME] for [WEEKDAY], [DAY] [MONTH] [YEAR] [TIME]', 'vikappointments');
				break;

			case 'VAPCRONJOBPAUSENOTIF':
				$result = __('How long do you want to pause the cron notifications?', 'vikappointments');
				break;

			case 'VAP_CRON_NOTIF_PAUSE_SUCCESS':
				$result = __('Cron notifications paused successfully until %s.', 'vikappointments');
				break;

			case 'VAP_CRON_NOTIF_PAUSE_ERROR':
				$result = __('Unable to pause the notifications for the following reason. %s', 'vikappointments');
				break;

			case 'VAPCRONJOBNOTIFYFOOTER':
				$result = __('<strong>Are you receiving too many error notifications from this cron job?</strong><br />Click the link below to temporarily pause them.', 'vikappointments');
				break;
		}

		return $result;
	}
}

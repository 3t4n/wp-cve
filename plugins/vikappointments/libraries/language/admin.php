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
 * Switcher class to translate the VikAppointments plugin admin languages.
 *
 * @since 	1.0
 */
class VikAppointmentsLanguageAdmin implements JLanguageHandler
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
			case 'VAPMENUTITLEHEADER1':
				$result = __('Management', 'vikappointments');
				break;

			case 'VAPMENUTITLEHEADER2':
				$result = __('Appointments', 'vikappointments');
				break;

			case 'VAPMENUTITLEHEADER3':
				$result = __('Global', 'vikappointments');
				break;

			case 'VAPMENUDASHBOARD':
				$result = __('Dashboard', 'vikappointments');
				break;

			case 'VAPMENUCALENDAR':
				$result = __('Calendar', 'vikappointments');
				break;

			case 'VAPMENURESERVATIONS':
				$result = __('Reservations', 'vikappointments');
				break;

			case 'VAPMENUGROUPS':
				$result = __('Groups', 'vikappointments');
				break;

			case 'VAPMENUSERVICES':
				$result = __('Services', 'vikappointments');
				break;

			case 'VAPMENUEMPLOYEES':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPMENUOPTIONS':
				$result = __('Options', 'vikappointments');
				break;

			case 'VAPMENUMEDIA':
				$result = __('Media Manager', 'vikappointments');
				break;

			case 'VAPMENUCOUPONS':
				$result = __('Coupons', 'vikappointments');
				break;

			case 'VAPMENUPAYMENTS':
				$result = __('Payments', 'vikappointments');
				break;

			case 'VAPMENUCUSTOMF':
				$result = __('Custom Fields', 'vikappointments');
				break;

			case 'VAPMENUCONFIG':
				$result = __('Configuration', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWDASHBOARD':
				$result = __('VikAppointments - Dashboard', 'vikappointments');
				break;

			case 'VAPDASHLATESTRESERVATIONS':
				$result = __('Latest', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCALENDAR':
				$result = __('VikAppointments - Calendar Overview', 'vikappointments');
				break;

			case 'VAPCALSTATTHEAD3':
				$result = __('Appoint.', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWGROUP':
				$result = __('VikAppointments - New Service Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITGROUP':
				$result = __('VikAppointments - Edit Service Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWGROUPS':
				$result = __('VikAppointments - Services Groups', 'vikappointments');
				break;

			case 'VAPMANAGEGROUP1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEGROUP2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEGROUP3':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMAINTITLEFINDRESERVATION':
				$result = __('VikAppointments - Find Reservation', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWRESERVATION':
				$result = __('VikAppointments - New Reservation', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITRESERVATION':
				$result = __('VikAppointments - Edit Reservation', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWRESERVATIONS':
				$result = __('VikAppointments - Reservations', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATIONTITLE1':
				$result = __('Reservation Details', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATIONTITLE2':
				$result = __('Custom Fields', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATIONTITLE3':
				$result = __('Additional Options', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATIONTITLE4':
				$result = __('Notes', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION0':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION1':
				$result = __('Order Number', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION2':
				$result = __('Order Key', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION3':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION4':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION5':
				$result = __('Begin', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION6':
				$result = __('End', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION7':
				$result = __('Change', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION8':
				$result = __('Purchaser E-Mail', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION9':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION10':
				$result = __('Duration', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION11':
				$result = __('Already Paid', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION12':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION13':
				$result = __('Payment', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION14':
				$result = __('Options', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION15':
				$result = __('Option Price', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION16':
				$result = __('Option Time', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION17':
				$result = __('Option Quantity', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION18':
				$result = __('Add Option', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION19':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION20':
				$result = __('Info.', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION21':
				$result = __('Coupon', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION22':
				$result = __('Edit', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION23':
				$result = __('Remove', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION24':
				$result = __('Notify Customer', 'vikappointments');
				break;

			case 'VAPRESDATETIMENOTAVERR':
				$result = __('The employee at the selected date and time is already occupied.', 'vikappointments');
				break;

			case 'VAPRESERVATIONHASNOOPTION':
				$result = __('This service has no option associated.', 'vikappointments');
				break;

			case 'VAPFINDRESTIMENOAV':
				$result = __('At this date & time there is already a reservation', 'vikappointments');
				break;

			case 'VAPFINDRESBOOKNOW':
				$result = __('Book Now', 'vikappointments');
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

			case 'VAPMAINTITLENEWSERVICE':
				$result = __('VikAppointments - New Service', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSERVICE':
				$result = __('VikAppointments - Edit Service', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSERVICES':
				$result = __('VikAppointments - Services', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE3':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE4':
				$result = __('Duration', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE5':
				$result = __('Price', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE6':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE7':
				$result = __('Upload Image', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE8':
				$result = __('Choose Image', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE9':
				$result = __('Image', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE10':
				$result = __('Group', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE11':
				$result = __('Options', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE12':
				$result = __('Add Option', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE13':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE14':
				$result = __('Add Employee', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE15':
				$result = __('Info.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE17':
				$result = __('Quick Contact', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE18':
				$result = __('Employee Choosable', 'vikappointments');
				break;

			case 'VAPSERVICENOGROUP':
				$result = __('- No Group -', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWEMPLOYEE':
				$result = __('VikAppointments - New Employee', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITEMPLOYEE':
				$result = __('VikAppointments - Edit Employee', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWEMPLOYEES':
				$result = __('VikAppointments - Employees', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE2':
				$result = __('First Name', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE3':
				$result = __('Last Name', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE4':
				$result = __('Nominative', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE5':
				$result = __('Upload Image', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE6':
				$result = __('Choose Image', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE7':
				$result = __('Image', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE8':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE9':
				$result = __('Bookings', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE10':
				$result = __('Phone', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE11':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE12':
				$result = __('Working Days', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE13':
				$result = __('Add Working Time', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE14':
				$result = __('From', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE15':
				$result = __('To', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE16':
				$result = __('Show Phone', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE17':
				$result = __('Quick Contact', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWOPTION':
				$result = __('VikAppointments - New Option', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITOPTION':
				$result = __('VikAppointments - Edit Option', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWOPTIONS':
				$result = __('VikAppointments - Options', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION3':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION4':
				$result = __('Price', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION5':
				$result = __('Selectable Quantity', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION6':
				$result = __('Max Quantity', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION7':
				$result = __('Upload Image', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION8':
				$result = __('Choose Image', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION9':
				$result = __('Image', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWMEDIA':
				$result = __('VikAppointments - New Media', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWMEDIA':
				$result = __('VikAppointments - Media', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA3':
				$result = __('Image', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA4':
				$result = __('Edit', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA5':
				$result = __('Remove', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA6':
				$result = __('Original Size', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA7':
				$result = __('Thumbnail Size', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA8':
				$result = __('Resize', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA9':
				$result = __('Width', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA10':
				$result = __('Height', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCOUPON':
				$result = __('VikAppointments - New Coupon', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCOUPON':
				$result = __('VikAppointments - Edit Coupon', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCOUPONS':
				$result = __('VikAppointments - Coupons', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON2':
				$result = __('Code', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON3':
				$result = __('Type', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON4':
				$result = __('Percent or Total', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON5':
				$result = __('Value', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON6':
				$result = __('Minimum Cost', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON7':
				$result = __('Date Start', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON8':
				$result = __('Date End', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON9':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPCOUPONTYPEOPTION1':
				$result = __('Permanent', 'vikappointments');
				break;

			case 'VAPCOUPONTYPEOPTION2':
				$result = __('Gift', 'vikappointments');
				break;

			case 'VAPCOUPONPERCENTOTOPTION1':
				$result = __('%', 'vikappointments');
				break;

			case 'VAPCOUPONVALID0':
				$result = __('Expired', 'vikappointments');
				break;

			case 'VAPCOUPONVALID1':
				$result = __('Valid', 'vikappointments');
				break;

			case 'VAPCOUPONVALID2':
				$result = __('Not Active', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWPAYMENT':
				$result = __('VikAppointments - New Payment', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITPAYMENT':
				$result = __('VikAppointments - Edit Payment', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWPAYMENTS':
				$result = __('VikAppointments - Payments', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT1':
				$result = __('Payment Name', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT2':
				$result = __('File Class', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT3':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT4':
				$result = __('Cost', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT5':
				$result = __('Auto-Set Order Confirmed', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT6':
				$result = __('Always Show Note', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT7':
				$result = __('Notes', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT8':
				$result = __('Parameters', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT9':
				$result = __('No Parameters Available', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCUSTOMF':
				$result = __('VikAppointments - New Custom Field', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCUSTOMF':
				$result = __('VikAppointments - Edit Custom Field', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCUSTOMFS':
				$result = __('VikAppointments - Custom Fields', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF1':
				$result = __('Field Name', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF2':
				$result = __('Type', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF3':
				$result = __('Required', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF5':
				$result = __('Popup Link', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF6':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION1':
				$result = __('Text', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION2':
				$result = __('TextArea', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION3':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION4':
				$result = __('Select', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION5':
				$result = __('Checkbox', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION6':
				$result = __('Separator', 'vikappointments');
				break;

			case 'VAPCUSTOMFSELECTADDANSWER':
				$result = __('ADD ANSWER', 'vikappointments');
				break;

			case 'VAPMAINTITLEEXPORTRES':
				$result = __('VikAppointments - Exporting Reservations', 'vikappointments');
				break;

			case 'VAPEXPORTRES1':
				$result = __('Filename', 'vikappointments');
				break;

			case 'VAPEXPORTRES2':
				$result = __('Export Type', 'vikappointments');
				break;

			case 'VAPEXPORTRES3':
				$result = __('Start Date', 'vikappointments');
				break;

			case 'VAPEXPORTRES4':
				$result = __('End Date', 'vikappointments');
				break;

			case 'VAPEXPORTRES5':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPEXPORTRES6':
				$result = __('- All -', 'vikappointments');
				break;

			case 'VAPEXPORTFILENOTFOUNDERR':
				$result = __('Impossible to export reservations! File [%s] not found...', 'vikappointments');
				break;

			case 'VAPEXPORTNOFILESERR':
				$result = __('You have none exporting file.', 'vikappointments');
				break;

			case 'VAPMAINTITLECONFIG':
				$result = __('VikAppointments - Settings', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG0':
				$result = __('Company Name', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG1':
				$result = __('Admin e-Mail', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG4':
				$result = __('Company Logo', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG5':
				$result = __('Date Format', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG6':
				$result = __('Time Format', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG7':
				$result = __('Currency Symbol', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG8':
				$result = __('Currency Name', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG10':
				$result = __('Minutes Intervals', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG11':
				$result = __('Closing Days', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG12':
				$result = __('Add Day', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG13':
				$result = __('Load jQuery', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG14':
				$result = __('Display Footer', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG15':
				$result = __('Opening Time', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG16':
				$result = __('Closing Time', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG19':
				$result = __('Visible Months', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG20':
				$result = __('Keep Appointments Locked For', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG21':
				$result = __('Default View', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG22':
				$result = __('Booking Minutes Restriction', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG23':
				$result = __('earlier', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG24':
				$result = __('Sender e-Mail', 'vikappointments');
				break;

			case 'VAPCONFIGTIMEFORMAT1':
				$result = __('12 Hours AM/PM', 'vikappointments');
				break;

			case 'VAPCONFIGTIMEFORMAT2':
				$result = __('24 Hours', 'vikappointments');
				break;

			case 'VAPCONFIGLISTINGTYPE1':
				$result = __('Ascending', 'vikappointments');
				break;

			case 'VAPCONFIGLISTINGTYPE2':
				$result = __('Descending', 'vikappointments');
				break;

			case 'VAPFREQUENCYTYPE0':
				$result = __('Single Day', 'vikappointments');
				break;

			case 'VAPFREQUENCYTYPE1':
				$result = __('Weekly', 'vikappointments');
				break;

			case 'VAPFREQUENCYTYPE2':
				$result = __('Monthly', 'vikappointments');
				break;

			case 'VAPFREQUENCYTYPE3':
				$result = __('Yearly', 'vikappointments');
				break;

			case 'VAPFIRSTIMAGENULL':
				$result = __('- No Image -', 'vikappointments');
				break;

			case 'VAPSAYRESERVATIONDETAILS':
				$result = __('%s with %s: %s', 'vikappointments');
				break;

			case 'VAPRESNOEXTRAOPTIONS':
				$result = __('No additional option.', 'vikappointments');
				break;

			case 'VAPNEW':
				$result = __('New', 'vikappointments');
				break;

			case 'VAPCANCEL':
				$result = __('Cancel', 'vikappointments');
				break;

			case 'VAPEDIT':
				$result = __('Edit', 'vikappointments');
				break;

			case 'VAPSAVE':
				$result = __('Save', 'vikappointments');
				break;

			case 'VAPDELETE':
				$result = __('Delete', 'vikappointments');
				break;

			case 'VAPEXPORT':
				$result = __('Export', 'vikappointments');
				break;

			case 'VAPDOWNLOAD':
				$result = __('Download', 'vikappointments');
				break;

			case 'VAPSHORTCUTMINUTE':
				$result = __('min.', 'vikappointments');
				break;

			case 'VAPSTATUSCONFIRMED':
				$result = __('Confirmed', 'vikappointments');
				break;

			case 'VAPSTATUSPENDING':
				$result = __('Pending', 'vikappointments');
				break;

			case 'VAPSTATUSREMOVED':
				$result = __('Removed', 'vikappointments');
				break;

			case 'VAPIMAGESTATUS0':
				$result = __('Image Not Exists', 'vikappointments');
				break;

			case 'VAPIMAGESTATUS1':
				$result = __('Image Ok', 'vikappointments');
				break;

			case 'VAPIMAGESTATUS2':
				$result = __('Image Not Added', 'vikappointments');
				break;

			case 'VAPFOOTER':
				$result = __('VikAppointments v.%s - Powered by', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_CONFIGURATION':
				$result = __('VikAppointments Configuration', 'vikappointments');
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

			case 'VAPRESERVATIONBUTTONFILTER':
				$result = __('Search', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION25':
				$result = __('People', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION26':
				$result = __('Check-in', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION27':
				$result = __('Purchaser Phone', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE18':
				$result = __('Listable', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE19':
				$result = __('User ID', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE20':
				$result = __('None', 'vikappointments');
				break;

			case 'VAPDAYCUSTOM':
				$result = __('- Custom Day -', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE19':
				$result = __('Sleep Time', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE20':
				$result = __('Time Slots Length', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE21':
				$result = __('Maximum Capacity', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE22':
				$result = __('Min People per App', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE23':
				$result = __('Max People per App', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE24':
				$result = __('Enable ZIP Restriction', 'vikappointments');
				break;

			case 'VAPSERVICETIMESLOTSLEN1':
				$result = __('Duration (%d min.)', 'vikappointments');
				break;

			case 'VAPSERVICETIMESLOTSLEN2':
				$result = __('%d min.', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME1':
				$result = __('Global', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME2':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME3':
				$result = __('Closing Days', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME4':
				$result = __('SMS APIs', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE1':
				$result = __('System', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE2':
				$result = __('ZIP Restrictions', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE3':
				$result = __('Recurring Appointments', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG25':
				$result = __('Symb Position', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG26':
				$result = __('Selectable Months', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG27':
				$result = __('Default Status', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG28':
				$result = __('Closing Periods', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG29':
				$result = __('Add Period', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG30':
				$result = __('Enable Order Cancellation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG31':
				$result = __('Accept Cancellation Before', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG32':
				$result = __('ZIP Code Field', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG33':
				$result = __('Assign restrictions to', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG34':
				$result = __('Accepted Codes', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG35':
				$result = __('Add ZIP Code', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG36':
				$result = __('Try Validation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG37':
				$result = __('Test', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG38':
				$result = __('- None -', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG39':
				$result = __('Load from File', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG40':
				$result = __('Upload File', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG41':
				$result = __('Specify only one ZIP code per line. It doesn\'t matter to have the ZIP codes in ascending order.', 'vikappointments');
				break;

			case 'VAPCONFIGSYMBPOSITION1':
				$result = __('Before Price', 'vikappointments');
				break;

			case 'VAPCONFIGSYMBPOSITION2':
				$result = __('After Price', 'vikappointments');
				break;

			case 'VAPCONFIGEMPTITLE1':
				$result = __('Registration', 'vikappointments');
				break;

			case 'VAPCONFIGEMPTITLE2':
				$result = __('Authorise', 'vikappointments');
				break;

			case 'VAPCONFIGEMPTITLE3':
				$result = __('Reservations', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP1':
				$result = __('Employee Sign-Up', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP2':
				$result = __('Default Status', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP3':
				$result = __('Default User Group', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP4':
				$result = __('Create Services', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP5':
				$result = __('Manage Profile', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP6':
				$result = __('Remove Services', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP7':
				$result = __('Max Number of Services', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP8':
				$result = __('Create', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP9':
				$result = __('Manage', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP10':
				$result = __('Remove', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP11':
				$result = __('Notify Admin', 'vikappointments');
				break;

			case 'VAPCONFIGEMPSIGNUPSTATUS1':
				$result = __('Pending', 'vikappointments');
				break;

			case 'VAPCONFIGEMPSIGNUPSTATUS2':
				$result = __('Approved', 'vikappointments');
				break;

			case 'VAPCONFIGZIPUPLOADEDMSG':
				$result = __('ZIP Codes Uploaded: %d', 'vikappointments');
				break;

			case 'VAPCONFIGUPLOADFILEERR':
				$result = __('Impossible to upload the file!', 'vikappointments');
				break;

			case 'VAPDAYSLABEL':
				$result = __('days', 'vikappointments');
				break;

			case 'VAPSTATUSCANCELED':
				$result = __('Cancelled', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS1':
				$result = __('SMS API file', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS2':
				$result = __('Enable Auto-Send', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS3':
				$result = __('Send to Customers', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS4':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS5':
				$result = __('Parameters', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS6':
				$result = __('- None Available -', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS7':
				$result = __('User Credit', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS8':
				$result = __('Estimate Credit', 'vikappointments');
				break;

			case 'VAPCONFIGSMSAPITO0':
				$result = __('Customer', 'vikappointments');
				break;

			case 'VAPCONFIGSMSAPITO1':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPCONFIGSMSAPITO2':
				$result = __('Administrator', 'vikappointments');
				break;

			case 'VAPSMSESTIMATEERR1':
				$result = __('SMS APIs file does not exists!', 'vikappointments');
				break;

			case 'VAPSMSESTIMATEERR2':
				$result = __('SMS APIs file can estimate the user credit!', 'vikappointments');
				break;

			case 'VAPSMSESTIMATEERR3':
				$result = __('An error occurred! Impossible to estimate the user credit.', 'vikappointments');
				break;

			case 'VAPSMSMESSAGESENT0':
				$result = __('Impossible to send messages!', 'vikappointments');
				break;

			case 'VAPSMSMESSAGESENT1':
				$result = __('Messages sent successfully!', 'vikappointments');
				break;

			case 'VAPPRINT':
				$result = __('Print', 'vikappointments');
				break;

			case 'VAPSENDSMS':
				$result = __('Send SMS', 'vikappointments');
				break;

			case 'VAPSERWORKDAYSTITLE':
				$result = __('VikAppointments - %s Working Days', 'vikappointments');
				break;

			case 'VAPSERWDLINKTO':
				$result =  __('Set the complete table of Working Days for this employee from here', 'vikappointments');
				break;

			case 'VAPNOSERWORKDAYSERR':
				$result = __('No employee has been assigned to this service! Just associate at least an employee to this service first.', 'vikappointments');
				break;

			case 'VAPSERWORKDAYSRESTORED1':
				$result = __('Working Days restored successfully!', 'vikappointments');
				break;

			case 'VAPSERWORKDAYSRESTORED0':
				$result = __('There is nothing to restore.', 'vikappointments');
				break;

			case 'VAPMANAGEWD1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEWD2':
				$result = __('Day', 'vikappointments');
				break;

			case 'VAPMANAGEWD3':
				$result = __('From', 'vikappointments');
				break;

			case 'VAPMANAGEWD4':
				$result = __('To', 'vikappointments');
				break;

			case 'VAPMANAGEWD5':
				$result = __('Open', 'vikappointments');
				break;

			case 'VAPMANAGEWD6':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWEMPRATES':
				$result = __('VikAppointments - %s Services', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE25':
				$result = __('Working Days', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE26':
				$result = __('Price per Person', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE27':
				$result = __('Enable Recurrence', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE21':
				$result = __('Payments', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE22':
				$result = __('Closed', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE23':
				$result = __('Overrides', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION7':
				$result = __('File', 'vikappointments');
				break;

			case 'VAPCUSTOMFFILEFILTER':
				$result = __('Allowed Files', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION28':
				$result = __('Payment Gateway', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION29':
				$result = __('Multiple Orders', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION30':
				$result = __('Single Order', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION31':
				$result = __('Re-Notify Customer', 'vikappointments');
				break;

			case 'VAPMEDIAPROPBOXTITLE':
				$result = __('Media Properties', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT10':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG42':
				$result = __('Login Requirements', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG43':
				$result = __('Optional', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG44':
				$result = __('Required on Confirmation Page', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG45':
				$result = __('Enable Cart', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG46':
				$result = __('Max. Appointments', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG47':
				$result = __('Unlimited', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG48':
				$result = __('Dashboard Refresh Time', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG49':
				$result = __('Mail Attachment', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG50':
				$result = __('Use Deposit After', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG52':
				$result = __('Deposit Amount', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG53':
				$result = __('Shop Button Filter', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSHOPOPT1':
				$result = __('- Hide Button -', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSHOPOPT2':
				$result = __('- All Groups -', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC1':
				$result = __('Enable Recurrence', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC2':
				$result = __('Repeat Every', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC3':
				$result = __('Min. Amount', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC4':
				$result = __('Max. Amount', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC5':
				$result = __('For The Next', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC6':
				$result = __('This is how the recurrence form will be displayed in the front-end', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECSINGOPT1':
				$result = __('Day', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECSINGOPT2':
				$result = __('Week', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECSINGOPT3':
				$result = __('Month', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECMULTOPT1':
				$result = __('Days', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECMULTOPT2':
				$result = __('Weeks', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECMULTOPT3':
				$result = __('Months', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP12':
				$result = __('Manage Services', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP13':
				$result = __('Manage Payments', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP14':
				$result = __('Manage Services Overrides', 'vikappointments');
				break;

			case 'VAPSHORTCUTSEC':
				$result = __('sec.', 'vikappointments');
				break;

			case 'VAPNOTIFYCUSTOK':
				$result = __('Customer notified successfully!', 'vikappointments');
				break;

			case 'VAPNOTIFYCUSTERR':
				$result = __('Impossible to notify the customer!', 'vikappointments');
				break;

			case 'VAPORDERTITLE2':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPSAVEANDCLOSE':
				$result = __('Save & Close', 'vikappointments');
				break;

			case 'VAPSAVEANDNEW':
				$result = __('Save & New', 'vikappointments');
				break;

			case 'VAPRESTORE':
				$result = __('Restore', 'vikappointments');
				break;

			case 'VAPMENUTITLEHEADER4':
				$result = __('Portal', 'vikappointments');
				break;

			case 'VAPMENUCUSTOMERS':
				$result = __('Customers', 'vikappointments');
				break;

			case 'VAPMANAGEGROUP4':
				$result = __('Services', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWEMPGROUP':
				$result = __('VikAppointments - New Employee Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITEMPGROUP':
				$result = __('VikAppointments - Edit Employee Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWEMPGROUPS':
				$result = __('VikAppointments - Employees Groups', 'vikappointments');
				break;

			case 'VAPGROUPSWITCHPAGE1':
				$result = __('View Employees Groups', 'vikappointments');
				break;

			case 'VAPGROUPSWITCHPAGE2':
				$result = __('View Services Groups', 'vikappointments');
				break;

			case 'VAPFINDRESREVSEARCH':
				$result = __('Reverse Search', 'vikappointments');
				break;

			case 'VAPFINDRESALLEMPLOYEES':
				$result = __('- All Employees -', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCUSTOMER':
				$result = __('VikAppointments - New Customer', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCUSTOMER':
				$result = __('VikAppointments - Edit Customer', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCUSTOMERS':
				$result = __('VikAppointments - Customers', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE1':
				$result = __('User Account', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE2':
				$result = __('Billing', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE3':
				$result = __('Custom Fields', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE4':
				$result = __('Notes', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER3':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER4':
				$result = __('Phone', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER5':
				$result = __('Country', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER6':
				$result = __('State / Province', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER7':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER8':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER9':
				$result = __('ZIP', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER10':
				$result = __('Company Name', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER11':
				$result = __('VAT Number', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER12':
				$result = __('User Account', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER13':
				$result = __('Password', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER14':
				$result = __('Confirm Password', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER15':
				$result = __('Guest', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER16':
				$result = __('Create new Account', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER17':
				$result = __('Generate Password', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER18':
				$result = __('Appointments', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER19':
				$result = __('Address 2', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER20':
				$result = __('SSN / Fiscal Code', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERERR3':
				$result = __('Missing Fields! Please fill in all the required fields.', 'vikappointments');
				break;

			case 'VAPREPORTSEMPTITLE':
				$result = __('VikAppointments - Employees Reports', 'vikappointments');
				break;

			case 'VAPMENUREVIEWS':
				$result = __('Reviews', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWREVIEW':
				$result = __('VikAppointments - New Review', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITREVIEW':
				$result = __('VikAppointments - Edit Review', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWREVIEWS':
				$result = __('VikAppointments - Reviews', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW2':
				$result = __('Title', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW3':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW4':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW5':
				$result = __('Rating', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW6':
				$result = __('Employee/Service', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW7':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW8':
				$result = __('Language', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW9':
				$result = __('Comment', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW10':
				$result = __('User', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW11':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPREVIEWSFILTEROPT1':
				$result = __('Only Employees', 'vikappointments');
				break;

			case 'VAPREVIEWSFILTEROPT2':
				$result = __('Only Services', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSUBSCRORDER':
				$result = __('VikAppointments - New Subscription Order', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSUBSCRORDER':
				$result = __('VikAppointments - Edit Subscription Order', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSUBSCRORDERS':
				$result = __('VikAppointments - Subscription Orders', 'vikappointments');
				break;

			case 'VAPSUBSCRIPTIONEXTENDED':
				$result = __('%s subscription extended to: %s', 'vikappointments');
				break;

			case 'VAPEMOSUBSCROVERVIEW':
				$result = __('Employees Subscriptions Overview', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD1':
				$result = __('Ord Num', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD2':
				$result = __('Ord Key', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD3':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD4':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD5':
				$result = __('Subscription', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD6':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD7':
				$result = __('Total Paid', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD8':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCRORD9':
				$result = __('Payment', 'vikappointments');
				break;

			case 'VAPMENUSUBSCRIPTIONS':
				$result = __('Subscriptions', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSUBSCRIPTION':
				$result = __('VikAppointments - New Subscription', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSUBSCRIPTION':
				$result = __('VikAppointments - Edit Subscription', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSUBSCRIPTIONS':
				$result = __('VikAppointments - Subscriptions', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR2':
				$result = __('Amount', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR3':
				$result = __('Type', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR4':
				$result = __('Price', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR5':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR6':
				$result = __('Trial', 'vikappointments');
				break;

			case 'VAPSUBSCRTYPE1':
				$result = __('Days', 'vikappointments');
				break;

			case 'VAPSUBSCRTYPE2':
				$result = __('Weeks', 'vikappointments');
				break;

			case 'VAPSUBSCRTYPE3':
				$result = __('Months', 'vikappointments');
				break;

			case 'VAPSUBSCRTYPE4':
				$result = __('Years', 'vikappointments');
				break;

			case 'VAPSUBSCRTYPE5':
				$result = __('Lifetime', 'vikappointments');
				break;

			case 'VAPMENUCOUNTRIES':
				$result = __('Countries', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCOUNTRY':
				$result = __('VikAppointments - New Country', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCOUNTRY':
				$result = __('VikAppointments - Edit Country', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCOUNTRIES':
				$result = __('VikAppointments - Countries', 'vikappointments');
				break;

			case 'VAPCOUNTRYUNIQUEERROR':
				$result = __('The Country 2 Code and 3 Code have to be unique!', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY2':
				$result = __('Country 2 Code', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY3':
				$result = __('Country 3 Code', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY4':
				$result = __('Phone Prefix', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY5':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGECOUNTRY6':
				$result = __('Flag', 'vikappointments');
				break;

			case 'VAPMENUSTATES':
				$result = __('States', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSTATE':
				$result = __('VikAppointments - %s New State', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSTATE':
				$result = __('VikAppointments - %s Edit State', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSTATES':
				$result = __('VikAppointments - %s States', 'vikappointments');
				break;

			case 'VAPSTATEUNIQUEERROR':
				$result = __('The State 2 Code and 3 Code have to be unique!', 'vikappointments');
				break;

			case 'VAPMANAGESTATE1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGESTATE2':
				$result = __('State 2 Code', 'vikappointments');
				break;

			case 'VAPMANAGESTATE3':
				$result = __('State 3 Code', 'vikappointments');
				break;

			case 'VAPMANAGESTATE4':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMENUCITIES':
				$result = __('Cities', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCITY':
				$result = __('VikAppointments - %s New City', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCITY':
				$result = __('VikAppointments - %s Edit City', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCITIES':
				$result = __('VikAppointments - %s Cities', 'vikappointments');
				break;

			case 'VAPCITYUNIQUEERROR':
				$result = __('The City 2 Code and 3 Code have to be unique!', 'vikappointments');
				break;

			case 'VAPMANAGECITYTITLE1':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPMANAGECITYTITLE2':
				$result = __('Map', 'vikappointments');
				break;

			case 'VAPMANAGECITY1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGECITY2':
				$result = __('City 2 Code', 'vikappointments');
				break;

			case 'VAPMANAGECITY3':
				$result = __('City 3 Code', 'vikappointments');
				break;

			case 'VAPMANAGECITY4':
				$result = __('Latitude', 'vikappointments');
				break;

			case 'VAPMANAGECITY5':
				$result = __('Longitude', 'vikappointments');
				break;

			case 'VAPMANAGECITY6':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPGOTOCOUNTRIES':
				$result = __('Back to Countries', 'vikappointments');
				break;

			case 'VAPGOTOSTATES':
				$result = __('Back to States', 'vikappointments');
				break;

			case 'VAPGOTOSUBSCRORDERS':
				$result = __('Back to Orders', 'vikappointments');
				break;

			case 'VAPGOTOEMPLOYEES':
				$result = __('Back to Employees', 'vikappointments');
				break;

			case 'VAPGOTOEMPSUBSCR':
				$result = __('Employees Overview', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE24':
				$result = __('Sync Key', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE25':
				$result = __('Sync URL', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE26':
				$result = __('Group', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE27':
				$result = __('Active To', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE28':
				$result = __('Active Since', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE29':
				$result = __('Locations', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE30':
				$result = __('Timezone', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE28':
				$result = __('Update Rate to all Employees', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION32':
				$result = __('Nominative', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION33':
				$result = __('User', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION34':
				$result = __('Guest', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION35':
				$result = __('Invoice', 'vikappointments');
				break;

			case 'VAPRESLISTCREATEDTIP':
				$result = __('Created on %s by %s.', 'vikappointments');
				break;

			case 'VAPRESLISTGUEST':
				$result = __('Guest', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION10':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION11':
				$result = __('Display Image', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION12':
				$result = __('Required', 'vikappointments');
				break;

			case 'VAPMANAGEOPTDISPLAYMODE0':
				$result = __('Hidden', 'vikappointments');
				break;

			case 'VAPMANAGEOPTDISPLAYMODE1':
				$result = __('Popup', 'vikappointments');
				break;

			case 'VAPMANAGEOPTDISPLAYMODE2':
				$result = __('Left-Side', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON10':
				$result = __('Last Minute', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON11':
				$result = __('Check-in Within', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF9':
				$result = __('Default Country', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWEMPLOCATIONS':
				$result = __('VikAppointments - %s Locations', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWEMPLOCATION':
				$result = __('VikAppointments - New Location', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITEMPLOCATION':
				$result = __('VikAppointments - Edit Location', 'vikappointments');
				break;

			case 'VAPEMPLOCATIONTITLE1':
				$result = __('Location', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION1':
				$result = __('Country', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION2':
				$result = __('State', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION3':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION4':
				$result = __('ZIP Code', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION5':
				$result = __('Latitude', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION6':
				$result = __('Longitude', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION7':
				$result = __('Map', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION8':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION9':
				$result = __('Working Days Assignment', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION10':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPMANAGEWD7':
				$result = __('Location', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE4':
				$result = __('Reservations List Columns', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE5':
				$result = __('E-mail', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE6':
				$result = __('Currency', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE7':
				$result = __('Calendars', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE8':
				$result = __('Shop', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE9':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE10':
				$result = __('Appointments Sync', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE11':
				$result = __('Services', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE12':
				$result = __('Reviews', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE13':
				$result = __('Timezone', 'vikappointments');
				break;

			case 'VAPCONFIGSMSTITLE2':
				$result = __('Customer SMS Template', 'vikappointments');
				break;

			case 'VAPCONFIGSMSTITLE3':
				$result = __('Administrator SMS Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG55':
				$result = __('Format Duration', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG56':
				$result = __('Display From', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG57':
				$result = __('The selected month will be displayed as first only if it isn\'t in the past, otherwise the calendar will always start from the current month.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG58':
				$result = __('Show Phones Prefix', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG59':
				$result = __('Printable Orders', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG60':
				$result = __('Description Length', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG61':
				$result = __('Image Link Action', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG62':
				$result = __('Customer E-Mail Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG63':
				$result = __('Sync URL', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG64':
				$result = __('Sync Password', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG65':
				$result = __('Attach ICS to', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG66':
				$result = __('Attach CSV to', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG67':
				$result = __('Enable Multilingual', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG68':
				$result = __('Display Colors Legend', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG69':
				$result = __('Manage Custom Text', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG70':
				$result = __('Enable Router', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG71':
				$result = __('Send to Customers with Order', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG72':
				$result = __('Send to Employees with Order', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG73':
				$result = __('Send to Admin with Order', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG74':
				$result = __('Enable Reviews', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG75':
				$result = __('Services Reviews', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG76':
				$result = __('Employees Reviews', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG77':
				$result = __('Comment Required', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG78':
				$result = __('Comment Min Length', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG79':
				$result = __('Comment Max Length', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG80':
				$result = __('List Limit', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG81':
				$result = __('Filter by Language', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG82':
				$result = __('New Auto-Published', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG83':
				$result = __('Always Confirm on Delete', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG84':
				$result = __('Cart Auto Expanded', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG85':
				$result = __('Loading Mode', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG86':
				$result = __('Enable Multiple Time Zones', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG87':
				$result = __('Current Default Time Zone', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG88':
				$result = __('Enable Groups Filter', 'vikappointments');
				break;

			case 'VAPCONFIGSENDMAILWHEN1':
				$result = __('Only Confirmed', 'vikappointments');
				break;

			case 'VAPCONFIGSENDMAILWHEN2':
				$result = __('Pending or Confirmed', 'vikappointments');
				break;

			case 'VAPCONFIGLINKHREF1':
				$result = __('Go to the Employee Details', 'vikappointments');
				break;

			case 'VAPCONFIGLINKHREF2':
				$result = __('Open a popup with the original image', 'vikappointments');
				break;

			case 'VAPCONFIGLINKHREF3':
				$result = __('Go to the Service Details', 'vikappointments');
				break;

			case 'VAPCONFIGLINKHREF4':
				$result = __('Display description on hover', 'vikappointments');
				break;

			case 'VAPCONFIGREVLOADMODE1':
				$result = __('On Scroll Down', 'vikappointments');
				break;

			case 'VAPCONFIGREVLOADMODE2':
				$result = __('On Button Press', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP15':
				$result = __('Services Auto-Assignment', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP16':
				$result = __('Manage Working Days', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP17':
				$result = __('Manage Locations', 'vikappointments');
				break;

			case 'VAPSMSDIALOGTITLE':
				$result = __('SMS to %s', 'vikappointments');
				break;

			case 'VAPSMSDIALOGMESSAGE':
				$result = __('Insert below the text to send via SMS (Max 160 characters).', 'vikappointments');
				break;

			case 'VAPKEEPSMSTEXTDEF':
				$result = __('Keep this text as default', 'vikappointments');
				break;

			case 'VAPCUSTSMSSENT1':
				$result = __('SMS sent successfully!', 'vikappointments');
				break;

			case 'VAPCUSTSMSSENT0':
				$result = __('Impossible to send the SMS! Please, try to check the API settings and the credit on your account.', 'vikappointments');
				break;

			case 'VAPJMODALEMAILTMPL':
				$result = __('Email Template', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWMAILTEXTCUST':
				$result = __('VikAppointments - Email Custom Text', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWMAILTEXT':
				$result = __('VikAppointments - New Email Custom Text', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITMAILTEXT':
				$result = __('VikAppointments - Edit Email Custom Text', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL3':
				$result = __('Position', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL4':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL5':
				$result = __('File', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL6':
				$result = __('Language', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL7':
				$result = __('Content', 'vikappointments');
				break;

			case 'VAPCUSTMAILTITLE1':
				$result = __('E-mail Details', 'vikappointments');
				break;

			case 'VAPCUSTMAILTITLE2':
				$result = __('E-mail Content', 'vikappointments');
				break;

			case 'VAPMEDIASELECTALL':
				$result = __('Select All', 'vikappointments');
				break;

			case 'VAPMEDIASELECTNONE':
				$result = __('Select None', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIATITLE1':
				$result = __('Media File', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIATITLE2':
				$result = __('Quick New', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIATITLE3':
				$result = __('Uploads', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA11':
				$result = __('Processing...', 'vikappointments');
				break;

			case 'VAPINVOICEDETAILS':
				$result = __('Invoice Details', 'vikappointments');
				break;

			case 'VAPINVOICEPROPERTIES':
				$result = __('Properties', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE1':
				$result = __('Unique Identifier', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE2':
				$result = __('Date', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE3':
				$result = __('Taxes', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE4':
				$result = __('Legal Information', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE5':
				$result = __('Send invoices to the customers via e-mail', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE6':
				$result = __('Generate', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICEPROP1':
				$result = __('Page Orientation', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICEPROP2':
				$result = __('Page Format', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICEPROP3':
				$result = __('Unit', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICEPROP4':
				$result = __('Scale', 'vikappointments');
				break;

			case 'VAPINVOICEPROPORIENTATIONOPT1':
				$result = __('Portrait', 'vikappointments');
				break;

			case 'VAPINVOICEPROPORIENTATIONOPT2':
				$result = __('Landscape', 'vikappointments');
				break;

			case 'VAPINVOICEPROPUNITOPT1':
				$result = __('Point', 'vikappointments');
				break;

			case 'VAPINVOICEPROPUNITOPT2':
				$result = __('Millimeter', 'vikappointments');
				break;

			case 'VAPINVOICEPROPUNITOPT3':
				$result = __('Centimeter', 'vikappointments');
				break;

			case 'VAPINVOICEPROPUNITOPT4':
				$result = __('Inch', 'vikappointments');
				break;

			case 'VAPINVOICEDATEOPT1':
				$result = __('Today - %s', 'vikappointments');
				break;

			case 'VAPINVOICEDATEOPT2':
				$result = __('Booking Date', 'vikappointments');
				break;

			case 'VAPERRORFRAMEWORKPDF':
				$result = __('The TCPDF library does not exist on this program!', 'vikappointments');
				break;

			case 'VAPSYSTEMCONFIRMATIONMSG':
				$result = __('Do you want to proceed?', 'vikappointments');
				break;

			case 'VAPMENUARCHIVE':
				$result = __('Invoices', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWARCHIVE':
				$result = __('VikAppointments - Invoices Archive', 'vikappointments');
				break;

			case 'VAPARCHIVEOTHERS':
				$result = __('Others', 'vikappointments');
				break;

			case 'VAPARCHIVEOTHERSALL':
				$result = __('All', 'vikappointments');
				break;

			case 'VAPLOADMOREINVOICES':
				$result = __('Load more invoices', 'vikappointments');
				break;

			case 'VAPLOADALLINVOICES':
				$result = __('Load all invoices', 'vikappointments');
				break;

			case 'VAPSMSMESSAGECUSTOMER':
				// @TRANSLATORS: Max 160 character (included service and date).
				$result = _x('Your appointment for {service} @ {checkin} is now CONFIRMED.', 'Max 160 character (included service and date).', 'vikappointments');
				break;

			case 'VAPSMSMESSAGECUSTOMERMULTI':
				// @TRANSLATORS: Max 160 character (included service and date).
				$result = _x('Your appointments booked on {created_on} are now CONFIRMED.', 'Max 160 character (included service and date).', 'vikappointments');
				break;

			case 'VAPSMSMESSAGEADMIN':
				// @TRANSLATORS: Max 160 character (included service and date).
				$result = _x('A new appointment @ {checkin} has been CONFIRMED for {service}.', 'Max 160 character (included service and date).', 'vikappointments');
				break;

			case 'VAPSMSMESSAGEADMINMULTI':
				// @TRANSLATORS: Max 160 character (included service and date).
				$result = _x('The appointments of {customer} have been CONFIRMED.', 'Max 160 character (included service and date).', 'vikappointments');
				break;

			case 'VAPSMSCONTSWITCHMULTI':
				$result = __('For Multiple Orders', 'vikappointments');
				break;

			case 'VAPSMSCONTSWITCHSINGLE':
				$result = __('For Single Orders', 'vikappointments');
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

			case 'VAPCLONE':
				$result = __('Clone', 'vikappointments');
				break;

			case 'VAPSAVEASCOPY':
				$result = __('Save as Copy', 'vikappointments');
				break;

			case 'VAPCHARS':
				$result = __('Characters', 'vikappointments');
				break;

			case 'VAPNOMATCHES':
				$result = __('Not found', 'vikappointments');
				break;

			case 'VAPGOTOP':
				$result = __('Go Top', 'vikappointments');
				break;

			case 'VAPINVOICE':
				$result = __('Invoice', 'vikappointments');
				break;

			case 'VAPREPORTS':
				$result = __('Reports', 'vikappointments');
				break;

			case 'VAPICSURL':
				$result = __('You can use this URL to automatically synchronize the appointments on VikAppointments into your favourite calendar providers. You have just to insert this URL into the apposite page of your provider and it will automatically download an ICS file to keep the calendar up-to-date.<br/><br/>You can also filter the appointments to export by employee simply by adding the &employee=[EMP_ID] parameter in the query string of the URL.', 'vikappointments');
				break;

			case 'VAPMENUSUBSCRIPTIONORDERS':
				$result = __('Subscr. Orders', 'vikappointments');
				break;

			case 'VAPMENULOCATIONS':
				$result = __('Locations', 'vikappointments');
				break;

			case 'VAPMENUPACKAGES':
				$result = __('Packages', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWPACKAGES':
				$result = __('VikAppointments - Packages', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITPACKAGE':
				$result = __('VikAppointments - Edit Package', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWPACKAGE':
				$result = __('VikAppointments - New Package', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGEFIELDSET1':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE1':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE2':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE3':
				$result = __('Price', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE4':
				$result = __('Num Appointments', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE5':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE6':
				$result = __('Start Date', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE7':
				$result = __('End Date', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE8':
				$result = __('Group', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE9':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE10':
				$result = __('Availability', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE11':
				$result = __('Available Services', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE12':
				$result = __('- all the services -', 'vikappointments');
				break;

			case 'VAPMENUPACKGROUPS':
				$result = __('Packages Groups', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWPACKGROUPS':
				$result = __('VikAppointments - Packages Groups', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITPACKGROUP':
				$result = __('VikAppointments - Edit Packages Group', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWPACKGROUP':
				$result = __('VikAppointments - New Packages Group', 'vikappointments');
				break;

			case 'VAPMANAGEPACKGROUP1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEPACKGROUP2':
				$result = __('Title', 'vikappointments');
				break;

			case 'VAPMANAGEPACKGROUP3':
				$result = __('Description', 'vikappointments');
				break;

			case 'VAPMANAGEPACKGROUP4':
				$result = __('Ordering', 'vikappointments');
				break;

			case 'VAPMENUPACKORDERS':
				$result = __('Packages Orders', 'vikappointments');
				break;

			case 'VAPMENUPACKORDERDETAILS':
				$result = __('Packages Order Details', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWPACKORDERS':
				$result = __('VikAppointments - Packages Orders', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITPACKORDER':
				$result = __('VikAppointments - Edit Packages Order', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWPACKORDER':
				$result = __('VikAppointments - New Packages Order', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDERFIELDSET1':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDERFIELDSET2':
				$result = __('Packages', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER2':
				$result = __('Ord Key', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER3':
				$result = __('Payment', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER4':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER5':
				$result = __('Total Cost', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER6':
				$result = __('Customer', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER7':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER8':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER9':
				$result = __('Created On', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER10':
				$result = __('Info.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER11':
				$result = __('Tot Paid', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER12':
				$result = __('Log', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER13':
				$result = __('Nominative', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER14':
				$result = __('- Pick a package to add it -', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER15':
				$result = __('app.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER16':
				$result = __('Used App.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER17':
				$result = __('Last Usage', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER18':
				$result = __('Package', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWLOCATIONS':
				$result = __('VikAppointments - Locations', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION11':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION12':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION13':
				$result = __('Coordinates', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION14':
				$result = __('- Global -', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION15':
				$result = __('Fetch coordinates of the specified address', 'vikappointments');
				break;

			case 'VAPDASHINCOMINGRESERVATIONS':
				$result = __('Incoming', 'vikappointments');
				break;

			case 'VAPDASHLATESTCUSTOMERS':
				$result = __('Latest Registered', 'vikappointments');
				break;

			case 'VAPDASHLOGGEDCUSTOMERS':
				$result = __('Logged in', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWWAITLIST':
				$result = __('VikAppointments - Waiting List', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITWAITING':
				$result = __('VikAppointments - Edit Waiting Customer', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWWAITING':
				$result = __('VikAppointments - New Waiting Customer', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST1':
				$result = __('Service', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST2':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST3':
				$result = __('Check-in Day', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST4':
				$result = __('User', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST5':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST6':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPMANAGEWAITLIST7':
				$result = __('Created On', 'vikappointments');
				break;

			case 'VAPWLNOTIFYMODALTITLE':
				$result = __('Notify Waiting List', 'vikappointments');
				break;

			case 'VAPWLNOTIFYMODALCONTENT':
				$result = __('Would you like to notify the users registered in the waiting list?', 'vikappointments');
				break;

			case 'VAPMANAGEGROUP5':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE29':
				$result = __('Start Publishing', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE30':
				$result = __('End Publishing', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE31':
				$result = __('- pick an employee -', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE32':
				$result = __('- pick an option -', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE33':
				$result = __('Has Own Calendar', 'vikappointments');
				break;

			case 'VAPHASOWNCALTITLE':
				$result = __('Has Own Calendar Notice', 'vikappointments');
				break;

			case 'VAPHASOWNCALMESSAGE':
				$result = __('This setting should be used only if your service is self-managed and doesn\'t need a real employee. When this option is enabled, the reservations of this service won\'t affect the calendar of the other services.', 'vikappointments');
				break;

			case 'VAPOPTIONFIELDSETTITLE1':
				$result = __('Option', 'vikappointments');
				break;

			case 'VAPOPTIONFIELDSETTITLE2':
				$result = __('Variations', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER21':
				$result = __('Quick Details', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE5':
				$result = __('Appointments List', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION36':
				$result = __('Notify Employee', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION37':
				$result = __('Created On', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION38':
				$result = __('Customer', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION39':
				$result = __('Variation', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF10':
				$result = __('Employee', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF11':
				$result = __('- Global -', 'vikappointments');
				break;

			case 'VAPMANAGECOUPONFIELDSET1':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPCOUPONINFOTIP':
				$result = __('Quantity Used: %d - Remaining Quantity: %s %s', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON12':
				$result = __('Validation Period', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON13':
				$result = __('Max Quantity', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON14':
				$result = __('Total Usages', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON15':
				$result = __('Auto Remove After Usage', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON16':
				$result = __('Notes', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON17':
				$result = __('Available Services', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON18':
				$result = __('Available Employees', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON19':
				$result = __('- all the services -', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON20':
				$result = __('- all the employees -', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT11':
				$result = __('Allowed For', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT12':
				$result = __('Output Position', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT13':
				$result = __('Notes Before Purchase', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT14':
				$result = __('Notes After Purchase', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT15':
				$result = __('Icon', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT1':
				$result = __('- Ignore -', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT2':
				$result = __('Top-Left', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT3':
				$result = __('Top-Center', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT4':
				$result = __('Top-Right', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT5':
				$result = __('Bottom-Left', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT6':
				$result = __('Bottom-Center', 'vikappointments');
				break;

			case 'VAPPAYMENTPOSOPT7':
				$result = __('Bottom-Right', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCRONJOB':
				$result = __('VikAppointments - New Cron Job', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCRONJOB':
				$result = __('VikAppointments - Edit Cron Job', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCRONJOBS':
				$result = __('VikAppointments - Cron Jobs', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBFIELDSET1':
				$result = __('Cron Job', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBFIELDSET2':
				$result = __('Settings', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB2':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB3':
				$result = __('Class', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB4':
				$result = __('Published', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB5':
				$result = __('Logs', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB6':
				$result = __('- Please choose a file -', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB7':
				$result = __('No Parameters Avaialble', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB8':
				$result = __('Last Log Status', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB9':
				$result = __('Manual Launch', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB10':
				$result = __('Unpublished', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCRONJOBLOGS':
				$result = __('VikAppointments - Cron Job Logs', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCRONJOBLOGINFO':
				$result = __('Cron Job Log Info', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBLOG1':
				$result = __('ID', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBLOG2':
				$result = __('Created On', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBLOG3':
				$result = __('Content', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBLOG4':
				$result = __('Status', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOBLOG5':
				$result = __('Mailed', 'vikappointments');
				break;

			case 'VAPCRONLOGSTATUSOK':
				$result = __('OK', 'vikappointments');
				break;

			case 'VAPCRONLOGSTATUSERROR':
				$result = __('ERROR', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP18':
				$result = __('Manage Coupons', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP19':
				$result = __('Manage Custom Fields', 'vikappointments');
				break;

			case 'VAPCONFIGSMSTITLE1':
				$result = __('SMS Templates', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME5':
				$result = __('CRON Jobs', 'vikappointments');
				break;

			case 'VAPCONFIGCRONTITLE1':
				$result = __('Settings', 'vikappointments');
				break;

			case 'VAPCONFIGCRONTITLE2':
				$result = __('Installation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON1':
				$result = __('Secure Key', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON2':
				$result = __('Register Log', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON3':
				$result = __('Only With Errors', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON4':
				$result = __('Always', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON5':
				$result = __('Installed Cron', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON6':
				$result = __('See Cron Jobs List', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON7':
				$result = __('Download Runnable File', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGCRON8':
				$result = __('Instructions', 'vikappointments');
				break;

			case 'VAPCRONJOBERROR1':
				$result = __('The selected cron doesn\'t exist or is not valid!', 'vikappointments');
				break;

			case 'VAPCRONJOBERROR2':
				$result = __('Before to download the runnable file, create at least a cron job.', 'vikappointments');
				break;

			case 'VAPCRONJOBINSTALLED1':
				$result = __('Cron Job %s installed correctly.', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.2 - administrator - VikWP.com
			 */

			case 'VAPCRONJOBINSTALLED0':
				$result = __('Cron Job %s installation error! Please, delete it manually and try to create a new one.', 'vikappointments');
				break;

			case 'VAPCRONJOBUNINSTALLED1':
				$result = __('Cron Job %s uninstalled correctly.', 'vikappointments');
				break;

			case 'VAPCRONJOBUNINSTALLED0':
				$result = __('Cron Job %s uninstallation error!', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE14':
				$result = __('Waiting List', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE15':
				$result = __('Listings', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE16':
				$result = __('Packages', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG89':
				$result = __('Required on Services/Employees Details', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG90':
				$result = __('Ordering Filter', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG91':
				$result = __('Admin E-Mail Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG92':
				$result = __('Employee E-Mail Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG93':
				$result = __('Cancellation E-Mail Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG94':
				$result = __('First Week Day', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG95':
				$result = __('Auto-Generation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG97':
				$result = __('As Specified', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG98':
				$result = __('Concurrent Check-ins', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG100':
				$result = __('Enable Waiting List', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG101':
				$result = __('SMS Content', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG102':
				$result = __('E-Mail Template', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG103':
				$result = __('Decimal Separator', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG104':
				$result = __('Thousands Separator', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG105':
				$result = __('Number of Decimals', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG106':
				$result = __('Packages per Row', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG107':
				$result = __('Max Packages in Cart', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG108':
				$result = __('Allow User Registration', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG109':
				$result = __('Enable Packages', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT1':
				$result = __('YYYY/mm/dd', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT2':
				$result = __('mm/dd/YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT3':
				$result = __('dd/mm/YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT4':
				$result = __('YYYY-mm-dd', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT5':
				$result = __('mm-dd-YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT6':
				$result = __('dd-mm-YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT7':
				$result = __('YYYY.mm.dd', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT8':
				$result = __('mm.dd.YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGDATEFORMAT9':
				$result = __('dd.mm.YYYY', 'vikappointments');
				break;

			case 'VAPCONFIGSENDMAILWHEN3':
				$result = __('Never', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSHOPOPT3':
				$result = __('- Custom Link -', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE1':
				$result = __('Alphabetically a..Z', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE2':
				$result = __('Alphabetically Z..a', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE3':
				$result = __('Newest Employees', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE4':
				$result = __('Oldest Employees', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE5':
				$result = __('Most Popular', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE6':
				$result = __('Highest Rating', 'vikappointments');
				break;

			case 'VAPDOWNLOADREPORTOPT1':
				$result = __('check-in date', 'vikappointments');
				break;

			case 'VAPDOWNLOADREPORTOPT2':
				$result = __('purchase date', 'vikappointments');
				break;

			case 'VAPIMPORT':
				$result = __('Import', 'vikappointments');
				break;

			case 'VAPMAKERECURRENCE':
				$result = __('Make Recurrence', 'vikappointments');
				break;

			case 'VAPRESERVATIONREMOVEMESSAGE':
				$result = __('Do you want to remove this reservation? This action cannot be undone.', 'vikappointments');
				break;

			case 'VAPMAINTITLEMAKERECURRENCE':
				$result = __('VikAppointments - Reservation Make Recurrence', 'vikappointments');
				break;

			case 'VAPMAKERECGETPREVIEW':
				$result = __('Get Recurrence Preview', 'vikappointments');
				break;

			case 'VAPMAKERECNOROWS':
				$result = __('No possible recurrence with the specified data!', 'vikappointments');
				break;

			case 'VAPMAKERECLAUNCHPROC':
				$result = __('Create Recurring Reservations', 'vikappointments');
				break;

			case 'VAPMAKERECDATEOK':
				$result = __('Date Available', 'vikappointments');
				break;

			case 'VAPMAKERECDATEFAIL':
				$result = __('Date Not Available', 'vikappointments');
				break;

			case 'VAPMAKERECSUCCESS1':
				$result = __('Number of reservations created: %d.', 'vikappointments');
				break;

			case 'VAPMAKERECSUCCESS0':
				$result = __('No reservation created!', 'vikappointments');
				break;

			case 'VAPCONNECTIONLOSTERROR':
				$result = __('Connection Lost! Please try again.', 'vikappointments');
				break;

			/**
			 * time format
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
			 * VikAppointments 1.6 - administrator - VikWP.com
			 */

			case 'VAPMAINTITLEVIEWEMPPAYMENTS':
				$result = __('VikAppointments - %s Payments', 'vikappointments');
				break;

			case 'VAPINVPAYCHARGE':
				$result = __('Payment Charge', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION41':
				$result = __('Created By', 'vikappointments');
				break;

			case 'VAPSEECCDETAILS':
				$result = __('See Credit Card', 'vikappointments');
				break;

			case 'VAPCREDITCARDAUTODELMSG':
				$result = __('The details of this credit card will be removed automatically on %s', 'vikappointments');
				break;

			case 'VAPCREDITCARDREMOVED':
				$result = __('Credit card details removed correctly!', 'vikappointments');
				break;

			case 'VAPPAYMENTICONOPT0':
				$result = __('- none -', 'vikappointments');
				break;

			case 'VAPPAYMENTICONOPT1':
				$result = __('Font Icon', 'vikappointments');
				break;

			case 'VAPPAYMENTICONOPT2':
				$result = __('Upload Image', 'vikappointments');
				break;

			case 'VAPMANAGEPAYALLOWEDFOROPT1':
				$result = __('Appointments Purchase', 'vikappointments');
				break;

			case 'VAPMANAGEPAYALLOWEDFOROPT2':
				$result = __('Packages and Subscriptions', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE34':
				$result = __('Multiple App. per Slot', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE35':
				$result = __('Check-out Selection', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE36':
				$result = __('Display Remaining Seats', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE17_DESC':
				$result = __('Enable this option to allow the customers to send e-mail messages to the administrator(s) from the front-end.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE18_DESC':
				$result = __('Turn on this option to allow the customers to select the employee from the front-end. This option should be disabled in case the employees are not visible by the customers.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE24_DESC':
				$result = __('If enabled, this service will be available only for those customers that live in ZIP codes that are accepted by the system.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE27_DESC':
				$result = __('When this option is enabled, the customers will be able to book an appointment with limited recurrence (only if the shopping cart is enabled).', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE19_DESC':
				$result = __('The specified amount is used to reserve some time between each appointment. Use a negative amount in case the service has a real duration lower than the specified one.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE20_DESC':
				$result = __('The daily frequency of the available times. The first amount matches the duration of the service (plus the sleep time). The second amount is the global interval.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE21_DESC':
				$result = __('The total number of available "seats" that can be reserved for each time slot. If this value is higher than 1, the people selection will be available for this service.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE22_DESC':
				$result = __('The minimum number of participants that can be selected per appointment.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE23_DESC':
				$result = __('The maximum number of participants that can be selected per appointment.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE34_DESC':
				$result = __('When enabled, the time slots will be able to host different appointments until the full capacity is reached. Turn off this setting only if you want to receive a single appointment per time slot.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE35_DESC':
				$result = __('Enable this setting to allow the customers to select the check-out time. The check-out selection is allowed only in case the service is assigned only to one employee or when the employee selection is enabled.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE36_DESC':
				$result = __('Turn on this setting to display the remaining seats within the timeline. The seats will be displayed only in case the service is assigned only to one employee.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE8_DESC':
				$result = __('The specified e-mail address will be used to receive appointments notifications and questions from the customers (see <b>Quick Contact</b> parameter).<br />The e-mail address will never be visible from the front-end.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE17_DESC':
				$result = __('Enable this option to allow the customers to send e-mail messages to this employee from the front-end.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE18_DESC':
				$result = __('Turn on this option to make this employee visible from the front-end. Turn off this parameter in case the customers cannot access the details of this employee.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE19_DESC':
				$result = __('Assign this employee to a user to let it accessing the employees area in the front-end.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE24_DESC':
				$result = __('The sync key is a unique key needed to protect the URL used to synchronize the ICS calendars of this employee.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE25_DESC':
				$result = __('Create a new subscription from your preferred calendar (such as Apple iCal or Google Calendar) by using this URL to synchronize the appointments of this employee with all your devices.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE27_DESC':
				$result = __('Select how long this employee will remain visible (only if the employee is <b>listable</b>).', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION12_DESC':
				$result = __('Select a value to make the location available only for that employee. Otherwise the location will be considered as global and could be used by any employee.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE4_DESC':
				$result = __('The total number of services/appointments that can be redeemed by purchasing this package.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE11_DESC':
				$result = __('All the possible services that can be redeemed by purchasing this package.', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT5_DESC':
				$result = __('This parameter is used to skip the payment form and to automatically CONFIRM the orders. When this option is enabled, there won\'t be a transaction between the bank and the customer. Keep this option disabled in case this payment method is used to collect online credit/debit cards.', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT12_DESC':
				$result = __('The position of the order summary page in which the payment form/button will be displayed.', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWIMPORT':
				$result = __('VikAppointments - Import', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWEXPORT':
				$result = __('VikAppointments - Export', 'vikappointments');
				break;

			case 'VAPMAINTITLEMANAGEIMPORT':
				$result = __('VikAppointments - Manage Import', 'vikappointments');
				break;

			case 'VAPIMPORTRECORDSADDED':
				$result = __('Imported records: %d of %d.', 'vikappointments');
				break;

			case 'VAPIMPORTTABLEFOOTER':
				$result = __('The first %d rows of your file.', 'vikappointments');
				break;

			case 'VAPEXPORTTABLEFOOTER':
				$result = __('The first %d rows of %d total.', 'vikappointments');
				break;

			case 'VAPIMPORTMOREDETAILSERR':
				$result = __('Show more details.', 'vikappointments');
				break;

			case 'VAPIMPORTINSERTERR':
				$result = __('An error occurred while inserting the record.', 'vikappointments');
				break;

			case 'VAPCUSTFIELDSLEGEND1':
				$result = __('Details', 'vikappointments');
				break;

			case 'VAPCUSTFIELDSLEGEND2':
				$result = __('Type Settings', 'vikappointments');
				break;

			case 'VAPCUSTFIELDSLEGEND3':
				$result = __('Rule Settings', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF12':
				$result = __('Rule', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF13':
				$result = __('Form Name', 'vikappointments');
				break;

			case 'VAPCFGROUP_DESC':
				$result = __('Select the group for which the custom field will be used.<br />Choose <b>customers</b> if the field will be used to collect information during the purchase (default).<br />Choose <b>employees</b> if the field will be used within the registration form of an employee.', 'vikappointments');
				break;

			case 'VAPCFFORMNAME_DESC':
				$result = __('Specify a unique name that will be used within the forms and URLs. If you leave this field empty, the form name will be taken from the field name.', 'vikappointments');
				break;

			case 'VAPCUSTOMFTYPEOPTION8':
				$result = __('Number', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE0':
				$result = __('None', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE1':
				$result = __('Nominative', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE2':
				$result = __('E-Mail', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE3':
				$result = __('Phone Number', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE4':
				$result = __('State/Province', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE5':
				$result = __('City', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE6':
				$result = __('Address', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE7':
				$result = __('ZIP', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE8':
				$result = __('Company Name', 'vikappointments');
				break;

			case 'VAPCUSTFIELDRULE9':
				$result = __('VAT Number', 'vikappointments');
				break;

			case 'VAPFORMCHANGEDCONFIRMTEXT':
				$result = __('If you leave the page you will lose all the changes made. Do you want to proceed?', 'vikappointments');
				break;

			case 'VAPFILTERSELECTGROUP':
				$result = __('- Select Group -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTSTATUS':
				$result = __('- Select Status -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTTYPE':
				$result = __('- Select Type -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTRULE':
				$result = __('- Select Rule -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTPAYMENT':
				$result = __('- Select Payment -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTRATING':
				$result = __('- Select Rating -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTLANG':
				$result = __('- Select Language -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTSUBSCR':
				$result = __('- Select Subscription -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTCOUNTRY':
				$result = __('- Select Country -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTVAL':
				$result = __('- Select Value -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTFILE':
				$result = __('- Select File -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTPOSITION':
				$result = __('- Select Position -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTOWNER':
				$result = __('- Select Owner -', 'vikappointments');
				break;

			case 'VAPWDLEGENDLABEL1':
				$result = __('Weekly Working Days', 'vikappointments');
				break;

			case 'VAPWDLEGENDTITLE1':
				$result = __('The working day will be used with weekly frequency (e.g. every %s).', 'vikappointments');
				break;

			case 'VAPWDLEGENDLABEL2':
				$result = __('Special Working Days', 'vikappointments');
				break;

			case 'VAPWDLEGENDTITLE2':
				$result = __('The working day will be used only for the specified date (e.g. %s).', 'vikappointments');
				break;

			case 'VAPCOUPONVALUETYPE1':
				$result = __('Percent', 'vikappointments');
				break;

			case 'VAPCOUPONVALUETYPE2':
				$result = __('Total', 'vikappointments');
				break;

			case 'VAPMANAGEGROUPS':
				$result = __('Manage Groups', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCOUPONGROUP':
				$result = __('VikAppointments - New Coupon Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCOUPONGROUP':
				$result = __('VikAppointments - Edit Coupon Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCOUPONGROUPS':
				$result = __('VikAppointments - Coupon Groups', 'vikappointments');
				break;

			case 'VAPMANAGESPECIALRATES':
				$result = __('Manage Special Rates', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSPECIALRATE':
				$result = __('VikAppointments - New Special Rate', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSPECIALRATE':
				$result = __('VikAppointments - Edit Special Rate', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSPECIALRATES':
				$result = __('VikAppointments - Special Rates', 'vikappointments');
				break;

			case 'VAPTESTRATES':
				$result = __('Test Rates', 'vikappointments');
				break;

			case 'VAPUSETIMEFILTER':
				$result = __('Use Time Filter', 'vikappointments');
				break;

			case 'VAPSPECIALRATEWD_HELP':
				$result = __('The special rate will be applied only for the selected days. Leave this field empty if you want to apply the rate for all the days of the week.', 'vikappointments');
				break;

			case 'VAPSPECIALRATETIME_HELP':
				$result = __('When this option is enabled, you can define the time range in which the special rate will be applied. Turn off this setting to apply the rate for the whole day.', 'vikappointments');
				break;

			case 'VAPSPECIALRATESER_HELP':
				$result = __('Select all the services that will be affected by this special rate. Leave this field empty to apply the rate to all the existing services.', 'vikappointments');
				break;

			case 'VAPSPECIALRATESTARTPUB_HELP':
				$result = __('The publishing period is referring to the check-in of the appointments and not to the current date. Leave this field empty to apply the rate with no time restrictions.', 'vikappointments');
				break;

			case 'VAPSPECIALRATEENDPUB_HELP':
				$result = __('The stop publishing date is inclusive of the specified day. Leave this field empty to consider only the start publishing.', 'vikappointments');
				break;

			case 'VAPSPECIALRATECHARGE_HELP':
				$result = __('Increase or decrease the base cost of the service by the specified amount.', 'vikappointments');
				break;

			case 'VAPDEBUGRATESFOOTER':
				$result = __('If you were looking for a rate that is not reported in the list, make sure it is published and assigned to the selected service.', 'vikappointments');
				break;

			case 'VAPCHDISC':
				$result = __('Charge/Discount', 'vikappointments');
				break;

			case 'VAPBASECOST':
				$result = __('Base Cost', 'vikappointments');
				break;

			case 'VAPCOSTPP':
				$result = __('Cost per Person', 'vikappointments');
				break;

			case 'VAPFINALCOST':
				$result = __('Final Cost', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCLOSURE':
				$result = __('VikAppointments - New Closure', 'vikappointments');
				break;

			case 'VAPCLOSUREINFOMESSAGE':
				$result = __('The employee <b>%s</b> is closed on <b>%s</b> from <b>%s</b> to <b>%s</b>.', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWCONVERSION':
				$result = __('VikAppointments - New Conversion Code', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITCONVERSION':
				$result = __('VikAppointments - Edit Conversion Code', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWCONVERSIONS':
				$result = __('VikAppointments - Conversion Codes', 'vikappointments');
				break;

			case 'VAPCODESNIPPET':
				$result = __('Code Snippet', 'vikappointments');
				break;

			case 'VAPPAGE':
				$result = __('Page', 'vikappointments');
				break;

			case 'VAPMANUALUPLOAD':
				$result = __('Upload File', 'vikappointments');
				break;

			case 'VAPMEDIADRAGDROP':
				$result = __('or DRAG IMAGES HERE', 'vikappointments');
				break;

			case 'VAPCSVDRAGDROP':
				$result = __('or DRAG A CSV FILE HERE', 'vikappointments');
				break;

			case 'VAPIMPORTCSVUPLOADALERT':
				$result = __('Please, upload a CSV file first.', 'vikappointments');
				break;

			case 'VAPUPDATEALL':
				$result = __('Update All', 'vikappointments');
				break;

			case 'VAPNEWFILENAMETITLE':
				$result = __('Enter the new filename:', 'vikappointments');
				break;

			case 'VAPFIELDSETASSOC':
				$result = __('Assignments', 'vikappointments');
				break;

			case 'VAPORDERSTATUSES':
				$result = __('Order Statuses', 'vikappointments');
				break;

			case 'VAPORDERSTATUS':
				$result = __('Order Status', 'vikappointments');
				break;

			case 'VAPREMOTEADDR':
				$result = __('IP', 'vikappointments');
				break;

			case 'VAPREFERER':
				$result = __('Referer', 'vikappointments');
				break;

			case 'VAPPARENTORDER':
				$result = __('Parent Order', 'vikappointments');
				break;

			case 'VAPSTARS':
				$result = __('stars', 'vikappointments');
				break;

			case 'VAPSTAR':
				$result = __('star', 'vikappointments');
				break;

			case 'VAPACTIVE':
				$result = __('Active', 'vikappointments');
				break;

			case 'VAPEXPIRED':
				$result = __('Expired', 'vikappointments');
				break;

			case 'VAPREGISTERED':
				$result = __('Registered', 'vikappointments');
				break;

			case 'VAPREQUIRED':
				$result = __('Required', 'vikappointments');
				break;

			case 'VAPOPTIONAL':
				$result = __('Optional', 'vikappointments');
				break;

			case 'VAPBLOCK':
				$result = __('Block', 'vikappointments');
				break;

			case 'VAPSUFFIXCLASS':
				$result = __('Class Suffix', 'vikappointments');
				break;

			case 'VAPMINVAL':
				$result = __('Min. Value', 'vikappointments');
				break;

			case 'VAPMAXVAL':
				$result = __('Max. Value', 'vikappointments');
				break;

			case 'VAPALLOWDECIMALS':
				$result = __('Accept Decimals', 'vikappointments');
				break;

			case 'VAPMULTIPLE':
				$result = __('Multiple', 'vikappointments');
				break;

			case 'VAPWEEKDAYS':
				$result = __('Week Days', 'vikappointments');
				break;

			case 'VAPTIME':
				$result = __('Time', 'vikappointments');
				break;

			case 'VAPIGNORE':
				$result = __('Ignore', 'vikappointments');
				break;

			case 'VAPUSERGROUPS':
				$result = __('User Groups', 'vikappointments');
				break;

			case 'VAPUSERCREDIT':
				$result = __('Credit', 'vikappointments');
				break;

			case 'VAPSHOWALL':
				$result = __('Show All', 'vikappointments');
				break;

			case 'VAPSTATUSCLOSURE':
				$result = __('Closure', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG110':
				$result = __('Use Deposit', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG111':
				$result = __('Enable Conversion Code', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG112':
				$result = __('Enable GDPR', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG113':
				$result = __('Privacy Policy Link', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG114':
				$result = __('Enable User Credit', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG115':
				$result = __('AJAX Search', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG116':
				$result = __('Resulting Price', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG117':
				$result = __('Template Theme', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP20':
				$result = __('Assign Services', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP21':
				$result = __('Confirm', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT0':
				$result = __('No', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT1':
				$result = __('Optional', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT2':
				$result = __('Always', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG1_DESC':
				$result = __('The e-mail address of the administrator. All the notifications will be sent to this address. If you want to notify multiple addresses, you have just to specify the e-mails separated by a comma.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG8_DESC':
				$result = __('Insert here the standard 3 letters code of the currency used in your system (like EUR, USD, GBP and so on).<br />Since this value represents the currency to collect payments, it must be a standard of <b>ISO 4217</b>.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG10_DESC':
				$result = __('The global frequency of the available time slots. When this value is set to 15 minutes, the customers will be able to select a time every 15 minutes (e.g. 10:00, 10:15, 10:30). When this value is set to 60 minutes, the customers will be able to select a time every hour (e.g. 10:00, 11:00, 12:00). This setting can be overwritten from the details of each service.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG19_DESC':
				$result = __('The number of calendars that will be displayed within the employee/service details page.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG20_DESC':
				$result = __('This setting defines the number of minutes for which the system should keep a PENDING reservation as <b>locked</b>, so that nobody can reserve a service/employee that is being reserved by another client. In case that time expires, the status of the appointment will be switched to REMOVED.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG22_DESC':
				$result = __('How much time (in minutes) the customers have to book in advance. In case this value was set to 120 minutes and the current time is 10:00, the first time slot available (for the current day) would be @ 12:00.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG26_DESC':
				$result = __('The number of months that can be selected from the dropdown. By selecting 1 month, the dropdown won\'t be displayed.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG27_DESC':
				$result = __('The default status to use in case you are not using any payment gateway. With <b>PENDING</b> status, an administrator or an employee will have to confirm the appointments manually.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG32_DESC':
				$result = __('If your services are performed out of your business base and you want to restrict the available areas, you need to select the ZIP field that the customers will use to enter their ZIP codes.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG45_DESC':
				$result = __('Turn on this option to allow the customers to book multiple appointments within the same order.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG49_DESC':
				$result = __('Upload and pick a file from here in case you need to include one or more attachments within the e-mail for the customers.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG50_DESC':
				$result = __('The deposit will be used only if the total cost of the order is equals or higher than the specified amount.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG53_DESC':
				$result = __('The link to use when the customers hit the <b>Continue Shopping</b> button below the order summary in the confirmation page.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG55_DESC':
				$result = __('The duration of the services will be formatted to the closest unit (e.g. 150 minutes will be displayed as 2 hours & 30 min).', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG58_DESC':
				$result = __('Turn on this value to allow the customers to select the phone prefix of their country. If you accept reservations only from one country, you can turn off this setting. The default phone prefix can be changed from the details of the <b>Phone Number</b> custom field.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG59_DESC':
				$result = __('Enable this setting to display a button within the order summary view to print the details of the order.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG61_DESC':
				$result = __('The action to perform after clicking the image of the employees/services.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG80_DESC':
				$result = __('The maximum number of employees to display within the list.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG82_DESC':
				$result = __('Turn on this setting if you would like to publish immediately the reviews left by the customers.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG84_DESC':
				$result = __('Whether the cart summary box in the confirmation page should be expanded or not.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG85_DESC':
				$result = __('The remaining reviews can be loaded by using an apposite button or by reaching the last visible review while scrolling.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG86_DESC':
				$result = __('Turn on this setting in case the employees are located in areas with a timezone different than the default one.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG88_DESC':
				$result = __('Turn on this setting to allow the customers to filter the employees by group.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG90_DESC':
				$result = __('Turn on this setting to allow the customers to sort the employees with the available <b>Listing Modes</b>.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG95_DESC':
				$result = __('The invoice will be automatically generated only after a successful payment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG98_DESC':
				$result = __('When disabled, the customers won\'t be allowed to book services with colliding check-in. Enable this option only in case a customer is able to receive different services simultaneously.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG100_DESC':
				$result = __('Turn on this option if you want to allow the customers to register themselves into a waiting list of a certain service. Every time an appointment is cancelled, all the subscribed customers will be notified.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG109_DESC':
				$result = __('Turn on this setting to start creating new packages. The packages allow the customers to pre-purchase multiple services without the selection of the check-in date.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG112_DESC':
				$result = __('The <b>General Data Protection Regulation</b> is a regulation in EU law on data protection and privacy for all individuals within the European Union.<br />Turn on this setting to be compliant with <b>GDPR</b> requirements.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG114_DESC':
				$result = __('Every time an appointment is cancelled, the amount paid will be kept within the user credit. In this way, the customer will be able to purchase a new appointment using its credit.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG115_DESC':
				$result = __('The <b>AJAX search</b> tool is used to display an availability table of the employees within the list. A different layout will be used when this tool is active.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG117_DESC':
				$result = __('Select the color scheme used by your theme. If you are using a theme with <b>dark</b> colors, you should select that option (dark) in order to force the plugin to use similar colors.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGREC1_DESC':
				$result = __('When this option is enabled, the customers will be able to book an appointment with recurrence.<br /><u>The CART setting must be enabled too.</u>', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP1_DESC':
				$result = __('Turn on this option to allow the users to register a new account as employees by themselves.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP2_DESC':
				$result = __('The default user status after sign-up. Use <b>Pending</b> status in case you are going to sell subscriptions to the employees.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP4_DESC':
				$result = __('Enable this option to allow the employees to create new services or to auto-assign themselves to the existing services.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP6_DESC':
				$result = __('Enable this option to allow the employees to remove the relationships with the services they own.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP11_DESC':
				$result = __('Notify the administrator every time an employee removes an appointment from the system.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP12_DESC':
				$result = __('Enable this option to allow the employees to manage the details of the services with which they own a relationship.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP14_DESC':
				$result = __('Enable this option to allow the employees to manage only the overrides (price, duration, etc...) of the services with which they own a relationship. They are still able to edit the details of the services they have created.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP15_DESC':
				$result = __('Create automatically a relationship between the employee and the selected services during the account registration.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP20_DESC':
				$result = __('Enable this option to allow the employees to auto-assign themselves to the existing services.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP21_DESC':
				$result = __('When this option is disabled, the employees won\'t be able to manually APPROVE a pending appointment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS2_DESC':
				$result = __('Turn on this option to send automatically a SMS notification every time an appointment is confirmed.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS4_DESC':
				$result = __('The phone number of the administrator that will receive the notifications.', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT0_DESC':
				$result = __('The customers have to pay always the full amount.', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT1_DESC':
				$result = __('The customers can choose to pay the deposit or the full amount.', 'vikappointments');
				break;

			case 'VAPCONFIGDEPOSITOPT2_DESC':
				$result = __('The customers must leave always the deposit, only if the deposit condition is verified.', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE7':
				$result = __('Price: Low to High', 'vikappointments');
				break;

			case 'VAPCONFIGEMPLISTMODE8':
				$result = __('Price: High to Low', 'vikappointments');
				break;

			case 'VAPCONFIGAJAXSEARCHOPT0':
				$result = __('Disabled', 'vikappointments');
				break;

			case 'VAPCONFIGAJAXSEARCHOPT1':
				$result = __('Always Enabled', 'vikappointments');
				break;

			case 'VAPCONFIGAJAXSEARCHOPT2':
				$result = __('Use with filters', 'vikappointments');
				break;

			case 'VAPDELIMITER':
				$result = __('Delimiter', 'vikappointments');
				break;

			case 'VAPDELIMITER_DESC':
				$result = __('The optional delimiter parameter sets the field delimiter (one character only).', 'vikappointments');
				break;

			case 'VAPENCLOSURE':
				$result = __('Enclosure', 'vikappointments');
				break;

			case 'VAPENCLOSURE_DESC':
				$result = __('The optional enclosure parameter sets the field enclosure (one character only).', 'vikappointments');
				break;

			case 'VAPCOMMA':
				$result = __('Comma', 'vikappointments');
				break;

			case 'VAPSEMICOLON':
				$result = __('Semicolon', 'vikappointments');
				break;

			case 'VAPDOUBLEQ':
				$result = __('Double Quote', 'vikappointments');
				break;

			case 'VAPSINGQ':
				$result = __('Single Quote', 'vikappointments');
				break;

			case 'VAPSQLEXPORTMAXQUERY':
				$result = __('Max Records per Insert', 'vikappointments');
				break;

			case 'VAPSQLEXPORTMAXQUERY_DESC':
				$result = __('The maximum number of records to insert using the same query.', 'vikappointments');
				break;

			case 'VAPSQLEXPORTDBPREFIX':
				$result = __('DB Prefix', 'vikappointments');
				break;

			case 'VAPSQLEXPORTDBPREFIX_DESC':
				$result = __('The database prefix to use. If not provided, the default one will be used.', 'vikappointments');
				break;

			case 'VAPTIMELINEHOVERTIP':
				$result = __('Hover the mouse above the occupied time slots to see the participants list.', 'vikappointments');
				break;

			case 'VAPCALNUMAPP':
				$result = __('x%d appointments', 'vikappointments');
				break;

			/**
			 * Update system.
			 */

			case 'VAPMAINTITLEUPDATEPROGRAM':
				$result = __('VikAppointments - Software Update', 'vikappointments');
				break;

			case 'VAPCHECKINGVERSION':
				$result = __('Checking Version...', 'vikappointments');
				break;

			case 'VAPDOWNLOADUPDATEBTN1':
				$result = __('Download Update & Install', 'vikappointments');
				break;

			case 'VAPDOWNLOADUPDATEBTN0':
				$result = __('Download & Re-Install', 'vikappointments');
				break;

			case 'VAP_PLEASE_WAIT_MESSAGE':
				$result = __('It may take a few minutes to completion.<br />Please wait without leaving the page or closing the browser.', 'vikappointments');
				break;

			/**
			 * Order status default comments.
			 */

			case 'VAP_STATUS_CHANGED_ON_MANAGE':
				$result = __('The status has been changed from the order management page.', 'vikappointments');
				break;

			case 'VAP_STATUS_CHANGED_FROM_LIST':
				$result = __('The status has been switched from the reservations list.', 'vikappointments');
				break;

			case 'VAP_STATUS_CONFIRMED_WITH_LINK':
				$result = __('The status has been changed by using the confirmation link.', 'vikappointments');
				break;

			case 'VAP_STATUS_CONFIRMED_AS_NO_COST':
				$result = __('The order has been confirmed automatically because there is not a cost to pay.', 'vikappointments');
				break;

			case 'VAP_STATUS_CONFIRMED_AS_NO_PAYMENT':
				$result = __('The order has been confirmed automatically since there are no methods of payment.', 'vikappointments');
				break;

			case 'VAP_STATUS_CONFIRMED_RESULT_OF_PAYMENT':
				$result = __('The order has been confirmed automatically as a result of the payment configuration.', 'vikappointments');
				break;

			case 'VAP_STATUS_PACKAGE_REDEEMED':
				$result = __('The records of this order have been redeemed using the packages.', 'vikappointments');
				break;

			case 'VAP_STATUS_CHANGED_FROM_PAY':
				$result = __('The order has been confirmed as a result of a successful transaction.', 'vikappointments');
				break;

			case 'VAP_STATUS_ORDER_CANCELLED':
				$result = __('The customer has cancelled the order from the front-end.', 'vikappointments');
				break;

			case 'VAP_STATUS_ORDER_REMOVED':
				$result = __('The order hasn\'t been confirmed within the specified range of time.', 'vikappointments');
				break;

			case 'VAP_STATUS_CHANGED_EMP_MANAGE':
				$result = __('The status has been changed from the management page in the Employees Area.', 'vikappointments');
				break;

			case 'VAPCRONJOBTYPEDISABLED':
				$result = __('Class disabled', 'vikappointments');
				break;

			case 'VAPCRONJOBTYPEDISABLED_HELP':
				$result = __('It is not possible to change the class of an existing CRON JOB. If you need to do that, just trash this record and create a new one.', 'vikappointments');
				break;

			case 'VAPCFFORMNAMEDISABLED_HELP':
				$result = __('It is not possible to change the form name of an existing custom field. If you need to do that, just trash this record and create a new one.', 'vikappointments');
				break;

			case 'VAPCFFORMNAMEALTER_ERROR':
				$result = __('An error occurred while installing a new column. Please, copy the query below and launch it manually through your database panel.<br /><pre><code>%s</code></pre>', 'vikappointments');
				break;

			case 'VAPRATETIMELINECLASS':
				$result = __('Timeline Class', 'vikappointments');
				break;

			case 'VAPRATETIMELINECLASS_HELP':
				$result = __('A suffix to be applied to the CSS class of the timeline blocks owning an active rate. This allows for individual styling.', 'vikappointments');
				break;

			case 'VAPRATETIMELINESTYLE':
				$result = __('Timeline Style', 'vikappointments');
				break;

			case 'VAPRATETIMELINESTYLE_HELP':
				$result = __('You can select one of these presets in order to stylize the time blocks that own an active rate.', 'vikappointments');
				break;

			case 'VAPRATETIMELINESTYLE_OPT1':
				$result = __('Red Circle (Top-Right)', 'vikappointments');
				break;

			case 'VAPRATETIMELINESTYLE_OPT2':
				$result = __('Purple Border', 'vikappointments');
				break;

			case 'VAPRATECALENDARCLASS':
				$result = __('Calendar Class', 'vikappointments');
				break;

			case 'VAPRATECALENDARCLASS_HELP':
				$result = __('A suffix to be applied to the CSS class of the calendar days owning an active rate. This allows for individual styling.', 'vikappointments');
				break;

			case 'VAPRATECALENDARSTYLE':
				$result = __('Calendar Style', 'vikappointments');
				break;

			case 'VAPRATECALENDARSTYLE_HELP':
				$result = __('You can select one of these presets in order to stylize the calendar cells that MIGHT own an active rate.', 'vikappointments');
				break;

			case 'VAPDESCOVERRIDE_HELP':
				$result = __('The description won\'t override the original one, which will be always displayed before the text that can be specified here.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG118':
				$result = __('Show Check-out Time', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG118_DESC':
				$result = __('Turn on this setting to display the check-out time within the time blocks.', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.3 - administrator - VikWP.com
			 */

			case 'VAPMANAGECUSTOMF5_DESC':
				$result = __('Insert here the URL that will be opened when clicking the label of this field. For example, you can insert here the link to your "Terms & Conditions" post.', 'vikappointments');
				break;

			case 'VAPUSEEDITOR':
				$result = __('Use Editor', 'vikappointments');
				break;

			case 'VAPUSEEDITOR_DESC':
				$result = __('Enable this option to display a WYSIWYG (What You See Is What You Get) editor instead of a plain textarea.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON21':
				$result = __('Publishing Mode', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON21_DESC':
				$result = __('Choose how the publishing dates should be validated.<br /><b>Current Date</b><br />The coupon will be valid only if the current date (while booking an appointment) is between the specified start and end dates.<br /><b>Check-in Date</b><br />The coupon will be valid only if all the check-in dates in the cart are between the specified start and end dates.', 'vikappointments');
				break;

			case 'VAPCOUPONPUBMODEOPT1':
				$result = __('Current Date', 'vikappointments');
				break;

			case 'VAPCOUPONPUBMODEOPT2':
				$result = __('Check-in Date', 'vikappointments');
				break;

			case 'VAPALLSERVICES':
				$result = __('All Services', 'vikappointments');
				break;

			case 'VAPREPORTSSERTITLE':
				$result = __('VikAppointments - Services Reports', 'vikappointments');
				break;

			case 'VAPSEEREPORTSER':
				$result = __('See Services Reports', 'vikappointments');
				break;

			case 'VAPSEEREPORTEMP':
				$result = __('See Employees Reports', 'vikappointments');
				break;

			case 'VAPREPORTSVALUETYPEOPT1':
				$result = __('Total Earning', 'vikappointments');
				break;

			case 'VAPREPORTSVALUETYPEOPT2':
				$result = __('Reservations Count', 'vikappointments');
				break;

			case 'VAPREPORTSVALUETYPEOPT3':
				$result = __('Packages Count', 'vikappointments');
				break;

			case 'VAPORDERREDEEMEDPACKS':
				$result = __('Redeemed packages: %d', 'vikappointments');
				break;

			case 'VAPORDERUNREDEEMEDPACKS':
				$result = __('Restored packages: %d', 'vikappointments');
				break;

			case 'VAPREPORTSPACKTITLE':
				$result = __('VikAppointments - Packages Reports', 'vikappointments');
				break;

			case 'VAPLINECHARTSUBLEG3':
				$result = __('Packages groups (# orders)', 'vikappointments');
				break;

			case 'VAPUNCATEGORIZED':
				$result = __('Uncategorized', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.4 - administrator - VikWP.com
			 */

			case 'VAP_CUSTOM_FIELDSET':
				$result = __('Custom', 'vikappointments');
				break;

			case 'VAP_N_PACKAGES':
				$result = __('%d packages', 'vikappointments');
				break;

			case 'VAP_N_PACKAGES_1':
				$result = __('1 package', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECSINGOPT4':
				$result = __('Fortnight', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGRECSINGOPT5':
				$result = __('2 Months', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.5 - administrator - VikWP.com
			 */

			case 'VAPCODESNIPPETFILE_DESC':
				$result = __('In case the conversion code needs an helper file, it is possible to introduce its URL here.', 'vikappointments');
				break;

			case 'VAPCODESNIPPET_DESC';
				$result = __('It is possible to insert here the JavaScript callback that will be used when the conversion code is resolved. The snippet supports both "script" and "noscript" tags.', 'vikappointments');
				break;

			case 'VAPSYNCUBSCRICS':
				$result = __('Select the calendar provider that you wish to keep up-to-date.', 'vikappointments');
				break;

			case 'VAPSUBSCRIBE':
				$result = __('Subscribe', 'vikappointments');
				break;

			case 'VAPASGLOBAL':
				$result = __('As Global', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE37':
				$result = __('Advance Booking', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE37_DESC':
				$result = __('The minimum number of minutes required to book the appointment in advance.', 'vikappointments');
				break;

			case 'VAPMANAGESPECIALRESTR':
				$result = __('Manage Restrictions', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSPECIALRESTR':
				$result = __('VikAppointments - New Restriction', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSPECIALRESTR':
				$result = __('VikAppointments - Edit Restriction', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSPECIALRESTR':
				$result = __('VikAppointments - Special Restrictions', 'vikappointments');
				break;

			case 'VAPMANAGERESTR1':
				$result = __('Max App.', 'vikappointments');
				break;

			case 'VAPMANAGERESTR2':
				$result = __('Interval', 'vikappointments');
				break;

			case 'VAPMANAGERESTR3':
				$result = __('Applies to', 'vikappointments');
				break;

			case 'VAPMANAGERESTR1_DESC':
				$result = __('The maximum number of appointments that the same customer can book within the specified interval of time.', 'vikappointments');
				break;

			case 'VAPMANAGERESTR3_DESC':
				$result = __('It is possible to apply the restriction to the current date and to the check-in date. The first option is more restrictive as it doesn\'t allow to book an appointment in the future in case the customer already reached the maximum threshold.', 'vikappointments');
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

			case 'VAPMANAGERESTRMODE1':
				$result = __('Current Date', 'vikappointments');
				break;

			case 'VAPMANAGERESTRMODE2':
				$result = __('Check-in Date', 'vikappointments');
				break;

			case 'VAPFILTERSELECTORDERSTATUS':
				$result = __('- Select Order Status -', 'vikappointments');
				break;

			case 'VAPFILTERCREATENEW':
				$result = __('- Create New -', 'vikappointments');
				break;

			case 'VAPCUSTMAILNAME':
				$result = __('Enter a name for the e-mail custom text...', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.6.6 - administrator - VikWP.com
			 */

			case 'VAPAPPOINTMENT':
				$result = __('Appointment', 'vikappointments');
				break;

			case 'VAPRESEXPIRESIN':
				// @TRANSLATORS: in example "expires in 2 minutes"
				$result = __('expires %s', 'vikappointments');
				break;

			case 'VAPMANAGERESERVATION42':
				$result = __('Check-out', 'vikappointments');
				break;

			case 'VAPSHOWCUSTFIELDS':
				$result = __('Show Custom Fields', 'vikappointments');
				break;

			case 'VAPHIDECUSTFIELDS':
				$result = __('Hide Custom Fields', 'vikappointments');
				break;

			case 'VAPORDERPAID':
				$result = __('Paid', 'vikappointments');
				break;

			case 'VAPORDERDUE':
				$result = __('Due', 'vikappointments');
				break;

			case 'VAPORDEREXCLUDECUSTMAIL':
				$result = __('Exclude other custom texts', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7 - administrator - VikWP.com
			 */

			case 'VAPNOTIFICATIONS':
				$result = __('Notifications', 'vikappointments');
				break;

			case 'VAPLANGUAGE':
				$result = __('Language', 'vikappointments');
				break;

			case 'VAPLANGUAGES':
				$result = __('Languages', 'vikappointments');
				break;

			case 'VAPORIGINAL':
				$result = __('Original', 'vikappointments');
				break;

			case 'VAPMENU':
				$result = __('Menu', 'vikappointments');
				break;

			case 'VAPCONTACT':
				$result = __('Contact', 'vikappointments');
				break;

			case 'VAPVISIBILITY':
				$result = __('Visibility', 'vikappointments');
				break;

			case 'VAPPUBLIC':
				$result = __('Public', 'vikappointments');
				break;

			case 'VAPPRIVATE':
				$result = __('Private', 'vikappointments');
				break;

			case 'VAPADD':
				$result = __('Add', 'vikappointments');
				break;

			case 'VAPCOPY':
				$result = __('Copy', 'vikappointments');
				break;

			case 'VAPCOPIED':
				$result = __('Copied.', 'vikappointments');
				break;

			case 'VAPPREFERENCES':
				$result = __('Preferences', 'vikappointments');
				break;

			case 'VAPBOOKING':
				$result = __('Booking', 'vikappointments');
				break;

			case 'VAPCAPACITY':
				$result = __('Capacity', 'vikappointments');
				break;

			case 'VAPCONDITIONS':
				$result = __('Conditions', 'vikappointments');
				break;

			case 'VAPAPPEARANCE':
				$result = __('Appearance', 'vikappointments');
				break;

			case 'VAPCALCULATE':
				$result = __('Calculate', 'vikappointments');
				break;

			case 'VAPCOLOR':
				$result = __('Color', 'vikappointments');
				break;

			case 'VAPCOLUMNS':
				$result = __('Columns', 'vikappointments');
				break;

			case 'VAPTAGS':
				$result = __('Tags', 'vikappointments');
				break;

			case 'VAPORDER':
				// @TRANSLATORS: meant as something to purchase
				$result = _x('Order', 'meant as something to purchase', 'vikappointments');
				break;

			case 'VAPORDERS':
				$result = __('Orders', 'vikappointments');
				break;

			case 'VAPQUANTITY':
				$result = __('Quantity', 'vikappointments');
				break;

			case 'VAPATTACHMENTS':
				$result = __('Attachments', 'vikappointments');
				break;

			case 'VAPTEMPLATES':
				$result = __('Templates', 'vikappointments');
				break;

			case 'VAPQUANTITYSHORT':
				$result = __('Qty', 'vikappointments');
				break;

			case 'VAPRECURRENCE':
				$result = __('Recurrence', 'vikappointments');
				break;

			case 'VAPAPPLY':
				$result = __('Apply', 'vikappointments');
				break;

			case 'VAPLOAD':
				$result = __('Load', 'vikappointments');
				break;

			case 'VAPALLOWED':
				$result = __('Allowed', 'vikappointments');
				break;

			case 'VAPDENIED':
				$result = __('Denied', 'vikappointments');
				break;

			case 'VAPDELETEALL':
				$result = __('Delete All', 'vikappointments');
				break;

			case 'VAPOPEN':
				// @TRANSLATORS: verb (something to open)
				$result = _x('Open', 'verb (something to open)', 'vikappointments');
				break;

			case 'VAPMOREINFO':
				$result = __('More info', 'vikappointments');
				break;

			case 'VAPPRICING':
				$result = __('Pricing', 'vikappointments');
				break;

			case 'VAPRULES':
				$result = __('Rules', 'vikappointments');
				break;

			case 'VAPTOTALGROSS':
				$result = __('Total Gross', 'vikappointments');
				break;

			case 'VAPTOTALNET':
				$result = __('Total Net', 'vikappointments');
				break;

			case 'VAPTOTALTAX':
				$result = __('Total Taxes', 'vikappointments');
				break;

			case 'VAPFILTERSELECTEMPLOYEE':
				$result = __('- Select Employee -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTSERVICE':
				$result = __('- Select Service -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTOPTION':
				$result = __('- Select Option -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTPACKAGE':
				$result = __('- Select Package -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTLOCATION':
				$result = __('- Select Location -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTSTATE':
				$result = __('- Select State -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTCITY':
				$result = __('- Select City -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTAVAIL':
				$result = __('- Select Availability -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTCOUPON':
				$result = __('- Select Coupon -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTTIME':
				$result = __('- Select Time -', 'vikappointments');
				break;

			case 'VAPFILTERSELECTAPP':
				$result = __('- Select Application -', 'vikappointments');
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

			case 'VAP_DISPLAY_N_EMPLOYEES':
				$result = __('Display %d employees', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_EMPLOYEES_1':
				$result = __('Display employee', 'vikappointments');
				break;

			case 'VAP_ADD_EMPLOYEE':
				$result = __('Add Employee', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_SERVICES':
				$result = __('Display %d services', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_SERVICES_1':
				$result = __('Display service', 'vikappointments');
				break;

			case 'VAP_ADD_SERVICE':
				$result = __('Add Service', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_OPTIONS':
				$result = __('Display %d options', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_OPTIONS_1':
				$result = __('Display option', 'vikappointments');
				break;

			case 'VAP_ADD_OPTION':
				$result = __('Add Option', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_COUPONS':
				$result = __('Display %d coupons', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_COUPONS_1':
				$result = __('Display coupon', 'vikappointments');
				break;

			case 'VAP_ADD_COUPON':
				$result = __('Add Coupon', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_PACKAGES':
				$result = __('Display %d packages', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_PACKAGES_1':
				$result = __('Display package', 'vikappointments');
				break;

			case 'VAP_ADD_PACKAGE':
				$result = __('Add Package', 'vikappointments');
				break;

			case 'VAP_DISPLAY_ALL_NOTES':
				$result = __('Display All Notes', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_NOTES':
				$result = __('Display %d notes', 'vikappointments');
				break;

			case 'VAP_DISPLAY_N_NOTES_1':
				$result = __('Display note', 'vikappointments');
				break;

			case 'VAP_ADD_NOTE':
				$result = __('Add Note', 'vikappointments');
				break;

			case 'VAP_DISPLAY_STATES':
				$result = __('Display States', 'vikappointments');
				break;

			case 'VAP_ADD_STATE':
				$result = __('Add State', 'vikappointments');
				break;

			case 'VAP_DISPLAY_CITIES':
				$result = __('Display Cities', 'vikappointments');
				break;

			case 'VAP_ADD_CITY':
				$result = __('Add City', 'vikappointments');
				break;

			case 'VAP_ADD_CUSTOMER':
				$result = __('Add Customer', 'vikappointments');
				break;

			case 'VAP_EDIT_CUSTOMER':
				$result = __('Edit Customer', 'vikappointments');
				break;

			case 'VAP_N_STARS':
				$result = __('%d stars', 'vikappointments');
				break;

			case 'VAP_N_STARS_1':
				$result = __('1 star', 'vikappointments');
				break;

			case 'VAP_N_PEOPLE':
				$result = __('%d people', 'vikappointments');
				break;

			case 'VAP_N_PEOPLE_1':
				$result = __('1 person', 'vikappointments');
				break;

			case 'VAP_N_ORDERS':
				$result = __('%d orders', 'vikappointments');
				break;

			case 'VAP_N_ORDERS_1':
				$result = __('1 order', 'vikappointments');
				break;

			case 'VAP_N_ORDERS_PER_DAY':
				$result = __('%d orders/day', 'vikappointments');
				break;

			case 'VAP_N_ORDERS_PER_DAY_1':
				$result = __('1 order/day', 'vikappointments');
				break;

			case 'VAP_N_ORDERS_PER_MONTH':
				$result = __('%d orders/month', 'vikappointments');
				break;

			case 'VAP_N_ORDERS_PER_MONTH_1':
				$result = __('1 order/month', 'vikappointments');
				break;

			case 'VAP_CURRENCY_PER_DAY':
				$result = __('%s/day', 'vikappointments');
				break;

			case 'VAP_CURRENCY_PER_MONTH':
				$result = __('%s/month', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS':
				$result = __('%d appointments', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS_1':
				$result = __('1 appointment', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS_PER_DAY':
				$result = __('%d app./day', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS_PER_DAY_1':
				$result = __('1 app./day', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS_PER_MONTH':
				$result = __('%d app./month', 'vikappointments');
				break;

			case 'VAP_N_RESERVATIONS_PER_MONTH_1':
				$result = __('1 app./month', 'vikappointments');
				break;

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

			case 'VAP_N_DAYS_LEFT':
				$result = __('%d days left', 'vikappointments');
				break;

			case 'VAP_N_DAYS_LEFT_1':
				$result = __('1 day left', 'vikappointments');
				break;

			case 'VAP_LAST_N_DAYS':
				$result = __('Last %d days', 'vikappointments');
				break;

			case 'VAP_LAST_N_DAYS_1':
				$result = __('Last day', 'vikappointments');
				break;

			case 'VAP_LAST_N_WEEKS':
				$result = __('Last %d weeks', 'vikappointments');
				break;

			case 'VAP_LAST_N_WEEKS_1':
				$result = __('Last week', 'vikappointments');
				break;

			case 'VAP_LAST_N_MONTHS':
				$result = __('Last %d months', 'vikappointments');
				break;

			case 'VAP_LAST_N_MONTHS_1':
				$result = __('Last month', 'vikappointments');
				break;

			case 'VAP_CURR_MONTH':
				$result = __('Current month', 'vikappointments');
				break;

			case 'VAP_LAST_N_YEARS':
				$result = __('Last %d years', 'vikappointments');
				break;

			case 'VAP_LAST_N_YEARS_1':
				$result = __('Last year', 'vikappointments');
				break;

			case 'VAP_CURR_YEAR':
				$result = __('Current year', 'vikappointments');
				break;

			case 'VAP_N_ATTENDEE':
				$result = __('Attendee #%d', 'vikappointments');
				break;


			case 'VAP_PUBL_START_ON':
				$result = __('Starts on %s', 'vikappointments');
				break;

			case 'VAP_PUBL_END_ON':
				$result = __('Ended on %s', 'vikappointments');
				break;

			case 'VAP_FINDRES_EDIT_SUMMARY':
				// @TRANSLATORS: built as "<b>Service Name</b> - <b>Employee Name</b> on <b>Check-in Date Time</b>"
				$result = _x('<b>%s</b> - <b>%s</b> on <b>%s</b>', 'built as "<b>Service Name</b> - <b>Employee Name</b> on <b>Check-in Date Time</b>"', 'vikappointments');
				break;

			case 'VAP_ADD_EXTRA':
				$result = __('Add Extra', 'vikappointments');
				break;

			case 'VAP_ADD_DISCOUNT':
				$result = __('Add Discount', 'vikappointments');
				break;

			case 'VAP_REM_DISCOUNT':
				$result = __('Remove Discount', 'vikappointments');
				break;

			case 'VAP_REM_DISCOUNT_UNDO':
				$result = __('Cancel Discount Removal', 'vikappointments');
				break;

			case 'VAP_DISC_CHANGE_INFO':
				$result = __('Changes will be applied after saving the page.', 'vikappointments');
				break;

			case 'VAP_FILTER_APPOINTMENTS':
				$result = __('Filter Appointments', 'vikappointments');
				break;

			case 'VAP_FILTER_PACKAGES':
				$result = __('Filter Packages', 'vikappointments');
				break;

			case 'VAP_FILTER_ORDERS':
				$result = __('Filter Orders', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE31':
				$result = __('Hide Past', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE32':
				$result = __('Select Services', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE10_DESC':
				$result = __('The specified phone number will be used to receive SMS notifications. If you wish you can display the phone number within the details page of this employee (see <b>Show Phone</b> parameter).', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE24_DESC2':
				$result = __('The URL will be displayed after saving the details of the employee.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE31_DESC':
				$result = __('When this option is active, the working days affecting a date in the past will be no more visible. The changes take effect only after refreshing the page.', 'vikappointments');
				break;

			case 'VAPMANAGEWD8':
				$result = __('Add Time', 'vikappointments');
				break;

			case 'VAPMANAGEWD9':
				$result = __('- special -', 'vikappointments');
				break;

			case 'VAPMANAGEWD_LINK':
				$result = __('This time is defined by the default working days of the employee.', 'vikappointments');
				break;

			case 'VAPMANAGEWD_UNLINK':
				$result = __('This is a custom working time available only for this service.', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION15':
				$result = __('Based on number of participants', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION6_DESC':
				$result = __('Select the number of times that this option can be picked for the same appointment. It is possible to specify a fixed amount or to let the system calculates the maximum quantity according to the number of participants (eg. in case of 4 participants, the option can be selected up to 4 times).', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION4_VAR_HELP':
				$result = __('This price will be added to the base cost of the option.', 'vikappointments');
				break;

			case 'VAP_OWN_N_VARS':
				$result = __('%d variations', 'vikappointments');
				break;

			case 'VAP_OWN_N_VARS_1':
				$result = __('1 variation', 'vikappointments');
				break;

			case 'VAPMENUTITLEANALYTICS':
				$result = __('Analytics', 'vikappointments');
				break;

			case 'VAPMENUFINANCE':
				$result = __('Finance', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSFINANCE':
				$result = __('VikAppointments - Finance Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSAPPOINTMENTS':
				$result = __('VikAppointments - Appointments Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSSERVICES':
				$result = __('VikAppointments - Services Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSEMPLOYEES':
				$result = __('VikAppointments - Employees Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSCUSTOMERS':
				$result = __('VikAppointments - Customers Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSPACKAGES':
				$result = __('VikAppointments - Packages Analytics', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWANALYTICSSUBSCRIPTIONS':
				$result = __('VikAppointments - Subscriptions Analytics', 'vikappointments');
				break;

			case 'VAP_ANALYTICS_CUSTOMERS_PLACEHOLDER':
				$result = __('Select at least a customer', 'vikappointments');
				break;

			case 'VAP_TOOLBAR_NEW_WIDGET':
				$result = __('New Widget', 'vikappointments');
				break;

			case 'VAP_ADD_WIDGET':
				$result = __('Add Widget', 'vikappointments');
				break;

			case 'VAP_EDIT_WIDGET':
				$result = __('Edit Widget', 'vikappointments');
				break;

			case 'VAP_ADD_POSITION':
				$result = __('Add Position', 'vikappointments');
				break;

			case 'VAP_WIDGET_NAME':
				$result = __('Name', 'vikappointments');
				break;

			case 'VAP_WIDGET_NAME_DESC':
				$result = __('Enter an optional name to identify the widget. If not specified, the default widget title will be used.', 'vikappointments');
				break;

			case 'VAP_WIDGET_CLASS':
				$result = __('Widget', 'vikappointments');
				break;

			case 'VAP_WIDGET_CLASS_DESC':
				$result = __('Select the type of widget you wish to display in the analytics view.', 'vikappointments');
				break;

			case 'VAP_WIDGET_SELECT_CLASS':
				$result = __('- Select Widget -', 'vikappointments');
				break;

			case 'VAP_WIDGET_POSITION':
				$result = __('Position', 'vikappointments');
				break;

			case 'VAP_WIDGET_POSITION_DESC':
				$result = __('The position block in which the widget will be displayed.', 'vikappointments');
				break;

			case 'VAP_WIDGET_POSITION_ADD_HELP':
				$result = __('Enter the alias of the position you wish to create. The alias must be unique and can contain only letters, numbers, dashes and underscores.', 'vikappointments');
				break;

			case 'VAP_WIDGET_POSITION_EXISTS_ERR':
				$result = __('The specified position already exists! Please choose a new one.', 'vikappointments');
				break;

			case 'VAP_WIDGET_SELECT_POSITION':
				$result = __('- Select Position -', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE':
				$result = __('Size', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_DESC':
				$result = __('Force a specific size to make the widget wider or shorter. Leave empty to let the widget automatically takes the remaining space.', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_DEFAULT':
				$result = __('- Default -', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_EXTRA_SMALL':
				$result = __('Extra Small', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_SMALL':
				$result = __('Small', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_NORMAL':
				$result = __('Normal', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_LARGE':
				$result = __('Large', 'vikappointments');
				break;

			case 'VAP_WIDGET_SIZE_OPT_EXTRA_LARGE':
				$result = __('Extra Large', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWOPTIONGROUP':
				$result = __('VikAppointments - New Option Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITOPTIONGROUP':
				$result = __('VikAppointments - Edit Option Group', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWOPTIONGROUPS':
				$result = __('VikAppointments - Option Groups', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE38':
				$result = __('Select Employees', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE39':
				$result = __('Select Options', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE40':
				$result = __('Use Default Settings', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE41':
				$result = __('Random Choice', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE40_HELP':
				$result = __('Turn off this option if you wish to use custom settings for the selected employee.', 'vikappointments');
				break;

			case 'VAPMANAGESERVICE41_HELP':
				$result = __('Enable this option to allow the customers to pick an employee only if they wish. Otherwise a random one will be automatically assigned.', 'vikappointments');
				break;

			case 'VAPMAKERECEMPHINT':
				$result = __('This date time is available for a different employee. Select the employee that you wish to assign to the new appointment or leave it empty to skip this date.', 'vikappointments');
				break;

			case 'VAPMAKERECTIMEHINT':
				$result = __('This employee is available at a different date and time. Select the time that you wish to assign to the new appointment or leave it empty to skip this date.', 'vikappointments');
				break;

			case 'VAP_STATUS_CREATED_RECURRENCE':
				$result = __('Created with recurrence from appointment #%d.', 'vikappointments');
				break;

			case 'VAPDEBUGRATESFIELDSET':
				$result = __('Rates Debug', 'vikappointments');
				break;

			case 'VAPSERWORKDAYEDITTITLE':
				$result = __('VikAppointments - Edit Working Day', 'vikappointments');
				break;

			case 'VAPSERWORKDAYNEWTITLE':
				$result = __('VikAppointments - New Working Day', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOCATION16':
				$result = __('Start filling the form to let the system fetches the coordinates of the location. Afterwards you will be able to adjust the coordinates by dragging the marker on the map.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE13':
				$result = __('Discount', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE13_DESC':
				$result = __('The price will be calculated by applying the specified discount to the average price of the assigned services, multiplied by the number of appointments that can be redeemed. Click the <b>Calculate</b> button again to turn this feature off', 'vikappointments');
				break;

			case 'VAPREDEEMEDPACKAGES':
				$result = __('%d/%d redeemed', 'vikappointments');
				break;

			case 'VAPLASTUPDATE':
				$result = __('Last Update: %s', 'vikappointments');
				break;

			case 'VAPMANAGECOUPONFIELDSET2':
				$result = __('Usages', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON22':
				$result = __('Usages per Customer', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON23':
				$result = __('Applicable', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON3_DESC':
				$result = __('<b>Permanent</b> coupon codes don\'t have any restrictions in terms of usages. <b>Gift</b> coupons can be used up to the specified number of times.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON10_DESC':
				$result = __('When enabled, the coupon can be used only whether the check-in is prior then the specified number of hours. By specifying an amount of 24 hours, the coupon will be applicable only for appointments with a check-in no more late than a day.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON13_DESC':
				$result = __('It is possible to specify here the maximum number of times that this coupon can be redeemed. Available only for <b>Gift</b> coupons.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON14_DESC':
				$result = __('Indicates how many times the coupon code have been already used.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON15_DESC':
				$result = __('Enable this option to auto remove the coupon code when the maximum number of usages is reached.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON18_DESC':
				$result = __('Notice that, by picking an employee, the coupon won\'t be applied in case the booked services do not allow the selection of the employee. Leave empty if you wish to apply the coupon independently from the booked employee.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON22_DESC':
				$result = __('It is possible to specify here the maximum number of times that the same user can redeem this coupon. In case of restrictions, only registered users are allowed to use the coupon.', 'vikappointments');
				break;

			case 'VAPMANAGECOUPON23_DESC':
				$result = __('Select for which entity the coupon is applicable. Leave empty to apply the coupon for any shopping type.', 'vikappointments');
				break;

			case 'VAPCOUPONDUPLICATEERR':
				$result = __('The coupon code must be unique! There\'s already another coupon with the same code.', 'vikappointments');
				break;

			case 'VAP_REVIEW_CARD_TITLE':
				$result = __('Review', 'vikappointments');
				break;

			case 'VAPMANAGEREVIEW12':
				$result = __('Record', 'vikappointments');
				break;

			case 'VAPMENUSTATUSCODES':
				$result = __('Status Codes', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWSTATUSCODES':
				$result = __('VikAppointments - Status Codes', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITSTATUSCODE':
				$result = __('VikAppointments - Edit Status Code', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWSTATUSCODE':
				$result = __('VikAppointments - New Status Code', 'vikappointments');
				break;

			case 'VAPSTATUSCODEUNIQUEERR':
				$result = __('The status code must be unique!', 'vikappointments');
				break;

			case 'VAPSTATUSCODECODE_HELP':
				$result = __('The status code accepts only letters (A-Z) and numbers (0-9).', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLES':
				$result = __('Roles', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_APPROVED':
				$result = __('Approved', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_APPROVED_HELP':
				$result = __('Flag as confirmed. Also used by the system to fetch the statistics.', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_RESERVED':
				$result = __('Reserved', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_RESERVED_HELP':
				$result = __('This role can be used to lock the availability.', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_EXPIRED':
				$result = __('Expired', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_EXPIRED_HELP':
				$result = __('Used when the confirmation is not made within the established range of time.', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_CANCELLED':
				$result = __('Cancelled', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_CANCELLED_HELP':
				$result = __('Assigned after a cancellation, usually made after a confirmation.', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_PAID':
				$result = __('Paid', 'vikappointments');
				break;

			case 'VAPSTATUSCODEROLE_PAID_HELP':
				$result = __('Flag as payment done.', 'vikappointments');
				break;

			case 'VAPMENUTAXES':
				$result = __('Taxes', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWTAXES':
				$result = __('VikAppointments - Taxes', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITTAX':
				$result = __('VikAppointments - Edit Tax', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWTAX':
				$result = __('VikAppointments - New Tax', 'vikappointments');
				break;

			case 'VAPTAXFIELDSET':
				$result = __('Tax', 'vikappointments');
				break;

			case 'VAPTAXRULEFIELDSET':
				$result = __('Tax Rules', 'vikappointments');
				break;

			case 'VAPTAXBREAKDOWN':
				$result = __('Tax Breakdown', 'vikappointments');
				break;

			case 'VAPTAXMATHOP':
				$result = __('Math Operation', 'vikappointments');
				break;

			case 'VAPTAXMATHOP_ADD':
				$result = __('+%', 'vikappointments');
				break;

			case 'VAPTAXMATHOP_SUB':
				$result = __('-%', 'vikappointments');
				break;

			case 'VAPTAXMATHOP_VAT':
				$result = __('VAT (included taxes)', 'vikappointments');
				break;

			case 'VAPTAXAPPLY':
				$result = __('Modifier', 'vikappointments');
				break;

			case 'VAPTAXAPPLY_OPT1':
				$result = __('Apply to initial price', 'vikappointments');
				break;

			case 'VAPTAXAPPLY_OPT2':
				$result = __('Apply in sequence to resulting price', 'vikappointments');
				break;

			case 'VAPTAXCAP':
				$result = __('Tax Cap', 'vikappointments');
				break;

			case 'VAPTAXCAP_HELP':
				$result = __('A tax cap places an upper bound on the amount of government tax a company might be required to pay. In this case the tax is said to be capped. This function is only required for some countries, where there is a maximum amount of taxes that cannot be exceeded. Please ignore this setting if nothing similar applies to your country of residence.', 'vikappointments');
				break;

			case 'VATTAXBDLABEL':
				$result = __('Tax Name', 'vikappointments');
				break;

			case 'VATTAXBDPLACEHOLDER':
				$result = __('e.g. Federal taxes', 'vikappointments');
				break;

			case 'VAPTESTTAXES':
				$result = __('Test Taxes', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWUSERNOTES':
				$result = __('VikAppointments - User Notes', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWUSERNOTE':
				$result = __('VikAppointments - New User Note', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITUSERNOTE':
				$result = __('VikAppointments - Edit User Note', 'vikappointments');
				break;

			case 'VAPMODALTITLEUSERNOTES':
				$result = __('Notes of %s', 'vikappointments');
				break;

			case 'VAPNOTESMODALHINT':
				$result = __('Here are displayed the latest 4 modified notes with non-empty comment.', 'vikappointments');
				break;

			case 'VAPNOTESDRAFTPLACEHOLDER':
				$result = __('Type some notes...', 'vikappointments');
				break;

			case 'VAPNOTESDRAFTTITLE':
				$result = __('Quick Notes', 'vikappointments');
				break;

			case 'VAPNOTESDRAFTHELP':
				$result = __('The <b>Quick Notes</b> are automatically saved 3 seconds after the time you stop typing.<br />It is possible to specify a <b>title</b> by leaving an <em>empty space</em> between 2 paragraphs.<br />The title cannot contain more than 128 characters, otherwise the note will be saved without it.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMERTITLE4_HELP':
				$result = __('You will be able to see the saved notes from the notes history of this customer. Every time you open this page, the textarea below will be always empty, giving you the opportunity to quickly enter new notes.', 'vikappointments');
				break;

			case 'VAPUSERNOTEMAILSUBJECT':
				// @TRANSLATORS: the wildcards will be replaced by the company name and by the title of the note
				$result = _x('%s - New Note - %s', 'the wildcards will be replaced by the company name and by the title of the note', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER22':
				$result = __('Expiration Date', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMER22_DESC':
				$result = __('It is possible to manually modify here the expiration date of the customer. As long as the subscription is not expired, this user will be able to book the assigned services for free.', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWTAGS':
				$result = __('VikAppointments - Tags', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWTAG':
				$result = __('VikAppointments - New Tag', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITTAG':
				$result = __('VikAppointments - Edit Tag', 'vikappointments');
				break;

			case 'VAPMANAGETAGS':
				$result = __('Manage Tags', 'vikappointments');
				break;

			case 'VAPTAGHELP':
				$result = __('Tags can be used to easily filter and categorize the user notes.', 'vikappointments');
				break;

			case 'VAPTAGPLACEHOLDER':
				$result = __('Type or select some tags', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITMEDIA':
				$result = __('VikAppointments - Edit Media', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIATITLE4':
				$result = __('Thumbnail', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA12':
				$result = __('Image Size', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA13':
				$result = __('File Size', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA14':
				$result = __('Creation Date', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA15':
				$result = __('Original Height', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA16':
				$result = __('Thumbnail Height', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA17':
				$result = __('Action', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA18':
				$result = __('Size', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA19':
				$result = __('Modification Date', 'vikappointments');
				break;

			case 'VAPMEDIAACTION0':
				$result = __('- None - ', 'vikappointments');
				break;

			case 'VAPMEDIAACTION1':
				$result = __('Replace Image', 'vikappointments');
				break;

			case 'VAPMEDIAACTION2':
				$result = __('Replace Thumb', 'vikappointments');
				break;

			case 'VAPMEDIAACTION3':
				$result = __('Replace Image and Thumb', 'vikappointments');
				break;

			case 'VAPMEDIARENERR':
				$result = __('Impossible to rename the media! The specified name [%s] already exists.', 'vikappointments');
				break;

			case 'VAPMEDIAFIRSTCONFIG':
				$result = __('Every time you upload an image, the system always creates a thumbnail of that image. Before to upload your first image, you should set up the default size that will be used to resize all the future images. Click this message to change the default size.', 'vikappointments');
				break;

			case 'VAPMEDIAFIRSTCONFIG2':
				$result = __('Here you have just to increase/decrease the size of the thumbnail and click the save button to apply the changes. You don\'t need to upload any images here.', 'vikappointments');
				break;

			case 'VAPCONFIGTABNAME6':
				$result = __('Applications', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE1':
				$result = __('API', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE2':
				$result = __('Web Hooks', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE3':
				$result = __('Users', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE4':
				$result = __('Plugins', 'vikappointments');
				break;

			case 'VAPCONFIGSEEAPIUSERS':
				$result = __('See Users List', 'vikappointments');
				break;

			case 'VAPCONFIGSEEAPIPLUGINS':
				$result = __('See Installed Plugins', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWAPIPLUGINS':
				$result = __('VikAppointments - API Plugins', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWAPIUSERS':
				$result = __('VikAppointments - API Users', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWAPIUSER':
				$result = __('VikAppointments - New API User', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITAPIUSER':
				$result = __('VikAppointments - Edit API User', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER2':
				$result = __('Application Name', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER3':
				$result = __('Username', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER5':
				$result = __('Allowed IPs', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER7':
				$result = __('Last Login', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER8':
				$result = __('Application', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER9':
				$result = __('Add IP Address', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER10':
				$result = __('- Never -', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER12':
				$result = __('See Logs', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER16':
				$result = __('See Banned List', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER17':
				$result = __('IP Address', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER18':
				$result = __('Last Update', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER19':
				$result = __('Failure Attempts', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER20':
				$result = __('Banned', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER21':
				$result = __('Plugins Rules', 'vikappointments');
				break;

			case 'VAPMANAGEAPIUSER22':
				$result = __('Payload', 'vikappointments');
				break;

			case 'VAPAPIUSERUSERNAMEEXISTS':
				$result = __('The specified username already exists!', 'vikappointments');
				break;

			case 'VAPAPIUSERUSERNAMEREGEX':
				$result = __('Username must have at least 3 characters and can contain only letters (a-z, A-Z), numbers (0-9), underscores (_) and dots (.)', 'vikappointments');
				break;

			case 'VAPAPIUSERPASSWORDREGEX':
				$result = __('Password must have at least 8 characters, at least 1 number and at least one letter', 'vikappointments');
				break;

			case 'VAPAPIUSEREMPTYIPNOTICE':
				$result = __('Any IP addresses will be accepted.', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWAPILOGS':
				$result = __('VikAppointments - API Logs', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWAPIBANS':
				$result = __('VikAppointments - API Banned List', 'vikappointments');
				break;

			case 'VAPAPIBANOPT1':
				$result = __('Only Banned', 'vikappointments');
				break;

			case 'VAPAPIBANOPT2':
				$result = __('All Records', 'vikappointments');
				break;

			case 'VAPCONFIGSEEWEBHOOKS':
				$result = __('Manage Web Hooks', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWWEBHOOKS':
				$result = __('VikAppointments - Web Hooks', 'vikappointments');
				break;

			case 'VAPMAINTITLEEDITWEBHOOK':
				$result = __('VikAppointments - Edit Web Hook', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWWEBHOOK':
				$result = __('VikAppointments - New Web Hook', 'vikappointments');
				break;

			case 'VAPWEBHOOKACTION':
				$result = __('Action', 'vikappointments');
				break;

			case 'VAPWEBHOOKEVENTNAME':
				$result = __('Event Name', 'vikappointments');
				break;

			case 'VAPWEBHOOKURL':
				$result = __('Delivery URL', 'vikappointments');
				break;

			case 'VAPWEBHOOKSECRET':
				$result = __('Secret Key', 'vikappointments');
				break;

			case 'VAPWEBHOOKLASTCALL':
				$result = __('Last Call', 'vikappointments');
				break;

			case 'VAPWEBHOOKACTION_DESC':
				$result = __('Select the action that will fire the web hook.', 'vikappointments');
				break;

			case 'VAPWEBHOOKURL_DESC':
				$result = __('The end-point URL where the request payload will be delivered.', 'vikappointments');
				break;

			case 'VAPWEBHOOKSECRET_DESC':
				$result = __('An optional secret key to be included within the request headers. When specified, the <code>X-VAP-WEBHOOK-SECURE</code> directive will specify a MD5 hash of the secret key.', 'vikappointments');
				break;

			case 'VAPAPICONFIG1':
				$result = __('Enable Framework', 'vikappointments');
				break;

			case 'VAPAPICONFIG2':
				$result = __('Auto-Flush Logs', 'vikappointments');
				break;

			case 'VAPAPICONFIG2_OPT1':
				$result = __('Every Day', 'vikappointments');
				break;

			case 'VAPAPICONFIG2_OPT2':
				$result = __('Every Week', 'vikappointments');
				break;

			case 'VAPAPICONFIG2_OPT3':
				$result = __('Every Month', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG1':
				$result = __('Max Failure Attempts', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG2':
				$result = __('Group Logs', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG3':
				$result = __('Logs Path', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG4':
				$result = __('Register Logs', 'vikappointments');
				break;

			case 'VAPAPICONFIG1_HELP':
				$result = __('Insert here the maximum number of sequential failures that every user can do. After reaching that amount, the calling IP will be automatically banned.', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG1_HELP':
				$result = __('Insert here the maximum number of sequential failures that every web hook can do. After reaching that amount, the web hook will be automatically unpublished.', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG2_HELP':
				$result = __('Choose how the logs should be grouped.', 'vikappointments');
				break;

			case 'VAPWEBHOOKCONFIG3_HELP':
				$result = __('Insert the folder absolute path where the logs will be stored.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG17':
				$result = __('Listing Mode', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG19':
				$result = __('Visible Months', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG34':
				$result = __('Accepted Codes', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG35':
				$result = __('Add ZIP Code', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG36':
				$result = __('Try Validation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG39':
				$result = __('Load from File', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG42':
				$result = __('User Login', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG46':
				$result = __('Max. Appointments', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG56':
				$result = __('Display From', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG57':
				$result = __('The selected month will be displayed as first only if it isn\'t in the past, otherwise the calendar will always start from the current month.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG62':
				$result = __('Customer Notification', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG71':
				$result = __('Customers', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG72':
				$result = __('Employees', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG73':
				$result = __('Administrator', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG85':
				$result = __('Load Mode', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG88':
				$result = __('Group Filter', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG90':
				$result = __('Ordering Filter', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG91':
				$result = __('Admin Notification', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG92':
				$result = __('Employee Notification', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG93':
				$result = __('Cancellation (admin)', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG95':
				$result = __('Auto-Generation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG98':
				$result = __('Concurrent Check-ins', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG99':
				$result = __('Disabled', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG119':
				$result = __('Use Tax Breakdown', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG120':
				$result = __('Countdown', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG121':
				$result = __('Mandatory Purchase', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG122':
				$result = __('Min Check-in Date', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG123':
				$result = __('Max Check-in Date', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG124':
				$result = __('Layout Type', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG125':
				$result = __('Number of Days', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG126':
				$result = __('Expiration Threshold', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE17':
				$result = __('Date & Time', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE18':
				$result = __('Cancellation', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE19':
				$result = __('Deposit', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE20':
				$result = __('Cart', 'vikappointments');
				break;

			case 'VAPCONFIGBOOKINGNOTIF':
				$result = __('Booking Notifications', 'vikappointments');
				break;

			case 'VAPCONFIGBOOKINGNOTIF_HELP':
				$result = __('Select all the statuses for which you wish to send an e-mail notification. These settings are considered only when the status is changed as a result of an action performed by the customer.', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE4_HELP':
				$result = __('These are the default columns that can be shown within the appointments table.', 'vikappointments');
				break;

			case 'VAPCONFIGGLOBTITLE4_CF_HELP':
				$result = __('Choose whether you wish to display some details, collected through the custom fields, within the appointments table. Custom fields that might show duplicate details (such as the customer e-mail) won\'t be included within this list.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG31_DESC':
				$result = __('Cancellation requests will be accepted with an advance equals to the number of specified days compared to the appointment check-in.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG49_DESC':
				$result = __('Upload and pick a file from here in case you need to include one or more attachments within the e-mail for the customers.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG60_DESC':
				$result = __('In case a description specifies a <b>READ MORE</b> separator, then the system will use the short description without considering the maximum number of characters.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG65_DESC':
				$result = __('While sending the notification e-mail, choose who should receive an ICS file containing the booked appointments as attachment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG66_DESC':
				$result = __('While sending the notification e-mail, choose who should receive a CSV file containing the booked appointments as attachment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG95_DESC':
				$result = __('The invoice will be automatically generated only after a successful payment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG98_DESC':
				$result = __('When disabled, the customers won\'t be allowed to book services with colliding check-in. Enable this option only in case a customer is able to receive different services simultaneously.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG106_DESC':
				$result = __('Choose how many packages the system should display per row in the front-end. The specified amount may be automatically reduced on small devices.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG119_DESC':
				$result = __('Enable this option to display line by line all the applied types of taxes before the total amount.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG120_DESC':
				$result = __('Choose whether to display a countdown to inform the users that they have a limited range of time to confirm/pay their appointments before they get removed.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG121_DESC':
				$result = __('When this option is turned on, the users will be able to book the appointments only if they have an active package to redeem. Users who haven\'t purchased a package (or have already redeemed all the packages) will be prompted to make a purchase first.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG121_DESC2':
				$result = __('When this option is turned on, the users will be able to book the appointments only if they have an active subscription plan. Users who don\'t own an active subscription will be prompted to make a purchase first.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG122_DESC':
				$result = __('The minimum number of days required to complete an appointment in advance. In example, by specifying 1 day, the first available date will be one day after the current one (tomorrow). Leave empty (or zero) to ignore this restriction.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG123_DESC':
				$result = __('The number of days from now on for which it will be possible to book an appointment. In example, by specifying 7 days, it will be possible to select a check-in date between today and the next 7 days. Leave empty (or zero) to ignore this restriction.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG124_DESC':
				$result = __('Choose the type of calendar layout to use within the front-end booking process.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG125_DESC':
				$result = __('The maximum number of days to display simultaneously.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG126_DESC':
				$result = __('Enable this option to prevent the customers from booking an appointment after the expiration date of their subscription. Otherwise the customers will be able to book services for any dates as long as their subscription is active.', 'vikappointments');
				break;

			case 'VAPCONFIGSYMBPOSITION3':
				$result = __('Before Price Without Space', 'vikappointments');
				break;

			case 'VAPCONFIGSYMBPOSITION4':
				$result = __('After Price Without Space', 'vikappointments');
				break;


			case 'VAPMAINTITLEEDITINVOICE':
				$result = __('VikAppointments - Edit Invoice', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWINVOICE':
				$result = __('VikAppointments - New Invoice', 'vikappointments');
				break;

			case 'VAPINVOICELAYOUT':
				$result = __('Layout', 'vikappointments');
				break;

			case 'VAPINVOICECONTENTS':
				$result = __('Contents', 'vikappointments');
				break;

			case 'VAPINVOICEMARGINS':
				$result = __('Margins', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE8':
				$result = __('Overwrite Existing', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE9':
				$result = __('Notify Customers', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT1':
				$result = __('Font Family', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT2':
				$result = __('Body Font Size', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT3':
				$result = __('Show Header', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT4':
				$result = __('Header Title', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT5':
				$result = __('Header Font Size', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT6':
				$result = __('Show Footer', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT7':
				$result = __('Footer Font Size', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT8':
				$result = __('Margin Top', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT9':
				$result = __('Margin Bottom', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT10':
				$result = __('Margin Left', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT11':
				$result = __('Margin Right', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT12':
				$result = __('Margin Header', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT13':
				$result = __('Margin Footer', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE1_DESC':
				$result = __('Insert here the invoices progressive number and an optional suffix. The number will be automatically increased by one every time a NEW invoice is generated. In example, the resulting invoice number will be equals to <em>1/2021</em> or <em>1/XYZ</em>.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE2_DESC':
				$result = __('Choose whether the date of the invoice should be equals to the check-in date, to the booking/purchase date or to the current date.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE3_DESC':
				$result = __('Select here the default type of taxes to use. It is possible to specify different types of taxes from the management page of each single item (services, options, packages and so on).', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE4_DESC':
				$result = __('This field can be used to include some legal information within the invoice. The specified text will be reported below the company logo (top-left side of the first page).', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE7_DESC':
				$result = __('The system will generate an invoice for all the orders that have been created within the selected month and year.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE8_DESC':
				$result = __('Turn on this option to overwrite any existing invoices already generated for the matching order. Leave it unchecked to generate the invoices only for the new orders.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICE9_DESC':
				$result = __('Turn on this option to automatically send the generated invoices to the customers. The invoice will be sent via e-mail to the address specified during the purchase.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICEPROP4_DESC':
				$result = __('All the images within the invoice will be scaled by the specified percentage amount. The higher the value, the smaller the images. Use <em>100%</em> to leave the images at their original sizes.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT4_DESC':
				$result = __('A title to be displayed on the top of the page. In case the text is not visible, it is needed to increase the <b>Margin Top</b> parameter until the title appears.', 'vikappointments');
				break;

			case 'VAPMANAGEINVOICELAYOUT6_DESC':
				$result = __('The footer only displays the number of the page (e.g. 1/2). In case the text is not visible, it is needed to increase the <b>Margin Bottom</b> parameter until the footer appears.', 'vikappointments');
				break;

			case 'VAPINVGENERATEDMSG':
				$result = __('%d invoices generated.', 'vikappointments');
				break;

			case 'VAPINVGENERATEDMSG_1':
				$result = __('Invoice generated.', 'vikappointments');
				break;

			case 'VAPINVMAILSENT':
				$result = __('%d customers notified via mail.', 'vikappointments');
				break;

			case 'VAPINVMAILSENT_1':
				$result = __('Customer notified via mail.', 'vikappointments');
				break;

			case 'VAPNOINVOICESGENERATED':
				$result = __('No invoice generated!', 'vikappointments');
				break;

			case 'VAPINVOICEDIALOG':
				$result = __('Issue Invoices', 'vikappointments');
				break;

			case 'VAPGENERATEINVOICESTXT':
				$result = __('Generate an invoice for the selected orders.<br />In case an order already owns an invoice, it WILL NOT be overwritten.', 'vikappointments');
				break;

			case 'VAPCONFIGTIMEFORMAT3':
				$result = __('12 Hours Without Leading Zero', 'vikappointments');
				break;

			case 'VAPCONFIGTIMEFORMAT4':
				$result = __('24 Hours Without Leading Zero', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP1':
				$result = __('Employee Sign-Up', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGEMP21_DESC':
				$result = __('When this option is disabled, the employees won\'t be able to manually APPROVE a pending appointment.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS9':
				$result = __('Send to Employees', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS10':
				$result = __('Send to Admin', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS2_DESC':
				$result = __('Turn on this option to send automatically a SMS notification every time an appointment is confirmed.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS3_DESC':
				$result = __('Automatically send a notification message to the customers.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS4_DESC':
				$result = __('The phone number of the administrator that will receive the notifications.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS9_DESC':
				$result = __('Automatically send a notification message to the employees.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIGSMS10_DESC':
				$result = __('Automatically send a notification message to the administrator.', 'vikappointments');
				break;

			case 'VAPCUSTOMERSMSSENT':
				$result = __('%d customers notified via SMS.', 'vikappointments');
				break;

			case 'VAPCUSTOMERSMSSENT_1':
				$result = __('Customer notified via SMS.', 'vikappointments');
				break;

			case 'VAPCUSTOMERSMSSENT_0':
				$result = __('No customers have been notified via SMS.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF14':
				$result = __('Repeatable', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF14_DESC':
				$result = __('Enable this option to repeat the custom fields for each attendee of the appointment.', 'vikappointments');
				break;

			case 'VAPUSEEDITOR_DESC':
				$result = __('Enable this option to display a WYSIWYG (What You See Is What You Get) editor instead of a plain textarea. Applied only in case the custom field belongs to the employees group.', 'vikappointments');
				break;

			case 'VAPCUSTOMFFILEFILTER':
				$result = __('Allowed Files', 'vikappointments');
				break;

			case 'VAPCUSTOMFFILEFILTER_HELP':
				$result = __('Insert here all the allowed file extensions, separated by a comma.', 'vikappointments');
				break;

			case 'VAPCUSTOMFFORMNAMEERR':
				$result = __('There\'s already another field using the specified form name! Please choose a new one.', 'vikappointments');
				break;

			case 'VAPCUSTOMFLANGHELP':
				$result = __('Choose for which language the custom field should be visible. Do NOT use this parameter to apply fields translations. For this purpose, use the apposite multilingual feature instead.', 'vikappointments');
				break;

			case 'VAPCUSTMAILTITLE1':
				$result = __('E-mail Details', 'vikappointments');
				break;

			case 'VAPCUSTMAILTITLE2':
				$result = __('E-mail Content', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL3_DESC':
				$result = __('Select the position of the e-mail template in which the text will be placed. The e-mail templates support 4 pre-installed positions, which can be seen and moved by editing the code of the template.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL4_DESC':
				$result = __('Select the status for which the e-mail content will be included. Leave empty to apply the content for any statuses.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL5_DESC':
				$result = __('Select the template file in which the e-mail content will be included. Leave empty to apply the content to any templates.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL_SER_DESC':
				$result = __('Select the service for which the e-mail content will be included. Leave empty to apply the content to any services.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL_EMP_DESC':
				$result = __('Select the employee for which the e-mail content will be included. Leave empty to apply the content to any employees.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_OFFCC_NEWSTATUS':
				$result = __('Order Status', 'vikappointments');
				break;

			case 'VAP_PAYMENT_OFFCC_NEWSTATUS_HELP':
				$result = __('Use PENDING in case you want to manually verify the credit card. Otherwise the order will be automatically confirmed after submitting the credit card details.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_OFFCC_USESSL':
				$result = __('Use SSL', 'vikappointments');
				break;

			case 'VAP_PAYMENT_OFFCC_BRANDS':
				$result = __('Accepted Brands', 'vikappointments');
				break;

			case 'VAP_PAYMENT_OFFCC_BRANDS_HELP':
				$result = __('Select all the credit card brands that you are able to charge. Leave empty to accept any brands.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_ACCOUNT':
				$result = __('PayPal Account', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_ACCOUNT_HELP':
				$result = __('Only the <b>e-mail address</b> assigned to the PayPal account should be typed here. DO NOT specify the PayPal <b>merchant account</b>, otherwise an error will occur while validating the transaction.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_SANDBOX':
				$result = __('Test Mode', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_SANDBOX_HELP':
				$result = __('When enabled, the PayPal <b>SANDBOX</b> will be used. Turn OFF this option to collect <b>PRODUCTION</b> payments.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_SSL':
				$result = __('Safe Connection', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_SSL_HELP':
				$result = __('When enabled, the connection to PayPal will be established only through the TLS 1.2 protocol.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_IMAGE':
				$result = __('Image URL', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_IMAGE_HELP':
				$result = __('The image URL that will be used to display the "Pay Now" button. Leave empty to use the default one.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_AUTO_SUBMIT':
				$result = __('Auto-Submit', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_AUTO_SUBMIT_HELP':
				$result = __('Enable this option to auto-submit the payment form when reaching the summary page.', 'vikappointments');
				break;

			case 'VAP_EXPORT_RAW':
				$result = __('Raw', 'vikappointments');
				break;

			case 'VAP_EXPORT_RAW_DESC':
				$result = __('Enable this option to export the columns of the records with their original values. Otherwise leave it off to properly format the columns (in example, the ID of a service will be replaced by its name).', 'vikappointments');
				break;

			case 'VAP_DELETE_PERMANENTLY':
				$result = __('Delete Permanently', 'vikappointments');
				break;

			case 'VAP_SELECT_USE_DEFAULT':
				$result = __('- Use Default -', 'vikappointments');
				break;

			case 'VAP_SELECT_USE_DEFAULT_X':
				$result = __('- Use Default (%s) -', 'vikappointments');
				break;

			case 'VAP_EDIT_SORT_DRAG_DROP':
				$result = __('Drag&drop the elements to change their ordering.', 'vikappointments');
				break;

			case 'VAP_TRX_LIST_TITLE':
				$result = __('VikAppointments - Translations', 'vikappointments');
				break;

			case 'VAP_TRX_EDIT_TITLE':
				$result = __('VikAppointments - Edit Translation', 'vikappointments');
				break;

			case 'VAP_TRX_NEW_TITLE':
				$result = __('VikAppointments - New Translation', 'vikappointments');
				break;

			case 'VAP_SAVE_TRX_DEF_LANG':
				$result = __('You are saving a translation for the default language of this website (%s). Do you want to proceed?', 'vikappointments');
				break;

			case 'VAP_AJAX_GENERIC_ERROR':
				$result = __('An error occurred! Please try again.', 'vikappointments');
				break;

			case 'VAP_USER_SAVE_BIND_ERR':
				$result = __('An error occurred while trying to save the user. Please, try again.', 'vikappointments');
				break;

			case 'VAP_USER_SAVE_CHECK_ERR':
				$result = __('An error occurred. The specified fields are not valid.', 'vikappointments');
				break;

			case 'VAP_MISSING_REQ_FIELD':
				$result = __('Missing required field "%s".', 'vikappointments');
				break;

			case 'VAP_INVALID_REQ_FIELD':
				$result = __('Invalid field "%s".', 'vikappointments');
				break;

			case 'VAP_CONFIRM_MESSAGE_UNSAVE':
				$result = __('Your changes will be lost if you don\'t save them. Do you want to proceed?', 'vikappointments');
				break;

			case 'VAP_MULTIORDER_EDITSERVICE_DISABLED':
				$result = __('It is not possible to edit the services from here. Access the details page of the single appointment if you wish to change something.', 'vikappointments');
				break;

			case 'VAPCONFIGUPLOADERROR':
				$result = __('Error while uploading the file', 'vikappointments');
				break;

			case 'VAPCONFIGFILETYPEERROR':
				$result = __('The selected file is not supported', 'vikappointments');
				break;

			case 'VAPCONFIGFILETYPEERRORWHO':
				$result = __('The selected file is not supported (%s)', 'vikappointments');
				break;

			case 'VAP_ORDER_EXPORT_DRIVER_ICS':
				$result = __('ICS - iCalendar', 'vikappointments');
				break;

			case 'VAP_ORDER_EXPORT_DRIVER_ICS_DESC':
				$result = __('The Internet Calendaring and Scheduling Core Object Specification (iCalendar) is a MIME type which allows users to store and exchange calendaring and scheduling information such as events, to-dos, journal entries, and free/busy information.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_PAST_DATES_FIELD':
				$result = __('Include Past Events', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_PAST_DATES_FIELD_HELP':
				$result = __('Enable this option if you wish to keep all the existing appointments/orders within the calendar. When disabled, reservations older than the current month won\'t be included.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_SUBJECT_FIELD':
				$result = __('Subject', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_SUBJECT_FIELD_HELP':
				$result = __('An optional subject to use instead of the default one. It is possible to include one of the following placeholders: {customer}, {service} and {people}.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD':
				$result = __('Reminder', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD_HELP':
				$result = __('The minutes in advance since the event date time for which the alert will be triggered.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_NONE':
				$result = __('None', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_EVENT_TIME':
				$result = __('At the event time', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_MIN':
				$result = __('%d minutes before', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_HOURS':
				$result = __('%d hours before', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_HOURS_1':
				$result = __('1 hour before', 'vikappointments');
				break;

			case 'VAP_ORDER_EXPORT_DRIVER_CSV_DESC':
				$result = __('CSV is a common data exchange format that is widely supported by consumer, business, and scientific applications.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_CONFIRMED_STATUS_FIELD':
				$result = __('Confirmed Status', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_CONFIRMED_STATUS_FIELD_HELP':
				$result = __('Enable this option if you want to download only the appointments/orders that has been CONFIRMED.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_USE_ITEMS_FIELD':
				$result = __('Use Items', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_USE_ITEMS_FIELD_HELP':
				$result = __('Enable this option if you wish to include the extra options/itema assigned to the appointments/orders within the CSV.', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD':
				$result = __('Delimiter', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_HELP':
				$result = __('The selected character will be used as delimiter between the CSV fields. It is possible to use a comma (,) or a semicolon (;).', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_OPT_COMMA':
				$result = __('Comma', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_DELIMITER_FIELD_OPT_SEMICOLON':
				$result = __('Semicolon', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD':
				$result = __('Enclosure', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_HELP':
				$result = __('The selected character will be used as enclosure to wrap the separated fields. It is possible to use a double quote (") or a single quote (\').', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_OPT_DOUBLE_QUOTE':
				$result = __('Double Quote', 'vikappointments');
				break;

			case 'VAP_EXPORT_DRIVER_CSV_ENCLOSURE_FIELD_OPT_SINGLE_QUOTE':
				$result = __('Single Quote', 'vikappointments');
				break;

			case 'VAP_ORDER_EXPORT_DRIVER_EXCEL':
				$result = __('Microsoft Excel', 'vikappointments');
				break;

			case 'VAP_ORDER_EXPORT_DRIVER_EXCEL_DESC':
				$result = __('Exports the rows in a (non-standard) CSV format compatible with Microsoft Excel.', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_RESERVATION':
				$result = __('Appointment - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_RESERVATION':
				$result = __('Appointment - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_STATUSCHANGE_RESERVATION':
				$result = __('Appointment - Status Change', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_PACKORDER':
				$result = __('Packages Order - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_PACKORDER':
				$result = __('Packages Order - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_STATUSCHANGE_PACKORDER':
				$result = __('Packages Order - Status Change', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_CUSTOMER':
				$result = __('Customer - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_CUSTOMER':
				$result = __('Customer - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_EMPLOYEE':
				$result = __('Employee - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_EMPLOYEE':
				$result = __('Employee - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_SERVICE':
				$result = __('Service - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_SERVICE':
				$result = __('Service - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVE_COUPON':
				$result = __('Coupon - Save', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_DELETE_COUPON':
				$result = __('Coupon - Delete', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVETYPE_PARAM':
				$result = __('Type', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVETYPE_PARAM_HELP':
				$result = __('Choose whether the web hook should be dispatched only for created records, updated records or both.', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVETYPE_PARAM_INSERT':
				$result = __('Insert', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVETYPE_PARAM_UPDATE':
				$result = __('Update', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_SAVETYPE_PARAM_BOTH':
				$result = __('Both', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_LOAD_PARAM':
				$result = __('Loading Mode', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_LOAD_PARAM_HELP':
				$result = __('Choose how the system should load the details of the payload. Use <b>Basic</b> to send only the details that have been saved. Use <b>Full</b> to send the whole database table row.', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_LOAD_PARAM_BASIC':
				$result = __('Basic', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_LOAD_PARAM_FULL':
				$result = __('Full', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_LOAD_PARAM_EXTENDED':
				$result = __('Extended', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_STATUSES_PARAM':
				$result = __('Statuses', 'vikappointments');
				break;

			case 'VAP_WEBHOOK_STATUSES_PARAM_HELP':
				$result = __('The web hook will be dispatched only in case the status changed is contained within this list. Leave empty to observe all the statuses.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_MAIL_REMINDER_TITLE':
				$result = __('Appointment Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends an appointment reminder via e-mail to the customer.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SMS_REMINDER_TITLE':
				$result = __('Appointment Reminder (SMS)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SMS_REMINDER_DESCRIPTION':
				$result = __('Sends an appointment reminder via SMS to the customer.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_ADMIN_MAIL_REMINDER_TITLE':
				$result = __('Administrator Appointment Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_ADMIN_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends an appointment reminder via e-mail to the administrator.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_EMPLOYEE_MAIL_REMINDER_TITLE':
				$result = __('Employee Appointment Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_EMPLOYEE_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends an appointment reminder via e-mail to the employee.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_EMPLOYEE_REVIEW_MAIL_REMINDER_TITLE':
				$result = __('Employee Review Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_EMPLOYEE_REVIEW_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends a notification via e-mail to the customer to ask to leave an employee review.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SERVICE_REVIEW_MAIL_REMINDER_TITLE':
				$result = __('Service Review Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SERVICE_REVIEW_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends a notification via e-mail to the customer to ask to leave a service review.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_EMPLOYEE_SUBSCRIPTION_MAIL_REMINDER_TITLE':
				$result = __('Employee Subscription Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_EMPLOYEE_SUBSCRIPTION_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends a notification via e-mail to the employee about the expiration of the subscription.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SUBSCRIPTION_MAIL_REMINDER_TITLE':
				$result = __('Customer Subscription Reminder (E-Mail)', 'vikappointments');
				break;

			case 'VAP_CRONJOB_CUSTOMER_SUBSCRIPTION_MAIL_REMINDER_DESCRIPTION':
				$result = __('Sends a notification via e-mail to the customer about the expiration of the subscription.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_ADVANCE_MIN':
				$result = __('Send in Advance', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_ADVANCE_MIN_HELP':
				$result = __('A reminder notification will be sent to the customer N minutes before the check-in of the appointment.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_ADVANCE_DAYS':
				$result = __('Notify Before', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_ADVANCE_DAYS_HELP':
				$result = __('A reminder notification will be sent to the customer N days before the expiration of the subscription.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_AFTER_DAYS':
				$result = __('Send After', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SEND_AFTER_DAYS_HELP':
				$result = __('A reminder notification will be sent to the customer N days after the check-in of the appointment.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SUBJECT':
				$result = __('Subject', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_CONTENT':
				$result = __('Content', 'vikappointments');
				break;

			case 'VAP_CRONJOB_SETTING_SERVICES_HELP':
				$result = __('Leave empty to automatically include all the existing services.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_CHART_TITLE':
				$result = __('Finance - Revenue Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the whole revenue of the company, inclusive of appointments, packages and subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_TABLE_TITLE':
				$result = __('Finance - Revenue Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_TABLE_DESC':
				$result = __('Displays a table containing the whole revenue of the company, inclusive of appointments, packages and subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_AVG_TITLE':
				$result = __('Finance - Average Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_REVENUE_AVG_DESC':
				$result = __('Calculates the daily/monthly average amount of both the total earning and the total number of received orders.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_OVERALL_TITLE':
				$result = __('Finance - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_OVERALL_DESC':
				$result = __('Calculates either the overall total earning and the total number of received orders between the given range of dates.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_ROG_TITLE':
				$result = __('Finance - Rate of Growth', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_ROG_DESC':
				$result = __('Calculates the rate of growth between the first selected month and the second one.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_ROG_PROP_FIELD':
				$result = __('Proportional', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_ROG_PROP_FIELD_HELP':
				$result = __('When enabled, the total earning of the month will be proportionally estimated depending on the money already earned and the remaining days (applies only for the current month).', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_PAYMENTS_TABLE_TITLE':
				$result = __('Finance - Payment Methods', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_PAYMENTS_TABLE_DESC':
				$result = __('Displays a table containing the total revenue received for each supported payment method.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_PAYMENTS_CHART_TITLE':
				$result = __('Finance - Payments Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_PAYMENTS_CHART_DESC':
				$result = __('Draws a chart containing the trend of all the supported methods of payment.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_COUPONS_TABLE_TITLE':
				$result = __('Finance - Coupons', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_COUPONS_TABLE_DESC':
				$result = __('Displays a table containing the total discounts offered by your coupon codes.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_COUPONS_CHART_TITLE':
				$result = __('Finance - Coupons Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_FINANCE_COUPONS_CHART_DESC':
				$result = __('Draws a chart containing the trend of all the supported coupon codes.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_CHART_TITLE':
				$result = __('Appointments - Revenue Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the revenue coming from the appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_TABLE_TITLE':
				$result = __('Appointments - Revenue Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_TABLE_DESC':
				$result = __('Displays a table containing the whole revenue coming from the appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_AVG_TITLE':
				$result = __('Appointments - Average Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_REVENUE_AVG_DESC':
				$result = __('Calculates the daily/monthly average amount of both the total earning and the total number of received appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_OVERALL_TITLE':
				$result = __('Appointments - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_OVERALL_DESC':
				$result = __('Calculates either the overall total earning and the total number of received appointments between the given range of dates.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_ROG_TITLE':
				$result = __('Appointments - Rate of Growth', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_ROG_PROP_FIELD_HELP':
				$result = __('When enabled, the total earning/count of the month will be proportionally estimated depending on the money/appointments already received and the remaining days (applies only for the current month).', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_STATUS_COUNT_TITLE':
				$result = __('Appointments - Statuses Count', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_STATUS_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total count of appointments for each supported status.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_TIMES_CHART_TITLE':
				$result = __('Appointments - Booked Times', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_TIMES_CHART_DESC':
				$result = __('Draws a chart containing the most booked hours.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_CUSTOMERS_CHART_TITLE':
				$result = __('Appointments - New Customers', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_CUSTOMERS_CHART_DESC':
				$result = __('Draws a chart containing the trend of new customers that book an appointment for the first time.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_PAYMENTS_TABLE_TITLE':
				$result = __('Appointments - Payment Methods', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_PAYMENTS_CHART_TITLE':
				$result = __('Appointments - Payments Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_COUPONS_TABLE_TITLE':
				$result = __('Appointments - Coupons', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_APPOINTMENTS_COUPONS_CHART_TITLE':
				$result = __('Appointments - Coupons Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_CHART_TITLE':
				$result = __('Packages - Revenue Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the revenue coming from the packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_TABLE_TITLE':
				$result = __('Packages - Revenue Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_TABLE_DESC':
				$result = __('Displays a table containing the whole revenue coming from the packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_AVG_TITLE':
				$result = __('Packages - Average Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_REVENUE_AVG_DESC':
				$result = __('Calculates the daily/monthly average amount of both the total earning and the total number of received packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_OVERALL_TITLE':
				$result = __('Packages - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_OVERALL_DESC':
				$result = __('Calculates either the overall total earning and the total number of received packages between the given range of dates.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ROG_TITLE':
				$result = __('Packages - Rate of Growth', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ROG_PROP_FIELD_HELP':
				$result = __('When enabled, the total earning/count of the month will be proportionally estimated depending on the money/packages already received and the remaining days (applies only for the current month).', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_STATUS_COUNT_TITLE':
				$result = __('Packages - Statuses Count', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_STATUS_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total count of packages for each supported status.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ITEMS_CHART_TITLE':
				$result = __('Packages - Items Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ITEMS_CHART_DESC':
				$result = __('Draws a chart containing the total revenue/quantity for each supported package.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ITEMS_CHART_GROUP_FIELD_HELP':
				$result = __('Select a group to automatically take all the assigned packages. The resulting packages will be merged to the ones selected from the apposite field.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ITEMS_COUNT_TITLE':
				$result = __('Packages - Items Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_ITEMS_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total quantity, of the selected packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_PAYMENTS_TABLE_TITLE':
				$result = __('Packages - Payment Methods', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_PAYMENTS_CHART_TITLE':
				$result = __('Packages - Payments Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_COUPONS_TABLE_TITLE':
				$result = __('Packages - Coupons', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_PACKAGES_COUPONS_CHART_TITLE':
				$result = __('Packages - Coupons Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_CHART_TITLE':
				$result = __('Subscriptions - Revenue Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the revenue coming from the subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_TABLE_TITLE':
				$result = __('Subscriptions - Revenue Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_TABLE_DESC':
				$result = __('Displays a table containing the whole revenue coming from the subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_AVG_TITLE':
				$result = __('Subscriptions - Average Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_REVENUE_AVG_DESC':
				$result = __('Calculates the daily/monthly average amount of both the total earning and the total number of received subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_OVERALL_TITLE':
				$result = __('Subscriptions - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_OVERALL_DESC':
				$result = __('Calculates either the overall total earning and the total number of received subscriptions between the given range of dates.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ROG_TITLE':
				$result = __('Subscriptions - Rate of Growth', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ROG_PROP_FIELD_HELP':
				$result = __('When enabled, the total earning/count of the month will be proportionally estimated depending on the money/subscriptions already received and the remaining days (applies only for the current month).', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_STATUS_COUNT_TITLE':
				$result = __('Subscriptions - Statuses Count', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_STATUS_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total count of subscriptions for each supported status.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ITEMS_CHART_TITLE':
				$result = __('Subscriptions - Items Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ITEMS_CHART_DESC':
				$result = __('Draws a chart containing the total revenue/quantity for each supported subscription.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ITEMS_COUNT_TITLE':
				$result = __('Subscriptions - Items Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_ITEMS_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total quantity, of the selected subscriptions.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_PAYMENTS_TABLE_TITLE':
				$result = __('Subscriptions - Payment Methods', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_PAYMENTS_CHART_TITLE':
				$result = __('Subscriptions - Payments Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_COUPONS_TABLE_TITLE':
				$result = __('Subscriptions - Coupons', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SUBSCRIPTIONS_COUPONS_CHART_TITLE':
				$result = __('Subscriptions - Coupons Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_REVENUE_CHART_TITLE':
				$result = __('Services - Trend Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the trend of all the supported services.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_REVENUE_CHART_GROUP_FIELD_HELP':
				$result = __('Select a group to automatically take all the assigned services. The resulting services will be merged to the ones selected from the apposite field.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_REVENUE_COUNT_TITLE':
				$result = __('Services - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_REVENUE_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total count of appointments, for each selected service.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_EMPLOYEES_CHART_TITLE':
				$result = __('Services - Employees Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_EMPLOYEES_CHART_DESC':
				$result = __('Draws a chart containing the revenue trend of all the employees assigned to the selected service.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_EMPLOYEES_COUNT_TITLE':
				$result = __('Services - Employees Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_SERVICES_EMPLOYEES_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total count of appointments, of the employees assigned to the selected service.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_REVENUE_CHART_TITLE':
				$result = __('Employees - Trend Chart', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_REVENUE_CHART_DESC':
				$result = __('Draws a chart containing the trend of all the supported employees.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_REVENUE_CHART_GROUP_FIELD_HELP':
				$result = __('Select a group to automatically take all the assigned employees. The resulting employees will be merged to the ones selected from the apposite field.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_REVENUE_COUNT_TITLE':
				$result = __('Employees - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_REVENUE_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total count of appointments, for each selected employee.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_SERVICES_CHART_TITLE':
				$result = __('Employees - Services Revenue', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_SERVICES_CHART_DESC':
				$result = __('Draws a chart containing the revenue trend of all the services assigned to the selected employee.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_SERVICES_COUNT_TITLE':
				$result = __('Employees - Services Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_EMPLOYEES_SERVICES_COUNT_DESC':
				$result = __('Displays a doughnut chart containing the total revenue, or the total count of appointments, of the services assigned to the selected employee.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_APPOINTMENTS_CHART_TITLE':
				$result = __('Customers - Appointments Trend', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_APPOINTMENTS_CHART_DESC':
				$result = __('Draws a chart containing the trend of the appointments booked by the selected customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_APPOINTMENTS_WEEKDAYS_TITLE':
				$result = __('Customers - Weekdays Count', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_APPOINTMENTS_WEEKDAYS_DESC':
				$result = __('Draws a chart containing the most booked days of the week by the selected customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_STATUS_COUNT_TITLE':
				$result = __('Customers - Statuses Count', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_STATUS_COUNT_DESC':
				$result = __('Displays a pie chart containing the total count of statuses for each selected customer.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_PREFERRED_SERVICES_TITLE':
				$result = __('Customers - Preferred Services', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_PREFERRED_SERVICES_DESC':
				$result = __('Displays a table containing the preferred services booked by the selected customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_PREFERRED_EMPLOYEES_TITLE':
				$result = __('Customers - Preferred Employees', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_PREFERRED_EMPLOYEES_DESC':
				$result = __('Displays a table containing the preferred employees booked by the selected customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_OVERALL_TITLE':
				$result = __('Customers - Overall', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_CUSTOMERS_OVERALL_DESC':
				$result = __('Displays a pie chart to compare the revenue coming from the selected customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_TITLE':
				$result = __('Dashboard - Appointments Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_DESC':
				$result = __('Displays a widget containing the latest registered appointments and the next incoming ones.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_LATEST_FIELD':
				$result = __('Latest', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_LATEST_FIELD_HELP':
				$result = __('Turn on this option to display the list of the latest registered appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_INCOMING_FIELD':
				$result = __('Incoming', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_INCOMING_FIELD_HELP':
				$result = __('Turn on this option to display the list of the next incoming appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_CURRENT_FIELD':
				$result = __('Current', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_TABLE_CURRENT_FIELD_HELP':
				$result = __('Turn on this option to display the list of the currently performed appointments.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_TITLE':
				$result = __('Dashboard - Waiting List', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_DESC':
				$result = __('Displays a widget containing the latest customers that subscribed into a waiting list and the next incoming ones.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_LATEST_FIELD':
				$result = __('Latest', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_LATEST_FIELD_HELP':
				$result = __('Turn on this option to display the list of the latest subscribed customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_INCOMING_FIELD':
				$result = __('Incoming', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_WAITLIST_TABLE_INCOMING_FIELD_HELP':
				$result = __('Turn on this option to display the list of the next incoming customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_TITLE':
				$result = __('Dashboard - Users Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_DESC':
				$result = __('Displays a widget containing the following lists: latest registered customers and currently logged in users.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_LATEST_FIELD':
				$result = __('Latest', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_LATEST_FIELD_HELP':
				$result = __('Turn on this option to display the list of the latest registered customers.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_CURRENT_FIELD':
				$result = __('Current', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_USERS_TABLE_CURRENT_FIELD_HELP':
				$result = __('Turn on this option to display the list of the currently logged in users.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_TITLE':
				$result = __('Dashboard - Packages Table', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_DESC':
				$result = __('Displays a widget containing the following lists for the packages: latest purchased and latest redeemed.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_PURCHASED_FIELD':
				$result = __('Latest Purchased', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_PURCHASED_FIELD_HELP':
				$result = __('Turn on this option to display the list of the latest purchased packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_REDEEMED_FIELD':
				$result = __('Latest Used', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_PACKAGES_TABLE_REDEEMED_FIELD_HELP':
				$result = __('Turn on this option to display the list of the latest redeemed packages.', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_CALENDAR_TITLE':
				$result = __('Dashboard - Daily Calendar', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_CALENDAR_DESC':
				$result = __('Displays a calendar containing all the appointments booked for a selected date (the current one by default).', 'vikappointments');
				break;

			case 'VAP_STATS_WIDGET_DASHBOARD_APPOINTMENTS_DRAG_ERR':
				$result = __('The selected time is not available.', 'vikappointments');
				break;

			case 'VAP_CHART_INITIAL_RANGE_FIELD':
				$result = __('Initial Range', 'vikappointments');
				break;

			case 'VAP_CHART_INITIAL_RANGE_FIELD_HELP':
				$result = __('Every time you start a new session, the widget will take the selected range as reference to load the statistics.', 'vikappointments');
				break;

			case 'VAP_CHART_TYPE':
				$result = __('Chart', 'vikappointments');
				break;

			case 'VAP_CHART_TYPE_LINE':
				$result = __('Line', 'vikappointments');
				break;

			case 'VAP_CHART_TYPE_BAR':
				$result = __('Bar', 'vikappointments');
				break;

			case 'VAP_CHART_TYPE_RADAR':
				$result = __('Radar', 'vikappointments');
				break;

			case 'VAP_CHART_VALUE_TYPE_FIELD':
				$result = __('Value Type', 'vikappointments');
				break;

			case 'VAP_CHART_VALUE_TYPE_FIELD_HELP':
				$result = __('Choose whether the chart should calculate the total count of received orders or the total earned amount.', 'vikappointments');
				break;

			case 'VAP_CHART_GROUPBY_FIELD':
				$result = __('Group by', 'vikappointments');
				break;

			case 'VAPREPORTSVALUETYPEOPT4':
				$result = __('Orders Count', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.1 - administrator - VikWP.com
			 */

			case 'VAPUPLOAD':
				$result = __('Upload', 'vikappointments');
				break;
				
			case 'VAPSAMPLE':
				$result = __('Example', 'vikappointments');
				break;
				
			case 'VAPFILEDRAGDROP':
				$result = __('or DRAG FILE HERE', 'vikappointments');
				break;
				
			case 'VAP_WORKTIME_IMPORT_DISABLED_WHY':
				$result = __('A working time for the same date already exists and it is not possible to overwrite it.', 'vikappointments');
				break;
				
			case 'VAP_WORKTIME_IMPORT_SAMPLE_TIP':
				$result = __('Select a type to see how the import file should be built.', 'vikappointments');
				break;
				
			case 'VAP_MANUAL_DISCOUNT_PROMPT':
				$result = __('Enter the discount to apply. Append a "%" at the end to apply a percentage discount.', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT16':
				$result = __('Trusted Customer', 'vikappointments');
				break;

			case 'VAPMANAGEPAYMENT16_DESC':
				$result = __('When this option is enabled, the payment gateway will be accessible only by those customers with a count of appointments/orders equals or higher than the specified amount.', 'vikappointments');
				break;				

			case 'VAP_STATUS_CODES_FACTORY_RESET_CONFIRM':
				$result = __('Do you want to proceed with the reset? If you confirm, the status codes will be restored to the factory settings.', 'vikappointments');
				break;

			case 'VAPMAINTITLEVIEWBACKUPS':
				$result = __('VikAppointments - Backups Archive', 'vikappointments');
				break;

			case 'VAPMAINTITLENEWBACKUP':
				$result = __('VikAppointments - New Backup', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE5':
				$result = __('Backup', 'vikappointments');
				break;

			case 'VAPBACKUPCONFIG1':
				$result = __('Export Type', 'vikappointments');
				break;

			case 'VAPBACKUPCONFIG2':
				$result = __('Folder Path', 'vikappointments');
				break;

			case 'VAPBACKUPCONFIG2_HELP':
				$result = __('Enter here the path used to store the backup archives created by VikAppointments. In case the folder does not exist, the system will attempt to create it.', 'vikappointments');
				break;

			case 'VAPBACKUPCONFIG2_WARN':
				$result = __('It is not safe to use the default temporary folder provided by the system. It is recommended to change it or to use a nested folder with an unpredictable name, such as: %s', 'vikappointments');
				break;

			case 'VAPCONFIGSEEBACKUP':
				$result = __('Manage Backups', 'vikappointments');
				break;

			case 'VAPBACKUPACTIONCREATE':
				$result = __('Create New', 'vikappointments');
				break;

			case 'VAPBACKUPACTIONUPLOAD':
				$result = __('Upload Existing', 'vikappointments');
				break;

			case 'VAPBACKUPDRAGDROP':
				$result = __('or DRAG ARCHIVE HERE', 'vikappointments');
				break;

			case 'VAPBACKUPRESTORED':
				$result = __('The backup has been restored successfully!', 'vikappointments');
				break;

			case 'VAPBACKUPRESTORECONF1':
				$result = __('Do you want to restore the program data with the selected backup?', 'vikappointments');
				break;

			case 'VAPBACKUPRESTORECONF2':
				$result = __('Confirm that you want to proceed one last time. This action cannot be undone.', 'vikappointments');
				break;

			case 'VAP_BACKUP_EXPORT_TYPE_FULL':
				$result = __('Full', 'vikappointments');
				break;

			case 'VAP_BACKUP_EXPORT_TYPE_FULL_DESCRIPTION':
				$result = __('The backup will export all the contents created through VikAppointments.', 'vikappointments');
				break;

			case 'VAP_BACKUP_EXPORT_TYPE_MANAGEMENT':
				$result = __('Management', 'vikappointments');
				break;

			case 'VAP_BACKUP_EXPORT_TYPE_MANAGEMENT_DESCRIPTION':
				$result = __('The backup will export only the contents used to set up the program. The records related to the customers, such as the appointments, will be completely ignored. This is useful to copy the configuration of this website into a new one.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_BACKUP_CREATOR_TITLE':
				$result = __('Backup Creator', 'vikappointments');
				break;

			case 'VAP_CRONJOB_BACKUP_CREATOR_DESCRIPTION':
				$result = __('Periodically creates a backup of the contents created through VikAppointments.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_BACKUP_CREATOR_INFO':
				$result = __('The backup will be created according to the settings specified from the configuration.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_BACKUP_CREATOR_FIELD_MAX':
				$result = __('Maximum Archives', 'vikappointments');
				break;

			case 'VAP_CRONJOB_BACKUP_CREATOR_FIELD_MAX_DESC':
				$result = __('Choose the maximum number of backup archives that can be created. When the specified threshold is reached, the system will automatically delete the oldest backup to allow the creation of a new one.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG127':
				$result = __('Self-Confirmation', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG127_DESC':
				$result = __('Enable this option to allow the customers to self-confirm their appointments through the link received via e-mail. The self-confirmation is used only in case the booking process doesn\'t require a payment.', 'vikappointments');
					break;

			case 'VAPMANAGECONFIG127_DESC2':
				$result = __('Enable this option to allow the customers to self-confirm their appointments through the link received via e-mail.', 'vikappointments');
				break;

			case 'VAPWIZARDWHAT':
				$result = __('<p>This wizard helps you setting up a basic configuration step by step. After reaching the completion, VikAppointments will be completely up and running.</p><p>If you are not interested in following the steps of the wizard, you can dismiss it by clicking the apposite button from the toolbar.</p>', 'vikappointments');
				break;

			case 'VAPWIZARDBTNDONE':
				$result = __('Close Wizard', 'vikappointments');
				break;

			case 'VAPWIZARDBTNREST':
				$result = __('Restore Wizard', 'vikappointments');
				break;

			case 'VAPWIZARDBTNDONE_DESC':
				$result = __('Are you sure that you really want to close the Wizard? Anyhow, you\'ll be able to restore it from the configuration.', 'vikappointments');
				break;

			case 'VAPWIZARDBTNIGNORE':
				$result = __('Ignore', 'vikappointments');
				break;

			case 'VAPWIZARDBTNDISMISS':
				$result = __('Dismiss', 'vikappointments');
				break;

			case 'VAPWIZARDBTNNOTNOW':
				$result = __('Not Now', 'vikappointments');
				break;

			case 'VAPWIZARDENABLE':
				$result = __('Enable', 'vikappointments');
				break;

			case 'VAPWIZARDDEPEND':
				$result = __('<p>Before to proceed you should complete all the following steps first.</p>', 'vikappointments');
				break;

			case 'VAPWIZARDOTHER_N_ITEMS':
				$result = __('and other %d items', 'vikappointments');
				break;

			case 'VAPWIZARDOTHER_N_ITEMS_1':
				$result = __('and another item', 'vikappointments');
				break;

			case 'VAPWIZARDFORMATH24':
				$result = __('24-hour clock', 'vikappointments');
				break;

			case 'VAPWIZARDCURRCODE':
				$result = __('Currency (ISO 4217)', 'vikappointments');
				break;

			case 'VAPWIZARDCURRSYMB':
				$result = __('Symbol', 'vikappointments');
				break;

			case 'VAPWIZARDCURROTHER':
				$result = __('Other', 'vikappointments');
				break;

			case 'VAPWIZARDTAXVATINCL':
				$result = __('VAT Included', 'vikappointments');
				break;

			case 'VAPWIZARDTAXVATEXCL':
				$result = __('VAT Excluded', 'vikappointments');
				break;

			case 'VAPWIZARDENABLESUBSCR':
				$result = __('Enable Subscriptions', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_TAXES_DESC':
				$result = __('<p>Define here the default amount of taxes to apply to all your items. Set the VAT percentage and whether the taxes are included within the specified prices or whether they should be added.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_TAXES_DESC_ADV':
				$result = __('<p>It is still possible to configure the taxes in a second time from the global section of the program, where you are also able to apply further rules.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_EMPLOYEES_DESC':
				$result = __('<p>The employees are used to set up the availability system of your bookings.</p><p>Regardless of whether it is a person, a room or any other resource, you need to create at least an employee to start receiving online appointments.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_EMPLOYEES_WORKTIME_WARN':
				$result = __('<p>The created employees do not specify any working hours. You should edit at least an employee and define some opening hours from the working days section.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SERVICES_DESC':
				$result = __('<p>Create the services that you are going to offer to your customers for online bookings.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SERVICES_EMPLOYEE_WARN':
				$result = __('<p>The created services haven\'t been assigned to any employees. You should assign at least an employee to your services in order to complete the booking system.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_OPTIONS_DESC':
				$result = __('<p>It is possible to enhance the booking process by upselling certain items. You can create a few options if you are interested in using this feature.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_OPTIONS_SERVICE_WARN':
				$result = __('<p>The options should be assigned to some services, otherwise the customers won\'t be able to purchase them.</p>', 'vikappointments');
					break;

			case 'VAP_WIZARD_STEP_LOCATIONS_DESC':
				$result = __('<p>Create some locations to indicate to the users where your employees work.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_LOCATIONS_GOOGLE_API_KEY_WARN':
				$result = __('It is strongly recommended to set up a <b>Google API Key</b>. Also make sure that the <b>Geocoding</b> service has been enabled from the console of your Google account.', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_LOCWDAYS':
				$result = __('Locations - Working Days', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_LOCWDAYS_DESC':
				$result = __('<p>The locations take effect only when assigned to the working days of the employees. From the locations page of a specific employee you need to click the <b>Working Days Assignment</b> button to create the relations between the locations and its working times.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_PAYMENTS_DESC':
				$result = __('<p>Publish at least a method of payment to receive money directly through your website. There are already 3 pre-installed payment methods: <b>PayPal</b>, <b>Offline Credit Card</b> and <b>Bank Transfer</b> (configurable as <b>pay upon arrival</b> too).</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SYSPACK':
				$result = __('Use Packages', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SYSPACK_DESC':
				$result = __('<p>The packages are used to pre-sell a fixed number of services without asking to the customers to pick a date and time. Afterwards it will be possible to book the appointments for free as long as there are some packages to redeem.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_PACKAGES_DESC':
				$result = __('<p>Create the packages that you wish to sell to your customers.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SYSSUBSCR':
				$result = __('Use Subscriptions', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SYSSUBSCR_DESC':
				$result = __('<p>The subscriptions are used to allow the customers to book the appointments (for free) as long as they have an active subscription plan.</p>', 'vikappointments');
				break;

			case 'VAP_WIZARD_STEP_SUBSCR_DESC':
				$result = __('<p>Create the subscriptions that you wish to sell to your customers.</p>', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.2 - administrator - VikWP.com
			 */

			case 'VAPMANAGECUSTOMF15':
				$result = __('Editable', 'vikappointments');
				break;

			case 'VAPMANAGECUSTOMF15_DESC':
				$result = __('Choose whether the customers are allowed to edit this field. Turn it off if you wish to prevent the customers from changing its value after the first appointment. In case of separator field, it will rather be displayed only once.', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA20':
				$result = __('Alternative Text', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA21':
				$result = __('Image Title', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIA22':
				$result = __('Caption', 'vikappointments');
				break;

			case 'VAPMANAGEMEDIANOTRX':
				$result = __('The selected image does not specify any translatable contents.', 'vikappointments');
				break;

			case 'VAPCONFIGAPPTITLE6':
				$result = __('Customizer', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_TAB_CALENDAR':
				$result = __('Calendar', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_TAB_TIMELINE':
				$result = __('Timeline', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_TAB_BUTTON':
				$result = __('Button', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_TAB_ADDITIONALCSS':
				$result = __('Additional CSS', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_AVAILABLE':
				$result = __('Available', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_PARTIAL':
				$result = __('Partially Available', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_UNAVAILABLE':
				$result = __('Unavailable', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_OCCUPIED':
				$result = __('Occupied', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_EMPTY':
				$result = __('Closed', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_SELECTED':
				$result = __('Selected', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_PRIMARY':
				$result = __('Primary', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_SUCCESS':
				$result = __('Success', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_DANGER':
				$result = __('Danger', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_SECONDARY':
				$result = __('Secondary', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_FIELDSET_DEFAULT':
				$result = __('Default', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_BACKGROUND':
				$result = __('Background', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_COLOR':
				$result = __('Color', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_HOVER_BACKGROUND':
				$result = __('Hover Background', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_HOVER_COLOR':
				$result = __('Hover Color', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_ACTIVE_BACKGROUND':
				$result = __('Active Background', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_PARAM_ACTIVE_COLOR':
				$result = __('Active Color', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_RESTORE_FACTORY_SETTINGS':
				$result = __('Do you want to restore these fields to the factory settings?', 'vikappointments');
				break;

			case 'VAP_CUSTOMIZER_TOGGLE_PREVIEW':
				$result = __('Toggle Preview', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.3 - administrator - VikWP.com
			 */

			case 'VAPFINDRESNODAYSERVICE':
				$result = __('This day the service is no longer available', 'vikappointments');
				break;

			case 'VAPCLOSE':
				$result = __('Close', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE33':
				$result = __('Import URL', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE33_DESC':
				$result = __('Enter here the iCalendar URL that the system will use to import the appointments for this employee. The events will be automatically assigned to the most appropriate service by checking whether its name is contained within the summary or by comparing their durations.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE33_DESC2':
				$result = __('Enter here the iCalendar URL that the system will use to import the appointments for this employee-service relationship.', 'vikappointments');
				break;

			case 'VAPMANAGEEMPLOYEE33_WARN':
				$result = __('In order to periodically execute the import process you need to set up an apposite CRON JOB. <a href="%s" target="_blank">Learn more</a>', 'vikappointments');
				break;

			case 'VAPSYNCUBSCRICS_EXCLUDE_IMPORT':
				$result = __('Exclude imported events', 'vikappointments');
				break;

			case 'VAPSYNCUBSCRICS_EXCLUDE_IMPORT_HELP':
				$result = __('Enable this option to exclude all the appointments that have been imported from a remote iCalendar.', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION13':
				$result = __('Extra Duration', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION13_DESC':
				$result = __('The duration of the appointment will be increased by the specified amount, multiplied by the selected quantity.', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION13_VAR_HELP':
				$result = __('This amount will be added to the base extra duration of the option.', 'vikappointments');
				break;

			case 'VAP_STATUS_CHANGED_ON_ICAL_IMPORT':
				$result = __('Imported from a remote iCalendar service.', 'vikappointments');
				break;

			case 'VAP_CRONJOB_ICALENDAR_IMPORT_TITLE':
				$result = __('iCalendar Appointments Import', 'vikappointments');
				break;

			case 'VAP_CRONJOB_ICALENDAR_IMPORT_DESCRIPTION':
				$result = __('Periodically scans the configured iCalendar URLs to import (or update) new appointments.', 'vikappointments');
				break;

			/**
			 * VikAppointments 1.7.4
			 */

			case 'VAPMANAGEOPTION16':
				$result = __('Always equal to the number of participants', 'vikappointments');
				break;

			case 'VAPMANAGEOPTION12_DESC':
				$result = __('Enable this parameter is you want to force the customers to pick this option before to proceed with the booking process. Useful to support a sort of terms of service acceptance or to choose something specific between a few variations.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE14':
				$result = __('Validity', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE14_DESC':
				$result = __('Choose whether this package should be redeemed within the specified number of days, starting from the purchase date. In case of no restrictions the package will never expire.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE14_OPT0':
				$result = __('No restrictions', 'vikappointments');
				break;

			case 'VAPMANAGEPACKAGE14_OPT1':
				$result = __('Within the specified days', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER19':
				$result = __('Valid Through', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER19_DESC':
				$result = __('The customer will be able to redeem this package until the specified date.', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER19_EXPIRED':
				$result = __('Expired on %s', 'vikappointments');
				break;

			case 'VAPMANAGEPACKORDER19_ACTIVE':
				$result = __('Valid through %s', 'vikappointments');
				break;

			case 'VAPMANAGESUBSCR6_DESC':
				$result = __('The trial subscription can be used to offer a demo to the customers/employees without having to pay. The same account cannot activate the trial more than once.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG14_DESC':
				$result = __('Shows the software version and credits at the end of the pages (back-end only).', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG48_DESC':
				$result = __('This value indicates the interval that will be used to refresh the widgets of the dashboard. The lower the value, the quicker the dashboard will be updated. It is recommended to choose a value between 30 and 120 seconds in order to avoid querying the database too often.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG67_DESC':
				$result = __('Enable this setting if you wish to translate the contents of VikAppointments in multiple languages.', 'vikappointments');
				break;

			case 'VAPMANAGECONFIG87_DESC':
				$result = __('This value indicates the timezone currently used by your server. However, the system will always adjust all the dates and times to the timezone specified from the WordPress configuration: <strong>%s</strong>.', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB11':
				$result = __('Resume Notifications', 'vikappointments');
				break;

			case 'VAPMANAGECRONJOB11_DESC':
				$result = __('The notifications for this cron job are currently paused and will resume on %s. Enable this option if you want to immediately resume them.', 'vikappointments');
				break;

			case 'VAPMANAGECUSTMAIL_PAY_DESC':
				$result = __('Select the method of payment for which the e-mail content will be included. Leave empty to apply the content to any payment method.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_PRODUCTION_SEPARATOR':
				$result = __('Production', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_SANDBOX_SEPARATOR':
				$result = __('Sandbox', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_APPEARANCE_SEPARATOR':
				$result = __('Appearance', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_CLIENT_ID_DESC':
				$result = __('The <b>Client ID</b> parameter that you can find on your PayPal account, under the API Credentials section. Bear in mind that it is NOT your PayPal merchant e-mail address.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_CLIENT_SECRET_DESC':
				$result = __('The <b>Client Secret</b> parameter that you can find from your PayPal account, under the API Credentials section.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_LAYOUT':
				$result = __('Layout', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_LAYOUT_DESC':
				$result = __('Whether the PayPal buttons should be displayed vertically or horizontally.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_LAYOUT_VERTICAL':
				$result = __('Vertical', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_LAYOUT_HORIZONTAL':
				$result = __('Horizontal', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR':
				$result = __('Color', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_DESC':
				$result = __('The color styling to apply to the default PayPal button.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_GOLD':
				$result = __('Gold', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_BLUE':
				$result = __('Blue', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_SILVER':
				$result = __('Silver', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_WHITE':
				$result = __('White', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_COLOR_BLACK':
				$result = __('Black', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_SHAPE':
				$result = __('Shape', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_SHAPE_DESC':
				$result = __('The shape of the payment buttons.', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_SHAPE_RECTANGULAR':
				$result = __('Rectangular', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_SHAPE_ROUNDED':
				$result = __('Rounded', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_TAGLINE':
				$result = __('Tagline', 'vikappointments');
				break;

			case 'VAP_PAYMENT_PAYPAL_EXPRESS_CHECKOUT_TAGLINE_DESC':
				$result = __('Whether the PayPal tagline (<em>"The safer, easier way to pay"</em>) should be displayed or not. Supported only by the horizontal layout.', 'vikappointments');
				break;
		}

		return $result;
	}
}

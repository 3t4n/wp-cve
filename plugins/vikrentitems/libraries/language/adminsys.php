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
 * Switcher class to translate the VikRentItems plugin common languages.
 *
 * @since 	1.0
 */
class VikRentItemsLanguageAdminSys implements JLanguageHandler
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
			 * Do not touch the first definition as it gives the title to the pages of the back-end
			 */
			case 'COM_VIKRENTITEMS':
				$result = __('Vik Rent Items', 'vikrentitems');
				break;

			/**
			 * Definitions
			 */
			case 'COM_VIKRENTITEMS':
				$result = __('VikRentItems', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_MENU':
				$result = __('VikRentItems', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CONFIGURATION':
				$result = __('Vik Rent Items - Access Levels', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_VIKRENTITEMS_VIEW_DEFAULT_TITLE':
				$result = __('Search Form', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_VIKRENTITEMS_VIEW_DEFAULT_DESC':
				$result = __('VikRentItems Search Form', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ITEMSLIST_VIEW_DEFAULT_TITLE':
				$result = __('Items List', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ITEMSLIST_VIEW_DEFAULT_DESC':
				$result = __('VikRentItems Items List', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CATEGORY_FIELD_SELECT_TITLE':
				$result = __('Category', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CATEGORY_FIELD_SELECT_TITLE_DESC':
				$result = __('Select a VikRentItems Category', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_LOCATIONSLIST_VIEW_DEFAULT_TITLE':
				$result = __('Locations List', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_LOCATIONSLIST_VIEW_DEFAULT_DESC':
				$result = __('VikRentItems Locations List', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_USERORDERS_VIEW_DEFAULT_TITLE':
				$result = __('User Orders', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_USERORDERS_VIEW_DEFAULT_DESC':
				$result = __('VikRentItems User Orders', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_SORTBY_FIELD_SELECT_TITLE':
				$result = __('Order Items By', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_SORTTYPE_FIELD_SELECT_TITLE':
				$result = __('Sort Type', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_RESLIM_FIELD_SELECT_TITLE':
				$result = __('Results per page', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ITEMSDETAILS_VIEW_DEFAULT_TITLE':
				$result = __('Item Details', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ITEMSDETAILS_VIEW_DEFAULT_DESC':
				$result = __('VikRentItems Item Details', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ELEMID_FIELD_SELECT_TITLE':
				$result = __('Item', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ELEMID_FIELD_SELECT_TITLE_DESC':
				$result = __('Select an Item from VikRentItems', 'vikrentitems');
				break;
			case 'VRIACTION_ITEMS':
				$result = __('Items Configuration', 'vikrentitems');
				break;
			case 'VRIACTION_ITEMS_DESC':
				$result = __('Items, Categories, Options and Characteristics Management', 'vikrentitems');
				break;
			case 'VRIACTION_PRICES':
				$result = __('Rental Costs', 'vikrentitems');
				break;
			case 'VRIACTION_PRICES_DESC':
				$result = __('Daily-Hourly Fares, Tax Rates, Types of Price, Fees Management', 'vikrentitems');
				break;
			case 'VRIACTION_ORDERS':
				$result = __('Orders Management', 'vikrentitems');
				break;
			case 'VRIACTION_ORDERS_DESC':
				$result = __('Orders, Availability, Calendars, Overview', 'vikrentitems');
				break;
			case 'VRIACTION_GSETTINGS':
				$result = __('Global Settings', 'vikrentitems');
				break;
			case 'VRIACTION_GSETTINGS_DESC':
				$result = __('Configuration, Payment Options, Locations', 'vikrentitems');
				break;
			case 'VRIACTION_MANAGEMENT':
				$result = __('Management', 'vikrentitems');
				break;
			case 'VRIACTION_MANAGEMENT_DESC':
				$result = __('Customers, Coupons and Statistics', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CATEGORIES_VIEW_DEFAULT_TITLE':
				$result = __('Categories', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CATEGORIES_VIEW_DEFAULT_DESC':
				$result = __('A list of categories that will group the various items', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_CATSORTBY_FIELD_SELECT_TITLE':
				$result = __('Order categories by', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_SHOWDESCR_FIELD_SELECT_TITLE':
				$result = __('Show descriptions', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ORDER_VIEW_DEFAULT_TITLE':
				$result = __('Order Details', 'vikrentitems');
				break;
			case 'COM_VIKRENTITEMS_ORDER_VIEW_DEFAULT_DESC':
				$result = __('The order details page', 'vikrentitems');
				break;

			/**
			 * @wponly Definitions for the Views "Gotopro" and "Getpro"
			 */
			case 'VRIMAINGOTOPROTITLE':
				$result = __('Vik Rent Items - Upgrade to Pro', 'vikrentitems');
				break;
			case 'VRILICKEYVALIDUNTIL':
				$result = __('License Key valid until %s', 'vikrentitems');
				break;
			case 'VRILICKEYEXPIREDON':
				$result = __('Your License Key expired on %s', 'vikrentitems');
				break;
			case 'VRIEMPTYLICKEY':
				$result = __('Please enter a valid License Key', 'vikrentitems');
				break;
			case 'VRINOPROERROR':
				$result = __('No valid and active License Key found.', 'vikrentitems');
				break;
			case 'VRIMAINGETPROTITLE':
				$result = __('Vik Rent Items - Downloading Pro version', 'vikrentitems');
				break;
			case 'VRIUPDCOMPLOKCLICK':
				$result = __('Update Completed. Please click here', 'vikrentitems');
				break;
			case 'VRIUPDCOMPLNOKCLICK':
				$result = __('Update Failed. Please click here', 'vikrentitems');
				break;
			case 'VRIPROPLWAIT':
				$result = __('Please wait', 'vikrentitems');
				break;
			case 'VRIPRODLINGPKG':
				$result = __('Downloading the package...', 'vikrentitems');
				break;
			case 'VRIPROTHANKSUSE':
				$result = __('Thanks for using the Pro version', 'vikrentitems');
				break;
			case 'VRIPROTHANKSLIC':
				$result = __('The true Vik Rent Items is Pro. Make sure to keep your License Key active to be able to install future updates.', 'vikrentitems');
				break;
			case 'VRIPROGETRENEWLICFROM':
				$result = __('Get or renew your License Key from VikWP.com', 'vikrentitems');
				break;
			case 'VRIPROGETRENEWLIC':
				$result = __('Get or renew your licence', 'vikrentitems');
				break;
			case 'VRIPROVALNUPD':
				$result = __('Validate and Update', 'vikrentitems');
				break;
			case 'VRIPROALREADYHAVEKEY':
				$result = __('Already have Vik Rent Items PRO? <br /> <small>Enter your licence key here</small>', 'vikrentitems');
				break;
			case 'VRIPROWHYUPG':
				$result = __('Why Upgrade to Pro?', 'vikrentitems');
				break;
			case 'VRIPROTRUEVRIPRO':
				$result = __('The true Vik Rent Items is Pro. Discover the power of a professional items rental software!', 'vikrentitems');
				break;
			case 'VRIPROGETLICNUPG':
				$result = __('Get your License Key and Upgrade to PRO', 'vikrentitems');
				break;
			case 'VRIPROWHYRATES':
				$result = __('Full Rates Management', 'vikrentitems');
				break;
			case 'VRIPROWHYRATESDESC':
				$result = __('Set different rates on some seasons, holidays, weekends or days of the year with just two clicks. Rental Restrictions: define the minimum or maximum days of rental for certain dates of the year and items, set days closed to arrival or departure, and much more.', 'vikrentitems');
				break;
			case 'VRIPROWHYBOOKINGS':
				$result = __('Create and Modify rental orders via back-end', 'vikrentitems');
				break;
			case 'VRIPROWHYBOOKINGSDESC':
				$result = __('The page Calendar will let you create new reservations manually, maybe to register walk-in customers or offline reservations. Modify the dates and switch items of certain reservations with the ease of a simple interface.', 'vikrentitems');
				break;
			case 'VRIPROWHYUNLOCKF':
				$result = __('Unlock over 30 must-have features', 'vikrentitems');
				break;
			case 'VRIPROWHYCUSTOMERS':
				$result = __('Customers Management', 'vikrentitems');
				break;
			case 'VRIPROWHYPROMOTIONS':
				$result = __('Promotions', 'vikrentitems');
				break;
			case 'VRIPROWHYCOUPONS':
				$result = __('Coupon Discounts', 'vikrentitems');
				break;
			case 'VRIPROWHYINVOICES':
				$result = __('Invoices', 'vikrentitems');
				break;
			case 'VRIPROWHYCHECKIN':
				$result = __('Check-in and Registration', 'vikrentitems');
				break;
			case 'VRIPROWHYGRAPHS':
				$result = __('Graphs and Statistics', 'vikrentitems');
				break;
			case 'VRIPROWHYPAYMENTS':
				$result = __('Payment Gateways', 'vikrentitems');
				break;
			case 'VRIPROWHYLOCOOHFEES':
				$result = __('Locations fees', 'vikrentitems');
				break;
			case 'VRIPROREADYTOUPG':
				$result = __('Ready to upgrade?', 'vikrentitems');
				break;
			case 'VRIPROGETNEWLICFROM':
				$result = __('Get your License Key from VikWP.com', 'vikrentitems');
				break;
			case 'VRIPROGETNEWLIC':
				$result = __('Get your License Key', 'vikrentitems');
				break;
			case 'VRIPROVALNINST':
				$result = __('Validate and Install', 'vikrentitems');
				break;
			case 'VRIGOTOPROBTN':
				$result = __('Upgrade to PRO', 'vikrentitems');
				break;
			case 'VRIISPROBTN':
				$result = __('PRO Version', 'vikrentitems');
				break;
			case 'VRILICKEYVALIDVCM':
				$result = __('Active License Key', 'vikrentitems');
				break;
			case 'VRIPROWHYOPTIONS':
				$result = __('Options and Extra Services', 'vikrentitems');
				break;
			case 'VRIPROWHYOPTIONSDESC':
				$result = __('Let customers choose extras and optional services for their rental orders. Such services will be displayed in the order summary with separate rows with their own cost. Offer anything your clients may need for their rental.', 'vikrentitems');
				break;
			case 'VRIPROALREADYHAVEPRO':
				$result = __('Already purchased Vik Rent Items PRO? Upgrade to the PRO version <a href="#upgrade">here</a>.', 'vikrentitems');
				break;
			case 'VRIPROINCREASEORDERS':
				$result = __('Would you like to expand your rental business?', 'vikrentitems');
				break;
			case 'VRIPROCREATEOWNRENTSYS':
				$result = __('Start creating your own Items Rental System', 'vikrentitems');
				break;
			case 'VRIPROMOSTTRUSTED':
				$result = __('Vik Rent Items PRO: the most complete multi-purpose items rental plugin for WordPress', 'vikrentitems');
				break;
			case 'VRIPROEASYANYONE':
				$result = __('Suitable for any kind of items and rentals', 'vikrentitems');
				break;
			case 'VRIPROFULLRESPONSIVE':
				$result = __('Fully responsive and mobile ready', 'vikrentitems');
				break;
			case 'VRIPROPOWERPRICING':
				$result = __('Powerful and flexible pricing system', 'vikrentitems');
				break;
			case 'VRIPROSEASONSONECLICK':
				$result = __('Set up your daily/seasonal prices with just a few clicks', 'vikrentitems');
				break;
			case 'VRIPROCONFIGOPTIONS':
				$result = __('Configure Options and Extra Services', 'vikrentitems');
				break;
			case 'VRIPROOCCUPREPORT':
				$result = __('Occupancy Ranking report to analyse every detail', 'vikrentitems');
				break;
			case 'VRIPROOCCUPREPORTDESC':
				$result = __('Get to monitor your future occupancy through the Occupancy Ranking report. Filter the targets by dates and analyse the data by day, week or month. The report will provide the information about the occupancy, the total number of items sold, days booked, revenues and more.', 'vikrentitems');
				break;
			case 'VRIPROWHYCUSTOMERSDESC':
				$result = __('Create your customers database on your website', 'vikrentitems');
				break;
			case 'VRIPROWHYPAYMENTSDESC':
				$result = __('PayPal, Offline Credit Card and Bank Transfer pre-installed', 'vikrentitems');
				break;
			case 'VRIPROPROMOCOUPONS':
				$result = __('Promotions and Coupons', 'vikrentitems');
				break;
			case 'VRIPROPROMOCOUPONSDESC':
				$result = __('Create Promotions to change rental costs and generate discount coupons', 'vikrentitems');
				break;
			case 'VRIPROPMSREPORTS':
				$result = __('Financial Reports', 'vikrentitems');
				break;
			case 'VRIPROPMSREPORTSDESC':
				$result = __('Total Revenue, Top Countries, Occupancy Ranking and more', 'vikrentitems');
				break;
			case 'VRIPROWHYINVOICESDESC':
				$result = __('Generate invoices and send them to your customers via email', 'vikrentitems');
				break;
			case 'VRIPROWHYCHECKINDESC':
				$result = __('Manage, print or send via email the check-in document for your customers', 'vikrentitems');
				break;
			case 'VRIPROWHYGRAPHSDESC':
				$result = __('Monitor your business trends thanks to the Graphs & Report functions', 'vikrentitems');
				break;
			case 'VRIPROWHYLOCOOHFEESDESC':
				$result = __('Get the most out of a tailored pricing framework', 'vikrentitems');
				break;
			case 'VRIPROWHYMOREEXTRA':
				$result = __('and much more...', 'vikrentitems');
				break;
			case 'VRIPROREADYINCREASE':
				$result = __('Ready to increase your orders?', 'vikrentitems');
				break;
			case 'VRIPROREADYINCREASEDESC':
				$result = __('Get Vik Rent Items PRO and start now.', 'vikrentitems');
				break;
			case 'VRIPROWHATCLIENTSSAY':
				$result = __('This is what our customers say about Vik Rent Items', 'vikrentitems');
				break;
			case 'VRIPROWHATCLIENTSSAYDESC':
				$result = __('These Reviews are published on the official WordPress and Joomla repositories.', 'vikrentitems');
				break;
			case 'VRIPROTIMESLOTS':
				$result = __('Time Slots', 'vikrentitems');
				break;
			case 'VRIPROTIMESLOTSDESC':
				$result = __('Guide your guests through the choice of the duration of the rental', 'vikrentitems');
				break;
			case 'VRIPROGROUPSITEMS':
				$result = __('Groups/Set of Items', 'vikrentitems');
				break;
			case 'VRIPROGROUPSITEMSDESC':
				$result = __('Create groups/kits of multiple items that include several sub-items', 'vikrentitems');
				break;
			case 'VRIPRODISCQUANT':
				$result = __('Discounts per quantity', 'vikrentitems');
				break;
			case 'VRIPRODISCQUANTDESC':
				$result = __('Offer automated discounts for those who book multiple units of your items', 'vikrentitems');
				break;
			case 'VRIPROCRONJOBS':
				$result = __('Automated Cron Jobs', 'vikrentitems');
				break;
			case 'VRIPROCRONJOBSDESC':
				$result = __('Schedule automated email sending functions and reminders for your guests', 'vikrentitems');
				break;
			case 'VRIPRORENTMULTIUNITS':
				$result = __('Allow rentals for multiple items with multiple or single units', 'vikrentitems');
				break;
			case 'VRIPRORENTMULTIUNITSDESC':
				$result = __('A flexible framework will allow you to set up items with single or multiple units, which can be rented alone or together with other items with the same order. This is perfect for the widest range of items, from bikes rentals to a meeting hall that should be booked at precise time intervals. Whether you rent small or large items, for either long or short terms, Vik Rent Items will simply adjust to your needs.', 'vikrentitems');
				break;

			/**
			 * @wponly - First Setup Dashboard
			 */
			case 'VRIFIRSTSETSHORTCODES':
				$result = __('Shortcodes in Pages/Posts', 'vikrentitems');
				break;

			/**
			 * @wponly Definitions for the Shortcodes view
			 */
			case 'VRI_SC_VIEWFRONT':
				$result = __('View page in front site', 'vikrentitems');
				break;
			case 'VRI_SC_ADDTOPAGE':
				$result = __('Create page', 'vikrentitems');
				break;
			case 'VRI_SC_VIEWTRASHPOSTS':
				$result = __('View trashed posts', 'vikrentitems');
				break;
			case 'VRI_SC_ADDTOPAGE_HELP':
				$result = __('You can always create a custom page or post manually and use this Shortcode text inside it. By proceeding, a page containing this Shortcode will be created automatically.', 'vikrentitems');
				break;
			case 'VRI_SC_ADDTOPAGE_OK':
				$result = __('The Shortcode was successfully added to a new page of your website. Visit the new page in the front site to see the content (if any).', 'vikrentitems');
				break;

			/**
			 * @wponly - Free version texts
			 */
			case 'VRIFREEPAYMENTSDESCR':
				$result = __('Allow your guests to pay their orders online through your preferred bank gateway. The Pro version comes with an integration for PayPal Standard and two more payment solutions, but the framework could be extended by installing apposite payment plugins for Vik Rent Items for your preferred bank.', 'vikrentitems');
				break;
			case 'VRIFREECOUPONSDESCR':
				$result = __('Thanks to the coupon codes you can give your clients some dedicated discounts for their rental orders.', 'vikrentitems');
				break;
			case 'VRIFREEOPTIONSDESCR':
				$result = __('Allow your guests to book some extra services, either they are optional or mandatory. This function can be used to create services or fees like insurances, extra mileage/km, late drop off and anything else that could be booked with the items.', 'vikrentitems');
				break;
			case 'VRIFREESEASONSDESCR':
				$result = __('This function will let you create seasonal prices, promotions or special rates for the weekends or any other day of the week. Those who are used to work with seasonal rates will find this feature fundamental.', 'vikrentitems');
				break;
			case 'VRIFREERESTRSDESCR':
				$result = __('The booking restrictions will let you define a minimum or maximum number of days of rent for specific items and dates of the year. You could also allow or deny the pickup/return on some specific days of the week.', 'vikrentitems');
				break;
			case 'VRIFREECUSTOMERSDESCR':
				$result = __('Here you can manage all of your customers information, send specific email messages, and manage their documents.', 'vikrentitems');
				break;
			case 'VRIFREESTATSDESCR':
				$result = __('This page will display graphs and charts by showing important information and statistics about your rental orders, occupancy and revenue.', 'vikrentitems');
				break;
			case 'VRIFREECRONSDESCR':
				$result = __('Cron Jobs are essentials to automatize certain functions, such as to send email reminders to your clients before the pick-up, after the drop-off, remaining balance payments and much more.', 'vikrentitems');
				break;
			case 'VRIFREEREPORTSDESCR':
				$result = __('Reports are essentials to obtain and/or export data. You can use them to calculate your revenue on some dates, your occupancy, or to generate documents for your accountant. This framework is also extendable with custom PMS reports.', 'vikrentitems');
				break;
			case 'VRIFREELOCFEESDESCR':
				$result = __('Those who work with one or multiple locations can use this feature to define costs for certain combinations of pickup/drop off locations.', 'vikrentitems');
				break;
			case 'VRIFREEOOHFEESDESCR':
				$result = __('This feature will let you define some Out of Hours Fees for those rentals who start or end at certain times of day, maybe when the office should be closed.', 'vikrentitems');
				break;
		}

		return $result;
	}
}

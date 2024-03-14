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
 * Switcher class to translate the VikAppointments plugin common languages.
 *
 * @since 	1.0
 */
class VikAppointmentsLanguageAdminSys implements JLanguageHandler
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
			 * VikAppointments core platform.
			 */

			case 'COM_VIKAPPOINTMENTS':
				$result = __('VikAppointments', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_MENU':
				$result = __('VikAppointments', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_MENU_APPOINTMENTS':
				$result = __('VikAppointments', 'vikappointments');
				break;

			/**
			 * Employees list view.
			 */

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESLIST_VIEW_DEFAULT_TITLE':
				$result = __('Employees List', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESLIST_VIEW_DEFAULT_DESC':
				$result = __('Shows the list of all employees to book an appointment.', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESLIST_FIELD_SELECT_TITLE':
				$result = __('Group Filter', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESLIST_FIELD_SELECT_TITLE_DESC':
				$result = __('Choose a group to filter employees.', 'vikappointments');
				break;

			/**
			 * Employee details view.
			 */

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESEARCH_VIEW_DEFAULT_TITLE':
				$result = __('Employee Details', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESEARCH_VIEW_DEFAULT_DESC':
				$result = __('Shows the employee calendar to book an appointment.', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESEARCH_FIELD_SELECT_TITLE':
				$result = __('Employee', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEESEARCH_FIELD_SELECT_TITLE_DESC':
				$result = __('Choose the employee to show.', 'vikappointments');
				break;

			/**
			 * Services list view.
			 */

			case 'COM_VIKAPPOINTMENTS_SERVICESLIST_VIEW_DEFAULT_TITLE':
				$result = __('Services List', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESLIST_VIEW_DEFAULT_DESC':
				$result = __('Shows the list of all services to book an appointment.', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESLIST_FIELD_SELECT_TITLE':
				$result = __('Group Filter', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESLIST_FIELD_SELECT_TITLE_DESC':
				$result = __('Choose a group to filter services.', 'vikappointments');
				break;

			/**
			 * Service details view.
			 */

			case 'COM_VIKAPPOINTMENTS_SERVICESEARCH_VIEW_DEFAULT_TITLE':
				$result = __('Service Details', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESEARCH_VIEW_DEFAULT_DESC':
				$result = __('Shows the service calendar to book an appointment.', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESEARCH_FIELD_SELECT_TITLE':
				$result = __('Service', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SERVICESEARCH_FIELD_SELECT_TITLE_DESC':
				$result = __('Choose the service to show.', 'vikappointments');
				break;

			/**
			 * Appointment confirmation view.
			 */

			case 'COM_VIKAPPOINTMENTS_CONFIRMAPP_VIEW_DEFAULT_TITLE':
				$result = __('Confirmation', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_CONFIRMAPP_VIEW_DEFAULT_DESC':
				$result = __('Shows the checkout form (only if the cart is not empty).', 'vikappointments');
				break;

			/**
			 * Order summary view.
			 */

			case 'COM_VIKAPPOINTMENTS_ORDER_VIEW_DEFAULT_TITLE':
				$result = __('Order View', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_ORDER_VIEW_DEFAULT_DESC':
				$result = __('Shows the form to view own order.', 'vikappointments');
				break;

			/**
			 * Orders list view (customers area).
			 */

			case 'COM_VIKAPPOINTMENTS_ALLORDERS_VIEW_DEFAULT_TITLE':
				$result = __('All Orders View', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_ALLORDERS_VIEW_DEFAULT_DESC':
				$result = __('This page lists all the orders made for the logged in users.', 'vikappointments');
				break;

			/**
			 * Employees area.
			 */

			case 'COM_VIKAPPOINTMENTS_EMPLOYEE_LOGIN_VIEW_DEFAULT_TITLE':
				$result = __('Employees Area', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_EMPLOYEE_LOGIN_VIEW_DEFAULT_DESC':
				$result = __('Shows the login page for registered employees.', 'vikappointments');
				break;

			/**
			 * Packages view.
			 */

			case 'COM_VIKAPPOINTMENTS_PACKAGES_VIEW_DEFAULT_TITLE':
				$result = __('Packages', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_PACKAGES_VIEW_DEFAULT_DESC':
				$result = __('Shows the list of all available packages.', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_PACKAGES_FIELD_SELECT_TITLE':
				$result = __('Group Filter', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_PACKAGES_FIELD_SELECT_TITLE_DESC':
				$result = __('Choose a group to filter packages.', 'vikappointments');
				break;

			/**
			 * Subscriptions view.
			 */

			case 'COM_VIKAPPOINTMENTS_SUBSCRIPTIONS_VIEW_DEFAULT_TITLE':
				$result = __('Subscriptions', 'vikappointments');
				break;

			case 'COM_VIKAPPOINTMENTS_SUBSCRIPTIONS_VIEW_DEFAULT_DESC':
				$result = __('Displays the page that a customer can use to purchase a subscription.', 'vikappointments');
				break;

			/**
			 * ACL rules (access.xml)
			 */

			case 'VAP_ACCESS_EMPLOYEES':
				$result = __('Employees View', 'vikappointments');
				break;

			case 'VAP_ACCESS_EMPLOYEES_DESC':
				$result = __('This rule allows the users to access to the employees view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_GROUPS':
				$result = __('Groups View', 'vikappointments');
				break;

			case 'VAP_ACCESS_GROUPS_DESC':
				$result = __('This rule allows the users to access to the groups view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_SERVICES':
				$result = __('Services View', 'vikappointments');
				break;

			case 'VAP_ACCESS_SERVICES_DESC':
				$result = __('This rule allows the users to access to the services view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_OPTIONS':
				$result = __('Options View', 'vikappointments');
				break;

			case 'VAP_ACCESS_OPTIONS_DESC':
				$result = __('This rule allows the users to access to the options view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_LOCATIONS':
				$result = __('Locations View', 'vikappointments');
				break;

			case 'VAP_ACCESS_LOCATIONS_DESC':
				$result = __('This rule allows the users to access to the locations view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_PACKAGES':
				$result = __('Packages View', 'vikappointments');
				break;

			case 'VAP_ACCESS_PACKAGES_DESC':
				$result = __('This rule allows the users to access to the packages view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_RESERVATIONS':
				$result = __('Reservations View', 'vikappointments');
				break;

			case 'VAP_ACCESS_RESERVATIONS_DESC':
				$result = __('This rule allows the users to access to the reservations view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_WAITINGLIST':
				$result = __('Waiting List View', 'vikappointments');
				break;

			case 'VAP_ACCESS_WAITINGLIST_DESC':
				$result = __('This rule allows the users to access to the waiting list view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_CUSTOMERS':
				$result = __('Customers View', 'vikappointments');
				break;

			case 'VAP_ACCESS_CUSTOMERS_DESC':
				$result = __('This rule allows the users to access to the customers view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_COUPONS':
				$result = __('Coupons View', 'vikappointments');
				break;

			case 'VAP_ACCESS_COUPONS_DESC':
				$result = __('This rule allows the users to access to the coupons view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_CALENDAR':
				$result = __('Calendar View', 'vikappointments');
				break;

			case 'VAP_ACCESS_CALENDAR_DESC':
				$result = __('This rule allows the users to access to the calendar view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_DASHBOARD':
				$result = __('Dashboard View', 'vikappointments');
				break;

			case 'VAP_ACCESS_DASHBOARD_DESC':
				$result = __('This rule allows the users to access to the dashboard view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_COUNTRIES':
				$result = __('Countries View', 'vikappointments');
				break;

			case 'VAP_ACCESS_COUNTRIES_DESC':
				$result = __('This rule allows the users to access to the countries view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_REVIEWS':
				$result = __('Reviews View', 'vikappointments');
				break;

			case 'VAP_ACCESS_REVIEWS_DESC':
				$result = __('This rule allows the users to access to the reviews view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_SUBSCRIPTIONS':
				$result = __('Subscriptions View', 'vikappointments');
				break;

			case 'VAP_ACCESS_SUBSCRIPTIONS_DESC':
				$result = __('This rule allows the users to access to the subscriptions view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_CUSTFIELDS':
				$result = __('Custom Fields View', 'vikappointments');
				break;

			case 'VAP_ACCESS_CUSTFIELDS_DESC':
				$result = __('This rule allows the users to access to the custom fields view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_PAYMENTS':
				$result = __('Payments View', 'vikappointments');
				break;

			case 'VAP_ACCESS_PAYMENTS_DESC':
				$result = __('This rule allows the users to access to the payments view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_STATUSCODES':
				$result = __('Status Codes View', 'vikappointments');
				break;
				
			case 'VAP_ACCESS_STATUSCODES_DESC':
				$result = __('This rule allows the users to access to the status codes view.', 'vikappointments');
				break;
				
			case 'VAP_ACCESS_TAXES':
				$result = __('Taxes View', 'vikappointments');
				break;
				
			case 'VAP_ACCESS_TAXES_DESC':
				$result = __('This rule allows the users to access to the taxes view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ARCHIVE':
				$result = __('Invoices Archive View', 'vikappointments');
				break;

			case 'VAP_ACCESS_ARCHIVE_DESC':
				$result = __('This rule allows the users to access to the invoices archive view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_MEDIA':
				$result = __('Media View', 'vikappointments');
				break;

			case 'VAP_ACCESS_MEDIA_DESC':
				$result = __('This rule allows the users to access to the media view.', 'vikappointments');
				break;

			case 'VAP_ACCESS_CLOSINGDAYS':
				$result = __('Closing Days', 'vikappointments');
				break;

			case 'VAP_ACCESS_CLOSINGDAYS_DESC':
				$result = __('This rule allows the users to manage the global closing days and periods.', 'vikappointments');
				break;
				
			case 'VAP_ACCESS_CONFIG':
				$result = __('Configuration', 'vikappointments');
				break;

			case 'VAP_ACCESS_CONFIG_DESC':
				$result = __('This rule allows the users to manage the configuration of the component.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_FINANCE':
				$result = __('Financial Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_FINANCE_DESC':
				$result = __('This rule allows the users to access the financial analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_APPOINTMENTS':
				$result = __('Appointments Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_APPOINTMENTS_DESC':
				$result = __('This rule allows the users to access the appointments analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_SERVICES':
				$result = __('Services Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_SERVICES_DESC':
				$result = __('This rule allows the users to access the services analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_EMPLOYEES':
				$result = __('Employees Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_EMPLOYEES_DESC':
				$result = __('This rule allows the users to access the employees analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_CUSTOMERS':
				$result = __('Customers Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_CUSTOMERS_DESC':
				$result = __('This rule allows the users to access the customers analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_PACKAGES':
				$result = __('Packages Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_PACKAGES_DESC':
				$result = __('This rule allows the users to access the packages analytics page.', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_SUBSCRIPTIONS':
				$result = __('Subscriptions Analytics', 'vikappointments');
				break;

			case 'VAP_ACCESS_ANALYTICS_SUBSCRIPTIONS_DESC':
				$result = __('This rule allows the users to access the subscriptions analytics page.', 'vikappointments');
				break;
		}

		return $result;
	}
}

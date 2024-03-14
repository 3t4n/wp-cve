<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  system
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Helper class to setup the WordPress Screen.
 *
 * @since 1.0
 */
class VikAppointmentsScreen
{
	/**
	 * The type of options to display.
	 * This property can be edited externally.
	 *
	 * @var boolean
	 */
	public static $optionsType = null;

	/**
	 * Creates the option section within the WP Screen for VikAppointments.
	 *
	 * @return 	void
	 */
	public static function options()
	{
		$app = JFactory::getApplication();

		// make sure we are in VikAppointments (back-end)
		if (!$app->isAdmin() || $app->input->get('page') != 'vikappointments')
		{
			// abort
			return;
		}

		// check if we should display screen options for listing pages
		if (static::$optionsType == 'list')
		{
			// create pagination option
		    $args = array(
		        'label'   => __('Number of items per page:'),
		        'default' => 20,
		        'option'  => 'vikappointments_list_limit',
		    );
		 
		    add_screen_option('per_page', $args);	
		}
	}

	/**
	 * Filters a screen option value before it is set.
	 *
	 * @param 	boolean  $skip    Whether to save or skip saving the screen option value. Default false.
	 * @param 	string   $option  The option name.
	 * @param 	mixed    $value   The option value.
	 *
	 * @return  mixed    Returning false to the filter will skip saving the current option.
	 */
	public static function saveOption($skip, $option, $value)
	{
		switch ($option)
		{
			case 'vikappointments_list_limit':
				// cannot have a value lower than 1
				$value = max(array(1, (int) $value));
				// refresh cached value
				JFactory::getApplication()->setUserState('com_vikappointments.limit', $value);

				return $value;
		}

		// skip otherwise
		return $skip;
	}

	/**
	 * Creates the Help tabs within the WP Screen for VikAppointments.
	 *
	 * @param 	WP_Screen  $screen  The current screen instance.
	 *
	 * @return 	void
	 */
	public static function help($screen = null)
	{
		$app = JFactory::getApplication();

		// make sure we are in VikAppointments (back-end)
		if (!$app->isAdmin() || $app->input->get('page') != 'vikappointments')
		{
			// abort
			return;
		}

		// make sure $screen is a valid instance
		if (!class_exists('WP_Screen') || !$screen instanceof WP_Screen)
		{
			if (VIKAPPOINTMENTS_DEBUG)
			{
				// trigger warning in case debug is enabled
				trigger_error('Method ' . __METHOD__ . ' has been called too early', E_USER_WARNING);
			}
			// abort
			return;
		}

		// extract view from request
		$view = $app->input->get('view', null);

		if (empty($view))
		{
			// no view, try to check 'task'
			$view = $app->input->get('task', 'vikappointments');
		}

		// make sure the view is supported
		if (!isset(static::$lookup[$view]))
		{
			// view not supported
			return;
		}

		// check if we have a link to an existing item
		if (is_string(static::$lookup[$view]))
		{
			// use the linked element
			$view = static::$lookup[$view];
		}

		// check if the view documentation has been already cached
		$doc = get_transient('vikappointments_screen_' . $view);

		if (!$doc)
		{
			// evaluate if we should stop using HELP tabs after 3 failed attempts
			$fail = (int) get_option('vikappointments_screen_failed_attempts', 0);

			if ($fail >= 5)
			{
				// Do not proceed as we hit too many failure attempts contiguously.
				// Reset 'vikappointments_screen_failed_attempts' option to restart using HELP tabs.
				return;
			}

			// create POST arguments
			$args = array(
				'documentation_alias' => 'vik-appointments',
				'lang'                => substr(JFactory::getLanguage()->getTag(), 0, 2),
			);

			$args = array_merge($args, static::$lookup[$view]);

			// build headers
			$headers = array(
				/**
				 * Always bypass SSL validation while reaching our end-point.
				 *
				 * @since 1.2
				 */
				'sslverify' => false,
			);

			$http = new JHttp();

			// make HTTP post
			$response = $http->post('https://vikwp.com/index.php?option=com_vikhelpdesk&format=json', $args, $headers);

			if ($response->code != 200)
			{
				// increase total number of failed attempts
				update_option('vikappointments_screen_failed_attempts', $fail + 1);

				return;
			}

			// try to decode JSON
			$doc = json_decode($response->body);

			if (!is_array($doc))
			{
				// increase total number of failed attempts
				update_option('vikappointments_screen_failed_attempts', $fail + 1);

				return;
			}

			// reset total number of failed attempts
			update_option('vikappointments_screen_failed_attempts', 0);

			// cache retrieved documentation (for one week only)
			set_transient('vikappointments_screen_' . $view, json_encode($doc), WEEK_IN_SECONDS);
		}
		else
		{
			// JSON decode the cached documentation
			$doc = json_decode($doc);
		}

		// iterate category sections
		foreach ($doc as $i => $cat)
		{
			// add subcategory as help tab
			$screen->add_help_tab(array(
				'id'       => 'vikappointments-' . $view . '-' . ($i + 1),
				'title'    => $cat->contentTitle,
				'content'  => $cat->content,
			));
		}

		// add help sidebar
		$screen->set_help_sidebar(
			'<p><strong>' . __('For more information:') . '</strong></p>' .
			'<p><a href="https://vikwp.com/support/documentation/vik-appointments/" target="_blank">VikWP.com</a></p>'
		);
	}

	/**
	 * Clears the cache for the specified view, if specified.
	 *
	 * @param 	string|null  $view  Clear the cache for the specified view (if specified)
	 * 								or for all the existing views.
	 *
	 * @return 	void
	 */
	public static function clearCache($view = null)
	{
		if ($view)
		{
			delete_transient('vikappointments_screen_' . $view);
		}
		else
		{
			foreach (static::$lookup as $view => $args)
			{
				if (is_array($args))
				{
					delete_transient('vikappointments_screen_' . $view);
				}
			}

			// delete settings too
			delete_option('vikappointments_screen_failed_attempts');
			delete_option('vikappointments_list_limit');
		}
	}

	/**
	 * Lookup used to retrieve the arguments for the HTTP request.
	 *
	 * @var array
	 */
	protected static $lookup = array(
		////////////////////////////////
		/////////// DASHBOARD //////////
		////////////////////////////////

		// dashboard
		'vikappointments' => array(
			'task'          => 'documentation.category',
			'category_name' => 'dashboard',
		),

		////////////////////////////////
		////////// MANAGEMENT //////////
		////////////////////////////////

		// groups
		'groups' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'groups',
		),
		'managegroup' => 'groups',

		// employees
		'employees' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			// 'content_name'     => 'employee',
			'no_contents' 	   => true,
		),

		// edit employee
		'manageemployee' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			'content_name'     => array(
									'employee', 
									'custom fields', 
									'working days',
								),
		),

		// employee overrides
		'emprates' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			'content_name'     => 'overrides',
		),

		// employee payments
		'emppayments' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			'content_name'     => 'payments',
		),

		// employee payments
		'emplocations' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			'content_name'     => 'locations',
		),
		'manageemplocation' => 'emplocations',

		// employee reports
		'reportsemp' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'employees',
			'content_name'     => 'reports',
		),

		// services
		'services' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'services',
			'content_name'     => array(
									'special rates',
								),
		),

		// edit service
		'manageservice' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'services',
			'content_name'     => array(
									'service',
									'assignments',
									'metadata',
								),
		),

		// service working days
		'serworkdays' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'services',
			'content_name'     => 'working days',
		),

		// service rates
		'rates' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'services',
			'content_name'     => array(
									'special rates',
									'rate fields',
									'rates debug',
								),
		),
		'managerate' => 'rates',

		// options
		'options' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'options',
			'no_contents'      => true,
		),

		// edit option
		'manageoption' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'options',
		),

		// locations
		'locations' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'locations',
			'no_contents'      => true,
		),

		// edit location
		'managelocation' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'locations',
		),

		// packages groups
		'packgroups' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'packages',
			'content_name'     => 'packages groups',
		),
		'managepackgroup' => 'packgroups',

		// packages products
		'packages' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'packages',
			'no_contents'      => true,
		),

		// edit package
		'managepackage' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'packages',
			'content_name'     => 'packages content',
		),

		// packages orders
		'packorders' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'management',
			'subcategory_name' => 'packages',
			'content_name'     => 'packages orders',
		),
		'managepackorder' => 'packorders',

		////////////////////////////////
		///////// APPOINTMENTS /////////
		////////////////////////////////

		// reservations
		'reservations' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'reservations',
			'content_name'     => array(
									'single & multiple appointments',
									'sms notifications',
									'invoices',
								),
		),

		// edit reservation
		'managereservation' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'reservations',
			'content_name'     => array(
									'reservation',
									'order statuses',
								),
		),

		// make recurrence
		'makerecurrence' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'reservations',
			'content_name'     => 'make recurrence',
		),

		// export reservations
		'exportres' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'reservations',
			'content_name'     => 'export',
		),

		// closures
		'manageclosure' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'reservations',
			'content_name'     => 'closures',
		),

		// waiting list
		'waitinglist' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'waiting list',
			'no_contents'      => true,
		),

		// edit waiting list
		'managewaiting' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'waiting list',
		),

		// customers
		'customers' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'customers',
			'content_name'     => array(
									'customers',
									'import',
									'export',
									'sms notifications',
								),
		),

		// edit customer
		'managecustomer' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'customers',
			'content_name'     => array(
									'customers',
									'user credit',
								),
		),

		// coupons
		'coupons' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'coupons',
			'content_name'     => array(
									'import',
									'export',
								),
		),

		// edit coupon
		'managecoupon' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'coupons',
			'content_name'     => 'coupon',
		),

		// coupon groups
		'coupongroups' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'coupons',
			'content_name'     => 'groups',
		),
		'managecoupongroup' => 'coupongroups',

		// calendar
		'calendar' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'calendar',
			'content_name'     => 'reports',
		),
		'reportsall' => 'calendar',

		// daily calendar
		'caldays' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'appointments',
			'subcategory_name' => 'calendar',
			'content_name'     => 'daily view',
		),

		////////////////////////////////
		//////////// PORTAL ////////////
		////////////////////////////////

		// countries
		'countries' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'countries',
			'no_contents'      => true,
		),

		// edit country
		'managecountry' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'countries',
			'content_name'     => 'countries',
		),

		// states
		'states' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'countries',
			'content_name'     => 'states',
		),
		'managestate' => 'states',

		// cities
		'cities' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'countries',
			'content_name'     => 'cities',
		),
		'managecity' => 'cities',

		// reviews
		'revs' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'reviews',
			'no_contents'      => true,
		),

		// edit review
		'managerev' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'reviews',
		),

		// subscriptions
		'subscriptions' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'subscriptions',
			'no_contents'      => true,
		),

		// edit subscription
		'managesubscription' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'subscriptions',
		),

		// subscription orders
		'subscrorders' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'subscription orders',
			'content_name'     => 'employees overview',
		),
		
		// edit subscription order
		'managesubscrorder' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'portal',
			'subcategory_name' => 'subscription orders',
			'content_name'     => 'subscription orders content',
		),

		////////////////////////////////
		//////////// GLOBAL ////////////
		////////////////////////////////

		// custom fields
		'customf' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'custom fields',
			'content_name'     => array(
									'rules',
									'override custom fields',
								),
		),

		// edit custom field
		'managecustomf' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'custom fields',
			'content_name'     => array(
									'custom field',
									'text type',
									'textarea type',
									'number type',
									'date type',
									'select type',
									'checkbox type',
									'file type',
									'separator type',
									'rules',
								),
		),

		// payments
		'payments' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'payments',
			'no_contents'      => true,
		),

		// edit payment
		'managepayment' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'payments',
		),

		// invoices
		'invfiles' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'invoices archive',
		),

		// media manager
		'media' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'media manager',
			'content_name'     => 'analyze media',
		),

		// new media
		'managemedia' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'media manager',
			'content_name'     => array(
									'upload',
									'quick upload',
								),
		),

		// cron jobs
		'cronjobs' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'cron jobs',
		),

		////////////////////////////////
		///////// CONFIGURATION ////////
		////////////////////////////////

		// configuration > global
		'editconfig' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'configuration',
			'subcategory_name' => 'global',
		),

		// configuration > employees
		'editconfigemp' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'configuration',
			'subcategory_name' => 'employees',
		),

		// configuration > closing days
		'editconfigcldays' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'configuration',
			'subcategory_name' => 'closing days',
		),

		// configuration > closing days
		'editconfigsmsapi' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'configuration',
			'subcategory_name' => 'sms apis',
		),

		// configuration > closing days
		'editconfigcron' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'configuration',
			'subcategory_name' => 'cron jobs',
		),

		// mail custom text
		'mailtextcust' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'e-mail custom text',
			'no_contents'      => true,
		),

		// mail custom text
		'managemailtext' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'e-mail custom text',
		),

		// conversion codes
		'conversions' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'conversion codes',
			'no_contents'      => true,
		),

		// edit conversion code
		'manageconversion' => array(
			'task'             => 'documentation.subcategory',
			'category_name'    => 'global',
			'subcategory_name' => 'conversion codes',
		),
	);
}

/**
 * In case VikAppointments displayed the menu, we are probably 
 * visiting a page with a list. For this reason, we should alter
 * the VikAppointmentsScreen::$optionsType to display a specific
 * screen options form.
 */
add_action('vikappointments_before_build_menu', function()
{
	VikAppointmentsScreen::$optionsType = 'list';
});

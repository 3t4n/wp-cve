<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * General back-end controller of VikAppointments component.
 *
 * @since 1.0
 */
class VikAppointmentsController extends JControllerVAP
{
	/**
	 * Display task.
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$input = JFactory::getApplication()->input;

		$view = $input->get('view');

		if (empty($view))
		{
			// set default view if not specified
			$input->set('view', $view = AppointmentsHelper::getDefaultView());
		}
		
		/**
		 * Fetch here whether to display the menu or not.
		 *
		 * @since 1.7
		 */
		$is_menu = $this->shouldDisplayMenu($view);

		if ($is_menu)
		{
			AppointmentsHelper::printMenu();
		}

		// call parent behavior
		parent::display();

		if ($is_menu)
		{
			AppointmentsHelper::printFooter();
		}
	}

	/**
	 * FULL SCREEN VIEWS (no menu)
	 */

	function store_dashboard_properties()
	{
		$input = JFactory::getApplication()->input;

		$prop = array(
			'appointments' 	=> $input->getUint('a_page', 1),
			'waiting' 		=> $input->getUint('w_page', 1),
			'customers' 	=> $input->getUint('c_page', 1),
			'packages' 		=> $input->getUint('p_page', 1),
		);
		
		JFactory::getSession()->set('dashboard-properties', $prop, 'vap');
		exit;
	}
	
	/**
	 * CANCEL TASKS
	 */
	
	function dashboard()
	{
		JFactory::getApplication()->redirect('index.php?option=com_vikappointments');
	}

	/**
	 * Task used to allow the plugins to self-render their contents
	 * within a stand-alone page of VikAppointments.
	 *
	 * Any arguments to be passed to the plugins can be specified
	 * within the `args` property in query string, such as:
	 * &args[action]=xxx&args[id]=123
	 *
	 * @return  void
	 *
	 * @since 	1.7.3
	 */
	public function plugin_action()
	{
		$input      = JFactory::getApplication()->input;
		$dispatcher = VAPFactory::getEventDispatcher();

		// a configuration array to be passed to the plugins
		$state = new VAPActionState($input->get('args', [], 'array'));

		// extract action from request and also from args array
		$action = $input->get('action', $state->get('action'));

		if (!$action)
		{
			// action was not specified in request
			throw new InvalidArgumentException('Missing action.', 400);
		}

		// create actions container
		$container = new VAPActionContainerMap();

		/**
		 * Trigger event to let the plugins be able to render their contents on a
		 * stand-alone page or to execute some tasks as a controller.
		 *
		 * @param 	VAPActionContainer  $container  A container used to attach the task.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.3
		 */
		$dispatcher->trigger('onDispatchVikAppointmentsPluginAction', array($container));

		// notify elements subscribed to the requested event/action
		$result = new VAPActionResultHtml($container->notify($action, $state));

		if ($result->has())
		{
			// display HTML
			echo $result->display();
		}
	}

	/**
	 * Task used to allow the plugins to self-render their contents
	 * within a stand-alone page of VikAppointments.
	 *
	 * Any arguments to be passed to the plugins can be specified
	 * within the `args` property in query string, such as:
	 * &args[action]=xxx&args[id]=123
	 *
	 * @return 	void
	 *
	 * @since 	    1.6.6
	 * @deprecated  1.8 Use plugin_action() instead.
	 */
	public function plugin_view()
	{
		$input      = JFactory::getApplication()->input;
		$dispatcher = VAPFactory::getEventDispatcher();

		// a configuration array to be passed to the plugins
		$args = $input->get('args', array(), 'array');

		// wrap arguments in a registry for a better ease of use
		$args = new JRegistry($args);

		/**
		 * Trigger event to let the plugins be able to
		 * render their contents on a stand-alone page.
		 *
		 * @param 	Registry  $args  A registry of arguments set in request.
		 *
		 * @return 	string    The HTML to display.
		 *
		 * @since 	1.6.6
		 */
		$html = $dispatcher->trigger('onDisplayVikAppointmentsPluginView', array($args));

		// strip empty values from resulting array
		$html = array_filter($html);

		// join HTML responses and echo string
		echo implode("\n", $html);
	}

	/////////////////////
	////// HELPERS //////
	/////////////////////

	/**
	 * Checks whether the specified view should display the menu.
	 *
	 * @param 	string 	$view  The view to check.
	 * @param 	array 	$list  An additional list of supported views.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	protected function shouldDisplayMenu($view, array $list = array())
	{
		$tmpl = JFactory::getApplication()->input->get('tmpl');

		// do not display in case of tmpl=component
		if (!strcmp((string) $tmpl, 'component'))
		{
			return false;
		}

		// defines list of views that supports menu and footer
		$views = array(
			// Dashboard
			'vikappointments',
			// Groups
			'groups',
			// Employees
			'employees',
			'emppayments',
			'emplocations',
			// Services
			'services',
			'serworkdays',
			'restrictions',
			'rates',
			// Options
			'options',
			'optiongroups',
			// Locations
			'locations',
			// Packages
			'packorders',
			'packgroups',
			'packages',
			// Reservations
			'reservations',
			// Waiting List
			'waitinglist',
			// Customer
			'customers',
			'usernotes',
			// Coupons
			'coupons',
			'coupongroups',
			// Calendar
			'calendar',
			'caldays',
			// Countries
			'countries',
			'states',
			'cities',
			// Reviews
			'reviews',
			// Subscriptions
			'subscriptions',
			'subscrorders',
			// Analytics
			'analytics',
			// Custom Fields
			'customf',
			// Payments
			'payments',
			// Status Codes
			'statuscodes',
			// Taxes
			'taxes',
			// Invoices
			'invoices',
			// Media
			'media',
			// Configuration
			'editconfig',
			'editconfigemp',
			'editconfigcldays',
			'editconfigsmsapi',
			'editconfigcron',
			'editconfigapp',
			// Misc
			'import',
			'export',
			'tags',
		);

		// merge lookup with overrides
		$views = array_merge($views, $list);

		// check whether the view is in the list
		return in_array($view, $views);
	}
}

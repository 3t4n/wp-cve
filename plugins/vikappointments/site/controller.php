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
 * General front-end controller of VikAppointments component.
 *
 * @since 1.0
 */
class VikAppointmentsController extends JControllerVAP
{
	/**
	 * Typical view method for MVC based architecture.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types.
	 *
	 * @return  void
	 */
	function display($cachable = false, $urlparams = false)
	{
		$input = JFactory::getApplication()->input;

		$view = strtolower($input->get('view', ''));

		switch ($view)
		{
			case 'confirmapp':
				$this->confirmapp();
				break;

			case 'packagesconfirm':
				$this->packagesconfirm();
				break;

			case 'employeeslist':
			case 'serviceslist':
			case 'servicesearch':
			case 'employeesearch':
			case 'order':
			case 'allorders':
			case 'packages':
			case 'packorders':
			case 'packagesorder':
			case 'subscriptions':
			case 'subscrpayment':
			case 'subscrhistory':
			case 'userprofile':
			case 'pushwl':
				// do nothing
				break;

			case 'unsubscr_waiting_list':
				$input->set('view', 'unsubscrwl');
				break;

			case 'empaccountstat':
			case 'empattachser':
			case 'empcoupons':
			case 'empcustfields':
			case 'empeditcoupon':
			case 'empeditcustfield':
			case 'empeditlocation':
			case 'empeditpay':
			case 'empeditprofile':
			case 'empeditservice':
			case 'empeditwdays':
			case 'emplocations':
			case 'emplocwdays':
			case 'emplogin':
			case 'empmakerecur':
			case 'empmanres':
			case 'emppaylist':
			case 'empserviceslist':
			case 'empsettings':
			case 'empsubscr':
			case 'empsubscrorder':
			case 'empwdays':
				VAPApplication::getInstance()->loadEmployeeAreaAssets();
				break;

			default:
				$input->set('view', 'serviceslist');
		}

		parent::display();
	}
	
	/**
	 * Task used to access the confirmapp view.
	 *
	 * @return 	void
	 */
	public function confirmapp()
	{	
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$args = array();
		$args['id_employee'] = $input->getUint('id_employee', 0);
		$args['id_service']  = $input->getUint('id_service', 0);
		$args['date']        = $input->getString('date', '');
		$args['hour']        = $input->getUint('hour', 0);
		$args['min']         = $input->getUint('min', 0);
		$args['people']      = $input->getUint('people', 1);
		$args['factor']      = $input->getUint('duration_factor', 1);
		$args['from']        = $input->getUint('from', 1);

		// get cart model
		$model = $this->getModel('cart');

		// get cart handler
		$cart = $model->getCart();

		/**
		 * If the cart is empty and there are no data to add a new appointment, redirect
		 * the users to the services list without displaying any error message.
		 *
		 * @since 1.6
		 */
		if ($cart->isEmpty() && $args['id_employee'] <= 0 && $args['id_service'] <= 0)
		{
			// invalid request, back to services list
			$app->redirect(JRoute::rewrite('index.php?option=com_vikappointments&view=serviceslist', false));
			return false;
		}

		/**
		 * Check if the time has been selected before clicking the Book Now button.
		 * In this way, the button can be used to add the services into the cart 
		 * more than once.
		 *
		 * It is also needed to check if the appointment is already in the cart,
		 * so that we can assume that a refresh has been performed.
		 *
		 * @note 	Refreshing the page after deleting the appointment that has
		 * 			been added will cause a loop that will re-add automatically
		 * 			that appointment (only if the cart contains 2 or more items).
		 *
		 * @since 1.6
		 */
		$time_is_selected = (bool) strlen($input->getString('hour', ''));

		if ($time_is_selected)
		{
			// get the employee timezone, if set
			$tz = JModelVAP::getInstance('employee')->getTimezone($args['id_employee']);
			// create check-in date according to the employee timezone, only if the time was specified
			$args['checkin'] = JDate::getInstance("{$args['date']} {$args['hour']}:{$args['min']}", $tz)->format('Y-m-d H:i:s');

			// check if the specified appointment is already in the cart
			if ($cart->indexOf($args['id_service'], $args['id_employee'], $args['checkin']) != -1)
			{
				// the appointment is already in the cart, we don't need to add it
				$time_is_selected = false;
			}
		}
		
		// add to cart [appoint.] from request when the cart is disabled or empty or the time is selected
		if ((!VAPFactory::getConfig()->getBool('enablecart') && !empty($args['id_service'])) || $cart->isEmpty() || $time_is_selected)
		{	
			// get recurrence roles
			$rules = $input->getString('recurrence', '');

			$args['options'] = array();

			$opt_id  = $input->get('opt_id', array(), 'uint');
			$opt_qty = $input->get('opt_quantity', array(), 'uint');
			$opt_var = $input->get('opt_var', array(), 'uint');

			// load selected options
			for ($i = 0; $i < count($opt_id); $i++)
			{
				$args['options'][] = array(
					'id'        => $opt_id[$i],
					'quantity'  => $opt_qty[$i],
					'variation' => $opt_var[$i],
				);
			}

			if ($rules)
			{
				// extract recurrence roles
				list($recurrence['by'], $recurrence['for'], $recurrence['amount']) = explode(',', $rules);	
				// add item with recurrence
				$res = $model->addRecurringItem($args, $recurrence);
			}
			else
			{
				// insert new item
				$res = $model->addItem($args);
			}
			
			if (!$res)
			{
				// get error from model
				$error = $model->getError($index = null, $string = true);

				if ($error)
				{
					// enqueue error message only if set
					$app->enqueueMessage($error, 'error');
				}

				if ($args['from'] == 1 || $args['id_employee'] <= 0)
				{
					// coming from service search details
					$app->redirect(JRoute::rewrite("index.php?option=com_vikappointments&view=servicesearch&id_service={$args['id_service']}&id_employee={$args['id_employee']}&date={$args['date']}", false));
				}
				else
				{
					// coming from employee search details
					$app->redirect(JRoute::rewrite("index.php?option=com_vikappointments&view=employeesearch&id_employee={$args['id_employee']}&id_service={$args['id_service']}&date={$args['date']}", false));
				}

				return false;
			}
		}
		
		if ($cart->isEmpty())
		{
			// cart is still empty... back to the services list
			$app->enqueueMessage(JText::translate('VAPCARTEMPTYERR'), 'error');
			$app->redirect(JRoute::rewrite('index.php?option=com_vikappointments&view=serviceslist', false));
			return false;
		}
		
		// validates the appointments contained within the cart
		if (!$model->checkIntegrity($errors))
		{
			// there's at least an invalid item...
			foreach ($errors as $error)
			{
				$name    = $error['item']->getServiceName();
				$at      = JText::translate('VAP_AT_DATE_SEPARATOR');
				$checkin = $error['item']->getCheckinDate(JText::translate('DATE_FORMAT_LC2'), VikAppointments::getUserTimezone());

				// build item identifier string
				$item_id = sprintf('%s %s %s', $name, $at, $checkin);

				// register error message
				$reason = JText::sprintf('VAPCARTITEMNOTAVERR', $item_id, $error['reason']);
				$app->enqueueMessage($reason, 'error');
			}
		}

		if ($cart->isEmpty())
		{
			// cart has been emptied due to invalid appointments, back to the services list
			$app->enqueueMessage(JText::translate('VAPCARTEMPTYERR'), 'error');
			$app->redirect(JRoute::rewrite('index.php?option=com_vikappointments&view=serviceslist', false));
			return false;
		}

		// try to redeem the packages before accessing the view
		VikAppointments::usePackagesForServicesInCart($cart);
	}

	/**
	 * Task used to access the packagesconfirm view.
	 *
	 * @return 	void
	 */
	function packagesconfirm()
	{
		$app = JFactory::getApplication();

		// get cart model
		$model = $this->getModel('packagescart');

		// get cart handler
		$cart = $model->getCart();

		if ($cart->isEmpty())
		{
			// cart empty, back to packages view
			$app->redirect(JRoute::rewrite('index.php?option=com_vikappointments&view=packages', false));
			return false;
		}
	}
	
	/**
	 * End-point used to export the appointments in ICS format.
	 * This task can be used by external applications to syncronize
	 * the appointments within their calendars (e.g Apple iCal or Google Calendar).
	 *
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  employee  An ID to obtain only the appointments
	 *                             assigned to the specified employee.
	 *                             If not provided, this filter will be ignored.
	 * @param 	string 	 key       The secure key to access the appointments.
	 *
	 * @return 	void
	 */
	function appsync()
	{
		$app = JFactory::getApplication();

		$id_emp   = $app->input->getUint('employee', 0);
		$key 	  = $app->input->getString('key', '');
		$imported = $app->input->getBool('imported', true);
		
		if ($id_emp <= 0)
		{
			// no specified employee, use system sync password
			$match = VAPFactory::getConfig()->get('synckey');
		}
		else
		{
			// specified employee, fetch record data
			$employee = JModelVAP::getInstance('employee')->getItem($id_emp);

			if (!$employee)
			{
				UIErrorFactory::raiseError(404, 'Employee not found');
			}

			// use employee sync password
			$match = $employee->synckey;
		}
		
		// compare the specified key with the correct one	
		if (!$match || strcmp($key, $match))
		{
			// invalid key, abort
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		VAPLoader::import('libraries.order.export.factory');

		try
		{
			// get ICS export driver
			$driver = VAPOrderExportFactory::getInstance('ics', 'appointment', [
				'id_employee' => $id_emp,
				'admin'       => true,
				'imported'    => $imported,
			]);
		}
		catch (Exception $e)
		{
			$code = $e->getCode();

			// an error occurred...
			UIErrorFactory::raiseError($code ? $code : 500, $e->getMessage());
		}

		// download ICS file
		$driver->download();

		// terminate execution
		$app->close();
	} 

	/**
	 * End-point used to dispatch the specified CRON JOB.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	integer  id_cron     The ID of the cron to launch.
	 * @param 	string   secure_key  The secure key to execute the command.
	 *
	 * @return 	void
	 */
	function cronjob_listener_rq()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// get request params
		$id_cron    = $input->getUint('id_cron', 0);
		$secure_key = $input->getString('secure_key', '');

		if (empty($secure_key))
		{
			UIErrorFactory::raiseError(400, 'Missing secure key');
		}

		// get CRON JOB model
		$model = JModelVAP::getInstance('cronjob');
		// dispatch cron job
		$status = $model->dispatch($id_cron, $secure_key);

		if (!$status)
		{
			// get error from model
			$error = $model->getError();

			if ($error instanceof Exception)
			{
				UIErrorFactory::raiseError($error->getCode(), $error->getMessage());
			}
			else
			{
				UIErrorFactory::raiseError(500, $error ? $error : JText::translate('ERROR'));	
			}
		}

		// terminate with success
		echo 1;
		$app->close();
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
	 * @return  void
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

	/**
	 * #########################
	 * #     API End-Point     #
	 * #########################
	 * 
	 * This function is the end-point to dispatch events requested from external connections.
	 * It is required to specify all the following values:
	 *
	 * @param 	string 	username  The username for login.
	 * @param 	string 	password  The password for login.
	 * @param 	string 	event     The name of the event to dispatch.
	 * 
	 * It is also possible to pre-send certain arguments to dispatch within the event:
	 *
	 * @param 	array 	args      The arguments of the event (optional).
	 *                            All the specified values are cleansed with string filtering.
	 *
	 * @return 	string            In case of error it is returned a JSON string with the code (errcode) 
	 *                            and the message of the error (error).
	  *                           In case of success the result may vary on the dispatched event.
	 *
	 * @since 	1.7
	 */
	public function api()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// instantiate API Framework
		// leave constructor empty to select default plugins folder: 
		// .../helpers/libraries/apislib/apis/plugins/
		$api = VAPFactory::getApi();

		// check if API connections are allowed, otherwise disable all
		if (!$api->isEnabled())
		{
			// use a HTTP response in place of the JSON one
			UIErrorFactory::raiseError(403, 'API Framework is disabled');
		}

		// flush stored API logs
		JModelVAP::getInstance('apilog')->flush();

		// get credentials
		$username = $input->getString('username');
		$password = $input->getString('password');

		// get event to dispatch
		$event = $input->get('event');

		// try to retrieve the plugin arguments from JSON body
		$args = $input->json->getArray();

		if (!$args)
		{
			// arguments not found, try to retrieve them from the request
			$args = $input->get('args', array(), 'array');
		}

		// create a Login for this user
		$login = new VAPApiLogin($username, $password, $input->server->get('REMOTE_ADDR'));

		// do login
		if (!$api->connect($login))
		{
			// user is not authorized to login
			$api->output($api->getError());

			// terminate the request
			$app->close();
		}

		// user correctly logged in, dispatch the event
		$result = $api->trigger($event, $args);

		// always disconnect the user
		$api->disconnect();

		if (!$result)
		{
			// event error thrown
			$api->output($api->getError());
		}

		// terminate the request
		$app->close();
	}

	/**
	 * This task is used by the administrators when they want to temporarily pause
	 * the e-mail notifications sent whenever a specific cron job faces an error.
	 * 
	 * It is required to specify all the following values:
	 * 
	 * @param   int     id   The ID of the cron job to pause.
	 * @param   string 	key  The cron secure key to prevent a public access.
	 * 
	 * It is also possible to specify the following parameter to choose how long
	 * to pause the e-mail notifications.
	 * 
	 * @param   int  days  The notifications will be stopped for the number of
	 *                     specified days. If not provided, they will be stopped
	 *                     for one week.
	 * 
	 * @return  void
	 */
	public function cron_pause_notif()
	{
		$app = JFactory::getApplication();

		$key = $app->input->get->get('key');

		// first of all, make sure the user is authorized to access this resource
		if (!$key || strcmp($key, VAPFactory::getConfig()->getString('cron_secure_key', '')))
		{
			// wrong secure key or missing
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// get selected cron job
		$id = $app->input->get->getUint('id', 0);

		if (!$id)
		{
			// wrong secure key or missing
			throw new Exception('Missing cron job ID', 403);
		}

		// how long the notification should be stopped?
		$days = $app->input->get->getUint('days', 0);

		if (!$days)
		{
			$lookup = [
				1   => JText::plural('VAP_N_DAYS', 1),
				7   => JText::plural('VAP_N_DAYS', 7),
				14  => JText::plural('VAP_N_WEEKS', 2),
				30  => JText::plural('VAP_N_MONTHS', 1),
				60  => JText::plural('VAP_N_MONTHS', 2),
				365 => JText::plural('VAP_N_YEARS', 1),
			];

			$li = [];

			foreach ($lookup as $days => $label)
			{
				$href = JRoute::rewrite('index.php?option=com_vikappointments&task=cron_pause_notif&id=' . $id . '&key=' . $key . '&days=' . $days . '&Itemid=' . $app->input->getUint('Itemid'));
				$li[] = '<li><a href="' . $href . '">' . $label . '</a></li>';
			}

			// prompt the selection of the pause interval
			echo '<div class="vap-confirmpage order-notice"><p>' . JText::translate('VAPCRONJOBPAUSENOTIF') . '</p><ul style="list-style: none; padding: 0; margin: 10px 0;">' . implode("\n", $li) . '</ul></div>';
			return;
		}

		$model = JModelVAP::getInstance('cronjob');

		// attempt to pause the notifications
		$date = $model->pauseNotifications($id, $days);

		if ($date)
		{
			// pause successful
			$date = JHtml::fetch('date', $date, JText::translate('DATE_FORMAT_LC2'));
			echo '<div class="vap-confirmpage order-good">' . JText::sprintf('VAP_CRON_NOTIF_PAUSE_SUCCESS', $date) . '</div>';
		}
		else
		{
			// an error has occurred
			$error = $model->getError(null, true) ?: 'Unknown.';
			echo '<div class="vap-confirmpage order-error">' . JText::sprintf('VAP_CRON_NOTIF_PAUSE_ERROR', $error) . '</div>';
		}
	}
}

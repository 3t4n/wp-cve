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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments employee search view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmployeesearch extends JModelVAP
{
	/**
	 * Returns the employee details.
	 *
	 * @param 	integer  $id       The employee ID.
	 * @param 	array    $options  An array of options.
	 *
	 * @return 	object   The employee details.
	 */
	public function getEmployee($id, array $options = array())
	{	
		$dispatcher = VAPFactory::getEventDispatcher();

		// inject employee ID within options array, which will be
		// passed to the hooks dispatcher
		$options['id_employee'] = (int) $id;

		$dbo = JFactory::getDbo();

		// inner query to calculate the average rating of the employee
		$rating = $dbo->getQuery(true)
			->select('AVG(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('e.id') . ' = ' . $dbo->qn('re.id_employee'),
				$dbo->qn('re.published') . ' = 1',
			));

		$q = $dbo->getQuery(true);

		$q->select('e.*');
		$q->select('(' . $rating . ') AS ' . $dbo->qn('ratingAVG'));
		$q->select($dbo->qn('eg.name', 'group_name'));
		$q->select($dbo->qn('eg.description', 'group_description'));

		$q->from($dbo->qn('#__vikappointments_employee', 'e'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee_group', 'eg') . ' ON ' . $dbo->qn('e.id_group') . ' = ' . $dbo->qn('eg.id'));

		$q->where($dbo->qn('e.id') . ' = ' . (int) $id);

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed  &$query    Either a query builder or a query string.
		 * @param 	array  &$options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildEmployeeSearchQuery', array(&$q, &$options));

		$dbo->setQuery($q, 0, 1);
		$employee = $dbo->loadObject();

		if (!$employee)
		{
			// employee not found
			$this->setError(JText::translate('VAPEMPNOTFOUNDERROR'));	
			return false;
		}

		// build employee data
		$employee = $this->buildEmployeeData($employee, $options);

		// get employee model
		$model = JModelVAP::getInstance('employee');

		// make sure the employee is active
		if (!$model->isVisible($employee))
		{
			// employee not active or not visible
			$this->setError(JText::translate('VAPEMPNOTFOUNDERROR'));	
			return false;
		}

		// translate employee details
		$this->translate($employee);

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can include further details into the employee object.
		 *
		 * @param 	object  $employee  An object holding the employee details.
		 * @param 	JModel  $model     The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildEmployeeSearchData', array($employee, $this));

		return $employee;
	}

	/**
	 * Returns the list of locations assigned to the given employee.
	 *
	 * @param 	mixed  $id  Either the employee ID or an array of IDs.
	 *
	 * @return 	array  The locations list.
	 */
	public function getLocations($id, $id_service = null)
	{
		$locations = array();

		if (!$id)
		{
			return $locations;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);
		
		// load all locations used by the employee
		$q->select($dbo->qn('l.id'));
		$q->from($dbo->qn('#__vikappointments_employee_location', 'l'));
		$q->leftjoin($dbo->qn('#__vikappointments_emp_worktime', 'w') . ' ON ' . $dbo->qn('l.id') . ' = ' . $dbo->qn('w.id_location'));

		if ($id_service)
		{
			// filter working days by service
			$q->where($dbo->qn('w.id_service') . ' = ' . (int) $id_service);
		}

		if (is_array($id))
		{
			// get all working days assigned to the employees
			$q->where($dbo->qn('w.id_employee') . ' IN (' . implode(',', $id) . ')');

			// then look for global locations or locations created by the
			// specified employees
			$q->andWhere(array(
				$dbo->qn('l.id_employee') . ' <= 0',
				$dbo->qn('l.id_employee') . ' IN (' . implode(',', $id) . ')',
			), 'OR');
		}
		else
		{
			// get all working days assigned to this employee
			$q->where($dbo->qn('w.id_employee') . ' = ' . (int) $id);

			// then look for global locations or locations created by the
			// specified employee
			$q->andWhere(array(
				$dbo->qn('l.id_employee') . ' <= 0',
				$dbo->qn('l.id_employee') . ' = ' . (int) $id,
			), 'OR');
		}

		$q->group($dbo->qn('l.id'));

		$dbo->setQuery($q);

		if ($rows = $dbo->loadColumn())
		{	
			// create location model only in case of records
			$model = JModelVAP::getInstance('location');

			// iterate locations found and fetch info one by one
			foreach ($rows as $id_location)
			{
				// get details through the location model
				$locations[] = $model->getInfo($id_location);
			}
		}

		return $locations;
	}

	/**
	 * Returns an object holding the details of the calendar.
	 *
	 * @param 	array 	&$options  An array of options.
	 *
	 * @return 	object
	 */
	public function getCalendar(&$options)
	{
		$config     = VAPFactory::getConfig();
		$dispatcher = VAPFactory::getEventDispatcher();

		// set number of visible months
		$options['numcal'] = $config->getUint('numcals');

		// get current date at midnight
		$date = new JDate('today 00:00:00', VikAppointments::getUserTimezone());
		// Back to the first day of the month.
		// Do not use "first day of" modifier because PHP 7.3
		// seems to experience some strange behaviors.
		$date->modify($date->format('Y-m-01', true));

		// check whether an initial date was set and it is in the future
		if (empty($options['date']) || $options['date'] < $date->format('Y-m-d', $local = true))
		{
			// get initial month/year from configuration
			$month = $config->getUint('calsfrom');
			$year  = $config->getUint('calsfromyear');

			// make sure the selected month and year are not in the past
			if (!$month || !$year || JDate::getInstance("{$year}-{$month}-01") < $date->format('Y-m-d', $local = true))
			{
				// use current month and year
				$month = (int) $date->format('n', $local = true);
				$year  = (int) $date->format('Y', $local = true);
			}

			// set initial date
			$options['date'] = JDate::getInstance("{$year}-{$month}-01")->format('Y-m-d');
		}

		// get service details
		$assocModel = JModelVAP::getInstance('serempassoc');
		$service = $assocModel->getOverrides($options['id_ser'], $options['id_emp']);

		if ($service && !VAPDateHelper::isNull($service->start_publishing))
		{
			// fetch employee/system timezone
			$emp_tz = JModelVAP::getInstance('employee')->getTimezone($options['id_emp']);

			// adjust start publishing to given timezone
			$startPub = new JDate($service->start_publishing);
			$startPub->setTimezone(new DateTimeZone($emp_tz));

			// compare service start publishing with start date
			if ($startPub->format('Y-m-01', $local = true) > $options['date'])
			{
				// use service start publishing
				$options['date'] = $startPub->format('Y-m-01', $local = true);
			}
		}

		if (empty($options['start']) || $options['start'] < $options['date'])
		{
			// use the default initial date
			$options['start'] = $options['date'];
		}

		// fetch calendar layout according to the configuration of the program
		$options['layout'] = $config->get('calendarlayoutsite', 'monthly');

		/**
		 * Trigger hook before fetching the data needed to display a calendar.
		 * Useful, in example, to switch the calendar layout at runtime by altering
		 * the related array attribute.
		 *
		 * @param 	array  &$options  An array of calendar options.
		 *
		 * @return 	void
		 * 
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBeforeFetchCalendarData', array(&$options));

		if (empty($options['layout']))
		{
			$options['layout'] = 'monthly';
		}

		// flag search as performed by the customer
		$options['admin'] = false;

		if ($options['layout'] == 'weekly')
		{
			// load apposite front-end model
			$calModelName = 'calendarweek';
		}
		else
		{
			// load default model used in the back-end too
			$calModelName = 'calendar';
		}

		// obtain calendar data through back-end model
		$calendar = JModelVAP::getInstance($calModelName);

		// make sure the requested model exists and owns a valid callback
		if ($calendar && method_exists($calendar, 'getCalendar'))
		{
			$data = $calendar->getCalendar($options);
		}
		else
		{
			// use an empty object, which will be probably filled by
			// the "after fetch" hook
			$data = new stdClass;
		}

		$data->layout = $options['layout'];

		// include months select options
		$data->select = array();

		$dt = new JDate($options['date']);

		for ($i = 0; $i < $config->getUint('nummonths', 0); $i++)
		{
			// get date string
			$k = $dt->format('Y-m-01');
			// register select option
			$data->select[$k] = $dt->monthToString($dt->format('n'));
			// go to next month
			$dt->modify('+1 month');
		}

		/**
		 * Trigger hook after fetching the data needed to display a calendar.
		 * Useful, in example, to include additional information about the calendar
		 * or to implement a new layout type.
		 *
		 * @param 	object  $data     The object holding the calendar data.
		 * @param 	array   $options  An array of calendar options.
		 *
		 * @return 	void
		 * 
		 * @since 	1.7
		 */
		$dispatcher->trigger('onAfterFetchCalendarData', array($data, $options));

		return $data;
	}

	/**
	 * Calculates the resulting availability timeline according
	 * to the specified search options.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	mixed 	The resulting renderer.
	 */
	public function getTimeline($options)
	{
		if (empty($options['admin']))
		{
			/**
			 * Validate service restrictions.
			 *
			 * @since 1.6.5
			 */
			VAPLoader::import('libraries.models.restrictions');
			
			if (!VAPSpecialRestrictions::canBookService($options['id_ser'], $options['date'], $restr))
			{
				if (VikAppointments::isUserLogged())
				{
					// the user already reached the maximum threshold
					$err = JText::sprintf(
						'VAPRESTRICTIONLIMITREACHED',
						$restr->maxapp,
						strtolower(JText::translate('VAPMANAGERESTRINTERVAL' . strtoupper($restr->interval)))
					);
				}
				else
				{
					// login needed before to see the available slots
					$err = JText::translate('VAPRESTRICTIONLIMITGUEST');
				}

				// register error message
				$this->setError($err);
				return false;
			}
		}

		VAPLoader::import('libraries.availability.manager');
		VAPLoader::import('libraries.availability.timeline.factory');

		// create availability search instance
		$search = VAPAvailabilityManager::getInstance($options['id_ser'], $options['id_emp'], $options);

		// get details of the selected service
		$service = JModelVAP::getInstance('serempassoc')->getOverrides($search->get('id_service'), $search->get('id_employee'));

		// define default options
		$options['people'] = isset($options['people']) ? $options['people'] : 1;

		if ($service)
		{
			// number of people cannot be lower than the minimum amount of the service
			$options['people'] = max(array($service->min_per_res, (int) $options['people']));
			// number of people cannot be higher than the maximum amount of the service
			$options['people'] = min(array($service->max_per_res, (int) $options['people']));
		}

		$options['id_res'] = isset($options['id_res']) ? $options['id_res'] : 0;

		try
		{
			// create timeline parser instance
			$parser = VAPAvailabilityTimelineFactory::getParser($search);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		// elaborate timeline
		$timeline = $parser->getTimeline($options['date'], $options['people'], $options['id_res']);

		if (!$timeline)
		{
			// propagate error message
			$this->setError($parser->getError());

			return false;
		}

		/**
		 * Do not block the times with the appointments within the cart when the
		 * system does not allow multiple bookings. This way, in case the users 
		 * go back through the browser history, they are still allowed to book
		 * the previously selected time.
		 * 
		 * @since 1.7.4
		 */
		if (empty($options['admin']) && VAPFactory::getConfig()->getBool('enablecart'))
		{
			// get cart handler
			$cart = JModelVAP::getInstance('cart')->getCart();

			// extract duration
			$duration = $service ? $service->duration : 5;

			foreach ($timeline as $level)
			{
				foreach ($level as $time)
				{
					// get check-in date time in UTC timezone, since the times in the cart are
					// always stored by using the UTC offset
					$checkin = $time->checkin('Y-m-d H:i:s', 'UTC');

					// check whether this time slot intersects an appointment within the cart
					$index = $cart->indexOf($search->get('id_service'), $search->get('id_employee'), $checkin, $duration);

					if ($index != -1)
					{
						// there's a conflict with a booked appointment, mark the time block as occupied
						$time->setStatus(false);
					}
				}
			}
		}

		try
		{
			// create timeline renderer instance
			$renderer = VAPAvailabilityTimelineFactory::getRenderer($timeline);
		}
		catch (Exception $e)
		{
			// register exception as error
			$this->setError($e);

			return false;
		}

		return $renderer; 
	}

	/**
	 * Sends an e-mail to the specified employee.
	 *
	 * @param 	integer  $id_employee  The employee to notify.
	 * @param 	string   $name         The customer name (sender name).
	 * @param 	string   $email        The customer e-mail (sender mail).
	 * @param 	string 	 $content      The e-mail content (plain text).
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function askQuestion($id_employee, $name, $email, $content)
	{
		// validate user data
		if (!$name || !$email)
		{
			// register missing fields error
			$this->setError(JText::translate('VAPCONFAPPREQUIREDERROR'));
			return false;
		}

		// make sure we have a valid e-mail
		if (!VikAppointments::validateUserEmail($email))
		{
			// invalid e-mail
			$this->setError(JText::translate('VAPCONFAPPREQUIREDMAILERROR'));
			return false;
		}

		// strip HTML tags from content
		$content = strip_tags($content);

		if (!$content)
		{
			// missing content
			$this->setError(JText::translate('VAPQUICKCONTACTNOCONTENT'));
			return false;
		}

		// use model to access the employee details
		$employee = JModelVAP::getInstance('employee')->getItem((int) $id_employee);

		if (!$employee)
		{
			// employee not found
			$this->setError(JText::translate('VAPEMPNOTFOUNDERROR'));
			return false;
		}

		if (!$employee->quick_contact)
		{
			// the employee doesn't allow quick contact messages
			$this->setError(JText::translate('VAPEMPNOTREACHABLE'));
			return false;
		}
		
		// fetch e-mail subject
		$subject = JText::translate('VAPEMPQUICKCONTACTSUBJECT');
		$is_html = false;

		// define sender details
		$sender = array('name' => $name, 'email' => $email);

		// set list of recipients
		$recipients = array($employee->email);

		// prepare hook arguments
		$args = array($id_employee, &$subject, &$content, &$is_html, &$sender, &$recipients, $this);

		try
		{
			/**
			 * Trigger event to allow the plugins to manipulate quick contact messages
			 * for the given employee. It is possible to throw an exception to avoid
			 * sending the message. The exception text will be used as error message.
			 *
			 * @param 	string 	 $id_emp       The employee ID.
			 * @param 	string 	 &$subject     The e-mail subject.
			 * @param 	string 	 &$content     The e-mail content (the customer message). 
			 * @param 	boolean  &$is_html     True if the e-mail should support HTML tags.
			 * @param 	string 	 &$sender      The e-mail sender details (@since 1.7). 
			 * @param 	array    &$recipients  A list of recipient e-mails (@since 1.7).
			 * @param 	JModel   $model        The current model (@since 1.7).
			 *
			 * @return 	boolean  False to prevent e-mail sending (@since 1.7).
			 *
			 * @throws 	Exception
			 *
			 * @since 	1.6
			 */
			if (VAPFactory::getEventDispatcher()->false('onBeforeQuickContactSend', $args))
			{
				// someone prevented the e-mail sending
				return false;
			}
		}
		catch (Exception $e)
		{
			// catch the error and register the message set
			$this->setError($e->getMessage());
			return false;
		}

		// By default e-mails are always sent by using the customer e-mail/name
		// as sender details. In case a server rejects certain e-mails, it is
		// possible to use the hook previously described to change the sender
		// details with other ones (such as the global sender e-mail).

		$sent = false;

		// iterate all recipients
		foreach ($recipients as $recipient)
		{
			// try to send the e-mail
			$sent = VAPApplication::getInstance()->sendMail(
				$sender['email'], // sender e-mail
				$sender['name'],  // sender name
				$recipient,       // recipient e-mail
				$email,           // reply-to e-mail
				$subject,         // e-mail subject
				$content,         // e-mail content
				null,             // attachments
				$is_html          // is HTML flag
			) || $sent;
		}

		if (!$sent)
		{
			/**
			 * Register generic error to inform the user that the website
			 * is currently unable to send messages.
			 *
			 * @since 1.7
			 */
			$this->setError(JText::translate('VAPMAILERR'));
			return false;
		}

		return true;
	}

	////////////////////////////////////////
	//////////// HELPER METHODS ////////////
	////////////////////////////////////////

	/**
	 * Applies additional queries to fill the employee object
	 * with other data, such as the supported locations.
	 *
	 * @param 	object  $employee  The employee details object.
	 * @param   array   $options   An array of options.
	 *
	 * @return 	object  The resulting employee object.
	 */
	protected function buildEmployeeData($employee, $options)
	{
		$dbo = JFactory::getDbo();

		if (!$employee->timezone)
		{
			// use default system timezone
			$employee->timezone = JFactory::getApplication()->get('offset', 'UTC');
		}

		if ($employee->id_group)
		{
			// register group data in a different object
			$group = new stdClass;
			$group->id          = $employee->id_group;
			$group->name        = $employee->group_name;
			$group->description = $employee->group_description;

			$employee->group = $group;
		}
		else
		{
			// no assigned group
			$employee->group = null;
		}

		// round rating to the closest .0 or .5
		$employee->rating = VikAppointments::roundHalfClosest($employee->ratingAVG);

		// load employee reviews
		$employee->reviews = VikAppointments::loadReviews('employee', $employee->id);

		// get rid of duplicates
		unset($employee->id_group);
		unset($employee->group_name);
		unset($employee->group_description);

		// load all services supported by the employee
		$employee->services = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('g.name', 'group_name'))
			->select($dbo->qn('s.id'))
			->from($dbo->qn('#__vikappointments_ser_emp_assoc', 'a'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('a.id_service'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'))
			->where($dbo->qn('a.id_employee') . ' = ' . (int) $employee->id)
			->where($dbo->qn('s.published') . ' = 1')
			->order($dbo->qn('g.ordering') . ' ASC')
			->order($dbo->qn('s.ordering') . ' ASC');

		/**
		 * Retrieve only the services that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.6
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('s.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		$dbo->setQuery($q);

		if ($services = $dbo->loadObjectList())
		{
			// get service-employee association model
			$assocModel = JModelVAP::getInstance('serempassoc');

			// get current date time
			$now = JFactory::getDate();

			foreach ($services as $s)
			{
				// get service details
				$service = $assocModel->getOverrides($s->id, $employee->id);

				if (!$service)
				{
					// It is totally improbable to enter here, because the services
					// list have been already loaded from the previous query...
					continue;
				}

				// validate service end publishing
				if (VAPDateHelper::isNull($service->end_publishing)
					|| $service->end_publishing > $now)
				{
					if ($service->id_group)
					{
						// register group name too
						$service->groupName = $s->group_name;
					}

					// service published, register it
					$employee->services[] = $service;
				}
			}
		}

		if (empty($options['id_service']) || $options['id_service'] <= 0)
		{
			$options['id_service'] = null;

			if (count($employee->services))
			{
				// use the first available service
				$options['id_service'] = $employee->services[0]->id;
			}
		}

		// fetch employee locations (filter by service if needed)
		$employee->locations = $this->getLocations($employee->id, $options['id_service']);

		// load options assigned to the selected service
		if ($options['id_service'])
		{
			// get service search view model instance
			$serviceSearchModel = JModelVAP::getInstance('servicesearch');
			// load options
			$employee->options = $serviceSearchModel->getOptions($options['id_service']);
		}
		else
		{
			$employee->options = array();
		}

		return $employee;
	}

	/**
	 * Translates the employee details.
	 *
	 * @param 	object  &$employee  The row to translate.
	 *
	 * @return 	void
	 */
	protected function translate(&$employee)
	{
		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang') == false)
		{
			return;
		}
		
		$langtag = JFactory::getLanguage()->getTag();

		// get translator
		$translator = VAPFactory::getTranslator();

		// translate employee for the given language
		$emp_tx = $translator->translate('employee', $employee->id, $langtag);

		if ($emp_tx)
		{
			$employee->nickname = $emp_tx->nickname;
			$employee->note     = $emp_tx->note;
		}

		if ($employee->group)
		{
			// translate group for the given language
			$grp_tx = $translator->translate('empgroup', $employee->group->id, $langtag);

			if ($grp_tx)
			{
				$employee->group->name        = $grp_tx->name;
				$employee->group->description = $grp_tx->description;
			}
		}

		$service_ids = array();
		$group_ids   = array();

		// map all existing services and groups
		foreach ($employee->services as $service)
		{
			$service_ids[] = $service->id;

			if ($service->id_group > 0)
			{
				$group_ids[] = $service->id_group;
			}
		}

		// preload services translations
		$serLang = $translator->load('service', array_unique($service_ids), $langtag);
		// preload groups translations
		$grpLang = $translator->load('group', array_unique($group_ids), $langtag);

		// apply translations found
		foreach ($employee->services as $service)
		{
			// get service translation
			$ser_tx = $serLang->getTranslation($service->id, $langtag);

			if ($ser_tx)
			{
				// use service name and description translations
				$service->name        = $ser_tx->name;
				$service->description = $ser_tx->description;

				/**
				 * The description might have overwritten the override
				 * for this specific employee/service combination. For
				 * this reason we need to re-apply it, if existing.
				 * 
				 * @since 1.7
				 */
				if (!empty($service->overrideDescription))
				{
					$service->description .= "\n" . $service->overrideDescription;
				}
			}

			if ($service->id_group > 0)
			{
				// get group translation
				$grp_tx = $grpLang->getTranslation($service->id_group, $langtag);

				if ($grp_tx)
				{
					// use group name translation
					$service->groupName = $grp_tx->name;
				}
			}
		}
	}
}

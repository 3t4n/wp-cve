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
 * VikAppointments service search view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelServicesearch extends JModelVAP
{
	/**
	 * Returns the service details.
	 *
	 * @param 	integer  $id       The service ID.
	 * @param 	array    $options  An array of options.
	 *
	 * @return 	object   The service details.
	 */
	public function getService($id, array $options = array())
	{	
		$dispatcher = VAPFactory::getEventDispatcher();

		// inject service ID within options array, which will be
		// passed to the hooks dispatcher
		$options['id_service'] = (int) $id;

		$dbo = JFactory::getDbo();

		// inner query to calculate the average rating of the service
		$rating = $dbo->getQuery(true)
			->select('AVG(' . $dbo->qn('re.rating') . ')')
			->from($dbo->qn('#__vikappointments_reviews', 're'))
			->where(array(
				$dbo->qn('s.id') . ' = ' . $dbo->qn('re.id_service'),
				$dbo->qn('re.published') . ' = 1',
			));

		$q = $dbo->getQuery(true);

		$q->select('s.*');
		$q->select('(' . $rating . ') AS ' . $dbo->qn('ratingAVG'));
		$q->select($dbo->qn('sg.name', 'group_name'));
		$q->select($dbo->qn('sg.description', 'group_description'));

		$q->from($dbo->qn('#__vikappointments_service', 's'));
		$q->leftjoin($dbo->qn('#__vikappointments_group', 'sg') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('sg.id'));

		$q->where($dbo->qn('s.id') . ' = ' . (int) $id);

		// make sure the service is not yet expired
		$q->andWhere(array(
			$dbo->qn('s.end_publishing') . ' IS NULL',
			$dbo->qn('s.end_publishing') . ' = ' . $dbo->q($dbo->getNullDate()),
			$dbo->qn('s.end_publishing') . ' > ' . $dbo->q(JFactory::getDate()->toSql()),
		), 'OR');

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
		$dispatcher->trigger('onBuildServiceSearchQuery', array(&$q, &$options));

		$dbo->setQuery($q, 0, 1);
		$service = $dbo->loadObject();

		if (!$service)
		{
			// service not found
			$this->setError(JText::translate('VAPSERNOTFOUNDERROR'));	
			return false;
		}

		// build service data
		$service = $this->buildServiceData($service, $options);

		// translate service details
		$this->translate($service);

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can include further details into the service object.
		 *
		 * @param 	object  $service  An object holding the service details.
		 * @param 	JModel  $model    The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildServiceSearchData', array($service, $this));

		return $service;
	}

	/**
	 * Loads all the options assigned to the specified service.
	 *
	 * @param 	integer  $id  The service ID.
	 *
	 * @return 	array    An array of extra options.
	 */
	public function getOptions($id)
	{
		$dbo = JFactory::getDbo();

		$options = array();

		$q = $dbo->getQuery(true)
			->select('o.*')
			->select(array(
				$dbo->qn('v.id', 'id_var'),
				$dbo->qn('v.name', 'var_name'),
				$dbo->qn('v.inc_price'),
				$dbo->qn('v.inc_duration'),
				$dbo->qn('g.name', 'group_name'),
				$dbo->qn('g.description', 'group_description'),
			))
			->from($dbo->qn('#__vikappointments_option', 'o'))
			->leftjoin($dbo->qn('#__vikappointments_ser_opt_assoc', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_option'))
			->leftjoin($dbo->qn('#__vikappointments_option_value', 'v') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('v.id_option'))
			->leftjoin($dbo->qn('#__vikappointments_option_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('o.id_group'))
			->where(array(
				$dbo->qn('a.id_service') . ' = ' . (int) $id,
				$dbo->qn('o.published') . ' = 1',
			))
			->order(array(
				$dbo->qn('g.ordering') . ' ASC',
				$dbo->qn('o.ordering') . ' ASC',
				$dbo->qn('v.ordering') . ' ASC',
			));

		/**
		 * Retrieve only the options that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.7.3
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('o.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed    &$query  Either a query builder or a query string.
		 * @param 	integer  $id      The service ID.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.3
		 */
		VAPFactory::getEventDispatcher()->trigger('onBuildServiceSearchOptionsQuery', array(&$q, $id));
		
		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no assigned options
			return array();
		}

		$options = array();

		$grp_ids = array();
		$opt_ids = array();
		$var_ids = array();

		foreach ($rows as $opt)
		{
			if (!isset($options[$opt->id_group]))
			{
				$group = new stdClass;
				$group->id          = (int) $opt->id_group;
				$group->name        = $opt->group_name;
				$group->description = $opt->group_description;
				$group->options     = array();

				$options[$opt->id_group] = $group;

				if ($group->id)
				{
					$grp_ids[] = $group->id;
				}
			}

			if (!isset($options[$opt->id_group]->options[$opt->id]))
			{
				// create option
				$opt->variations = array();
				$options[$opt->id_group]->options[$opt->id] = $opt;

				$opt_ids[] = $opt->id;
			}

			if ($opt->id_var)
			{
				// create option variation
				$var = new stdClass;
				$var->id       = $opt->id_var;
				$var->name     = $opt->var_name;
				$var->price    = $opt->inc_price;
				$var->duration = $opt->inc_duration;

				$options[$opt->id_group]->options[$opt->id]->variations[] = $var;

				$var_ids[] = $var->id;
			}
		}

		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * DO NOT move the options translations within the `translate` method provided by this
		 * model because other classes might want to retrieve the options. That's why the 
		 * translation process has been merged within the method used to fetch them.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang'))
		{
			$translator = VAPFactory::getTranslator();
			$langtag    = JFactory::getLanguage()->getTag();

			// preload groups translations
			$grpLang = $translator->load('optiongroup', array_unique($grp_ids), $langtag);
			// preload options translations
			$optLang = $translator->load('option', array_unique($opt_ids), $langtag);
			// preload variations translations
			$varLang = $translator->load('optionvar', array_unique($var_ids), $langtag);

			foreach ($options as $group)
			{
				if ($group->id)
				{
					// get group translation
					$grp_tx = $grpLang->getTranslation($group->id, $langtag);

					if ($grp_tx)
					{
						// apply option translation
						$group->name        = $grp_tx->name;
						$group->description = $grp_tx->description;
					}
				}

				foreach ($group->options as $opt)
				{
					// get option translation
					$opt_tx = $optLang->getTranslation($opt->id, $langtag);

					if ($opt_tx)
					{
						// apply option translation
						$opt->name        = $opt_tx->name;
						$opt->description = $opt_tx->description;
					}

					foreach ($opt->variations as $var)
					{
						// get variation translation
						$var_tx = $varLang->getTranslation($var->id, $langtag);

						if ($var_tx)
						{
							// apply option translation
							$var->name = $var_tx->name;
						}
					}
				}
			}
		}

		$options = array_values($options);

		if (count($options) && !$options[0]->id)
		{
			// always move options without group at the end of the list
			$options[] = array_shift($options);
		}
		
		return $options;
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
		// fetch calendar availability through the employee search model,
		// since the behavior is exactly the same
		return JModelVAP::getInstance('employeesearch')->getCalendar($options);
	}

	/**
	 * Sends an e-mail to the specified service.
	 *
	 * @param 	integer  $id_service  The service to notify.
	 * @param 	string   $name        The customer name (sender name).
	 * @param 	string   $email       The customer e-mail (sender mail).
	 * @param 	string 	 $content     The e-mail content (plain text).
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function askQuestion($id_service, $name, $email, $content)
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

		// use model to access the service details
		$service = JModelVAP::getInstance('service')->getItem((int) $id_service);

		if (!$service)
		{
			// service not found
			$this->setError(JText::translate('VAPSERNOTFOUNDERROR'));
			return false;
		}

		if (!$service->quick_contact)
		{
			// the service doesn't allow quick contact messages
			$this->setError(JText::translate('VAPSERNOTREACHABLE'));
			return false;
		}
		
		// fetch e-mail subject
		$subject = JText::sprintf('VAPSERQUICKCONTACTSUBJECT', $service->name);
		$is_html = false;

		// define sender details
		$sender = [
			'name'  => $name,
			'email' => VikAppointments::getSenderMail(),
		];

		// use all administrator e-mails as recipient.
		$recipients = VikAppointments::getAdminMailList();

		// prepare hook arguments
		$args = array($id_service, &$subject, &$content, &$is_html, &$sender, &$recipients, $this);

		try
		{
			/**
			 * Trigger event to allow the plugins to manipulate quick contact messages
			 * for the administrators. It is possible to throw an exception to avoid
			 * sending the message. The exception text will be used as error message.
			 *
			 * @param 	string 	 $id_ser       The service ID.
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
			if (VAPFactory::getEventDispatcher()->false('onBeforeQuickContactServiceSend', $args))
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
	 * Applies additional queries to fill the service object
	 * with other data, such as the supported locations.
	 *
	 * @param 	object  $service  The service details object.
	 * @param   array   $options  An array of options.
	 *
	 * @return 	object  The resulting service object.
	 */
	protected function buildServiceData($service, $options)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		if ($service->id_group)
		{
			// register group data in a different object
			$group = new stdClass;
			$group->id          = $service->id_group;
			$group->name        = $service->group_name;
			$group->description = $service->group_description;

			$service->group = $group;
		}
		else
		{
			// no assigned group
			$service->group = null;
		}

		// round rating to the closest .0 or .5
		$service->rating = VikAppointments::roundHalfClosest($service->ratingAVG);

		// load service reviews
		$service->reviews = VikAppointments::loadReviews('service', $service->id);

		// get rid of duplicates
		unset($service->id_group);
		unset($service->group_name);
		unset($service->group_description);

		// load all employees supported by the service
		$q = $dbo->getQuery(true)
			->select('e.*')
			->from($dbo->qn('#__vikappointments_ser_emp_assoc', 'a'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
			->where($dbo->qn('a.id_service') . ' = ' . (int) $service->id)
			->order($dbo->qn('a.ordering') . ' ASC');

		if ($service->choose_emp)
		{
			// take only listable employees
			$q->where($dbo->qn('e.listable') . ' = 1');
		}

		// take only those employees with lifetime license or that are not expired
		$q->andWhere(array(
			$dbo->qn('e.active_to') . ' = -1',
			$dbo->qn('e.active_to_date') . ' >= ' . $dbo->q(JFactory::getDate()->toSql()),
		), 'OR');

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed   &$query   Either a query builder or a query string.
		 * @param   object  $service  The service details.
		 * @param 	array   $options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.4
		 */
		$dispatcher->trigger('onBuildServiceSearchEmployeesQuery', array(&$q, $service, $options));

		$dbo->setQuery($q);
		$service->employees = $dbo->loadObjectList();

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can include further details into the service object.
		 *
		 * @param   array   &$employees  A list of fetched employees.
		 * @param 	object  $service     An object holding the service details.
		 * @param 	array   $options     An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7.4
		 */
		$dispatcher->trigger('onBuildServiceSearchEmployeesData', array(&$service->employees, $service, $options));

		/**
		 * Pick the first available employee only in case the random selection is disabled.
		 *
		 * @since 1.7
		 */
		if ((empty($options['id_employee']) || $options['id_employee'] <= 0) && !$service->random_emp)
		{
			$options['id_employee'] = null;

			if ($service->choose_emp && count($service->employees))
			{
				// use the first available service
				$options['id_employee'] = $service->employees[0]->id;
			}
		}

		if ($options['id_employee'])
		{
			// get service-employee overrides
			$ov = JModelVAP::getInstance('serempassoc')->getOverrides($service->id, $options['id_employee']);

			if ($ov)
			{
				// overwrite original service data with the overrides found
				foreach ($ov as $k => $v)
				{
					$service->{$k} = $v;
				}
			}
		}

		$service->locations = array();

		/**
		 * The locations should be retrieved even if the employee is not choosable.
		 * In this case, we should obtain the locations of all the employees
		 * assigned to the current service.
		 *
		 * @since 1.6
		 */
		if ($options['id_employee'] > 0 || count($service->employees))
		{
			if ($options['id_employee'])
			{
				// use selected employee
				$tmp = $options['id_employee'];
			}
			else
			{
				// use all employees
				$tmp = array_map(function($e)
				{
					return (int) $e->id;
				}, $service->employees);
			}

			// fetch employee locations and filter by service
			$service->locations = JModelVAP::getInstance('employeesearch')->getLocations($tmp, $service->id);
		}

		// load options
		$service->options = $this->getOptions($options['id_service']);

		return $service;
	}

	/**
	 * Translates the service details.
	 *
	 * @param 	object  &$service  The row to translate.
	 *
	 * @return 	void
	 */
	protected function translate(&$service)
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

		// translate service for the given language
		$ser_tx = $translator->translate('service', $service->id, $langtag);

		if ($ser_tx)
		{
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

		if ($service->group)
		{
			// translate group for the given language
			$grp_tx = $translator->translate('group', $service->group->id, $langtag);

			if ($grp_tx)
			{
				$service->group->name        = $grp_tx->name;
				$service->group->description = $grp_tx->description;
			}
		}

		// translate employees only whether their selection is allowed
		if ($service->choose_emp)
		{
			$employee_ids = array();

			// map all existing employees
			foreach ($service->employees as $employee)
			{
				$employee_ids[] = $employee->id;
			}

			// preload employees translations
			$empLang = $translator->load('employee', array_unique($employee_ids), $langtag);

			// apply translations found
			foreach ($service->employees as $employee)
			{
				// get employee translation
				$emp_tx = $empLang->getTranslation($employee->id, $langtag);

				if ($emp_tx)
				{
					// use employee name and description translations
					$employee->nickname = $emp_tx->nickname;
					$employee->note     = $emp_tx->note;
				}
			}
		}
	}
}

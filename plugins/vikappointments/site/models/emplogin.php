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
 * VikAppointments employee area view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmplogin extends JModelVAP
{
	/**
	 * The list view pagination object.
	 *
	 * @var JPagination
	 */
	protected $pagination = null;

	/**
	 * The total number of fetched rows.
	 *
	 * @var integer
	 */
	protected $total = 0;

	/**
	 * Returns an array of services assigned to the current logged employee.
	 *
	 * @return 	array  An array of services.
	 */
	public function getServices()
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$dbo = JFactory::getDbo();

		// get employee services
		$q = $dbo->getQuery(true)
			->select('s.*')
			->select(array(
				$dbo->qn('a.rate', 'price'),
				$dbo->qn('a.duration'),
				$dbo->qn('g.name', 'group_name'),
			))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('a.id_service'))
			->where($dbo->qn('a.id_employee') . ' = ' . $auth->id)
			->order(array(
				$dbo->qn('g.ordering') . ' ASC',
				$dbo->qn('s.ordering') . ' ASC',
			));
		
		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Returns an array of incoming appointments.
	 *
	 * @param 	array  $options  An array of options.
	 *
	 * @return 	array  An array of appointments.
	 */
	public function getAppointments(array $options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$dbo = JFactory::getDbo();

		$options['start'] = !isset($options['start']) ? 0 : $options['start'];
		$options['limit'] = !isset($options['limit']) ? $auth->getSettings()->listlimit : $options['limit'];

		// get any reserved codes
		$reserved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1)); 

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS r.*')
			->select($dbo->qn('s.name', 'service_name'))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'))
			->where(array(
				$dbo->qn('r.id_employee') . ' = ' . $auth->id,
				$dbo->qn('r.id_parent') . ' <> -1',
				$dbo->qn('r.closure') . ' = 0',
			))
			->order($dbo->qn('r.checkin_ts') . ' ' . $auth->getSettings()->listordering);

		// take only the upcoming appointments
		$q->where(sprintf(
			'DATE_ADD(%s, INTERVAL (%s + %s) MINUTE) > %s',
			$dbo->qn('r.checkin_ts'),
			$dbo->qn('r.duration'),
			$dbo->qn('r.sleep'),
			$dbo->q(JFactory::getDate()->toSql())
		));

		if ($reserved)
		{
			// filter by reserved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $reserved)) . ')');
		}
		
		$dbo->setQuery($q, $options['start'], $options['limit']);
		$rows = $dbo->loadAssocList();

		if ($rows)
		{	
			// fetch pagination
			$this->getPagination($options);
		}

		return $rows;
	}

	/**
	 * Returns the list pagination.
	 *
	 * @param 	array  $options  An array of options.
	 *
	 * @return  JPagination
	 */
	public function getPagination(array $options = array())
	{
		if (!$this->pagination)
		{
			jimport('joomla.html.pagination');
			$dbo = JFactory::getDbo();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->total = (int) $dbo->loadResult();
			$this->pagination = new JPagination($this->total, $options['start'], $options['limit']);
		}

		return $this->pagination;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// load employee preferences
		$settings = $auth->getSettings();

		// set number of visible months
		$options['numcal'] = $settings->numcals;

		// get current date at midnight
		$date = new JDate('today 00:00:00', VikAppointments::getUserTimezone());
		// Back to the first day of the month.
		// Do not use "first day of" modifier because PHP 7.3
		// seems to experience some strange behaviors.
		$date->modify($date->format('Y-m-01', true));

		// check whether an initial date was set
		if (empty($options['date']))
		{
			// get initial month and year from configuration
			$month = $settings->firstmonth;
			$year  = $date->format('Y', true);

			// make sure the selected month is not in the past
			if ($month >= 1 && JDate::getInstance("{$year}-{$month}-01") < $date->format('Y-m-d', $local = true))
			{
				// use current month and year
				$month = (int) $date->format('n', $local = true);
				$year  = (int) $date->format('Y', $local = true);
			}

			if ($month < 1)
			{
				// in case of invalid month, use the current one
				$month = $date->format('m', true);
			}

			// set initial date
			$options['date'] = JDate::getInstance("{$year}-{$month}-01")->format('Y-m-d');
		}

		// set initial date
		$options['start'] = $options['date'];

		// grant administrator rights
		$options['admin'] = true;

		// obtain calendar data through back-end model
		$calendar = JModelVAP::getInstance('calendar');
		$data = $calendar->getCalendar($options);

		// include months select options
		$data->select = array();

		$dt = new JDate($options['date']);

		for ($i = 1; $i <= 12; $i++)
		{
			// update month
			$dt->modify($dt->format('Y') . '-' . $i . '-01');

			// get date string
			$k = $dt->format('Y-m-01');
			// register select option
			$data->select[$k] = $dt->monthToString($i);
		}

		return $data;
	}

	/**
	 * Helper method used to create a new employee record
	 * after a successful registration.
	 *
	 * @param 	array  $args  The user details.
	 *
	 * @return 	boolean
	 */
	public function register(array $args)
	{
		$dbo = JFactory::getDbo();
		
		// check whether the employee should be immediately listable
		$listable = VAPEmployeeAreaManager::getSignUpStatus() == 2 ? 1 : 0;

		// lifetime license by default
		$active_to = -1;

		if (!$listable)
		{
			// not listable, set the subscription to pending
			$active_to = 0;
		}

		// Even if the user is not active, it is still assigned to a specific ID.
		// We should recover the user ID that matches the username specified in the args.
		if (!isset($args['id']) || (int) $args['id'] <= 0)
		{
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__users'))
				->where($dbo->qn('username') . ' = ' . $dbo->q($args['username']));

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				/**
				 * Do not proceed with the employee creation in case
				 * the user registration failed (e.g. due to a duplicated e-mail).
				 *
				 * @since 1.6.2
				 */
				return false;
			}

			$args['id'] = (int) $dbo->loadResult();
		}

		$data = array();
		$data['firstname'] = $args['firstname'];
		$data['lastname']  = $args['lastname'];
		$data['nickname']  = $args['lastname'] . ' ' . $args['firstname'];
		$data['email']     = $args['email'];
		$data['jid']       = $args['id'];
		$data['listable']  = $listable;
		$data['active_to'] = $active_to;

		$employeeModel = JModelVAP::getInstance('employee');

		// create a new employee
		$data['id'] = $employeeModel->save($data);

		if (!$data['id'])
		{
			// something went wrong...
			return false;
		}
		
		// auto assign services
		$auto_services = VAPEmployeeAreaManager::getServicesToAssign();

		$serviceModel = JModelVAP::getInstance('service');
		$assocModel   = JModelVAP::getInstance('serempassoc');

		foreach ($auto_services as $id_service)
		{
			// load service details through model
			$item = $serviceModel->getItem((int) $id_service);

			if (!$item)
			{
				// item not found, go ahead
				continue;
			}

			// inject relation details
			$item->id_employee = $data['id'];
			$item->id_service  = $item->id;

			// unset item PK
			$item->id = 0;

			// clear description
			$item->description = '';

			// use global rates
			$item->global = 1;

			// attempt to assign the service to the employee
			$assocModel->save($item);
		}

		// MAIL

		$admin_mail_list = VikAppointments::getAdminMailList();
		$sender_mail     = VikAppointments::getSenderMail();
		$company_name    = VAPFactory::getConfig()->get('agencyname');

		$mail_subject = JText::sprintf('VAPEMPREGADMINSUBJECT', $data['nickname']);
		$mail_content = JText::sprintf('VAPEMPREGADMINCONTENT', $data['nickname']);

		$dispatcher = VAPFactory::getEventDispatcher();

		/**
		 * Trigger hook to allow external plugins to manipulate the e-mail subject and text
		 * sent to the administrator(s) after a successful employee registration.
		 *
		 * @param 	string   &$subject  The e-mail subject.
		 * @param 	string   &$content  The e-mail (HTML) content.
		 * @param 	array    $employee  An array containing the details filled by the employee.
		 *
		 * @return 	boolean  False to prevent the e-mail sending.
		 *
		 * @since 	1.7
		 */
		if (!$dispatcher->false('onBeforeSendMailEmployeeRegistration', array(&$mail_subject, &$mail_content, $data)))
		{
			$vik = VAPApplication::getInstance();

			// send e-mail notification
			foreach ($admin_mail_list as $_m)
			{
				$vik->sendMail($sender_mail, $company_name, $_m, $sender_mail, $mail_subject, $mail_content, $attachments = null, $is_html = true);
			}
		}
		
		return true;
	}
}

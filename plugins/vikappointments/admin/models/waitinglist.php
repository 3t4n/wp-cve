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
 * VikAppointments waiting list model.
 *
 * @since 1.7
 */
class VikAppointmentsModelWaitinglist extends JModelVAP
{
	/**
	 * Save implementation performed from the front-end customer.
	 *
	 * @return 	mixed  The ID on success, false otherwise.
	 */
	public function subscribe($data)
	{
		$data = (array) $data;

		$user = JFactory::getUser();

		// use current user ID if not specified
		if (empty($data['jid']))
		{
			$data['jid'] = $user->id;
		}

		// try to use the current user e-mail if not specified
		if (empty($data['email']))
		{
			$data['email'] = $user->email;
		}

		// validate required fields
		if (empty($data['email']) || empty($data['phone_number']) || empty($data['id_service']) || empty($data['timestamp']))
		{
			// missing required fields
			$this->setError(JText::translate('VAPERRINSUFFCUSTF'));
			return false;
		}

		// sanitize employee ID
		$data['id_employee'] = isset($data['id_employee']) && $data['id_employee'] > 1 ? $data['id_employee'] : 0;

		// validate service and employee
		$assocModel = JModelVAP::getInstance('serempassoc');
		$service = $assocModel->getOverrides($data['id_service'], $data['id_employee']);
		
		if (!$service)
		{
			// one between the service and the relation with the employee does not exist
			$this->setError(JText::translate('VAPERRINSUFFCUSTF'));
			return false;
		}

		$dbo = JFactory::getDbo();

		// make sure the user is not yet subscribed to this waiting list

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_waitinglist'))
			->where(array(
				$dbo->qn('id_service') . ' = ' . (int) $data['id_service'],
				$dbo->qn('id_employee') . ' = ' . (int) $data['id_employee'],
				$dbo->qn('timestamp') . ' = ' . $dbo->q($data['timestamp']),
			));

		if ($data['jid'] > 0)
		{
			// check by user ID
			$q->where($dbo->qn('jid') . ' = ' . $data['jid']);
		}
		else
		{
			// check email or phone number (it doesn't need to have both them identical)
			$q->andWhere(array(
				$dbo->qn('email') . ' = ' . $dbo->q($data['email']),
				$dbo->qn('phone_number') . ' = ' . $dbo->q($data['phone_number']),
			), 'OR');
		}

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			// already in waiting list
			$this->setError(JText::translate('VAPWAITLISTALREADYIN'));
			return false;
		}

		// save in waiting list
		return $this->save($data);
	}

	/**
	 * Delete implementation performed from the front-end customer.
	 *
	 * @return 	mixed  The number of deleted records, false on failure.
	 */
	public function unsubscribe($data)
	{
		$data = (array) $data;

		$user = JFactory::getUser();

		// use current user ID if not specified
		if (empty($data['jid']))
		{
			$data['jid'] = $user->id;
		}

		// try to use the current user e-mail if not specified
		if (empty($data['email']))
		{
			$data['email'] = $user->email;
		}

		// validate required fields
		if (empty($data['email']) && empty($data['phone_number']) && empty($data['jid']))
		{
			// missing required fields
			$this->setError(JText::translate('VAPERRINSUFFCUSTF'));
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_waitinglist'))
			->where(1);

		if ((int) $data['jid'])
		{
			// get all records belonging to this user
			$q->where($dbo->qn('jid') . ' = ' . (int) $data['jid']);
		}

		$where = array();

		if (!empty($data['email']))
		{
			// filter by e-mail
			$where[] = $dbo->qn('email') . ' = ' . $dbo->q($data['email']);
		}

		if (!empty($data['phone_number']))
		{
			// filter by phone number
			$where[] = $dbo->qn('phone_number') . ' = ' . $dbo->q($data['phone_number']);
		}

		if ($where)
		{
			$q->orWhere($where);
		}

		$filters = array();

		if (isset($data['timestamp']))
		{
			$date = JFactory::getDate($data['timestamp']);

			$date->modify('00:00:00');
			$start = $date->toSql();

			$date->modify('23:59:59');
			$end = $date->toSql();

			// filter by check-in date
			$filters[] = $dbo->qn('timestamp') . ' BETWEEN ' . $dbo->q($start) . ' AND ' . $dbo->q($end);
		}

		if (isset($data['id_service']))
		{
			// filter by service ID
			$filters[] = $dbo->qn('id_service') . ' = ' . (int) $data['id_service'];
		}

		if ($filters)
		{
			// filter the waiting list by date/service
			$q->andWhere($filters, 'AND');
		}

		$dbo->setQuery($q);

		if ($columns = $dbo->loadColumn())
		{
			// delete all fetched records
			$this->delete($columns);
		}

		return count($columns);
	}

	/**
	 * Processes the waiting list queue for the users registered for
	 * the service and check-in of the cancelled appointment.
	 *
	 * @param 	integer  $id  The appointment ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function notify($id)
	{
		if (!VAPFactory::getConfig()->getBool('enablewaitlist'))
		{
			// waiting list not enabled
			return false;
		}

		try
		{
			// load order details
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($id);
		}
		catch (Exception $e)
		{
			// propagate error
			$this->setError($e);

			return false;
		}

		$services = array();

		// fetch common services
		foreach ($order->appointments as $appointment)
		{
			$sid = $appointment->service->id;
			$eid = $appointment->employee->id;

			// adjust check-in to the employee timezone
			$date = new JDate($appointment->checkin->utc);
			$date->setTimezone(new DateTimeZone($appointment->employee->checkin->timezone));

			// format day and time
			$day  = $date->format('Y-m-d', $local = true);
			$time = $date->format('H:i', $local = true);
			// convert time in minutes
			$time = JHtml::fetch('vikappointments.time2min', $time);

			if (empty($services[$sid]))
			{
				// init service pool
				$services[$sid] = array();
			}

			if (empty($services[$sid][$eid]))
			{
				// init employee pool
				$services[$sid][$eid] = array();
			}

			if (empty($services[$sid][$eid][$day]))
			{
				// init date pool
				$services[$sid][$eid][$day] = array();
			}

			// register time
			$services[$sid][$eid][$day][] = $time;
		}

		/**
		 * Here we should have a tree structure built as follows:
		 *
		 * - ID service (20)
		 * 		- ID employee (2)
		 *			- checkin day (Y-m-d)
		 * 				- time (630)
		 * 				- time (730)
		 * 			- checkin day (Y-m-d)
		 * 				- time (960)
		 */

		$notified = false;

		// get compatible customers in waiting list
		foreach ($services as $id_service => $employees)
		{
			foreach ($employees as $id_employee => $dates)
			{
				foreach ($dates as $day => $times)
				{
					// get matching customers
					$list = $this->getMatches($id_service, $id_employee, $day);

					// send notifications
					$notified = $this->sendNotifications($order, $list, $id_service, $id_employee, $day, $times) || $notified;
				}
			}
		}

		return $notified;
	}

	/**
	 * Finds a list of customers registered in the waiting list that
	 * match the specified search arguments.
	 *
	 * @param 	integer  id_service   The service ID.
	 * @param 	integer  id_employee  The employee ID.
	 * @param 	string   date         The date in military format.
	 *
	 * @return 	array    A list of matching records.
	 */
	protected function getMatches($id_service, $id_employee, $date)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('*');
		$q->from($dbo->qn('#__vikappointments_waitinglist'));

		// Check if the employee is set and matches the specified one.
		// In this case, the slot has been emptied even if the order
		// was referring to a different service.
		$q->where(array(
			$dbo->qn('id_employee') . ' = ' . (int) $id_employee,
			$dbo->qn('id_employee') . ' > 0',
		));

		// Otherwise make sure the ID of the service matches the specified one.
		// In addition, the employee must be not set or equals to the specified one.
		$q->orWhere(array(
			$dbo->qn('id_service') . ' = ' . (int) $id_service,
			'(' . $dbo->qn('id_employee') . ' = ' . (int) $id_employee . ' OR ' . $dbo->qn('id_employee') . ' <= 0)',
		), 'AND');

		// extend the previous statement and make sure the subscription is for the specified day
		$q->andWhere($dbo->qn('timestamp') . ' = ' . $dbo->q($date));

		// older registrations come first
		$q->order($dbo->qn('created_on') . ' ASC');

		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Sends the notifications to the specified customers.
	 *
	 * @param 	object   $order        The order details.
	 * @param 	array    $list         The waiting list to notify.
	 * @param 	integer  $id_service   The service ID.
	 * @param 	integer  $id_employee  The employee ID.
	 * @param 	string   $date         The check-in date.
	 * @param 	array    $times        A list of check-in times.
	 *
	 * @return 	boolean  True if notified, false otherwise.
	 */
	protected function sendNotifications($order, $list, $id_service, $id_employee, $date, $times)
	{
		if (!$list)
		{
			// empty list, none to notify
			return false;
		}

		$index = null;

		// iterate the order details until we find the appointment
		// that matches the given parameters
		foreach ($order->appointments as $i => $appointment)
		{
			if ($appointment->service->id == $id_service && $appointment->employee->id == $id_employee)
			{
				// record found
				$index = $i;
				break;
			}
		}

		if (is_null($index))
		{
			// no matches...
			return false;
		}

		// send e-mail notification
		$mail = $this->sendEmailNotification($order->appointments[$index], $list, $date, $times);
		// send SMS notification
		$sms = $this->sendSmsNotification($order->appointments[$index], $list, $date, $times);

		return $mail || $sms;
	}

	/**
	 * Sends an e-mail notification to the specified customers.
	 *
	 * @param 	object   $appointment  The appointment details.
	 * @param 	array    $list         The waiting list to notify.
	 * @param 	string   $date         The check-in date.
	 * @param 	array    $times        A list of check-in times.
	 *
	 * @return 	boolean  True if notified, false otherwise.
	 */
	protected function sendEmailNotification($appointment, $list, $date, $times)
	{
		$notified = false;

		VAPLoader::import('libraries.mail.factory');

		// iterate subscribed customers one by one
		foreach ($list as $customer)
		{
			// create mail instance
			$mail = VAPMailFactory::getInstance('waitlist', $appointment, $customer, $date, $times);

			// make sure we should send the e-mail
			if ($mail->shouldSend())
			{
				// try to dispatch the notification
				$notified = $mail->send() || $notified;
			}
		}
		
		return $notified;
	}

	/**
	 * Sends a SMS notification to the specified customers.
	 *
	 * @param 	object   $appointment  The appointment details.
	 * @param 	array    $list         The waiting list to notify.
	 * @param 	string   $date         The check-in date.
	 * @param 	array    $times        A list of check-in times.
	 *
	 * @return 	boolean  True if notified, false otherwise.
	 */
	protected function sendSmsNotification($appointment, $list, $date, $times)
	{
		try
		{
			// get current SMS instance
			$smsapi = VAPApplication::getInstance()->getSmsInstance();
		}
		catch (Exception $e)
		{
			// SMS API not configured
			$this->setError(JText::translate('VAPSMSESTIMATEERR1'));

			return false;
		}

		$notified = 0;
		$errors   = array();

		// iterate subscribed customers one by one
		foreach ($list as $customer)
		{
			// get phone number
			$phone = $customer->phone_number;

			if (!$phone)
			{
				// missing phone number, go ahead
				continue;
			}

			// generate and parse SMS template
			$tmpl = $this->getSmsTemplate($appointment, $customer, $date, $times);

			if (!$tmpl)
			{
				// missing template, go ahead
				continue;
			}

			// check if we have a dial code and the phone doesn't specify it
			if ($customer->phone_prefix && !preg_match("/^\+/", $phone))
			{
				// prepend dial code
				$phone = $customer->phone_prefix . $phone;
			}

			// send message
			$response = $smsapi->sendMessage($phone, $tmpl);

			// validate response
			if ($smsapi->validateResponse($response))
			{
				// increase number of notified customers
				$notified++;
			}
			else
			{
				// register log found
				$errors[] = $smsapi->getLog();
			}
		}

		if ($errors)
		{
			// notify administrator
			VikAppointments::sendAdminMailSmsFailed($errors);
		}
		
		return (bool) $notified;
	}

	/**
	 * Parses SMS template used for the notifications of the waiting list.
	 * The placeholders contained in the template will be replaced with real values.
	 *
	 * The SMS template message to use depends on the language and the number
	 * of times that are available again (1 or more).
	 *
	 * @param 	object 	$appointment  The appointment details object.
	 * @param 	object  $record       The waiting list record.
	 * @param 	string  $date         The check-in date (military format).
	 * @param 	array   $times        All the times that have been emptied for the given day.
	 *
	 * @return 	string 	The SMS plain message to send.
	 */
	protected function getSmsTemplate($appointment, $record, $date, $times)
	{
		$vik    = VAPApplication::getInstance();
		$config = VAPFactory::getConfig();

		// get SMS map
		$sms_map = $config->getArray('waitlistsmscont');

		// NOTE: the system sends a notification into the current language.
		// This means that, if we have an Italian customer that cancels an
		// appointment from the front-end, any other user will be notified
		// it Italian. We should consider to always use the default site
		// language or to register the langtag of the subscribed customers.

		$lang = JFactory::getLanguage()->getTag();
		$sms  = $sms_map[(count($times) == 1 ? 0 : 1)][$lang];

		// Format check-in date. Force UTC timezone because the date should be
		// displayed as it is.
		$formatted_date = JHtml::fetch('date', $date, JText::translate('DATE_FORMAT_LC4'), 'UTC');

		$formatted_times = array();

		foreach ($times as $time)
		{
			// convert minutes in time
			$formatted_times[] = JHtml::fetch('vikappointments.min2time', $time, true);
		}

		if (count($times) > 1)
		{
			// use list of available times
			$formatted_time = implode(', ', $formatted_times);
		}
		else
		{
			// use only the first available time
			$formatted_time = $formatted_times[0];
		}

		if ($appointment->service->id == $record->id_service)
		{
			// the customer did a cancellation for the same service
			$service = $appointment->service;
		}
		else
		{
			// the customer did the cancellation for a different service,
			// we need to fetch the details of the service for which this
			// user subscribed into the waiting list
			$service = JModelVAP::getInstance('service')->getItem($record->id_service);

			if (!$service)
			{
				return false;
			}
		}

		// create link to access the service details page
		$dt  = JHtml::fetch('date', $date, $config->get('dateformat'), 'UTC');
		$url = "index.php?option=com_vikappointments&view=servicesearch&id_service={$service->id}&date={$dt}";

		if ($appointment->viewEmp > 0)
		{
			// include employee ID too, if selectable
			$url .= '&id_emp=' . $appointment->employee->id;
		}

		$url = $vik->routeForExternalUse($url);

		// parse placeholders
		$sms = str_replace('{checkin_day}'	, $formatted_date            , $sms);
		$sms = str_replace('{checkin_time}'	, $formatted_time            , $sms);
		$sms = str_replace('{service}'		, $service->name             , $sms);
		$sms = str_replace('{company}'		, $config->get('agencyname') , $sms);
		$sms = str_replace('{details_url}'	, $url                       , $sms);

		return $sms;
	}
}

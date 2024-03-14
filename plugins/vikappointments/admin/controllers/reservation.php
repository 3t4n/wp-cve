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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments appointment controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerReservation extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$data = array();
		$data['id_service']  = $app->input->getUint('id_ser');
		$data['id_employee'] = $app->input->getUint('id_emp');
		$data['people']      = $app->input->getUint('people');
		$data['day']         = $app->input->getString('day');

		if (!empty($data['day']))
		{
			// get employee timezone
			$tz = $this->getModel('employee')->getTimezone($data['id_employee']);

			$h = $app->input->getUint('hour', 0);
			$m = $app->input->getUint('min', 0);

			// create date instance adjusted to employee timezone
			$date = new JDate($data['day'] . " $h:$m:00", $tz);

			// get check-in UTC
			$data['checkin_ts'] = $date->toSql();
		}

		// strip missing information
		$data = array_filter($data);

		// unset user state for being recovered again
		$app->setUserState('vap.reservation.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$url = 'index.php?option=com_vikappointments&view=managereservation';

		// check whether a custom return view has been specified
		$from = $app->input->get('from');

		if ($from)
		{
			$url .= '&from=' . $from;
		}

		$this->setRedirect($url);

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$data = array();
		$data['id_service']  = $app->input->getUint('id_ser');
		$data['id_employee'] = $app->input->getUint('id_emp');
		$data['people']      = $app->input->getUint('people');
		$data['day']         = $app->input->getString('day');

		if (!empty($data['day']))
		{
			// get employee timezone
			$tz = $this->getModel('employee')->getTimezone($data['id_employee']);

			$h = $app->input->getUint('hour', 0);
			$m = $app->input->getUint('min', 0);

			// create date instance adjusted to employee timezone
			$date = new JDate($data['day'] . " $h:$m:00", $tz);

			// get check-in UTC
			$data['checkin_ts'] = $date->toSql();
		}

		// strip missing information
		$data = array_filter($data);

		// unset user state for being recovered again
		$app->setUserState('vap.reservation.data', $data);

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$url = 'index.php?option=com_vikappointments&view=managereservation&cid[]=' . $cid[0];

		// check whether a custom return view has been specified
		$from = $app->input->get('from');

		if ($from)
		{
			$url .= '&from=' . $from;
		}

		$this->setRedirect($url);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the creation
	 * page of a new record.
	 *
	 * @return 	void
	 */
	public function savenew()
	{
		if ($this->save())
		{
			$this->setRedirect('index.php?option=com_vikappointments&view=findreservation');
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	boolean
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();

		// get order details
		$args['id']             = $input->getUint('id', 0);
		$args['id_payment']     = $input->getUint('id_payment', 0);
		$args['status']         = $input->getString('status', '');
		$args['status_comment'] = $input->getString('comment', '');
		$args['notifycust']     = $input->getBool('notifycust', false);
		$args['notifyemp']      = $input->getBool('notifyemp', false);
		$args['notifywl']       = $input->getBool('notifywl', false);
		$args['notes']          = JComponentHelper::filterText($input->getRaw('notes', ''));

		// get billing details
		$args['id_user']              = $input->getUint('id_user', 0);
		$args['purchaser_nominative'] = $input->getString('purchaser_nominative', '');
		$args['purchaser_mail']       = $input->getString('purchaser_mail', '');
		$args['purchaser_phone']      = $input->getString('purchaser_phone', '');
		$args['purchaser_prefix']     = $input->getString('purchaser_prefix', '');
		$args['purchaser_country']    = $input->getString('purchaser_country', '');

		// get service details
		$args['id_service']  = $input->getUint('id_service', 0);
		$args['id_employee'] = $input->getUint('id_employee', 0);
		$args['checkin_ts']  = $input->getString('checkin_ts', '');
		$args['duration']    = $input->getUint('duration', 0);
		$args['sleep']       = $input->getUint('sleep', 0);
		$args['people']      = $input->getUint('people', 1);

		// get order totals
		$args['total_cost']     = $input->getFloat('total_cost', 0);
		$args['total_net']      = $input->getFloat('total_net', 0);
		$args['total_tax']      = $input->getFloat('total_tax', 0);
		$args['payment_charge'] = $input->getFloat('payment_charge', 0);
		$args['payment_tax']    = $input->getFloat('payment_tax', 0);

		// get service totals
		$args['service_price'] = $input->getFloat('service_price', 0);
		$args['service_net']   = $input->getFloat('service_net', 0);
		$args['service_tax']   = $input->getFloat('service_tax', 0);
		$args['service_gross'] = $input->getFloat('service_gross', 0);
		$args['tax_breakdown'] = $input->getString('tax_breakdown', '');

		// get actions
		$args['add_discount']    = $input->getString('add_discount', '');
		$args['remove_discount'] = $input->getBool('remove_discount', false);

		if ($args['add_discount'] === 'manual')
		{
			// fetch manual discount from request
			$args['add_discount'] = $input->get('manual_discount', [], 'array');
		}

		// check whether the model should (re)validate the availability
		$args['validate_availability'] = $input->getBool('validate_availability', false);

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->ofEmployee($args['id_employee'])
			->forService($args['id_service'])
			->noSeparator()
			->noRequiredCheckbox()
			->fetch();

		// load custom fields from request
		$args['custom_f'] = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = false);

		// copy uploads into the apposite column
		$args['uploads'] = $tmp['uploads'];

		// register data fetched by the custom fields so that the reservation
		// model is able to use them for saving purposes
		$args['fields_data'] = $tmp;

		$args['attendees'] = array();

		/**
		 * Recover attendees custom fields.
		 *
		 * @since 1.7
		 */
		for ($people = 0; $people < $args['people'] - 1; $people++)
		{
			// reset attendee array
			$attendee = array();

			// load custom fields from request for other attendees
			$tmp = VAPCustomFieldsRequestor::loadFormAttendee($people + 1, $_cf, $attendee, $strict = false);
			// inject attendee custom fields within the array containing the fetched rules
			$attendee['fields'] = $tmp;

			// register attendee
			$args['attendees'][] = $attendee;
		}

		// get selected options
		$args['options'] = $input->get('option_json', array(), 'array');
		// load deleted options
		$args['deletedOptions'] = $input->get('option_deleted', array(), 'uint');

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		if ($args['notifycust'])
		{
			/**
			 * Loads any additional custom text to include within the e-mail notification.
			 *
			 * @since 1.6.5
			 */
			$custMail = array();
			$custMail['id']       = $input->getUint('custmail_id', 0);
			$custMail['name']     = $input->getString('custmail_name', '');
			$custMail['position'] = $input->getString('custmail_position', '');
			$custMail['content']  = JComponentHelper::filterText($input->getRaw('custmail_content', ''));

			if (!empty($custMail['name']) && !empty($custMail['content']))
			{
				// create new custom e-mail template (unpublished)
				$custMail['published'] = 0;

				// get e-mail text model
				$custMailModel = $this->getModel('mailtext');
				// attempt to create new mail text
				$mail_id = $custMailModel->save($custMail);

				if ($mail_id)
				{
					// inject selected custom e-mail within order details
					// for being retrieved while generating the notification
					$args['mail_custom_text'] = $mail_id;

					/**
					 * Added the possibility to exclude the default mail custom texts.
					 *
					 * @since 1.6.6
					 */
					$args['exclude_default_mail_texts'] = $input->getBool('exclude_default_mail_texts', false);
				}
			}
		}

		// get reservation model
		$order = $this->getModel();

		if ($args['id'] == 0)
		{
			VAPLoader::import('libraries.models.subscriptions');

			// In case the service owns a cost and the system supports the subscriptions
			// for the customers, always recalculate the totals. Ignore subscription benefits
			// in case of updates, because the price might have been manually changed.
			if ($args['service_price'] > 0 && $args['id_user'] > 0 && VAPSubscriptions::has())
			{
				// recalculate costs by unsetting the service price
				$order->recalculateTotals($args);
			}
		}

		// get packages order model
		$package = JModelVAP::getInstance('packorder');

		$can_redeem_pack = false;

		$approved = JHtml::fetch('vaphtml.status.isapproved', 'appointments', $args['status']);

		// attempt to redeem the package only in case the service has a cost
		// and the status is approved
		if ($args['service_price'] > 0 && !empty($args['id_user']) && $approved)
		{
			$count = $package->countRemaining($args['id_service'], $args['id_user']);

			// in case the count of remaining packages is equals or higher than the selected
			// number of people, we can redeem the packages
			if ($count >= $args['people'])
			{
				// we can redeem the packages
				$can_redeem_pack = true;

				// recalculate costs by unsetting the service price
				$order->recalculateTotals($args, 0.0);

				// use a different status comment, if blank
				if (empty($args['status_comment']))
				{
					$args['status_comment'] = 'VAP_STATUS_PACKAGE_REDEEMED';
				}
			}
		}

		// try to save arguments
		$id = $order->save($args);

		if (!$id)
		{
			// get string error
			$error = $order->getError(null, true);

			// update user state data by refactoring the options structure
			$data = (array) $app->getUserState('vap.reservation.data', array());

			if (!empty($data['options']))
			{
				foreach ($data['options'] as $i => $opt)
				{
					if (is_string($opt))
					{
						// JSON decode options
						$data['options'][$i] = json_decode($opt);
					}
				}

				// commit changes
				$app->setUserState('vap.reservation.data', $data);
			}

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managereservation';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		if ($can_redeem_pack)
		{
			// finally redeem the packages
			$redeemed = $package->usePackages($id);

			if ($redeemed)
			{
				// display message to the user
				$app->enqueueMessage(JText::sprintf('VAPORDERREDEEMEDPACKS', $redeemed));
			}
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=reservation.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function delete()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->get('cid', array(), 'uint');

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel()->delete($cid);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$input = JFactory::getApplication()->input;

		// check whether a custom return view has been specified
		$view = $input->get('from');

		if (!$view)
		{
			// back to appointments list by default
			$view = 'reservations';
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=' . $view);
	}

	/**
	 * Task used to send an e-mail notification to the customer
	 * of the specified appointment.
	 *
	 * @return 	void
	 */
	public function notify()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to send e-mail notifications
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$ids = $input->getUint('cid', array(0), 'uint');

		// get appointment model
		$model = $this->getModel();

		$errors = array();

		foreach ($ids as $id)
		{
			// try to send e-mail notification
			if (!$model->sendEmailNotification($id))
			{
				// get string error
				$error = $model->getError(null, true);

				// enqueue error message
				$errors[] = $error ? $error : JText::translate('VAPNOTIFYCUSTERR');
			}
		}

		if ($errors)
		{
			// display duplicate error messages only once
			foreach (array_unique($errors) as $err)
			{
				$app->enqueueMessage($err, 'error');
			}
		}
		else
		{
			// display successful message
			$app->enqueueMessage(JText::translate('VAPNOTIFYCUSTOK'));
		}

		// back to main list
		$this->cancel();
	}

	/**
	 * Task used to send a SMS notification to the customer
	 * of the specified appointment.
	 *
	 * @return 	void
	 */
	public function sendsms()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to send SMS notifications
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$ids = $input->getUint('cid', array(0), 'uint');

		// get appointment model
		$model = $this->getModel();

		$notified = 0;
		$errors   = array();

		foreach ($ids as $id)
		{
			// try to send SMS notification
			if ($model->sendSmsNotification($id))
			{
				$notified++;
			}
			else
			{
				// get string error
				$error = $model->getError(null, true);

				// enqueue error message
				$errors[] = $error;
			}
		}

		if ($notified)
		{
			// successful message
			$app->enqueueMessage(JText::plural('VAPCUSTOMERSMSSENT', $notified));
		}
		else
		{
			// no notifications sent
			$app->enqueueMessage(JText::plural('VAPCUSTOMERSMSSENT', $notified), 'warning');
		}

		// display any returned errors
		if ($errors)
		{
			// do not display duplicate or empty errors
			$errors = array_unique(array_filter($errors));

			foreach ($errors as $err)
			{
				$app->enqueueMessage($err, 'error');
			}
		}

		// back to main list
		$this->cancel();
	}

	/**
	 * AJAX end-point used to change the status code.
	 * The task expects the following arguments to be set in request.
	 *
	 * @param 	integer  id      The appointment ID.
	 * @param 	string   status  The new status code.
	 * @param 	string   layout  The layout to use (for return).
	 *
	 * @return 	void
	 */
	public function changestatusajax()
	{
		$input = JFactory::getApplication()->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$data = array();
		$data['id']     = $input->getUint('id');
		$data['status'] = $input->getString('status');

		// check user permissions
		if (!$data['id'] || !$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// not authorised to edit records
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		// get status code details
		$code = JHtml::fetch('vaphtml.status.find', '*', array('code' => $data['status']), $limit = true);

		if (!$code)
		{
			// code not found
			UIErrorFactory::raiseError(404, JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
		}

		// register comment for status change
		$data['status_comment'] = 'VAP_STATUS_CHANGED_FROM_LIST';

		// update status
		$this->getModel()->save($data);

		// render HTML
		$code->html = JHtml::fetch('vaphtml.status.display', $code, $input->getString('layout'));

		// send code to caller
		$this->sendJSON($code);
	}

	/**
	 * AJAX end-point used return a list of employees available for the 
	 * specified check-in arguments.
	 *
	 * @param 	integer  id_employee  The currently set employee ID.
	 * @param 	integer  id_service   The service ID.
	 * @param 	string   checkin_ts   The appointment check-in.
	 * @param 	integer  people       The number of attendees.
	 *
	 * @return 	void
	 */
	public function employeespreviewajax()
	{
		$input = JFactory::getApplication()->input;

		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$args = array();
		$args['id_service']  = $input->getUint('id_service', 0);
		$args['checkin_ts']  = $input->getString('checkin_ts', '');
		$args['people']      = $input->getUint('people', 0);

		$curr_employee = $input->getUint('id_employee', 0);

		// load all employees assigned to the specified service
		$employees = $this->getModel('service')->getEmployees($args['id_service']);

		if (!$employees)
		{
			// no employees, raise error
			UIErrorFactory::raiseError(404, 'No employees found');
		}

		// get reservation model
		$model = $this->getModel();

		$lookup = array();

		// iterate all employees and validate availability one by one
		foreach ($employees as $employee)
		{
			// prepare lookup data
			$tmp = array(
				'id'     => $employee->id,
				'name'   => $employee->nickname,
			);

			if ($employee->id == $curr_employee)
			{
				// always available the currently set employee
				$tmp['status'] = true;
			}
			else
			{
				// update search query with current employee ID
				$args['id_employee'] = $employee->id;
				// validate employee availability
				$tmp['status'] = $model->isAvailable($args);
			}

			// register result
			$lookup[] = $tmp;
		}

		// send response to caller
		$this->sendJSON($lookup);
	}

	/**
	 * AJAX end-point used to change the check-in of the selected appointment.
	 *
	 * @param 	integer  id           The appointment ID.
	 * @param 	string   checkin_ts   The appointment check-in.
	 *
	 * @return 	void
	 */
	public function changecheckinajax()
	{
		$input = JFactory::getApplication()->input;

		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$args = array();
		$args['id']         = $input->getUint('id', 0);
		$args['checkin_ts'] = $input->getString('checkin_ts', '');
		$args['duration']   = $input->getUint('duration', null);

		// get reservation model
		$model = $this->getModel();

		// get reservation details
		$item = $model->getItem($args['id']);

		if (!$item)
		{
			// no appointment, raise error
			UIErrorFactory::raiseError(404, 'Appointment not found');
		}

		$args['id_employee'] = $item->id_employee;
		$args['id_service']  = $item->id_service;
		$args['people']      = $item->people;
		$args['sleep']       = $item->sleep;

		if (!$args['duration'])
		{
			// use default duration
			$args['duration'] = $item->duration;
		}

		// make sure the appointment is still available
		$args['validate_availability'] = true;

		// attempt to apply the changes
		if (!$model->save($args))
		{
			// get registered error message
			$error = $model->getError($index = null, $string = true);

			// propagate error to caller
			UIErrorFactory::raiseError(500, $error);
		}

		// send response to caller
		$this->sendJSON(1);
	}
}

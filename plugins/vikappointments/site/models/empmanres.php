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
 * VikAppointments employee area reservation management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpmanres extends JModelVAP
{
	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		// load item through parent
		$item = parent::getItem($pk, $new);

		if ($item)
		{
			$item->id_employee = VAPEmployeeAuth::getInstance()->id;

			$item->userNote = null;

			if ($item->id)
			{
				// decode custom fields and uploads
				$item->custom_f  = (array) json_decode($item->custom_f, true);
				$item->attendees = (array) json_decode($item->attendees, true);
				$item->uploads   = (array) json_decode($item->uploads, true);
				// merge them together
				$item->custom_f = array_merge($item->custom_f, $item->uploads);

				$dbo = JFactory::getDbo();
				
				// load assigned options
				$q = $dbo->getQuery(true)
					->select('a.*')
					->select($dbo->qn('o.name'))
					->select($dbo->qn('v.name', 'var_name'))
					->from($dbo->qn('#__vikappointments_option', 'o'))
					->leftjoin($dbo->qn('#__vikappointments_res_opt_assoc', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_option'))
					->leftjoin($dbo->qn('#__vikappointments_option_value', 'v') . ' ON ' . $dbo->qn('v.id') . ' = ' . $dbo->qn('a.id_variation'))
					->where($dbo->qn('a.id_reservation') . ' = ' . $item->id)
					->order($dbo->qn('a.id') . ' ASC');

				$dbo->setQuery($q);
				$item->options = $dbo->loadObjectList();

				/**
				 * Loads the last note assigned to this reservation.
				 *
				 * @since 1.7
				 */
				$q = $dbo->getQuery(true)
					->select('*')
					->from($dbo->qn('#__vikappointments_user_notes'))
					->where($dbo->qn('id_parent') . ' = ' . $item->id)
					->where($dbo->qn('group') . ' = ' . $dbo->q('appointments'))
					->order(sprintf(
						'IFNULL(%s, %s) %s',
						$dbo->qn('modifiedon'),
						$dbo->qn('createdon'),
						'DESC'
					));

				$dbo->setQuery($q, 0, 1);
				$item->userNote = $dbo->loadObject();
			}
			else
			{
				// new record
				$item->custom_f  = array();
				$item->attendees = array();
				$item->options   = array();
				$item->jid       = 0;
			}

			if (!$item->userNote)
			{
				// load an empty object
				$item->userNote = JModelVAP::getInstance('usernote')->getItem(0, $blank = true);
			}

			// load customer details
			$item->userData = JModelVAP::getInstance('customer')->getItem($item->id_user);
		}

		return $item;
	}

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// get reservation model
		$appModel = JModelVAP::getInstance('reservation');

		// force author and owner
		$data['createdby']   = JFactory::getUser()->id;
		$data['id_employee'] = $auth->id;

		/**
		 * If the status was already confirmed, resconfirm() will return 
		 * true even if the rule is disabled.
		 *
		 * @since 1.6
		 */ 
		if (JHtml::fetch('vaphtml.status.isapproved', 'appointments', $data['status']) && !$auth->confirmReservation($data['id']))
		{
			// the employee cannot confirm the reservation, use default PENDING status
			$data['status'] = JHtml::fetch('vaphtml.status.pending', 'appointments', 'code');
		}

		$data['people'] = max(array(1, $data['people']));

		// make sure the selected payment can be used by this employee
		if (empty($data['id_payment']) || !VikAppointments::getPayment($data['id_payment']))
		{
			// nope, unset it
			$data['id_payment'] = 0;
		}

		if ($data['notifycust'])
		{
			$input = $app->input;

			/**
			 * Loads any additional custom text to include within the e-mail notification.
			 *
			 * @since 1.6.5
			 */
			$custMail = array();
			$custMail['id']          = $input->getUint('custmail_id', 0);
			$custMail['name']        = $input->getString('custmail_name', '');
			$custMail['position']    = $input->getString('custmail_position', '');
			$custMail['content']     = JComponentHelper::filterText($input->getRaw('custmail_content', ''));
			$custMail['id_employee'] = $data['id_employee'];

			if (!empty($custMail['name']) && !empty($custMail['content']))
			{
				// create new custom e-mail template (unpublished)
				$custMail['published'] = 0;

				// get e-mail text model
				$custMailModel = JModelVAP::getInstance('mailtext');
				// attempt to create new mail text
				$mail_id = $custMailModel->save($custMail);

				if ($mail_id)
				{
					// inject selected custom e-mail within order details
					// for being retrieved while generating the notification
					$data['mail_custom_text'] = $mail_id;

					/**
					 * Added the possibility to exclude the default mail custom texts.
					 *
					 * @since 1.6.6
					 */
					$data['exclude_default_mail_texts'] = $input->getBool('exclude_default_mail_texts', false);
				}
			}
		}

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->ofEmployee($data['id_employee'])
			->forService($data['id_service'])
			->noSeparator()
			->noRequiredCheckbox()
			->fetch();

		// load custom fields from request
		$data['custom_f'] = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = false);

		// copy uploads into the apposite column
		$data['uploads'] = $tmp['uploads'];

		// register data fetched by the custom fields so that the reservation
		// model is able to use them for saving purposes
		$data['fields_data'] = $tmp;

		$data['attendees'] = array();

		/**
		 * Recover attendees custom fields.
		 *
		 * @since 1.7
		 */
		for ($people = 0; $people < $data['people'] - 1; $people++)
		{
			// reset attendee array
			$attendee = array();

			// load custom fields from request for other attendees
			$tmp = VAPCustomFieldsRequestor::loadFormAttendee($people + 1, $_cf, $attendee, $strict = false);
			// inject attendee custom fields within the array containing the fetched rules
			$attendee['fields'] = $tmp;

			// register attendee
			$data['attendees'][] = $attendee;
		}

		// set user state for being recovered again
		$app->setUserState('vap.emparea.reservation.data', $data);

		// make sure the employee is allowed to book an appointment for the selected service
		if (!$auth->manageServices($data['id_service'], $readOnly = true))
		{
			// register error and abort
			$this->setError(JText::translate('VAPRESERVATIONEDITED0'));
			return false;
		}

		if (empty($data['notes']) && !empty($data['id_notes']))
		{
			// we need to delete the note has the employee cleared the description
			JModelVAP::getInstance('usernote')->delete($data['id_notes']);
		}

		/**
		 * Scan the user fields to check whether we should automatically create a 
		 * new customer record.
		 * 
		 * @since 1.7.1
		 */
		if ((empty($data['id_user']) || $data['id_user'] < 0) && $data['fields_data'])
		{
			// The reservation hasn't been assigned to any customers...
			// Try to save a new customer record.
			$id_user = JModelVAP::getInstance('customer')->save($data['fields_data']);

			if ($id_user)
			{
				// assign the reservation to the created/updated user
				$data['id_user'] = (int) $id_user;
			}
		}

		// delegate save to reservation model
		return $appModel->save($data);
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$result = false;

		$shouldNotify = $auth->isNotifyOnReservationDelete();

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		$model = JModelVAP::getInstance('reservation');

		foreach ($ids as $id)
		{
			if (!$auth->removeReservation($id))
			{
				// not allowed, go ahead
				continue;
			}

			if ($shouldNotify)
			{
				// load appointment details before deleting it
				VAPLoader::import('libraries.order.factory');
				$order = VAPOrderFactory::getAppointments($id);
			}

			if ($model->delete($id))
			{
				$result = true;

				if ($shouldNotify)
				{
					// send a notification to the administrator
					$this->sendAdminNotification($order);
				}
			}
		}

		if (!$result)
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		return true;
	}

	/**
	 * Sends a notification to the administrator every time an employee
	 * deletes an appointment.
	 *
	 * @param  object  $order
	 *
	 * @return 	void
	 */
	protected function sendAdminNotification($order)
	{
		$appointment = $order->appointments[0];

		$mail_sub = JText::sprintf('VAPREMRESMAILSUBJECT', $appointment->employee->id);

		$mail_cont  = JText::translate('VAPMANAGERESERVATION1') . ": {$order->id} - {$order->sid}<br />";
		$mail_cont .= "{$appointment->service->name} - {$appointment->employee->nickname}<br />";
		$mail_cont .= $appointment->systemCheckin->lc3 . ' - ' . VAPFactory::getCurrency()->format($order->totals->gross) . '<br />';
		$mail_cont .= JText::translate('VAPMANAGERESERVATION12'). ': ' . JHtml::fetch('vaphtml.status.display', $order->status);

		$mail_cont = JText::sprintf('VAPREMRESMAILCONT', $mail_cont);
			
		$vik = VAPApplication::getInstance();

		$company_name    = VAPFactory::getConfig()->get('agencyname');
		$admin_mail_list = VikAppointments::getAdminMailList();
		$sender_mail     = VikAppointments::getSenderMail();

		// send e-mail notification
		foreach ($admin_mail_list as $_m)
		{
			$vik->sendMail($sender_mail, $company_name, $_m, $sender_mail, $mail_sub, $mail_cont, $attachments = null, $is_html = true);
		}
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = 'reservation', $prefix = '', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}

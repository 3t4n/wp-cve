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
 * VikAppointments multi-order (appointments) controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerMultiorder extends VAPControllerAdmin
{
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
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	boolean
	 */
	public function save()
	{
		$dbo   = JFactory::getDbo();
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
		$args['notifywl']       = $input->getBool('notifywl', false);

		// get order totals
		$args['total_cost']     = $input->getFloat('total_cost', 0);
		$args['total_net']      = $input->getFloat('total_net', 0);
		$args['total_tax']      = $input->getFloat('total_tax', 0);
		$args['payment_charge'] = $input->getFloat('payment_charge', 0);
		$args['payment_tax']    = $input->getFloat('payment_tax', 0);

		// get billing details
		$args['id_user']              = $input->getUint('id_user', 0);
		$args['purchaser_nominative'] = $input->getString('purchaser_nominative', '');
		$args['purchaser_mail']       = $input->getString('purchaser_mail', '');
		$args['purchaser_phone']      = $input->getString('purchaser_phone', '');
		$args['purchaser_prefix']     = $input->getString('purchaser_prefix', '');
		$args['purchaser_country']    = $input->getString('purchaser_country', '');

		// get actions
		$args['add_discount']    = $input->getString('add_discount', '');
		$args['remove_discount'] = $input->getBool('remove_discount', false);

		if ($args['add_discount'] === 'manual')
		{
			// fetch manual discount from request
			$args['add_discount'] = $input->get('manual_discount', [], 'array');
		}

		// check user permissions (do not allow creation)
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments') || !$args['id'])
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get db model
		$order = $this->getModel();

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->noSeparator()
			->noRequiredCheckbox();

		// get children details
		$children = $order->getChildren($args['id'], array('id_employee', 'id_service'));

		$employees = array();

		foreach ($children as $assoc)
		{
			// extend custom fields by specifying the selected service
			$_cf->forService($assoc->id_service);

			if (!in_array($assoc->id_employee, $employees))
			{
				// register employee within the pool
				$employees[] = $assoc->id_employee;
			}
		}

		if (count($employees) == 1)
		{
			// obtain custom fields assigned to the selected employee only
			// in case all the appointments assigned to this multi-order
			// refers to the same employee
			$_cf->ofEmployee($employees[0]);
		}

		// load custom fields from request
		$args['custom_f'] = VAPCustomFieldsRequestor::loadForm($_cf->fetch(), $tmp, $strict = false);

		// copy uploads into the apposite column
		$args['uploads'] = $tmp['uploads'];

		// register data fetched by the custom fields so that the reservation
		// model is able to use them for saving purposes
		$args['fields_data'] = $tmp;

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

		// try to save arguments
		$id = $order->save($args);

		if (!$id)
		{
			// get string error
			$error = $order->getError(null, true);

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

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=reservation.edit&cid[]=' . $id);

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
}

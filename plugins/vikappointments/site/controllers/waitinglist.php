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
 * VikAppointments waiting list controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerWaitinglist extends VAPControllerAdmin
{
	/**
	 * AJAX end-point used to subscribe a user into the waiting list.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	string   date         The date for which the user should be subscribed.
	 * @param 	integer  id_service   The ID of the service for which the user is interested.
	 * @param 	integer  id_employee  The ID of the employee for which the user is interested.
	 * @param 	string   email        The user e-mail.
	 * @param 	string   phone 	      The user phone number.
	 * 
	 * @return 	void
	 */
	public function subscribeajax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		if (!VikAppointments::isWaitingList())
		{
			// waiting list feature is blocked
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		$args = array();
		$args['timestamp']    = $input->getString('date', null);
		$args['id_service']   = $input->getUint('id_service', 0);
		$args['id_employee']  = $input->getUint('id_employee', 0);
		$args['email']        = $input->getString('mail');
		$args['phone_number'] = $input->getString('phone');

		// get waiting list model
		$model = $this->getModel();

		// subscribe into waiting list
		if (!$model->subscribe($args))
		{
			// get last error fetched
			$error = $model->getError($index = null, $string = true);
			UIErrorFactory::raiseError(500, $error ? $error : JText::translate('VAPWAITLISTADDED0'));
		}

		// send response to caller
		$this->sendJSON(json_encode(JText::translate('VAPWAITLISTADDED1')));
	}

	/**
	 * Task used to unsubscribe a user from the waiting list.
	 * This method expects the following parameters to be sent
	 * via POST or GET.
	 *
	 * @param 	string 	email  The user e-mail.
	 * @param 	string 	phone  The user phone number.
	 * 
	 * @return 	boolean
	 */
	public function unsubscribe()
	{
		$app     = JFactory::getApplication();
		$input   = $app->input;
		$session = JFactory::getSession();

		$itemid = $input->getUint('Itemid', 0);

		// set redirect URL
		$url = 'index.php?option=com_vikappointments&view=unsubscr_waiting_list' . ($itemid ? '&Itemid=' . $itemid : '');

		if (!JSession::checkToken())
		{
			// direct access attempt
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->setRedirect(JRoute::rewrite($url, false));
			return false;
		}

		$args = array();
		$args['email']        = $input->getString('email');
		$args['phone_number'] = $input->getString('phone');

		// get waiting list model
		$model = $this->getModel();

		// try to unsubscribe user
		$count = $model->unsubscribe($args);

		// register count of deleted records in URL
		$url .= '&deleted=' . $count;
		$this->setRedirect(JRoute::rewrite($url, false));

		if (!$count)
		{
			// get error message fetched by the model, if any
			$error = $model->getError($index = null, $string = true);

			if ($error)
			{
				// enqueue error message
				$app->enqueueMessage($error, 'error');
			}
		}
	}
}

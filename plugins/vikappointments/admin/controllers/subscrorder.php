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
 * VikAppointments subscription order controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerSubscrorder extends VAPControllerAdmin
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

		$data  = array();
		$group = $app->input->getUint('group', null);

		if (!is_null($group))
		{
			$data['group'] = $group;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.subscrorder.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.subscriptions', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managesubscrorder');

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

		// unset user state for being recovered again
		$app->setUserState('vap.subscrorder.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.subscriptions', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managesubscrorder&cid[]=' . $cid[0]);

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
			$input = JFactory::getApplication()->input;

			$url = 'index.php?option=com_vikappointments&task=subscrorder.add';

			$group = $input->getUint('group', null);

			if (!is_null($group))
			{
				// preserve group for the next record
				$url .= '&group=' . $group;
			}

			$this->setRedirect($url);
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
		$args['id_subscr']       = $input->getUint('id_subscr', 0);
		$args['id_user']         = $input->getUint('id_user', 0);
		$args['id_employee']     = $input->getUint('id_employee', 0);
		$args['id_payment']      = $input->getUint('id_payment', 0);
		$args['total_cost']      = $input->getFloat('total_cost', 0.0);
		$args['total_net']       = $input->getFloat('total_net', 0.0);
		$args['total_tax']       = $input->getFloat('total_tax', 0.0);
		$args['payment_charge']  = $input->getFloat('payment_charge', 0.0);
		$args['payment_tax']     = $input->getFloat('payment_tax', 0.0);
		$args['add_discount']    = $input->getString('add_discount', '');
		$args['remove_discount'] = $input->getBool('remove_discount', false);
		$args['status']          = $input->getString('status', '');
		$args['id']              = $input->getUint('id', 0);

		if ($args['add_discount'] === 'manual')
		{
			// fetch manual discount from request
			$args['add_discount'] = $input->get('manual_discount', [], 'array');
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.subscriptions', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get db model
		$order = $this->getModel();

		// try to save arguments
		$id = $order->save($args);

		if (!$id)
		{
			// get string error
			$error = $order->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managesubscrorder';

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
		$this->setRedirect('index.php?option=com_vikappointments&task=subscrorder.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.subscriptions', 'com_vikappointments'))
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

		$url = 'index.php?option=com_vikappointments&view=subscrorders';

		$group = $input->getUint('group', null);

		if (!is_null($group))
		{
			// preserve group to change list filtering
			$url .= '&group=' . $group;
		}

		$this->setRedirect($url);
	}

	/**
	 * AJAX end-point used to change the status code.
	 * The task expects the following arguments to be set in request.
	 *
	 * @param 	integer  id      The subscription order ID.
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
		if (!$data['id'] || !$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.subscriptions', 'com_vikappointments'))
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

		// update status
		$this->getModel()->save($data);

		// render HTML
		$code->html = JHtml::fetch('vaphtml.status.display', $code, $input->getString('layout'));

		// send code to caller
		$this->sendJSON($code);
	}
}

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
 * VikAppointments package order controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerPackorder extends VAPControllerAdmin
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

		// unset user state for being recovered again
		$app->setUserState('vap.packorder.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.packages', 'com_vikappointments') || !VikAppointments::isPackagesEnabled())
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managepackorder');

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
		$app->setUserState('vap.packorder.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.packages', 'com_vikappointments') || !VikAppointments::isPackagesEnabled())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managepackorder&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=packorder.add');
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
		$args['id_user']              = $input->getUint('id_user', 0);
		$args['purchaser_nominative'] = $input->getString('purchaser_nominative', '');
		$args['purchaser_mail']       = $input->getString('purchaser_mail', '');
		$args['purchaser_phone']      = $input->getString('purchaser_phone', '');
		$args['purchaser_prefix']     = $input->getString('purchaser_prefix', '');
		$args['purchaser_country']    = $input->getString('purchaser_country', '');
		$args['id_payment']           = $input->getUint('id_payment', 0);
		$args['total_cost']           = $input->getFloat('total_cost', 0);
		$args['total_net']            = $input->getFloat('total_net', 0);
		$args['total_tax']            = $input->getFloat('total_tax', 0);
		$args['payment_charge']       = $input->getFloat('payment_charge', 0);
		$args['payment_tax']          = $input->getFloat('payment_tax', 0);
		$args['status']               = $input->getString('status', '');
		$args['status_comment']       = $input->getString('comment', '');
		$args['notify']               = $input->getBool('notifycust', false);
		$args['add_discount']         = $input->getString('add_discount', '');
		$args['remove_discount']      = $input->getBool('remove_discount', false);
		$args['id']                   = $input->getUint('id', 0);

		if ($args['add_discount'] === 'manual')
		{
			// fetch manual discount from request
			$args['add_discount'] = $input->get('manual_discount', [], 'array');
		}

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->noRequiredCheckbox()
			->fetch();

		// load custom fields from request
		$args['custom_f'] = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = false);

		// inject uploads in custom fields array
		foreach ($tmp['uploads'] as $k => $v)
		{
			$args['custom_f'][$k] = $v;
		}

		// register data fetched by the custom fields so that the package
		// order is able to use them for saving purposes
		$args['fields_data'] = $tmp;

		// get selected packages
		$args['items'] = $input->get('pack_json', array(), 'array');
		// load deleted packages
		$args['deletedItems'] = $input->get('pack_deleted', array(), 'uint');

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.packages', 'com_vikappointments') || !VikAppointments::isPackagesEnabled())
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

			$url = 'index.php?option=com_vikappointments&view=managepackorder';

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
		$this->setRedirect('index.php?option=com_vikappointments&task=packorder.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.packages', 'com_vikappointments') || !VikAppointments::isPackagesEnabled())
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
		if (VikAppointments::isPackagesEnabled())
		{
			$this->setRedirect('index.php?option=com_vikappointments&view=packorders');
		}
		else
		{
			$this->setRedirect('index.php?option=com_vikappointments');
		}
	}

	/**
	 * AJAX end-point used to change the status code.
	 * The task expects the following arguments to be set in request.
	 *
	 * @param 	integer  id      The package order ID.
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
		if (!$data['id'] || !$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.packages', 'com_vikappointments') || !VikAppointments::isPackagesEnabled())
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
}

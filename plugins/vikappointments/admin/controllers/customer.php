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
 * VikAppointments customer controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerCustomer extends VAPControllerAdmin
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
		$app->setUserState('vap.customer.data', array());

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$url = 'index.php?option=com_vikappointments&view=managecustomer';

		if ($blank)
		{
			$url .= '&tmpl=component';
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

		// unset user state for being recovered again
		$app->setUserState('vap.customer.data', array());

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$url = 'index.php?option=com_vikappointments&view=managecustomer&cid[]=' . $cid[0];

		if ($blank)
		{
			$url .= '&tmpl=component';
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
			$this->setRedirect('index.php?option=com_vikappointments&task=customer.add');
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @param 	boolean  $copy  True to save the record as a copy.
	 *
	 * @return 	boolean
	 */
	public function save($copy = false)
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
		$args['jid']               = $input->get('jid', 0, 'int');
		$args['billing_name']      = $input->get('billing_name', '', 'string');
		$args['billing_mail']      = $input->get('billing_mail', '', 'string');
		$args['billing_phone']     = $input->get('billing_phone', '', 'string');
		$args['country_code']      = $input->get('country_code', '', 'string');
		$args['billing_state']     = $input->get('billing_state', '', 'string');
		$args['billing_city']      = $input->get('billing_city', '', 'string');
		$args['billing_address']   = $input->get('billing_address', '', 'string');
		$args['billing_address_2'] = $input->get('billing_address_2', '', 'string');
		$args['billing_zip']       = $input->get('billing_zip', '', 'string');
		$args['company']           = $input->get('company', '', 'string');
		$args['vatnum']            = $input->get('vatnum', '', 'string');
		$args['ssn']               = $input->get('ssn', '', 'string');
		$args['credit']            = $input->get('credit', null, 'float');
		$args['active_to_date']    = $input->get('active_to_date', '', 'string');
		$args['image']             = $input->get('image', '', 'string');
		$args['id']                = $input->get('id', 0, 'int');

		// fill user fields only if we need to create them
		if ($input->getBool('create_new_user'))
		{
			// user fields
			$args['user'] = array();
			$args['user']['username'] = $input->get('username', '', 'string');
			$args['user']['usermail'] = $input->get('usermail', '', 'string');
			$args['user']['password'] = $input->get('password', '', 'string');
			$args['user']['confirm']  = $input->get('confpassword', '', 'string');
		}

		// convert expiratiom date from local timezone to UTC
		$args['active_to_date'] = VAPDateHelper::getSqlDateLocale($args['active_to_date']);

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->noRequiredCheckbox()
			->fetch();

		// load custom fields from request
		$args['fields'] = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = false);

		// inject uploads in custom fields array
		foreach ($tmp['uploads'] as $k => $v)
		{
			$args['fields'][$k] = $v;
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get customer model
		$customer = $this->getModel();

		// try to save arguments
		$id = $customer->save($args);

		if (!$id)
		{
			if (!empty($args['user']))
			{
				// update user state data by injecting the user groups and username
				$data = $app->getUserState('vap.customer.data', array());
				$data['username'] = $args['user']['username'];
				$data['usermail'] = $args['user']['usermail'];
				$app->setUserState('vap.customer.data', $data);
			}

			// get string error
			$error = $customer->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managecustomer';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			if ($blank)
			{
				$url .= '&tmpl=component';
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		$notes = $input->get('notes', '', 'string');

		if ($notes)
		{
			// save the notes by using the apposite model
			$this->getModel('usernote')->saveDraft(array(
				'id_user' => $id,
				'content' => $notes,
			));
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		$url = 'index.php?option=com_vikappointments&task=customer.edit&cid[]=' . $id;

		if ($blank)
		{
			// keep blank template when returning to edit page
			$url .= '&tmpl=component';
		}

		// redirect to edit page
		$this->setRedirect($url);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
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
		$this->setRedirect('index.php?option=com_vikappointments&view=customers');
	}

	/**
	 * Sends a custom SMS to the specified customer.
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
		
		$cid = $input->get('cid', array(), 'uint');

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			// back to main list, not authorised to send SMS notifications
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		try
		{
			// get current SMS instance
			$smsapi = VAPApplication::getInstance()->getSmsInstance();
		}
		catch (Exception $e)
		{
			// back to main list, SMS API not configured
			$app->enqueueMessage(JText::translate('VAPSMSESTIMATEERR1'), 'error');
			$this->cancel();

			return false;
		}

		// load message from request
		$message = $input->get('sms_message', '', 'string');

		// make sure the message is not empty
		if (!$message)
		{
			// missing contents, back to main list
			$this->cancel();

			return false;
		}

		$notified = 0;
		$errors   = array();

		foreach ($cid as $id)
		{
			// get customer details
			$customer = VikAppointments::getCustomer($id);

			if ($customer && $customer->billing_phone)
			{
				// send message
				$response = $smsapi->sendMessage($customer->billing_phone, $message);

				// validate response
				if ($smsapi->validateResponse($response))
				{
					// successful notification
					$notified++;
				}
				else
				{
					// unable to send the notification, register error message
					$errors[] = $smsapi->getLog();
				}
			}
		}

		// update default message if needed
		if ($input->getBool('sms_keep_def'))
		{
			// alter configuration
			VAPFactory::getConfig()->set('smstextcust', $message);
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
	 * AJAX end-point to obtain a list of users belonging
	 * to the current platform (CMS).
	 *
	 * @return 	void
	 */
	public function jusers()
	{	
		$input = JFactory::getApplication()->input;

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
		
		$search = $input->getString('term');
		$id 	= $input->getUint('id', null);

		// get customers model
		$model = $this->getModel();

		// search CMS users
		$users = $model->searchUsers($search, $id);

		// send users to caller
		$this->sendJSON($users);
	}

	/**
	 * AJAX end-point to obtain a list of customers.
	 *
	 * @return 	void
	 */
	public function users()
	{	
		$input = JFactory::getApplication()->input;

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
		
		$search = $input->getString('term', '');

		// get customers model
		$model = $this->getModel();

		// search customers
		$customers = $model->search($search);

		// send users to caller
		$this->sendJSON($customers);
	}
}

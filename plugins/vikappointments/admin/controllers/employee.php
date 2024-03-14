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
 * VikAppointments employee controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerEmployee extends VAPControllerAdmin
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

		$data     = array();
		$id_group = $app->input->getInt('id_group', 0);

		if ($id_group > 0)
		{
			$data['id_group'] = $id_group;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.employee.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageemployee');

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
		$app->setUserState('vap.employee.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageemployee&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=employee.add');
		}
	}

	/**
	 * Task used to save the record data as a copy of the current item.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	void
	 */
	public function savecopy()
	{
		$this->save(true);
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
		$args['firstname']     = $input->getString('firstname');
		$args['lastname']      = $input->getString('lastname');
		$args['nickname']      = $input->getString('nickname');
		$args['alias']         = $input->getString('alias');
		$args['email']         = $input->getString('email');
		$args['notify']        = $input->getUint('notify', 0);
		$args['showphone']     = $input->getUint('showphone', 0);
		$args['quick_contact'] = $input->getUint('quick_contact', 0);
		$args['listable']      = $input->getUint('listable', 0);
		$args['phone']         = $input->getString('phone');
		$args['note']          = JComponentHelper::filterText($input->getRaw('note'));
		$args['image']         = $input->getString('image');
		$args['synckey']       = $input->getString('synckey');
		$args['ical_url']      = $input->getString('ical_url');
		$args['id_group']      = $input->getUint('id_group', 0);
		$args['jid']           = $input->getUint('jid', 0);
		$args['active_to']     = $input->getString('active_to', '');
		$args['timezone']      = $input->getString('timezone');
		$args['id']            = $input->getUint('id', 0);

		switch ($input->getString('active_to_type', ''))
		{
			case 'lifetime':
				$args['active_to'] = -1;
				break;

			case 'pending':
				$args['active_to'] = 0;
				break;

			default:
				/**
				 * Convert timestamp from local timezone to UTC.
				 *
				 * @since 1.7
				 */
				$args['active_to_date'] = VAPDateHelper::getSqlDateLocale($args['active_to']);
				$args['active_to']      = 1; 
		}

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		/**
		 * Try to auto-create a new group before saving the employee.
		 *
		 * @since 1.7
		 */
		if ($args['id_group'] == 0 && ($group_name = $input->getString('group_name')))
		{
			// make sure the user is authorised
			if ($user->authorise('core.create', 'com_vikappointments') && $user->authorise('core.access.groups', 'com_vikappointments'))
			{
				$group = $this->getModel('empgroup');

				// attempt to save group
				$id_group = $group->save(array('name' => $group_name));
				
				if ($id_group)
				{
					// overwrite the group ID
					$args['id_group'] = $id_group;
				}
			}
		}

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->employees()
			->noRequiredCheckbox()
			->fetch();

		// load custom fields from request
		$cust_req = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = false);

		if (!empty($tmp['uploads']))
		{
			// inject uploads within the custom fields array
			$cust_req = array_merge($cust_req, $tmp['uploads']);
		}

		// inject custom fields within the employee table
		foreach ($cust_req as $k => $v)
		{
			$args['field_' . $k] = $v;
		}

		// get employee model
		$employee = $this->getModel();

		// try to save arguments
		$id = $employee->save($args);

		if (!$id)
		{
			// get string error
			$error = $employee->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=manageemployee';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// get working days model
		$wdModel = $this->getModel('worktime');

		// delete working days only if we are not saving as copy
		if (!$copy)
		{
			// load deleted working times
			$wd_deleted = $input->get('wd_deleted', array(), 'uint');

			// delete working times before save the other ones
			$wdModel->delete($wd_deleted);
		}

		// load working times details
		$wd_json = $input->get('wd_json', array(), 'array');

		foreach ($wd_json as $json)
		{
			// obtain the list of grouped working times, if any
			$list = (array) json_decode($json, true);

			foreach ($list as $src)
			{
				// always specify the employee ID
				$src['id_employee'] = $id;

				if ($copy)
				{
					// unset ID to force creation
					$src['id'] = 0;
				}

				// attempt to save the working time
				$wdModel->save($src);
			}
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=employee.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
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
	 * Duplicates a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function duplicate()
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
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// duplicate selected records
		$result = $this->getModel()->duplicate($cid);

		/**
		 * @todo should we display how many records have been created?
		 */

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Toggles the listable status of an employee.
	 *
	 * @return 	boolean
	 */
	public function listable()
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

		$cid  = $app->input->get('cid', array(), 'uint');
		$task = $app->input->get('task', null);

		$state = $app->input->get('state', 0, 'uint');

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.employees', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state, 'listable');

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

		// build URL by using a specific return view (if specified)
		$url = 'index.php?option=com_vikappointments&view=' . $input->get('return', 'employees');

		// look for tmpl in query string
		if ($tmpl = $input->get('tmpl'))
		{
			// preserve given tmpl
			$url .= '&tmpl=' . $tmpl;
		}

		$this->setRedirect($url);
	}

	/**
	 * AJAX end-point used to obtain all the services assigned to the given employee.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	integer  id_emp  The employee ID.
	 *
	 * @return 	void
	 */
	public function servicesajax()
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

		$id_emp = $input->getUint('id_emp', 0);

		// get all assigned services
		$services = $this->getModel()->getServices($id_emp);

		// send resulting object to caller
		$this->sendJSON($services);
	}

	/**
	 * AJAX end-point used to import the working times from an uploaded file.
	 * 
	 * @return 	void
	 */
	public function importworkdays()
	{
		VAPLoader::import('libraries.worktime.import.manager');

		$input = JFactory::getApplication()->input;

		$options = [];

		if ($layout = $input->get('layout'))
		{
			// use the requested layout
			$options['layout'] = $layout;
		}

		try
		{
			// import file
			$results = VAPWorktimeImportManager::process('file', $options);
		}
		catch (Exception $e)
		{
			// an error occurred
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// send result to caller
		$this->sendJSON($results);
	}
}

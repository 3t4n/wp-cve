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
 * VikAppointments service controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerService extends VAPControllerAdmin
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
		$app->setUserState('vap.service.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageservice');

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
		$app->setUserState('vap.service.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageservice&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=service.add');
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
		$args['name']               = $input->getString('name', '');
		$args['alias']              = $input->getString('alias', '');
		$args['description']        = JComponentHelper::filterText($input->getRaw('description', ''));
		$args['duration']           = $input->getUint('duration', 0);
		$args['sleep']              = $input->getInt('sleep', 0);
		$args['interval']           = $input->getUint('interval', 1);
		$args['minrestr']           = $input->getInt('minrestr', 0);
		$args['mindate']            = $input->getInt('mindate', 0);
		$args['maxdate']            = $input->getInt('maxdate', 0);
		$args['price']              = $input->getFloat('price', 0);
		$args['id_tax']             = $input->getFloat('id_tax', 0);
		$args['max_capacity']       = $input->getUint('max_capacity', 1);
		$args['max_per_res']        = $input->getUint('max_per_res', 1);
		$args['min_per_res']        = $input->getUint('min_per_res', 1);
		$args['priceperpeople']     = $input->getUint('priceperpeople', 0);
		$args['app_per_slot']       = $input->getUint('app_per_slot', 0);
		$args['published']          = $input->getUint('published', 0);
		$args['quick_contact']      = $input->getUint('quick_contact', 0);
		$args['choose_emp']         = $input->getUint('choose_emp', 0);
		$args['random_emp']         = $input->getUint('random_emp', 0);
		$args['has_own_cal']        = $input->getUint('has_own_cal', 0);
		$args['checkout_selection'] = $input->getUint('checkout_selection', 0);
		$args['display_seats']      = $input->getUint('display_seats', 0);
		$args['use_recurrence']     = $input->getUint('use_recurrence', 0);
		$args['enablezip']          = $input->getUint('enablezip', 0);
		$args['image']              = $input->getString('image', ''); 
		$args['start_publishing']   = $input->getString('start_publishing', '');
		$args['end_publishing']     = $input->getString('end_publishing', '');
		$args['level']              = $input->getUint('level', 0);
		$args['id_group']           = $input->getUint('id_group', 0);
		$args['attachments']        = $input->getString('attachments', array());
		$args['metadata']           = $input->get('metadata', array(), 'array');
		$args['id']                 = $input->getUint('id', 0);

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		/**
		 * Try to auto-create a new group before saving the service.
		 *
		 * @since 1.7
		 */
		if ($args['id_group'] == 0 && ($group_name = $input->getString('group_name')))
		{
			// make sure the user is authorised
			if ($user->authorise('core.create', 'com_vikappointments') && $user->authorise('core.access.groups', 'com_vikappointments'))
			{
				$group = $this->getModel('group');

				// attempt to save group
				$id_group = $group->save(array('name' => $group_name));
				
				if ($id_group)
				{
					// overwrite the group ID
					$args['id_group'] = $id_group;
				}
			}
		}

		/**
		 * Convert timestamp from local timezone to UTC.
		 *
		 * @since 1.7
		 */
		$args['start_publishing'] = VAPDateHelper::getSqlDateLocale($args['start_publishing']);
		$args['end_publishing']   = VAPDateHelper::getSqlDateLocale($args['end_publishing']);

		// get service model
		$service = $this->getModel();

		// try to save arguments
		$id = $service->save($args);

		if (!$id)
		{
			// get string error
			$error = $service->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=manageservice';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// get save data
		$data = $service->getData();

		// get service-option relation model
		$optModel = $this->getModel('seroptassoc');

		if (!$copy)
		{
			// load deleted options
			$opt_deleted = $input->get('option_deleted', array(), 'uint');

			// delete options before save the other ones
			$optModel->delete($opt_deleted);

			// load new options
			$opt_ids = $input->get('id_option', array(), 'uint');
		}
		else
		{
			// We are saving as copy, so we don't need to delete the specified options.
			// Load the option IDs from a different pool.
			$opt_ids = $input->get('id_option_copy', array(), 'uint');
		}

		foreach ($opt_ids as $opt_id)
		{
			$src = array();
			$src['id_service'] = $id;
			$src['id_option']  = $opt_id;

			// attempt to save the relation
			$optModel->save($src);
		}

		// get service-employee relation model
		$empModel = $this->getModel('serempassoc');

		// load deleted employees
		$emp_deleted = $input->get('employee_deleted', array(), 'uint');

		// delete employees before save the other ones
		$empModel->delete($emp_deleted);

		// load employee details
		$emp_json = $input->get('employee_json', array(), 'array');

		foreach ($emp_json as $i => $json)
		{
			// decode the employee data
			$src = json_decode($json, true);

			if ($copy)
			{
				// unset ID to create a copy
				$src['id'] = 0;
			}

			// always specify the service ID
			$src['id_service'] = $id;
			// set up the ordering
			$src['ordering'] = $i + 1;

			if ($src['global'])
			{
				// overwrite with default service settings
				$src['rate']        = $data['price'];
				$src['duration']    = $data['duration'];
				$src['sleep']       = $data['sleep'];
				$src['description'] = '';
			}

			// attempt to save the service
			$empModel->save($src);
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=service.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
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
	 * Publishes the selected records.
	 *
	 * @return 	boolean
	 */
	public function publish()
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

		$state = $task == 'unpublish' ? 0 : 1;

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state);

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
		$this->setRedirect('index.php?option=com_vikappointments&view=services');
	}

	/**
	 * AJAX end-point used to obtain all the employees assigned to the given service.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	integer  id_ser  The service ID.
	 * @param 	boolean  all 	 True to return all the employees.
	 *							 False to obtain only the employees listed in the front-end.
	 *
	 * @return 	void
	 */
	public function employeesajax()
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

		$id_ser = $input->getUint('id_ser', 0);
		$all 	= $input->getBool('all', false);

		// negate ALL because the model expects a "strict" argument
		$employees = $this->getModel()->getEmployees($id_ser, !$all);

		// send resulting object to caller
		$this->sendJSON($employees);
	}

	/**
	 * AJAX end-point used to calculate the average price of the services.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	array  cid  A list of services IDs.
	 *
	 * @return 	void
	 */
	public function avgajax()
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

		$ids = $input->getUint('cid', array());

		// calculate the average price of the specified services
		$avg = $this->getModel()->getAveragePrice($ids);

		// send resulting amount to caller
		$this->sendJSON($avg);
	}

	/**
	 * AJAX end-point used to change the thumb color of the given service.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	integer  id  	The service ID.
	 * @param 	string   color 	The hex color.
	 *
	 * @return 	void
	 */
	public function changecolorajax()
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

		$args = array();
		$args['id']    = $input->getUint('id', 0);
		$args['color'] = $input->getString('color', '');

		// get service model
		$model = $this->getModel();

		// update service color without taking care
		// of any possible errors
		$model->save($args);

		// send response to caller
		$this->sendJSON(1);
	}
}

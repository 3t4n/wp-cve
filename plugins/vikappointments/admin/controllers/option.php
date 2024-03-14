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
 * VikAppointments option controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerOption extends VAPControllerAdmin
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
		$app->setUserState('vap.option.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageoption');

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
		$app->setUserState('vap.option.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageoption&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=option.add');
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
		$args['name']        = $input->getString('name', '');
		$args['description'] = $input->getString('description');
		$args['price']       = $input->getFloat('price', 0.0);
		$args['id_tax']      = $input->getUint('id_tax', 0);
		$args['published']   = $input->getUint('published', 0);
		$args['single']      = $input->getUint('single', 0);
		$args['maxq']        = $input->getUint('maxq', 1);
		$args['maxqpeople']  = $input->getUint('maxqpeople', 0);
		$args['required']    = $input->getUint('required', 0);
		$args['duration']    = $input->getUint('duration', 0);
		$args['image']       = $input->getString('image', '');
		$args['displaymode'] = $input->getUint('displaymode', 0);
		$args['level']       = $input->getUint('level', 0);
		$args['id_group']    = $input->getUint('id_group', 0);
		$args['id']          = $input->getUint('id', 0);

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		/**
		 * Try to auto-create a new group before saving the option.
		 *
		 * @since 1.7
		 */
		if ($args['id_group'] == 0 && ($group_name = $input->getString('group_name')))
		{
			// make sure the user is authorised
			if ($user->authorise('core.create', 'com_vikappointments'))
			{
				$group = $this->getModel('optiongroup');

				// attempt to save group
				$id_group = $group->save(array('name' => $group_name));
				
				if ($id_group)
				{
					// overwrite the group ID
					$args['id_group'] = $id_group;
				}
			}
		}

		// get option model
		$option = $this->getModel();

		// try to save arguments
		$id = $option->save($args);

		if (!$id)
		{
			// get string error
			$error = $option->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=manageoption';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// get option variation model
		$varModel = $this->getModel('optionvar');

		// delete variations only if we are not saving as copy
		if (!$copy)
		{
			// load deleted variations
			$var_deleted = $input->get('var_deleted', array(), 'uint');

			// delete variations before save the other ones
			$varModel->delete($var_deleted);
		}

		// load variations details
		$var_json = $input->get('var_json', array(), 'array');

		foreach ($var_json as $i => $json)
		{
			// decode the variation data
			$src = json_decode($json, true);

			if ($copy)
			{
				// unset ID to create a copy
				$src['id'] = 0;
			}

			// always specify the option ID
			$src['id_option'] = $id;
			// set up the ordering
			$src['ordering'] = $i + 1;

			// attempt to save the variation
			$varModel->save($src);
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=option.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
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
	 * Toggles the multi-selection status of an option.
	 *
	 * @return 	boolean
	 */
	public function single()
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state, 'single');

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Toggles the required/optional status of an option.
	 *
	 * @return 	boolean
	 */
	public function required()
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.options', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state, 'required');

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
		$this->setRedirect('index.php?option=com_vikappointments&view=options');
	}

	/**
	 * AJAX end-point used to load the option details.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	integer  id_opt  The option ID.
	 *
	 * @return 	void
	 */
	public function detailsajax()
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

		$id = $input->getUint('id_opt', 0);

		// get option model
		$model = $this->getModel();
		// attempt to load option
		$option = $model->getItem($id);

		if (!$option)
		{
			// option not found, raise error
			UIErrorFactory::raiseError(404, JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
		}

		// send option to caller
		$this->sendJSON($option);
	}
}

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
VAPLoader::import('libraries.customfields.factory');

/**
 * VikAppointments custom field controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerCustomf extends VAPControllerAdmin
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
		$app->setUserState('vap.customf.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.custfields', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managecustomf');

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
		$app->setUserState('vap.customf.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.custfields', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managecustomf&cid[]=' . $cid[0]);

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

			$url = 'index.php?option=com_vikappointments&task=customf.add';

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
		$args['group']       = $input->getUint('group', 0);
		$args['name']        = $input->getString('name', '');
		$args['description'] = JComponentHelper::filterText($input->getRaw('description', ''));
		$args['type']        = $input->getString('type', '');
		$args['required']    = $input->getUint('required', 0);
		$args['repeat']      = $input->getUint('repeat', 0);
		$args['readonly']    = $input->getUint('readonly', 0);
		$args['rule']        = $input->getString('rule', '');
		$args['locale']      = $input->getString('locale', '*');
		$args['multiple']    = 0;
		$args['poplink']     = '';
		$args['choose']      = '';
		$args['id']          = $input->getUint('id', 0);

		if ($args['group'] == 0)
		{
			// customers group
			$args['id_employee'] = $input->getUint('id_employee', 0);
			$args['services']    = $input->getUint('services', array());
		}
		else if ($args['group'] == 1)
		{
			// employees group
			$args['formname'] = $input->getString('formname', null);
		}

		if ($args['type'] == 'select')
		{
			/**
			 * Do not use a string filter so that we can preserve the keys
			 * of the options. Use array_filter instead to get rid of the
			 * options with blank contents.
			 *
			 * @since 1.7
			 */
			$args['choose']   = array_filter($input->get('choose', array(), 'array'));
			$args['multiple'] = $input->getUint('multiple', 0);
		}
		else if ($args['type'] == 'textarea')
		{
			$args['choose'] = array(
				'editor' => $input->getUint('use_editor', 0),
			);
		}
		else if ($args['type'] == 'number')
		{
			$args['choose'] = array(
				'min'      => $input->getString('number_min', ''),
				'max'      => $input->getString('number_max', ''),
				'decimals' => $input->getUint('number_decimals', 0),
			);

			if (strlen($args['choose']['min']))
			{
				$args['choose']['min'] = (float) $args['choose']['min'];
			}

			if (strlen($args['choose']['max']))
			{
				$args['choose']['max'] = (float) $args['choose']['max'];
			}
		}
		else if ($args['type'] == 'checkbox')
		{
			$args['poplink'] = $input->getString('poplink', '');
		}
		else if ($args['type'] == 'file')
		{
			$args['choose']   = $input->getString('filters', '');
			$args['multiple'] = $input->getUint('multiple', 0);
		}
		else if ($args['type'] == 'separator')
		{
			$args['choose'] = $input->getString('sep_suffix', '');
		}
		
		if ($args['rule'] == 'phone')
		{
			$args['choose'] = $input->getString('country_code', '');
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.custfields', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get custom field model
		$customf = $this->getModel();

		// try to save arguments
		$id = $customf->save($args);

		if (!$id)
		{
			// get string error
			$error = $customf->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managecustomf';

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

		// try to obtain an error, because the model might register
		// an error message also in case of successful saving
		$error = $customf->getError(null, true);

		if ($error)
		{
			$app->enqueueMessage($error, 'error');
		}

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=customf.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.custfields', 'com_vikappointments'))
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.custfields', 'com_vikappointments'))
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
		$input = JFactory::getApplication()->input;

		$url = 'index.php?option=com_vikappointments&view=customf';

		$group = $input->getUint('group', null);

		if (!is_null($group))
		{
			// preserve group to change list filtering
			$url .= '&group=' . $group;
		}

		$this->setRedirect($url);
	}
}

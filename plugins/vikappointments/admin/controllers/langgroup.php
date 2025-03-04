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
 * VikAppointments group language controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerLanggroup extends VAPControllerAdmin
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
		$app->setUserState('vap.langgroup.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.groups', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_group = $app->input->getUint('id_group');

		// extract page type from request:
		// - 1 for services (default)
		// - 2 for employees
		$type = $app->input->get('type', 1, 'uint');

		$this->setRedirect('index.php?option=com_vikappointments&view=managelanggroup&id_group=' . $id_group . '&type=' . $type);

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
		$app->setUserState('vap.langgroup.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.groups', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		// extract page type from request:
		// - 1 for services (default)
		// - 2 for employees
		$type = $app->input->get('type', 1, 'uint');

		$this->setRedirect('index.php?option=com_vikappointments&view=managelanggroup&cid[]=' . $cid[0] . '&type=' . $type);

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

			// recover group ID from request
			$id_group = $input->getUint('id_group');

			// extract page type from request:
			// - 1 for services (default)
			// - 2 for employees
			$type = $input->get('type', 1, 'uint');

			$url = 'index.php?option=com_vikappointments&task=langgroup.add&type=' . $type;

			if ($id_group)
			{
				$url .= '&id_group=' . $id_group;
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
		$args['name']        = $input->get('name', '', 'string');
		$args['description'] = JComponentHelper::filterText($input->get('description', '', 'raw'));
		$args['id'] 	     = $input->get('id', 0, 'uint');
		$args['id_group']    = $input->get('id_group', 0, 'uint');
		$args['tag']         = $input->get('tag', '', 'string');
		
		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.groups', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// extract page type from request:
		// - 1 for services (default)
		// - 2 for employees
		$pagetype = $input->get('type', 1, 'uint');

		// get db model
		$langgroup = $this->getGroupModel($pagetype);

		// try to save arguments
		$id = $langgroup->save($args);

		if (!$id)
		{
			// get string error
			$error = $langgroup->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managelanggroup&type=' . $pagetype;

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}
			else
			{
				$url .= '&id_group=' . $args['id_group'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=langgroup.edit&cid[]=' . $id . '&type=' . $pagetype);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.groups', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getGroupModel()->delete($cid);

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

		// recover group ID from request
		$id_group = $input->getUint('id_group');

		// extract page type from request:
		// - 1 for services (default)
		// - 2 for employees
		$type = $input->get('type', 1, 'uint');

		$url = 'index.php?option=com_vikappointments&view=langgroups&type=' . $type;

		if ($id_group)
		{
			$url .= '&id_group=' . $id_group;
		}

		$this->setRedirect($url);
	}

	/**
	 * Creates the correct database model.
	 *
	 * @param 	mixed  $type  An optional page type. If not specified,
	 *                        it will be retrieved from the request.
	 *
	 * @return 	JModel
	 */
	protected function getGroupModel($type = null)
	{
		if (!$type)
		{
			$input = JFactory::getApplication()->input;

			// extract page type from request:
			// - 1 for services (default)
			// - 2 for employees
			$type = $input->get('type', 1, 'uint');
		}

		// use correct model according to the specified page type
		$tbl = $type == 1 ? 'langgroup' : 'langempgroup';

		return $this->getModel($tbl);
	}
}

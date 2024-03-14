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
 * VikAppointments service working days controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerSerworkday extends VAPControllerAdmin
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
		$app->setUserState('vap.serworkday.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$url = 'index.php?option=com_vikappointments&view=manageserworkday';

		$id_service  = $app->input->getUint('id_service', 0);
		$id_employee = $app->input->getUint('id_employee', 0);

		$url .= '&id_service=' . $id_service;
		$url .= '&id_employee=' . $id_employee;

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
		$app->setUserState('vap.serworkday.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$url = 'index.php?option=com_vikappointments&view=manageserworkday&cid[]=' . $cid[0];

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
			$url = 'index.php?option=com_vikappointments&task=serworkday.add';

			$id_service  = $app->input->getUint('id_service', 0);
			$id_employee = $app->input->getUint('id_employee', 0);

			$url .= '&id_service=' . $id_service;
			$url .= '&id_employee=' . $id_employee;

			$this->setRedirect($url);
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

		$id_employee = $input->getUint('id_employee', 0);
		
		$args = array();
		$args['id_service']  = $input->getUint('id_service', 0);
		$args['id_employee'] = $input->getUint('id_employee', 0);
		$args['day']         = $input->getInt('day', 0);
		$args['fromts']      = $input->getUint('fromts', 0);
		$args['endts']       = $input->getUint('endts', 0);
		$args['closed']      = $input->getUint('closed', 0);
		$args['id_location'] = $input->getUint('id_location', 0);
		$args['id']          = $input->getUint('id', 0);

		if ($args['day'] == -1)
		{
			$args['date'] = $input->getString('date', '');
		}

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		// always unset parent, because we don't want to
		// keep a relation with the original working day
		$args['parent'] = -1;

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get worktime model
		$model = $this->getModel('worktime');

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=manageserworkday';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}
			else
			{
				$url .= '&id_service=' . $args['id_service'];
				$url .= '&id_employee=' . $args['id_employee'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		$url = 'index.php?option=com_vikappointments&task=serworkday.edit&cid[]=' . $id;

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel('worktime')->delete($cid);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function restore()
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

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_service  = $app->input->get('id_service', 0, 'uint');
		$id_employee = $app->input->get('id_employee', 0, 'uint');

		// delete selected records
		$restored = $this->getModel('worktime')->restore($id_service, $id_employee);

		if ($restored)
		{
			$app->enqueueMessage(JText::translate('VAPSERWORKDAYSRESTORED1'));
		}
		else
		{
			$app->enqueueMessage(JText::translate('VAPSERWORKDAYSRESTORED0'), 'warning');
		}

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

		$id_service  = $input->getUint('id_service', 0);
		$id_employee = $input->getUint('id_employee', 0);
		
		$url = 'index.php?option=com_vikappointments&view=serworkdays';

		$url .= '&id_service=' . $id_service;
		$url .= '&id_employee=' . $id_employee;

		$this->setRedirect($url);
	}
}

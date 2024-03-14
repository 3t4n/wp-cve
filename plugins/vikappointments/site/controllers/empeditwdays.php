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

VAPLoader::import('libraries.employee.area.controller');

/**
 * Employee area working days controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditwdays extends VAPEmployeeAreaController
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.worktime.data', array());

		// check user permissions
		if (!$auth->isEmployee() || !$auth->manageWorkDays())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_service = $app->input->getUint('id_service', 0);

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditwdays' . ($id_service ? '&id_service=' . $id_service : ''));

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.worktime.data', array());

		// check user permissions
		if (!$auth->isEmployee() || !$auth->manageWorkDays())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$id_service = $app->input->getUint('id_service', 0);

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditwdays&cid[]=' . $cid[0] . ($id_service ? '&id_service=' . $id_service : ''));

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 *
	 * @since   1.7
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Save employee working day.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

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

		$id_worktime = $input->getUint('id', 0);

		// check user permissions
		if (!$auth->manageWorkDays($id_worktime))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_service = $input->getUint('id_service', 0);

		// check user permissions
		if ($id_service && !$auth->manageServices($id_service, $readOnly = true))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();
		$args['type']		 = $input->getUint('type', 1);
		$args['day'] 		 = $input->getUint('day', 0);
		$args['fromts'] 	 = $input->getUint('fromts');
		$args['endts'] 	     = $input->getUint('endts');
		$args['closed'] 	 = $input->getUint('closed', 0);
		$args['date'] 	     = $input->getString('date_from', '');
		$args['date_to'] 	 = $input->getString('date_to', '');
		$args['id_location'] = $input->getUint('id_location', 0);
		$args['id'] 		 = $id_worktime;
		
		if ($id_service)
		{
			// create/update only for the specified service
			$args['id_service'] = $id_service;
		}
		else
		{
			// join to the specified services
			$args['services'] = $input->getUint('services', array());
		}

		// get working day model
		$model = $this->getModel();

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=empeditwdays';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			if ($id_service)
			{
				$url .= '&id_service=' . $id_service;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditwdays.edit&cid[]=' . $id . ($id_service ? '&id_service=' . $id_service : ''));

		return true;
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		if ($id = $app->input->getUint('id'))
		{
			$cid[] = $id;
		}

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

		try
		{
			// delete selected records
			if ($this->getModel()->delete($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPWDREMOVED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list (reset list limit)
		$this->cancel(['listlimit' => 0]);

		return true;
	}

	/**
	 * Clones the specified working day.
	 *
	 * @return 	void
	 */
	public function duplicate()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		if ($id = $app->input->getUint('id'))
		{
			$cid[] = $id;
		}

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

		try
		{
			// duplicate selected records
			if ($this->getModel()->duplicate($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPWDCREATED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Restores the default working days.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function restore()
	{
		$app = JFactory::getApplication();

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

		$id_service = $app->input->getUint('id_service', 0);

		try
		{
			if ($this->getModel()->restore($id_service))
			{
				$app->enqueueMessage(JText::translate('VAPSERWORKDAYSRESTORED1'));
			}
			else
			{
				$app->enqueueMessage(JText::translate('VAPSERWORKDAYSRESTORED0'), 'warning');
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list (reset list limit)
		$this->cancel(['listlimit' => 0]);

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @param 	array  $query  An array of query arguments.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel(array $query = array())
	{
		$url = 'index.php?option=com_vikappointments&view=empwdays';

		$input = JFactory::getApplication()->input;

		if ($id_service = $input->getUint('id_service'))
		{
			// include service ID in redirect URL
			$query['id_service'] = $id_service;
		}

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}
}

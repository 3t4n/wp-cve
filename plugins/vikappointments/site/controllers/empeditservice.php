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
 * Employee area edit service controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditservice extends VAPEmployeeAreaController
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
		$app->setUserState('vap.emparea.service.data', array());

		// check user permissions
		if (!$auth->isEmployee() || !$auth->createService($count = true))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditservice');

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
		$app->setUserState('vap.emparea.service.data', array());

		$cid = $app->input->getUint('cid', array(0));

		// check user permissions
		if (!$auth->isEmployee() || (!$auth->manageServices($cid[0]) && !$auth->manageServicesRates()))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditservice&cid[]=' . $cid[0]);

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
	 * Save employee service.
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

		// check user permissions
		if (!$auth->isEmployee())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_service = $input->getUint('id', 0);

		if ($id_service)
		{
			// check whether the employee is able to manage this service
			$canEdit = $auth->manageServices($id_service);
			$canEditRate = $auth->manageServicesRates();
		}
		else
		{
			// check whether the employee is able to create services
			$canEdit = $auth->createService($count = true);
			$canEditRate = false;
		}

		if (!$canEdit && !$canEditRate)
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		// get args
		$args = array();
		$args['id']           = $id_service;
		$args['price']        = $input->getFloat('price', 0);
		$args['duration']     = $input->getUint('duration', 0);
		$args['sleep']        = $input->getInt('sleep', 0);
		$args['max_capacity'] = $input->getUint('max_capacity', 1);
		$args['description']  = $input->getRaw('description', '');

		if ($canEdit)
		{
			$args['name']             = $input->getString('name', '');
			$args['id_group']         = $input->getUint('id_group', 0);
			$args['interval']         = $input->getUint('interval', 0);
			$args['min_per_res']      = $input->getUint('min_per_res', 1);
			$args['max_per_res']      = $input->getUint('max_per_res', 1);
			$args['priceperpeople']   = $input->getUint('priceperpeople', 0);
			$args['app_per_slot']     = $input->getUint('app_per_slot', 0);
			$args['display_seats']    = $input->getUint('display_seats', 0);
			$args['published']        = $input->getUint('published', 0);
			$args['has_own_cal']      = $input->getUint('has_own_cal', 0);
			$args['enablezip']        = $input->getUint('enablezip', 0);
			$args['use_recurrence']   = $input->getUint('use_recurrence', 0);
			$args['start_publishing'] = $input->getString('start_publishing', ''); 
			$args['end_publishing']   = $input->getString('end_publishing', '');

			/**
			 * Convert timestamp from local timezone to UTC.
			 *
			 * @since 1.7
			 */
			$args['start_publishing'] = VAPDateHelper::getSqlDateLocale($args['start_publishing']);
			$args['end_publishing']   = VAPDateHelper::getSqlDateLocale($args['end_publishing']);
		}

		if ($canEdit)
		{
			// get service model
			$model = $this->getModel();

			// try to save arguments
			$id = $model->save($args);
		}
		else
		{
			// get service-employee relations model
			$model = $this->getModel('serempassoc');

			$args['id']          = 0;
			$args['id_employee'] = $auth->id;
			$args['id_service']  = $id_service;
			$args['global']      = 0;

			$id = $model->save($args);

			if ($id)
			{
				// use the ID service as PK
				$id = $id_service;
			}
		}

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=empeditservice';

			if ($id_service)
			{
				$url .= '&cid[]=' . $id_service;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditservice.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Attaches the working days of the employee to the specified service.
	 *
	 * @param 	integer  $id_service 	The service ID.
	 * @param 	integer  $id_employee 	The employee ID.
	 *
	 * @return 	boolean  True if attached, otherwise false.
	 *
	 * @deprecated 1.8 	Use VikAppointments::attachWorkingDays() instead.
	 */
	public function attachWorkingDays($id_service, $id_employee)
	{
		return VikAppointments::attachWorkingDays($id_service, $id_employee);
	}

	/**
	 * Removes the service or detach it from the employee.
	 *
	 * @return 	void
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
				$app->enqueueMessage(JText::translate('VAPEMPSERREMOVED1'));	
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
		$url = 'index.php?option=com_vikappointments&view=empserviceslist';

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}
}

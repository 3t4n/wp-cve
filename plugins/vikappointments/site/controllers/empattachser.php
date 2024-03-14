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
 * Employee area attach services controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpattachser extends VAPEmployeeAreaController
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

		// check user permissions
		if (!$auth->isEmployee() || !$auth->attachServices())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empattachser');

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
	 * Save employee-services assignments.
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
		if (!$auth->isEmployee() || !$auth->attachServices())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// always set redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&view=empattachser');

		$services = $input->get('services', array(), 'uint');

		// get service model
		$model = $this->getModel();

		// try to attach the services
		$count = $model->save(array('services' => $services));

		if (!$count)
		{
			// get string error
			$error = $model->getError(null, true);

			if ($error)
			{
				// display error message
				$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');
			}
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('VAPEMPATTACHSERCREATED'));

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

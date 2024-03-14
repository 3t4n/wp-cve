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
 * Employee area edit working days - locations controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmplocwdays extends VAPEmployeeAreaController
{
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
			$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');
		}
	}

	/**
	 * Save employee working days - locations relationships.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

		// always redirect to edit page
		$this->cancel();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');

			return false;
		}

		// check user permissions
		if (!$auth->manageWorkDays())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');

			return false;
		}

		// get args
		$args = array();
		$args['locations'] = $input->get('location', array(), 'array');

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
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

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
		$url = 'index.php?option=com_vikappointments&view=emplocwdays';

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}
}

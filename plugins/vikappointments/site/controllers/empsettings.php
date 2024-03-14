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
 * Employee area edit settings controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpsettings extends VAPEmployeeAreaController
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
	 * Save employee settings.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

		// always redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&view=empsettings');

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
		if (!$auth->isEmployee())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');

			return false;
		}
		
		// get settings
		$args = array();
		$args['listlimit']    = $input->getUint('listlimit', 5);
		$args['listposition'] = $input->getUint('listposition', 1);
		$args['listordering'] = $input->getString('listordering', 'ASC');
		$args['numcals']      = $input->getUint('numcals', 6);
		$args['firstmonth']   = $input->getUint('firstmonth', 0);
		$args['zip_field_id'] = $input->getUint('zip_field_id', 0);
		$args['zipcodesfrom'] = $input->get('zip_code_from', array(), 'string');
		$args['zipcodesto']   = $input->get('zip_code_to', array(), 'string');
		$args['synckey']      = $input->getString('synckey', '');
		$args['timezone']     = $input->getString('timezone', '');

		// get settings model
		$model = $this->getModel('empsettingsman');

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
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
		$app->enqueueMessage(JText::translate('VAPEMPSETTINGSUPDATED'));

		return true;
	}
}

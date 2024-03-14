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
 * VikAppointments plugin License controller.
 *
 * @since 1.0
 */
class VikAppointmentsControllerLicense extends VAPControllerAdmin
{
	/**
	 * License Key validation through ajax request.
	 * This task takes also the change-log for the current version.
	 *
	 * @return 	void
	 */
	public function validate()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}

		$input = JFactory::getApplication()->input;

		// get input key
		$key = $input->getString('key');

		// get license model
		$model = $this->getModel();

		// dispatch license key validation
		$response = $model->validate($key);

		// make sure the validation went fine
		if ($response === false)
		{
			// nope, retrieve the error
			$error = $model->getError(null, $toString = false);

			// an error will be always an exception
			throw $error;
		}

		$this->sendJSON($response);
	}

	/**
	 * Downloads the PRO version from VikWP servers.
	 *
	 * @return 	void
	 */
	public function downloadpro()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}

		$input = JFactory::getApplication()->input;

		// get input key
		$key = $input->getString('key');

		// get license model
		$model = $this->getModel();

		// dispatch pro version download
		$response = $model->download($key);

		// make sure the download went fine
		if ($response === false)
		{
			// nope, retrieve the error
			$error = $model->getError(null, $toString = false);

			// an error will be always an exception
			throw $error;
		}

		// downloaded successfully
	}
}

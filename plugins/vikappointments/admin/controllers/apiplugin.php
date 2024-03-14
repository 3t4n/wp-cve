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
 * VikAppointments API plugin controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerApiplugin extends VAPControllerAdmin
{
	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// unset user state for being recovered again
		$app->setUserState('vap.apiplugin.data', array());

		// check user permissions
		if (!JFactory::getUser()->authorise('core.edit', 'com_vikappointments') || !VAPFactory::getApi()->isEnabled())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getString('cid', array(''));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageapiplugin&cid[]=' . $cid[0]);

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
		$cid  = $app->input->get('cid', array(), 'string');

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
		if (!JFactory::getUser()->authorise('core.delete', 'com_vikappointments') || !VAPFactory::getApi()->isEnabled())
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$res = $this->getModel()->delete($cid);

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
		$this->setRedirect('index.php?option=com_vikappointments&view=apiplugins');
	}

	/**
	 * AJAX end-point used to load the supported columns of the given model.
	 * In case the given model doesn't support tables, an empty object will
	 * be returned.
	 *
	 * This task expects the following arguments to be set in request.
	 *
	 * @param 	string 	model  The model to load.
	 *
	 * @return 	void
	 */
	public function tableajax()
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

		// load model from request
		$modelName = $input->get('model');

		if (!$modelName)
		{
			UIErrorFactory::raiseError(400, 'Missing model name');
		}

		// load model
		$model = JModelVAP::getInstance($modelName);

		if (!$model)
		{
			UIErrorFactory::raiseError(404, sprintf('Model [%s] not found', $modelName));
		}

		try
		{
			// send default item details
			$this->sendJSON($model->getItem(0, true));
		}
		catch (Exception $e)
		{
			// an error occurred, send empty object
			$this->sendJSON(new stdClass);
		}
	}
}

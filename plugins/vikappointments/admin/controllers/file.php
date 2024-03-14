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
 * VikAppointments file controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerFile extends VAPControllerAdmin
{
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
		$app->setUserState('vap.file.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.config'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getString('cid', array(''));

		$url = 'index.php?option=com_vikappointments&view=managefile&cid[]=' . $cid[0];

		if ($app->input->get('tmpl') == 'component')
		{
			$url .= '&tmpl=component';
		}

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
	 * Task used to save the record data as a copy of the current item.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	void
	 */
	public function savecopy()
	{
		$input = JFactory::getApplication()->input;

		// get directory and file name from request
		$directory = $input->getString('dir');
		$filename  = $input->getString('filename');

		// check if directory exists
		if (!is_dir($directory))
		{
			// try to decode from base64
			$directory = base64_decode($directory);
		}

		// build final path
		$file = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

		// inject file in request
		$input->set('file', $file);

		// launch save method
		$this->save();
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
		$args['id']      = $input->get('file', '', 'string');
		$args['content'] = $input->get('content', '', 'raw');

		// check if blank layout
		$tmpl = $input->get('tmpl') == 'component';

		// check user permissions
		if (!$user->authorise('core.access.config', 'com_vikappointments'))
		{
			if ($tmpl)
			{
				// throw exception in case of blank layout
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
			}
			
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get file model
		$model = $this->getModel();

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managefile&cid[]=' . base64_encode($args['id']);

			if ($tmpl)
			{
				$url .= '&tmpl=component';
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		$url = 'index.php?option=com_vikappointments&task=file.edit&cid[]=' . base64_encode($id);

		if ($tmpl)
		{
			$url .= '&tmpl=component';
		}

		// redirect to edit page
		$this->setRedirect($url);

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfig');
	}
}

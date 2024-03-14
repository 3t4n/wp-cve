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
 * VikAppointments import controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerImport extends VAPControllerAdmin
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

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$type = $app->input->get('import_type', '', 'string');

		$url = 'index.php?option=com_vikappointments&view=manageimport&import_type=' . $type;

		$args = $app->input->get('import_args', array(), 'array');

		if ($args)
		{
			$url .= '&' . http_build_query(array('import_args' => $args));
		}

		$this->setRedirect($url);

		return true;
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

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$args = array();
		$args['assoc'] = $input->get('column', array(), 'array');
		$args['type']  = $input->getString('import_type');
		$args['args']  = $input->get('import_args', array(), 'array');

		// get import model
		$import = $this->getModel();

		// try to import records
		$result = $import->save($args);

		// go ahead only in case of result
		if ($result)
		{
			// display number of imported records
			$app->enqueueMessage(
				JText::sprintf('VAPIMPORTRECORDSADDED', $result['count'], $result['total']),
				$result['count'] ? 'success' : 'error'
			);

			// look for any registered errors
			$errors = $import->getErrors();

			if (count($errors))
			{
				/**
				 * Directly display all the messages because the
				 * enqueueMessage() method now seems to use a filter
				 * to strip all blacklisted tags and attributes, and
				 * "onclick" seems to be one of them.
				 *
				 * @since 1.7
				 */	
				$app->enqueueMessage(implode('', $errors), 'error');
			}
		}

		// back to import list
		$this->cancel();

		return true;
	}

	/**
	 * Task used to upload files via AJAX.
	 *
	 * @return 	void
	 */
	public function dropupload()
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
		
		$csv  = $input->files->get('source', null, 'array');
		$type = $input->getString('import_type');

		// get import model
		$import = $this->getModel();

		// try to upload file
		$id = $import->upload($type, $csv);

		if ($id === false)
		{
			// get string error
			$error = $import->getError(null, true);

			// something went wrong, raise error
			UIErrorFactory::raiseError(500, $error);
		}

		// in case of success, retrieve file properties
		$resp = AppointmentsHelper::getFileProperties($id);

		// send file info to caller
		$this->sendJSON($resp);
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

		$type = $app->input->getString('import_type');

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete imported file of specified type
		$this->getModel()->delete($type);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Downloads a sample file for the requested import type.
	 *
	 * @return 	void
	 */
	public function downloadsample()
	{
		$app = JFactory::getApplication();
		$input = $app->input;

		$type = $input->getString('import_type');

		VAPLoader::import('libraries.import.factory');
		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		$file = $handler->getSampleFile();

		if ($file === false)
		{
			throw new Exception('This type does not own any sample data.', 404);
		}

		// prepare download headers
		$app->setHeader('Content-Type', 'application/csv');
		$app->setHeader('Content-Disposition', 'attachment; filename="' . basename($file) . '"');
		$app->sendHeaders();

		// read the file at once, because its size should be pretty small
		readfile($file);

		// terminate request
		$app->close();
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$app = JFactory::getApplication();

		$type = $app->input->get('import_type', '', 'string');

		$url = 'index.php?option=com_vikappointments&view=import&import_type=' . $type;

		$args = $app->input->get('import_args', array(), 'array');

		if ($args)
		{
			$url .= '&' . http_build_query(array('import_args' => $args));
		}

		$this->setRedirect($url);
	}
}

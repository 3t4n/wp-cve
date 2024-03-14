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
 * VikAppointments media controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerMedia extends VAPControllerAdmin
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
		$app->setUserState('vap.media.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.media', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$url = 'index.php?option=com_vikappointments&view=newmedia';

		if ($app->input->getBool('configure'))
		{
			// append configuration flag
			$url .= '&configure=1';
		}

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
		$app->setUserState('vap.media.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.media', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getString('cid', array(''));

		$this->setRedirect('index.php?option=com_vikappointments&view=managemedia&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&view=media');
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
			$this->setRedirect('index.php?option=com_vikappointments&task=media.add');
		}
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
		$args['id']        = $input->get('media', null, 'string');
		$args['name']      = $input->get('name', null, 'string');
		$args['action']    = $input->get('action', 0, 'uint');
		$args['oriwres']   = $input->get('oriwres', 512, 'uint');
		$args['orihres']   = $input->get('orihres', 512, 'uint');
		$args['smallwres'] = $input->get('smallwres', 256, 'uint');
		$args['smallhres'] = $input->get('smallhres', 256, 'uint');
		$args['isresize']  = $input->get('isresize', 0, 'uint');
		$args['alt']       = $input->get('alt', '', 'string');
		$args['title']     = $input->get('title', '', 'string');
		$args['caption']   = $input->get('caption', '', 'string');

		$args['file'] = $input->files->get('file', null, 'array');

		$rule = 'core.' . ($args['id'] ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.media', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get media model
		$media = $this->getModel();

		// try to save arguments
		$id = $media->save($args);

		if ($id === false)
		{
			// get string error
			$error = $media->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			if ($args['id'])
			{
				$url = 'index.php?option=com_vikappointments&view=managemedia&cid[]=' . $args['id'];
			}
			else
			{
				$url = 'index.php?option=com_vikappointments&view=newmedia';
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=media.edit&cid[]=' . $id);

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
		
		$args = array();
		$args['file']      = 'file';
		$args['path']      = $input->get('path', null, 'base64');
		$args['oriwres']   = $input->get('oriwres', null, 'uint');
		$args['orihres']   = $input->get('orihres', null, 'uint');
		$args['smallwres'] = $input->get('smallwres', null, 'uint');
		$args['smallhres'] = $input->get('smallhres', null, 'uint');
		$args['isresize']  = $input->get('isresize', 0, 'uint');

		// get media model
		$media = $this->getModel();

		// try to save arguments
		$id = $media->save($args);

		if ($id === false)
		{
			// get string error
			$error = $media->getError(null, true);

			// something went wrong, raise error
			UIErrorFactory::raiseError(500, $error);
		}

		// get saved data
		$data = $media->getData();

		// in case of success, retrieve media properties
		$resp = AppointmentsHelper::getFileProperties($data['file']);

		if ($resp)
		{
			// include HTML preview of the media file
			$resp['html'] = $media->renderMedia($resp['file']);
		}

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

		$cid  = $app->input->get('cid', array(), 'string');
		$ajax = $app->input->getBool('ajax');
		$path = $app->input->getBase64('path', null);

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.media', 'com_vikappointments'))
		{
			if ($ajax)
			{
				UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
			}
			else
			{
				// back to main list, not authorised to delete records
				$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
				$this->cancel();

				return false;
			}
		}

		// delete selected records
		$res = $this->getModel()->delete($cid, $path);

		if ($ajax)
		{
			$this->sendJSON($res);
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
		$this->setRedirect('index.php?option=com_vikappointments&view=media');
	}
}

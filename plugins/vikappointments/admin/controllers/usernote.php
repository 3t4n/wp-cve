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
 * VikAppointments user note controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerUsernote extends VAPControllerAdmin
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

		$data = array();

		// preserve user ID, if set
		$id_user = $app->input->getUint('id_user', 0);

		if ($id_user > 0)
		{
			$data['id_user'] = $id_user;
		}

		// preserve parent ID, if set
		$id_parent = $app->input->getUint('id_parent', 0);

		if ($id_parent > 0)
		{
			$data['id_parent'] = $id_parent;
		}

		// preserve note group, if set
		$group = $app->input->getString('group', '');

		if ($group)
		{
			$data['group'] = $group;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.usernote.data', $data);

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageusernote' . ($blank ? '&tmpl=component' : ''));

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
		$app->setUserState('vap.usernote.data', array());

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=manageusernote&cid[]=' . $cid[0] . ($blank ? '&tmpl=component' : ''));

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
			$app = JFactory::getApplication();

			$url = '';
		
			// preserve user ID, if set
			$id_user = $app->input->getUint('id_user', 0);

			if ($id_user > 0)
			{
				$url .= '&id_user=' . $id_user;
			}

			// preserve parent ID, if set
			$id_parent = $app->input->getUint('id_parent', 0);

			if ($id_parent > 0)
			{
				$url .= '&id_parent=' . $id_parent;
			}

			// preserve note group, if set
			$group = $app->input->getString('group', '');

			if ($group)
			{
				$url .= '&group=' . $group;
			}

			if ($url)
			{
				$this->setRedirect('index.php?option=com_vikappointments&task=usernote.add' . $url);
			}
			else
			{
				$this->cancel();
			}
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @param 	boolean  $copy  True to save the record as a copy.
	 *
	 * @return 	boolean
	 */
	public function save($copy = false)
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
		$args['title']       = $input->getString('title', '');
		$args['content']     = JComponentHelper::filterText($input->getRaw('usernote_content', ''));
		$args['status']      = $input->getUint('status', 0);
		$args['tags']        = $input->getString('tags', '');
		$args['attachments'] = $input->getString('attachments', array());
		$args['notifycust']  = $input->getBool('notifycust', 0);
		$args['id_user']     = $input->getUint('id_user', 0);
		$args['id_parent']   = $input->getUint('id_parent', 0);
		$args['group']       = $input->getString('group', '');
		$args['id']          = $input->getUint('id', 0);

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check if we should use a blank template
		$blank = $app->input->get('tmpl') === 'component';

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			if ($blank)
			{
				// throw exception in order to avoid unexpected behaviors
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), '403');
			}

			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get user note model
		$usernote = $this->getModel();

		// try to save arguments
		$id = $usernote->save($args);

		if (!$id)
		{
			// get string error
			$error = $usernote->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=manageusernote';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			if ($blank)
			{
				$url .= '&tmpl=component';
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=usernote.edit&cid[]=' . $id . ($blank ? '&tmpl=component' : ''));

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

		$cid = $app->input->get('cid', array(), 'uint');

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.customers', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel()->delete($cid);

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
		$app = JFactory::getApplication();
		
		$url = '';
		
		// preserve user ID, if set
		$id_user = $app->input->getUint('id_user', 0);

		if ($id_user > 0)
		{
			$url .= '&id_user=' . $id_user;
		}

		// preserve parent ID, if set
		$id_parent = $app->input->getUint('id_parent', 0);

		if ($id_parent > 0)
		{
			$url .= '&id_parent=' . $id_parent;
		}

		// preserve note group, if set
		$group = $app->input->getString('group', '');

		if ($group)
		{
			$url .= '&group=' . $group;
		}

		if ($url)
		{
			$this->setRedirect('index.php?option=com_vikappointments&view=usernotes' . $url);
		}
		else
		{
			$this->setRedirect('index.php?option=com_vikappointments&view=customers');
		}
	}

	/**
	 * Redirects the users to the parent page.
	 *
	 * @return 	void
	 */
	public function back()
	{
		$app = JFactory::getApplication();

		$group     = $app->input->getString('group', '');
		$id_parent = $app->input->getUint('id_parent', 0);
		
		if ($group == 'appointments')
		{
			// go to appointment management page
			$this->setRedirect('index.php?option=com_vikappointments&task=reservation.edit&cid[]=' . $id_parent);
		}
		else
		{
			// go to customers page
			$this->setRedirect('index.php?option=com_vikappointments&view=customers');
		}
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
		$args['file'] = 'file';
		$args['path'] = $input->get('path', null, 'base64');

		if (!$args['path'])
		{
			// path not found, create it
			$id_note = $input->get('id_note', 0, 'uint');

			$options = array();
			$options['id_user']   = $input->get('id_user', 0, 'uint');
			$options['id_parent'] = $input->get('id_parent', 0, 'uint');
			$options['group']     = $input->get('group', '', 'string');

			try
			{
				// get uploads path
				$args['path'] = $this->getModel()->getUploadsPath($id_note, $options);
			}
			catch (Exception $e)
			{
				// unable to create uploads path, raise error
				UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
			}
		}

		// get media model
		$media = $this->getModel('media');

		// try to upload file
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
	 * AJAX end-point used to auto-save the user notes.
	 *
	 * @return 	void
	 */
	public function savedraftajax()
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
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$args = array();
		$args['content']   = $input->get('draft', '', 'string');
		$args['status']    = $input->get('status', 0, 'uint');
		$args['tags']      = $input->get('tags', array(), 'string');
		$args['id_user']   = $input->get('id_user', 0, 'uint');
		$args['id_parent'] = $input->get('id_parent', 0, 'uint');
		$args['group']     = $input->get('group', '', 'string');
		$args['id']        = $input->get('id', 0, 'uint');

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments'))
		{
			// raise AJAX error, not authorised to edit records
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		// get user note model
		$model = $this->getModel();

		// try to save arguments
		if (!$model->saveDraft($args))
		{
			// get string error
			$error = $model->getError(null, true);
			
			// raise returned error while saving the record
			UIErrorFactory::raiseError(500, $error);
		}

		// send response to caller
		$this->sendJSON($model->getData());
	}
}

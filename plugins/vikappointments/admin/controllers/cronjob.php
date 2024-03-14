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
 * VikAppointments cron job controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerCronjob extends VAPControllerAdmin
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

		$class = $app->input->getString('class', '');

		if ($class)
		{
			$data['class'] = $class;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.cronjob.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managecronjob');

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
		$app->setUserState('vap.cronjob.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managecronjob&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=cronjob.add');
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
		$this->save(true);
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
		$args['name']      = $input->getString('name');
		$args['class']     = $input->getString('class');
		$args['published'] = $input->getUint('published', 0);
		$args['id']        = $input->getUint('id', 0);

		/**
		 * Check whether the paused notifications should be instantly resumed.
		 * 
		 * @since 1.7.4
		 */
		if ($input->getBool('resume_notif', false))
		{
			$args['resume_notif'] = JFactory::getDbo()->getNullDate();
		}

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get cron job model
		$cron = $this->getModel();

		// try to save arguments
		$id = $cron->save($args);

		if (!$id)
		{
			// get string error
			$error = $cron->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managecronjob';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// look for an error even if the record was saved
		$error = $cron->getError();

		if ($error)
		{
			// raise warning
			$app->enqueueMessage($error, 'warning');
		}

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=cronjob.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$model = $this->getModel();

		// delete selected records
		$model->delete($cid);

		// look for an error even if the record was saved
		$errors = $model->getErrors();

		foreach ($errors as $error)
		{
			// raise warning
			$app->enqueueMessage($error, 'warning');
		}

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Publishes the selected records.
	 *
	 * @return 	boolean
	 */
	public function publish()
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

		$cid  = $app->input->get('cid', array(), 'uint');
		$task = $app->input->get('task', null);

		$state = $task == 'unpublish' ? 0 : 1;

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state);

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
		$this->setRedirect('index.php?option=com_vikappointments&view=cronjobs');
	}

	/**
	 * AJAX end-point used to retrieve the configuration
	 * of the selected driver.
	 *
	 * @return 	void
	 */
	public function driverfields()
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
		
		$driver = $input->getString('driver');
		$id     = $input->getUint('id', 0);
		
		VikAppointments::loadCronLibrary();

		// get cron job configuration
		$config = VAPCronDispatcher::getJobConfiguration($driver);

		if ($config === null)
		{
			// an error occurred, raise error
			UIErrorFactory::raiseError(404, JText::translate('VAPCRONJOBERROR1'));
		}
		
		$params = array();

		if ($id > 0)
		{
			// load cron job record
			$item = $this->getModel()->getItem($id);
			
			if ($item)
			{
				// decode parameters
				$params = (array) json_decode($item->params, true);
			}
		}
		
		// create form builder
		$builder = new VikAppointmentsCronFormBuilder($config);
		// render form HTML
		$html = $builder->build($params);
		
		// send HTML form to caller
		$this->sendJSON(json_encode($html));
	}
}

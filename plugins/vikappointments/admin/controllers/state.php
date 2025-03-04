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
 * VikAppointments state controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerState extends VAPControllerAdmin
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
		$app->setUserState('vap.state.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.countries', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$id_country = $app->input->get('id_country', 0, 'uint');

		$url = 'index.php?option=com_vikappointments&view=managestate&id_country=' . $id_country;

		if ($tmpl = $app->input->get('tmpl'))
		{
			// propagate specified tmpl
			$url .= '&tmpl=' . $tmpl;
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
		$app->setUserState('vap.state.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.countries', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$url = 'index.php?option=com_vikappointments&view=managestate&cid[]=' . $cid[0];

		if ($tmpl = $app->input->get('tmpl'))
		{
			// propagate specified tmpl
			$url .= '&tmpl=' . $tmpl;
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

			$id_country = $app->input->get('id_country', 0, 'uint');

			$this->setRedirect('index.php?option=com_vikappointments&task=state.add&id_country=' . $id_country);
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
		$args['state_name']   = $input->getString('state_name');
		$args['state_2_code'] = $input->getString('state_2_code');
		$args['state_3_code'] = $input->getString('state_3_code');
		$args['published']    = $input->getUint('published', 0);
		$args['id_country']   = $input->getUint('id_country', 0);
		$args['id']           = $input->getUint('id', 0);

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.countries', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$tmpl = $input->get('tmpl');

		// get state model
		$state = $this->getModel();

		// try to save arguments
		$id = $state->save($args);

		if (!$id)
		{
			// get string error
			$error = $state->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managestate';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}
			else
			{
				$url .= '&id_country=' . $args['id_country'];
			}

			if ($tmpl)
			{
				// propagate specified tmpl
				$url .= '&tmpl=' . $tmpl;
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		$url = 'index.php?option=com_vikappointments&task=state.edit&cid[]=' . $id;

		if ($tmpl)
		{
			// propagate specified tmpl
			$url .= '&tmpl=' . $tmpl;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect($url);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.countries', 'com_vikappointments'))
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.countries', 'com_vikappointments'))
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
		$input = JFactory::getApplication()->input;

		$id_country = $input->get('id_country', 0, 'uint');

		$url = 'index.php?option=com_vikappointments&view=states&id_country=' . $id_country;

		if ($tmpl = $input->get('tmpl'))
		{
			// propagate specified tmpl
			$url .= '&tmpl=' . $tmpl;
		}

		$this->setRedirect($url);
	}

	/**
	 * AJAX end-point used to obtain all the cities assigned to the given state.
	 * The task expects the following parameters to be set in request.
	 * 
	 * @param 	integer  id_state  The state ID.
	 *
	 * @return 	void
	 */
	function citiesajax()
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

		$id_state = $input->getUint('id_state', 0);
		$cities   = VAPLocations::getCities($id_state, 'city_name');

		// send cities to caller
		$this->sendJSON($cities);
	}
}

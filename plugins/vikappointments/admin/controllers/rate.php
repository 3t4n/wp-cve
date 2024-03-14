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
 * VikAppointments special rate controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerRate extends VAPControllerAdmin
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
		$app->setUserState('vap.rate.data', array());

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managerate');

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
		$app->setUserState('vap.rate.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managerate&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&task=rate.add');
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
		$args['name']        = $input->getString('name');
		$args['description'] = JComponentHelper::filterText($input->getRaw('description'));
		$args['charge']      = abs($input->getFloat('charge')) * $input->getInt('factor', 1);
		$args['people']      = $input->getUint('people', 0);
		$args['published']   = $input->getUint('published', 0);
		$args['weekdays']    = $input->getUint('weekdays', array());
		$args['usergroups']  = $input->getString('usergroups', array());
		$args['fromdate']    = $input->getString('fromdate');
		$args['todate']      = $input->getString('todate');
		$args['fromtime']    = $input->getUint('fromtime', 0);
		$args['totime']      = $input->getUint('totime', 0);
		$args['params']      = $input->get('params', array(), 'array');
		$args['services']    = $input->getUint('services', array());
		$args['id']          = $input->getUint('id', 0);

		if ($copy)
		{
			// unset ID to create a copy
			$args['id'] = 0;
		}

		// unset people if it should be ignored
		if (!$input->getUint('enablepeople', 0))
		{
			$args['people'] = 0;
		}

		// unset range if the time shouldn't be used
		if (!$input->getUint('usetime', 0) || $args['fromtime'] >= $args['totime'])
		{
			$args['fromtime'] = $args['totime'] = 0;
		}

		/**
		 * Convert timestamp from local timezone to UTC.
		 *
		 * @since 1.7
		 */
		$args['fromdate'] = VAPDateHelper::getSqlDateLocale($args['fromdate']);
		$args['todate']   = VAPDateHelper::getSqlDateLocale($args['todate']);

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get rate model
		$rate = $this->getModel();

		// try to save arguments
		$id = $rate->save($args);

		if (!$id)
		{
			// get string error
			$error = $rate->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managerate';

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

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=rate.edit&cid[]=' . $id);

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
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
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
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.services', 'com_vikappointments'))
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
		$this->setRedirect('index.php?option=com_vikappointments&view=rates');
	}

	/**
	 * AJAX end-point used to test how the special rates are applied.
	 * The task expects the following arguments to be set in request.
	 *
	 * @param 	integer  id_service   The service ID.
	 * @param 	integer  id_employee  The employee ID (optional).
	 * @param 	string   checkin      The checkin date and time.
	 * @param 	integer  people       The number of people (optional).
	 *
	 * @return 	void
	 */
	function testajax()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

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

		$id_service  = $input->getUint('id_service', 0);
		$id_employee = $input->getUint('id_employee', 0);
		$usergroup   = $input->getString('usergroup', 0);
		$checkin     = $input->getString('checkin');
		$people      = $input->getUint('people', 1);
		$is_debug    = $input->getBool('debug', false);

		// store the last search in the user state
		$app->setUserState('ratestest.id_service', $id_service);
		$app->setUserState('ratestest.id_employee', $id_employee);
		$app->setUserState('ratestest.usergroup', $usergroup);
		$app->setUserState('ratestest.checkin', $checkin);
		$app->setUserState('ratestest.people', $people);
		$app->setUserState('ratestest.debug', $is_debug);

		// create checkin timestamp
		$checkin = VAPDateHelper::getDate($checkin)->format('Y-m-d H:i:s');

		// var used to trace the rates calculation
		$trace = array();

		if ($usergroup)
		{
			// inject property to force usergroup
			$trace['usergroup'] = $usergroup;
		}

		if ($is_debug)
		{
			// inject property to force debugging
			$trace['debug'] = array();
		}

		// calculate rate
		$rate = VAPSpecialRates::getRate($id_service, $id_employee, $checkin, $people, $trace);

		// send result to caller
		$this->sendJSON(array($rate, $trace));
	}
}

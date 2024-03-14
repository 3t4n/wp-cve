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
 * VikAppointments employee location-working days controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerEmplocwdays extends VAPControllerAdmin
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
		$app->setUserState('vap.emplocwdays.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.locations', 'com_vikappointments'))
		{
			// not authorised to access this resource
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		$this->cancel();
		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	void
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
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.locations', 'com_vikappointments'))
		{
			// not authorised to access this resource
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		// get working times model
		$model = $this->getModel('worktime');

		// load locations from request
		$locations = $input->get('location', array(), 'array');

		// get employee ID
		$id_employee = $input->getUint('id_employee', 0);

		foreach ($locations as $id_worktime => $id_location)
		{
			$src = array(
				'id'          => $id_worktime,
				'id_location' => $id_location,
				// the employee ID is needed to trigger the update for
				// all the children working days
				'id_employee' => $id_employee,
			);

			// update working time location
			$model->save($src);
		}

		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));
		$this->cancel();
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$id_employee = JFactory::getApplication()->input->getUint('id_employee', 0);
		
		$url = 'index.php?option=com_vikappointments&view=emplocwdays&tmpl=component&id_employee=' . $id_employee;

		$this->setRedirect($url);
	}
}

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

VAPLoader::import('libraries.employee.area.controller');

/**
 * Employee area edit profile controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditprofile extends VAPEmployeeAreaController
{
	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.profile.data', array());

		// check user permissions
		if (!$auth->isEmployee() || !$auth->manage())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditprofile');

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 *
	 * @since   1.7
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
		$auth  = VAPEmployeeAuth::getInstance();

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
		if (!$auth->isEmployee() || !$auth->manage())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();
		$args['firstname']     = $input->getString('firstname');
		$args['lastname']      = $input->getString('lastname');
		$args['nickname']      = $input->getString('nickname');
		$args['email']         = $input->getString('email');
		$args['notify']        = $input->getUint('notify', 0);
		$args['showphone']     = $input->getUint('showphone', 0);
		$args['quick_contact'] = $input->getUint('quick_contact', 0);;
		$args['phone']         = $input->getString('phone');
		$args['note']          = JComponentHelper::filterText($input->getRaw('note'));
		$args['id_group']      = $input->getUint('id_group', 0);
		$args['image']         = $auth->image;

		if ($auth->nickname != $args['nickname'])
		{
			// rebuild alias every time the nickname changes
			$args['alias'] = $args['nickname'];
		}

		// get employee model
		$employee = $this->getModel();

		// try to save arguments
		$id = $employee->save($args);

		if (!$id)
		{
			// get string error
			$error = $employee->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			// redirect to edit page
			$this->setRedirect('index.php?option=com_vikappointments&view=empeditprofile');
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditprofile.edit');

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');
	}
}

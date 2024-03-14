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
 * VikAppointments employees configuration controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerConfigemp extends VAPControllerAdmin
{
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
		if (!$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to access the configuration
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();
		
		////////////////////////////////////////////////////
		/////////////////// REGISTRATION ///////////////////
		////////////////////////////////////////////////////

		$args['empsignup']     = $input->getUint('empsignup', 0);
		$args['empsignstatus'] = $input->getUint('empsignstatus', 1);
		$args['empsignrule']   = $input->getString('empsignrule', 3); // use string for WP compatibility
		$args['empassignser']  = $input->get('empassignser', array(), 'uint');

		////////////////////////////////////////////////////
		//////////////////// AUTHORISE /////////////////////
		////////////////////////////////////////////////////

		// SERVICES

		$args['empcreate']     = $input->getUint('empcreate', 0);
		$args['empmaxser']     = $input->getUint('empmaxser', 5);
		$args['empattachser']  = $input->getUint('empattachser', 0);
		$args['empmanageser']  = $input->getUint('empmanageser', 0);
		$args['empmanagerate'] = $input->getUint('empmanagerate', 0);
		$args['empremove']     = $input->getUint('empremove', 0);

		// EMPLOYEE

		$args['empmanage']    = $input->getUint('empmanage', 0);
		$args['empmanagewd']  = $input->getUint('empmanagewd', 0);
		$args['empmanageloc'] = $input->getUint('empmanageloc', 0);

		// GLOBAL

		$args['empmanagecoupon']    = $input->getUint('empmanagecoupon', 0);
		$args['empmanagepay']       = $input->getUint('empmanagepay', 0);
		$args['empmanagecustfield'] = $input->getUint('empmanagecustfield', 0);

		////////////////////////////////////////////////////
		/////////////////// RESERVATIONS ///////////////////
		////////////////////////////////////////////////////

		$args['emprescreate']  = $input->getUint('emprescreate', 0);
		$args['empresmanage']  = $input->getUint('empresmanage', 0);
		$args['empresconfirm'] = $input->getUint('empresconfirm', 0);
		$args['empresremove']  = $input->getUint('empresremove', 0);
		$args['empresnotify']  = $input->getUint('empresnotify', 0);

		////////////////////////////////////////////////////

		// get configuration model
		$config = $this->getModel();

		// Save all configuration.
		// Do not care of any errors.
		$changed = $config->saveAll($args);

		if ($changed)
		{
			// display generic successful message
			$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));
		}

		// redirect to configuration page
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
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfigemp');
	}
}

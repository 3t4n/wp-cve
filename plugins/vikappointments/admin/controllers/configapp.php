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
 * VikAppointments applications configuration controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerConfigapp extends VAPControllerAdmin
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
		/////////////////////// API ////////////////////////
		////////////////////////////////////////////////////

		$args['apifw']       = $input->getUint('apifw', 0);
		$args['apilogmode']  = $input->getUint('apilogmode', 1);
		$args['apilogflush'] = $input->getUint('apilogflush', 7);
		$args['apimaxfail']  = $input->getUint('apimaxfail', 20);
		
		////////////////////////////////////////////////////
		///////////////////// WEBHOOKS /////////////////////
		////////////////////////////////////////////////////

		$args['webhooksmaxfail']  = $input->getUint('webhooksmaxfail', 0);
		$args['webhooksuselog']   = $input->getUint('webhooksuselog', 0);
		$args['webhookslogspath'] = $input->getString('webhookslogspath', '');
		$args['webhooksgroup']    = $input->getString('webhooksgroup', 'day');

		////////////////////////////////////////////////////
		////////////////////// BACKUP //////////////////////
		////////////////////////////////////////////////////

		$args['backuptype']   = $input->getString('backuptype', 'full');
		$args['backupfolder'] = $input->getString('backupfolder', '');

		////////////////////////////////////////////////////

		// get configuration model
		$config = $this->getModel();

		// Save all configuration.
		// Do not care of any errors.
		$changed = $config->saveAll($args);

		// get customizer model
		$customizerModel = $this->getModel('customizer');

		// fetch customizer properties
		$customizer = $input->get('customizer', [], 'array');

		// save customizer
		$changed = $customizerModel->save($customizer) || $changed;

		// fetch CSS code
		$custom_css_code = $input->get('custom_css_code', '', 'raw');

		// save custom CSS code
		$changed = $customizerModel->setCustomCSS($custom_css_code) || $changed;

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
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfigapp');
	}
}

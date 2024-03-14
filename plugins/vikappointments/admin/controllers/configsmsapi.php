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
 * VikAppointments sms api configuration controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerConfigsmsapi extends VAPControllerAdmin
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
		///////////////////// SETTINGS /////////////////////
		////////////////////////////////////////////////////

		// BASIC

		$args['smsapi']           = $input->getString('smsapi');
		$args['smsenabled']       = $input->getUint('smsenabled', 0);
		$args['smsapiadminphone'] = $input->getString('smsapiadminphone');
		$args['smsapito']         = array(
			$input->getUint('smsapitocust', 0),
			$input->getUint('smsapitoemp', 0),
			$input->getUint('smsapitoadmin', 0),
		);

		// PARAMS

		try
		{
			$args['smsapifields'] = array();

			// get SMS driver configuration
			$smsconfig = VAPApplication::getInstance()->getSmsConfig($args['smsapi']);

			foreach ($smsconfig as $k => $p)
			{
				$args['smsapifields'][$k] = $input->get('smsparam_' . $k, '', 'string');
			}
		}
		catch (Exception $e)
		{
			// SMS driver not supported
		}

		////////////////////////////////////////////////////
		//////////////////// TEMPLATES /////////////////////
		////////////////////////////////////////////////////

		// CUSTOMER

		$args['smstmplcust']      = array();
		$args['smstmplcustmulti'] = array();

		$sms_cust_tmpl = $input->get('smstmplcust', array(), 'array');
		
		$languages = VikAppointments::getKnownLanguages();
		
		for ($i = 0; $i < count($languages); $i++)
		{
			$args['smstmplcust'][$languages[$i]]      = $sms_cust_tmpl[0][$i];
			$args['smstmplcustmulti'][$languages[$i]] = $sms_cust_tmpl[1][$i];
		}

		// ADMINISTRATOR

		$sms_admin_tmpl = $input->get('smstmpladmin', array(), 'array');
		
		$args['smstmpladmin']      = $sms_admin_tmpl[0];
		$args['smstmpladminmulti'] = $sms_admin_tmpl[1];

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
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfigsmsapi');
	}

	/**
	 * AJAX end-point to load the configuration fields
	 * of the requested SMS API driver.
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
		
		try
		{
			// access driver config through platform handler
			$form = VAPApplication::getInstance()->getSmsConfig($driver);
		}
		catch (Exception $e)
		{
			// raise AJAX error, driver not found
			UIErrorFactory::raiseError(404, JText::translate('VAPSMSESTIMATEERR1'));
		}
		
		$params = array();

		// retrieve SMS driver configuration
		$params = VikAppointments::getSmsApiFields();
		
		// build display data
		$data = array(
			'fields' => $form,
			'params' => $params,
			'prefix' => 'smsparam_',
		);

		// render payment form
		$html = JLayoutHelper::render('form.fields', $data);
		
		// send JSON to caller
		$this->sendJSON(json_encode($html));
	}

	/**
	 * AJAX end-point to estimate the remaining balance of
	 * the current SMS driver.
	 *
	 * @return 	void
	 */
	public function apicredit()
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
		
		try
		{
			// access driver instance through platform handler
			$api = VAPApplication::getInstance()->getSmsInstance($driver);
		}
		catch (Exception $e)
		{
			// raise AJAX error, driver not found
			UIErrorFactory::raiseError(404, JText::translate('VAPSMSESTIMATEERR1'));
		}
		
		$phone = $input->get('phone', '', 'string');

		if (empty($phone))
		{
			// use admin phone number
			$phone = VAPFactory::getConfig()->get('smsapiadminphone');
		}
		
		// make sure the driver support an estimation feature
		if (!method_exists($api, 'estimate'))
		{
			// raise AJAX error, estimate not supported
			UIErrorFactory::raiseError(405, JText::translate('VAPSMSESTIMATEERR2'));
		}
		
		// try to estimate
		$result = $api->estimate($phone, 'Sample');
		
		if ($result->errorCode != 0)
		{
			// raise AJAX error, unable to estimate
			UIErrorFactory::raiseError(500, JText::translate('VAPSMSESTIMATEERR3'));
		}
		
		// return the plain user credit
		$this->sendJSON($result->userCredit);
	}
}

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
 * VikAppointments user profile view controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerUserprofile extends VAPControllerAdmin
{
	/**
	 * Task used to create a new user through the registration form
	 * used by VikAppointments.
	 *
	 * @return 	boolean
	 */
	public function register()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$return = base64_decode($input->getBase64('return'));

		if (empty($return))
		{
			$return = 'index.php';
		}
		
		// create successful return URL
		$okReturn = JRoute::rewrite($return, false);

		// create failure return URL
		$failReturn = JUri::getInstance($return);
		$failReturn->setVar('tab', 'registration');

		// set error redirect URL by default
		$this->setRedirect(JRoute::rewrite($failReturn, false));

		if (!JSession::checkToken())
		{
			// invalid session token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}

		$vik = VAPApplication::getInstance();

		if ($vik->isCaptcha() && !$vik->reCaptcha('check'))
		{
			// invalid captcha
			$app->enqueueMessage(JText::translate('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL'), 'error');
			return false;
		}
		
		$args = array();
		$args['firstname']    = $input->getString('fname');
		$args['lastname']     = $input->getString('lname');
		$args['email']        = $input->getString('email');
		$args['username']     = $input->getString('reg_username');
		$args['password']     = $input->getString('reg_password');
		$args['confpassword'] = $input->getString('confpassword');
		
		if (!VikAppointments::checkUserArguments($args))
		{
			// missing required field (or the user was already logged in)
			$app->enqueueMessage(JText::translate('VAPREGISTRATIONFAILED2'), 'error');
			return false;
		}
		
		// try to register a new user account
		$userid = VikAppointments::createNewUserAccount($args);

		if (!$userid)
		{
			// an error occurred...
			return false;
		}

		// switch redirect URL on success
		$this->setRedirect($okReturn);
		
		if ($userid == 'useractivate' || $userid == 'adminactivate')
		{
			// registration requires a manual activation
			return true;
		}
		
		// successful registration, auto log-in
		$credentials = array(
			'username' => $args['username'],
			'password' => $args['password'],
			'remember' => true,
		);
		
		$app->login($credentials);

		$user = JFactory::getUser();
		$user->setLastVisit();
		$user->set('guest', 0);
		
		return true;		
	}

	/**
	 * Save and close task.
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
	 * Task used to save the billing details of the logged-in user.
	 * If the task is reached by a guest user, it will be redirected 
	 * to the "allorders" page.
	 *
	 * @return 	boolean
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		$itemid = $input->getInt('Itemid', 0);
		
		if ($user->guest)
		{
			// back to all orders view
			$this->cancel();
			return false;
		}
		
		// get customer details from request
		$args = array();
		$args['jid']               = $user->id;
		$args['billing_name']      = $input->getString('billing_name');
		$args['billing_mail']      = $input->getString('billing_mail');
		$args['billing_phone']     = $input->getString('billing_phone');
		$args['country_code']      = $input->getString('country_code');
		$args['billing_state']     = $input->getString('billing_state');
		$args['billing_city']      = $input->getString('billing_city');
		$args['billing_address']   = $input->getString('billing_address');
		$args['billing_address_2'] = $input->getString('billing_address_2');
		$args['billing_zip']       = $input->getString('billing_zip');
		$args['company']           = $input->getString('company');
		$args['vatnum']            = $input->getString('vatnum');
		$args['ssn']               = $input->getString('ssn');

		// get customer model
		$model = $this->getModel('customer');

		// load current user profile
		$data = $model->getItem(array('jid' => $user->id));

		$old_image = false;

		if ($data)
		{
			// set ID for direct update
			$args['id'] = $data->id;
			// check if we have an image to delete after the upload
			$old_image = $data->image;
		}

		// get uploaded image, if any
		$img = $input->files->get('image', null, 'array');
		
		// upload image
		$result = VikAppointments::uploadImage($img, VAPCUSTOMERS_AVATAR . DIRECTORY_SEPARATOR);
		
		if ($result->status)
		{
			// successful upload
			$args['image'] = $result->name;

			// unlink old customer image
			if ($old_image)
			{
				unlink(VAPCUSTOMERS_AVATAR . DIRECTORY_SEPARATOR . $old_image);
			}
		}
		else
		{
			if ($result->errno == 1)
			{
				// invalid file tyoe
				$app->enqueueMessage(JText::translate('VAPCONFIGFILETYPEERROR'), 'error');
			}
			else if ($result->errno == 2)
			{
				// upload error
				$app->enqueueMessage(JText::translate('VAPCONFIGUPLOADERROR'), 'error');
			}
		}
		
		// if the country code doesn't exist, make it empty
		if (VAPLocations::getCountryFromCode($args['country_code']) === false)
		{
			/**
			 * Use an empty value instead of "US".
			 *
			 * @since 1.6.3
			 */
			$args['country_code'] = '';
		}

		// set return URL
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=userprofile' . ($itemid ? '&Itemid=' . $itemid : ''), false));

		// try to save the customer data
		if (!$model->save($args))
		{
			// an error occurred, back to edit page
			$app->enqueueMessage(JText::translate('VAPERRINSUFFCUSTF'), 'error');
			
			return false;
		}

		// update/insert successful
		$app->enqueueMessage(JText::translate('VAPUSERPROFILEDATASTORED'));
	
		return true;
	}

	/**
	 * Task used to go back to the all orders view.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$itemid = JFactory::getApplication()->input->getUint('Itemid', 0);
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=allorders' . ($itemid ? '&Itemid=' . $itemid : ''), false));
	}

	/**
	 * Task used to perform the logout of the current user.
	 * The user will be redirected to the "allorders" page.
	 *
	 * @return 	void
	 */
	public function logout()
	{
		$app = JFactory::getApplication();
		$app->logout(JFactory::getUser()->id);
		$this->cancel();
	}
}

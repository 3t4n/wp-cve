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
class VikAppointmentsControllerEmplogin extends VAPEmployeeAreaController
{
	/**
	 * Task used to create a new employee through the registration form
	 * used by VikAppointments.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
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

		if (!VAPEmployeeAreaManager::canRegister())
		{
			// employee registration not allowed
			$app->enqueueMessage(JText::translate('VAPREGISTRATIONFAILED1'), 'error');
			return false;
		}

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
		$userid = VikAppointments::createNewUserAccount($args, VikAppointments::REGISTER_EMPLOYEE);

		if (!$userid)
		{
			// an error occurred...
			return false;
		}

		// switch redirect URL on success
		$this->setRedirect($okReturn);
		
		if (is_numeric($userid))
		{
			$args['id'] = $userid;

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
		}

		// complete employee registration through model
		if (!$this->getModel()->register($args))
		{
			// get last registered error
			$error = $this->getError($index = null, $string = true);

			if ($error)
			{
				$app->enqueueMessage($error, 'error');
			}
		}
		
		return true;		
	}

	/**
	 * Performs the logout of the employee.
	 *
	 * @return 	void
	 */
	public function logout()
	{
		$app = JFactory::getApplication();

		// do log off
		$app->logout(JFactory::getUser()->id);

		// back to the employee area login page
		$this->cancel();
	}

	/**
	 * AJAX end-point used to load a list of appointments for
	 * the specified date.
	 *
	 * @param 	string   $date  The check-in date.
	 *
	 * @return 	void
	 *
	 * @since   1.7
	 */
	public function appointmentsajax()
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

		$auth = VAPEmployeeAuth::getInstance();

		// make sure the user is an employee
		if (!$auth->isEmployee())
		{
			// not authorised
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		$date = $input->getString('date', '');

		// get appointments model
		$model = $this->getModel('reservation');

		// find appointments list
		$list = $model->getAppointmentsOn($date, $auth->id);

		// get rid of closures
		$list = array_values(array_filter($list, function($elem)
		{
			return !$elem->closure;
		}));

		if (!$list)
		{
			// do not display anything in case of empty list
			$this->sendJSON([]);
		}

		$has_cap = false;

		// check if the employee owns at least a service with maximum capacity higher than 1
		foreach ($list as $elem)
		{
			$has_cap = $has_cap || $elem->people > 1;
		}

		// build reservations list
		$data = array(
			'auth'         => $auth,
			'has_capacity' => $has_cap,
			'orders'       => $list,
			'itemid'       => $input->getUint('Itemid', null),
		);

		// build appointments HTML table
		$html = JLayoutHelper::render('emparea.dayorders', $data);

		// return list to caller
		$this->sendJSON(json_encode($html));
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

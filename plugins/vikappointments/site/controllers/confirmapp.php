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
 * VikAppointments appointment confirmation controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerConfirmapp extends VAPControllerAdmin
{
	/**
	 * Saves the appointments that have been registered within the cart.
	 *
	 * @return 	boolean
	 */
	public function saveorder()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$ajax = $input->getBool('ajax', false);

		$itemid = $input->getUint('Itemid');

		// prepare redirect URL
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=confirmapp' . ($itemid ? '&Itemid=' . $itemid : ''), false));

		/**
		 * Validate session token before to proceed.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			if ($ajax)
			{
				// in case of AJAX, raise HTTP error
				UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
			}

			// invalid token, back to confirm page
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}

		$vik = VAPApplication::getInstance();

		/**
		 * Validate ReCaptcha before processing the reservation request.
		 * The ReCaptcha is never asked to registered customers.
		 *
		 * @since 1.7
		 */
		if (JFactory::getUser()->guest && $vik->isGlobalCaptcha() && !$vik->reCaptcha('check'))
		{
			if ($ajax)
			{
				// in case of AJAX, raise HTTP error
				UIErrorFactory::raiseError(400, JText::translate('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL'));
			}

			// invalid captcha
			$app->enqueueMessage(JText::translate('PLG_RECAPTCHA_ERROR_INCORRECT_CAPTCHA_SOL'), 'error');
			return false;
		}

		// load arguments from request
		$args = array();
		$args['id_payment'] = $input->getUint('id_payment', 0);
		$args['itemid']     = $itemid;

		// get view model
		$model = $this->getModel();

		// try to save the appointments and get landing page
		$url = $model->save($args);

		// make sure we haven't faced any errors		
		if (!$url)
		{
			// get all registered errors
			$errors = $model->getErrors();

			if ($ajax)
			{
				// in case of AJAX, raise HTTP error
				UIErrorFactory::raiseError(500, implode("\n", $errors));
			}

			foreach ($errors as $err)
			{
				// enqueue error message
				$app->enqueueMessage($err, 'error');
			}

			return false;
		}
		
		if ($ajax)
		{
			// in case of AJAX, return the URL of the landing page
			$this->sendJSON(json_encode($url));
		}

		// update redirect URL to reach the landing page
		$this->setRedirect($url);
		return true;
	}

	/**
	 * End-point used to redeem the coupon code.
	 *
	 * @return 	boolean
	 */
	public function redeemcoupon()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$itemid = $input->getUint('Itemid');

		// prepare redirect URL
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=confirmapp' . ($itemid ? '&Itemid=' . $itemid : ''), false));

		/**
		 * Validate form token to prevent brute force attacks.
		 *
		 * @since 1.6
		 */
		if (!JSession::checkToken())
		{
			// direct access attempt
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}
		
		// get coupon key from POST
		$coupon = $input->post->getString('couponkey', '');

		// get cart model
		$model = $this->getModel('cart');

		// try to redeem the coupon code
		$res = $model->redeemCoupon($coupon);

		if (!$res)
		{
			// get last error registered by the model
			$error = $model->getError($index = null, $string = true);
			// propagate error or use the default one
			$app->enqueueMessage($error ? $error : JText::translate('VAPCOUPONNOTVALID'), 'error');
			return false;
		}

		// coupon applied successfully
		$app->enqueueMessage(JText::translate('VAPCOUPONFOUND'));
		return true;
	}

	/**
	 * AJAX task used to validate the specified zip code against
	 * the appointments contained within the user cart.
	 *
	 * @return 	void
	 */
	public function checkzip()
	{
		// get ZIP code from request
		$zip = JFactory::getApplication()->input->getString('zip');

		// validate the specified ZIP code
		$result = $this->getModel()->validateZipCode($zip);

		if (!$result)
		{
			// raise error in case the ZIP is not accepted
			UIErrorFactory::raiseError(500, JText::translate('VAPCONFAPPZIPERROR'));
		}
		
		// ZIP code valid
		$this->sendJSON(1);
	}
}

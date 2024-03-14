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
 * VikAppointments packages confirmation controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerPackagesconfirm extends VAPControllerAdmin
{
	/**
	 * Saves the packages that have been registered within the cart.
	 *
	 * @return 	boolean
	 */
	public function saveorder()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$itemid = $input->getUint('Itemid');

		// prepare redirect URL
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=packagesconfirm' . ($itemid ? '&Itemid=' . $itemid : ''), false));

		/**
		 * Validate session token before to proceed.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// invalid token, back to confirm page
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}

		// load arguments from request
		$args = array();
		$args['id_payment'] = $input->getUint('id_payment', 0);
		$args['itemid']     = $itemid;

		// get view model
		$model = $this->getModel();

		// try to save the packages and get landing page
		$url = $model->save($args);

		// make sure we haven't faced any errors		
		if (!$url)
		{
			// get all registered errors
			$errors = $model->getErrors();

			foreach ($errors as $err)
			{
				// enqueue error message
				$app->enqueueMessage($err, 'error');
			}

			return false;
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
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=packagesconfirm' . ($itemid ? '&Itemid=' . $itemid : ''), false));

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
		$model = $this->getModel('packagescart');

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
}

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
 * Employee area subscriptions controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerEmpsubscr extends VAPEmployeeAreaController
{
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
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=empsubscr' . ($itemid ? '&Itemid=' . $itemid : ''), false));

		// validate form token to prevent brute force attacks
		if (!JSession::checkToken())
		{
			// direct access attempt
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			return false;
		}
		
		// get coupon key from POST
		$coupon = $input->post->getString('couponkey', '');

		// get cart model
		$model = $this->getModel('empsubscrcart');

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
	 * AJAX end-point used to obtain the updated totals based on the
	 * selected subscription plan and payment method.
	 *
	 * @return 	void
	 */
	public function refreshtotalsajax()
	{
		// get subscriptions model
		$model = $this->getModel('empsubscrcart');

		// refresh totals based on updated subscription plan and payment
		$totals = $model->getTotals();

		// send totals to caller
		$this->sendJSON($totals);
	}
}

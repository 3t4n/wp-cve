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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments employee area subscription order view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpsubscrorder extends JModelVAP
{
	/**
	 * Completes the booking process by saving the purchased subscription.
	 *
	 * @param 	array  $data  An array containing some booking options.
	 *
	 * @return 	mixed  The landing page URL on success, false otherwise.
	 */
	public function save($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		// get cart model
		$cart = JModelVAP::getInstance('empsubscrcart');

		////////////////////////////////////////////////////////////
		////////////////////// INITIALIZATION //////////////////////
		////////////////////////////////////////////////////////////

		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// the user is not an employee
			$this->setError(JText::translate('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		/**
		 * Trigger event to manipulate the cart instance.
		 *
		 * @param 	mixed 	$cart  The cart instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onInitSaveSubscriptionOrder', array($cart));

		// prepare order array
		$order = array();

		////////////////////////////////////////////////////////////
		//////////////////// FETCH SUBSCRIPTION ////////////////////
		////////////////////////////////////////////////////////////

		try
		{
			// get selected subscription
			$subscr = $cart->getSubscription();
		}
		catch (Exception $e)
		{
			// an error occurred, register message and abort
			$this->setError($e);
			return false;
		}

		$order['id_subscr'] = $subscr['id'];

		////////////////////////////////////////////////////////////
		//////////////////// FETCH CUSTOM FIELDS ///////////////////
		////////////////////////////////////////////////////////////

		// register current language tag
		$order['langtag'] = JFactory::getLanguage()->getTag();

		// assign subscription to the current employee
		$order['id_employee'] = $auth->id;

		if (!empty($data['billing']))
		{
			$order['billing'] = array_map(function($elem)
			{
				// make input safe
				return JComponentHelper::filterText($elem);
			}, (array) $data['billing']);

			$tmp = array();

			/**
			 * Trigger event to manipulate the custom fields array and the
			 * billing information of the employee.
			 *
			 * @param 	array 	&$fields  The custom fields values.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onPrepareFieldsSaveEmployeeSubscriptionOrder', array(&$order['billing']));

			// get employee model
			$empModel = JModelVAP::getInstance('employee');

			// update employee billing details
			$empModel->save([
				'id'           => $order['id_employee'],
				'billing_json' => json_encode($order['billing']),
			]);
		}

		////////////////////////////////////////////////////////////
		///////////////////// FETCH TOTAL COSTS ////////////////////
		////////////////////////////////////////////////////////////

		$totals = $cart->getTotals();

		// set up order totals
		$order['total_cost'] = $totals->gross;
		$order['total_net']  = $totals->net;
		$order['total_tax']  = $totals->tax;
		$order['discount']   = $totals->discount;

		////////////////////////////////////////////////////////////
		///////////////////// VALIDATE PAYMENT /////////////////////
		////////////////////////////////////////////////////////////

		$payment = null;

		$order['id_payment'] = 0;
		
		if ($order['total_cost'] > 0)
		{
			// get selected payment
			$payment = $cart->getPayment();

			if ($payment)
			{
				if (!empty($totals->payment))
				{
					// include payment charge
					$order['payment_charge'] = $totals->payment->net;
					$order['payment_tax']    = $totals->payment->tax;
				}

				$order['id_payment'] = $payment['id'];

				// auto-confirm orders according to the configuration of
				// the payment, otherwise force PENDING status to let the
				// employees be able to start a transaction
				if ($payment['setconfirmed'])
				{
					// auto-confirm order
					$order['status'] = JHtml::fetch('vaphtml.status.confirmed', 'subscriptions', 'code');
				}
			}
		}

		////////////////////////////////////////////////////////////
		/////////////////////// ORDER STATUS ///////////////////////
		////////////////////////////////////////////////////////////

		if (empty($order['status']))
		{
			// auto-confirm in case of no cost
			$status = $order['total_cost'] > 0 ? 'pending' : 'confirmed';

			// status not yet specified, use the default one
			$order['status'] = JHtml::fetch('vaphtml.status.' . $status, 'subscriptions', 'code');
		}

		$order['status_comment'] = null;

		/**
		 * Trigger event to manipulate the order status at runtime.
		 *
		 * @param 	string  &$status   The currently fetched order status.
		 * @param 	string  &$comment  An optional status comment to be used.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onFetchStatusSaveSubscriptionOrder', array(&$order['status'], &$order['status_comment']));

		// check whether the status has been immediately confirmed and we have an empty comment
		if (empty($order['status_comment']) && JHtml::fetch('vaphtml.status.isconfirmed', 'subscriptions', $order['status']))
		{
			if ($order['total_cost'] == 0)
			{
				// no cost, automatically confirmed
				$order['status_comment'] = 'VAP_STATUS_CONFIRMED_AS_NO_COST';
			}
			else if (!$payment)
			{
				// no configured payments
				$order['status_comment'] = 'VAP_STATUS_CONFIRMED_AS_NO_PAYMENT';
			}
			else
			{
				// auto-approved through the configuration of the payment
				$order['status_comment'] = 'VAP_STATUS_CONFIRMED_RESULT_OF_PAYMENT';
			}
		}

		////////////////////////////////////////////////////////////
		///////////////////// FETCH COUPON CODE ////////////////////
		////////////////////////////////////////////////////////////

		// check whether the coupon code was set
		$coupon = $cart->getDiscount('coupon');

		if ($coupon)
		{
			// assign coupon code to the order
			$order['coupon'] = (array) $coupon->get('couponData');
			// redeem coupon code
			VikAppointments::couponUsed($order['coupon']);
		}

		////////////////////////////////////////////////////////////
		//////////////////// SAVE ORDER DETAILS ////////////////////
		////////////////////////////////////////////////////////////

		$ordnum = $ordkey = null;

		// get subscription order model
		$orderModel = JModelVAP::getInstance('subscrorder');

		// save the order
		if (!$orderModel->save($order))
		{
			// an error occurred while trying to save the order
			$error = $orderModel->getError();
			// propagate the error found or use a generic one
			$this->setError($error ? $error : JText::translate('VAPSUBSCRINSERTERR'));
			return false;
		}

		// get order saved data
		$orderData = $orderModel->getData();

		// use order number/key pair of saved order
		$ordnum = $orderData['id'];

		// empty cart on success
		$cart->emptyCart();

		////////////////////////////////////////////////////////////
		////////////////////// NOTIFICATIONS ///////////////////////
		////////////////////////////////////////////////////////////

		// $mailOptions = array();
		// validate e-mail rules before sending
		// $mailOptions['check'] = true;

		// send e-mail notification to the employee
		// $orderModel->sendEmailNotification($ordnum, $mailOptions);

		// send e-mail notification to the administrator(s)
		// $mailOptions['client'] = 'subscradmin';
		// $orderModel->sendEmailNotification($ordnum, $mailOptions);

		$redirect_url = 'index.php?option=com_vikappointments&view=empsubscrorder&id=' . $ordnum;

		if (!empty($data['itemid']))
		{
			$redirect_url .= '&Itemid=' . $data['itemid'];
		}

		/**
		 * Trigger event to manipulate the redirect URL after completing
		 * the subscription purchase process.
		 *
		 * Use VAPOrderFactory::getEmployeeSubscription($ordnum) to access the order details.
		 *
		 * @param 	string 	 &$url   The redirect URL (plain).
		 * @param 	integer  $order  The order id.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onRedirectSubscriptionOrder', array(&$redirect_url, $ordnum));
		
		// rewrite landing page
		return JRoute::rewrite($redirect_url, false);
	}
}

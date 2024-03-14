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
 * VikAppointments user subscription payment view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelSubscrpayment extends JModelVAP
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
		$cart = JModelVAP::getInstance('subscrcart');

		////////////////////////////////////////////////////////////
		////////////////////// INITIALIZATION //////////////////////
		////////////////////////////////////////////////////////////

		$user = JFactory::getUser();

		if ($user->guest)
		{
			// the user must be logged in
			$this->setError(JText::translate('VAPPACKLOGINREQERR'));
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

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->setLanguageFilter($order['langtag'])
			->noSeparator()
			->fetch();

		try
		{
			// load custom fields from request
			$order['custom_f'] = VAPCustomFieldsRequestor::loadForm($_cf, $tmp, $strict = true);
		}
		catch (Exception $e)
		{
			// catch exception and register it as error message
			$this->setError($e->getMessage());
			return false;
		}

		// merge custom fields and uploaded files
		$order['custom_f'] = array_merge($order['custom_f'], $tmp['uploads']);

		/**
		 * Trigger event to manipulate the custom fields array and the
		 * billing information of the customer, extrapolated from the rules
		 * of the custom fields.
		 *
		 * @param 	array 	&$fields  The custom fields values.
		 * @param 	array 	&$args    The billing array.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onPrepareFieldsSaveSubscriptionOrder', array(&$order['custom_f'], &$tmp));

		// register data fetched by the custom fields so that the subscription order
		// model is able to use them for saving purposes
		$order['fields_data'] = $tmp;

		if (empty($order['fields_data']['purchaser_nominative']))
		{
			// use name of the currently logged-in user
			$order['fields_data']['purchaser_nominative'] = $user->name;
		}

		if (empty($order['fields_data']['purchaser_mail']))
		{
			// use e-mail of the currently logged-in user
			$order['fields_data']['purchaser_mail'] = $user->email;
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
				// customers be able to start a transaction
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
		///////////////////// USER REGISTRATION ////////////////////
		////////////////////////////////////////////////////////////

		// create customer data
		$customer = array(
			'id'     => 0,
			'jid'    => $user->id,
			'fields' => $order['custom_f'],
		);

		// inject fetched billing details
		$customer = array_merge($customer, $order['fields_data']);

		// get customer model
		$customerModel = JModelVAP::getInstance('customer');

		// insert/update customer
		if ($id_user = $customerModel->save($customer))
		{
			// assign order to saved customer
			$order['id_user'] = $id_user;
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
		$ordkey = $orderData['sid'];

		// empty cart on success
		$cart->emptyCart();

		////////////////////////////////////////////////////////////
		////////////////////// NOTIFICATIONS ///////////////////////
		////////////////////////////////////////////////////////////

		// $mailOptions = array();
		// validate e-mail rules before sending
		// $mailOptions['check'] = true;

		// send e-mail notification to the customer
		// $orderModel->sendEmailNotification($ordnum, $mailOptions);

		// send e-mail notification to the administrator(s)
		// $mailOptions['client'] = 'subscradmin';
		// $orderModel->sendEmailNotification($ordnum, $mailOptions);

		$redirect_url = "index.php?option=com_vikappointments&view=subscrpayment&ordnum={$ordnum}&ordkey={$ordkey}";

		if (!empty($data['itemid']))
		{
			$redirect_url .= "&Itemid={$data['itemid']}";
		}

		/**
		 * Trigger event to manipulate the redirect URL after completing
		 * the subscription purchase process.
		 *
		 * Use VAPOrderFactory::getCustomerSubscription($ordnum) to access the order details.
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

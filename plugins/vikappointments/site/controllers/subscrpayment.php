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
 * VikAppointments user subscription payment controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerSubscrpayment extends VAPControllerAdmin
{
	/**
	 * Saves the subscription that has been registered within the cart.
	 *
	 * @return 	boolean
	 */
	public function saveorder()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		$itemid = $input->getUint('Itemid');

		// prepare redirect URL
		$this->setRedirect(JRoute::rewrite('index.php?option=com_vikappointments&view=subscriptions' . ($itemid ? '&Itemid=' . $itemid : ''), false));

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
		$args['itemid'] = $itemid;

		// get view model
		$model = $this->getModel();

		// try to save the subscription and get landing page
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
	 * This is the end-point used by the gateway to validate a payment transaction.
	 * It is mandatory to send the following parameters (via GET or POST) in order to
	 * retrieve the correct details of the order transaction.
	 *
	 * @param 	integer  ordnum  The order number (ID).
	 * @param 	string 	 ordkey  The order key (SID).
	 *
	 * @return 	void
	 */
	public function notifypayment()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$app   = JFactory::getApplication();
		$input = $app->input;
			
		$oid = $input->getUint('ordnum');
		$sid = $input->getAlnum('ordkey');

		// Get order details (filter by ID and SID).
		// In case the order doesn't exist, an exception will be thrown.
		VAPLoader::import('libraries.order.factory');
		$order = VAPOrderFactory::getCustomerSubscription($oid, null, array('sid' => $sid));

		/**
		 * This event is triggered every time a payment tries to validate a transaction made.
		 *
		 * DOES NOT trigger in case the order doesn't exist.
		 *
		 * @param 	mixed 	$order  The details of the purchased subscription.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		$dispatcher->trigger('onReceivePaymentNotification', array($order));

		// build return and error URL
		$return_url = "index.php?option=com_vikappointments&view=subscrpayment&ordnum={$oid}&ordkey={$sid}";
		$error_url  = "index.php?option=com_vikappointments&view=subscrpayment&ordnum={$oid}&ordkey={$sid}";

		/**
		 * If we are trying to validate an order already paid/confirmed, auto-redirect to
		 * the return URL instead of throwing an exception.
		 * 
		 * @since 1.7.1
		 */
		if ($order->statusRole == 'APPROVED')
		{
			$this->setRedirect(JRoute::rewrite($return_url, false));
			return;
		}
		
		// make sure the order can be paid
		if ($order->statusRole != 'PENDING')
		{
			// status not allowed
			throw new Exception('The current status of the order does not allow any payments.', 403);
		}

		if (!$order->payment)
		{
			// payment method not found
			throw new Exception('The selected payment does not exist', 404);
		}

		// reload payment details to access the parameters
		$payment = JModelVAP::getInstance('payment')->getItem($order->payment->id);

		$vik = VAPApplication::getInstance();

		$config = VAPFactory::getConfig();
			
		// fetch transaction data	
		$paymentData = array();

		// the payment URLs are correctly routed for external usage
		$return_url = $vik->routeForExternalUse($return_url, false);
		$error_url  = $vik->routeForExternalUse($error_url, false);

		// include the Notification URL in both the PLAIN and ROUTED formats
		$notify_url = "index.php?option=com_vikappointments&task=subscrpayment.notifypayment&ordnum={$oid}&ordkey={$sid}";

		// subtract amount already paid
		$total_to_pay = max(array(0, $order->totals->gross - $order->totals->paid));
	
		$paymentData['type']                 = 'subscriptions';
		$paymentData['action']               = 'validate';
		$paymentData['oid']                  = $order->id;
		$paymentData['sid']                  = $order->sid;
		$paymentData['attempt']              = $order->payment_attempt;
		$paymentData['transaction_name']     = JText::sprintf('VAPTRANSACTIONNAMESUBSCR', $order->subscription->name, $config->get('agencyname'));
		$paymentData['transaction_currency'] = $config->get('currencyname');
		$paymentData['currency_symb']        = $config->get('currencysymb');
		$paymentData['tax']                  = 0;
		$paymentData['return_url']           = $return_url;
		$paymentData['error_url']            = $error_url;
		$paymentData['notify_url']           = $vik->routeForExternalUse($notify_url, false);
		$paymentData['notify_url_plain']     = JUri::root() . $notify_url;
		$paymentData['total_to_pay']         = $total_to_pay;
		$paymentData['total_net_price']      = $total_to_pay;
		$paymentData['total_tax']            = 0;
		$paymentData['payment_info']         = $payment;
		$paymentData['billing']              = $order->billing;
		$paymentData['details'] = array(
			'purchaser_nominative' => $order->billing->billing_name,
			'purchaser_mail'       => $order->billing->billing_mail,
			'purchaser_phone'      => $order->billing->billing_phone,
		);

		/**
		 * Trigger event to manipulate the payment details.
		 *
		 * @param 	array 	&$order   The transaction details.
		 * @param 	array 	&$params  The payment configuration array.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		$dispatcher->trigger('onInitPaymentTransaction', array(&$paymentData, &$payment->params));

		/**
		 * Instantiate the payment using the platform handler.
		 *
		 * @since 1.6.3
		 */
		$obj = $vik->getPaymentInstance($payment->file, $paymentData, $payment->params);
		
		try
		{
			// validate payment transaction
			$result = $obj->validatePayment();
		}
		catch (Exception $e)
		{
			// catch any exceptions that might have been thrown by the gateway
			$result = [];
			$result['verified'] = 0;
			$result['log']      = $e->getMessage();
		}

		// get order model
		$model = JModelVAP::getInstance('subscrorder');
		
		// successful response
		if ($result['verified'])
		{
			if (!empty($result['tot_paid']))
			{
				// increase total amount paid
				$order->totals->paid += (float) $result['tot_paid'];
			}

			if ($order->totals->paid >= $order->totals->gross)
			{
				// the whole amount has been paid, use the apposite PAID status
				$order->status = JHtml::fetch('vaphtml.status.paid', 'subscriptions', 'code');
			}
			else
			{
				// a deposit have been left, use CONFIRMED status
				$order->status = JHtml::fetch('vaphtml.status.confirmed', 'subscriptions', 'code');
			}

			// prepare data to dave
			$data = array(
				'id'             => $order->id,
				'status'         => $order->status,
				'status_comment' => 'VAP_STATUS_CHANGED_FROM_PAY',
				'tot_paid'       => $order->totals->paid,
				'paid'           => $order->paid,
			);

			$model->save($data);
			
			// $mailOptions = array();
			// validate e-mail rules before sending
			// $mailOptions['check'] = true;

			// send e-mail notification to the customer
			// $model->sendEmailNotification($order->id, $mailOptions);

			// send e-mail notification to the administrator(s)
			// $mailOptions['client'] = 'subscradmin';
			// $model->sendEmailNotification($order->id, $mailOptions);

			// try to auto-generate the invoice
			VikAppointments::generateInvoice($order->id, 'subscriptions');

			/**
			 * Trigger event after the validation of a successful transaction.
			 *
			 * @param 	array  $order  The transaction details.
			 * @param 	array  $args   The response array.
			 *
			 * @return 	void
			 *
			 * @since 	1.6
			 */
			$dispatcher->trigger('onSuccessPaymentTransaction', array($paymentData, $result));
		}
		// failure response
		else
		{
			// check if the payment registered any logs
			if (!empty($result['log']))
			{
				$text = array(
					'Order #' . $order->id . '-' . $order->sid . ' (Subscription)',
					nl2br($result['log']),
				);

				// send error logs to administrator(s)
				VikAppointments::sendAdminMailPaymentFailed($order->id, $text);

				// get current date and time
				$timeformat = preg_replace("/:i/", ':i:s', $config->get('timeformat'));
				$now = JHtml::fetch('date', 'now', $config->get('dateformat') . ' ' . $timeformat, $app->get('offset', 'UTC'));

				// build log string
				$log  = str_repeat('-', strlen($now) + 4) . "\n";
				$log .= "| $now |\n";
				$log .= str_repeat('-', strlen($now) + 4) . "\n";
				$log .= "\n" . $result['log'];

				if (!empty($order->log))
				{
					// always prepend new logs at the beginning
					$log = $log . "\n\n" . $order->log;
				}

				// prepare save data
				$data = array(
					'id'              => $order->id,
					'log'             => $log,
					'payment_attempt' => ++$order->payment_attempt,
				);

				// update order logs
				$model->save($data);
			}

			/**
			 * Trigger event after the validation of a failed transaction.
			 *
			 * @param 	array  $order  The transaction details.
			 * @param 	array  $args   The response array.
			 *
			 * @return 	void
			 *
			 * @since 	1.6
			 */
			$dispatcher->trigger('onFailPaymentTransaction', array($paymentData, $result));
		}

		// check whether the payment instance supports a method
		// to be executed after the validation
		if (method_exists($obj, 'afterValidation'))
		{
			$obj->afterValidation($result['verified'] ? 1 : 0);
		}
	}
}

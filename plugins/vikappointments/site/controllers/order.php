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
 * VikAppointments order controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerOrder extends VAPControllerAdmin
{
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
		$order = VAPOrderFactory::getAppointments($oid, null, array('sid' => $sid));

		/**
		 * This event is triggered every time a payment tries to validate a transaction made.
		 *
		 * DOES NOT trigger in case the order doesn't exist.
		 *
		 * @param 	mixed 	$order  The details of the booked appointments.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onReceivePaymentNotification', array($order));

		// build return and error URL
		$return_url = "index.php?option=com_vikappointments&view=order&ordnum={$oid}&ordkey={$sid}";
		$error_url  = "index.php?option=com_vikappointments&view=order&ordnum={$oid}&ordkey={$sid}";

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
		
		/**
		 * Allow the payment for REMOVED reservations because they
		 * have been probably paid while they were PENDING.
		 * 
		 * @since 1.7
		 */
		$accepted = array(
			'PENDING',
			'REMOVED',
		);
		
		// make sure the order can be paid
		if (!in_array($order->statusRole, $accepted))
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

		/**
		 * The payment URLs are correctly routed for external usage.
		 *
		 * @since 1.6
		 */
		$return_url = $vik->routeForExternalUse($return_url, false);
		$error_url  = $vik->routeForExternalUse($error_url, false);

		/**
		 * Include the Notification URL in both the PLAIN and ROUTED formats.
		 *
		 * @since 1.7
		 */
		$notify_url = "index.php?option=com_vikappointments&task=order.notifypayment&ordnum={$oid}&ordkey={$sid}";

		// subtract amount already paid
		$total_to_pay = max(array(0, $order->totals->gross - $order->totals->paid));

		// flag used to check whether a deposit should be left
		$deposit = $payfull = false;

		// if "full amount" and "optional deposit", the deposit won't be calculated
		$deposit = VikAppointments::getDepositAmountToLeave($total_to_pay, $order->skip_deposit);

		if ($deposit !== false)
		{
			// use the specified deposit
			$total_to_pay = $deposit;
		}
	
		$paymentData['type']                 = 'appointments';
		$paymentData['action']               = 'validate';
		$paymentData['oid']                  = $order->id;
		$paymentData['sid']                  = $order->sid;
		$paymentData['attempt']              = $order->payment_attempt;
		$paymentData['transaction_name']     = JText::sprintf('VAPTRANSACTIONNAME', $config->get('agencyname'));
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
		$paymentData['leavedeposit']         = (bool) $deposit;
		$paymentData['payfull']              = (bool) $payfull;
		$paymentData['payment_info']         = $payment;
		$paymentData['details'] = array(
			'purchaser_nominative' => $order->purchaser_nominative,
			'purchaser_mail'       => $order->purchaser_mail,
			'purchaser_phone'      => $order->purchaser_phone,
		);

		/**
		 * Added support for customer billing details.
		 *
		 * @since 1.7
		 */
		$paymentData['billing'] = $order->billing;

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

		// get multi-order model
		$model = JModelVAP::getInstance('multiorder');
		
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
				$order->status = JHtml::fetch('vaphtml.status.paid', 'appointments', 'code');
				// flag the old "paid" flag for BC
				$order->paid = 1;
			}
			else
			{
				// a deposit have been left, use CONFIRMED status
				$order->status = JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code');
				// keep old "paid" flag disabled for BC
				$order->paid = 0;
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
			
			// try to send e-mail notifications
			VikAppointments::sendMailAction($order->id);

			// try to send SMS notifications
			VikAppointments::sendSmsAction($order->id);

			// try to auto-generate the invoice
			VikAppointments::generateInvoice($order->id);

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
					'Order #' . $order->id . '-' . $order->sid . ' (Appointment)',
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

	/**
	 * This task is used to confirm an order (only PENDING status).
	 * After a successful confirmation, the owner of the appointment will be notified via e-mail.
	 *
	 * The response of this action is echoed directly.
	 *
	 * This method expects the following parameters to be sent via POST or GET.
	 *
	 * NOTE: this task MUST not use security tokens to prevent CSRF, because this link is included
	 * within the e-mail of the administrators/employees, letting them to access this resource
	 * without having to log in first.
	 *
	 * @param 	integer  id        The order number.
	 * @param 	string   conf_key  The confirmation key.
	 *
	 * @return 	boolean
	 */
	public function confirm()
	{
		$input = JFactory::getApplication()->input;

		$id  = $input->getUint('id', 0);
		$key = $input->getAlnum('conf_key');
		
		if (empty($key))
		{
			// missing confirmation key
			echo '<div class="vap-confirmpage order-error">' . JText::translate('VAPCONFORDNOROWS') . '</div>';
			return false;
		}
		
		VAPLoader::import('libraries.order.factory');

		try
		{
			// get order details (search by confirmation key)
			$order = VAPOrderFactory::getAppointments($id, null, array('conf_key' => $key));
		}
		catch (Exception $e)
		{
			// order not found
			echo '<div class="vap-confirmpage order-error">' . JText::translate('VAPCONFORDNOROWS') . '</div>';
			return false;
		}

		if ($order->statusRole != 'PENDING')
		{
			if ($order->statusRole == 'APPROVED')
			{
				// this order has been already confirmed
				echo '<div class="vap-confirmpage order-notice">' . JText::translate('VAPCONFORDISCONFIRMED') . '</div>';
				return true;
			}
			else
			{
				// order expired, cannot confirm it
				echo '<div class="vap-confirmpage order-error">' . JText::translate('VAPCONFORDISREMOVED') . '</div>';
				return false;
			}
		}

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		/**
		 * NOTE: it is possible to use the onBeforeSaveReservation hook to validate the order
		 * before saving it. The "scope" attribute will let you understand that we are
		 * going to approve one or more appointments.
		 */

		// prepare save data
		$data = array(
			'id'             => $id,
			'status'         => JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code'),
			'status_comment' => 'VAP_STATUS_CONFIRMED_WITH_LINK',
			'scope'          => 'approve',
		);

		// update records
		if (!$model->save($data))
		{
			// get last registered error
			$error = $model->getError($index = null, $string = true);
			
			echo '<div class="vap-confirmpage order-error">' . ($error ? $error : JText::translate('ERROR')) . '</div>';
			return false;
		}

		// try to send e-mail notifications
		VikAppointments::sendMailAction($order->id);

		// try to send SMS notifications
		VikAppointments::sendSmsAction($order->id);

		// display successful message
		echo '<div class="vap-confirmpage order-good">' . JText::translate('VAPCONFORDCOMPLETED') . '</div>';
		return true;
	}

	/**
	 * Mark the specified order as cancelled.
	 *
	 * @return 	void
	 */
	public function cancel()
	{	
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$config = VAPFactory::getConfig();
		
		$id        = $input->getUint('id', 0);
		$sid       = $input->getString('sid', '');
		$id_parent = $input->getInt('parent', 0);

		$itemid = $input->getUint('Itemid');

		if ($id_parent > 0 && $id_parent != $id)
		{
			// we are cancelling an appointment that belong to a multi-order
			$ordnum = $id_parent;
		}
		else
		{
			// go back to the specified appointment/order
			$ordnum = $id;
		}

		// set redirection URL
		$uri = 'index.php?option=com_vikappointments&view=order&ordnum=' . $ordnum . '&ordkey=' . $sid . ($itemid ? '&Itemid=' . $itemid : '');
		$this->setRedirect(JRoute::rewrite($uri, false));
		
		if (!$config->getBool('enablecanc'))
		{
			// cancellation disabled
			$app->enqueueMessage(JText::translate('VAPORDERCANCDISABLEDERROR'), 'error');
			return false;
		}

		// Get order details (filter by ID and SID).
		// In case the order doesn't exist, an exception will be thrown.
		VAPLoader::import('libraries.order.factory');
		$order = VAPOrderFactory::getAppointments($id, null, array('sid' => $sid));

		// all the appointments must be allowed to cancel the appointments
		foreach ($order->appointments as $appointment)
		{
			if (!VikAppointments::canUserCancelOrder($appointment))
			{
				// make sure the appointment status is valid
				if ($appointment->statusRole == 'APPROVED')
				{
					// currently unable to cancel the order
					$error = JText::sprintf('VAPORDERCANCEXPIREDERROR', $config->getUint('canctime'));
					$app->enqueueMessage($error, 'error');
				}

				return false;
			}
		}

		/**
		 * Trigger event before the cancellation of the specified order.
		 *
		 * @param 	integer  $id  The order ID to cancel.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 * @deprecated 1.8 Use onBeforeSaveReservation instead.
		 */
		VAPFactory::getEventDispatcher()->trigger('onBeforeCancelOrder', array($id));

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		/**
		 * NOTE: it is possible to use the onBeforeSaveReservation hook to validate the order
		 * before saving it. The "scope" attribute will let you understand that we are
		 * going to cancel one or more appointments.
		 */

		// prepare save data
		$data = array(
			'id'             => $id,
			'status'         => JHtml::fetch('vaphtml.status.cancelled', 'appointments', 'code'),
			'status_comment' => 'VAP_STATUS_ORDER_CANCELLED',
			// auto-process the waiting list
			'notifywl'       => true,
			'scope'          => 'cancellation',
		);

		// update records
		if (!$model->save($data))
		{
			// get last registered error
			$error = $model->getError($index = null, $string = true);
			$app->enqueueMessage($error ? $error : JText::translate('ERROR'), 'error');
			return false;
		}

		if ($id_parent == $ordnum)
		{
			// we have a multi-order, make sure now all the appointments have been cancelled
			$multiOrderModel = JModelVAP::getInstance('multiorder');
			// load the status of all the children assigned to this order
			$statuses = array_unique($multiOrderModel->getChildren($ordnum, 'status'));

			// check if the statuses list contains only one element, meaning that all the
			// appointments of the order has been already cancelled
			if (count($statuses) == 1)
			{
				$data['id'] = $ordnum;
				
				unset($data['notifywl']);
				unset($data['scope']);

				// change the status of the multi-order to CANCELLED once the last
				// appointment gets cancelled
				$model->save($data);
			}
		}

		// try to send e-mail notifications
		VikAppointments::sendMailAction($id);
		return true;
	}

	/**
	 * Fetches the given order and prepares the view that is going to be printed.
	 * The template is immediately echoed and the print popup is automatically triggered.
	 * 
	 * This method expects the following parameters to be sent via POST or GET.
	 *
	 * @param 	integer  id   The order number.
	 * @param 	string 	 sid  The order key.
	 *
	 * @return 	void
	 */
	public function doprint()
	{
		$input = JFactory::getApplication()->input;

		$oid = $input->getUint('id', 0);
		$sid = $input->getString('sid', '');

		$lang = JFactory::getLanguage()->getTag();
		
		// Get order details (filter by ID and SID).
		// In case the order doesn't exist, an exception will be thrown.
		VAPLoader::import('libraries.order.factory');
		$order = VAPOrderFactory::getAppointments($oid, $lang, array('sid' => $sid));

		// load mail factory
		VAPLoader::import('libraries.mail.factory');
		$mail = VAPMailFactory::getInstance('customer', $order, array('lang' => $lang));

		// force blank template (might be not needed)
		$input->set('tmpl', 'component');

		// get template HTML
		$html = $mail->getHtml();
		
		// append script to print the document
		$html .= "<script>setTimeout(() => { window.print(); }, 500);</script>\n";

		/**
		 * Use the specific blank layout to print the view
		 * and exit to avoid including internal and external assets,
		 * which may alter the default style of the template.
		 *
		 * @since 1.6
		 */
		echo JLayoutHelper::render('document.blankpage', array('body' => $html));
		exit;
	}

	/**
	 * AJAX end-point used to fetch the formatted countdown text.
	 *
	 * This method expects the following parameters to be sent via POST or GET.
	 *
	 * @param 	integer  id            The order number.
	 * @param 	integer  locked_until  The expiration of the order (optional).
	 *
	 * @return 	void
	 */
	public function countdown()
	{
		$input = JFactory::getApplication()->input;

		// look for a given threshold
		$locked_until = $input->getUint('locked_until', null);

		if (is_null($locked_until))
		{
			// load order details
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($input->getUint('order'));
			// retrieved threshold from order details
			$locked_until = $order->locked_until;
		}

		$result = new stdClass;

		// calculate remaining seconds
		$remaining = $locked_until - time();

		if ($remaining > 0)
		{
			// format remaining seconds in a readable text
			$remaining = VikAppointments::formatMinutesToTime(ceil($remaining / 60), $apply = true);

			// order still active
			$result->status = true;
			// create countdown message
			$result->text = JText::sprintf('VAPORDERCOUNTDOWN', $remaining);
		}
		else
		{
			// expired order
			$result->status = false;
		}

		// send response to caller
		$this->sendJSON($result);
	}
}

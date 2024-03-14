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
 * VikAppointments packages confirmation view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackagesconfirm extends JModelVAP
{
	/**
	 * Completes the booking process by saving the purchased packages.
	 *
	 * @param 	array  $data  An array containing some booking options.
	 *
	 * @return 	mixed  The landing page URL on success, false otherwise.
	 */
	public function save($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		// get cart model
		$model = JModelVAP::getInstance('packagescart');
		// get cart instance
		$cart = $model->getCart();

		////////////////////////////////////////////////////////////
		////////////////////// INITIALIZATION //////////////////////
		////////////////////////////////////////////////////////////

		if ($cart->isEmpty())
		{
			// cart is empty
			$this->setError(JText::translate('VAPCARTEMPTYERR'));
			return false;
		}

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
		$dispatcher->trigger('onInitSavePackagesOrder', array($cart));

		////////////////////////////////////////////////////////////
		//////////////////// FETCH CUSTOM FIELDS ///////////////////
		////////////////////////////////////////////////////////////

		// prepare order array
		$order = array();

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
		$dispatcher->trigger('onPrepareFieldsSavePackagesOrder', array(&$order['custom_f'], &$tmp));

		// register data fetched by the custom fields so that the package order
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
		///////////////////// VALIDATE PAYMENT /////////////////////
		////////////////////////////////////////////////////////////

		$payment = null;
		
		if ($cart->getTotalGross() > 0)
		{
			// load supported payments
			$payments = VikAppointments::getPayments('packages');

			if (!isset($data['id_payment']))
			{
				$data['id_payment'] = 0;
			}

			// unset payment charge
			$order['payment_charge'] = 0;
			$order['payment_tax']    = 0;

			/**
			 * Trigger event to manipulate the selected payment gateway.
			 *
			 * @param 	integer  &$id_payment  The ID of the selected payment.
			 * @param 	array 	 &$payments    The list of the available payments.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onSwitchPaymentSavePackagesOrder', array(&$data['id_payment'], &$payments));
			
			if ($payments)
			{
				// search for the selected gateway
				$payments = array_filter($payments, function($gateway) use ($data)
				{
					return $gateway['id'] == $data['id_payment'];
				});

				// take the first payment found
				$payment = array_shift($payments);

				if (!$payment)
				{
					// invalid payment
					$this->setError(JText::translate('VAPERRINVPAYMENT'));
					return false;
				}

				// register payment ID
				$order['id_payment'] = $payment['id'];

				if ($payment['charge'] > 0)
				{
					VAPLoader::import('libraries.tax.factory');

					$options = array();
					$options['subject'] = 'payment';
					$options['order']   = $order;
					// $options['id_user'] = $user->id;

					// calculate payment taxes
					$charge = VAPTaxFactory::calculate($payment['id'], $payment['charge'], $options);

					// set payment charge
					$order['payment_charge'] = $charge->net;
					$order['payment_tax']    = $charge->tax;
				}
				else if ($payment['charge'] < 0)
				{
					// register payment charge within the cart as discount
					$cart->setDiscount(new VAPCartDiscount('payment', $payment['charge']));
				}

				// auto-confirm orders according to the configuration of
				// the payment, otherwise force PENDING status to let the
				// customers be able to start a transaction
				if ($payment['setconfirmed'])
				{
					// auto-confirm order
					$order['status'] = JHtml::fetch('vaphtml.status.confirmed', 'packages', 'code');
				}
			}
		}

		////////////////////////////////////////////////////////////
		///////////////////// FETCH TOTAL COSTS ////////////////////
		////////////////////////////////////////////////////////////

		/**
		 * Trigger event to manipulate the total cost before it is going to be calculated.
		 * 
		 * The prices of the cart are strictly related to the taxes and to the discounts.
		 * For this reason it is not possible to change the total cost and the user credit
		 * at runtime. Any surcharge/discount have to be applied by using the apposite
		 * methods provided by the cart objects.
		 *
		 * @param 	VAPCartPackages  $cart  The cart instance.
		 * @param 	JUser 	         $user  The instance of the current user.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBeforeCalculateTotalSavePackagesOrder', array($cart, $user));

		// set up order totals
		$order['total_cost'] = $cart->getTotalGross();
		$order['total_net']  = $cart->getTotalNet();
		$order['total_tax']  = $cart->getTotalTax();
		$order['discount']   = $cart->getTotalDiscount();

		// increase total cost by the payment charge
		if (!empty($order['payment_charge']))
		{
			$order['total_cost'] += $order['payment_charge'] + $order['payment_tax'];
			$order['total_tax']  += $order['payment_tax'];
		}

		////////////////////////////////////////////////////////////
		/////////////////////// ORDER STATUS ///////////////////////
		////////////////////////////////////////////////////////////

		if (empty($order['status']))
		{
			// auto-confirm in case of no cost
			$status = $order['total_cost'] > 0 ? 'pending' : 'confirmed';

			// status not yet specified, use pending
			$order['status'] = JHtml::fetch('vaphtml.status.' . $status, 'packages', 'code');
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
		$dispatcher->trigger('onFetchStatusSavePackagesOrder', array(&$order['status'], &$order['status_comment']));

		// check whether the status has been immediately confirmed and we have an empty comment
		if (empty($order['status_comment']) && JHtml::fetch('vaphtml.status.isconfirmed', 'packages', $order['status']))
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
		///////////////////// FETCH ORDER ITEMS ////////////////////
		////////////////////////////////////////////////////////////

		$itemsTotals = $cart->getTotalsPerItem();

		$order['items'] = array();

		foreach ($cart->getPackagesList() as $i => $p)
		{
			$item = array();

			// register package details
			$item['id_package'] = $p->getID();
			$item['price']      = $p->getPrice();
			$item['quantity']   = $p->getQuantity();
			$item['num_app']    = $p->getNumberAppointments() * $p->getQuantity();

			// register package totals
			$item['net']      = $itemsTotals[$i]->net;
			$item['tax']      = $itemsTotals[$i]->tax;
			$item['gross']    = $itemsTotals[$i]->gross;
			$item['discount'] = $itemsTotals[$i]->discount;

			// register package tax breakdown
			$item['tax_breakdown'] = json_encode($itemsTotals[$i]->breakdown);

			/**
			 * Check whether the purchased package has an expiration threshold.
			 * 
			 * @since 1.7.4
			 */
			$validity = (int) JModelVAP::getInstance('package')->getItem($p->getID(), $blank = true)->validity;

			if ($validity)
			{
				// the package can be redeemed until {$validity} days since now
				$item['validthru'] = JFactory::getDate('+' . $validity . ' days')->toSql();
			}

			// register package
			$order['items'][] = $item;
		}

		////////////////////////////////////////////////////////////
		//////////////////// SAVE ORDER DETAILS ////////////////////
		////////////////////////////////////////////////////////////

		$ordnum = $ordkey = null;

		// get package order model
		$orderModel = JModelVAP::getInstance('packorder');

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
		$cart->store();

		////////////////////////////////////////////////////////////
		////////////////////// NOTIFICATIONS ///////////////////////
		////////////////////////////////////////////////////////////

		$mailOptions = array();
		// validate e-mail rules before sending
		$mailOptions['check'] = true;

		// send e-mail notification to the customer
		$orderModel->sendEmailNotification($ordnum, $mailOptions);

		// send e-mail notification to the administrator(s)
		$mailOptions['client'] = 'packadmin';
		$orderModel->sendEmailNotification($ordnum, $mailOptions);

		$redirect_url = "index.php?option=com_vikappointments&view=packagesorder&ordnum={$ordnum}&ordkey={$ordkey}";

		if (!empty($data['itemid']))
		{
			$redirect_url .= "&Itemid={$data['itemid']}";
		}

		/**
		 * Trigger event to manipulate the redirect URL after completing
		 * the packages purchase process.
		 *
		 * Use VAPOrderFactory::getPackages($ordnum) to access the order details.
		 *
		 * @param 	string 	 &$url   The redirect URL (plain).
		 * @param 	integer  $order  The order id.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onRedirectPackagesOrder', array(&$redirect_url, $ordnum));
		
		// rewrite landing page
		return JRoute::rewrite($redirect_url, false);
	}
}

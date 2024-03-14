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
// load cart framework
VikAppointments::loadCartLibrary();

/**
 * VikAppointments appointments confirmation view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelConfirmapp extends JModelVAP
{
	/**
	 * Completes the booking process by saving the booked appointments.
	 *
	 * @param 	array  $data  An array containing some booking options.
	 *
	 * @return 	mixed  The landing page URL on success, false otherwise.
	 */
	public function save($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$config = VAPFactory::getConfig();

		// get cart model
		$model = JModelVAP::getInstance('cart');
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

		/**
		 * Trigger event to manipulate the cart instance.
		 *
		 * @param 	mixed 	&$cart 	The cart instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		$dispatcher->trigger('onInitSaveOrder', array(&$cart));

		////////////////////////////////////////////////////////////
		//////////////////// AVAILABILITY CHECK ////////////////////
		////////////////////////////////////////////////////////////

		try
		{
			// validates the availability according to the current platform
			VAPApplication::getInstance()->checkAvailability();
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// validates the appointments contained within the cart and
		// obtain all the employees that have been assigned to each
		// appointment into the cart
		if (!$model->checkIntegrity($errors, $employeesLookup))
		{
			// there's at least an invalid item...
			foreach ($errors as $error)
			{
				$name    = $error['item']->getServiceName();
				$at      = JText::translate('VAP_AT_DATE_SEPARATOR');
				$checkin = $error['item']->getCheckinDate(JText::translate('DATE_FORMAT_LC2'), VikAppointments::getUserTimezone());

				// build item identifier string
				$item_id = sprintf('%s %s %s', $name, $at, $checkin);

				// register error message
				$reason = JText::sprintf('VAPCARTITEMNOTAVERR', $item_id, $error['reason']);
				$this->setError($reason);
			}

			return false;
		}

		/**
		 * Validates the "Mandatory Purchase" setting of the packages, by checking
		 * whether all the items within the cart can be redeemed.
		 *
		 * @since 1.7
		 */
		if (VikAppointments::isCompliantWithMandatoryPackage($cart) == false)
		{
			// not enough packages to redeem
			$link = JRoute::rewrite('index.php?option=com_vikappointments&view=packages');
			$this->setError(JText::sprintf('VAPPACKAGEREQERR', $link));

			return false;
		}

		////////////////////////////////////////////////////////////
		//////////////////// ZIP CODE VALIDATION ///////////////////
		////////////////////////////////////////////////////////////

		// try to validate the specified ZIP code
		if (!$this->validateZipCode(isset($data['zip']) ? $data['zip'] : null))
		{
			// the specified ZIP code is not allowed
			$this->setError(JText::translate('VAPCONFAPPZIPERROR'));
			return false;
		}

		////////////////////////////////////////////////////////////
		//////////////////// FETCH CUSTOM FIELDS ///////////////////
		////////////////////////////////////////////////////////////

		// get cart items
		$items = $cart->getItemsList();

		// fetch all the booked services
		$all_booked_services = VAPCartUtils::getServices($items);
		// fetch all the employees that have been explicitly booked
		$all_booked_employees = VAPCartUtils::getEmployees($items);

		// prepare order array
		$order = array();

		// register current language tag
		$order['langtag'] = JFactory::getLanguage()->getTag();

		$user = JFactory::getUser();

		// import custom fields requestor and loader (as dependency)
		VAPLoader::import('libraries.customfields.requestor');

		// get relevant custom fields only
		$_cf = VAPCustomFieldsLoader::getInstance()
			->noSeparator()
			->setLanguageFilter($order['langtag'])
			// extend custom fields by specifying all the booked services
			->forService($all_booked_services);

		if (count($all_booked_employees) == 1)
		{
			// obtain custom fields assigned to the selected employee only
			// in case all the appointments have been explictly booked for
			// the same employee
			$_cf->ofEmployee($all_booked_employees[0]);
		}

		// load custom fields array
		$customFields = $_cf->fetch();

		try
		{
			// load custom fields from request
			$order['custom_f'] = VAPCustomFieldsRequestor::loadForm($customFields, $tmp, $strict = true);
		}
		catch (Exception $e)
		{
			// catch exception and register it as error message
			$this->setError($e->getMessage());
			return false;
		}

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
		 * @since 	1.6
		 */
		$dispatcher->trigger('onPrepareFieldsSaveOrder', array(&$order['custom_f'], &$tmp));

		// copy uploads into the apposite column
		$order['uploads'] = $tmp['uploads'];

		// register data fetched by the custom fields so that the reservation
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
		/////////////////// FETCH ATTENDEES DATA ///////////////////
		////////////////////////////////////////////////////////////

		$numAttendees = VAPCartUtils::getAttendees($items);

		$order['attendees'] = array();

		/**
		 * Recover attendees custom fields.
		 *
		 * @since 1.7
		 */
		for ($attendee = 1; $attendee < $numAttendees; $attendee++)
		{
			// reset attendee array
			$attendeeData = array();

			// load custom fields from request for other attendees
			$tmp = VAPCustomFieldsRequestor::loadFormAttendee($attendee, $customFields, $attendeeData);
			// inject attendee custom fields within the array containing the fetched rules
			$attendeeData['fields'] = $tmp;

			// register attendee
			$order['attendees'][] = $attendeeData;
		}

		////////////////////////////////////////////////////////////
		//////////////////// FETCH USER TIMEZONE ///////////////////
		////////////////////////////////////////////////////////////

		if ($config->getBool('multitimezone'))
		{
			// multi-timezone enabled, we need to register the timezone
			// that might have been selected by the user, in order to 
			// display the correct date and time also after the purchase
			$order['user_timezone'] = VikAppointments::getUserTimezone()->getName();
		}

		////////////////////////////////////////////////////////////
		///////////////////// VALIDATE PAYMENT /////////////////////
		////////////////////////////////////////////////////////////

		$payment = null;
		
		if ($cart->getTotalGross() > 0)
		{
			if (count($all_booked_employees) == 1)
			{
				// only one employee has been explicitly selected, use its own payments
				$payments = VikAppointments::getAllEmployeePayments($all_booked_employees[0]);
			}
			else
			{
				// get global payments
				$payments = VikAppointments::getAllEmployeePayments();
			}

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
			 * @since 	1.6
			 */
			$dispatcher->trigger('onSwitchPaymentSaveOrder', array(&$data['id_payment'], &$payments));
			
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
					$order['status'] = JHtml::fetch('vaphtml.status.confirmed', 'appointments', 'code');
				}
				else
				{
					// leave it pending
					$order['status'] = JHtml::fetch('vaphtml.status.pending', 'appointments', 'code');
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
		 * For this reason, @since 1.7 it is no more possible to change the total cost and
		 * the user credit at runtime. Any surcharge/discount have now to be applied by
		 * using the apposite methods provided by the cart objects.
		 *
		 * @param 	VAPCart  $cart  The cart instance (@since 1.7).
		 * @param 	JUser 	 $user  The instance of the current user.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		$dispatcher->trigger('onBeforeCalculateTotalSaveOrder', array($cart, $user));

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
			// status not yet specified, use the default one set in config
			$order['status'] = $config->get('defstatus');
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
		$dispatcher->trigger('onFetchStatusSaveOrder', array(&$order['status'], &$order['status_comment']));

		// check whether the status has been immediately confirmed and we have an empty comment
		if (empty($order['status_comment']) && JHtml::fetch('vaphtml.status.isconfirmed', 'appointments', $order['status']))
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

		/**
		 * Trigger event to manipulate any coupon code. It is also possible
		 * to apply additional events in case a specific coupon code is applied.
		 *
		 * @param 	mixed 	&$coupon  The coupon code array, if any. Otherwise an empty string.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 * @since 	1.7  The hook has been deactivated. Any further validations of the coupon 
		 *               codes should be applied by using the apposite hooks.
		 *
		 * @see     onBeforeActivateCoupon
		 */
		// $dispatcher->trigger('onBeforeCouponSaveOrder', array(&$coupon));

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

		// save user data
		if (!$user->guest || !empty($order['fields_data']['purchaser_mail']))
		{
			// create customer data
			$customer = array(
				'id'     => 0,
				'jid'    => $user->guest ? 0 : $user->id,
				'fields' => array_merge($order['custom_f'], $order['uploads']),
			);

			// inject fetched billing details
			$customer = array_merge($customer, $order['fields_data']);

			// get all redeemed discounts
			$offers = $cart->getTotalDiscountPerOffer();

			if (!empty($offers['credit']))
			{
				// registers the used credit
				$customer['used_credit'] = (float) $offers['credit'];
			}

			// get customer model
			$customerModel = JModelVAP::getInstance('customer');

			// insert/update customer
			if ($id_user = $customerModel->save($customer))
			{
				// assign reservation to saved customer
				$order['id_user'] = $id_user;
			}
		}

		////////////////////////////////////////////////////////////
		///////////////////// SAVE PARENT ORDER ////////////////////
		////////////////////////////////////////////////////////////

		$ordnum = $ordkey = null;

		// get multi-order model
		$multiOrderModel = JModelVAP::getInstance('multiorder');

		if ($cart->getCartLength() > 1)
		{
			// we are booking 2 or more appointments, so we need to create the parent order first
			if (!$multiOrderModel->save($order))
			{
				// something went wrong, retrieve error
				$error = $multiOrderModel->getError($index = null, $string = true);
				$this->setError($error);
				return false;
			}

			// load details of the saved order
			$parent = $multiOrderModel->getData();

			// use order number/key pair of saved parent
			$ordnum = $parent['id'];
			$ordkey = $parent['sid'];
		}
		else
		{
			// no parent to use
			$parent = null;
		}

		////////////////////////////////////////////////////////////
		//////////////////// LOAD ITEMS TOTALS /////////////////////
		////////////////////////////////////////////////////////////

		$itemsTotals = $cart->getTotalsPerItem();

		////////////////////////////////////////////////////////////
		/////////////////// SAVE BOOKED SERVICES ///////////////////
		////////////////////////////////////////////////////////////

		// get appointments model
		$appModel = JModelVAP::getInstance('reservation');
		// get service-employee assoc model
		$assocModel = JModelVAP::getInstance('serempassoc');
		// get waiting list model
		$waitModel = JModelVAP::getInstance('waitinglist');

		// track all the employees that have been assigned to the reservations
		$assigned_employees = array();

		// iterate all the registered items
		foreach ($items as $i => $item)
		{
			if ($parent)
			{
				// use the parent ID and SID
				$order['id_parent'] = $parent['id'];
				$order['sid'] = $parent['sid'];
			}

			// Load service overrides to fetch sleep time.
			// NOTE: we need to use the employee ID set in the cart, because in case the
			// employee was not selected, we should keep using the default service sleep time.
			$assoc = $assocModel->getOverrides($item->getServiceID(), $item->getEmployeeID());

			// register appointment details
			$order['id_service']  = $item->getServiceID();
			$order['id_employee'] = $employeesLookup[$i];
			$order['checkin_ts']  = JDate::getInstance($item->getCheckinDate())->toSql();
			$order['people']      = $item->getPeople();
			$order['duration']    = $item->getDuration();
			$order['sleep']       = $assoc ? $assoc->sleep : 0;
			$order['view_emp']    = $assoc ? $assoc->choose_emp : 0;

			// register appointment totals
			$order['total_cost'] = $itemsTotals[$i]->subgross;
			$order['total_net']  = $itemsTotals[$i]->subnet;
			$order['total_tax']  = $itemsTotals[$i]->subtax;
			$order['discount']   = $itemsTotals[$i]->subdisc;

			// register service totals
			$order['service_price']    = $itemsTotals[$i]->priceBeforeDiscount / $item->getPeople();
			$order['service_net']      = $itemsTotals[$i]->net;
			$order['service_tax']      = $itemsTotals[$i]->tax;
			$order['service_gross']    = $itemsTotals[$i]->gross;
			$order['service_discount'] = $itemsTotals[$i]->discount;
			
			// register service tax breakdown
			$order['tax_breakdown'] = json_encode($itemsTotals[$i]->breakdown);
			
			// unset payment charge
			$order['payment_charge'] = 0;
			$order['payment_tax']    = 0;

			$order['options'] = array();

			foreach ($item->getOptionsList() as $j => $itemOption)
			{
				$option = array();

				// init option base details
				$option['id_option']    = $itemOption->getID();
				$option['id_variation'] = $itemOption->getVariationID();
				$option['quantity']     = $itemOption->getQuantity();
				$option['inc_price']    = $itemOption->getPrice();

				// register option totals
				$option['net']      = $itemsTotals[$i]->options[$j]->net;
				$option['tax']      = $itemsTotals[$i]->options[$j]->tax;
				$option['gross']    = $itemsTotals[$i]->options[$j]->gross;
				$option['discount'] = $itemsTotals[$i]->options[$j]->discount;

				// register option tax breakdown
				$option['tax_breakdown'] = json_encode($itemsTotals[$i]->options[$j]->breakdown);

				/**
				 * Trigger event to manipulate the order item option before storing it.
				 *
				 * @param 	array  &$option  The option details (array @since 1.7).
				 * @param 	array  $order    The order item details (array @since 1.7).
				 * @param 	mixed  $item     The cart item instance.
				 *
				 * @return 	void
				 *
				 * @since 	1.6
				 * @deprecated 1.8  Use onBeforeSaveResoption hook instead.
				 */
				$dispatcher->trigger('onBeforeOptionSaveOrder', array(&$option, $order, $item));

				// add option
				$order['options'][] = $option;
			}

			/**
			 * Trigger event to manipulate the order item details before storing it.
			 *
			 * @param 	array  &$order  The order item details (array @since 1.7).
			 * @param 	mixed  $item    The cart item instance.
			 *
			 * @return 	void
			 *
			 * @since 	1.6
			 * @deprecated 1.8  Use onBeforeSaveReservation hook instead.
			 */
			$dispatcher->trigger('onBeforeSaveOrder', array(&$order, $item));

			// save the order
			if ($appModel->save($order))
			{
				// get reservation saved data
				$appData = $appModel->getData();

				if (!$ordnum)
				{
					// use order number/key pair of saved reservation
					$ordnum = $appData['id'];
					$ordkey = $appData['sid'];
				}

				// register booked employee
				if (!in_array($appData['id_employee'], $assigned_employees))
				{
					$assigned_employees[] = $appData['id_employee'];
				}

				/**
				 * Trigger event after storing the order item details.
				 *
				 * @param 	object  $order  The order item details object (removed reference @since 1.7).
				 * @param 	mixed 	$item   The cart item instance.
				 *
				 * @return 	void
				 *
				 * @since 	1.6
				 * @deprecated 1.8  Use onAfterSaveReservation hook instead.
				 */
				$dispatcher->trigger('onAfterSaveOrder', array($order, $item));

				// the appointment was registered, unsubscribe the customer from
				// the related waiting list
				$waitModel->unsubscribe(array(
					'jid'          => $appData['createdby'],
					'email'        => $appData['purchaser_mail'],
					'phone_number' => $appData['purchaser_phone'],
					'timestamp'    => $appData['checkin_ts'],
					'id_service'   => $appData['id_service'],
				));
			}
		}

		// empty cart on success
		$cart->emptyCart();
		$cart->store();

		////////////////////////////////////////////////////////////
		///////////////////////// PACKAGES /////////////////////////
		////////////////////////////////////////////////////////////

		// register used packages after saving all the appointments,
		// because we need to load all the saved records
		$redeemed = JModelVAP::getInstance('packorder')->usePackages($ordnum, $increase = true);

		if ($redeemed)
		{
			// some packages have been redeemed, use a different status comment
			VAPOrderStatus::getInstance()->keepTrack($order['status'], $ordnum, 'VAP_STATUS_PACKAGE_REDEEMED');
		}

		////////////////////////////////////////////////////////////
		////////////////////// NOTIFICATIONS ///////////////////////
		////////////////////////////////////////////////////////////

		$mailOptions = array();
		// validate e-mail rules before sending
		$mailOptions['check'] = true;

		// send e-mail notification to the customer
		$appModel->sendEmailNotification($ordnum, $mailOptions);

		// send e-mail notification to the administrator(s)
		$mailOptions['client'] = 'admin';
		$appModel->sendEmailNotification($ordnum, $mailOptions);

		// send e-mail notification to all the booked employees
		$mailOptions['client'] = 'employee';

		foreach ($assigned_employees as $id_employee)
		{
			$mailOptions['id_employee'] = (int) $id_employee;
			$appModel->sendEmailNotification($ordnum, $mailOptions);
		}
		
		// try to send SMS notifications
		VikAppointments::sendSmsAction($ordnum);

		$redirect_url = "index.php?option=com_vikappointments&view=order&ordnum={$ordnum}&ordkey={$ordkey}";

		if (!empty($data['itemid']))
		{
			$redirect_url .= "&Itemid={$data['itemid']}";
		}

		/**
		 * Trigger event to manipulate the redirect URL after completing
		 * the appointment booking process.
		 *
		 * Use VAPOrderFactory::getAppointments($ordnum) to access the order details.
		 *
		 * @param 	string 	 &$url   The redirect URL (plain).
		 * @param 	integer  $order  The order id (replaced order array @since 1.7).
		 *
		 * @return 	void
		 *
		 * @since 	1.6.4
		 */
		$dispatcher->trigger('onRedirectOrder', array(&$redirect_url, $ordnum));
		
		// rewrite landing page
		return JRoute::rewrite($redirect_url, false);
	}

	/**
	 * Checks whether the specified ZIP code is accepted by the booked
	 * employees. If not specified, the ZIP code will be retrieved from
	 * the request according to the name of the assigned custom field.
	 *
	 * @param 	string|null  $zip  The ZIP code.
	 *
	 * @return  boolean      True if accepted, false otherwise.
	 */
	public function validateZipCode($zip = null)
	{
		// get cart instance
		$cart = JModelVAP::getInstance('cart')->getCart();

		// get cart items
		$items = $cart->getItemsList();

		// load all the selected employees
		$id_employees = VAPCartUtils::getEmployees($items);		

		// load all the selected services
		$id_services = VAPCartUtils::getServices($items);
		
		// validate ZIP code
		return VikAppointments::validateZipCode($zip, $id_employees, $id_services);
	}
}

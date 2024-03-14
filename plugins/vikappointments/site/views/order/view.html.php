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

/**
 * VikAppointments reservation (order) summary view.
 * In case the request doesn't provide the ORDER NUMBER
 * and the ORDER KEY, a form to search a reservation
 * will be displayed.
 *
 * @since 1.0
 */
class VikAppointmentsVieworder extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$config = VAPFactory::getConfig();
		
		$oid = $input->get('ordnum', 0, 'uint');
		$sid = $input->get('ordkey', '', 'alnum');

		$this->itemid = $input->getInt('Itemid', 0);
		
		$order = null;
		
		if (!empty($oid) && !empty($sid))
		{
			// check whether the appointment has expired
			JModelVAP::getInstance('reservation')->checkExpired(array('id' => $oid));

			try
			{
				// get order details (filter by ID and SID)
				VAPLoader::import('libraries.order.factory');
				$order = VAPOrderFactory::getAppointments($oid, JFactory::getLanguage()->getTag(), array('sid' => $sid));
			}
			catch (Exception $e)
			{
				// reservation not found
			}

			if ($order)
			{
				// check if a payment is required
				if ($order->payment)
				{
					// reload payment details to access the parameters
					$payment = JModelVAP::getInstance('payment')->getItem($order->payment->id);

					// apply payment translations
					$payment->name    = $order->payment->name;
					$payment->prenote = $order->payment->notes->beforePurchase;
					$payment->note    = $order->payment->notes->afterPurchase;

					$paymentData = array();

					$vik = VAPApplication::getInstance();

					/**
					 * The payment URLs are correctly routed for external usage.
					 *
					 * @since 1.6
					 */
					$return_url = $vik->routeForExternalUse("index.php?option=com_vikappointments&view=order&ordnum={$oid}&ordkey={$sid}", false);
					$error_url  = $vik->routeForExternalUse("index.php?option=com_vikappointments&view=order&ordnum={$oid}&ordkey={$sid}", false);

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

					if ($order->statusRole == 'PENDING')
					{
						// check if the customer should pay the full amount (only for PENDING orders)
						$payfull = $this->shouldPayFullAmount($order);

						// if "full amount" and "optional deposit", the deposit won't be calculated
						$deposit = VikAppointments::getDepositAmountToLeave($total_to_pay, $payfull);

						if ($deposit !== false)
						{
							// use the specified deposit
							$total_to_pay = $deposit;
						}
					}
				
					$paymentData['type']                 = 'appointments';
					$paymentData['action']               = 'create';
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
					 * @param 	mixed 	&$params  The payment configuration as array or JSON.
					 *
					 * @return 	void
					 *
					 * @since 	1.6
					 */
					VAPFactory::getEventDispatcher()->trigger('onInitPaymentTransaction', array(&$paymentData, &$paymentData['payment_info']->params));

					// register the payment details
					$this->payment = $paymentData;
				}

				// register all the locations 
				$this->locations = array();

				foreach ($order->appointments as $appointment)
				{
					if ($appointment->location)
					{
						// create location marker
						$marker = new stdClass;
						$marker->lat = $appointment->location->latitude;
						$marker->lng = $appointment->location->longitude;

						// check whether the same marker was already in the list
						for ($i = 0, $found = false; $i < count($this->locations) && !$found; $i++)
						{
							$found = (
								$this->locations[$i]->lat === $marker->lat
								&& $this->locations[$i]->lng === $marker->lng
							);
						}

						/**
						 * Exclude the locations that have not specified the coordinates,
						 * since it wouldn't be possible to display them through Google Maps.
						 * 
						 * @since 1.7.1
						 */
						if (!$found && $marker->lat !== null && $marker->lng !== null)
						{
							// create marker label built as [SERVICE], [EMPLOYEE] (optional) <br /> [CHECK-IN]
							$marker->label = $appointment->service->name;

							if ($appointment->viewEmp)
							{
								$marker->label .= ', ' . $appointment->employee->name;
							}

							$marker->label .= '<br />' . $appointment->customerCheckin->lc2;

							// register full location address
							$marker->address = $appointment->location->text;

							// marker not found, add it in the list
							$this->locations[] = $marker;
						}
					}
				}

				// sort order appointments by service check-in
				$this->services = $this->sortOrdersByServiceDate($order);
			}
			else
			{
				// raise error, reservation not found
				$app->enqueueMessage(JText::translate('VAPORDERRESERVATIONERROR'), 'error');
			}
		}
		
		if (!$order)
		{
			// use "track" layout in case the order
			// was not found or in case the order number
			// and the order key was not submitted
			$this->setLayout('track');
		}
		else
		{
			// print conversion code if needed
			VAPLoader::import('libraries.models.conversion');
			VAPConversion::getInstance(array('page' => 'order'))->trackCode($order);

			// register order details on success
			$this->order = $order;

			// count the number of confirmed appointments
			$this->canUserCancelAll = true;

			foreach ($order->appointments as $appointment)
			{
				// check whether the user can cancel this appointment
				$appointment->canUserCancel = VikAppointments::canUserCancelOrder($appointment);
				// update global flag
				$this->canUserCancelAll = $this->canUserCancelAll && $appointment->canUserCancel;
			}
		}

		// prepare page content
		VikAppointments::prepareContent($this);

		// extend pathway for breadcrumbs module
		$this->extendPathway($app);
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sort the appointments by service ID and then by check-in date.
	 *
	 * @param 	VAPOrderAppointment  $order  The appointment details object.
	 *
	 * @return 	array  An array containing the grouped services.
	 *
	 * @since 	1.4
	 */
	protected function sortOrdersByServiceDate($order)
	{
		$list = $order->appointments;

		usort($list, function($a, $b)
		{
			// get ordering factor based on service ID
			$factor = $a->service->id - $b->service->id;

			if ($factor == 0)
			{
				// same service ID, get ordering factor based on check-in
				$factor = $a->checkin->timestamp - $b->checkin->timestamp;
			}

			return $factor;
		});

		$map = array();

		// group appointments by service
		foreach ($list as $app)
		{
			if (!isset($map[$app->service->id]))
			{
				$map[$app->service->id] = array();
			}

			$map[$app->service->id][] = $app;
		}

		return $map;
	}

	/**
	 * Checks whether the specified user is allowed to pay the full amount
	 * in place of the requested deposit.
	 * 
	 * @param 	VAPOrderAppointment  $order  The appointment details object.
	 *
	 * @return 	boolean  True in case the full amount should be paid, false
	 *                   otherwise (or in case the deposit is disabled).
	 *
	 * @since 	1.6
	 */
	protected function shouldPayFullAmount($order)
	{
		if (VAPFactory::getConfig()->getUint('usedeposit') != 1)
		{
			// deposit is disabled
			return false;
		}

		// value set after checking the input to pay the full amount instead than the deposit
		$pay_full_amount = JFactory::getApplication()->input->getUint('payfull', null);

		if (!is_null($pay_full_amount))
		{
			// update order details with given decision
			$order->skip_deposit = $pay_full_amount;

			$dbo = JFactory::getDbo();

			// commit in database too
			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_reservation'))
				->set($dbo->qn('skip_deposit') . ' = ' . $pay_full_amount)
				->where(array(
					$dbo->qn('id') . ' = ' . $order->id,
					$dbo->qn('id_parent') . ' = ' . $order->id_parent,
				), 'OR');

			$dbo->setQuery($q);
			$dbo->execute();
		}

		return (bool) $order->skip_deposit;
	}

	/**
	 * Checks whether the payment (if needed) matches the specified position.
	 * In that case, the payment form/notes will be echoed.
	 *
	 * @param 	string 	$position  The position in which to print the payment.
	 *
	 * @return 	string 	The HTML to display.
	 *
	 * @since 	1.7
	 */
	protected function displayPayment($position)
	{
		if (empty($this->payment))
		{
			// nothing to display
			return '';
		}

		$position = 'vap-payment-position-' . $position;

		// get payment position
		$tmp = $this->payment['payment_info']->position;

		if (!$tmp)
		{
			// use bottom-left by default
			$tmp = 'vap-payment-position-bottom-left';
		}

		// compare payment position
		if (strpos($tmp, $position) === false)
		{
			// position doesn't match
			return '';
		}

		// build display data
		$data = array(
			'data'  => $this->payment,
			'order' => $this->order,
			'scope' => 'appointments',
		);

		// get status role to identify the correct payment layout
		$status = strtolower($this->order->statusRole);

		if (!$status)
		{
			// unable to detect the status role...
			return '';
		}

		// return payment layout based on current status role
		return JLayoutHelper::render('blocks.payment.' . $status, $data);
	}

	/**
	 * Extends the pathway for breadcrumbs module.
	 *
	 * @param 	mixed 	$app  The application instance.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	protected function extendPathway($app)
	{
		$pathway = $app->getPathway();
		$items   = $pathway->getPathway();
		$last 	 = end($items);

		// Make sure the order page is not a menu item, otherwise
		// the pathway will display something like:
		// Home > Menu > Order > [ORDNUM]-[ORDKEY]
		if ($last && strpos($last->link, '&view=order') === false && !empty($this->order))
		{
			// register link into the Breadcrumb
			$link = 'index.php?option=com_vikappointments&view=order&ordnum=' . $this->order->id . '&ordkey=' . $this->order->sid;
			$pathway->addItem($this->order->id . '-' . $this->order->sid, $link);
		}
	}
}

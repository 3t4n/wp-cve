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
 * VikAppointments cart model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCart extends JModelVAP
{
	/**
	 * Registers a new appointment within the cart.
	 *
	 * @param 	mixed  $data  Either an array or an object holding
	 *                        the appointment details.
	 *
	 * @return 	mixed  The added item on success, false otherwise.
	 */
	public function addItem($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();
		$config     = VAPFactory::getConfig();
			
		// load service details according to the overrides of the specified employee (if any)
		$service = JModelVAP::getInstance('serempassoc')->getOverrides($data['id_service'], $data['id_employee']);

		if (!$service)
		{
			// the service (or the relation with the employee) doesn't exist
			$this->setError(JText::translate('VAPSERNOTFOUNDERROR'));
			return false;
		}

		$empModel = JModelVAP::getInstance('employee');

		// validate selected employee
		$data['id_employee'] = isset($data['id_employee']) ? (int) $data['id_employee'] : 0;

		if ($data['id_employee'] > 0)
		{
			// get employee details
			$employee = $empModel->getItem($data['id_employee']);

			if (!$employee)
			{
				// employee not found
				$this->setError(JText::translate('VAPEMPNOTFOUNDERROR'));
				return false;
			}
		}

		// check if we have to build the check-in date
		if (isset($data['date']) && empty($data['checkin']))
		{
			$tz = $empModel->getTimezone($data['id_employee']);
			// create date according to the employee timezone
			$dt = new JDate("{$data['date']} {$data['hour']}:{$data['min']}:00", $tz);
			// adjust date to UTC
			$data['checkin'] = $dt->format('Y-m-d H:i:s');
		}

		$data['options'] = isset($data['options']) ? $data['options'] : array();
		
		// validate options
		$empty_options = $this->validateOptions($service->id, $data['options']);

		if ($empty_options !== false)
		{
			// missing required options
			$this->setError(JText::translate('VAPOPTIONREQUIREDERR'));
			return false;
		}

		/**
		 * Validate service restrictions.
		 *
		 * @since 1.6.5
		 */
		VAPLoader::import('libraries.models.restrictions');
		
		if (!VAPSpecialRestrictions::canBookService($data['id_service'], $data['checkin'], $restr))
		{
			if (VikAppointments::isUserLogged())
			{
				// the user already reached the maximum threshold
				$err = JText::sprintf(
					'VAPRESTRICTIONLIMITREACHED',
					$restr->maxapp,
					strtolower(JText::translate('VAPMANAGERESTRINTERVAL' . strtoupper($restr->interval)))
				);
			}
			else
			{
				// login needed before to see the available slots
				$err = JText::translate('VAPRESTRICTIONLIMITGUEST');
			}

			// register fetched error
			$this->setError($err);
			return false;
		}

		// fetch number of participants
		$data['people'] = isset($data['people']) ? (int) $data['people'] : 1;
		$data['people'] = max(array(1, $data['people']));

		// get check-in date adjusted to the local timezone of the employee, so that we can
		// properly calculate the special rates
		$empCheckin = JHtml::fetch('date', $data['checkin'], 'Y-m-d H:i:s', $empModel->getTimezone($data['id_employee']));

		/**
		 * The price is calculated using the special rates.
		 *
		 * @since 1.6
		 */
		$service->price = VAPSpecialRates::getRate($service->id, $data['id_employee'], $empCheckin, $data['people']);
		
		if ($service->priceperpeople)
		{
			// multiply the price by the number of participants
			$service->price *= $data['people'];
		}

		/**
		 * In case the checkout selection is allowed, we need to extend the price
		 * and the duration by the number of selected slots.
		 *
		 * @since 1.6
		 */
		if ($service->checkout_selection)
		{
			// fetch selected factor
			$factor = isset($data['factor']) ? (int) $data['factor'] : 1;
		}
		else
		{
			$factor = 1;
		}

		// get cart instance
		$cart = $this->getCart();
		
		// create new cart item
		$item = new VAPCartItem(
			$service->id,
			$data['id_employee'] > 0 ? $employee->id : -1,
			$service->name,
			$data['id_employee'] > 0 ? $employee->nickname : '',
			$service->price,
			$service->duration,
			$data['checkin'],
			$data['people']
		);

		// set factor in case of check-out selection
		$item->setFactor($factor);

		// get currently logged-in customer
		$customer = VikAppointments::getCustomer();

		// check whether the customer is logged-in and it is subscribed for the
		// booked service and the selected check-in
		if ($customer && $customer->isSubscribed($service->id, $data['checkin']))
		{
			// the customer owns a subscription, unset the service price
			$item->setPrice(0);
		}
		else if ($config->getBool('subscrmandatory'))
		{
			// a subscription is mandatory, do not allow the purchase of this service
			if (VikAppointments::isUserLogged())
			{
				// customer logged-in with an expired (or missing) subscription
				$link = JRoute::rewrite('index.php?option=com_vikappointments&view=subscriptions');
				$this->setError(JText::sprintf('VAPSUBSCRREQERR', $link));
			}
			else
			{
				// login needed in order to check the subscription plan
				$this->setError(JText::translate('VAPSUBSCRREQERRGUEST'));
			}

			return false;
		}

		// get options model
		$optModel = JModelVAP::getInstance('option');
		
		// validate specified options
		foreach ($data['options'] as $opt)
		{
			$pk = array(
				'id'           => $opt['id'],
				'published'    => 1,
				'id_variation' => $opt['variation'],
			);

			// get option details
			$option = $optModel->getItem($pk);

			if (!$option || !$optModel->exists($opt['id'], $service->id))
			{
				// option not found or not assigned to the given service
				continue;
			}

			/**
			 * Check whether the maximum quantity varies according to the
			 * number of selected participants.
			 * 
			 * @since 1.7
			 */
			if ($option->maxqpeople && $option->single)
			{
				// use current number of participants as maximum amount
				$option->maxq = $data['people'];

				/**
				 * Force the quantity in case the option was configured to be
				 * equal to the selected number of participants
				 * 
				 * @since 1.7.4
				 */
				if ($option->maxqpeople == 2)
				{
					$opt['quantity'] = $option->maxq;
				}
			}

			// fetch quantity
			$option->quantity = min(array(intval($opt['quantity']), $option->maxq));
			$option->quantity = max(array(1, $option->quantity));

			if ($option->variations)
			{
				// add variation name to option
				$option->name .= ' - ' . $option->variations[0]->name;
				// increase price by the variation cost
				$option->price += $option->variations[0]->inc_price;
				// increase duration
				$option->duration += $option->variations[0]->inc_duration;
			}
			
			// create option instance
			$option = new VAPCartOption(
				$option->id,
				$opt['variation'],
				$option->name,
				$option->price,
				$option->maxq,
				$option->required,
				$option->quantity,
				$option->duration
			);

			/**
			 * Trigger event before adding an option to the cart item.
			 *
			 * @param 	mixed 	 $item 		The cart item object.
			 * @param 	mixed 	 &$option 	The item option object.
			 *
			 * @return 	boolean  False to avoid adding the option.
			 *
			 * @since 	1.6
			 */
			if (!$dispatcher->not('onAddOptionCart', array($item, &$option)))
			{
				// push option in case no plugin was triggered
				// or in case we got only positive results
				$item->addOption($option);
			}
		}

		// get reservation model
		$reservationModel = JModelVAP::getInstance('reservation');

		// build availability query
		$query = [
			'id_service'  => $item->getServiceID(),
			'id_employee' => $item->getEmployeeID() > 0 ? $item->getEmployeeID() : 0,
			'duration'    => $item->getDuration(),
			'sleep'       => $service->sleep,
			'people'      => $item->getPeople(),
			'checkin_ts'  => $item->getCheckinDate(),
		];

		// check availability
		if (!$reservationModel->isAvailable($query))
		{
			// the selected appointment is no longer available
			$this->setError(JText::translate('VAPCARTITEMADDERR3'));

			return false;
		}

		// junk variable used by plugins to set custom errors
		$err = '';

		/**
		 * Trigger event before adding an item into the cart.
		 *
		 * @param 	mixed 	 $cart 	 The cart instance.
		 * @param 	mixed 	 &$item  The cart item object.
		 * @param 	string 	 &$err 	 String used to raise custom errors.
		 *
		 * @return 	boolean  False to avoid adding the item.
		 *
		 * @since 	1.6
		 */
		if ($dispatcher->not('onAddItemCart', array($cart, &$item, &$err)))
		{
			// Avoid pushing the item into the cart in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the item will be added correctly.
			$this->setError($err ? $err : JText::translate('ERROR'));
			return false;
		}
		
		// push item into the cart
		$res = $cart->addItem($item);
		
		if (!$res)
		{
			if (!VikAppointments::canAddItemToCart($cart->getCartLength()))
			{
				// limit reached
				$this->setError(JText::translate('VAPCARTITEMADDERR1'));
			}
			else
			{
				// service already in cart
				$this->setError(JText::translate('VAPCARTITEMADDERR2'));
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
			if (VikAppointments::isUserLogged())
			{
				// not enough packages to redeem
				$link = JRoute::rewrite('index.php?option=com_vikappointments&view=packages');
				$this->setError(JText::sprintf('VAPPACKAGEREQERR', $link));
			}
			else
			{
				// login needed in order to count the packages
				$this->setError(JText::translate('VAPPACKAGEREQERRGUEST'));
			}

			return false;
		}

		// revalidate coupon code
		$this->revalidateCoupon();
		
		// save cart data
		$cart->store();
		
		return $item;
	}

	/**
	 * Removes the matching appointment from the cart.
	 *
	 * @param 	mixed    $data  Either an array or an object holding
	 *                          the appointment details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeItem($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();

		// validate selected employee
		$data['id_employee'] = isset($data['id_employee']) ? (int) $data['id_employee'] : 0;

		// load service details according to the overrides of the specified employee (if any)
		$service = JModelVAP::getInstance('serempassoc')->getOverrides($data['id_service'], $data['id_employee']);

		if (!$service)
		{
			// the service (or the relation with the employee) doesn't exist
			$this->setError(JText::translate('VAPSERNOTFOUNDERROR'));
			return false;
		}
		
		// get cart handler
		$cart = $this->getCart();

		/**
		 * Trigger event before deleting an item from the cart.
		 *
		 * @param 	mixed    $cart         The cart instance.
		 * @param 	integer  $id_service   The service ID.
		 * @param 	integer  $id_employee  The employee ID.
		 * @param 	string   $checkin      The check-in date time (UTC).
		 *
		 * @return 	boolean  False to avoid deleting the item.
		 *
		 * @since 	1.6
		 */
		if ($dispatcher->not('onRemoveItemCart', array($cart, $data['id_service'], $data['id_employee'], $data['checkin'])))
		{
			// Avoid deleting the item into the cart in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the item will be removed correctly.

			$this->setError(JText::translate('VAPCARTITEMDELERR'));
			return false;
		}
		
		// try to delete the item
		$res = $cart->removeItem($data['id_service'], $data['id_employee'], $data['checkin']);
		
		// check item removed
		
		if (!$res)
		{
			$this->setError(JText::translate('VAPCARTITEMDELERR'));
			return false;
		}

		// revalidate coupon code
		$this->revalidateCoupon();

		// apply the changes
		$cart->store();

		return true;
	}

	/**
	 * Removes all the appointments from the cart.
	 *
	 * @return 	void
	 */
	public function emptyCart()
	{
		$dispatcher = VAPFactory::getEventDispatcher();
		
		// get cart handler
		$cart = $this->getCart();

		/**
		 * Trigger event before flushing the cart.
		 *
		 * @param 	mixed  $cart  The cart instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.6
		 */
		$dispatcher->not('onEmptyCart', array($cart));
		
		// flush the cart
		$cart->emptyCart();

		// revalidate coupon code
		$this->revalidateCoupon();
		
		// apply the changes
		$cart->store();
	}

	/**
	 * Increases the units of the specified option.
	 *
	 * @param 	mixed    $data  Either an array or an object holding
	 *                          the appointment details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function addOption($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();

		// get cart handler
		$cart = $this->getCart();

		// validate selected employee
		$data['id_employee'] = isset($data['id_employee']) ? (int) $data['id_employee'] : 0;
		
		// find the item matching the appointment
		$index = $cart->indexOf($data['id_service'], $data['id_employee'], $data['checkin']);
		$item  = $cart->getItemAt($index);

		if (!$item)
		{
			// item not found
			$this->setError(JText::translate('VAPCARTOPTADDERR1'));
			return false;
		}

		// get the specified option from the item
		$option = $item->getOptionAt($item->indexOf($data['id_option']));
		
		if (!$option)
		{
			// option not found
			$this->setError(JText::translate('VAPCARTOPTADDERR1'));
			return false;
		}

		/**
		 * Trigger event before adding an option to the cart item.
		 *
		 * @param 	mixed 	 $item 		The cart item object.
		 * @param 	mixed 	 &$option 	The item option object.
		 *
		 * @return 	boolean  False to avoid adding the option.
		 *
		 * @since 	1.6
		 */
		if ($dispatcher->not('onAddOptionCart', array($item, &$option)))
		{
			// Avoid adding the option into the item in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the option will be added correctly.

			$this->setError(JText::translate('VAPCARTOPTADDERR1'));
			return false;
		}

		// get current quantity
		$qty = $option->getQuantity();
		// increase by the specified units (1 by default)
		$option->add(isset($data['units']) ? $data['units'] : 1);

		// check whether something has changed
		if ($qty == $option->getQuantity())
		{
			// the maximum quantity was reached
			$this->setError(JText::translate('VAPOPTIONMAXQUANTITYNOTICE'));
			return false;
		}

		// revalidate coupon code
		$this->revalidateCoupon();

		// save changes
		$cart->store();

		return true;
	}

	/**
	 * Decreases the units of the specified option.
	 *
	 * @param 	mixed    $data  Either an array or an object holding
	 *                          the appointment details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function removeOption($data)
	{
		$data = (array) $data;

		$dispatcher = VAPFactory::getEventDispatcher();

		// get cart handler
		$cart = $this->getCart();

		// validate selected employee
		$data['id_employee'] = isset($data['id_employee']) ? (int) $data['id_employee'] : 0;
		
		// find the item matching the appointment
		$index = $cart->indexOf($data['id_service'], $data['id_employee'], $data['checkin']);
		$item  = $cart->getItemAt($index);

		if (!$item)
		{
			// item not found
			$this->setError(JText::translate('VAPCARTOPTADDERR1'));
			return false;
		}

		/**
		 * Trigger event before detaching an option from the item.
		 *
		 * @param 	integer  $id_option  The option ID.
		 * @param 	mixed 	 $item       The cart item instance.
		 *
		 * @return 	boolean  False to avoid detaching the option.
		 *
		 * @since 	1.6
		 */
		if ($dispatcher->not('onRemoveOptionCart', array($data['id_option'], $item)))
		{
			// Avoid detaching the option from the item in case at least a plugin
			// returns a negative value. If no plugin is attached to this event,
			// the option will be detached correctly.

			$this->setError(JText::translate('VAPCARTOPTDELERR'));
			return false;
		}

		// validate units to decrease
		$units = isset($data['units']) ? $data['units'] : 1;
		// try to delete the option
		$res = $item->removeOption($data['id_option'], $units);

		if (!$res)
		{
			// unable to delete the option
			$this->setError(JText::translate('VAPCARTOPTDELERR'));
			return false;
		}

		// revalidate coupon code
		$this->revalidateCoupon();

		// save changes
		$cart->store();

		return true;
	}

	/**
	 * Registers a new appointment with recurrence within the cart.
	 *
	 * @param 	mixed  $data        Either an array or an object holding the
	 *                              appointment details.
	 * @param 	array  $recurrence  An array containing the recurrence roles.
	 */
	public function addRecurringItem($data, $recurrence)
	{
		$data = (array) $data;

		// get recurrence model
		$model = JModelVAP::getInstance('makerecurrence');

		$data['id_employee'] = isset($data['id_employee']) ? $data['id_employee'] : 0;

		// get employee timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($data['id_employee']);

		// check if we have to build the check-in date
		if (isset($data['date']))
		{
			// create date according to the employee timezone
			$dt = new JDate("{$data['date']} {$data['hour']}:{$data['min']}:00", $tz);
			// adjust date to UTC
			$data['checkin'] = $dt->format('Y-m-d H:i:s');
		}

		// adjust recurrence date to the employee/system timezone because
		// DST might change over time
		$dt = new JDate($data['checkin']);
		$dt->setTimezone(new DateTimeZone($tz));

		// compose dates recurrence
		$arr = $model->getRecurrence($dt, $recurrence);
		
		if (!$arr)
		{
			// invalid recurrence
			$this->setError(JText::translate('VAPMAKERECNOROWS'));
			return false;
		}

		// include selected check-in within the list
		array_unshift($arr, $data['checkin']);

		$results = array();

		// iterate all dates found
		foreach ($arr as $date)
		{
			$tmp = array();
			// format check-in date for response
			$tmp['date'] = JHtml::fetch('date', $date, JText::translate('DATE_FORMAT_LC2'), VikAppointments::getUserTimezone()->getName());
			// set initial status
			$tmp['status'] = 0;

			// make sure we haven't reached the maximum size
			if (!VikAppointments::canAddItemToCart($this->getCart()->getCartLength()))
			{
				// max cart length reached
				$tmp['error'] = JText::sprintf('VAPCARTRECURITEMERR1', $tmp['date']);

				$results[] = $tmp;

				// go to the next date
				continue;
			}

			$data['checkin'] = $date;

			// try to add the item into the cart
			$item = $this->addItem($data);

			if (!$item)
			{
				// get registered error message
				$error = $this->getError($index = null, $string = true);

				if ($error == JText::translate('VAPCARTITEMADDERR2'))
				{
					// item already in cart
					$tmp['error'] = JText::sprintf('VAPCARTRECURITEMERR2', $tmp['date']);
				}
				else if ($error == JText::translate('VAPCARTITEMADDERR3'))
				{
					// item no longer available
					$tmp['error'] = JText::sprintf('VAPCARTRECURITEMERR3', $tmp['date']);
				}
				else
				{
					// use the specified error
					$tmp['error'] = $error;
				}

				$results[] = $tmp;

				// go to the next date
				continue;
			}

			// item added successfully
			$tmp['status'] = 1;
			$tmp['item']   = $item->toArray();

			// register result
			$results[] = $tmp;
		}

		return $results;
	}

	/**
	 * Validates the items inside the cart and makes sure they are
	 * still available for booking, by checking whether the selected
	 * slots have been already booked by other customers or by checkin
	 * whether the selected check-in is still in the future.
	 *
	 * @param 	array    &$errors     An argument to be passed as reference,
	 *                                which will be filled with all the fetched
	 *                                error messages.
	 * @param 	array    &$employees  An array containing all the employees
	 *                                that have been assigned to the appointments
	 *                                registered into the cart.
	 *
	 * @return 	boolean  True on success, false in case of invalid items.
	 */
	public function checkIntegrity(&$errors = null, &$employees = array())
	{
		// get cart handler
		$cart = $this->getCart();

		// get reservation model
		$model = JModelVAP::getInstance('reservation');

		$errors = array();

		// temporary flag used to check whether the same employee has been already assigned
		// to a different booking at the same date and time
		$lookup = array();
		
		foreach ($cart->getItemsList() as $k => $item) 
		{
			// get item data
			$data = $item->toArray();

			// replicate check-in into a different attribute that will be used
			// by the reservation model to check the availability (UTC)
			$data['checkin_ts'] = $data['checkin'];

			if (isset($lookup[$data['checkin_ts']]))
			{
				/**
				 * When the cart allows the selection of concurrent check-ins, we need
				 * to make sure that the appointments at the same date time are not
				 * assigned to the same employee, causing an unexpected overbooking.
				 * For this reason, we need to track all the employees that have been
				 * currently assigned and for which dates. This way, we are able to
				 * avoid validating certain employees in case of colliding slots.
				 *
				 * Instruct the availability search to skip all the employees that have
				 * been currently assigned for the same date and time.
				 *
				 * @since 1.7
				 */
				$data['exclude_employees'] = $lookup[$data['checkin_ts']];
			}

			// fetch overrides for the selected service-employee relation
			$service = JModelVAP::getInstance('serempassoc')->getOverrides($data['id_service'], $data['id_employee']);

			if ($service)
			{
				// always register the sleep time to estimate a correct availability
				$data['sleep'] = $service->sleep;
			}

			// check whether the item is still available
			$avail = $model->isAvailable($data);

			if ($avail)
			{
				// assign matching employee
				$employees[$k] = $avail === true ? $item->getEmployeeID() : (int) $avail;

				if (!isset($lookup[$data['checkin_ts']]))
				{
					// create check-in lookup
					$lookup[$data['checkin_ts']] = array();
				}

				// assign employee to check-in time
				$lookup[$data['checkin_ts']][] = $employees[$k];
			}
			else
			{
				// register error
				$errors[] = array(
					'item'   => $item,
					'reason' => $model->getError($index = null, $string = true),
				);

				// remove item from the list
				$cart->removeItem($item->getServiceID(), $item->getEmployeeID(), $item->getCheckinDate());
			}
		}

		if (!$errors)
		{
			// no faced errors
			return true;
		}

		// revalidate coupon code
		$this->revalidateCoupon();

		// save changes
		$cart->store();

		// return the list of invalid items
		return false;
	}

	/**
	 * Helper method used to redeem the specified coupon code.
	 *
	 * @param 	mixed 	 $coupon  Either the coupon details or its code.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function redeemCoupon($coupon)
	{
		if (empty($coupon))
		{
			// coupon code not specified
			$this->setError(JText::translate('VAPCOUPONNOTVALID'));
			return false;
		}

		if (is_string($coupon))
		{
			// get model to load coupon details
			$couponModel = JModelVAP::getInstance('coupon');
			$coupon = $couponModel->getCoupon($coupon);

			if (!$coupon)
			{
				// coupon not found in database
				$this->setError(JText::translate('VAPCOUPONNOTVALID'));
				return false;
			}
		}

		// get cart instance
		$cart = $this->getCart();

		// validate the coupon code
		if (!VikAppointments::validateCoupon($coupon, $cart))
		{
			// cannot apply the coupon code
			$this->setError(JText::translate('VAPCOUPONNOTVALID'));
			return false;
		}

		// coupon valid, create discount object
		$discount = new VAPCartDiscount('coupon', $coupon->value, $coupon->percentot == 1);
		// register coupon data for later use
		$discount->set('couponData', $coupon);

		// apply discount, by replacing any other coupon discount previously set
		$cart->setDiscount($discount);
		// commit cart changes
		$cart->store();

		return true;
	}

	/**
	 * Revalidates the internal coupon code, since the cart might be no more
	 * compliant with the coupon restrictions after some changes.
	 *
	 * @param 	boolean  $store  True to commit the changes.
	 *
	 * @return 	boolean  True in case of valid coupon, false otherwise.
	 */
	public function revalidateCoupon($store = false)
	{
		// get cart instance
		$cart = $this->getCart();
		// get coupon discount, if any
		$discount = $cart->getDiscount('coupon');

		if (!$discount)
		{
			// coupon discount not set
			return false;
		}

		// extract coupon data
		$coupon = $discount->get('couponData');

		// try to redeem the coupon code
		$res = $this->redeemCoupon($coupon);

		if (!$res)
		{
			// coupon no more valid, unset it
			$cart->removeDiscount($discount);

			if ($store)
			{
				// commit changes
				$cart->store();
			}
			
			return false;
		}

		// coupon still valid
		return true;
	}

	/**
	 * Helper method used to obtain an instance of the cart.
	 *
	 * @return 	VAPCart
	 */
	public function getCart()
	{
		static $cart = null;

		if (!$cart)
		{
			// load cart instance
			$cart = VAPCart::getInstance();

			$config = VAPFactory::getConfig();

			// set cart configuration
			$cart->setParams(array( 
				VAPCart::CART_ENABLED => $config->getBool('enablecart'),
				VAPCart::MAX_SIZE     => $config->getInt('maxcartsize'),
				VAPCart::ALLOW_SYNC   => $config->getBool('cartallowsync'),
			));
		}

		return $cart;
	}

	/**
	 * Helper method used to make sure all the required options have been selected.
	 *
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	array 	 $options 	An array containing the selected options.
	 * 
	 * @return 	array 	 The list of the required options that haven't been selected, otherwise false.
	 */
	protected function validateOptions($id_ser, $options)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('o.id'))
			->from($dbo->qn('#__vikappointments_option', 'o'))
			->leftjoin($dbo->qn('#__vikappointments_ser_opt_assoc', 'ao') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('ao.id_option'))
			->where(array(
				$dbo->qn('ao.id_service') . ' = ' . (int) $id_ser,
				$dbo->qn('o.required') . ' = 1',
				$dbo->qn('o.published') . ' = 1',
			));

		/**
		 * Retrieve only the options that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.7.3
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('o.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		if (count($options))
		{
			$options = array_map(function($op)
			{
				return (int) $op['id'];
			}, $options);

			$q->where($dbo->qn('o.id') . ' NOT IN (' . implode(',', $options) . ')');
		}
		
		$dbo->setQuery($q);

		// In case the query returned some rows, they are the required options that the customer
		// haven't specified. False indicates that all the required options have been filled in.
		return $dbo->loadColumn() ?: false;
	}
}

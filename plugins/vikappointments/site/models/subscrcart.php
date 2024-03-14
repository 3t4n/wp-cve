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
VAPLoader::import('libraries.models.subscriptions');
VAPLoader::import('libraries.cart.discount');

/**
 * VikAppointments subscription cart model.
 *
 * @since 1.7
 */
class VikAppointmentsModelSubscrcart extends JModelVAP
{
	/**
	 * An associative array holding the subscription details.
	 *
	 * @var array
	 */
	private $subscription = null;

	/**
	 * An associative array holding the payment details.
	 *
	 * @var array
	 */
	private $payment = null;

	/**
	 * A list of applied discounts.
	 *
	 * @var VAPCartDiscount[]
	 */
	private $discounts = null;

	/**
	 * An optional suffix to be included within the session keys.
	 *
	 * @var string
	 */
	protected $suffix = '';

	/**
	 * Loads all the subscriptions available for the current user.
	 *
	 * @return 	array
	 */
	public function getAllSubscriptions()
	{
		static $subscriptions = null;

		// load subscriptions only once
		if (is_null($subscriptions))
		{
			// load all the active subscriptions (ignore TRIAL, if any)
			$subscriptions = VAPSubscriptions::getList();

			// flag used to include the trial version
			$trial = VAPSubscriptions::getTrial();

			if (VikAppointments::isUserLogged())
			{
				// get customer details of currently logged-in user
				$user = VikAppointments::getCustomer();

				// make sure the user never subscribed before
				if ($user && !VAPDateHelper::isNull($user->active_since))
				{
					// nope, the user cannot benefit of any TRIAL
					$trial = false;
				}
			}

			if ($trial)
			{
				// fetch trial subscription and add it as first element
				array_unshift($subscriptions, $trial);
			}
		}

		return $subscriptions;
	}

	/**
	 * Loads the details of the selected subscription plan.
	 *
	 * @return 	array
	 */
	public function getSubscription()
	{
		// load selected subscription only once
		if (is_null($this->subscription))
		{
			$input = JFactory::getApplication()->input;

			// load selected subscription (from cookie)
			$id_subscr = $input->cookie->getUint('vikappointments_subscr' . $this->suffix . '_id', null);

			if ($id_subscr)
			{
				// try to load the details of the selected subscription
				$this->subscription = $this->fetchSubscription($id_subscr);
			}

			if (!$this->subscription)
			{
				$all = $this->getAllSubscriptions();

				if (!$all)
				{
					// No available subscriptions...
					// Throw an exception to prevent unexpected behaviors
					throw new RuntimeException('Unsupported subscriptions', 500);
				}

				// auto-select the first available subscription
				$this->subscription = $all[0];
			}
		}

		return $this->subscription;
	}

	/**
	 * Helper method used to fetch the subscription data.
	 * Children classes can override this method to fetch
	 * the subscription data in a different way.
	 *
	 * @param 	integer  $id_subscr  The subscription ID.
	 *
	 * @return 	array
	 */
	protected function fetchSubscription($id_subscr)
	{
		$subscr = VAPSubscriptions::get($id_subscr);

		if ($subscr['trial'])
		{
			// do not auto-select the trial offer because it might not be
			// active for this user
			return null;
		}

		return $subscr;
	}

	/**
	 * Subscription plan setter.
	 *
	 * @param 	integer  $id  The subscription plan to use.
	 *
	 * @return 	self     This object to support chaining.
	 */
	public function setSubscription($id)
	{
		// change subscription plan by overriding the cookie
		$input = JFactory::getApplication()->input;
		$input->cookie->set('vikappointments_subscr' . $this->suffix . '_id', (int) $id);

		// unset cached property to refresh the internal contents
		// at the next getter access
		$this->subscription = null;

		return $this;
	}

	/**
	 * Loads all the payment methods available for the current user.
	 *
	 * @return 	array
	 */
	public function getPaymentMethods()
	{
		static $payments = null;

		// load payments only once
		if (is_null($payments))
		{
			// load payments and translate them
			$payments = VikAppointments::getPayments($this->suffix . 'subscriptions');
			VikAppointments::translatePayments($payments);
		}

		return $payments;
	}

	/**
	 * Loads the details of the selected payment method.
	 *
	 * @return 	array|null
	 */
	public function getPayment()
	{
		// load selected payment method only once
		if (is_null($this->payment))
		{
			$input = JFactory::getApplication()->input;

			// load selected payment method (from cookie)
			$id_payment = $input->cookie->getUint('vikappointments_subscr' . $this->suffix . '_payment', null);

			$all = $this->getPaymentMethods();

			if ($id_payment)
			{
				for ($i = 0; $i < count($all) && !$this->payment; $i++)
				{
					if ($all[$i]['id'] == $id_payment)
					{
						// payment found
						$this->payment = $all[$i];
					}
				}
			}

			if (!$this->payment && $all)
			{
				// auto-select the first available payment, if any
				$this->payment = $all[0];
			}
		}

		return $this->payment;
	}

	/**
	 * Payment method setter.
	 *
	 * @param 	integer  $id  The payment method to use.
	 *
	 * @return 	self     This object to support chaining.
	 */
	public function setPayment($id)
	{
		// change payment method by overriding the cookie
		$input = JFactory::getApplication()->input;
		$input->cookie->set('vikappointments_subscr' . $this->suffix . '_payment', (int) $id);

		// unset cached property to refresh the internal contents
		// at the next getter access
		$this->payment = null;

		return $this;
	}

	/**
	 * Calculates the totals for the specified subscription and payment method.
	 *
	 * @return 	object
	 */
	public function getTotals()
	{
		VAPLoader::import('libraries.tax.factory');

		$options = array();
		$options['lang']   = JFactory::getLanguage()->getTag();
		$options['suffix'] = $this->suffix ?: 'cust';
		// $options['id_user'] = JFactory::getUser()->id;

		// always revalidate coupon code
		$this->revalidateCoupon();

		// get subscription details
		$subscr = $this->getSubscription();

		// get subscription base amount
		$amount = $subscr['price'];

		// get selected payment, if any
		$payment = $this->getPayment();

		if ($payment && $payment['charge'] < 0)
		{
			// treat payment charge as a discount
			$this->setDiscount(new VAPCartDiscount('payment', $payment['charge']));
		}
		else
		{
			// alternatively remove the payment discount, if was registered
			$this->removeDiscount('payment');
		}

		foreach ($this->getDiscounts() as $discount)
		{
			// reset internal index
			$discount->set('count', 0);
			// reset internal total discount
			$discount->set('disctot', 0);
			// set total number of itemd (only 1)
			$discount->set('length', 1);
			// register the base cost of the subscription
			$discount->set('total', $amount);

			// apply discount
			$amount = $discount->apply($amount, $subscr['price'], $subscr);
		}

		// calculate taxes of subscription
		$result = VAPTaxFactory::calculate($subscr['id_tax'], $amount, $options);

		// calculate total discount
		$result->discount = $subscr['price'] - $amount;

		// ignore payment selection in case the subscription plan has no cost
		if ($result->gross && $payment && $payment['charge'] > 0)
		{
			// calculate taxes of payment method
			$pay = VAPTaxFactory::calculate($payment['id_tax'], $payment['charge'], $options);

			$result->payment = $pay;

			// sum totals to subscription
			$result->gross += $pay->gross;
			$result->net   += $pay->net;
			$result->tax   += $pay->tax;

			// merge breakdowns
			$result->breakdown = array_merge($result->breakdown, $pay->breakdown);
		}

		return $result;
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

		// validate the coupon code
		if (!VikAppointments::validateSubscriptionsCoupon($coupon, $this))
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
		$this->setDiscount($discount);

		return true;
	}

	/**
	 * Revalidates the internal coupon code, since the cart might be no more
	 * compliant with the coupon restrictions after some changes.
	 *
	 * @return 	boolean  True in case of valid coupon, false otherwise.
	 */
	public function revalidateCoupon()
	{
		// get coupon discount, if any
		$discount = $this->getDiscount('coupon');

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
			$this->removeDiscount($discount);
			
			return false;
		}

		// coupon still valid
		return true;
	}

	/**
	 * Registers a new discount within the cart.
	 *
	 * @param 	VAPCartDiscount  $discount  The discount to apply.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function addDiscount(VAPCartDiscount $discount)
	{
		// load discounts first
		$this->getDiscounts();

		// add discount element
		$this->discounts[] = $discount;

		// commit changes
		$this->saveDiscounts();

		return $this;
	}

	/**
	 * Removes a discount from the cart, if any.
	 *
	 * @param 	mixed  $discount  Either the discount ID or an object.
	 *
	 * @return 	mixed  The deleted discount on success, false otherwise.
	 */
	public function removeDiscount($discount)
	{
		// load discounts first
		$this->getDiscounts();

		foreach ($this->discounts as $i => $elem)
		{
			if ($elem === $discount || (is_scalar($discount) && $elem->getID() == $discount)
				|| ($discount instanceof VAPCartDiscount && $discount->getID() == $elem->getID()))
			{
				// remove from array
				array_splice($this->discounts, $i, 1);

				// commit changes
				$this->saveDiscounts();

				return $elem;
			}
		}

		return false;
	}

	/**
	 * Sets a discount within the cart. In case the same discount
	 * is already set into the cart, the old one will be replaced
	 * by the new one.
	 *
	 * @param 	VAPCartDiscount  $discount  The discount to apply.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function setDiscount(VAPCartDiscount $discount)
	{
		// remove discount first
		$this->removeDiscount($discount);

		// then add new discount element
		$this->discounts[] = $discount;

		// commit changes
		$this->saveDiscounts();

		return $this;
	}

	/**
	 * Returns the discount matching the specified code.
	 *
	 * @param 	mixed  $discount  Either the discount ID or an object.
	 *
	 * @return 	mixed  The discount object on success, null otherwise.
	 */
	public function getDiscount($discount)
	{
		// load discounts first
		$this->getDiscounts();

		foreach ($this->discounts as $i => $elem)
		{
			if ((is_scalar($discount) && $elem->getID() == $discount)
				|| ($discount instanceof VAPCartDiscount && $discount->getID() == $elem->getID()))
			{
				return $elem;
			}
		}

		return null;
	}

	/**
	 * Returns the list containing all the discounts.
	 *
	 * @return 	array
	 */
	public function getDiscounts()
	{
		// fetch discounts from session only once
		if (is_null($this->discounts))
		{
			// load from session
			$list = JFactory::getSession()->get('vap.subscr' . $this->suffix . '.discounts', null);

			if ($list)
			{
				$this->discounts = unserialize($list);
			}
			else
			{
				$this->discounts = array();
			}
		}

		return $this->discounts;
	}

	/**
	 * Empties the current cart items.
	 *
	 * @return 	void
	 */
	public function emptyCart()
	{
		// unset subscription
		$this->setSubscription(null);
		// unset payment method
		$this->setPayment(null);

		// clear applied discounts
		$this->discounts = [];
		$this->saveDiscounts();
	}

	/**
	 * Helper method used to save the registered discounts within the
	 * user session.
	 *
	 * @return 	void
	 */
	protected function saveDiscounts()
	{
		JFactory::getSession()->set('vap.subscr' . $this->suffix . '.discounts', serialize($this->getDiscounts()));
	}
}

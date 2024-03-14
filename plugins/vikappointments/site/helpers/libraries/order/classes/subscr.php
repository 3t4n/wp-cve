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

VAPLoader::import('libraries.order.wrapper');

/**
 * Customer subscription order class wrapper.
 *
 * @since 1.7
 */
class VAPOrderSubscr extends VAPOrderWrapper
{	
	/**
	 * @override
	 * Returns the subscription order object.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 *
	 * @return 	mixed    The array/object to load.
	 *
	 * @throws 	Exception
	 */
	protected function load($id, $langtag = null, array $options = array())
	{
		$dbo        = JFactory::getDbo();
		$config     = VAPFactory::getConfig();
		$dispatcher = VAPFactory::getEventDispatcher();

		// create query
		$q = $dbo->getQuery(true);

		// select all order columns
		$q->select('o.*');
		$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));

		// select payment details
		$q->select($dbo->qn('gp.name', 'payment_name'));
		$q->select($dbo->qn('gp.file', 'payment_file'));
		$q->select($dbo->qn('gp.note', 'payment_note'));
		$q->select($dbo->qn('gp.prenote', 'payment_prenote'));
		$q->select($dbo->qn('gp.icontype', 'payment_icontype'));
		$q->select($dbo->qn('gp.icon', 'payment_icon'));
		$q->leftjoin($dbo->qn('#__vikappointments_gpayments', 'gp') . ' ON ' . $dbo->qn('o.id_payment') . ' = ' . $dbo->qn('gp.id'));

		// select purchased subscription
		$q->select($dbo->qn('s.id', 'subscr_id'));
		$q->select($dbo->qn('s.name', 'subscr_name'));
		$q->select($dbo->qn('s.amount', 'subscr_amount'));
		$q->select($dbo->qn('s.type', 'subscr_type'));
		$q->select($dbo->qn('s.price', 'subscr_price'));
		$q->select($dbo->qn('s.trial', 'subscr_trial'));
		$q->leftjoin($dbo->qn('#__vikappointments_subscription', 's') . ' ON ' . $dbo->qn('o.id_subscr') . ' = ' . $dbo->qn('s.id'));

		// filter by order key, if specified
		if (isset($options['sid']))
		{
			$q->where($dbo->qn('o.sid') . ' = ' . $dbo->q($options['sid']));
		}

		// load order matching the specified ID
		$q->where($dbo->qn('o.id') . ' = ' . (int) $id);

		/**
		 * External plugins can attach to this hook in order to manipulate
		 * the query at runtime, in example to alter the default ordering.
		 *
		 * @param 	mixed    &$query   A query builder instance.
		 * @param 	integer  $id       The ID of the order.
		 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
		 * @param 	array 	 $options  An array of options to be passed to the order instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onLoadCustomerSubscriptionOrderDetails', array(&$q, $id, $langtag, $options));

		$dbo->setQuery($q, 0, 1);
		
		// create parent order details
		$order = $dbo->loadObject();

		if (!$order)
		{
			// order not found raise error
			throw new Exception(sprintf('Order [%d] not found', $id), 404);
		}

		$order->subscription = new stdClass;
		$order->subscription->id     = $this->detach($order, 'subscr_id');
		$order->subscription->name   = $this->detach($order, 'subscr_name');
		$order->subscription->amount = $this->detach($order, 'subscr_amount');
		$order->subscription->type   = $this->detach($order, 'subscr_type');
		$order->subscription->price  = $this->detach($order, 'subscr_price');
		$order->subscription->trial  = $this->detach($order, 'subscr_trial');

		// fetch coupon
		if ($order->coupon)
		{
			list($code, $type, $amount) = explode(';;', $this->detach($order, 'coupon'));

			$order->coupon = new stdClass;
			$order->coupon->code   = $code;
			$order->coupon->amount = $amount;
			$order->coupon->type   = $type;
		}
		else
		{
			$order->coupon = null;
			$this->detach($order, 'coupon');
		}

		// fetch payment data
		if ($order->payment_file)
		{
			$order->payment = new stdClass;
			$order->payment->id       = $this->detach($order, 'id_payment');
			$order->payment->name     = $this->detach($order, 'payment_name');
			$order->payment->driver   = $this->detach($order, 'payment_file');
			$order->payment->iconType = $this->detach($order, 'payment_icontype');
			$order->payment->icon     = $this->detach($order, 'payment_icon');

			if ($order->payment->iconType == 1)
			{
				// Font Icon
				$order->payment->fontIcon = $order->payment->icon;
			}
			else
			{
				// Image Icon
				$order->payment->iconURI = JUri::root() . $order->payment->icon;

				// fetch Font Icon based on payment driver
				switch ($order->payment->driver)
				{
					case 'bank_transfer.php':
						$order->payment->fontIcon = 'fas fa-money-bill';
						break;

					case 'paypal.php':
						$order->payment->fontIcon = 'fab fa-paypal';
						break;

					default:
						$order->payment->fontIcon = 'fas fa-credit-card';
				}
			}

			$order->payment->notes = new stdClass;
			$order->payment->notes->beforePurchase = $this->detach($order, 'payment_prenote');
			$order->payment->notes->afterPurchase  = $this->detach($order, 'payment_note');
		}
		else
		{
			$order->payment = null;
		}

		// setup totals
		$order->totals = new stdClass;
		$order->totals->net       = $this->detach($order, 'total_net');
		$order->totals->tax       = $this->detach($order, 'total_tax');
		$order->totals->gross     = $this->detach($order, 'total_cost');
		$order->totals->discount  = $this->detach($order, 'discount');
		$order->totals->paid      = $this->detach($order, 'tot_paid');
		$order->totals->payCharge = $this->detach($order, 'payment_charge');
		$order->totals->payTax    = $this->detach($order, 'payment_tax');
		$order->totals->due       = $order->totals->gross - $order->totals->paid;

		// fetch paid flag based on current order status
		$order->paid = JHtml::fetch('vaphtml.status.ispaid', 'subscriptions', $order->status);

		if ($order->paid)
		{
			// amount paid, no remaining balance
			$order->totals->due = 0;
		}

		$order->statusRole = null;

		// fetch status role
		if (JHtml::fetch('vaphtml.status.ispending', 'subscriptions', $order->status))
		{
			$order->statusRole = 'PENDING';
		}
		else if (JHtml::fetch('vaphtml.status.isapproved', 'subscriptions', $order->status))
		{
			$order->statusRole = 'APPROVED';
		}
		else if (JHtml::fetch('vaphtml.status.iscancelled', 'subscriptions', $order->status))
		{
			$order->statusRole = 'CANCELLED';
		}

		/**
		 * External plugins can use this event to manipulate the object holding
		 * the details of the order. Useful to inject all the additional data
		 * fetched with the manipulation of the query.
		 *
		 * @param 	mixed  $order  The order details object.
		 *
		 * @return 	void
		 */
		$dispatcher->trigger('onSetupCustomerSubscriptionOrderDetails', array($order));

		$unsetList = array(
			'id_payment'
		);

		// get rid of not needed properties
		foreach (get_object_vars($order) as $k => $v)
		{
			if (preg_match("/^(payment)_/", $k))
			{
				// get rid of blank payment
				unset($order->{$k});
			}
			else if (preg_match("/^__/", $k))
			{
				// remove deprecated (back-up) property
				unset($order->{$k});
			}
			else if (in_array($k, $unsetList))
			{
				// remove property if contained in the list
				unset($order->{$k});
			}
		}

		return $order;
	}

	/**
	 * @override
	 * Translates the internal properties.
	 *
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 *
	 * @return 	void
	 */
	protected function translate($langtag = null)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		if (!$langtag)
		{
			// use order lang tag in case it was not specified
			$langtag = $this->get('langtag', null);

			if (!$langtag)
			{
				// the order is not assigned to any lang tag, use the current one
				$langtag = JFactory::getLanguage()->getTag();
			}
		}

		// get translator
		$translator = VAPFactory::getTranslator();

		// get subscription translation
		$sub_tx = $translator->translate('subscription', $this->subscription->id, $langtag);

		if ($sub_tx)
		{
			// inject translation within order details
			$this->subscription->name = $sub_tx->name;
		}

		// translate payment if specified
		if ($this->payment)
		{
			// get payment translation
			$pay_tx = $translator->translate('payment', $this->payment->id, $langtag);

			if ($pay_tx)
			{
				// inject translation within order details
				$this->payment->name                  = $pay_tx->name;
				$this->payment->notes->beforePurchase = $pay_tx->prenote;
				$this->payment->notes->afterPurchase  = $pay_tx->note;
			}
		}

		/**
		 * External plugins can use this event to apply the translations to
		 * additional details manually included within the order object.
		 *
		 * @param 	mixed   $order    The order details object.
		 * @param   string  $langtag  The requested language tag.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onTranslateCustomerSubscriptionOrderDetails', array($this, $langtag));
	}

	/**
	 * @override
	 * Returns the billing details of the user that made the order.
	 *
	 * @return 	object
	 */
	protected function getBilling()
	{
		// load all customer details
		return VikAppointments::getCustomer($this->id_user);
	}

	/**
	 * @override
	 * Returns the account details of the order author.
	 *
	 * @return 	object
	 */
	protected function getAuthor()
	{
		if ($this->createdby <= 0)
		{
			// no registered author, do not go ahead
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('name'))
			->select($dbo->qn('username'))
			->select($dbo->qn('email'))
			->from($dbo->qn('#__users'))
			->where($dbo->qn('id') . ' = ' . (int) $this->createdby);

		$dbo->setQuery($q, 0, 1);
		return $dbo->loadObject() ?? false;
	}

	/**
	 * @override
	 * Returns the invoice details of the order.
	 *
	 * @return 	mixed   The invoice object if exists, false otherwise.
	 */
	protected function getInvoice()
	{
		return JModelVAP::getInstance('invoice')->getInvoice($this->id, 'subscriptions');
	}

	/**
	 * @override
	 * Returns the history of the status codes set for the order.
	 *
	 * @return 	array
	 */
	protected function getHistory()
	{
		return VAPOrderStatus::getInstance('subscr_order')->getOrderTrack($this->id, $locale = true);
	}

	/**
	 * @override
	 * Returns a list of notes assigned to this order.
	 *
	 * @return 	array
	 */
	protected function getNotes()
	{
		return array();
	}
}

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
 * Packages order class wrapper.
 *
 * @since 1.7
 */
class VAPOrderPackage extends VAPOrderWrapper
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $id       The order ID.
	 * @param 	mixed    $langtag  The language tag. If null, the default one will be used.
	 * @param 	array 	 $options  An array of options to be passed to the order instance.
	 */
	public function __construct($id, $langtag = null, array $options = array())
	{
		// always force translation
		$options['translate'] = true;

		parent::__construct($id, $langtag, $options);
	}
	
	/**
	 * @override
	 * Returns the packages order object.
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
		$q->from($dbo->qn('#__vikappointments_package_order', 'o'));

		// select payment details
		$q->select($dbo->qn('gp.name', 'payment_name'));
		$q->select($dbo->qn('gp.file', 'payment_file'));
		$q->select($dbo->qn('gp.note', 'payment_note'));
		$q->select($dbo->qn('gp.prenote', 'payment_prenote'));
		$q->select($dbo->qn('gp.icontype', 'payment_icontype'));
		$q->select($dbo->qn('gp.icon', 'payment_icon'));
		$q->leftjoin($dbo->qn('#__vikappointments_gpayments', 'gp') . ' ON ' . $dbo->qn('o.id_payment') . ' = ' . $dbo->qn('gp.id'));

		// select purchased packages
		$q->select($dbo->qn('oi.id', 'item_id'));
		$q->select($dbo->qn('oi.id_package', 'item_id_package'));
		$q->select($dbo->qn('oi.quantity', 'item_quantity'));
		$q->select($dbo->qn('oi.num_app', 'item_num_app'));
		$q->select($dbo->qn('oi.used_app', 'item_used_app'));
		$q->select($dbo->qn('oi.validthru', 'item_validthru'));
		$q->select($dbo->qn('oi.price', 'item_price'));
		$q->select($dbo->qn('oi.net', 'item_net'));
		$q->select($dbo->qn('oi.tax', 'item_tax'));
		$q->select($dbo->qn('oi.gross', 'item_gross'));
		$q->select($dbo->qn('oi.discount', 'item_discount'));
		$q->select($dbo->qn('oi.tax_breakdown', 'item_tax_breakdown'));
		$q->select($dbo->qn('oi.modifiedon', 'item_modified'));
		$q->select($dbo->qn('p.name', 'item_name'));
		$q->select($dbo->qn('p.description', 'item_description'));
		$q->leftjoin($dbo->qn('#__vikappointments_package_order_item', 'oi') . ' ON ' . $dbo->qn('oi.id_order') . ' = ' . $dbo->qn('o.id'));
		$q->leftjoin($dbo->qn('#__vikappointments_package', 'p') . ' ON ' . $dbo->qn('oi.id_package') . ' = ' . $dbo->qn('p.id'));

		// select package group details
		$q->select($dbo->qn('gpk.id', 'item_group_id'));
		$q->select($dbo->qn('gpk.title', 'item_group_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_package_group', 'gpk') . ' ON ' . $dbo->qn('p.id_group') . ' = ' . $dbo->qn('gpk.id'));

		// filter by order key, if specified
		if (isset($options['sid']))
		{
			$q->where($dbo->qn('o.sid') . ' = ' . $dbo->q($options['sid']));
		}

		// load order matching the specified ID
		$q->where($dbo->qn('o.id') . ' = ' . (int) $id);

		// sort by group, package
		$q->order($dbo->qn('gpk.ordering') . ' ASC');
		$q->order($dbo->qn('p.ordering') . ' ASC');

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
		$dispatcher->trigger('onLoadPackagesOrderDetails', array(&$q, $id, $langtag, $options));

		$dbo->setQuery($q);
		$list = $dbo->loadObjectList();

		if (!$list)
		{
			// order not found raise error
			throw new Exception(sprintf('Order [%d] not found', $id), 404);
		}

		// create parent order details
		$order = $list[0];

		$order->usedApp    = 0;
		$order->totalApp   = 0;
		$order->lastUpdate = null;
		$order->packages   = array();

		foreach ($list as $row)
		{
			// check if we have a package to register
			if (!empty($row->item_id))
			{
				// create package
				$package = new stdClass;
				$package->id_assoc     = $this->detach($row, 'item_id');
				$package->id           = $this->detach($row, 'item_id_package');
				$package->name         = $this->detach($row, 'item_name');
				$package->description  = $this->detach($row, 'item_description');
				$package->quantity     = $this->detach($row, 'item_quantity');
				$package->price        = $this->detach($row, 'item_price');
				$package->modified     = $this->detach($row, 'item_modified');
				$package->totalApp     = $this->detach($row, 'item_num_app');
				$package->usedApp      = $this->detach($row, 'item_used_app');
				$package->remainingApp = max(array(0, $package->totalApp - $package->usedApp));
				$package->validthru    = $this->detach($row, 'item_validthru');

				if (VAPDateHelper::isNull($package->validthru))
				{
					$package->validthru = null;
				}

				// increase global counters
				$order->usedApp  += $package->usedApp;
				$order->totalApp += $package->totalApp;

				if ($package->modified > $order->lastUpdate)
				{
					// update global last modification date
					$order->lastUpdate = $package->modified;
				}

				// create package group
				if ($row->item_group_id)
				{
					$package->group = new stdClass;
					$package->group->id   = $this->detach($row, 'item_group_id');
					$package->group->name = $this->detach($row, 'item_group_name');
				}
				else
				{
					$package->group = null;
				}

				// create package totals
				$package->totals = new stdClass;
				$package->totals->net       = $this->detach($row, 'item_net');
				$package->totals->tax       = $this->detach($row, 'item_tax');
				$package->totals->gross     = $this->detach($row, 'item_gross');
				$package->totals->discount  = $this->detach($row, 'item_discount');
				$package->totals->breakdown = $row->item_tax_breakdown ? json_decode($this->detach($row, 'item_tax_breakdown')) : null;

				// register item within packages list
				$order->packages[] = $package;
			}
		}

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

		// decode stored CF data
		$order->custom_f      = (array) json_decode($order->custom_f, true);
		$order->fields        = $order->custom_f;
		$order->displayFields = $order->fields;

		$vars = array_values($order->fields);
		$vars = array_filter($vars, function($elem)
		{
			if (is_array($elem))
			{
				return (bool) $elem;
			}

			return strlen($elem);
		});
		
		$order->hasFields = (bool) $vars;

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
		$order->paid = JHtml::fetch('vaphtml.status.ispaid', 'packages', $order->status);

		if ($order->paid)
		{
			// amount paid, no remaining balance
			$order->totals->due = 0;
		}

		$order->statusRole = null;

		// fetch status role
		if (JHtml::fetch('vaphtml.status.ispending', 'packages', $order->status))
		{
			$order->statusRole = 'PENDING';
		}
		else if (JHtml::fetch('vaphtml.status.isapproved', 'packages', $order->status))
		{
			$order->statusRole = 'APPROVED';
		}
		else if (JHtml::fetch('vaphtml.status.iscancelled', 'packages', $order->status))
		{
			$order->statusRole = 'CANCELLED';
		}

		/**
		 * External plugins can use this event to manipulate the object holding
		 * the details of the order. Useful to inject all the additional data
		 * fetched with the manipulation of the query.
		 *
		 * @param 	mixed  $order  The order details object.
		 * @param 	array  $list   The query resulting array.
		 *
		 * @return 	void
		 */
		$dispatcher->trigger('onSetupPackagesOrderDetails', array($order, $list));

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

		$package_ids   = array();
		$pack_group_ids = array();

		foreach ($this->packages as $pack)
		{
			$package_ids[]  = $pack->id;

			if ($pack->group)
			{
				$pack_group_ids[] = $pack->group->id;
			}
		}

		// pre-load packages translations
		$packLang = $translator->load('package', array_unique($package_ids), $langtag);
		// pre-load packages groups translations
		$packGroupLang = $translator->load('packgroup', array_unique($pack_group_ids), $langtag);

		// iterate packages and apply translationss
		foreach ($this->packages as $k => $pack)
		{
			// translate package for the given language
			$pack_tx = $packLang->getTranslation($pack->id, $langtag);

			if ($pack_tx)
			{
				$pack->name        = $pack_tx->name;
				$pack->description = $pack_tx->description;
			}

			if ($pack->group)
			{
				// translate package group for the given language
				$grp_tx = $packGroupLang->getTranslation($pack->group->id, $langtag);

				if ($grp_tx)
				{
					$pack->group->name = $grp_tx->title;
				}
			}

			// update package
			$this->packages[$k] = $pack;
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

		// import custom fields loader
		VAPLoader::import('libraries.customfields.loader');

		// get relevant custom fields only
		$cf = VAPCustomFieldsLoader::getInstance()
			->translate($langtag)
			->noRequiredCheckbox()
			->noSeparator()
			->fetch();
		
		// translate CF data object
		$this->fields = VAPCustomFieldsLoader::translateObject($this->fields, $cf, $langtag);

		// reset display fields
		$this->displayFields = array();

		foreach ($cf as $field)
		{
			$k = $field['name'];

			// always skip file custom fields
			if (!array_key_exists($k, $this->fields) || $field['type'] == 'file')
			{
				// field not found inside the given object, go to next one
				continue;
			}

			$v = $this->fields[$k];

			// take only if the value is not empty
			if ((is_scalar($v) && strlen($v)) || !empty($v))
			{
				// get a more readable label/text of the saved value
				$this->displayFields[$field['langname']] = $v;
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
		$dispatcher->trigger('onTranslatePackagesOrderDetails', array($this, $langtag));
	}

	/**
	 * @override
	 * Returns the billing details of the user that made the order.
	 *
	 * @return 	object
	 */
	protected function getBilling()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_users'))
			->where($dbo->qn('id') . ' = ' . (int) $this->id_user)
			->orWhere(array(
				$dbo->qn('billing_mail') . ' <> ' . $dbo->q(''),
				$dbo->qn('billing_mail') . ' IS NOT NULL',
				$dbo->qn('billing_mail') . ' = ' . $dbo->q($this->purchaser_mail),
			), 'AND');

		$dbo->setQuery($q, 0, 1);
		return $dbo->loadObject() ?? false;
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
		return JModelVAP::getInstance('invoice')->getInvoice($this->id, 'packages');
	}

	/**
	 * @override
	 * Returns the history of the status codes set for the order.
	 *
	 * @return 	array
	 */
	protected function getHistory()
	{
		return VAPOrderStatus::getInstance('package_order')->getOrderTrack($this->id, $locale = true);
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

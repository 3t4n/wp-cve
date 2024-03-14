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

VAPLoader::import('libraries.cartpack.item');
VAPLoader::import('libraries.cart.discount');

/**
 * Class used to handle a cart to book the packages.
 *
 * @since 1.6
 */
class VAPCartPackages implements JsonSerializable
{
	/**
	 * The instance of the Cart.
	 * There should be only one cart instance for the whole session.
	 *
	 * @var VAPCartPackages
	 *
	 * @since 1.6
	 */
	protected static $instance = null;

	/**
	 * The list containing the selected items.
	 *
	 * @var VAPCartPackagesItem[]
	 */
	private $cart = array();

	/**
	 * A list of applied discounts.
	 *
	 * @var VAPCartDiscount[]
	 * @since 1.7
	 */
	private $discounts = array();
	
	/**
	 * The configuration array.
	 *
	 * @var array
	 */
	private $params = array(
		self::MAX_SIZE => self::UNLIMITED,	
	);

	/**
	 * Returns the instance of the cart object, only creating it
	 * if doesn't exist yet.
	 * 
	 * @param 	array 	$cart 	 The array containing all the items to push.
	 * @param 	array 	$params  The settings array.
	 *
	 * @return 	self 	A new instance.
	 *
	 * @since 	1.6
	 */
	public static function getInstance(array $cart = array(), array $params = array())
	{
		if (static::$instance === null)
		{
			// get cart from session
			$session_cart = JFactory::getSession()->get(self::CART_SESSION_KEY, null);

			if (empty($session_cart))
			{
				$cart = new static($cart, $params);
			}
			else
			{
				$cart = unserialize($session_cart);
			}

			static::$instance = $cart;
		}

		// always overwrite existing params
		static::$instance->setParams($params);

		return static::$instance;
	}
	
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$cart 	 The array containing all the items to push.
	 * @param 	array 	$params  The settings array.
	 *
	 * @uses 	setParams()
	 */
	public function __construct(array $cart = array(), array $params = array())
	{
		$this->cart = $cart;
		$this->setParams($params);
	}

	/**
	 * Store this instance into the PHP session.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @since 	1.6
	 */
	public function store()
	{
		JFactory::getSession()->set(self::CART_SESSION_KEY, serialize($this));

		return $this;
	}
	
	/**
	 * Sets the configuration of the cart.
	 *
	 * @param 	array 	$params  The settings array.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setParams(array $params = array())
	{
		foreach ($params as $k => $v)
		{
			$this->params[$k] = $v;
		}

		return $this;
	}
	
	/**
	 * Empties the items within the cart.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function emptyCart()
	{
		$this->cart = array();

		// reset discounts too
		$this->discounts = array();

		return $this;
	}
	
	/**
	 * Checks if the cart is empty.
	 *
	 * @return 	boolean  True if empty, false otherwise.
	 */
	public function isEmpty()
	{
		return count($this->cart) == 0;
	}
	
	/**
	 * Balances the cart in order to empty the free slots
	 * created after removing one or more items.
	 *
	 * @return 	self    This object to support chaining.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public function balance()
	{
		// do nothing, balance is automatically made every
		// time an item gets removed

		return $this;
	}
	
	/**
	 * Pushes a new package within the cart.
	 * This method checks if the item can be added as the cart
	 * may own an internal size limit.
	 *
	 * @param 	VAPCartPackagesItem  $pack 	The package to push.
	 * 
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	getPackagesInCart()
	 * @uses 	indexOf()
	 */
	public function addPackage(VAPCartPackagesItem $pack)
	{	
		if ($this->params[self::MAX_SIZE] == self::UNLIMITED || $this->getPackagesInCart() < $this->params[self::MAX_SIZE])
		{	
			$index = $this->indexOf($pack->getID());
		
			if ($index == -1)
			{
				// add package at the end of the cart
				$this->cart[] = $pack;
			}
			else
			{
				// increase quantity by the specified one
				$this->cart[$index]->addQuantity($pack->getQuantity());
			}

			return true;
		}
		
		return false;
	}
	
	/**
	 * Removes the specified package.
	 *
	 * @param 	integer  $id 	 The package ID.
	 * @param 	integer  $units  The number of units to remove.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	indexOf()
	 */
	public function removePackage($id, $units = 1)
	{
		$index = $this->indexOf($id);

		if ($index != -1)
		{
			// remove by the specified units
			$q = $this->cart[$index]->removeQuantity($units);
			
			if ($q == 0)
			{
				// no more units, delete the option from the list
				array_splice($this->cart, $index, 1);
			}

			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the position within the list of the specified package.
	 *
	 * @param 	integer  $id 	The package ID.
	 *
	 * @return 	integer  The package index if exists, -1 otherwise.
	 *
	 * @uses 	getCartLength()
	 */
	public function indexOf($id)
	{
		for ($i = 0; $i < $this->getCartLength(); $i++)
		{
			if ($this->cart[$i]->getID() == $id)
			{
				return $i;
			}
		}
		
		return -1;
	}
	
	/**
	 * Returns the total cost of the cart.
	 *
	 * @return 	float
	 */
	public function getTotalCost()
	{
		$total = 0;

		foreach ($this->cart as $p)
		{
			$total += $p->getTotalCost();
		}
		
		return $total;
	}

	/**
	 * Returns the total net of the cart.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalNet()
	{
		$this->prepareDiscounts();

		$total = 0;

		foreach ($this->cart as $i)
		{
			$total += $i->getTotalNet($this);
		}
		
		return $total;
	}

	/**
	 * Returns the total tax of the cart.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalTax()
	{
		$this->prepareDiscounts();

		$total = 0;

		foreach ($this->cart as $i)
		{
			$total += $i->getTotalTax($this);
		}
		
		return $total;
	}

	/**
	 * Returns the total gross of the cart.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalGross()
	{
		$this->prepareDiscounts();

		$total = 0;

		foreach ($this->cart as $i)
		{
			$total += $i->getTotalGross($this);
		}
		
		return $total;
	}

	/**
	 * Returns the total discount.
	 *
	 * @param 	array  &$lookup  A lookup used to track the applied discounts.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalDiscount(&$lookup = array())
	{
		$this->prepareDiscounts();

		$total = 0;

		foreach ($this->cart as $i)
		{
			// calculate the difference between the item full price
			// and the discounted price, if any
			$total += $i->getTotalCost() - $i->getDiscountedPrice($this, $lookup);
		}
		
		return round($total, 2);
	}

	/**
	 * Returns the totals per each registered item and option.
	 *
	 * @return 	array    An array of discounts, matching the index
	 *                   of the related item.
	 *
	 * @since 	1.7
	 */
	public function getTotalsPerItem()
	{
		$this->prepareDiscounts();

		$items = array();

		$options = array();
		// $options['id_user'] = JFactory::getUser()->id;

		foreach ($this->cart as $i => $item)
		{
			$itemTotals = new stdClass;
			// calculate original price
			$itemTotals->priceBeforeDiscount = $item->getPrice();
			// calculate final price per item and related discount
			$itemTotals->price    = $item->getDiscountedPrice($this);
			$itemTotals->discount = $itemTotals->priceBeforeDiscount - $itemTotals->price;

			$options['subject'] = 'package';

			// re-calculate totals of discounted item
			$tmp = VAPTaxFactory::calculate($item->getID(), $itemTotals->price, $options);

			// register new totals inside the object
			foreach ($tmp as $k => $v)
			{
				$itemTotals->{$k} = $v;
			}

			// register item
			$items[] = $itemTotals;
		}
		
		return $items;
	}

	/**
	 * Returns the total discount per each registered offer.
	 *
	 * @return 	array  A lookup of discounts, where the key is the
	 *                 title/ID and the value is the discount.
	 *
	 * @since 	1.7
	 */
	public function getTotalDiscountPerOffer()
	{
		// pass a junk variable to the method used to calculate the
		// total discount per each offer
		$this->getTotalDiscount($lookup);

		$map = array();
		
		// iterate all registered discounts
		foreach ($this->getDiscounts() as $discount)
		{
			$id = $discount->getID();

			if (!isset($lookup[$id]))
			{
				// discount not set, go ahead
				continue;
			}

			// try to check whether the discount supports a readable title
			$k = $discount->get('title');

			if ($k)
			{
				// title given, try to translate it
				$k = JText::translate($k);
			}
			else
			{
				// missing title, use the ID
				$k = $id;
			}

			// register discount total
			$map[$k] = round($lookup[$id], 2);
		}

		return $map;
	}
	
	/**
	 * Returns the package at the specified position.
	 *
	 * @param 	integer  $index
	 *
	 * @return 	mixed 	 The package if exists, null otherwise.
	 *
	 * @uses 	getCartLength()
	 */
	public function getPackageAt($index)
	{
		if ($index >= 0 && $index < $this->getCartLength())
		{
			return $this->cart[$index];
		}
		
		return null;
	}
	
	/**
	 * Returns the number of packages within the cart.
	 * The list may contain also packages that are no more
	 * active.
	 *
	 * @return 	integer
	 */
	public function getCartLength()
	{
		return count($this->cart);
	}
	
	/**
	 * Returns the number of active packages within the list.
	 *
	 * @return 	integer
	 */
	public function getPackagesInCart()
	{
		$count = 0;

		foreach ($this->cart as $p)
		{
			$count += $p->getQuantity();
		}

		return $count;
	}
	
	/**
	 * Returns a list containing all the active packages.
	 *
	 * @return 	array
	 */
	public function getPackagesList()
	{
		return $this->cart;
	}

	/**
	 * Configures the discount objects before being used.
	 *
	 * @return 	self
	 *
	 * @since 	1.7
	 */
	protected function prepareDiscounts()
	{
		$count = 0;

		// counts the total number of items that have a cost
		foreach ($this->cart as $item)
		{
			if ($item->getPrice() > 0)
			{
				// item with cost, increase counter
				$count++;
			}
		}

		foreach ($this->discounts as $discount)
		{
			// reset internal index
			$discount->set('count', 0);
			// reset internal total discount
			$discount->set('disctot', 0);
			// set total number of items with cost
			$discount->set('length', $count);
			// register the total cost of the order
			$discount->set('total', $this->getTotalCost());
		}

		return $this;
	}

	/**
	 * Registers a new discount within the cart.
	 *
	 * @param 	VAPCartDiscount  $discount  The discount to apply.
	 *
	 * @return 	self  This object to support chaining.
	 *
	 * @since 	1.7
	 */
	public function addDiscount(VAPCartDiscount $discount)
	{
		// add discount element
		$this->discounts[] = $discount;

		return $this;
	}

	/**
	 * Removes a discount from the cart, if any.
	 *
	 * @param 	mixed  $discount  Either the discount ID or an object.
	 *
	 * @return 	mixed  The deleted discount on success, false otherwise.
	 *
	 * @since 	1.7
	 */
	public function removeDiscount($discount)
	{
		foreach ($this->discounts as $i => $elem)
		{
			if ($elem === $discount || (is_scalar($discount) && $elem->getID() == $discount)
				|| ($discount instanceof VAPCartDiscount && $discount->getID() == $elem->getID()))
			{
				return array_splice($this->discounts, $i, 1);
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
	 *
	 * @since 	1.7
	 */
	public function setDiscount(VAPCartDiscount $discount)
	{
		// remove discount first
		$this->removeDiscount($discount);

		// then add new discount element
		$this->discounts[] = $discount;

		return $this;
	}

	/**
	 * Returns the discount matching the specified code.
	 *
	 * @param 	mixed  $discount  Either the discount ID or an object.
	 *
	 * @return 	mixed  The discount object on success, null otherwise.
	 *
	 * @since 	1.7
	 */
	public function getDiscount($discount)
	{
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
	 *
	 * @since 	1.7
	 */
	public function getDiscounts()
	{
		return $this->discounts;
	}
	
	/**
	 * Returns the first available index to push a new item.
	 * Used to replace a unactive item with a new one.
	 *
	 * @return 	integer
	 *
	 * @uses 	getCartLength()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	protected function getFirstAvailableIndex()
	{
		return $this->getCartLength();
	}
	
	/**
	 * Magic method used to return a string representation of this instance.
	 *
	 * @return 	string
	 */
	public function __tostring()
	{
		return '<pre>' . print_r($this, true) . '</pre><br />Total Cost = ' . $this->getTotalCost();
	}

	/**
	 * Creates a standard object, containing all the supported properties,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  object
	 *
	 * @since 	1.7
	 *
	 * @see     JsonSerializable
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return array(
			'cart'      => $this->cart,
			'discounts' => $this->discounts,
		);
	}
	
	/**
	 * Identifier used to make the size of the cart unlimited.
	 *
	 * @var integer
	 */
	const UNLIMITED = -1;
	
	/**
	 * Setting name used to retrieve the maximum number of items
	 * that can be added within the list.
	 *
	 * @var string
	 */
	const MAX_SIZE = "maxsize";
	
	/**
	 * CART_SESSION_KEY identifier for session key.
	 *
	 * @var string
	 *
	 * @since 1.6
	 */
	const CART_SESSION_KEY = 'vapcartpackdev';
}

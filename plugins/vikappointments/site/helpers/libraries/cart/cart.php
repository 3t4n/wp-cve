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

VAPLoader::import('libraries.cart.item');
VAPLoader::import('libraries.cart.discount');

/**
 * Class used to handle a cart to book the appointments.
 *
 * @since 1.6
 */
class VAPCart implements JsonSerializable
{
	/**
	 * The instance of the Cart.
	 * There should be only one cart instance for the whole session.
	 *
	 * @var VAPCart
	 *
	 * @since 1.6
	 */
	protected static $instance = null;

	/**
	 * The list containing the selected items.
	 *
	 * @var VAPCartItem[]
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
		'append' 	=> true,
		'maxsize' 	=> self::UNLIMITED,
		'allowsync' => true,
	);

	/**
	 * Returns the instance of the cart object, only creating it
	 * if doesn't exist yet.
	 * 
	 * @param 	array  $cart    The array containing all the items to push.
	 * @param 	array  $params  The settings array.
	 *
	 * @return 	self   A new instance.
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
	 * @param 	array  $cart    The array containing all the items to push.
	 * @param 	array  $params  The settings array.
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
	 * @return 	self  This object to support chaining.
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
	 * @param 	array  $params  The settings array.
	 *
	 * @return 	self   This object to support chaining.
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
	 * Pushes a new item within the cart.
	 * This method checks if the item can be added as the cart
	 * may own an internal size limit.
	 *
	 * @param 	VAPCartItem  $item  The item to push.
	 * 
	 * @return 	boolean      True on success, false otherwise.
	 *
	 * @uses 	getCartLength()
	 * @uses 	indexOf()
	 * @uses 	emptyCart()
	 */
	public function addItem(VAPCartItem $item)
	{	
		if ($this->params['maxsize'] == -1 || $this->getCartLength() < $this->params['maxsize'] || !$this->params['append'])
		{
			// check whether the same appointment already exists
			$index = $this->indexOf($item->getServiceID(), $item->getEmployeeID(), $item->getCheckinDate(), $item->getDuration());
		
			if ($index == -1 || !$this->params['append'])
			{
				if (!$this->params['append'])
				{
					// cart system not supported, replace the existing item
					// with the new one
					$this->emptyCart();
				}
			
				$this->cart[] = $item;
			
				return true;	
			}
		}
		
		return false;
	}
	
	/**
	 * Removes the item from the cart considering the specified arguments.
	 *
	 * @param 	integer  $id        The service ID.
	 * @param 	integer  $id2       The employee ID.
	 * @param 	string   $checkin   The check-in date time (UTC).
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	indexOf()
	 */
	public function removeItem($id, $id2, $checkin)
	{
		// reset allow sync to retrieve the correct item
		$tmp = $this->params['allowsync'];
		$this->params['allowsync'] = true;

		$index = $this->indexOf($id, $id2, $checkin);
		$this->params['allowsync'] = $tmp;

		if ($index != -1)
		{
			// remove item from cart
			array_splice($this->cart, $index, 1);

			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the index of the item that matches the specified arguments.
	 *
	 * @param 	integer  $id_service   The service ID.
	 * @param 	integer  $id_employee  The employee ID.
	 * @param 	string   $checkin      The check-in date time (UTC).
	 * @param 	integer  $duration     The duration used to calculate the ending delimiter
	 *                                 to check if there is an intersection.
	 *
	 * @return 	integer  The item index on success, -1 on failure.
	 *
	 * @uses 	getCartLength()
	 * @uses 	bounds()
	 */
	public function indexOf($id_service, $id_employee, $checkin, $duration = 0)
	{
		$checkout = new JDate($checkin);
		// re-format check-in date time for a correct comparison
		$checkin = $checkout->format('Y-m-d H:i:s');
		// Then create check-out date string.
		// When not specified, use "5" as duration to avoid a failure due to "bounds" method.
		$checkout->modify('+' . ($duration ? $duration : 5) . ' minutes');
		$checkout = $checkout->format('Y-m-d H:i:s');

		for ($i = 0; $i < $this->getCartLength(); $i++)
		{
			if ($this->params['allowsync'])
			{
				// check whether the services are matching
				$same_service = $this->cart[$i]->getServiceID() == $id_service;
				// check whether the employees are matching (add check to include different identifiers for employees not selected: <= 0)
				$same_employee = $this->cart[$i]->getEmployeeID() == $id_employee || ($this->cart[$i]->getEmployeeID() <= 0 && $id_employee <= 0);

				// check the exact item stored within the cart
				if ($same_service && $same_employee && $this->cart[$i]->getCheckinDate() == $checkin)
				{
					return $i;
				}
			}
			else
			{
				// look for any item that intersects the specified query
				if ($this->bounds($this->cart[$i]->getCheckinDate(), $this->cart[$i]->getCheckoutDate(), $checkin, $checkout))
				{
					return $i;
				}
			}
		}
		
		return -1;
	}

	/**
	 * Checks if there is an intersection between the specified delimiters.
	 *
	 * @param 	string  $start_a  The first initial delimiter.
	 * @param 	string  $end_a    The first ending delimiter.
	 * @param 	string  $start_b  The second initial delimiter.
	 * @param 	string  $end_b    The second ending delimiter.
	 *
	 * @return 	boolean  True if they intersect, false otherwise.
	 */
	private function bounds($start_a, $end_a, $start_b, $end_b)
	{
		// IN_A <= IN_B  AND IN_B  <  OUT_A
		// IN_A <  OUT_B AND OUT_B <= OUT_A
		// IN_B <  IN_A  AND OUT_A <  OUT_B
		return ($start_a <= $start_b && $start_b <  $end_a)
			|| ($start_a <  $end_b   && $end_b   <= $end_a)
			|| ($start_b <  $start_a && $end_a   <  $end_b);
	}
	
	/**
	 * Returns the total cost of the cart.
	 *
	 * @return 	float
	 */
	public function getTotalCost()
	{
		$total = 0;

		foreach ($this->cart as $i)
		{
			$total += $i->getTotalCost();
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
			$total += $i->getPrice() - $i->getDiscountedPrice($this, $lookup);

			foreach ($i->getOptionsList() as $o)
			{
				$optPrice = $o->getTotalPrice();

				if ($optPrice > 0)
				{
					// Calculate the difference between the option full price
					// and the discounted price, if any. Ignore in case the
					// option is a discount itself, because it is already
					// considered by the item discounted price.
					$total += $optPrice - $o->getDiscountedPrice($this, $lookup);
				}
			}
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

			$options['subject'] = 'service';

			// re-calculate totals of discounted item
			$tmp = VAPTaxFactory::calculate($item->getServiceID(), $itemTotals->price, $options);

			// register new totals inside the object
			foreach ($tmp as $k => $v)
			{
				$itemTotals->{$k} = $v;
			}

			$itemTotals->options = array();

			$itemTotals->subdisc  = $itemTotals->discount;
			$itemTotals->subnet   = $itemTotals->net;
			$itemTotals->subtax   = $itemTotals->tax;
			$itemTotals->subgross = $itemTotals->gross;

			// iterate internal options
			foreach ($item->getOptionsList() as $option)
			{
				$optionTotals = new stdClass;
				// calculate original price
				$optionTotals->priceBeforeDiscount = $option->getTotalPrice();
				// calculate final price per item and related discount
				$optionTotals->price    = $option->getDiscountedPrice($this);
				$optionTotals->discount = $optionTotals->priceBeforeDiscount - $optionTotals->price;

				$options['subject'] = 'option';

				// re-calculate totals of discounted option
				$tmp = VAPTaxFactory::calculate($option->getID(), $optionTotals->price, $options);

				// register new totals inside the object
				foreach ($tmp as $k => $v)
				{
					$optionTotals->{$k} = $v;
				}

				// do not recalculate totals in case the option is used
				// to offer a discount, since it has been already applied
				if ($optionTotals->gross > 0)
				{
					// increase item sub-totals
					$itemTotals->subdisc  += $optionTotals->discount;
					$itemTotals->subnet   += $optionTotals->net;
					$itemTotals->subtax   += $optionTotals->tax;
					$itemTotals->subgross += $optionTotals->gross;
				}

				// register item option
				$itemTotals->options[] = $optionTotals;
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
	 * Returns the item at the specified position.
	 *
	 * @param 	integer  $index
	 *
	 * @return 	mixed    The item if exists, null otherwise.
	 *
	 * @uses 	getCartLength()
	 */
	public function getItemAt($index)
	{
		if ($index >= 0 && $index < $this->getCartLength())
		{
			return $this->cart[$index];
		}
		
		return null;
	}
	
	/**
	 * Returns the number of items within the cart.
	 * The list may contain also items that are no more
	 * active.
	 *
	 * @return 	integer
	 */
	public function getCartLength()
	{
		return count($this->cart);
	}
	
	/**
	 * Returns the number of active items within the list.
	 *
	 * @return 	integer
	 *
	 * @deprecated 1.8  Use getCartLength() instead.
	 */
	public function getCartRealLength()
	{
		return $this->getCartLength();
	}
	
	/**
	 * Returns a list containing all the active items.
	 *
	 * @return 	array
	 */
	public function getItemsList()
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

			foreach ($item->getOptionsList() as $option)
			{
				if ($option->getPrice() > 0)
				{
					// option with cost, increase counter
					$count++;
				}
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
		return [
			'cart'       => $this->cart,
			'discounts'  => $this->discounts,
			'total'      => $this->getTotalCost(),
			'totalNet'   => $this->getTotalNet(),
			'totalTax'   => $this->getTotalTax(),
			'totalGross' => $this->getTotalGross(),
		];
	}
	
	/**
	 * Identifier used to make the size of the cart unlimited.
	 *
	 * @var integer
	 */
	const UNLIMITED = -1;
	
	/**
	 * Setting name used to check if the cart is enabled or not.
	 * In case the cart is disabled, before pushing a new item, the list
	 * will be always emptied.
	 *
	 * @var string
	 */
	const CART_ENABLED = 'append';

	/**
	 * Setting name used to retrieve the maximum number of items
	 * that can be added within the list.
	 *
	 * @var string
	 */
	const MAX_SIZE = 'maxsize';

	/**
	 * Setting name used to check if the cart can contain more than
	 * one appointment at the same date and time.
	 *
	 * @var string
	 */
	const ALLOW_SYNC = 'allowsync';

	/**
	 * CART_SESSION_KEY identifier for session key.
	 *
	 * @var string
	 *
	 * @since 1.6
	 */
	const CART_SESSION_KEY = 'vapcartdev';
}

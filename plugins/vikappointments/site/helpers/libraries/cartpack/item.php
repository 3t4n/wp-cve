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

VAPLoader::import('libraries.tax.factory');

/**
 * Class used to handle the packages that can be stored within a cart.
 *
 * @since 1.6
 */
class VAPCartPackagesItem implements JsonSerializable
{
	/**
	 * The package identifier.
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * The package name.
	 *
	 * @var string
	 */	
	private $name;

	/**
	 * The package cost.
	 *
	 * @var float
	 */
	private $price;

	/**
	 * The number of appointments that can be redeemed with
	 * a single unit of this package.
	 *
	 * @var integer
	 */
	private $numapp;

	/**
	 * The number of selected units.
	 *
	 * @var integer
	 */
	private $quantity;

	/**
	 * An object holding the item totals.
	 *
	 * @var object
	 * @since 1.7
	 */
	private $totals;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $id        The package ID.
	 * @param 	string 	 $name      The package name.
	 * @param 	float 	 $price     The package cost.
	 * @param 	integer  $numapp    The appointments to redeem.
	 * @param 	integer  $quantity  The number of units.
	 */
	public function __construct($id, $name, $price, $numapp, $quantity = 1)
	{
		$this->id       = $id;
		$this->name     = $name;
		$this->numapp   = $numapp;
		$this->quantity = $quantity;

		// calculate totals
		$this->setPrice($price);
	}
	
	/**
	 * Returns the package identifier.
	 *
	 * @return 	integer
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 * Returns the package name.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		/**
		 * Translate package name at runtime.
		 *
		 * @since 1.7
		 */
		$translator = VAPFactory::getTranslator();
		// translate the specified package
		$tx = $translator->translate('package', $this->getID());

		if ($tx)
		{
			// use the specified translation
			$name = $tx->name;
		}
		else
		{
			// use the default service name
			$name = $this->name;
		}

		return $name;
	}
	
	/**
	 * Returns the package base price.
	 *
	 * @return 	float
	 */
	public function getPrice()
	{
		return (float) $this->price;
	}

	/**
	 * Returns the resulting price after applying the discounts.
	 *
	 * @param 	mixed  $cart     When specified, the system will try to apply
	 *                           the discounts to the base price.
	 * @param 	array  &$lookup  A lookup used to track the applied discounts.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getDiscountedPrice($cart = null, &$lookup = array())
	{
		$price = $this->getTotalCost();

		// in case the cart was specified, check whether there are some
		// discounts to apply
		if (!$cart || !$cart->getDiscounts())
		{
			// nope, use default price
			return $price;
		}

		$base = $price;

		foreach ($cart->getDiscounts() as $discount)
		{
			$old = $price;

			// apply discount on cascade
			$price = $discount->apply($price, $base, $this);

			if (!isset($lookup[$discount->getID()]))
			{
				// create discount repository
				$lookup[$discount->getID()] = 0;
			}

			// increase repo by subtracting the price after the discount
			// from the price before the discount
			$lookup[$discount->getID()] += $old - $price;
		}

		// make sure the price is not lower than 0
		return max(array(0, $price));
	}

	/**
	 * Sets the package base price.
	 *
	 * @param 	float 	$price  The package base cost.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @since 	1.6.6
	 */
	public function setPrice($price)
	{
		$this->price = (float) $price;

		$options = array();
		$options['subject'] = 'package';
		// $options['id_user'] = JFactory::getUser()->id;

		// calculate taxes
		$this->totals = VAPTaxFactory::calculate($this->getID(), $this->getTotalCost(), $options);

		return $this;
	}

	/**
	 * Returns the package total cost.
	 *
	 * @return 	integer
	 *
	 * @uses 	getPrice()
	 * @uses 	getQuantity()
	 */
	public function getTotalCost()
	{
		return $this->getPrice() * $this->getQuantity();
	}

	/**
	 * Returns the total net of the package.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalNet($cart = null)
	{
		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalCost())
		{
			// no discount, use default total net
			return $this->totals->net;
		}

		$options = array();
		$options['subject'] = 'package';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate net of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->net;
	}

	/**
	 * Returns the total taxes of the package.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalTax($cart = null)
	{
		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalCost())
		{
			// no discount, use default total tax
			return $this->totals->tax;
		}

		$options = array();
		$options['subject'] = 'package';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate taxes of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->tax;
	}

	/**
	 * Returns the total gross of the package.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalGross($cart = null)
	{
		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalCost())
		{
			// no discount, use default total gross
			return $this->totals->gross;
		}

		$options = array();
		$options['subject'] = 'package';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate gross of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->gross;
	}
	
	/**
	 * Returns the number of appointments that can be redeemed
	 * with a single unit of this package.
	 *
	 * @return 	integer
	 */
	public function getNumberAppointments() 
	{
		return $this->numapp;
	}
	
	/**
	 * Returns the number of selected units.
	 *
	 * @return 	integer
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	/**
	 * Checks if this item is active or not.
	 *
	 * @return 	boolean
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public function isActive()
	{
		return true;
	}
	
	/**
	 * Marks this item as active.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public function active()
	{
		return $this;
	}
	
	/**
	 * Marks this item as unactive.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public function remove()
	{
		return $this;
	}

	/**
	 * Increases the quantity of this package by the specified units.
	 *
	 * @param 	integer  $unit  The units to add.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function addQuantity($unit = 1)
	{
		// increase quantity by the specified units
		$this->quantity += abs($unit);

		// refresh taxes
		$this->setPrice($this->getPrice());
	}

	/**
	 * Decreases the quantity of this package by the specified units.
	 *
	 * @param 	integer  $unit  The units to remove.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function removeQuantity($unit = 1)
	{
		// decrease units   
		$this->quantity -= abs($unit);

		if ($this->quantity <= 0)
		{
			// permanently removed
			$this->quantity = 0;
		}
		else
		{
			// refresh taxes
			$this->setPrice($this->getPrice());
		}

		return $this->quantity;
	}
	
	/**
	 * Magic method used to return a string representation of this instance.
	 *
	 * @return 	string
	 */
	public function __tostring()
	{
		return '<pre>' . print_r($this, true) . '</pre>';
	}
	
	/**
	 * Returns an array containing the details of this instance.
	 *
	 * @return 	array
	 */
	public function toArray()
	{
		$arr = array(
			'id'       => $this->getID(),
			'name'     => $this->getName(),
			'price'    => $this->getPrice(),
			'total'    => $this->getTotalCost(),
			'numapp'   => $this->getNumberAppointments(),
			'quantity' => $this->getQuantity(),
		);
		
		return $arr;
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
		return $this->toArray();
	}
}

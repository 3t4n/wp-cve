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
 * Class used to handle the options that can be attached to the cart items.
 *
 * @since 1.6
 */
class VAPCartOption implements JsonSerializable
{
	/**
	 * The option identifier.
	 *
	 * @var integer
	 */
	private $id;

	/**
	 * The variation identifier.
	 *
	 * @var integer
	 */
	private $idVariation;

	/**
	 * The option name. If the variation is set, the name is built as:
	 * [OPTION_NAME] - [VARIATION_NAME]
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The option price plus the variation price (if any).
	 *
	 * @var float
	 */
	private $price;

	/**
	 * The option (plus the variation) extra duration.
	 *
	 * @var int
	 * @since 1.7.3
	 */
    private $duration;

	/**
	 * The selected quantity of the option.
	 *
	 * @var integer
	 */
	private $quantity;

	/**
	 * The minimum number of units that can be selected.
	 *
	 * @var integer
	 * @since 1.7.4
	 */
    private $minQuantity = 1;

	/**
	 * The maximum number of units that can be selected.
	 *
	 * @var integer
	 */
    private $maxQuantity;

    /**
	 * Flag used to check if the option is required (mandatory selection) or not.
	 *
	 * @var boolean
	 */
    private $required;

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
	 * @param 	integer  $id        The option ID.
	 * @param 	integer  $id_var    The variation ID (-1 if not specified).
	 * @param 	string 	 $name      The option name (variation included).
	 * @param 	float    $price     The option price (variation included).
	 * @param 	integer  $maxq      The maximum quantity.
	 * @param 	boolean  $required  True if required, false otherwise.
	 * @param 	integer  $quantity  The selected units.
	 * @param 	integer  $duration  The extra duration, in minutes (added @since 1.7.3).
	 */
	public function __construct($id, $var, $name, $price, $maxq, $required, $quantity = 1, $duration = 0)
	{
		$this->id          = (int) $id;
		$this->idVariation = (int) $var;
		$this->name        = $name;
		$this->quantity    = (int) $quantity;
        $this->maxQuantity = (int) $maxq;
        $this->required    = (bool) $required;
        $this->duration    = abs((int) $duration);

        /**
         * Check whether the selected option needs to have the number of units always
         * equal to the number of selected participants. In that case, the minimum
         * quantity should be equal to the maximum one.
         * 
         * @since 1.7.4
         */
        if ($this->maxQuantity > 1 && JModelVAP::getInstance('option')->getItem($id, $blank = true)->maxqpeople == 2)
		{
			$this->minQuantity = $this->maxQuantity;
		}

        $this->setPrice($price);
	}
	
	/**
	 * Returns the option identifier.
	 *
	 * @return 	integer
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * Returns the option variation identifier.
	 * If not set, returns -1.
	 *
	 * @return 	integer
	 */
	public function getVariationID()
	{
		return $this->idVariation > 0 ? $this->idVariation : -1;
	}
	
	/**
	 * Returns the option and variation name.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		/**
		 * Translate option name at runtime.
		 *
		 * @since 1.7
		 */
		$translator = VAPFactory::getTranslator();
		// translate the specified option
		$tx = $translator->translate('option', $this->getID());

		if ($tx)
		{
			// use the specified translation
			$name = $tx->name;
		}
		else
		{
			// use the default option name
			$name = $this->name;
		}

		if ($this->getVariationID() > 0)
		{
			// translate the specified variation
			$tx = $translator->translate('optionvar', $this->getVariationID());

			if ($tx)
			{
				// use the specified translation
				$name .= ' - ' . $tx->name;
			}
		}

		return $name;
	}
	
	/**
	 * Returns the option price.
	 *
	 * @return 	float
	 */
	public function getPrice()
	{
		return (float) $this->price;
	}

	/**
	 * Returns the option price multiplied by the selected units.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getTotalPrice()
	{
		return (float) $this->price * $this->getQuantity();
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
		$price = $this->getTotalPrice();

		if (!$price)
		{
			// the option has no cost, ignore discounts
			return 0.0;
		}

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
		return max(array(0.0, $price));
	}

	/**
	 * Returns the total net of the option.
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
		$price = $this->getPrice();

		if ($price <= 0)
		{
			// the option has not cost
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalPrice())
		{
			// no discount, use default total net
			return $this->totals->net;
		}

		$options = array();
		$options['subject'] = 'option';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate net of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->net;
	}

	/**
	 * Returns the total taxes of the option.
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
		$price = $this->getPrice();

		if ($price <= 0)
		{
			// the option has not cost
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalPrice())
		{
			// no discount, use default total tax
			return $this->totals->tax;
		}

		$options = array();
		$options['subject'] = 'option';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate taxes of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->tax;
	}

	/**
	 * Returns the total gross of the option.
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
		$price = $this->getPrice();

		if ($price <= 0)
		{
			// the option has not cost
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $this->getTotalPrice())
		{
			// no discount, use default total gross
			return $this->totals->gross;
		}

		$options = array();
		$options['subject'] = 'option';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate gross of discounted item
		return VAPTaxFactory::calculate($this->getID(), $price, $options)->gross;
	}

	/**
	 * Sets the option price.
	 *
	 * @param 	float  $price  The option cost.
	 *
	 * @return 	self   This object to support chaining.
	 *
	 * @since 	1.6.6
	 */
	public function setPrice($price)
	{
		$this->price = (float) $price;

		VAPLoader::import('libraries.tax.factory');

		$options = array();
		$options['subject'] = 'option';
		// $options['id_user'] = JFactory::getUser()->id;

		// calculate taxes
		$this->totals = VAPTaxFactory::calculate($this->getID(), $this->getTotalPrice(), $options);

		return $this;
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
	 * Returns the maximum number of units that can be selected.
	 *
	 * @return 	integer
	 */
    public function getMaxQuantity()
    {
        return $this->maxQuantity;
    }
    
    /**
	 * Checks if the option is required.
	 *
	 * @return 	boolean
	 */
    public function isRequired()
    {
        return $this->required;
    }

    /**
	 * Returns the extra duration applied by the option.
	 *
	 * @return 	integer
	 * 
	 * @since 	1.7.3
	 */
	public function getDuration()
	{
		return $this->duration;
	}

	/**
	 * Returns the option duration multiplied by the selected units.
	 *
	 * @return 	integer
	 *
	 * @since 	1.7.3
	 */
	public function getTotalDuration()
	{
		return $this->duration * $this->getQuantity();
	}
	
	/**
	 * Decreases the number of selected units by the specified amount.
	 *
	 * @param 	integer  $unit 	The number of units to remove (1 by default).
	 *
	 * @return 	integer  The remaining quantity.
	 */
	public function remove($unit = 1)
	{
		// always take the maximum value between the provided units and the minimum allowed ones
		$units = max($this->minQuantity, abs($unit));

		// decrease units   
		$this->quantity -= $units;

		if ($this->quantity <= 0)
		{
			if ($this->required)
			{
				// cannot definitively remove a required option 
				$this->quantity = 1;
			}
			else
			{
				// permanently removed
				$this->quantity = 0;
			}
		}

		if ($this->quantity)
		{
			// refresh taxes
			$this->setPrice($this->getPrice());
		}

		return $this->quantity;
	}
	
	/**
	 * Increases the number of selected units by the specified amount.
	 *
	 * @param 	integer  $unit 	The number of units to add (1 by default).
	 *
	 * @return 	self     This object to support chaining.
	 */
	public function add($unit = 1)
	{
		// increase quantity by the specified units
		$this->quantity += abs($unit);
		// final quantity cannot exceed the maximum amount
		$this->quantity = min(array($this->quantity, $this->maxQuantity));

		// refresh taxes
		$this->setPrice($this->getPrice());

		return $this;
	}

	/**
	 * Magic method used to return a string representation of this instance.
	 *
	 * @return 	string
	 */
	public function __tostring()
	{
		return 'ID = ' . $this->id . '<br />' .
			'Variation ID = '. $this->idVariation . '<br />' .
			'Name = ' . $this->getName() . '<br />' .
			'Price = ' . $this->price . '<br />' .
			'Quantity = ' . $this->quantity;
	}
	
	/**
	 * Returns an array containing the details of this instance.
	 *
	 * @return 	array
	 */
	public function toArray()
	{
		return array(
			'id'           => $this->getID(),
			'id_variation' => $this->getVariationID(),
			'name'         => $this->getName(),
			'price'        => $this->getPrice(),
			'quantity'     => $this->getQuantity(),
			'max_quantity' => $this->getMaxQuantity(),
			'required'     => $this->isRequired(),
			'totals'       => $this->totals,
		);
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

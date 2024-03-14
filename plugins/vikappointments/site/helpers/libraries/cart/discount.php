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
 * Encapsulates the details of a generic discount.
 *
 * @since 1.7
 */
class VAPCartDiscount extends JObject implements JsonSerializable
{	
	/**
	 * The ID of the deal.
	 *
	 * @var mixed
	 */
	private $id;

	/**
	 * The amount of the discount.
	 *
	 * @var float
	 */
	private $amount;

	/**
	 * True whether the discount should be calculated in percentage.
	 * 
	 * @var boolean
	 */
	private $percent;
	
	/**
	 * Class constructor.
	 *
	 * @param 	mixed    $id       The ID of the deal.
	 * @param 	float    $amount   The amount of the deal.
	 * @param 	integer  $percent  True if percent, false when fixed.
	 */
	public function __construct($id, $amount, $percent = false)
	{
		$this->id      = $id;
		$this->amount  = abs((float) $amount);
		$this->percent = (bool) $percent;
	}
	
	/**
	 * Returns the discount identifier.
	 *
	 * @return 	mixed
	 */
	public function getID()
	{
		return $this->id;
	}

	/**
	 * Returns the discount amount.
	 *
	 * @return 	float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * Check if the amount type of the deal is percentage.
	 *
	 * @return 	boolean
	 */
	public function isPercent()
	{
		return $this->percent;
	}

	/**
	 * Check if the amount type of the deal is total.
	 *
	 * @return 	boolean
	 */
	public function isTotal()
	{
		return !$this->percent;
	}

	/**
	 * Applies a discount to the specified amount.
	 *
	 * @param 	float  $amount  The amount to discount.
	 * @param 	float  $base    The initial price of the item.
	 * @param 	mixed  $item    The item/option to discount.
	 *
	 * @return 	float  The resulting amount.
	 */
	public function apply($amount, $base, $item)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		// get internal count
		$count = (int) $this->get('count', 0);
		// immediately increase internal count by one because external plugins
		// might prevent the application of the discount
		$this->set('count', ++$count);

		/**
		 * Trigger event to let external plugins prevent the application of the
		 * discount at runtime. Useful in example to ignore the discount for
		 * certain items and options.
		 *
		 * @param 	self   $discount  The current discount instance.
		 * @param 	float  $amount    The current amount to discount.
		 * @param 	mixed  $item      The item/option to discount.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		if ($dispatcher->not('onBeforeApplyCartDiscount', array($this, $amount, $item)))
		{
			// do not apply the discount
			return $amount;
		}

		// get total discount already applied
		$applied = (float) $this->get('disctot', 0);

		if ($this->isPercent())
		{
			// calculate percentage discount
			$disc_val = $amount * $this->amount / 100.0;
		}
		else
		{
			// get total number of itema
			$length = (int) $this->get('length', 0);

			if ($count < $length)
			{
				// Fixed discount, apply proportionally according to
				// the total cost of the items. Since the discounts
				// might be applied on cascade, we need to calculate
				// the proportion on the base cost of the item.
				$percentage = $base * 100 / (float) $this->get('total', 0);
				$disc_val   = $this->amount * $percentage / 100;
			}
			else
			{
				// We are fetching the last element of the list, instead of calculating the
				// proportional discount, we should subtract the total discount from the coupon
				// value, in order to avoid rounding issues. Let's take as example a coupon of
				// EUR 10 applied on 3 items. The final result would be 3.33 + 3.33 + 3.33,
				// which won't match the initial discount value of the coupon. With this
				// alternative way, the result would be: 10 - 3.33 - 3.33 = 3.34.
				$disc_val = $this->amount - $applied;
			}
		}

		// always round discount to 2 decimals
		$disc_val = round($disc_val, 2);

		/**
		 * Trigger event to let external plugins alter the discount to apply at runtime.
		 *
		 * @param 	float  &$value    The calculated discount value.
		 * @param 	self   $discount  The current discount instance.
		 * @param 	float  $amount    The current amount to discount.
		 * @param 	mixed  $item      The item/option to discount.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onAfterApplyCartDiscount', array(&$disc_val, $this, $amount, $item));

		// discount value cannot be lower than 0 and cannot be higher than the total amount to discount
		$disc_val = max(array($disc_val, 0));
		$disc_val = min(array($disc_val, $amount));

		// subtract discount from amount
		$amount -= $disc_val;

		// update internal total discount
		$this->set('disctot', $applied + $disc_val);

		return $amount;
	}
	
	/**
	 * Magic toString method to debug the discount contents.
	 *
	 * @return  string  The debug string of this object.
	 */
	public function __toString()
	{
		return '<pre>' . print_r($this, true) . '</pre>';
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
			'id'      => $this->id,
			'amount'  => $this->amount,
			'percent' => $this->percent,
		);
	}
}

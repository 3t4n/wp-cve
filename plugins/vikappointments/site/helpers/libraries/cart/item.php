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

VAPLoader::import('libraries.cart.option');
VAPLoader::import('libraries.tax.factory');

/**
 * Class used to handle the items that can be stored within a cart.
 *
 * @since 1.6
 */
class VAPCartItem implements JsonSerializable
{
	/**
	 * The service identifier.
	 *
	 * @var integer
	 */
	private $idService;

	/**
	 * The employee identifier (-1 if not specified).
	 *
	 * @var integer
	 */
	private $idEmployee;

	/**
	 * The service name.
	 *
	 * @var string
	 */
	private $serviceName;

	/**
	 * The employee name (empty if not specified).
	 *
	 * @var string
	 */
	private $employeeName;

	/**
	 * The service price.
	 *
	 * @var float
	 */
	private $price;

	/**
	 * The service duration (in minutes).
	 *
	 * @var integer
	 */
	private $duration;

	/**
	 * The appointment checkin date and time.
	 *
	 * @var string
	 */
	private $checkin;

	/**
	 * The number of people.
	 *
	 * @var integer
	 */
	private $people;

	/**
	 * A factor used to increase the duration by the given number.
	 * For example, if we have a factor equals to 3 and the service lasts 1 hour,
	 * the resulting duration will be equals to 3 hours (3 * 60 min).
	 *
	 * @var integer
	 */
	private $factor = 1;

	/**
	 * Flag used to check if the item is discounted by a specific deal.
	 * For example, when the service is redeemed by using a package.
	 *
	 * @var boolean
	 */
	private $discounted = false;
	
	/**
	 * A list of selected options.
	 *
	 * @var VAPCartOption[]
	 */
	private $options = array();

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
	 * @param 	integer  $id_ser    The service ID.
	 * @param 	integer  $id_emp    The employee ID.
	 * @param 	string 	 $ser_name  The service name.
	 * @param 	string   $emp_name  The employee name.
	 * @param 	float    $price     The service price.
	 * @param 	integer  $duration  The service duration.
	 * @param 	string   $checkin   The appointment check-in datetime (UTC).
	 * @param 	integer  $people    The number of guests.
	 */
	public function __construct($id_ser, $id_emp, $ser_name, $emp_name, $price, $duration, $checkin, $people = 1)
	{
		$this->idService    = (int) $id_ser;
		$this->idEmployee   = (int) $id_emp;
		$this->serviceName  = $ser_name;
		$this->employeeName = $emp_name;
		$this->duration     = (int) $duration;
		$this->checkin      = $checkin;
		$this->people       = (int) $people;

		// calculate totals
		$this->setPrice($price);
	}
	
	/**
	 * Returns the service identifier.
	 *
	 * @return 	integer
	 *
	 * @deprecated 1.8  Use getServiceID() instead.
	 */
	public function getID()
	{
		return $this->getServiceID();
	}

	/**
	 * Returns the service identifier.
	 *
	 * @return 	integer
	 */
	public function getServiceID()
	{
		return $this->idService;
	}
	
	/**
	 * Returns the employee identifier.
	 *
	 * @return 	integer
	 *
	 * @deprecated 1.8  Use getEmployeeID() instead.
	 */
	public function getID2()
	{
		return $this->getEmployeeID();
	}

	/**
	 * Returns the employee identifier.
	 *
	 * @return 	integer
	 */
	public function getEmployeeID()
	{
		return $this->idEmployee;
	}
	
	/**
	 * Returns the service name.
	 *
	 * @return 	string
	 *
	 * @deprecated 1.8  Use getServiceID() instead.
	 */
	public function getName()
	{
		return $this->getServiceName();
	}

	/**
	 * Returns the service name.
	 *
	 * @return 	string
	 */
	public function getServiceName()
	{
		/**
		 * Translate service name at runtime.
		 *
		 * @since 1.7
		 */
		$translator = VAPFactory::getTranslator();
		// translate the specified service
		$tx = $translator->translate('service', $this->getServiceID());

		if ($tx)
		{
			// use the specified translation
			$name = $tx->name;
		}
		else
		{
			// use the default service name
			$name = $this->serviceName;
		}

		return $name;
	}
	
	/**
	 * Returns the employee name.
	 *
	 * @return 	string
	 *
	 * @deprecated 1.8  Use getEmployeeName() instead.
	 */
	public function getName2()
	{
		return $this->getEmployeeName();
	}

	/**
	 * Returns the employee name.
	 *
	 * @return 	string
	 */
	public function getEmployeeName()
	{
		if ($this->getEmployeeID() <= 0)
		{
			// no selected employee
			return '';
		}

		/**
		 * Translate employee name at runtime.
		 *
		 * @since 1.7
		 */
		$translator = VAPFactory::getTranslator();
		// translate the specified employee
		$tx = $translator->translate('employee', $this->getEmployeeID());

		if ($tx)
		{
			// use the specified translation
			$name = $tx->nickname;
		}
		else
		{
			// use the default employee name
			$name = $this->employeeName;
		}

		return $name;
	}
	
	/**
	 * Returns the service price.
	 * In case the service is discounted by a package, the value will be 0.
	 *
	 * @return 	float
	 */
	public function getPrice()
	{
		if ($this->isDiscounted())
		{
			// service discounted by a package
			return 0;
		}

		return max(array(0, $this->price));
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
		$price = $this->getPrice();

		if (!$price)
		{
			// the item has been discounted by a package or has no price
			return 0;
		}

		// iterate all options in search of a discount
		foreach ($this->options as $option)
		{
			// calculate option total
			$total = $option->getTotalPrice();

			if ($total < 0)
			{
				// in case we have a total lower than 0, treat the option as a discount
				// and subtract it from the service price
				$price += $total;
			}
		}

		// in case the cart was specified, check whether there are some
		// discounts to apply
		if (!$cart || !$cart->getDiscounts())
		{
			// nope, use default price
			return $price;
		}

		foreach ($cart->getDiscounts() as $discount)
		{
			$old = $price;

			// apply discount on cascade
			$price = $discount->apply($price, $price, $this);

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
	 * Returns the net price of the service.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getPriceNet($cart = null)
	{
		$base = $this->getPrice();

		if (!$base)
		{
			// the item has been discounted by a package or has no price
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $base)
		{
			// no discount, use default total net
			return $this->totals->net;
		}

		$options = array();
		$options['subject'] = 'service';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate net of discounted item
		return VAPTaxFactory::calculate($this->getServiceID(), $price, $options)->net;
	}

	/**
	 * Returns the taxes of the service cost.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getPriceTax($cart = null)
	{
		$base = $this->getPrice();

		if (!$base)
		{
			// the item has been discounted by a package or has no price
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $base)
		{
			// no discount, use default total tax
			return $this->totals->tax;
		}

		$options = array();
		$options['subject'] = 'service';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate taxes of discounted item
		return VAPTaxFactory::calculate($this->getServiceID(), $price, $options)->tax;
	}

	/**
	 * Returns the gross price of the service.
	 *
	 * @param 	mixed  $cart  When specified, the system will try to apply
	 *                        the discounts to the base price.
	 *
	 * @return 	float
	 *
	 * @since 	1.7
	 */
	public function getPriceGross($cart = null)
	{
		$base = $this->getPrice();

		if (!$base)
		{
			// the item has been discounted by a package or has no price
			return 0.0;
		}

		// get discounted price
		$price = $this->getDiscountedPrice($cart);

		if ($price == $base)
		{
			// no discount, use default total gross
			return $this->totals->gross;
		}

		$options = array();
		$options['subject'] = 'service';
		// $options['id_user'] = JFactory::getUser()->id;

		// re-calculate gross of discounted item
		return VAPTaxFactory::calculate($this->getServiceID(), $price, $options)->gross;
	}

	/**
	 * Sets the service price.
	 *
	 * @param 	float  $price  The service cost.
	 *
	 * @return 	self   This object to support chaining.
	 *
	 * @since 	1.6.6
	 */
	public function setPrice($price)
	{
		$this->price = (float) $price;

		$options = array();
		$options['subject'] = 'service';
		// $options['id_user'] = JFactory::getUser()->id;

		// calculate taxes
		$this->totals = VAPTaxFactory::calculate($this->getServiceID(), $this->getPrice(), $options);

		return $this;
	}
	
	/**
	 * Returns the total cost of the service.
	 *
	 * @return 	float
	 *
	 * @uses 	getPrice()
	 */
	public function getTotalCost()
	{
		$total = $this->getPrice();

		foreach ($this->options as $o)
		{
			$total += $o->getTotalPrice();
		}

		return $total;
	}

	/**
	 * Returns the total net.
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
		$net = $this->getPriceNet($cart);

		foreach ($this->options as $o)
		{
			$net += $o->getTotalNet($cart);
		}

		return $net;
	}

	/**
	 * Returns the total taxes.
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
		$tax = $this->getPriceTax($cart);

		foreach ($this->options as $o)
		{
			$tax += $o->getTotalTax($cart);
		}

		return $tax;
	}

	/**
	 * Returns the total gross.
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
		$gross = $this->getPriceGross($cart);

		foreach ($this->options as $o)
		{
			$gross += $o->getTotalGross($cart);
		}

		return $gross;
	}
	
	/**
	 * Returns the service base duration (in minutes).
	 * 
	 * @param 	boolean  $total  True to include the extra duration that might be applied
	 *                           by the selected options (added @since 1.7.3).
	 *
	 * @return 	integer
	 */
	public function getDuration($total = true)
	{
		$duration = $this->duration;

		if ($total)
		{
			/**
			 * Apply the extra duration provided by the options.
			 * 
			 * @since 1.7.3
			 */
			foreach ($this->options as $o)
			{
				$duration += $o->getTotalDuration();
			}
		}

		return $duration;
	}

	/**
	 * Sets the service duration.
	 *
	 * @param 	integer  $duration  The service duration (in minutes).
	 *
	 * @return 	self 	 This object to support chaining.
	 *
	 * @since 	1.6.6
	 */
	public function setDuration($duration)
	{
		$this->duration = abs((int) $duration);

		return $this;
	}
	
	/**
	 * Returns the check-in timestamp (UTC).
	 *
	 * @return 	integer
	 */
	public function getCheckinTimestamp()
	{
		return JDate::getInstance($this->checkin)->getTimestamp();
	}
	
	/**
	 * Returns the formatted check-in.
	 *
	 * @param 	string 	$format  The date format.
	 * @param 	mixed   $local   False to return the date in UTC,
	 *                           'customer' to adjust the date into
	 *                           the user timezone, 'employee' to
	 *                           use the local timezone set by the 
	 *                           operator, or a DateTimeZone object.
	 *
	 * @return 	string
	 */
	public function getCheckinDate($format = null, $local = false)
	{
		return $this->formatDate($this->checkin, $format, $local);
	}

	/**
	 * Returns the check-out timestamp (UTC).
	 *
	 * @return 	integer
	 *
	 * @since 	1.7
	 */
	public function getCheckoutTimestamp()
	{
		$dt = JDate::getInstance($this->checkin);
		$dt->modify('+' . $this->getDuration() . ' minutes');
		return $dt->getTimestamp();
	}

	/**
	 * Returns the formatted check-out.
	 *
	 * @param 	string 	$format  The date format.
	 * @param 	mixed   $local   False to return the date in UTC,
	 *                           'customer' to adjust the date into
	 *                           the user timezone, 'employee' to
	 *                           use the local timezone set by the 
	 *                           operator, or a DateTimeZone object.
	 *
	 * @return 	string
	 *
	 * @since 	1.7
	 */
	public function getCheckoutDate($format = null, $local = false)
	{
		$dt = JDate::getInstance($this->checkin);
		$dt->modify('+' . $this->getDuration() . ' minutes');

		return $this->formatDate($dt, $format, $local);
	}

	/**
	 * Helper method to format the given date.
	 *
	 * @param 	mixed   $date    The date to format.
	 * @param 	string 	$format  The format to use.
	 * @param 	mixed   $local   False to return the date in UTC,
	 *                           'customer' to adjust the date into
	 *                           the user timezone, 'employee' to
	 *                           use the local timezone set by the 
	 *                           operator, or a DateTimeZone object.
	 *
	 * @return 	string
	 *
	 * @since 	1.7
	 */
	private function formatDate($date, $format = null, $local = false)
	{
		if ($local instanceof DateTimeZone)
		{
			// use given timezone
			$tz = $local;
		}
		else if (preg_match("/^(user|customer)$/i", $local))
		{
			// get timezone of the current user
			$tz = VikAppointments::getUserTimezone();
		}
		else if (preg_match("/^(emp|employee)$/i", $local))
		{
			// get timezone of the employee
			$tz = JModelVAP::getInstance('employee')->getTimezone($this->idEmployee);
		}
		else
		{
			$tz = null;
		}

		if (is_string($date))
		{
			// create date instance
			$date = new JDate($date);
		}

		if ($tz)
		{
			// adjust to fetched timezone
			$date->setTimezone($tz);
		}

		if (!$format)
		{
			// use default military format if not specified
			$format = 'Y-m-d H:i:s';
		}

		// format check-in date
		return $date->format($format, (bool) $tz);
	}
	
	/**
	 * Returns the number of guests.
	 *
	 * @return 	integer
	 */
	public function getPeople()
	{
		return $this->people;
	}
	
	/**
	 * Returns the appointment details.
	 *
	 * @return 	string
	 */
	public function getDetails()
	{
		// include service name
		$details = $this->getServiceName();

		if ($this->getEmployeeID() > 0)
		{
			// include employee name
			$details .= ' - ' . $this->getEmployeeName();
		}

		// wrap service in a tag
		$details = '<span class="cart-item-summary-service">' . $details . '</span>';

		// fetch details separator
		$separator = trim(JText::translate('VAP_FOR_DATE_SEPARATOR'));

		if ($separator)
		{
			// include the separator in a tag
			$details .= '<span class="cart-item-summary-separator">&nbsp;' . $separator . '&nbsp;</span>';
		}

		// format check-in date time, adjusted to the user timezone
		$checkin = $this->getCheckinDate(JText::translate('DATE_FORMAT_LC2'), 'customer');
		// include check-in date time within the summary details
		$details .= '<span class="cart-item-summary-checkin">' . $checkin . '</span>';

		// get working days model
		$worktime = JModelVAP::getInstance('worktime');
		// fetch the location matching the specified appointment details
		$id_loc = $worktime->getLocation($this->getCheckinDate(), $this->getServiceID(), $this->getEmployeeID());

		if ($id_loc)
		{
			// get location model
			$locModel = JModelVAP::getInstance('location');
			// get location details
			$location = $locModel->getInfo($id_loc);

			if ($location)
			{
				// include location details
				$details .= '<br /><span class="cart-item-summary-location">' . $location->text . '</span>';
			}
		}

		/**
		 * Trigger hook to manipulate the description of the item that is displayed
		 * within the summary cart.
		 *
		 * @param 	string       &$details  The description to show.
		 * @param 	VAPCartItem  $item      The item instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onPrepareCartItemDetails', array(&$details, $this));

		return $details;
	}

	/**
	 * Checks if the service is discounted.
	 * The "discounted" term here means that the item has been
	 * redeemed by a package and has no cost.
	 *
	 * @return 	boolean
	 */
	public function isDiscounted()
	{
		return $this->discounted;
	}

	/**
	 * Marks the service as discounted or not.
	 *
	 * @param 	mixed  $s  True if discounted, false otherwise.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setDiscounted($s)
	{
		$this->discounted = (bool) $s;

		return $this;
	}

	/**
	 * Returns the duration factor of the service.
	 *
	 * @return 	integer
	 */
	public function getFactor()
	{
		return $this->factor;
	}

	/**
	 * Sets the duration factor.
	 *
	 * @param 	integer  $f  The factor.
	 *
	 * @return 	self     This object to support chaining.
	 */
	public function setFactor($f)
	{
		// make sure the factor has changed
		if ($f != $this->factor)
		{
			// recalculate base price and duration
			$this->duration /= $this->factor;
			$this->price    /= $this->factor;

			// update factor
			$this->factor = max(array(1, (int) $f));

			// multiply duration and prices by the new factor
			$this->duration *= $this->factor;
			$this->price    *= $this->factor;

			// refresh totals
			$this->setPrice($this->price);
		}

		return $this;
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
	 * Adds a new option within the list.
	 *
	 * @param 	VAPCartOption  $opt  The option to add.	
	 *
	 * @return 	self           This object to support chaining.
	 *
	 * @uses 	indexOf()
	 */
	public function addOption(VAPCartOption $opt)
	{
		// check whether the specified option already exists
		$index = $this->indexOf($opt->getID());

		if ($index != -1)
		{
			// update the existing one
			$this->options[$index]->add($opt->getQuantity());
		}
		else
		{
			// add the option at the end of the list
			$this->options[] = $opt;
		}

		return $this;
	}
	
	/**
	 * Removes the specified option.
	 *
	 * @param 	integer  $id 	The option ID.
	 * @param 	integer  $unit  The number of units to remove.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	indexOf()
	 */
	public function removeOption($id, $unit = 1) 
	{
		$index = $this->indexOf($id);

		if ($index != -1)
		{
			// remove by the specified units
			$q = $this->options[$index]->remove($unit);
			
			if ($q == 0)
			{
				// no more units, delete the option from the list
				array_splice($this->options, $index, 1);
			}

			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the position within the list of the specified option.
	 *
	 * @param 	integer  $id  The option ID.
	 *
	 * @return 	integer  The option index if exists, -1 otherwise.
	 *
	 * @uses 	getOptionsLength()
	 */
	public function indexOf($id)
	{
		for ($i = 0; $i < $this->getOptionsLength(); $i++)
		{
			if ($this->options[$i]->getID() == $id)
			{
				return $i;
			}
		}

		return -1;
	}
	
	/**
	 * Returns the option at the specified index.
	 *
	 * @param 	integer  $index  The option index.
	 *
	 * @return 	mixed 	 The option if exists, false otherwise.
	 *
	 * @uses 	getoptionsLength()
	 */
	public function getOptionAt($index)
	{
		if ($index >= 0 && $index < $this->getOptionsLength())
		{
			return $this->options[$index];
		}
		
		return null;
	}
	
	/**
	 * Returns the number of options within the list.
	 * The list may contain also options that are no more
	 * active (quantity less than 1).
	 * 
	 * @return 	integer
	 */
	public function getOptionsLength()
	{
		return count($this->options);
	}
	
	/**
	 * Empties the options list.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function emptyOptions()
	{
		$this->options = array();

		return $this;
	}
	
	/**
	 * Checks if there are no options within the list.
	 *
	 * @return 	boolean
	 */
	public function isEmpty()
	{
		return count($this->options) == 0;
	}
	
	/**
	 * Balances the item in order to remove all the options
	 * with quantity lower than 1.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public function balance()
	{
		// do nothing, balance is automatically made every
		// time an option gets removed

		return $this;
	}
	
	/**
	 * Returns a list containing all the active options.
	 *
	 * @return 	array
	 */
	public function getOptionsList()
	{
		return $this->options;
	}
	
	/**
	 * Returns the first available index to push a new option.
	 * Used to replace a unactive option with a new one.
	 *
	 * @return 	integer
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	protected function getFirstAvailableIndex()
	{
		return $this->getOptionsLength();
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
			'id_service'    => $this->getServiceID(),
			'id_employee'   => $this->getEmployeeID(),
			'service_name'  => $this->getServiceName(),
			'employee_name' => $this->getEmployeeName(),
			'price'         => $this->getPrice(),
			'totalcost'     => $this->getTotalCost(),
			'duration'      => $this->getDuration(),
			'checkin'       => $this->getCheckinDate(),
			'people'        => $this->getPeople(),
			'details'       => $this->getDetails(),
			'totals'        => $this->totals,
			'options'       => array(),
		);

		// replicate checkin date time by using a different attribute
		$arr['checkin_ts'] = $arr['checkin'];
		
		foreach ($this->options as $o)
		{
			$arr['options'][] = $o->toArray();
		}
		
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

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
 * Timeline block wrapper.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineBlock implements JsonSerializable
{
	/**
	 * The check-in date time.
	 *
	 * @var JDate
	 */
	protected $checkin;

	/**
	 * The check-out date time.
	 *
	 * @var JDate
	 */
	protected $checkout;

	/**
	 * The availability status.
	 *
	 * @var integer
	 */
	protected $status;

	/**
	 * An optional cost for this time slot.
	 *
	 * @var float
	 */
	protected $price = null;

	/**
	 * A list of matching rates.
	 *
	 * @var array
	 */
	protected $ratesTrace = array();

	/**
	 * The total number of participants.
	 *
	 * @var integer
	 */
	protected $occupancy = null;

	/**
	 * The maximum number of allowed participants.
	 *
	 * @var integer
	 */
	protected $capacity = null;

	/**
	 * Class constructor.
	 *
	 * @param 	mixed    $date       Either a date string or a JDate instance.
	 * @param 	integer  $status     The availability status.
	 * @param 	integer  $duration   The time slot duration (in minutes).
	 * @param 	float    $price      An optional time slot price.
	 * @param 	array    $occupancy  An optional occupancy array.
	 */
	public function __construct($date, $status, $duration, $price = null, $occupancy = null)
	{
		if ($date instanceof JDate)
		{
			// register date instance
			$this->checkin = $date;
		}
		else
		{
			// create date instance
			$this->checkin = new JDate($date);
		}

		// create check-out date by adding the duration to the check-in
		$this->checkout = clone $this->checkin;
		$this->checkout->modify('+' . $duration . ' minutes');

		$this->status = (int) $status;

		if (!is_null($price))
		{
			$this->setPrice($price);
		}

		if (!is_null($occupancy))
		{
			$this->setOccupancy($occupancy);
		}
	}

	/**
	 * Magic method used to safely access internal properties
	 * without giving the possibility to alter them.
	 *
	 * @param 	string 	$name  The property name.
	 *
	 * @return 	mixed 	The property value. 
	 */
	public function __get($name)
	{
		if ($name == 'date')
		{
			// create a clone of the date
			return clone $this->checkin;
		}

		// make sure the property is set
		if (isset($this->{$name}))
		{
			return $this->{$name};
		}

		// the specified property does not exist
		return null;
	}

	/**
	 * Checks whether the time slot is available.
	 *
	 * @return 	boolean
	 */
	public function isAvailable()
	{
		return $this->status == 1;
	}

	/**
	 * Checks whether the time slot is occupied.
	 *
	 * @return 	boolean
	 */
	public function isOccupied()
	{
		return $this->status == 0;
	}

	/**
	 * Updates the status of the time slot.
	 *
	 * @param 	mixed  Either an integer or a boolean.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setStatus($status)
	{
		$this->status = (int) $status;

		return $this;
	}

	/**
	 * Returns the check-in date time string according to the specified format.
	 *
	 * @param 	string  $format  The format to use.
	 * @param 	mixed   $tz      An optional timezone.
	 *
	 * @return 	string  The formatted check-in.
	 */
	public function checkin($format = 'Y-m-d H:i:s', $tz = null)
	{
		// format check-in
		return $this->format('checkin', $format, $tz);
	}

	/**
	 * Returns the check-out date time string according to the specified format.
	 *
	 * @param 	string  $format  The format to use.
	 * @param 	mixed   $tz      An optional timezone.
	 *
	 * @return 	string  The formatted check-out.
	 */
	public function checkout($format = 'Y-m-d H:i:s', $tz = null)
	{
		// format check-in
		return $this->format('checkout', $format, $tz);
	}

	/**
	 * Returns a date time string according to the specified format.
	 *
	 * @param 	string 	$what    The date to format (checkin or checkout).
	 * @param 	string  $format  The format to use.
	 * @param 	mixed   $tz      An optional timezone.
	 *
	 * @return 	string  The formatted date-time.
	 */
	protected function format($what, $format, $tz = null)
	{
		if ($tz)
		{
			// get previous timezone
			$old_tz = $this->{$what}->getTimezone();

			if (!$tz instanceof DateTimeZone)
			{
				// create timezone instance first
				$tz = new DateTimeZone($tz);
			}

			// set new timezone
			$this->{$what}->setTimezone($tz);
		}

		// format date time adjusted to local area
		$str = $this->{$what}->format($format, $local = true);

		if ($tz && !empty($old_tz))
		{
			// restore previous timezone
			$this->{$what}->setTimezone($old_tz);
		}

		return $str;
	}

	/**
	 * Sets the time slot price.
	 *
	 * @param 	float  $price  The time slot price.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setPrice($price)
	{
		// price cannot be lower than 0
		$this->price = max(array(0, (float) $price));

		return $this;
	}

	/**
	 * Sets a trace of matching special rates.
	 *
	 * @param 	array  $trace  The rates trace.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setRatesTrace(array $trace)
	{
		$this->ratesTrace = $trace;

		return $this;
	}

	/**
	 * Sets the time slot occupancy.
	 *
	 * @param 	float  $price  The time slot price.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setOccupancy($count, $max = null)
	{
		if (is_array($count))
		{
			// set occupancy from array
			$this->occupancy = (int) $count[0];
			$this->capacity  = (int) $count[1];
		}
		else
		{
			// set occupancy from arguments
			$this->occupancy = (int) $count;
			$this->capacity  = (int) $max;
		}

		// the total capacity cannot be lower than 1
		$this->capacity = max(array(1, $this->capacity));

		// the occupancy cannot be lower than 0 and cannot
		// be higher than the max capacity
		$this->occupancy = max(array(0, $this->occupancy));
		$this->occupancy = min(array($this->occupancy, $this->capacity));

		return $this;
	}

	/**
	 * Converts this object into an associative array.
	 *
	 * @return 	array
	 */
	public function toArray()
	{
		$arr = get_object_vars($this);

		$arr['utc'] = $arr['checkin']->format('Y-m-d\TH:i:s');

		// replace date objects with formatted date-time
		$arr['checkin']  = $this->checkin('Y-m-d\TH:i:s');
		$arr['checkout'] = $this->checkout('Y-m-d\TH:i:s');

		return $arr;
	}

	/**
	 * Creates an associative array, containing all the supported properties,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  array
	 *
	 * @see     JsonSerializable
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return $this->toArray();
	}
}

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
 * Encapsulates the status of a payment transaction.
 *
 * The I/O of this class MUST be the same for all the E4J programs that support
 * extendable payment methods.
 *
 * @note  The class prefix is equals to the 3-letter name of the program,
 *        "VAP" in this case.
 *
 * @since 1.7.1
 */
class VAPPaymentStatus implements ArrayAccess
{
	/**
	 * The payment status.
	 *
	 * @var boolean
	 */
	protected $verified = false;

	/**
	 * Property used to track what is happening
	 * during the validation of the payment.
	 *
	 * @var string
	 */
	protected $log = '';

	/**
	 * The total paid amount, if available.
	 *
	 * @var float|null
	 */
	protected $totPaid = null;

	/**
	 * Additional transaction data.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Magic method to check whether an internal property exists.
	 *
	 * @param 	string 	 $name 	The property name.
	 *
	 * @return 	boolean  True if exists, false otherwise.
	 */
	public function __isset($name)
	{
		return isset($this->{$name}) || isset($this->data[$name]);
	}

	/**
	 * Magic method to access the property of the object.
	 *
	 * @param 	string 	$name 	The property name.
	 *
	 * @return 	mixed 	The property value.
	 */
	public function __get($name)
	{
		if (property_exists($this, $name))
		{
			return $this->{$name};
		}
		else if (isset($this->data[$name]))
		{
			return $this->data[$name];
		}

		return null;
	}

	/**
	 * Checks whether the payment was successful.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 */
	public function isVerified()
	{
		return $this->verified;
	}

	/**
	 * Marks the payment status as verified or failed.
	 *
	 * @param 	boolean  $status  True if verified (default).
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function setVerified($status = true)
	{
		$this->verified = (bool) $status;

		return $this;
	}

	/**
	 * Sets the total amount that have been paid.
	 *
	 * @param 	float 	$amount  The total paid.
	 * 
	 * @return 	self 	This object to support chaining.
	 */
	public function setTotPaid($amount)
	{
		$this->totPaid = (float) $amount;

		return $this;
	}

	/**
	 * Tracks the given log.
	 *
	 * @param 	mixed 	$log 	A string or a non-scalar value.
	 * 							An array will be logged using print_r.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setLog($log)
	{
		if (!is_scalar($log))
		{
			// use print_r for non scalar logs
			$log = print_r($log, true);
		}

		$this->log = $log;

		return $this;
	}

	/**
	 * Appends the given log to the existing string.
	 *
	 * @param 	mixed 	$log 	A string or a non-scalar value.
	 * 							An array will be logged using print_r.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @uses 	setLog()
	 */
	public function appendLog($log, $separator = "\n")
	{
		// keep current log
		$current = $this->log;

		// set log using the proper method
		$this->setLog($log);

		// re-build the log by prepending the existing logs
		$this->log = $current . $separator . $this->log;

		return $this;
	}

	/**
	 * Prepends the given log to the existing string.
	 *
	 * @param 	mixed 	$log 	A string or a non-scalar value.
	 * 							An array will be logged using print_r.
	 *
	 * @return 	self 	This object to support chaining.
	 *
	 * @uses 	setLog()
	 */
	public function prependLog($log, $separator = "\n")
	{
		// keep current log
		$current = $this->log;

		// set log using the proper method
		$this->setLog($log);

		// re-build the log by appending the existing logs
		$this->log .= $separator . $current;

		return $this;
	}

	/**
	 * Registers the additional transaction data.
	 *
	 * @param 	string 	$key  The transaction key.
	 * @param 	mixed 	$val  The transaction value.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setData($key, $val)
	{
		$this->data[$key] = $val;

		return $this;
	}

	/**
	 * Checks whether an offset exists in the iterator.
	 *
	 * @param   mixed    $offset  The array offset.
	 *
	 * @return  boolean  True if the offset exists, false otherwise.
	 *
	 * @see 	ArrayAccess
	 */
	#[ReturnTypeWillChange]
	public function offsetExists($offset)
	{
		$property = $this->attr2prop($offset);

		return isset($this->{$property});
	}

	/**
	 * Gets an offset in the iterator.
	 *
	 * @param   mixed  $offset  The array offset.
	 *
	 * @return  mixed  The array value if it exists, null otherwise.
	 *
	 * @see 	ArrayAccess
	 */
	#[ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		$property = $this->attr2prop($offset);

		if (isset($this->{$property}))
		{
			return $this->{$property};
		}

		return null;
	}

	/**
	 * Sets an offset in the iterator.
	 *
	 * @param   mixed  $offset  The array offset.
	 * @param   mixed  $value   The array value.
	 *
	 * @return  void
	 *
	 * @see 	ArrayAccess
	 */
	#[ReturnTypeWillChange]
	public function offsetSet($offset, $value)
	{
		// prevent manual property setter
	}

	/**
	 * Unsets an offset in the iterator.
	 *
	 * @param   mixed  $offset  The array offset.
	 *
	 * @return  void
	 *
	 * @see 	ArrayAccess
	 */
	#[ReturnTypeWillChange]
	public function offsetUnset($offset)
	{
		$property = $this->attr2prop($offset);

		unset($this->{$property});
	}

	/**
	 * Converts a snake case text into camel case.
	 * In example "foo_bar_baz" becomes "fooBarBaz".
	 *
	 * @param 	string  $name  The attribute name.
	 *
	 * @param 	string 	The property name.
	 */
	private function attr2prop($name)
	{
		$name = preg_replace("/_+/", ' ', $name);
		$name = ucwords($name);
		$name = preg_replace("/\s+/", '', $name);

		return lcfirst($name);
	}
}

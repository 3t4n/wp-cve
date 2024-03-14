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
 * Wraps the results returned by the subscribers.
 * 
 * @since 1.7.3
 */
class VAPActionResult implements IteratorAggregate
{
	/**
	 * An array of results.
	 * 
	 * @var array
	 */
	private $results = [];

	/**
	 * Class Constructor.
	 * 
	 * @param 	mixed  $results  Either an array or an action result instance
	 */
	final public function __construct($results = [])
	{
		if ($results instanceof VAPActionResult)
		{
			// in case of a self instance, extracts the array results
			$results = $results->toArray();
		}

		if (!is_array($results))
		{
			// not an array, throw exception
			throw new InvalidArgumentException(sprintf('The argument must be an array, %s given', gettype($results)), 400);
		}

		// map the values according to the requirements of the class
		$results = array_map([$this, 'map'], $results);

		// get rid of NULL elements
		$results = array_filter($results, function($elem)
		{
			return $elem !== null;
		});

		// take only the values of the array
		$this->results = array_values($results);
	}

	/**
	 * Filters the resulting elements while constructing the object.
	 * Children classes can override this method to sanitize the 
	 * received results.
	 * 
	 * @param 	mixed 	$elem  The element to map.
	 * 
	 * @return 	mixed   The mapped element.
	 */
	protected function map($elem)
	{
		return $elem;
	}

	/**
	 * Returns the results array.
	 * 
	 * @return array
	 */
	final public function toArray()
	{
		return $this->results;
	}

	/**
	 * Checks whether the specified value exists within the
	 * array of results. If omitted, it will check whether
	 * the list is empty or not.
	 * 
	 * @param 	mixed    $value  The value to search.
	 * 
	 * @return 	boolean  True if exists, false otherwise.
	 */
	final public function has($value = null)
	{
		if (is_null($value))
		{
			// check whether the list is empty or not
			return (bool) $this->results;
		}

		// look for the given value
		return in_array($value, $this->results, true);
	}

	/**
	 * Extracts the first returned element.
	 * 
	 * @return 	mixed
	 */
	final public function first()
	{
		return $this->results ? $this->results[0] : null;
	}

	/**
	 * Extracts the last returned element.
	 * 
	 * @return 	mixed
	 */
	final public function last()
	{
		return $this->results ? $this->results[count($this->results) - 1] : null;
	}

	/**
	 * Returns the iterator interface.
	 *
	 * @return  Traversable
	 *
	 * @see     IteratorAggregate
	 */
	#[ReturnTypeWillChange]
	final public function getIterator()
	{
		return new ArrayIterator($this->toArray());
	}
}

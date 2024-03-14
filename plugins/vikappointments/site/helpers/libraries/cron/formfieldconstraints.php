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
 * Class used to wrap all the constraints (attributes and properties) of an input field.
 *
 * @since 1.5
 * @since 1.7 Renamed from CronFormFieldConstraints.
 */
class VAPCronFormFieldConstraints implements IteratorAggregate
{
	/**
	 * The list containing all the attributes of the field.
	 *
	 * @var array
	 */
	private $attributes;

	/**
	 * The construct of the cron form field constraints to 
	 * initialize the required parameters of this object.
	 *
	 * @param 	array  $attributes 	The field attributes.
	 *
	 * @uses 	setAttributes()
	 */
	public function __construct(array $attributes = array())
	{
		$this->setAttributes($attributes);
	}

	/**
	 * Set all the attributes in the list.
	 *
	 * @param 	array 	$attributes  The attributes to restrict a field.
	 *								 The key of a row will be used as attribute
	 *								 and the content will be used as value.
	 *
	 * @return 	self 	Returns this object to support chaining.
	 */
	public function setAttributes($attributes)
	{
		if (is_array($attributes) && count(array_keys($attributes)) > 0)
		{
			$this->attributes = $attributes;
		}
	}

	/**
	 * Returns the associative array containing all the attributes of this object.
	 *
	 * @return 	array 	The array containing the attributes.
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Insert a new attribute in the constraints list.
	 *
	 * @param 	string  $key 		The standard name of the attribute.
	 * @param 	string  $val 		An acceptable value of the attribute.
	 * @param 	string  $separator  The separator to use if the setting
	 * 								is not empty. Null to overwrite always.
	 * 								 
	 *
	 * @return 	self	Returns this object to support chaining.
	 */
	public function add($key, $val, $separator = null)
	{
		if ($separator === null || empty($this->attributes[$key]))
		{
			$this->attributes[$key] = $val;
		}
		else
		{
			$this->attributes[$key] .= $separator . $val;
		}

		return $this;
	}

	/**
	 * Returns the value of the specified attribute.
	 * Returns the default value if the attribute doesn't exist.
	 *
	 * @param 	string  $key  The standard name of the attribute.
	 * @param 	mixed   $def  The default value to use.
	 *
	 * @return 	mixed 	The value of the attribute.	
	 */	
	public function get($key, $def = false)
	{
		if (array_key_exists($key, $this->attributes))
		{
			return $this->attributes[$key];
		}

		return $def;
	}

	/**
	 * Creates an array iterator for ease of use.
	 *
	 * @return 	ArrayIterator
	 *
	 * @since 	1.7
	 */
	#[ReturnTypeWillChange]
	public function getIterator()
    {
        return new ArrayIterator($this->getAttributes());
    }
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPCronFormFieldConstraints', 'CronFormFieldConstraints');

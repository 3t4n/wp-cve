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
 * Class used to represent the shape of a custom item.
 *
 * @since 	1.5
 */
abstract class CustomShape
{
	/**
	 * The array containing all the attributes of this custom item.
	 * The attributes of this custom item cannot be directly accessed.
	 *
	 * @var array 
	 *
	 * @see  get()
	 */
	private $params;

	/**
	 * The construct sets all the required attributes of this class.
	 *
	 * @param 	array 	$params  Default empty array.
	 *
	 * @uses 	setParams()
	 */
	public function __construct(array $params = array())
	{
		$this->setParams($params);
	}

	/**
	 * Sets all the attributes of this custom item.
	 *
	 * @param 	array 	$params  The attributes to use.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function setParams($params)
	{
		if (is_array($params))
		{
			$this->params = $params;
		}

		return $this;
	}

	/**
	 * Returns the value of the attribute specified. Returns NULL is the specified attribute doesn't exist.
	 * The attributes of this custom item are not accessible from external classes.
	 *
	 * @param 	string 	$key  The name of the attribute.
	 *
	 * @return 	string 	The value of the attribute.
	 */
	public function get($key)
	{
		if (array_key_exists($key, $this->params))
		{
			return $this->params[$key];
		}

		return null;
	}

	/**
	 * Adds a new parameter with the specified attribute.
	 *
	 * @param 	string 	$key 	The attribute of the parameter.
	 * @param 	string 	$param 	The value of the parameter.
	 *
	 * @return 	self	This object to support chaining.
	 */
	public function add($key, $param)
	{
		$this->params[$key] = $param;

		return $this;
	}

	/**
	 * Builds and returns the html structure of the custom menu item.
	 * This method must be implemented to define a specific graphic of the custom item.
	 *
	 * @return 	string 	The html of the custom item.
	 */
	abstract public function buildHtml();
}

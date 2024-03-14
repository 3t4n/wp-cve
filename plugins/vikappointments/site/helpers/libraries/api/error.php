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
 * The API error representation.
 *
 * @since 1.7
 */
class VAPApiError implements JsonSerializable
{
	/**
	 * The identifier code of the error.
	 *
	 * @var integer
	 */
	public $errcode;

	/**
	 * The text description of the error.
	 *
	 * @var string
	 */
	public $error;

	/**
	 * Class constructor.
	 * 
	 * @param 	integer  $errcode  The code identifier.
	 * @param 	string 	 $error    The text description.
	 */
	public function __construct($errcode, $error)
	{
		$this->errcode = $errcode;
		$this->error   = $error;
	}

	/**
	 * Return this object encoded in JSON.
	 *
	 * @return 	string 	This object in JSON.
	 */
	public function toJSON()
	{
		return json_encode($this);
	}

	/**
	 * Creates a standard object, containing all the supported properties,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  object
	 *
	 * @see     JsonSerializable
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return get_object_vars($this);
	}
}

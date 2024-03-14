<?php
/**
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */
Namespace Accordeonmenuck;
// No direct access
defined('CK_LOADED') or die;

Class CKParams {

	protected $_attrs = array();

	public function __construct($properties = null)
	{
		if ($properties !== null)
		{
			$this->setProperties($properties);
		}
	}

	public function get($property, $default = null, $notNull = false)
	{
		if (isset($this->$property) && ($notNull === false || $this->$property !== '' ))
		{
			return $this->$property;
		}

		return $default;
	}

	public function set($property, $value = null)
	{
		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;

		return $previous;
	}

	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}

			return true;
		}

		return false;
	}
}

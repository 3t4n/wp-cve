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
 * Populate the options array with the existing cities,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7.3
 */
class ImportColumnCity extends ImportColumn
{
	/**
	 * Cities cache.
	 *
	 * @var array
	 */
	private static $cities = null;

	/**
	 * Binds the internal properties with the given array/object.
	 *
	 * @param 	mixed  $data  Either an array or an object.
	 *
	 * @return 	void
	 */
	protected function setup($data)
	{
		// use parent to set up data
		parent::setup($data);

		foreach (static::getCities() as $city)
		{
			// register city as option
			$this->options[$city['id']] = $city['city_name'];
		}
	}

	/**
	 * Loads a list of available cities.
	 *
	 * @return 	array
	 */
	protected static function getCities()
	{
		// load cities only once
		if (is_null(static::$cities))
		{
			// sort cities list by name ASC
			static::$cities = VAPLocations::getCities(0, 'city_name');
		}

		return static::$cities;
	}
}

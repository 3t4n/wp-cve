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
 * Populate the options array with the existing countries,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7.3
 */
class ImportColumnCountry extends ImportColumn
{
	/**
	 * Countries cache.
	 *
	 * @var array
	 */
	private static $countries = null;

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

		foreach (static::getCountries() as $country)
		{
			// register country as option
			$this->options[$country['id']] = $country['country_name'];
		}
	}

	/**
	 * Loads a list of available countries.
	 *
	 * @return 	array
	 */
	protected static function getCountries()
	{
		// load countries only once
		if (is_null(static::$countries))
		{
			// sort countries list by name ASC
			static::$countries = VAPLocations::getCountries('country_name');
		}

		return static::$countries;
	}
}

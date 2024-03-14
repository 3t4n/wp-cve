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
 * VikAppointments countries class handler.
 *
 * @since 1.7
 */
abstract class VAPHtmlCountries
{
	/**
	 * Countries cache/lookup.
	 *
	 * @var array
	 */
	protected static $countries = array();

	/**
	 * Returns the list of supported countries.
	 *
	 * @param 	mixed 	$orderby  The ordering column or an array with column and direction.
	 *
	 * @return 	array 	The countries list.
	 */
	public static function getlist($orderby = 'id')
	{
		if (is_scalar($orderby))
		{
			$orderby = array($orderby, 'ASC');
		}

		// route ordering column
		$orderby[0] = static::ordcol($orderby[0]);

		static $all = false;

		// check if we already retrieved the countries from the database
		if (!$all)
		{
			// do not execute again
			$all = true;

			// clear countries list
			static::$countries = array();

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_countries'))
				->where($dbo->qn('published') . ' = 1')
				->order($dbo->qn('id') . ' ASC');
			
			$dbo->setQuery($q);
			
			// iterate countries and cache them
			foreach ($dbo->loadObjectList() as $row)
			{
				static::$countries[$row->country_2_code] = static::db2country($row);
			}
		}

		// keep countries
		$list = array_values(static::$countries);

		// do not sort in case of default ordering
		if ($orderby[0] != 'id' || $orderby[1] != 'ASC')
		{
			// sort the countries with the given ordering
			usort($list, function($a, $b) use ($orderby)
			{
				// fetch ordering direction factor
				$factor = strcasecmp($orderby[1], 'DESC') ? 1 : -1;

				if ($a->{$orderby[0]} > $b->{$orderby[0]})
				{
					return 1 * $factor;
				}

				if ($a->{$orderby[0]} < $b->{$orderby[0]})
				{
					return -1 * $factor;
				}

				return 0;
			});
		}

		return $list;
	}
	
	/**
	 * Returns the published country that match the given 2 letters code.
	 *
	 * @param 	string  $country_2_code  The 2 letters code (ISO 3166).
	 *
	 * @return 	mixed 	The country details on success, false otherwise.
	 */
	public static function withcode($country_2_code)
	{
		$country_2_code = strtoupper($country_2_code);

		// recover country from database if not set
		if (!isset(static::$countries[$country_2_code]))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_countries'))
				->where($dbo->qn('published') . ' = 1')
				->where($dbo->qn('country_2_code') . ' = ' . $dbo->q($country_2_code));
			
			$dbo->setQuery($q, 0, 1);
			
			$country = static::db2country($dbo->loadObject());

			if ($country)
			{
				// cache country
				static::$countries[$country_2_code] = $country;
			}
			else
			{
				// mark the country as missing
				static::$countries[$country_2_code] = false;
			}
		}
		
		return static::$countries[$country_2_code];
	}

	/**
	 * Creates a country object by using the details
	 * retrieved from the database.
	 *
	 * @param 	mixed 	$record  The database record.
	 *
	 * @return  object  The country object.
	 */
	public static function db2country($record)
	{
		$record = (object) $record;

		$country = new stdClass;

		if (isset($record->id))
		{
			$country->id = $record->id;
		}

		if (isset($record->country_name))
		{
			$country->name  = $record->country_name;
		}

		if (isset($record->country_2_code))
		{
			$country->code2 = $record->country_2_code;
		}
		
		if (isset($record->country_3_code))
		{
			$country->code3 = $record->country_3_code;
		}

		if (isset($record->phone_prefix))
		{
			$country->dial = $record->phone_prefix;
		}

		return $country;
	}

	/**
	 * Fetched the countries property that should be
	 * used to sort the list.
	 *
	 * @param 	string  $column  Either a database column or an object
	 * 							 property for sorting the records.
	 *
	 * @return 	string  The resulting column.
	 */
	protected static function ordcol($column)
	{
		// reverse lookup to check whether a db column was specified
		$lookup = array(
			'id'             => 'id',
			'country_name'   => 'name',
			'country_2_code' => 'code2',
			'country_3_code' => 'code3',
			'phone_prefix'   => 'dial',
		);

		if (isset($lookup[$column]))
		{
			// a db column was specified, route it
			return $lookup[$column];
		}

		if (in_array($column, array_values($lookup)))
		{
			// the specified column is supported, return it
			return $column;
		}

		// unsupported column, fallback to ID
		return 'id';
	}
}

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
 * VikAppointments locations (countries, states and cities) class handler.
 *
 * @since 1.6
 */
abstract class VAPLocations
{
	/**
	 * Returns the list of supported countries.
	 *
	 * @param 	mixed 	$orderby  The ordering column or an array with column and direction.
	 *
	 * @return 	array 	The countries list.
	 */
	public static function getCountries($orderby = 'id')
	{
		if (is_scalar($orderby))
		{
			$orderby = array($orderby, 'ASC');
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_countries'))
			->where($dbo->qn('published') . ' = 1')
			->order($dbo->qn($orderby[0]) . ' ' . $orderby[1]);
		
		$dbo->setQuery($q);
		return $dbo->loadAssocList();
	}
	
	/**
	 * Returns the published country that match the given 2 letters code.
	 *
	 * @param 	string  $country_2_code  The 2 letters code (ISO 3166).
	 *
	 * @return 	mixed 	The country details on success, false otherwise.
	 */
	public static function getCountryFromCode($country_2_code)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_countries'))
			->where($dbo->qn('published') . ' = 1')
			->where($dbo->qn('country_2_code') . ' = ' . $dbo->q($country_2_code));
		
		$dbo->setQuery($q, 0, 1);
		return $dbo->loadAssoc() ?? false;
	}
	
	/**
	 * Returns the list of supported states that belong to the given country.
	 *
	 * @param 	integer  $id_country  The country ID.
	 * @param 	mixed 	 $orderby  	  The ordering column or an array with column and direction.
	 *
	 * @return 	array 	 The states list.
	 */
	public static function getStates($id_country, $orderby = 'id')
	{
		if (is_scalar($orderby))
		{
			$orderby = array($orderby, 'ASC');
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_states'))
			->where($dbo->qn('published') . ' = 1')
			->order($dbo->qn($orderby[0]) . ' ' . $orderby[1]);

		if ($id_country)
		{
			$q->where($dbo->qn('id_country') . ' = ' . (int) $id_country);
		}

		$dbo->setQuery($q);
		return $dbo->loadAssocList();
	}
	
	/**
	 * Returns the list of supported cities that belong to the given state.
	 *
	 * @param 	integer  $id_state  The state ID.
	 * @param 	mixed 	 $orderby   The ordering column or an array with column and direction.
	 *
	 * @return 	array 	 The cities list.
	 */
	public static function getCities($id_state, $orderby = 'id')
	{
		if (is_scalar($orderby))
		{
			$orderby = array($orderby, 'ASC');
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_cities'))
			->where($dbo->qn('published') . ' = 1')
			->order($dbo->qn($orderby[0]) . ' ' . $orderby[1]);

		if ($id_state)
		{
			$q->where($dbo->qn('id_state') . ' = ' . (int) $id_state);
		}

		$dbo->setQuery($q);
		return $dbo->loadAssocList();
	}
}

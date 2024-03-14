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

VAPLoader::import('libraries.import.object');

/**
 * Class used to handle an import event for the LOCATIONS.
 *
 * @see   ImportObject
 * @since 1.7.3
 */
class ImportObjectLocations extends ImportObject
{
	/**
	 * Overloaded bind function.
	 *
	 * @param 	object 	 &$data  The object of the record to import.
	 * @param 	array 	 $args 	 Associative list of additional parameters.
	 *
	 * @return 	boolean  True if the record should be imported, otherwise false.
	 */
	protected function bind(&$data, array $args = array())
	{
		// check whether the employee is not specified and the import
		// request rather include it
		if (empty($data->id_employee) && !empty($args['id_employee']))
		{
			// force employee ID
			$data->id_employee = $args['id_employee'];
		}

		// check if we are dealing with a country 2 code
		if (!empty($data->id_country) && preg_match("/^[A-Z][A-Z0-9]$/i", $data->id_country))
		{
			// overwrite country 2 code with ID
			$data->id_country = $this->searchCountry($data->id_country);
		}

		// check if we are dealing with a state 2 code
		if (!empty($data->id_state) && preg_match("/^[A-Z][A-Z0-9]$/i", $data->id_state) && !empty($data->id_country))
		{
			// overwrite state 2 code with ID
			$data->id_state = $this->searchState($data->id_country, $data->id_state);
		}

		// check if we are dealing with a city 2 code
		if (!empty($data->id_city) && preg_match("/^[A-Z][A-Z0-9]$/i", $data->id_city) && !empty($data->id_state))
		{
			// overwrite city 2 code with ID
			$data->id_city = $this->searchCity($data->id_state, $data->id_city);
		}

		// call parent method to check the data integrity
		return parent::bind($data);
	}

	/**
	 * Helper method used to search the matching ID for the given country 2 code.
	 * 
	 * @param 	string   $code  The country 2 code.
	 * 
	 * @return 	integer  The matching ID.
	 */
	private function searchCountry($code)
	{
		static $countries = [];

		// check whether the same code has been already fetched
		if (!isset($countries[$code]))
		{
			// nope, cache initial result
			$countries[$code] = 0;

			// fetch country details
			$item = JModelVAP::getInstance('country')->getItem([
				'country_2_code' => $code,
			]);

			if ($item)
			{
				// register the ID of the country 2 code
				$countries[$code] = $item->id;
			}
		}

		return $countries[$code];
	}

	/**
	 * Helper method used to search the matching ID for the given state 2 code.
	 * 
	 * @param 	integer  $country  The ID of the country to which the state
	 *                             should belong.
	 * @param 	string   $code     The state 2 code.
	 * 
	 * @return 	integer  The matching ID.
	 */
	private function searchState($country, $code)
	{
		static $states = [];

		// check whether the same code has been already fetched
		if (!isset($states[$code]))
		{
			// nope, cache initial result
			$states[$code] = 0;

			// fetch state details
			$item = JModelVAP::getInstance('state')->getItem([
				'state_2_code' => $code,
				'id_country'   => (int) $country,
			]);

			if ($item)
			{
				// register the ID of the state 2 code
				$states[$code] = $item->id;
			}
		}

		return $states[$code];
	}

	/**
	 * Helper method used to search the matching ID for the given city 2 code.
	 * 
	 * @param 	integer  $state  The ID of the state to which the city
	 *                           should belong.
	 * @param 	string   $code   The city 2 code.
	 * 
	 * @return 	integer  The matching ID.
	 */
	private function searchCity($state, $code)
	{
		static $cities = [];

		// check whether the same code has been already fetched
		if (!isset($cities[$code]))
		{
			// nope, cache initial result
			$cities[$code] = 0;

			// fetch city details
			$item = JModelVAP::getInstance('city')->getItem([
				'city_2_code' => $code,
				'id_state'    => (int) $state,
			]);

			if ($item)
			{
				// register the ID of the city 2 code
				$cities[$code] = $item->id;
			}
		}

		return $cities[$code];
	}

	/**
	 * Builds the base query to export all the records.
	 * @since 	1.7  $app and $dbo are now included within the $options argument.
	 *
	 * @param 	JObject  $options  A registry of export options.
	 * @param 	string   $alias    The table alias.
	 *
	 * @return 	mixed 	 The query builder object.
	 *
	 * @uses 	getColumns()
	 * @uses 	getTable()
	 * @uses 	getPrimaryKey()
	 */
	protected function buildExportQuery($options, $alias = 'l')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');

		$filters = array();
		$filters['keys']        = $app->getUserStateFromRequest('vaplocations.keys', 'keys', '', 'string');
		$filters['id_country']  = $app->getUserStateFromRequest('vaplocations.country', 'id_country', 0, 'uint');
		$filters['id_state']    = $app->getUserStateFromRequest('vaplocations.state[' . $filters['id_country'] . ']', 'id_state', 0, 'uint');
		$filters['id_city']     = $app->getUserStateFromRequest('vaplocations.city[' . $filters['id_state'] . ']', 'id_city', 0, 'uint');
		$filters['id_employee'] = $options->get('id_employee', 0);

		$q = parent::buildExportQuery($options, $alias);

		if ($filters['id_employee'])
		{
			// filter the location by employee
			$q->where($dbo->qn('l.id_employee') . ' = ' . $filters['id_employee']);
		}
		else
		{
			// retrieve also the location owner (employee)
			$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('l.id_employee'));
		}
		
		if (strlen($filters['keys']))
		{
			$key = $dbo->q("%{$filters['keys']}%");

			$where = array();
			// search by location name
			$where[] = $dbo->qn('l.name') . ' LIKE ' . $key;
			// search by location address
			$where[] = $dbo->qn('l.address') . ' LIKE ' . $key;
			// search by ZIP code
			$where[] = $dbo->qn('l.zip') . ' LIKE ' . $key;
			// search by country
			$where[] = $dbo->qn('c.country_name') . ' LIKE ' . $key;
			// search by state
			$where[] = $dbo->qn('s.state_name') . ' LIKE ' . $key;
			// search by city
			$where[] = $dbo->qn('c.city_name') . ' LIKE ' . $key;

			if (!$filters['id_employee'])
			{
				$sprintf = 'CONCAT_WS(\' \', %s, %s) LIKE %s';

				// search also by employee full name (first + last and last + first)
				$where[] = sprintf($sprintf, $dbo->qn('e.firstname'), $dbo->qn('e.lastname'), $key);
				$where[] = sprintf($sprintf, $dbo->qn('e.lastname'), $dbo->qn('e.firstname'), $key);

				$q->andWhere($where, 'OR');
			}
		}

		if ($filters['id_country'])
		{
			$q->where($dbo->qn('l.id_country') . ' = ' . $filters['id_country']);

			if ($filters['id_state'])
			{
				$q->where($dbo->qn('l.id_state') . ' = ' . $filters['id_state']);

				if ($filters['id_city'])
				{
					$q->where($dbo->qn('l.id_city') . ' = ' . $filters['id_city']);
				}
			}
		}

		return $q;
	}
}

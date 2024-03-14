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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments location model.
 *
 * @since 1.7
 */
class VikAppointmentsModelLocation extends JModelVAP
{
	/**
	 * Cache of location details.
	 *
	 * @var array
	 */
	public static $info = array();

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned working time
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where($dbo->qn('id_location') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($worktime_ids = $dbo->loadColumn())
		{
			// get worktime model
			$model = JModelVAP::getInstance('worktime');

			// detach location from worktimes
			foreach ($worktime_ids as $worktime_id)
			{
				$data = array(
					'id'          => (int) $worktime_id,
					'id_location' => 0,
				);

				$model->save($data);
			}
		}

		return true;
	}

	/**
	 * Method used to return the details of the given location.
	 *
	 * @param 	integer  $id_location The location ID.
	 *
	 * @return 	mixed    The location details on success, null otherwise.
	 */
	public function getInfo($id_location)
	{
		if ((int) $id_location <= 0)
		{
			return null;
		}

		// load details only if not yet cached
		if (!isset(static::$info[$id_location]))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('l.*')
				->select($dbo->qn('c.country_2_code', 'countryCode2'))
				->select($dbo->qn('c.country_3_code', 'countryCode3'))
				->select($dbo->qn('c.country_name', 'countryName'))
				->select($dbo->qn('s.state_2_code', 'stateCode2'))
				->select($dbo->qn('s.state_name', 'stateName'))
				->select($dbo->qn('ci.city_name', 'cityName'))
				->from($dbo->qn('#__vikappointments_employee_location', 'l'))
				->leftjoin($dbo->qn('#__vikappointments_countries', 'c') . ' ON ' . $dbo->qn('l.id_country') . ' = ' . $dbo->qn('c.id'))
				->leftjoin($dbo->qn('#__vikappointments_states', 's') . ' ON ' . $dbo->qn('l.id_state') . ' = ' . $dbo->qn('s.id'))
				->leftjoin($dbo->qn('#__vikappointments_cities', 'ci') . ' ON ' . $dbo->qn('l.id_city') . ' = ' . $dbo->qn('ci.id'))
				->where($dbo->qn('l.id') . ' = ' . (int) $id_location);

			$dbo->setQuery($q, 0, 1);
			
			// get location data
			$location = $dbo->loadObject();

			if (!$location)
			{
				return null;
			}

			// create LONG location text by using the following format
			// [ADDRESS], [ZIP] [CITY] [STATE], [COUNTRY]
			
			$components = array();

			// register address first
			$components[] = $location->address;

			$block = array();

			// register ZIP within 2nd block
			$block[] = $location->zip;

			// register city within 2nd block
			$block[] = $location->cityName;

			// register state/province code within 2nd block
			$block[] = $location->stateCode2;

			// register 2nd block
			$components[] = implode(' ', array_filter($block));
			
			// register country code (2 letters)
			$components[] = $location->countryCode2;
			
			// join string components
			$location->text = implode(', ', array_filter($components));

			// create SHORT location text by using the following format
			// [ADDRESS], [ZIP] [CITY]
			
			$components = array();

			// register address first
			$components[] = $location->address;

			$block = array();

			// register ZIP within 2nd block
			$block[] = $location->zip;

			// register city within 2nd block
			$block[] = $location->cityName;

			// register 2nd block
			$components[] = implode(' ', array_filter($block));
			
			// join string components
			$location->short = implode(', ', array_filter($components));

			/**
			 * This event can be used to manipulate the location details and the
			 * full address strings at runtime. It is suggested to override the
			 * following properties in case the default format is not suitable
			 * to a specific area:
			 *
			 * $location->text
			 * $location->short
			 *
			 * @param 	object 	 $location  The location object.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			VAPFactory::getEventDispatcher()->false('onLocationGetInfo', array($location));

			// cache result
			static::$info[$id_location] = $location;
		}

		return static::$info[$id_location];
	}
}

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
 * VikAppointments employee area locations list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmplocations extends JModelVAP
{
	/**
	 * Loads a list of locations to be displayed within the
	 * employees area view.
	 *
	 * @param 	array  &$filters  An array of filters.
	 * @param 	array  &$options  An array of options, such as the ordering mode.
	 *
	 * @return 	array  A list of locations.
	 */
	public function getItems(array &$filters = array(), array &$options = array())
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$dbo = JFactory::getDbo();

		$rows = array();

		$q = $dbo->getQuery(true)
			->select('l.*')
			->select($dbo->qn(array('c.country_name', 'c.country_2_code', 's.state_name', 's.state_2_code', 'ci.city_name')))
			->from($dbo->qn('#__vikappointments_employee_location', 'l'))
			->leftjoin($dbo->qn('#__vikappointments_countries', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('l.id_country'))
			->leftjoin($dbo->qn('#__vikappointments_states', 's') . ' ON ' . $dbo->qn('s.id') . ' = ' . $dbo->qn('l.id_state'))
			->leftjoin($dbo->qn('#__vikappointments_cities', 'ci') . ' ON ' . $dbo->qn('ci.id') . ' = ' . $dbo->qn('l.id_city'))
			->where(array(
				$dbo->qn('id_employee') . ' = ' . $auth->id,
				$dbo->qn('id_employee') . ' <= 0',
			), 'OR')
			->order($dbo->qn('id_employee') . ' DESC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $location)
		{
			$components = array();

			// register address first
			$components[] = $location->address;

			$block = array();

			// register ZIP within 2nd block
			$block[] = $location->zip;

			// register city within 2nd block
			$block[] = $location->city_name;

			// register state/province code within 2nd block
			$block[] = $location->state_2_code;

			// register 2nd block
			$components[] = implode(' ', array_filter($block));
			
			// register country code (2 letters)
			$components[] = $location->country_2_code;
			
			// join string components
			$location->text = implode(', ', array_filter($components));

			$rows[] = $location;
		}

		return $rows;
	}
}

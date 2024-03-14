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
 * From now on, the system is able to auto-updates the overrides for the employees according to a
 * new column in the database, which is called global. As long as this parameter is turned on, the
 * system will always use the default parameters offered by the service.
 *
 * For this reason, during the update we should accurately search for those employees that seem to
 * use different parameters and turn the global column off.
 *
 * In addition, the maximum capacity can now be overwritten for each employee. So we need to update
 * each service-employee association record by specifying the same max capacity of the assigned service.
 *
 * @since 1.7
 */
class VAPUpdateRuleServiceEmployeeOverrides1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$this->fetchGlobal();

		return true;
	}

	/**
	 * Fixes the existing overrides.
	 *
	 * @return 	void
	 */
	private function fetchGlobal()
	{
		$dbo = JFactory::getDbo();

		$services = array();

		// load all the services
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_service'));

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows())
		{
			return;
		}

		// map the services by ID
		foreach ($dbo->loadObjectList() as $s)
		{
			$services[$s->id] = $s;
		}

		// load all the service-employee relations
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_ser_emp_assoc'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $override)
			{
				if (!isset($services[$override->id_service]))
				{
					continue;
				}

				$service = $services[$override->id_service];

				if ($service->price == $override->rate
					&& $service->duration == $override->duration
					&& $service->sleep == $override->sleep
					&& empty($override->description))
				{
					// no changes, use global override
					$override->global = 1;
				}
				else
				{
					// the employee uses a custom configuration
					$override->global = 0;
				}

				// add support for the maximum capacity
				$override->max_capacity = $service->max_capacity;

				$dbo->updateObject('#__vikappointments_ser_emp_assoc', $override, 'id');
			}
		}
	}
}

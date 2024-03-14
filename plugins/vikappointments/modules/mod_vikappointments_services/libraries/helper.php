<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_services
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.helpers.module');

/**
 * Helper class used by the Services module.
 *
 * @since 1.2
 */
class VikAppointmentsServicesModuleHelper
{
	/**
	 * Use methods defined by modules trait for a better reusability.
	 *
	 * @see VAPModuleHelper
	 *
	 * @since 1.4
	 */
	use VAPModuleHelper;

	/**
	 * Returns the list of services that should be displayed
	 * depending on the configuration of the module.
	 *
	 * @param 	JRegistry  $params  The configuration registry.
	 *
	 * @return 	array      The services list.
	 */
	public static function getServices($params)
	{
		$dbo  = JFactory::getDbo();
		$user = JFactory::getUser();

		$group_filter 	= $params->get('groupfilter');
		$service_filter = $params->get('servicefilter');

		$services = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'name', 'description', 'duration', 'price', 'image')))
			->from($dbo->qn('#__vikappointments_service'))
			->where($dbo->qn('published') . ' = 1')
			->order($dbo->qn('ordering') . ' ASC');

		if ($group_filter)
		{
			$count = count($group_filter);

			if ($count == 1)
			{
				$q->where($dbo->qn('id_group') . ' = ' . (int) $group_filter[0]);
			}
			else
			{
				$q->where($dbo->qn('id_group') . ' IN (' . implode(', ', array_map('intval', $group_filter)) . ')');	
			}
		}

		if ($service_filter)
		{
			$count = count($service_filter);

			if ($count == 1)
			{
				$q->where($dbo->qn('id') . ' = ' . (int) $service_filter[0]);
			}
			else
			{
				$q->where($dbo->qn('id') . ' IN (' . implode(', ', array_map('intval', $service_filter)) . ')');	
			}
		}

		// retrieve only the services that belong to the view
		// access level of the current user
		$levels = $user->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('level') . ' IN (' . implode(', ', $levels) . ')');
		}

		$dbo->setQuery($q);
		$services = $dbo->loadAssocList();

		if ($services)
		{
			VikAppointments::translateServices($services);
		}

		return $services;
	}
}

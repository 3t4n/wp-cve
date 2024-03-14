<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_zip
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.helpers.module');

/**
 * Helper class used by the ZIP Checker module.
 *
 * @since 1.2
 */
class VikAppointmentsZipCheckerHelper
{
	/**
	 * Use methods defined by modules trait for a better reusability.
	 *
	 * @see VAPModuleHelper
	 *
	 * @since 1.3
	 */
	use VAPModuleHelper;
	
	/**
	 * Returns the list of all the services that can be evaluated.
	 *
	 * @param 	JRegistry  $params  The configuration registry.
	 *
	 * @return 	array      The services list.
	 */
	public static function getServices($params)
	{
		$dbo  = JFactory::getDbo();
		$user = JFactory::getUser();

		// get services
		
		$services = array();

		$group_ids = $service_ids = array();

		/**
		 * Fixed an issue that was joining the services with the groups
		 * by using a wrong ON condition.
		 *
		 * @since 1.3
		 */
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('s.id', 's.name', 's.choose_emp', 's.id_group')))
			->select($dbo->qn('g.id', 'gid'))
			->select($dbo->qn('g.name', 'gname'))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'))
			->where($dbo->qn('s.published') . ' = 1')
			->order(array(
				$dbo->qn('g.ordering') . ' ASC',
				$dbo->qn('s.ordering') . ' ASC',
				$dbo->qn('s.name') . ' ASC',
			));
			
		if ($params->get('displayser') == 2)
		{
			$q->where($dbo->qn('s.enablezip') . ' = 1');
		}

		/**
		 * Retrieve only the services that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.2
		 */
		$levels = $user->getAuthorisedViewLevels();

		if ($levels)
		{
			$q->where($dbo->qn('s.level') . ' IN (' . implode(', ', $levels) . ')');
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadAssocList() as $r)
		{
			if (!isset($services[$r['id_group']]))
			{
				$services[$r['id_group']] = array(
					'id'   => $r['gid'],
					'name' => $r['gname'],
					'list' => array(),
				);

				if ($r['id_group'])
				{
					$group_ids[] = $r['id_group'];
				}
			}

			$services[$r['id_group']]['list'][] = array(
				'id'         => $r['id'],
				'name'       => $r['name'],
				'choose_emp' => $r['choose_emp'],
				'id_group'   => $r['id_group'],
			);

			$service_ids[] = $r['id'];
		}

		$translator = VAPFactory::getTranslator();

		$lang = JFactory::getLanguage()->getTag();

		// preload translations
		$serLang = $translator->load('service', array_unique($service_ids), $lang);
		$grpLang = $translator->load('group', array_unique($group_ids), $lang);

		foreach ($services as $id_group => $group)
		{
			// translate record for the given language
			$tx = $grpLang->getTranslation($id_group, $lang);

			if ($tx)
			{
				$group['name'] = $tx->name;
			}

			foreach ($group['list'] as $i => $service)
			{
				// translate record for the given language
				$tx = $serLang->getTranslation($service['id'], $lang);

				if ($tx)
				{
					$group['list'][$i]['name'] = $tx->name;
				}
			}

			// update group
			$services[$id_group] = $group;
		}

		// always append services without group at the end of the list
		if (isset($services[0]))
		{
			$tmp = $services[0];
			unset($services[0]);
			$services[0] = $tmp;
		}
		
		return $services;
	}
	
	/**
	 * Returns the list of all the employees that are assigned to the specified service.
	 *
	 * @param 	integer  $id_service  The service ID.
	 *
	 * @return 	array    The employees list.
	 */
	public static function getEmployees($id_service)
	{
		/**
		 * Include the folder containing all the VikAppointments models for the site client.
		 * 
		 * @since 1.3.2
		 */
		JModelLegacy::addIncludePath(VAPBASE . DIRECTORY_SEPARATOR . 'models');

		/**
		 * Take only the employees that should be listed.
		 *
		 * @since 1.2.2
		 */
		$employees = JModelVAP::getInstance('service')->getEmployees($id_service, $strict = true);

		if ($employees)
		{
			// convert to array for backward compatibility
			$employees = array_map(function($elem)
			{
				return (array) $elem;
			}, $employees);

			VikAppointments::translateEmployees($employees);
		}

		return $employees;
	}
}

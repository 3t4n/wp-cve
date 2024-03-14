<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.helpers.module');

/**
 * Helper class used by the Search module.
 *
 * @since 1.3
 */
class VikAppointmentsSearchHelper
{
	/**
	 * Use methods defined by modules trait for a better reusability.
	 *
	 * @see VAPModuleHelper
	 *
	 * @since 1.5
	 */
	use VAPModuleHelper;

	/**
	 * Returns an associative array containing the data set
	 * in the request.
	 *
	 * @return 	array 	The data array.
	 */
	public static function getViewHtmlReferences()
	{
		$app    = JFactory::getApplication();
		$dbo    = JFactory::getDbo();
		$user   = JFactory::getUser();
		$config = VAPFactory::getConfig();
		
		$data = array(
			'date'   => $app->input->getString('date', null),
			'id_ser' => $app->input->getUint('id_ser', 0),
			'id_emp' => $app->input->getUint('id_emp', 0),
		);

		// get user timezone
		$tz = VikAppointments::getUserTimezone()->getName();

		if (VAPDateHelper::isNull($data['date']))
		{
			// use the current date when not specified
			$data['date'] = JHtml::fetch('date', 'now', $config->get('dateformat'), $tz);
		}

		$max_sys_date = static::getMaxTimeStamp();

		// get services

		$group_ids = $service_ids = array();
		
		$services = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('s.id', 's.name', 's.choose_emp', 's.random_emp', 's.id_group', 'mindate', 'maxdate')))
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

		// retrieve only the services that belong to the view
		// access level of the current user
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

			if ($r['maxdate'] == -1)
			{
				// get maximum number of days from configuration
				$r['maxdate'] = $config->getUint('maxdate');
			}

			if ($r['maxdate'] > 0)
			{
				$modifier = '+' . $r['maxdate'] . ' days';
			}
			else
			{
				// use the system maximum date
				$modifier = $max_sys_date;
			}

			// calculate the maximum date
			$r['maxdate'] = JHtml::fetch('date', $modifier, 'Y-m-d\T00:00:00', $tz);

			if ($r['mindate'] == -1)
			{
				// get minimum number of days from configuration
				$r['mindate'] = $config->getUint('mindate');
			}

			if ($r['mindate'] > 0)
			{
				$modifier = '+' . $r['mindate'] . ' days';
			}
			else
			{
				// use the current date
				$modifier = 'now';
			}

			// calculate the minimum date
			$r['mindate'] = JHtml::fetch('date', $modifier, 'Y-m-d\T00:00:00', $tz);

			$services[$r['id_group']]['list'][] = array(
				'id'         => $r['id'],
				'name'       => $r['name'],
				'choose_emp' => $r['choose_emp'],
				'random_emp' => $r['random_emp'],
				'id_group'   => $r['id_group'],
				'mindate'    => $r['mindate'],
				'maxdate'    => $r['maxdate'],
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

		$group = reset($services);
		
		// fetch the first available service when not specified
		if (!$data['id_ser'] && $services && $group['list'])
		{
			$data['id_ser'] = $group['list'][0]['id'];
		}

		/**
		 * Include the folder containing all the VikAppointments models for the site client.
		 * 
		 * @since 1.5.2
		 */
		JModelLegacy::addIncludePath(VAPBASE . DIRECTORY_SEPARATOR . 'models');

		/**
		 * Take only the employees that should be listed.
		 *
		 * @since 1.4.3
		 */
		$employees = JModelVAP::getInstance('service')->getEmployees($data['id_ser'], $strict = true);

		if ($employees)
		{
			// convert to array for backward compatibility
			$employees = array_map(function($elem)
			{
				return (array) $elem;
			}, $employees);

			VikAppointments::translateEmployees($employees, $lang);
		}
		
		return array(
			'lastValues' => $data,
			'services' 	 => $services,
			'employees'  => $employees,
		);
	}
	
	/**
	 * Returns the maximum timestamp that can be used within the
	 * jQuery Datepicker.
	 *
	 * @return 	JDate  The maximum date threshold.
	 */
	public static function getMaxTimeStamp()
	{
		$date = JFactory::getDate();
		$date->modify('+' . VAPFactory::getConfig()->getUint('numcals') . ' months');
		$date->modify($date->format('Y') . '-' . $date->format('m') . '-' . $date->format('t'));

		return $date;
	}
}

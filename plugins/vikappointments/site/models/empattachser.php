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
 * VikAppointments employee area attach services view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpattachser extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$data = (array) $data;

		// extract selected services
		$services = isset($data['services']) ? (array) $data['services'] : array();

		// take only the services that can be attached to this employee
		$services = array_intersect($services, $this->getAttachableItems());

		// get helper models
		$serviceModel = JModelVAP::getInstance('service');
		$assocModel   = JModelVAP::getInstance('serempassoc');

		$count = 0;

		foreach ($services as $id)
		{
			// load service details through model
			$item = $serviceModel->getItem((int) $id);

			if (!$item)
			{
				// item not found, go ahead
				continue;
			}

			// inject relation details
			$item->id_employee = $auth->id;
			$item->id_service  = $item->id;

			// unset item PK
			$item->id = 0;

			// clear description
			$item->description = '';

			// use global rates
			$item->global = 1;

			// attempt to assign the service to the employee
			if ($assocModel->save($item))
			{
				// success, increase counter
				$count++;
			}
			else
			{
				// get registered error
				$error = $assocModel->getError();

				// propagate error, if any
				if ($error)
				{
					$this->setError($error);
				}
			}
		}

		return $count;
	}

	/**
	 * Loads a list of services to be displayed within the
	 * employees area view.
	 *
	 * @return 	array  A list of services.
	 */
	public function getItems()
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// raise error in case of no employee
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$dbo = JFactory::getDbo();

		// get all services assigned to the current employee
		$assigned = JModelVAP::getInstance('employee')->getServices($auth->id, $strict = false);

		// map to take only the ID of the services
		$assigned = array_map(function($elem)
		{
			return $elem->id;
		}, $assigned);

		$no_group = JText::translate('VAPSERVICENOGROUP');

		// load all the existing services
		$services = array();

		$q = $dbo->getQuery(true)
			->select(array(
				$dbo->qn('s.id'),
				$dbo->qn('s.name'),
				$dbo->qn('s.id_group'),
				$dbo->qn('g.name', 'group_name'),
			))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'))
			->where(array(
				$dbo->qn('s.published') . ' = 1',
				$dbo->qn('s.createdby') . ' <= 0',
			))
			->order(array(
				$dbo->qn('g.name') . ' ASC',
				$dbo->qn('s.name') . ' ASC',
			));

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $row)
		{
			$group = $row->id_group ? $row->group_name : $no_group;

			// group by category
			if (!isset($services[$group]))
			{
				$services[$group] = array();
			}

			// create option
			$opt = JHtml::fetch('select.option', $row->id, $row->name);

			if (in_array($row->id, $assigned))
			{
				// disable option, since the service is already assigned to this employee
				$opt->disable = true;
			}

			// append option
			$services[$group][] = $opt;
		}

		if (isset($services[$no_group]))
		{
			// always move services without group at the end of the list
			$tmp = $services[$no_group];
			unset($services[$no_group]);
			$services[$no_group] = $tmp;
		}

		return $services;
	}

	/**
	 * Returns an array of services that can be attached.
	 *
	 * @return 	array  A list of services.
	 */
	public function getAttachableItems()
	{
		$list = array();

		// iterate groups
		foreach ($this->getItems() as $services)
		{
			// iterate group services
			foreach ($services as $opt)
			{
				// make sure the service is not disabled
				if (empty($opt->disable))
				{
					// register service ID
					$list[] = $opt->value;
				}
			}
		}

		return $list;
	}
}

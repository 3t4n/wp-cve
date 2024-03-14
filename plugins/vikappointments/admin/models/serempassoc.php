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
 * VikAppointments service-employee relation model.
 *
 * @since 1.7
 */
class VikAppointmentsModelSerempassoc extends JModelVAP
{
	/**
	 * Cache of service-employee overrides.
	 *
	 * @var array
	 */
	public static $overrides = array();

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		// check whether we are creating or updating
		$is_new = empty($data['id']);

		// check whether a relation between the specified service and employee already exists
		if ($is_new && !empty($data['id_employee']) && !empty($data['id_service']))
		{
			$exists = $this->getItem(array(
				'id_service'  => $data['id_service'],
				'id_employee' => $data['id_employee']
			));

			if ($exists)
			{
				// update ID with the existing one
				$data['id'] = $exists->id;
				$is_new = false;
			}
		}

		// attempt to save the relation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		if ($is_new && isset($data['id_employee']) && isset($data['id_service']))
		{
			$dbo = JFactory::getDbo();

			// load default working days of the employee
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where($dbo->qn('id_service') . ' <= 0')
				->where($dbo->qn('id_employee') . ' = ' . (int) $data['id_employee']);

			$dbo->setQuery($q);
			$worktimes = $dbo->loadObjectList();

			// get working times model
			$wdModel = JModelVAP::getInstance('worktime');

			foreach ($worktimes as $wd)
			{
				// make relation with parent working time
				$wd->parent = $wd->id;
				// make relation with service
				$wd->id_service = $data['id_service'];
				// unset ID to create new record
				$wd->id = 0;

				$wdModel->save($wd);
			}
		}

		return $id;
	}

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

		if (!$ids)
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned employees first
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id_service', 'id_employee')))
			->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')' )
			->where($dbo->qn('id_service') . ' > 0')
			->where($dbo->qn('id_employee') . ' > 0');

		$dbo->setQuery($q);
		$ser_emp_assoc = $dbo->loadObjectList();

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		// get working times model
		$wdModel = JModelVAP::getInstance('worktime');

		foreach ($ser_emp_assoc as $assoc)
		{
			// load any assigned working times
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where($dbo->qn('id_employee') . ' = ' . $assoc->id_employee)
				->where($dbo->qn('id_service') . ' = ' . $assoc->id_service);

			$dbo->setQuery($q);

			if ($worktime_ids = $dbo->loadColumn())
			{
				// delete assigned working times
				$wdModel->delete($worktime_ids);
			}
		}

		return true;
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   integer  $id_ser  The service ID.
	 * @param   integer  $id_emp  The employee ID.
	 *
	 * @return 	mixed    The record details on success, null otherwise.
	 */
	public function getOverrides($id_ser, $id_emp = 0)
	{
		// prepare primary keys for search
		$pk = array(
			'id_service'  => (int) $id_ser,
			'id_employee' => (int) $id_emp,
		);

		$sign = serialize($pk);

		// search for a cached override first
		if (array_key_exists($sign, static::$overrides))
		{
			// return cached version
			return static::$overrides[$sign];
		}

		// get service model
		$serModel = JModelVAP::getInstance('service');

		// get service details
		$service = $serModel->getItem($id_ser);

		if (!$service)
		{
			// service not found
			return null;
		}

		if ($id_emp <= 0)
		{
			// employee not specified, return service details.
			return $service;
		}

		// load matching overrides
		$assoc = parent::getItem($pk);

		if (!$assoc)
		{
			// assoc not found
			return null;
		}

		// check whether the relation should rely on the
		// global parameters given by the service
		if (!$assoc->global)
		{
			// copy service details by iterating the properties set within
			// the association table, so that we can copy third-party columns
			// too (as long as they share the same name)
			foreach ($assoc as $k => $v)
			{
				if (preg_match("/^id(_$)?/", $k) || $k == 'ordering')
				{
					// ignore primary key, foreign keys, ordering and description,
					// which is always appended to the default one
					continue;
				}

				// rate is the only property that do not use the same name and
				// needs to be treated in a different way
				if ($k == 'rate')
				{
					$service->price = $assoc->rate;
				}
				// the description doesn't overwrite the default one
				else if ($k == 'description')
				{
					// append description to default one
					$service->description .= "\n" . $v;
					// and register the override description also in a
					// different property, because the default one may
					// be replaced by the translation
					$service->overrideDescription = $v;
				}
				// make sure the property exists
				else if (isset($service->{$k}))
				{
					// copy within assoc
					$service->{$k} = $v;
				}
			}
		}

		// cache result for later usage
		static::$overrides[$sign] = $service;

		return $service;
	}
}

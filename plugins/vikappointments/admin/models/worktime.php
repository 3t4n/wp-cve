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
 * VikAppointments employee working time model.
 *
 * @since 1.7
 */
class VikAppointmentsModelWorktime extends JModelVAP
{
	/**
	 * Lookup used to cache the services assigned to
	 * a specified employee (key).
	 *
	 * @var array
	 */
	protected $services = array();

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		// load item through parent
		$item = parent::getItem($pk, $new);

		if ($item && !$item->id)
		{
			// use default times
			$item->fromts = 540;
			$item->endts  = 720;
		}

		return $item;
	}

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

		// attempt to save the working time
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		// make sure the employee was specified and we didn't update a working time for a service
		if (!empty($data['id_employee']) && (empty($data['id_service']) || $data['id_service'] == -1))
		{
			$dbo = JFactory::getDbo();

			$services = isset($data['services']) ? (array) $data['services'] : array();

			// load services list
			$services = $this->getServices($data['id_employee'], $services);

			foreach ($services as $id_service)
			{
				// insert/update also the service relation
				$data['id']         = 0;
				$data['id_service'] = (int) $id_service;
				$data['parent']     = $id;

				if (!$is_new)
				{
					// lets look for a specific record to update
					$q = $dbo->getQuery(true)
						->select($dbo->qn('id'))
						->from($dbo->qn('#__vikappointments_emp_worktime'))
						->where($dbo->qn('parent') . ' = ' . (int) $data['parent'])
						->where($dbo->qn('id_service') . ' = ' . (int) $data['id_service']);

					$dbo->setQuery($q, 0, 1);
					
					// existing record, overwrite ID to update it
					$data['id'] = $dbo->loadResult();
				}

				// update only if we need to create a new child record
				// or if have to update an existing one
				if ($is_new || $data['id'])
				{
					// save relation
					parent::save($data);
				}
			}
		}

		
		// exit;

		return $id;
	}

	/**
	 * Basic delete implementation.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// attempt to delete the working time
		if (!parent::delete($ids))
		{
			// there's nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load all working times assigned to the deleted records
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where($dbo->qn('parent') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($columns = $dbo->loadColumn())
		{
			// delete records found
			parent::delete($columns);
		}

		return true;
	}

	/**
	 * Restores the working days for the given service and
	 * employee relation.
	 *
	 * @param 	integer  $id_service   The service ID.
	 * @param 	integer  $id_employee  The employee ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function restore($id_service, $id_employee)
	{
		$dbo = JFactory::getDbo();

		// load all employee working days
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where(array(
				$dbo->qn('id_employee') . ' = ' . $id_employee,
				$dbo->qn('id_service') . ' <= 0',
			));

		$today = strtotime('00:00:00', VikAppointments::now());

		// exclude special days in the past
		$q->andWhere(array(
			$dbo->qn('ts') . ' = -1',
			$dbo->qn('ts') . ' >= ' . $today,
		), 'OR');

		$dbo->setQuery($q);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// the employee doesn't have any working days
			return false;
		}

		$restored = false;

		foreach ($rows as $row)
		{
			// check whether the service already have this working time
			$q = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where($dbo->qn('parent') . ' = ' . $row->id)
				->where($dbo->qn('id_service') . ' = ' . (int) $id_service);

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if (!$dbo->getNumRows())
			{
				// make relation with parent
				$row->parent = $row->id;
				// make relation with service
				$row->id_service = (int) $id_service;
				// unset ID to create new
				$row->id = 0;

				// working time not found, restore it
				$restored = $this->save($row) || $restored;
			}
		}

		return $restored;
	}

	/**
	 * Retrieves a list of services assigned to the specified
	 * employee. The list of services is cached within an
	 * internal property, in order to avoid duplicate queries.
	 *
	 * @param 	integer  $id_employee
	 *
	 * @return 	array
	 */
	public function getServices($id_employee, array $services = array())
	{
		if (!isset($this->services[$id_employee]))
		{
			$this->services[$id_employee] = array();

			$dbo = JFactory::getDbo();

			// retrieve all services assigned to this employee
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_service'))
				->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
				->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);

			$dbo->setQuery($q);
			$this->services[$id_employee] = $dbo->loadColumn();
		}

		if ($services)
		{
			// filter the services to take only the ones that have been specified
			return array_values(array_filter($this->services[$id_employee], function($s) use ($services)
			{
				return in_array($s, $services);
			}));
		}

		return $this->services[$id_employee];
	}

	/**
	 * Returns the location related to the specified employee, service and check-in.
	 *
	 * @param 	string   $checkin      The UTC check-in date time.
	 * @param 	integer  $id_service   The service ID.
	 * @param 	integer  $id_employee  The employee ID. If not provided, it won't be used.
	 *
	 * @return 	mixed 	 The location ID.
	 */
	public function getLocation($checkin, $id_service, $id_employee = 0)
	{
		$dbo = JFactory::getDbo();

		// get timezone
		$tz = JModelVAP::getInstance('employee')->getTimezone($id_employee);

		// create date object and adjust it to the employee timezone
		$date = new JDate($checkin);
		$date->setTimezone(new DateTimeZone($tz));

		// convert local time into minutes int
		$hm = JHtml::fetch('vikappointments.time2min', $date->format('H:i', $local = true));

		// back at midnight for a correct comparison between the dates
		$date->modify('00:00:00');

		$q = $dbo->getQuery(true);

		// search working day for a specific day of the year

		$q->select($dbo->qn('id_location'));
		$q->from($dbo->qn('#__vikappointments_emp_worktime'));
		$q->where($dbo->qn('id_service') . ' = ' . (int) $id_service);

		if ($id_employee > 0)
		{
			$q->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);
		}

		// filter working days by time
		$q->where($hm . ' BETWEEN ' . $dbo->qn('fromts') . ' AND ' . $dbo->qn('endts'));

		// filter by date/day
		$q->andWhere(array(
			$dbo->qn('day') . ' = ' . (int) $date->format('w', $local = true) . ' AND ' . $dbo->qn('ts') . ' <= 0',
			$dbo->qn('tsdate') . ' = ' . $dbo->q($date->toSql($local = true)),
		));

		// take working times for the days of the year first
		$q->order($dbo->qn('ts') . ' DESC');
		
		$dbo->setQuery($q, 0, 1);
		// return location found
		return max(0, (int) $dbo->loadResult());
	}
}

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
 * VikAppointments employee model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmployee extends JModelVAP
{
	/**
	 * A list of cached timezones.
	 *
	 * @var array
	 */
	protected static $timezones = array();

	/**
	 * Extend duplicate implementation to clone any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids     Either the record ID or a list of records.
	 * @param 	mixed    $src     Specifies some values to be used while duplicating.
	 * @param 	array    $ignore  A list of columns to skip.
	 *
	 * @return 	mixed    The ID of the records on success, false otherwise.
	 */
	public function duplicate($ids, $src = array(), $ignore = array())
	{
		$new_ids = array();

		// do not copy sync key
		$ignore[] = 'synckey';

		$dbo = JFactory::getDbo();

		// get employee translation model
		$langModel = JModelVAP::getInstance('langemployee');
		// get service assoc model
		$assocModel = JModelVAP::getInstance('serempassoc');
		// get working times model
		$wdModel = JModelVAP::getInstance('worktime');

		foreach ($ids as $id_employee)
		{
			// start by duplicating the whole record
			$new_id = parent::duplicate($id_employee, $src, $ignore);

			if ($new_id)
			{
				$new_id = array_shift($new_id);

				// register copied
				$new_ids[] = $new_id;
			
				// load any assigned translation
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_lang_employee'))
					->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$lang_data = array();
					$lang_data['id_employee'] = $new_id;

					// duplicate languages by using the new employee ID
					$langModel->duplicate($duplicate, $lang_data);
				}

				// load any assigned service
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
					->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$assoc_data = array();
					$assoc_data['id_employee'] = $new_id;

					// duplicate services by using the new employee ID
					$assocModel->duplicate($duplicate, $assoc_data);
				}

				// load any assigned working day (generic)
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_emp_worktime'))
					->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee)
					->where($dbo->qn('id_service') . ' <= 0');

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$wd_data = array();
					$wd_data['id_employee'] = $new_id;
					// unset ID location, since it might be not 
					// accessible by the employee
					$wd_data['id_location'] = -1;

					// duplicate services by using the new employee ID
					$wdModel->duplicate($duplicate, $wd_data);
				}
			}
		}

		return $new_ids;
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

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned translation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_lang_employee'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langemployee');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// load any assigned services
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get assoc model
			$model = JModelVAP::getInstance('serempassoc');
			// delete assigned services
			$model->delete($assoc_ids);
		}

		// load any assigned working times
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($worktime_ids = $dbo->loadColumn())
		{
			// get working times model
			$model = JModelVAP::getInstance('worktime');
			// delete assigned working times
			$model->delete($worktime_ids);
		}

		// load any assigned locations
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_employee_location'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($location_ids = $dbo->loadColumn())
		{
			// get locations model
			$model = JModelVAP::getInstance('location');
			// delete assigned locations
			$model->delete($location_ids);
		}

		// load any employee-coupon relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_coupon_employee_assoc'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('couponemployee');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any employee-payment relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('payment');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any employee-custom field relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_custfields'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('customf');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any employee settings
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_employee_settings'))
			->where($dbo->qn('id_employee') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('empsettings');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}

	/**
	 * Creates a new column within the employees database table.
	 *
	 * @param 	string 	 $name  The field name.
	 * @param 	string   $type  The field type.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @throws  Exception
	 */
	public function createColumn($name, $type = 'text')
	{
		$dbo = JFactory::getDbo();
			
		/**
		 * Use a text type instead of a varchar for textarea fields,
		 * which might be used as editors.
		 *
		 * @since 1.6.3
		 * @since 1.7.4 Accept up to 1024 chars for files custom fields.
		 */
		switch ($type)
		{
			case 'textarea':
				$type = 'text';
				break;

			case 'file':
				$type = 'varchar(1024)';
				break;

			default:
				$type = 'varchar(128)';
		}

		$q = "ALTER TABLE `#__vikappointments_employee` ADD COLUMN `field_{$name}` $type DEFAULT NULL";

		try
		{
			// attempt to alter the table
			$dbo->setQuery($q);
			$dbo->execute();
		}
		catch (Exception $e)
		{
			/**
			 * An error occurred, register it
			 *
			 * @since 1.6.2
			 */
			$this->setError(JText::sprintf('VAPCFFORMNAMEALTER_ERROR', $q));

			return false;
		}

		return true;
	}

	/**
	 * Removes an existing column from the employees database table.
	 *
	 * @param 	string  $name  The name of the column to remove.
	 *
	 * @return 	void
	 */
	public function dropColumn($name)
	{
		$dbo = JFactory::getDbo();

		$q = "ALTER TABLE `#__vikappointments_employee` DROP COLUMN `field_{$name}`";

		try
		{
			// attempt to alter the table
			$dbo->setQuery($q);
			$dbo->execute();
		}
		catch (Exception $e)
		{
			/**
			 * Probably the column wasn't properly installed.
			 * Catch error to avoid breaking the flow.
			 *
			 * @since 1.6.2
			 */
		}
	}

	/**
	 * Checks whether the employees database table already owns the
	 * specified column.
	 *
	 * @param 	string   $name  The name of the column to check.
	 *
	 * @return 	boolean  True if existing, false otherwise.
	 */
	public function hasColumn($name)
	{
		// get database table
		$table = $this->getTable();

		// check whether the table object owns the given
		// column as property
		return property_exists($table, 'field_' . $name);
	}

	/**
	 * Returns a list of services assigned to the specified employee.
	 *
	 * @param 	integer  $id      The employee ID.
	 * @param 	boolean  $strict  True to return all the services.
	 *                            False to obtain only the services listed in the front-end.
	 *
	 * @return 	array    A list of services.
	 */
	public function getServices($id, $strict = false)
	{
		$dbo = JFactory::getDbo();
		
		$services = array();

		$q = $dbo->getQuery(true)
			->select('a.*')
			->select($dbo->qn('s.id', 'id_service'))
			->select($dbo->qn('s.name'))
			->select($dbo->qn('s.color'))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('g.id') . ' = ' . $dbo->qn('s.id_group'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_service') . ' = ' . $dbo->qn('s.id'))
			->where($dbo->qn('a.id_employee') . ' = ' . (int) $id);
		
		if ($strict)
		{
			// do not show unpublished services
			$q->where($dbo->qn('s.published') . ' = 1');
		}
		else
		{
			// show published services first
			$q->order($dbo->qn('s.published') . ' DESC');
		}

		$q->order($dbo->qn('g.ordering') . ' ASC');
		$q->order($dbo->qn('s.ordering') . ' ASC');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $service)
		{
			// use a different name for assoc ID
			$service->id_assoc = $service->id;
			// switch service ID to standard name
			$service->id = $service->id_service;
			unset($service->id_service);

			$services[] = $service;
		}

		return $services;
	}

	/**
	 * Returns the timezone of the given employee.
	 *
	 * @param 	integer  $id  The employee ID.
	 *
	 * @return 	string 	 The employee timezone.
	 */
	public function getTimezone($id)
	{
		if (!isset(static::$timezones[$id]))
		{
			// use global timezone by default
			static::$timezones[$id] = JFactory::getApplication()->get('offset', 'UTC');

			// go ahead only in case an employee has been specified
			if ($id)
			{
				$dbo = JFactory::getDbo();

				$q = $dbo->getQuery(true)
					->select($dbo->qn('timezone'))
					->from($dbo->qn('#__vikappointments_employee'))
					->where($dbo->qn('id') . ' = ' . (int) $id);

				$dbo->setQuery($q, 0, 1);
				$tz = $dbo->loadResult();

				if ($tz)
				{
					// use employee timezone
					static::$timezones[$id] = $tz;
				}
			}
		}

		return static::$timezones[$id];
	}

	/**
	 * Helper method used to check whether the specified employee
	 * is active and can be accessed in the front-end.
	 *
	 * @param 	mixed    $employee  Either an object or and ID.
	 *
	 * @return 	boolean  True if active, false otherwise.
	 */
	public function isVisible($employee)
	{
		if (is_numeric($employee))
		{
			// get employee details
			$employee = $this->getItem((int) $employee);
		}
		else
		{
			// treat as object
			$employee = (object) $employee;
		}

		if (!$employee)
		{
			// missing employee
			return false;
		}

		if (!$employee->listable)
		{
			// employee not visible in the front-end
			return false;
		}

		if ($employee->active_to == 0)
		{
			// the employee activation is pending
			return false;
		}

		if ($employee->active_to == 1 && $employee->active_to_date < JFactory::getDate())
		{
			// the employee was active but the license expired
			return false;
		}

		/**
		 * This event can be used to apply additional conditions while checking whether
		 * the specified employee is listable or not. When this event is triggered, the
		 * system already validated the standard conditions and the employee is going
		 * to be listed into the website.
		 *
		 * @param 	object 	 $employee  The employee to check.
		 *
		 * @return 	boolean  Return false to hide the employee.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onCheckEmployeeVisibility', array($employee)))
		{
			// a plugin decided to hide the employee
			return false;
		}

		// at this point, we do not need to look for a LIFETIME status...
		return true;
	}
}

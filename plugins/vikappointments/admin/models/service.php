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
 * VikAppointments service model.
 *
 * @since 1.7
 */
class VikAppointmentsModelService extends JModelVAP
{
	/**
	 * Cache of has own calendars.
	 *
	 * @var array
	 */
	protected static $privCal = array();

	/**
	 * Cache of zip restrictions.
	 *
	 * @var array
	 */
	protected static $zipRestr = array();

	/**
	 * Cache of assigned employees.
	 *
	 * @var array
	 */
	protected static $employees = array();

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
			// use default duration
			$item->duration = 60;
		}

		return $item;
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
			->from($dbo->qn('#__vikappointments_lang_service'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langservice');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// load any service-employee relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_emp_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('serempassoc');
			// delete relations and attached WORKING DAYS
			$model->delete($assoc_ids);
		}

		// load any service-option relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_opt_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('seroptassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any service-rate relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_rates_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('serrateassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any service-restriction relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_ser_restr_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('serrestrassoc');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any service-coupon relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_coupon_service_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('couponservice');
			// delete relations
			$model->delete($assoc_ids);
		}

		// load any service-custom field relation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_cf_service_assoc'))
			->where($dbo->qn('id_service') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($assoc_ids = $dbo->loadColumn())
		{
			// get model
			$model = JModelVAP::getInstance('customfservice');
			// delete relations
			$model->delete($assoc_ids);
		}

		return true;
	}

	/**
	 * Returns a list of employees assigned to the specified service.
	 *
	 * @param 	integer  $id      The service ID.
	 * @param 	boolean  $strict  True to return all the employees.
	 *                            False to obtain only the employees listed in the front-end.
	 *
	 * @return 	array    A list of employees.
	 */
	public function getEmployees($id, $strict = false)
	{
		// check whether the employees of the specified service
		// have been already registered within the internal cache
		if (!isset(static::$employees[$id]))
		{
			static::$employees[$id] = array();

			$dbo = JFactory::getDbo();
			
			$employees = array();

			$q = $dbo->getQuery(true)
				->select('a.*')
				->select($dbo->qn('e.id', 'id_employee'))
				->select($dbo->qn('e.nickname'))
				->from($dbo->qn('#__vikappointments_employee', 'e'))
				->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_employee') . ' = ' . $dbo->qn('e.id'))
				->where($dbo->qn('a.id_service') . ' = ' . (int) $id)
				->order($dbo->qn('a.ordering') . ' ASC');			

			if ($strict)
			{
				$q->where($dbo->qn('e.listable') . ' = 1');
			}

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $employee)
			{
				// use a different name for assoc ID
				$employee->id_assoc = $employee->id;
				// switch employee ID to standard name
				$employee->id = $employee->id_employee;
				unset($employee->id_employee);

				static::$employees[$id][] = $employee;
			}
		}

		return static::$employees[$id];
	}

	/**
	 * Calculates the average price of the specified services.
	 *
	 * @param 	array 	$ids 	A list of services to fetch. Leave empty
	 *                          to load all the services.
	 *
	 * @return 	float   The average price.
	 */
	public function getAveragePrice(array $ids = array())
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('AVG(' . $dbo->qn('price') . ')')
			->from($dbo->qn('#__vikappointments_service'));
		
		if ($ids)
		{
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $ids)) . ')');
		}

		$dbo->setQuery($q);
		return (float) $dbo->loadResult();
	}

	/**
	 * Checks if the given service owns a private calendar
	 * that cannot be shared with other services.
	 *
	 * @param 	integer  $id  The service ID.
	 *
	 * @return 	boolean  True if own calendar, false otherwise.
	 */
	public function hasOwnCalendar($id)
	{
		if (!isset(static::$privCal[$id]))
		{
			static::$privCal[$id] = false;

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('has_own_cal'))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . (int) $id);

			$dbo->setQuery($q, 0, 1);
			static::$privCal[$id] = (bool) $dbo->loadResult();
		}

		return static::$privCal[$id];
	}

	/**
	 * Checks if the given service owns a private calendar
	 * that cannot be shared with other services.
	 *
	 * @param 	integer  $id  The service ID.
	 *
	 * @return 	boolean  True if own calendar, false otherwise.
	 */
	public function hasZipRestriction($id)
	{
		if (!isset(static::$zipRestr[$id]))
		{
			static::$zipRestr[$id] = false;

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('enablezip'))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . (int) $id);

			$dbo->setQuery($q, 0, 1);
			static::$zipRestr[$id] = (bool) $dbo->loadResult();
		}

		return static::$zipRestr[$id];
	}
}

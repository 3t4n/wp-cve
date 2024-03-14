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
 * Populate the options array with the existing employees,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7
 */
class ImportColumnEmployee extends ImportColumn
{
	/**
	 * Employees cache.
	 *
	 * @var array
	 */
	private static $employees = null;

	/**
	 * Binds the internal properties with the given array/object.
	 *
	 * @param 	mixed  $data  Either an array or an object.
	 *
	 * @return 	void
	 */
	protected function setup($data)
	{
		// use parent to set up data
		parent::setup($data);

		foreach (static::getEmployees() as $employee)
		{
			// register employee as option
			$this->options[$employee->id] = $employee->nickname;
		}
	}

	/**
	 * Loads a list of available employees.
	 *
	 * @return 	array
	 */
	protected static function getEmployees()
	{
		// load employees only once
		if (is_null(static::$employees))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'nickname')))
				->from($dbo->qn('#__vikappointments_employee'))
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			
			// cache employees found
			static::$employees = $dbo->loadObjectList();
		}

		return static::$employees;
	}
}

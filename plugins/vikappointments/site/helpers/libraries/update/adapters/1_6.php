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
 * Update adapter for com_vikappointments 1.6 version.
 *
 * This class can include update() and finalise().
 *
 * NOTE. do not call exit() or die() because the update won't be finalised correctly.
 * Return false instead to stop in anytime the flow without errors.
 *
 * @since 1.6
 */
abstract class VAPUpdateAdapter1_6
{
	/**
	 * Method run during update process.
	 *
	 * @param 	object 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	public static function update($parent)
	{
		try
		{
			// define alias for services
			self::defineRecordAlias('service', 'name');
			// define alias for language services
			self::defineRecordAlias('lang_service', 'name');
			// define alias for employees
			self::defineRecordAlias('employee', 'nickname');
			// define alias for language employees
			self::defineRecordAlias('lang_employee', 'nickname');

			// insert new ordering modes for employees list
			self::addEmployeesOrderingModes();

			// fix custom fields
			self::fixCustomFields($parent);

			// move the employee payments within the global DB table
			self::fixEmployeePayments();

			// find working days parent
			self::fixWorkingDays();
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Method run during postflight process.
	 *
	 * @param 	object 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	public static function finalise($parent)
	{
		try
		{
			// fix router configuration
			self::fixRouter($parent);

			// drop employee payments DB table
			self::dropEmployeePayments();
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			
			return false;
		}

		return true;
	}

	/**
	 * Creates an alias for the records that belong to the specified type.
	 *
	 * @param 	string 	 $type 		The entity type.
	 * @param 	string 	 $name_col 	The column containing the record name.
	 *
	 * @param 	boolean  True on success, false otherwise.
	 */
	protected static function defineRecordAlias($type, $name_col)
	{
		VAPLoader::import('libraries.helpers.alias');

		$dbo = JFactory::getDbo();

		$tables = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', $name_col, 'alias')))
			->from($dbo->qn('#__vikappointments_' . $type));

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows())
		{
			// no records to fetch
			return true;
		}

		$records = $dbo->loadObjectList();

		foreach ($records as $obj)
		{
			// use only allowed chars and make sure it is unique
			$obj->alias = AliasHelper::getUniqueAlias($obj->{$name_col}, $type);

			$dbo->updateObject('#__vikappointments_' . $type, $obj, 'id');
		}

		return true;
	}

	/**
	 * Inserts new employees ordering modes:
	 * - price low to high (7)
	 * - price high to low (8)
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function addEmployeesOrderingModes()
	{
		$config = VAPFactory::getConfig();
		$modes  = $config->getArray('emplistmode');

		$modes[7] = 1;
		$modes[8] = 1;
		
		$config->set('emplistmode', $modes);

		return true;
	}

	/**
	 * Fixes the existing custom fields to support
	 * the new DB structure.
	 *
	 * @param 	object 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixCustomFields($parent)
	{
		// check if the preflight method (script.php) was
		// able to retrieve the existing custom fields
		if (!isset($parent->customFields))
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		// iterate the custom fields
		foreach ($parent->customFields as $field)
		{
			// find the rule to use
			if ($field->isnominative)
			{
				$field->rule = 1;
			}
			else if ($field->isemail)
			{
				$field->rule = 2;
			}
			else if ($field->isphone)
			{
				$field->rule = 3;
			}
			else
			{
				$field->rule = 0;
			}

			// update only the rule found
			$q = $dbo->getQuery(true)
				->update('#__vikappointments_custfields')
				->set($dbo->qn('rule') . ' = ' . $field->rule)
				->where($dbo->qn('id') . ' = ' . $field->id);

			$dbo->setQuery($q);
			$dbo->execute();
		}

		return true;
	}

	/**
	 * Moves the employee payments within the global
	 * database table. The record will be assigned to the
	 * employees using a specific column.
	 * 
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixEmployeePayments()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_employee_payment'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $payment)
			{
				// unset primary key as the record needs to be added
				unset($payment->id);

				$dbo->insertObject('#__vikappointments_gpayments', $payment, 'id');
			}
		}

		return true;
	}

	/**
	 * Finds the parent of the existing working days,
	 * so that the duplicated records (for the services) will
	 * belong always to the original record.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixWorkingDays()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_emp_worktime'));

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows())
		{
			return true;
		}

		$wdays = $dbo->loadObjectList();

		foreach ($wdays as $wd)
		{
			if ($wd->id_service == -1)
			{
				continue;
			}

			foreach ($wdays as $inner)
			{
				if ($wd->id == $inner->id || $inner->id_service != -1)
				{
					continue;
				}

				if (self::matchWorkingDays($wd, $inner))
				{
					$wd->parent = $inner->id;
					$dbo->updateObject('#__vikappointments_emp_worktime', $wd, 'id');
					break;
				}

			}
		}

		return true;
	}

	/**
	 * Checks if 2 working days are matching.
	 *
	 * @param 	object 	 $a  The first working day.
	 * @param 	object 	 $b  The second working day.
	 *
	 * @return 	boolean  True if equals, false otherwise.
	 */
	protected static function matchWorkingDays($a, $b)
	{
		return ($a->id_employee == $b->id_employee
			&& $a->day == $b->day
			&& $a->fromts == $b->fromts
			&& $a->endts == $b->endts
			&& $a->ts == $b->ts
			&& $a->closed == $b->closed);
	}

	/**
	 * Fixes the router configuration by adding a new
	 * dedicated setting and by renaming the _router.php
	 * into router.php.
	 *
	 * @param 	object 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixRouter($parent)
	{
		$dbo = JFactory::getDbo();

		$value = (int) $parent->routerEnabled;

		$q = $dbo->getQuery(true)
			->insert($dbo->qn('#__vikappointments_config'))
			->columns($dbo->qn(array('param', 'setting')))
			->values($dbo->q('router') . ', ' . $value);

		$dbo->setQuery($q);
		$dbo->execute();

		$res = (bool) $dbo->insertid();

		if (is_file(VAPBASE . DIRECTORY_SEPARATOR . '_router.php'))
		{
			$res = unlink(VAPBASE . DIRECTORY_SEPARATOR . '_router.php');
		}

		return $res;
	}

	/**
	 * Deletes the employee payments table from the database.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function dropEmployeePayments()
	{
		$dbo = JFactory::getDbo();

		$q = "DROP TABLE `#__vikappointments_employee_payment`";

		$dbo->setQuery($q);
		$dbo->execute();

		return true;
	}
}

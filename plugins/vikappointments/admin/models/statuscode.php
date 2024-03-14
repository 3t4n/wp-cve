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
 * VikAppointments status code model.
 *
 * @since 1.7
 */
class VikAppointmentsModelStatuscode extends JModelVAP
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
		$data = (array) $data;

		$old = null;

		// in case of update of a status, we should make
		// sure whether the given code is changing
		if (!empty($data['code']) && !empty($data['id']))
		{
			$table = $this->getTable();

			// attempt to load the status details and look for any changes
			if ($table->load($data['id']) && $data['code'] != $table->code)
			{
				// the code seems to change, register old properties for later use
				$old = $table->getProperties();
			}
		}

		// attempt to save the relation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		if ($old)
		{
			// The code changed, we need to update all the records that
			// are currently assigned to that status code.
			// Mass update records without caring of triggering any events,
			// since we are doing a stability update.
			$dbo = JFactory::getDbo();

			if ($old['appointments'])
			{
				$q = $dbo->getQuery(true)
					->update($dbo->qn('#__vikappointments_reservation'))
					->set($dbo->qn('status') . ' = ' . $dbo->q($data['code']))
					->where($dbo->qn('status') . ' = ' . $dbo->q($old['code']));

				$dbo->setQuery($q);
				$dbo->execute();
			}

			if ($old['packages'])
			{
				$q = $dbo->getQuery(true)
					->update($dbo->qn('#__vikappointments_package_order'))
					->set($dbo->qn('status') . ' = ' . $dbo->q($data['code']))
					->where($dbo->qn('status') . ' = ' . $dbo->q($old['code']));

				$dbo->setQuery($q);
				$dbo->execute();
			}

			if ($old['subscriptions'])
			{
				$q = $dbo->getQuery(true)
					->update($dbo->qn('#__vikappointments_subscr_order'))
					->set($dbo->qn('status') . ' = ' . $dbo->q($data['code']))
					->where($dbo->qn('status') . ' = ' . $dbo->q($old['code']));

				$dbo->setQuery($q);
				$dbo->execute();
			}

			$q = $dbo->getQuery(true)
				->update($dbo->qn('#__vikappointments_cust_mail'))
				->set($dbo->qn('status') . ' = ' . $dbo->q($data['code']))
				->where($dbo->qn('status') . ' = ' . $dbo->q($old['code']));

			$dbo->setQuery($q);
			$dbo->execute();
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
			->from($dbo->qn('#__vikappointments_lang_status_code'))
			->where($dbo->qn('id_status_code') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langstatuscode');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		return true;
	}

	/**
	 * Helper method used to ensure that all the required status codes have been
	 * properly configured for all the sections.
	 * 
	 * It is possible to use the getErrors() method to fetch the list of errors
	 * that have been registered while running the tests.
	 * 
	 * @return 	boolean  True in case of success, false otherwise.
	 * 
	 * @since 	1.7.1
	 */
	public function runTests()
	{
		// build the array of tests
		$tests = [
			// define an array of status codes required to the appointments group
			'appointments' => [
				'confirmed',
				'paid',
				'pending',
				'removed',
				'cancelled',
			],
			// define an array of status codes required to the packages group
			'packages' => [
				'confirmed',
				'paid',
				'pending',
			],
			// define an array of status codes required to the subscriptions group
			'subscriptions' => [
				'confirmed',
				'paid',
				'pending',
			],
		];

		// ignore tests for the packages in case this section is unused
		if (VAPFactory::getConfig()->getBool('enablepackages') === false)
		{
			// packages disabled, ignore tests for this group
			unset($tests['packages']);
		}

		VAPLoader::import('libraries.models.subscriptions');

		// ignore tests for the subscriptions in case this section is unused
		if (!VAPSubscriptions::has(0) && !VAPSubscriptions::has(1))
		{
			unset($tests['subscriptions']);
		}

		$status = true;

		// iterate all groups
		foreach ($tests as $group => $roles)
		{
			// iterate all roles
			foreach ($roles as $role)
			{
				try
				{
					// try to fetch the status code
					JHtml::fetch('vaphtml.status.' . $role, $group, $column = 'code', $strict = true);
				}
				catch (Exception $e)
				{
					// status not found, register the error message (include the group alias)
					$this->setError($e->getMessage() . ' (' . $group . ')');
					$status = false;
				}
			}
		}

		return $status;
	}

	/**
	 * Restores the status codes to the factory settings.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7.1
	 */
	public function restore()
	{
		$dbo = JFactory::getDbo();

		// delete all the existing status codes
		$q = "TRUNCATE TABLE `#__vikappointments_status_code`";
		$dbo->setQuery($q);
		$dbo->execute();

		// delete all the existing status codes translations
		$q = "TRUNCATE TABLE `#__vikappointments_lang_status_code`";
		$dbo->setQuery($q);
		$dbo->execute();

		// re-create all the default status codes
		$q = "INSERT INTO `#__vikappointments_status_code`
		(     `name`, `code`,  `color`, `ordering`, `approved`, `reserved`, `expired`, `cancelled`, `paid`, `appointments`, `packages`, `subscriptions`) VALUES
		('Confirmed',    'C', '008000',          1,          1,          1,         0,           0,      0,              1,          1,               1),
		(     'Paid',    'P', '339CCC',          2,          1,          1,         0,           0,      1,              1,          1,               1),
		(  'Pending',    'W', 'FF7000',          3,          0,          1,         0,           0,      0,              1,          1,               1),
		(  'Removed',    'E', '990000',          4,          0,          0,         1,           0,      0,              1,          0,               0),
		('Cancelled',    'X', 'F01B17',          5,          0,          0,         0,           1,      0,              1,          1,               1),
		( 'Refunded',    'R', '8116C9',          6,          0,          0,         0,           1,      1,              1,          1,               1),
		(  'No-Show',    'N', '828282',          7,          1,          1,         0,           0,      0,              1,          0,               0);";

		$dbo->setQuery($q);
		$dbo->execute();
	}
}

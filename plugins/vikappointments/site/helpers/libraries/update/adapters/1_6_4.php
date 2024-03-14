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
 * Update adapter for com_vikappointments 1.6.4 version.
 *
 * This class can include update() and finalise().
 *
 * NOTE. do not call exit() or die() because the update won't be finalised correctly.
 * Return false instead to stop in anytime the flow without errors.
 *
 * @since 1.6.4
 */
abstract class VAPUpdateAdapter1_6_4
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
			// updates the ordering of the subscriptions
			self::fixSubscriptionsOrdering();

			// updates the ordering of the services-employees relations
			self::fixServicesEmployeesOrdering();
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Updates the ordering columns of the subscriptions.
	 * 
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixSubscriptionsOrdering()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_subscription'))
			->order(array(
				$dbo->qn('type') . ' ASC',
				$dbo->qn('amount') . ' ASC',
			));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $i => $subscr)
			{
				// update ordering
				$subscr->ordering = $i + 1;

				$dbo->updateObject('#__vikappointments_subscription', $subscr, 'id');
			}
		}

		return true;
	}

	/**
	 * Updates the ordering columns of the services-employees relations.
	 * 
	 * @return 	boolean  True on success, false otherwise.
	 */
	protected static function fixServicesEmployeesOrdering()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_service'));

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows())
		{
			// no installed services
			return true;
		}

		foreach ($dbo->loadColumn() as $id_service)
		{
			$q = $dbo->getQuery(true)
				->select($dbo->qn('a.id'))
				->from($dbo->qn('#__vikappointments_ser_emp_assoc', 'a'))
				->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
				->where($dbo->qn('a.id_service') . ' = ' . $id_service)
				->order($dbo->qn('e.nickname') . ' ASC');

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows())
			{
				foreach ($dbo->loadColumn() as $i => $id)
				{
					$q = $dbo->getQuery(true)
						->update($dbo->qn('#__vikappointments_ser_emp_assoc'))
						->set($dbo->qn('ordering') . ' = ' . ($i + 1))
						->where($dbo->qn('id') . ' = ' . $id);

					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
		}

		return true;
	}
}

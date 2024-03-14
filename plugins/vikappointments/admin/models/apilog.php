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
 * VikAppointments API log model.
 *
 * @since 1.7
 */
class VikAppointmentsModelApilog extends JModelVAP
{
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
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// find number of existing logs
		$q = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_api_login_logs'));

		$dbo->setQuery($q, 0, 1);

		if ((int) $dbo->loadResult() == count($ids))
		{
			// truncate all in case the user selected all the existing logs
			return $this->truncate();
		}

		// otherwise invoke parent to delete logs
		return parent::delete($ids);
	}

	/**
	 * Method to truncate the table.
	 *
	 * @param 	integer  $id_login  An optional ID to delete all the logs assigned to the
	 *                              specified account.
	 *
	 * @return  boolean  True on success.
	 */
	public function truncate($id_login = null)
	{
		$dbo = JFactory::getDbo();

		if ($id_login)
		{
			// delete only the logs assigned to the specified account
			$q = $dbo->getQuery(true)
				->delete($dbo->qn('#__vikappointments_api_login_logs'))
				->where($dbo->qn('id_login') . ' = ' . (int) $id_login);
		}
		else
		{
			// truncate API logs
			$q = "TRUNCATE TABLE " . $dbo->qn('#__vikappointments_api_login_logs');
		}

		$dbo->setQuery($q);
		return $dbo->execute();
	}

	/**
	 * Flushes older API logs.
	 *
	 * @return 	void
	 */
	public function flush()
	{
		$factor = VAPFactory::getConfig()->getUint('apilogflush');

		if ($factor > 0)
		{
			$dbo = JFactory::getDbo();

			$now = JFactory::getDate();
			$now->modify('-' . $factor . ' days');

			$q = $dbo->getQuery(true)
				->delete($dbo->qn('#__vikappointments_api_login_logs'))
				->where($dbo->qn('createdon') . ' < ' . $dbo->q($now->toSql()));
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
}

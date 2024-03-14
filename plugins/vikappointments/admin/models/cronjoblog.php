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
 * VikAppointments cron job log model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCronjoblog extends JModelVAP
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
		// fetch cron logging mode:
		// - [1] only with errors
		// - [2] always
		$cron_logging_mode = VAPFactory::getConfig()->getUint('cron_log_mode');

		// auto-flush existing logs first
		$this->flush(@$data['id_cronjob']);
		
		if ($cron_logging_mode == 1 && !empty($data['status']))
		{
			// get last succesful log
			$last = $this->getItem(array(
				'id_cronjob' => @$data['id_cronjob'],
				'status'     => 1,
			));

			if ($last)
			{
				// delete log found
				$this->delete($last->id);
			}
		}

		// attempt to save the cron job log
		return parent::save($data);
	}

	/**
	 * Method to truncate the table.
	 *
	 * @param 	int   $id_cron  An optional ID to delete all the logs assigned to the
	 *                          specified cron job.
	 *
	 * @return  bool  True on success, false otherwise.
	 * 
	 * @since 	1.7.3
	 */
	public function truncate($id_cron = null)
	{
		$dbo = JFactory::getDbo();

		if ($id_cron)
		{
			// delete only the logs assigned to the specified cron job
			$q = $dbo->getQuery(true)
				->delete($dbo->qn('#__vikappointments_cronjob_log'))
				->where($dbo->qn('id_cronjob') . ' = ' . (int) $id_cron);
		}
		else
		{
			// truncate all the logs registered by any cron jobs
			$q = "TRUNCATE TABLE " . $dbo->qn('#__vikappointments_cronjob_log');
		}

		$dbo->setQuery($q);
		return $dbo->execute();
	}

	/**
	 * Flushes all the cron logs that are at least N days in the past, where
	 * N is equals to the passed argument.
	 * 
	 * @param 	int       $id_cron  An optional cron ID to flush only the logs
	 *                              assigned to the latter.
	 * @param 	int|null  $days     The threshold in days. Use the defaul system
	 *                              setting when not specified.
	 *
	 * @return 	void
	 * 
	 * @since 	1.7.3
	 */
	public function flush($id_cron = null, $days = null)
	{
		if (is_null($days))
		{
			// use global setting
			$days = VAPFactory::getConfig()->getUint('cron_log_flush', 0);
		}

		if ($days > 0)
		{
			$dbo = JFactory::getDbo();

			$now = JFactory::getDate();
			$now->modify('-' . $days . ' days');

			$q = $dbo->getQuery(true)
				->delete($dbo->qn('#__vikappointments_cronjob_log'))
				->where($dbo->qn('createdon') . ' < ' . $dbo->q($now->toSql()));

			if ($id_cron)
			{
				// filter logs by cron job
				$q->where($dbo->qn('id_cronjob') . ' = ' . (int) $id_cron);
			}
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
}

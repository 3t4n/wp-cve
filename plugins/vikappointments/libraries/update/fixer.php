<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  update
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

VAPLoader::import('libraries.update.adapter');

/**
 * Implements the abstract methods to fix an update.
 *
 * Never use exit() and die() functions to stop the flow.
 * Return false instead to break process safely.
 *
 * @since 1.0
 */
class VikAppointmentsUpdateFixer
{
	/**
	 * The current version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Class constructor.
	 */
	public function __construct($version)
	{
		$this->version = $version;
	}

	/**
	 * This method is called before the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function beforeInstallation()
	{
		if (version_compare($this->version, '1.2', '<') && !VikAppointmentsLiteManager::guessPro())
		{
			$dbo = JFactory::getDbo();
			// truncate the payment gateways table
			$dbo->setQuery("TRUNCATE TABLE `#__vikappointments_gpayments`");
			$dbo->execute();
		}

		return true;
	}

	/**
	 * This method is called after the SQL installation.
	 *
	 * @return 	boolean  True to proceed with the update, otherwise false to stop.
	 */
	public function afterInstallation()
	{
		if (version_compare($this->version, '1.1.3', '<'))
		{
			$this->fix_1_1_3();
		}
		
		if (version_compare($this->version, '1.2', '<'))
		{
			$this->fix_1_2();
		}

		if (version_compare($this->version, '1.2.3', '<'))
		{
			$this->fix_1_2_3();
		}

		if (version_compare($this->version, '1.2.4', '<'))
		{
			$this->fix_1_2_4();
		}

		return true;
	}

	/**
	 * Fix 1.2.4 version.
	 *
	 * @return void
	 */
	protected function fix_1_2_4()
	{
		// create customizer folder
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'customizer');
	}

	/**
	 * Fix 1.2.3 version.
	 *
	 * @return void
	 */
	protected function fix_1_2_3()
	{
		// use the old notation of the hook used to dispatch the cron jobs
		$hook = 'vikappointments_cron_listener';

		// fetch the next scheduled event
		$event = wp_get_scheduled_event($hook);

		if ($event)
		{
			// unschedule the event
			wp_unschedule_event($event->timestamp, $hook);
		}

		// create customizer folder
		JFolder::create(VAP_UPLOAD_DIR_PATH . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'customizer');
	}

	/**
	 * Fix 1.2 version.
	 *
	 * @return void
	 */
	protected function fix_1_2()
	{
		JFolder::create(VAPCUSTOMERS_DOCUMENTS);

		// load the update adapter used for 1.7 version in Joomla
		VAPLoader::import('libraries.update.adapters.1_7');
		// instantiate the update adapter
		$adapter = new VAPUpdateAdapter1_7();

		// launch all the update methods
		$adapter->update($this);
		$adapter->finalise($this);
		$adapter->afterupdate($this);
	}

	/**
	 * Fix 1.1.3 version.
	 *
	 * @return void
	 */
	protected function fix_1_1_3()
	{
		$dbo = JFactory::getDbo();

		//////////////////////////////////
		/// FIX SUBSCRIPTIONS ORDERING ///
		//////////////////////////////////

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

		///////////////////////////////////////
		/// FIX SERVICES-EMPLOYEES ORDERING ///
		///////////////////////////////////////

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
	}
}

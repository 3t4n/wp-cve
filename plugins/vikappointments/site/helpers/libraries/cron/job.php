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
 * The class used to manage the procedure and the configuration of a CronJob.
 *
 * @since 1.5
 * @since 1.7 Renamed from CronJob.
 */
abstract class VAPCronJob
{
	/**
	 * The unique identifier of the cron job.
	 *
	 * @var integer
	 */
	private $id;

	/** 
	 * The list containing the settings of the cron job.
	 * The settings of the cron job cannot be directly accessed (@see get() method).
	 *
	 * @var array
	 */
	private $args;

	/**
	 * The construct of the cron job to initialize the required parameters of this object.
	 *
	 * @param 	integer  $id 	The Cron Job ID.
	 * @param 	mixed 	 $args 	The configuration array.
	 */
	public function __construct($id, $args = array())
	{
		$this->id 	= $id;
		$this->args = $args && is_string($args) ? json_decode($args, true) : (array) $args;
	}

	/**
	 * Sets the ID of the cron job.
	 *
	 * @param 	integer  $id  The record identifier.
	 *
	 * @return 	void
	 */
	public function setID($id)
	{
		if (!$this->id)
		{
			// change ID only in case it was not set
			$this->id = (int) $id;
		}
	}

	/**
	 * Returns the ID of the cron job.
	 *
	 * @return 	integer  The identifier of the cron job.
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * Returns the title of the cron job.
	 *
	 * @return 	string 	The title of the cron job.
	 */
	public function title()
	{
		return '';
	}

	/**
	 * Returns the description of the cron job.
	 *
	 * @return 	string 	The description of the cron job.
	 *
	 * @since 	1.7
	 */
	public function description()
	{
		return '';
	}

	/**
	 * Returns the value of the setting specified. An empty value if the setting doesn't exist.
	 * The settings of the cronjob are not accessible from external classes.
	 *
	 * @param 	string  $key 	The name of the setting.
	 * @param 	mixed 	$def 	The default value to get if the
	 * 							setting doesn't exist.
	 *
	 * @return 	string 	The value of the setting.
	 */
	protected function get($key, $def = '')
	{
		if (array_key_exists($key, $this->args))
		{
			return $this->args[$key];
		}
		
		return $def;
	}

	/**	 
	 * Performs the work that the cron job should do.
	 *
	 * @return 	VAPCronJobResponse  The response of the job.
	 */
	public abstract function doJob();

	/**
	 * Returns the fields of the configuration in an array.
	 *
	 * @return 	array 	The fields list used for the configuration. 
	 */
	public function getConfiguration()
	{
		return array();
	}

	/**
	 * This function is called only once during the installation of the cron job.
	 * Returns true on success, otherwise false.
	 *
	 * @return  boolean	 The status of the installation.
	 */
	public function install()
	{
		return true;
	}

	/**
	 * This function is called only once during the uninstallation of the cron job.
	 * Returns true on success, otherwise false.
	 *
	 * @return 	boolean  The status of the uninstallation.
	 */
	public function uninstall()
	{
		return true;
	}
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPCronJob', 'CronJob');

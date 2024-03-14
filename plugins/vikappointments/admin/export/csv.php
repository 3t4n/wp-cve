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
 * Class used to export a list of reservations in CSV format.
 *
 * @deprecated 1.8  Use VAPOrderExportFactory instead.
 */
class VikExporterCSV
{
	/**
	 * An array of options.
	 *
	 * @var array
	 */
	private $options;
	
	/**
	 * Flag to start the auto-download or not.
	 *
	 * @var boolean
	 */
	private $auto_download = true;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer  $from_ts 	The starting timestamp.
	 * @param 	integer  $to_ts 	The ending timestamp.
	 * @param 	integer  $id_emp 	The employee ID.
	 */
	public function __construct($from_ts, $to_ts, $id_emp = 0)
	{
		if (is_int($from_ts))
		{
			$from_ts = date('Y-m-d', $from_ts);
		}

		if (is_int($to_ts))
		{
			$to_ts = date('Y-m-d', $to_ts);
		}

		$this->options = array(
			'fromdate'    => $from_ts,
			'todate'      => $to_ts,
			'id_employee' => $id_emp,
		);
	}
	
	/**
	 * Forces the ID of the order to fetch.
	 *
	 * @param 	integer  $id_order 	The order ID.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function forceOrderID($id_order)
	{
		$this->options['cid'] = (array) $id_order;

		return $this;
	}
	
	/**
	 * Sets if the download starts automatically or not.
	 *
	 * @param 	boolean  $enabled 	True for the auto-download,
	 * 								false to write the contents only.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function setAutoDownload($enabled)
	{
		$this->auto_download = $enabled;

		return $this;
	}

	/**
	 * Forces the ID of the employee.
	 *
	 * @param 	integer  $id_emp 	The ID of the employee.
	 *
	 * @return 	self 	 This object to support chaining.
	 */
	public function setEmployee($id_emp)
	{
		$this->options['id_employee'] = $id_emp;
		
		return $this;
	}
	
	/**
	 * Sets if the CSV can contain sensitive data.
	 * For example the name of the employee (if not visible) will
	 * be shown only for an administrator.
	 *
	 * @param 	boolean  True to allow sensitive data, otherwise false.
	 * 
	 * @return 	self 	 This object to support chaining.
	 */
	public function setAdminInterface($is)
	{
		$this->options['admin'] = $is;

		return $this;
	}
	
	/**
	 * Fetches the records to export.
	 *
	 * @return 	array 	The resulting array to export.
	 */
	public function getString()
	{	
		return array();
	}
	
	/**
	 * Exports the provided records using the CSV format.
	 * When the auto-download is enabled, the method will stop the flow (exit).
	 *
	 * @param 	array 	$csv 		The records to export, usually fetched 
	 * 								with the getString() method.
	 * @param 	string 	$file_name 	The file name to use. In case the auto-download
	 * 								is disabled, the CSV will by written in this file (full path).
	 *
	 * @return 	void
	 */
	public function export(array $csv = array(), $file_name = '')
	{
		VAPLoader::import('libraries.order.export.factory');

		// get ICS export driver
		$driver = VAPOrderExportFactory::getInstance('csv', 'appointment', $this->options);

		// should we auto-download the CSV?
		if ($this->auto_download)
		{
			// download file
			$driver->download($file_name);
		}
		else
		{
			// export CSV
			$csv = $driver->export();
			// put CSV into the specified file
			file_put_contents($file_name, $csv);
		}
	}	
}

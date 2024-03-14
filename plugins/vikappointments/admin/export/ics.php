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
 * Class used to export a list of reservations in ICS format.
 *
 * The ICS format is compatible with any calendar client/cloud service,
 * such as Apple iCal or Google Calendar.
 *
 * Since Microsoft Outlook Calendar uses non-standard syntax, this ICS
 * may not display the correct timezone when the DST is on.
 *
 * @deprecated 1.8  Use VAPOrderExportFactory instead.
 */
class VikExporterICS
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
	public function __construct($from_ts, $to_ts, $id_emp)
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
	 * Sets the ICS heading information.
	 *
	 * @param 	string 	$version 	The ICS syntax version.
	 * @param 	string 	$calscale 	The scale of the calendar.
	 * @param 	string 	$timezone 	The timezone to use.
	 */
	public function setHeader($version = '2.0', $calscale = 'GREGORIAN', $timezone = null)
	{
		$this->head = "VERSION:{$version}\n";
		$this->head .= "PRODID:-//e4j//VikAppointments " . VIKAPPOINTMENTS_SOFTWARE_VERSION . "//EN\n";
		$this->head .= "CALSCALE:{$calscale}\n";

		if (empty($timezone))
		{
			$timezone = date_default_timezone_get();
		}

		$this->head .= "X-WR-TIMEZONE:{$timezone}\n";
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
	 * Used to set a different title depending on the client.
	 * For administrator: SERVICE_NAME - FIRST_NAME LAST_NAME
	 * For customers: SERVICE_NAME
	 *
	 * Also used to display sensitive data or not.
	 * For example the name of the employee (if not visible) will
	 * be shown only for an administrator.
	 *
	 * @param 	boolean  True for administrator, otherwise false.
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
	 * @return 	string 	The resulting string to export.
	 */
	public function getString()
	{
		VAPLoader::import('libraries.order.export.factory');

		// get ICS export driver
		$driver = VAPOrderExportFactory::getInstance('ics', 'appointment', $this->options);

		// export string
		return $driver->export();
	}
	
	/**
	 * Exports the provided ICS string in the apposite format.
	 * When the auto-download is enabled, the method will stop the flow (exit).
	 *
	 * @param 	string 	$ics 		The ICS string to export, usually fetched 
	 * 								with the getString() method.
	 * @param 	string 	$file_name 	The file name to use. In case the auto-download
	 * 								is disabled, the ICS will by written in this file (full path).
	 *
	 * @return 	void
	 */
	public function export($ics = '', $file_name = '')
	{
		if ($this->auto_download)
		{
			header("Content-Type: application/octet-stream;"); 
			header("Content-Disposition: attachment;filename=\"{$file_name}\"");
			header("Cache-Control: no-store, no-cache");
		
			$f = fopen('php://output', 'w');
			fwrite($f, $ics);
			fclose($f);

			exit;
		}
		else
		{
			$f = fopen($file_name, 'w+');
			$w = fwrite($f, $ics);
			fclose($f);
		}
	}

	/**
	 * Displays the ICS string on a blank web page.
	 *
	 * @param 	string 	$ics 		The ICS string to export, usually fetched 
	 * 								with the getString() method.
	 * @param 	string 	$file_name 	The file name to use.
	 *
	 * @return 	void
	 */
	public function renderBrowser($ics, $file_name)
	{
		header("Content-Type: text/calendar; charset=utf-8");
		header("Content-Disposition: attachment; filename=\"{$file_name}\"");
		echo $ics;
	}
}

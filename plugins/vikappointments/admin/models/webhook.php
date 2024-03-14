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
 * VikAppointments web hook model.
 *
 * @since 1.7
 */
class VikAppointmentsModelWebhook extends JModelVAP
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

		$config = VAPFactory::getConfig();

		$maxfail = $config->getUint('webhooksmaxfail', 0);

		// in case of failures counter, make sure the web hook didn't exceed the maximum threshold
		if (isset($data['failed']) && $maxfail > 0 && $data['failed'] >= $maxfail)
		{
			// threshold reached, auto-unpublish the web hook
			$data['published'] = 0;
		}

		// attempt to save the web hook
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		$logspath = $config->get('webhookslogspath');

		if (!$logspath)
		{
			// use default path when missing
			$logspath = JFactory::getApplication()->get('log_path', '');
		}

		if (!$config->getBool('webhooksuselog'))
		{
			// logs disabled, avoid storing them
			$data['log'] = '';
		}

		// register logs
		if (!empty($data['log']) && $logspath && is_dir($logspath))
		{
			// check how the logs should be grouped
			$group = $config->getString('webhooksgroup', 'day');

			$now = JFactory::getDate();

			switch ($group)
			{
				case 'week':
					$filename = $now->format('Y-W');
					break;

				case 'month':
					$filename = $now->format('Y-m');
					break;

				default:
					$filename = $now->format('Y-m-d');
			}

			// in case the log key was not specified, we need to load it
			if (!isset($data['logkey']))
			{
				// load item details
				$data['logkey'] = $this->getItem($id, true)->logkey;
			}

			// build log file name
			$filename = 'webhook_' . $id . '_' . $filename . ($data['logkey'] ? '_' . $data['logkey'] : '') . '.log';

			$date = $now->format('c');

			// create log body
			$log  = $date . "\n" . str_repeat('-', strlen($date)) . "\n\n";;
			$log .= is_string($data['log']) ? $data['log'] : print_r($data['log'], true);
			$log .= "\n\n";

			// open log file and append log data (create if the file doesn't exist)
			$handle = fopen(rtrim($logspath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename, 'a');
			fwrite($handle, $log);
			fclose($handle);
		}

		return $id;
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		// load item through parent
		$item = parent::getItem($pk, $new);

		if ($item)
		{
			// decode encoded parameters
			$item->params = $item->params ? (array) json_decode($item->params, true) : array();
		}

		return $item;
	}

	/**
	 * Returns a list of registered log files for the given web hook.
	 *
	 * @param 	integer  $id  The web hook PK.
	 *
	 * @return 	array    An array of log files.
	 */
	public function getLogFiles($id)
	{
		if (!$id)
		{
			// we cannot have logs for a web hook before its creation
			return array();
		}

		// fetch logs path
		$config = VAPFactory::getConfig();
		$dir = $config->get('webhookslogspath');

		if (!$dir)
		{
			// log path not specified, use the default one
			$dir = JFactory::getApplication()->get('log_path', '');
		}

		if (!$dir || !is_dir($dir))
		{
			// invalid folder
			return array();
		}

		$logs = array();

		// load all log files contained within this folder that starts with "webhook_[ID]"
		$files = glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'webhook_' . (int) $id . '*.log');

		foreach ($files as $file)
		{
			$name = basename($file);

			if (preg_match("/^webhook_[0-9]+_([0-9\-]+)_/", $name, $match))
			{
				// extract readable name from file
				$name = end($match);
			}
			
			$logs[$file] = $name;
		}

		return $logs;
	}

	/**
	 * Loads the log details from the specified path.
	 *
	 * @param 	string  $file  The file path.
	 *
	 * @return 	mixed   The log details on success, false otherwise.
	 */
	public function getLog($file)
	{
		$config = VAPFactory::getConfig();
		$dir = $config->get('webhookslogspath');

		if (!$dir)
		{
			// log path not specified, use the default one
			$dir = JFactory::getApplication()->get('log_path', '');
		}

		// make sure the file exists
		if (is_file($file))
		{
			// file found, make sure we are not trying to exceed to a different path
			if (strpos($file, $dir) !== 0)
			{
				// trying to access a different folder
				return false;
			}
		}
		else
		{
			// file not found, probably we received only the file name
			$file = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;

			if (!is_file($file))
			{
				// file not found
				return false;
			}
		}

		$handle = fopen($file, 'r');

		if (!$handle)
		{
			return '';
		}

		$buffer = '';

		while (!feof($handle))
		{
			$buffer .= fread($handle, 8192);
		}

		fclose($handle);

		return $buffer;
	}
}

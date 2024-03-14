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

VAPLoader::import('libraries.backup.import.rule.sql');

/**
 * Backup SQL File import rule.
 * 
 * @since 1.7.1
 */
class VAPBackupImportRuleSqlfile extends VAPBackupImportRuleSql
{
	/**
	 * Executes the backup import command.
	 * 
	 * @param 	mixed  $data  The import rule instructions.
	 * 
	 * @return 	void
	 */
	public function execute($data)
	{
		if (empty($data->path))
		{
			// missing file path
			throw new Exception('Invalid SQL File import rule, path missing', 500);
		}

		// set up the path from which the SQL queries should be loaded
		$path = JPath::clean($this->path . '/' . $data->path);

		if (!JFile::exists($path))
		{
			// file not found
			throw new Exception(sprintf('Invalid SQL File import rule, file [%s] not found', $path), 404);
		}

		$buffer = '';

		// open file
		$fp = fopen($path, 'r');

		while (!feof($fp))
		{
			// read from file and copy into the buffer
			$buffer .= fread($fp, 8192);
		}

		// close file
		fclose($fp);

		$queries = [];

		// split SQL queries into an array according to the platform in use
		if (VersionListener::isJoomla())
		{
			$queries = JDatabaseDriver::splitSql($buffer);
		}
		else if (VersionListener::isWordpress())
		{
			$queries = JDatabaseHelper::splitSql($buffer);
		}

		// free some space
		unset($buffer);

		// execute queries through the parent
		parent::execute($queries);

		// free some space
		unset($queries);
	}
}

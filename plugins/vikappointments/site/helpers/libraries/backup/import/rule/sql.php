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

VAPLoader::import('libraries.backup.import.rule');

/**
 * Backup SQL import rule.
 * 
 * @since 1.7.1
 */
class VAPBackupImportRuleSql extends VAPBackupImportRule
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
		$dbo = JFactory::getDbo();

		// iterate all specified queries
		foreach ((array) $data as $q)
		{
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
}

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

VAPLoader::import('libraries.backup.export.type.full');

/**
 * MANAGEMENT backup export type.
 * 
 * @since 1.7.1
 */
class VAPBackupExportTypeManagement extends VAPBackupExportTypeFull
{
	/**
	 * Returns a readable name of the export type.
	 * 
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAP_BACKUP_EXPORT_TYPE_MANAGEMENT');
	}

	/**
	 * Returns a readable description of the export type.
	 * 
	 * @return 	string
	 */
	public function getDescription()
	{
		return JText::translate('VAP_BACKUP_EXPORT_TYPE_MANAGEMENT_DESCRIPTION');
	}

	/**
	 * Returns an array of database tables to export.
	 * 
	 * @return 	array
	 */
	protected function getDatabaseTables()
	{
		// get database tables from parent
		$tables = parent::getDatabaseTables();

		// define list of database tables to exclude
		$exclude = [
			// native
			'#__vikappointments_reservation',
			'#__vikappointments_res_opt_assoc',
			'#__vikappointments_waitinglist',
			'#__vikappointments_reviews',
			'#__vikappointments_subscr_order',
			'#__vikappointments_package_order',
			'#__vikappointments_package_order_item',
			'#__vikappointments_users',
			'#__vikappointments_user_notes',
			'#__vikappointments_order_status',
			'#__vikappointments_invoice',
			'#__vikappointments_api_login_event_options',
			'#__vikappointments_api_login_logs',
			'#__vikappointments_api_ban',
			// third-party
			'#__vikappointments_zoom_meeting',
			'#__vikappointments_zoom_meeting_assoc',
		];

		// remove the specified tables from the list
		$tables = array_values(array_diff($tables, $exclude));

		return $tables;
	}

	/**
	 * Returns an array of files to export.
	 * 
	 * @return 	array
	 */
	protected function getFolders()
	{
		// get folders from parent
		$folders = parent::getFolders();

		// unset some folders
		unset($folders['invoices']);
		unset($folders['uploads']);
		unset($folders['avatar']);
		unset($folders['documents']);

		return $folders;
	}
}

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

VAPLoader::import('libraries.backup.export.director');

/**
 * Backup export type interface.
 * 
 * @since 1.7.1
 */
interface VAPBackupExportType
{
	/**
	 * Returns a readable name of the export type.
	 * 
	 * @return 	string
	 */
	public function getName();

	/**
	 * Returns a readable description of the export type.
	 * 
	 * @return 	string
	 */
	public function getDescription();

	/**
	 * Configures the backup director.
	 * 
	 * @param 	VAPBackupExportDirector  $director
	 * 
	 * @return 	void
	 */
	public function build(VAPBackupExportDirector $director);
}

<?php
/**
 * The Uninstaller for this plugin.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Plugin;

use Etracker\Database\LoggingTable;
use Etracker\Database\ReportingDataTable;

/**
 * Uninstaller class to manage all actions required to cleanup WordPress.
 */
class Uninstaller {
	/**
	 * Main function for uninstaller.
	 *
	 * Method will be called from `uninstall.php` during uninstall process.
	 *
	 * @return void
	 */
	public static function uninstall() {
		$reporting_data_table = new ReportingDataTable();
		$reporting_data_table->uninstall();

		$logging_table = new LoggingTable();
		$logging_table->uninstall();

		CapabilityManager::uninstall();
	}
}

<?php

namespace TotalContestVendors\TotalCore\Migrations;

/**
 * Manager
 * @package TotalCore
 * @since   1.0.0
 */
class Manager {
	/**
	 * Migrate Database.
	 */
	public function migrateDatabase() {
		$databaseMigrate = new Database();
		$databaseMigrate->upgrade();
	}
}
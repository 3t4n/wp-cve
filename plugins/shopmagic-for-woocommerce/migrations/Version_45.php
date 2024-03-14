<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * Force update database to mitigate malfunctions introduced with Version 43 and 44.
 * Previously Marketing Lists table was using Unique key for (email, list_id) combination.
 * This caused issues as key was too large for most of the databases.
 * From this update Version_43 class installs table without Unique key.
 *
 * If the table is already created, just bump version number.
 */
final class Version_45 extends AbstractMigration {

	public function up(): bool {
		if ( ! $this->needs_force_update() ) {
			return true;
		}

		$version_43 = new Version_43($this->wpdb, $this->logger);
		$no_errors  = $version_43->up();

		if ( $no_errors ) {
			delete_option( Version_44::UPDATE_REQUIRED );
			$version_44 = new Version_44($this->wpdb, $this->logger);
			$no_errors  = $version_44->up();
		}

		return $no_errors;
	}

	private function needs_force_update(): bool {
		$table_name = DatabaseTable::subscribers();
		return $this->wpdb->query( "SELECT * FROM {$table_name} LIMIT 1" ) === false;
	}
}

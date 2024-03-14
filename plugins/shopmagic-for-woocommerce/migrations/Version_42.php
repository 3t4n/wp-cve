<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_42 extends AbstractMigration {

	public function up(): bool {
		$table_name = DatabaseTable::outcome_logs();

		// @todo: handle only if necessary
		$sql    = "ALTER TABLE {$table_name} MODIFY `note` TEXT NOT NULL";
		$result = $this->wpdb->query( $sql );
		$sql    = "ALTER TABLE {$table_name} ADD `note_context` TEXT";

		return $result && $this->wpdb->query( $sql );
	}
}

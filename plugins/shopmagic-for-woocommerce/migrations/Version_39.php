<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_39 extends AbstractMigration {

	public function up(): bool {
		$charset_collate = $this->wpdb->get_charset_collate();
		$table_name      = DatabaseTable::outcome_logs();

		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
				id int NOT NULL AUTO_INCREMENT,
				execution_id varchar(48) NOT NULL,
				note varchar(2048) NOT NULL,
				created datetime NOT NULL,
				PRIMARY KEY  (id),
				KEY execution_id (execution_id)
			) {$charset_collate};";

		return $this->wpdb->query( $sql );
	}
}

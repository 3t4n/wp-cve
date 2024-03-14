<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_41 extends AbstractMigration {

	public function up(): bool {
		$charset_collate = $this->wpdb->get_charset_collate();
		$table_name      = DatabaseTable::guest_meta();

		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
				meta_id int NOT NULL AUTO_INCREMENT,
				guest_id int NOT NULL,
				meta_key varchar(255) NOT NULL,
				meta_value longtext NOT NULL,
				PRIMARY KEY  (meta_id),
				KEY guest_id (guest_id)
			) {$charset_collate};";

		return $this->wpdb->query( $sql );
	}
}

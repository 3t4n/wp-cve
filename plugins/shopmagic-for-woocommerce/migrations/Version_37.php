<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_37 extends AbstractMigration {

	public function up(): bool {
		$table_name      = DatabaseTable::optin_email();
		$charset_collate = $this->wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			id int NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			communication_type int NOT NULL,
			created datetime NOT NULL,
			subscribe tinyint(1) NOT NULL,
			active tinyint(1) NOT NULL DEFAULT TRUE,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		return (bool) $this->wpdb->query( $sql );
	}
}

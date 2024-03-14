<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_43 extends AbstractMigration {

	public function up(): bool {
		$charset_collate = $this->wpdb->get_charset_collate();
		$table_name      = DatabaseTable::subscribers();

		$sql = "
		CREATE TABLE IF NOT EXISTS {$table_name} (
		    id      int unsigned NOT NULL AUTO_INCREMENT,
		    list_id int unsigned NOT NULL,
		    email   varchar(255) NOT NULL,
		    active  tinyint(1)   NOT NULL DEFAULT 1,
		    type    tinyint(1)   NOT NULL,
		    created datetime     NOT NULL,
		    updated datetime     NOT NULL,
		    PRIMARY KEY (id)
		) {$charset_collate};";

		return (bool) $this->wpdb->query( $sql );
	}
}

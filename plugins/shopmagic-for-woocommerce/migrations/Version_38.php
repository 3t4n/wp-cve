<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_38 extends AbstractMigration {

	public function up(): bool {
		$charset_collate    = $this->wpdb->get_charset_collate();
		$table_outcome_name = DatabaseTable::automation_outcome();

		$sql = "CREATE TABLE IF NOT EXISTS {$table_outcome_name} (
			id int NOT NULL AUTO_INCREMENT,
			execution_id varchar(48) NOT NULL,
			automation_id int NOT NULL,
			automation_name varchar(255) NOT NULL,
			action_index varchar(255) NOT NULL,
			action_name varchar(255) NOT NULL,
			customer_id int,
			guest_id int,
			customer_email varchar(255) NOT NULL,
			success tinyint(1),
			finished tinyint(1) NOT NULL DEFAULT FALSE,
			created datetime NOT NULL,
			updated datetime NOT NULL,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		return $this->wpdb->query( $sql );
	}

}

<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_40 extends AbstractMigration {

	public function up(): bool {
		$charset_collate = $this->get_collation_statement_from_existing_table();
		if ( $charset_collate === null ) {
			$charset_collate = $this->wpdb->get_charset_collate();
		}
		$table_guest_name = DatabaseTable::guest();

		$sql = "CREATE TABLE IF NOT EXISTS {$table_guest_name} (
			id int NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL,
			tracking_key varchar(32) NOT NULL,
			created datetime NOT NULL,
			updated datetime NOT NULL,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		return $this->wpdb->query( $sql );
	}

	private function get_collation_statement_from_existing_table(): ?string {
		$charset = $this->wpdb->get_col_charset( $this->wpdb->users, 'user_email' );
		if ( $charset instanceof \WP_Error || $charset === false ) {
			return null;
		}

		$col_info = $this->wpdb->get_row( "SHOW FULL COLUMNS FROM {$this->wpdb->users} WHERE Field = 'user_email'",
			ARRAY_A );

		if ( is_array( $col_info ) && isset( $col_info['Collation'] ) ) {
			$collation = $col_info['Collation'];
		} else {
			return null;
		}

		return "DEFAULT CHARACTER SET {$charset} COLLATE {$collation}";
	}
}

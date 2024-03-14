<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * Improve collation consistency between `wp_users` and `wp_shopmagic_guest`.
 * This mostly affects old installations, where the newest collation was installed for ShopMagic,
 * but old, not compatible version was already on `wp_users` table.
 */
class Version_48 extends AbstractMigration {
	public function up(): bool {
		$table = DatabaseTable::guest();
		$collation = $this->get_collation_from_existing_table();

		$our_columns = $this->wpdb->get_row(
			"SHOW FULL COLUMNS FROM {$table} WHERE Field = 'email'",
			ARRAY_A
		);
		if ( is_array( $our_columns ) ) {
			[ 'Collation' => $our_collation ] = $our_columns;
		} else {
			$our_collation = null;
		}

		if ( is_null( $collation ) || $our_collation === $collation ) {
			$this->logger->info('Guest table collation is consistent with `wp_users`. Skipping update.');
			return true;
		}

		return (bool) $this->wpdb->query(
			"
			ALTER TABLE $table
			MODIFY `email` varchar(255) COLLATE $collation;
			"
		);
	}

	private function get_collation_from_existing_table(): ?string {
		$charset = $this->wpdb->get_col_charset( $this->wpdb->users, 'user_email' );
		if ( $charset instanceof \WP_Error || $charset === false ) {
			return null;
		}

		$col_info = $this->wpdb->get_row( "SHOW FULL COLUMNS FROM {$this->wpdb->users} WHERE Field = 'user_email'",
			ARRAY_A );

		if ( is_array( $col_info ) && isset( $col_info['Collation'] ) ) {
			return $col_info['Collation'];
		}

		return null;
	}
}

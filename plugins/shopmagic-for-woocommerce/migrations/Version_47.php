<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\migrations;

use WPDesk\ShopMagic\Database\DatabaseTable;

class Version_47 extends \ShopMagicVendor\WPDesk\Migrations\AbstractMigration {

	public function up(): bool {
		$table_name      = DatabaseTable::tracked_emails_clicks();
		$charset_collate = $this->wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
           `id` bigint unsigned AUTO_INCREMENT NOT NULL PRIMARY KEY,
           `message_id` char(36) NOT NULL,
           `original_uri` varchar(255) NOT NULL,
           `clicked_at` datetime NOT NULL,
          INDEX (`message_id`)
        ) {$charset_collate}";

		return $this->wpdb->query( $sql );
	}
}

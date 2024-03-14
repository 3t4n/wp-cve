<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\migrations;

use WPDesk\ShopMagic\Database\DatabaseTable;

class Version_46 extends \ShopMagicVendor\WPDesk\Migrations\AbstractMigration {

	public function up(): bool {
		$table_name      = DatabaseTable::tracked_emails();
		$charset_collate = $this->wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
    `id` bigint unsigned AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `message_id` char(36) NOT NULL,
    `automation_id` bigint unsigned NOT NULL,
    `customer_id` bigint unsigned NULL,
    `recipient_email` varchar(255) NOT NULL,
    `dispatched_at` datetime NOT NULL,
    `opened_at` datetime NULL,
    `clicked_at` datetime NULL,
    UNIQUE (`message_id`),
    INDEX (`automation_id`),
    INDEX (`customer_id`)
    ) {$charset_collate}";

		return $this->wpdb->query( $sql );
	}
}

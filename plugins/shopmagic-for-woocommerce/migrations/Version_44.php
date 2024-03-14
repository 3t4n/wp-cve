<?php

namespace WPDesk\ShopMagic\migrations;

use ShopMagicVendor\WPDesk\Migrations\AbstractMigration;
use WPDesk\ShopMagic\Database\DatabaseTable;

final class Version_44 extends AbstractMigration {

	const UPDATE_REQUIRED = 'shopmagic_subscribers_update_required';

	public function up(): bool {
		$table_name     = DatabaseTable::subscribers();
		$old_table_name = DatabaseTable::optin_email();
		$time           = microtime( true );
		if ( ! get_option( self::UPDATE_REQUIRED ) ) {
			update_option( self::UPDATE_REQUIRED, $time, true );
			if ( get_option( self::UPDATE_REQUIRED ) === $time ) {
				$sql = "INSERT INTO {$table_name} (list_id, email, active, type, created, updated)
				(SELECT p1.communication_type as list_id,
					p1.email as email,
					p1.subscribe as active,
					type,
					initial as created,
					max(p1.created) as updated
				FROM
					`{$old_table_name}` as p1
				JOIN (
					SELECT communication_type, email, min(created) as initial
					FROM `{$old_table_name}`
					GROUP BY email, communication_type
				) as p2 ON p1.email = p2.email AND p1.communication_type = p2.communication_type
				JOIN (
					SELECT post_id, (case when meta_value = 'opt_out' then 0 else 1 end) as type FROM `{$this->wpdb->postmeta}`
					WHERE meta_key = 'type'
				) as m1 ON m1.post_id = p1.communication_type
				WHERE p1.active = 1
				GROUP BY p1.email, p1.communication_type)
				ON DUPLICATE KEY UPDATE active=VALUES(active);";

				return (bool) $this->wpdb->query( $sql );
			}
		}

		return true;
	}
}

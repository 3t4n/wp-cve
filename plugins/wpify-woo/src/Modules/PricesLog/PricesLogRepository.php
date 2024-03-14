<?php

namespace WpifyWoo\Modules\PricesLog;

use WpifyWooDeps\Wpify\Model\Abstracts\AbstractDbTableRepository;

/**
 * @method PricesLogModel create()
 */
class PricesLogRepository extends AbstractDbTableRepository {
	const VERSION_OPTION_KEY = 'prices_log_db_version';


	/**
	 * Return table name.
	 *
	 * @return string
	 */
	public static function table(): string {
		global $wpdb;

		return $wpdb->prefix . 'prices_log';
	}

	/**
	 * Create table.
	 *
	 * @return void
	 */
	public function create_table() {
		$charset_collate = $this->db->get_charset_collate();
		$table           = $this->table();

		$sql = "CREATE TABLE `$table` (
			`id` int NOT NULL AUTO_INCREMENT,
			`product_id` bigint unsigned NOT NULL,
			`regular_price` varchar(255) DEFAULT NULL,
			`sale_price` varchar(255) DEFAULT NULL,
			`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
			`updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
			`deleted_at` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
		  ) $charset_collate;";

		$system_version  = get_option( self::VERSION_OPTION_KEY );
		$current_version = md5( $sql );

		if ( $system_version !== $current_version ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			update_option( self::VERSION_OPTION_KEY, $current_version );
		}
	}

	public function model(): string {
		return PricesLogModel::class;
	}

	public function table_exist(): string {
		return $this->db->get_var( $this->db->prepare( "SHOW TABLES LIKE %s", $this->table() ) ) === $this->table();
	}

	public function find_by_product_id( $product_id ): array {
		return $this->get_by( 'product_id', $product_id );
	}

	public function get_last_by_product_id( $product_id ): ?PricesLogModel {
		$table = $this::table();
		$last  = $this->db->get_var( $this->db->prepare( "SELECT id FROM $table WHERE product_id = %s ORDER BY created_at DESC", $product_id ) );

		return $last ? $this->get( $last ) : null;
	}

	public function find_lowest_price( $product_id ) {
		$prices = [];
		foreach ( $this->find_by_product_id( $product_id ) as $item ) {
			if ( strtotime( $item->created_at ) < strtotime( '-30 days' ) ) {
				continue;
			}
			$prices[] = $item->sale_price ?: $item->regular_price;
		}

		if ( empty( $prices ) ) {
			return null;
		}

		return min( $prices );
	}
}

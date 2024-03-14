<?php
/**
 * Table Settings class
 *
 * @package    Rock_Convert\Inc\Core
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\core;

/**
 * This class defines how the custom table of the plugin will be created.
 */
class Table_Structure {//phpcs:ignore

	/**
	 * Current database structure version
	 *
	 * @var string
	 */
	public $db_version = '1.2';

	/**
	 * Store current version of plugin.
	 *
	 * @var string Current version of table.
	 */
	public $current_version;

	/**
	 * Table name on DB
	 *
	 * @var string
	 */
	public $table_name;

	/**
	 * This variable store db object.
	 *
	 * @var mixed Db object.
	 */
	public $db;

	/**
	 * Table_Structure constructor.
	 */
	public function __construct() {
		global $wpdb;

		$this->current_version = get_option( 'rock_convert_db_version' );
		$this->db              = $wpdb;
		$this->table_name      = $wpdb->prefix . 'rconvert-subscriptions';
	}

	/**
	 * Check if table is already created
	 *
	 * @return bool
	 */
	public function is_installed() {
		return $this->current_version;
	}

	/**
	 * Check if table version is outdated.
	 *
	 * @return bool Return if is an outdated version.
	 */
	public function is_outdated() {
		return $this->current_version !== $this->db_version;
	}

	/**
	 * Install the plugin's table in WordPress structure.
	 *
	 * @return void
	 */
	public function install() {
		$charset_collate = $this->db->get_charset_collate();

		$sql
			= "CREATE TABLE IF NOT EXISTS `$this->table_name` (
                    id bigint(9) NOT NULL AUTO_INCREMENT,
					user_name varchar(100) DEFAULT '' NOT NULL,
                    email varchar(100) DEFAULT '' NOT NULL,
					custom_field varchar(100) DEFAULT '' NOT NULL,
                    post_id bigint(20) unsigned NOT NULL DEFAULT '0',
                    url varchar(255) NULL DEFAULT NULL,
                    created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		            PRIMARY KEY  (id)
		        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'rock_convert_db_version', $this->db_version );
	}

	/**
	 * Update DB Schema based on current version difference
	 *
	 * @return void
	 */
	public function migrate() {
		global $wpdb;

		switch ( $this->current_version ) {
			case '1.0':
				$sql = $this->sql_update_1_0( $wpdb );
				break;
			case '1.1':
				$sql = $this->sql_update_1_2( $wpdb );
				break;
			default:
				$sql = $this->sql_update_1_2( $wpdb );
				break;
		}

		if ( isset( $sql ) ) {
			$wpdb->query( $sql );// phpcs:ignore
			update_option( 'rock_convert_db_version', $this->db_version );
		}

		// TODO: Something is definitely wrong.
	}

	/**
	 * SQL Update statement for version 1.0
	 *
	 * @param mixed $wpdb DB object.
	 *
	 * @return string
	 */
	public function sql_update_1_0( $wpdb ) {
		return $wpdb->prepare(
			'ALTER TABLE `%s` ADD `url` VARCHAR(255) NULL DEFAULT NULL AFTER `post_id`;',
			$this->table_name
		);
	}

	/**
	 * SQL Update statement for version 1.2
	 *
	 * @param mixed $wpdb DB object.
	 *
	 * @return string
	 */
	public function sql_update_1_2( $wpdb ) {
		return $wpdb->prepare(
			'ALTER TABLE `%s`
            ADD `user_name` VARCHAR(100) NULL DEFAULT NULL AFTER `id`,
            ADD `custom_field` VARCHAR(100) NULL DEFAULT NULL AFTER `email`;',
			$this->table_name
		);
	}

	/**
	 * Insert data in subscriptions table
	 *
	 * @param array $data Array of data.
	 */
	public function insert( $data ) {
		$this->db->insert( $this->table_name, $data );
	}
}

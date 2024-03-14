<?php

namespace WPDesk\FlexibleWishlist\Migration;

use WPDesk\FlexibleWishlist\PluginConstants;

/**
 * {@inheritdoc}
 */
class Migration100 implements Migration {

	/**
	 * @var \wpdb
	 */
	private $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_version(): string {
		return '1.0.0';
	}

	/**
	 * {@inheritdoc}
	 */
	public function up() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = sprintf(
			'CREATE TABLE `%s` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`user_token` varchar(32) NOT NULL,
				`user_id` bigint(20) DEFAULT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `user_token` (`user_token`),
				UNIQUE KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_USERS
		);
		maybe_create_table( $this->wpdb->prefix . PluginConstants::SQL_TABLE_USERS, $sql );

		$sql = sprintf(
			'CREATE TABLE `%s` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`user_id` bigint(20) NOT NULL,
				`list_token` varchar(32) NOT NULL,
				`name` varchar(255) NOT NULL,
				`is_default` tinyint(1) NOT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				KEY `user_id` (`user_id`),
				KEY `list_token` (`list_token`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_LISTS
		);
		maybe_create_table( $this->wpdb->prefix . PluginConstants::SQL_TABLE_LISTS, $sql );

		$sql = sprintf(
			'CREATE TABLE `%s` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`list_id` bigint(20) NOT NULL,
				`product_id` bigint(20) DEFAULT NULL,
				`product_desc` varchar(255) DEFAULT NULL,
				`quantity` int(11) NOT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				KEY `list_id` (`list_id`),
				KEY `product_id` (`product_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_ITEMS
		);
		maybe_create_table( $this->wpdb->prefix . PluginConstants::SQL_TABLE_ITEMS, $sql );
	}

	/**
	 * {@inheritdoc}
	 */
	public function down() {
		$sql = sprintf(
			'DROP TABLE `%s`;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_USERS
		);
		$this->wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$sql = sprintf(
			'DROP TABLE `%s`;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_LISTS
		);
		$this->wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$sql = sprintf(
			'DROP TABLE `%s`;',
			$this->wpdb->prefix . PluginConstants::SQL_TABLE_ITEMS
		);
		$this->wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}
}

<?php
/**
 * DB component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DB {

	/**
	 * @var string
	 */
	protected $tb_zones;
	/**
	 * @var string
	 */
	protected $tb_containers;
	/**
	 * @var string
	 */
	protected $tb_columns;
	/**
	 * @var string
	 */
	protected $tb_elements;
	/**
	 * @var string
	 */
	protected $tb_container_viewport;
	/**
	 * @var string
	 */
	protected $tb_container_items;
	/**
	 * @var string
	 */
	protected $tb_templates;
	/**
	 * @var string
	 */
	protected $tb_components;

	/**
	 * @var \wpdb
	 */
	protected $db;

	/**
	 * @var DB
	 */
	public static $instance = null;

	/**
	 * @return null|DB
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * DB constructor.
	 */
	public function __construct() {
		global $wpdb;

		$this->db = $wpdb;

		$this->tb_zones              = $this->db->prefix . 'stax_zones';
		$this->tb_containers         = $this->db->prefix . 'stax_containers';
		$this->tb_columns            = $this->db->prefix . 'stax_columns';
		$this->tb_elements           = $this->db->prefix . 'stax_elements';
		$this->tb_container_viewport = $this->db->prefix . 'stax_container_viewport';
		$this->tb_container_items    = $this->db->prefix . 'stax_container_items';
		$this->tb_templates          = $this->db->prefix . 'stax_templates';
		$this->tb_components         = $this->db->prefix . 'stax_components';
	}

	/**
	 *
	 */
	public function migrate() {

		if ( ! get_option( 'stax-render-status' ) ) {
			update_option( 'stax-render-status', true );
		}

		$charset_collate = $this->db->get_charset_collate();

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_zones . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `uuid` varchar(36) NOT NULL,
                `slug` varchar(10) DEFAULT NULL,
                `type` tinyint(1) NOT NULL,
                `selector` BLOB NOT NULL,
                `pack` BLOB NOT NULL,
                `condition` BLOB DEFAULT NULL,
                `fonts` BLOB DEFAULT NULL,
                `enabled` tinyint(1) NOT NULL,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`uuid`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_containers . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `uuid` varchar(36) NOT NULL,
                `properties` MEDIUMBLOB,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`uuid`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_columns . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `uuid` varchar(36) NOT NULL,
                `properties` MEDIUMBLOB,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`uuid`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_elements . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `uuid` varchar(36) NOT NULL,
                `properties` MEDIUMBLOB,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`uuid`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_container_viewport . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `container_uuid` varchar(36) NOT NULL,
                `viewport` varchar(10) NOT NULL,
                `belongs_to` varchar(36) DEFAULT NULL,
                `visibility` tinyint(1) DEFAULT 1,
                `position` tinyint(1) DEFAULT 1,
                PRIMARY KEY (`id`),
                UNIQUE (`container_uuid`, `viewport`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_container_items . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `container_uuid` varchar(36) NOT NULL,
                `column_uuid` varchar(36) NOT NULL,
                `elements` MEDIUMBLOB,
                `viewport` varchar(10) NOT NULL,
                `visibility` tinyint(1) DEFAULT 1,
                `position` tinyint(1) DEFAULT 1,
                PRIMARY KEY (`id`),
                UNIQUE (`container_uuid`, `column_uuid`, `viewport`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_templates . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(50) NOT NULL,
                `pack` MEDIUMBLOB,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`)
              ) $charset_collate;";

		$sql[] = "CREATE TABLE IF NOT EXISTS `" . $this->tb_components . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(50) NOT NULL,
                `properties` MEDIUMBLOB,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`)
              ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		foreach ( $sql as $query ) {
			dbDelta( $query );
		}

		update_option( 'stax-version', STAX_VERSION );
	}

	/**
	 * @return \wpdb
	 */
	public function wpdb() {
		return $this->db;
	}

}

<?php

namespace WP_VGWORT;

/**
 * Setup
 *
 * activate, deactivate, uninstall functions (setup database, add or purge settings, ...)
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Setup {

	/**
	 * @var object holds plugin reference
	 */
	private object $plugin;

	/**
	 * constructor
	 *
	 * @param object $plugin
	 */
	public function __construct( object &$plugin ) {
		// set plugin reference
		$this->plugin = $plugin;
		// register activation handling
		register_activation_hook( $this->plugin->locations['plugin'], [ $this, 'activate' ] );
		// register deactivation handling
		register_deactivation_hook( $this->plugin->locations['plugin'], [ $this, 'deactivate' ] );
		// register uninstall handling
		register_uninstall_hook( $this->plugin->locations['plugin'], [ self::class, 'uninstall' ] );
	}

	/**
	 * activate plugin - create table and initialize posts
	 *
	 * @return void
	 */
	public function activate(): void {
		if ( ! $this->create_table() ) {
			die( esc_html__( 'Bei der Erstellung der Datenbank-Tabelle ist ein Fehler aufgetreten', 'vgw-metis' ) );
		}
		Services::initialize_all_posts();
		Services::initialize_participants_from_wp_users();
	}

	/**
	 * Create a table to store the ordered pixels
	 *
	 * @return bool success / error
	 */
	public function create_table(): bool {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$plugin_version  = $this->plugin->get_version();
		$db_version      = get_site_option( 'metis_db_version', '0.0.0' );
		$success         = true;

		if ( version_compare( $db_version, $plugin_version, '<' ) ) {

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			// Add metis tables
			$create_sql = "CREATE TABLE `{$wpdb->base_prefix}metis_pixels` (
			public_identification_id varchar(100),
			private_identification_id varchar(100),
			count_started tinyint(1),
			domain varchar(130) NOT NULL,
			ordered_at datetime DEFAULT CURRENT_TIMESTAMP,
			min_hits varchar(150) DEFAULT '',
			source varchar(20) NOT NULL,
			disabled tinyint(1) NOT NULL DEFAULT false ,
			ownership tinyint(1) NOT NULL DEFAULT true,
			message_created_at datetime DEFAULT null,
			PRIMARY KEY  (public_identification_id)
			) $charset_collate;
			
			CREATE TABLE `{$wpdb->base_prefix}metis_pixel_posts` (
			    public_identification_id varchar(100),
			    post_id bigint(20) UNSIGNED,
			    active tinyint(1) NOT NULL,
			    PRIMARY KEY  (public_identification_id, post_id),
			    UNIQUE KEY  post_id (post_id) 
			) $charset_collate;
		
			CREATE TABLE `{$wpdb->base_prefix}metis_participants` (
			id int NOT NULL AUTO_INCREMENT,
			first_name varchar(40) NOT NULL,
			last_name varchar(255) NOT NULL,
			file_number varchar(7) NOT NULL,
			involvement varchar(15) NOT NULL,
			wp_user varchar(60) DEFAULT '',
			PRIMARY KEY  (id)
			) $charset_collate;
			
			CREATE TABLE `{$wpdb->base_prefix}metis_text_limit_changes` (
			    post_id int NOT NULL,
			    public_identification_id varchar(100),
			    changed_at datetime DEFAULT null,
			    text_length int NOT NULL
			) $charset_collate;";

			dbDelta( $create_sql );
			$success = empty( $wpdb->last_error );
			update_site_option( 'metis_db_version', '1.1.0' );
		}

		return $success;
	}

	/**
	 * deactivate action
	 *
	 * nothing to do for us now
	 *
	 * @return void
	 */
	public function deactivate(): void {

	}


	/**
	 * uninstall the vgw-metis plugin
	 *
	 * Drops table metis_pixels and metis_pixel_posts and deletes options and WordPress post meta db entries.
	 */
	public static function uninstall(): \WP_Error|null {
		global $wpdb;

		// delete tables
		$table_name = $wpdb->base_prefix . "metis_pixels";
		$sql        = "DROP TABLE IF EXISTS " . $table_name;
		$wpdb->query( $sql );
		$success_pixels = empty( $wpdb->last_error );

		$table_name = $wpdb->base_prefix . "metis_pixel_posts";
		$sql        = "DROP TABLE IF EXISTS " . $table_name;
		$wpdb->query( $sql );
		$success_pixel_posts = empty( $wpdb->last_error );

		$table_name = $wpdb->base_prefix . "metis_participants";
		$sql        = "DROP TABLE IF EXISTS " . $table_name;
		$wpdb->query( $sql );
		$success_participants = empty( $wpdb->last_error );

		$table_name = $wpdb->base_prefix . "metis_text_limit_changes";
		$sql        = "DROP TABLE IF EXISTS " . $table_name;
		$wpdb->query( $sql );
		$success_text_limit_changes = empty( $wpdb->last_error );


		if ( $success_pixels && $success_pixel_posts && $success_participants && $success_text_limit_changes) {
			// delete options
			delete_option( "metis_db_version" );
			delete_option( "wp_metis_api_key" );
			delete_option( "wp_metis_pixel_auto_add_pages" );
			delete_option( "wp_metis_pixel_auto_add_posts" );

			// delete post meta
			delete_post_meta_by_key( "_metis_text_type" );
			delete_post_meta_by_key( "_metis_text_length" );
		} else {
			return new \WP_Error( 'vgw-metis_uninstall_drop_tables_error', esc_html__( 'Fehler bei der Deinstallation: Tabellen konnten nicht entfernt werden!', 'vgw-metis' ) );
		}

		return null;
	}
}
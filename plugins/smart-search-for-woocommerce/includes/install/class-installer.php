<?php
/**
 * Searchanise Installer
 *
 * @package Searchanise/Installer
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise installer class
 */
class Installer {

	/**
	 * Install Searchanise data
	 */
	public static function install() {
		// Recreate search results page.
		self::delete_search_results_page();
		self::create_search_results_page();

		if ( ! self::is_searchanise_installed() ) {
			$tables_result = self::create_tables();
			$settings_result = self::set_default_settings();

			return ! empty( $tables_result ) && ! empty( $settings_result );
		}

		return true;
	}

	/**
	 * Uninstall Searchanise data
	 */
	public static function uninstall() {
		self::delete_search_results_page();
	}

	/**
	 * Returns true if Searchanise was installed.
	 *
	 * @return boolean true if Searchanise is installed.
	 */
	public static function is_searchanise_installed() {
		global $wpdb;

		$wc_se_settings_row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s',
				array( DB_NAME, "{$wpdb->prefix}wc_se_settings" )
			),
			ARRAY_A
		);

		return ! empty( $wc_se_settings_row ) && Api::get_instance()->get_system_setting( 'version' ) != '';
	}

	/**
	 * Returns true if Searchanse was installed and successfully registered
	 *
	 * @return boolean
	 */
	public static function is_searchanise_registered() {
		global $wpdb;

		$registered = false;

		if ( self::is_searchanise_installed() ) {
			$registered = (bool) $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$wpdb->prefix}wc_se_settings WHERE name = %s", 'parent_private_key' ) );
		}

		return $registered;
	}

	/**
	 * Create Searchanise search result page
	 *
	 * @param array   $update_params Additional page params.
	 * @param boolean $force_update  If true, page content will be updated.
	 *
	 * @return int $page_id
	 */
	public static function create_search_results_page( array $update_params = array(), $force_update = false ) {
		global $wpdb;
		static $post_id = null;

		$can_edit_post = current_user_can( 'edit_posts' );

		if ( null == $post_id ) {
			$page_name = Api::get_instance()->get_system_setting( 'search_result_page' );
			$post_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT
					ID
				FROM {$wpdb->posts}
				WHERE post_name LIKE %s OR post_name LIKE %s
				LIMIT 1",
					$page_name,
					$page_name . '__trashed'
				)
			);
		}

		if ( $can_edit_post && ( empty( $post_id ) || $force_update ) ) {
			$content = <<< JS
<!-- wp:html -->
<!-- Do NOT edit this page. Searchanise shows the search results here -->
<div class="snize" id="snize_results"></div>
<!-- /wp:html -->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph -->
JS;
			$post_data = array(
				'post_title'     => __( 'Search results', 'woocommerce-searchanise' ),
				'comment_status' => 'closed',
				'post_excerpt'   => '',
				'post_content'   => $content,
				'post_status'    => 'publish',
				'post_author'    => get_current_user_id(),
				'post_name'      => isset( $page_name ) ? $page_name : null,
				'post_type'      => 'page',
				'page_template'  => 'template-fullwidth.php',
			);

			$post_data = array_merge( $post_data, $update_params );
			if ( $force_update && ! empty( $post_id ) ) {
				$post_data['ID'] = $post_id;
			}

			if ( ! defined( 'WP_POST_REVISIONS' ) ) {
				define( 'WP_POST_REVISIONS', true );
			}

			$post_id = wp_insert_post( $post_data );

			if ( is_wp_error( $post_id ) ) {
				$post_id = 0;
			}
		}

		return $post_id;
	}

	/**
	 * Delete Searchanise Result Page
	 */
	public static function delete_search_results_page() {
		global $wpdb;

		$page_name = Api::get_instance()->get_system_setting( 'search_result_page' );
		if ( is_multisite() && is_plugin_active_for_network( SE_ABSPATH ) ) {
			$database_tables = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT table_name AS name FROM information_schema.TABLES WHERE table_schema = %s ORDER BY name ASC;',
					DB_NAME
				)
			);
			foreach ( $database_tables as $table ) {
				if ( strpos( $table->name, '_posts' ) ) {
					$id = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT ID FROM %1s WHERE post_name = %s',
							$table->name,
							$page_name
						)
					);

					if ( ! empty( $id ) ) {
						$result = $wpdb->delete( $table->name, array( 'ID' => $id ) );
					}
				}
			}
		} else {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s", $page_name ) );

			if ( ! empty( $id ) ) {
				wp_delete_post( $id, true );
			}
		}

		return true;
	}

	/**
	 * Create Searchanise tables
	 */
	public static function create_tables() {
		global $wpdb;

		$collate = '';
		$result = true;

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		if ( $wpdb->query(
			$wpdb->prepare(
				"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wc_se_settings ("
				. " name varchar(32) NOT NULL default '',"
				. " lang_code char(8) NOT NULL default 'default',"
				. " value varchar(255) NOT NULL default '',"
				. ' PRIMARY KEY (name, lang_code)'
				. ') %1s;',
				$collate
			)
		) === false || $wpdb->query(
			$wpdb->prepare(
				"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}wc_se_queue ("
					. ' queue_id mediumint NOT NULL auto_increment,'
					. ' data text,'
					. " action varchar(32) NOT NULL default '',"
					. " lang_code char(8) NOT NULL default '',"
					. ' started int(11) NOT NULL DEFAULT 0,'
					. " status enum('pending', 'processing') default 'pending',"
					. ' priority int(2) NOT NULL DEFAULT 1,'
					. ' attempts int(2) NOT NULL DEFAULT 0,'
					. ' error MEDIUMTEXT NULL DEFAULT NULL,'
					. ' PRIMARY KEY (queue_id),'
					. ' KEY status (`status`),'
					. ' KEY StoreAction (`lang_code`,`action`)'
					. ') %1s;',
				$collate
			)
		) === false ) {
			$result = false;
			$wpdb->print_error();
		}

		return $result;
	}

	/**
	 * Set default settings
	 */
	public static function set_default_settings() {
		global $wp_version;

		Api::get_instance()->set_system_setting( 'version', SE_PLUGIN_VERSION );
		Api::get_instance()->set_system_setting( 'search_input_selector', htmlentities( stripslashes( Api::DEFAULT_SEARCH_FIELD_ID ), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 ) );
		Api::get_instance()->set_system_setting( 'search_result_page', Api::DEFAULT_SEARCH_RESULTS_PAGE );
		Api::get_instance()->set_system_setting( 'sync_mode', Api::SYNC_MODE_REALTIME );
		Api::get_instance()->set_system_setting( 'use_direct_image_links', 'N' );
		Api::get_instance()->set_system_setting( 'enabled_searchanise_search', 'Y' );
		Api::get_instance()->set_system_setting( 'resync_interval', 'daily' );
		Api::get_instance()->set_system_setting( 'cron_async_enabled', 'Y' );
		Api::get_instance()->set_system_setting( 'ajax_async_enabled', 'N' );
		Api::get_instance()->set_system_setting( 'object_async_enabled', 'Y' );
		Api::get_instance()->set_system_setting( 'color_attribute', Api::DEFAULT_COLOR_NAME );
		Api::get_instance()->set_system_setting( 'size_attribute', Api::DEFAULT_SIZE_NAME );
		Api::get_instance()->set_system_setting( 'import_block_posts', 'Y' );
		Api::get_instance()->set_system_setting( 'admin_footer_text_rated', 'N' );
		Api::get_instance()->set_system_setting( 'need_reindexation', 'N' );

		if ( version_compare( $wp_version, Api::MIN_WORDPRESS_VERSION_FOR_WP_JQUERY, '>=' ) ) {
			Api::get_instance()->set_system_setting( 'use_wp_jquery', 'Y' );
		} else {
			Api::get_instance()->set_system_setting( 'use_wp_jquery', 'N' );
		}

		return true;
	}
}

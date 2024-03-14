<?php
/**
 * Searchanise Installer
 *
 * @package Searchanise/Upgrade
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise upgrade
 */
class Upgrade {

	/**
	 * Checks if plugin has actual version
	 *
	 * @return boolean
	 */
	public static function is_updated() {
		$current_version = Api::get_instance()->get_system_setting( 'version' );

		if ( empty( $current_version ) ) {
			$current_version = '1.0.1';
		}

		return SE_PLUGIN_VERSION == $current_version;
	}

	/**
	 * Run plugin upgrades
	 *
	 * @return boolean
	 */
	public static function process_upgrade() {
		$old_version = Api::get_instance()->get_system_setting( 'version' );

		if ( empty( $old_version ) ) {
			$old_version = '1.0.1';
		}

		if ( SE_PLUGIN_VERSION != $old_version ) {
			// Process addon upgrade.
			list($old_major_version, $old_minor_version, $old_path_version) = explode( '.', $old_version );
			list($new_major, $new_minor, $new_path_version) = explode( '.', SE_PLUGIN_VERSION );

			for ( $current_ver = $old_path_version; $current_ver < $new_path_version; $current_ver++ ) {
				$from_version = $old_major_version . $old_minor_version . $current_ver;
				$to_version = $old_major_version . $old_minor_version . ( $current_ver + 1 );
				$upgrade_fn = 'upgrade_' . $from_version . '_to_' . $to_version;

				if ( is_callable( array( __CLASS__, $upgrade_fn ) ) ) {
					call_user_func( array( __CLASS__, $upgrade_fn ) );
				}
			}

			Installer::create_search_results_page( array(), true );
			Api::get_instance()->set_system_setting( 'version', SE_PLUGIN_VERSION );
			wp_cache_flush();
		}

		return true;
	}

	/**
	 * Upgrade plugin from 1.0.1 to 1.0.2
	 */
	public static function upgrade_101_to_102() {
		global $wpdb;

		Installer::set_default_settings();

		$search_input_selector = get_option( 'se_search_field_id' );
		$use_direct_image_links = get_option( 'se_use_resize_images' ) == 'Y' ? 'Y' : 'N';
		$exported_attributes = get_option( 'se_exported_attributes' );

		foreach ( array( 'se_search_field_id', 'se_use_resize_images', 'se_exported_attributes', 'se_queue_mod', 'se_every_minute', 'se_widget_info', 'se_last_resync', 'se_last_request' ) as $to_delete ) {
			delete_option( $to_delete );
		}

		if ( ! empty( $exported_attributes['color_attributes'] ) ) {
			Api::get_instance()->set_system_setting( 'color_attribute', implode( ',', $exported_attributes['color_attributes'] ) );
		}

		if ( ! empty( $exported_attributes['size_attributes'] ) ) {
			Api::get_instance()->set_system_setting( 'size_attribute', implode( ',', $exported_attributes['size_attributes'] ) );
		}

		if ( ! empty( $search_input_selector ) ) {
			Api::get_instance()->set_system_setting( 'search_input_selector', $search_input_selector );
		}

		Api::get_instance()->set_system_setting( 'use_direct_image_links', $use_direct_image_links );

		// Add error column.
		$table_queue_columns = $wpdb->get_col( "SHOW COLUMNS FROM {$wpdb->prefix}wc_se_queue" );

		if ( ! empty( $table_queue_columns ) && ! in_array( 'error', $table_queue_columns ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}wc_se_queue ADD error MEDIUMTEXT NULL DEFAULT NULL" );
		}

		// Adds indexes.
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}wc_se_queue ADD INDEX(`status`)" );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}wc_se_queue ADD INDEX `StoreAction` (`lang_code`, `action`)" );

		$wpdb->query( "UPDATE {$wpdb->prefix}wc_se_settings SET lang_code = '' WHERE name = 'parent_private_key'" );

		// Drop old table.
		$database_tables = $wpdb->get_col(
			$wpdb->prepare(
				'SELECT table_name AS name FROM information_schema.TABLES WHERE table_schema = %s ORDER BY name ASC;',
				DB_NAME
			)
		);
		if ( ! empty( $database_tables ) && in_array( $wpdb->prefix . 'wc_se_plugin_settings', $database_tables ) ) {
			$wpdb->query( "DROP TABLE {$wpdb->prefix}wc_se_plugin_settings" );
		}

		// Delete old page.
		if ( is_multisite() && is_plugin_active_for_network( SE_PLUGIN_BASENAME ) ) {
			$database_tables = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT table_name AS name FROM information_schema.TABLES WHERE table_schema = %s ORDER BY name ASC;',
					DB_NAME
				)
			);
			foreach ( $database_tables as $table ) {
				if ( strpos( $table->name, '_posts' ) ) {
					$id = $wpdb->get_var( $wpdb->prepare( 'SELECT ID FROM %1s WHERE post_name = %s', $table->name, 'searchanise' ) );
					if ( ! empty( $id ) ) {
						$result = $wpdb->delete( $table->name, array( 'ID' => $id ) );
					}
				}
			}
		} else {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s", 'searchanise' ) );
			if ( ! empty( $id ) ) {
				wp_delete_post( $id, true );
			}
		}

		return true;
	}

	/**
	 * Upgrade plugin from 1.0.4 to 1.0.5
	 */
	public static function upgrade_104_to_105() {
		global $wpdb;

		$default_locale = Api::get_instance()->get_default_locale();

		// Update database structure.
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}wc_se_settings CHANGE `lang_code` `lang_code` CHAR(8) NOT NULL DEFAULT 'default'" );
		$wpdb->query( "ALTER TABLE {$wpdb->prefix}wc_se_queue CHANGE `lang_code` `lang_code` CHAR(8) NOT NULL DEFAULT 'default'" );

		// Update locale settings.
		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}wc_se_settings SET lang_code = %s WHERE lang_code = %s", 'default', $default_locale ) );

		// Upgrade setting import block post.
		Api::get_instance()->set_system_setting( 'import_block_posts', 'N' );
	}

	/**
	 * Upgrade plugin from 1.0.5 to 1.0.6
	 */
	public static function upgrade_105_to_106() {
		$exluded_pages = array(
			'cart',
			'checkout',
			'my-account',
			'searchanise',
			'shop',
		);

		$exluded_categories = array(
			'uncategorized',
		);

		Api::get_instance()->set_system_setting( 'excluded_pages', implode( ',', $exluded_pages ) );
		Api::get_instance()->set_system_setting( 'excluded_categories', implode( ',', $exluded_categories ) );
	}

	/**
	 * Upgrade plugin from 1.0.6 to 1.0.7
	 */
	public static function upgrade_106_to_107() {
		Api::get_instance()->set_system_setting( 'show_analytics_on_dashboard', 'Y' );
	}
}

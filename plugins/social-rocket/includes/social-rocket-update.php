<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'check_admin_referer', 'social_rocket_pre_update_handler', 10, 2 );
add_action( 'check_ajax_referer', 'social_rocket_pre_update_handler', 10, 2 );

function social_rocket_pre_update_handler( $action, $result ) {
    remove_action( 'check_ajax_referer', 'social_rocket_pre_update_handler', 10 );
	if ( ! class_exists( 'Social_Rocket_Admin' ) ) {
		return;
	}
    if( $result ) {
		$this_plugin = plugin_basename( SOCIAL_ROCKET_FILE );
		if (
			// one click update && bulk update from plugins.php
			(
				$action === 'updates' &&
				isset( $_REQUEST['plugin'] ) &&
				$_REQUEST['plugin'] === $this_plugin
			) ||
			// one click update from update_core.php
			(
				$action === 'upgrade-plugin_' . $this_plugin
			) ||
			// bulk update from update_core.php
			(
				$action === 'upgrade-core' &&
				isset( $_REQUEST['action'] ) &&
				$_REQUEST['action'] === 'do-plugin-upgrade' &&
				in_array( $this_plugin, $_REQUEST['checked'] )
			)
		) {
			$settings = get_option( 'social_rocket_settings' );
			if ( isset( $settings['auto_backup'] ) && $settings['auto_backup'] ) {
				$wp_upload_dir = wp_upload_dir();
				$uploads_dir = trailingslashit( $wp_upload_dir['basedir'] ) . 'social-rocket-backups';
				wp_mkdir_p($uploads_dir);
				if ( ! file_exists( $uploads_dir . '/.htaccess' ) ) {
					try {
						$file_handle = fopen( $uploads_dir . '/.htaccess', 'w' );
						if ( $file_handle ) {
							fwrite( $file_handle, 'deny from all' );
							fclose( $file_handle );
						}
					} catch (Exception $e) {
						// continue silently
					}
				}
				if ( ! file_exists( $uploads_dir . '/index.html' ) ) {
					try {
						$file_handle = fopen( $uploads_dir . '/index.html', 'w' );
						if ( $file_handle ) {
							fwrite( $file_handle, '' );
							fclose( $file_handle );
						}
					} catch (Exception $e) {
						// continue silently
					}
				}
				$backup = Social_Rocket_Admin::handle_backup( true );
				$file_name = 'social-rocket-backup-' . date( 'Y-m-d-His' ) . '-' . preg_replace( '/[^a-z0-9]+/', '-', strtolower( site_url() ) ) . '.json';
				try {
					$file_handle = fopen( $uploads_dir . '/' . $file_name, 'w' );
					if ( $file_handle ) {
						fwrite( $file_handle, $backup );
						fclose( $file_handle );
					}
				} catch (Exception $e) {
					// continue silently
				}
			}
		}
    }
}


/* ==============================================================================
 * DATABASE UPDATES
 * ==============================================================================
 *
 * History:
 * 2019-07-09 -- DB 2, for Social Rocket v1.1.0
 * 2019-08-22 -- DB 3, for Social Rocket v1.2.0
 * 2019-12-07 -- DB 4, for Social Rocket v1.2.5
 * 2020-04-29 -- DB 5, for Social Rocket v1.2.8
 */
function social_rocket_db_update() {
	
	global $post, $wpdb;
	
	$settings = get_option( 'social_rocket_settings' );
	
	if ( isset( $settings['db_version'] ) && $settings['db_version'] >= SOCIAL_ROCKET_DBVERSION ) {
		// all good
		return;
	}
	
	// upgrade from v1 to 2
	if ( ! isset( $settings['db_version'] ) || $settings['db_version'] < 2 ) {
		
		if ( $settings['refresh_interval'] === 360 ) {
			$settings['refresh_interval'] = 3600;
		}
		
		$saved_settings = array();
		foreach ( $settings['tweet_settings']['saved_settings'] as $saved_setting_key => $saved_setting_values ) {
			$values = array();
			foreach ( $saved_setting_values as $key => $value ) {
				$new_key = str_replace( 'social_rocket_tweet_', '', $key );
				$values[ $new_key ] = $value;
			}
			$saved_settings[ $saved_setting_key ] = $values;
		}
		$settings['tweet_settings'] = array(
			'saved_settings' => $saved_settings,
		);
		
	}
	
	// upgrade from v2 to 3
	if ( ! isset( $settings['db_version'] ) || $settings['db_version'] < 3 ) {
		$settings['floating_buttons']['total_show_icon'] = true;
		$settings['inline_buttons']['total_show_icon'] = true;
	}
	
	
	// upgrade from v3 to 4
	if ( ! isset( $settings['db_version'] ) || $settings['db_version'] < 4 ) {
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		// create new queue table
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'social_rocket_count_queue';
		$sql = "CREATE TABLE $table_name (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			hash varchar(32) NOT NULL,
			data text NOT NULL,
			request_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY hash (hash)
		) $charset_collate;";
		dbDelta( $sql );
		
		// clear old background queue
		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';
		
		if ( is_multisite() ) {
			$table        = $wpdb->sitemeta;
			$column       = 'meta_key';
			$key_column   = 'meta_id';
			$value_column = 'meta_value';
		}
		
		$key = $wpdb->esc_like( 'wp_social_rocket_background_process_batch_' ) . '%';
		
		$wpdb->query( $wpdb->prepare( "
			DELETE
			FROM {$table}
			WHERE {$column} LIKE %s
		", $key ) );
	}
	
	// upgrade from v4 to 5
	if ( ! isset( $settings['db_version'] ) || $settings['db_version'] < 5 ) {
		$settings['auto_fix_gutenberg'] = true;
	}
	
	// Done
	$settings['db_version'] = SOCIAL_ROCKET_DBVERSION;
	update_option( 'social_rocket_settings', $settings );
	
}
add_action( 'init', 'social_rocket_db_update' );

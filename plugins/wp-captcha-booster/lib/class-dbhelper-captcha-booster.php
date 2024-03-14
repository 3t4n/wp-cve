<?php
/**
 * This file is used for creating dbHelper class.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		if ( ! class_exists( 'Dbhelper_Captcha_Booster' ) ) {
			/**
			 * This Class is used for Insert, Update & Delete Operations.
			 */
			class Dbhelper_Captcha_Booster {
				/**
				 * This Function is used to Insert data in database.
				 *
				 * @param string $table_name .
				 * @param string $data .
				 */
				public function insert_command( $table_name, $data ) {
					global $wpdb;
					$wpdb->insert( $table_name, $data );// db call ok; no-cache ok.
					return $wpdb->insert_id;
				}
				/**
				 * This function is used to update data in database.
				 *
				 * @param string $table .
				 * @param string $data .
				 * @param string $where .
				 */
				public function update_command( $table, $data, $where ) {
					global $wpdb;
					$wpdb->update( $table, $data, $where, $format = null, $where_format = null );// WPCS: db call ok; no-cache ok, @codingStandardsIgnoreLine.
				}
				/**
				 * This function is used to delete data from database.
				 *
				 * @param string $table_name .
				 * @param string $where .
				 */
				public function delete_command( $table_name, $where ) {
					global $wpdb;
					$wpdb->delete( $table_name, $where );// db call ok; no-cache ok.
				}
				/**
				 * This function is used to delete multiple data from database.
				 *
				 * @param string $table_name .
				 * @param string $where .
				 * @param string $data .
				 */
				public function bulk_delete_command( $table_name, $where, $data ) {
					global $wpdb;
					$wpdb->query( "DELETE FROM $table_name WHERE $where IN ($data)" );// WPCS: unprepared SQL ok, WPCS: db call ok; no-cache ok.
				}
			}
		}

		if ( ! class_exists( 'Plugin_Info_Captcha_Booster' ) ) {
			/**
			 * This Class is used to get the the information about plugins.
			 */
			class Plugin_Info_Captcha_Booster { // @codingStandardsIgnoreLine.
				/**
				 * This function is used to return the information about plugins.
				 */
				public function get_plugin_info_captcha_booster() {
					$active_plugins = (array) get_option( 'active_plugins', array() );
					if ( is_multisite() ) {
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
					}
					$plugins = array();
					if ( count( $active_plugins ) > 0 ) {
						$get_plugins = array();
						foreach ( $active_plugins as $plugin ) {
							$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin ); // @codingStandardsIgnoreLine.

							$get_plugins['plugin_name']    = strip_tags( $plugin_data['Name'] );
							$get_plugins['plugin_author']  = strip_tags( $plugin_data['Author'] );
							$get_plugins['plugin_version'] = strip_tags( $plugin_data['Version'] );
							array_push( $plugins, $get_plugins );
						}
						return $plugins;
					}
				}
			}
		}
	}
}

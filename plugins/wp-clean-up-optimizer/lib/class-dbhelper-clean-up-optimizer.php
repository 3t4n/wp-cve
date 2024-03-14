<?php
/**
 * This File is used for creating helper class.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {

		if ( ! class_exists( 'Dbhelper_Clean_Up_Optimizer' ) ) {
			/**
			 * Class Name: Dbhelper_Clean_Up_Optimizer
			 * Parameters: No
			 * Description: This Class is used for Insert Update and Delete operations.
			 * Created On: 23-09-2016 1:10
			 * Created By: Tech Banker Team
			 */
			class Dbhelper_Clean_Up_Optimizer {
				/**
				 * This Function is used to Insert data in database.
				 *
				 * @param string $table_name passes parameter as table name.
				 * @param string $data passes parameter as data.
				 */
				public function insert_command( $table_name, $data ) {
					global $wpdb;
					$wpdb->insert( $table_name, $data );// WPCS: db call ok.
					return $wpdb->insert_id;
				}
				/**
				 * This function is used to update data in database.
				 *
				 * @param string $table_name passes parameter as table.
				 * @param string $data passes parameter as data.
				 * @param string $where passes parameter as where.
				 */
				public function update_command( $table_name, $data, $where ) {
					global $wpdb;
					$wpdb->update( $table_name, $data, $where );// WPCS: db call ok, cache ok.
				}
				/**
				 * This function is used to delete data from database.
				 *
				 * @param string $table_name passes parameter as table name.
				 * @param string $where passes parameter as where.
				 */
				public function delete_command( $table_name, $where ) {
					global $wpdb;
					$wpdb->delete( $table_name, $where );// WPCS: db call ok, cache ok.
				}
				/**
				 * This function is used to delete multiple data from database.
				 *
				 * @param string $table_name passes parameter as table name.
				 * @param string $where passes parameter as where.
				 * @param string $data passes parameter as data.
				 */
				public function bulk_delete_command( $table_name, $where, $data ) {
					global $wpdb;
					$wpdb->query(
						"DELETE FROM $table_name WHERE $where IN ($data)"
					);// WPCS: unprepared SQL ok, db call ok; no-cache ok.
				}
			}
		}

		if ( ! class_exists( 'Plugin_Info_Clean_Up_Optimizer' ) ) {
			/**
			 * Class Name: Plugin_Info_Clean_Up_Optimizer
			 * Parameters: No
			 * Description: This Class is used to get the the information about plugins.
			 * Created On: 05-04-2017 18:06
			 * Created By: Tech Banker Team
			 */
			class Plugin_Info_Clean_Up_Optimizer {// @codingStandardsIgnoreLine
				/**
				 * Function Name: get_plugin_info_clean_up_optimizer
				 * Parameters: No
				 * Decription: This function is used to return the information about plugins.
				 * Created On: 05-04-2017 18:06
				 * Created By: Tech Banker Team
				 */
				function get_plugin_info_clean_up_optimizer() {// @codingStandardsIgnoreLine
					$active_plugins = (array) get_option( 'active_plugins', array() );
					if ( is_multisite() ) {
						$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
					}
					$plugins = array();
					if ( count( $active_plugins ) > 0 ) {
						$get_plugins = array();
						foreach ( $active_plugins as $plugin ) {
							$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );// @codingStandardsIgnoreLine

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

<?php
/**
 * This File is used for managing database.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
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
		if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
			include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
		}
		if ( ! function_exists( 'get_excluded_termids_clean_up_optimizer' ) ) {
			/**
			 * This function is get_excluded_termids_clean_up_optimizer.
			 */
			function get_excluded_termids_clean_up_optimizer() {
				$default_term_ids = get_default_taxonomy_termids_clean_up_optimizer();
				if ( ! is_array( $default_term_ids ) ) {
					$default_term_ids = array();
				}
				$parent_term_ids = get_parent_termids_clean_up_optimizer();
				if ( ! is_array( $parent_term_ids ) ) {
					$parent_term_ids = array();
				}
				return array_merge( $default_term_ids, $parent_term_ids );
			}
		}

		if ( ! function_exists( 'get_default_taxonomy_termids_clean_up_optimizer' ) ) {
			/**
			 * This function is get_default_taxonomy_termids_clean_up_optimizer.
			 */
			function get_default_taxonomy_termids_clean_up_optimizer() {
				$taxonomies       = get_taxonomies();
				$default_term_ids = array();
				if ( $taxonomies ) {
					$tax = array_keys( $taxonomies );
					if ( $tax ) {
						foreach ( $tax as $t ) {
							$term_id = intval( get_option( 'default_' . $t ) );
							if ( $term_id > 0 ) {
								$default_term_ids[] = $term_id;
							}
						}
					}
				}
				return $default_term_ids;
			}
		}

		if ( ! function_exists( 'get_parent_termids_clean_up_optimizer' ) ) {
			/**
			 * This function is get_parent_termids_clean_up_optimizer.
			 */
			function get_parent_termids_clean_up_optimizer() {
				global $wpdb;
				return $wpdb->get_col(
					$wpdb->prepare(
						"SELECT tt.parent FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt
						ON t.term_id = tt.term_id
						WHERE  tt.parent > %d", '0'
					)
				);// WPCS: db call ok, cache ok.
			}
		}

		if ( ! function_exists( 'get_clean_up_optimizer_unserialize_data' ) ) {
			/**
			 * This function is get unserialize data.
			 *
			 * @param string $manage_data passes parameter as manage data.
			 */
			function get_clean_up_optimizer_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				if ( count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $value ) {
						$unserialize_data            = maybe_unserialize( $value->meta_value );
						$unserialize_data['meta_id'] = $value->meta_id;
						array_push( $unserialize_complete_data, $unserialize_data );
					}
				}
				return $unserialize_complete_data;
			}
		}

		if ( ! function_exists( 'clean_up_optimizer_data' ) ) {
			/**
			 * This function is clean_up_optimizer_data.
			 *
			 * @param string $types passes parameter as type.
			 */
			function clean_up_optimizer_data( $types ) {
				global $wpdb;
				$obj   = new Dbhelper_Clean_Up_Optimizer();
				$where = array();
				switch ( $types ) {
					case 1:
						$where['post_status'] = 'auto-draft';
						$obj->delete_command( $wpdb->posts, $where );
						break;

					case 2:
						$wpdb->query(
							$wpdb->prepare(
								'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE %s OR option_name LIKE %s OR option_name LIKE %s OR option_name LIKE %s', '_site_transient_browser_%', '_site_transient_timeout_browser_%', '_transient_feed_%', '_transient_timeout_feed_%'
							)
						);// WPCS: db call ok, cache ok.
						break;

					case 3:
						$where['comment_approved'] = '0';
						$obj->delete_command( $wpdb->comments, $where );
						break;

					case 4:
						$wpdb->query(
							'DELETE FROM ' . $wpdb->commentmeta . " WHERE comment_id NOT IN (SELECT comment_id FROM $wpdb->comments)"
						);// WPCS: db call ok, cache ok.
						break;

					case 5:
						$wpdb->query(
							'DELETE pm FROM ' . $wpdb->postmeta . " pm LEFT JOIN $wpdb->posts wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL"
						);// WPCS: db call ok, cache ok.
						break;

					case 6:
						$wpdb->query(
							$wpdb->prepare(
								'DELETE FROM ' . $wpdb->term_relationships . ' WHERE term_taxonomy_id=%d AND object_id NOT IN (SELECT id FROM ' . $wpdb->posts . ')', 1
							)
						);// WPCS: db call ok, cache ok.
						break;

					case 7:
						$where['post_type'] = 'revision';
						$obj->delete_command( $wpdb->posts, $where );
						break;

					case 8:
						$where['comment_type'] = 'pingback';
						$obj->delete_command( $wpdb->comments, $where );
						break;

					case 9:
						$wpdb->query(
							$wpdb->prepare(
								'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE %s OR option_name LIKE %s', '_transient_%', '_site_transient_%'
							)
						);// WPCS: db call ok, cache ok.
						break;

					case 10:
						$where['comment_type'] = 'trackback';
						$obj->delete_command( $wpdb->comments, $where );
						break;

					case 11:
						$where['comment_approved'] = 'spam';
						$obj->delete_command( $wpdb->comments, $where );
						break;

					case 12:
						$where['comment_approved'] = 'trash';
						$obj->delete_command( $wpdb->comments, $where );
						break;

					case 13:
						$where['post_status'] = 'draft';
						$obj->delete_command( $wpdb->posts, $where );
						break;

					case 14:
						$where['post_status'] = 'trash';
						$obj->delete_command( $wpdb->posts, $where );
						break;

					case 15:
						if ( ! function_exists( 'get_where_sql' ) ) {
							/**
							 * This function is get_where_sql.
							 */
							function get_where_sql() {
								global $wpdb;
								return sprintf(
									"WHERE meta_id NOT IN (
										SELECT *
										FROM (
											SELECT MAX(meta_id)
											FROM $wpdb->postmeta
											GROUP BY post_id, meta_key,meta_value
										) AS x
									)"
								);
							}
						}
						$where_sql = get_where_sql();
						$query_sql = "DELETE FROM {$wpdb->postmeta} " . $where_sql;
						$wpdb->query( $query_sql );// WPCS: db call ok, cache ok, unprepared SQL ok.
						echo '1';
						break;

					case 16:
						$query = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT post_id, meta_key FROM $wpdb->postmeta WHERE meta_key LIKE (%s)", '%_oembed_%'
							)
						);// WPCS: db call ok, cache ok.
						if ( $query ) {
							foreach ( $query as $meta ) {
								$post_id = intval( $meta->post_id );
								if ( 0 === $post_id ) {
									$wpdb->query(
										$wpdb->prepare(
											"DELETE FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, $meta->meta_key
										)
									);// WPCS: db call ok, cache ok.
								} else {
									delete_post_meta( $post_id, $meta->meta_key );
								}
							}
						}
						break;

					case 17:
						$query = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT GROUP_CONCAT(meta_id ORDER BY meta_id DESC) AS ids, comment_id, COUNT(*) AS count
								FROM $wpdb->commentmeta GROUP BY comment_id, meta_key, meta_value HAVING count > %d", 1
							)
						);// WPCS: db call ok, cache ok.
						if ( $query ) {
							foreach ( $query as $meta ) {
								$ids = array_map( 'intval', explode( ',', $meta->ids ) );
								array_pop( $ids );
								$wpdb->query(
									$wpdb->prepare(
										'DELETE FROM $wpdb->commentmeta WHERE meta_id IN (' . implode( ',', $ids ) . ') AND comment_id = %d', intval( $meta->comment_id )
									)
								);// WPCS: db call ok, cache ok, unprepared SQL ok.
							}
						}
						break;

					case 18:
						$wpdb->query(
							'DELETE FROM ' . $wpdb->usermeta . ' WHERE user_id NOT IN (SELECT ID FROM ' . $wpdb->users . ')'// @codingStandardsIgnoreLine.
						);// WPCS: db call ok, cache ok, unprepared SQL ok.
						break;

					case 19:
						$query = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT GROUP_CONCAT(umeta_id ORDER BY umeta_id DESC) AS ids, user_id, COUNT(*) AS count
								FROM $wpdb->usermeta GROUP BY user_id, meta_key, meta_value HAVING count > %d", 1// @codingStandardsIgnoreLine.
							)
						);// WPCS: db call ok, cache ok.
						if ( $query ) {
							foreach ( $query as $meta ) {
								$ids = array_map( 'intval', explode( ',', $meta->ids ) );
								array_pop( $ids );
								$wpdb->query(
									$wpdb->prepare(
										'DELETE FROM $wpdb->usermeta WHERE umeta_id IN (' . implode( ',', $ids ) . ') AND user_id = %d', intval( $meta->user_id )
									)
								);// WPCS: db call ok, cache ok, unprepared SQL ok.
							}
						}
						break;

					case 20:
						$query = $wpdb->get_results(
							"SELECT tr.object_id, tt.term_id, tt.taxonomy FROM $wpdb->term_relationships AS tr
							INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
							WHERE tt.taxonomy != 'link_category' AND tr.object_id NOT IN (SELECT ID FROM $wpdb->posts)"
						);// WPCS: db call ok, cache ok.
						if ( $query ) {
							foreach ( $query as $tax ) {
								wp_remove_object_terms( intval( $tax->object_id ), intval( $tax->term_id ), $tax->taxonomy );
							}
						}
						break;

					case 21:
						$query = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT tt.term_taxonomy_id, t.term_id, tt.taxonomy FROM $wpdb->terms AS t
								INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
								WHERE tt.count = %d AND t.term_id NOT IN (' . implode( ',', get_excluded_termids_clean_up_optimizer() ) . ')", 0
							)
						);// WPCS: db call ok, cache ok, unprepared SQL ok..
						if ( $query ) {
							$check_wp_terms = false;
							foreach ( $query as $tax ) {
								if ( taxonomy_exists( $tax->taxonomy ) ) {
									wp_delete_term( intval( $tax->term_id ), $tax->taxonomy );
								} else {
									$wpdb->query(
										$wpdb->prepare(
											"DELETE FROM $wpdb->term_taxonomy WHERE term_taxonomy_id = %d", intval( $tax->term_taxonomy_id )
										)
									);// WPCS: db call ok, cache ok.
									$check_wp_terms = true;
								}
							}
						}
						break;
				}
			}
		}

		if ( isset( $_REQUEST['param'] ) ) {// WPCS: input var ok.
			$obj_dbhelper_clean_up_optimizer = new Dbhelper_Clean_Up_Optimizer();
			switch ( sanitize_text_field( wp_unslash( $_REQUEST['param'] ) ) ) {// WPCS: input var ok, sanitization ok, CSRF ok.
				case 'wizard_clean_up_optimizer':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_optimizer_check_status' ) ) {// WPCS: input var ok.
						$type             = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';// WPCS: input var ok.
						$user_admin_email = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';// WPCS: input var ok.
						if ( '' === $user_admin_email ) {
							$user_admin_email = get_option( 'admin_email' );
						}
						update_option( 'clean-up-optimizer-admin-email', $user_admin_email );
						update_option( 'clean-up-optimizer-wizard-set-up', $type );
						if ( 'opt_in' === $type ) {
							$plugin_info_clean_up_optimizer = new Plugin_Info_Clean_Up_Optimizer();
							global $wp_version;

							$theme_details = array();
							if ( $wp_version >= 3.4 ) {
								$active_theme                   = wp_get_theme();
								$theme_details['theme_name']    = strip_tags( $active_theme->name );
								$theme_details['theme_version'] = strip_tags( $active_theme->version );
								$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
							}
							$plugin_stat_data                     = array();
							$plugin_stat_data['plugin_slug']      = 'wp-clean-up-optimizer';
							$plugin_stat_data['type']             = 'standard_edition';
							$plugin_stat_data['version_number']   = CLEAN_UP_OPTIMIZER_VERSION_NUMBER;
							$plugin_stat_data['status']           = $type;
							$plugin_stat_data['event']            = 'activate';
							$plugin_stat_data['domain_url']       = site_url();
							$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
							$plugin_stat_data['email']            = $user_admin_email;
							$plugin_stat_data['wp_version']       = $wp_version;
							$plugin_stat_data['php_version']      = sanitize_text_field( phpversion() );
							$plugin_stat_data['mysql_version']    = $wpdb->db_version();
							$plugin_stat_data['max_input_vars']   = ini_get( 'max_input_vars' );
							$plugin_stat_data['operating_system'] = PHP_OS . '  (' . PHP_INT_SIZE * 8 . ') BIT';
							$plugin_stat_data['php_memory_limit'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
							$plugin_stat_data['extensions']       = get_loaded_extensions();
							$plugin_stat_data['plugins']          = $plugin_info_clean_up_optimizer->get_plugin_info_clean_up_optimizer();
							$plugin_stat_data['themes']           = $theme_details;
							$url                                  = TECH_BANKER_STATS_URL . '/wp-admin/admin-ajax.php';
							$response                             = wp_safe_remote_post(
								$url, array(
									'method'      => 'POST',
									'timeout'     => 45,
									'redirection' => 5,
									'httpversion' => '1.0',
									'blocking'    => true,
									'headers'     => array(),
									'body'        => array(
										'data'    => maybe_serialize( $plugin_stat_data ),
										'site_id' => false !== get_option( 'cpo_tech_banker_site_id' ) ? get_option( 'cpo_tech_banker_site_id' ) : '',
										'action'  => 'plugin_analysis_data',
									),
								)
							);
							if ( ! is_wp_error( $response ) ) {
								false !== $response['body'] ? update_option( 'cpo_tech_banker_site_id', $response['body'] ) : '';
							}
						}
					}
					break;

				case 'manual_clean_up_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'wordpress_data_manual_clean_up' ) ) {// WPCS: input var ok.
						$types = isset( $_REQUEST['data'] ) ? array_map( 'intval', is_array( json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) ) ? json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) : array() ) : array();// WPCS: input var ok, sanitization ok.
						for ( $flag = 0; $flag < count( $types ); $flag++ ) {// @codingStandardsIgnoreLine.
							clean_up_optimizer_data( $types[ $flag ] );
						}
					}
					break;

				case 'manual_clean_up_empty_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'empty_manual_clean_up' ) ) {// WPCS: input var ok.
						$types = isset( $_REQUEST['delete_id'] ) ? intval( $_REQUEST['delete_id'] ) : 0;// WPCS: input var ok.
						clean_up_optimizer_data( $types );
					}
					break;

				case 'bulk_action_manual_clean_up_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'manual_db_bulk_action' ) ) {// WPCS: input var ok.
						$action     = isset( $_REQUEST['table_action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_action'] ) ) : '';// WPCS: input var ok.
						$table_name = isset( $_REQUEST['data'] ) ? array_map( 'sanitize_text_field', is_array( json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) ) ? json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) : array() ) : array();// WPCS: input var ok, sanitization ok.
						if ( isset( $table_name ) && count( $table_name ) > 0 ) {
							$wpdb->query(
								'SET FOREIGN_KEY_CHECKS = 0'
							);// WPCS: db call ok, cache ok.
							foreach ( $table_name as $row ) {
								switch ( $action ) {
									case 'delete':
										$wpdb->query(
											"DROP TABLE IF EXISTS $row"// @codingStandardsIgnoreLine.
										);// WPCS: db call ok, cache ok.
										break;

									case 'optimize':
										$wpdb->query(
											"OPTIMIZE TABLE $row"// @codingStandardsIgnoreLine.
										);// WPCS: db call ok, cache ok.
										break;
								}
							}
							$wpdb->query(
								'SET FOREIGN_KEY_CHECKS = 1'
							);// WPCS: db call ok, cache ok.
						}
					}
					break;

				case 'select_action_manual_clean_up_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'manual_db_select_action' ) ) {// WPCS: input var ok.
						$action     = isset( $_REQUEST['perform_action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['perform_action'] ) ) : '';// WPCS: input var ok.
						$table_name = isset( $_REQUEST['table_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_name'] ) ) : '';// WPCS: input var ok.
						switch ( $action ) {
							case 'optimize':
								$wpdb->query(
									"OPTIMIZE TABLE $table_name"// @codingStandardsIgnoreLine.
								);// WPCS: db call ok, cache ok.
								break;
							case 'delete':
								$wpdb->query(
									'SET FOREIGN_KEY_CHECKS = 0'
								);// WPCS: db call ok, cache ok.
								$wpdb->query(
									"DROP TABLE IF EXISTS $table_name" // @codingStandardsIgnoreLine
								);// WPCS: db call ok, cache ok.
								$wpdb->query(
									'SET FOREIGN_KEY_CHECKS = 1'
								);// WPCS: db call ok, cache ok.
								break;
						}
					}
					break;

				case 'delete_selected_recent_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'recent_selected_delete' ) ) {// WPCS: input var ok.
						$login_id           = isset( $_REQUEST['login_id'] ) ? intval( $_REQUEST['login_id'] ) : 0;// WPCS: input var ok.
						$where              = array();
						$where_parent       = array();
						$where['meta_id']   = $login_id;
						$where_parent['id'] = $login_id;
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer_meta(), $where );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer(), $where_parent );
					}
					break;

				case 'delete_selected_traffic_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'traffic_delete' ) ) {// WPCS: input var ok.
						$confirm_id            = isset( $_REQUEST['confirm_id'] ) ? intval( $_REQUEST['confirm_id'] ) : 0;// WPCS: input var ok.
						$where_meta            = array();
						$where_parent          = array();
						$where_meta['meta_id'] = $confirm_id;
						$where_parent['id']    = $confirm_id;
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer_meta(), $where_meta );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer(), $where_parent );
					}
					break;

				case 'visitor_log_delete_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'visitor_log_delete' ) ) {// WPCS: input var ok.
						$confirm_id            = isset( $_REQUEST['confirm_id'] ) ? intval( $_REQUEST['confirm_id'] ) : 0;// WPCS: input var ok.
						$where_meta            = array();
						$where_parent          = array();
						$where_meta['meta_id'] = $confirm_id;
						$where_parent['id']    = $confirm_id;
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer_meta(), $where_meta );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer(), $where_parent );
					}
					break;

				case 'other_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_other_settings' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $other_settings_array );// WPCS: input var ok.
						$update_clean_up_type = array();
						$where                = array();
						if ( 'enable' === $other_settings_array['ux_ddl_trackback'] ) {
							$trackback = $wpdb->query(
								$wpdb->prepare(
									'UPDATE ' . $wpdb->posts . ' SET ping_status=%s', 'open'
								)
							);// WPCS: db call ok, cache ok.
						} else {
							$trackback = $wpdb->query(
								$wpdb->prepare(
									'UPDATE ' . $wpdb->posts . ' SET ping_status=%s', 'closed'
								)
							);// WPCS: db call ok, cache ok.
						}
						if ( 'enable' === $other_settings_array['ux_ddl_Comments'] ) {
							$comments = $wpdb->query(
								$wpdb->prepare(
									'UPDATE ' . $wpdb->posts . ' SET comment_status=%s', 'open'
								)
							);// WPCS: db call ok, cache ok.
						} else {
							$comments = $wpdb->query(
								$wpdb->prepare(
									'UPDATE ' . $wpdb->posts . ' SET comment_status=%s', 'closed'
								)
							);// WPCS: db call ok, cache ok.
						}
						$other_settings_data['automatic_plugin_updates']    = 'disable';
						$update_clean_up_type['live_traffic_monitoring']    = sanitize_text_field( $other_settings_array['ux_ddl_live_traffic_monitoring'] );
						$update_clean_up_type['visitor_logs_monitoring']    = sanitize_text_field( $other_settings_array['ux_ddl_visitor_log_monitoring'] );
						$update_clean_up_type['remove_tables_uninstall']    = sanitize_text_field( $other_settings_array['ux_ddl_remove_tables'] );
						$update_clean_up_type['ip_address_fetching_method'] = sanitize_text_field( $other_settings_array['ux_ddl_ip_address_fetching_method'] );
						$update_data                                        = array();
						$where['meta_key']                                  = 'other_settings';// WPCS: db slow query ok.
						$update_data['meta_value']                          = maybe_serialize( $update_clean_up_type );// WPCS: db slow query ok.
						$obj_dbhelper_clean_up_optimizer->update_command( clean_up_optimizer_meta(), $update_data, $where );
					}
					break;

				case 'blocking_options_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_block' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $blocking_option_array );// WPCS: input var ok.
						$update_type = array();
						$where       = array();

						$update_type['auto_ip_block']                  = sanitize_text_field( $blocking_option_array['ux_ddl_auto_ip'] );
						$update_type['maximum_login_attempt_in_a_day'] = intval( $blocking_option_array['ux_txt_login'] );
						$update_type['block_for']                      = sanitize_text_field( $blocking_option_array['ux_ddl_blocked_for'] );

						$update_block_data               = array();
						$where['meta_key']               = 'blocking_options';// WPCS: db slow query ok.
						$update_block_data['meta_value'] = maybe_serialize( $update_type );// WPCS: db slow query ok.
						$obj_dbhelper_clean_up_optimizer->update_command( clean_up_optimizer_meta(), $update_block_data, $where );
					}
					break;

				case 'manage_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_manage_ip_address' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $advance_security_data );// WPCS: input var ok.
						$ip       = isset( $_REQUEST['ip_address'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['ip_address'] ) ) ) ) : '';// WPCS: input var ok.
						$get_ip   = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $ip ) );
						$location = '' == $get_ip->country_name && '' == $get_ip->city ? '' : '' == $get_ip->country_name ? '' : '' == $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name;// WPCS: Loose comparison ok.

						$ip_address_count = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'block_ip_address'
							)
						);// WPCS: db call ok, cache ok.
						if ( isset( $ip_address_count ) && count( $ip_address_count ) > 0 ) {
							foreach ( $ip_address_count as $data ) {
								$ip_address_unserialize = maybe_unserialize( $data->meta_value );
								if ( $ip === $ip_address_unserialize['ip_address'] ) {
									echo '1';
									die();
								}
							}
						}
						$ip_address_ranges_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'block_ip_range'
							)
						);// WPCS: db call ok, cache ok.
						$ip_exists              = false;
						if ( isset( $ip_address_ranges_data ) && count( $ip_address_ranges_data ) > 0 ) {
							foreach ( $ip_address_ranges_data as $data ) {
								$ip_range_unserialized_data = maybe_unserialize( $data->meta_value );
								$data_range                 = explode( ',', $ip_range_unserialized_data['ip_range'] );
								if ( $ip >= $data_range[0] && $ip <= $data_range[1] ) {
									$ip_exists = true;
									break;
								}
							}
						}
						$cpo_ip_address  = get_ip_address_clean_up_optimizer();
						$user_ip_address = '::1' === $cpo_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpo_ip_address ) );
						if ( true === $ip_exists ) {
							echo 1;
						} elseif ( $user_ip_address === $ip ) {
							echo 2;
						} else {
							$insert_manage_ip_address              = array();
							$insert_manage_ip_address['type']      = 'block_ip_address';
							$insert_manage_ip_address['parent_id'] = '0';
							$last_id                               = $obj_dbhelper_clean_up_optimizer->insert_command( clean_up_optimizer(), $insert_manage_ip_address );

							$insert_manage_ip_address                = array();
							$insert_manage_ip_address['ip_address']  = $ip;
							$insert_manage_ip_address['blocked_for'] = sanitize_text_field( $advance_security_data['ux_ddl_ip_blocked_for'] );
							$insert_manage_ip_address['location']    = $location;
							$insert_manage_ip_address['comments']    = sanitize_text_field( $advance_security_data['ux_txtarea_ip_comments'] );
							$insert_manage_ip_address['date_time']   = CLEAN_UP_OPTIMIZER_LOCAL_TIME;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_address';// WPCS: db slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_address );// WPCS: db slow query ok.
							$obj_dbhelper_clean_up_optimizer->insert_command( clean_up_optimizer_meta(), $insert_data );


							$time_interval = sanitize_text_field( $advance_security_data['ux_ddl_ip_blocked_for'] );
							if ( 'permanently' !== $time_interval ) {
								$cron_name = 'ip_address_unblocker_' . $last_id;
								schedule_clean_up_optimizer_ip_address_and_ranges( $cron_name, $time_interval );
							}
						}
					}
					break;

				case 'delete_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_manage_ip_address_delete' ) ) {// WPCS: input var ok.
						$id                 = isset( $_REQUEST['id_address'] ) ? intval( $_REQUEST['id_address'] ) : 0;// WPCS: input var ok.
						$where              = array();
						$where_parent       = array();
						$where['meta_id']   = $id;
						$where_parent['id'] = $id;
						$cron_name          = 'ip_address_unblocker_' . $id;
						unschedule_events_clean_up_optimizer( $cron_name );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer_meta(), $where );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer(), $where_parent );
					}
					break;

				case 'delete_ip_range_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_manage_ip_ranges_delete' ) ) {// WPCS: input var ok.
						$id_range           = isset( $_REQUEST['id_range'] ) ? intval( $_REQUEST['id_range'] ) : 0;// WPCS: input var ok.
						$where              = array();
						$where_parent       = array();
						$where['meta_id']   = $id_range;
						$where_parent['id'] = $id_range;
						$cron_name          = 'ip_range_unblocker_' . $where['meta_id'];
						unschedule_events_clean_up_optimizer( $cron_name );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer_meta(), $where );
						$obj_dbhelper_clean_up_optimizer->delete_command( clean_up_optimizer(), $where_parent );
					}
					break;

				case 'manage_ip_ranges_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'clean_up_manage_ip_ranges' ) ) {// WPCS: input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $ip_range_data );// WPCS: input var ok.
						$start_ip_range = isset( $_REQUEST['start_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['start_range'] ) ) ) ) : '';// WPCS: input var ok.
						$end_ip_range   = isset( $_REQUEST['end_range'] ) ? sprintf( '%u', ip2long( wp_unslash( $_REQUEST['end_range'] ) ) ) : '';// WPCS: input var ok, sanitization ok.
						$ip_range       = $start_ip_range . ',' . $end_ip_range;
						$get_ip         = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $start_ip_range ) );
						$location       = '' == $get_ip->country_name && '' == $get_ip->city ? '' : '' == $get_ip->country_name ? '' : '' == $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name;// WPCS: loose comparison ok.

						$ip_address_range_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'block_ip_range'
							)
						);// WPCS: db call ok, cache ok.
						$ip_exists             = false;
						if ( isset( $ip_address_range_data ) && count( $ip_address_range_data ) > 0 ) {
							foreach ( $ip_address_range_data as $data ) {
								$ip_range_unserialized_data = maybe_unserialize( $data->meta_value );
								$data_range                 = explode( ',', $ip_range_unserialized_data['ip_range'] );
								if ( ( $start_ip_range >= $data_range[0] && $start_ip_range <= $data_range[1] ) || ( $end_ip_range >= $data_range[0] && $end_ip_range <= $data_range[1] ) ) {
									echo 1;
									$ip_exists = true;
									break;
								} elseif ( ( $start_ip_range <= $data_range[0] && $start_ip_range <= $data_range[1] ) && ( $end_ip_range >= $data_range[0] && $end_ip_range >= $data_range[1] ) ) {
									echo 1;
									$ip_exists = true;
									break;
								}
							}
						}
						$cpo_ip_address  = get_ip_address_clean_up_optimizer();
						$user_ip_address = '::1' === $cpo_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpo_ip_address ) );
						if ( $user_ip_address >= $start_ip_range && $user_ip_address <= $end_ip_range ) {
							echo 2;
							$ip_exists = true;
							break;
						}
						if ( false === $ip_exists ) {
							$insert_manage_ip_range              = array();
							$insert_manage_ip_range['type']      = 'block_ip_range';
							$insert_manage_ip_range['parent_id'] = '0';
							$last_id                             = $obj_dbhelper_clean_up_optimizer->insert_command( clean_up_optimizer(), $insert_manage_ip_range );

							$insert_manage_ip_range                = array();
							$insert_manage_ip_range['ip_range']    = $ip_range;
							$insert_manage_ip_range['blocked_for'] = sanitize_text_field( $ip_range_data['ux_ddl_range_blocked'] );
							$insert_manage_ip_range['location']    = $location;
							$insert_manage_ip_range['comments']    = sanitize_text_field( $ip_range_data['ux_txtarea_manage_ip_range'] );
							$insert_manage_ip_range['date_time']   = CLEAN_UP_OPTIMIZER_LOCAL_TIME;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_range';// WPCS: db slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_range );// WPCS: db slow query ok.
							$obj_dbhelper_clean_up_optimizer->insert_command( clean_up_optimizer_meta(), $insert_data );

							$time_interval = sanitize_text_field( $ip_range_data['ux_ddl_range_blocked'] );
							if ( 'permanently' !== $time_interval ) {
								$cron_name = 'ip_range_unblocker_' . $last_id;
								schedule_clean_up_optimizer_ip_address_and_ranges( $cron_name, $time_interval );
							}
						}
					}
					break;

				case 'change_email_template_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'email_template_data' ) ) {// WPCS: input var ok.
						$templates           = isset( $_REQUEST['data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['data'] ) ) : '';// WPCS: input var ok.
						$templates_data      = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', $templates
							)
						);// WPCS: db call ok, cache ok.
						$email_template_data = get_clean_up_optimizer_unserialize_data( $templates_data );
						echo wp_json_encode( $email_template_data );
					}
					break;
			}
			die();
		}
	}
}

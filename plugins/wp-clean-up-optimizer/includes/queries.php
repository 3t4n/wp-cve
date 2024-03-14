<?php
/**
 * This File is used to fetch data from database.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if accessed directly.
}
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
		$data_logs = array();
		if ( ! function_exists( 'get_clean_up_optimizer_details' ) ) {
			/**
			 * This function is used to get clean up optimizer details.
			 *
			 * @param string $manage_data .
			 */
			function get_clean_up_optimizer_details( $manage_data ) {
				$clean_up_id      = array();
				$clean_up_details = array();
				if ( isset( $manage_data ) && count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $row ) {
						array_push( $clean_up_id, $row->meta_id );
					}
				}
				$clean_up_id = array_unique( $clean_up_id, SORT_REGULAR );
				if ( isset( $manage_data ) && count( $manage_data ) > 0 ) {
					foreach ( $clean_up_id as $id ) {
						$clean_up = get_clean_up_optimizer_data( $id, $manage_data );
						array_push( $clean_up_details, $clean_up );
					}
				}
				return array_unique( $clean_up_details, SORT_REGULAR );
			}
		}
		if ( ! function_exists( 'get_clean_up_optimizer_meta_data' ) ) {
			/**
			 * This function is used to get clean up optimizer meta data.
			 *
			 * @param string $meta_key .
			 */
			function get_clean_up_optimizer_meta_data( $meta_key ) {
				global $wpdb;
				$data = $wpdb->get_var(
					$wpdb->prepare(
						'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key=%s', $meta_key
					)
				);// WPCS: db call ok; no-cache ok.
				return maybe_unserialize( $data );
			}
		}
		if ( ! function_exists( 'get_clean_up_optimizer_details_date' ) ) {
			/**
			 * This function is used to get clean up optimizer details date.
			 *
			 * @param string $clean_up_manage .
			 * @param string $date1 .
			 * @param string $date2 .
			 */
			function get_clean_up_optimizer_details_date( $clean_up_manage, $date1, $date2 ) {
				$array_details = array();
				if ( isset( $clean_up_manage ) && count( $clean_up_manage ) > 0 ) {
					foreach ( $clean_up_manage as $raw_row ) {
						$unserialize_data            = maybe_unserialize( $raw_row->meta_value );
						$unserialize_data['id']      = $raw_row->id;
						$unserialize_data['meta_id'] = $raw_row->meta_id;
						if ( $unserialize_data['date_time'] >= $date1 && $unserialize_data['date_time'] <= $date2 ) {
							array_push( $array_details, $unserialize_data );
						}
					}
				}
				return $array_details;
			}
		}

		if ( ! function_exists( 'get_clean_up_optimizer_unserialize_data' ) ) {
			/**
			 * This function is used to get clean up optimizer unserialize data.
			 *
			 * @param string $manage_data .
			 */
			function get_clean_up_optimizer_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				if ( isset( $manage_data ) && count( $manage_data ) > 0 ) {
					foreach ( $manage_data as $value ) {
						$unserialize_data = maybe_unserialize( $value->meta_value );

						$unserialize_data['meta_id'] = $value->meta_id;
						array_push( $unserialize_complete_data, $unserialize_data );
					}
				}
				return $unserialize_complete_data;
			}
		}

		if ( ! function_exists( 'get_excluded_termids_clean_up_optimizer' ) ) {
			/**
			 * This function is used to get data.
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
			 * This function is used to get default taxonomy.
			 */
			function get_default_taxonomy_termids_clean_up_optimizer() {
				$taxonomies       = get_taxonomies();
				$default_term_ids = array();
				if ( $taxonomies ) {
					$tax = array_keys( $taxonomies );
					if ( $tax ) {
						if ( isset( $tax ) && count( $tax ) > 0 ) {
							foreach ( $tax as $t ) {
								$term_id = intval( get_option( 'default_' . $t ) );
								if ( $term_id > 0 ) {
									$default_term_ids[] = $term_id;
								}
							}
						}
					}
				}
				return $default_term_ids;
			}
		}

		if ( ! function_exists( 'get_crons_clean_up_optimizer' ) ) {
			/**
			 * This function is used to get crons.
			 *
			 * @param string $get_schedulers .
			 */
			function get_crons_clean_up_optimizer( $get_schedulers ) {
				$current_scheduler = array();
				if ( isset( $get_schedulers ) && count( $get_schedulers ) > 0 ) {
					foreach ( $get_schedulers as $value => $key ) {
						$arr_key = array_keys( $key );
						if ( isset( $arr_key ) && count( $arr_key ) > 0 ) {
							foreach ( $arr_key as $value ) {
								array_push( $current_scheduler, $value );
							}
						}
					}
				}
				$core_cron_hooks = array(
					'wp_scheduled_delete',
					'upgrader_scheduled_cleanup',
					'importer_scheduled_cleanup',
					'publish_future_post',
					'akismet_schedule_cron_recheck',
					'akismet_scheduled_delete',
					'do_pings',
					'wp_version_check',
					'wp_update_plugins',
					'wp_update_themes',
					'wp_maybe_auto_update',
					'wp_scheduled_auto_draft_delete',
					'clean_up_optimizer_license_validator',
					'automatic_updates_clean_up_optimizer',
					'limit_attempts_optimizer_license_validator',
					'clean_up_optimizer_license_validator',
					'check_plugin_updates-clean-up-optimizer-personal-edition',
					'check_plugin_updates-clean-up-optimizer-business-edition',
					'check_plugin_updates-clean-up-optimizer-developer-edition',
					'check_plugin_updates-limit-attempts-optimizer-business-edition',
					'check_plugin_updates-limit-attempts-optimizer-personal-edition',
					'check_plugin_updates-limit-attempts-optimizer-developer-edition',
					'check_plugin_updates-captcha-optimizer-business-edition',
					'check_plugin_updates-backup-bank-business-edition',
					'check_plugin_updates-backup-bank-developer-edition',
					'check_plugin_updates-backup-bank-personal-edition',
					'backup_bank_license_validator',
					'check_plugin_updates-captcha-optimizer-personal-edition',
					'check_plugin_updates-captcha-optimizer-developer-edition',
					'check_plugin_updates-clean-up-optimizer-business-edition',
					'check_plugin_updates-clean-up-optimizer-personal-edition',
					'check_plugin_updates-clean-up-optimizer-developer-edition',
					'captcha_optimizer_license_scheduler',
					'mail_optimizer_license_validator_scheduler',
					'captcha_bank_license_validator_scheduler',
				);
				if ( isset( $current_scheduler ) && count( $current_scheduler ) > 0 ) {
					foreach ( $current_scheduler as $value ) {
						if ( strstr( $value, 'ip_address_unblocker_' ) ) {
							array_push( $core_cron_hooks, $value );
						} elseif ( strstr( $value, 'ip_range_unblocker_' ) ) {
							array_push( $core_cron_hooks, $value );
						} elseif ( strstr( $value, 'wordpress_data_scheduler_' ) ) {
							array_push( $core_cron_hooks, $value );
						} elseif ( strstr( $value, 'database_scheduler_' ) ) {
							array_push( $core_cron_hooks, $value );
						}
					}
				}
				return $core_cron_hooks;
			}
		}
		if ( ! function_exists( 'get_parent_termids_clean_up_optimizer' ) ) {
			/**
			 * This function is used to get parent termids.
			 */
			function get_parent_termids_clean_up_optimizer() {
				global $wpdb;
				return $wpdb->get_col(
					$wpdb->prepare(
						"SELECT tt.parent FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt
						ON t.term_id = tt.term_id
						WHERE  tt.parent > %d", 0
					)
				);// WPCS: db call ok, no-cache ok.
			}
		}

		if ( ! function_exists( 'display_cron_arguments_clean_up_optimizer' ) ) {
			/**
			 * This function is used to display cron arguments.
			 *
			 * @param string  $key .
			 * @param string  $value .
			 * @param integer $depth .
			 */
			function display_cron_arguments_clean_up_optimizer( $key, $value, $depth = 0 ) {
				if ( is_string( $value ) ) {
					echo str_repeat( '&nbsp;', ( $depth * 2 ) ) . wp_strip_all_tags( $key ) . ' => ' . esc_html( $value ) . '<br />';// WPCS: XSS ok.
				} elseif ( is_array( $value ) ) {
					if ( count( $value ) > 0 ) {
						echo str_repeat( '&nbsp;', ( $depth * 2 ) ) . wp_strip_all_tags( $key ) . '=> array(<br />';// WPCS: XSS ok.
						$depth++;
						foreach ( $value as $k => $v ) {
							display_cron_arguments_clean_up_optimizer( $k, $v, $depth );
						}
						echo str_repeat( '&nbsp;', ( ( $depth - 1 ) * 2 ) ) . ')';// WPCS: XSS ok.
					}
				}
			}
		}
		if ( ! function_exists( 'get_clean_up_optimizer_data' ) ) {
			/**
			 * This function is used to get data.
			 *
			 * @param integer $id .
			 * @param string  $clean_up_details .
			 */
			function get_clean_up_optimizer_data( $id, $clean_up_details ) {
				$get_single_detail = array();
				if ( isset( $clean_up_details ) && count( $clean_up_details ) > 0 ) {
					foreach ( $clean_up_details as $row ) {
						if ( $row->meta_id === $id ) {
							$get_single_detail[ $row->meta_key ] = $row->meta_value; // WPCS: slow query ok.
							$get_single_detail['meta_id']        = $row->meta_id;
						}
					}
				}
				return $get_single_detail;
			}
		}
		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );// WPCS: CSRF ok,WPCS: input var ok.
		}
		$check_clean_up_optimizer_wizard = get_option( 'clean-up-optimizer-wizard-set-up' );
		$page_url                        = false === $check_clean_up_optimizer_wizard ? 'cpo_wizard_optimizer' : $page;
		if ( isset( $_GET['page'] ) ) {// WPCS: CSRF ok, input var ok.
			switch ( $page_url ) {
				case 'cpo_dashboard':
					if ( ! function_exists( 'count_clean_up_optimizer' ) ) {
						/**
						 * This function is used to count.
						 *
						 * @param string $type .
						 */
						function count_clean_up_optimizer( $type ) {
							global $wpdb;
							switch ( $type ) {
								case 'autodraft':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_status = %s', 'auto-draft'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'transient_feed':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->options . " WHERE option_name LIKE '_site_transient_browser_%' OR option_name LIKE '_site_transient_timeout_browser_%' OR option_name LIKE '_transient_feed_%' OR option_name LIKE '_transient_timeout_feed_%'"
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'unapproved_comments':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->comments . ' WHERE comment_approved = %s', '0'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'comments_meta':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->commentmeta . ' WHERE comment_id NOT IN (SELECT comment_id FROM ' . $wpdb->comments . ')'
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'posts_meta':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->postmeta . ' pm LEFT JOIN ' . $wpdb->posts . ' wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL'
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'relationships':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->term_relationships . ' WHERE term_taxonomy_id = 1 AND object_id NOT IN (SELECT id FROM ' . $wpdb->posts . ')'
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'revision':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_type = %s', 'revision'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'remove_pingbacks':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->comments . " WHERE comment_type = 'pingback'"
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'remove_transient_options':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->options . " WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'"
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'remove_trackbacks':
									$count = $wpdb->get_var(
										'SELECT COUNT(*) FROM ' . $wpdb->comments . " WHERE comment_type = 'trackback'"
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'spam':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->comments . ' WHERE comment_approved = %s', 'spam'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'trash':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->comments . ' WHERE comment_approved = %s', 'trash'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'draft':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_status = %s AND (post_type = %s OR post_type = %s)', 'draft', 'page', 'post'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'deleted_posts':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_status = %s', 'trash'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'duplicated_postmeta':
									$query = $wpdb->get_col(
										$wpdb->prepare(
											'SELECT COUNT(meta_id) AS count FROM ' . $wpdb->postmeta . ' GROUP BY post_id, meta_key, meta_value HAVING count > %d', 1
										)
									);// WPCS: db call ok; no-cache ok.
									if ( is_array( $query ) ) {
										$count = array_sum( array_map( 'intval', $query ) ) - count( $query );
									}
									break;

								case 'oembed_caches':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(meta_id) FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE(%s)', '%_oembed_%'
										)
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'duplicated_commentmeta':
									$query = $wpdb->get_col(
										$wpdb->prepare(
											'SELECT COUNT(meta_id) AS count FROM ' . $wpdb->commentmeta . ' GROUP BY comment_id, meta_key, meta_value HAVING count > %d', 1
										)
									);// WPCS: db call ok; no-cache ok.
									if ( is_array( $query ) ) {
										$count = array_sum( array_map( 'intval', $query ) ) - count( $query );
									}
									break;

								case 'orphan_user_meta':
									$count = $wpdb->get_var(
										'SELECT COUNT(umeta_id) FROM ' . $wpdb->usermeta . ' WHERE user_id NOT IN (SELECT ID FROM ' . $wpdb->users . ')'// @codingStandardsIgnoreLine.
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'duplicated_usermeta':
									$query = $wpdb->get_col(
										$wpdb->prepare(
											'SELECT COUNT(umeta_id) AS count FROM ' . $wpdb->usermeta . ' GROUP BY user_id, meta_key, meta_value HAVING count > %d', 1 // @codingStandardsIgnoreLine.
										)
									);// WPCS: db call ok; no-cache ok.
									if ( is_array( $query ) ) {
										$count = array_sum( array_map( 'intval', $query ) ) - count( $query );
									}
									break;

								case 'orphaned_term_relationships':
									$count = $wpdb->get_var(
										'SELECT COUNT(object_id) FROM ' . $wpdb->term_relationships . ' AS tr INNER JOIN ' . $wpdb->term_taxonomy . " AS tt
										ON tr.term_taxonomy_id = tt.term_taxonomy_id
										WHERE tt.taxonomy != 'link_category' AND tr.object_id NOT IN (SELECT ID FROM " . $wpdb->posts . ')'
									);// WPCS: db call ok; no-cache ok.
									break;

								case 'unused_terms':
									$count = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT COUNT(t.term_id) FROM ' . $wpdb->terms . ' AS t INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
											ON t.term_id = tt.term_id WHERE tt.count = %d AND t.term_id NOT IN (' . implode( ',', get_excluded_termids_clean_up_optimizer() ) . ')', 0 // @codingStandardsIgnoreLine.
										)
									);// WPCS: db call ok; no-cache ok.
									break;
							}
							return $count;
						}
					}
					break;
				case 'cpo_schedule_optimizer':
					$schedule_data    = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key=%s  ORDER BY meta_id desc', 'schedule_clean_up_wordpress'
						)
					);// WPCS: db call ok; no-cache ok.
					$manage_scheduled = get_clean_up_optimizer_unserialize_data( $schedule_data );
					break;
				case 'cpo_add_new_wordpress_schedule':
					if ( isset( $_REQUEST['id'] ) ) {
						$id                = intval( $_REQUEST['id'] );// WPCS: input var ok.
						$schedule_all_data = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_id=%d', $id
							)
						);// WPCS: db call ok; no-cache ok.
						$data_array        = maybe_unserialize( $schedule_all_data );
					}
					break;
				case 'cpo_db_optimizer':
					if ( is_multisite() ) {
						$name     = '';
						$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
						if ( isset( $blog_ids ) && count( $blog_ids ) > 0 ) {
							foreach ( $blog_ids as $blog_id ) {
								$name .= " AND Name NOT LIKE '" . $wpdb->prefix . $blog_id . "%'";
							}
						}
						$manual_clean_up = 'SHOW TABLE STATUS FROM `' . DB_NAME . "` WHERE Name LIKE '" . $wpdb->prefix . "%'" . $name;
					} else {
						$manual_clean_up = 'SHOW TABLE STATUS FROM `' . DB_NAME . '`';
					}
					$result = $wpdb->get_results( $manual_clean_up );// @codingStandardsIgnoreLine.
					break;
				case 'cpo_database_view_records':
					$table_columns_name  = array();
					$view_records        = array();
					$table_name_database = '';
					if ( wp_verify_nonce( isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : '', 'get_selected_database_data' ) ) {
						$table_name_database = isset( $_REQUEST['row_type'] ) ? base64_decode( wp_unslash( $_REQUEST['row_type'] ) ) : '';// // WPCS: input var ok, sanitization ok.
						if ( isset( $_REQUEST['row_type'] ) ) {// WPCS: CSRF ok, input var ok.
							$view_records = $wpdb->get_results( "SELECT * FROM $table_name_database" );// @codingStandardsIgnoreLine.
							$columns      = $wpdb->get_results( "SHOW columns FROM $table_name_database" );// @codingStandardsIgnoreLine.
							for ( $row = 0; $row < count( $columns ); $row++ ) { // @codingStandardsIgnoreLine.
								$count = 0;
								if ( isset( $columns[ $row ] ) && '' !== $columns[ $row ] ) {
									foreach ( $columns[ $row ] as $data => $records ) {
										if ( $count < 1 ) {
											array_push( $table_columns_name, $records );
										}
										$count++;
									}
								}
							}
						}
					}
					break;
				case 'cpo_schedule_db_optimizer':
					$schedule_data   = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key=%s  ORDER BY meta_id desc', 'schedule_clean_up_database'
						)
					);// WPCS: db call ok; no-cache ok.
					$get_db_schedule = get_clean_up_optimizer_unserialize_data( $schedule_data );
					break;
				case 'cpo_add_new_database_schedule':
					if ( isset( $_REQUEST['id'] ) ) {// WPCS: CSRF ok, input var ok.
						$id                = intval( $_REQUEST['id'] );// WPCS: CSRF ok, input var ok.
						$schedule_all_data = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_id=%d', $id
							)
						);// WPCS: db call ok; no-cache ok.
						$get_array         = maybe_unserialize( $schedule_all_data );
						$tables_array      = explode( ', ', $get_array['table_name_database'] );
					}
					if ( is_multisite() ) {
						$name     = '';
						$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
						if ( isset( $blog_ids ) && count( $blog_ids ) > 0 ) {
							foreach ( $blog_ids as $blog_id ) {
								$name .= " AND Name NOT LIKE '" . $wpdb->prefix . $blog_id . "%'";
							}
						}
						$manual_clean_up = 'SHOW TABLE STATUS FROM `' . DB_NAME . "` WHERE Name LIKE '" . $wpdb->prefix . "%'" . $name;
					} else {
						$manual_clean_up = 'SHOW TABLE STATUS FROM `' . DB_NAME . '`';
					}
					$result = $wpdb->get_results( $manual_clean_up );// @codingStandardsIgnoreLine.
					break;
				case 'cpo_login_logs':
					$end_date   = CLEAN_UP_OPTIMIZER_LOCAL_TIME + 86340;
					$start_date = $end_date - 604380;

					$login_logs = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'recent_login_data'
						)
					);// WPCS: db call ok; no-cache ok.
					$data_logs  = get_clean_up_optimizer_details_date( $login_logs, $start_date, $end_date );
					break;
				case 'cpo_live_traffic':
					global $wpdb;
					$live_traffic_logs_data = get_clean_up_optimizer_meta_data( 'other_settings' );
					if ( 'enable' === $live_traffic_logs_data['live_traffic_monitoring'] ) {
						$end_date          = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
						$start_date        = $end_date - 60;
						$live_traffic_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'visitor_log_data'
							)
						);// WPCS: db call ok; no-cache ok.
						$data_logs         = get_clean_up_optimizer_details_date( $live_traffic_data, $start_date, $end_date );
					}
					break;
				case 'cpo_visitor_logs':
					global $wpdb;
					$visitor_logs_data = get_clean_up_optimizer_meta_data( 'other_settings' );
					if ( 'enable' === $visitor_logs_data['visitor_logs_monitoring'] ) {
						$end_date          = CLEAN_UP_OPTIMIZER_LOCAL_TIME + 86340;
						$start_date        = $end_date - 172640;
						$live_traffic_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'visitor_log_data'
							)
						);// WPCS: db call ok; no-cache ok.
						$data_logs         = get_clean_up_optimizer_details_date( $live_traffic_data, $start_date, $end_date );
					}
					break;
				case 'cpo_notifications_setup':
					$meta_data_array = get_clean_up_optimizer_meta_data( 'alert_setup' );
					break;
				case 'cpo_message_settings':
					$meta_data_array = get_clean_up_optimizer_meta_data( 'error_message' );
					break;
				case 'cpo_other_settings':
					$meta_data_array   = get_clean_up_optimizer_meta_data( 'other_settings' );
					$trackbacks_status = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT count(ping_status) FROM ' . $wpdb->posts .
							' WHERE ping_status=%s', 'open'
						)
					);// WPCS: db call ok; no-cache ok.
					$comments_status   = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT count(comment_status) FROM ' . $wpdb->posts .
							' WHERE comment_status=%s', 'open'
						)
					);// WPCS: db call ok; no-cache ok.
					break;
				case 'cpo_blockage_settings':
					$blocking_option_array = get_clean_up_optimizer_meta_data( 'blocking_options' );

					break;
				case 'cpo_ip_addresses':
					$end_date          = CLEAN_UP_OPTIMIZER_LOCAL_TIME + 86340;
					$start_date        = $end_date - 2678340;
					$manage_ip         = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_address'
						)
					);// WPCS: db call ok; no-cache ok.
					$manage_ip_address = get_clean_up_optimizer_details_date( $manage_ip, $start_date, $end_date );
					break;
				case 'cpo_ip_ranges':
					$end_date        = CLEAN_UP_OPTIMIZER_LOCAL_TIME + 86340;
					$start_date      = $end_date - 2678340;
					$manage_range    = $wpdb->get_results(
						$wpdb->prepare(
							'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s ORDER BY meta_id DESC', 'block_ip_range'
						)
					);// WPCS: db call ok; no-cache ok.
					$manage_ip_range = get_clean_up_optimizer_details_date( $manage_range, $start_date, $end_date );
					break;
				case 'cpo_block_unblock_countries':
					$country_data_array = get_clean_up_optimizer_meta_data( 'country_blocks' );
					break;
				case 'cpo_roles_and_capabilities':
					$details_roles_capabilities = get_clean_up_optimizer_meta_data( 'roles_and_capabilities' );
					$other_roles_access_array   = array(
						'manage_options',
						'edit_plugins',
						'edit_posts',
						'publish_posts',
						'publish_pages',
						'edit_pages',
						'read',
					);
					$other_roles_array          = isset( $details_roles_capabilities['capabilities'] ) && '' !== $details_roles_capabilities['capabilities'] ? $details_roles_capabilities['capabilities'] : $other_roles_access_array;
					break;
				case 'cpo_core_jobs':
					$get_schedulers   = _get_cron_array();
					$schedule_details = wp_get_schedules();
					$core_cron_hooks  = get_crons_clean_up_optimizer( $get_schedulers );
					break;
				case 'cpo_custom_jobs':
					$schedulers       = _get_cron_array();
					$schedule_details = wp_get_schedules();
					$core_cron_hooks  = get_crons_clean_up_optimizer( $schedulers );
					break;
			}
		}
	}
}

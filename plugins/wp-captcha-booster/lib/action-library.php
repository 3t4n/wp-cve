<?php
/**
 * This file is used for managing data in database.
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
		if ( ! function_exists( 'get_captcha_booster_unserialize_data' ) ) {
			/**
			 * This function is used to fetch nserialized data.
			 *
			 * @param array $manage_data .
			 */
			function get_captcha_booster_unserialize_data( $manage_data ) {
				$unserialize_complete_data = array();
				foreach ( $manage_data as $value ) {
					$unserialize_data = maybe_unserialize( $value->meta_value );

					$unserialize_data['meta_id'] = $value->meta_id;
					array_push( $unserialize_complete_data, $unserialize_data );
				}
				return $unserialize_complete_data;
			}
		}

		if ( ! function_exists( 'get_captcha_details_unserialize' ) ) {
			/**
			 * This function is used to fetch nserialized data according to timestamp.
			 *
			 * @param array   $captcha_manage .
			 * @param integer $cpb_date1 .
			 * @param integer $cpb_date2 .
			 */
			function get_captcha_details_unserialize( $captcha_manage, $cpb_date1, $cpb_date2 ) {
				$captcha_details = array();
				foreach ( $captcha_manage as $raw_row ) {
					$row = maybe_unserialize( $raw_row->meta_value );
					if ( $row['date_time'] >= $cpb_date1 && $row['date_time'] <= $cpb_date2 ) {
						array_push( $captcha_details, $row );
					}
				}
				return $captcha_details;
			}
		}

		if ( isset( $_REQUEST['param'] ) ) { // WPCS: CSRF ok, input var ok.
			$obj_dbhelper_captcha_booster = new Dbhelper_Captcha_Booster();
			switch ( sanitize_text_field( wp_unslash( $_REQUEST['param'] ) ) ) { // WPCS: CSRF ok, input var ok.
				case 'wizard_captcha_booster':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_booster_check_status' ) ) { // WPCS: CSRF ok, input var ok.
						$type             = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : ''; // WPCS: CSRF ok, input var ok.
						$user_admin_email = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : ''; // WPCS: CSRF ok, input var ok.
						if ( '' === $user_admin_email ) {
							$user_admin_email = get_option( 'admin_email' );
						}
						update_option( 'captcha-booster-admin-email', $user_admin_email );
						update_option( 'captcha-booster-wizard-set-up', $type );
						if ( 'opt_in' === $type ) {
							$plugin_info_captcha_booster = new Plugin_Info_Captcha_Booster();
							global $wp_version;
							$theme_details = array();
							if ( $wp_version >= 3.4 ) {
								$active_theme                   = wp_get_theme();
								$theme_details['theme_name']    = strip_tags( $active_theme->name );
								$theme_details['theme_version'] = strip_tags( $active_theme->version );
								$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
							}
							$plugin_stat_data                     = array();
							$plugin_stat_data['plugin_slug']      = 'wp-captcha-booster';
							$plugin_stat_data['type']             = 'standard_edition';
							$plugin_stat_data['version_number']   = CAPTCHA_BOOSTER_VERSION_NUMBER;
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
							$plugin_stat_data['plugins']          = $plugin_info_captcha_booster->get_plugin_info_captcha_booster();
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
										'site_id' => false !== get_option( 'cpbo_tech_banker_site_id' ) ? get_option( 'cpbo_tech_banker_site_id' ) : '',
										'action'  => 'plugin_analysis_data',
									),
								)
							);
							if ( ! is_wp_error( $response ) ) {
								false !== $response['body'] ? update_option( 'cpbo_tech_banker_site_id', $response['body'] ) : '';
							}
						}
					}
					break;

				case 'captcha_type_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_booster_file' ) ) { // WPCS: CSRF ok, input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $captcha_type_data ); // WPCS: Input var ok.
						$arithmetic = isset( $_REQUEST['arithmetic'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['arithmetic'] ) ) ) ) ? json_decode( stripcslashes( $_REQUEST['arithmetic'] ) ) : array() ) : array(); // WPCS: CSRF ok, input var ok, sanitization ok.

						$update_text_captcha = array();
						$where               = array();

						$update_text_captcha['captcha_type_text_logical'] = sanitize_text_field( $captcha_type_data['ux_ddl_captcha_type'] );
						$update_text_captcha['captcha_characters']        = intval( $captcha_type_data['ux_txt_character'] );
						$update_text_captcha['captcha_type']              = sanitize_text_field( $captcha_type_data['ux_ddl_alphabets'] );
						$update_text_captcha['text_case']                 = sanitize_text_field( $captcha_type_data['ux_ddl_case'] );
						$update_text_captcha['case_sensitive']            = sanitize_text_field( $captcha_type_data['ux_ddl_case_disable'] );
						$update_text_captcha['captcha_width']             = intval( $captcha_type_data['ux_txt_width'] );
						$update_text_captcha['captcha_height']            = intval( $captcha_type_data['ux_txt_height'] );
						$update_text_captcha['captcha_background']        = 'bg4.jpg';
						$update_text_captcha['border_style']              = sanitize_text_field( implode( ',', $captcha_type_data['ux_txt_border_style'] ) );
						$update_text_captcha['lines']                     = intval( $captcha_type_data['ux_txt_line'] );
						$update_text_captcha['lines_color']               = sanitize_text_field( $captcha_type_data['ux_txt_color'] );
						$update_text_captcha['noise_level']               = intval( $captcha_type_data['ux_txt_noise_level'] );
						$update_text_captcha['noise_color']               = sanitize_text_field( $captcha_type_data['ux_txt_noise_color'] );
						$update_text_captcha['text_transperancy']         = intval( $captcha_type_data['ux_txt_transperancy'] );
						$update_text_captcha['signature_text']            = 'Captcha Booster';
						$update_text_captcha['signature_style']           = '8,#cccccc';
						$update_text_captcha['signature_font']            = 'Roboto:100';
						$update_text_captcha['text_shadow_color']         = sanitize_text_field( $captcha_type_data['ux_txt_shadow_color'] );
						$update_text_captcha['mathematical_operations']   = sanitize_text_field( $captcha_type_data['ux_rdl_mathematical_captcha'] );
						$update_text_captcha['arithmetic_actions']        = sanitize_text_field( implode( ',', $arithmetic ) );
						$update_text_captcha['relational_actions']        = '1,1';
						$update_text_captcha['arrange_order']             = '1,1';
						$update_text_captcha['text_style']                = '20,#000000';
						$update_text_captcha['text_font']                 = 'Roboto Condensed';


						$font_value           = isset( $update_text_captcha['text_font'] ) ? $update_text_captcha['text_font'] : '';
						$signature_font_value = isset( $update_text_captcha['signature_font'] ) ? $update_text_captcha['signature_font'] : '';

						$update_data               = array();
						$where['meta_key']         = 'captcha_type'; // WPCS: slow query ok.
						$update_data['meta_value'] = maybe_serialize( $update_text_captcha ); // WPCS: slow query ok.
						$obj_dbhelper_captcha_booster->update_command( captcha_booster_meta(), $update_data, $where );
					}
					break;

				case 'captcha_display_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_booster_settings' ) ) { // WPCS: CSRF ok, input var ok.
						$checkbox_array                            = isset( $_REQUEST['checkbox_array'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['checkbox_array'] ) ) ) ) ? json_decode( stripcslashes( wp_unslash( $_REQUEST['checkbox_array'] ) ) ) : array() ) : array(); // WPCS: CSRF ok, input var ok, sanitization ok.
						$update_display_settings_array             = array();
						$update_display_settings_array['settings'] = sanitize_text_field( implode( ',', $checkbox_array ) );

						$where                     = array();
						$update_data               = array();
						$where['meta_key']         = 'display_settings'; // WPCS: slow query ok.
						$update_data['meta_value'] = maybe_serialize( $update_display_settings_array ); // WPCS: slow query ok.
						$obj_dbhelper_captcha_booster->update_command( captcha_booster_meta(), $update_data, $where );
					}
					break;

				case 'captcha_log_delete_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_selected_logs_delete' ) ) { // WPCS: CSRF ok, input var ok.
						$where              = array();
						$meta_id            = isset( $_REQUEST['meta_id'] ) ? intval( wp_unslash( $_REQUEST['meta_id'] ) ) : 0; // WPCS: Input var ok.
						$where['meta_id']   = $meta_id;
						$where_parent['id'] = $meta_id;
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster_meta(), $where );
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster(), $where_parent );
					}
					break;

				case 'captcha_blocking_options_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_booster_options' ) ) { // WPCS: CSRF ok, input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $blocking_option_data ); // WPCS: Input var ok.
						$update_captcha_type = array();
						$where               = array();

						$update_captcha_type['auto_ip_block']                  = sanitize_text_field( $blocking_option_data['ux_ddl_auto_ip'] );
						$update_captcha_type['maximum_login_attempt_in_a_day'] = intval( $blocking_option_data['ux_txt_login'] );
						$update_captcha_type['block_for_time']                 = sanitize_text_field( $blocking_option_data['ux_ddl_blocked_for'] );

						$update_blocking_options_data               = array();
						$where['meta_key']                          = 'blocking_options'; // WPCS: slow query ok.
						$update_blocking_options_data['meta_value'] = maybe_serialize( $update_captcha_type ); // WPCS: slow query ok.
						$obj_dbhelper_captcha_booster->update_command( captcha_booster_meta(), $update_blocking_options_data, $where );
					}
					break;

				case 'captcha_manage_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_manage_ip_address' ) ) { // WPCS: CSRF ok, input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $advance_security_data ); // WPCS: Input var ok.
						$ip          = isset( $_REQUEST['ip_address'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['ip_address'] ) ) ) ) : 0; // WPCS: Input var ok.
						$ip_address  = long2ip_captcha_booster( $ip );
						$get_ip      = get_ip_location_captcha_booster( $ip_address );
						$blocked_for = sanitize_text_field( $advance_security_data['ux_ddl_hour'] );
						$location    = '' == $get_ip->country_name && '' == $get_ip->city ? '' : '' == $get_ip->country_name ? '' : '' == $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name; // WPCS: loose comparison ok.

						$ip_address_count = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'block_ip_address'
							)
						);// db call ok; no-cache ok.
						foreach ( $ip_address_count as $data ) {
							$ip_address_unserialize = maybe_unserialize( $data->meta_value );
							if ( $ip === $ip_address_unserialize['ip_address'] ) {
								echo '1';
								die();
							}
						}
						$ip_address_ranges_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'block_ip_range'
							)
						);// db call ok; no-cache ok.
						$ip_exists              = false;
						foreach ( $ip_address_ranges_data as $data ) {
							$ip_range_unserialized_data = maybe_unserialize( $data->meta_value );
							$data_range                 = explode( ',', $ip_range_unserialized_data['ip_range'] );
							if ( $ip >= $data_range[0] && $ip <= $data_range[1] ) {
								$ip_exists = true;
								break;
							}
						}
						$cpb_ip_address  = get_ip_address_for_captcha_booster();
						$user_ip_address = '::1' === $cpb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpb_ip_address ) );
						if ( true === $ip_exists ) {
							echo 1;
						} elseif ( $ip === $user_ip_address ) {
							echo 2;
						} else {
							$ip_address_parent_id                  = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'captcha_booster WHERE type=%s', 'advance_security'
								)
							);// db call ok; no-cache ok.
							$insert_manage_ip_address              = array();
							$insert_manage_ip_address['type']      = 'block_ip_address';
							$insert_manage_ip_address['parent_id'] = $ip_address_parent_id;
							$last_id                               = $obj_dbhelper_captcha_booster->insert_command( captcha_booster(), $insert_manage_ip_address );

							$insert_manage_ip_address                = array();
							$insert_manage_ip_address['ip_address']  = $ip;
							$insert_manage_ip_address['blocked_for'] = $blocked_for;
							$insert_manage_ip_address['location']    = $location;
							$insert_manage_ip_address['comments']    = sanitize_text_field( $advance_security_data['ux_txtarea_comments'] );
							$insert_manage_ip_address['date_time']   = CAPTCHA_BOOSTER_LOCAL_TIME;
							$insert_manage_ip_address['meta_id']     = $last_id;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_address'; // WPCS: slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_address ); // WPCS: slow query ok.
							$obj_dbhelper_captcha_booster->insert_command( captcha_booster_meta(), $insert_data );

							if ( 'permanently' !== $blocked_for ) {
								$cron_name = 'ip_address_unblocker_' . $last_id;
								wp_schedule_captcha_booster( $cron_name, $blocked_for );
							}
						}
					}
					break;

				case 'captcha_delete_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_manage_ip_address_delete' ) ) { // WPCS: CSRF ok, input var ok.
						$where              = array();
						$where_parent       = array();
						$id                 = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : 0; // WPCS: Input var ok.
						$where_parent['id'] = $id;
						$where['meta_id']   = $id;
						$cron_name          = 'ip_address_unblocker_' . $where['meta_id'];
						wp_unschedule_captcha_booster( $cron_name );
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster_meta(), $where );
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster(), $where_parent );
					}
					break;

				case 'captcha_manage_ip_ranges_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_manage_ip_ranges' ) ) { // WPCS: CSRF ok, input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $ip_range_data ); // WPCS: Input var ok.
						$start_ip_range = isset( $_REQUEST['start_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['start_range'] ) ) ) ) : 0; // WPCS: Input var ok.
						$end_ip_range   = isset( $_REQUEST['end_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['end_range'] ) ) ) ) : 0; // WPCS: Input var ok.
						$blocked_for    = sanitize_text_field( $ip_range_data['ux_ddl_blocked'] );
						$get_ip         = get_ip_location_captcha_booster( long2ip_captcha_booster( $start_ip_range ) );
						$location       = '' == $get_ip->country_name && '' == $get_ip->city ? '' : '' == $get_ip->country_name ? '' : '' == $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name; // WPCS: loose comparison ok.

						$ip_address_range_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'block_ip_range'
							)
						);// db call ok; no-cache ok.
						$ip_exists             = false;
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
						$cpb_ip_address  = get_ip_address_for_captcha_booster();
						$user_ip_address = '::1' === $cpb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpb_ip_address ) );
						if ( $user_ip_address >= $start_ip_range && $user_ip_address <= $end_ip_range ) {
							echo 2;
							$ip_exists = true;
							break;
						}
						if ( false === $ip_exists ) {
							$ip_range_parent_id                  = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'captcha_booster WHERE type=%s', 'advance_security'
								)
							);// db call ok; no-cache ok.
							$insert_manage_ip_range              = array();
							$insert_manage_ip_range['type']      = 'block_ip_range';
							$insert_manage_ip_range['parent_id'] = $ip_range_parent_id;
							$last_id                             = $obj_dbhelper_captcha_booster->insert_command( captcha_booster(), $insert_manage_ip_range );

							$insert_manage_ip_range                = array();
							$insert_manage_ip_range['ip_range']    = $start_ip_range . ',' . $end_ip_range;
							$insert_manage_ip_range['blocked_for'] = $blocked_for;
							$insert_manage_ip_range['location']    = $location;
							$insert_manage_ip_range['comments']    = sanitize_text_field( $ip_range_data['ux_txtarea_manage_ip_range'] );
							$insert_manage_ip_range['date_time']   = CAPTCHA_BOOSTER_LOCAL_TIME;
							$insert_manage_ip_range['meta_id']     = $last_id;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_range'; // WPCS: slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_range ); // WPCS: slow query ok.
							$obj_dbhelper_captcha_booster->insert_command( captcha_booster_meta(), $insert_data );

							if ( 'permanently' !== $blocked_for ) {
								$cron_name = 'ip_range_unblocker_' . $last_id;
								wp_schedule_captcha_booster( $cron_name, $blocked_for );
							}
						}
					}
					break;

				case 'captcha_delete_ip_range_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_manage_ip_ranges_delete' ) ) { // WPCS: CSRF ok, input var ok.
						$where              = array();
						$where_parent       = array();
						$id                 = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : 0; // WPCS: Input var ok.
						$where_parent['id'] = $id;
						$where['meta_id']   = $id;
						$cron_name          = 'ip_range_unblocker_' . $where['meta_id'];
						wp_unschedule_captcha_booster( $cron_name );
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster_meta(), $where );
						$obj_dbhelper_captcha_booster->delete_command( captcha_booster(), $where_parent );
					}
					break;

				case 'captcha_type_email_templates_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_type_email_templates' ) ) { // WPCS: CSRF ok, input var ok.
						$templates            = isset( $_REQUEST['data'] ) ? sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : ''; // WPCS: Input var ok.
						$email_templates_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key=%s', $templates
							)
						);// db call ok; no-cache ok.

						$email_template_data_unseralize = get_captcha_booster_unserialize_data( $email_templates_data );
						echo wp_json_encode( $email_template_data_unseralize );
					}
					break;

				case 'captcha_booster_other_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_booster_other_settings' ) ) { // WPCS: CSRF ok, input var ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $update_array ); // WPCS: Input var ok.
						$update_captcha_type = array();
						$where               = array();

						$update_captcha_type['automatic_plugin_updates']   = 'disable';
						$update_captcha_type['remove_tables_at_uninstall'] = sanitize_text_field( $update_array['ux_ddl_remove_tables'] );
						$update_captcha_type['live_traffic_monitoring']    = sanitize_text_field( $update_array['ux_ddl_live_traffic_monitoring'] );
						$update_captcha_type['visitor_logs_monitoring']    = sanitize_text_field( $update_array['ux_ddl_visitor_log_monitoring'] );
						$update_captcha_type['ip_address_fetching_method'] = sanitize_text_field( $update_array['ux_ddl_ip_address_fetching_method'] );
						$update_data                                       = array();
						$where['meta_key']                                 = 'other_settings'; // WPCS: slow query ok.
						$update_data['meta_value']                         = maybe_serialize( $update_captcha_type ); // WPCS: slow query ok.
						$obj_dbhelper_captcha_booster->update_command( captcha_booster_meta(), $update_data, $where );
					}
					break;
			}
			die();
		}
	}
}

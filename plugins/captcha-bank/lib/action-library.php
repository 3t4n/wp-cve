<?php
/**
 * This file is used for managing data in database.
 *
 * @author  Tech Banker
 * @package captcha-bank/lib
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
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
		if ( ! function_exists( 'get_fonts_captcha_bank' ) ) {
			/**
			 * This Function is used to get fonts.
			 *
			 * @param string $url .
			 */
			function get_fonts_captcha_bank( $url ) {
				if ( function_exists( 'curl_init' ) ) {
					$curl_handler = curl_init();// @codingStandardsIgnoreLine.
					curl_setopt( $curl_handler, CURLOPT_URL, $url );// @codingStandardsIgnoreLine.
					curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );// @codingStandardsIgnoreLine.
					curl_setopt( $curl_handler, CURLOPT_CONNECTTIMEOUT, 5 );// @codingStandardsIgnoreLine.
					curl_setopt( $curl_handler, CURLOPT_SSL_VERIFYPEER, false );// @codingStandardsIgnoreLine.
					$font = curl_exec( $curl_handler );// @codingStandardsIgnoreLine.
				} elseif ( function_exists( 'file_get_contents' ) ) {// @codingStandardsIgnoreLine.
					$font = @file_get_contents( $url );// @codingStandardsIgnoreLine.
				}
				return $font;
			}
		}
		if ( ! function_exists( 'get_captcha_bank_unserialize_data' ) ) {
			/**
			 * This Function is used to get unserialized data.
			 *
			 * @param string $manage_data .
			 */
			function get_captcha_bank_unserialize_data( $manage_data ) {
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
			 * This Function is used to get unserialized data.
			 *
			 * @param string $captcha_manage .
			 * @param string $cpb_date1 .
			 * @param string $cpb_date2 .
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
		if ( isset( $_REQUEST['param'] ) ) {// WPCS: CSRF ok, input var ok.
			$obj_dbmailer_captcha_bank = new Dbmailer_Captcha_Bank();
			$obj_dbhelper_captcha_bank = new Dbhelper_Captcha_Bank();
			switch ( sanitize_text_field( wp_unslash( $_REQUEST['param'] ) ) ) {// WPCS: CSRF ok, input var ok.
				case 'wizard_captcha_bank':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wp_nonce'] ) ) : '', 'captcha_bank_check_status' ) ) {// WPCS: CSRF ok, input var ok.
						$type             = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';// WPCS: input var ok.
						$user_admin_email = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';// WPCS: input var ok.
						if ( '' === $user_admin_email ) {
							$user_admin_email = get_option( 'admin_email' );
						}
						update_option( 'captcha-bank-admin-email', $user_admin_email );
						update_option( 'captcha-bank-wizard-set-up', $type );
						if ( 'opt_in' === $type ) {
							$plugin_info_captcha_bank = new Plugin_Info_Captcha_Bank();
							global $wp_version;
							$url           = TECH_BANKER_STATS_URL . '/wp-admin/admin-ajax.php';
							$theme_details = array();

							if ( $wp_version >= 3.4 ) {
								$active_theme                   = wp_get_theme();
								$theme_details['theme_name']    = strip_tags( $active_theme->name );
								$theme_details['theme_version'] = strip_tags( $active_theme->version );
								$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
							}

							$plugin_stat_data                     = array();
							$plugin_stat_data['plugin_slug']      = 'captcha-bank';
							$plugin_stat_data['type']             = 'standard_edition';
							$plugin_stat_data['version_number']   = CAPTCHA_BANK_VERSION_NUMBER;
							$plugin_stat_data['status']           = $type;
							$plugin_stat_data['event']            = 'activate';
							$plugin_stat_data['domain_url']       = site_url();
							$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
							$plugin_stat_data['email']            = $user_admin_email;
							$plugin_stat_data['wp_version']       = $wp_version;
							$plugin_stat_data['php_version']      = esc_html( phpversion() );
							$plugin_stat_data['mysql_version']    = $wpdb->db_version();
							$plugin_stat_data['max_input_vars']   = ini_get( 'max_input_vars' );
							$plugin_stat_data['operating_system'] = PHP_OS . '  (' . PHP_INT_SIZE * 8 . ') BIT';
							$plugin_stat_data['php_memory_limit'] = ini_get( 'memory_limit' ) ? ini_get( 'memory_limit' ) : 'N/A';
							$plugin_stat_data['extensions']       = get_loaded_extensions();
							$plugin_stat_data['plugins']          = $plugin_info_captcha_bank->get_plugin_info_captcha_bank();
							$plugin_stat_data['themes']           = $theme_details;
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
										'site_id' => false !== get_option( 'cpb_tech_banker_site_id' ) ? get_option( 'cpb_tech_banker_site_id' ) : '',
										'action'  => 'plugin_analysis_data',
									),
								)
							);
							if ( ! is_wp_error( $response ) ) {
								false !== $response['body'] ? update_option( 'cpb_tech_banker_site_id', $response['body'] ) : '';
							}
						}
					}
					break;

				case 'captcha_type_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bank_file' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $captcha_type_data );// WPCS: input var ok.
						$arithmetic        = isset( $_REQUEST['arithmetic'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['arithmetic'] ) ) ) ) ? json_decode( stripcslashes( wp_unslash( $_REQUEST['arithmetic'] ) ) ) : array() ) : array();// WPCS: CSRF ok, input var ok, sanitization ok.
						$relational        = isset( $_REQUEST['relational'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['relational'] ) ) ) ) ? json_decode( stripcslashes( wp_unslash( $_REQUEST['relational'] ) ) ) : array() ) : array();// WPCS: CSRF ok, input var ok, sanitization ok.
						$arrange           = isset( $_REQUEST['arrange'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['arrange'] ) ) ) ) ? json_decode( stripcslashes( wp_unslash( $_REQUEST['arrange'] ) ) ) : array() ) : array();// WPCS: CSRF ok, input var ok, sanitization ok.
						$checkbox_settings = isset( $_REQUEST['chkBoxArray_settings'] ) ? array_map( 'intval', is_array( json_decode( stripcslashes( wp_unslash( $_REQUEST['chkBoxArray_settings'] ) ) ) ) ? json_decode( stripcslashes( wp_unslash( $_REQUEST['chkBoxArray_settings'] ) ) ) : array() ) : array();// WPCS: CSRF ok, input var ok, sanitization ok.

						$update_display_settings             = array();
						$update_display_settings['settings'] = sanitize_text_field( implode( ',', $checkbox_settings ) );

						$update_text_captcha                              = array();
						$update_text_captcha['captcha_type_text_logical'] = sanitize_text_field( $captcha_type_data['ux_rdl_captcha_type'] );
						$update_text_captcha['captcha_characters']        = intval( $captcha_type_data['ux_txt_character'] );
						$update_text_captcha['captcha_type']              = sanitize_text_field( $captcha_type_data['ux_ddl_alphabets'] );
						$update_text_captcha['text_case']                 = sanitize_text_field( $captcha_type_data['ux_ddl_case'] );
						$update_text_captcha['case_sensitive']            = sanitize_text_field( $captcha_type_data['ux_ddl_case_disable'] );
						$update_text_captcha['captcha_width']             = intval( $captcha_type_data['ux_txt_width'] );
						$update_text_captcha['captcha_height']            = intval( $captcha_type_data['ux_txt_height'] );
						$update_text_captcha['captcha_background']        = sanitize_text_field( $captcha_type_data['ux_ddl_background'] );
						$update_text_captcha['border_style']              = sanitize_text_field( implode( ',', $captcha_type_data['ux_txt_border_style'] ) );
						$update_text_captcha['lines']                     = intval( $captcha_type_data['ux_txt_line'] );
						$update_text_captcha['lines_color']               = sanitize_text_field( $captcha_type_data['ux_txt_color'] );
						$update_text_captcha['noise_level']               = intval( $captcha_type_data['ux_txt_noise_level'] );
						$update_text_captcha['noise_color']               = sanitize_text_field( $captcha_type_data['ux_txt_noise_color'] );
						$update_text_captcha['text_transperancy']         = intval( $captcha_type_data['ux_txt_transperancy'] );
						$update_text_captcha['signature_text']            = 'Captcha Bank';
						$update_text_captcha['signature_style']           = '7,#ff0000';
						$update_text_captcha['signature_font']            = 'Roboto:100';
						$update_text_captcha['text_shadow_color']         = sanitize_text_field( $captcha_type_data['ux_txt_shadow_color'] );
						$update_text_captcha['mathematical_operations']   = sanitize_text_field( $captcha_type_data['ux_ddl_mathematical_operations'] );
						$update_text_captcha['arithmetic_actions']        = sanitize_text_field( implode( ',', $arithmetic ) );
						$update_text_captcha['relational_actions']        = '1,1';
						$update_text_captcha['arrange_order']             = '1,1';
						$update_text_captcha['text_style']                = '24,#000000';
						$update_text_captcha['text_font']                 = 'Roboto';

						$update_text_captcha['recaptcha_site_key']        = esc_attr( $captcha_type_data['ux_txt_site_key'] );
						$update_text_captcha['recaptcha_secret_key']      = esc_attr( $captcha_type_data['ux_txt_secret_key'] );
						$update_text_captcha['recaptcha_key_type']        = esc_attr( $captcha_type_data['ux_ddl_recaptcha_key_type'] );
						$update_text_captcha['recaptcha_data_badge']      = esc_attr( $captcha_type_data['ux_ddl_recaptcha_data_badge'] );
						$update_text_captcha['recaptcha_type']            = esc_attr( $captcha_type_data['ux_ddl_recaptcha_type'] );
						$update_text_captcha['recaptcha_theme']           = esc_attr( $captcha_type_data['ux_ddl_recaptcha_theme_type'] );
						$update_text_captcha['recaptcha_size']            = esc_attr( $captcha_type_data['ux_ddl_recaptcha_size'] );
						$update_text_captcha['recaptcha_language']        = 'en';
						$update_text_captcha['captcha_bank_behind_proxy'] = isset( $captcha_type_data['ux_chk_proxy'] ) ? esc_attr( $captcha_type_data['ux_chk_proxy'] ) : 0;


						$font_value           = isset( $update_text_captcha['text_font'] ) ? $update_text_captcha['text_font'] : '';
						$signature_font_value = isset( $update_text_captcha['signature_font'] ) ? $update_text_captcha['signature_font'] : '';

						if ( 'text_captcha' === $update_text_captcha['captcha_type_text_logical'] ) {
							$font_css           = get_fonts_captcha_bank( 'http://fonts.googleapis.com/css?family=' . captcha_bank_url_encode( $font_value ) );
							$signature_font_css = get_fonts_captcha_bank( 'http://fonts.googleapis.com/css?family=' . captcha_bank_url_encode( $signature_font_value ) );
							preg_match_all( "#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#", $font_css, $match );
							preg_match_all( "#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#", $signature_font_css, $match_signature );
							foreach ( $match as $val => $key ) {
								if ( 0 === $val ) {
									$arr = $key;
								}
							}
							foreach ( $match_signature as $value => $key ) {
								if ( 0 === $value ) {
									$arr_sign = $key;
								}
							}

							$font_url           = get_fonts_captcha_bank( $arr[0] );
							$font_url_signature = get_fonts_captcha_bank( $arr_sign[0] );
							file_put_contents( CAPTCHA_BANK_DIR_PATH . '/fonts/font.ttf', $font_url );// @codingStandardsIgnoreLine.
							file_put_contents( CAPTCHA_BANK_DIR_PATH . '/fonts/font-signature.ttf', $font_url_signature );// @codingStandardsIgnoreLine.
						}
						$where_display_data                = array();
						$update_display_data               = array();
						$where_display_data['meta_key']    = 'display_settings';// WPCS: slow query ok.
						$update_display_data['meta_value'] = maybe_serialize( $update_display_settings );// WPCS: slow query ok.
						$obj_dbhelper_captcha_bank->update_command( captcha_bank_meta(), $update_display_data, $where_display_data );

						$where                     = array();
						$update_data               = array();
						$where['meta_key']         = 'captcha_type';// WPCS: slow query ok.
						$update_data['meta_value'] = maybe_serialize( $update_text_captcha );// WPCS: slow query ok.
						$obj_dbhelper_captcha_bank->update_command( captcha_bank_meta(), $update_data, $where );
					}
					break;

				case 'captcha_whitelist_ip_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bank_whitelist_ip_nonce' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $whitelist_ip_data );// WPCS: input var ok.

						$ip                   = isset( $_REQUEST['ip_address'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['ip_address'] ) ) ) ) : 0;// WPCS: input var ok.
						$start_ip_range       = isset( $_REQUEST['start_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['start_range'] ) ) ) ) : 0;// WPCS: input var ok.
						$end_ip_range         = isset( $_REQUEST['end_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['end_range'] ) ) ) ) : 0;// WPCS: input var ok.
						$multiple_ip_address  = isset( $_REQUEST['multiple_ip_address'] ) ? $_REQUEST['multiple_ip_address'] : 0;// WPCS: input var ok, sanitization ok.
						$multiple_ip          = explode( ',', $multiple_ip_address );
						$ip_address_parent_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'whitelist_ip_addresses'
							)
						);// db call ok; no-cache ok.

						$insert_manage_ip_address              = array();
						$insert_manage_ip_address['type']      = 'whitelist_ip_addresses';
						$insert_manage_ip_address['parent_id'] = $ip_address_parent_id;
						$last_id                               = $obj_dbhelper_captcha_bank->insert_command( captcha_bank_parent(), $insert_manage_ip_address );

						$ip_address_count = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'whitelist_ip_addresses'
							)
						);// db call ok; no-cache ok.
						switch ( $whitelist_ip_data['ux_ddl_whitelist_ip_type'] ) {
							case 'single':
								$ip_exists = false;
								foreach ( $ip_address_count as $data ) {
									$ip_address_unserialize = maybe_unserialize( $data->meta_value );
									$data_start_range       = $ip_address_unserialize['whitelist_ip_start_range'];
									$data_end_range         = $ip_address_unserialize['whitelist_ip_end_range'];
									if ( $ip === $ip_address_unserialize['whitelist_single_ip'] ) {
										echo '1';
										die();
									}
									if ( $ip >= $data_start_range && $ip <= $data_end_range ) {
										$ip_exists = true;
										break;
									}
								}
								$cb_ip_address   = get_ip_address_for_captcha_bank();
								$user_ip_address = '::1' === $cb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cb_ip_address ) );
								if ( true === $ip_exists ) {
									echo 1;
								} elseif ( $user_ip_address === $ip ) {
									echo 2;
								} else {
									$insert_whitelist_ip_address                             = array();
									$insert_whitelist_ip_address['whitelist_ip_type']        = esc_attr( $whitelist_ip_data['ux_ddl_whitelist_ip_type'] );
									$insert_whitelist_ip_address['whitelist_single_ip']      = $ip;
									$insert_whitelist_ip_address['whitelist_ip_start_range'] = 0;
									$insert_whitelist_ip_address['whitelist_ip_end_range']   = 0;
									$insert_whitelist_ip_address['whitelist_multiple_ip']    = 0;
									$insert_whitelist_ip_address['whitelist_ip_comments']    = esc_attr( $whitelist_ip_data['ux_txtarea_comments'] );
									$insert_whitelist_ip_address['date_time']                = CAPTCHA_BANK_LOCAL_TIME;
									$insert_whitelist_ip_address['meta_id']                  = $last_id;

									$insert_data               = array();
									$insert_data['meta_id']    = $last_id;
									$insert_data['meta_key']   = 'whitelist_ip_addresses';// WPCS: slow query ok.
									$insert_data['meta_value'] = maybe_serialize( $insert_whitelist_ip_address );// WPCS: slow query ok.
									$obj_dbhelper_captcha_bank->insert_command( captcha_bank_meta(), $insert_data );
								}
								break;
							case 'range':
								$ip_exists = false;
								foreach ( $ip_address_count as $data ) {
									$ip_address_unserialize = maybe_unserialize( $data->meta_value );
									$data_start_range       = $ip_address_unserialize['whitelist_ip_start_range'];
									$data_end_range         = $ip_address_unserialize['whitelist_ip_end_range'];
									if ( ( $start_ip_range >= $data_start_range && $start_ip_range <= $data_end_range ) || ( $end_ip_range >= $data_start_range && $end_ip_range <= $data_end_range ) ) {
											echo 1;
											$ip_exists = true;
											break;
									} elseif ( ( $start_ip_range <= $data_start_range && $start_ip_range <= $data_end_range ) && ( $end_ip_range >= $data_start_range && $end_ip_range >= $data_end_range ) ) {
											echo 1;
											$ip_exists = true;
											break;
									}
								}
								$cpb_ip_address  = get_ip_address_for_captcha_bank();
								$user_ip_address = '::1' === $cpb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpb_ip_address ) );
								if ( $user_ip_address >= $start_ip_range && $user_ip_address <= $end_ip_range ) {
									echo 2;
									$ip_exists = true;
									break;
								}
								if ( false === $ip_exists ) {
									$insert_whitelist_ip_address                             = array();
									$insert_whitelist_ip_address['whitelist_ip_type']        = esc_attr( $whitelist_ip_data['ux_ddl_whitelist_ip_type'] );
									$insert_whitelist_ip_address['whitelist_single_ip']      = 0;
									$insert_whitelist_ip_address['whitelist_ip_start_range'] = $start_ip_range;
									$insert_whitelist_ip_address['whitelist_ip_end_range']   = $end_ip_range;
									$insert_whitelist_ip_address['whitelist_multiple_ip']    = 0;
									$insert_whitelist_ip_address['whitelist_ip_comments']    = esc_attr( $whitelist_ip_data['ux_txtarea_comments'] );
									$insert_whitelist_ip_address['date_time']                = CAPTCHA_BANK_LOCAL_TIME;
									$insert_whitelist_ip_address['meta_id']                  = $last_id;

									$insert_data               = array();
									$insert_data['meta_id']    = $last_id;
									$insert_data['meta_key']   = 'whitelist_ip_addresses';// WPCS: slow query ok.
									$insert_data['meta_value'] = maybe_serialize( $insert_whitelist_ip_address );// WPCS: slow query ok.
									$obj_dbhelper_captcha_bank->insert_command( captcha_bank_meta(), $insert_data );
								}
								break;
						}
					}
					break;

				case 'captcha_delete_whitelist_ip_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bank_whitelist_ip_delete' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
							$id                 = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;// WPCS: Input var ok.
							$where              = array();
							$where['id']        = $id;
							$where_parent['id'] = $id;
							$obj_dbhelper_captcha_bank->delete_command( captcha_bank_meta(), $where );
							$obj_dbhelper_captcha_bank->delete_command( captcha_bank_parent(), $where_parent );
					}
					break;

				case 'captcha_blocking_options_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bank_options' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $blocking_option_data );// WPCS: input var ok.
						$update_captcha_type = array();
						$where               = array();

						$update_captcha_type['auto_ip_block']                  = sanitize_text_field( $blocking_option_data['ux_ddl_auto_ip'] );
						$update_captcha_type['maximum_login_attempt_in_a_day'] = intval( $blocking_option_data['ux_txt_login'] );
						$update_captcha_type['block_for_time']                 = sanitize_text_field( $blocking_option_data['ux_ddl_blocked_for'] );

						$update_blocking_options_data               = array();
						$where['meta_key']                          = 'blocking_options';// WPCS: slow query ok.
						$update_blocking_options_data['meta_value'] = maybe_serialize( $update_captcha_type );// WPCS: slow query ok.
						$obj_dbhelper_captcha_bank->update_command( captcha_bank_meta(), $update_blocking_options_data, $where );
					}
					break;

				case 'captcha_bank_logs_bulk_block':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bulk_ip_address_block_logs' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						$user_logs_id  = isset( $_REQUEST['data'] ) ? array_map( 'intval', is_array( json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) ) ? json_decode( stripslashes( html_entity_decode( wp_unslash( $_REQUEST['data'] ) ) ) ) : array() ) : array();// WPCS: Input var ok, sanitization ok.
						$get_parent_id = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'advance_security'
							)
						);// db call ok; no-cache ok.
						foreach ( $user_logs_id as $logs_id ) {
							$get_user_data             = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_id = %d', $logs_id
								)
							);// db call ok; no-cache ok.
							$get_user_data_unserialize = maybe_unserialize( $get_user_data );
							$cb_ip_address             = get_ip_address_for_captcha_bank();
							$user_ip_address           = '::1' === $cb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cb_ip_address ) );
							if ( $user_ip_address !== $get_user_data_unserialize['user_ip_address'] ) {
								$insert_bulk_logs_parent              = array();
								$insert_bulk_logs_parent['type']      = 'block_ip_address';
								$insert_bulk_logs_parent['parent_id'] = $get_parent_id;
								$last_id                              = $obj_dbhelper_captcha_bank->insert_command( captcha_bank_parent(), $insert_bulk_logs_parent );

								$insert_bulk_logs_meta                = array();
								$insert_bulk_logs_meta['ip_address']  = isset( $get_user_data_unserialize['user_ip_address'] ) ? intval( $get_user_data_unserialize['user_ip_address'] ) : '';
								$insert_bulk_logs_meta['blocked_for'] = isset( $_REQUEST['time_for'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['time_for'] ) ) : '';// WPCS: input var ok.
								$insert_bulk_logs_meta['location']    = isset( $get_user_data_unserialize['location'] ) ? $get_user_data_unserialize['location'] : '';
								$insert_bulk_logs_meta['comments']    = '';
								$insert_bulk_logs_meta['date_time']   = CAPTCHA_BANK_LOCAL_TIME;
								$insert_bulk_logs_meta['meta_id']     = $last_id;

								$insert_logs_data               = array();
								$insert_logs_data['meta_id']    = $last_id;
								$insert_logs_data['meta_key']   = 'block_ip_address';// WPCS: slow query ok.
								$insert_logs_data['meta_value'] = maybe_serialize( $insert_bulk_logs_meta );// WPCS: slow query ok.
								$obj_dbhelper_captcha_bank->insert_command( captcha_bank_meta(), $insert_logs_data );

								if ( 'permanently' !== $blocked_for ) {
									$cron_name = 'ip_address_unblocker_' . $last_id;
									wp_schedule_captcha_bank( $cron_name, $blocked_for );
								}

								$alert_setup_data              = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
									)
								);// db call ok; no-cache ok.
								$alert_setup_unserialized_data = maybe_unserialize( $alert_setup_data );

								if ( 'enable' === $alert_setup_unserialized_data['email_when_an_ip_address_is_blocked'] ) {
									$template_ip_address_blocked_data             = $wpdb->get_var(
										$wpdb->prepare(
											'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_address_blocked'
										)
									);// db call ok; no-cache ok.
									$template_for_ip_address_blocked_unserialized = maybe_unserialize( $template_ip_address_blocked_data );
									$obj_dbmailer_captcha_bank->ip_address_mail_command_captcha_bank( $template_for_ip_address_blocked_unserialized, $insert_bulk_logs_meta );
								}
							}
						}
					}

					break;

				case 'captcha_manage_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_manage_ip_address' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $advance_security_data );// WPCS: input var ok.
						$ip          = isset( $_REQUEST['ip_address'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['ip_address'] ) ) ) ) : 0;// WPCS: input var ok.
						$ip_address  = long2ip_captcha_bank( $ip );
						$get_ip      = get_ip_location_captcha_bank( $ip_address );
						$blocked_for = isset( $advance_security_data['ux_ddl_hour'] ) ? sanitize_text_field( $advance_security_data['ux_ddl_hour'] ) : '';
						if ('' == $get_ip->country_name && '' == $get_ip->city)
						{
							$location = '';
						}
						else if ('' == $get_ip->country_name)
						{
							$location = '';
						}
						else if ('' == $get_ip->city)
						{
							$location = $get_ip->country_name;
						}
						else
						{
							$location = $get_ip->city . ', ' . $get_ip->country_name;
						}

						$ip_address_count = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'block_ip_address'
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
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'block_ip_range'
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
						$cb_ip_address   = get_ip_address_for_captcha_bank();
						$user_ip_address = '::1' === $cb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cb_ip_address ) );
						if ( true === $ip_exists ) {
							echo 1;
						} elseif ( $user_ip_address === $ip ) {
							echo 2;
						} else {
							$ip_address_parent_id                  = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'advance_security'
								)
							);// db call ok; no-cache ok.
							$insert_manage_ip_address              = array();
							$insert_manage_ip_address['type']      = 'block_ip_address';
							$insert_manage_ip_address['parent_id'] = $ip_address_parent_id;
							$last_id                               = $obj_dbhelper_captcha_bank->insert_command( captcha_bank_parent(), $insert_manage_ip_address );

							$insert_manage_ip_address                = array();
							$insert_manage_ip_address['ip_address']  = $ip;
							$insert_manage_ip_address['blocked_for'] = $blocked_for;
							$insert_manage_ip_address['location']    = $location;
							$insert_manage_ip_address['comments']    = sanitize_text_field( $advance_security_data['ux_txtarea_comments'] );
							$insert_manage_ip_address['date_time']   = CAPTCHA_BANK_LOCAL_TIME;
							$insert_manage_ip_address['meta_id']     = $last_id;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_address';// WPCS: slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_address );// WPCS: slow query ok.
							$obj_dbhelper_captcha_bank->insert_command( captcha_bank_meta(), $insert_data );

							if ( 'permanently' !== $blocked_for ) {
								$cron_name = 'ip_address_unblocker_' . $last_id;
								wp_schedule_captcha_bank( $cron_name, $blocked_for );
							}

							$alert_setup_data              = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'alert_setup'
								)
							);// db call ok; no-cache ok.
							$alert_setup_unserialized_data = maybe_unserialize( $alert_setup_data );

							if ( 'enable' === $alert_setup_unserialized_data['email_when_an_ip_address_is_blocked'] ) {
								$template_ip_address_blocked_data             = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_address_blocked'
									)
								);// db call ok; no-cache ok.
								$template_for_ip_address_blocked_unserialized = maybe_unserialize( $template_ip_address_blocked_data );
								$obj_dbmailer_captcha_bank->ip_address_mail_command_captcha_bank( $template_for_ip_address_blocked_unserialized, $insert_manage_ip_address );
							}
						}
					}
					break;

				case 'captcha_delete_ip_address_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_manage_ip_address_delete' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						$where                           = array();
						$where_parent                    = array();
						$id                              = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;// WPCS: input var ok.
						$where_parent['id']              = $id;
						$where['meta_id']                = $id;
						$cron_name                       = 'ip_address_unblocker_' . $where['meta_id'];
						$alert_setup_data_array          = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'alert_setup'
							)
						);// db call ok; no-cache ok.
						$email_when_ip_address_unblocked = maybe_unserialize( $alert_setup_data_array );

						if ( 'enable' === $email_when_ip_address_unblocked['email_when_an_ip_address_is_unblocked'] ) {
							$send_email                = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key= %s', 'template_for_ip_address_unblocked'
								)
							);// db call ok; no-cache ok.
							$template_for_ip_unblocked = maybe_unserialize( $send_email );

							$get_data                               = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_id=%d AND meta_key=%s', $where['meta_id'], 'block_ip_address'
								)
							);// db call ok; no-cache ok.
							$ip_address_unblocked_unserialized_data = maybe_unserialize( $get_data );
							$obj_dbmailer_captcha_bank->ip_address_mail_command_captcha_bank( $template_for_ip_unblocked, $ip_address_unblocked_unserialized_data );
						}
						wp_unschedule_captcha_bank( $cron_name );
						$obj_dbhelper_captcha_bank->delete_command( captcha_bank_meta(), $where );
						$obj_dbhelper_captcha_bank->delete_command( captcha_bank_parent(), $where_parent );
					}
					break;

				case 'captcha_manage_ip_ranges_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_manage_ip_ranges' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '', $ip_range_data );// WPCS: input var ok.
						$start_ip_range = isset( $_REQUEST['start_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['start_range'] ) ) ) ) : 0;// WPCS: input var ok.
						$end_ip_range   = isset( $_REQUEST['end_range'] ) ? sprintf( '%u', ip2long( sanitize_text_field( wp_unslash( $_REQUEST['end_range'] ) ) ) ) : 0;// WPCS: input var ok.
						$blocked_for    = isset( $ip_range_data['ux_ddl_blocked'] ) ? sanitize_text_field( $ip_range_data['ux_ddl_blocked'] ) : '';
						$get_ip         = get_ip_location_captcha_bank( long2ip_captcha_bank( $start_ip_range ) );
						if ('' == $get_ip->country_name && '' == $get_ip->city)
						{
							$location = '';
						}
						else if ('' == $get_ip->country_name)
						{
							$location = '';
						}
						else if ('' == $get_ip->city)
						{
							$location = $get_ip->country_name;
						}
						else
						{
							$location = $get_ip->city . ', ' . $get_ip->country_name;
						}

						$ip_address_range_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'block_ip_range'
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
						$cpb_ip_address  = get_ip_address_for_captcha_bank();
						$user_ip_address = '::1' === $cpb_ip_address ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $cpb_ip_address ) );
						if ( $user_ip_address >= $start_ip_range && $user_ip_address <= $end_ip_range ) {
							echo 2;
							$ip_exists = true;
							break;
						}
						if ( false === $ip_exists ) {
							$ip_range_parent_id                  = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT id FROM ' . $wpdb->prefix . 'captcha_bank WHERE type=%s', 'advance_security'
								)
							);// db call ok; no-cache ok.
							$insert_manage_ip_range              = array();
							$insert_manage_ip_range['type']      = 'block_ip_range';
							$insert_manage_ip_range['parent_id'] = $ip_range_parent_id;
							$last_id                             = $obj_dbhelper_captcha_bank->insert_command( captcha_bank_parent(), $insert_manage_ip_range );

							$insert_manage_ip_range                = array();
							$insert_manage_ip_range['ip_range']    = $start_ip_range . ',' . $end_ip_range;
							$insert_manage_ip_range['blocked_for'] = $blocked_for;
							$insert_manage_ip_range['location']    = $location;
							$insert_manage_ip_range['comments']    = sanitize_text_field( $ip_range_data['ux_txtarea_manage_ip_range'] );
							$insert_manage_ip_range['date_time']   = CAPTCHA_BANK_LOCAL_TIME;
							$insert_manage_ip_range['meta_id']     = $last_id;

							$insert_data               = array();
							$insert_data['meta_id']    = $last_id;
							$insert_data['meta_key']   = 'block_ip_range';// WPCS: slow query ok.
							$insert_data['meta_value'] = maybe_serialize( $insert_manage_ip_range );// WPCS: slow query ok.
							$obj_dbhelper_captcha_bank->insert_command( captcha_bank_meta(), $insert_data );

							if ( 'permanently' !== $blocked_for ) {
								$cron_name = 'ip_range_unblocker_' . $last_id;
								wp_schedule_captcha_bank( $cron_name, $blocked_for );
							}

							$email_when_ip_range_blocked            = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'alert_setup'
								)
							);// db call ok; no-cache ok.
							$email_for_ip_range_blocked_unserialize = maybe_unserialize( $email_when_ip_range_blocked );

							if ( 'enable' === $email_for_ip_range_blocked_unserialize['email_when_an_ip_range_is_blocked'] ) {
								$template_for_ip_range_blocked             = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_range_blocked'
									)
								);// db call ok; no-cache ok.
								$template_for_ip_range_blocked_unserialize = maybe_unserialize( $template_for_ip_range_blocked );
								$obj_dbmailer_captcha_bank->ip_range_mail_command_captcha_bank( $template_for_ip_range_blocked_unserialize, $insert_manage_ip_range );
							}
						}
					}
					break;

				case 'captcha_delete_ip_range_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_manage_ip_ranges_delete' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						$where                                    = array();
						$where_parent                             = array();
						$id                                       = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;// WPCS: input var ok.
						$where_parent['id']                       = $id;
						$where['meta_id']                         = $id;
						$cron_name                                = 'ip_range_unblocker_' . $where['meta_id'];
						$email_when_ip_range_unblocked            = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'alert_setup'
							)
						);// db call ok; no-cache ok.
						$email_for_ip_range_unblocked_unserialize = maybe_unserialize( $email_when_ip_range_unblocked );

						if ( 'enable' === $email_for_ip_range_unblocked_unserialize['email_when_an_ip_range_is_unblocked'] ) {
							$template_for_ip_range_unblocked             = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', 'template_for_ip_range_unblocked'
								)
							);// db call ok; no-cache ok.
							$template_for_ip_range_unblocked_unserialize = maybe_unserialize( $template_for_ip_range_unblocked );
							$ip_range_unblocked_data                     = $wpdb->get_var(
								$wpdb->prepare(
									'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_id=%d AND meta_key=%s', $where['meta_id'], 'block_ip_range'
								)
							);// db call ok; no-cache ok.
							$ip_range_unblocked_data_unserialize         = maybe_unserialize( $ip_range_unblocked_data );
							$obj_dbmailer_captcha_bank->ip_range_mail_command_captcha_bank( $template_for_ip_range_unblocked_unserialize, $ip_range_unblocked_data_unserialize );
						}
						wp_unschedule_captcha_bank( $cron_name );
						$obj_dbhelper_captcha_bank->delete_command( captcha_bank_meta(), $where );
						$obj_dbhelper_captcha_bank->delete_command( captcha_bank_parent(), $where_parent );
					}
					break;

				case 'captcha_type_email_templates_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_type_email_templates' ) ) {// WPCS: CSRF ok, input var ok, sanitization ok.
						$templates            = isset( $_REQUEST['data'] ) ? sanitize_text_field( wp_unslash( filter_input( INPUT_POST, 'data' ) ) ) : '';// WPCS: input var ok.
						$email_templates_data = $wpdb->get_results(
							$wpdb->prepare(
								'SELECT * FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key=%s', $templates
							)
						);// db call ok; no-cache ok.

						$email_template_data_unseralize = get_captcha_bank_unserialize_data( $email_templates_data );
						echo wp_json_encode( $email_template_data_unseralize );
					}
					break;

				case 'captcha_bank_other_settings_module':
					if ( wp_verify_nonce( isset( $_REQUEST['_wp_nonce'] ) ? wp_unslash( $_REQUEST['_wp_nonce'] ) : '', 'captcha_bank_other_settings' ) ) {// WPCS: input var ok, sanitization ok.
						parse_str( isset( $_REQUEST['data'] ) ? base64_decode( wp_unslash( $_REQUEST['data'] ) ) : '', $update_array );// WPCS: input var ok, sanitization ok.
						$update_captcha_type = array();
						$where               = array();

						$update_captcha_type['automatic_plugin_updates']   = 'disable';
						$update_captcha_type['remove_tables_at_uninstall'] = sanitize_text_field( $update_array['ux_ddl_remove_tables'] );
						$update_captcha_type['ip_address_fetching_method'] = sanitize_text_field( $update_array['ux_ddl_ip_address_fetching_method'] );

						$update_data               = array();
						$where['meta_key']         = 'other_settings';// WPCS: slow query ok.
						$update_data['meta_value'] = maybe_serialize( $update_captcha_type );// WPCS: slow query ok.
						$obj_dbhelper_captcha_bank->update_command( captcha_bank_meta(), $update_data, $where );
					}
					break;
			}
			die();
		}
	}
}

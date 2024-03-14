<?php

class arflitemaincontroller {

	function __construct() {

		global $arflite_is_active_cornorstone;

		//add_action( 'admin_menu', array( $this, 'arflitemenu' ) );

		add_action( 'admin_head', array( $this, 'arflite_menu_css' ) );

		add_filter( 'plugin_action_links_arforms-form-builder/arforms-form-builder.php', array( $this, 'arflite_settings_link' ), 10, 2 );

		add_action( 'wp_head', array( $this, 'arflite_front_head' ), 1 );

		add_action( 'before_arformslite_editor_init', array( $this, 'arflite_update_auto_increment_after_install' ), 11, 0 );

		add_action( 'wp_head', array( $this, 'arflite_front_head_js' ), 1, 0 );

		register_activation_hook( ARFLITE_FORMPATH . '/arforms-form-builder.php', array( $this, 'arfliteforms_check_network_activation' ) );

		add_action( 'init', array( $this, 'arflite_parse_standalone_request' ) );

		add_action( 'init', array( $this, 'arflite_start_session' ), 1 );

		add_shortcode( 'ARFormslite', array( $this, 'arflite_get_form_shortcode_legacy' ) );

		global $arformsmain;

		if( !empty( $arformsmain) && (!$arformsmain->arforms_is_pro_active() || ($arformsmain->arforms_is_pro_active() && version_compare( $arformsmain->arforms_get_premium_version(), '6.1', '>='))) ){
			add_shortcode( 'ARForms', array( $this, 'arflite_get_form_shortcode' ) );
		}

		add_filter( 'widget_text', array( $this, 'arflite_widget_text_filter' ), 9 );

		add_filter( 'widget_text', array( $this, 'arflite_widget_text_filter_popup' ), 9 );

		add_action( 'arflitestandaloneroute', array( $this, 'arflite_globalstandalone_route' ), 10, 2 );

		add_filter( 'upgrader_pre_install', array( $this, 'arflite_backup' ), 10, 2 );

		add_action( 'admin_init', array( $this, 'arflite_upgrade_data' ) );

		//add_action( 'init', array( $this, 'arfliteafterinstall_front' ) );

		add_action( 'admin_init', array( $this, 'arflite_db_check' ) );

		add_filter( 'the_content', array( $this, 'arflite_modify_the_content' ), 10000 );

		add_filter( 'widget_text', array( $this, 'arflite_modify_the_content' ), 10000 );

		add_action( 'admin_head', array( $this, 'arflite_hide_update_notice_to_all_admin_users' ), 10000 );

		/* add_action( 'admin_init', array( $this, 'arflite_export_form_data' ) ); */

		add_action( 'wp_head', array( $this, 'arflite_front_assets' ), 1, 0 );

		add_action( 'print_admin_scripts', array( $this, 'arflite_print_all_admin_scripts' ) );

		add_action( 'admin_footer', array( $this, 'arflite_add_new_version_release_note' ), 1 );
		add_action( 'wp_ajax_arflite_dont_show_upgrade_notice', array( $this, 'arflite_dont_show_upgrade_notice' ), 1 );

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require ABSPATH . '/wp-admin/includes/plugin.php';
		}
		if ( $arflite_is_active_cornorstone ) {
			add_action( 'cornerstone_register_elements', array( $this, 'arformslite_cs_register_element' ) );
			add_filter( 'cornerstone_icon_map', array( $this, 'arformslite_cs_icon_map' ) );
		}

		if ( is_plugin_active( 'wp-rocket/wp-rocket.php' ) && ! is_admin() ) {
			add_filter( 'script_loader_tag', array( $this, 'arflite_prevent_rocket_loader_script' ), 10, 2 );
		}

		if ( is_admin() ) {
			add_filter( 'script_loader_tag', array( $this, 'arflite_defer_attribute_to_js_for_editor' ), 10, 2 );
		}

		if ( ! is_admin() ) {
			add_filter( 'script_loader_tag', array( $this, 'arflite_modify_rocket_script_clf' ), 10, 2 );
		}

		add_action( 'user_register', array( $this, 'arflite_add_capabilities_to_new_user' ) );

		add_action( 'admin_init', array( $this, 'arflite_plugin_add_suggested_privacy_content' ), 20 );

		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
			add_action( 'wp_print_scripts', array( $this, 'arflite_dequeue_elementor_script' ), 100 );
			add_action( 'arflite_load_assets_for_elementor', array( $this, 'arflite_load_assets_for_elementor_callback' ), 10, 2 );
		}

		add_filter( 'upload_mimes', array( $this, 'arflite_custom_mime_types' ) );

		add_filter( 'plugin_action_links', array( $this, 'arflite_plugin_action_links' ), 10, 2 );

		add_action( 'admin_footer', array( $this, 'arflite_deactivate_feedback_popup' ), 1 );

		add_action( 'wp_ajax_arflite_deactivate_plugin', array( $this, 'arflite_deactivate_plugin_func' ) );

		add_action( 'admin_init', array( $this, 'arflite_display_sale_popup' ) );

		add_filter( 'cron_schedules', array( $this, 'arflite_add_cron_schedule' ) );

		add_action( 'arflite_display_sale_upgrade_popup', array( $this, 'arflite_enable_sale_popup' ) );

		add_action( 'wp_ajax_arflite_disable_sale_popup', array( $this, 'arflite_disable_sale_popup' ) );

		add_action( 'admin_notices', array( $this, 'arflite_display_notice_for_rating' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'arflite_enqueue_notice_assets' ) );

		add_action( 'wp_ajax_arflite_dismiss_rate_notice', array( $this, 'arflite_reset_ratenow_notice' ) );

		add_action( 'wp_ajax_arflite_dismiss_rate_notice_no_display', array( $this, 'arflite_reset_ratenow_notice_never' ) );

		add_action( 'arflite_display_ratenow_popup', array( $this, 'arflite_display_ratenow_popup_callback' ) );

		add_action( 'login_footer', array( $this, 'arflite_login_footer' ) );

		add_action( 'wp_ajax_arf_regenerate_nonces', array( $this, 'arf_regenerate_nonces' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'arflite_set_deactivation_js') );
		add_action( 'admin_enqueue_scripts', array( $this, 'arflite_set_deactivation_css') );

		global $arformsmain;
		if( ! $arformsmain->arforms_is_pro_active() ){
			add_filter( 'login_redirect', array( $this, 'arflite_check_page_nonce_url' ), 10, 3 );
			add_action( 'admin_init', array( $this, 'arflite_redirect_with_nonce_url' ) );

			
			add_action( 'admin_enqueue_scripts', array( $this, 'arflite_set_js' ), 12 );
			add_action( 'admin_enqueue_scripts', array( $this, 'arflite_set_css' ), 11 );
			add_action( 'admin_footer', array( $this, 'arflite_wp_enqeue_footer_script' ), 10 );
			
			add_action( 'admin_init', array( $this, 'arflite_admin_js' ), 11 );
			add_action( 'admin_footer', array( $this, 'arflite_pro_feature_popup' ) );
			add_action( 'admin_footer', array( $this, 'arflite_display_sale_popup_callback' ) );
			
		}
		add_action('arforms_send_anonymous_data',array($this,'arforms_send_anonymous_data_cron'));
	}

		/**
         * Send anonymous data through cron
         *
         * @return void
         */
        function arforms_send_anonymous_data_cron() {

			global $arformsmain;

			$arforms_send_anonymous_data = $arformsmain->arforms_get_settings('anonymous_data','general_settings'); 

			if( $arforms_send_anonymous_data == true ){
				
				global $wpdb, $wp_version;

				$activated_addon = $inactivated_addon = $active_plugins_arr =  $inactive_plugin_arr = array();

                $arforms_lite_version = $arforms_lite_installation_date = $arforms_pro_version = $arfoms_pro_installation_date =
                $home_url = $admin_url = $site_timezone = $site_locale = '';

				$arforms_lite_version = get_option('arflite_db_version');
				$arforms_lite_version = !empty($arforms_lite_version) ? $arforms_lite_version : '';
				$arforms_lite_installation_date = get_option('arformslite_install_date');
				$arforms_lite_installation_date = !empty($arforms_lite_installation_date) ? $arforms_lite_installation_date : '';
				$arforms_pro_version = get_option('arf_db_version');
				$arforms_pro_version = !empty($arforms_pro_version) ? $arforms_pro_version : '';
				$arforms_pro_installation_date = get_option('arformspro_install_date');
				$arforms_pro_installation_date = !empty($arforms_pro_installation_date) ? $arforms_pro_installation_date : '';
				$home_url = home_url();
                $admin_url = admin_url();
                $site_locale = get_locale();
                $site_locale = !empty($site_locale) ? $site_locale : '';
                $site_timezone = wp_timezone_string();
				$server_information = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field($_SERVER['SERVER_SOFTWARE']) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
				$my_theme   = wp_get_theme();
                $theme_data = $my_theme->get('Name').'  ('.$my_theme->get('Version').' )';
                $is_multisite = is_multisite() ? 'Yes' : 'NO';

				$plugin_list    = get_plugins();                               
                $active_plugins = get_option('active_plugins');
				$arforms_lite_download_automatic  = get_option('arforms_lite_download_automatic');
				$arforms_lite_download_automatic  = !empty( $arforms_lite_download_automatic ) ? $arforms_lite_download_automatic : '';

				foreach ( $plugin_list as $key => $plugin_detail ) {
                    $is_active = in_array($key, $active_plugins);
                    if ($is_active == 1 ) {
                        $name      = substr($key, 0, strpos($key, '/'));
                        $active_plugins_arr[] = array(            
                            $plugin_detail['Name'] => $plugin_detail['Version']
                        );
                    } else {
                        $inactive_plugin_arr[]  = array(            
                            $plugin_detail['Name'] => $plugin_detail['Version']
                        );
                    }
                }

				$arforms_addon_url = 'https://www.arformsplugin.com/addonlist/arf_addon_api_details.php';
                $arforms_addons_res = wp_remote_post(
                    $arforms_addon_url,
                    array(
                        'method'    => 'POST',
                        'timeout'   => 45,
                        'sslverify' => false,
                        'body'      => array(
                            'arforms_addon_list' => 1,
                        ),
                    )
                );

                $active_addon_list = $inactive_addon_list =  array();
				if ( ! is_wp_error( $arforms_addons_res ) ) {
					$arforms_body_res = json_decode( $arforms_addons_res['body'], true );
                    if ( ! empty( $arforms_body_res ) ) {
						/* $arforms_body_res = json_decode( $arforms_body_res, true ); */
                        foreach ( $arforms_body_res as $arforms_body_key => $arforms_body_val ) {                            
                            if(!empty($arforms_body_val['plugin_installer'])) {
                                if(file_exists( WP_PLUGIN_DIR . '/'.$arforms_body_val['plugin_installer'])) {        
                                    $is_addon_active = is_plugin_active($arforms_body_val['plugin_installer']);
                                    if($is_addon_active) {
                                        $active_addon_list[$arforms_body_val['arf_plugin_name']] = !empty( $arforms_body_val['addon_version'] ) ? $arforms_body_val['addon_version'] : '';
                                    } else {
                                        $inactive_addon_list[$arforms_body_val['arf_plugin_name']] = !empty( $arforms_body_val['addon_version'] ) ? $arforms_body_val['addon_version'] : '';
                                    } 
                                }
                            }
                        }
                    }
                }

				$arforms_anonymous_data = array(
                    'php_version'                             => phpversion(),
                    'arforms_lite_version'                    => $arforms_lite_version,
                    'arforms_lite_installation_date'    => $arforms_lite_installation_date,
                    'arforms_pro_version'                     => $arforms_pro_version,
                    'arforms_pro_installation_date'     => $arforms_pro_installation_date,
                    'wp_version'                              => $wp_version,
                    'server_information'                      => $server_information,
                    'is_multisite'                            => $is_multisite,
                    'theme_data'                              => $theme_data,
                    'home_url'                                => $home_url,
                    'admin_url'                               => $admin_url,
                    'active_plugin_list'                      => wp_json_encode($active_plugins_arr),
                    'inactivate_plugin_list'                  => wp_json_encode($inactive_plugin_arr),
                    'site_locale'                             => $site_locale,
                    'site_timezone'                           => $site_timezone,
					'activated_addons'                        => wp_json_encode($active_addon_list),
                    'inactive_addons'                         => wp_json_encode($inactive_addon_list),
					'arforms_lite_download_automatic'         => $arforms_lite_download_automatic,
				);

				$url = '';
                $url = 'https://www.arformsplugin.com/arf_misc/arforms_tracking_usage.php';
                $response = wp_remote_post(
                    $url,
                    array(
                    	'timeout' => 500,
                    	'body'    => array( 'arf_anonymous_data' =>  wp_json_encode($arforms_anonymous_data)),
                    )
                );
			}
		}

	function arflite_check_page_nonce_url( $redirect_to, $requested_redirect_to, $user ) {

		if ( ! empty( $user->ID ) && ! empty( $redirect_to ) ) {
			$url_parts = explode( '?', $redirect_to );
			if ( ! empty( $url_parts[1] ) ) {
				$query_params = $url_parts[1];

				$query_parts = explode( '&', $query_params );

				foreach ( $query_parts as $qry_part ) {
					if ( preg_match( '/(arflite_settings_nonce\=(.*))/', $qry_part ) ) {
						$new_nonce = wp_create_nonce( 'arflite_settings_nonce' );

						$new_redirect_url = preg_replace( '/(arflite_settings_nonce\=(.*))/', '', $redirect_to );

						$redirect_to = $new_redirect_url;
						break;
					} elseif ( preg_match( '/(arflite_page_nonce\=(.*))/', $qry_part ) ) {
						$new_nonce = wp_create_nonce( 'arflite_page_nonce' );

						$new_redirect_url = preg_replace( '/(arflite_page_nonce\=(.*))/', '', $redirect_to );

						$redirect_to = $new_redirect_url;
						break;
					} elseif ( preg_match( '/(arflite_entries_nonce\=(.*))/', $qry_part ) ) {
						$new_nonce = wp_create_nonce( 'arflite_entries_nonce' );

						$new_redirect_url = preg_replace( '/(arflite_entries_nonce\=(.*))/', '', $redirect_to );

						$redirect_to = $new_redirect_url;
						break;
					} elseif ( preg_match( '/(arflite_import_export_nonce\=(.*))/', $qry_part ) ) {
						$new_nonce = wp_create_nonce( 'arflite_import_export_nonce' );

						$new_redirect_url = preg_replace( '/(arflite_import_export_nonce\=(.*))/', '', $redirect_to );

						$redirect_to = $new_redirect_url;
						break;
					}
				}
			}
		}
		return $redirect_to;
	}

	function arflite_check_page_nonce( $user_login, $user ) {

		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = esc_url_raw( $_REQUEST['redirect_to'] );

			$url_parts = explode( '?', $redirect_to );

			if ( ! empty( $url_parts[1] ) ) {
				$query_params = $url_parts[1];

				$query_parts = explode( '&', $query_params );

				foreach ( $query_parts as $qry_part ) {
					if ( preg_match( '/(arflite_settings_nonce\=(.*))/', $qry_part ) ) {
						$new_nonce = wp_create_nonce( 'arflite_settings_nonce' );

						$new_redirect_url = preg_replace( '/(arflite_settings_nonce\=(.*))/', 'arflite_settings_nonce=' . $new_nonce, $redirect_to );

						wp_redirect( $new_redirect_url );
						die;
					}
				}
			}
		}

	}

	function arflite_display_sale_popup_callback() {

		if ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) {
			$sale_popup = '
                <input type="hidden" id="arflite_ajaxurl_popup" value="' . admin_url( 'admin-ajax.php' ) . '" />
                <input type="hidden" id="arflite_enable_sale_popup" value="' . get_option( 'arflite_display_bf_sale_popup' ) . '" />
                <div class="arflite_black_friday_sale_popup_wrapper">
                    <div class="arflite_black_friday_sale_popup_container">
                        <span class="arflite_black_friday_sale_close_btn" id="arflite_black_friday_sale_close_btn">
                            <i class="fa fa-times fa-lg"></i>
                        </span>
                        <span class="arflite_bf_sale_title">UPGRADE TO PREMIUM VERSION</span>
                        <span class="arflite_bf_sale_text">
                            BLACK FRIDAY SALE
                        </span>
                        <span class="arflite_bf_discount_price">FLAT 50% OFF PREMIUM</span>
                        <span class="arflite_bf_limited_text_wrapper">LIMITED TIME OFFER</span>
                        <a class="arflite_bf_popup_btn" href="https://1.envato.market/rdeQD" target="_blank">UPGRADE TO PREMIUM</a>
                    </div>
                </div>';

			echo wp_kses(
				$sale_popup,
				array(
					'input' => array(
						'type'  => array(),
						'id'    => array(),
						'value' => array(),
					),
					'div'   => array(
						'class' => array(),
						'id'    => array(),
					),
					'span'  => array(
						'class' => array(),
						'id'    => array(),
					),
					'i'     => array(
						'class' => array(),
					),
					'a'     => array(
						'class'  => array(),
						'href'   => array(),
						'target' => array(),
					),
				)
			);
		}

	}

	function arflite_login_footer() {
		$arflite_script                  = '<script type="text/javascript" data-cfasync="false">';
			$arflite_script             .= 'jQuery(document).ready(function(){';
				$arflite_script         .= 'if( typeof window.parent.adminpage != "undefined" && window.parent.adminpage == "toplevel_page_ARForms"){';
					$arflite_script     .= 'if( document.getElementById("loginform") == null && window.parent.arflite_regenerate_nonce != null ){';
						$arflite_script .= ' window.parent.arflite_regenerate_nonce(); ';
					$arflite_script     .= '}';
				$arflite_script         .= '} else if( window.opener != null && typeof window.opener.adminpage != "undefined" && window.opener.adminpage == "toplevel_page_ARForms" ){';
					$arflite_script     .= 'if( document.getElementById("loginform") == null && window.opener != null && window.opener.arflite_regenerate_nonce != null ){';
						$arflite_script .= ' window.opener.arflite_regenerate_nonce(); ';
						$arflite_script .= ' window.close() ';
					$arflite_script     .= '}';
				$arflite_script         .= '}';
			$arflite_script             .= '});';
		$arflite_script                 .= '</script>';

		echo $arflite_script; //phpcs:ignore
	}

	function arf_regenerate_nonces() {
		echo json_encode(
			array(
				'arflite_page_nonce'       => wp_create_nonce( 'arflite_page_nonce' ),
				'arflite_validation_nonce' => wp_create_nonce( 'arflite_wp_nonce' ),
			)
		);
		die;
	}

	function arflite_enable_sale_popup() {
		update_option( 'arflite_display_bf_sale_popup', 1 );
	}

	function arflite_disable_sale_popup() {
		update_option( 'arflite_display_bf_sale_popup', 0 );
	}

	function arflite_add_cron_schedule( $schedules ) {
		$schedules['every_twelve_hours'] = array(
			'interval' => 43200,
			'display'  => __( 'Every 12 hours', 'arforms-form-builder' ),
		);

		return $schedules;
	}

	function arflite_display_sale_popup() {
		if ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) {
			if ( current_time( 'timestamp' ) < strtotime( '2020-12-06' ) ) {
				if ( ! wp_next_scheduled( 'arflite_display_sale_upgrade_popup' ) ) {
					wp_schedule_event( time(), 'every_twelve_hours', 'arflite_display_sale_upgrade_popup' );
				}
			} else {
				update_option( 'arflite_display_bf_sale_popup', 0 );
			}
		}
	}


	function arflite_redirect_with_nonce_url() {

		if ( is_admin() ) {

			if ( isset( $_GET['page'] ) && 'ARForms' == sanitize_text_field( $_GET['page'] ) ) {

				if ( ! isset( $_GET['arflite_page_nonce'] ) ) {

					if(isset($_GET['upgrade-to-pro']) && $_GET['upgrade-to-pro'] == 'yes')
					{
						$url = admin_url( 'admin.php?page=ARForms&upgrade-to-pro=yes&arflite_page_nonce=' . wp_create_nonce( 'arflite_page_nonce' ) );
					}
					else
					{
						$url = admin_url( 'admin.php?page=ARForms&arflite_page_nonce=' . wp_create_nonce( 'arflite_page_nonce' ) );
					}

					if ( isset( $_GET['arfaction'] ) ) {
						$query_args = '';
						unset( $_GET['page'] );
						foreach ( $_GET as $k => $v ) {
							$query_args .= '&' . $k . '=' . $v;
						}
						$url .= $query_args;
					}
					wp_redirect( $url );
					die;

				} elseif ( isset( $_GET['arflite_page_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_GET['arflite_page_nonce'] ), 'arflite_page_nonce' ) ) {
					if ( current_user_can( 'arfviewforms' ) ) {

						if(isset($_GET['upgrade-to-pro']) && $_GET['upgrade-to-pro'] == 'yes')
						{
							$url = admin_url( 'admin.php?page=ARForms&upgrade-to-pro=yes&arflite_page_nonce=' . wp_create_nonce( 'arflite_page_nonce' ) );
						}
						else
						{
							$url = admin_url( 'admin.php?page=ARForms&arflite_page_nonce=' . wp_create_nonce( 'arflite_page_nonce' ) );
						}

						if ( isset( $_GET['arfaction'] ) ) {
							$query_args = '';
							unset( $_GET['page'] );
							foreach ( $_GET as $k => $v ) {
								if ( $k == 'arflite_page_nonce' ) {
									continue;
								}
								$query_args .= '&' . $k . '=' . $v;
							}
							$url .= $query_args;
						}
						wp_redirect( $url );
						die;

					} else {
						wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
					}
				} elseif ( isset( $_GET['arflite_page_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_GET['arflite_page_nonce'] ), 'arflite_page_nonce' ) && isset( $_GET['arfaction'] ) && 'edit' == sanitize_text_field( $_GET['arfaction'] ) ) {
					global $wpdb,$tbl_arf_forms;

					$form_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : '';

					$url = admin_url( 'admin.php?page=ARForms&arflite_page_nonce=' . wp_create_nonce( 'arflite_page_nonce' ) );

					if ( '' == $form_id ) {
						wp_redirect( $url );
						die;
					}

					$total = $wpdb->get_var( $wpdb->prepare( 'SELECT count(*) as total FROM `' . $tbl_arf_forms . '` WHERE id = %d', $form_id ) ); //phpcs:ignore

					if ( $total < 1 ) {
						wp_redirect( $url );
						die;
					}
				}
			} elseif ( isset( $_GET['page'] ) && 'ARForms-entries' == sanitize_text_field( $_GET['page'] ) ) {

				if ( ! isset( $_GET['arflite_entries_nonce'] ) ) {

					$url = admin_url( 'admin.php?page=ARForms-entries&arflite_entries_nonce=' . wp_create_nonce( 'arflite_entries_nonce' ) );
					if ( isset( $_GET['form'] ) ) {
						$query_args = '';
						unset( $_GET['page'] );
						foreach ( $_GET as $k => $v ) {
							if ( 'arflite_entries_nonce' == $k ) {
								continue;
							}
							$query_args .= '&' . $k . '=' . $v;
						}
						$url .= $query_args;
					}
					wp_redirect( $url );
					die;
				} elseif ( isset( $_GET['arflite_entries_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_GET['arflite_entries_nonce'] ), 'arflite_entries_nonce' ) ) {
					if ( current_user_can( 'arfviewentries' ) ) {
						$url = admin_url( 'admin.php?page=ARForms-entries&arflite_entries_nonce=' . wp_create_nonce( 'arflite_entries_nonce' ) );

						if ( isset( $_GET['arfaction'] ) ) {
							$query_args = '';
							unset( $_GET['page'] );
							foreach ( $_GET as $k => $v ) {
								if ( 'arflite_entries_nonce' == $k ) {
									continue;
								}
								$query_args .= '&' . $k . '=' . $v;
							}
							$url .= $query_args;
						}
						wp_redirect( $url );
						die;
					} else {
						wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
					}
				}
			} elseif ( isset( $_GET['page'] ) && 'ARForms-settings' == sanitize_text_field( $_GET['page'] ) ) {

				if ( ! isset( $_GET['arflite_settings_nonce'] ) ) {
					$url = admin_url( 'admin.php?page=ARForms-settings&arflite_settings_nonce=' . wp_create_nonce( 'arflite_settings_nonce' ) );

					wp_redirect( $url );
					die;
				} elseif ( isset( $_GET['arflite_settings_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_GET['arflite_settings_nonce'] ), 'arflite_settings_nonce' ) ) {
					if ( current_user_can( 'arfchangesettings' ) ) {
						$url = admin_url( 'admin.php?page=ARForms-settings&arflite_settings_nonce=' . wp_create_nonce( 'arflite_settings_nonce' ) );

						if ( isset( $_GET['arfaction'] ) ) {
							$query_args = '';
							unset( $_GET['page'] );
							foreach ( $_GET as $k => $v ) {
								if ( 'arflite_settings_nonce' == $k ) {
									continue;
								}
								$query_args .= '&' . $k . '=' . $v;
							}
							$url .= $query_args;
						}
						wp_redirect( $url );
						die;
					} else {
						wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
					}
				}
			} elseif ( isset( $_GET['page'] ) && 'ARForms-import-export' == sanitize_text_field( $_GET['page'] ) ) {
				if ( ! isset( $_GET['arflite_import_export_nonce'] ) ) {
					$url = admin_url( 'admin.php?page=ARForms-import-export&arflite_import_export_nonce=' . wp_create_nonce( 'arflite_import_export_nonce' ) );

					wp_redirect( $url );
					die;
				} elseif ( isset( $_GET['arflite_import_export_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_GET['arflite_import_export_nonce'] ), 'arflite_import_export_nonce' ) ) {
					if ( current_user_can( 'arfchangesettings' ) ) {
						$url = admin_url( 'admin.php?page=ARForms-import-export&arflite_import_export_nonce=' . wp_create_nonce( 'arflite_import_export_nonce' ) );

						if ( isset( $_GET['arfaction'] ) ) {
							$query_args = '';
							unset( $_GET['page'] );
							foreach ( $_GET as $k => $v ) {
								if ( 'arflite_import_export_nonce' == $k ) {
									continue;
								}
								$query_args .= '&' . $k . '=' . $v;
							}
							$url .= $query_args;
						}
						wp_redirect( $url );
						die;
					} else {
						wp_die( 'Sorry, the page you are trying to access is not accessible due to security reason.' );
					}
				}
			}
		}

	}

	
	function arflite_custom_mime_types( $mimes ) {

		$mimes['heic'] = 'image/heic';
		$mimes['heif'] = 'image/heif';
		return $mimes;
	}

	function arflite_dequeue_elementor_script() {
		global $wp_scripts;

		if ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) {

			wp_deregister_script( 'backbone-marionette' );
			wp_dequeue_script( 'backbone-marionette' );

			wp_deregister_script( 'backbone-radio' );
			wp_dequeue_script( 'backbone-radio' );

			wp_deregister_script( 'elementor-common' );
			wp_dequeue_script( 'elementor-common' );

			wp_deregister_script( 'editor-preview' );
			wp_dequeue_script( 'editor-preview' );

			wp_deregister_script( 'elementor-admin' );
			wp_dequeue_script( 'elementor-admin' );

			wp_deregister_script( 'wp-color-picker-alpha' );
			wp_dequeue_script( 'wp-color-picker-alpha' );

		}
	}

	function arflite_plugin_add_suggested_privacy_content() {

		$content  = '<b>' . __( 'Who we are?', 'arforms-form-builder' ) . '</b>';
		$content .= '<p>' . __( 'ARForms Lite is a WordPress Free Form Builder Plugin to create stylish and modern style form withing few clicks.', 'arforms-form-builder' ) . ' </p>';
		$content .= '<br/>';
		$content .= '<b>' . __( 'What Personal Data we collect and why we collect it.', 'arforms-form-builder' ) . '</b>';

		$content .= '<p>' . __( 'ARForms Lite will not store any personal data except user_id (only if user is logged in), ip address, country, browser user_agent, referrer only when submit the form.', 'arforms-form-builder' ) . '</p>';

		$content .= '<p>' . __( 'ARForms Lite will also store the all type of data (this may contain personal data) in the database which plugin user has included in the form. These data cannot be editable but can be removed from form entry section of ARForms.', 'arforms-form-builder' ) . '</p>';

		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			wp_add_privacy_policy_content( 'ARForms lite', $content );
		}
	}

	function arflite_add_capabilities_to_new_user( $user_id ) {
		global $arflitemainhelper;
		if ( $user_id == '' ) {
			return;
		}
		if ( user_can( $user_id, 'administrator' ) ) {

			global $current_user;
			$arfroles = $arflitemainhelper->arflite_frm_capabilities();

			$userObj = new WP_User( $user_id );
			foreach ( $arfroles as $arfrole => $arfroledescription ) {
				$userObj->add_cap( $arfrole );
			}
			unset( $arfrole );
			unset( $arfroles );
			unset( $arfroledescription );
		}
	}
	/**
	 *       arf_dev_flag review below function's query
	 * * */
	function arflite_update_auto_increment_after_install() {
		global $wpdb, $tbl_arf_forms;
		$result_1 = $wpdb->get_results( "SHOW TABLE STATUS LIKE '" . $tbl_arf_forms . "'" ); //phpcs:ignore
		if ( $result_1[0]->Auto_increment < 100 ) {
			$wpdb->query( "ALTER TABLE {$tbl_arf_forms} AUTO_INCREMENT = 100" ); //phpcs:ignore
		}
	}

	function arflite_prevent_rocket_loader_script( $tag, $handle ) {

		$script   = htmlspecialchars( $tag );
		$pattern2 = '/\/(wp\-content\/plugins\/arforms\-form\-builder)|(wp\-includes\/js)/';
		preg_match( $pattern2, $script, $match_script );

		if ( ! isset( $match_script[0] ) || $match_script[0] == '' ) {
			return $tag;
		}

		$pattern = '/(.*?)(data\-cfasync\=)(.*?)/';
		preg_match_all( $pattern, $tag, $matches );
		if ( ! is_array( $matches ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && ! empty( $matches[2] ) && ! empty( $matches[2][0] ) && strtolower( trim( $matches[2][0] ) ) != 'data-cfasync=' ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && empty( $matches[2] ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} else {
			return $tag;
		}
	}

	function arflite_modify_rocket_script_clf( $tag, $handle ) {
		$script   = htmlspecialchars( $tag );
		$pattern2 = '/\/(wp\-content\/plugins\/arforms\-form\-builder)|(wp\-includes\/js)/';
		preg_match( $pattern2, $script, $match_script );

		if ( ! isset( $match_script[0] ) || $match_script[0] == '' ) {
			return $tag;
		}

		$pattern = '/(.*?)(data\-cfasync\=)(.*?)/';
		preg_match_all( $pattern, $tag, $matches );

		$pattern3 = '/type\=(\'|")[a-zA-Z0-9]+\-(text\/javascript)(\'|")/';
		preg_match_all( $pattern3, $tag, $match_tag );

		if ( ! isset( $match_tag[0] ) || '' == $match_tag[0] ) {
			return $tag;
		}

		if ( ! is_array( $matches ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && ! empty( $matches[2] ) && ! empty( $matches[2][0] ) && strtolower( trim( $matches[2][0] ) ) != 'data-cfasync=' ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} elseif ( ! empty( $matches ) && empty( $matches[2] ) ) {
			return str_replace( ' src', ' data-cfasync="false" src', $tag );
		} else {
			return $tag;
		}
	}

	function arflite_defer_attribute_to_js_for_editor( $tag, $handle ) {
		if ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] ) == 'ARForms' && isset( $_GET['arfaction'] ) && sanitize_text_field( $_GET['arfaction'] ) != '' ) {
			$script  = htmlspecialchars( $tag );
			$pattern = '/\/(wp\-content\/plugins\/arforms\-form\-builder)/';
			preg_match( $pattern, $script, $match_script );

			if ( ! isset( $match_script[0] ) || $match_script[0] == '' ) {
				return $tag;
			}

			return str_replace( ' src', ' defer="defer" src', $tag );
		} else {
			return $tag;
		}
	}

	function arflite_get_remote_post_params( $plugin_info = '' ) {
		global $wpdb, $arfliteversion;

		$action = '';
		$action = $plugin_info;

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin_list = get_plugins();
		$site_url    = home_url();
		$plugins     = array();

		$active_plugins = get_option( 'active_plugins' );

		foreach ( $plugin_list as $key => $plugin ) {
			$is_active = in_array( $key, $active_plugins );

			if ( strpos( strtolower( $plugin['Title'] ), 'arforms' ) !== false ) {
				$name      = substr( $key, 0, strpos( $key, '/' ) );
				$plugins[] = array(
					'name'      => $name,
					'version'   => $plugin['Version'],
					'is_active' => $is_active,
				);
			}
		}
		$plugins = json_encode( $plugins );

		$theme            = wp_get_theme();
		$theme_name       = $theme->get( 'Name' );
		$theme_uri        = $theme->get( 'ThemeURI' );
		$theme_version    = $theme->get( 'Version' );
		$theme_author     = $theme->get( 'Author' );
		$theme_author_uri = $theme->get( 'AuthorURI' );

		$im        = is_multisite();
		$sortorder = get_option( 'arfliteSortOrder' );

		$post = array(
			'wp'        => get_bloginfo( 'version' ),
			'php'       => phpversion(),
			'mysql'     => $wpdb->db_version(),
			'plugins'   => $plugins,
			'tn'        => $theme_name,
			'tu'        => $theme_uri,
			'tv'        => $theme_version,
			'ta'        => $theme_author,
			'tau'       => $theme_author_uri,
			'im'        => $im,
			'sortorder' => $sortorder,
		);

		return $post;
	}

	public static function arfliteforms_check_network_activation( $network_wide ) {
		if ( ! $network_wide ) {
			return;
		}

		deactivate_plugins( plugin_basename( __FILE__ ), true, true );

		header( 'Location: ' . network_admin_url( 'plugins.php?deactivate=true' ) );
		exit;
	}

	function arflite_modify_the_content( $content ) {

		$regex   = '/<arfsubmit>(.*?)<\/arfsubmit>/is';
		$content = preg_replace_callback( $regex, array( $this, 'arflite_the_content_remove_ptag' ), $content );

		$regex   = '/<arffile>(.*?)<\/arffile>/is';
		$content = preg_replace_callback( $regex, array( $this, 'arflite_the_content_remove_ptag' ), $content );

		$regex   = '/<arfpassword>(.*?)<\/arfpassword>/is';
		$content = preg_replace_callback( $regex, array( $this, 'arflite_the_content_remove_ptag' ), $content );

		$content = preg_replace( '/<arfsubmit>|<\/arfsubmit>|<arffile>|<\/arffile>|<arfpassword>|<\/arfpassword>/is', '', $content );

		return $content;
	}

	function arflite_the_content_remove_ptag( $match ) {
		$content = $match[1];

		$content = preg_replace( '|<p>|', '', $content );

		$content = preg_replace( '|</p>|', '', $content );

		$content = preg_replace( '|<br />|', '', $content );

		return $content;
	}

	function arflite_the_content_removeptag( $matches ) {
		return $matches[1];
	}

	function arflite_the_content_removeemptyptag( $matches ) {
		return $matches[1];
	}

	function arflite_globalstandalone_route( $controller, $action ) {
		global $arflitemainhelper, $arflitesettingcontroller;

		if ( $controller == 'fields' ) {

			if ( ! defined( 'DOING_AJAX' ) ) {
				define( 'DOING_AJAX', true );
			}

			global $arflitefieldcontroller;

		} elseif ( $controller == 'entries' ) {

			global $arfliterecordcontroller;

			if ( $action == 'csv' ) {

				$s = isset( $_REQUEST['s'] ) ? 's' : 'search';

				$arfliterecordcontroller->arflitecsv( $arflitemainhelper->arflite_get_param( 'form' ), $arflitemainhelper->arflite_get_param( $s ), $arflitemainhelper->arflite_get_param( 'fid' ) );

				unset( $s );
			} else {

				if ( ! defined( 'DOING_AJAX' ) ) {
					define( 'DOING_AJAX', true );
				}

				if ( $action == 'send_email' ) {
					$arfliterecordcontroller->arflite_send_email( $arflitemainhelper->arflite_get_param( 'entry_id' ), $arflitemainhelper->arflite_get_param( 'form_id' ), $arflitemainhelper->arflite_get_param( 'type' ) );

				} elseif ( $action == 'create' ) {
					$arfliterecordcontroller->arflite_ajax_create();

				} elseif ( $action == 'previous' ) {
					$arfliterecordcontroller->arflite_ajax_previous();
				} elseif ( $action == 'check_recaptcha' ) {
					$arfliterecordcontroller->arflite_ajax_check_recaptcha();
				} elseif ( $action == 'checkinbuiltcaptcha' ) {
					$arfliterecordcontroller->arflite_ajax_check_spam_filter();

				} elseif ( $action == 'update' ) {
					$arfliterecordcontroller->arflite_ajax_update();

				} elseif ( $action == 'destroy' ) {
					$arfliterecordcontroller->arflite_ajax_destroy();
				}
			}
		} elseif ( $controller == 'settingspreview' ) {

			global $arflite_style_settings;

			if ( ! is_admin() ) {
				$use_saved = true;
			}

			if ( isset( $_REQUEST['arfmfws'] ) ) {
				$arfssl    = ( is_ssl() ) ? 1 : 0;
				$css_class = '';
				if ( isset( $_REQUEST['arfinpst'] ) && sanitize_text_field( $_REQUEST['arfinpst'] ) == 'material' ) {
					$css_class = ' .arf_materialize_form ';
					include ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';
				} else {
					$css_class = '';
					include ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';
				}

				include ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';
				if ( is_rtl() ) {
					include ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';
				}

				global $arfliteform, $wpdb, $arfliterecordhelper, $arflitefieldhelper, $arfliteformcontroller;
				$arfformid = !empty( $_REQUEST['arfformid']) ? intval( $_REQUEST['arfformid'] ) : '';
				$form      = $arfliteform->arflitegetOne( (int) $arfformid );

				$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form->id, false, 0 );
				$values = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );

				$form->options['arf_form_other_css'] = $arfliteformcontroller->arflitebr2nl( $form->options['arf_form_other_css'] );
				echo $arflitemainhelper->arflite_esc_textarea( $form->options['arf_form_other_css'] ); //phpcs:ignore

				$custom_css_array_form = array(
					'arf_form_outer_wrapper'   => '.arf_form_outer_wrapper|.arfmodal',
					'arf_form_inner_wrapper'   => '.arf_fieldset|.arfmodal',
					'arf_form_title'           => '.formtitle_style',
					'arf_form_description'     => 'div.formdescription_style',
					'arf_form_element_wrapper' => '.arfformfield',
					'arf_form_element_label'   => 'label.arf_main_label',
					'arf_form_elements'        => '.controls',
					'arf_submit_outer_wrapper' => 'div.arfsubmitbutton',
					'arf_form_submit_button'   => '.arfsubmitbutton button.arf_submit_btn',
					'arf_form_success_message' => '#arf_message_success',
					'arf_form_error_message'   => '.control-group.arf_error .help-block|.control-group.arf_warning .help-block|.control-group.arf_warning .help-inline|.control-group.arf_warning .control-label|.control-group.arf_error .popover|.control-group.arf_warning .popover',
				);

				$css_class = esc_html( $css_class );

				foreach ( $custom_css_array_form as $custom_css_block_form => $custom_css_classes_form ) {

					if ( isset( $form->options[ $custom_css_block_form ] ) && $form->options[ $custom_css_block_form ] != '' ) {

						$form->options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form->options[ $custom_css_block_form ] );

						if ( $custom_css_block_form == 'arf_form_outer_wrapper' ) {
							$arf_form_outer_wrapper_array = explode( '|', $custom_css_classes_form );

							foreach ( $arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ) {
								if ( $arf_form_outer_wrapper1 == '.arf_form_outer_wrapper' ) {
									echo '.arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '.arf_form_outer_wrapper { ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
								}
								if ( $arf_form_outer_wrapper1 == '.arfmodal' ) {
									echo '#popup-form-' . esc_html( $form->id ) . esc_html( $css_class ) . '.arfmodal{ ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
								}
							}
						} elseif ( $custom_css_block_form == 'arf_form_inner_wrapper' ) {
							$arf_form_inner_wrapper_array = explode( '|', $custom_css_classes_form );
							foreach ( $arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ) {
								if ( $arf_form_inner_wrapper1 == '.arf_fieldset' ) {
									echo '.arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' ' . esc_html( $arf_form_inner_wrapper1 ) . ' { ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
								}
								if ( $arf_form_inner_wrapper1 == '.arfmodal' ) {
									echo '.arfmodal .arfmodal-body .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' .arf_fieldset { ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
								}
							}
						} elseif ( $custom_css_block_form == 'arf_form_error_message' ) {
							$arf_form_error_message_array = explode( '|', $custom_css_classes_form );

							foreach ( $arf_form_error_message_array as $arf_form_error_message1 ) {
								echo '.arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' ' . esc_html( $arf_form_error_message1 ) . ' { ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
							}
						} else {
							echo '.arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' ' . esc_html( $custom_css_classes_form ) . ' { ' . esc_html( $form->options[ $custom_css_block_form ] ) . ' } ';
						}
					}
				}

				foreach ( $values['fields'] as $field ) {

					$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

					if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
						echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' #arf_field_' . esc_html( $field['id'] ) . '_container .help-block { width: ' . esc_html( $field['field_width'] ) . 'px; } ';
					}

					$custom_css_array = array(
						'css_outer_wrapper' => '.arf_form_outer_wrapper',
						'css_label'         => '.css_label',
						'css_input_element' => '.css_input_element',
						'css_description'   => '.arf_field_description',
					);

					foreach ( $custom_css_array as $custom_css_block => $custom_css_classes ) {

						if ( isset( $field[ $custom_css_block ] ) && $field[ $custom_css_block ] != '' ) {

							$field[ $custom_css_block ] = esc_html( $arfliteformcontroller->arflitebr2nl( $field[ $custom_css_block ] ) );

							if ( $custom_css_block == 'css_outer_wrapper' ) {
								echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' #arf_field_' . esc_html( $field['id'] ) . '_container { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							} elseif ( $custom_css_block == 'css_outer_wrapper' ) {
								echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' #heading_' . esc_html( $field['id'] ) . ' { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							} elseif ( $custom_css_block == 'css_label' ) {
								echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' #arf_field_' . esc_html( $field['id'] ) . '_container label.arf_main_label { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							} elseif ( $custom_css_block == 'css_label' ) {
								echo ' .arflite_main_div_' . esc_html($form->id) . ' #heading_' . esc_html( $field['id'] ) . ' h2.arf_sec_heading_field { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							} elseif ( $custom_css_block == 'css_input_element' ) {

								if ( $field['type'] == 'textarea' ) {
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .controls textarea { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
								} elseif ( $field['type'] == 'select' ) {
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .controls select { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .controls .arfbtn.dropdown-toggle { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
								} elseif ( $field['type'] == 'radio' ) {
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .arf_radiobutton label { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
								} elseif ( $field['type'] == 'checkbox' ) {
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .arf_checkbox_style label { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
								} else {
									echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .controls input { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
									if ( $field['type'] == 'email' ) {
										echo '.arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . ' #arf_field_' . esc_html( $field['id'] ) . '_container + .confirm_email_container .controls input {' . esc_html( $field[ $custom_css_block ] ) . '}';
									}
								}
							} elseif ( $custom_css_block == 'css_description' ) {
								echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #arf_field_' . esc_html( $field['id'] ) . '_container .arf_field_description { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							} elseif ( $custom_css_block == 'css_description' ) {
								echo ' .arflite_main_div_' . esc_html( $form->id ) . esc_html( $css_class ) . '  #heading_' . esc_html( $field['id'] ) . ' .arf_heading_description { ' . esc_html( $field[ $custom_css_block ] ) . ' } ';
							}
						}
					}
				}
			} else {
				return false;
			}
		}
	}

	function arflitemenu() {

		global $arflitemainhelper;

		function arflite_get_free_menu_position( $start, $increment = 0.1 ) {
			foreach ( $GLOBALS['menu'] as $key => $menu ) {
				$menus_positions[] = $key;
			}

			if ( ! in_array( $start, $menus_positions ) ) {
				return $start;
			} else {
				$start += $increment;
			}

			while ( in_array( $start, $menus_positions ) ) {
				$start += $increment;
			}
			return $start;
		}

		$place = arflite_get_free_menu_position( 26.1, .1 );

		if ( current_user_can( 'arfviewforms' ) ) {

			global $arfliteformcontroller;

			add_menu_page( 'ARForms Lite', 'ARForms Lite', 'arfviewforms', 'ARForms', array( $arfliteformcontroller, 'arfliteroute' ), ARFLITEIMAGESURL . '/main-icon-small2n.png', (string) $place );
		} elseif ( current_user_can( 'arfviewentries' ) ) {

			global $arfliterecordcontroller;

			add_menu_page( 'ARForms Lite', 'ARForms Lite', 'arfviewentries', 'ARForms', array( $arfliterecordcontroller, 'arfliteroute' ), ARFLITEIMAGESURL . '/main-icon-small2n.png', (string) $place );
		}

		add_submenu_page( '', '', '', 'administrator', 'ARForms-settings1', array( $this, 'list_entries' ) );
	}

	function arflite_menu_css() {
		?>


		<style type="text/css">
			#adminmenu .toplevel_page_ARForms div.wp-menu-image img{  padding: 5px 0 0 2px; }

		</style>


		<?php

	}


	function arflite_settings_link( $links, $file ) {

		$settings = '<a href="' . admin_url( 'admin.php?page=ARForms-settings' ) . '">' . __( 'Settings', 'arforms-form-builder' ) . '</a>';

		array_unshift( $links, $settings );

		return $links;
	}

	function arflite_plugin_action_links( $links, $file ) {

		global $arformsmain;

		if ( $file == 'arforms-form-builder/arforms-form-builder.php' ) {

			if ( isset( $links['deactivate'] ) ) {

				$deactivation_link = $links['deactivate'];

				$extra_popup_class = '';
				if($arformsmain->arforms_is_pro_active()) {
					$extra_popup_class = 'arflite-confirm-deactivate-wrapper';
				}

				$deactivation_link   = str_replace(
					'<a ',
					'<div class="arflite-deactivate-form-wrapper">
                         <span class="arflite-deactivate-form '.$extra_popup_class.'" id="arflite-deactivate-form-' . esc_attr( 'ARFormslite' ) . '"></span>
                     </div><a id="arflite-deactivate-link-' . esc_attr( 'ARFormslite' ) . '" ',
					$deactivation_link
				);
				$links['deactivate'] = $deactivation_link;
			}
		}
		return $links;
	}

	function arflite_deactivate_feedback_popup() {

		global $pagenow;
		if ( $pagenow == 'plugins.php' ) {
			$question_options = array();

			$question_options['list_data_options'] = array(
				'setup-difficult'  => __( 'Set up is too difficult', 'arforms-form-builder' ),
				'docs-improvement' => __( 'Lack of documentation', 'arforms-form-builder' ),
				'features'         => __( 'Not the features I wanted', 'arforms-form-builder' ),
				'better-plugin'    => __( 'Found a better plugin', 'arforms-form-builder' ),
				'incompatibility'  => __( 'Incompatible with theme or plugin', 'arforms-form-builder' ),
				'bought-premium'   => __( 'I bought premium version of ARForms', 'arforms-form-builder' ),
				'maintenance'      => __( 'Other', 'arforms-form-builder' ),
			);

			$html2 = '<div class="arflite-deactivate-form-head"><svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#fff"><path d="M4.47 21h15.06c1.54 0 2.5-1.67 1.73-3L13.73 4.99c-.77-1.33-2.69-1.33-3.46 0L2.74 18c-.77 1.33.19 3 1.73 3zM12 14c-.55 0-1-.45-1-1v-2c0-.55.45-1 1-1s1 .45 1 1v2c0 .55-.45 1-1 1zm1 4h-2v-2h2v2z"/></svg><p class="arforms_lite_deactivate"><strong>' . esc_html(__('ARForm Lite plugin Deactivation', 'arforms-form-builder')).'.</strong></p></div>';
            $html2 .= '<div class="arflite-deactivate-form-body">';
            $html2 .= '<div class="arflite-deactivate-options">';

            $html2 .= '<p><strong>' . esc_html(__('You are using ARForms Premium plugin on your website and it is an extension to ARForms Lite, so, If you deactivate ARForms Lite then it will automatically deactivate ARForms Premium and all the add-ons', 'arforms-form-builder')) . '.</strong></p></br>';

            $html2 .= '<p><label><input type="checkbox" name="arflite-risk-confirm" id="arflite-risk-confirm" value="risk-confirm">'.esc_html__('I understand the risk', 'arforms-form-builder').'</label></p>';
            $html2 .= '</div>';
            $html2 .= '<hr/>';
            $html2 .= '</div>';
            $html2 .= '<div class="arflite-deactivate-form-footer"><p>';                            
            $html2 .= '<button id="arflite-deactivate-cancel-btn" class="arf-deactivate-btn arf-deactivate-btn-cancel" >'.__('Cancel', 'arforms-form-builder')
            . '</button>';
            $html2 .= '<button id="arflite-deactivate-submit-btn" disabled=disabled class="arf-deactivate-btn arf-disable-btn arf-deactivate-btn-primary button button-primary" href="#">'.__('Proceed', 'arforms-form-builder')
            . '</button></p>';
            $html2 .= '</div>';

			$html = '<div class="arflite-deactivate-form-head"><strong>' . esc_html( __( 'ARForms Lite - Sorry to see you go', 'arforms-form-builder' ) ) . '</strong></div>';

			$html .= '<div class="arflite-deactivate-form-body">';

			if ( is_array( $question_options['list_data_options'] ) ) {

				$html .= '<div class="arflite-deactivate-options">';

					$html .= '<p><strong>' . esc_html( __( 'Before you deactivate the ARForms Lite plugin, would you quickly give us your reason for doing so?', 'arforms-form-builder' ) ) . '</strong></p><p>';

				foreach ( $question_options['list_data_options'] as $key => $option ) {
					$html .= '<input type="radio" name="arflite-deactivate-reason" id="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '">' . esc_attr( $option ) . '</label><br>';
				}

					$html .= '</p><label id="arflite-deactivate-details-label" for="arflite-deactivate-reasons"><strong>' . esc_html( __( 'How could we improve ?', 'arforms-form-builder' ) ) . '</strong></label><textarea name="arflite-deactivate-details" id="arflite-deactivate-details" rows="2"></textarea>';
					$html .= '</div>';
			}

			$html .= '<hr/>';

			$html .= '</div>';

			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __( 'Submitting form', 'arforms-form-builder' ) . '</p>';

			$html .= '<div class="arflite-deactivate-form-footer"><p>';

			$html .= '<label for="arflite_anonymous" title="'
				. __( 'If you UNCHECK this then your email address will be sent along with your feedback. This can be used by arflite to get back to you for more info or a solution.', 'arforms-form-builder' )
				. '"><input type="checkbox" name="arflite-deactivate-tracking" checked="checked" id="arflite_anonymous"> ' . __( 'Send anonymous', 'arforms-form-builder' ) . '</label><br>';

			$html .= '<a id="arflite-deactivate-submit-form" class="button button-primary" href="#">'. sprintf( __( '%s Submit and%s Deactivate', 'arforms-form-builder' ),'<span>','</span>'). '</a>'; //phpcs:ignore

			$html .= '</p></div>';
			?>
			<?php global $arformsmain; 
			 if( $arformsmain->arforms_is_pro_active() ){ ?>

				<div class="arflite-deactivate-form-skeleton" id="arflite-deactivate-form-skeleton"><?php echo $html2; //phpcs:ignore ?></div>
			<?php } else { ?>
				<div class="arflite-deactivate-form-skeleton" id="arflite-deactivate-form-skeleton"><?php echo $html; //phpcs:ignore ?></div>
			<?php } ?>

			<div class="arflite-deactivate-form-bg"></div>
			<?php
		}
	}

	function arflite_pro_feature_popup() {

		if ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) {

			echo '<div class="arflite_pro_popup_overlay">';

				echo '<div class="arflite_pro_popup_wrapper">';

					echo '<span class="arflite_pro_popup_close_icon dashicons dashicons-no-alt"></span>';

					echo '<span class="arflite_pro_popup_lock_icon"></span>';

					echo '<p class="arflite_pro_version_popup_title">This Feature is Available in PRO version</p>';

					echo '<p class="arflite_pro_version_popup_subtitle">Please upgrade to PRO version to Use <br/> All these Awesome Features</p>';

					echo '<a class="arflite_pro_version_popup_btn" href="javascript:void(0);">UPGRADE TO PRO</a>';

				echo '</div>';

			echo '</div>';

		}

	}

	function arflite_deactivate_plugin_func() {

		check_ajax_referer( 'arflite_deactivate_plugin', 'security' );

		if ( ! empty( $_POST['arflite_reason'] ) && isset( $_POST['arflite_details'] ) ) {

			$arflite_anonymous = isset( $_POST['arflite_anonymous'] ) && sanitize_text_field( $_POST['arflite_anonymous'] );

			$args                      = array();
			$args['arflite_reason']    = ! empty( $_POST['arflite_reason'] ) ? sanitize_text_field( $_POST['arflite_reason'] ) : 'maintenance';
			$args['arflite_details']   = ! empty( $_POST['arflite_details'] ) ? sanitize_text_field( $_POST['arflite_details'] ) : '';
			$args['security']          = ! empty( $_POST['security'] ) ? sanitize_text_field( $_POST['security'] ) : '';
			$args['action']            = ! empty( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';
			$args['dataType']          = ! empty( $_POST['dataType'] ) ? sanitize_text_field( $_POST['dataType'] ) : '';
			$args['arflite_anonymous'] = ! empty( $_POST['arflite_anonymous'] ) ? sanitize_text_field( $_POST['arflite_anonymous'] ) : '';

			$args['arflite_site_url'] = ARFLITE_HOME_URL;

			if ( ! $arflite_anonymous ) {

				$args['arf_lite_site_email'] = get_option( 'admin_email' );
			}

			$url = 'https://www.arformsplugin.com/download_samples/arflite_feedback.php';

			$response = wp_remote_post(
				$url,
				array(
					'body'    => $args,
					'timeout' => 500,
				)
			);
		}
		echo json_encode(
			array(
				'status' => 'OK',
			)
		);
		die();
	}

	function arflite_admin_js() {

		global $arfliteversion, $pagenow, $arflitemaincontroller, $wp_version;

		$jquery_handler       = 'jquery';
		$jquery_ui_handler    = 'jquery-ui-core';
		$jq_draggable_handler = 'jquery-ui-draggable';

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );

		wp_register_style( 'arf_admin_menu_style', ARFLITEURL .'/css/arf_admin_menu_style.css', array(), $arfliteversion );
		wp_enqueue_style( 'arf_admin_menu_style' );

		$arf_current_date = current_time('timestamp', true );
		$arf_sale_start_time = '1700503200';
		$arf_sale_end_time = '1701561600';

		if( $arf_current_date >= $arf_sale_start_time && $arf_current_date <= $arf_sale_end_time ){
			$stylesheet = '#adminmenu #toplevel_page_ARForms .wp-submenu li:last-child a {
				color:red;
				background: transparent;
				font-weight: bold;
			}';
			wp_add_inline_style( 'arf_admin_menu_style', $stylesheet );
		}

		if ( isset( $_GET ) && ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) || ( $pagenow == 'edit.php' && isset( $_GET ) && isset( $_GET['post_type'] ) && sanitize_text_field( $_GET['post_type'] ) == 'frm_display' ) ) {

			add_filter( 'admin_body_class', array( $this, 'arflite_admin_body_class' ) );

			wp_enqueue_script( 'jquery-ui-resizable' );
			wp_enqueue_script( 'admin-widgets' );

			wp_enqueue_style( 'widgets' );

			if ( version_compare( $wp_version, '5.0', '<' ) ) {
				wp_enqueue_script( 'wp-hooks', ARFLITEURL . '/js/hooks.js', array( $jquery_handler ), $arfliteversion );
			}

			wp_enqueue_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( $jquery_handler ), $arfliteversion );

			wp_enqueue_script( 'arformslite_hooks', ARFLITEURL . '/js/arformslite_hooks.js', array( $jquery_handler ), $arfliteversion );

			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );

			wp_enqueue_script( 'arformslite_admin_editor', ARFLITEURL . '/js/arformslite_admin_editor.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );

			if ( is_rtl() ) {
				wp_enqueue_style( 'arformslite-admin-rtl', ARFLITEURL . '/css/arformslite-rtl.css', array(), $arfliteversion );
			}

			wp_enqueue_style( 'arformslite_v3.0', ARFLITEURL . '/css/arformslite_v3.0.css', array(), $arfliteversion );

			wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );

			wp_register_style( 'arflite-media-css', ARFLITEURL . '/css/arflite_media_css.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-media-css' );

			if ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' && isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) != '' ) {
				wp_enqueue_script( 'jquery-json', ARFLITEURL . '/js/jquery.json-2.4.js', array( $jquery_handler ), $arfliteversion );
			}

			if ( $GLOBALS['wp_version'] >= '3.8' && version_compare( $GLOBALS['wp_version'], '3.9', '<' ) ) {

				wp_enqueue_style( 'arformslite-admin-3.8', ARFLITEURL . '/css/arflite_plugin_3.8.css', array(), $arfliteversion );
			}

			if ( $GLOBALS['wp_version'] >= '3.9' && version_compare( $GLOBALS['wp_version'], '3.10', '<' ) ) {

				wp_enqueue_style( 'arformslite-admin-3.9', ARFLITEURL . '/css/arflite_plugin_3.9.css', array(), $arfliteversion );
			}

			if ( $GLOBALS['wp_version'] >= '4.0' ) {

				wp_enqueue_style( 'arformslite-admin-4.0', ARFLITEURL . '/css/arflite_plugin_4.0.css', array(), $arfliteversion );
			}
		} elseif ( $pagenow == 'post.php' || ( $pagenow == 'post-new.php' && isset( $_REQUEST['post_type'] ) && sanitize_text_field( $_REQUEST['post_type'] ) == 'frm_display' ) ) {

			if ( isset( $_REQUEST['post_type'] ) ) {

				$post_type = sanitize_text_field( $_REQUEST['post_type'] );
			} elseif ( isset( $_REQUEST['post'] ) && ! empty( $_REQUEST['post'] ) ) {

				$post = get_post( intval( $_REQUEST['post'] ) );

				if ( ! $post ) {
					return;
				}

				$post_type = $post->post_type;
			} else {

				return;
			}

			if ( $post_type == 'frm_display' ) {

				wp_enqueue_script( 'jquery-ui-draggable' );

				if ( version_compare( $wp_version, '5.0', '<' ) ) {
					wp_enqueue_script( 'wp-hooks', ARFLITEURL . '/js/hooks.js', array( $jquery_handler ), $arfliteversion );
				}

				wp_enqueue_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( $jquery_handler ), $arfliteversion );

				wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );

				wp_enqueue_script( 'arformslite_hooks', ARFLITEURL . '/js/arformslite_hooks.js', array( $jquery_handler ), $arfliteversion );

				wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );

				wp_enqueue_script( 'arformslite_admin_editor', ARFLITEURL . '/js/arformslite_admin_editor.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );

				wp_enqueue_style( 'arformslite_v3.0', ARFLITEURL . '/css/arformslite_v3.0.css', array(), $arfliteversion );

				wp_register_style( 'arflite-media-css', ARFLITEURL . '/css/arflite_media_css.css', array(), $arfliteversion );
				wp_enqueue_style( 'arflite-media-css' );

				if ( $GLOBALS['wp_version'] >= '3.8' and version_compare( $GLOBALS['wp_version'], '3.9', '<' ) ) {

					wp_enqueue_style( 'arformslite-admin-3.8', ARFLITEURL . '/css/arflite_plugin_3.8.css', array(), $arfliteversion );
				}
			}
		}
	}

	function arflite_admin_body_class( $classes ) {

		global $wp_version;

		if ( version_compare( $wp_version, '3.4.9', '>' ) ) {
			$classes .= ' arf35trigger';
		}

		return $classes;
	}

	function arflite_front_head( $ispost = '' ) {

		global $arformsmain, $arfliteversion, $arflitedbversion, $arflitemaincontroller, $arfliteformcontroller, $wp_version;

		if ( ! is_admin() ) {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), $arfliteversion );

			wp_register_script( 'jqbootstrapvalidation', ARFLITEURL . '/bootstrap/js/jqBootstrapValidation.js', array( 'jquery' ), $arfliteversion );

			wp_register_style( 'arflitedisplaycss', ARFLITEURL . '/css/arflite_front.css', array(), $arfliteversion );

			wp_register_style( 'arflitedisplayfootercss', ARFLITEURL . '/css/arflite_front_footer.css', array(), $arfliteversion );

			wp_register_style( 'flag_icon', ARFLITEURL . '/css/flag_icon.css', array(), $arfliteversion );

			wp_register_style( 'arfliterecaptchacss', ARFLITEURL . '/css/recaptcha_style.css', array(), $arfliteversion );

			wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $arfliteversion );
			wp_register_script( 'bootstrap-inputmask', ARFLITEURL . '/bootstrap/js/bootstrap-inputmask.js', array( 'jquery' ), $arfliteversion );
			wp_register_script( 'jquery-maskedinput', ARFLITEURL . '/js/jquery.maskedinput.min.js', array( 'jquery' ), $arfliteversion, true );

			wp_register_script( 'jscolor', ARFLITEURL . '/js/jscolor.js', array( 'jquery' ), $arfliteversion );

			wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );

			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array(), $arfliteversion );

			wp_register_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( 'jquery' ), $arfliteversion );

			wp_register_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );

			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );

			wp_register_script( 'jquery-animatenumber', ARFLITEURL . '/js/jquery.animateNumber.js', array(), $arfliteversion );

		} else {
			wp_enqueue_script( 'jquery' );
		}

		$path = !empty( $_SERVER['REQUEST_URI'] ) ? esc_url($_SERVER['REQUEST_URI']) : ''; //phpcs:ignore
		$file_path = basename( $path );

		if ( ! strstr( $file_path, 'post.php' ) ) {
			wp_register_script( 'jquery-maskedinput', ARFLITEURL . '/js/jquery.maskedinput.min.js', array( 'jquery' ), $arfliteversion, true );
			wp_register_script( 'bootstrap-inputmask', ARFLITEURL . '/bootstrap/js/bootstrap-inputmask.js', array( 'jquery' ), $arfliteversion );
			wp_register_script( 'intltelinput', ARFLITEURL . '/js/intlTelInput.min.js', array(), $arfliteversion, true );
			wp_register_script( 'arformslite_phone_utils', ARFLITEURL . '/js/arf_phone_utils.js', array(), $arfliteversion, true );
			if ( version_compare( $wp_version, '5.0', '<' ) ) {
				wp_register_script( 'wp-hooks', ARFLITEURL . '/js/hooks.js', array( 'jquery' ), $arfliteversion );
			}
			wp_register_script( 'arformslite_hooks', ARFLITEURL . '/js/arformslite_hooks.js', array( 'jquery' ), $arfliteversion );
			wp_register_script( 'arformslite-js', ARFLITEURL . '/js/arformslite.js', array( 'jquery' ), $arfliteversion . '_' . rand( 1, 5 ), true );
		}

		wp_register_script( 'recaptcha-ajax', ARFLITEURL . '/js/recaptcha_ajax.js', array(), $arfliteversion );

		if ( $ispost = '1' && ! is_admin() ) {
			global $post;
			$post_content = isset( $post->post_content ) ? $post->post_content : '';
			$parts        = explode( '[ARForms', $post_content );

			if ( isset( $parts[1] ) ) {
				$myidpart = explode( 'id=', $parts[1] );
				$myid     = isset( $myidpart[1] ) ? explode( ']', $myidpart[1] ) : array();
				if ( isset( $myid[0] ) && $myid[0] > 0 ) {

				}
			}
		}

		$arf_load_style = $arformsmain->arforms_get_settings('load_style','general_settings');
		$arf_load_style = !empty( $arf_load_style ) ? $arf_load_style : 'none';
		
		if ( ! is_admin() && isset( $arf_load_style ) && $arf_load_style == 'all' ) {

			$css = apply_filters( 'getarflitestylesheet', ARFLITEURL . '/css/arflite_front.css', 'header' );

			if ( is_array( $css ) ) {

				foreach ( $css as $css_key => $file ) {
					wp_enqueue_style( 'arflite-forms' . $css_key, $file, array(), $arfliteversion );
				}

				unset( $css_key );

				unset( $file );
			} else {
				wp_enqueue_style( 'arflite-forms', $css, array(), $arfliteversion );
			}

			unset( $css );

			global $arflitecssloaded;

			$arflitecssloaded = true;
		}
	}

	function arflite_wp_enqeue_footer_script() {

		global $arflite_fields_with_external_js, $arflite_bootstraped_fields_array, $wpdb, $ARFLiteMdlDb,$arfliteversion, $arformsmain;

		if( $arformsmain->arforms_is_pro_active() ){
            return;
        }

		if ( is_admin() && isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' && isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) != '' ) {
			if ( isset( $arflite_fields_with_external_js ) && is_array( $arflite_fields_with_external_js ) && ! empty( $arflite_fields_with_external_js ) ) {
				$matched_fields = array_intersect( $arflite_fields_with_external_js, $arflite_bootstraped_fields_array );

				foreach ( $matched_fields as $field_type ) {
					switch ( $field_type ) {
						case 'select':
							wp_register_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( 'jquery' ), $arfliteversion );
							wp_enqueue_script( 'arformslite_selectpicker' );
							wp_register_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );
							wp_enqueue_style( 'arformslite_selectpicker' );

							break;
						case 'date':
							break;
						case 'time':
							break;
						default:
							do_action( 'arflite_load_bootstrap_js_from_outside', $field_type );
							break;
					}
				}
			}
		}
	}

	function arflite_front_head_js() {
		global $post, $wpdb, $arfliteformcontroller, $arfliteversion, $arfliteform, $arflitemainhelper, $arfliterecordhelper, $arflitefieldhelper, $arflite_form_type_with_id, $ARFLiteMdlDb,$arflite_func_val, $arflite_jscss_version, $tbl_arf_forms;

		$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
		$upload_main_dir = ARFLITE_UPLOAD_DIR . '/maincss';

		if ( ! isset( $arflite_form_type_with_id ) || $arflite_form_type_with_id == '' ) {
			$arflite_form_type_with_id = array();
		}

		global $arfliteversion;

		$post_content = isset( $post->post_content ) ? $post->post_content : '';

		$parts = explode( '[ARForms', $post_content );

		$parts[1]    = isset( $parts[1] ) ? $parts[1] : '';
		$myidpart    = ( $parts[1] != '' ) ? explode( 'id=', $parts[1] ) : array();
		$myidpart[1] = isset( $myidpart[1] ) ? $myidpart[1] : '';
		$myid        = ( $myidpart[1] != '' ) ? explode( ']', $myidpart[1] ) : '';

		if ( ! is_admin() ) {
			global $wp_query,$arflite_is_active_cornorstone, $arformsmain;
			$posts = $wp_query->posts;
			if ( $arflite_is_active_cornorstone ) {
				$pattern = '\[(\[?)(ARForms|cs_arformslite_cs|arformsdivi)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			} else {
				$pattern = '\[(\[?)(ARForms|arformsdivi)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			}


			if ( is_array( $posts ) ) {
				$formids                   = array();
				foreach ( $posts as $post_data ) {
					if ( preg_match_all( '/' . $pattern . '/s', $post_data->post_content, $matches ) && array_key_exists( 2, $matches ) && ( in_array( 'ARForms', $matches[2] ) ) ) {
						break;
					} else if(  preg_match_all( '/' . $pattern . '/s', $post_data->post_content, $matches ) && array_key_exists( 2, $matches ) && ( in_array( 'arformsdivi', $matches[2] ) )  ){
						break;
					} else if( preg_match_all( '/(\<!--(\s+)(wp\:arforms-form-builder\/arforms-form)(\s+)\{(\'|")form_id(\'|")\:(\'|")(\d+)(\'|")\})/', $post_data->post_content, $matches ) && array_key_exists( 8, $matches ) && in_array( 'wp:arforms-form-builder/arforms-form', $matches[3] ) ){
						//$formids[] = $matches[8][0];
						foreach( $matches[8] as $frm_id ){
							$formids[] = $frm_id;
						}
						break;
						
					}
				}

				$arflite_form_type_with_id = array();

				if ( isset( $matches ) ) {
					if ( is_array( $matches ) && count( $matches ) > 0 ) {
						foreach ( $matches as $k => $v ) {
							foreach ( $v as $key => $val ) {
								$parts_cornerstone = 0;
								
								if( strpos( $val, 'form_id=' ) !== false && preg_match('/\[arformsdivi/', $val ) ){
									$parts = explode( 'form_id=', $val );
								}else if ( strpos( $val, 'id=' ) !== false ) {
									$parts = explode( 'id=', $val );
								} elseif ( strpos( $val, 'arf_forms=' ) !== false ) {
									$parts_cornerstone = explode( 'arf_forms=', $val );
								}

								if ( $parts > 0 && isset( $parts[1] ) ) {

									if ( stripos( $parts[1], ']' ) !== false ) {
										$partsnew  = explode( ']', $parts[1] );
										$formids[] = $partsnew[0];
									} elseif ( stripos( $parts[1], ' ' ) !== false ) {

										$partsnew  = explode( ' ', $parts[1] );
										$formids[] = $partsnew[0];
									} else {

									}
								}
								if ( $parts_cornerstone > 0 && isset( $parts_cornerstone[1] ) ) {
									if ( ! is_array( $parts_cornerstone[1] ) ) {

										$parts_cornerstone[1]    = explode( ' ', $parts_cornerstone[1] );
										$parts_cornerstone[1][0] = str_replace( '"', '', $parts_cornerstone[1][0] );

										$formids[] = $parts_cornerstone[1][0];
									}
								}

								if ( strpos( $val, '[' ) !== false && strpos( $val, ']' ) !== false ) {
									$temp_value = shortcode_parse_atts( $val );
									if ( isset( $temp_value[1] ) ) {

										$temp_value[1] = explode( '=', $temp_value[1] );
										if ( isset( $temp_value[1][1] ) ) {
											$temp_value[1][1]                = str_replace( "'", '', $temp_value[1][1] );
											$temp_value[1][1]                = str_replace( '"', '', $temp_value[1][1] );
											$temp_value[1][1]                = str_replace( ']', '', $temp_value[1][1] );
											$temp_value[1][1]                = str_replace( '[', '', $temp_value[1][1] );
											$temp_value[ $temp_value[1][0] ] = $temp_value[1][1];
										}
									}

									if ( isset( $temp_value['id'] ) ) {
										$arflite_form_type_with_id[] = $temp_value;
									} elseif ( isset( $temp_value['arf_forms'] ) ) {
										$temp_value['id']            = $temp_value['arf_forms'];
										$arflite_form_type_with_id[] = $temp_value;
									} elseif ( isset( $temp_value['form_id'] ) ) {
										$temp_value['id']            = $temp_value['form_id'];
										$arflite_form_type_with_id[] = $temp_value;
									}
								}
							}
						}
					}
				}
			}

			$newvalarr = array();

			if ( isset( $formids ) && is_array( $formids ) && count( $formids ) > 0 ) {
				foreach ( $formids as $newkey => $newval ) {
					if ( stripos( $newval, ' ' ) !== false ) {
						$partsnew    = explode( ' ', $newval );
						$newvalarr[] = $partsnew[0];
					} else {
						$newvalarr[] = $newval;
					}
				}
			}

			if ( is_array( $newvalarr ) && count( $newvalarr ) > 0 ) {
				$newvalarr = array_unique( $newvalarr );

				foreach ( $newvalarr as $newkey => $newval ) {
					$pattern = '/(\d+)/';
					preg_match_all( $pattern, $newval, $matches );
					$newval = $matches[0][0];
					if ( is_ssl() ) {
						$fid = str_replace( 'http://', 'https://', $upload_main_url . '/maincss_' . $newval . '.css' );
					} else {
						$fid = $upload_main_url . '/maincss_' . $newval . '.css';
					}
					$fid_dir = $upload_main_dir .'/maincss_' . $newval . '.css';

					$fid = esc_url_raw( $fid );

					if ( is_ssl() ) {
						$fid_material = str_replace( 'http://', 'https://', $upload_main_url . '/maincss_materialize_' . $newval . '.css' );
					} else {
						$fid_material = $upload_main_url . '/maincss_materialize_' . $newval . '.css';
					}
					$fid_material_dir = $upload_main_dir .'/maincss_materialize_' . $newval . '.css';

					$fid_material = esc_url_raw( $fid_material );

						/* $res = wp_cache_get( 'arflite_front_head_js_select_form_' . $newval );

					if ( ! $res ) { */
						$res = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id = %d', $newval ), 'ARRAY_A' ); //phpcs:ignore
						/*	wp_cache_set( 'arflite_front_head_js_select_form_' . $newval, $arfliteformcontroller->arfliteArraytoObj( $res ) );
					} */

					if ( isset( $res['is_template'] ) && isset( $res['status'] ) && $res['is_template'] == '0' && $res['status'] == 'published' ) {
						$arflite_func_val = apply_filters( 'arflite_hide_forms', $arfliteformcontroller->arflite_class_to_hide_form( $newval ), $newval );

						$form_css = maybe_unserialize( $res['form_css'] );
						if ( $arflite_func_val == '' ) {
							if ( isset( $form_css['arfinputstyle'] ) && $form_css['arfinputstyle'] != 'material' && file_exists( $fid_dir ) ) {
								wp_enqueue_style( 'arfliteformscss_' . $newval, $fid, array(), $arflite_jscss_version );
							}

							if ( isset( $form_css['arfinputstyle'] ) && $form_css['arfinputstyle'] == 'material' && file_exists( $fid_material_dir ) ) {
								wp_enqueue_style( 'arfliteformscss_materialize_' . $newval, $fid_material, array(), $arflite_jscss_version );
							}
							wp_enqueue_style( 'arflitedisplaycss' );
							wp_enqueue_style( 'flag_icon' );
						} else {
							if ( isset( $form_css['arfinputstyle'] ) && $form_css['arfinputstyle'] != 'material' && file_exists( $fid_dir ) ) {
								wp_enqueue_style( 'arfliteformscss_' . $newval, $fid, array(), $arflite_jscss_version );
							}

							if ( isset( $form_css['arfinputstyle'] ) && $form_css['arfinputstyle'] == 'material' && file_exists( $fid_material_dir ) ) {
								wp_enqueue_style( 'arfliteformscss_materialize_' . $newval, $fid_material, array(), $arflite_jscss_version );

							}
						}
					}
				}
			}

			foreach ( $arflite_form_type_with_id as $key => $value ) {

				$define_cs_position = '';
				if ( isset( $value['arf_link_type'] ) == 'fly' ) {
					$define_cs_position = ( isset( $value['arf_fly_position'] ) ? $value['arf_fly_position'] : '' );
				} else {
					$define_cs_position = ( isset( $value['arf_link_position'] ) ? $value['arf_link_position'] : '' );
				}
				$value['type']     = isset( $value['type'] ) ? $value['type'] : ( isset( $value['arf_link_type'] ) ? $value['arf_link_type'] : '' );
				$value['position'] = isset( $value['position'] ) ? $value['position'] : ( isset( $define_cs_position ) ? $define_cs_position : '' );
				$bgcolor           = isset( $value['bgcolor'] ) ? $value['bgcolor'] : ( isset( $value['arf_button_background_color'] ) ? $value['arf_button_background_color'] : '#8ccf7a' );
				$txtcolor          = isset( $value['txtcolor'] ) ? $value['txtcolor'] : ( isset( $value['arf_button_text_color'] ) ? $value['arf_button_text_color'] : '#ffffff' );
				$btn_angle         = isset( $value['angle'] ) ? $value['angle'] : ( isset( $value['arf_fly_button_angle'] ) ? $value['arf_fly_button_angle'] : '0' );
				$modal_bgcolor     = isset( $value['modal_bgcolor'] ) ? $value['modal_bgcolor'] : ( isset( $value['arf_background_overlay_color'] ) ? $value['arf_background_overlay_color'] : '#000000' );
				$overlay           = isset( $value['overlay'] ) ? $value['overlay'] : ( isset( $value['arf_background_overlay'] ) ? $value['arf_background_overlay'] : '0.6' );

				$is_fullscreen_act = ( isset( $value['is_fullscreen'] ) && $value['is_fullscreen'] == 'yes' ) ? $value['is_fullscreen'] : 'no';

				if ( isset( $value['arf_show_full_screen'] ) && $value['arf_show_full_screen'] == 'yes' ) {
					$is_fullscreen_act = 'yes';
				}

				$inactive_min = isset( $value['inactive_min'] ) ? $value['inactive_min'] : ( isset( $value['arf_inactive_min'] ) ? $value['arf_inactive_min'] : '0' );

				$modaleffect = isset( $value['modaleffect'] ) ? $value['modaleffect'] : ( isset( $value['arf_modaleffect'] ) ? $value['arf_modaleffect'] : 'no_animation' );

				$type = $value['type'];
				if ( isset( $value['arf_onclick_type'] ) && ! empty( $value['arf_onclick_type'] ) ) {
					$type = $value['arf_onclick_type'];
				}
			}
		}
	}

	public static function arflite_db_check() {
		global $ARFLiteMdlDb;
		$arf_db_version = get_option( 'arflite_db_version' );
		if ( ( $arf_db_version == '' || ! isset( $arf_db_version ) ) && IS_WPMU ) {
			$ARFLiteMdlDb->arfliteupgrade( $old_db_version );
		}
	}

	

	function arflite_start_session( $force = false ) {
		if ( version_compare( PHP_VERSION, '7.0.0' ) >= 0 ) {
			if ( ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE && ! is_admin() ) || $force == true ) {
				@session_start(
					array(
						'read_and_close' => false,
					)
				);
			}
		} elseif ( version_compare( PHP_VERSION, '5.4.0' ) >= 0 ) {
			if ( ( function_exists( 'session_status' ) && session_status() == PHP_SESSION_NONE && ! is_admin() ) || $force == true ) {
				@session_start();
			}
		} else {
			if ( ( session_id() == '' && ! is_admin() ) || $force == true ) {
				@session_start();
			}
		}
	}

	function arflite_parse_standalone_request() {

		$plugin = $this->arflite_get_param( 'plugin' );

		$action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

		$action = $this->arflite_get_param( $action );

		$controller = $this->arflite_get_param( 'controller' );

		if ( ! empty( $plugin ) && $plugin == 'ARFormslite' && ! empty( $controller ) ) {

			$this->arflite_standalone_route( $controller, $action );

			exit;
		}
	}

	function arflite_standalone_route( $controller, $action = '' ) {

		global $arfliteformcontroller;

		if ( $controller == 'forms' && ! in_array( $action, array( 'export', 'import' ) ) ) {
			$arfliteformcontroller->arflitepreview( $this->arflite_get_param( 'form' ) );
		} else {
			do_action( 'arflitestandaloneroute', $controller, $action );
		}
	}

	function arflite_get_param( $param, $default = '' ) {

		return ( isset( $_POST[ $param ] ) ? sanitize_text_field( $_POST[ $param ] ) : ( isset( $_GET[ $param ] ) ? sanitize_text_field( $_GET[ $param ] ) : $default ) ); //phpcs:ignore
	}

	function arflite_get_form_shortcode_legacy( $atts ){

		global $arformsmain;
		

		if( $arformsmain->arforms_is_pro_active() ){	
			$form_id = $atts['id'];

				if ( '' == $form_id ) {
				return 'Please select valid form';
			}

			global $wpdb, $tbl_arf_forms;

			$get_pro_form_id = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_forms . '` WHERE arf_is_lite_form = %d AND arf_lite_form_id = %d', 1, $form_id ) ); //phpcs:ignore
			
			if ( $get_pro_form_id > 0 ) {
				return do_shortcode( '[ARForms id=' . $get_pro_form_id . ']' );
			} else {
				return do_shortcode( '[ARForms id=' . $form_id. ']' );
			}
		} else {
			return $this->arflite_get_form_shortcode( $atts );
		}
	}

	function arflite_get_form_shortcode( $atts ) {

		global $arfliteskipshortcode, $arfliterecordcontroller, $arflite_loaded_form_unique_id_array, $arfliteformcontroller;

		wp_enqueue_style( 'arflitedisplaycss' );

		if ( $arfliteskipshortcode ) {

			$sc = '[ARForms';

			foreach ( $atts as $k => $v ) {
				$sc .= ' ' . $k . '="' . $v . '"';
			}

			return $sc . ']';
		}

		extract(
			shortcode_atts(
				array(
					'id'          => '',
					'key'         => '',
					'title'       => false,
					'description' => false,
					'readonly'    => false,
					'entry_id'    => false,
					'fields'      => array(),
				),
				$atts
			)
		);

		if( !preg_match( '/^(\d+)$/', $id ) ){
			$id = '';
		}

		do_action( 'ARFormslite_shortcode_atts', compact( 'id', 'key', 'title', 'description', 'readonly', 'entry_id', 'fields' ) );

		global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;
		
		$res = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id = %d AND arf_is_lite_form = %d', $id, 1 ), 'ARRAY_A' );//phpcs:ignore

		$res = ( isset( $res[0] ) && is_array( $res ) && count( $res ) > 0 ) ? $res[0] : $res;

		if( empty( $res ) ){
			return esc_html__( 'Please select valid form', 'arforms-form-builder' );
		}

		if ( ! empty( $res ) ) {
			if ( isset( $res['arforms_update_form'] ) && 1 == $res['arforms_update_form'] ) {
				do_action( 'arflite_rewrite_css_after_update', $id, $res['form_css'] );

				$wpdb->update(
					$tbl_arf_forms,
					array(
						'arforms_update_form' => 0,
					),
					array(
						'id' => $id,
					)
				);
			}
		}

		$values = isset( $res['options'] ) ? maybe_unserialize( $res['options'] ) : '';

		if ( isset( $values['display_title_form'] ) && $values['display_title_form'] == '0' ) {
			$is_title    = false;
			$description = false;
		} else {
			$is_title    = true;
			$description = true;
		}

		$arflite_data_uniq_id = '';
		if ( isset( $arflite_loaded_form_unique_id_array[ $id ]['normal'][0] ) ) {
			$arflite_data_uniq_id = current( $arflite_loaded_form_unique_id_array[ $id ]['normal'] );
			if ( is_array( $arflite_loaded_form_unique_id_array[ $id ]['normal'] ) ) {
				array_shift( $arflite_loaded_form_unique_id_array[ $id ]['normal'] );
			} else {
				unset( $arflite_loaded_form_unique_id_array[ $id ]['normal'] );
			}
		} else {
			$arflite_data_uniq_id = rand( 1, 99999 );
			if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
				$arflite_data_uniq_id = $id;
			}
		}

		if ( isset( $atts['arfsubmiterrormsg'] ) ) {
			$_REQUEST['arfsubmiterrormsg'] = $atts['arfsubmiterrormsg'];
		}
		global $is_gutenberg;
		$is_gutenberg = false;
		if( !empty( $atts['is_gutenberg'] ) && 'true' == $atts['is_gutenberg'] ){
			$is_gutenberg = true;
		}

		global $is_beaverbuilder;
		$is_beaverbuilder = false;
		if( !empty( $atts['is_beaverbuilder'] ) && 'true' == $atts['is_beaverbuilder'] ){
			$is_beaverbuilder = true;
		}
		
		global $is_divibuilder;
		$is_divibuilder = false;
		if( !empty( $atts['is_divibuilder'] ) && 'true' == $atts['is_divibuilder'] ){
			$is_divibuilder = true;
		}

		global $is_fusionbuilder;
		$is_fusionbuilder = false;
		if( !empty( $atts['is_fusionbuilder'] ) && 'true' == $atts['is_fusionbuilder'] ){
			$is_fusionbuilder = true;
		}
		
		require_once ARFLITE_VIEWS_PATH . '/arflite_front_form.php';
		$contents = arflite_get_form_builder_string( $id, $key, false, false, '', $arflite_data_uniq_id );
		$contents = apply_filters( 'arflite_pre_display_arfomrms', $contents, $id, $key );

		if ( ! empty( $_REQUEST['action'] ) && ( 'elementor' == $_REQUEST['action'] || 'elementor_ajax' == $_REQUEST['action'] ) ) {
			echo "<input type='hidden' name='arf_is_elementor' value='1' class='arf_is_elementor'>";
			do_action( 'arflite_load_assets_for_elementor', $id, sanitize_text_field( $_REQUEST['action'] ) );
		}

		if( !empty( $atts['is_gutenberg'] ) && 'true' == $atts['is_gutenberg'] ){
			do_action( 'arflite_load_assets_for_elementor', $id, 'elementor_ajax' );
		}

		return $contents;
	}

	function arflite_load_assets_for_elementor_callback( $form_id = '', $action = '' ) {

		global $arflite_jscss_version, $arfliterecordcontroller, $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;

		if ( empty( $form_id ) ) {
			return;
		}

		$temp_form_opts = $wpdb->get_row( $wpdb->prepare( 'SELECT `form_css` FROM `' . $tbl_arf_forms . '` WHERE id = %d', $form_id ) );//phpcs:ignore

		if ( empty( $temp_form_opts ) || $temp_form_opts == null ) {
			return;
		}

		$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
		$temp_opts       = maybe_unserialize( $temp_form_opts->form_css );
		$inputStyle      = isset( $temp_opts['arfinputstyle'] ) ? $temp_opts['arfinputstyle'] : 'material';

		$materialize_css = '';
		if ( $inputStyle == 'material' ) {
			$materialize_css = '_materialize';
			$is_material     = true;
		}

		if ( is_ssl() ) {
			$fid = str_replace( 'http://', 'https://', $upload_main_url . '/maincss' . $materialize_css . '_' . $form_id . '.css' );
		} else {
			$fid = $upload_main_url . '/maincss' . $materialize_css . '_' . $form_id . '.css';
		}

		$return_link        = '';
		$stylesheet_handler = 'arfformscss_' . $materialize_css . $form_id;

		wp_register_style( $stylesheet_handler, $fid, array(), $arflite_jscss_version );
		wp_print_styles( $stylesheet_handler );

		wp_register_style( 'arflitedisplaycss', ARFLITEURL . '/css/arflite_front.css', array(), $arflite_jscss_version );
		wp_print_styles( 'arflitedisplaycss' );

		wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arflite_jscss_version );

		wp_register_style( 'arflitedisplayfootercss', ARFLITEURL . '/css/arflite_front_footer.css', array(), $arflite_jscss_version );

		wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array(), $arflite_jscss_version );
		wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arflite_jscss_version );

		wp_register_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array(), $arflite_jscss_version );
		wp_register_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arflite_jscss_version );

		wp_register_script( 'jquery-maskedinput', ARFLITEURL . '/js/jquery.maskedinput.min.js', array(), $arflite_jscss_version );
		wp_register_script( 'intltelinput', ARFLITEURL . '/js/intlTelInput.min.js', array(), $arflite_jscss_version );
		wp_register_script( 'arformslite_phone_utils', ARFLITEURL . '/js/arf_phone_utils.js', array(), $arflite_jscss_version );
		wp_register_style( 'flag_icon', ARFLITEURL . '/css/flag_icon.css', array(), $arflite_jscss_version );

		wp_register_script( 'jquery-animatenumber', ARFLITEURL . '/js/jquery.animateNumber.js', array(), $arflite_jscss_version );
		wp_register_script( 'bootstrap-moment-with-locales', ARFLITEURL . '/bootstrap/js/moment-with-locales.js', array(), $arflite_jscss_version );
		wp_register_script( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/js/bootstrap-datetimepicker.js', array(), $arflite_jscss_version );
		wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $arflite_jscss_version );

		wp_register_script( 'jscolor', ARFLITEURL . '/js/jscolor.js', array(), $arflite_jscss_version );
		wp_register_script( 'arf-colorpicker-basic-js', ARFLITEURL . '/js/jquery.simple-color-picker.js', array(), $arflite_jscss_version );

		wp_register_style( 'nouislider', ARFLITEURL . '/css/nouislider.css', array(), $arflite_jscss_version );
		wp_register_script( 'nouislider', ARFLITEURL . '/js/nouislider.js', array(), $arflite_jscss_version );

		wp_register_script( 'arformslite-js', ARFLITEURL . '/js/arformslite.js', array(), $arflite_jscss_version );
		wp_print_scripts( 'arformslite-js' );

		if ( 'elementor_ajax' == $action ) {
			$arfliterecordcontroller->arflite_footer_js( false, true, true );
		} else {
			$arfliterecordcontroller->arflite_footer_js( true, false, false );
		}
	}

	function arflite_widget_text_filter( $content ) {

		$regex = '/\[\s*ARForms\s+.*\]/';

		return preg_replace_callback( $regex, array( $this, 'arflite_widget_text_filter_callback' ), $content );
	}

	function arflite_widget_text_filter_callback( $matches ) {

		if ( $matches[0] ) {
			$parts    = explode( 'id=', $matches[0] );
			$partsnew = explode( ' ', $parts[1] );
			$formid   = $partsnew[0];
			$formid   = str_replace( ']', '', $formid );
			$formid   = trim( $formid );
			global $arforms_lite_loaded;
			$arforms_lite_loaded[ $formid ] = true;
		}

		return do_shortcode( $matches[0] );
	}

	function arflite_widget_text_filter_popup( $content ) {

		$regex = '/\[\s*ARForms_popup\s+.*\]/';

		return preg_replace_callback( $regex, array( $this, 'arflite_widget_text_filter_callback_popup' ), $content );
	}

	function arflite_widget_text_filter_callback_popup( $matches ) {

		if ( $matches[0] ) {
			$parts    = explode( 'id=', $matches[0] );
			$partsnew = explode( ' ', $parts[1] );
			$formid   = $partsnew[0];
			$formid   = trim( $formid );
			global $arforms_lite_loaded;
			$arforms_lite_loaded[ $formid ] = true;
		}

		return do_shortcode( $matches[0] );
	}

	function arflite_get_postbox_class() {

		return 'postbox-container';
	}

	function arflite_set_deactivation_js( $hook ){
		global $pagenow, $arfliteversion;
		if ( $pagenow == 'plugins.php' ) {
			wp_register_script( 'arflite-feedback-popup-script', ARFLITEURL . '/js/arflite_deactivation_script.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'arflite-feedback-popup-script' );

			$scriptData = 'var arflite_detailsStrings = {
				"setup-difficult":"' . __( 'What was the dificult part?', 'arforms-form-builder' ) . '",
				"docs-improvement":"' . __( 'What can we describe more?', 'arforms-form-builder' ) . '",
				"features":"' . __( 'How could we improve?', 'arforms-form-builder' ) . '",
				"better-plugin":"' . __( 'Can you mention it?', 'arforms-form-builder' ) . '",
				"incompatibility":"' . __( 'With what plugin or theme is incompatible?', 'arforms-form-builder' ) . '",
				"bought-premium":"' . __( 'Please specify experience', 'arforms-form-builder' ) . '",
				"maintenance":"' . __( 'Please specify', 'arforms-form-builder' ) . '"
			};

			var pluginName = "' . esc_attr( 'ARFormslite' ) . '";
			var pluginSecurity = "' . wp_create_nonce( 'arflite_deactivate_plugin' ) . '";
			';

			wp_add_inline_script( 'arflite-feedback-popup-script', $scriptData );
		}
	}

	function arflite_set_deactivation_css( $hook ){
		global $pagenow, $arfliteversion;
		if ( $pagenow == 'plugins.php' ) {
			wp_register_style( 'arflite-feedback-popup-style', ARFLITEURL . '/css/arflite_deactivation_style.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-feedback-popup-style' );
		}
	}

	function arflite_set_js( $hook ) {
		global $arfliteversion,$wp_version,$arfliteajaxurl, $arformsmain, $pagenow, $ARFLiteMdlDb, $wpdb, $tbl_arf_forms;
		$jquery_handler       = 'jquery';
		$jq_draggable_handler = 'jquery-ui-draggable';
		if ( version_compare( $wp_version, '4.2', '<' ) ) {
			$jquery_handler       = 'jquery-custom';
			$jq_draggable_handler = 'jquery-ui-draggable-custom';
		}
		if ( isset( $hook ) && 'plugins.php' == $hook ) {
			global $wp_version;

			if ( version_compare( $wp_version, '4.5.0', '<' ) ) {
				deactivate_plugins( plugin_basename( 'arforms-form-builder/arforms-form-builder.php' ), true, false );
				$redirect_url = network_admin_url( 'plugins.php?deactivate=true' );
				wp_die( '<div class="arf_dig_sig_wp_notice"><p class="arf_dig_sig_wp_notice_text" >Please meet the minimum requirement of WordPress version 4.5 to activate ARForms<p class="arf_dig_sig_wp_notice_continue">Please <a href="javascript:void(0)" onclick="window.location.href=\'' . esc_url( $redirect_url ) . '\'">Click Here</a> to continue.</p></div>' );
			}
		}

		if ( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' ) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-entries' ) {
			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'bootstrap' );

			wp_enqueue_script( $jquery_handler );

			wp_enqueue_script( 'datatables' );
			wp_enqueue_script( 'buttons-colvis' );

			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'tipso' );

		} elseif( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-status' ){

			wp_enqueue_script( 'datatables' );
			wp_enqueue_script( 'buttons-colvis' );

			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'bootstrap' );

			wp_enqueue_script( $jquery_handler );

			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array(), $arfliteversion );

		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-settings' ) {
			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'bootstrap' );

			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'tipso' );

			wp_register_script( 'jquery-json', ARFLITEURL . '/js/jquery.json-2.4.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'jquery-json' );
			if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
				$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
				wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
				wp_enqueue_script( 'wp-theme-plugin-editor' );
				wp_enqueue_script( 'csslint' );
			}
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-import-export' ) {
			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'bootstrap' );

			wp_enqueue_script( 'bootstrap-datetimepicker' );
			wp_enqueue_script( 'bootstrap-moment-with-locales' );

			wp_enqueue_script( 'jquery-form', ARFLITEURL . '/js/jquery.form.js', array(), $arfliteversion );
			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'tipso' );
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && ( sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' ) && ! isset( $_REQUEST['arfaction'] ) ) {
			wp_enqueue_script( $jquery_handler );

			wp_enqueue_script( 'datatables' );
			wp_enqueue_script( 'buttons-colvis' );

			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );

			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'tipso' );
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' && ( sanitize_text_field( $_REQUEST['arfaction'] ) == 'edit' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'new' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'duplicate' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'update' ) ) {

			wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'bootstrap' );

			wp_enqueue_script( 'bootstrap-modernizr', ARFLITEURL . '/bootstrap/js/modernizr.js', array( $jquery_handler ), $arfliteversion, true );

			wp_enqueue_script( 'nouislider', ARFLITEURL . '/js/nouislider.js', array( $jquery_handler ), $arfliteversion, true );

			wp_enqueue_script( 'jscolor', ARFLITEURL . '/js/jscolor.js', array( $jquery_handler ), $arfliteversion );
			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( $jquery_handler ), $arfliteversion );
			wp_enqueue_script( 'tipso' );

			if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
				$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
				wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
				wp_enqueue_script( 'wp-theme-plugin-editor' );
				wp_enqueue_script( 'csslint' );
			}

			wp_enqueue_script( 'arformslite_editor_phone_utils', ARFLITEURL . '/js/arf_phone_utils.js', array(), $arfliteversion, true );
			wp_enqueue_script( 'arformslite_editor_phone_intl_input', ARFLITEURL . '/js/intlTelInput.min.js', array(), $arfliteversion, true );
			if ( version_compare( $wp_version, '5.0', '<' ) ) {
				wp_enqueue_script( 'wp-hooks', ARFLITEURL . '/js/hooks.js', array( $jquery_handler ), $arfliteversion );
			}

			wp_enqueue_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( $jquery_handler ), $arfliteversion );

			wp_enqueue_script( 'arformslite_hooks', ARFLITEURL . '/js/arformslite_hooks.js', array(), $arfliteversion );
			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array(), $arfliteversion );
			wp_enqueue_script( 'arformslite_admin_editor', ARFLITEURL . '/js/arformslite_admin_editor.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );

		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'AROrder-entries' ) {
			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array(), $arfliteversion );
			wp_enqueue_script( 'arformslite_selectpicker', ARFLITEURL . '/js/arflite_selectpicker.js', array( $jquery_handler ), $arfliteversion );
		}

		$field_type_label_array = array(
			'text'     => __( 'Single Line Text', 'arforms-form-builder' ),
			'textarea' => __( 'Multiline Text', 'arforms-form-builder' ),
			'checkbox' => __( 'Checkbox', 'arforms-form-builder' ),
			'radio'    => __( 'Radio', 'arforms-form-builder' ),
			'select'   => __( 'Dropdown', 'arforms-form-builder' ),
			'email'    => __( 'Email Address', 'arforms-form-builder' ),
			'number'   => __( 'Number', 'arforms-form-builder' ),
			'phone'    => __( 'Phone Number', 'arforms-form-builder' ),
			'date'     => __( 'Date', 'arforms-form-builder' ),
			'time'     => __( 'Time', 'arforms-form-builder' ),
			'url'      => __( 'Website/URL', 'arforms-form-builder' ),
			'image'    => __( 'Image URL', 'arforms-form-builder' ),
			'html'     => __( 'HTML', 'arforms-form-builder' ),
		);

		$field_type_label_array = apply_filters( 'arflite_field_type_label_filter', $field_type_label_array );

		$js_data = "__ARF_FIELD_TYPE_LABELS = '" . json_encode( $field_type_label_array ) . "';";

		$js_data .= "
            var __ARF_DEL_FORM_MSG = '" . addslashes( __( 'Are you sure you want to delete this form?', 'arforms-form-builder' ) ) . "'; 

            var __ARF_DEL_ENTRY_MSG = '" . addslashes( __( 'Are you sure you want to delete this entry?', 'arforms-form-builder' ) ) . "';

            var __ARF_DEL_FILE_MSG = '" . addslashes( __( 'Are you sure you want to delete this file?', 'arforms-form-builder' ) ) . "';

            var __ARF_RESET_STYLE_MSG = '" . addslashes( __( 'Are you sure want to reset style?', 'arforms-form-builder' ) ) . "';

            var __ARF_SELECT_FIELD_TEXT = '" . addslashes( __( 'Please select one or more record to perform action', 'arforms-form-builder' ) ) . " ';
            var __ARF_ADDIMG = '" . addslashes( __( 'Add Image', 'arforms-form-builder' ) ) . "';

            var __ARF_SEL_FIELD = '" . addslashes( __( 'Please Select Field', 'arforms-form-builder' ) ) . "';
            var __CLICKTOCOPY = '" . addslashes( __( 'Click to Copy', 'arforms-form-builder' ) ) . "';
            var __COPIED = '" . addslashes( __( 'copied', 'arforms-form-builder' ) ) . "';
            var __ARF_CSV_MSG = '" . addslashes( __( 'Please upload csv file', 'arforms-form-builder' ) ) . " ';

            var __ARF_PRESET_FILE_MSG = '" . addslashes( __( 'Please Enter preset title', 'arforms-form-builder' ) ) . " ';

            var __ARF_SELECT_VALIDACTION_MSG = '" . addslashes( __( 'Please select valid action', 'arforms-form-builder' ) ) . " ';

            var __ARF_DELETE_TEXT = '" . addslashes( __( 'Delete', 'arforms-form-builder' ) ) . "';

            var __ARF_CANCEL_TEXT = '" . addslashes( __( 'Cancel', 'arforms-form-builder' ) ) . "';

            var __ARF_RESET_TEXT = '" . addslashes( __( 'Reset', 'arforms-form-builder' ) ) . "';

            var __ARFLITE_DEL_IMG_MSG = '" . addslashes( __( 'Are you sure you want to delete this image?', 'arforms-form-builder' ) ) . "';

            var __ARFLITE_FORM_TITLE = '" . addslashes( __( 'Please enter form title', 'arforms-form-builder' ) ) . " ';
            ";
			$arf_blank_msg = $arformsmain->arforms_get_settings('blank_msg','general_settings');
			$arf_blank_msg = !empty( $arf_blank_msg ) ? $arf_blank_msg : addslashes(esc_html__('This field cannot be blank.','arforms-form-builder'));
		$traslated_text = "
            var __ARF_DEL_CONF_MSG  = '" . addslashes( __( 'Are you sure to delete configuration?', 'arforms-form-builder' ) ) . "';
            var __ARF_DEL_FIELD_MSG = '" . addslashes( __( 'Are you sure you want to  delete this field?', 'arforms-form-builder' ) ) . "';
            var __SEL_FIELD = '" . addslashes( __( 'Select Field', 'arforms-form-builder' ) ) . "';
            var __TRYAGAIN = '" . addslashes( __( 'Please try again', 'arforms-form-builder' ) ) . "';

            var __REMOVE_MSG = '" . addslashes( __( 'Successfully removed file', 'arforms-form-builder' ) ) . "';

            var __SEL_FORM = '" . addslashes( __( 'Please select form', 'arforms-form-builder' ) ) . "';

            var __ARFINVALIDFORMULA = '" . addslashes( __( 'Your formula is invalid', 'arforms-form-builder' ) ) . "';

            var __ARFVALIDFORMULA = '" . addslashes( __( 'Your formula is valid', 'arforms-form-builder' ) ) . "';

            var __ARF_SELECT_FIELD_TEXT = '" . addslashes( __( 'Please select one or more record to perform action', 'arforms-form-builder' ) ) . "';

            var __NOTHNG_SEL = '" . addslashes( __( 'Nothing Selected', 'arforms-form-builder' ) ) . "';

            var __ARF_NOT_REQUIRE = '" . addslashes( __( 'Click to mark as not compulsory field', 'arforms-form-builder' ) ) . "';

            var __ARF_REQUIRE = '" . addslashes( __( 'Click to mark as compulsory field', 'arforms-form-builder' ) ) . "';

            var __ARFMAINURL = '" . esc_url_raw( $arfliteajaxurl ) . "';

            var __ARFLITE_PLUGIN_URL = '" . ARFLITEURL . "';

            var __ARFDEFAULTTITLE = '" . addslashes( __( '(Click here to add text)', 'arforms-form-builder' ) ) . "';

            var __ARFDEFAULTDESCRIPTION = '" . addslashes( __( '(Click here to add description or instructions)', 'arforms-form-builder' ) ) . "';

            var __ARFDELETEURL = '" . admin_url( 'admin.php?page=ARForms&err=1' ) . "';

            var __ARFINVALID = '" . ( !empty( $arf_blank_msg ) ? $arf_blank_msg : addslashes(esc_html__('This field cannot be blank.', 'arforms-form-builder')) ) . "';

            var __ARFEQUALS = '" . addslashes( __( 'equals', 'arforms-form-builder' ) ) . "';

			var __ARFNOTEQUALS = '" . addslashes( __( 'not equals', 'arforms-form-builder' ) ) . "';
			var __ARFGREATER = '" . addslashes( __( 'greater than', 'arforms-form-builder' ) ) . "';
			var __ARFLESS = '" . addslashes( __( 'less than', 'arforms-form-builder' ) ) . "';
			var __ARFCONTAIN = '" . addslashes( __( 'contains', 'arforms-form-builder' ) ) . "';
			var __ARFNOTCONTAIN = '" . addslashes( __( 'not contains', 'arforms-form-builder' ) ) . "';
			var __ARFADDRULE = '" . addslashes( __( 'Please add one or more rules', 'arforms-form-builder' ) ) . "';

			var __ARFSHOW = '" . addslashes( __( 'Show', 'arforms-form-builder' ) ) . "';
			var __ARFHIDE = '" . addslashes( __( 'Hide', 'arforms-form-builder' ) ) . "';
			var __ARFENBALE = '" . addslashes( __( 'Enable', 'arforms-form-builder' ) ) . "';
			var __ARFDISABLE = '" . addslashes( __( 'Disable', 'arforms-form-builder' ) ) . "';
			var __ARFSETVALUE = '" . addslashes( __( 'Set Value of', 'arforms-form-builder' ) ) . "';

			var _ARFRADIOCHKIMGMSG = '" . addslashes( __( 'Are you sure you want to delete image?', 'arforms-form-builder' ) ) . "';

			var __ARF_BLANKMSG = '" . addslashes( __( 'This field cannot be blank', 'arforms-form-builder' ) ) . "';

			var __ARF_BLANKMSG_CHK = '" . addslashes( __( 'Select atleast one flag', 'arforms-form-builder' ) ) . "';

			var __ARF_DELETE_IMAGE_TEXT = '" . addslashes( __( 'Please, Select appropriate option', 'arforms-form-builder' ) ) . ' <br/> ' . addslashes( __( 'to change/delete image', 'arforms-form-builder' ) ) . "';
			var __ARF_ADD_TEXT = '" . addslashes( __( 'Add', 'arforms-form-builder' ) ) . "';
			var __ARF_DELETE_TEXT = '" . addslashes( __( 'Delete', 'arforms-form-builder' ) ) . "';

			var __ARF_CL_IF_TEXT = '" . addslashes( __( 'IF', 'arforms-form-builder' ) ) . "';
			var __ARF_CL_ALL_TEXT = '" . addslashes( __( 'All', 'arforms-form-builder' ) ) . "';
			var __ARF_CL_ANY_TEXT = '" . addslashes( __( 'Any', 'arforms-form-builder' ) ) . "';
			var __ARF_CL_THAN_TEXT = '" . addslashes( __( 'THEN', 'arforms-form-builder' ) ) . "';

			var __ARF_CL_SELECT_FIELD_TEXT = '" . addslashes( __( 'Select Field', 'arforms-form-builder' ) ) . "';

			var __ARF_IS_TEXT = '" . addslashes( __( 'is', 'arforms-form-builder' ) ) . "';

			var __ARF_THEN_EMAIL_SEND_TO_TEXT = '" . addslashes( __( 'Then Mail Send To', 'arforms-form-builder' ) ) . "';

			var __ARF_THEN_REDIRECT_TO_TEXT = '" . addslashes( __( 'Then Redirect to', 'arforms-form-builder' ) ) . "';

			var __ARF_UNTITLED_TEXT = '" . addslashes( __( 'Untitled', 'arforms-form-builder' ) ) . "';

			var __ARF_ADD_IMAGE_TEXT = '" . addslashes( __( 'Add Image', 'arforms-form-builder' ) ) . "';

			var __ARF_YES_TEXT = '" . addslashes( __( 'Yes', 'arforms-form-builder' ) ) . "';

			var __ARF_CANCEL_TEXT = '" . addslashes( __( 'Cancel', 'arforms-form-builder' ) ) . "';

			var __ARF_PAGE_ONLY_TEXT = '" . addslashes( __( 'Page', 'arforms-form-builder' ) ) . "';

			var __ARF_BEGIN_ONLY_TEXT = '" . addslashes( __( 'begin', 'arforms-form-builder' ) ) . "';

			var __ARF_ADD_ICON_TEXT = '" . addslashes( __( 'Add Icon', 'arforms-form-builder' ) ) . "';

			var __ARF_EXPORT_FORM_NOTE = '" . addslashes( __( 'To export this form, first you need to save it', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_AJAX_SAVE_FORM_ERROR = '" . addslashes( __( 'There is something error while saving form', 'arforms-form-builder' ) ) . "';

			var __ARFLITEIMAGESURL = '" . ARFLITEIMAGESURL . "';

			var __ARFLITE_LOADER_ICON = '" . ARFLITE_LOADER_ICON . "';

			var __ARFLITE_NO_ENTRY_FOUND = '" . addslashes( __( 'There is no entry found', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_NOT_CLEAR_DEFAULT_TEXT = '" . addslashes( __( 'Do not clear default text on focus.', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_CLEAR_DEFAULT_TEXT = '" . addslashes( __( 'Clear default text on focus.', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_PASS_VALIDATION = '" . addslashes( __( 'Pass the validation with default value.', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_NOT_PASS_VALIDATION = '" . addslashes( __( 'Do not pass the validation with default value.', 'arforms-form-builder' ) ) . "';

			var __CLICKTOEDIT = '" . addslashes( __( 'Click to edit', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_NO_FORM_FOUND = '" . addslashes( __( 'There is no any form found', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_NEXT_TEXT = '" . addslashes( __( 'Next', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_LAST_TEXT = '" . addslashes( __( 'Last', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_FIRST_TEXT = '" . addslashes( __( 'First', 'arforms-form-builder' ) ) . "';

			var __ARFLITE_PREVIOUS_TEXT = '" . addslashes( __( 'Previous', 'arforms-form-builder' ) ) . "';

            ";

		$arflite_hook_data = "
        	var field_convert_part1 = '" . addslashes( __( 'Field values will be lost once converted to', 'arforms-form-builder' ) ) . "';
        	var arflite_type_text = '" . addslashes( __( 'Type', 'arforms-form-builder' ) ) . "';
        	var field_converting_part1 = '" . addslashes( __( 'You are converting', 'arforms-form-builder' ) ) . "';
        	var field_converting_type_to = '" . addslashes( __( 'type to', 'arforms-form-builder' ) ) . "';
        	var field_converting_part2 = '" . addslashes( __( 'type, field options will be different from', 'arforms-form-builder' ) ) . "';
        	var field_coverting_to_text = '" . addslashes( __( 'type to', 'arforms-form-builder' ) ) . "';
        	var field_converting_part3 = '" . addslashes( __( 'Please do needful', 'arforms-form-builder' ) ) . "';
        	var field_converting_notice = '" . addslashes( __( 'Field type changing also may affect email notification section, conditional rule section, payment gateways configuration and other add-ons configuration. So it is highly recommend to verify all these settings after changing field type', 'arforms-form-builder' ) ) . "';
        ";

		wp_add_inline_script( 'arformslite_admin_editor', $js_data );
		wp_add_inline_script( 'arformslite_admin', $traslated_text );

		wp_localize_script(
			'arformslite_admin',
			'arflite_pro_data_obj',
			array(
				'arf_version'         => $arfliteversion,
				'arf_request_version' => get_bloginfo( 'version' ),
			)
		);

		wp_add_inline_script( 'arformslite_hooks', $arflite_hook_data );

		$page = !empty( $_SERVER['PHP_SELF']) ? sanitize_text_field(basename( $_SERVER['PHP_SELF'] )) : ''; //phpcs:ignore

		wp_register_script( 'arflite_insert_form_popup_script', ARFLITEURL . '/js/arflite_insert_form_popup.js', array(), $arfliteversion );

		if ( in_array( $page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) || ( isset( $_GET ) && isset( $_GET['page'] ) && $_GET['page'] == 'ARForms-entry-templates' ) ) {
			wp_enqueue_script( 'arflite_insert_form_popup_script' );

			$translated_msg = "
				var __ARFLITE_FORM_SELECT =  '" . addslashes( __( 'Please select a form', 'arforms-form-builder' ) ) . "';
				var __ARFLITE_CUSTOM_DISPLAY_MSG = '" . addslashes( __( 'Please select a custom display', 'arforms-form-builder' ) ) . "';
			";

			wp_add_inline_script( 'arflite_insert_form_popup_script', $translated_msg );

		} elseif ( in_array( $page, array( 'widgets.php' ) ) ) {

			/* wp_register_script( 'arformslite_gutenberg_script', ARFLITEURL . '/js/arflite_gutenberg_widget_script.js', array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components' ), $arfliteversion );

			wp_enqueue_script( 'arformslite_gutenberg_script' );

			wp_register_style( 'arformslite_gutenberg_style', ARFLITEURL . '/css/arflite_gutenberg_style.css', array(), $arfliteversion );

			wp_enqueue_style( 'arformslite_gutenberg_style' ); */

			$arformslite_forms = $tbl_arf_forms;

			$arforms_forms_lite_data = $wpdb->get_results( 'SELECT * FROM `' . $tbl_arf_forms . "` WHERE is_template=0 AND (status is NULL OR status = '' OR status = 'published') ORDER BY id DESC" ); //phpcs:ignore

			$arforms_forms_lite_list = array();
			$n                       = 0;
			foreach ( $arforms_forms_lite_data as $k => $value ) {
				$arforms_forms_lite_list[ $n ]['id']    = $value->id;
				$arforms_forms_lite_list[ $n ]['label'] = $value->name . ' (id: ' . $value->id . ')';
				$n++;
			}

			wp_localize_script( 'wp-block-editor', 'arformslite_list_for_gutenberg', $arforms_forms_lite_list );

		}

		/* if ( $pagenow == 'plugins.php' ) {
			wp_register_script( 'arflite-feedback-popup-script', ARFLITEURL . '/js/arflite_deactivation_script.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'arflite-feedback-popup-script' );

			$scriptData = 'var arflite_detailsStrings = {
				"setup-difficult":"' . __( 'What was the dificult part?', 'arforms-form-builder' ) . '",
				"docs-improvement":"' . __( 'What can we describe more?', 'arforms-form-builder' ) . '",
				"features":"' . __( 'How could we improve?', 'arforms-form-builder' ) . '",
				"better-plugin":"' . __( 'Can you mention it?', 'arforms-form-builder' ) . '",
				"incompatibility":"' . __( 'With what plugin or theme is incompatible?', 'arforms-form-builder' ) . '",
				"bought-premium":"' . __( 'Please specify experience', 'arforms-form-builder' ) . '",
				"maintenance":"' . __( 'Please specify', 'arforms-form-builder' ) . '"
			};

			var pluginName = "' . esc_attr( 'ARFormslite' ) . '";
			var pluginSecurity = "' . wp_create_nonce( 'arflite_deactivate_plugin' ) . '";
			';

			wp_add_inline_script( 'arflite-feedback-popup-script', $scriptData );
		} */
	}

	function arflite_set_css() {
		global $arfliteversion, $pagenow,$wp_version;


		if ( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' ) && ( sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-entries' || sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-import-export' || sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-status' ) ) {

			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );

			wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );

			wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $arfliteversion );
			wp_enqueue_style( 'bootstrap-datetimepicker' );

			wp_enqueue_style( 'datatables' );

		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-settings' ) {
			wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );
			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );
			if ( version_compare( $wp_version, '4.9.0', '>=' ) ) {
				wp_enqueue_style( 'wp-codemirror' );
			}
			wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-font-awesome' );
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-import-export' ) {
			wp_enqueue_style( 'arformslite_selectpicker', ARFLITEURL . '/css/arflite_selectpicker.css', array(), $arfliteversion );
			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] == 'ARForms' ) && ! isset( $_REQUEST['arfaction'] ) ) {
			wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'datatables' );
			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );
		} 
		elseif(isset ($_REQUEST['page']) && $_REQUEST['page'] == "ARForms-addons" )
		{
			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array(), $arfliteversion );
			wp_enqueue_script( 'tipso' );
			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );
		} elseif ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' && ( sanitize_text_field( $_REQUEST['arfaction'] ) == 'edit' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'new' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'duplicate' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'update' ) ) {
			wp_register_style( 'arflitedisplaycss_editor', ARFLITEURL . '/css/arflite_front.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflitedisplaycss_editor' );
			wp_register_style( 'flag_icon', ARFLITEURL . '/css/flag_icon.css', array(), $arfliteversion );
			wp_enqueue_style( 'flag_icon' );

			wp_register_style( 'nouislider', ARFLITEURL . '/css/nouislider.css', array(), $arfliteversion );
			wp_enqueue_style( 'nouislider' );

			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'tipso' );

			wp_register_style( 'arflite-font-awesome', ARFLITEURL . '/css/font-awesome.min.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-font-awesome' );
			wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $arfliteversion );
			wp_enqueue_style( 'bootstrap-datetimepicker' );

			wp_register_style( 'flag_icon', ARFLITEURL . '/css/flag_icon.css', array(), $arfliteversion );
			wp_enqueue_style( 'flag_icon' );
			if ( version_compare( $wp_version, '4.9.0', '>=' ) ) {
				wp_enqueue_style( 'wp-codemirror' );
			}
		}

		/* if ( $pagenow == 'plugins.php' ) {
			wp_register_style( 'arflite-feedback-popup-style', ARFLITEURL . '/css/arflite_deactivation_style.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-feedback-popup-style' );
		} else */ if ( $pagenow == 'widgets.php' ) {
			wp_register_style( 'arflite-widget-style', ARFLITEURL . '/css/arflite_widget_style.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-widget-style' );
		}
	}

	function arflite_getwpversion() {
		global $arfliteversion, $ARFLiteMdlDb, $arflitenotifymodel, $arfliteform, $arfliterecordmeta;
		$bloginformation = array();
		$str             = $ARFLiteMdlDb->arflite_get_rand_alphanumeric( 10 );

		if ( is_multisite() ) {
			$multisiteenv = 'Multi Site';
		} else {
			$multisiteenv = 'Single Site';
		}

		$bloginformation[] = $arflitenotifymodel->arflite_sitename();
		$bloginformation[] = $arfliteform->arflite_sitedesc();
		$bloginformation[] = home_url();
		$bloginformation[] = get_bloginfo( 'admin_email' );
		$bloginformation[] = $arfliterecordmeta->arflitewpversioninfo();
		$bloginformation[] = $arfliterecordmeta->arflitegetlanguage();
		$bloginformation[] = $arfliteversion;
		$bloginformation[] = !empty( $_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
		$bloginformation[] = $str;
		$bloginformation[] = $multisiteenv;

		$arflitenotifymodel->arflitechecksite( $str );

		$valstring  = implode( '||', $bloginformation );
		$encodedval = base64_encode( $valstring );

		$urltopost = $arfliteform->arflitegetsiteurl();
		$response  = wp_remote_post(
			$urltopost,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'body'        => array( 'wpversion' => $encodedval ),
				'cookies'     => array(),
			)
		);
	}

	function arflite_backup() {
		$databaseversion = get_option( 'arflite_db_version' );
		update_option( 'old_db_version', $databaseversion );
	}

	function arflite_upgrade_data() {
		global $arflitenewdbversion;

		if ( ! isset( $arflitenewdbversion ) || $arflitenewdbversion == '' ) {
			$arflitenewdbversion = get_option( 'arflite_db_version' );
		}

		if ( version_compare( $arflitenewdbversion, '1.6.3', '<' ) ) {
			$path = ARFLITE_FORMPATH . '/core/views/arflite_upgrade_latest_data.php';
			include $path;
			$this->arforms_send_anonymous_data_cron();
		}
	}

	function arflite_rmdirr( $dirname ) {

		if ( ! file_exists( $dirname ) ) {
			return false;
		}

		if ( is_file( $dirname ) ) {
			return unlink( $dirname );
		}

		$dir = dir( $dirname );
		while ( false !== $entry = $dir->read() ) {

			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}

			$this->arflite_rmdirr( "$dirname/$entry" );
		}

		$dir->close();
		return rmdir( $dirname );
	}

	function arflite_copyr( $source, $dest ) {
		global $wp_filesystem;

		if ( is_link( $source ) ) {
			return symlink( readlink( $source ), $dest );
		}

		if ( is_file( $source ) ) {
			return $wp_filesystem->copy( $source, $dest );
		}

		if ( ! is_dir( $dest ) ) {
			$wp_filesystem->mkdir( $dest );
		}

		$dir = dir( $source );
		while ( false !== $entry = $dir->read() ) {

			if ( $entry == '.' || $entry == '..' ) {
				continue;
			}

			$this->arflite_copyr( "$source/$entry", "$dest/$entry" );
		}

		$dir->close();
		return true;
	}

	function arflite_hide_update_notice_to_all_admin_users() {
		global $pagenow;

		if ( isset( $_GET ) && ( isset( $_GET['page'] ) && preg_match( '/ARForms*/', sanitize_text_field( $_GET['page'] ) ) ) || ( $pagenow == 'edit.php' && isset( $_GET ) && isset( $_GET['post_type'] ) && sanitize_text_field( $_GET['post_type'] ) == 'frm_display' ) ) {
			remove_all_actions( 'network_admin_notices', 10000 );
			remove_all_actions( 'user_admin_notices', 10000 );
			remove_all_actions( 'admin_notices', 10000 );
			remove_all_actions( 'all_admin_notices', 10000 );
		}
	}

	function arflite_front_assets() {
		global $arfliteversion, $arformsmain;

		$arfsettings_data = $arformsmain->arforms_get_settings( ['arfmainformloadjscss', 'arf_load_js_css'], 'general_settings' );
		$arf_load_js_css = !empty( $arfsettings_data['arf_load_js_css'] ) ? json_decode( $arfsettings_data['arf_load_js_css'], true ) : array();

		if( $arformsmain->arforms_is_pro_active() ){
            return;
        }
		
		if ( $arfsettings_data['arfmainformloadjscss'] == 1 ) {
			wp_enqueue_script( 'bootstrap-inputmask' );
			wp_enqueue_script( 'jquery-maskedinput' );
			wp_enqueue_script( 'arformslite_phone_utils' );
			wp_enqueue_script( 'wp-hooks' );
			wp_enqueue_script( 'arformslite_hooks' );
			wp_enqueue_script( 'arformslite-js' );

			wp_enqueue_style( 'arflitedisplaycss' );
			wp_enqueue_style( 'arflitedisplayfootercss' );
			wp_enqueue_style( 'flag_icon' );
			wp_enqueue_script( 'jqbootstrapvalidation' );
			if ( ! empty( $arf_load_js_css ) ) {
				if ( in_array( 'slider', $arf_load_js_css ) ) {
					wp_enqueue_script( 'bootstrap' );
				}

				if ( in_array( 'dropdown', $arf_load_js_css ) ) {
					/* wp_enqueue_script( 'bootstrap' ); */
					wp_enqueue_script( 'arformslite_selectpicker' );
					wp_enqueue_style( 'arformslite_selectpicker' );
				}
				if ( in_array( 'date_time', $arf_load_js_css ) ) {
					wp_enqueue_script( 'bootstrap' );
					wp_enqueue_script( 'bootstrap-moment-with-locales' );
					wp_enqueue_script( 'bootstrap-datetimepicker' );
					wp_enqueue_style( 'bootstrap-datetimepicker' );
				}
				if ( in_array( 'fontawesome', $arf_load_js_css ) ) {
					wp_enqueue_style( 'arflite-font-awesome' );
				}
				if ( in_array( 'mask_input', $arf_load_js_css ) ) {
					wp_enqueue_script( 'bootstrap' );
					wp_enqueue_script( 'bootstrap-inputmask' );
					wp_enqueue_script( 'jquery-maskedinput' );
					wp_enqueue_script( 'intltelinput' );
					wp_enqueue_script( 'arformslite_phone_utils' );
				}
				if ( in_array( 'tooltip', $arf_load_js_css ) ) {
					wp_enqueue_script( 'tipso' );
					wp_enqueue_style( 'tipso' );
				}
				if ( in_array( 'animate_number', $arf_load_js_css ) ) {
					wp_enqueue_script( 'jquery-animatenumber' );
				}
				if ( in_array( 'captcha', $arf_load_js_css ) ) {

					$re_lang = $arformsmain->arforms_get_settings('re_lang','general_settings');
					$re_lang = !empty( $re_lang ) ? $re_lang : 'en';
					$lang = $re_lang;

					wp_register_script( 'arflite-google-captcha-apijs', 'https://www.google.com/recaptcha/api.js?hl=' . $lang . '&onload=render_arflite_captcha&render=explicit', array(), $arfliteversion );
					wp_enqueue_script( 'arflite-google-captcha-apijs' );
				}
			}
		}
	}

	function arflite_print_all_admin_scripts() {
		global $arfliteversion,$wp_version;

		$jquery_handler       = 'jquery';
		$jq_draggable_handler = 'jquery-ui-draggable';
		if ( version_compare( $wp_version, '4.2', '<' ) ) {
			$jquery_handler       = 'jquery-custom';
			$jq_draggable_handler = 'jquery-ui-draggable-custom';
		}
		wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array( $jquery_handler ), $arfliteversion );
		wp_print_scripts( 'tipso' );

		wp_register_script( 'arflite_admin_js_ajax', ARFLITEURL . '/js/arformslite_admin.js', array(), $arfliteversion );
		wp_print_scripts( 'arflite_admin_js_ajax' );

		wp_register_script( 'arflite_admin_js_ajax_editor', ARFLITEURL . '/js/arformslite_admin_editor.js', array( $jquery_handler, $jq_draggable_handler ), $arfliteversion );
		wp_print_scripts( 'arflite_admin_js_ajax_editor' );

		wp_register_script( 'arformslite_selectpicker_js_ajax', ARFLITEURL . '/js/arflite_selectpicker.js', array(), $arfliteversion );
		wp_print_scripts( 'arformslite_selectpicker_js_ajax' );

		wp_register_script( 'bootstrap', ARFLITEURL . '/bootstrap/js/bootstrap.min.js', array( $jquery_handler ), $arfliteversion );
		wp_print_scripts( 'bootstrap' );

		wp_register_script( 'bootstrap-modernizr', ARFLITEURL . '/bootstrap/js/modernizr.js', array( $jquery_handler ), $arfliteversion, true );
		wp_print_scripts( 'bootstrap-modernizr' );

		wp_register_script( 'nouislider', ARFLITEURL . '/js/nouislider.js', array( $jquery_handler ), $arfliteversion, true );
		wp_print_scripts( 'nouislider' );

		if ( version_compare( $wp_version, '4.2', '<' ) ) {
			wp_print_scripts( 'jquery-ui-widget-custom' );
			wp_print_scripts( 'jquery-ui-mouse-custom' );

			wp_print_scripts( 'jquery-ui-resizable-custom' );
		} else {
			wp_print_scripts( 'jquery-ui-draggable' );
		}

		wp_print_scripts( 'admin-widgets' );

		wp_print_scripts( 'widgets' );

		wp_register_script( 'jquery-json', ARFLITEURL . '/js/jquery.json-2.4.js', array( $jquery_handler ), $arfliteversion );
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms' && isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) != '' ) {
			wp_print_scripts( 'jquery-json' );
		}

	}

	function arflite_reset_ratenow_notice() {
		$nextEvent = strtotime( '+60 days' );

		wp_schedule_single_event( $nextEvent, 'arflite_display_ratenow_popup' );

		update_option( 'arflite_display_rating_notice', 'no' );

		die;
	}

	function arflite_display_ratenow_popup_callback() {
		update_option( 'arflite_display_rating_notice', 'yes' );
	}

	function arflite_reset_ratenow_notice_never() {
		update_option( 'arflite_display_rating_notice', 'no' );
		update_option( 'arflite_never_display_rating_notice', 'true' );
		die;
	}

	function arflite_enqueue_notice_assets() {
		global $arflite_jscss_version;

		wp_register_script( 'arflite-admin-notices', ARFLITEURL . '/js/arflite-admin-notices.js', array(), $arflite_jscss_version );

		wp_enqueue_script( 'arflite-admin-notices' );
	}

	function arflite_display_notice_for_rating() {
		$display_notice       = get_option( 'arflite_display_rating_notice' );
		$display_notice_never = get_option( 'arflite_never_display_rating_notice' );

		if ( '' != $display_notice && 'yes' == $display_notice && ( '' == $display_notice_never || 'true' != $display_notice_never ) ) {
			$class           = 'notice notice-warning arflite-rate-notice is-dismissible';
			$message         = "Hey, you've been using <strong>ARForms Form Builder</strong> for a long time. <br>Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation. <br><br>Your help is much appreciated. Thank you very much";
			$rate_link       = 'https://wordpress.org/support/plugin/arforms-form-builder/reviews/';
			$rate_link_text  = __( 'OK, you deserve it', 'arforms-form-builder' );
			$close_btn_text  = __( 'No, Maybe later', 'arforms-form-builder' );
			$rated_link_text = __( 'I already did', 'arforms-form-builder' );

			printf( '<div class="%1$s"><p>%2$s</p><br/><br/><a href="%3$s" class="arflite_rate_link" target="_blank">%4$s</a><br/><a class="arflite_maybe_later_link" href="javascript:void(0);">%5$s</a><br/><a class="arflite_already_rated_link" href="javascript:void(0)">%6$s</a><br/>&nbsp;</div>', esc_attr( $class ), wp_kses( $message, wp_kses_allowed_html( 'post' ) ), esc_url_raw( $rate_link ), esc_html( $rate_link_text ), esc_attr( $close_btn_text ), esc_html( $rated_link_text ) );

		}
	}


	function arformslite_cs_register_element() {
		cornerstone_register_element( 'ARFormslite_CS', 'arformslite-cs', ARFLITE_CSDIR . '/includes/arformslite-cs' );
	}

	function arformslite_cs_icon_map( $icon_map ) {
		$icon_map['ARFORMS'] = ARFLITE_CSURL . '/assets/svg/ar_forms.svg';
		return $icon_map;
	}

	function arflite_add_new_version_release_note() {
		global $wp, $wpdb, $pagenow, $arfliteajaxurl, $arflite_plugin_slug, $wp_version, $arflitemaincontroller, $arfliteversion, $arformsmain;

		$popupData = '';
		$arf_slugs = array( 'ARForms', 'ARForms-entries', 'ARForms-settings', 'ARForms-import-export', 'ARForms-addons' );

		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $arf_slugs ) ) {
			$show_document_video = get_option( 'arflite_new_version_installed', 0 );

			if( $arformsmain->arforms_is_pro_active() ){
				update_option( 'arflite_new_version_installed', 0 );
				$show_document_video = '0';
			}

			if ( $show_document_video == '0' ) {
				return;
			}

			$popupData = '<div class="arf_modal_overlay arfactive">
                <div class="arf_whatsnew_popup_container_wrapper">
                    <div class="arf_popup_container arf_popup_container_whatnew_model arf_view_whatsnew_modal arfactive arf_whatsnew_model_larger">
                        <div class="arf_popup_container_header">' . __( "What's New in ARForms Form Builder", 'arforms-form-builder' ) . ' ' . $arfliteversion . '</div>
                        <div class="arfwhatsnew_modal_content arf_whatsnew_popup_content_container">

                            <div class="arf_whatsnew_popup_row">
                                <div class="arf_whatsnew_popup_inner_content">

                                    You can always refer our online documentation for all the features <a href="https://www.arformsplugin.com/documentation/1-getting-started-with-arforms/" target="_blank">here</a><br>
                                        <ul style="list-style-type: disc;">
											<li>Improved field sorting experience in editor with field swapping</li>
											<li>Other minor bug fixes</li>
                                        </ul>
                                </div>';

			$arf_addon_list_api_url = 'https://www.arformsplugin.com/addonlist/arf_addon_api_details.php';

			$args                           = array(
				'slug'            => $arflite_plugin_slug,
				'version'         => $arfliteversion,
				'other_variables' => $this->arflite_get_remote_post_params(),
			);
			$arf_addon_list_api_request_str = array(
				'body'       => array(
					'action'  => 'plugin_new_version_check',
					'request' => maybe_serialize( $args ),
					'api-key' => md5( home_url() ),
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
			);

			$arf_addon_raw_response_json = wp_remote_post( $arf_addon_list_api_url, $arf_addon_list_api_request_str );
			if ( ! is_wp_error( $arf_addon_raw_response_json ) ) {
				$arf_addon_raw_response_json  = $arf_addon_raw_response_json['body'];
				$arf_addon_raw_response       = json_decode( $arf_addon_raw_response_json, true );
				$count_arf_addon_raw_response = count( $arf_addon_raw_response );
				if ( ! empty( $arf_addon_raw_response ) && $count_arf_addon_raw_response > 0 ) {
					$arf_list_addon_width = ( 142 ) * ( $count_arf_addon_raw_response );
					$popupData           .= '<div class="arf_whatsnew_addons_list_title">' . __( 'Available Add-ons', 'arforms-form-builder' ) . '</div>';
					$popupData           .= '<div class="arf_whatsnew_addons_list_div" style="min-height:165px;">';
					$popupData           .= '<div class="arf_whatsnew_addons_list" style="width:' . $arf_list_addon_width . 'px;min-width:100%;">';

					foreach ( $arf_addon_raw_response as $arf_addon_raw_key => $arf_addon_raw ) {
						$available_for_lite = ! empty( $arf_addon_raw['available_for_lite'] ) ? $arf_addon_raw['available_for_lite'] : false;
						$popupData         .= '<div class="arf_whatsnew_add_on">';
						if ( false == $available_for_lite ) {
							$popupData .= '<span class="arf_add_on_for_pro_ribbon">' . __( 'Pro', 'arforms-form-builder' ) . '</span>';
						}
							$popupData     .= '<a href="' . $arf_addon_raw['arf_plugin_link'] . '" target="_blank">';
								$popupData .= '<img src="' . $arf_addon_raw['arf_plugin_image'] . '" width="82" height="82" />';
							$popupData     .= '</a>';
							$popupData     .= '<div class="arf_whatsnew_add_on_text">';
								$popupData .= '<a href="' . $arf_addon_raw['arf_plugin_link'] . '" target="_blank">' . $arf_addon_raw['arf_plugin_name'] . '</a>';
							$popupData     .= '</div>';
						$popupData         .= '</div>';
					}

					$popupData .= '</div>';
					$popupData .= '</div>';
				}
			}

					$popupData .= '</div></div>
                        <div class="arf_popup_footer arf_view_whatsnew_modal_footer">
                            <button class="rounded_button arf_btn_dark_blue" style="margin-right:7px;" name="arf_update_whatsnew_button" onclick="arflite_hide_update_notice();">' . __( 'OK', 'arforms-form-builder' ) . '</button>
                        </div>
                    </div>
                </div>
            </div>';

			$popupData .= '<script type="text/javascript">';
			$popupData .= 'jQuery(document).ready(function(){ jQuery("html").css("overflow","hidden");  });';
			$popupData .= 'function arflite_hide_update_notice(){
                var ishide = 1;
                jQuery.ajax({
                type: "POST",
                url: "' . $arfliteajaxurl . '",
                data: "action=arflite_dont_show_upgrade_notice&is_hide=" + ishide,
                success: function (res) {
                        jQuery(".arf_view_whatsnew_modal.arfactive").parents(".arf_modal_overlay.arfactive").removeClass("arfactive");
                        jQuery(".arf_view_whatsnew_modal.arfactive").removeClass("arfactive");
                        jQuery("html").css("overflow",""); 
                        return false;
                    }
                });
                return false;
            }';
			$popupData .= '</script>';
			echo $popupData; //phpcs:ignore
		}
	}

	function arflite_dont_show_upgrade_notice() {
		global $wp, $wpdb;
		delete_option( 'arflite_new_version_installed' );
		die();
	}


	function arflite_check_valid_file( $file_content = '' ) {
		if ( '' == $file_content ) {
			return true;
		}

		$arf_valid_pattern = '/(\<\?(php))/';

		if ( preg_match( $arf_valid_pattern, $file_content ) ) {
			return false;
		}
		return true;
	}

	function arflite_check_user_cap( $arflite_cap = '', $arflite_is_ajax_call = false ) {

		$arflite_errors = array();
		if ( true == $arflite_is_ajax_call ) {

			if ( ! current_user_can( $arflite_cap ) ) {

				$msg = __( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );

				array_push( $arflite_errors, $msg );
				array_push( $arflite_errors, 'capability_error' );
				return wp_json_encode( $arflite_errors );

			}
		}
		$arflite_nonce = isset( $_REQUEST['_wpnonce_arflite'] ) ? sanitize_text_field( $_REQUEST['_wpnonce_arflite'] ) : '';
		if ( '' == $arflite_nonce ) {
			$arflite_nonce = isset( $_POST['_wpnonce_arflite'] ) ? sanitize_text_field( $_POST['_wpnonce_arflite'] ) : '';
		}

		$arflite_verify_nonce_flag = wp_verify_nonce( $arflite_nonce, 'arflite_wp_nonce' );

		if ( ! $arflite_verify_nonce_flag ) {
			$msg = __( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

			array_push( $arflite_errors, $msg );

			array_push( $arflite_errors, 'security_error' );

			return wp_json_encode( $arflite_errors );

		}

		return 'success';

	}

	public function arflite_selectpicker_dom( $name = '', $id = '', $attr_class = '', $style = '', $default = '', $attrs = array(), $options = array(), $grouped = false, $options_cls = array(), $disable = false, $options_attr = array(), $is_form_field = false, $field = array(), $enable_autocomplete = false, $list_class = '', $list_id = '', $use_label_as_default = false ) {

		$return_dom = '';

		if ( ! $grouped ) {
			if ( empty( $default ) ) {
				$first_option_key = key( $options );
			} else {
				$first_option_key = $default;
			}

			$first_option_value = isset( $options[ $first_option_key ] ) ? $options[ $first_option_key ] : '';
		} else {
			if ( empty( $default ) ) {
				$first_group_key    = key( $options );
				$first_option_key   = key( $options[ $first_group_key ] );
				$first_option_value = ! empty( $options[ $first_group_key ][ $first_option_key ] ) ? $options[ $first_group_key ][ $first_option_key ] : '';
			} else {
				foreach ( $options as $k => $opt_arr ) {
					foreach ( $opt_arr as $opt_key => $opt_val ) {
						if ( $opt_key == $default ) {
							$first_option_value = $opt_val;
							break;
						}
					}
				}
			}
		}

		$attr_str = '';

		$input_cls = '';

		if ( ! empty( $attrs ) ) {
			foreach ( $attrs as $key => $value ) {
				if ( 'class' == $key ) {
					$input_cls = $value;
				} else {
					$attr_str .= ' ' . $key . '=\'' . $value . '\' ';
				}
			}
		}

		if ( $disable ) {
			$attr_class .= ' arf_disabled';
		}

		if ( $enable_autocomplete ) {
			$attr_class .= ' arf-has-autocomplete ';
		}

		$return_dom .= '<div class="arf_selectpicker_wrapper" style="' . $style . '">';

			$return_dom .= '<input type="' . ( $enable_autocomplete ? 'hidden' : 'text' ) . '" autocomplete="off" class="arf-selectpicker-input-control ' . $input_cls . '" id="' . $id . '" name="' . $name . '" value="' . $default . '" ' . $attr_str . '>';

			$return_dom .= '<dl class="arf-selectpicker-control ' . $attr_class . '" data-id="' . $id . '" data-name="' . $name . '">';

				$return_dom .= '<dt>';

					$return_dom .= '<span>';
		if ( true == $use_label_as_default ) {
			$return_dom .= '';
		} else {
			$return_dom .= $first_option_value;
		}
					$return_dom .= '</span>';

		if ( $enable_autocomplete ) {
			$return_dom .= '<input type="text" class="arf-selectpicker-autocomplete">';
		}

					$return_dom .= '<i class="arf-selectpicker-caret"></i>';

				$return_dom .= '</dt>';

				$return_dom .= '<dd>';

					$return_dom .= '<ul data-id="' . $id . '" id="' . $list_id . '" class="' . $list_class . '">';
		if ( ! $is_form_field ) {
			if ( ! $grouped ) {
				if ( ! empty( $options ) ) {
					foreach ( $options as $value => $label ) {
						$cls_attr = '';
						if ( ! empty( $options_cls[ $value ] ) ) {
							$cls_attr = $options_cls[ $value ];
						}

						$opts_attr = '';
						if ( isset( $options_attr['data-type'][ $value ] ) ) {
							$opts_attr = $options_attr['data-type'][ $value ];
							$opts_attr = ' data-type="' . $opts_attr . '"';
						}

						$option_condition = '';
						if ( isset( $options_attr['data-field-in-condition'][ $value ] ) ) {
							$option_condition = $options_attr['data-field-in-condition'][ $value ];
							$option_condition = ' data-field-in-condition="' . $option_condition . '"';
						}

						$opts_style = '';
						if ( isset( $options_attr['style'][ $value ] ) ) {
							$opts_style = $options_attr['style'][ $value ];
							$opts_style = ' style="' . $opts_style . '"';
						}

						$opts_val = '';
						if ( isset( $options_attr['value'][ $value ] ) ) {
							$opts_val = $options_attr['value'][ $value ];
							$opts_val = ' value="' . $opts_val . '"';
						}

						$opts_ids = '';
						if ( isset( $options_attr['id'][ $value ] ) ) {
							$opts_ids = $options_attr['id'][ $value ];
							$opts_ids = ' id="' . $opts_ids . '"';
						}

						$return_dom .= '<li class="' . $cls_attr . '" data-value="' . $value . '" data-label="' . htmlentities( $label ) . '" ' . $opts_ids . $opts_attr . $option_condition . $opts_style . $opts_val . '>' . $label . '</li>';
					}
				}
			} else {

				foreach ( $options as $k => $opt_arr ) {
					if ( ! empty( $k ) ) {
						$extracted   = explode( '||', $k );
						$return_dom .= '<ol>' . $extracted[1] . '</ol>';
					}
					foreach ( $opt_arr as $opt_val => $opt_label ) {
						$cls_attr = '';
						if ( ! empty( $options_cls[ $opt_val ] ) ) {
							$cls_attr = $options_cls[ $opt_val ];
						}

						$opts_attr = '';
						if ( isset( $options_attr['data-type'][ $opt_val ] ) ) {
							$opts_attr = $options_attr['data-type'][ $opt_val ];
							$opts_attr = ' data-type="' . $opts_attr . '"';
						}

						$option_condition = '';
						if ( isset( $options_attr['data-field-in-condition'][ $opt_val ] ) ) {
							$option_condition = $options_attr['data-field-in-condition'][ $opt_val ];
							$option_condition = ' data-field-in-condition="' . $option_condition . '"';
						}

						$opts_style = '';
						if ( isset( $options_attr['style'][ $opt_val ] ) ) {
							$opts_style = $options_attr['style'][ $opt_val ];
							$opts_style = ' style="' . $opts_style . '"';
						}

						$opts_val = '';
						if ( isset( $options_attr['value'][ $opt_val ] ) ) {
							$opts_val = $options_attr['value'][ $opt_val ];
							$opts_val = ' value="' . $opts_val . '"';
						}

						$return_dom .= '<li class="' . $cls_attr . '" data-value="' . $opt_val . '" data-label="' . htmlentities( $opt_label ) . '" ' . $opts_attr . $option_condition . $opts_style . $opts_val . '>' . $opt_label . '</li>';
					}
				}
			}
		} elseif ( $is_form_field ) {

			if ( ! empty( $field['options'] ) ) {
				$count_i = 0;
				foreach ( $field['options'] as $opt_key => $opt ) {
					$field_val = apply_filters( 'arfdisplaysavedfieldvalue', $opt, $opt_key, $field );

					$opt = apply_filters( 'show_field_label', $opt, $opt_key, $field );

					if ( is_array( $opt ) ) {
						$opt = $opt['label'];
						if ( $field_val['value'] == '(Blank)' ) {
							$field_val['value'] = '';
						}
						$field_val = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
					}

					if ( $count_i == 0 and $field_val == '' ) {
						$opt = esc_html__( 'Please select', 'arforms-form-builder' );
						if ( $use_label_as_default ) {
							$opt                          = ! empty( $field['name'] ) ? $field['name'] : esc_html__( 'Choose Option', 'arforms-form-builder' );
							$field['options'][ $opt_key ] = $opt;
						}
					}

					$cls_attr = '';
					if ( ! empty( $options_cls[ $value ] ) ) {
						$cls_attr = $options_cls[ $value ];
					}

					$field['value']          = isset( $field['value'] ) ? $field['value'] : '';
					$arfdefault_selected_val = ( isset( $field['separate_value'] ) ) ? $field['default_value'] : $field['value'];
					if ( isset( $field['set_field_value'] ) ) {
						$arfdefault_selected_val = $field['set_field_value'];
					}

					$return_dom .= '<li class="' . $cls_attr . '" data-pos="' . $count_i . '" data-value="' . $field_val . '" data-label="' . htmlentities( $opt ) . '">' . $opt . '</li>';

					$count_i++;
				}
			}
		}
					$return_dom .= '</ul>';

				$return_dom .= '</dd>';
			$return_dom     .= '</dl>';

		$return_dom .= '</div>';

		return $return_dom;

	}

}

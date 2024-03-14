<?php
/**
 * This file is used for creating sidebar menu.
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
		$flag                                     = 0;
		$role_capabilities                        = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'roles_and_capabilities'
			)
		);// db call ok; no-cache ok.
		$roles_and_capabilities_unserialized_data = maybe_unserialize( $role_capabilities );
		$capabilities                             = explode( ',', $roles_and_capabilities_unserialized_data['roles_and_capabilities'] );

		if ( is_super_admin() ) {
			$cpb_role = 'administrator';
		} else {
			$cpb_role = check_user_roles_captcha_booster();
		}
		switch ( $cpb_role ) {
			case 'administrator':
				$privileges = 'administrator_privileges';
				$flag       = $capabilities[0];
				break;

			case 'author':
				$privileges = 'author_privileges';
				$flag       = $capabilities[1];
				break;

			case 'editor':
				$privileges = 'editor_privileges';
				$flag       = $capabilities[2];
				break;

			case 'contributor':
				$privileges = 'contributor_privileges';
				$flag       = $capabilities[3];
				break;

			case 'subscriber':
				$privileges = 'subscriber_privileges';
				$flag       = $capabilities[4];
				break;

			default:
				$privileges = 'other_privileges';
				$flag       = $capabilities[5];
				break;
		}

		foreach ( $roles_and_capabilities_unserialized_data as $key => $value ) {
			if ( $privileges === $key ) {
				$privileges_value = $value;
				break;
			}
		}

		$full_control = explode( ',', $privileges_value );
		if ( ! defined( 'FULL_CONTROL' ) ) {
			define( 'FULL_CONTROL', "$full_control[0]" );
		}
		if ( ! defined( 'CAPTCHA_SETUP_CAPTCHA_BOOSTER' ) ) {
			define( 'CAPTCHA_SETUP_CAPTCHA_BOOSTER', "$full_control[1]" );
		}
		if ( ! defined( 'LOGS_SETTINGS_CAPTCHA_BOOSTER' ) ) {
			define( 'LOGS_SETTINGS_CAPTCHA_BOOSTER', "$full_control[2]" );
		}
		if ( ! defined( 'ADVANCE_SECURITY_CAPTCHA_BOOSTER' ) ) {
			define( 'ADVANCE_SECURITY_CAPTCHA_BOOSTER', "$full_control[3]" );
		}
		if ( ! defined( 'GENERAL_SETTINGS_CAPTCHA_BOOSTER' ) ) {
			define( 'GENERAL_SETTINGS_CAPTCHA_BOOSTER', "$full_control[4]" );
		}
		if ( ! defined( 'EMAIL_TEMPLATES_CAPTCHA_BOOSTER' ) ) {
			define( 'EMAIL_TEMPLATES_CAPTCHA_BOOSTER', "$full_control[5]" );
		}
		if ( ! defined( 'ROLES_AND_CAPABILITIES_CAPTCHA_BOOSTER' ) ) {
			define( 'ROLES_AND_CAPABILITIES_CAPTCHA_BOOSTER', "$full_control[6]" );
		}
		if ( ! defined( 'SYSTEM_INFORMATION_CAPTCHA_BOOSTER' ) ) {
			define( 'SYSTEM_INFORMATION_CAPTCHA_BOOSTER', "$full_control[7]" );
		}
		$check_captcha_booster_wizard = get_option( 'captcha-booster-wizard-set-up' );
		if ( '1' === $flag ) {
			if ( $check_captcha_booster_wizard ) {
				add_menu_page( $cpb_captcha_booster_breadcrumb, $cpb_captcha_booster_breadcrumb, 'read', 'cpb_captcha_booster', '', plugins_url( 'assets/global/img/icons.png', dirname( __FILE__ ) ) );
			} else {
				add_menu_page( $cpb_captcha_booster_breadcrumb, $cpb_captcha_booster_breadcrumb, 'read', 'cpb_wizard_captcha_booster', '', plugins_url( 'assets/global/img/icons.png', dirname( __FILE__ ) ) );
				add_submenu_page( $cpb_captcha_booster_type_breadcrumb, $cpb_captcha_booster_type_breadcrumb, '', 'read', 'cpb_wizard_captcha_booster', 'cpb_wizard_captcha_booster' );
			}
			add_submenu_page( 'cpb_captcha_booster', $cpb_captcha_booster_type_breadcrumb, $cpb_captcha_setup_menu, 'read', 'cpb_captcha_booster', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_captcha_booster' );
			add_submenu_page( $cpb_error_message_common, $cpb_error_message_common, '', 'read', 'cpb_error_message', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_error_message' );
			add_submenu_page( $cpb_display_settings_title, $cpb_display_settings_title, '', 'read', 'cpb_display_settings', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_display_settings' );


			add_submenu_page( 'cpb_captcha_booster', $cpb_live_traffic_title, $cpb_logs_menu, 'read', 'cpb_live_traffic', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_live_traffic' );
			add_submenu_page( $cpb_recent_login_log_title, $cpb_recent_login_log_title, '', 'read', 'cpb_login_logs', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_login_logs' );
			add_submenu_page( $cpb_visitor_logs_title, $cpb_visitor_logs_title, '', 'read', 'cpb_visitor_logs', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_visitor_logs' );

			add_submenu_page( 'cpb_captcha_booster', $cpb_blocking_options, $cpb_advance_security_menu, 'read', 'cpb_blocking_options', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_blocking_options' );
			add_submenu_page( $cpb_manage_ip_addresses, $cpb_manage_ip_addresses, '', 'read', 'cpb_manage_ip_addresses', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_manage_ip_addresses' );
			add_submenu_page( $cpb_manage_ip_ranges, $cpb_manage_ip_ranges, '', 'read', 'cpb_manage_ip_ranges', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_manage_ip_ranges' );
			add_submenu_page( $cpb_country_blocks_menu, $cpb_country_blocks_menu, '', 'read', 'cpb_country_blocks', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_country_blocks' );

			add_submenu_page( 'cpb_captcha_booster', $cpb_alert_setup_menu, $cpb_general_settings_menu, 'read', 'cpb_alert_setup', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_alert_setup' );
			add_submenu_page( $cpb_other_settings_menu, $cpb_other_settings_menu, '', 'read', 'cpb_other_settings', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_other_settings' );

			add_submenu_page( 'cpb_captcha_booster', $cpb_email_templates_menu, $cpb_email_templates_menu, 'read', 'cpb_email_templates', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_email_templates' );

			add_submenu_page( 'cpb_captcha_booster', $cpb_roles_and_capabilities_menu, $cpb_roles_and_capabilities_menu, 'read', 'cpb_roles_and_capabilities', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_roles_and_capabilities' );
			add_submenu_page( 'cpb_captcha_booster', $cpb_support_forum, $cpb_support_forum, 'read', 'https://wordpress.org/support/plugin/wp-captcha-booster', '' );
			add_submenu_page( 'cpb_captcha_booster', $cpb_system_information_menu, $cpb_system_information_menu, 'read', 'cpb_system_information', false === $check_captcha_booster_wizard ? 'cpb_wizard_captcha_booster' : 'cpb_system_information' );
			add_submenu_page( 'cpb_captcha_booster', $cpb_premium, $cpb_premium, 'read', 'https://tech-banker.com/captcha-booster/pricing/', '' );
		}

		if ( ! function_exists( 'cpb_wizard_captcha_booster' ) ) {
			/**
			 * This function is used for creating cpb_wizard_captcha_booster menu.
			 */
			function cpb_wizard_captcha_booster() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/wizard/wizard.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/wizard/wizard.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_captcha_booster' ) ) {
			/**
			 * This function is used for creating cpb_captcha_booster menu.
			 */
			function cpb_captcha_booster() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/captcha-type.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/captcha-type.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_error_message' ) ) {
			/**
			 * This function is used for creating cpb_error_message menu.
			 */
			function cpb_error_message() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/error-mesage.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/error-mesage.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_display_settings' ) ) {
			/**
			 * This function is used for creating cpb_display_settings menu.
			 */
			function cpb_display_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/display-settings.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/captcha-setup/display-settings.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_alert_setup' ) ) {
			/**
			 * This function is used for creating cpb_alert_setup menu.
			 */
			function cpb_alert_setup() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/general-settings/alert-setup.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/general-settings/alert-setup.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_live_traffic' ) ) {
			/**
			 * This function is used for creating cpb_live_traffic menu.
			 */
			function cpb_live_traffic() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/live-traffic.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/live-traffic.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_login_logs' ) ) {
			/**
			 * This function is used for creating cpb_login_logs menu.
			 */
			function cpb_login_logs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/login-logs.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/login-logs.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_visitor_logs' ) ) {
			/**
			 * This function is used for creating cpb_live_traffic menu.
			 */
			function cpb_visitor_logs() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/visitor-logs.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/logs/visitor-logs.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_blocking_options' ) ) {
			/**
			 * This function is used for creating cpb_blocking_options menu.
			 */
			function cpb_blocking_options() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/blocking-options.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/blocking-options.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_manage_ip_addresses' ) ) {
			/**
			 * This function is used for creating cpb_manage_ip_addresses menu.
			 */
			function cpb_manage_ip_addresses() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/manage-ip-addresses.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/manage-ip-addresses.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_manage_ip_ranges' ) ) {
			/**
			 * This function is used for creating cpb_manage_ip_ranges menu.
			 */
			function cpb_manage_ip_ranges() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/manage-ip-ranges.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/manage-ip-ranges.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_country_blocks' ) ) {
			/**
			 * This function is used for creating cpb_country_blocks menu.
			 */
			function cpb_country_blocks() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/country-blocks.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/security-settings/country-blocks.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_email_templates' ) ) {
			/**
			 * This function is used for creating cpb_email_templates menu.
			 */
			function cpb_email_templates() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/email-templates/email-templates.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/email-templates/email-templates.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_other_settings' ) ) {
			/**
			 * This function is used for creating cpb_other_settings menu.
			 */
			function cpb_other_settings() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/general-settings/other-settings.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/general-settings/other-settings.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_roles_and_capabilities' ) ) {
			/**
			 * This function is used for creating cpb_roles_and_capabilities menu.
			 */
			function cpb_roles_and_capabilities() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/roles-and-capabilities/roles-and-capabilities.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/roles-and-capabilities/roles-and-capabilities.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}

		if ( ! function_exists( 'cpb_system_information' ) ) {
			/**
			 * This function is used for creating cpb_system_information menu.
			 */
			function cpb_system_information() {
				global $wpdb;
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/header.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/sidebar.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/queries.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'views/system-information/system-information.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'views/system-information/system-information.php';
				}
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/footer.php';
				}
			}
		}
	}
}

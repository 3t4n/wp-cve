<?php // @codingStandardsIgnoreLine
/**
 * Plugin Name: Captcha, Anti Spam, Security, Spam Protection - Captcha Booster
 * Plugin URI: https://tech-banker.com/captcha-booster/
 * Description: Captcha Plugin for WordPress can be used for login, registration, password recovery, comments, popular contact forms.
 * Author: Tech Banker
 * Author URI: https://tech-banker.com/captcha-booster/
 * Version: 2.0.33
 * License: GPLv3
 * Text Domain: wp-captcha-booster
 * Domain Path: /languages
 *
 * @package wp-captcha-booster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
/* Constant Declaration */
if ( ! defined( 'CAPTCHA_BOOSTER_FILE' ) ) {
	define( 'CAPTCHA_BOOSTER_FILE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'CAPTCHA_BOOSTER_DIR_PATH' ) ) {
	define( 'CAPTCHA_BOOSTER_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CAPTCHA_BOOSTER_PLUGIN_DIRNAME' ) ) {
	define( 'CAPTCHA_BOOSTER_PLUGIN_DIRNAME', plugin_basename( dirname( __FILE__ ) ) );
}
if ( ! defined( 'CAPTCHA_BOOSTER_LOCAL_TIME' ) ) {
	define( 'CAPTCHA_BOOSTER_LOCAL_TIME', strtotime( date_i18n( 'Y-m-d H:i:s' ) ) );
}
if ( ! defined( 'TECH_BANKER_URL' ) ) {
	define( 'TECH_BANKER_URL', 'https://tech-banker.com' );
}
if ( ! defined( 'TECH_BANKER_BETA_URL' ) ) {
	define( 'TECH_BANKER_BETA_URL', 'https://tech-banker.com/captcha-booster/' );
}
if ( ! defined( 'TECH_BANKER_SERVICES_URL' ) ) {
	define( 'TECH_BANKER_SERVICES_URL', 'https://tech-banker.com' );
}
if ( ! defined( 'TECH_BANKER_STATS_URL' ) ) {
	define( 'TECH_BANKER_STATS_URL', 'http://stats.tech-banker-services.org' );
}
if ( ! defined( 'CAPTCHA_BOOSTER_VERSION_NUMBER' ) ) {
	define( 'CAPTCHA_BOOSTER_VERSION_NUMBER', '2.0.33' );
}

$memory_limit_captcha_booster = intval( ini_get( 'memory_limit' ) );
if ( ! extension_loaded( 'suhosin' ) && $memory_limit_captcha_booster < 512 ) {
	ini_set( 'memory_limit', '512M' ); // @codingStandardsIgnoreLine
}

ini_set( 'max_execution_time', 6000 ); // @codingStandardsIgnoreLine
ini_set( 'max_input_vars', 10000 ); // @codingStandardsIgnoreLine

if ( ! function_exists( 'install_script_for_captcha_booster' ) ) {
	/**
	 * This function is used to create Tables in Database.
	 */
	function install_script_for_captcha_booster() {
		global $wpdb;
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// db call ok; no-cache ok.
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id ); // @codingStandardsIgnoreLine
				$version = get_option( 'captcha_booster_version_number' );
				if ( $version < '2.0.3' ) {
					if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-install-script-captcha-booster.php' ) ) {
						include CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-install-script-captcha-booster.php';
					}
				}
				restore_current_blog();
			}
		} else {
			$version = get_option( 'captcha_booster_version_number' );
			if ( $version < '2.0.3' ) {
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-install-script-captcha-booster.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-install-script-captcha-booster.php';
				}
			}
		}
	}
}

if ( ! function_exists( 'captcha_booster' ) ) {
	/**
	 * This function is used to return Parent Table name with prefix.
	 */
	function captcha_booster() {
		global $wpdb;
		return $wpdb->prefix . 'captcha_booster';
	}
}

if ( ! function_exists( 'captcha_booster_ip_locations' ) ) {
	/**
	 * This function is used to return ip locations Table name with prefix.
	 */
	function captcha_booster_ip_locations() {
		global $wpdb;
		return $wpdb->prefix . 'captcha_booster_ip_locations';
	}
}

if ( ! function_exists( 'captcha_booster_meta' ) ) {
	/**
	 * This function is used to return Meta Table name with prefix.
	 */
	function captcha_booster_meta() {
		global $wpdb;
		return $wpdb->prefix . 'captcha_booster_meta';
	}
}

if ( ! function_exists( 'get_others_capabilities_captcha_booster' ) ) {
	/**
	 * This function is used to get all the roles available in WordPress
	 */
	function get_others_capabilities_captcha_booster() {
		$user_capabilities = array();
		if ( function_exists( 'get_editable_roles' ) ) {
			foreach ( get_editable_roles() as $role_name => $role_info ) {
				foreach ( $role_info['capabilities'] as $capability => $values ) {
					if ( ! in_array( $capability, $user_capabilities, true ) ) {
						array_push( $user_capabilities, $capability );
					}
				}
			}
		} else {
			$user_capabilities = array(
				'manage_options',
				'edit_plugins',
				'edit_posts',
				'publish_posts',
				'publish_pages',
				'edit_pages',
				'read',
			);
		}
		return $user_capabilities;
	}
}

if ( ! function_exists( 'check_user_roles_captcha_booster' ) ) {
	/**
	 * This function is used for checking roles of different users.
	 */
	function check_user_roles_captcha_booster() {
		global $current_user;
		$user = $current_user ? new WP_User( $current_user ) : wp_get_current_user();
		return $user->roles ? $user->roles[0] : false;
	}
}

/**
 * This function is used to create link for Pro Editions.
 *
 * @param string $plugin_link .
 */
function captcha_booster_action_links( $plugin_link ) {
	$plugin_link[] = '<a href="https://tech-banker.com/captcha-booster/pricing/" style="color: red; font-weight: bold;" target="_blank">Go Pro!</a>';
	return $plugin_link;
}

/**
 * This function is used to convert number to integer.
 *
 * @param integer $long .
 */
function long2ip_captcha_booster( $long ) {
	// Valid range: 0.0.0.0 -> 255.255.255.255.
	if ( $long < 0 || $long > 4294967295 ) {
		return false;
	}
	$ip = '';
	for ( $i = 3;$i >= 0;$i-- ) {
		$ip   .= (int) ( $long / pow( 256, $i ) );
		$long -= (int) ( $long / pow( 256, $i ) ) * pow( 256, $i );
		if ( $i > 0 ) {
			$ip .= '.';
		}
	}
	return $ip;
}

$version = get_option( 'captcha_booster_version_number' );
if ( $version >= '2.0.3' ) {

	if ( ! function_exists( 'get_users_capabilities_captcha_booster' ) ) {
		/**
		 * This function is used to get users capabilities.
		 */
		function get_users_capabilities_captcha_booster() {
			global $wpdb;
			$capabilities              = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// db call ok; no-cache ok.
			$core_roles                = array(
				'manage_options',
				'edit_plugins',
				'edit_posts',
				'publish_posts',
				'publish_pages',
				'edit_pages',
				'read',
			);
			$unserialized_capabilities = maybe_unserialize( $capabilities );
			return isset( $unserialized_capabilities['capabilities'] ) ? $unserialized_capabilities['capabilities'] : $core_roles;
		}
	}

	if ( ! function_exists( 'captcha_booster_settings_action_links' ) ) {
		/**
		 * This function is used to create link for Plugin Settings.
		 *
		 * @param array $action .
		 */
		function captcha_booster_settings_action_links( $action ) {
			global $wpdb;
			$user_role_permission = get_users_capabilities_captcha_booster();
			$settings_link        = '<a href = "' . admin_url( 'admin.php?page=cpb_captcha_booster' ) . '"> Settings </a>';
			array_unshift( $action, $settings_link );
			return $action;
		}
	}

	if ( is_admin() ) {
		if ( ! function_exists( 'backend_js_css_for_captcha_booster' ) ) {
			/**
			 * This function is used for including js and css files for backend.
			 */
			function backend_js_css_for_captcha_booster() {
				$pages_captcha_booster = array(
					'cpb_wizard_captcha_booster',
					'cpb_captcha_booster',
					'cpb_error_message',
					'cpb_display_settings',
					'cpb_live_traffic',
					'cpb_login_logs',
					'cpb_visitor_logs',
					'cpb_blocking_options',
					'cpb_manage_ip_addresses',
					'cpb_manage_ip_ranges',
					'cpb_country_blocks',
					'cpb_alert_setup',
					'cpb_other_settings',
					'cpb_email_templates',
					'cpb_roles_and_capabilities',
					'cpb_system_information',
				);
				if ( in_array( isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '', $pages_captcha_booster, true ) ) { // WPCS: CSRF ok, input var ok.
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'captcha-booster-custom.js', plugins_url( 'assets/global/plugins/custom/js/custom.js', __FILE__ ) );
					wp_enqueue_script( 'captcha-booster-validate.js', plugins_url( 'assets/global/plugins/validation/jquery.validate.js', __FILE__ ) );
					wp_enqueue_script( 'captcha-booster-datatables.js', plugins_url( 'assets/global/plugins/datatables/media/js/jquery.datatables.js', __FILE__ ) );
					wp_enqueue_script( 'captcha-booster-fngetfilterednodes.js', plugins_url( 'assets/global/plugins/datatables/media/js/fngetfilterednodes.js', __FILE__ ) );
					wp_enqueue_script( 'captcha-booster-toastr.js', plugins_url( 'assets/global/plugins/toastr/toastr.js', __FILE__ ) );
					wp_enqueue_script( 'captcha-booster-colpick.js', plugins_url( 'assets/global/plugins/colorpicker/colpick.js', __FILE__ ) );
					if ( is_ssl() ) {
						wp_enqueue_script( 'captcha-booster-maps_script.js', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyC4rVG7IsNk9pKUO_uOZuxQO4FmF6z03Ks' );
					} else {
						wp_enqueue_script( 'captcha-booster-maps_script.js', 'http://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyC4rVG7IsNk9pKUO_uOZuxQO4FmF6z03Ks' );
					}
					wp_enqueue_style( 'captcha-booster-simple-line-icons.css', plugins_url( 'assets/global/plugins/icons/icons.css', __FILE__ ) );
					wp_enqueue_style( 'captcha-booster-components.css', plugins_url( 'assets/global/css/components.css', __FILE__ ) );
					wp_enqueue_style( 'captcha-booster-custom.css', plugins_url( 'assets/admin/layout/css/captcha-booster-custom.css', __FILE__ ) );
					if ( is_rtl() ) {
						wp_enqueue_style( 'captcha-booster-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'captcha-booster-layout.css', plugins_url( 'assets/admin/layout/css/layout-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'captcha-booster-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom-rtl.css', __FILE__ ) );
					} else {
						wp_enqueue_style( 'captcha-booster-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom.css', __FILE__ ) );
						wp_enqueue_style( 'captcha-booster-layout.css', plugins_url( 'assets/admin/layout/css/layout.css', __FILE__ ) );
						wp_enqueue_style( 'captcha-booster-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom.css', __FILE__ ) );
					}
					wp_enqueue_style( 'captcha-booster-default.css', plugins_url( 'assets/admin/layout/css/themes/default.css', __FILE__ ) );
					wp_enqueue_style( 'captcha-booster-toastr.min.css', plugins_url( 'assets/global/plugins/toastr/toastr.css', __FILE__ ) );
					wp_enqueue_style( 'captcha-booster-jquery-ui.css', plugins_url( 'assets/global/plugins/datepicker/jquery-ui.css', __FILE__ ), false, '2.0', false );
					wp_enqueue_style( 'captcha-booster-datatables.foundation.css', plugins_url( 'assets/global/plugins/datatables/media/css/datatables.foundation.css', __FILE__ ) );
					wp_enqueue_style( 'captcha-booster-colpick.css', plugins_url( 'assets/global/plugins/colorpicker/colpick.css', __FILE__ ) );
				}
			}
		}
		add_action( 'admin_enqueue_scripts', 'backend_js_css_for_captcha_booster' );
	}

	if ( ! function_exists( 'js_frontend_for_captcha_booster' ) ) {
		/**
		 * This function is used for including js files for frontend.
		 */
		function js_frontend_for_captcha_booster() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'captcha-booster-front-end-script.js', plugins_url( 'assets/global/plugins/custom/js/front-end-script.js', __FILE__ ) );
		}
	}

	if ( ! function_exists( 'create_sidebar_menu_for_captcha_booster' ) ) {
		/**
		 * This function is used to create Admin Sidebar Menus.
		 */
		function create_sidebar_menu_for_captcha_booster() {
			global $wpdb, $current_user;
			$user_role_permission = get_users_capabilities_captcha_booster();
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
				include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
			}
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/sidebar-menu.php' ) ) {
				include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/sidebar-menu.php';
			}
		}
	}

	if ( ! function_exists( 'create_topbar_menu_for_captcha_booster' ) ) {
		/**
		 * This function is used to create Topbar Menus.
		 */
		function create_topbar_menu_for_captcha_booster() {
			global $wpdb, $current_user, $wp_admin_bar;
			$roles_and_capabilities      = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// db call ok; no-cache ok.
			$roles_and_capabilities_data = maybe_unserialize( $roles_and_capabilities );

			if ( 'enable' === $roles_and_capabilities_data['show_captcha_booster_top_bar_menu'] ) {
				$user_role_permission = get_users_capabilities_captcha_booster();
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
					include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
				}
				if ( get_option( 'captcha-booster-wizard-set-up' ) ) {
					if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/admin-bar-menu.php' ) ) {
						include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/admin-bar-menu.php';
					}
				}
			}
		}
	}

	if ( ! function_exists( 'helper_file_for_captcha_booster' ) ) {
		/**
		 * This function is used to create Class and Functions to perform operations.
		 */
		function helper_file_for_captcha_booster() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_captcha_booster();
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-captcha-booster.php' ) ) {
				include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/class-dbhelper-captcha-booster.php';
			}
		}
	}

	if ( ! function_exists( 'ajax_register_for_captcha_booster' ) ) {
		/**
		 * This function is used to Register Ajax.
		 */
		function ajax_register_for_captcha_booster() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_captcha_booster();
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php' ) ) {
				include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations.php';
			}
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/action-library.php' ) ) {
				include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/action-library.php';
			}
		}
	}

	if ( ! function_exists( 'validate_ip_captcha_booster' ) ) {
		/**
		 * This function is used for validating ip address.
		 *
		 * @param string $ip .
		 */
		function validate_ip_captcha_booster( $ip ) {
			if ( strtolower( $ip ) === 'unknown' ) {
				return false;
			}
			$ip = sprintf( '%u', ip2long( $ip ) );

			if ( false !== $ip && -1 !== $ip ) {
				$ip = sprintf( '%u', $ip );

				if ( $ip >= 0 && $ip <= 50331647 ) {
					return false;
				}
				if ( $ip >= 167772160 && $ip <= 184549375 ) {
					return false;
				}
				if ( $ip >= 2130706432 && $ip <= 2147483647 ) {
					return false;
				}
				if ( $ip >= 2851995648 && $ip <= 2852061183 ) {
					return false;
				}
				if ( $ip >= 2886729728 && $ip <= 2887778303 ) {
					return false;
				}
				if ( $ip >= 3221225984 && $ip <= 3221226239 ) {
					return false;
				}
				if ( $ip >= 3232235520 && $ip <= 3232301055 ) {
					return false;
				}
				if ( $ip >= 4294967040 ) {
					return false;
				}
			}
			return true;
		}
	}

	if ( ! function_exists( 'get_ip_address_for_captcha_booster' ) ) {
		/**
		 * This function returns the IP Address of the user.
		 */
		function get_ip_address_for_captcha_booster() {
			static $ip = null;
			if ( isset( $ip ) ) {
				return $ip;
			}

			global $wpdb;
			$data                = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key=%s', 'other_settings'
				)
			);// WPCS: db call ok; no-cache ok.
			$other_settings_data = maybe_unserialize( $data );

			switch ( $other_settings_data['ip_address_fetching_method'] ) {
				case 'REMOTE_ADDR':
					if ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // @codingStandardsIgnoreLine.
						if ( ! empty( $_SERVER['REMOTE_ADDR'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) { // @codingStandardsIgnoreLine.
							$ip = wp_unslash( $_SERVER['REMOTE_ADDR'] ); // @codingStandardsIgnoreLine.
							return $ip;
						}
					}
					break;

				case 'HTTP_X_FORWARDED_FOR':
					if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: Input var ok.
						if ( strpos( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ), ',' ) !== false ) { // WPCS: Input var ok, sanitization ok.
							$iplist = explode( ',', wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ); // WPCS: Input var ok, sanitization ok.
							foreach ( $iplist as $ip_address ) {
								if ( validate_ip_captcha_booster( $ip_address ) ) {
									$ip = $ip_address;
									return $ip;
								}
							}
						} else {
							if ( validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) { // WPCS: Input var ok, sanitization ok.
								$ip = wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ); // WPCS: Input var ok, sanitization ok.
								return $ip;
							}
						}
					}
					break;

				case 'HTTP_X_REAL_IP':
					if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_X_REAL_IP'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) ) ) { // WPCS: Input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ); // WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					break;

				case 'HTTP_CF_CONNECTING_IP':
					if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) ) { // WPCS: Input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ); // WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					break;

				default:
					if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) ) { // WPCS: CSRF ok, input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ); // WPCS: CSRF ok, input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: Input var ok.
						if ( strpos( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ), ',' ) !== false ) { // WPCS: CSRF ok, input var ok, sanitization ok.
							$iplist = explode( ',', wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ); // WPCS: CSRF ok, input var ok, sanitization ok.
							foreach ( $iplist as $ip_address ) {
								if ( validate_ip_captcha_booster( $ip_address ) ) {
									$ip = $ip_address;
									return $ip;
								}
							}
						} else {
							if ( validate_ip_captcha_booster( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: CSRF ok, input var ok, sanitization ok.
								$ip = wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ); // WPCS: CSRF ok, input var ok, sanitization ok.
								return $ip;
							}
						}
					}
					if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_X_FORWARDED'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) ) ) { // WPCS: CSRF ok, input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ); // WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) ) ) { // WPCS: CSRF ok, input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ); // WPCS: CSRF ok, input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_FORWARDED_FOR'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) ) ) { // WPCS: CSRF ok, input var ok.
							$ip = wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ); // WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_FORWARDED'] ) ) { // WPCS: Input var ok.
						if ( ! empty( $_SERVER['HTTP_FORWARDED'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) ) ) { // WPCS: Input var ok, sanitization ok.
							$ip = wp_unslash( $_SERVER['HTTP_FORWARDED'] ); // WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // @codingStandardsIgnoreLine.
						if ( ! empty( $_SERVER['REMOTE_ADDR'] ) && validate_ip_captcha_booster( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) {  // @codingStandardsIgnoreLine.
							$ip = wp_unslash( $_SERVER['REMOTE_ADDR'] ); // @codingStandardsIgnoreLine.
							return $ip;
						}
					}
					break;
			}
			return '0.0.0.0';
		}
	}

	if ( ! function_exists( 'get_ip_location_captcha_booster' ) ) {
		/**
		 * This function returns the location of the IP Address.
		 *
		 * @param string $ip_address .
		 */
		function get_ip_location_captcha_booster( $ip_address ) {
			global $wpdb;
			$core_data              = '{"ip":"0.0.0.0","country_code":"","country_name":"","region_code":"","region_name":"","city":"","latitude":0,"longitude":0}';
			$ip_location_meta_value = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_ip_locations WHERE ip=%s', $ip_address
				)
			);// WPCS: db call ok, no-cache ok.
			if ( '' != $ip_location_meta_value ) { // WPCS: loose comparison ok.
				return $ip_location_meta_value;
			} else {
				$api_call = TECH_BANKER_SERVICES_URL . '/api-server/getipaddress.php?ip_address=' . $ip_address;
				if ( ! function_exists( 'curl_init' ) ) {
					$json_data = @file_get_contents( $api_call );  // @codingStandardsIgnoreLine.
				} else {
					$ch = curl_init();  // @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_URL, $api_call );  // @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) );  // @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );  // @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  // @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );  // @codingStandardsIgnoreLine.

					$json_data = curl_exec( $ch );  // @codingStandardsIgnoreLine.
				}
				$ip_location_array = false === json_decode( $json_data ) ? json_decode( $core_data ) : json_decode( $json_data );
				if ( '' != $ip_location_array ) { // WPCS: loose comparison ok.
					$ip_location_array_data                 = array();
					$ip_location_array_data['ip']           = $ip_location_array->ip;
					$ip_location_array_data['country_code'] = $ip_location_array->country_code;
					$ip_location_array_data['country_name'] = $ip_location_array->country_name;
					$ip_location_array_data['region_code']  = $ip_location_array->region_code;
					$ip_location_array_data['region_name']  = $ip_location_array->region_name;
					$ip_location_array_data['city']         = $ip_location_array->city;
					$ip_location_array_data['latitude']     = $ip_location_array->latitude;
					$ip_location_array_data['longitude']    = $ip_location_array->longitude;

					$wpdb->insert( captcha_booster_ip_locations(), $ip_location_array_data ); // WPCS: db call ok.
				}
				return false === json_decode( $json_data ) ? json_decode( $core_data ) : json_decode( $json_data );
			}
		}
	}

	if ( ! function_exists( 'blocking_visitors_captcha_booster' ) ) {
		/**
		 * This function is used to Block IP Address.
		 */
		function blocking_visitors_captcha_booster() {
			global $wpdb;
			$count_ip                        = 0;
			$flag                            = 0;
			$ip_address                      = '::1' === get_ip_address_for_captcha_booster() ? sprintf( '%u', ip2long( '0.0.0.0' ) ) : sprintf( '%u', ip2long( get_ip_address_for_captcha_booster() ) );
			$location                        = get_ip_location_captcha_booster( long2ip_captcha_booster( $ip_address ) );
			$error_message_data              = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s',
					'error_message'
				)
			);// db call ok; no-cache ok.
			$error_message_unserialized_data = maybe_unserialize( $error_message_data );

			$meta_values_ip_blocks = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key IN(%s,%s)',
					'block_ip_address',
					'block_ip_range'
				)
			);// db call ok; no-cache ok.
			foreach ( $meta_values_ip_blocks as $data ) {
				$ip_address_data_array = maybe_unserialize( $data->meta_value );
				if ( 'block_ip_address' === $data->meta_key ) {
					if ( $ip_address_data_array['ip_address'] === $ip_address ) {
						$count_ip = 1;
						break;
					}
				} else {
					$ip_range_address = explode( ',', $ip_address_data_array['ip_range'] );
					if ( $ip_address >= $ip_range_address[0] && $ip_address <= $ip_range_address[1] ) {
						$flag = 1;
						break;
					}
				}
			}
			if ( 1 === $count_ip || 1 === $flag ) {
				if ( 1 === $count_ip ) {
					$replace_address_data = str_replace( '[ip_address]', long2ip_captcha_booster( $ip_address ), $error_message_unserialized_data['for_blocked_ip_address_error'] );
					wp_die( $replace_address_data ); // WPCS: XSS ok.
				} elseif ( 1 === $flag ) {
					$replace_range = str_replace( '[ip_range]', long2ip_captcha_booster( $ip_range_address[0] ) . '-' . long2ip_captcha_booster( $ip_range_address[1] ), $error_message_unserialized_data['for_blocked_ip_range_error'] );
					wp_die( $replace_range ); // WPCS: XSS ok.
				}
			}
		}
	}

	if ( ! function_exists( 'wp_schedule_captcha_booster' ) ) {
		/**
		 * This function is used to Create Schedules.
		 *
		 * @param string $cron_name .
		 * @param string $blocked_time .
		 */
		function wp_schedule_captcha_booster( $cron_name, $blocked_time ) {
			if ( ! wp_next_scheduled( $cron_name ) ) {
				switch ( $blocked_time ) {
					case '1Hour':
						$this_time = 60 * 60;
						break;

					case '12Hour':
						$this_time = 12 * 60 * 60;
						break;

					case '24hours':
						$this_time = 24 * 60 * 60;
						break;

					case '48hours':
						$this_time = 2 * 24 * 60 * 60;
						break;

					case 'week':
						$this_time = 7 * 24 * 60 * 60;
						break;

					case 'month':
						$this_time = 30 * 24 * 60 * 60;
						break;
				}
			}
			wp_schedule_event( time() + $this_time, $blocked_time, $cron_name );
		}
	}

	$scheulers         = _get_cron_array();
	$current_scheduler = array();

	foreach ( $scheulers as $value => $key ) {
		$arr_key = array_keys( $key );
		foreach ( $arr_key as $value ) {
			array_push( $current_scheduler, $value );
		}
	}

	if ( isset( $current_scheduler[0] ) ) {
		if ( ! defined( 'SCHEDULER_NAME' ) ) {
			define( 'SCHEDULER_NAME', $current_scheduler[0] );
		}

		if ( strstr( $current_scheduler[0], 'ip_address_unblocker_' ) ) {
			add_action( $current_scheduler[0], 'unblock_script_captcha_booster' );
		} elseif ( strstr( $current_scheduler[0], 'ip_range_unblocker_' ) ) {
			add_action( $current_scheduler[0], 'unblock_script_captcha_booster' );
		}
	}

	if ( ! function_exists( 'unblock_script_captcha_booster' ) ) {
		/**
		 * This function is used to Unblock IP Address.
		 */
		function unblock_script_captcha_booster() {
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/unblock-script.php' ) ) {
				$nonce_unblock_script = wp_create_nonce( 'unblock_script' );
				global $wpdb;
				include_once CAPTCHA_BOOSTER_DIR_PATH . 'lib/unblock-script.php';
			}
		}
	}

	if ( ! function_exists( 'wp_unschedule_captcha_booster' ) ) {
		/**
		 * This function is used to Unschedule a previously scheduled cron job.
		 *
		 * @param string $cron_name .
		 */
		function wp_unschedule_captcha_booster( $cron_name ) {
			if ( wp_next_scheduled( $cron_name ) ) {
				$db_cron = wp_next_scheduled( $cron_name );
				wp_unschedule_event( $db_cron, $cron_name );
			}
		}
	}

	if ( ! function_exists( 'captcha_booster_smart_ip_detect_crawler' ) ) {
		/**
		 * This function is used to detect the bot ip addresses.
		 */
		function captcha_booster_smart_ip_detect_crawler() {
			$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] ); // @codingStandardsIgnoreLine.
			// A list of some common words used only for bots and crawlers.
			$bot_identifiers = array(
				'bot',
				'slurp',
				'crawler',
				'spider',
				'curl',
				'facebook',
				'fetch',
				'scoutjet',
				'bingbot',
				'AhrefsBot',
				'spbot',
				'robot',
			);
			// See if one of the identifiers is in the UA string.
			foreach ( $bot_identifiers as $identifier ) {
				if ( strpos( $user_agent, $identifier ) !== false ) {
					return true;
				}
			}
			return false;
		}
	}

	if ( ! function_exists( 'captcha_booster_visitor_logs' ) ) {
		/**
		 * This function is used to insert Visitor Logs data.
		 */
		function captcha_booster_visitor_logs() {
			if ( ! is_admin() && ! defined( 'DOING_CRON' ) ) {
				if ( ! captcha_booster_smart_ip_detect_crawler() ) {
					global $wpdb, $current_user;
					$username   = $current_user->user_login;
					$parent_id  = $wpdb->get_var(
						$wpdb->prepare(
							'SELECT id FROM ' . $wpdb->prefix . 'captcha_booster WHERE type = %s',
							'logs'
						)
					);// WPCS: db call ok; no-cache ok.
					$ip         = get_ip_address_for_captcha_booster();
					$ip_address = '::1' === $ip ? sprintf( '%u', ip2long( '0.0.0.0' ) ) : sprintf( '%u', ip2long( $ip ) );
					$get_ip     = get_ip_location_captcha_booster( long2ip_captcha_booster( $ip_address ) );

					$insert_live_traffic              = array();
					$insert_live_traffic['type']      = 'visitor_logs';
					$insert_live_traffic['parent_id'] = $parent_id;
					$wpdb->insert( captcha_booster(), $insert_live_traffic ); // WPCS: db call ok; no-cache ok.

					$last_id = $wpdb->insert_id;

					$insert_live_traffic                    = array();
					$insert_live_traffic['username']        = $username;
					$insert_live_traffic['user_ip_address'] = $ip_address;
					$insert_live_traffic['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // WPCS: Input var ok, sanitization ok.
					$insert_live_traffic['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : ''; // @codingStandardsIgnoreLine.
					$location                               = '' == $get_ip->country_name && '' == $get_ip->city ? '' : '' == $get_ip->country_name ? '' : '' == $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name; // WPCS: loose comparison ok.
					$insert_live_traffic['location']        = $location;
					$insert_live_traffic['latitude']        = $get_ip->latitude;
					$insert_live_traffic['longitude']       = $get_ip->longitude;
					$insert_live_traffic['date_time']       = CAPTCHA_BOOSTER_LOCAL_TIME;
					$insert_live_traffic['meta_id']         = $last_id;

					$insert_data               = array();
					$insert_data['meta_id']    = $last_id;
					$insert_data['meta_key']   = 'visitor_logs_data'; // WPCS: slow query ok.
					$insert_data['meta_value'] = maybe_serialize( $insert_live_traffic ); // WPCS: slow query ok.
					$wpdb->insert( captcha_booster_meta(), $insert_data ); // WPCS: db call ok; no-cache ok.
				}
			}
		}
	}

	if ( ! function_exists( 'cron_scheduler_for_intervals_captcha_booster' ) ) {
		/**
		 * This function is used to cron scheduler for intervals.
		 *
		 * @param string $schedules .
		 */
		function cron_scheduler_for_intervals_captcha_booster( $schedules ) {
			$schedules['1Hour']   = array(
				'interval' => 60 * 60,
				'display'  => 'Every 1 Hour',
			);
			$schedules['12Hour']  = array(
				'interval' => 60 * 60 * 12,
				'display'  => 'Every 12 Hours',
			);
			$schedules['Daily']   = array(
				'interval' => 60 * 60 * 24,
				'display'  => 'Daily',
			);
			$schedules['24hours'] = array(
				'interval' => 60 * 60 * 24,
				'display'  => 'Every 24 Hours',
			);
			$schedules['48hours'] = array(
				'interval' => 60 * 60 * 48,
				'display'  => 'Every 48 Hours',
			);
			$schedules['week']    = array(
				'interval' => 60 * 60 * 24 * 7,
				'display'  => 'Every 1 Week',
			);
			$schedules['month']   = array(
				'interval' => 60 * 60 * 24 * 30,
				'display'  => 'Every 1 Month',
			);
			return $schedules;
		}
	}

	if ( ! function_exists( 'call_captcha_booster' ) ) {
		/**
		 * This function is used to Manage Captcha Settings for frontend.
		 */
		function call_captcha_booster() {
			global $wpdb;
			$captcha_type  = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key = %s', 'captcha_type'
				)
			);// db call ok; no-cache ok.
			$captcha_array = array();
			foreach ( $captcha_type as $row ) {
				$captcha_array = maybe_unserialize( $row->meta_value );
			}
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/common-functions.php' ) ) {
				include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/common-functions.php';
			}
			if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations-frontend.php' ) ) {
				include CAPTCHA_BOOSTER_DIR_PATH . 'includes/translations-frontend.php';
			}
			if ( 'logical_captcha' === $captcha_array['captcha_type_text_logical'] ) {
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/logical-captcha.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/logical-captcha.php';
				}
			} elseif ( 'text_captcha' === $captcha_array['captcha_type_text_logical'] ) {
				if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'includes/text-captcha.php' ) ) {
					include_once CAPTCHA_BOOSTER_DIR_PATH . 'includes/text-captcha.php';
				}
				if ( isset( $_REQUEST['captcha_code'] ) ) { // WPCS: CSRF ok, input var ok.
					if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . '/includes/captcha-generate-code.php' ) ) {
						include_once CAPTCHA_BOOSTER_DIR_PATH . '/includes/captcha-generate-code.php';
						die();
					}
				}
			}
		}
	}

	if ( ! function_exists( 'captcha_booster_url_encode' ) ) {
		/**
		 * This function is used to return the encoded string.
		 *
		 * @param string $string .
		 */
		function captcha_booster_url_encode( $string ) {
			$entities     = array( '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D' );
			$replacements = array( '!', '*', "'", '(', ')', ';', ':', '@', '&', '=', '+', '$', ',', '/', '?', '%', '#', '[', ']' );
			return str_replace( $entities, $replacements, urlencode( $string ) ); // @codingStandardsIgnoreLine.
		}
	}

	if ( ! function_exists( 'plugin_load_textdomain_captcha_booster' ) ) {
		/**
		 * This function is used to Load the plugin’s translated strings.
		 */
		function plugin_load_textdomain_captcha_booster() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( 'wp-captcha-booster', false, CAPTCHA_BOOSTER_PLUGIN_DIRNAME . '/languages' );
			}
		}
	}

	if ( ! function_exists( 'admin_functions_for_captcha_booster' ) ) {
		/**
		 * This function is used to call functions on init hook.
		 */
		function admin_functions_for_captcha_booster() {
			install_script_for_captcha_booster();
			helper_file_for_captcha_booster();
			blocking_visitors_captcha_booster();
		}
	}

	if ( ! function_exists( 'user_functions_for_captcha_booster' ) ) {
		/**
		 * This function is used to call functions on init hook.
		 */
		function user_functions_for_captcha_booster() {
			plugin_load_textdomain_captcha_booster();
			js_frontend_for_captcha_booster();
			global $wpdb;
			$other_settings_data              = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_booster_meta WHERE meta_key=%s', 'other_settings'
				)
			);// WPCS: db call ok; no-cache ok.
			$other_settings_unserialized_data = maybe_unserialize( $other_settings_data );
			if ( 'enable' === $other_settings_unserialized_data['visitor_logs_monitoring'] || 'enable' === $other_settings_unserialized_data['live_traffic_monitoring'] ) {
				captcha_booster_visitor_logs();
			}
		}
	}

	if ( ! function_exists( 'deactivation_function_for_captcha_booster' ) ) {
		/**
		 * This function is used for executing the code on deactivation.
		 */
		function deactivation_function_for_captcha_booster() {
			delete_option( 'captcha-booster-wizard-set-up' );
		}
	}


	/* Hooks */
	/**
	 * This hook contains all admin_init functions.
	 */

	add_action( 'admin_init', 'admin_functions_for_captcha_booster' );

	/**
	 * This function is used for managing captcha on frontend.
	 */

	call_captcha_booster();

	/**
	 * This hook is used for calling the function of sidebar menu in multisite case.
	 */

	add_action( 'network_admin_menu', 'create_sidebar_menu_for_captcha_booster' );

	/**
	 * This hook is used for calling the function of sidebar menus.
	 */

	add_action( 'admin_menu', 'create_sidebar_menu_for_captcha_booster' );

	/**
	 * This hook is used for calling the function of top bar menu.
	 */

	add_action( 'admin_bar_menu', 'create_topbar_menu_for_captcha_booster', 100 );

	/**
	 * This hook is used to calling the function of ajax register.
	 */

	add_action( 'wp_ajax_captcha_booster_action_library', 'ajax_register_for_captcha_booster' );

	/**
	 * This hook calling that function which contains function of init hook.
	 */

	add_action( 'init', 'user_functions_for_captcha_booster' );

	/**
	 * This hook is used for calling the function of cron schedulers jobs.
	 */

	add_filter( 'cron_schedules', 'cron_scheduler_for_intervals_captcha_booster' );

	/**
	 * This hook is used for calling the function on authentication.
	 */

	add_filter( 'wp_authenticate', 'blocking_visitors_captcha_booster' );

	/**
	 * This hook is used to sets the deactivation hook for a plugin.
	 */

	register_deactivation_hook( __FILE__, 'deactivation_function_for_captcha_booster' );

	/**
	 * This hook is used for create link for Plugin Settings.
	 */

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'captcha_booster_settings_action_links', 10, 2 );

}

/**
 * This hook is used for calling the function of install script
 */

register_activation_hook( __FILE__, 'install_script_for_captcha_booster' );

	/**
	 * This hook is used for calling the function of install script
	 */

add_action( 'admin_init', 'install_script_for_captcha_booster' );

/**
 * This hook is used to create link for premium Edition.
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'captcha_booster_action_links' );

if ( ! function_exists( 'plugin_activate_captcha_booster' ) ) {
	/**
	 * This function is used to add option.
	 */
	function plugin_activate_captcha_booster() {
		add_option( 'captcha_booster_do_activation_redirect', true );
	}
}

if ( ! function_exists( 'captcha_booster_redirect' ) ) {
	/**
	 * This function is used to redirect page.
	 */
	function captcha_booster_redirect() {
		if ( get_option( 'captcha_booster_do_activation_redirect', false ) ) {
			delete_option( 'captcha_booster_do_activation_redirect' );
			wp_safe_redirect( admin_url( 'admin.php?page=cpb_captcha_booster' ) );
			exit;
		}
	}
}

/**
 * This hook is used for calling the function plugin_activate_captcha_booster.
 */

register_activation_hook( __FILE__, 'plugin_activate_captcha_booster' );

/**
 * This hook is used for calling the function captcha_booster_redirect.
 */

add_action( 'admin_init', 'captcha_booster_redirect' );

	/**
	 * This function is used to create the object of admin notices.
	 */
function captcha_booster_admin_notice_class() {
	global $wpdb;
	/**
	 * This class is used to create the object of admin notices.
	 */
	class Captcha_Booster_Admin_Notices {
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      string    $config  .
		 */
		public $config;
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      integer    $notice_spam .
		 */
		public $notice_spam = 0;
		/**
		 * The version of this plugin.
		 *
		 * @access   public
		 * @var      integer    $notice_spam_max .
		 */
		public $notice_spam_max = 2;
		/**
		 * Public Constructor.
		 *
		 * @param array $config .
		 */
		public function __construct( $config = array() ) {
			// Runs the admin notice ignore function incase a dismiss button has been clicked.
			add_action( 'admin_init', array( $this, 'cbo_admin_notice_ignore' ) );
			// Runs the admin notice temp ignore function incase a temp dismiss link has been clicked.
			add_action( 'admin_init', array( $this, 'cbo_admin_notice_temp_ignore' ) );
			add_action( 'admin_notices', array( $this, 'cbo_display_admin_notices' ) );
		}
		/**
		 * Checks to ensure notices aren't disabled and the user has the correct permissions.
		 */
		public function cbo_admin_notices() {
			$settings = get_option( 'cbo_admin_notice' );
			if ( ! isset( $settings['disable_admin_notices'] ) || ( isset( $settings['disable_admin_notices'] ) && 0 === $settings['disable_admin_notices'] ) ) {
				if ( current_user_can( 'manage_options' ) ) {
					return true;
				}
			}
			return false;
		}
		/**
		 * Primary notice function that can be called from an outside function sending necessary variables.
		 *
		 * @param string $admin_notices .
		 */
		public function change_admin_notice_captcha_booster( $admin_notices ) {
			// Check options.
			if ( ! $this->cbo_admin_notices() ) {
				return false;
			}
			foreach ( $admin_notices as $slug => $admin_notice ) {
				// Call for spam protection.
				if ( $this->cbo_anti_notice_spam() ) {
					return false;
				}

				// Check for proper page to display on.
				if ( isset( $admin_notices[ $slug ]['pages'] ) && is_array( $admin_notices[ $slug ]['pages'] ) ) {
					if ( ! $this->cbo_admin_notice_pages( $admin_notices[ $slug ]['pages'] ) ) {
						return false;
					}
				}

				// Check for required fields.
				if ( ! $this->cbo_required_fields( $admin_notices[ $slug ] ) ) {

					// Get the current date then set start date to either passed value or current date value and add interval.
					$current_date = current_time( 'm/d/Y' );
					$start        = ( isset( $admin_notices[ $slug ]['start'] ) ? $admin_notices[ $slug ]['start'] : $current_date );
					$start        = date( 'm/d/Y' );
					$interval     = ( isset( $admin_notices[ $slug ]['int'] ) ? $admin_notices[ $slug ]['int'] : 0 );
					$date         = strtotime( '+' . $interval . ' days', strtotime( $start ) );
					$start        = date( 'm/d/Y', $date );

					// This is the main notices storage option.
					$admin_notices_option = get_option( 'cbo_admin_notice', array() );
					// Check if the message is already stored and if so just grab the key otherwise store the message and its associated date information.
					if ( ! array_key_exists( $slug, $admin_notices_option ) ) {
						$admin_notices_option[ $slug ]['start'] = date( 'm/d/Y' );
						$admin_notices_option[ $slug ]['int']   = $interval;
						update_option( 'cbo_admin_notice', $admin_notices_option );
					}

					// Sanity check to ensure we have accurate information.
					// New date information will not overwrite old date information.
					$admin_display_check    = ( isset( $admin_notices_option[ $slug ]['dismissed'] ) ? $admin_notices_option[ $slug ]['dismissed'] : 0 );
					$admin_display_start    = ( isset( $admin_notices_option[ $slug ]['start'] ) ? $admin_notices_option[ $slug ]['start'] : $start );
					$admin_display_interval = ( isset( $admin_notices_option[ $slug ]['int'] ) ? $admin_notices_option[ $slug ]['int'] : $interval );
					$admin_display_msg      = ( isset( $admin_notices[ $slug ]['msg'] ) ? $admin_notices[ $slug ]['msg'] : '' );
					$admin_display_title    = ( isset( $admin_notices[ $slug ]['title'] ) ? $admin_notices[ $slug ]['title'] : '' );
					$admin_display_link     = ( isset( $admin_notices[ $slug ]['link'] ) ? $admin_notices[ $slug ]['link'] : '' );
					$output_css             = false;

					// Ensure the notice hasn't been hidden and that the current date is after the start date.
					if ( 0 === $admin_display_check && strtotime( $admin_display_start ) <= strtotime( $current_date ) ) {

						// Get remaining query string.
						$query_str = ( isset( $admin_notices[ $slug ]['later_link'] ) ? $admin_notices[ $slug ]['later_link'] : esc_url( add_query_arg( 'cbo_admin_notice_ignore', $slug ) ) );
						if ( strpos( $slug, 'promo' ) === false ) {
							// Admin notice display output.
							echo '<div class="update-nag cbo-admin-notice" style="width:95%!important;">
															 <div></div>
																<strong><p>' . $admin_display_title . '</p></strong>
																<strong><p style="font-size:14px !important">' . $admin_display_msg . '</p></strong>
																<strong><ul>' . $admin_display_link . '</ul></strong>
															</div>'; // WPCS: XSS ok.
						} else {
							echo '<div class="admin-notice-promo">';
							echo $admin_display_msg; // WPCS: XSS ok.
							echo '<ul class="notice-body-promo blue">
																		' . $admin_display_link . '
																	</ul>'; // WPCS: XSS ok.
							echo '</div>';
						}
						$this->notice_spam += 1;
						$output_css         = true;
					}
				}
			}
		}
		/**
		 * Spam protection check
		 */
		public function cbo_anti_notice_spam() {
			if ( $this->notice_spam >= $this->notice_spam_max ) {
				return true;
			}
			return false;
		}
		/**
		 * Ignore function that gets ran at admin init to ensure any messages that were dismissed get marked
		 */
		public function cbo_admin_notice_ignore() {
			// If user clicks to ignore the notice, update the option to not show it again.
			if ( isset( $_GET['cbo_admin_notice_ignore'] ) ) { // WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'cbo_admin_notice', array() );
				$admin_notices_option[ wp_unslash( $_GET['cbo_admin_notice_ignore'] ) ]['dismissed'] = 1; // WPCS: CSRF ok, input var ok, sanitization ok.
				update_option( 'cbo_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( 'cbo_admin_notice_ignore' );
				wp_safe_redirect( $query_str );
				exit;
			}
		}
		/**
		 * Temp Ignore function that gets ran at admin init to ensure any messages that were temp dismissed get their start date changed.
		 */
		public function cbo_admin_notice_temp_ignore() {
			// If user clicks to temp ignore the notice, update the option to change the start date - default interval of 14 days.
			if ( isset( $_GET['cbo_admin_notice_temp_ignore'] ) ) { // WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'cbo_admin_notice', array() );
				$current_date         = current_time( 'm/d/Y' );
				$interval             = ( isset( $_GET['int'] ) ? wp_unslash( $_GET['int'] ) : 7 );// WPCS: input var ok, CSRF ok, sanitization ok.
				$date                 = strtotime( '+' . $interval . ' days', strtotime( $current_date ) );
				$new_start            = date( 'm/d/Y', $date );

				$admin_notices_option[ wp_unslash( $_GET['cbo_admin_notice_temp_ignore'] ) ]['start']     = $new_start; // WPCS: CSRF ok, input var ok, sanitization ok.
				$admin_notices_option[ wp_unslash( $_GET['cbo_admin_notice_temp_ignore'] ) ]['dismissed'] = 0; // WPCS: CSRF ok, input var ok, sanitization ok.
				update_option( 'cbo_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( array( 'cbo_admin_notice_temp_ignore', 'int' ) );
				wp_safe_redirect( $query_str );
				exit;
			}
		}
		/**
		 * Display admin notice on pages.
		 *
		 * @param array $pages .
		 */
		public function cbo_admin_notice_pages( $pages ) {
			foreach ( $pages as $key => $page ) {
				if ( is_array( $page ) ) {
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page[0] && isset( $_GET['tab'] ) && $_GET['tab'] === $page[1] ) { // WPCS: CSRF ok, input var ok.
						return true;
					}
				} else {
					if ( 'all' === $page ) {
						return true;
					}
					if ( get_current_screen()->id === $page ) {
						return true;
					}
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page ) { // WPCS: CSRF ok, input var ok.
						return true;
					}
				}
				return false;
			}
		}
		/**
		 * Required fields check.
		 *
		 * @param array $fields .
		 */
		public function cbo_required_fields( $fields ) {
			if ( ! isset( $fields['msg'] ) || ( isset( $fields['msg'] ) && empty( $fields['msg'] ) ) ) {
				return true;
			}
			if ( ! isset( $fields['title'] ) || ( isset( $fields['title'] ) && empty( $fields['title'] ) ) ) {
				return true;
			}
			return false;
		}
		/**
		 * Display Content in admin notice.
		 */
		public function cbo_display_admin_notices() {
			$two_week_review_ignore = add_query_arg( array( 'cbo_admin_notice_ignore' => 'two_week_review' ) );
			$two_week_review_temp   = add_query_arg(
				array(
					'cbo_admin_notice_temp_ignore' => 'two_week_review',
					'int'                          => 7,
				)
			);

			$notices['two_week_review'] = array(
				'title'      => __( 'Leave A Captcha Booster Review ?', 'wp-captcha-booster' ),
				'msg'        => __( 'We love and care about you. Captcha Booster Team is putting our maximum efforts to provide you the best functionalities.<br> We would really appreciate if you could spend a couple of seconds to give a Nice Review to the plugin for motivating us!', 'wp-captcha-booster' ),
				'link'       => '<span class="dashicons dashicons-external captcha-booster-admin-notice"></span><span class="captcha-booster-admin-notice"><a href="https://wordpress.org/support/plugin/wp-captcha-booster/reviews/?filter=5" target="_blank" class="captcha-booster-admin-notice-link">' . __( 'Sure! I\'d love to!', 'wp-captcha-booster' ) . '</a></span>
												<span class="dashicons dashicons-smiley captcha-booster-admin-notice"></span><span class="captcha-booster-admin-notice"><a href="' . $two_week_review_ignore . '" class="captcha-booster-admin-notice-link"> ' . __( 'I\'ve already left a review', 'wp-captcha-booster' ) . '</a></span>
												<span class="dashicons dashicons-calendar-alt captcha-booster-admin-notice"></span><span class="captcha-booster-admin-notice"><a href="' . $two_week_review_temp . '" class="captcha-booster-admin-notice-link">' . __( 'Maybe Later', 'wp-captcha-booster' ) . '</a></span>',
				'later_link' => $two_week_review_temp,
				'int'        => 7,
			);

			$this->change_admin_notice_captcha_booster( $notices );
		}
	}
	$captcha_booster_admin_notice = new Captcha_Booster_Admin_Notices();
}
add_action( 'init', 'captcha_booster_admin_notice_class' );
/**
 * Add Pop on deactivation.
 */
function add_popup_on_deactivation_captcha_booster() {
	global $wpdb;
	/**
	 * Display deactivation form.
	 */
	class Captcha_Booster_Deactivation_Form { // @codingStandardsIgnoreLine.
		/**
		 * Public Constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_post_user_feedback_captcha_booster', array( $this, 'post_user_feedback_captcha_booster' ) );
			global $pagenow;
			if ( 'plugins.php' === $pagenow ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'feedback_form_js_captcha_booster' ) );
					add_action( 'admin_head', array( $this, 'add_form_layout_captcha_booster' ) );
					add_action( 'admin_footer', array( $this, 'add_deactivation_dialog_form_captcha_booster' ) );
			}
		}
		/**
		 * Add css and js files.
		 */
		public function feedback_form_js_captcha_booster() {
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_register_script( 'captcha-booster', plugins_url( 'assets/global/plugins/deactivation/deactivate-popup.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ), false, true );
			wp_localize_script( 'captcha-booster', 'post_feedback', array( 'admin_ajax' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'captcha-booster' );
		}
		/**
		 * Post user Fedback.
		 */
		public function post_user_feedback_captcha_booster() {
			$captcha_booster_deactivation_reason = isset( $_POST['reason'] ) ? wp_unslash( $_POST['reason'] ) : ''; // WPCS: CSRF ok, input var ok, sanitization ok.
			$type                                = get_option( 'captcha-booster-wizard-set-up' );
			$user_admin_email                    = get_option( 'captcha-booster-admin-email' );
			$plugin_info_captcha_booster         = new Plugin_Info_Captcha_Booster();
			global $wp_version, $wpdb;
			$theme_details = array();

			if ( $wp_version >= 3.4 ) {
				$active_theme                   = wp_get_theme();
				$theme_details['theme_name']    = strip_tags( $active_theme->name );
				$theme_details['theme_version'] = strip_tags( $active_theme->version );
				$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
			}

			$plugin_stat_data                     = array();
			$plugin_stat_data['plugin_slug']      = 'wp-captcha-booster';
			$plugin_stat_data['reason']           = $captcha_booster_deactivation_reason;
			$plugin_stat_data['type']             = 'standard_edition';
			$plugin_stat_data['version_number']   = CAPTCHA_BOOSTER_VERSION_NUMBER;
			$plugin_stat_data['status']           = $type;
			$plugin_stat_data['event']            = 'de-activate';
			$plugin_stat_data['domain_url']       = site_url();
			$plugin_stat_data['wp_language']      = defined( 'WPLANG' ) && WPLANG ? WPLANG : get_locale();
			$plugin_stat_data['email']            = false !== $user_admin_email ? $user_admin_email : get_option( 'admin_email' );
			$plugin_stat_data['wp_version']       = $wp_version;
			$plugin_stat_data['php_version']      = esc_html( phpversion() );
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
				die( 'success' );
		}
		/**
		 * Add form layout of deactivation form.
		 */
		function add_form_layout_captcha_booster() {
			?>
			<style type="text/css">
					.captcha-booster-feedback-form .ui-dialog-buttonset {
						float: none !important;
					}
					#captcha-booster-feedback-dialog-continue,#captcha-booster-feedback-dialog-skip {
						float: right;
					}
					#captcha-booster-feedback-cancel{
						float: left;
					}
					#captcha-booster-feedback-content p {
						font-size: 1.1em;
					}
					.captcha-booster-feedback-form .ui-icon {
						display: none;
					}
					#captcha-booster-feedback-dialog-continue.captcha-booster-ajax-progress .ui-icon {
						text-indent: inherit;
						display: inline-block !important;
						vertical-align: middle;
						animation: rotate 2s infinite linear;
					}
					#captcha-booster-feedback-dialog-continue.captcha-booster-ajax-progress .ui-button-text {
						vertical-align: middle;
					}
					@keyframes rotate {
						0%    { transform: rotate(0deg); }
						100%  { transform: rotate(360deg); }
					}
			</style>
			<?php
		}
		/**
		 * Add deactivation dialog form.
		 */
		function add_deactivation_dialog_form_captcha_booster() {
			?>
			<div id="captcha-booster-feedback-content" style="display: none;">
			<p style="margin-top:-5px"><?php echo esc_attr( __( 'We feel guilty when anyone stop using Captcha Booster', 'wp-captcha-booster' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If Captcha Booster isn\'t working for you, others also may not.', 'wp-captcha-booster' ) ); ?></p>
						<p><?php echo esc_attr( __( 'We would love to hear your feedback about what went wrong.', 'wp-captcha-booster' ) ); ?></p>
						<p><?php echo esc_attr( __( 'We would like to help you in fixing the issue.', 'wp-captcha-booster' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If you click Continue, some data would be sent to our servers for Compatiblity Testing Purposes.', 'wp-captcha-booster' ) ); ?></p>
						<p><?php echo esc_attr( __( 'If you Skip, no data would be shared with our servers.', 'wp-captcha-booster' ) ); ?></p>
			<form>
				<?php wp_nonce_field(); ?>
				<ul id="captcha-booster-deactivate-reasons">
					<li class="captcha-booster-reason captcha-booster-custom-input">
						<label>
							<span><input value="0" type="radio" name="reason"></span>
							<span><?php echo esc_attr( __( 'The Plugin didn\'t work', 'wp-captcha-booster' ) ); ?></span>
						</label>
					</li>
					<li class="captcha-booster-reason captcha-booster-custom-input">
						<label>
							<span><input value="1" type="radio" name="reason" /></span>
							<span><?php echo esc_attr( __( 'I found a better Plugin', 'wp-captcha-booster' ) ); ?></span>
						</label>
					</li>
					<li class="captcha-booster-reason">
						<label>
							<span><input value="2" type="radio" name="reason" checked/></span>
							<span><?php echo esc_attr( __( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'wp-captcha-booster' ) ); ?></span>
						</label>
					</li>
					<li class="captcha-booster-reason captcha-booster-custom-input">
						<label>
							<span><input value="3" type="radio" name="reason" /></span>
							<span><a href="https://wordpress.org/support/plugin/wp-captcha-booster" target="_blank"><?php echo esc_attr( __( 'Open a Support Ticket for me.', 'wp-captcha-booster' ) ); ?></a></span>
						</label>
					</li>
				</ul>
			</form>
		</div>
		<?php
		}
	}
	$plugin_deactivation_details = new Captcha_Booster_Deactivation_Form();
}
add_action( 'plugins_loaded', 'add_popup_on_deactivation_captcha_booster' );
/**
 * Insert deactivation link.
 *
 * @param array $links .
 */
function insert_deactivate_link_id_captcha_booster( $links ) {
	if ( ! is_multisite() ) {
		$links['deactivate'] = str_replace( '<a', '<a id="captcha-booster-plugin-disable-link"', $links['deactivate'] );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'insert_deactivate_link_id_captcha_booster', 10, 2 );

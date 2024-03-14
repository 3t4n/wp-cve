<?php // @codingStandardsIgnoreLine.
/**
 * Plugin Name: Clean Up Optimizer
 * Plugin URI: https://tech-banker.com/clean-up-optimizer/
 * Description: Clean Up Optimizer is a Superlative High Quality WordPress Plugin which not only allows you to clean and optimize the WordPress Database but also performs other vast functions.
 * Author: Tech Banker
 * Author URI: https://tech-banker.com/clean-up-optimizer/
 * Version: 4.0.28
 * License: GPLv3
 * Text Domain: wp-clean-up-optimizer
 * Domain Path: /languages
 *
 * @package wp-cleanup-optimizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
/* Constant Declaration */
if ( ! defined( 'CLEAN_UP_OPTIMIZER_DIR_PATH' ) ) {
	define( 'CLEAN_UP_OPTIMIZER_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'CLEAN_UP_OPTIMIZER_PLUGIN_DIRNAME' ) ) {
	define( 'CLEAN_UP_OPTIMIZER_PLUGIN_DIRNAME', plugin_basename( dirname( __FILE__ ) ) );
}
if ( ! defined( 'CLEAN_UP_OPTIMIZER_LOCAL_TIME' ) ) {
	define( 'CLEAN_UP_OPTIMIZER_LOCAL_TIME', strtotime( date_i18n( 'Y-m-d H:i:s' ) ) );
}
if ( ! defined( 'TECH_BANKER_URL' ) ) {
	define( 'TECH_BANKER_URL', 'https://tech-banker.com' );
}
if ( ! defined( 'TECH_BANKER_BETA_URL' ) ) {
	define( 'TECH_BANKER_BETA_URL', 'https://tech-banker.com/clean-up-optimizer' );
}
if ( ! defined( 'TECH_BANKER_SERVICES_URL' ) ) {
	define( 'TECH_BANKER_SERVICES_URL', 'https://tech-banker.com' );
}
if ( ! defined( 'TECH_BANKER_STATS_URL' ) ) {
	define( 'TECH_BANKER_STATS_URL', 'http://stats.tech-banker-services.org' );
}
if ( ! defined( 'CLEAN_UP_OPTIMIZER_VERSION_NUMBER' ) ) {
	define( 'CLEAN_UP_OPTIMIZER_VERSION_NUMBER', '4.0.28' );
}

$memory_limit_clean_up_optimizer = intval( ini_get( 'memory_limit' ) );
if ( ! extension_loaded( 'suhosin' ) && $memory_limit_clean_up_optimizer < 512 ) {
	@ini_set( 'memory_limit', '512M' );// @codingStandardsIgnoreLine.
}

@ini_set( 'max_execution_time', 6000 );// @codingStandardsIgnoreLine.
@ini_set( 'max_input_vars', 10000 );// @codingStandardsIgnoreLine.

if ( ! function_exists( 'install_script_for_clean_up_optimizer' ) ) {
	/**
	 * This function is used to create tables in database.
	 */
	function install_script_for_clean_up_optimizer() {
		global $wpdb;
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );// WPCS: db call ok, no-cache ok.
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );// @codingStandardsIgnoreLine.
				$clean_up_optimizer_version_number = get_option( 'wp-cleanup-optimizer-version-number' );
				if ( $clean_up_optimizer_version_number < '4.0.2' ) {
					if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-install-script-clean-up-optimizer.php' ) ) {
						include CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-install-script-clean-up-optimizer.php';
					}
				}
				restore_current_blog();
			}
		} else {
			$clean_up_optimizer_version_number = get_option( 'wp-cleanup-optimizer-version-number' );
			if ( $clean_up_optimizer_version_number < '4.0.2' ) {
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-install-script-clean-up-optimizer.php' ) ) {
					include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-install-script-clean-up-optimizer.php';
				}
			}
		}
	}
}

if ( ! function_exists( 'get_others_capabilities_clean_up_optimizer' ) ) {
	/**
	 * This function is used to get all the roles available in WordPress
	 */
	function get_others_capabilities_clean_up_optimizer() {
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

if ( ! function_exists( 'check_user_roles_for_clean_up_optimizer' ) ) {
	/**
	 * This function is used for checking roles of different users.
	 */
	function check_user_roles_for_clean_up_optimizer() {
		global $current_user;
		$user = $current_user ? new WP_User( $current_user ) : wp_get_current_user();
		return $user->roles ? $user->roles[0] : false;
	}
}

if ( ! function_exists( 'clean_up_optimizer_ip_locations' ) ) {
	/**
	 * This function is used for creating ip locations table.
	 */
	function clean_up_optimizer_ip_locations() {
		global $wpdb;
		return $wpdb->prefix . 'clean_up_optimizer_ip_locations';
	}
}

if ( ! function_exists( 'clean_up_optimizer' ) ) {
	/**
	 * This function is used for creating parent table.
	 */
	function clean_up_optimizer() {
		global $wpdb;
		return $wpdb->prefix . 'clean_up_optimizer';
	}
}

if ( ! function_exists( 'clean_up_optimizer_meta' ) ) {
	/**
	 * This function is used for creating meta table.
	 */
	function clean_up_optimizer_meta() {
		global $wpdb;
		return $wpdb->prefix . 'clean_up_optimizer_meta';
	}
}

/**
 * This function is used for convert long integer to ip.
 *
 * @param integer $long .
 */
function long2ip_clean_up_optimizer( $long ) {
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

if ( ! function_exists( 'clean_up_optimizer_settings_action_links' ) ) {
	/**
	 * This function is used to add settings link
	 *
	 * @param array $action .
	 */
	function clean_up_optimizer_settings_action_links( $action ) {
		global $wpdb;
		$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
		$settings_link        = '<a href = "' . admin_url( 'admin.php?page=cpo_dashboard' ) . '"> Settings </a>';
		array_unshift( $action, $settings_link );
		return $action;
	}
}
$clean_up_optimizer_version_number = get_option( 'wp-cleanup-optimizer-version-number' );
if ( $clean_up_optimizer_version_number >= '4.0.2' ) {
	if ( is_admin() ) {
		if ( ! function_exists( 'backend_js_css_for_clean_up_optimizer' ) ) {
			/**
			 * This function is used to include backend js.
			 */
			function backend_js_css_for_clean_up_optimizer() {
				$pages_clean_up_optimizer = array(
					'cpo_wizard_optimizer',
					'cpo_dashboard',
					'cpo_schedule_optimizer',
					'cpo_add_new_wordpress_schedule',
					'cpo_db_optimizer',
					'cpo_schedule_db_optimizer',
					'cpo_database_view_records',
					'cpo_schedule_db_optimizer',
					'cpo_add_new_database_schedule',
					'cpo_live_traffic',
					'cpo_login_logs',
					'cpo_visitor_logs',
					'cpo_custom_jobs',
					'cpo_core_jobs',
					'cpo_notifications_setup',
					'cpo_message_settings',
					'cpo_email_templates',
					'cpo_roles_and_capabilities',
					'cpo_blockage_settings',
					'cpo_ip_addresses',
					'cpo_ip_ranges',
					'cpo_block_unblock_countries',
					'cpo_other_settings',
					'cpo_system_information',
				);
				if ( in_array( isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '', $pages_clean_up_optimizer, true ) ) {// WPCS: Input var ok, CSRF ok.
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'clean-up-optimizer-bootstrap.js', plugins_url( 'assets/global/plugins/custom/js/custom.js', __FILE__ ) );
					wp_enqueue_script( 'clean-up-optimizer-bootstrap-tabdrop.js', plugins_url( 'assets/global/plugins/tabdrop/js/tabdrop.js', __FILE__ ) );
					wp_enqueue_script( 'clean-up-optimizer-jquery.validate.js', plugins_url( 'assets/global/plugins/validation/jquery.validate.js', __FILE__ ) );
					wp_enqueue_script( 'clean-up-optimizer-jquery.datatables.js', plugins_url( 'assets/global/plugins/datatables/media/js/jquery.datatables.js', __FILE__ ) );
					wp_enqueue_script( 'clean-up-optimizer-jquery.fngetfilterednodes.js', plugins_url( 'assets/global/plugins/datatables/media/js/fngetfilterednodes.js', __FILE__ ) );
					wp_enqueue_script( 'clean-up-optimizer-toastr.js', plugins_url( 'assets/global/plugins/toastr/toastr.js', __FILE__ ) );
					if ( is_ssl() ) {
						wp_enqueue_script( 'clean-up-optimizer-maps_script.js', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyDOpCmwYFyneS7t5j8d6lNE1kRxL9vzsCI' );
					} else {
						wp_enqueue_script( 'clean-up-optimizer-maps_script.js', 'http://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyDOpCmwYFyneS7t5j8d6lNE1kRxL9vzsCI' );
					}

					wp_enqueue_style( 'clean-up-optimizer-simple-line-icons.css', plugins_url( 'assets/global/plugins/icons/icons.css', __FILE__ ) );
					wp_enqueue_style( 'clean-up-optimizer-components.css', plugins_url( 'assets/global/css/components.css', __FILE__ ) );
					wp_enqueue_style( 'clean-up-optimizer-custom.css', plugins_url( 'assets/admin/layout/css/clean-up-optimizer-custom.css', __FILE__ ) );
					if ( is_rtl() ) {
						wp_enqueue_style( 'clean-up-optimizer-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'clean-up-optimizer-layout.css', plugins_url( 'assets/admin/layout/css/layout-rtl.css', __FILE__ ) );
						wp_enqueue_style( 'clean-up-optimizer-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom-rtl.css', __FILE__ ) );
					} else {
						wp_enqueue_style( 'clean-up-optimizer-bootstrap.css', plugins_url( 'assets/global/plugins/custom/css/custom.css', __FILE__ ) );
						wp_enqueue_style( 'clean-up-optimizer-layout.css', plugins_url( 'assets/admin/layout/css/layout.css', __FILE__ ) );
						wp_enqueue_style( 'clean-up-optimizer-tech-banker-custom.css', plugins_url( 'assets/admin/layout/css/tech-banker-custom.css', __FILE__ ) );
					}
					wp_enqueue_style( 'clean-up-optimizer-plugins.css', plugins_url( 'assets/global/css/plugins.css', __FILE__ ) );
					wp_enqueue_style( 'clean-up-optimizer-default.css', plugins_url( 'assets/admin/layout/css/themes/default.css', __FILE__ ) );
					wp_enqueue_style( 'clean-up-optimizer-toastr.min.css', plugins_url( 'assets/global/plugins/toastr/toastr.css', __FILE__ ) );
					wp_enqueue_style( 'clean-up-optimizer-jquery-ui.css', plugins_url( 'assets/global/plugins/datepicker/jquery-ui.css', __FILE__ ), false, '2.0', false );
					wp_enqueue_style( 'clean-up-optimizer-datatables.foundation.css', plugins_url( 'assets/global/plugins/datatables/media/css/datatables.foundation.css', __FILE__ ) );
				}
			}
		}
		add_action( 'admin_enqueue_scripts', 'backend_js_css_for_clean_up_optimizer' );
	}

	if ( ! function_exists( 'validate_ip_cleanup_optimizer' ) ) {
		/**
		 * This function is used for validating ip address.
		 *
		 * @param string $ip .
		 */
		function validate_ip_cleanup_optimizer( $ip ) {
			if ( strtolower( $ip ) === 'unknown' ) {
				return false;
			}
			$ip = '::1' == $ip ? sprintf( '%u', ip2long( '127.0.0.1' ) ) : sprintf( '%u', ip2long( $ip ) );// WPCS: Loose Comparison ok.

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

	if ( ! function_exists( 'get_ip_address_clean_up_optimizer' ) ) {
		/**
		 * This function is used for getIpAddress.
		 */
		function get_ip_address_clean_up_optimizer() {
			static $ip = null;
			if ( isset( $ip ) ) {
				return $ip;
			}

			global $wpdb;
			$data                = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key=%s', 'other_settings'
				)
			);// WPCS: db call ok, no-cache ok.
			$other_settings_data = maybe_unserialize( $data );

			switch ( $other_settings_data['ip_address_fetching_method'] ) {
				case 'REMOTE_ADDR':
					if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {// @codingStandardsIgnoreLine.
						$remote_addr = wp_unslash( $_SERVER['REMOTE_ADDR'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( $remote_addr ) ) {
							$ip = wp_unslash( $remote_addr );
							return $ip;
						}
					}
					break;

				case 'HTTP_X_FORWARDED_FOR':
				$http_forwarded_for = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) : ''; // @codingStandardsIgnoreLine.
					if ( isset( $http_forwarded_for ) ) {// @codingStandardsIgnoreLine.
						if ( strpos( $http_forwarded_for, ',' ) !== false ) {// @codingStandardsIgnoreLine.
							$iplist = explode( ',', wp_unslash( $http_forwarded_for ) );// WPCS: Input var ok, sanitization ok.
							foreach ( $iplist as $ip_address ) {
								if ( validate_ip_cleanup_optimizer( $ip_address ) ) {
									$ip = $ip_address;
									return $ip;
								}
							}
						} else {
							if ( validate_ip_cleanup_optimizer( wp_unslash( $http_forwarded_for ) ) ) {// WPCS: Input var ok, sanitization ok.
								$ip = wp_unslash( $http_forwarded_for );// WPCS: Input var ok, sanitization ok.
								return $ip;
							}
						}
					}
					break;

				case 'HTTP_X_REAL_IP':
					if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {// WPCS: Input var ok.
						$http_real_ip = wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( wp_unslash( $http_real_ip ) ) ) {// WPCS: Input var ok, sanitization ok.
							$ip = wp_unslash( $http_real_ip );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					break;

				case 'HTTP_CF_CONNECTING_IP':
					if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {// WPCS: Input var ok.
						$http_connecting_ip = wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( wp_unslash( $http_connecting_ip ) ) ) {// WPCS: Input var ok.
							$ip = wp_unslash( $http_connecting_ip );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					break;

				default:
					if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {// WPCS: Input var ok.
						$http_client_ip = wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( wp_unslash( $http_client_ip ) ) ) {// WPCS: Input var ok.
							$ip = wp_unslash( $http_client_ip );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					$http_forward_for = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) : ''; // @codingStandardsIgnoreLine.
					if ( isset( $http_forward_for ) ) {
						if ( strpos( $http_forward_for, ',' ) !== false ) {
							$iplist = explode( ',', wp_unslash( $http_forward_for ) );// WPCS: Input var ok, sanitization ok.
							foreach ( $iplist as $ip_address ) {
								if ( validate_ip_cleanup_optimizer( $ip_address ) ) {
									$ip = $ip_address;
									return $ip;
								}
							}
						} else {
							if ( validate_ip_cleanup_optimizer( $http_forward_for ) ) {
								$ip = wp_unslash( $http_forward_for );// WPCS: Input var ok, sanitization ok.
								return $ip;
							}
						}
					}
					if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {// @codingStandardsIgnoreLine.
						$http_x_forwarded = wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( $http_x_forwarded ) ) {
							$ip = wp_unslash( $http_x_forwarded );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) ) {// @codingStandardsIgnoreLine.
						$http_cluster_client_ip = wp_unslash( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] );// @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( $http_cluster_client_ip ) ) {
							$ip = wp_unslash( $http_cluster_client_ip );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {// @codingStandardsIgnoreLine.
						$http_forward = wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( $http_forward ) ) {
							$ip = wp_unslash( $http_forward );// WPCS: Input var ok, sanitization ok.
							return $ip;
						}
					}
					if ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {// @codingStandardsIgnoreLine.
						$http_forwarded = wp_unslash( $_SERVER['HTTP_FORWARDED'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( wp_unslash( $http_forwarded ) ) ) {// WPCS: Input var ok.
							$ip = wp_unslash( $http_forwarded );// WPCS: Input var ok, sanitization ok..
							return $ip;
						}
					}
					if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {// @codingStandardsIgnoreLine.
						$remote_addr = wp_unslash( $_SERVER['REMOTE_ADDR'] ); // @codingStandardsIgnoreLine.
						if ( validate_ip_cleanup_optimizer( $remote_addr ) ) {// @codingStandardsIgnoreLine.
							$ip = wp_unslash( $remote_addr );// WPCS: Input var ok, sanitization ok, @codingStandardsIgnoreLine.
							return $ip;
						}
					}
					break;
			}
			return '0.0.0.0';
		}
	}

	if ( ! function_exists( 'get_users_capabilities_for_clean_up_optimizer' ) ) {
		/**
		 * This function is used to get users capabilities.
		 */
		function get_users_capabilities_for_clean_up_optimizer() {
			global $wpdb;
			$capabilities              = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// WPCS: db call ok, no-cache ok.
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

	if ( ! function_exists( 'sidebar_menu_for_clean_up_optimizer' ) ) {
		/**
		 * This function is used for sidebar menu.
		 */
		function sidebar_menu_for_clean_up_optimizer() {
			global $wpdb, $current_user;
			$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
			if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
				include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
			}
			if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/sidebar-menu.php' ) ) {
				include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/sidebar-menu.php';
			}
		}
	}

	if ( ! function_exists( 'topbar_menu_for_clean_up_optimizer' ) ) {
		/**
		 * This function is used for topbar menu.
		 */
		function topbar_menu_for_clean_up_optimizer() {
			global $wpdb, $current_user, $wp_admin_bar;
			$role_capabilities           = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'roles_and_capabilities'
				)
			);// WPCS: db call ok, no-cache ok.
			$roles_and_capabilities_data = maybe_unserialize( $role_capabilities );
			if ( 'enable' === $roles_and_capabilities_data['show_clean_up_optimizer_top_bar_menu'] ) {
				$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
				if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php' ) ) {
					include CLEAN_UP_OPTIMIZER_DIR_PATH . 'includes/translations.php';
				}
				if ( get_option( 'clean-up-optimizer-wizard-set-up' ) ) {
					if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/admin-bar-menu.php' ) ) {
						include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/admin-bar-menu.php';
					}
				}
			}
		}
	}

	if ( ! function_exists( 'helper_file_for_clean_up_optimizer' ) ) {
		/**
		 * This function is used for helper file.
		 */
		function helper_file_for_clean_up_optimizer() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
			if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-clean-up-optimizer.php' ) ) {
				include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/class-dbhelper-clean-up-optimizer.php';
			}
		}
	}

	if ( ! function_exists( 'ajax_register_clean_up_optimizer' ) ) {
		/**
		 * This function is used for register ajax.
		 */
		function ajax_register_clean_up_optimizer() {
			global $wpdb;
			$user_role_permission = get_users_capabilities_for_clean_up_optimizer();
			if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/action-library.php' ) ) {
				include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/action-library.php';
			}
		}
	}

	if ( ! function_exists( 'cleanup_optimizer_smart_ip_detect_crawler' ) ) {
		/**
		 * This function is used for register ajax.
		 */
		function cleanup_optimizer_smart_ip_detect_crawler() {
			// User lowercase string for comparison.
			$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );// @codingStandardsIgnoreLine.
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

	if ( ! function_exists( 'user_login_status_clean_up_optimizer' ) ) {
		/**
		 * This function is used for check the user's Login status.
		 *
		 * @param string $username .
		 * @param string $password .
		 */
		function user_login_status_clean_up_optimizer( $username, $password ) {
			global $wpdb;
			$ip         = get_ip_address_clean_up_optimizer();
			$ip_address = '::1' == $ip ? sprintf( '%u', ip2long( '0.0.0.0' ) ) : sprintf( '%u', ip2long( $ip ) );// WPCS: Loose Comparison ok.
			$location   = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $ip_address ) );
			if ( ! cleanup_optimizer_smart_ip_detect_crawler() ) {
				$place = '' == $location->country_name && '' == $location->city ? '' : '' == $location->country_name ? '' : '' == $location->city ? $location->country_name : $location->city . ', ' . $location->country_name;// WPCS: Loose comparison ok.

				$userdata        = get_user_by( 'login', $username );
				$user_email_data = get_user_by( 'email', $username );
				if ( ( $userdata && wp_check_password( $password, $userdata->user_pass ) ) || ( $user_email_data && wp_check_password( $password, $user_email_data->user_pass ) ) ) {
					$insert_login_logs              = array();
					$insert_login_logs['type']      = 'recent_login_logs';
					$insert_login_logs['parent_id'] = '0';
					$wpdb->insert( clean_up_optimizer(), $insert_login_logs );// WPCS: db call ok, no-cache ok.
					$last_id = $wpdb->insert_id;

					$insert_login_logs                    = array();
					$insert_login_logs['username']        = esc_attr( $username );
					$insert_login_logs['user_ip_address'] = esc_attr( $ip_address );
					$insert_login_logs['location']        = esc_attr( $place );
					$insert_login_logs['latitude']        = esc_attr( $location->latitude );
					$insert_login_logs['longitude']       = esc_attr( $location->longitude );
					$insert_login_logs['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';// @codingStandardsIgnoreLine.
					$insert_login_logs['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? esc_attr( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';// @codingStandardsIgnoreLine.
					$timestamp                            = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
					$insert_login_logs['date_time']       = intval( $timestamp );
					$insert_login_logs['status']          = 'Success';
					$insert_login_logs['meta_id']         = intval( $last_id );
					$recent_logs_data                     = array();
					$recent_logs_data['meta_id']          = $last_id;
					$recent_logs_data['meta_key']         = 'recent_login_data';// WPCS: Slow query ok.
					$recent_logs_data['meta_value']       = maybe_serialize( $insert_login_logs );// WPCS: Slow query ok.
					$wpdb->insert( clean_up_optimizer_meta(), $recent_logs_data );// WPCS: db call ok, no-cache ok.
				} else {
					if ( '' == $username || '' == $password ) { // WPCS: loose comparison ok.
						return;
					} else {
						$insert_login_logs              = array();
						$insert_login_logs['type']      = 'recent_login_logs';
						$insert_login_logs['parent_id'] = '0';
						$wpdb->insert( clean_up_optimizer(), $insert_login_logs );// WPCS: db call ok, no-cache ok.
						$last_id = $wpdb->insert_id;

						$insert_login_logs                    = array();
						$insert_login_logs['username']        = esc_attr( $username );
						$insert_login_logs['user_ip_address'] = esc_attr( $ip_address );
						$insert_login_logs['location']        = esc_attr( $place );
						$insert_login_logs['latitude']        = esc_attr( $location->latitude );
						$insert_login_logs['longitude']       = esc_attr( $location->longitude );
						$insert_login_logs['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? esc_attr( $_SERVER['REQUEST_URI'] ) : '';// @codingStandardsIgnoreLine.
						$insert_login_logs['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? esc_attr( $_SERVER['HTTP_USER_AGENT'] ) : '';// @codingStandardsIgnoreLine.
						$timestamp                            = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
						$insert_login_logs['date_time']       = intval( $timestamp );
						$insert_login_logs['status']          = 'Failure';
						$insert_login_logs['meta_id']         = intval( $last_id );

						$recent_logs_data               = array();
						$recent_logs_data['meta_id']    = $last_id;
						$recent_logs_data['meta_key']   = 'recent_login_data';// WPCS: Slow query.
						$recent_logs_data['meta_value'] = maybe_serialize( $insert_login_logs );// WPCS: Slow query.
						$wpdb->insert( clean_up_optimizer_meta(), $recent_logs_data );// WPCS: db call ok, no-cache ok.

						$auto_ip_block = $wpdb->get_var(
							$wpdb->prepare(
								'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'blocking_options'
							)
						);// WPCS: db call ok, no-cache ok.

						$blocking_options_data = maybe_unserialize( $auto_ip_block );
						if ( 'enable' === $blocking_options_data['auto_ip_block'] ) {
							add_filter( 'login_errors', 'login_error_messages_clean_up_optimizer', 10, 1 );
							$get_ip   = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $ip_address ) );
							$location = '' === $get_ip->country_name && '' === $get_ip->city ? '' : '' === $get_ip->country_name ? '' : '' === $get_ip->city ? $get_ip->country_name : $get_ip->city . ', ' . $get_ip->country_name;// WPCS: Loose Comparison ok.
							$date     = CLEAN_UP_OPTIMIZER_LOCAL_TIME;

							$meta_data_array = $blocking_options_data;

							$get_all_user_data = $wpdb->get_results(
								$wpdb->prepare(
									'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'recent_login_data'
								)
							);// WPCS: db call ok, no-cache ok.

							$blocked_for_time = $meta_data_array['block_for'];

							switch ( $blocked_for_time ) {
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

								case 'permanently':
									$this_time = 'permanently';
									break;
							}

							$user_data = count( get_clean_up_optimizer_details_login_count_check( $get_all_user_data, $date, $this_time, $ip_address ) );
							if ( ! defined( 'CPO_COUNT_LOGIN_STATUS' ) ) {
								define( 'CPO_COUNT_LOGIN_STATUS', $user_data );
							}
							if ( $user_data >= $meta_data_array['maximum_login_attempt_in_a_day'] ) {
								$advance_security_manage_ip_address = wp_create_nonce( 'cleanup_manage_ip_address' );
								$ip_address_block                   = array();
								$ip_address_block['type']           = 'block_ip_address';
								$ip_address_block['parent_id']      = 0;
								$wpdb->insert( clean_up_optimizer(), $ip_address_block );// WPCS: db call ok, no-cache ok.
								$last_id = $wpdb->insert_id;

								$ip_address_block_meta                = array();
								$ip_address_block_meta['ip_address']  = esc_attr( $ip_address );
								$ip_address_block_meta['blocked_for'] = esc_attr( $blocked_for_time );
								$ip_address_block_meta['location']    = esc_attr( $location );
								$ip_address_block_meta['comments']    = 'IP ADDRESS AUTOMATIC BLOCKED!';
								$timestamp                            = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
								$ip_address_block_meta['date_time']   = intval( $timestamp );

								$insert_data               = array();
								$insert_data['meta_id']    = $last_id;
								$insert_data['meta_key']   = 'block_ip_address';// WPCS: Slow query.
								$insert_data['meta_value'] = maybe_serialize( $ip_address_block_meta );// WPCS: Slow query.
								$wpdb->insert( clean_up_optimizer_meta(), $insert_data );// WPCS: db call ok, no-cache ok.

								if ( 'permanently' !== $blocked_for_time ) {
									$cron_name = 'ip_address_unblocker_' . $last_id;
									schedule_clean_up_optimizer_ip_address_and_ranges( $cron_name, $blocked_for_time );
								}
								$error_message_data = $wpdb->get_var(
									$wpdb->prepare(
										'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'error_message'
									)
								);// WPCS: db call ok, no-cache ok.

								$meta_data_array   = maybe_unserialize( $error_message_data );
								$replace_ipaddress = $meta_data_array['for_blocked_ip_address_error'];
								$replace_address   = str_replace( '[ip_address]', long2ip_clean_up_optimizer( $ip_address ), $replace_ipaddress );
								wp_die( $replace_address );// WPCS: XSS ok.
							}
						}
					}
				}
			}
		}
	}

	if ( ! function_exists( 'login_error_messages_clean_up_optimizer' ) ) {
		/**
		 * This Function is used for login error messages.
		 *
		 * @param string $default_error_message .
		 */
		function login_error_messages_clean_up_optimizer( $default_error_message ) {
			global $wpdb;
			$max_login_attempts          = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'blocking_options'
				)
			);// WPCS: db call ok, no-cache ok.
			$max_login_attempts_data     = maybe_unserialize( $max_login_attempts );
			$error_message_attempts      = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key = %s', 'error_message'
				)
			);// WPCS: db call ok, no-cache ok.
			$error_message_attempts_data = maybe_unserialize( $error_message_attempts );
			$login_attempts              = $max_login_attempts_data['maximum_login_attempt_in_a_day'] - CPO_COUNT_LOGIN_STATUS;
			$replace_attempts            = str_replace( '[maxAttempts]', $login_attempts, $error_message_attempts_data['for_maximum_login_attempts'] );
			$display_error_message       = $default_error_message . ' ' . $replace_attempts;
			return $display_error_message;
		}
	}

	if ( ! function_exists( 'schedule_clean_up_optimizer_ip_address_and_ranges' ) ) {
		/**
		 * This function is used for creating a scheduler of ip address.
		 *
		 * @param string $cron_name .
		 * @param string $time_interval .
		 */
		function schedule_clean_up_optimizer_ip_address_and_ranges( $cron_name, $time_interval ) {
			if ( wp_next_scheduled( $cron_name ) ) {
				unschedule_events_clean_up_optimizer( $cron_name );
			}
			switch ( $time_interval ) {
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
			wp_schedule_event( time() + $this_time, $time_interval, $cron_name );
		}
	}

	$scheduler         = _get_cron_array();
	$current_scheduler = array();

	foreach ( $scheduler as $value => $key ) {
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
			add_action( $current_scheduler[0], 'unblock_script_clean_up_optimizer' );
		} elseif ( strstr( $current_scheduler[0], 'ip_range_unblocker_' ) ) {
			add_action( $current_scheduler[0], 'unblock_script_clean_up_optimizer' );
		}
	}

	if ( ! function_exists( 'unblock_script_clean_up_optimizer' ) ) {
		/**
		 * This function is used for including the unblock-script file.
		 */
		function unblock_script_clean_up_optimizer() {
			if ( file_exists( CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/unblock-script.php' ) ) {
				$nonce_unblock_script = wp_create_nonce( 'unblock_script' );
				global $wpdb;
				include_once CLEAN_UP_OPTIMIZER_DIR_PATH . 'lib/unblock-script.php';
			}
		}
	}

	if ( ! function_exists( 'manage_security_settings_for_clean_up_optimizer' ) ) {
		/**
		 * This function  is used for blocking ip address and ip ranges.
		 *
		 * @param array  $meta_data_array .
		 * @param array  $meta_values_ip_blocks .
		 * @param string $ip_address .
		 * @param array  $location .
		 */
		function manage_security_settings_for_clean_up_optimizer( $meta_data_array, $meta_values_ip_blocks, $ip_address, $location ) {
			// code for checking ip range.
			$flag     = 0;
			$count_ip = 0;
			for ( $key = 0; $key < count( $meta_values_ip_blocks ); $key++ ) {// @codingStandardsIgnoreLine
				if ( 'block_ip_range' === $meta_values_ip_blocks[ $key ]->meta_key ) {
					$block_ip_range   = maybe_unserialize( $meta_values_ip_blocks[ $key ]->meta_value );
					$ip_range_address = explode( ',', $block_ip_range['ip_range'] );
					if ( $ip_address >= $ip_range_address[0] && $ip_address <= $ip_range_address[1] ) {
						$flag = 1;
						break;
					}
				} elseif ( 'block_ip_address' === $meta_values_ip_blocks[ $key ]->meta_key ) {
					$block_ip_address = maybe_unserialize( $meta_values_ip_blocks[ $key ]->meta_value );
					if ( $block_ip_address['ip_address'] === $ip_address ) {
						$count_ip = 1;
						break;
					}
				}
			}
			if ( 1 === $count_ip || 1 === $flag ) {
				if ( 1 === $count_ip ) {
					$replace_ipaddress = $meta_data_array['for_blocked_ip_address_error'];
					$replace_address   = str_replace( '[ip_address]', long2ip_clean_up_optimizer( $ip_address ), $replace_ipaddress );
					wp_die( $replace_address );// WPCS: XSS ok.
				} else {
					$replace_iprange = $meta_data_array['for_blocked_ip_range_error'];
					$replace_range   = str_replace( '[ip_range]', long2ip_clean_up_optimizer( $ip_range_address[0] ) . '-' . long2ip_clean_up_optimizer( $ip_range_address[1] ), $replace_iprange );
					wp_die( $replace_range );// WPCS: XSS ok.
				}
			}
		}
	}

	if ( ! function_exists( 'visitor_logs_insertion_clean_up_optimizer' ) ) {
		/**
		 * This Function is used for insert the visitor log data in database.
		 *
		 * @param array  $meta_data_array .
		 * @param string $ip_address .
		 * @param array  $location .
		 */
		function visitor_logs_insertion_clean_up_optimizer( $meta_data_array, $ip_address, $location ) {
			if ( ! is_admin() && ! defined( 'DOING_CRON' ) ) {
				if ( ! cleanup_optimizer_smart_ip_detect_crawler() ) {
					global $current_user, $wpdb;
					$place    = '' == $location->country_name && '' == $location->city ? '' : '' == $location->country_name ? '' : '' == $location->city ? $location->country_name : $location->city . ', ' . $location->country_name;// WPCS: Loose comparison ok.
					$username = $current_user->user_login;

					$insert_live_traffic              = array();
					$insert_live_traffic['type']      = 'visitor_log';
					$insert_live_traffic['parent_id'] = 0;
					$wpdb->insert( clean_up_optimizer(), $insert_live_traffic );// WPCS: db call ok, no-cache ok.
					$last_id = $wpdb->insert_id;

					$insert_live_traffic                    = array();
					$insert_live_traffic['username']        = esc_attr( $username );
					$insert_live_traffic['user_ip_address'] = esc_attr( $ip_address );

					$insert_live_traffic['location']        = esc_attr( $place );
					$insert_live_traffic['latitude']        = esc_attr( $location->latitude );
					$insert_live_traffic['longitude']       = esc_attr( $location->longitude );
					$insert_live_traffic['resources']       = isset( $_SERVER['REQUEST_URI'] ) ? esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';// WPCS: Input var ok, sanitization ok.
					$insert_live_traffic['http_user_agent'] = isset( $_SERVER['HTTP_USER_AGENT'] ) ? esc_attr( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';// WPCS: Input var ok, sanitization ok, @codingStandardsIgnoreLine.

					$timestamp                              = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
					$insert_live_traffic['date_time']       = intval( $timestamp );
					$insert_live_traffic['meta_id']         = intval( $last_id );
					$insert_live_traffic_data               = array();
					$insert_live_traffic_data['meta_id']    = $last_id;
					$insert_live_traffic_data['meta_key']   = 'visitor_log_data';// WPCS: Slow query.
					$insert_live_traffic_data['meta_value'] = maybe_serialize( $insert_live_traffic );// WPCS: Slow query.
					$wpdb->insert( clean_up_optimizer_meta(), $insert_live_traffic_data );// WPCS: db call ok, no-cache ok.
				}
			}
		}
	}

	if ( ! function_exists( 'get_ip_location_clean_up_optimizer' ) ) {
		/**
		 * This function is used to get ip location.
		 *
		 * @param string $ip_address .
		 */
		function get_ip_location_clean_up_optimizer( $ip_address ) {
			global $wpdb;
			$core_data              = '{"ip":"0.0.0.0","country_code":"","country_name":"","region_code":"","region_name":"","city":"","latitude":0,"longitude":0}';
			$ip_location_meta_value = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'clean_up_optimizer_ip_locations WHERE ip=%s', $ip_address
				)
			);// WPCS: db call ok, no-cache ok.
			if ( '' != $ip_location_meta_value ) { // WPCS: loose comparison ok.
				return $ip_location_meta_value;
			} else {
				$apicall = TECH_BANKER_SERVICES_URL . '/api-server/getipaddress.php?ip_address=' . $ip_address;
				if ( ! function_exists( 'curl_init' ) ) {
					$jsondata = @file_get_contents( $apicall );// @codingStandardsIgnoreLine.
				} else {
					$ch = curl_init();// @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_URL, $apicall );// @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json' ) );// @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );// @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );// @codingStandardsIgnoreLine.
					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );// @codingStandardsIgnoreLine.
					$jsondata = curl_exec( $ch );// @codingStandardsIgnoreLine.
				}
				$ip_location_array = false === json_decode( $jsondata ) ? json_decode( $core_data ) : json_decode( $jsondata );
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

					$wpdb->insert( clean_up_optimizer_ip_locations(), $ip_location_array_data ); // WPCS: db call ok.
				}
				return false === json_decode( $jsondata ) ? json_decode( $core_data ) : json_decode( $jsondata );
			}
		}
	}

	if ( ! function_exists( 'get_clean_up_optimizer_details_login_count_check' ) ) {
		/**
		 * This function is used to get count of login details .
		 *
		 * @param array  $data .
		 * @param string $date .
		 * @param string $time_interval .
		 * @param string $ip_address .
		 */
		function get_clean_up_optimizer_details_login_count_check( $data, $date, $time_interval, $ip_address ) {
			$clean_up_details = array();
			foreach ( $data as $raw_row ) {
				$row = maybe_unserialize( $raw_row->meta_value );
				if ( $ip_address === $row['user_ip_address'] ) {
					if ( 'permanently' !== $time_interval ) {
						if ( 'Failure' === $row['status'] && $row['date_time'] + $time_interval >= $date ) {
							array_push( $clean_up_details, $row );
						}
					} else {
						if ( 'Failure' === $row['status'] ) {
							array_push( $clean_up_details, $row );
						}
					}
				}
			}
			return $clean_up_details;
		}
	}

	if ( ! function_exists( 'cron_scheduler_for_intervals_clean_up_optimizer' ) ) {
		/**
		 * This function is used to cron scheduler for intervals.
		 *
		 * @param array $schedules .
		 */
		function cron_scheduler_for_intervals_clean_up_optimizer( $schedules ) {
			$schedules['1Hour']   = array(
				'interval' => 60 * 60,
				'display'  => 'Every 1 Hour',
			);
			$schedules['2Hour']   = array(
				'interval' => 60 * 60 * 2,
				'display'  => 'Every 2 Hours',
			);
			$schedules['3Hour']   = array(
				'interval' => 60 * 60 * 3,
				'display'  => 'Every 3 Hours',
			);
			$schedules['4Hour']   = array(
				'interval' => 60 * 60 * 4,
				'display'  => 'Every 4 Hours',
			);
			$schedules['5Hour']   = array(
				'interval' => 60 * 60 * 5,
				'display'  => 'Every 5 Hours',
			);
			$schedules['6Hour']   = array(
				'interval' => 60 * 60 * 6,
				'display'  => 'Every 6 Hours',
			);
			$schedules['7Hour']   = array(
				'interval' => 60 * 60 * 7,
				'display'  => 'Every 7 Hours',
			);
			$schedules['8Hour']   = array(
				'interval' => 60 * 60 * 8,
				'display'  => 'Every 8 Hours',
			);
			$schedules['9Hour']   = array(
				'interval' => 60 * 60 * 9,
				'display'  => 'Every 9 Hours',
			);
			$schedules['10Hour']  = array(
				'interval' => 60 * 60 * 10,
				'display'  => 'Every 10 Hours',
			);
			$schedules['11Hour']  = array(
				'interval' => 60 * 60 * 11,
				'display'  => 'Every 11 Hours',
			);
			$schedules['12Hour']  = array(
				'interval' => 60 * 60 * 12,
				'display'  => 'Every 12 Hours',
			);
			$schedules['13Hour']  = array(
				'interval' => 60 * 60 * 13,
				'display'  => 'Every 13 Hours',
			);
			$schedules['14Hour']  = array(
				'interval' => 60 * 60 * 14,
				'display'  => 'Every 14 Hours',
			);
			$schedules['15Hour']  = array(
				'interval' => 60 * 60 * 15,
				'display'  => 'Every 15 Hours',
			);
			$schedules['16Hour']  = array(
				'interval' => 60 * 60 * 16,
				'display'  => 'Every 16 Hours',
			);
			$schedules['17Hour']  = array(
				'interval' => 60 * 60 * 17,
				'display'  => 'Every 17 Hours',
			);
			$schedules['18Hour']  = array(
				'interval' => 60 * 60 * 18,
				'display'  => 'Every 18 Hours',
			);
			$schedules['19Hour']  = array(
				'interval' => 60 * 60 * 19,
				'display'  => 'Every 19 Hours',
			);
			$schedules['20Hour']  = array(
				'interval' => 60 * 60 * 20,
				'display'  => 'Every 20 Hours',
			);
			$schedules['21Hour']  = array(
				'interval' => 60 * 60 * 21,
				'display'  => 'Every 21 Hours',
			);
			$schedules['22Hour']  = array(
				'interval' => 60 * 60 * 22,
				'display'  => 'Every 22 Hours',
			);
			$schedules['23Hour']  = array(
				'interval' => 60 * 60 * 23,
				'display'  => 'Every 23 Hours',
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

	if ( ! function_exists( 'unschedule_events_clean_up_optimizer' ) ) {
		/**
		 * This function is used to unscheduling the events.
		 *
		 * @param string $cron_name .
		 */
		function unschedule_events_clean_up_optimizer( $cron_name ) {
			if ( wp_next_scheduled( $cron_name ) ) {
				$db_cron = wp_next_scheduled( $cron_name );
				wp_unschedule_event( $db_cron, $cron_name );
			}
		}
	}

	if ( ! function_exists( 'plugin_load_textdomain_clean_up_optimizer' ) ) {
		/**
		 * This function is used to load languages.
		 */
		function plugin_load_textdomain_clean_up_optimizer() {
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				load_plugin_textdomain( 'wp-clean-up-optimizer', false, CLEAN_UP_OPTIMIZER_PLUGIN_DIRNAME . '/languages' );
			}
		}
	}

	/**
	 * This function is used to check login page.
	 */
	function authenticate_blocked_user_clean_up_optimizer() {
		global $wpdb;
		$meta_values           = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key IN(%s,%s)',
				'error_message',
				'other_settings'
			)
		);// WPCS: db call ok, no-cache ok.
		$meta_values_ip_blocks = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key IN(%s,%s,%s)',
				'block_ip_address',
				'block_ip_range',
				'country_blocks'
			)
		);// WPCS: db call ok, no-cache ok.
		$meta_data_array       = array();
		foreach ( $meta_values as $row ) {
			$meta_data_array[ $row->meta_key ] = maybe_unserialize( $row->meta_value );// WPCS: Slow Query.
		}
		$error_message_array = $meta_data_array['error_message'];

		$ip_address = '::1' == get_ip_address_clean_up_optimizer() ? sprintf( '%u', ip2long( '0.0.0.0' ) ) : sprintf( '%u', ip2long( get_ip_address_clean_up_optimizer() ) );// WPCS Loose comparison ok.
		$location   = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $ip_address ) );
		manage_security_settings_for_clean_up_optimizer( $error_message_array, $meta_values_ip_blocks, $ip_address, $location );
	}

	if ( ! function_exists( 'admin_functions_clean_up_optimizer' ) ) {
		/**
		 * This function is used for calling add_action .
		 */
		function admin_functions_clean_up_optimizer() {
			install_script_for_clean_up_optimizer();
			helper_file_for_clean_up_optimizer();
			authenticate_blocked_user_clean_up_optimizer();
		}
	}

	if ( ! function_exists( 'user_functions_clean_up_optimizer' ) ) {
		/**
		 * This function is used for calling add_action for frontend .
		 */
		function user_functions_clean_up_optimizer() {
			global $wpdb;
			plugin_load_textdomain_clean_up_optimizer();
			$meta_values           = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key IN(%s,%s)',
					'error_message',
					'other_settings'
				)
			);// WPCS: db call ok, no-cache ok.
			$meta_values_ip_blocks = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT meta_key,meta_value FROM ' . $wpdb->prefix . 'clean_up_optimizer_meta WHERE meta_key IN(%s,%s,%s)',
					'block_ip_address',
					'block_ip_range',
					'country_blocks'
				)
			);// WPCS: db call ok, no-cache ok.
			$meta_data_array       = array();
			foreach ( $meta_values as $row ) {
				$meta_data_array[ $row->meta_key ] = maybe_unserialize( $row->meta_value );// WPCS: Slow Query.
			}
			$other_settings_array = $meta_data_array['other_settings'];

			$ip_address = '::1' == get_ip_address_clean_up_optimizer() ? sprintf( '%u', ip2long( '0.0.0.0' ) ) : sprintf( '%u', ip2long( get_ip_address_clean_up_optimizer() ) );// WPCS Loose comparison ok.
			$location   = get_ip_location_clean_up_optimizer( long2ip_clean_up_optimizer( $ip_address ) );
			if ( array_key_exists( 'visitor_logs_monitoring', $other_settings_array ) && array_key_exists( 'live_traffic_monitoring', $other_settings_array ) ) {
				if ( 'enable' === $other_settings_array['visitor_logs_monitoring'] || 'enable' === $other_settings_array['live_traffic_monitoring'] ) {
					visitor_logs_insertion_clean_up_optimizer( $meta_data_array, $ip_address, $location );
				}
			}
		}
	}
	/**
	 * This function is used to create link for Pro Editions.
	 *
	 * @param string $plugin_link .
	 */
	function clean_up_optimizer_action_links( $plugin_link ) {
		$plugin_link[] = '<a href="https://tech-banker.com/clean-up-optimizer/pricing/" style="color: red; font-weight: bold;" target="_blank">Go Pro!</a>';
		return $plugin_link;
	}

	if ( ! function_exists( 'deactivation_function_for_clean_up_optimizer' ) ) {
		/**
		 * This function is used for executing the code on deactivation.
		 */
		function deactivation_function_for_clean_up_optimizer() {
			delete_option( 'clean-up-optimizer-wizard-set-up' );
		}
	}

	/* Hooks */

	/**
	 * This hook is used for calling all the Backend Functions
	 */
	add_action( 'admin_init', 'admin_functions_clean_up_optimizer' );

	/**
	 * This hook is used to register ajax
	 */
	add_action( 'wp_ajax_clean_up_optimizer_action', 'ajax_register_clean_up_optimizer' );

	/**
	 * This hook is used for calling all the Backend Functions
	 */
	add_action( 'init', 'user_functions_clean_up_optimizer' );

	/**
	 * This hook is uesd for calling the function of sidebar menu.
	 */
	add_action( 'admin_menu', 'sidebar_menu_for_clean_up_optimizer' );

	/**
	 * This hook is used for calling the function of sidebar menuin multisite case.
	 */
	add_action( 'network_admin_menu', 'sidebar_menu_for_clean_up_optimizer' );

	/**
	 * This hook is used for calling the function of topbar menu.
	 */
	add_action( 'admin_bar_menu', 'topbar_menu_for_clean_up_optimizer', 100 );

	/**
	 * This hook is used for calling function of check user login status.
	 */
	add_action( 'wp_authenticate', 'user_login_status_clean_up_optimizer', 10, 2 );
	add_action( 'wp_authenticate', 'authenticate_blocked_user_clean_up_optimizer' );

	/**
	 * This hook is used for calling the function of cron schedulers jobs for WordPress data and database.
	 */
	add_filter( 'cron_schedules', 'cron_scheduler_for_intervals_clean_up_optimizer' );

		/**
	 * This Hook is used for calling the function of deactivation.
	 */
	register_deactivation_hook( __FILE__, 'deactivation_function_for_clean_up_optimizer' );

	/**
	 * This hook is used for create link for premium Edition.
	 */
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'clean_up_optimizer_action_links' );

	/**
	 * This hook is used for create link for Plugin Settings.
	 */
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'clean_up_optimizer_settings_action_links' );
}

/**
 * This hook is used for calling the function of install script.
 */
register_activation_hook( __FILE__, 'install_script_for_clean_up_optimizer' );

/**
 * This hook used for calling the function of install script
 */
add_action( 'admin_init', 'install_script_for_clean_up_optimizer' );


if ( ! function_exists( 'plugin_activate_cleanup_optimizer' ) ) {
	/**
	 * This function is used to add option.
	 */
	function plugin_activate_cleanup_optimizer() {
		add_option( 'cleanup_optimizer_do_activation_redirect', true );
	}
}

if ( ! function_exists( 'cleanup_optimizer_redirect' ) ) {
	/**
	 * This function is used to redirect page.
	 */
	function cleanup_optimizer_redirect() {
		if ( get_option( 'cleanup_optimizer_do_activation_redirect', false ) ) {
			delete_option( 'cleanup_optimizer_do_activation_redirect' );
			wp_safe_redirect( admin_url( 'admin.php?page=cpo_dashboard' ) );
			exit;
		}
	}
}

/**
 * This hook is used for calling the function plugin_activate_cleanup_optimizer
 */
register_activation_hook( __FILE__, 'plugin_activate_cleanup_optimizer' );

/**
 * This hook is used for calling the function cleanup_optimizer_redirect
 */
add_action( 'admin_init', 'cleanup_optimizer_redirect' );

/**
 * This function is used to create the object of admin notices.
 */
function cleanup_optimizer_admin_notice_class() {
	global $wpdb;
	/**
	 * This class is used to add admin notices.
	 */
	class Cleanup_Optimizer_Admin_Notices { // @codingStandardsIgnoreLine
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
		 * Public Constructor
		 *
		 * @param array $config .
		 */
		public function __construct( $config = array() ) {
			// Runs the admin notice ignore function incase a dismiss button has been clicked.
			add_action( 'admin_init', array( $this, 'cpo_admin_notice_ignore' ) );
			// Runs the admin notice temp ignore function incase a temp dismiss link has been clicked.
			add_action( 'admin_init', array( $this, 'cpo_admin_notice_temp_ignore' ) );
			add_action( 'admin_notices', array( $this, 'cpo_display_admin_notices' ) );
		}
		/**
		 * Checks to ensure notices aren't disabled and the user has the correct permissions.
		 */
		public function cpo_admin_notices() {
			$settings = get_option( 'cpo_admin_notice' );
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
		public function change_admin_notice_cleanup_optimizer( $admin_notices ) {
			// Check options.
			if ( ! $this->cpo_admin_notices() ) {
				return false;
			}
			foreach ( $admin_notices as $slug => $admin_notice ) {
				// Call for spam protection.
				if ( $this->cpo_anti_notice_spam() ) {
					return false;
				}

				// Check for proper page to display on.
				if ( isset( $admin_notices[ $slug ]['pages'] ) && is_array( $admin_notices[ $slug ]['pages'] ) ) {
					if ( ! $this->cpo_admin_notice_pages( $admin_notices[ $slug ]['pages'] ) ) {
						return false;
					}
				}

				// Check for required fields.
				if ( ! $this->cpo_required_fields( $admin_notices[ $slug ] ) ) {

					// Get the current date then set start date to either passed value or current date value and add interval.
					$current_date = current_time( 'm/d/Y' );
					$start        = ( isset( $admin_notices[ $slug ]['start'] ) ? $admin_notices[ $slug ]['start'] : $current_date );
					$start        = date( 'm/d/Y' );
					$interval     = ( isset( $admin_notices[ $slug ]['int'] ) ? $admin_notices[ $slug ]['int'] : 0 );
					$date         = strtotime( '+' . $interval . ' days', strtotime( $start ) );
					$start        = date( 'm/d/Y', $date );

					// This is the main notices storage option.
					$admin_notices_option = get_option( 'cpo_admin_notice', array() );
					// Check if the message is already stored and if so just grab the key otherwise store the message and its associated date information.
					if ( ! array_key_exists( $slug, $admin_notices_option ) ) {
						$admin_notices_option[ $slug ]['start'] = date( 'm/d/Y' );
						$admin_notices_option[ $slug ]['int']   = $interval;
						update_option( 'cpo_admin_notice', $admin_notices_option );
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
						$query_str = ( isset( $admin_notices[ $slug ]['later_link'] ) ? $admin_notices[ $slug ]['later_link'] : esc_url( add_query_arg( 'cpo_admin_notice_ignore', $slug ) ) );
						if ( strpos( $slug, 'promo' ) === false ) {
							// Admin notice display output.
							echo '<div class="update-nag cpo-admin-notice" style="width:95%!important;">
															 <div></div>
																<strong><p>' . $admin_display_title . '</p></strong>
																<strong><p style="font-size:14px !important">' . $admin_display_msg . '</p></strong>
																<strong><ul>' . $admin_display_link . '</ul></strong>
															</div>';// WPCS: XSS ok.
						} else {
							echo '<div class="admin-notice-promo">';
							echo $admin_display_msg;// WPCS: XSS ok.
							echo '<ul class="notice-body-promo blue">
																		' . $admin_display_link . '
																	</ul>';// WPCS: XSS ok.
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
		public function cpo_anti_notice_spam() {
			if ( $this->notice_spam >= $this->notice_spam_max ) {
				return true;
			}
			return false;
		}
		/**
		 * Ignore function that gets ran at admin init to ensure any messages that were dismissed get marked.
		 */
		public function cpo_admin_notice_ignore() {
			// If user clicks to ignore the notice, update the option to not show it again.
			if ( isset( $_GET['cpo_admin_notice_ignore'] ) ) {// WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'cpo_admin_notice', array() );
				$admin_notices_option[ $_GET['cpo_admin_notice_ignore'] ]['dismissed'] = 1;// WPCS: CSRF ok, Input var ok, no-cache ok, sanitization ok.
				update_option( 'cpo_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( 'cpo_admin_notice_ignore' );
				wp_safe_redirect( $query_str );
				exit;
			}
		}
		/**
		 * Temp Ignore function that gets ran at admin init to ensure any messages that were temp dismissed get their start date changed.
		 */
		public function cpo_admin_notice_temp_ignore() {
			// If user clicks to temp ignore the notice, update the option to change the start date - default interval of 7 days.
			if ( isset( $_GET['cpo_admin_notice_temp_ignore'] ) ) {// WPCS: CSRF ok, input var ok.
				$admin_notices_option = get_option( 'cpo_admin_notice', array() );
				$current_date         = current_time( 'm/d/Y' );
				$interval             = ( isset( $_GET['int'] ) ? wp_unslash( $_GET['int'] ) : 7 );// WPCS: CSRF ok, input var ok, sanitization ok.
				$date                 = strtotime( '+' . $interval . ' days', strtotime( $current_date ) );
				$new_start            = date( 'm/d/Y', $date );

				$admin_notices_option[ $_GET['cpo_admin_notice_temp_ignore'] ]['start']     = $new_start;// WPCS: CSRF ok, input var ok, sanitization ok.
				$admin_notices_option[ $_GET['cpo_admin_notice_temp_ignore'] ]['dismissed'] = 0;// WPCS: CSRF ok, input var ok, sanitization ok.
				update_option( 'cpo_admin_notice', $admin_notices_option );
				$query_str = remove_query_arg( array( 'cpo_admin_notice_temp_ignore', 'cpo_int' ) );
				wp_safe_redirect( $query_str );
				exit;
			}
		}
		/**
		 * Display admin notice on pages.
		 *
		 * @param array $pages .
		 */
		public function cpo_admin_notice_pages( $pages ) {
			foreach ( $pages as $key => $page ) {
				if ( is_array( $page ) ) {
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page[0] && isset( $_GET['tab'] ) && $_GET['tab'] === $page[1] ) {// WPCS: CSRF ok, input var ok.
						return true;
					}
				} else {
					if ( 'all' === $page ) {
						return true;
					}
					if ( get_current_screen()->id === $page ) {
						return true;
					}
					if ( isset( $_GET['page'] ) && $_GET['page'] === $page ) {// WPCS: CSRF ok, input var ok.
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
		public function cpo_required_fields( $fields ) {
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
		public function cpo_display_admin_notices() {
			$two_week_review_ignore = add_query_arg( array( 'cpo_admin_notice_ignore' => 'two_week_review' ) );
			$two_week_review_temp   = add_query_arg(
				array(
					'cpo_admin_notice_temp_ignore' => 'two_week_review',
					'int'                          => 7,
				)
			);

			$notices['two_week_review'] = array(
				'title'      => __( 'Leave A Clean Up Optimizer Review?', 'wp-clean-up-optimizer' ),
				'msg'        => __( 'We love and care about you. Clean Up Optimizer Team is putting our maximum efforts to provide you the best functionalities.<br> We would really appreciate if you could spend a couple of seconds to give a Nice Review to the plugin for motivating us!', 'wp-clean-up-optimizer' ),
				'link'       => '<span class="dashicons dashicons-external clean-up-optimizer-admin-notice"></span><span class="clean-up-optimizer-admin-notice"><a href="https://wordpress.org/support/plugin/wp-clean-up-optimizer/reviews/?filter=5" target="_blank" class="clean-up-optimizer-admin-notice-link">' . __( 'Sure! I\'d love to!', 'wp-clean-up-optimizer' ) . '</a></span>
												<span class="dashicons dashicons-smiley clean-up-optimizer-admin-notice"></span><span class="clean-up-optimizer-admin-notice"><a href="' . $two_week_review_ignore . '" class="clean-up-optimizer-admin-notice-link"> ' . __( 'I\'ve already left a review', 'wp-clean-up-optimizer' ) . '</a></span>
												<span class="dashicons dashicons-calendar-alt clean-up-optimizer-admin-notice"></span><span class="clean-up-optimizer-admin-notice"><a href="' . $two_week_review_temp . '" class="clean-up-optimizer-admin-notice-link">' . __( 'Maybe Later', 'wp-clean-up-optimizer' ) . '</a></span>',
				'later_link' => $two_week_review_temp,
				'int'        => 7,
			);

			$this->change_admin_notice_cleanup_optimizer( $notices );
		}
	}
	$plugin_info_cleanup_optimizer = new Cleanup_Optimizer_Admin_Notices();
}
add_action( 'init', 'cleanup_optimizer_admin_notice_class' );
/**
 * Add Pop on deactivation.
 */
function add_popup_on_deactivation_clean_up_optimizer() {
	global $wpdb;
	/**
	 * Display deactivation form.
	 */
	class clean_up_optimizer_deactivation_form {// @codingStandardsIgnoreLine.
		/**
		 * Public Constructor.
		 */
		function __construct() {
			add_action( 'wp_ajax_post_user_feedback_clean_up_optimizer', array( $this, 'post_user_feedback_clean_up_optimizer' ) );
			global $pagenow;
			if ( 'plugins.php' === $pagenow ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'feedback_form_js_clean_up_optimizer' ) );
				add_action( 'admin_head', array( $this, 'add_form_layout_clean_up_optimizer' ) );
				add_action( 'admin_footer', array( $this, 'add_deactivation_dialog_form_clean_up_optimizer' ) );
			}
		}
		/**
		 * Add css and js files.
		 */
		function feedback_form_js_clean_up_optimizer() {
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_register_script( 'cpo-post-feedback', plugins_url( 'assets/global/plugins/deactivation/deactivate-popup.js', __FILE__ ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ), false, true );
			wp_localize_script( 'cpo-post-feedback', 'post_feedback', array( 'admin_ajax' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'cpo-post-feedback' );
		}
		/**
		 * Post user Fedback.
		 */
		function post_user_feedback_clean_up_optimizer() {
			$clean_up_optimizer_deactivation_reason = isset( $_POST['reason'] ) ? wp_unslash( $_POST['reason'] ) : ''; // WPCS: CSRF ok, input var ok, sanitization ok.
			$type                                   = get_option( 'clean-up-optimizer-wizard-set-up' );
			$user_admin_email                       = get_option( 'clean-up-optimizer-admin-email' );
			$plugin_info_clean_up_optimizer         = new Plugin_Info_Clean_Up_Optimizer();
			global $wp_version, $wpdb;
			$url           = TECH_BANKER_STATS_URL . '/wp-admin/admin-ajax.php';
			$theme_details = array();
			if ( $wp_version >= 3.4 ) {
				$active_theme                   = wp_get_theme();
				$theme_details['theme_name']    = strip_tags( $active_theme->name );
				$theme_details['theme_version'] = strip_tags( $active_theme->version );
				$theme_details['author_url']    = strip_tags( $active_theme->{'Author URI'} );
			}
			$plugin_stat_data                     = array();
			$plugin_stat_data['plugin_slug']      = 'wp-clean-up-optimizer';
			$plugin_stat_data['reason']           = $clean_up_optimizer_deactivation_reason;
			$plugin_stat_data['type']             = 'standard_edition';
			$plugin_stat_data['version_number']   = CLEAN_UP_OPTIMIZER_VERSION_NUMBER;
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
			$plugin_stat_data['plugins']          = $plugin_info_clean_up_optimizer->get_plugin_info_clean_up_optimizer();
			$plugin_stat_data['themes']           = $theme_details;

			$response = wp_safe_remote_post(
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
				die( 'success' );
		}
		/**
		 * Add form layout of deactivation form.
		 */
		function add_form_layout_clean_up_optimizer() {
			?>
			<style type="text/css">
					.clean-up-optimizer-feedback-form .ui-dialog-buttonset {
						float: none !important;
					}
					#clean-up-optimizer-feedback-dialog-continue,#clean-up-optimizer-feedback-dialog-skip {
						float: right;
					}
					#clean-up-optimizer-feedback-cancel{
						float: left;
					}
					#clean-up-optimizer-feedback-content p {
						font-size: 1.1em;
					}
					.clean-up-optimizer-feedback-form .ui-icon {
						display: none;
					}
					#clean-up-optimizer-feedback-dialog-continue.clean-up-optimizer-ajax-progress .ui-icon {
						text-indent: inherit;
						display: inline-block !important;
						vertical-align: middle;
						animation: rotate 2s infinite linear;
					}
					#clean-up-optimizer-feedback-dialog-continue.clean-up-optimizer-ajax-progress .ui-button-text {
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
		function add_deactivation_dialog_form_clean_up_optimizer() {
			?>
			<div id="clean-up-optimizer-feedback-content" style="display: none;">
				<p style="margin-top:-5px"><?php echo esc_attr( __( 'We feel guilty when anyone stop using Clean Up Optimizer.', 'wp-clean-up-optimizer' ) ); ?></p>
				<p><?php echo esc_attr( __( 'If Clean Up Optimizer isn\'t working for you, others also may not.', 'wp-clean-up-optimizer' ) ); ?></p>
				<p><?php echo esc_attr( __( 'We would love to hear your feedback about what went wrong.', 'wp-clean-up-optimizer' ) ); ?></p>
				<p><?php echo esc_attr( __( 'We would like to help you in fixing the issue.', 'wp-clean-up-optimizer' ) ); ?></p>
				<p><?php echo esc_attr( __( 'If you click Continue, some data would be sent to our servers for Compatiblity Testing Purposes.', 'wp-clean-up-optimizer' ) ); ?></p>
				<p><?php echo esc_attr( __( 'If you Skip, no data would be shared with our servers.', 'wp-clean-up-optimizer' ) ); ?></p>
				<form>
					<?php wp_nonce_field(); ?>
					<ul id="clean-up-optimizer-deactivate-reasons clean-up-optimizer-custom-input">
						<li class="clean-up-optimizer-reason">
							<label>
								<span><input value="0" type="radio" name="reason"/></span>
								<span><?php echo esc_attr( __( 'The Plugin didn\'t work', 'wp-clean-up-optimizer' ) ); ?></span>
							</label>
						</li>
						<li class="clean-up-optimizer-reason clean-up-optimizer-custom-input">
							<label>
								<span><input value="1" type="radio" name="reason" /></span>
								<span><?php echo esc_attr( __( 'I found a better Plugin', 'wp-clean-up-optimizer' ) ); ?></span>
							</label>
						</li>
						<li class="clean-up-optimizer-reason">
							<label>
								<span><input value="2" type="radio" name="reason" checked /></span>
								<span><?php echo esc_attr( __( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'wp-clean-up-optimizer' ) ); ?></span>
							</label>
						</li>
						<li class="clean-up-optimizer-reason clean-up-optimizer-custom-input">
							<label>
								<span><input value="3" type="radio" name="reason" /></span>
								<span><a href="https://wordpress.org/support/plugin/clean-up-optimizer" target="_blank"><?php echo esc_attr( __( 'Open a Support Ticket for me.', 'wp-clean-up-optimizer' ) ); ?></a></span>
							</label>
						</li>
					</ul>
				</form>
			</div>
		<?php
		}
	}
	$plugin_deactivation_details = new clean_up_optimizer_deactivation_form();
}
add_action( 'plugins_loaded', 'add_popup_on_deactivation_clean_up_optimizer' );
/**
 * This function is used to add deactivate link on deactivation.
 *
 * @param array $links .
 */
function insert_deactivate_link_id_clean_up_optimizer( $links ) {
	if ( ! is_multisite() ) {
		$links['deactivate'] = str_replace( '<a', '<a id="clean-up-optimizer-plugin-disable-link"', $links['deactivate'] );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'insert_deactivate_link_id_clean_up_optimizer', 10, 2 );

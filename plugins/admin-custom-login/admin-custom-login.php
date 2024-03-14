<?php
/**
 * Plugin Name: Admin Custom Login
 * Version: 3.5.5
 * Description: Customize Your WordPress Login Screen Amazingly - Add Own Logo, Add Social Profiles, Login Form Positions, Background Image Slide Show
 * Author: Weblizar
 * Author URI: https://weblizar.com/plugins/
 * Plugin URI: https://wordpress.org/plugins/admin-custom-login/
 * Text Domain: admin-custom-login
 * Domain Path: /languages
 */

/** Exit if accessed directly **/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WEBLIZAR_NALF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WEBLIZAR_ACL_PLUGIN_DIR_PATH_FREE', plugin_dir_path( __FILE__ ) );
define( 'WEBLIZAR_ACL', 'admin-custom-login' );

final class WL_ACL_FREE {
	private static $instance = null;

	private function __construct() {
		$this->initialize_hooks();
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function initialize_hooks() {
		require_once 'admin/index.php';
	}
}
WL_ACL_FREE::get_instance();

/*** Admin Custom Login installation script */
register_activation_hook( __FILE__, 'ACL_WeblizarDoInstallation' );
function ACL_WeblizarDoInstallation() {
	$plugin_data = get_plugin_data( __FILE__ );
	require_once 'installation.php';
}

/**
 * @param $links
 * @return mixed
 */
function acl_links( $links ) {
	$acl_pro_link      = '<a href="https://weblizar.com/plugins/admin-custom-login-pro/" target="_blank">' . esc_html__( 'Go Pro', WEBLIZAR_ACL ) . '</a>';
	$acl_settings_link = '<a href="admin.php?page=admin_custom_login">' . esc_html__( 'Settings', WEBLIZAR_ACL ) . '</a>';
	array_unshift( $links, $acl_settings_link );
	array_unshift( $links, $acl_pro_link );
	return $links;
}
$acl_plugin_name = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$acl_plugin_name", 'acl_links' );
require_once WEBLIZAR_ACL_PLUGIN_DIR_PATH_FREE . '/init.php';

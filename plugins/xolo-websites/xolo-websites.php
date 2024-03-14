<?php
/*
Plugin Name: Xolo Websites
Plugin URI: https://xolowebsites.com/
Description: Import Xolo Websites Demo content.
Version: 1.6
Author: xolosoftware
Author URI: https://profiles.wordpress.org/xolosoftware/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: xolo-websites
*/

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! defined( 'XOLO_WEBSITES_FILE' ) ) {
	define( 'XOLO_WEBSITES_FILE', __FILE__ );
}

if ( ! defined( 'XOLO_WEBSITES_BASE' ) ) {
	define( 'XOLO_WEBSITES_BASE', plugin_basename( XOLO_WEBSITES_FILE ) );
}

define( 'XOLO_WEB_DIR_URI', plugin_dir_url( __FILE__ ) );
include( plugin_dir_path( __FILE__ ) . '/inc/xolo_dependencies.php' );
register_activation_hook( __FILE__, 'xolo_web_activation_logic' );

if ( strpos( get_template(), 'pro' ) == true || strpos( get_template(), 'premium' ) == true ) {
    $plugin_state = 'pro';
} else {
	$plugin_state = 'free';
}

define( 'XOLO_WEB_PLUGIN_STATE' , $plugin_state );

function XOLO_WEB_import_files() {

	$buy_premium_url = 'https://wpxolo.com/';
	add_action( 'wp_ajax_xolo-websites-activate-theme', array( $buy_premium_url, 'activate_theme' ) );
	
	return array(	
		array(
			'import_file_name'           => __( 'Xolo Free', 'xolo-websites' ),
			'categories'                 => array( 'Business' ),
			'import_file_url'            => XOLO_WEB_DIR_URL.'/inc/import/free/demo-1/xolo-site.xml',
		    'import_widget_file_url'     => XOLO_WEB_DIR_URL.'/inc/import/free/demo-1/xolo-widget.wie',
		    'import_customizer_file_url' => XOLO_WEB_DIR_URL.'/inc/import/free/demo-1/xolo-settings.dat',
			'import_preview_image_url'   => XOLO_WEB_DIR_URL.'/assets/images/demo/free/screenshot.jpg',
			'preview_url'                => 'https://xolotheme.com/free/demo1/',
		),
		// array(
			// 'import_file_name'           => __( 'Xolo Pro', 'xolo-websites' ),
			// 'categories'                 => array( 'Business' ),
			// 'import_preview_image_url'   => XOLO_WEB_DIR_URL.'/assets/images/demo/pro/screenshot.jpg',
			// 'preview_url'                => 'https://xolowebsites.com/elementor/demo-1',
			// 'premium_url'                => $buy_premium_url,
		// ),	
		
	);
}
add_filter( 'XOLO-WEBSITES/import_files', 'XOLO_WEB_import_files' );

// Automatically assign "Front page", "Posts page" and menu locations after the importer is done
function xolo_web_after_import_setup( $selected_import ) {
	// Assign menus to their locations.
	$primary_menu 		= get_term_by( 'name', 'Primary Menu', 'nav_menu' );
	$footer_menu 	= get_term_by( 'name', 'Footer Menu', 'nav_menu' );

	set_theme_mod( 'nav_menu_locations', array(
			'primary_menu' => $primary_menu->term_id, // replace 'main-menu' here with the menu location identifier from register_nav_menu() function
			'mobile_menu' => $primary_menu->term_id,
			'footer_menu' => $footer_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

}
add_action( 'XOLO-WEBSITES/after_import', 'xolo_web_after_import_setup' );



/**
 * Activate theme
 *
 * @since 1.3.2
 * @return void
 */
function activate_theme() {

	// Verify Nonce.
	check_ajax_referer( 'xolo-websites', '_ajax_nonce' );

	if ( ! current_user_can( 'customize' ) ) {
		wp_send_json_error( __( 'You are not allowed to perform this action', 'xolo-websites' ) );
	}

	switch_theme( 'xolo' );

	wp_send_json_success(
		array(
			'success' => true,
			'message' => __( 'Theme Activated', 'xolo-websites' ),
		)
	);
}
		
/**
 * Main plugin class with initialization tasks.
 */
class XOLO_WEB_Plugin {
	/**
	 * Constructor for this class.
	 */
	public function __construct() {
		/**
		 * Display admin error message if PHP version is older than 5.3.2.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.3.2', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		}
		else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Composer autoloader.
			require_once XOLO_WEB_DIR_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$pt_one_click_demo_import = XOLO_WEBS\XoloWebDemoImport::get_instance();

			// Register WP CLI commands
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'XOLO_WEBS list', array( 'XOLO_WEBS\WPCLICommands', 'list_predefined' ) );
				WP_CLI::add_command( 'XOLO_WEBS import', array( 'XOLO_WEBS\WPCLICommands', 'import' ) );
			}
		}
	}


	/**
	 * Display an admin error notice when PHP is older the version 5.3.2.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() {
		$message = sprintf( esc_html__( 'The %2$sXolo Websites%3$s plugin requires %2$sPHP 5.3.2+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 5.3.2.%4$s Your current version of PHP: %2$s%1$s%3$s', 'xolo-websites' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'XOLO_WEB_DIR_PATH' ) ) {
			define( 'XOLO_WEB_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'XOLO_WEB_DIR_URL' ) ) {
			define( 'XOLO_WEB_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}


	/**
	 * Set plugin version constant -> XOLO_WEB_VERSION.
	 */
	public function set_plugin_version_constant() {
		if ( ! defined( 'XOLO_WEB_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );
			define( 'XOLO_WEB_VERSION', $plugin_data['Version'] );
		}
	}
}

// Instantiate the plugin class.
$XOLO_WEB_Plugin = new XOLO_WEB_Plugin();


add_action( 'wp_ajax_xolo_web_install_act_plugin', 'xolo_webs_install_plugin' );

function xolo_webs_install_plugin() {
    /**
     * Install Plugin.
     */
    include_once ABSPATH . '/wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    //$plugin_list 	= $_POST[ 'plugin_slug' ];
    if ( XOLO_WEB_PLUGIN_STATE == 'free' ) {
    	$plugin_list	= array( 'elementor', 'contact-form-7' );
    } else {
    	$plugin_list	= array( 'elementor', 'contact-form-7' );
    }

    foreach ( $plugin_list as $plugin ) {
	    $api = plugins_api( 'plugin_information', array(
	        'slug'   => sanitize_key( wp_unslash( $plugin ) ),
	        'fields' => array(
	            'sections' => false,
	        ),
	    ) );

	    // If plugin not installed then install
    	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
		    if ( strpos( $plugin , 'premium' ) ) {
		    	$premium_plugin_url = 'https://xolosoftware.com/' . $plugin . '.zip';
		    	$upgrader = new Plugin_Upgrader();
  				$result = $upgrader->install( $premium_plugin_url );
		    } else {
			    $skin     = new WP_Ajax_Upgrader_Skin();
		    	$upgrader = new Plugin_Upgrader( $skin );
		    	$result   = $upgrader->install( $api->download_link );
		    }
		}

	    // Activate plugin
	    if ( strpos( $plugin , 'premium' ) ) {
			if ( current_user_can( 'activate_plugin' ) && is_plugin_inactive( $plugin . '/' . $plugin . '.php' ) ) {
				$eae_free_slug = str_replace( '-premium', '', $plugin );
				activate_plugin( $plugin . '/' . $plugin . '.php' );
			}
		} else {
			$install_status = install_plugin_install_status( $api );
		    // If user can activate plugin and if the plugin is not active
			if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
				$result = activate_plugin( $install_status['file'] );

				if ( is_wp_error( $result ) ) {
					$status['errorCode']    = $result->get_error_code();
					$status['errorMessage'] = $result->get_error_message();
					wp_send_json_error( $status );
				}
			}
		}
    } // End Foreach

    wp_send_json( $plugin_list );
}

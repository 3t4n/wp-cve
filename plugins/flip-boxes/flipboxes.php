<?php
/*
 Plugin Name:Cool Flipbox
 Plugin URI:https://coolplugins.net/
 Description:Use animated Flip Boxes WordPress plugin to highlight your content inside your page in a great way. Use shortcode to add anywhere.
 Version:1.8.3
 License:GPL2
 Author:Cool Plugins
 Author URI:https://coolplugins.net/
 License URI:https://www.gnu.org/licenses/gpl-2.0.html
 Domain Path: /languages
 Text Domain:c-flipboxes
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'CFB_VERSION' ) || define( 'CFB_VERSION', '1.8.3' );
defined( 'CFB_DIR_PATH' ) || define( 'CFB_DIR_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CFB_URL' ) || define( 'CFB_URL', plugin_dir_url( __FILE__ ) );

if ( ! class_exists( 'CflipBoxes' ) ) {

	/**
	 * Class CflipBoxes
	 */
	class CflipBoxes {

		/**
		 * Initializes the plugin functions
		 */
		function __construct() {
			if ( is_admin() ) {
				require_once CFB_DIR_PATH . 'admin/feedback/admin-feedback-form.php';  // Include admin feedback form
			}
			$this->cfb_includes(); // Include necessary files
			add_action( 'admin_enqueue_scripts', array( 'CFB_Functions', 'cfb_admin_assets' ) );  // Add action for admin assets
			add_action( 'activated_plugin', array( $this, 'cfb_activation_redirect' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'cfb_plugin_action_links' ) );
		}

		// added setting page link to the plugin
		function cfb_plugin_action_links( $links ) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=cfb_settings' ) . '">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		// redirect to the setting sub menu page when plugin is activated
		function cfb_activation_redirect( $plugin ) {
			if ( $plugin == plugin_basename( __FILE__ ) ) {
				exit( wp_redirect( admin_url( 'options-general.php?page=cfb_settings' ) ) );
			}
		}
		/**
		 * Include necessary files
		 */
		public function cfb_includes() {
			require_once CFB_DIR_PATH . '/includes/cfb-functions.php';  // Include plugin functions
			if ( get_option( 'cfb_flip_type_option', 'post' ) === 'post' ) {
				require_once CFB_DIR_PATH . '/includes/cfb-shortcode.php';  // Include shortcode
				new CFB_Shortcode();    // Initialize shortcode
			} else {
				require_once CFB_DIR_PATH . '/includes/cfb-block/inc/class-cfb-block.php';
				Cfb_Block::instance();
			}

			if ( is_admin() ) {
				require_once CFB_DIR_PATH . '/admin/cfb-post-type.php';  // Include post type for admin
				new CFB_post_type();  // Initialize post type
				require_once CFB_DIR_PATH . '/includes/cfb-feedback-notice.php';  // Include feedback notice
				new CFB_CoolPlugins_Review_Notice();  // Initialize review notice
			}

			if ( is_admin() && CFB_Functions::cfb_get_post_type_page() == 'flipboxes' ) {
				if ( file_exists( CFB_DIR_PATH . '/admin/CMB2/init.php' ) ) {
					require_once CFB_DIR_PATH . '/admin/CMB2/init.php';  // Include CMB2 initialization
					require_once CFB_DIR_PATH . '/admin/CMB2/cmb2-fontawesome-picker.php';  // Include fontawesome picker
				}
			}
		}

		/**
		 * Activating plugin and adding some info
		 */
		public static function activate() {
			  update_option( 'Flip-Boxes-v', CFB_VERSION );  // Update plugin version
			  update_option( 'Flip-Boxes-type', 'FREE' );  // Update plugin type
			  update_option( 'Flip-Boxes-installDate', date( 'Y-m-d h:i:s' ) );  // Update installation date
			if ( ! get_option( 'Flip-Boxes-ratingDiv' ) ) {
				update_option( 'Flip-Boxes-ratingDiv', 'no' );  // Update rating div
			}
		}
		// END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate() {
			// Do nothing
		}


	}//end class

}

// Installation and uninstallation hooks
register_activation_hook( __FILE__, array( 'CflipBoxes', 'activate' ) );  // Register activation hook
register_deactivation_hook( __FILE__, array( 'CflipBoxes', 'deactivate' ) );  // Register deactivation hook

$CflipBoxes_obj = new CflipBoxes(); // initialization of plugin


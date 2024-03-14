<?php
/**
 * Plugin Name: WPSyncSheets Lite For Elementor
 * Plugin URI: https://www.wpsyncsheets.com/wpsyncsheets-for-elementor/
 * Description: Save all Elementor Pro Form entries to Google Spreadsheet
 * Author: Creative Werk Designs
 * Author URI: http://www.creativewerkdesigns.com/
 * Version: 1.4
 * Text Domain: wpsse
 * Domain Path: /languages
 *
 * @package     wpsyncsheets-elementor
 * @author      Creative Werk Designs
 * @category    Plugin
 * @copyright   Copyright (c) 2021 Creative Werk Designs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! get_option( 'active_wpsyncsheets_elementor' ) ) {
	// Plugin version.
	define( 'WPSSLE_VERSION', '1.4' );
	// Plugin URL.
	define( 'WPSSLE_URL', plugin_dir_url( __FILE__ ) );
	// Plugin directory.
	define( 'WPSSLE_DIR', plugin_dir_path( __FILE__ ) );
	define( 'WPSSLE_LITE_ROOT', dirname( __FILE__ ) );
	define( 'WPSSLE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
	define( 'WPSSLE_DIRECTORY', dirname( plugin_basename( __FILE__ ) ) );
	define( 'WPSSLE_PLUGIN_SLUG', WPSSLE_DIRECTORY . '/' . basename( __FILE__ ) );
	define( 'WPSSLE_BASE_FILE', basename( dirname( __FILE__ ) ) . '/wpsyncsheets-lite-elementor.php' );
	define( 'WPSSLE_PRO_VERSION_URL', 'https://www.wpsyncsheets.com/wpsyncsheets-for-elementor/' );
	define( 'WPSSLE_DOCUMENTATION_URL', 'https://docs.wpsyncsheets.com/wpsse-introduction/' );
	define( 'WPSSLE_DOC_SHEET_SETTING_URL', 'https://docs.wpsyncsheets.com/wpsse-google-sheets-api-settings/' );
	define( 'WPSSLE_SUPPORT_URL', 'https://wordpress.org/support/plugin/wpsyncsheets-elementor/' );
	define( 'WPSSLE_DOC_MENU_URL', 'https://docs.wpsyncsheets.com' );
	/**
	 * Get list of active plugins.
	 *
	 * @param string $plugin plugin name.
	 */
	function wpssle_is_elementor_plugin_active( $plugin ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true );
	}
	// Check Elementor Pro plugin is active or not.
	if ( wpssle_is_elementor_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
		// Add methods if Elementor Pro is active.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpssle_add_action_links' );
		/**
		 * Add setings link at plugin page.
		 *
		 * @param array $wpssle_links Add settings link.
		 */
		function wpssle_add_action_links( $wpssle_links ) {
			$wpssle_mylinks = array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=wpsyncsheets-elementor' ) ) . '">' . esc_html__( 'Settings', 'wpsse' ) . '</a>',
			);
			return array_merge( $wpssle_mylinks, $wpssle_links );
		}
		// Define the class and the function.
		require_once dirname( __FILE__ ) . '/src/class-wpsyncsheetselementor.php';
		wpssle();
	} else {
		add_action( 'admin_notices', 'wppse_admin_notice' );
		if ( ! function_exists( 'wppse_admin_notice' ) ) {
			/**
			 * Add notice if Elementor pro plugin not install or active.
			 */
			function wppse_admin_notice() {
				?> 
				<div class="notice error">
					<div>
						<p><?php echo esc_html__( 'WPSyncSheets Lite For Elementor plugin requires', 'wpsse' ); ?> <a href="<?php echo esc_url( 'https://elementor.com/pro/' ); ?>" target = "_blank"><?php echo esc_html__( 'Elementor Pro', 'wpsse' ); ?></a> <?php echo esc_html__( 'plugin to be active!', 'wpsse' ); ?></p>
					</div>
				</div>
				<?php
			}
		}
	}
}

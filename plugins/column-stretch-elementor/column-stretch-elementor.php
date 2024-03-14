<?php
/**
 * Plugin Name: Column Stretch for Elementor
 * Description: An Elementor extension to easily stretch columns to left or right
 * Author: BloomPixel
 * Version: 1.0.3
 * Author URI: https://www.bloompixel.com/
 * Elementor tested up to: 3.17.0
 * Elementor Pro tested up to: 3.17.0
 *
 * Text Domain: elementor-stretch-column
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'COLUMN_STRETCH_ELEMENTOR_VERSION', '1.0.3' );

define( 'COLUMN_STRETCH_ELEMENTOR__FILE__', __FILE__ );
define( 'COLUMN_STRETCH_ELEMENTOR_PLUGIN_BASE', plugin_basename( COLUMN_STRETCH_ELEMENTOR__FILE__ ) );
define( 'COLUMN_STRETCH_ELEMENTOR_PATH', plugin_dir_path( COLUMN_STRETCH_ELEMENTOR__FILE__ ) );
define( 'COLUMN_STRETCH_ELEMENTOR_URL', plugins_url( '/', COLUMN_STRETCH_ELEMENTOR__FILE__ ) );
define( 'COLUMN_STRETCH_ELEMENTOR_ASSETS_URL', COLUMN_STRETCH_ELEMENTOR_URL . 'assets/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function column_stretch_elementor_load_plugin() {
	load_plugin_textdomain( 'column-stretch-elementor' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'column_stretch_elementor_fail_load' );
		return;
	}

	$elementor_version_required = '3.5.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'column_stretch_elementor_fail_load_out_of_date' );
		return;
	}

	require( COLUMN_STRETCH_ELEMENTOR_PATH . 'plugin.php' );
}
add_action( 'plugins_loaded', 'column_stretch_elementor_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function column_stretch_elementor_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Column Stretch for Elementor is not working because you need to activate the Elementor plugin.', 'column-stretch-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'column-stretch-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Column Stretch for Elementor is not working because you need to install the Elemenor plugin', 'column-stretch-elementor' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'column-stretch-elementor' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function column_stretch_elementor_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Column Stretch for Elementor is not working because you are using an old version of Elementor.', 'column-stretch-elementor' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'column-stretch-elementor' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}


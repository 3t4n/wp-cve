<?php
/**
 * Plugin Name: Elementor Imagebox 
 * Description: Additional Hover Styles for elementor Imagebox Widget.
 * Plugin URI: https://flickdevs.com/elementor/
 * Author: FlickDevs, Aezaz Shaikh
 * Version: 1.2.8
 * Elementor tested up to: 3.19.0
 * Author URI: https://www.flickdevs.com/
 *
 * Text Domain: fd-eaw
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_ADVANCED_IMAGEBOX_URL', plugins_url( '/', __FILE__ ) );  // Define Plugin URL 
define( 'ELEMENTOR_ADVANCED_IMAGEBOX_PATH', plugin_dir_path( __FILE__ ) );  // Define Plugin Directory Path
define( 'FD_EAW', 'fd-eaw' );

// load the plugin Category
require_once ELEMENTOR_ADVANCED_IMAGEBOX_PATH.'inc/elementor-helper.php';

//register the widgtes file in elementor widgtes.
function fd_imagebox_elements(){
	
	require_once ELEMENTOR_ADVANCED_IMAGEBOX_PATH.'elements/fd-adv-imagebox.php';
	
}
add_action('elementor/widgets/widgets_registered','fd_imagebox_elements');

// Incule css file
 function fd_imagebox_script(){
	
   wp_enqueue_style('fd-imagebox-element',ELEMENTOR_ADVANCED_IMAGEBOX_URL.'assets/css/fd-elemetor-imagebox.css',true);
}
add_action( 'wp_enqueue_scripts', 'fd_imagebox_script' ); 


/**
  *   Check the elementor current version.
  */
function fd_elementor_imagebox__load_plugin() {
	load_plugin_textdomain( 'FD_EAW' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'fd_elementor_imagebox_widgets_fail_load' );
		return;
	}

	$elementor_version_required = '2.6.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'fd_elementor_imagebox__fail_load_out_of_date' );
		return;
	}
}
add_action( 'plugins_loaded', 'fd_elementor_imagebox__load_plugin' );


function fd_elementor_imagebox_widgets_fail_load() {
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

		$message = '<p>' . __( 'Elementor Imagebox not working because you need to activate the Elementor plugin.', 'FD_EAW' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'FD_EAW' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Elementor Imagebox not working because you need to install the Elemenor plugin', 'FD_EAW' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'FD_EAW' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function fd_elementor_imagebox__fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Imagebox not working because you are using an old version of Elementor.', 'FD_EAW' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'FD_EAW' ) ) . '</p>';
	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}

add_action( 'admin_notices', 'imagebox_plugin_feedback' );
function imagebox_plugin_feedback(){
	if ( ! is_admin() ) {
			return;
		}
		else if ( ! is_user_logged_in() ) {
			return;
		}
		else if ( ! current_user_can( 'update_core' ) ) {
			return;
		}
		if ( is_plugin_active( 'fd-elementor-imagebox/fd-elementor-imagebox.php' ) ) {
			echo sprintf('<div class="notice notice-info is-dismissible"><p>Give Best <a href="https://wordpress.org/support/plugin/fd-elementor-imagebox/reviews/" target="_blank">Feedback And Ratings</a> to our  <strong> Elementor Imagebox Plugin</strong></p></div>','FD_EAW');
		}
}


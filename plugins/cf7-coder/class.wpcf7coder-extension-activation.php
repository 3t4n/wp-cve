<?php
/**
 * Activation handler
 *
 * @package     wpcf7\ActivationHandler
 * @since       1.0.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * wpcf7 Extension Activation Handler Class
 *
 * @since       1.0.0
 */
class wpcf7_Coder_Extension_Activation {

	public $plugin_name, $plugin_path, $plugin_file, $has_wpcf7, $wpcf7_base;

	/**
	 * Setup the activation class
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function __construct( $plugin_path, $plugin_file ) {
		// We need plugin.php!
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugins = get_plugins();

		// Set plugin directory
		$plugin_path       = array_filter( explode( '/', $plugin_path ) );
		$this->plugin_path = end( $plugin_path );

		// Set plugin file
		$this->plugin_file = $plugin_file;

		// Set plugin name
		if ( isset( $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name'] ) ) {
			$this->plugin_name = str_replace( 'for Contact Form 7', '', $plugins[ $this->plugin_path . '/' . $this->plugin_file ]['Name'] );
		} else {
			$this->plugin_name = esc_attr__( 'This plugin', 'cf7-coder' );
		}

		// Is Contact Form 7 installed?
		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( $plugin['Name'] == 'Contact Form 7' ) {
				$this->has_wpcf7  = true;
				$this->wpcf7_base = $plugin_path;
				break;
			}
		}
	}


	/**
	 * Process plugin deactivation
	 *
	 * @access      public
	 * @return      void
	 * @since       1.0.0
	 */
	public function run() {
		// Display notice
		add_action( 'admin_notices', array( $this, 'missing_wpcf7_notice' ) );
	}


	/**
	 * Display notice if wpcf7 isn't installed
	 *
	 * @access      public
	 * @return      string The notice to displayS
	 * @since       1.0.0
	 */
	public function missing_wpcf7_notice() {
		if ( $this->has_wpcf7 ) {
			$url  = esc_url( wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $this->wpcf7_base ), 'activate-plugin_' . $this->wpcf7_base ) );
			$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'activate it', 'cf7-coder' ) . '</a>';
		} else {
			$url  = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=wpcf7' ), 'install-plugin_wpcf7' ) );
			$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'install it', 'cf7-coder' ) . '</a>';
		}

		echo '<div class="error"><p>' . esc_html( $this->plugin_name ) . sprintf( esc_html__( ' requires Contact Form 7! Please %s first and then activate this.', 'cf7-coder' ), $link ) . '</p></div>';
	}
}

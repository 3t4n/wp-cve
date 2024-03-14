<?php
/*
Plugin Name: Meks Quick Plugin Disabler
Plugin URI: https://mekshq.com
Description: Temporarily disable (and restore) all currently active plugins with a single click. Main purpose of the plugin is to quickly find out if any recent issue you are having on your website is related to the one of currently active plugins.
Version: 1.0
Author: Meks
Author URI: https://mekshq.com
Text Domain: meks-quick-plugin-disabler
Domain Path: /languages
*/


/* Prevent direct access */

if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}



/**
* Plugin main class
*/

class Meks_Quick_Plugin_Disabler {

	private $disable_action = 'meks-disable-all-plugins';
	private $restore_action = 'meks-restore-all-plugins';
	private $mirror = 'meks_quick_plugin_disabler_temp';
	private $active = 'active_plugins';

	/**
	 * Class constructor
	 */

	function __construct() {

		// Load translations
		add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );

		//Check disable/revert actions
		add_action( 'admin_init', array( $this, 'check' ) );

		//Display action links
		add_action( 'pre_current_active_plugins', array( $this, 'action_links' ) );


	}


	/**
	 * Callback function to display action links for disabling and restoring
	 */
	
	function action_links() {

		$active = get_option( $this->active );
		$mirror = get_option( $this->mirror );

		// echo 'mirror';
		// print_r( $mirror );
		// echo '<br/>';
		// echo 'active';
		// print_r( $active );

		if ( empty( $mirror ) && !empty( $active ) && count( $active ) > 1 ) {
			echo '<a href="'. esc_url( add_query_arg( $this->disable_action, '1', admin_url( 'plugins.php' ) ) ) .'" style="display:block; max-width: 300px;" class="widget-control-remove">'.esc_html__( 'Temporarily disable all active plugins', 'meks-quick-plugin-disabler' ).'</a>';
		} else {

			if ( !empty( $mirror ) ) {
				echo '<a href="'. esc_url( add_query_arg( $this->restore_action, '1', admin_url( 'plugins.php' ) ) ).'" style="display:block; max-width: 300px;">'.esc_html__( 'Restore disabled plugins', 'meks-quick-plugin-disabler' ).'</a></div>';
			}
		}

	}

	/**
	 * Main function that checks if we should perform disabling or restoring
	 */
	
	function check() {

		global $pagenow;

		if ( $pagenow == 'plugins.php' ) {

			if ( isset( $_GET[$this->disable_action] ) && !empty( $_GET[$this->disable_action] ) ) {
				$mirror = get_option( $this->mirror ) ? get_option( $this->mirror ) : array();
				$active = get_option( $this->active ) ? get_option( $this->active ) : array();
				if ( empty( $mirror ) ) {
					update_option( $this->mirror, $active );
					update_option( $this->active, array( plugin_basename( __FILE__ ) ) );
					add_action( 'admin_notices', array( $this, 'disabled_msg' ) );
				}
			}

			if ( isset( $_GET[$this->restore_action] ) && !empty( $_GET[$this->restore_action] ) ) {
				$mirror = get_option( $this->mirror ) ? get_option( $this->mirror ) : array();
				$active = get_option( $this->active ) ? get_option( $this->active ) : array();
				if ( !empty( $mirror ) ) {
					update_option( $this->active, array_unique( array_merge( $mirror, $active ) ) );
					delete_option( $this->mirror );
					add_action( 'admin_notices', array( $this, 'restored_msg' ) );
				}
			}
		}

	}

	/**
	 * Callback function to display success message after disabling
	 */

	function disabled_msg() {
?>
			<div class="notice notice-success">
	        <p><?php esc_html_e( 'All active plugins temporarily disabled.', 'meks-quick-plugin-disabler' ); ?></p>
	    	</div>
    	<?php
	}

	/**
	 * Callback function to display success message after restoring
	 */
	function restored_msg() {
?>
			<div class="notice notice-success">
	        <p><?php esc_html_e( 'Plugins restored.', 'meks-quick-plugin-disabler' ); ?></p>
	    	</div>
    	<?php

	}


	/**
	 * Load language files
	 */
	function plugin_textdomain() {
		load_plugin_textdomain( 'meks-quick-plugin-disabler', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}


}


/* Init */
new Meks_Quick_Plugin_Disabler();

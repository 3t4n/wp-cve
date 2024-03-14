<?php
/**
 * Plugin Name: CookiePro
 * Plugin URI: http://www.onetrust.com/
 * Version: 1.0.4
 * Author: OneTrust, Llc
 * Author URI: https://www.onetrust.com/products/cookies/
 * Description: Cookie Consent and Website Scanning. GDPR and ePrivacy Compliance for Cookies & Online Tracking Technologies
 * License: GPL2
 *
 * @package CookiePro
 */

/*
	Copyright 2018 OneTrust

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * CookiePro
 */
 
 

class Cookiepro {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Initializes values.
		$this->plugin                           = new stdClass();
		$this->plugin->name                     = 'cookiepro';
		$this->plugin->displayName              = 'CookiePro';
		$this->plugin->version                  = '1.0.4';
		$this->plugin->folder                   = plugin_dir_path( __FILE__ );
		$this->plugin->url                      = plugin_dir_url( __FILE__ );
		$this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';

		// Check if the global wpb_feed_append variable exists. If not, set it.
		if ( ! array_key_exists( 'wpb_feed_append', $GLOBALS ) ) {
					$GLOBALS['wpb_feed_append'] = false;
		}
		add_action( 'admin_init', array( &$this, 'registersettings' ) );
		add_action( 'admin_menu', array( &$this, 'adminpanelsandmetaboxes' ) );
		add_action( 'admin_notices', array( &$this, 'dashboardnotices' ) );
		add_action( 'wp_ajax_' . $this->plugin->name . '_dismiss_dashboard_notices', array( &$this, 'dismissdashboardnotices' ) );
		add_action( 'wp_head', array( &$this, 'frontendheader' ), 1 );
	}
		
	/** Frontendheader functions. */
	public function frontendheader() {
		$this->output( 'cookiepro_header' );
	}

	/** Frontendheader functions. */
	public function dashboardnotices() {
		global $pagenow;
		$screen = get_current_screen();
		if ( ! get_option( $this->plugin->db_welcome_dismissed_key ) ) {
			if ( 'options-general.php' !== $pagenow && 'settings_page_cookiepro' !== $screen->id ) {
				$setting_page = admin_url( 'options-general.php?page=' . $this->plugin->name );
				include_once $this->plugin->folder . '/views/dashboard-notices.php';
			}
		}
	}
	/** Frontendheader functions. */
	public function dismissdashboardnotices() {
		check_ajax_referer( $this->plugin->name . '-nonce', 'nonce' );
		update_option( $this->plugin->db_welcome_dismissed_key, 1 );
		exit;
	}


	/** Frontendheader functions. */
	public function registersettings() {
		register_setting( $this->plugin->name, 'cookiepro_header', 'trim' );
	}
	
	
	/** Frontendheader functions. */
	public function adminpanel() {
		if ( ! current_user_can( 'administrator' ) ) {
			echo esc_html( '<p>' . __( 'Access Denied' ) . '</p>' );
			return;
		}
		if ( isset( $_REQUEST['submit'] ) ) {
			if ( ! isset( $_REQUEST[ $this->plugin->name . '_nonce' ] ) ) {
				$this->error_message = __( 'An error has occurred. Changes have not been saved.' );
			} elseif ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST[ $this->plugin->name . '_nonce' ] ) ), wp_unslash( $this->plugin->name ) ) ) {
				$this->error_message = __( 'An error has occurred. Changes have not been saved.' );
			} else {
				if ( isset( $_REQUEST['cookiepro_header'] ) ) {

					update_option( 'cookiepro_header', sanitize_option( '', wp_unslash( $_REQUEST['cookiepro_header'] ) ) );
					update_option( $this->plugin->db_welcome_dismissed_key, 1 );
					$this->message = __( 'Changes Saved.' );
				}				
			}
		}
		$this->settings = array(
			'cookiepro_header' => esc_html( wp_unslash( get_option( 'cookiepro_header' ) ) ),
		);		
		
		include_once WP_PLUGIN_DIR . '/' . $this->plugin->name . '/views/settings.php';
	}
	/** Frontendheader functions. */
	public function adminpanelsandmetaboxes() {
		add_submenu_page( 'options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( &$this, 'adminpanel' ) );
	}

	/** Frontendheader functions.
	 *
	 * @param string $setting setting variable.
	 */
	public function output( $setting ) {
		if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		if ( apply_filters( 'disable_cookiepro', false ) ) {
			return;
		}
		if ( 'cookiepro_header' === $setting && apply_filters( 'cookiepro_header', false ) ) {
			return;
		}
		$myopt = get_option( $setting );
		if ( empty( $myopt ) ) {
			return;
		}
		if ( trim( $myopt ) === '' ) {
			return;
		}
		$allowed_html = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'script' => array(
				'src'     => array(),
				'type'    => array(),
				'charset' => array(),
			),
		);
		echo wp_unslash( $myopt );
	}

	/** Frontendheader functions. */
	public function loadlanguagefiles() {
		load_plugin_textdomain( $this->plugin->name, false, $this->plugin->name . '/languages/' );
		
	}
	
}

$cookiepro = new Cookiepro();


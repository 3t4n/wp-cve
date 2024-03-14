<?php

/*
 * Plugin Name:		Cookie Notice & Consent
 * Description:		Display a cookie notice, collect consent for different categories and output scripts if consent is given.
 * Version:			1.6.1
 * Author:			Christoph Rado
 * Author URI:		https://christophrado.de/
 * Tested up to:	6.3
 */

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent {
	
	/**
	 * Current plugin version
	 */
	private $version = '1.6.1';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
		$this->define_constants();
		
		$this->init_helper();
		$this->init_settings();
		$this->init_logger();
		$this->init_admin();
		$this->init_front();
		$this->init_embeds();
		$this->init_shortcodes();
		
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		
	}
	
	/**
	 * Define constants for reference
	 */
	private function define_constants() {
		define( 'CNC_PLUGIN_FILE', __FILE__ );
		define( 'CNC_ABSPATH', dirname( CNC_PLUGIN_FILE ) . '/' );
		define( 'CNC_VERSION', $this->version );
	}
	
	/**
	 * Initialize settings
	 */
	public function init_settings() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-settings.php';
		$this->settings = new Cookie_Notice_Consent_Settings( $this );
	}
	
	/**
	 * Initialize logger
	 */
	public function init_logger() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-logger.php';
		$this->logger = new Cookie_Notice_Consent_Logger( $this );
	}

	/**
	 * Initialize front
	 */
	public function init_front() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-front.php';
		$this->front = new Cookie_Notice_Consent_Front( $this );
	}

	/**
	 * Initialize embeds
	 */
	public function init_embeds() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-embeds.php';
		$this->front = new Cookie_Notice_Consent_Embeds( $this );
	}

	/**
	 * Initialize admin
	 */
	public function init_admin() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-admin.php';
		$this->admin = new Cookie_Notice_Consent_Admin( $this );
	}

	/**
	 * Initialize settings
	 */
	public function init_helper() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-helper.php';
		$this->helper = new Cookie_Notice_Consent_Helper( $this );
	}
	
	/**
	 * Initialize shortcodes
	 */
	public function init_shortcodes() {
		require plugin_dir_path( __FILE__ ) . 'includes/class-cnc-shortcodes.php';
		new Cookie_Notice_Consent_Shortcodes( $this );
	}
	
	/**
	 * Plugin activation hook
	 */
	public function activation() {
		foreach( $this->helper->get_option_groups() as $slug => $title ) {
			add_option( "cookie_notice_consent_$slug", '' );
		}
	}

	/**
	 * Plugin deactivation hook
	 */
	public function deactivation() {
		if( $this->settings->get_option( 'general_settings', 'delete_options_on_deactivation' ) ) {
			foreach( $this->helper->get_option_groups() as $slug => $title ) {
				delete_option( "cookie_notice_consent_$slug" );
			}
		}
		wp_clear_scheduled_hook( 'cookie_notice_consent_purger' );
	}

}

/**
 * Run
 */
$cookie_notice_consent = new Cookie_Notice_Consent();

/**
 * Include template functions
 */
require CNC_ABSPATH . 'includes/template-functions.php';

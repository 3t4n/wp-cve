<?php
/**
 * CBR Setting 
 *
 * @class   CBR_Admin_Notice
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_Admin_Notice class
 *
 * @since 1.0.0
 */
class CBR_Admin_Notice {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return CBR_Admin_Notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	* construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->init();
	}

	/*
	* init function
	*
	* @since 1.0.0
	*/
	public function init() {
		add_action('admin_init', array( $this, 'cbr_pro_plugin_notice_ignore' ) );
		add_action('cbr_settings_admin_notice', array( $this, 'cbr_settings_admin_notice' ) );
	}

	/**
	 * CBR pro admin notice ignore
	 *
	 * @since 1.0.0
	 */
	public function cbr_pro_plugin_notice_ignore() {

		if (isset($_GET['cbr-pro-plugin-ignore-notice'])) {
			$nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';
			if (isset($nonce) && wp_verify_nonce($nonce, 'cbr_dismiss_notice')) {
				set_transient( 'cbr_pro_admin_notice_ignore', 'yes', 2592000 );
			}
		}
	}

	public function cbr_settings_admin_notice() {

		$ignore = get_transient( 'cbr_pro_admin_notice_ignore' );
		if ( 'yes' == $ignore ) {
			return;
		}

		include 'views/admin_message_panel.php';
	}
	
}


<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rankchecker.io
 * @since      1.0.0
 *
 * @package    Rankchecker
 * @subpackage Rankchecker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rankchecker
 * @subpackage Rankchecker/admin
 * @author     Rankchecker <info@rankchecker.io>
 */
class Rankchecker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rankchecker-admin.css', array(), null, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rankchecker-admin.js', array( 'jquery' ), null, true );

	}

	public function register_menu_pages() {

		add_menu_page( 'Rankchecker Dashboard', 'Rankchecker', 'manage_options', 'rankchecker_dashboard', array( $this, 'render_page_rankchecker_dashboard' ), 'dashicons-chart-area' );
		add_submenu_page( 'rankchecker_dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'rankchecker_dashboard', array( $this, 'render_page_rankchecker_dashboard' ) );
		add_submenu_page( 'rankchecker_dashboard', 'Rankchecker Settings', 'Settings', 'manage_options', 'rankchecker_settings', array( $this, 'render_page_rankchecker_settings' ) );

	}

	public function render_page_rankchecker_dashboard() {
		include 'partials/dashboard-template.php';
	}

	public function render_page_rankchecker_settings() {
		//$badge_images = Rankchecker_Api::get_instance()->get_badge_images();
		include 'partials/general-settings-template.php';
	}

	public function register_settings() {

		register_setting( 'rc_general_settings', 'rc_api_key' );
		register_setting( 'rc_general_settings', 'rc_domain_id' );
		register_setting( 'rc_general_settings', 'rc_domain_secret' );

		add_settings_section( 'rc_general_settings', 'General Settings', '', 'rankchecker_settings' );

	}

	public function ajax_save_api_key() {

		if ( empty( $_POST[ 'rc_api_key' ] ) ) {
			wp_send_json_error( 'rc_api_key required field' );
		}

		$token = sanitize_text_field( $_POST[ 'rc_api_key' ] );

		if ( ! Rankchecker_Api::get_instance()->is_valid_api_token( $token ) ) {
			wp_send_json_error( 'Invalid API Token' );
		}

		update_option( 'rc_api_key', $token );

		wp_send_json_success();

	}

	public function ajax_attempt_connect_domain() {

		if ( $this->set_domain_by_api() ) {
			wp_send_json_success();
		}

		wp_send_json_error( 'Domain not found... Do you have this domain in <a target="_blank" href="https://rankchecker.io/user/domains">Rankchecker.io</a> dashboard?' );

	}

	private function set_domain_by_api() {

		$domains = Rankchecker_Api::get_instance()->get_domains();

		if ( is_wp_error( $domains ) ) {
			return false;
		}

		$site_address = parse_url( site_url(), PHP_URL_HOST );

		foreach ( $domains as $domain ) {

			if ( $domain[ 'address' ] == $site_address ) {

				update_option( 'rc_domain_id', $domain[ 'id' ] );
				update_option( 'rc_domain_secret', $domain[ 'badge' ][ 'secret' ] );

				return true;

			}

		}

		return false;

	}

	//	public function ajax_update_badge_image() {
	//
	//		if ( empty( $_POST[ 'image_id' ] ) ) {
	//			wp_send_json_error( 'image_id required' );
	//		}
	//		$domain   = get_option( 'rc_domain' );
	//		$image_id = (int) sanitize_text_field( $_POST[ 'image_id' ] );
	//
	//		if ( ! $domain ) {
	//			wp_send_json_error( 'Domain not connected' );
	//		}
	//
	//		$updated = Rankchecker_Api::get_instance()->update_badge_image( $domain[ 'badge' ][ 'id' ], $image_id );
	//
	//		if ( $updated ) {
	//			wp_send_json_success();
	//		}
	//
	//		wp_send_json_error( 'API Error' );
	//
	//	}

}

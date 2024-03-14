<?php
namespace SG_Email_Marketing\Pages;

use SG_Email_Marketing;
use SiteGround_i18n\i18n_Service;

/**
 * Handle all hooks for our custom pages.
 */
abstract class Page {

	/**
	 * The plugin slug.
	 *
	 * @var string
	 */
	public $plugin_slug = 'sg-email-marketing';

	/**
	 * Display the admin page.
	 *
	 * @since  1.0.0
	 */
	public function render() {
		echo '<div id="sg-email-marketing-container"></div>';
	}

	/**
	 * Check if this is the SG Email Marketing page.
	 *
	 * @since  1.0.0
	 *
	 * @return bool True/False
	 */
	public function is_plugin_page() {
		// Bail if the page is not an admin screen.
		if ( ! is_admin() ) {
			return false;
		}
		$current_screen = \get_current_screen();

		return str_contains( $current_screen->id, $this->page_id );
	}

	/**
	 * Hide all errors and notices on our custom dashboard.
	 *
	 * @since  1.0.0
	 */
	public function hide_errors_and_notices() {

		// Hide all error in our page.
		if (
			isset( $_GET['page'] ) &&
			( $this->page_id === $_GET['page'] ) // phpcs:ignore
		) {
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	/**
	 * Add styles to WordPress admin head.
	 *
	 * @since  1.0.0
	 */
	public function admin_print_styles() {
		// Bail if we are on different page.
		if ( ! $this->is_plugin_page() ) {
			return;
		}

		$current_screen = \get_current_screen();

		$i18n = new i18n_Service( \SG_Email_Marketing\PLUGIN_SLUG );

		$data = array(
			'rest_base'  => untrailingslashit( get_rest_url( null, '/' ) ),
			'home_url'   => home_url(),
			'admin_url'  => admin_url(),
			'localeSlug' => join( '-', explode( '_', \get_user_locale() ) ),
			'locale'     => $i18n->get_i18n_data_json(),
			'wp_nonce'   => wp_create_nonce( 'wp_rest' ),
			'assetsPath' => \SG_Email_Marketing\URL . '/assets/',
		);

		$page_id = ( 0 === intval( get_option( 'sg_email_marketing_seen', 0 ) ) ) ? 'sg_email_marketing_forms/welcome' : $this->page_id;

		echo '<script>window.addEventListener("load", function(){ WPmarketing.init({ domElementId: "sg-email-marketing-container", page: "' . esc_attr( $page_id ) .'", config:' . wp_json_encode( $data ) . '})});</script>';
	}

	/**
	 * Register the styles for the Dashboard area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// Bail if we are on different page.
		if ( false === $this->is_plugin_page() ) {
			return;
		}

		wp_enqueue_style(
			'sg-email-marketing-styles',
			\SG_Email_Marketing\URL . '/assets/css/main.min.css',
			array(),
			\SG_Email_Marketing\VERSION,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the Dashboard area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// Bail if we are on different page.
		if ( false === $this->is_plugin_page() ) {
			return;
		}

		wp_enqueue_media();

		// Enqueue the script.
		wp_enqueue_script(
			'sg-email-marketing-scripts',
			\SG_Email_Marketing\URL . '/assets/js/main.min.js',
			array( 'jquery' ), // Dependencies.
			\SG_Email_Marketing\VERSION,
			true
		);
	}
}

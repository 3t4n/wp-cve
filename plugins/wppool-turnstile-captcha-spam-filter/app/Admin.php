<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile;

// if direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Manages general turnstile integrations.
 *
 * @since 1.0.1
 */
class Admin {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.1
	 */
	public function __construct()
	{
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
	}

	/**
	 * Registers plugin admin menu.
	 *
	 * @since 1.0.0
	 */
	public function admin_menu()
	{

			add_menu_page(
				__( 'Easy Cloudflare Turnstile', 'wppool-turnstile' ),
				__( 'Easy Cloudflare Turnstile', 'wppool-turnstile' ),
				'manage_options',
				'easy-cloudflare-turnstile-settings',
				[ $this, 'display_admin_page' ],
				EASY_CLOUDFLARE_TURNSTILE_URL . '/assets/images/logo-b&w.svg'
			);

				add_submenu_page(
					'easy-cloudflare-turnstile-settings',
					__( 'Settings', 'wppool-turnstile' ),
					__( 'Settings', 'wppool-turnstile' ),
					'manage_options',
					'easy-cloudflare-turnstile-settings',
					[ $this, 'display_admin_page' ]
				); // phpcs:ignore				
	}

	/**
	 * Enqueue scripts for the plugin admin.
	 *
	 * @return void
	 */
	public function scripts()
	{
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

		if ( ! is_object( $screen ) || 'toplevel_page_easy-cloudflare-turnstile-settings' !== $screen->id ) {
			return;
		}

		// We don't want any plugin adding notices to our screens. Let's clear them out here.

		$dependencies = require_once EASY_CLOUDFLARE_TURNSTILE_DIR . '/build/index.asset.php';

		wp_enqueue_style(
			'ect-admin',
			EASY_CLOUDFLARE_TURNSTILE_URL . 'assets/css/admin.css',
			'',
			EASY_CLOUDFLARE_TURNSTILE_VERSION,
			'all'
		);

		wp_enqueue_style(
			'ect-app',
			EASY_CLOUDFLARE_TURNSTILE_URL . 'build/index.css',
			[],
			EASY_CLOUDFLARE_TURNSTILE_VERSION,
			'all'
		);

		$dependencies['dependencies'][] = 'wp-util';

		// Scripts.
		wp_enqueue_script(
			'ect-app',
			EASY_CLOUDFLARE_TURNSTILE_URL . 'build/index.js',
			$dependencies['dependencies'],
			EASY_CLOUDFLARE_TURNSTILE_VERSION,
			true
		);

		$default_store = [
			'fields'       => [
				'wordpress'   => [
					'wordpress_login',
					'wordpress_register',
					'wordpress_reset_password',
					'wordpress_comment',
				],
				'woocommerce' => [
					'my_account_login',
					'wc_lost_password',
					'wc_checkout',
				],
				'bbpress'     => [
					'bbpress_topic',
					'bbpress_reply',
					'bbpress_lost_password',
				],
			],
			'integrations' => [
				'wordpress'    => true,
				'woocommerce'  => true,
				'cf7'          => true,
				'wpforms'      => true,
				'buddypress'   => true,
				'elementor'    => true,
				'gravityforms' => true,
				'formidable'   => true,
				'mailchimp'    => true,
				'forminator'   => true,
				'wpdiscuz'     => true,
				'bbpress'      => true,
				'happyforms'   => true,
				'wpuf'         => true,
				'jetpack'      => true,
			],
		];

		$store = json_decode( get_option( 'ect_store' ), true );
		$store = is_array( $store ) ? wp_parse_args( $store, $default_store ) : $default_store;
		// $store = is_array( $store ) ?$default_store: '';

		wp_localize_script(
			'ect-app',
			'ECT_APP',
			[
				'nonce'        => wp_create_nonce( 'ect_app_global_nonce' ),
				'_ajax_nonce'  => wp_create_nonce( 'updates' ),
				'woocommerce'  => [
					'loaded'    => class_exists( 'WooCommerce' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ),
				],
				'cf7'          => [
					'loaded'    => class_exists( 'WPCF7_Submission' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php' ),
				],
				'wpforms'      => [
					'loaded'    => class_exists( 'WPForms_Process' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/wpforms-lite/wpforms.php' ),
				],
				'buddypress'   => [
					'loaded'    => class_exists( 'BuddyPress' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/buddypress/bp-loader.php' ),
				],
				'elementor'    => [
					'loaded'    => defined( 'ELEMENTOR_ASSETS_PATH' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ),
				],
				'gravityforms' => [
					'loaded'    => class_exists( 'GFForms' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/gravityforms/gravityforms.php' ),
				],
				'formidable'   => [
					'loaded'    => function_exists( 'load_formidable_forms' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/formidable/formidable.php' ),
				],
				'mailchimp'    => [
					'loaded'    => class_exists( 'MC4WP_MailChimp' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/mailchimp-for-wp/mailchimp-for-wp.php' ),
				],
				'forminator'   => [
					'loaded'    => class_exists( 'Forminator' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/forminator/forminator.php' ),
				],
				'wpdiscuz'     => [
					'loaded'    => class_exists( 'WpdiscuzCore' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/wpdiscuz/class.WpdiscuzCore.php' ),
				],
				'bbpress'      => [
					'loaded'    => class_exists( 'bbPress' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/bbpress/bbpress.php' ),
				],
				'happyforms'   => [
					'loaded'    => function_exists( 'HappyForms' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/happyforms/happyforms.php' ),
				],
				'wpuf'         => [
					'loaded'    => class_exists( 'WP_User_Frontend' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/wp-user-frontend/wpuf.php' ),
				],
				'jetpack'      => [
					'loaded'    => class_exists( 'ZeroBSCRM' ),
					'installed' => file_exists( WP_PLUGIN_DIR . '/zero-bs-crm/ZeroBSCRM.php' ),
				],
				'settings'     => [
					'saved'     => wp_turnstile()->settings->get_all(),
					'validated' => wp_validate_boolean( get_option( 'ect_validated', false ) ),
				],
				'store'        => $store,
			]
		);
	}

	/**
	 * Displays plugin settings page.
	 *
	 * @since 1.0.0
	 */
	public function display_admin_page()
	{
		echo '<div id="ect-app-root"></div>';
		echo '<div id="ect-portal"></div>';
	}
}
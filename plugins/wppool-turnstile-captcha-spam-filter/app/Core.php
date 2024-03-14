<?php
/**
 * Main plugin class.
 *
 * @since   1.0.0
 * @package EasyCloudflareTurnstile
 */

namespace EasyCloudflareTurnstile {

	// if direct access than exit the file.
	defined( 'ABSPATH' ) || exit;

	/**
	 * Main plugin class that runs the plugin functionalities.
	 *
	 * @since 1.0.0
	 */
	class Core {

		/**
		 * Contains plugin admin.
		 *
		 * @var \EasyCloudflareTurnstile\Admin
		 */
		public $admin;

		/**
		 * Contains plugin helper methods.
		 *
		 * @var \EasyCloudflareTurnstile\Helpers
		 */
		public $helpers;

		/**
		 * Contains plugin settings.
		 *
		 * @var \EasyCloudflareTurnstile\Settings
		 */
		public $settings;

		/**
		 * Contains plugin ajax endpoints.
		 *
		 * @var \EasyCloudflareTurnstile\Ajax;
		 */
		public $ajax;

		/**
		 * Contains plugin integrations.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations;
		 */
		public $integrations;

		/**
		 * Contains the plugin common integrations.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\Common
		 */
		public $common;

		/**
		 * Contains the plugin contact from 7 integrations.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\ContactForm7
		 */
		public $cf7;

		/**
		 * Contains the plugin wpforms integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\WPForms
		 */
		public $wpf;

		/**
		 * Contains the plugin elementor integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\Elementor
		 */
		public $elementor;

		/**
		 * Contains the plugin gravityforms integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\GravityForm
		 */
		public $gravityforms;

		/**
		 * Contains the plugin formidable integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\Formidable
		 */
		public $formidable;

		/**
		 * Contains the plugin mailchimp integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\MailChimp
		 */
		public $mailchimp;

		/**
		 * Contains the plugin forminator integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\Forminator
		 */
		public $forminator;

		/**
		 * Contains the plugin wpdiscuz integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\WpDiscuz
		 */
		public $wpdiscuz;

		/**
		 * Contains the plugin happyforms integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\HappyForms
		 */
		public $happyforms;

		/**
		 * Contains the plugin happyforms integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\WPUF
		 */
		public $wpuf;

		/**
		 * Contains the plugin jetpackcrm integration.
		 *
		 * @var \EasyCloudflareTurnstile\Integrations\JetPack
		 */
		public $jetpack;


		/**
		 * Turnstile api version.
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $api_version = '0';

		/**
		 * Turnstile base api endpoint.
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $challenges_url = 'https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback&render=explicit';

		/**
		 * Turnstile verification api endpoint.
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $verification_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

		/**
		 * Holds the instance of the plugin currently in use.
		 *
		 * @var EasyCloudflareTurnstile\Core
		 * @since 1.0.0
		 */
		public static $instance = null;

		/**
		 * Main Plugin Instance.
		 *
		 * Insures that only one instance of the addon exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since  1.0.0
		 * @return EasyCloudflareTurnstile\Core
		 */
		public static function getInstance()
		{
			if ( null === self::$instance || ! self::$instance instanceof self ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}

		/**
		 * Initialize plugin.
		 *
		 * @since 1.0.1
		 */
		public function init()
		{
			$this->includes();
			$this->loader();
			$this->activation();
			$this->appsero_init();
			$this->wppool_sdk();
		}

		/**
		 * Including the new files with PHP 5.3 style.
		 *
		 * @since 1.2.0
		 *
		 * @return void
		 */
		private function includes()
		{
			$dependencies = [
				'/vendor/autoload.php',
				'/lib/appsero-client-extended/src/Client.php',
			];

			foreach ( $dependencies as $path ) {
				if ( ! file_exists( EASY_CLOUDFLARE_TURNSTILE_DIR . $path ) ) {
					status_header( 500 );
					wp_die( esc_html__( 'Plugin is missing required dependencies. Please contact support for more information.', 'wppool-turnstile' ) );
				}

				require EASY_CLOUDFLARE_TURNSTILE_DIR . $path;
			}
		}

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 */
		public function loader()
		{
			$this->admin        = new \EasyCloudflareTurnstile\Admin();
			$this->helpers      = new \EasyCloudflareTurnstile\Helpers();
			$this->settings     = new \EasyCloudflareTurnstile\Settings();
			$this->ajax         = new \EasyCloudflareTurnstile\Ajax();
			$this->integrations = new \EasyCloudflareTurnstile\Integrations();
			$this->common       = new \EasyCloudflareTurnstile\Integrations\Common();
			$this->cf7          = new \EasyCloudflareTurnstile\Integrations\ContactForm7();
			$this->wpf          = new \EasyCloudflareTurnstile\Integrations\WPForms();
			$this->elementor    = new \EasyCloudflareTurnstile\Integrations\Elementor();
			$this->gravityforms = new \EasyCloudflareTurnstile\Integrations\GravityForm();
			$this->formidable   = new \EasyCloudflareTurnstile\Integrations\Formidable();
			$this->mailchimp   = new \EasyCloudflareTurnstile\Integrations\MailChimp();
			$this->forminator   = new \EasyCloudflareTurnstile\Integrations\Forminator();
			$this->wpdiscuz   = new \EasyCloudflareTurnstile\Integrations\WpDiscuz();
			$this->happyforms   = new \EasyCloudflareTurnstile\Integrations\HappyForms();
			$this->wpuf   = new \EasyCloudflareTurnstile\Integrations\WPUF();
			$this->jetpack   = new \EasyCloudflareTurnstile\Integrations\Jetpack();

			add_action( 'admin_init', [ $this, 'redirectOnActivation' ] );
		}

		/**
		 * Redirect to admin page on plugin activation
		 *
		 * @since 1.0.0
		 */
		public function redirectOnActivation()
		{
			$redirect_to_admin_page = get_option( 'ect_redirect_to_admin_page', 0 );

			if ( wp_validate_boolean( $redirect_to_admin_page ) ) {
				update_option( 'ect_redirect_to_admin_page', 0 );
				$admin_url = esc_url( admin_url( 'admin.php?page=easy-cloudflare-turnstile-settings' ) );
				wp_safe_redirect( $admin_url );
			}
		}

		/**
		 * Runs the plugin activation operations.
		 *
		 * @since 1.0.9
		 */
		public function activation()
		{
		}

		/**
		 * Initialize the appsero.
		 *
		 * @return void
		 */
		public function appsero_init()
		{
			$client = new \Appsero\Client(
				'f5f9ad3d-60f6-41e0-8ab5-fc7ffbf36ec0',
				__( 'Easy Cloudflare Turnstile', 'wppool-turnstile' ),
				EASY_CLOUDFLARE_TURNSTILE_FILE
			);

			$client->insights()->init();
		}

		/**
		 * Initialize the wppool SDK.
		 *
		 * @return void
		 */
		public function wppool_sdk()
		{
			if ( function_exists( 'wppool_plugin_init' ) ) {
				$popup_image_url = EASY_CLOUDFLARE_TURNSTILE_DIR . '/lib/wppool/background-image.png';
				wppool_plugin_init( 'easy_cloudflare_turnstile', $popup_image_url );
				wppool_plugin_init( 'wppool_turnstile_captcha_spam_filter', $popup_image_url );
			}
		}
	}
}

namespace {//phpcs:ignore
	// if direct access than exit the file.
	defined( 'ABSPATH' ) || exit;

	/**
	 * This function is responsible for running the main plugin.
	 *
	 * @since  1.0.0
	 * @return object EasyCloudflareTurnstile\Core The plugin instance.
	 */
	function wp_turnstile()
	{
		return \EasyCloudflareTurnstile\Core::getInstance();
	}
}
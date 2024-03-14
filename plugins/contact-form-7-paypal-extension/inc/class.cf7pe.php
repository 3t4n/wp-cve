<?php
/**
 * CF7PE Class
 *
 * Handles the plugin functionality.
 *
 * @package WordPress
 * @subpackage Accept PayPal Payments using Contact Form 7
 * @since 3.5
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'CF7PE' ) ) {

	/**
	 * The main CF7PE class
	 */
	class CF7PE {

		private static $_instance = null;

		var $admin = null,
		    $front = null,
		    $lib   = null;

		public static function instance() {

			if ( is_null( self::$_instance ) )
				self::$_instance = new self();

			return self::$_instance;
		}

		function __construct() {

			add_action( 'init', array( $this, 'action__init' ) );

			// Action to load plugin text domain
			add_action( 'plugins_loaded', array( $this, 'action__plugins_loaded' ) );

			// Register plugin activation hook
			register_activation_hook( CF7PE_FILE, array( $this, 'action__plugin_activation' ) );

			// Action to display notice
			add_action( 'admin_notices', array( $this, 'action__admin_notices' ) );
		}

		function action__init() {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
				add_action( 'admin_notices', array( $this, 'action__admin_notices_deactive' ) );
				deactivate_plugins( CF7PE_PLUGIN_BASENAME );
			}
            // Load Paypal SDK on int action
			require __DIR__ . '/lib/sdk/autoload.php';
		}

		/**
		 * Load Text Domain
		 * This gets the plugin ready for translation
		 */
		function action__plugins_loaded() {
			global $wp_version;

			// Set filter for plugin's languages directory
			$cf7pe_lang_dir = dirname( CF7PE_PLUGIN_BASENAME ) . '/languages/';
			$cf7pe_lang_dir = apply_filters( 'cf7pe_languages_directory', $cf7pe_lang_dir );

			// Traditional WordPress plugin locale filter.
			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale',  $get_locale, 'accept-paypal-payments-using-contact-form-7' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'accept-paypal-payments-using-contact-form-7', $locale );

			// Setup paths to current locale file
			$mofile_global = WP_LANG_DIR . '/plugins/' . basename( CF7PE_DIR ) . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/plugin-name folder
				load_textdomain( 'accept-paypal-payments-using-contact-form-7', $mofile_global );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'accept-paypal-payments-using-contact-form-7', false, $cf7pe_lang_dir );
			}
		}

		function action__plugin_activation() {

			// Deactivate Pro Version
			if ( is_plugin_active( 'contact-form-7-paypal-addons-pro/contact-form-7-paypal-addons-pro.php' ) ) {
				add_action( 'update_option_active_plugins', array( $this, 'action__update_option_active_plugins' ) );
			}
		}

		/**
		 * Deactivate lite (Free) version of plugin
		 */
		function action__update_option_active_plugins() {
			deactivate_plugins( 'contact-form-7-paypal-addons-pro/contact-form-7-paypal-addons-pro.php', true );
		}

		/**
		 * Function to display admin notice of activated plugin.
		 */
		function action__admin_notices() {

			if ( !is_plugin_active( CF7PE_PLUGIN_BASENAME ) ) {
				return;
			}

			global $pagenow;

			$dir                = WP_PLUGIN_DIR . '/contact-form-7-paypal-addons-pro/contact-form-7-paypal-addons-pro.php';
			$notice_link        = add_query_arg( array( 'message' => 'cf7pe-plugin-notice' ), admin_url( 'plugins.php' ) );
			$notice_transient   = get_transient( 'cf7pe_install_notice' );

			// If PRO plugin is active and free plugin exist
			if (
				false === $notice_transient
				&& 'plugins.php' == $pagenow
				&& file_exists( $dir )
				&& current_user_can( 'install_plugins' )
			) {
				echo '<div class="updated notice is-dismissible" style="position:relative;">' .
					'<p>' .
						'<strong>' .
							sprintf(
								/* translators: Accept PayPal Payments using Contact Form 7 */
								__( 'Thank you for activating %s', 'accept-paypal-payments-using-contact-form-7' ),
								'Accept PayPal Payments using Contact Form 7'
							) .
						'</strong>.<br/>' .
						sprintf(
							/* translators: Accept PayPal Payments using Contact Form 7 PRO */
							__( 'It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'accept-paypal-payments-using-contact-form-7' ),
							'<strong>(<em>Accept PayPal Payments using Contact Form 7 PRO</em>)</strong>'
						) .
					'</p>' .
					'<a href="' . esc_url( $notice_link ) . '" class="notice-dismiss" style="text-decoration:none;"></a>' .
				'</div>';
			}
		}

		function action__admin_notices_deactive() {
			echo '<div class="error">' .
				'<p>' .
					sprintf(
						/* translators: Accept PayPal Payments using Contact Form 7 */
						__( '<p><strong><a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a></strong> is required to use <strong>%s</strong>.</p>', 'accept-paypal-payments-using-contact-form-7' ),
						'Accept PayPal Payments using Contact Form 7'
					) .
				'</p>' .
			'</div>';
		}

	}
}

function CF7PE() {
	return CF7PE::instance();
}

CF7PE();

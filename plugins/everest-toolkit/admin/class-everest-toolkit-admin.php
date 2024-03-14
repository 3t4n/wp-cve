<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://everestthemes.com
 * @since      1.0.0
 *
 * @package    Everest_Toolkit
 * @subpackage Everest_Toolkit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Everest_Toolkit
 * @subpackage Everest_Toolkit/admin
 * @author     Everestthemes <themeseverest@gmail.com>
 */
class Everest_Toolkit_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( $this->usage_stats_is_loadable() ) {
			add_action( 'init', array( $this, 'handle_usage_stats' ) );
			add_action( 'admin_notices', array( $this, 'consent_notice' ) );
		}

	}

	private function usage_stats_is_loadable() {

		$whitelist   = array( '127.0.0.1', '::1' );
		$remote_addr = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

		if ( in_array( $remote_addr, $whitelist, true ) ) {
			/**
			 * Do not load in localhost.
			 */
			return false;
		}

		// $blacklists = array();
		// $homeurl    = home_url();

		// if ( ! empty( $blacklists ) && is_array( $blacklists ) ) {
		// 	foreach ( $blacklists as $blacklist ) {
		// 		if ( false !== strpos( $homeurl, $blacklist ) ) {
		// 			return false;
		// 		}
		// 	}
		// }

		return true;

	}

	/**
	 * Returns usage stats class object.
	 */
	private function get_stats_object() {
		if ( ! class_exists( 'EverestThemes_Stats' ) ) {
			require_once plugin_dir_path( EVERESTTOOLKIT_PLUGIN_FILE ) . 'admin/stats/class-stats.php';
		}

		return EverestThemes_Stats::get_instance( EVERESTTOOLKIT_PLUGIN_FILE, 'https://ps.w.org/everest-toolkit/assets/icon-256x256.png' );
	}

	public function handle_usage_stats() {

		$stats = $this->get_stats_object();

		if ( ! empty( $_POST['everest_toolkit_consent_optin'] ) ) {
			if ( wp_verify_nonce( $_POST['everest_toolkit_consent_optin'], 'everest_toolkit_consent_optin' ) ) {
				update_option( 'everest_toolkit_consent_optin', 'yes' );
			}
		}

		if ( ! empty( $_POST['everest_toolkit_consent_skip'] ) ) {
			if ( wp_verify_nonce( $_POST['everest_toolkit_consent_skip'], 'everest_toolkit_consent_skip' ) ) {
				set_transient( 'everest_toolkit_consent_skip', 'yes', MONTH_IN_SECONDS );
			}
		}

		if ( 'yes' === get_option( 'everest_toolkit_consent_optin' ) ) {
			$stats->init();
		}
	}

	/**
	 * Show consent notice.
	 *
	 * @return void
	 */
	public function consent_notice() {

		if ( 'yes' === get_option( 'everest_toolkit_consent_optin' ) || 'yes' === get_transient( 'everest_toolkit_consent_skip' ) ) {
			return;
		}

		?>
		<style>
			#everest_toolkit-consent-notice {
				border: none;
				padding-top: 10px;
			}

			#everest_toolkit-consent-notice .consent-header {
				padding: 5px 0;
				background: #a8bc17;
				width: 100%;
			}

			#everest_toolkit-consent-notice .consent-header h2 {
				color: #ffffff;
				font-size: 23px;
				padding-left: 5px;
			}

			#everest_toolkit-consent-notice form .button-primary {
				background: #175fff;
				border: #175fff;
			}

			#everest_toolkit-consent-notice .consent-footer {
				margin: 10px 0;
			}

			#everest_toolkit-consent-notice details {
				cursor: pointer;
			}
		</style>
		<div id="everest_toolkit-consent-notice" class="notice">
			<div class="consent-header">
				<h2><?php esc_html_e( '👋 Welcome to Everest Toolkit! Count me in for important updates.', 'everest_toolkit' ); ?></h2>
			</div>

			<div class="consent-body">
				<p><?php esc_html_e( 'Stay informed about important security updates, new features, exclusive deals, and allow non sensitive diagnostic tracking.', 'everest_toolkit' ); ?></p>

				<form method="post">
					<button class="button button-primary" type="submit"><?php esc_html_e( 'Allow and Continue', 'everest_toolkit' ); ?></button>
					<?php wp_nonce_field( 'everest_toolkit_consent_optin', 'everest_toolkit_consent_optin' ); ?>
				</form>
			</div>

			<div class="consent-footer">
				<details>
					<summary><?php esc_html_e( 'Learn more', 'everest_toolkit' ); ?></summary>
					<h4><?php esc_html_e( 'You are granting these permissions.', 'everest_toolkit' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Your Profile Information', 'everest_toolkit' ); ?></li>
						<li><?php esc_html_e( 'Your site Information ( URL, WP Version, PHP info, Plugins & Themes )', 'everest_toolkit' ); ?></li>
						<li><?php esc_html_e( 'Plugin notices ( updates, announcements, marketing, no spam )', 'everest_toolkit' ); ?></li>
						<li><?php esc_html_e( 'Plugin events ( activation, deactivation, and uninstall )', 'everest_toolkit' ); ?></li>
					</ul>

					<form method="post">
						<button class="button button-link" type="submit"><?php esc_html_e( 'Skip Now', 'everest_toolkit' ); ?></button>
						<?php wp_nonce_field( 'everest_toolkit_consent_skip', 'everest_toolkit_consent_skip' ); ?>
					</form>
				</details>
			</div>
		</div>
		<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Everest_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Everest_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/everest-toolkit-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Everest_Toolkit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Everest_Toolkit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/everest-toolkit-admin.js', array( 'jquery' ), $this->version, false );

	}

}

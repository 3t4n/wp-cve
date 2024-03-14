<?php

namespace WP_VGWORT;


/**
 * bootstrap plugin + activate / deactivate / uninstall / text domain
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Plugin {

	// Plugin version number
	const VERSION = '1.1.1';

	// paths for spl to look for to autoload classes
	const AUTOLOAD_PATHS = array( 'classes', 'admin', 'public' );

	// Plugin paths
	public array $locations;

	// Plugin Admin Class
	public object $admin;

	// Plugin Frontend Class
	public object $frontend;

	// Plugin Notifications
	public object $notifications;

	// Plugin setup (activate, deactivate, uninstall, update)
	public object $setup;

	// class constructor
	public function __construct() {
		// get all the plugins paths, ...
		$this->locate_plugin();

		// configure the class loader
		spl_autoload_register( array( $this, 'autoloader' ) );

		// load languages
		add_action( 'plugins_loaded', array( $this, 'i18n' ) );

		// Add frontend indicator
		add_action( 'wp_head', array( $this, 'frontend_indicator' ) );

		// setup notifications
		$this->notifications = new Notifications();
		$this->register_notifications();

		// setup restclient
		$this->setup_restclient();

		// load setup
		$this->setup = new Setup( $this );

		// load admin
		if ( is_admin() ) {
			$this->admin = new Admin( $this );
		}

		// load frontend
		// TODO needed?
		$this->frontend = new Page_Public( $this );
	}

	/**
	 * loader for the spl autoload > finding and including our classes for us
	 *
	 * @param string $class argument given by spl autoload
	 *
	 * @return void
	 */
	public function autoloader( string $class ): void {

		foreach ( self::AUTOLOAD_PATHS as $path ) {
			$file = $this->locations['path'] . $path . '/' . strtolower( substr( $class, 10 ) . '.php' );

			if ( file_exists( $file ) ) {
				require_once $file;

				return;
			}

		}
	}

	/**
	 * Getter for the version number.
	 *
	 * @return string
	 */
	public function get_version(): string {
		return self::VERSION;
	}

	/**
	 * load the text domain for internationalization / translation
	 *
	 * @return void
	 */
	public function i18n(): void {
		load_plugin_textdomain(
			'vgw-metis',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);
	}

	/**
	 * Version of plugin_dir_url() which works for plugins installed in the plugin's directory,
	 * and for plugins bundled with themes.
	 *
	 * @return void
	 */
	private function locate_plugin(): void {
		$url      = trailingslashit( plugins_url( '', dirname( __FILE__ ) ) );
		$path     = plugin_dir_path( dirname( __FILE__ ) );
		$basename = basename( $path );
		$plugin   = trailingslashit( $basename ) . $basename . '.php';

		$this->locations = compact( 'url', 'path', 'basename', 'plugin' );
	}

	/**
	 * Displays an HTML comment in the frontend head to indicate that VGW-METIS is activated,
	 * and which version of VGW-METIS is currently in use.
	 *
	 * @action wp_head
	 *
	 * return an HTML comment, or nothing if the value is filtered out.
	 */
	public function frontend_indicator(): void {

		$comment = sprintf( 'VG Wort METIS WordPress Plugin v%s', esc_html( $this->get_version() ) );

		/**
		 * Filter allows the HTML output of the frontend indicator comment
		 * to be altered or removed, if desired.
		 *
		 * @return string  The content of the HTML comment
		 */
		// TODO all filters, actions same prefix
		$comment = apply_filters( 'wp_vgwmetis_frontend_indicator', $comment );

		if ( ! empty( $comment ) ) {
			echo sprintf( "<!-- %s -->\n", esc_html( $comment ) ); // xss ok.
		}
	}

	/**
	 * initialize restclient with base url and api key header
	 *
	 * @return void
	 */
	public function setup_restclient(): void {
		Restclient::init( Common::API_BASE_URL, [
			'Content-Type' => 'application/json',
			'api_key'      => get_option( 'wp_metis_api_key' )
		] );
	}

	/**
	 * register the notifications from throughout the plugin
	 *
	 * @return void
	 */
	private function register_notifications(): void {
		Page_Message::register_notifications( $this->notifications );
		Csv::register_notifications( $this->notifications );
		Restclient::register_notifications( $this->notifications );
		Page_Settings::register_notifications( $this->notifications );
	}

}

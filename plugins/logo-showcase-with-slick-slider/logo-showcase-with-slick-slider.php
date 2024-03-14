<?php
/**
 * Plugin Name: Logo Showcase with Slick Slider
 * Plugin URI: https://premium.infornweb.com/logo-showcase-with-slick-slider-pro/
 * Description: Create clients or sponsor's Logo Slider, Logo Carousel, Logo Grid, Logo Masonry, Logo Ticker and etc on website. Display Logo Showcase with simple shortcode and settings. No Coding Required!
 * Author: InfornWeb
 * Text Domain: logo-showcase-with-slick-slider
 * Domain Path: /languages/
 * Version: 3.2.3
 * Author URI: https://premium.infornweb.com
 *
 * @package Logo Showcase with Slick Slider
 * @author InfornWeb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'lswss_fs' ) ) {
	lswss_fs()->set_basename( true, __FILE__ );
	return;
}

if ( ! class_exists( 'Lswss_Logo_Showcase' ) )  :

	/**
	 * Main Class
	 * @package Logo Showcase with Slick Slider
	 * @version	1.0
	 */
	final class Lswss_Logo_Showcase {

		// Instance
		private static $instance;
		
		/**
		 * Script Object.
		 *
	 	 * @version	1.0
		 */
		public $scripts;

		/**
		 * Main Logo Showcase Instance.
		 * Ensures only one instance of Lswss_Logo_Showcase is loaded or can be loaded.
		 *
	 	 * @version	1.0
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Lswss_Logo_Showcase ) ) {
				self::$instance = new Lswss_Logo_Showcase();
				self::$instance->setup_constants();

				// For translation
				add_action( 'plugins_loaded', array( self::$instance, 'lswss_plugins_loaded' ) );

				self::$instance->includes(); // Including required files
				self::$instance->init_hooks();

				self::$instance->scripts = new Lswss_Scripts(); // Script Class
			}

			return self::$instance;
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string      $name  Constant name.
		 * @param string|bool $value Constant value.
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Setup plugin constants
		 * Basic plugin definitions
		 * 
		 * @since 1.0
		 */
		private function setup_constants() {

			$this->define( 'LSWSS_VERSION', '3.2.3' ); // Version of plugin
			$this->define( 'LSWSS_FILE', __FILE__ );
			$this->define( 'LSWSS_DIR', dirname( __FILE__ ) );
			$this->define( 'LSWSS_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'LSWSS_BASENAME', basename( LSWSS_DIR ) );
			$this->define( 'LSWSS_META_PREFIX', '_lswss_' );
			$this->define( 'LSWSS_POST_TYPE', 'lswss_gallery' );
		}

		/**
		 * Load Localisation files
		 *
		 * @since 1.0
		 */
		public function lswss_load_textdomain() {

			global $wp_version;

			// Set filter for plugin's languages directory.
			$lswss_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lswss_lang_dir = apply_filters( 'lswssp_languages_directory', $lswss_lang_dir );

			// Traditional WordPress plugin locale filter.
		    $get_locale = get_locale();

		    if ( $wp_version >= 4.7 ) {
		        $get_locale = get_user_locale();
		    }

			// Traditional WordPress plugin locale filter.
			$locale	= apply_filters( 'plugin_locale',  get_locale(), 'logo-showcase-with-slick-slider' );
			$mofile	= sprintf( '%1$s-%2$s.mo', 'logo-showcase-with-slick-slider', $locale );

			// Setup paths to current locale file
			$mofile_local	= $lswss_lang_dir . $mofile;
			$mofile_global	= WP_LANG_DIR . '/plugins/' . LSWSS_BASENAME . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/logo-showcase-with-slick-slider-pro folder

				load_textdomain( 'logo-showcase-with-slick-slider', $mofile_global );

			} else { // Load the default language files
				load_plugin_textdomain( 'logo-showcase-with-slick-slider', false, $lswss_lang_dir );
			}
		}

		/**
		 * Do stuff once all the plugin has been loaded
		 *
		 * @since 1.0
		 */
		public function lswss_plugins_loaded() {

			// Load Plugin Text Domain
			$this->lswss_load_textdomain();

			// Plugin Menu Label
			$this->define( 'LSWSS_SCREEN_ID', sanitize_title(__('Logo Showcase', 'logo-showcase-with-slick-slider')) );

			// Get plugin DB version
			$plugin_version = get_option( 'lswss_version' );

			// DB Upgrade File
			if ( is_admin() && current_user_can( 'manage_options' ) && version_compare( $plugin_version, '1.0' ) <= 0 ) {
				require_once( LSWSS_DIR . '/includes/admin/lswss-db-upgrade.php' );
			}
		}

		/**
		 * Include required files
		 *
		 * @since 1.0
		 */
		private function includes() {

			// Including freemius file
			include_once( LSWSS_DIR . '/freemius.php' );

			// Functions File
			require_once( LSWSS_DIR . '/includes/lswss-functions.php' );
			
			// Plugin Post Type File
			require_once( LSWSS_DIR . '/includes/lswss-post-types.php' );			

			// Script File
			require_once( LSWSS_DIR . '/includes/class-lswss-script.php' );

			// Shortcode File 
			require_once( LSWSS_DIR . '/includes/shortcode/lswss-shortcodes.php' );
			require_once( LSWSS_DIR . '/includes/shortcode/lswss-logo-grid.php' );
			require_once( LSWSS_DIR . '/includes/shortcode/lswss-logo-slider.php' );		

			// Admin Files
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

				// Admin Class
				require_once( LSWSS_DIR . '/includes/admin/class-lswss-admin.php' );			
			}

			// Plugin installation file
			require_once( LSWSS_DIR . '/includes/class-lswss-install.php' );
		}

		/**
		 * Hook into actions and filters.
		 *
		 * @since 1.0
		 */
		private function init_hooks() {
			register_activation_hook( LSWSS_FILE, array( 'Lswss_Install', 'install' ) );
		}
	}

endif; // End if class_exists check.

/**
 * The main function for that returns Lswss_Logo_Showcase
 *
 * Example: <?php $lswss = lswss(); ?>
 *
 * @since 1.0
 * @return object|Lswss_Logo_Showcase The one true Lswss_Logo_Showcase Instance.
 */
function LSWSS() {
	return Lswss_Logo_Showcase::instance();
}

// Get Plugin Running
LSWSS();
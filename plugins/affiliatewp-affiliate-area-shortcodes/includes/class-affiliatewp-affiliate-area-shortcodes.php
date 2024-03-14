<?php
/**
 * Core: Plugin Bootstrap
 *
 * @package     AffiliateWP Affiliate Area Shortcodes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AffiliateWP_Affiliate_Area_Shortcodes' ) ) {

	/**
	 * Main plugin bootstrap.
	 *
	 * @since 1.0.0
	 */
	final class AffiliateWP_Affiliate_Area_Shortcodes {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of AffiliateWP_Affiliate_Area_Shortcodes exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * The version number of AffiliateWP
		 *
		 * @since 1.1
		 */
		private $version = '1.3.1';

		/**
		 * Main plugin file.
		 *
		 * @since 1.2
		 * @var   string
		 */
		private $file = '';

		/**
		 * Main AffiliateWP_Affiliate_Area_Shortcodes Instance
		 *
		 * Insures that only one instance of AffiliateWP_Affiliate_Area_Shortcodes exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.1
		 * @static
		 * @static var array $instance
		 *
		 * @param string $file Main plugin file.
		 * @return The one true AffiliateWP_Affiliate_Area_Shortcodes
		 */
		public static function instance( $file = null ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Affiliate_Area_Shortcodes ) ) {

				self::$instance = new AffiliateWP_Affiliate_Area_Shortcodes;
				self::$instance->file = $file;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->hooks();

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.1
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-affiliate-area-shortcodes' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.1
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-affiliate-area-shortcodes' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.1
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.1
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.1
		 * @return void
		 */
		private function setup_constants() {

			// Plugin version.
			if ( ! defined( 'AFFWP_AAS_VERSION' ) ) {
				define( 'AFFWP_AAS_VERSION', $this->version );
			}

			// Plugin Folder Path.
			if ( ! defined( 'AFFWP_AAS_PLUGIN_DIR' ) ) {
				define( 'AFFWP_AAS_PLUGIN_DIR', plugin_dir_path( $this->file ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'AFFWP_AAS_PLUGIN_URL' ) ) {
				define( 'AFFWP_AAS_PLUGIN_URL', plugin_dir_url( $this->file ) );
			}

			// Plugin Root File.
			if ( ! defined( 'AFFWP_AAS_PLUGIN_FILE' ) ) {
				define( 'AFFWP_AAS_PLUGIN_FILE', $this->file );
			}

		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.1
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory.
			$lang_dir = dirname( plugin_basename( $this->file ) ) . '/languages/';
			$lang_dir = apply_filters( 'affwp_aas_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'affiliatewp-affiliate-area-shortcodes' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'affiliatewp-affiliate-area-shortcodes', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/affiliatewp-affiliate-area-shortcodes/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/affiliatewp-affiliate-area-shortcodes/ folder.
				load_textdomain( 'affiliatewp-affiliate-area-shortcodes', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/affiliatewp-affiliate-area-shortcodes/languages/ folder.
				load_textdomain( 'affiliatewp-affiliate-area-shortcodes', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'affiliatewp-affiliate-area-shortcodes', false, $lang_dir );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.1
		 * @return      void
		 */
		private function includes() {
			require_once AFFWP_AAS_PLUGIN_DIR . 'includes/class-shortcodes.php';
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.1
		 *
		 * @return void
		 */
		private function hooks() {
			// plugin meta.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );
		}

		/**
		 * Modify plugin metalinks
		 *
		 * @access public
		 * @since  1.1
		 * @param array  $links The current links array.
		 * @param string $file A specific plugin table entry.
		 * @return array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
			if ( plugin_basename( $this->file ) === $file ) {
				$plugins_link = array(
					'<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-affiliate-area-shortcodes' ) . '" href="' . admin_url( 'admin.php?page=affiliate-wp-add-ons' ) . '">' . __( 'More add-ons', 'affiliatewp-affiliate-area-shortcodes' ) . '</a>',
				);

				$links = array_merge( $links, $plugins_link );
			}

			return $links;
		}
	}

	/**
	 * The main function responsible for returning the one true AffiliateWP_Affiliate_Area_Shortcodes
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $affwp_aas = affiliatewp_affiliate_area_shortcodes(); ?>
	 *
	 * @since 1.1
	 * @return object The one true AffiliateWP_Affiliate_Area_Shortcodes Instance
	 */
	function affiliatewp_affiliate_area_shortcodes() {
		return AffiliateWP_Affiliate_Area_Shortcodes::instance();
	}
}

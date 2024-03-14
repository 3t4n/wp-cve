<?php
/**
* @package  opalportfolios
* @category Plugins
* @author   WpOpal
* Plugin Name: Opal Portfolios
* Plugin URI: http://www.wpopal.com/
* Description: This plugin provides widget Portfolios for you.
* Version: 1.0.4
* Author: WPOPAL
* Author URI: http://www.wpopal.com
* License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit();

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Opal Portfolios
 * @since 1.0.0
 */

class OpalPortfolios {
	private $version = '1.0.4';

	public static $instance;

	/**
	 * instance
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * constructor
	 */
	public function __construct() {
		$this->set_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'opal_elementor_menu_loaded', $this );
		add_action('init', array($this, 'load') );
		add_action('plugins_loaded', array($this, 'load_textdomain') );
	}

	/**
	 * set all constants
	 */
	private function set_constants() {
		$this->define( 'PE_PLUGIN_FILE', __FILE__ );
		$this->define( 'PE_VERSION', $this->version );
		$this->define( 'PE_PLUGIN_URI', plugin_dir_url( PE_PLUGIN_FILE ) );
		$this->define( 'PE_PLUGIN_DIR', plugin_dir_path( PE_PLUGIN_FILE ) );
		$this->define( 'PE_PLUGIN_ASSET_URI', trailingslashit( PE_PLUGIN_URI . 'assets' ) );
		$this->define( 'PE_PLUGIN_INC_DIR', trailingslashit( PE_PLUGIN_DIR . 'includes' ) );
		$this->define( 'PE_PLUGIN_TEMPLATE_DIR', trailingslashit( PE_PLUGIN_DIR . 'templates' ) );
		// Plugin post type
		$this->define( 'PE_POST_TYPE', 'opal_portfolio');
		$this->define( 'PE_CAT', 'portfolio_cat');
		
	}

	/**
	 * set define
	 *
	 * @param string name
	 * @param string | boolean | anythings
	 * @since 1.0.0
	 */
	private function define( $name = '', $value = '' ) {
		defined( $name ) || define( $name, $value );
	}

	/**
	 * Load and init Portfolio
	 */
	public function load() {

		$this->_include( 'includes/post-type/portfolio.php' );
		
	}

	/**
	 * include all required files
	 */
	private function includes() {
		$this->_include( 'includes/class-opalportfolio-portfolio.php' );
		$this->_include( 'includes/class-template-loader.php' );	
		$this->_include( 'includes/hook-functions.php' );
		
		if ( ! is_admin() ) {
			$this->_include( 'includes/class-style-customizer.php' );
		}
		
		$this->_include( 'includes/shortcode/filter.php' );	
		$this->_include( 'includes/shortcode/grid.php' );	
		$this->_include( 'includes/shortcode/carousel.php' );

		//-- include admin setting
		$this->_include('includes/admin/class-admin.php');

		// Customizer
		$this->_include("includes/class-opalportfolio-customizer.php");

		// Widgets
		$this->_include("includes/class-opalportfolio-widgets.php");
	}

	/**
	 * Include list of collection files
	 *
	 * @var array $files
	 */
	public function include_files ( $files ) {
		foreach ( $files as $file ) {
			$this->_include( $file );
		}
	}

	/**
	 * include single file
	 */
	private function _include( $file = '' ) {
		$file = PE_PLUGIN_DIR . $file;
		if ( file_exists( $file ) ) {
			include_once $file;
		}
	}

	/**
	 * init main plugin hooks
	 */
	private function init_hooks() {
		// trigger init hooks
		
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since  1.0
	 * @return void
	*/
	public function load_textdomain() {
			// Set filter for Opalservice's languages directory
		$lang_dir = dirname( plugin_basename( PE_PLUGIN_DIR ) ) . '/languages/';
		$lang_dir = apply_filters( 'opalservice_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'opalportfolios' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'opalportfolios', $locale );

			// Setup paths to current locale file
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/opalportfolios/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/opalservice folder
			load_textdomain( 'opalportfolios', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/opalservice/languages/ folder
			load_textdomain( 'opalportfolios', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'opalportfolios', false, $lang_dir );
		}
	}
}

function Opalportfolios() {
	return OpalPortfolios::instance();
}
Opalportfolios();
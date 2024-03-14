<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Autoloader
 *
 * @param   string $class
 *
 * @return  boolean
 */
function siteseo_elementor_addon_autoloader( $class ) {
	$dir = '/inc';

	switch ( $class ) {
		case false !== strpos( $class, 'SiteSeoElementorAddon\\Admin\\' ):
										$class = strtolower( str_replace( 'SiteSeoElementorAddon\\Admin', '', $class ) );
										$dir  .= '/admin';
			break;
		case false !== strpos( $class, 'SiteSeoElementorAddon\\Controls\\' ):
										$class = strtolower( str_replace( 'SiteSeoElementorAddon\\Controls', '', $class ) );
										$dir  .= '/controls';
			break;
		case false !== strpos( $class, 'SiteSeoElementorAddon\\' ):
										$class = strtolower( str_replace( 'SiteSeoElementorAddon', '', $class ) );
			break;
		default:
			return;
	}

	$filename = dirname( __FILE__ ) . $dir . str_replace( '_', '-', str_replace( '\\', '/class-', $class ) ) . '.php';

	if ( file_exists( $filename ) ) {
		require_once $filename;

		if ( class_exists( $class ) ) {
			return true;
		}
	}

	return false;
}
spl_autoload_register( 'siteseo_elementor_addon_autoloader' );

final class SiteSeo_Elementor_Addon {
	/**
	 * Class instance
	 *
	 * @var \SiteSeo_Elementor_Addon
	 */
	private static $instance = null;

	/**
	 * Load instance of the class
	 *
	 * @return  \SiteSeo_Elementor_Addon
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
			self::$instance->_constants();
			self::$instance->_load_objects();
		}


		return self::$instance;
	}

	/**
	 * Constructor private
	 *
	 * @return  void
	 */
	private function __construct() {

	}

	/**
	 * Define plugin constants
	 *
	 * @return  void
	 */
	private function _constants() {
		if ( ! defined( 'SITESEO_ELEMENTOR_ADDON_DIR' ) ) {
			define( 'SITESEO_ELEMENTOR_ADDON_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		if ( ! defined( 'SITESEO_ELEMENTOR_ADDON_URL' ) ) {
			define( 'SITESEO_ELEMENTOR_ADDON_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
	}

	/**
	 * Initiate classes
	 *
	 * @return  void
	 */
	private function _load_objects() {
		if ( is_admin() ) {
			\SiteSeoElementorAddon\Register_Controls::get_instance();
			\SiteSeoElementorAddon\Admin\Siteseo_Meta_Helper::get_meta_fields();
			\SiteSeoElementorAddon\Admin\Document_Settings_Section::get_instance();
		}
	}
}

SiteSeo_Elementor_Addon::get_instance();

function siteseo_elementor_tabs_seo_start() {
	ob_start();
}

function siteseo_elementor_tabs_seo_end() {
	$output  = \ob_get_clean();
	$search  = '/(<div class="elementor-component-tab elementor-panel-navigation-tab" data-tab="global">.*<\/div>)/m';
	$replace = '${1}<div id="siteseo-seo-tab" class="elementor-panel-navigation-tab" data-tab="seo">SEO</div>';
	
	// phpcs:disable
	echo \preg_replace(
		$search,
		$replace,
		$output
	); 
	// phpcs:enable
}
add_action( 'elementor/editor/footer', 'siteseo_elementor_tabs_seo_start', 0 );
add_action( 'elementor/editor/footer', 'siteseo_elementor_tabs_seo_end', 999 );
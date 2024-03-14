<?php
/**
 * Custom CSS, JS & PHP - Main Class
 *
 * @version 2.2.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_CCJP' ) ) :

final class Alg_CCJP {

	/**
	 * @var   Alg_CCJP The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_CCJP Instance
	 *
	 * Ensures only one instance of Alg_CCJP is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_CCJP - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_CCJP Constructor.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Core
		$this->core = require_once( 'class-alg-custom-css-js-php-core.php' );

		// Admin
		if ( is_admin() ) {
			// Action links
			add_filter( 'plugin_action_links_' . plugin_basename( ALG_CCJP_PLUGIN_FILE ), array( $this, 'action_links' ) );
			// Settings
			$this->settings = array();
			$this->settings['general'] = require_once( 'settings/class-alg-custom-css-js-php-settings.php' );
			// Version updated
			if ( get_alg_ccjp_option( 'version', '' ) !== ALG_CCJP_VERSION ) {
				add_action( 'admin_init', array( $this, 'version_updated' ), PHP_INT_MAX );
			}
		}
	}

	/**
	 * localize.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function localize() {
		load_plugin_textdomain( 'custom-css', false, dirname( plugin_basename( ALG_CCJP_PLUGIN_FILE ) ) . '/langs/' );
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		foreach ( array( 'css', 'js', 'php' ) as $section ) {
			$custom_links[] = '<a href="' . admin_url( 'tools.php?page=alg-custom-' . $section ) . '">' . strtoupper( $section ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * version_updated.
	 *
	 * @version 2.1.0
	 * @since   2.0.0
	 */
	function version_updated() {
		foreach ( $this->settings as $section ) {
			foreach ( array( 'css', 'js', 'php' ) as $_section ) {
				foreach ( $section->get_settings( $_section ) as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
						add_option( ALG_CCJP_ID . '_' . $value['id'], $value['default'], '', $autoload );
					}
				}
			}
		}
		update_option( ALG_CCJP_ID . '_' . 'version', ALG_CCJP_VERSION );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_CCJP_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_CCJP_PLUGIN_FILE ) );
	}

}

endif;

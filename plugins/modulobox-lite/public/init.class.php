<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Front End class
 *
 * @class ModuloBox_Init
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Init {

	/**
	 * Options
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $options = array();

	/**
	 * Inline scrit
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private $inline_script;

	/**
	 * Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Init ModuloBox after the global WP class object is set up
		add_action( 'wp', array( $this, 'init' ) );

	}

	/**
	 * Init ModuloBox
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Get ModuloBox settings
		$this->get_settings();

		// Enqueue on mobile devices only if enabled
		if ( $this->options['mobileDevice'] || ! wp_is_mobile() ) {

			// Generate inline script
			$this->get_inline_script();
			// Load admin style sheet and JavaScript.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9999 );

		}

		// Add custom gallery shortcode
		if ( $this->options['gallery'] ) {

			$gallery = new ModuloBox_Gallery;
			$gallery->load( $this->options );

		}

	}


	/**
	 * Get and normalize settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_settings() {

		$options = get_option( MOBX_NAME );

		// Normalize sanitized settings
		$normalize = new ModuloBox_Normalize_Settings( $options );
		$this->options = $normalize->get_settings();

	}

	/**
	 * Add inline script
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_inline_script() {

		$this->jquery_wrapper_start();
		$this->set_options();
		$this->add_custom_js_before();
		$this->dom_loaded_start();
		$this->instantiate_script();
		$this->add_modules();
		$this->add_custom_js_after();
		$this->initialize_script();
		$this->dom_loaded_end();
		$this->jquery_wrapper_end();

	}

	/**
	 * Add inline options "manually"
	 * Because wp_localize_script converting numbers/booleans into strings (which is not compliant with JavaScript options format)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function set_options() {

		$options = apply_filters( MOBX_NAME . '_settings', $this->options );

		$this->inline_script .= 'var mobx_options = ' . wp_json_encode( $options['options'] ) . ';';
		$this->inline_script .= 'var mobx_accessibility = ' . wp_json_encode( $options['accessibility'] ) . ';';

	}

	/**
	 * Add custom JS entered by user (before instantiation)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_custom_js_before() {

		$this->inline_script .= apply_filters( MOBX_NAME . '_before_custom_js', '' );

	}

	/**
	 * Add custom JS entered by user (before initialization)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_custom_js_after() {

		$this->inline_script .= apply_filters( MOBX_NAME . '_after_custom_js', '' );

	}

	/**
	 * Start jQuery wrapper to benefit of jQuery alias ($)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function jquery_wrapper_start() {

		$this->inline_script .= '(function($){';
		$this->inline_script .= '"use strict";';

	}

	/**
	 * End jQuery wrapper to benefit of jQuery alias ($)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function jquery_wrapper_end() {

		$this->inline_script .= '})(jQuery)';

	}

	/**
	 * Start DOMContentLoaded event wrapper
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function dom_loaded_start() {

		$this->inline_script .= 'document.addEventListener(\'DOMContentLoaded\',function(){';

	}

	/**
	 * End DOMContentLoaded event wrapper
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function dom_loaded_end() {

		$this->inline_script .= '});';

	}

	/**
	 * Script instantiation
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function instantiate_script() {

		$this->inline_script .= 'var mobx = new ModuloBox(mobx_options);';

	}

	/**
	 * Script initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function initialize_script() {

		$this->inline_script .= 'mobx.init();';

	}

	/**
	 * Add main modules for 3rd party plugins
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_modules() {

		$content  = '';
		$modules  = $this->options['modules'];
		$minified = ! $this->options['debugMode'] ? '.min' : '';

		foreach ( $modules as $module ) {

			$path = MOBX_PUBLIC_PATH . '/modules/' . $module . $minified . '.php';

			if ( function_exists( 'realpath' ) ) {
				$path = realpath( $path );
			}

			if ( $path && is_file( $path ) ) {

				$content = include_once( $path );

				if ( ! empty( $content ) ) {

					$this->inline_script .= ! $minified ? "\n" . '// ' . $module . ' module' : '';
					$this->inline_script .= $content;

				}
			}
		}
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {

		$this->options['inlineCSS'] = apply_filters( MOBX_NAME . '_custom_css', '' );

		$minified = ! $this->options['debugMode'] ? '.min' : '';

		wp_enqueue_style( MOBX_NAME, MOBX_PUBLIC_URL . 'assets/css/modulobox' . $minified . '.css', array(), MOBX_VERSION );
		wp_add_inline_style( MOBX_NAME, $this->options['inlineCSS'] );

		wp_enqueue_script( MOBX_NAME, MOBX_PUBLIC_URL . 'assets/js/modulobox' . $minified . '.js', array(), MOBX_VERSION, true );
		wp_add_inline_script( MOBX_NAME, $this->inline_script );

	}
}

new ModuloBox_Init;

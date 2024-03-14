<?php
/**
 * The internationalization class
 *
 * @package    Rock_Convert\Inc\Core
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Core;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */
class Internationalization_i18n {//phpcs:ignore

	/**
	 * Text domain of translate.
	 *
	 * @var string
	 */
	private $text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_text_domain The domain text od plugin.
	 */
	public function __construct( $plugin_text_domain ) {

		$this->text_domain = $plugin_text_domain;

	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			$this->text_domain,
			false,
			dirname( dirname( dirname( plugin_basename( __FILE__ ) ) ) ) . '/languages/'
		);
	}
}

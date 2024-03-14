<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rankchecker.io
 * @since      1.0.0
 *
 * @package    Rankchecker
 * @subpackage Rankchecker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rankchecker
 * @subpackage Rankchecker/public
 * @author     Rankchecker <info@rankchecker.io>
 */
class Rankchecker_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rankchecker-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rankchecker-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Verification Meta Tag
	 */
	public function add_meta_tag() {
		if ( get_option( 'rc_domain_secret' ) ) {
			echo sprintf( '<meta name="rankchecker" content="%s">', get_option( 'rc_domain_secret' ) );
		}
	}

}

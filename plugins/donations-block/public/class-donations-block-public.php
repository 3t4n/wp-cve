<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://about.me/bharatkambariya
 * @since      2.1.0
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/public
 * @author     bharatkambariya <bharatkambariya@gmail.com>
 */
class Donations_Block_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    2.1.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/donations-block-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    2.1.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'jQuery', plugin_dir_url( __FILE__ ) . 'js/jquery.min.js','', $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/donations-block-public.js', array( 'jquery' ), $this->version, true );
	}

}

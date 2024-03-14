<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://forhad.net/
 * @since      1.0.0
 *
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Post_Slider_Grandslider
 * @subpackage Wp_Post_Slider_Grandslider/public
 * @author     Forhad <need@forhad.net>
 */
class Wp_Post_Slider_Grandslider_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Post_Slider_Grandslider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Post_Slider_Grandslider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-post-slider-grandslider-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wppsgs-shorcode-essentials', plugin_dir_url( __FILE__ ) . 'css/wppsgs-shorcode-essentials.css', array(), $this->version, 'all' );
		wp_register_style( 'wppsgs-slider-css', plugin_dir_url( __FILE__ ) . 'css/splide.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Post_Slider_Grandslider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Post_Slider_Grandslider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-post-slider-grandslider-public.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'wppsgs-slider-js', plugin_dir_url( __FILE__ ) . 'js/splide.min.js', array(), $this->version, false );

	}

}

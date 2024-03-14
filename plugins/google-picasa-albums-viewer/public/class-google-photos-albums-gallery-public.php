<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       nakunakifi.com
 * @since      4.0.0
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/public
 * @author     Ian Kennerley <iankennerley@gmail.com>
 */
class Google_Photos_Albums_Gallery_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
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
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Photos_Albums_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Photos_Albums_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/google-photos-albums-gallery-public.css', array(), $this->version, 'all' );
		// Adding lightbox assets
		wp_enqueue_style( 'lightbox', plugin_dir_url( __FILE__ ) . 'css/lightbox/lightbox.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Photos_Albums_Gallery_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Photos_Albums_Gallery_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/google-photos-albums-gallery-public.js', array( 'jquery' ), $this->version, false );
		
		// Adding lightbox assets
		wp_enqueue_script( 'cws_gpp_lightbox', plugin_dir_url( __FILE__ )  . '../public/js/lightbox/lightbox.js', array( 'jquery' ), false, true );                                         
		wp_enqueue_script( 'cws_gpp_init_lightbox', plugin_dir_url( __FILE__ )  . '../public/js/lightbox/init_lightbox.js', array( 'cws_gpp_lightbox' ), false , true );
	
	}

}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Preloader_Awesome
 * @subpackage Preloader_Awesome/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Preloader_Awesome
 * @subpackage Preloader_Awesome/public
 * @author     Themes Awesome <admin@themesawesome.com>
 */
class Preloader_Awesome_Public {

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
		 * defined in Preloader_Awesome_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Preloader_Awesome_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/preloader-awesome-public.css', array(), $this->version, 'all' );

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
		 * defined in Preloader_Awesome_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Preloader_Awesome_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;

		$preloader_awesome_style = carbon_get_post_meta( $post->ID, 'preloader_awesome_style' );
		$preloader_awesome_style_global = carbon_get_theme_option( 'preloader_awesome_style_global' );

		if(!empty($preloader_awesome_style_global) || !empty($preloader_awesome_style)) {
			wp_enqueue_script( 'snap', plugin_dir_url( __FILE__ ) . 'js/snap.svg-min.js', array(), $this->version, false );
			wp_enqueue_script( 'ta-preloader-classie', plugin_dir_url( __FILE__ ) . 'js/classie.js', array(), $this->version, false );
			wp_enqueue_script( 'svgloader', plugin_dir_url( __FILE__ ) . 'js/svgloader.js', array(), $this->version, false );
		}

		if(empty($preloader_awesome_style_global) || empty($preloader_awesome_style)) {
			wp_enqueue_script( 'ta-preloader-classie', plugin_dir_url( __FILE__ ) . 'js/classie.js', array(), $this->version, false );
		}

	}

}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://chetansatasiya.com/blog
 * @since      1.0.0
 *
 * @package    Cs_Remove_Version_Number_From_Css_Js
 * @subpackage Cs_Remove_Version_Number_From_Css_Js/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cs_Remove_Version_Number_From_Css_Js
 * @subpackage Cs_Remove_Version_Number_From_Css_Js/public
 * @author     Chetan Satasiya <chetansatasiya88@gmail.com>
 */
class Cs_Remove_Version_Number_From_Css_Js_Public {

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
		 * defined in Cs_Remove_Version_Number_From_Css_Js_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Remove_Version_Number_From_Css_Js_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cs-remove-version-number-from-css-js-public.css', array(), $this->version, 'all' );

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
		 * defined in Cs_Remove_Version_Number_From_Css_Js_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cs_Remove_Version_Number_From_Css_Js_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cs-remove-version-number-from-css-js-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Function to remove version numbers
	 * @param $src
	 *
	 * @return string
	 */
	public function cs_remove_ver_css_js( $src ) {
		if ( strpos( $src, 'ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}

}

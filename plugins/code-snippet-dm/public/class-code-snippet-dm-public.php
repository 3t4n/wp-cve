<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       devmaverick.com
 * @since      1.0.0
 *
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/public
 * @author     George Cretu <george@devmaverick.com>
 */
class CSDM_Public {

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
	public function csdm_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Code_Snippet_Dm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Code_Snippet_Dm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_style( $this->plugin_name . '-main-min', plugin_dir_url( __FILE__ ) . 'css/main.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function csdm_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Code_Snippet_Dm_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Code_Snippet_Dm_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
  	// wp_enqueue_script( $this->plugin_name . '-dm-clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), $this->version, false );
      wp_enqueue_script( $this->plugin_name . '-dm-clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboardv201.min.js', array( 'jquery' ), $this->version, false );
      wp_enqueue_script( $this->plugin_name . '-dm-prism', plugin_dir_url( __FILE__ ) . 'js/prism.js', array( 'jquery' ), $this->version, false );
      wp_enqueue_script( $this->plugin_name . '-dm-manually-start-prism', plugin_dir_url( __FILE__ ) . 'js/manually-start-prism.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/code-snippet-dm-public.js', array( 'jquery' ), $this->version, false );

	}

}

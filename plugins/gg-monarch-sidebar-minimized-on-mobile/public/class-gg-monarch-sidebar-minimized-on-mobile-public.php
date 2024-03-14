<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/tomas-groulik/
 * @since      1.0.0
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/public
 * @author     Tomas Groulik <tomas.groulik@gmail.com>
 */
class GG_Monarch_Sidebar_Minimized_On_Mobile_Public {

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

    protected $enquier;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version , $enquier) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->enquier = $enquier;
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
		 * defined in GG_Monarch_Sidebar_Minimized_On_Mobile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GG_Monarch_Sidebar_Minimized_On_Mobile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        $this->enquier->enqueue( 'main', 'monarchSidebarMinMainStyle', [ 'css' => true, 'media' => 'all' ] );
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/styles/gg-monarch-sidebar-minimized-on-mobile-public.css', array(), $this->version, 'all' );

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
		 * defined in GG_Monarch_Sidebar_Minimized_On_Mobile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GG_Monarch_Sidebar_Minimized_On_Mobile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        $assets = $this->enquier->enqueue( 'main', 'monarchSidebarMinMain', [ 'in_footer' => true ] );
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/scripts/gg-monarch-sidebar-minimized-on-mobile-public.bundle.js', array( 'jquery' ), $this->version, true );

	}

}

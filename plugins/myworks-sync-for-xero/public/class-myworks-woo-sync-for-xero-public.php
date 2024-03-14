<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://myworks.software
 * @since      1.0.0
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    MyWorks_WC_Xero_Sync
 * @subpackage MyWorks_WC_Xero_Sync/public
 * @author     MyWorks Software <support@myworks.design>
 */
class MyWorks_WC_Xero_Sync_Public {

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
		 * defined in MyWorks_WC_Xero_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyWorks_WC_Xero_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		#wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/'.$this->plugin_name.'-public.css', array(), $this->version, 'all' );

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
		 * defined in MyWorks_WC_Xero_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The MyWorks_WC_Xero_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		#wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/'.$this->plugin_name.'-public.js', array( 'jquery' ), $this->version, false );

	}
	
	# Public URLs
	public function public_api_init(){
		add_rewrite_rule( 'public-api.php$', 'index.php?mw_xero_sync_public_api=1', 'top' );
		add_rewrite_rule( 'sync-window.php$', 'index.php?mw_xero_sync_public_sync_window=1', 'top' );
	}
	
	public function  public_api_query_vars( $query_vars ){
		$query_vars[] = 'mw_xero_sync_public_api';
		$query_vars[] = 'mw_xero_sync_public_sync_window';
		return $query_vars;
	}
	
	public function public_api_request($wp) {
		if ( array_key_exists( 'mw_xero_sync_public_api', $wp->query_vars ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'partials/public-api.php';
			exit();
		}
		
		if ( array_key_exists( 'mw_xero_sync_public_sync_window', $wp->query_vars ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'partials/sync-window.php';
			exit();
		}
	}

}

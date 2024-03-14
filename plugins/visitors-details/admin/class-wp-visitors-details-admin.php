<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://topinfosoft.com
 * @since      1.0.0
 *
 * @package    Wp_Visitors_Details
 * @subpackage Wp_Visitors_Details/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Visitors_Details
 * @subpackage Wp_Visitors_Details/admin
 * @author     Top Infosoft <topinfosoft@gmail.com>
 */
class Wp_Visitors_Details_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css');
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dataTables.bootstrap4.min.css');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-visitors-details-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */


	public function enqueue_scripts() {
    	wp_enqueue_script('datatable', plugins_url('js/jquery.dataTables.min.js',__FILE__ ));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-visitors-details-admin.js', array( 'jquery' ), $this->version, false );

	}

}

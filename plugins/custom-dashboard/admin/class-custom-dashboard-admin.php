<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://freelancertestbd.blogspot.com
 * @since      1.0.0
 *
 * @package    Custom_Dashboard
 * @subpackage Custom_Dashboard/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Custom_Dashboard
 * @subpackage Custom_Dashboard/admin
 * @author     Dipto Paul <dipto71@outlook.com>
 */
class Custom_Dashboard_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $custom_dashboard    The ID of this plugin.
	 */
	private $custom_dashboard;

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
	 * @param      string    $custom_dashboard       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $custom_dashboard, $version ) {

		$this->custom_dashboard = $custom_dashboard;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->custom_dashboard, plugin_dir_url( __FILE__ ) . 'css/custom-dashboard-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'custom_dashboard_icon', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custom_Dashboard_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Dashboard_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->custom_dashboard, plugin_dir_url( __FILE__ ) . 'js/custom-dashboard-admin.js', array( 'jquery' ), $this->version, false );

	}

}

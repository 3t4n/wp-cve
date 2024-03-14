<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://pixeldima.com
 * @since      1.0.0
 *
 * @package    Dima_Take_Action
 * @subpackage Dima_Take_Action/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dima_Take_Action
 * @subpackage Dima_Take_Action/admin
 * @author     Your Name <email@example.com>
 */
class Dima_Take_Action_Admin {

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
		if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/redux-framework/ReduxCore/framework.php' ) ) {
//			require_once( dirname( __FILE__ ) . '/redux-framework/redux-framework.php' );
			require_once( dirname( __FILE__ ) . '/redux-framework/ReduxCore/framework.php' );
		}
		if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/redux-framework/pixeldima/pixeldima-config.php' ) ) {		
			require_once( dirname( __FILE__ ) . '/redux-framework/pixeldima/pixeldima-config.php' );
		}
		
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dima_Take_Action_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dima_Take_Action_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dima-take-action-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dima_Take_Action_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dima_Take_Action_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dima-take-action-admin.js', array( 'jquery' ), $this->version, false );

	}

}

function dima_ta_addPanelCSS() {
    wp_register_style(
        'redux-custom-css',
        plugin_dir_url( __FILE__ ).'/redux-framework/redux-custom.css',
        array( 'redux-admin-css' ), // Be sure to include redux-admin-css so it's appended after the core css is applied
        time(),
        'all'
    );  
    wp_enqueue_style('redux-custom-css');
}
add_action( 'redux/page/dima_ta_demo/enqueue', 'dima_ta_addPanelCSS' );
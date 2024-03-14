<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dipankar-team.business.site
 * @since      1.0.0
 *
 * @package    Customwprest
 * @subpackage Customwprest/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Customwprest
 * @subpackage Customwprest/admin
 * @author     Dipankar <dipankarpal212@gmail.com>
 */
class WCRA_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Customwprest_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customwprest_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/customwprest-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'wcra_font-awesome', plugin_dir_url( __FILE__ ) . '/font-awesome/css/font-awesome.css', array(), $this->version, 'all' );
		

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
		 * defined in Customwprest_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Customwprest_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// Register the script
		wp_register_script( 'wcra_back_handle',  plugin_dir_url( __FILE__ ) . 'js/customwprest-admin.js'  );

		// Localize the script with new data
		$translation_array = array(
			'base_url' => plugins_url(WCRA_PLUGIN_SLUG) ,
			'settings_url' => admin_url('admin.php?page=wcra_api_settings'),
		);
		wp_localize_script( 'wcra_back_handle', 'wcraObj', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'wcra_back_handle' );

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/customwprest-admin.js', array( 'jquery' ), $this->version, false );


	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://olark.com
 * @since      1.0.0
 *
 * @package    Olark_Wp
 * @subpackage Olark_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Olark_Wp
 * @subpackage Olark_Wp/admin
 * @author     Olark <platform@olark.com>
 */
class Olark_Wp_Admin {

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
		 * defined in Olark_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Olark_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/olark-wp-admin.css', array(), $this->version, 'all' );

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
		 * defined in Olark_Wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Olark_Wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/olark-wp-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function add_plugin_admin_menu() {


		add_options_page( 'Olark settings', 'Olark Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
    );
	}

 /**
 * Add settings action link to the plugins page.
 *
 * @since    1.0.0
 */

	public function add_action_links( $links ) {

		$settings_link = array(
		'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );
	}

	public function display_plugin_setup_page() {
		include_once( 'partials/olark-wp-admin-display.php' );
	}

	public function validate($input) {
    // All checkboxes inputs
    $valid = array();

    $valid['olark_site_ID'] = esc_textarea($input['olark_site_ID']);
	  $valid['enable_olark'] = (isset($input['enable_olark']) && !empty($input['enable_olark'])) ? 1 : 0;
    $valid['enable_cartsaver'] = (isset($input['enable_cartsaver']) && !empty($input['enable_cartsaver'])) ? 1 : 0;
    $valid['start_expanded'] = (isset($input['start_expanded']) && !empty($input['start_expanded'])) ? 1 : 0;
    $valid['detached_chat'] = (isset($input['detached_chat']) && !empty($input['detached_chat'])) ? 1 : 0;
    $valid['override_lang'] = (isset($input['override_lang']) && !empty($input['override_lang'])) ? 1 : 0;
		$valid['olark_lang'] = esc_textarea($input['olark_lang']);
		$valid['olark_api'] = esc_textarea($input['olark_api']);
		$valid['olark_mobile'] = (isset($input['olark_mobile']) && !empty($input['olark_mobile'])) ? 1 : 0;

    return $valid;
 }

	public function options_update() {
    register_setting($this->plugin_name, $this->plugin_name	, array($this, 'validate'));
 }

}

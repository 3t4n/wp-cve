<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.audioeye.com
 * @since      1.0.0
 *
 * @package    Audioeye
 * @subpackage Audioeye/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Audioeye
 * @subpackage Audioeye/admin
 * @author     AudioEye <hhedger@audioeye.com>
 */
class Audioeye_Admin {

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

	public function register_admin_page() {
		add_menu_page( 'AudioEye Dashboard', 'AudioEye', 'manage_options', 'ae-admin', array( $this, 'include_admin_partial' ) );
	}

	public function include_admin_partial() {
		include( plugin_dir_path( __FILE__ ) . 'partials/audioeye-admin-display.php' );
	}

	public function post_first() {
		if ( !isset($_POST['nonce']) ) {
			exit;
		}
	
		$nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ) );
	
		if ( !isset( $nonce ) || !wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			die( 'Nonce validation failed.');
		}

		if (!isset($_POST['site_hash'])) {
			exit;
		}

		update_option('audioeye_config', array(
			'site_hash' => sanitize_text_field( wp_unslash( $_POST['site_hash'] ) )
		));

		exit;
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
		 * defined in Audioeye_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Audioeye_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/audioeye-admin.css', array(), $this->version, 'all' );

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
		 * defined in Audioeye_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Audioeye_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$params = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce')
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/audioeye-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'params', $params );

	}

}

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       thetechtribe.com
 * @since      1.0.0
 *
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/admin
 * @author     Nigel Moore <help@thetechtribe.com>
 */
class The_Tribal_Plugin_Admin {

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
		 * defined in The_Tribal_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Tribal_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( tttAllowedAdminAssetInclude() ){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-tribal-plugin-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-bootstrap-5-iso', tttc_get_plugin_dir_url() . 'assets/css/bootstrap-iso-v5.3.2.min.css', array(), '5.3.2', 'all' );
		}

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
		 * defined in The_Tribal_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The The_Tribal_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( tttAllowedAdminAssetInclude() ){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/the-tribal-plugin-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-bootstrap-5-iso', tttc_get_plugin_dir_url() . 'assets/js/bootstrap-v5.3.2.bundle.min.js', array( 'jquery' ), '5.3.2', false );
			wp_localize_script( $this->plugin_name, 'ttt_admin_ajax_object',
				[
					'plugin_url' => tttc_get_plugin_dir_url(),
					'ajax_url' => admin_url( 'admin-ajax.php' )
				]
			);
		}
	}

	public function cron_jobs()
	{
		\TheTribalPlugin\CronJobs::get_instance()->init();
	}

}

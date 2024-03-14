<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://walkerwp.com/
 * @since      1.0.0
 *
 * @package    Walker_Core
 * @subpackage Walker_Core/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Walker_Core
 * @subpackage Walker_Core/admin
 * @author     WalkerWp <support@walkerwp.com>
 */
class Walker_Core_Admin {

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
		$this->walker_core_admin();

	}

	private function walker_core_admin(){
		/**
		* Register custom post type for plugins
		*/
		require_once WALKER_CORE_PATH . 'admin/walker-core-posttype.php';

		/**
		* Register meta box for walker post type
		*/
		require_once WALKER_CORE_PATH . 'admin/register-metabox.php';

		/**
		 * Register Menu plugins
		 */
		require_once WALKER_CORE_PATH . 'admin/register-menu.php';

		/**
		 * Register Menu plugins
		 */
		require_once WALKER_CORE_PATH . 'admin/customizer.php';



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
		 * defined in Walker_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Walker_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'walker-core-admin-style', plugin_dir_url( __FILE__ ) . 'css/walker-core-admin.css', array(), $this->version, 'all' );

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
		 * defined in Walker_Core_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Walker_Core_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/walker-core-admin.js', array( 'jquery' ), $this->version, false );

	}

}

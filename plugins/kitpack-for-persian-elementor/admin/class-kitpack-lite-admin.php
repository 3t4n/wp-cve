<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       elementorplus.net
 * @since      1.0.0
 *
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/admin
 * @author     elementorplus <plugin@elementorplus.net>
 */
class Kitpack_Lite_Admin {

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
		require_once plugin_dir_path( __FILE__ ) . 'framework/kpe-framework.php';
		require_once plugin_dir_path( __FILE__ ) . 'options/kitpack-lite-options.php';

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
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if($this->kpe_get_option('admin-farsi-font')){
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/kitpack-lite-admin.css', array(), $this->version, 'all' );
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
		 * defined in Kitpack_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Kitpack_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/kitpack-lite-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		/**
		 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		 * The "plugins.php" must match with the previously added add_submenu_page first option.
		 * For custom post type you have to change 'plugins.php?page=' to 'edit.php?post_type=your_custom_post_type&page='
		 */
		$settings_link = array( '<a href="' . admin_url( 'admin.php?page=kitpack' ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

		$pro_link = array( '<a href="https://kitpack.ir/pro">' . __( 'pro', $this->plugin_name ) . '</a>', );
		// -- OR --

		// $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );

		return array_merge(  $settings_link, $pro_link, $links );

	}


	public static function kpe_get_option( $option = '', $default = null ) {
		$options = get_option( 'kpe_option' ); // Attention: Set your unique id of the framework
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}

}

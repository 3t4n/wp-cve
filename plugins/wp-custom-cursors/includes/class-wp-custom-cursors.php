<?php
/**
 * Main Class
 * Admin and Public Hooks
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

/**
 *
 * Wp_Custom_Cursors
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_custom_cursors {

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Custom_Cursors_Loader    $loader
	 */
	protected $loader;

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name
	 */
	protected $plugin_name;

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version   
	 */
	protected $version;

	/**
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_CUSTOM_CURSORS_VERSION' ) ) {
			$this->version = WP_CUSTOM_CURSORS_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		if ( defined( 'WP_CUSTOM_CURSORS_PLUGIN_BASE' ) ) {
			$this->plugin_base = WP_CUSTOM_CURSORS_PLUGIN_BASE;
		} else {
			$this->plugin_base = 'wp-custom-cursors/wp-custom-cursors.php';
		}

		$this->plugin_name = 'wpcustom-cursors';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-custom-cursors-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-custom-cursors-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-custom-cursors-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-custom-cursors-public.php';

		$this->loader = new Wp_Custom_Cursors_Loader();

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Custom_Cursors_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Custom_Cursors_Admin( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wp_custom_cursors_add_admin_menu' );

		$this->loader->add_action( 'wp_loaded', $plugin_admin, 'crud_cursor' );

		$this->loader->add_filter( 'plugin_action_links_' . $this->plugin_base, $plugin_admin, 'add_plugin_settings_link' );

	}

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Custom_Cursors_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	} 

	/**
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string   
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    Wp_Custom_Cursors_Loader   
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 *
	 * @since     1.0.0
	 * @return    string   
	 */
	public function get_version() {
		return $this->version;
	}

}

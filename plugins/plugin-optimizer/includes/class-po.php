<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/includes
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */

class PluginOptimizer {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {
		
        $this->version     = SOSPO_VERSION;
		$this->plugin_name = 'plugin-optimizer';

		$this->include_dependencies();
		$this->init();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @access   private
	 */
	private function include_dependencies() {
        
		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-i18n.php';

		// The class responsible for defining helper functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-helper.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin.php';

		// The class responsible for menu pages of the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-pages.php';

		// The class responsible for defining all Ajax actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-po-admin-ajax.php';

        if( function_exists("sospo_mu_plugin") && in_array( "woocommerce/woocommerce.php", sospo_mu_plugin()->blocked_plugins ) ){
            
            // sospo_mu_plugin()->write_log( sospo_mu_plugin()->blocked_plugins, "PluginOptimizer-load_dependencies-blocked_plugins" );
            
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-woocommerce.php';
            
        }
		
		// The class responsible for all communication with the Dictionary
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-po-dictionary.php';

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function init() {

		$plugin_i18n  = new SOSPO_i18n();
		$plugin_admin = new SOSPO_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin_ajax   = new SOSPO_Ajax();
		$menu_pages   = new SOSPO_Admin_Menu_Pages();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

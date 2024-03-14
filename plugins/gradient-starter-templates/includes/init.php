<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used to
 * add/remove/edit the functionality of the Gradient Themes Theme
 *
 * @link       https://www.gradientthemes.com/
 * @since      1.0.0
 *
 * @package    Gradient Themes
 *  
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * add/remove/edit the functionality of the Gradient Themes Theme
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Gradient Themes
 *  
 * @author     Gradient Themes <info@gradientthemes.com>
 */
class gradient_starter_templates {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      gradient_starter_templates_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Full Name of plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_full_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_full_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Main Instance
	 *
	 * Insures that only one instance of gradient_starter_templates exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @return object
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new gradient_starter_templates;
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		if ( defined( 'gradient_starter_templates_VERSION' ) ) {
			$this->version = gradient_starter_templates_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'gradient-starter-templates';
		$this->plugin_full_name = esc_html__('Gradient Sterter Templates','gradient-starter-templates');

		$this->load_dependencies();
		$this->set_locale();

		if ( gradient_starter_templates_get_current_theme_author() == 'gradientthemes') {
			$this->define_hooks();
			$this->load_hooks();
		}
		else {
			add_action( 'admin_notices', array( $this, 'gradient_starter_templates_missing_notice' ) );
		}

	}

	/**
	 * Since the plugin is created specially for Gradient Themes
	 * Show notice if Gradient Themes theme is not installed/activated
	 *
	 * @since    1.0.0
	 */
	public function gradient_starter_templates_missing_notice() {

		$search_url = in_array( 'gradientthemes', array_keys( wp_get_themes()), true ) ? admin_url( 'theme-install.php?search=gradientthemes' ) : admin_url( 'theme-install.php?search=gradientthemes' );

		echo '<div class="error notice is-dismissible"><p><strong>' . esc_html($this->plugin_full_name) . '</strong> &#8211; ' . sprintf( esc_html__( 'This plugin requires %s Theme to be activated to work.', 'gradient-starter-templates' ), '<a href="'.esc_url( $search_url ).'">' . esc_html__('Gradient Themes','gradient-starter-templates'). '</a>' ) . '</p></div>';
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - gradient_starter_templates_Loader. Orchestrates the hooks of the plugin.
	 * - gradient_starter_templates_i18n. Defines internationalization functionality.
	 * - gradient_starter_templates_Admin. Defines all hooks for the admin area.
	 * - gradient_starter_templates_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
        
        //
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'/best-shop-pro/best-shop-pro.php')){
           require_once plugin_dir_path(dirname(__FILE__)).'/best-shop-pro/best-shop-pro.php'; 
        }

        //
        if (file_exists(plugin_dir_path(dirname(__FILE__)).'/news-blog-pro/news-blog-pro.php')){
            require_once plugin_dir_path(dirname(__FILE__)).'/news-blog-pro/news-blog-pro.php';
        }
        
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once gradient_starter_templates_PATH . 'includes/loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once gradient_starter_templates_PATH . 'includes/i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        require_once gradient_starter_templates_PATH . 'includes/functions.php';
		require_once gradient_starter_templates_PATH . 'includes/hooks.php';

		/*API*/
        require_once gradient_starter_templates_PATH . 'includes/api.php';


        $this->loader = new gradient_starter_templates_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the gradient_starter_templates_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new gradient_starter_templates_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_hooks() {

		$plugin_admin = gradient_starter_templates_hooks();
        $this->loader->add_action( 'admin_init', $plugin_admin, 'redirect' );
        $this->loader->add_action( 'advanced_import_demo_lists', $plugin_admin, 'add_demo_lists',999 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'import_menu' );
        $this->loader->add_action( 'wp_ajax_gradient_starter_templates_getting_started', $plugin_admin, 'install_advanced_import' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function load_hooks() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    gradient_starter_templates_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
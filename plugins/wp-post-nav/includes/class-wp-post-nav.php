<?php

/**
 * WP Post Nav core class.
 *
 * @link:       https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/includes
 */

// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 

class wp_post_nav {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      wp_post_nav_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $wp_post_nav    The string used to uniquely identify this plugin.
	 */
	protected $wp_post_nav;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    0.0.1
	 */
	public function __construct() {

		$this->plugin_name = 'wp-post-nav';
		$this->version = '2.0.3';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->version_control();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - wp_post_nav_Loader. Orchestrates the hooks of the plugin.
	 * - wp_post_nav_i18n. Defines internationalization functionality.
	 * - wp_post_nav_Admin. Defines all hooks for the dashboard.
	 * - wp_post_nav_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-nav-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-post-nav-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-post-nav-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-post-nav-public.php';

		$this->loader = new wp_post_nav_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the wp_post_nav_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new wp_post_nav_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new wp_post_nav_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//add admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		//$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update');

		//setup basename
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
                //write_log($plugin_basename);
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new wp_post_nav_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.0.1
	 * @return    wp_post_nav_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 *Perform a version control check and perform the required updates
	 *Version control was added in version 1.0.0
	 *We need to perform some checks to see if the user is installing from scratch, updating or something else
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function version_control() {
    //get the current plugin version
		$current_version 	=	$this->get_version();
		//see if there is an old version
		$old_version			= get_option( 'wp_post_nav_version' );
		
  	//check whats happened with the version.  if the option isnt defined (added in 1.0.0) then its either a major update, or a fresh install
  	if (!$old_version) {
    	$update_version = new wp_post_nav_Activator;
    	$update_version->activate($current_version);
  	}

  	else {
  		$version_check = version_compare($old_version, $current_version, '=') ? true : false;
  		
  		//versions dont match, update the option in the database
  		if (!$version_check) {
  			update_option ($old_version, $current_version);
  		}
  	}
  	if( get_transient( 'wp-post-nav' ) ){
    		add_action( 'admin_notices', function() {
            ?>
            <div class='notice notice-info is-dismissible'>
                <p><?php _e( 'WP Post Nav has performed an update to the database. Check everything is as it should be by visiting the <a href="' . admin_url( 'options-general.php?page=wp-post-nav">Settings</a> Page', 'wp_post_nav' )); ?></p>
            </div>
            <?php  
        });
        //delete_transient( 'wp-post-nav' );
    	}
	}
}

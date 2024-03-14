<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/includes
 * @author     Clifton Griffin <clif@cgd.io>
 */
class Simple_Content_Templates extends WordPress_SimpleSettings {


	/**
	 * The post type slug for ACT templates
	 *
	 * (default value: "act_template")
	 *
	 * @var string
	 * @access public
	 */
	var $post_type = "act_template";


	/**
	 * A prefix for various name signatures
	 *
	 * (default value: "Simple_Content_Templates")
	 *
	 * @var string
	 * @access public
	 */
	var $prefix = "Simple_Content_Templates";


	/**
	 * A shorter prefix for other name signatures.
	 *
	 * (default value: "act_")
	 *
	 * @var string
	 * @access public
	 */
	var $short_prefix = "act_";

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Simple_Content_Templates_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct() {

		parent::__construct();
		$this->plugin_name  = 'advanced-content-templates';
		$this->version = CGD_SCT_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Simple_Content_Templates_Loader. Orchestrates the hooks of the plugin.
	 * - Simple_Content_Templates_i18n. Defines internationalization functionality.
	 * - Simple_Content_Templates_Admin. Defines all hooks for the dashboard.
	 * - Simple_Content_Templates_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-content-templates-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-advanced-content-templates-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-advanced-content-templates-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-advanced-content-templates-public.php';

		$this->loader = new Simple_Content_Templates_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Simple_Content_Templates_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Simple_Content_Templates_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		global $act_plugin_admin;
		$act_plugin_admin = new Simple_Content_Templates_Admin( $this->get_Simple_Content_Templates(), $this->get_version(), $this );

		$this->loader->add_action( 'admin_enqueue_scripts', $act_plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $act_plugin_admin, 'enqueue_scripts' );

		// The admin menus
		$this->loader->add_action('admin_menu', $act_plugin_admin, 'admin_menus', 11);

		// Template Selector Metabox
		$this->loader->add_action('add_meta_boxes', $act_plugin_admin, 'boxes' );

		// Save page template on template save (confusing wording I know)
		$this->loader->add_action('save_post', $act_plugin_admin, 'save_template', 10, 1 );

		// Redirect to settings on first activate
		$this->loader->add_action('admin_init', $act_plugin_admin, 'redirect_on_first_activate' );

		// Actually load templates, yo
		$this->loader->add_filter('default_excerpt', $act_plugin_admin, 'template_load', 1, 2);

		$this->loader->add_action('admin_init', $this, 'sct_upgrade', 1, 1);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Simple_Content_Templates_Public( $this->get_Simple_Content_Templates(), $this->get_version(), $this );

		// Register Post Type
		$this->loader->add_action('init', $plugin_public, 'register_post_type', 0 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();

		do_action('act_loaded');
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_Simple_Content_Templates() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Simple_Content_Templates_Loader    Orchestrates the hooks of the plugin.
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


	/**
	 * activate function.
	 *
	 * @access public
	 * @return void
	 */
	function activate() {
		if (  get_option('spt_version', false) === false ) {
			$this->update_setting('act_first_activate', true);

			// Default Post Setting
			$this->add_setting('act_post_type_settings', array(
				'post' => array(
					'show_ui' => true,
				)
			));
		}

		update_option('spt_version', CGD_SCT_VERSION);
	}

	/**
	 * convert simple content template to ACT
	 *
	 * @access public
	 * @return void
	 */
	function convert_sct_to_act() {
		global $wpdb;

		// Make sure we have a table from the old version
		if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}simple_post_templates'") !== "{$wpdb->prefix}simple_post_templates" ) return;

		// Make sure user is an admin
		if ( isset($_GET['force_act_convert']) && ! current_user_can('manage_options') ) return;

		$old_templates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}simple_post_templates");

		foreach($old_templates as $ot) {
			$post = array();
			$post['post_type'] = $this->post_type;
			$post['post_status'] = 'publish';
			$post['post_title'] = $ot->title;
			$post['post_content'] = $ot->content;
			$post['post_excerpt'] = $ot->excerpt;

			$result = wp_insert_post($post);
		}

		$this->update_setting('act_converted_spt', true);
	}

	/**
	 * deactivate function.
	 *
	 * @access public
	 * @return void
	 */
	function deactivate() {
	}


	/**
	 * get_php_enabled function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_php_enabled() {
		return apply_filters('act_enable_php_in_templates', false);
	}

	/**
	 * get_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_post_types() {
		$post_type_objects = array();
		$post_types = get_post_types( array('show_ui' => true) );
		unset($post_types['attachment']);

		foreach($post_types as $pt) {
			if ( $pt == $this->post_type ) continue;
			$post_type_objects[] = get_post_type_object($pt);
		}

		return $post_type_objects;
	}

	/**
	 * Upgrade SCT <= 2.0.x to 2.1.x
	 * @return void
	 */
	function sct_upgrade() {
		global $wpdb;

		// If table doesn't exist, bail
		if ( $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}simple_post_templates'") !== "{$wpdb->prefix}simple_post_templates" ) return;

		// Force ACT convert
		if ( isset($_GET['force_act_convert']) ) {
			$this->convert_sct_to_act();
		}

		if( $this->get_setting('act_converted_spt') === false && get_option('spt_version', false) !== false ) {
			$old_templates = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}simple_post_templates");

			if ( ! empty($old_templates) ) {
				$this->convert_sct_to_act();
			}
		}
	}
}

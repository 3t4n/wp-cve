<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://miniorange.com/
 * @since      1.1.1
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.1
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 * @author     test <test@test.com>
 */
class Media_Restriction {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      Media_Restriction_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.1.1
	 */
	public function __construct() {

		if ( defined( 'MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION' ) ) {
			$this->version = MO_MEDIA_RESTRICTION_PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.1.1';
		}
		$this->plugin_name = 'prevent-file-access';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Media_Restriction_Loader. Orchestrates the hooks of the plugin.
	 * - Media_Restriction_I18n. Defines internationalization functionality.
	 * - Media_Restriction_Admin. Defines all hooks for the admin area.
	 * - Media_Restriction_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-media-restriction-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-media-restriction-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-media-restriction-admin.php';

		$this->loader = new Media_Restriction_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Media_Restriction_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Media_Restriction_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.1.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Media_Restriction_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		add_action( 'admin_menu', array( $this, 'miniorange_media_menu' ) );
		add_action( 'admin_init', array( $this, 'miniorange_media_init' ) );
	}

	/**
	 * Initialize the plugin dectivation form.
	 *
	 * @return void
	 */
	public function miniorange_media_init() {
		$plugin_admin = new Media_Restriction_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin->mo_media_restrict_support();
	}

	/**
	 * Added menu to WordPress dashboard.
	 *
	 * @return void
	 */
	public function miniorange_media_menu() {
		// Add miniOrange plugin to the menu.
		$page = add_menu_page( 'Prevent Files / Folders Access' . __( 'Configure Prevent Files / Folders Access', 'mo_media_restrict' ), 'Prevent Files /	 Folders Access', 'administrator', 'mo_media_restrict', array( $this, 'mo_media_restrict_options' ), plugin_dir_url( __FILE__ ) . '../admin/images/miniorange.png' );
	}

	/**
	 * Initiate the function for post data handle.
	 *
	 * @return void
	 */
	public function mo_media_restrict_options() {
		$plugin_admin = new Media_Restriction_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin->mo_media_restrict_page();
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.1.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.1.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.1.1
	 * @return    Media_Restriction_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.1.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

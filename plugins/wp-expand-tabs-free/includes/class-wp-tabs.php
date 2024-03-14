<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
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
 * @since      2.0.0
 * @package    WP_Tabs
 * @subpackage WP_Tabs/includes
 * @author     ShapedPlugin <help@shapedplugin.com>
 */
class SP_WP_Tabs_Free {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      WP_Tabs_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
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
	 * @since    2.0.0
	 */
	public function __construct() {

		if ( defined( 'WP_TABS_VERSION' ) ) {

			$this->version = WP_TABS_VERSION;
		} else {

			$this->version = '2.0.0';
		}
		$this->plugin_name = 'wp-expand-tabs-free';

		$this->load_dependencies();
		// $this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Tabs_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Tabs_i18n. Defines internationalization functionality.
	 * - WP_Tabs_Admin. Defines all hooks for the admin area.
	 * - WP_Tabs_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-updates.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-i18n.php';

		/**
		 * The class responsible for custom post type
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-cpt.php';

		/**
		 * The class responsible for admin sub-menu
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-admin-menu.php';

		/**
		 * The class responsible for Export and Import.
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-tabs-import-export.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-tabs-admin.php';

		/**
		 * The class responsible for help page
		 */
		require_once WP_TABS_PATH . 'admin/help-page/help.php';

		/**
		 * The class responsible for shortcode
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-tabs-shortcode.php';

		/**
		 * The class live preview.
		 */
		require_once WP_TABS_PATH . 'admin/preview/class-wp-tabs-preview.php';

		/**
		 * The class Elementor shortcode addons..
		 */
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			require_once WP_TABS_PATH . 'admin/ElementorAddons/class-wp-tabs-elementor-addons.php';
		}

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-tabs-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/notices/review.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/class-wp-tabs-widget.php';

		$this->loader = new WP_Tabs_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Tabs_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Tabs_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		// Plugin admin styles n scripts.
		$plugin_admin = new WP_Tabs_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_wptabs_widget' );
		$this->loader->add_action( 'admin_action_sp_duplicate_tabs', $plugin_admin, 'duplicate_wp_tabs' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'sp_duplicate_tabs_link', 10, 2 );

		// Plugin admin custom post types.
		$plugin_admin_cpt = new WP_Tabs_CPT( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_admin_cpt, 'sptpro_post_type' );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin_cpt, 'sptpro_updated_messages', 10, 2 );
		$this->loader->add_filter( 'manage_sp_wp_tabs_posts_columns', $plugin_admin_cpt, 'sptpro_admin_column' );
		$this->loader->add_action( 'manage_sp_wp_tabs_posts_custom_column', $plugin_admin_cpt, 'sptpro_admin_field', 10, 2 );

		// Plugin admin menus.
		$plugin_admin_menu = new WP_Tabs_Admin_Menu( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin_menu, 'sptpro_plugin_action_links', 10, 2 );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin_menu, 'sptpro_review_text', 10, 2 );
		$this->loader->add_filter( 'update_footer', $plugin_admin_menu, 'sptpro_version_text', 11 );
		// Redirect after active.
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'sp_tabs_redirect_after_activation', 10, 2 );

		$plugin_review_notice = new WP_Tabs_Review( WP_TABS_NAME, WP_TABS_VERSION );
		$this->loader->add_action( 'admin_notices', $plugin_review_notice, 'display_admin_notice' );
		$this->loader->add_action( 'wp_ajax_sp-wptabs-never-show-review-notice', $plugin_review_notice, 'dismiss_review_notice' );

		// Export Import.
		$import_export = new Wp_Tabs_Import_Export( WP_TABS_NAME, WP_TABS_VERSION );
		$this->loader->add_action( 'wp_ajax_tabs_export_shortcode', $import_export, 'export_shortcode' );
		$this->loader->add_action( 'wp_ajax_tabs_import_shortcode', $import_export, 'import_shortcode' );

		/**
		 * Gutenberg block.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			require_once WP_TABS_PATH . 'admin/class-wp-tabs-free-gutenberg-block.php';
			new WP_Tabs_Free_Gutenberg_Block();
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Plugin public enqueue.
		$plugin_public = new WP_Tabs_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'admin_enqueue_styles' );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'register_all_scripts' );

		$this->loader->add_filter( 'sp_wp_tabs_content', $plugin_public, 'sp_wp_tabs_markdown_to_html' );

		// Add Shortcode.
		$plugin_shortcode = new WP_Tabs_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'sptpro_action_tag_for_shortcode', $plugin_shortcode, 'sptpro_shortcode_execute' );
		add_shortcode( 'wptabs', array( $plugin_shortcode, 'sptpro_shortcode_execute' ) );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     2.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     2.0.0
	 * @return    WP_Tabs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

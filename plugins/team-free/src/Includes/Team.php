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
 * @since      2.0.0
 * @package   WP_Team
 * @subpackage WP_Team/Includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Includes;

use ShapedPlugin\WPTeam\Admin\Admin;
use ShapedPlugin\WPTeam\Frontend\Frontend;
use ShapedPlugin\WPTeam\Includes\Loader;
use ShapedPlugin\WPTeam\Includes\WP_Team_i18n;
use ShapedPlugin\WPTeam\Admin\Team_Import_Export;
use ShapedPlugin\WPTeam\Admin\WP_Team_Gutenberg_Block;
use ShapedPlugin\WPTeam\Admin\HelpPage\Help;
// use ShapedPlugin\WPTeam\Admin\Helper\Team_Premium;
use ShapedPlugin\WPTeam\Admin\Helper\Review_Notice;
use ShapedPlugin\WPTeam\Admin\Team_Element_Shortcode_Block;
use ShapedPlugin\WPTeam\Admin\Team_Element_Shortcode_Block_Deprecated;

/**
 * The Main file class of the plugin.
 */
class Team {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$active_plugins = get_option( 'active_plugins' );
		foreach ( $active_plugins as $active_plugin ) {
			$_temp = strpos( $active_plugin, 'team-free.php' );
			if ( false != $_temp ) {
				add_filter( 'plugin_action_links_' . $active_plugin, array( $this, 'add_generator_links' ) );
			}
		}
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Create team link at plugins bottom.
	 *
	 * @since 2.0.0
	 * @param string $links links probived by WordPress.
	 */
	public function add_generator_links( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'post-new.php?post_type=sptp_generator' ) . '">' . __( 'Create Team', 'team-free' ) . '</a>',
		);
		$links[] = '<a href="https://getwpteam.com/pricing/?ref=1" style="color: #35b747; font-weight: 700;">' . __( 'Go Premium!', 'team-free' ) . '</a>';
		return array_merge( $mylinks, $links );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Team_Pro_Loader. Orchestrates the hooks of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    2.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Team_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Team_i18n();

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

		$plugin_admin = new Admin( SPT_PLUGIN_SLUG, SPT_PLUGIN_VERSION );
		// Help Page.
		Help::instance();
		$review_notice = new Review_Notice( SPT_PLUGIN_SLUG, SPT_PLUGIN_VERSION );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_print_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'sptp_member_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'sptp_generator_post_type' );
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'hide_publishing_actions' );
		$this->loader->add_action( 'admin_head-post-new.php', $plugin_admin, 'hide_publishing_actions' );
		$this->loader->add_action( 'manage_sptp_member_posts_custom_column', $plugin_admin, 'get_member_columns', 10, 2 );
		$this->loader->add_action( 'manage_sptp_generator_posts_custom_column', $plugin_admin, 'get_generator_columns', 10, 2 );

		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_wpteam_widget' );
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'redirect_to_help', 10, 2 );

		$this->loader->add_filter( 'manage_sptp_member_posts_columns', $plugin_admin, 'set_member_columns' );
		$this->loader->add_filter( 'manage_sptp_generator_posts_columns', $plugin_admin, 'set_generator_columns' );
		$this->loader->add_filter( 'enter_title_here', $plugin_admin, 'member_name' );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'sptp_review_text', 10, 2 );
		$this->loader->add_filter( 'update_footer', $plugin_admin, 'sptp_version_text', 11 );
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'sptp_update', 10, 1 );

		// Export import.
		$import_export = new Team_Import_Export( SPT_PLUGIN_NAME, SPT_PLUGIN_VERSION );

		$this->loader->add_action( 'wp_ajax_SPT_export_shortcodes', $import_export, 'export_shortcodes' );
		$this->loader->add_action( 'wp_ajax_SPT_import_shortcodes', $import_export, 'import_shortcodes' );

		// Review notice for the plugin.
		$this->loader->add_action( 'admin_notices', $review_notice, 'display_admin_notice' );
		$this->loader->add_action( 'wp_ajax_sp-wpt-never-show-review-notice', $review_notice, 'dismiss_review_notice' );

		// Gutenberg block.
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new WP_Team_Gutenberg_Block();
		}

		// Elementor shortcode block.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Team_Element_Shortcode_Block();
			new Team_Element_Shortcode_Block_Deprecated();
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

		$plugin_public = new Frontend( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 120 );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'register_all_scripts' );
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
	 * @return    Loader    Orchestrates the hooks of the plugin.
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

<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/includes
 */

namespace Codexin\ImageMetadataSettings;

use Codexin\ImageMetadataSettings\Admin\Notice;
use Codexin\ImageMetadataSettings\Admin\Media;
use Codexin\ImageMetadataSettings\Admin\Admin_Ajax;

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
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/includes
 * @author     Your Name <email@codexin.com>
 */
class Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Codexin_Image_Metadata_Settings_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $pluginname    The string used to uniquely identify this plugin.
	 */
	protected $pluginname = 'media-library-helper';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version = '1.3.0';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new I18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );
		$plugin_i18n->load_plugin_textdomain();

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this );
		$media        = new Media();
		$notice       = new Notice();
		$admin_ajax   = new Admin_Ajax();
		//define admin styles and scripts hook
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//definr admin_ajax hooks
		$this->loader->add_action( 'wp_ajax_cdxn_mlh_attachment_save_bulk', $admin_ajax, 'attachment_save_bulk_edit' );
		$this->loader->add_action( 'wp_ajax_image_metadata', $admin_ajax, 'image_metadata' );
		// define media hooks
		$this->loader->add_filter( 'manage_media_columns', $media, 'custom_column' );
		$this->loader->add_action( 'manage_media_custom_column', $media, 'display_column_value', 10, 2 );
		$this->loader->add_filter( 'manage_upload_sortable_columns', $media, 'sortable_columns' );
		$this->loader->add_filter( 'posts_clauses', $media, 'manage_media_sortable_columns', 1, 2 );
		$this->loader->add_filter( 'request', $media, 'alt_column_orderby', 20, 2 );
		$this->loader->add_action( 'restrict_manage_posts', $media, 'search_box', 10, 2 );
		$this->loader->add_action( 'pre_get_posts', $media, 'media_filter' );
		$this->loader->add_filter( 'posts_join', $media, 'search_join_table', 20, 2 );
		$this->loader->add_filter( 'posts_where', $media, 'search_where_table', 20, 2 );

		$this->loader->add_action( 'load-upload.php', $media, 'add_bulk_action_export_to_list_media' );
		//define notice hooks
		$this->loader->add_action( 'admin_notices', $notice, 'notice' );
		$this->loader->add_action( 'wp_ajax_rate_the_plugin', $notice, 'rate_the_plugin_action' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->define_admin_hooks();
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
		return $this->pluginname;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Codexin_Image_Metadata_Settings_Loader    Orchestrates the hooks of the plugin.
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

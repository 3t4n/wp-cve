<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wenthemes.com
 * @since      1.0.0
 *
 * @package    Wen_Featured_Image
 * @subpackage Wen_Featured_Image/includes
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
 * @since      1.0.0
 * @package    Wen_Featured_Image
 * @subpackage Wen_Featured_Image/includes
 * @author     WEN Themes <info@wenthemes.com>
 */
class Wen_Featured_Image {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wen_Featured_Image_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wen_featured_image    The string used to uniquely identify this plugin.
	 */
	protected $wen_featured_image;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Default plugin options.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $defaults    Default plugin options.
   */
  protected $defaults;

	/**
	 * Plugin options.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $defaults    Plugin options.
	 */
	protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->wen_featured_image = 'wen-featured-image';
		$this->version = WEN_FEATURED_IMAGE_VERSION;

	    $this->load_dependencies();
	    $this->set_locale();
	    $this->set_default_options();
	    $this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wen_Featured_Image_Loader. Orchestrates the hooks of the plugin.
	 * - Wen_Featured_Image_i18n. Defines internationalization functionality.
	 * - Wen_Featured_Image_Admin. Defines all hooks for the admin area.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-featured-image-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wen-featured-image-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wen-featured-image-admin.php';

		$this->loader = new Wen_Featured_Image_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wen_Featured_Image_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wen_Featured_Image_i18n();
		$plugin_i18n->set_domain( $this->get_wen_featured_image() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

  private function set_default_options(){

    $this->defaults = $this->get_default_plugin_options();
    $this->options  = $this->get_plugin_options();

  }
  private function get_default_plugin_options(){

    $output = array(
      'image_column_cpt' => array( 'post', 'page' ),
      'required_cpt'     => array(),
      'message_cpt'      => array( 'post', 'page' ),
      'required_message' => __( 'Featured Image is required to publish.', 'wen-featured-image' ),
      'message_before'   => '',
      'message_after'    => '',
    );
    return $output;

  }
  public function get_plugin_options(){

    return get_option( 'wen_featured_image_options', $this->get_default_plugin_options() );

  }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

    $plugin_admin = new Wen_Featured_Image_Admin( $this->get_wen_featured_image(), $this->get_version(), $this->get_plugin_options() );

    // Admin notices
    $this->loader->add_action( 'admin_notices', $plugin_admin, 'wfi_admin_notices' );

    // Support for Post Thumbnails
    $this->loader->add_action( 'after_setup_theme', $plugin_admin, 'check_theme_support' );

    // Plugin Options
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'setup_menu' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

    $post_types = array();
    if ( isset( $this->options['image_column_cpt'] ) ) {
      $post_types = $this->options['image_column_cpt'];
    }
    if ( ! empty( $post_types )) {
      foreach ($post_types as $key => $p ) {

        $this->loader->add_filter( 'manage_' . $p . '_posts_columns', $plugin_admin, 'posts_column_head' );
        $this->loader->add_action( 'manage_' . $p . '_posts_custom_column', $plugin_admin, 'posts_column_content', 10, 2 );

      }
    }

    // AJAX handling
    // Add
    $this->loader->add_action( 'wp_ajax_nopriv_wfi-add-featured-image', $plugin_admin, 'ajax_add_featured_image' );
    $this->loader->add_action( 'wp_ajax_wfi-add-featured-image', $plugin_admin, 'ajax_add_featured_image' );
    // Remove
    $this->loader->add_action( 'wp_ajax_nopriv_wfi-remove-featured-image', $plugin_admin, 'ajax_remove_featured_image' );
    $this->loader->add_action( 'wp_ajax_wfi-remove-featured-image', $plugin_admin, 'ajax_remove_featured_image' );
    // Change
    $this->loader->add_action( 'wp_ajax_nopriv_wfi-change-featured-image', $plugin_admin, 'ajax_change_featured_image' );
    $this->loader->add_action( 'wp_ajax_wfi-change-featured-image', $plugin_admin, 'ajax_change_featured_image' );

    // Template filtering
    $this->loader->add_filter( 'wen_featured_image_filter_block_template', $plugin_admin, 'custom_block_template' );

    // Message hooks
    $this->loader->add_filter( 'admin_post_thumbnail_html', $plugin_admin, 'custom_message_admin_featured_box', 10, 2 );

    // Save hook
    $this->loader->add_action( 'save_post', $plugin_admin, 'wfi_required_thumbnail_check' );
    $this->loader->add_filter( 'redirect_post_location', $plugin_admin, 'custom_redirect_post_location', 10, 2 );

    // Settings links in plugin listing
    $this->loader->add_filter( "plugin_action_links_" . WEN_FEATURED_IMAGE_BASE_FILE , $plugin_admin, 'add_links_in_plugin_listing' );

    // Select for filtering
    $this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'wfi_table_filtering' );
    // Query manipulation according to selected value
    $this->loader->add_filter( 'parse_query', $plugin_admin, 'wfi_query_filtering' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_wen_featured_image() {
		return $this->wen_featured_image;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wen_Featured_Image_Loader    Orchestrates the hooks of the plugin.
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

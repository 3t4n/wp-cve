<?php
namespace OACS\SolidPostLikes\Controllers\App;

use OACS\SolidPostLikes\Controllers\App\SolidPostLikesLoader; // All actions and filters
use OACS\SolidPostLikes\Controllers\App\SolidPostLikesI18n; // language
use OACS\SolidPostLikes\Views\SolidPostLikesAdmin; // admin settings
use OACS\SolidPostLikes\Views\SolidPostLikesPublic; // views output
use OACS\SolidPostLikes\Controllers\SolidPostLikesProcess; // like dislike process
use OACS\SolidPostLikes\Views\SolidPostLikesWoo;
use OACS\SolidPostLikes\Views\SolidPostLikesPosts;
use OACS\SolidPostLikes\Views\SolidPostLikesUserProfile;
use OACS\SolidPostLikes\Views\SolidPostLikesComments;
use OACS\SolidPostLikes\Controllers\SolidPostLikesSetter;
use OACS\SolidPostLikes\Controllers\SolidPostLikesGetter;

if ( ! defined( 'WPINC' ) ) { die; }
/**
 * The core plugin class.
 */
class SolidPostLikesPlugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      SolidPostLikesLoader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SOLID_POST_LIKES_VERSION' ) ) {
			$this->version = SOLID_POST_LIKES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'solid-post-likes';


		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_process_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Solid_Post_Likes_Loader. Orchestrates the hooks of the plugin.
	 * - Solid_Post_Likes_i18n. Defines internationalization functionality.
	 * - Solid_Post_Likes_Admin. Defines all hooks for the admin area.
	 * - Solid_Post_Likes_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new SolidPostLikesLoader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the SolidPostLikesI18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new SolidPostLikesI18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'oacs_spl_load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new SolidPostLikesAdmin( $this->get_plugin_name(), $this->get_version() );
		$plugin_user_profile = new SolidPostLikesUserProfile( $this->get_plugin_name(), $this->get_version() );
		$plugin_like_setter = new SolidPostLikesSetter();

		$this->loader->add_action( 'show_user_profile', $plugin_user_profile, 'oacs_spl_show_user_likes' );
		if ( ! shortcode_exists( 'oacs_spl_profile' ) ) {
		$this->loader->add_shortcode( 'oacs_spl_profile', $plugin_user_profile, 'oacs_spl_show_user_likes' );
		}

		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'oacs_load_carbon_fields' );
		$this->loader->add_action( 'carbon_fields_register_fields', $plugin_admin, 'oacs_add_plugin_settings_page' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'carbon_fields_theme_options_container_saved', $plugin_like_setter, 'oacs_spl_set_like_count' );

		$this->loader->add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ttt_wpmdr_add_action_plugin', 10,5 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {


		$plugin_public = new SolidPostLikesPublic( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		if ( ! shortcode_exists( 'oacsspl' ) ) {
		$this->loader->add_shortcode( 'oacsspl', $plugin_public, 'oacs_spl_display_like_shortcode' );
		}
		if ( ! shortcode_exists( 'oacsspllist' ) ) {
		$this->loader->add_shortcode( 'oacsspllist', $plugin_public, 'oacs_spl_display_like_postlist_shortcode' );
		}
		$comment_likes = get_option('_oacs_spl_likes_for_comments_setting');
		$plugin_comment_likes = new SolidPostLikesComments( $this->get_plugin_name(), $this->get_version() );

		($comment_likes) ? ($this->loader->add_filter('comment_text', $plugin_comment_likes, 'oacs_spl_display_like_position_comments', 100)) : ('');

		$post_hook = get_option('_oacs_spl_hook_post_hook');
		$plugin_public_posts = new SolidPostLikesPosts( $this->get_plugin_name(), $this->get_version() );

		(!empty($post_hook)) ?
		($this->loader->add_action( $post_hook, $plugin_public_posts, 'oacs_spl_display_post_likes_hook')) :
		$this->loader->add_filter('the_content', $plugin_public_posts, 'oacs_spl_display_like_position', 30);


		$plugin_public_woo = new SolidPostLikesWoo( $this->get_plugin_name(), $this->get_version() );
		$woo_action = get_option('_oacs_spl_hook_woo_hook');
		(!empty($action)) ?
            ($this->loader->add_action($woo_action, $plugin_public_woo, 'oacs_spl_display_product_likes')) : ($this->loader->add_action('woocommerce_single_product_summary', $plugin_public_woo, 'oacs_spl_display_product_likes'));
	}

	/**
	 * Register all of the hooks related to the process-related functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_process_hooks()
	{
		$plugin_public = new SolidPostLikesProcess( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_nopriv_oacs_spl_process_like', $plugin_public, 'oacs_spl_process_like' );
		$this->loader->add_action( 'wp_ajax_oacs_spl_process_like', $plugin_public, 'oacs_spl_process_like' );

		$oacs_spl_like_cache_support_setting       = get_option('_oacs_spl_cache_support') ?? '';

		if($oacs_spl_like_cache_support_setting === 'yes')
		{
			$plugin_getter = new SolidPostLikesGetter( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'wp_ajax_nopriv_oacs_spl_get_like_info', $plugin_getter, 'oacs_spl_get_like_info' );
			$this->loader->add_action( 'wp_ajax_oacs_spl_get_like_info', $plugin_getter, 'oacs_spl_get_like_info' );
		}

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
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    SolidPostLikesLoader    Orchestrates the hooks of the plugin.
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
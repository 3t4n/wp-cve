<?php
/**
 * Init class from RockConvert.
 *
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @package    Rock_Convert
 */

namespace Rock_Convert\Inc\Core;

use Rock_Convert as NS;
use Rock_Convert\Inc\Admin as Admin;
use Rock_Convert\Inc\Admin\CTA\Custom_Meta_Box as Custom_Meta_Box;
use Rock_Convert\Inc\Admin\Page_Settings as Page_Settings;
use Rock_Convert\Inc\Admin\Ebook\Generator as Ebook_Generator;
use Rock_Convert\Inc\Admin\announcements\Announcement as AnnouncementsBar;
use Rock_Convert\Inc\Admin\Ebook\Meta_Box as Ebook_Meta_box;
use Rock_Convert\Inc\Admin\Ebook\Form as Download_Form;
use Rock_Convert\Inc\Admin\Widget\Subscribe_Form;
use Rock_Convert\Inc\Frontend as Frontend;

/**
 * The core plugin class.
 * Defines internationalization, admin-specific hooks, and public-facing site hooks.
 *
 * @link  https://rockcontent.com
 * @since 1.0.0
 *
 * @author Rock Content
 */
class Init {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $plugin_base_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_basename;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The text domain of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string $version The current version of the plugin.
	 */
	protected $plugin_text_domain;

	/**
	 * The admin url from css admin
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_admin_css_bundle_url The string used to enqueue admin assets.
	 */
	protected $plugin_admin_css_bundle_url;

	/**
	 * The admin url from js admin
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_admin_js_bundle_url The string used to enqueue admin assets.
	 */
	protected $plugin_admin_js_bundle_url;

	/**
	 * The frontend url from assets
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_front_css_bundle_url The string used to enqueue frontend assets.
	 */
	protected $plugin_front_css_bundle_url;

	/**
	 * The front url from js front
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_front_js_bundle_url The string used to enqueue front assets.
	 */
	protected $plugin_front_js_bundle_url;

	/**
	 * Construct Function
	 */
	public function __construct() {

		$this->plugin_name                 = PLUGIN_NAME;
		$this->version                     = PLUGIN_VERSION;
		$this->plugin_basename             = PLUGIN_BASENAME;
		$this->plugin_text_domain          = PLUGIN_TEXT_DOMAIN;
		$this->plugin_admin_css_bundle_url = RC_ADMIN_CSS_BUNDLE_PATH;
		$this->plugin_admin_js_bundle_url  = RC_ADMIN_JS_BUNDLE_PATH;
		$this->plugin_front_css_bundle_url = RC_FRONT_CSS_BUNDLE_PATH;
		$this->plugin_front_js_bundle_url  = RC_FRONT_JS_BUNDLE_PATH;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Loads the following required dependencies for this plugin.
	 *
	 * - Loader - Orchestrates the hooks of the plugin.
	 * - Internationalization_i18n - Defines internationalization functionality.
	 * - Admin - Defines all hooks for the admin area.
	 * - Frontend - Defines all hooks for the public side of the site.
	 *
	 * @access private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization_i18n( $this->plugin_text_domain );

		$this->loader->add_action(
			'plugins_loaded',
			$plugin_i18n,
			'load_plugin_textdomain'
		);

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * Callbacks are documented in inc/admin/class-admin.php
	 *
	 * @access private
	 */
	private function define_admin_hooks() {
		$plugin_admin   = new Admin\Admin(
			$this->get_plugin_name(),
			$this->get_version(),
			$this->get_plugin_text_domain(),
			$this->get_admin_css_bundle_url(),
			$this->get_admin_js_bundle_url()
		);
		$download_form  = new Download_Form();
		$page_settings  = new Page_Settings();
		$activator      = new Activator();
		$subscribe_form = new Subscribe_Form();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Register CTA post type.
		$this->loader->add_action( 'init', $plugin_admin, 'register_cta_post_type' );

		// Register Settings page.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'getting_started_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_support_submenu_link' );

		/**
		 * Handle download subscribers form
		 */
		$this->loader->add_action(
			'admin_post_nopriv_rock_convert_download_form',
			$download_form,
			'download_form_callback'
		);

		$this->loader->add_action(
			'admin_post_rock_convert_download_form',
			$download_form,
			'download_form_callback'
		);

		/**
		 * Handle Widget subscribers form
		 */
		$this->loader->add_action(
			'admin_post_nopriv_rock_convert_subscribe_form',
			$subscribe_form,
			'subscribe_form_callback'
		);

		$this->loader->add_action(
			'admin_post_rock_convert_subscribe_form',
			$subscribe_form,
			'subscribe_form_callback'
		);

		/**
		 * Handle page settings change
		 */
		$this->loader->add_action(
			'admin_post_rock_convert_settings_form',
			$page_settings,
			'save_settings_callback'
		);

		$this->loader->add_action(
			'admin_post_nopriv_rock_convert_settings_form',
			$page_settings,
			'save_settings_callback'
		);

		/**
		 * Handle export CSV feature
		 */
		$this->loader->add_action(
			'admin_post_rock_convert_export_csv',
			$page_settings,
			'export_csv_callback'
		);

		$this->loader->add_action(
			'admin_post_nopriv_rock_convert_export_csv',
			$page_settings,
			'export_csv_callback'
		);

		$this->loader->add_action(
			'plugin_action_links_rock-convert/rock-convert.php',
			$page_settings,
			'action_links'
		);

		/**
		 * Admin notices
		 */

		$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : null; //phpcs:ignore
		if ( ! Admin\Admin::analytics_enabled() && 'cta' === $post_type ) {
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'analytics_activation_notice' );
		}

		/**
		 * Load table structure
		 */
		$this->loader->add_action( 'plugins_loaded', $activator, 'table_structure_db_check' );

		new Custom_Meta_Box();
		new Ebook_Generator();
		new Ebook_Meta_Box();
		new AnnouncementsBar();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the text domain of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The text domain of the plugin.
	 */
	public function get_plugin_text_domain() {
		return $this->plugin_text_domain;
	}

	/**
	 * Retrieve the admin css url of the plugin.
	 *
	 * @since  2.11.0
	 * @return string    The admin assets url of the plugin.
	 */
	public function get_admin_css_bundle_url() {
		return $this->plugin_admin_css_bundle_url;
	}

	/**
	 * Retrieve the admin js url of the plugin.
	 *
	 * @since  2.11.0
	 * @return string    The admin assets url of the plugin.
	 */
	public function get_admin_js_bundle_url() {
		return $this->plugin_admin_js_bundle_url;
	}

	/**
	 * Retrieve the front js url of the plugin.
	 *
	 * @since  2.11.0
	 * @return string    The front assets url of the plugin.
	 */
	public function get_front_js_bundle_url() {
		return $this->plugin_front_js_bundle_url;
	}

	/**
	 * Retrieve the frontend assets url of the plugin.
	 *
	 * @since  2.11.0
	 * @return string     The frontend assets url of the plugin.
	 */
	public function get_front_css_bundle_url() {
		return $this->plugin_front_css_bundle_url;
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new Frontend\Frontend(
			$this->get_plugin_name(),
			$this->get_version(),
			$this->get_plugin_text_domain(),
			$this->get_front_css_bundle_url(),
			$this->get_front_js_bundle_url()
		);

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'rest_api_endpoint' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}

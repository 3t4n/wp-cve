<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/includes
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
 * @since      1.0
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/includes
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fathom_Analytics_Conversions_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
		if ( defined( 'FATHOM_ANALYTICS_CONVERSIONS_VERSION' ) ) {
			$this->version = FATHOM_ANALYTICS_CONVERSIONS_VERSION;
		}
		else {
			$this->version = '1.0.7';
		}
		$this->plugin_name = 'fathom-analytics-conversions';

		define( 'FAC4WP_OPTIONS', 'fac4wp-options' );
		define( 'FAC4WP_OPTION_API_KEY_CODE', 'fac-api-key-code' );
		define( 'FAC_OPTION_SITE_ID', 'fac-site-id' );
		define( 'FAC_OPTION_INSTALLED_TC', 'installed-tracking-code-elsewhere' );
		define( 'FAC_FATHOM_TRACK_ADMIN', 'fac-fathom-track-admin' );

		define( 'FAC4WP_OPTION_INTEGRATE_WPCF7', 'integrate-wpcf7' );
		define( 'FAC4WP_OPTION_INTEGRATE_WPFORMS', 'integrate-wpforms' );
		define( 'FAC4WP_OPTION_INTEGRATE_GRAVIRYFORMS', 'integrate-gravityforms' );
		define( 'FAC4WP_OPTION_INTEGRATE_FLUENTFORMS', 'integrate-fluentforms' );
		define( 'FAC4WP_OPTION_INTEGRATE_NINJAFORMS', 'integrate-ninjaforms' );
		define( 'FAC4WP_OPTION_INTEGRATE_WOOCOMMERCE', 'integrate-woocommerce' );

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
	 * - Fathom_Analytics_Conversions_Loader. Orchestrates the hooks of the plugin.
	 * - Fathom_Analytics_Conversions_i18n. Defines internationalization functionality.
	 * - Fathom_Analytics_Conversions_Admin. Defines all hooks for the admin area.
	 * - Fathom_Analytics_Conversions_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fathom-analytics-conversions-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fathom-analytics-conversions-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the plugin Contact Form 7
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-wpcf7.php';

		/**
		 * The class responsible for defining all actions that occur in the plugin WPForms
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-wpforms.php';

		/**
		 * The class responsible for defining all actions that occur in the plugin Gravity Forms
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-gravityforms.php';

		/**
		 * The class responsible for defining all actions that occur in the URL-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-url.php';

		/**
		 * The class responsible for defining all actions that occur in the fluentform-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-fluentform.php';

		/**
		 * The class responsible for defining all actions that occur in the ninja-forms-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-ninja-forms.php';

		/**
		 * The class responsible for defining all actions that occur in the woocommerce-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-woocommerce.php';

		/**
		 * The class responsible for defining all actions that occur in the standard WP login/registration-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fac-wp-login-registration.php';

		/**
		 * The class responsible for defining all actions that occur in the standard WP login/registration-specific functionality
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fac-classes-ids.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-fathom-analytics-conversions-public.php';

        /**
         * The class responsible for defining all actions that occur in the tracking functionality
         * side of the site.
         */
        //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fathom-analytics-conversions-appsero.php';
		//new Fathom_Analytics_Conversions_Appsero();

		/**
		 * The core functions available on both the front-end and admin
		 */
		require_once FAC4WP_PATH . '/includes/fac-core-functions.php';

		$this->loader = new Fathom_Analytics_Conversions_Loader();



	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Fathom_Analytics_Conversions_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Fathom_Analytics_Conversions_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin   = new Fathom_Analytics_Conversions_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_wpcf7   = new Fathom_Analytics_Conversions_WPCF7( $this->get_plugin_name(), $this->get_version() );
		$plugin_wpforms = new Fathom_Analytics_Conversions_WPForms( $this->get_plugin_name(), $this->get_version() );
		$plugin_gf      = new Fathom_Analytics_Conversions_GravityForms( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_URL( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_Fluent_Form( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_Ninja_Forms( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_Woocommerce( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_WP( $this->get_plugin_name(), $this->get_version() );
		new Fathom_Analytics_Conversions_Classes_IDs( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		define( 'FAC4WP_ADMINSLUG', 'fac4wp-settings' );
		define( 'FAC4WP_ADMIN_GROUP', 'fac4wp-admin-group' );

		define( 'FAC4WP_ADMIN_GROUP_GENERAL', 'fac4wp-admin-group-general' );
		define( 'FAC4WP_ADMIN_GROUP_API_KEY', 'fac4wp-admin-group-api-key' );
		define( 'FAC4WP_ADMIN_GROUP_SITE_ID', 'fac4wp-admin-group-site-id' );
		define( 'FAC4WP_ADMIN_GROUP_INTEGRATION', 'fac4wp-admin-group-integration' );

		define( 'FAC4WP_PHASE_STABLE', 'fac4wp-phase-stable' );

		// Admin settings/sections.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'fac4wp_admin_init' );
		// Admin menu page.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'fac_admin_menu' );
		// Admin notices.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'fac_admin_notices' );

		// Add meta box to CF7 form admin.
		$this->loader->add_filter( 'wpcf7_editor_panels', $plugin_wpcf7, 'fac_cf7_meta_box' );
		// Save FAC CF7 options.
		$this->loader->add_action( 'wpcf7_after_save', $plugin_wpcf7, 'fac_cf7_save_options' );

		// Check to add/update event id to new cf7 form.
		$this->loader->add_action( 'wpcf7_after_save', $plugin_wpcf7, 'fac_wpcf7_after_save', 20 );

		// Add settings section to WPForms form admin.
		$this->loader->add_filter( 'wpforms_builder_settings_sections', $plugin_wpforms, 'fac_wpforms_builder_settings_sections', 8 );
		// FAC custom panel.
		$this->loader->add_action( 'wpforms_form_settings_panel_content', $plugin_wpforms, 'fac_wpforms_form_settings_panel_content' );

		// Check to add event id to new WPForms form.
		$this->loader->add_action( 'wp_insert_post', $plugin_wpforms, 'fac_wp_insert_post_wpforms', 10, 3 );

		// Add settings tab to Gravity Forms form admin.
		$this->loader->add_filter( 'gform_form_settings_menu', $plugin_gf, 'fac_gform_form_settings_menu' );
		// Render setting page.
		$this->loader->add_filter( 'gform_form_settings_page_fac-gform', $plugin_gf, 'fac_gform_render_settings_page' );
		// Initialize whether Ajax is on or off.
		$this->loader->add_filter( 'gform_form_args', $plugin_gf, 'fac_gform_ajax_only', 15 );
		// Check to add event id to new Gravity Forms form.
		$this->loader->add_action( 'gform_after_save_form', $plugin_gf, 'fac_gform_after_save_form', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Fathom_Analytics_Conversions_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add hidden field to CF7 form - frontend.
		$this->loader->add_filter( 'wpcf7_form_hidden_fields', $plugin_public, 'fac_cf7_hidden_fields' );
		// add hidden field to WPForms form - frontend.
		$this->loader->add_action( 'wpforms_display_submit_before', $plugin_public, 'fac_wpforms_display_submit_before' );
		// add hidden field to GravityForms form - frontend.
		//$this->loader->add_action( 'gform_pre_render', $plugin_public, 'fac_gform_pre_render' );
		//$this->loader->add_action( 'gform_pre_submission_filter', $plugin_public, 'fac_gform_pre_render' );
		//$this->loader->add_action( 'gform_submit_button', $plugin_public, 'fac_gform_submit_button', 10, 2 );

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
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Fathom_Analytics_Conversions_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}

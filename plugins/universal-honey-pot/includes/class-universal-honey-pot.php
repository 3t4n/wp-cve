<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webdeclic.com
 * @since      1.0.0
 *
 * @package    Universal_Honey_Pot
 * @subpackage Universal_Honey_Pot/includes
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
 * @package    Universal_Honey_Pot
 * @subpackage Universal_Honey_Pot/includes
 * @author     Webdeclic <contact@webdeclic.com>
 */
class Universal_Honey_Pot {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Universal_Honey_Pot_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'UNIVERSAL_HONEY_POT_VERSION' ) ) {
			$this->version = UNIVERSAL_HONEY_POT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'universal-honey-pot';

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
	 * - Universal_Honey_Pot_Loader. Orchestrates the hooks of the plugin.
	 * - Universal_Honey_Pot_i18n. Defines internationalization functionality.
	 * - Universal_Honey_Pot_Admin. Defines all hooks for the admin area.
	 * - Universal_Honey_Pot_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for loading composer dependencies.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'includes/vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'includes/class-universal-honey-pot-loader.php';

		/**
		 * This file is loaded only on local environement for test or debug.
		 */
		if( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' ){
			require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH. 'includes/dev-toolkits.php';
		}
		
		/**
		 * The global functions for this plugin
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'includes/global-functions.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'includes/class-universal-honey-pot-i18n.php';

		/**
		 * The class responsible of settings.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'admin/class-settings.php';
		
		/**
		 * The class responsible of Contact form 7 support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-contact-form-7.php';

		/**
		 * The class responsible of Elementor form support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-elementor-form.php';
		
		/**
		 * The class responsible of Formidable Forms support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-formidable-forms.php';
		
		/**
		 * The class responsible of Forminator Forms support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-forminator.php';
		
		/**
		 * The class responsible of Divi Forms support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-divi-form.php';
		
		/**
		 * The class responsible of WPForms Forms support.
		 */
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/class-wpforms.php';
				
		$this->loader = new Universal_Honey_Pot_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Universal_Honey_Pot_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Universal_Honey_Pot_i18n();

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

		$universal_honey_pot_settings = new Universal_Honey_Pot_Settings( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $universal_honey_pot_settings, 'add_settings_menu' );
		$this->loader->add_action( 'activated_plugin', $universal_honey_pot_settings, 'redirect_to_settings_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$universal_honey_pot_contact_form_7 = new Universal_Honey_Pot_Contact_Form_7( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'wpcf7_form_elements', $universal_honey_pot_contact_form_7, 'add_honey_pot_fields_to_form' );
		$this->loader->add_filter( 'wpcf7_spam', $universal_honey_pot_contact_form_7, 'validate_honey_pot_fields' );
		
		$universal_honey_pot_elementor_form = new Universal_Honey_Pot_Elementor_Form( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'elementor-pro/forms/pre_render', $universal_honey_pot_elementor_form, 'add_honey_pot_fields_to_form', 10, 2 );
		$this->loader->add_action( 'elementor_pro/forms/validation', $universal_honey_pot_elementor_form, 'validate_honey_pot_fields', 10, 2 );
		
		$universal_honey_pot_formidable_forms = new Universal_Honey_Pot_Formidable_Forms( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'frm_after_title', $universal_honey_pot_formidable_forms, 'add_honey_pot_fields_to_form' );
		$this->loader->add_filter( 'frm_validate_entry', $universal_honey_pot_formidable_forms, 'validate_honey_pot_fields', 10, 2 );

		$universal_honey_pot_forminator = new Universal_Honey_Pot_Forminator( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'forminator_render_button_markup', $universal_honey_pot_forminator, 'add_honey_pot_fields_to_form' );
		$this->loader->add_filter( 'forminator_render_button_disabled_markup', $universal_honey_pot_forminator, 'add_honey_pot_fields_to_form' );
		$this->loader->add_filter( 'forminator_custom_form_submit_errors', $universal_honey_pot_forminator, 'validate_honey_pot_fields', 10, 3 );
		
		$universal_honey_pot_divi_form = new Universal_Honey_Pot_Divi_Form( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'et_pb_module_content', $universal_honey_pot_divi_form, 'add_honey_pot_fields_to_form', 10, 4 );
		$this->loader->add_filter( 'et_head_meta', $universal_honey_pot_divi_form, 'validate_honey_pot_fields' );
		
		$universal_honey_pot_wpforms = new Universal_Honey_Pot_Wpforms( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'wpforms_frontend_output', $universal_honey_pot_wpforms, 'add_honey_pot_fields_to_form', 10, 2 );
		$this->loader->add_action( 'wpforms_process_complete', $universal_honey_pot_wpforms, 'validate_honey_pot_fields', 10, 4 );
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
	 * @return    Universal_Honey_Pot_Loader    Orchestrates the hooks of the plugin.
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

<?php

/**
 * The core plugin class.
 */
class idivi_post_settings {

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		$this->plugin_name = 'i-divi_post_settings';
		$this->version = '1.3.3';

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - class-divi-post-settings-loader. Orchestrates the hooks of the plugin.
	 * - class-divi-post-settings-admin. Defines all hooks for the admin area.
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-divi-post-settings-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-divi-post-settings-admin.php';

		$this->loader = new idivi_post_settings_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks() {

		$plugin_admin = new idivi_post_settings_Admin( $this->get_plugin_name(), $this->get_version() );
		$post_id = get_the_ID();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

        $this->loader->add_action( 'admin_notices', $plugin_admin, 'inform_user' );
		$this->loader->add_action( 'wp_ajax_idivi_dismiss', $plugin_admin, 'process_ajax' );

		$this->loader->add_action( 'customize_register', $plugin_admin, 'post_settings_options' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'remove_metabox' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'idivi_add_custom_metabox' );
		
		//$this->loader->add_action( 'the_post', $plugin_admin, 'set_initial_theme_mods_values');		

		$this->loader->add_filter( 'et_builder_page_settings_modal_toggles', $plugin_admin, 'idivi_add_page_toggles' );
		$this->loader->add_filter( 'et_builder_page_settings_definitions', $plugin_admin, 'idivi_add_page_settings' );
		$this->loader->add_filter( 'et_builder_page_settings_values', $plugin_admin, 'idivi_save_page_settings', $post_id );

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
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

}

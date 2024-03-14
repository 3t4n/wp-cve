<?php

/* The core plugin class. */

class GSPS {

	/* The loader that's responsible for maintaining and registering all hooks that power the plugin. */
	protected $loader;

	/* The unique identifier of this plugin. */
	protected $plugin_name;

	/* The current version of the plugin. */
	protected $version;

	/* Define the core functionality of the plugin. */
	public function __construct() {
		if ( defined( 'GSPS_VERSION' ) ) {
			$this->version = GSPS_VERSION;
		} else {
			$this->version = '1.4.0';
		}
		$this->plugin_name = 'genesis-simple-page-sections';

		$this->load_dependencies();
		$this->define_public_hooks();
	}

	/* Load the required dependencies for this plugin. */
	private function load_dependencies() {
		/* The class responsible for orchestrating the actions and filters of the core plugin. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-genesis-simple-page-sections-loader.php';

		/* The class responsible for defining all actions that occur in the public-facing side of the site. */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-genesis-simple-page-sections-public.php';

		$this->loader = new GSPS_Loader();
	}

	/* Register all of the hooks related to the public-facing functionality of the plugin. */
	private function define_public_hooks() {
		$plugin_public = new GSPS_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_class' );
		$this->loader->add_shortcode( 'gsps', $plugin_public, 'gsps_shortcode' );
		$this->loader->add_shortcode( 'genesis-simple-page-section', $plugin_public, 'gsps_shortcode' );
	}

	/* Run the loader to execute all of the hooks with WordPress. */
	public function run() {
		$this->loader->run();
	}

	/* The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality. */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/* The reference to the class that orchestrates the hooks with the plugin. */
	public function get_loader() {
		return $this->loader;
	}

	/* Retrieve the version number of the plugin. */
	public function get_version() {
		return $this->version;
	}
}
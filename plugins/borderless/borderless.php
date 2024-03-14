<?php

/*
Plugin Name: Borderless
Plugin URI: https://visualmodo.com/borderless/
Description: One service packed with powerful tools to help you reach your purposes.
Version: 1.5.3
Author: Visualmodo
Author URI: https://visualmodo.com
License: GPLv3 or later
Text Domain: borderless
Domain Path: /languages
*/

// don't load directly
defined( 'ABSPATH' ) || exit;


/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless Constants
/*-----------------------------------------------------------------------------------*/

define( 'BORDERLESS__VERSION', '1.5.3' );
define( 'BORDERLESS__DIR', plugin_dir_path( __FILE__ ) );
define( 'BORDERLESS__URL', plugins_url( '/', __FILE__ ) );
define( 'BORDERLESS__INC', BORDERLESS__DIR . '/includes' );
define( 'BORDERLESS__ASSETS', BORDERLESS__URL . 'assets/' );
define( 'BORDERLESS__STYLES', BORDERLESS__ASSETS . 'styles/' );
define( 'BORDERLESS__SCRIPTS', BORDERLESS__ASSETS . 'scripts/' );
define( 'BORDERLESS__LIB', BORDERLESS__ASSETS . 'lib/' );
define( 'BORDERLESS__LIBRARY__DIR', BORDERLESS__DIR . '/includes/library/' );
define( 'BORDERLESS__LIBRARY__URL', BORDERLESS__URL . 'includes/library/' );
define( 'BORDERLESS__BLOCK_EDITOR', BORDERLESS__DIR . '/modules/block-editor' );
define( 'BORDERLESS__ELEMENTOR', BORDERLESS__DIR . '/modules/elementor' );
define( 'BORDERLESS__WPBAKERY', BORDERLESS__DIR . '/modules/wpbakery' );
define( 'BORDERLESS__LIBS', BORDERLESS__DIR . '/modules/libraries' );
define( 'BORDERLESS__RELATED_POSTS', BORDERLESS__DIR . '/modules/related-posts' );

/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless Core
/*-----------------------------------------------------------------------------------*/

class Borderless {
	
	/**
	* The loader that's responsible for maintaining and registering all hooks that power
	* the plugin.
	*
	* @since    1.0.0
	* @access   protected
	* @var      Borderless_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		if ( defined( 'BORDERLESS__VERSION' ) ) {
			$this->version = BORDERLESS__VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'borderless';
		
		$this->borderless_load_dependencies();
		$this->borderless_define_public_hooks();		

	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	
	public function borderless_load_dependencies() {
		
		$options = get_option( 'borderless' );

		$theme = wp_get_theme(get_template());

		/**
		* Dashboard Page.
		*/	

		require_once( BORDERLESS__INC . "/templates/dashboard.php" );

		/**
		* License Manager.
		*/

		require_once( BORDERLESS__INC . "/templates/license.php" );
		
		/**
		* The class responsible for orchestrating the actions and filters of the
		* core plugin.
		*/		
		require_once( BORDERLESS__INC . "/class-borderless-loader.php" );

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once( BORDERLESS__INC . "/class-borderless-public.php" );
		
		require_once( BORDERLESS__INC . "/templates/system-info.php" );
		require_once( BORDERLESS__INC . "/icon-manager/icon-manager.php" );
		require_once( BORDERLESS__INC . "/custom-post-types/custom-post-types.php" );
		require_once( BORDERLESS__INC . "/svg/svg.php" );
		require_once( BORDERLESS__BLOCK_EDITOR . "/contact-information/contact-information.php" );
		require_once( BORDERLESS__BLOCK_EDITOR . "/social-icons/social-icons.php" );
		require_once( BORDERLESS__BLOCK_EDITOR . "/spacer/spacer.php" );
		require_once( BORDERLESS__ELEMENTOR . "/elementor.php" );
		require_once( BORDERLESS__WPBAKERY . "/wpbakery.php" );
		require_once( BORDERLESS__INC . "/helper.php" );
		require_once( BORDERLESS__INC . "/custom-post-types/templates.php" );
		require_once( BORDERLESS__LIBRARY__DIR . "templates/templates.php" );
		require_once( BORDERLESS__LIBRARY__DIR . "importer.php" );
		 
		if ( isset( $options['related_posts'] ) ) { 
			require_once( BORDERLESS__RELATED_POSTS . "/related-posts.php" ); 
		}
		
		$this->loader = new Borderless_Loader();
		
	}
	
	/**
	* Register all of the hooks related to the public-facing functionality
	* of the plugin.
	*
	* @since    1.0.0
	* @access   private
	*/
	private function borderless_define_public_hooks() {
		
		$plugin_public = new Borderless_Public( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		function borderless_settings_colors() {
		$options = get_option( 'borderless' );

		?>
		<style type="text/css">

			:root {
				--borderless-color-primary: <?php echo esc_attr( $options['primary_color'], '#0000FF' ); ?>;
				--borderless-secondary-primary: <?php echo esc_attr( $options['secondary_color'], '#FF6819' ); ?>;
				--borderless-color-tertiary: <?php echo esc_attr( $options['tertiary_color'], '#3FCC14' ); ?>;
			}

		</style>
		<?php

		}
		add_action( 'wp_head', 'borderless_settings_colors' );
		
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
	* @return    Borderless_Loader    Orchestrates the hooks of the plugin.
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

/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless Init
/*-----------------------------------------------------------------------------------*/

function run_borderless() {

	$plugin = new Borderless();
	$plugin->run();

}
run_borderless();
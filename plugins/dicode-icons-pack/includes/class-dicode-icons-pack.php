<?php

/**
 * @since      1.0.0
 * @package    Dicode_Icons_Pack
 * @subpackage Dicode_Icons_Pack/includes
 * @author     Designinvento <team@designinvento.net>
 */
class Dicode_Icons_Pack {

	
	protected $loader;
	protected $plugin_name;
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
		if ( defined( 'DICODE_ICONS_PACK_VERSION' ) ) {
			$this->version = DICODE_ICONS_PACK_VERSION;
		} else {
			$this->version = '1.1.1';
		}
		$this->plugin_name = 'dicode-icons-pack';

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
	 * - Dicode_Icons_Pack_Loader. Orchestrates the hooks of the plugin.
	 * - Dicode_Icons_Pack_i18n. Defines internationalization functionality.
	 * - Dicode_Icons_Pack_Admin. Defines all hooks for the admin area.
	 * - Dicode_Icons_Pack_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dicode-icons-pack-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dicode-icons-pack-i18n.php';

		require( DICODE_ICONS_PACK_PATH . 'includes/helper-functions.php' );
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dicode-icons-pack-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dicode-icons-pack-public.php';

		// Admin Init file
        if( is_admin() ){
           require( DICODE_ICONS_PACK_PATH.'admin/settings-api/settings-api-init.php' );
        }

        // Ico moon Brands icons
        if ( dicode_icons_get_option( 'dicode_icomb_icons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-brands-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-icomoon-brands.php' );
        }

        // devicons Icon
        if ( dicode_icons_get_option( 'dicode_devicons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-devicons-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-devicons.php' );
        }        

        // Elegant icon
        if ( dicode_icons_get_option( 'dicode_elegant_icons', 'dicode_icons_activation', 'on') === 'on' && !file_exists('class-elegant-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-elegant.php' );
        }

        // Elusive icons
        if ( dicode_icons_get_option( 'dicode_elusive_icons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-elusive-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-elusive.php' );
        }

        // Ico Font
        if ( dicode_icons_get_option( 'dicode_icofont_icons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-icofont-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-icofont.php' );
        }        

        // Icomoon Icon
        if ( dicode_icons_get_option( 'dicode_icomoon_icons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-icomoon-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-icomoon.php' );
        }

        // Iconic icons
        if ( dicode_icons_get_option( 'dicode_iconic_icons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-iconic-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-iconic.php' );
        }        

        // Ion icon
        if ( dicode_icons_get_option( 'dicode_ionicons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-ion-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-ionicons.php' );
        }

        // linearicons
        if ( dicode_icons_get_option( 'dicode_linearicons', 'dicode_icons_activation', 'on') === 'on' && !file_exists('class-linearicons-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-linearicons.php' );
        } 
        
        // Line Awesome Icon
        if ( dicode_icons_get_option( 'dicode_lineawesome', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-lineawesome-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-lineawesome.php' );
        }

        // Line icon
        if ( dicode_icons_get_option( 'dicode_lineicons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-line-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-lineicons.php' );
        }    

        // Material Design Icon
        if ( dicode_icons_get_option( 'material_icon', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-materialdesign-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-material-icons.php' );
        }

        // Open Iconic icons
        if ( dicode_icons_get_option( 'dicode_open_iconic', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-open-iconic-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-open-iconic.php' );
        }

        // simpleline icon
        if ( dicode_icons_get_option( 'dicode_simple_lineicons', 'dicode_icons_activation', 'off') === 'on' && !file_exists('class-simpleline-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-simple-lineicons.php' );
        }      
        
        // themify icon
        if ( dicode_icons_get_option( 'dicode_themify_icons', 'dicode_icons_activation', 'on') === 'on' && !file_exists('class-themify-icon.php') ){
            require( DICODE_ICONS_PACK_PATH . 'includes/icons/class-themify.php' );
        }
		
		$this->loader = new Dicode_Icons_Pack_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dicode_Icons_Pack_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dicode_Icons_Pack_i18n();

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

		$plugin_admin = new Dicode_Icons_Pack_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dicode_Icons_Pack_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Dicode_Icons_Pack_Loader    Orchestrates the hooks of the plugin.
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

<?php
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
 * @package    Kitpack_Lite
 * @subpackage Kitpack_Lite/includes
 * @author     elementorplus <plugin@elementorplus.net>
 */
class Kitpack_Lite {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Kitpack_Lite_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * @since    2.0.0
	 */
	public function __construct() {
		if ( defined( 'KITPACK_LITE_VERSION' ) ) {
			$this->version = KITPACK_LITE_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'kitpack-lite';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
//		$this->define_public_hooks();
		$this->define_elementor_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Kitpack_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Kitpack_Lite_i18n. Defines internationalization functionality.
	 * - Kitpack_Lite_Admin. Defines all hooks for the admin area.
	 * - Kitpack_Lite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		* include plugin file
		*/
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kitpack-lite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-kitpack-lite-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-kitpack-lite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-kitpack-lite-public.php';
		
		/**
		 * The class responsible for defining all actions that occur in the elementor-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/class-kitpack-lite-elementor.php';
		

		/**
		 * The class responsible for defining all actions that occur in the elementor-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/modules/kitpack-elementor-icons.php';

		/**
		 * The class responsible for defining all actions that occur in the elementor-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/modules/kitpack-elementor-fonts.php';
		/**
		 * The class responsible for defining all actions that occur in the elementor-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/modules/kitpack-elementor-template-module.php';

		$this->loader = new Kitpack_Lite_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Kitpack_Lite_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Kitpack_Lite_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		$plugin_i18n->load_custom_textdomain();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Kitpack_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Kitpack_Lite_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}
	/**
	 * Register all of the hooks related to the elementor-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_elementor_hooks() {

		$plugin_elementor = new Kitpack_Lite_elementor( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_elementor, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_elementor, 'enqueue_scripts' );
		if(Kitpack_Lite_Admin::kpe_get_option('elementor-ready-kits')){
			$this->loader->add_action('elementor/editor/before_enqueue_scripts', $plugin_elementor ,'kitpack_elementor_template' );
		}
		$plugin_elementor_icon = new Kitpack_Lite_Icons();
		if(Kitpack_Lite_Admin::kpe_get_option('elementor-icon-iran')){
			$this->loader->add_filter( 'elementor/icons_manager/additional_tabs', $plugin_elementor_icon, 'add_font_irani' );
		}

		if(Kitpack_Lite_Admin::kpe_get_option('elementor-farsi-font')){
			$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_elementor, 'enqueue_styles_editor' );
			$this->loader->add_action( 'elementor/preview/enqueue_styles', $plugin_elementor, 'enqueue_styles_preview' );
		}

		$plugin_elementor_fonts = new Kitpack_Lite_Fonts();
		$this->loader->add_filter( 'elementor/fonts/groups', $plugin_elementor_fonts, 'font_groups' );
		$this->loader->add_filter( 'elementor/fonts/additional_fonts', $plugin_elementor_fonts, 'add_fonts' );
		$this->loader->add_action('elementor/frontend/before_enqueue_styles', $plugin_elementor_fonts, 'add_fonts_style' );



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
	 * @return    Kitpack_Lite_Loader    Orchestrates the hooks of the plugin.
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

		/**
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param string $path Path inside plugin dir.
		 *
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;

		}

}

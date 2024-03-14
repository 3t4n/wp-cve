<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://iqonic.design/
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
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
 * @since      1.7.2
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.7.2
	 * @access   protected
	 * @var      Marvy_Animation_Addons_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.7.2
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.7.2
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
	 * @since    1.7.2
	 */
	public function __construct() {
		if ( defined( 'MARVY_ANIMATION_ADDONS_VERSION' ) ) {
			$this->version = MARVY_ANIMATION_ADDONS_VERSION;
		} else {
			$this->version = '1.7.2';
		}
		$this->plugin_name = 'marvy-animation-addons';
        $this->config = $GLOBALS['marvy_config']['bg-animation'];

        $this->load_dependencies();
		$this->set_locale();
		$this->define_elementor_hooks();
        $this->define_animation_elementor_hooks();
        $this->define_admin_hooks();
    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Marvy_Animation_Addons_Loader. Orchestrates the hooks of the plugin.
	 * - Marvy_Animation_Addons_i18n. Defines internationalization functionality.
	 * - Marvy_Animation_Addons_Admin. Defines all hooks for the admin area.
	 * - Marvy_Animation_Addons_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.7.2
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-marvy-animation-addons-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-marvy-animation-addons-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'elementor/class-marvy-animation-addons-elementor.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-marvy-animation-addons-admin.php';

		$this->loader = new Marvy_Animation_Addons_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Marvy_Animation_Addons_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.7.2
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Marvy_Animation_Addons_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.7.2
	 * @access   private
	 */
	private function define_elementor_hooks() {
		$plugin_public = new Marvy_Animation_Addons_Elementor( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
//		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        if (marvy_is_preview_mode()) {
            $this->loader->add_filter('elementor/frontend/builder_content_data', $this, 'get_marvy_animations_loaded_templates', 10, 2);
        } else {
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        }
    }

    function get_marvy_animations_loaded_templates($content, $post_id)
    {
        $plugin_public = new Marvy_Animation_Addons_Elementor($this->get_plugin_name(), $this->get_version());
        $plugin_public->enqueue_scripts($post_id);
        return $content;
    }

      /**
       * Register all of the hooks related to the public-facing functionality
       * of the plugin.
       *
       * @since    1.7.2
       * @access   private
       */
    private function define_animation_elementor_hooks() {
        $config =  (function_exists('marvy_get_config')) ? marvy_get_config() : '';
        $elements = (function_exists('marvy_get_setting')) ? marvy_get_setting() : '';
        if($elements !== '') {
            foreach ($elements as $key => $item) {
                if (isset($config[$item])) {
                    new $config[$item]['class'];
                }
            }
        }
  }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.7.2
     * @access   private
     */
    private function define_admin_hooks() {
        if (is_admin()) {
            $plugin_admin = new Marvy_Animation_Addons_Admin($this->get_plugin_name(), $this->get_version());

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
            $this->loader->add_action('wp_ajax_save_marvy_settings', $plugin_admin, 'save_settings');
			
			
            $this->loader->add_action('admin_notices', $plugin_admin, 'iqonic_sale_banner_notice');
            $this->loader->add_action('wp_ajax_iq_dismiss_notice', $plugin_admin, 'iq_dismiss_notice');
        }
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.7.2
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.7.2
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.7.2
	 * @return    Marvy_Animation_Addons_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.7.2
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

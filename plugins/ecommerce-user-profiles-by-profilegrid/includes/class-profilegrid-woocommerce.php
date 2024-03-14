<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
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
 * @package    Profilegrid_Woocommerce
 * @subpackage Profilegrid_Woocommerce/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $profilegrid_woocommerce    The string used to uniquely identify this plugin.
	 */
	protected $profilegrid_woocommerce;

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

		$this->profilegrid_woocommerce = 'profilegrid-woocommerce';
		$this->version = '1.0.0';

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
	 * - Profilegrid_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_Woocommerce_i18n. Defines internationalization functionality.
	 * - Profilegrid_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_Woocommerce_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-woocommerce-i18n.php';
                require_once plugin_dir_path(  dirname( __FILE__ )) . 'includes/class-profilegrid-woocommerce-activator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-woocommerce-deactivator.php';
                
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profilegrid-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-profilegrid-woocommerce-public.php';

		
                $this->loader = new Profilegrid_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Profilegrid_Woocommerce_i18n();
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

		$plugin_admin = new Profilegrid_Woocommerce_Admin( $this->get_profilegrid_woocommerce(), $this->get_version() );
                $this->loader->add_action( 'activated_plugin', $plugin_admin, 'pg_woo_activation_redirect' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                $this->loader->add_action( 'admin_menu', $plugin_admin, 'profilegrid_woocommerce_admin_menu' );
                $this->loader->add_action( 'profile_magic_setting_option', $plugin_admin, 'profilegrid_woocommerce_add_option_setting_page' );
                $this->loader->add_action( 'admin_notices', $plugin_admin, 'profile_magic_woocommerce_notice_fun' );
                $this->loader->add_action( 'network_admin_notices', $plugin_admin, 'profile_magic_woocommerce_notice_fun' );
                $this->loader->add_action( 'profile_magic_group_option', $plugin_admin, 'profile_magic_woocommerce_group_option',10,2 );
                $this->loader->add_action( 'profile_magic_group_woocommerce_option', $plugin_admin, 'profile_magic_woocommerce_group_option',10,2 );
                $this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
                $this->loader->add_filter('pm_profile_tabs', $plugin_admin, 'pm_woocommerce_tabs_filters');
                //$this->loader->add_action( 'admin_footer', $plugin_admin, 'pm_woocommerce_plugin_popup');
                $this->loader->add_action( 'admin_footer', $plugin_admin, 'pm_woocommerce_check_core_plugin_install_popup');
                $this->loader->add_action( 'wp_ajax_pg_install_profilegrid',$plugin_admin, 'pg_install_profilegrid' );
//$this->loader->add_action('wp_ajax_pg_update_woo_popup_setting',$plugin_admin,'pg_update_woo_popup_setting');
        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
            $plugin_public = new Profilegrid_Woocommerce_Public( $this->get_profilegrid_woocommerce(), $this->get_version() );
            $this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public,'pg_my_orders_tab',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public,'pg_my_billing_address_tab',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab', $plugin_public,'pg_my_shipping_address_tab',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public,'pg_my_orders_tab_content',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public,'pg_my_billing_address_tab_content',10,2);
            $this->loader->add_action('profile_magic_profile_settings_tab_content', $plugin_public,'pg_my_shipping_address_tab_content',10,2);
            $this->loader->add_action('wp_ajax_pg_woocommerce_get_order',$plugin_public,'pg_woocommerce_get_order');
            $this->loader->add_action('profile_magic_update_frontend_user_settings',$plugin_public,'profile_magic_update_billing_address',10,2);
            $this->loader->add_action('profile_magic_update_frontend_user_settings',$plugin_public,'profile_magic_update_shipping_address',10,2);
            $this->loader->add_action('profile_magic_show_additional_header_info',$plugin_public,'profile_magic_show_total_spend_and_total_orders',10,1);
            $this->loader->add_action('profile_magic_profile_tab_link',$plugin_public, 'profile_magic_profile_tab_link_fun',10,5);
            $this->loader->add_action('profile_magic_profile_tab_extension_content',$plugin_public, 'profile_magic_profile_tab_extension_content_fun',10,5);
            
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
	public function get_profilegrid_woocommerce() {
		return $this->profilegrid_woocommerce;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_Woocommerce_Loader    Orchestrates the hooks of the plugin.
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
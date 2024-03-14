<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       www.theritesites.com
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/includes
 * @author     TheRiteSites <contact@theritesites.com>
 */

// use TRS\EAA2C\Single;
// use TRS\EAA2C\Group;
use TRS\EAA2C\Settings;
use TRS\EAA2C\Admin;
use TRS\EAA2C\Front;
use TRS\EAA2C\Loader;

class Enhanced_Ajax_Add_To_Cart_Wc {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Enhanced_Ajax_Add_To_Cart_Wc_Loader    $loader    Maintains and registers all hooks for the plugin.
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

	protected $plugin_admin;

	protected $settings;

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
		
		
		$this->define_constants();
		$this->plugin_name = EAA2C_NAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->register_settings();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	public function define_constants() {
		if ( defined( 'ENHANCED_AJAX_ADD_TO_CART' ) ) {
			$this->version = ENHANCED_AJAX_ADD_TO_CART;
		} else {
			$this->version = '1.0.0';
		}

		if ( ! defined( 'EAA2C_NAME' ) ) {
			define( 'EAA2C_NAME', 'enhanced-ajax-add-to-cart-wc' );
		}
		if ( ! defined( 'EAA2C_DEBUG' ) ) {
			$debug = get_option( 'eaa2c_debug', false );
			if ( strcmp( $debug, 'true' ) === 0 || strcmp( $debug, 'on' ) === 0 ) {
				$debug = true;
			}
			define( 'EAA2C_DEBUG', $debug );
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Enhanced_Ajax_Add_To_Cart_Wc_Loader. Orchestrates the hooks of the plugin.
	 * - Enhanced_Ajax_Add_To_Cart_Wc_i18n. Defines internationalization functionality.
	 * - Enhanced_Ajax_Add_To_Cart_Wc_Admin. Defines all hooks for the admin area.
	 * - Enhanced_Ajax_Add_To_Cart_Wc_Public. Defines all hooks for the public side of the site.
	 * - Enhanced_Ajax_Add_To_Cart_Wc_AJAX. Defines all the callback functions for AJAX functionality.
	 * 
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/abstract-eaa2c-button.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-ajax.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-single.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'src/class-eaa2c-group.php';

		$this->loader = new Loader();

	}

    public function register_routes() {
		$namespace = 'eaa2c/v1';
		
		register_rest_route( $namespace, '/settings', array(
			array(
				'methods'   => \WP_REST_Server::READABLE,
                'callback'  => array( $this, 'get_eaa2c_settings' ),
                'permission_callback'    => array( $this, 'user_can_manage' ),
                // 'args'      => array(
                //     'context' => array(
                //         'default'   => 'view',
                //     ),
                // ),
            )
		) );

		register_rest_route( $namespace, '/product-image/' . '(?P<id>[\d-]+)', array(
			array(
				'methods'   => \WP_REST_Server::READABLE,
                'callback'  => array( $this, 'get_eaa2c_product_image' ),
                'permission_callback'    => array( $this, 'user_can_manage' ),
                // 'args'      => array(
                //     'context' => array(
                //         'default'   => 'view',
                //     ),
                // ),
            )
		) );

        /*register_rest_route( $namespace, '/connect', array(
            array(
                'methods'   => \WP_REST_Server::ALLMETHODS,
                'callback'  => array( $this, 'error_check_route' ),
                // 'permissions_callback    => array( $this, '' ),
                // 'args'      => array(
                //     'context' => array(
                //         'default'   => 'view',
                //     ),
                // ),
            )
		) );*/
		register_rest_route( $namespace, '/products', array(
            array(
                'methods'   => \WP_REST_Server::ALLMETHODS,
                'callback'  => array( $this, 'get_all_products_and_variations' ),
                'permission_callback'    => function(){ return current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ); },
                // 'args'      => array(
                //     'context' => array(
                //         'default'   => 'view',
                //     ),
                // ),
            )
		) );
	}

	private function user_can_manage() {
		if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
			return true;
		}
		return false;
	}

	public function get_eaa2c_product_image( WP_REST_Request $request ) {
		global $_wp_additional_image_sizes;
		$image = array( 'src' => '', 'width' => 0, 'height' => 0, 'pid' => 0 );
		$params = $request->get_params();
		$sizes = apply_filters( 'image_size_names_choose',
			array(
				'thumbnail' => __( 'Thumbnail' ),
				'medium'    => __( 'Medium' ),
				'large'     => __( 'Large' ),
				'full'      => __( 'Full Size' ),
			)
		);
		// $params['sizes'] = $_wp_additional_image_sizes;
		$params['sizes'] = $sizes;

		$product = wc_get_product( $params['id'] );
		if ( $product ) {
			$image_id = $product->get_image_id();

			if ( $image_id > 0 && in_array( $params['type'] , array_keys( $sizes ) ) ) {
				$temp = wp_get_attachment_image_src( $image_id, $params['type'] );
				if ( is_array( $temp ) ) {
					if ( isset( $temp[0] ) ) {
						$image['src'] = $temp[0];
					}
					if ( isset( $temp[1] ) ) {
						$image['width'] = $temp[1];
					}
					if ( isset( $temp[2] ) ) {
						$image['height'] = $temp[2];
					}
					$image['pid'] = $product->get_id();
				}
			}
		}

		return wp_send_json_success( $image );
	}

	public function get_eaa2c_settings( WP_REST_Request $request ) {
		// $params = $request->get_params();

		$image_sizes = apply_filters( 'image_size_names_choose',
			array(
				'thumbnail' => __( 'Thumbnail' ),
				'medium'    => __( 'Medium' ),
				'large'     => __( 'Large' ),
				'full'      => __( 'Full Size' ),
			)
		);

		$dat = array( 'image_sizes' => $image_sizes );
		return wp_send_json_success( $dat );
	}

	public function get_all_products_and_variations( WP_REST_Request $request ) {
		$params = $request->get_params();

		$q = `SELECT p.post_parent as pp, GROUP_CONCAT(DISTINCT p.ID) FROM wp_posts as p WHERE p.post_type = 'product_variation' AND p.post_status = 'publish' GROUP BY pp;`;
		
		if ( WP_DEBUG || EAA2C_DEBUG ) {
			error_log( 'parameters to get all products and variations: ' . wc_print_r( $params, true ) );
		}
		return true;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Enhanced_Ajax_Add_To_Cart_Wc_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new \Enhanced_Ajax_Add_To_Cart_Wc_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Call settings class for the plugin.
	 * 
	 * @since 2.0.0
	 * @access	private
	 */
	private function register_settings() {
		$settings = new Settings();

		$this->loader->add_action( 'admin_menu', $settings, 'register_menu_item' );
		$this->loader->add_action( 'admin_init', $settings, 'register_settings' );
		
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->plugin_admin = new Admin();

		$this->loader->add_action( 'init', $this->plugin_admin, 'register_a2cp_single', 9999 );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'register_scripts' );

		// add_action( 'admin_notices', array( $this, 'register_app_rest' ) );
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Front();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_scripts' );

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
	 * @return    Enhanced_Ajax_Add_To_Cart_Wc_Loader    Orchestrates the hooks of the plugin.
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

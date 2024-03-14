<?php

/**
 * The file that defines the core plugin class
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 * @package    Caddy
 * @subpackage Caddy/includes
 * @author     Tribe Interactive <success@madebytribe.co>
 */
class Caddy {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Caddy_Loader $loader Maintains and registers all hooks for the plugin.
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
		if ( defined( 'CADDY_VERSION' ) ) {
			$this->version = CADDY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'caddy';

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
	 * - Caddy_Loader. Orchestrates the hooks of the plugin.
	 * - Caddy_i18n. Defines internationalization functionality.
	 * - Caddy_Admin. Defines all hooks for the admin area.
	 * - Caddy_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-caddy-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-caddy-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-caddy-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-caddy-public.php';

		$this->loader = new Caddy_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Caddy_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Caddy_i18n();

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

		if ( class_exists( 'ET_Builder_Element' ) ) {
			return;
		}

		$caddy_admin_obj = new Caddy_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $caddy_admin_obj, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $caddy_admin_obj, 'enqueue_scripts' );

		// Add action to register menu page
		$this->loader->add_action( 'admin_menu', $caddy_admin_obj, 'cc_register_menu_page' );

		// Add action to add caddy deactivation popup HTML
		$this->loader->add_action( 'admin_footer', $caddy_admin_obj, 'caddy_load_deactivation_html' );

		// Add action to include tab screen files
		$this->loader->add_action( 'caddy_admin_tab_screen', $caddy_admin_obj, 'cc_include_tab_screen_files' );

		// Add action to load html for upgrade to premium
		$this->loader->add_action( 'cc_upgrade_to_premium', $caddy_admin_obj, 'cc_upgrade_to_premium_html' );

		// Add action to dismiss the welcome notice
		$this->loader->add_action( 'wp_ajax_dismiss_welcome_notice', $caddy_admin_obj, 'cc_dismiss_welcome_notice' );

		// Add action to dismiss the optin notice
		$this->loader->add_action( 'wp_ajax_dismiss_optin_notice', $caddy_admin_obj, 'cc_dismiss_optin_notice' );

		// Add action to display addons html
		$this->loader->add_action( 'cc_addons_html', $caddy_admin_obj, 'cc_addons_html_display' );

		// Add action to display header links html
		$this->loader->add_action( 'caddy_header_links', $caddy_admin_obj, 'caddy_header_links_html' );

		// Add action to display submit deactivation form data
		$this->loader->add_action( 'wp_ajax_cc_submit_deactivation_form_data', $caddy_admin_obj, 'caddy_submit_deactivation_form_data' );
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
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		if ( class_exists( 'ET_Builder_Element' ) ) {
			return;
		}

		global $caddy_public_obj;
		$caddy_public_obj = new Caddy_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $caddy_public_obj, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $caddy_public_obj, 'enqueue_scripts' );

		// Add action to load cc widget
		$this->loader->add_action( 'wp_footer', $caddy_public_obj, 'cc_load_widget' );

		// Add action for ajaxify cart count
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $caddy_public_obj, 'cc_compass_cart_count_fragments' );

		// Add action for ajaxify update cart count in shortcode
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $caddy_public_obj, 'cc_shortcode_cart_count_fragments' );

		// Add action for ajaxify update cc-cart html
		$this->loader->add_filter( 'woocommerce_add_to_cart_fragments', $caddy_public_obj, 'cc_cart_html_fragments' );

		// Add filter to hide shipping rates when free shipping amount matched
		$this->loader->add_filter( 'woocommerce_package_rates', $caddy_public_obj, 'cc_shipping_when_free_is_available' );

		// Add a short-code for saved list
		$this->loader->add_shortcode( 'cc_saved_items', $caddy_public_obj, 'cc_saved_items_shortcode' );

		// Add a short-code for cart items list
		$this->loader->add_shortcode( 'cc_cart_items', $caddy_public_obj, 'cc_cart_items_shortcode' );

		// Add action to display caddy cart bubble icon
		$this->loader->add_action( 'caddy_cart_bubble_icon', $caddy_public_obj, 'cc_display_cart_bubble_icon' );

		// Add action for adding a product to save for later
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $caddy_public_obj, 'cc_add_product_to_sfl', 20 );

		// Add action to hide "added to your cart" message
		$this->loader->add_filter( 'wc_add_to_cart_message_html', $caddy_public_obj, 'cc_empty_wc_add_to_cart_message', 10, 2 );

		// Add action to load the custom css into footer
		$this->loader->add_action( 'wp_footer', $caddy_public_obj, 'cc_load_custom_css', 99 );

		// Add action to display up-sell message in product added screen
		$this->loader->add_action( 'caddy_free_shipping_title_text', $caddy_public_obj, 'cc_free_shipping_bar_html' );

		// Add action to display compass icon
		$this->loader->add_action( 'caddy_compass_icon', $caddy_public_obj, 'cc_display_compass_icon' );

		// Add action to display up-sells slider in product added screen
		$this->loader->add_action( 'caddy_product_upsells_slider', $caddy_public_obj, 'cc_display_product_upsells_slider', 10 );

		// Add action to display free shipping Congrats text
		$this->loader->add_action( 'caddy_fs_congrats_text', $caddy_public_obj, 'caddy_display_free_shipping_congrats_text', 10, 1 );

		// Add action to display free shipping Congrats text
		$this->loader->add_action( 'caddy_fs_spend_text', $caddy_public_obj, 'caddy_display_free_shipping_spend_text', 10, 2 );

		// Add action for add item to the cart
		$this->loader->add_action( 'wc_ajax_cc_add_to_cart', $caddy_public_obj, 'caddy_add_to_cart' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_add_to_cart', $caddy_public_obj, 'caddy_add_to_cart' );

		// Add action for remove product from the cart
		$this->loader->add_action( 'wc_ajax_cc_remove_item_from_cart', $caddy_public_obj, 'caddy_remove_item_from_cart' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_remove_item_from_cart', $caddy_public_obj, 'caddy_remove_item_from_cart' );

		// Add action for update quantity for cart item
		$this->loader->add_action( 'wc_ajax_cc_quantity_update', $caddy_public_obj, 'caddy_cart_item_quantity_update' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_quantity_update', $caddy_public_obj, 'caddy_cart_item_quantity_update' );

		// Add action to add cart item into wishlist
		$this->loader->add_action( 'wc_ajax_cc_save_for_later', $caddy_public_obj, 'caddy_save_for_later_item' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_save_for_later', $caddy_public_obj, 'caddy_save_for_later_item' );

		// Add action to add item to cart from wishlist
		$this->loader->add_action( 'wc_ajax_cc_move_to_cart', $caddy_public_obj, 'caddy_move_to_cart_item' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_move_to_cart', $caddy_public_obj, 'caddy_move_to_cart_item' );

		// Add action to remove item from wishlist
		$this->loader->add_action( 'wc_ajax_cc_remove_item_from_sfl', $caddy_public_obj, 'caddy_remove_item_from_sfl' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_remove_item_from_sfl', $caddy_public_obj, 'caddy_remove_item_from_sfl' );

		// Add action to apply coupon code to the cart
		$this->loader->add_action( 'wc_ajax_cc_apply_coupon_to_cart', $caddy_public_obj, 'caddy_apply_coupon_to_cart' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_apply_coupon_to_cart', $caddy_public_obj, 'caddy_apply_coupon_to_cart' );

		// Add action to remove coupon code from the cart
		$this->loader->add_action( 'wc_ajax_cc_remove_coupon_code', $caddy_public_obj, 'caddy_remove_coupon_code' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_remove_coupon_code', $caddy_public_obj, 'caddy_remove_coupon_code' );

		// Add action to add product to save for later
		$this->loader->add_action( 'wc_ajax_cc_add_product_to_sfl_action', $caddy_public_obj, 'caddy_add_product_to_sfl_action' );
		$this->loader->add_action( 'wc_ajax_nopriv_cc_add_product_to_sfl_action', $caddy_public_obj, 'caddy_add_product_to_sfl_action' );
		
		// Add filter to insert cart widget to menu
		$this->loader->add_filter('wp_nav_menu_items', $caddy_public_obj, 'caddy_add_cart_widget_to_menu', 20, 2);
		
		// Add filter to insert saves widget to menu
		$this->loader->add_filter('wp_nav_menu_items', $caddy_public_obj, 'caddy_add_saves_widget_to_menu', 10, 2);
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
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Caddy_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Check Caddy premium version license is active or not
	 *
	 * @return bool
	 */
	public function cc_check_premium_license_activation() {
		$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
		// Check if premium plugin is active or not
		if ( ! class_exists( 'Caddy_Premium' ) ||
		     ( isset( $caddy_license_status ) && ! empty( $caddy_license_status ) ) ) {
			// Return if the license key is valid
			if ( 'valid' === $caddy_license_status ) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}

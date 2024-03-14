<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpartisan.net/
 * @since      1.0.0
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/includes
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
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/includes
 * @author     wpArtisan
 */
class Remove_Add_To_Cart_Button_Woocommerce
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Remove_Add_To_Cart_Button_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        
        if ( defined( 'REMOVE_ADD_TO_CART_BUTTON_WOOCOMMERCE_VERSION' ) ) {
            $this->version = REMOVE_ADD_TO_CART_BUTTON_WOOCOMMERCE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        
        $this->plugin_name = 'remove-add-to-cart-button-woocommerce';
        $this->ratcw_load_dependencies();
        $this->ratcw_set_locale();
        $this->ratcw_define_admin_hooks();
        $this->ratcw_define_public_hooks();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Remove_Add_To_Cart_Button_Woocommerce_Loader. Orchestrates the hooks of the plugin.
     * - Remove_Add_To_Cart_Button_Woocommerce_i18n. Defines internationalization functionality.
     * - Remove_Add_To_Cart_Button_Woocommerce_Admin. Defines all hooks for the admin area.
     * - Remove_Add_To_Cart_Button_Woocommerce_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function ratcw_load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-remove-add-to-cart-button-woocommerce-loader.php';
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-remove-add-to-cart-button-woocommerce-i18n.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-remove-add-to-cart-button-woocommerce-admin.php';
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-remove-add-to-cart-button-woocommerce-public.php';
        $this->loader = new Remove_Add_To_Cart_Button_Woocommerce_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Remove_Add_To_Cart_Button_Woocommerce_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function ratcw_set_locale()
    {
        $plugin_i18n = new Remove_Add_To_Cart_Button_Woocommerce_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'ratcw_load_plugin_textdomain' );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function ratcw_define_admin_hooks()
    {
        $plugin_admin = new Remove_Add_To_Cart_Button_Woocommerce_Admin( $this->ratcw_get_plugin_name(), $this->ratcw_get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'ratcw_enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'ratcw_enqueue_scripts' );
        //custom tab in woocommerce product data panel
        $this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'ratcw_remove_add_to_cart_button_data_tab' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'ratcw_custom_style' );
        $this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'ratcw_remove_add_to_cart_button_product_data_fields' );
        $this->loader->add_action(
            'woocommerce_process_product_meta',
            $plugin_admin,
            'ratcw_save_fields',
            10,
            2
        );
        //plugin settions options
        $this->loader->add_action(
            'woocommerce_settings_tabs_array',
            $plugin_admin,
            'ratcw_add_settings_tab',
            50
        );
        $this->loader->add_action( 'woocommerce_settings_tabs_remove-add-to-cart-button-settings', $plugin_admin, 'ratcw_settings_tab' );
        $this->loader->add_action(
            'woocommerce_update_options_remove-add-to-cart-button-settings',
            $plugin_admin,
            'ratcw_update_settings',
            10,
            2
        );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function ratcw_define_public_hooks()
    {
        $plugin_public = new Remove_Add_To_Cart_Button_Woocommerce_Public( $this->ratcw_get_plugin_name(), $this->ratcw_get_version() );
        // hide product price
        $this->loader->add_action(
            'woocommerce_get_price_html',
            $plugin_public,
            'ratcw_hide_price_for_product',
            10,
            2
        );
        $this->loader->add_filter(
            'woocommerce_cart_item_price',
            $plugin_public,
            'ratcw_hide_cart_item_price',
            10,
            3
        );
        $this->loader->add_filter(
            'woocommerce_cart_item_subtotal',
            $plugin_public,
            'ratcw_hide_cart_item_subtotal',
            10,
            3
        );
        //remove add to cart button
        $this->loader->add_filter(
            'woocommerce_loop_add_to_cart_link',
            $plugin_public,
            'ratcw_woocommerce_loop_add_to_cart_link',
            10,
            3
        );
        $this->loader->add_action(
            'woocommerce_simple_add_to_cart',
            $plugin_public,
            'ratcw_remove_add_to_cart_button_from_single_product',
            10
        );
        $this->loader->add_action(
            'woocommerce_variable_add_to_cart',
            $plugin_public,
            'ratcw_remove_add_to_cart_button_from_single_product',
            10
        );
        $this->loader->add_action(
            'woocommerce_grouped_add_to_cart',
            $plugin_public,
            'ratcw_remove_add_to_cart_button_from_single_product',
            10
        );
        $this->loader->add_action(
            'woocommerce_external_add_to_cart',
            $plugin_public,
            'ratcw_remove_add_to_cart_button_from_single_product',
            10
        );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function ratcw_run()
    {
        $this->loader->ratcw_run_loader();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function ratcw_get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Remove_Add_To_Cart_Button_Woocommerce_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function ratcw_get_version()
    {
        return $this->version;
    }

}
<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
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
 * @package    Woo_Hide_Shipping_Methods
 * @subpackage Woo_Hide_Shipping_Methods/includes
 * @author     theDotstore <wordpress@multidots.in>
 */
if ( !class_exists( 'Woo_Hide_Shipping_Methods' ) ) {
    class Woo_Hide_Shipping_Methods
    {
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         * the plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      Woo_Hide_Shipping_Methods_Loader    $loader    Maintains and registers all hooks for the plugin.
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
            $this->plugin_name = 'woo-hide-shipping-methods';
            $this->version = WOO_HIDE_SHIPPING_METHODS_VERSION;
            $this->load_dependencies();
            $this->set_locale();
            $this->define_admin_hooks();
            $this->define_public_hooks();
            $prefix = ( is_network_admin() ? 'network_admin_' : '' );
            add_filter(
                "{$prefix}plugin_action_links_" . WHSM_PLUGIN_BASENAME,
                array( $this, 'plugin_action_links' ),
                10,
                4
            );
        }
        
        /**
         * Load the required dependencies for this plugin.
         *
         * Include the following files that make up the plugin:
         *
         * - Woo_Hide_Shipping_Methods_Loader. Orchestrates the hooks of the plugin.
         * - Woo_Hide_Shipping_Methods_i18n. Defines internationalization functionality.
         * - Woo_Hide_Shipping_Methods_Admin. Defines all hooks for the admin area.
         * - Woo_Hide_Shipping_Methods_Public. Defines all hooks for the public side of the site.
         *
         * Create an instance of the loader which will be used to register the hooks
         * with WordPress.
         *
         * @since    1.0.0
         * @access   private
         */
        private function load_dependencies()
        {
            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-hide-shipping-methods-loader.php';
            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-hide-shipping-methods-i18n.php';
            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-hide-shipping-methods-admin.php';
            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-hide-shipping-methods-public.php';
            $this->loader = new Woo_Hide_Shipping_Methods_Loader();
        }
        
        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the Woo_Hide_Shipping_Methods_i18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @since    1.0.0
         * @access   private
         */
        private function set_locale()
        {
            $plugin_i18n = new Woo_Hide_Shipping_Methods_i18n();
            $plugin_i18n->set_domain( $this->get_plugin_name() );
            $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
        }
        
        /**
         * Register all of the hooks related to the admin area functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_admin_hooks()
        {
            $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $plugin_admin = new Woo_Hide_Shipping_Methods_Admin( $this->get_plugin_name(), $this->get_version() );
            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'whsma_enqueue_styles' );
            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'whsma_enqueue_scripts' );
            $this->loader->add_action( 'admin_menu', $plugin_admin, 'whsma_dot_store_menu_shipping_method_pro' );
            $this->loader->add_action( 'admin_head', $plugin_admin, 'whsma_remove_admin_submenus' );
            $this->loader->add_action(
                'admin_init',
                $plugin_admin,
                'whsma_register_post_type',
                0
            );
            $this->loader->add_action( 'wp_ajax_whsma_product_fees_conditions_values_ajax', $plugin_admin, 'whsma_product_fees_conditions_values_ajax' );
            $this->loader->add_action( 'wp_ajax_whsma_product_fees_conditions_values_product_ajax', $plugin_admin, 'whsma_product_fees_conditions_values_product_ajax' );
            $this->loader->add_action( 'wp_ajax_whsm_change_status_from_list_section', $plugin_admin, 'whsm_change_status_from_list_section' );
            $this->loader->add_filter(
                'set-screen-option',
                $plugin_admin,
                'whsma_set_screen_options',
                10,
                3
            );
            $this->loader->add_filter( 'admin_body_class', $plugin_admin, 'whsma_admin_body_class' );
            if ( !empty($page) && false !== strpos( $page, 'whsm' ) ) {
                $this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'whsma_admin_footer_review' );
            }
            $this->loader->add_action( 'wp_ajax_whsm_plugin_setup_wizard_submit', $plugin_admin, 'whsm_plugin_setup_wizard_submit' );
            $this->loader->add_action( 'admin_init', $plugin_admin, 'whsm_send_wizard_data_after_plugin_activation' );
        }
        
        /**
         * Register all of the hooks related to the public-facing functionality
         * of the plugin.
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_public_hooks()
        {
            $whsm_hide_shipping_cart = get_option( 'whsm_hide_shipping_cart' );
            $plugin_public = new Woo_Hide_Shipping_Methods_Public( $this->get_plugin_name(), $this->get_version() );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'whsmp_enqueue_styles' );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'whsmp_enqueue_scripts' );
            $this->loader->add_filter(
                'woocommerce_package_rates',
                $plugin_public,
                'whsmp_unset_shipping_method',
                10,
                2
            );
            $this->loader->add_filter(
                'woocommerce_shipping_packages',
                $plugin_public,
                'whsmp_conditional_fee_add_to_cart',
                10,
                1
            );
            if ( 'on' === $whsm_hide_shipping_cart ) {
                $this->loader->add_filter(
                    'woocommerce_cart_ready_to_calc_shipping',
                    $plugin_public,
                    'whsmp_unset_shipping_method_from_cart',
                    10,
                    2
                );
            }
        }
        
        /**
         * Return the plugin action links.  This will only be called if the plugin
         * is active.
         *
         * @since 1.0.0
         * @param array $actions associative array of action names to anchor tags
         * @return array associative array of plugin action links
         */
        public function plugin_action_links( $actions )
        {
            $custom_actions = array(
                'configure' => sprintf( '<a href="%s">%s</a>', esc_url( add_query_arg( array(
                'page' => 'whsm-start-page',
            ), admin_url( 'admin.php' ) ) ), __( 'Settings', 'woo-hide-shipping-methods' ) ),
                'docs'      => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://docs.thedotstore.com/category/180-premium-plugin-settings' ), __( 'Docs', 'woo-hide-shipping-methods' ) ),
                'support'   => sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'thedotstore.com/support' ), __( 'Support', 'woo-hide-shipping-methods' ) ),
            );
            // add the links to the front of the actions list
            return array_merge( $custom_actions, $actions );
        }
        
        /**
         * Run the loader to execute all of the hooks with WordPress.
         *
         * @since    1.0.0
         */
        public function run()
        {
            $this->loader->run();
        }
        
        /**
         * The name of the plugin used to uniquely identify it within the context of
         * WordPress and to define internationalization functionality.
         *
         * @since     1.0.0
         * @return    string    The name of the plugin.
         */
        public function get_plugin_name()
        {
            return $this->plugin_name;
        }
        
        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         *
         * @since     1.0.0
         * @return    Woo_Hide_Shipping_Methods_Loader    Orchestrates the hooks of the plugin.
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
        public function get_version()
        {
            return $this->version;
        }
    
    }
}
<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Activatable_Interface;
use ACFWF\Interfaces\Deactivatable_Interface;
use ACFWF\Interfaces\Initializable_Interface;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Objects\ACFW_Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Model that houses the logic of 'Bootstraping' the plugin.
 * Private Model.
 *
 * @since 1.0
 */
class Bootstrap extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Array of models implementing the ACFWF\Interfaces\Activatable_Interface.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_activatables;

    /**
     * Array of models implementing the ACFWF\Interfaces\Initializable_Interface.
     *
     * @since 1.0
     * @access private
     * @var array
     */
    private $_initializables;

    /**
     * Array of models implementing the ACFWF\Interfaces\Deactivatable_Interface.
     *
     * @since 1.0.1
     * @access private
     * @var array
     */
    private $_deactivatables;

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 1.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @param array                      $activatables     Array of models implementing ACFWF\Interfaces\Activatable_Interface.
     * @param array                      $initializables   Array of models implementing ACFWF\Interfaces\Initializable_Interface.
     * @param array                      $deactivatables   Array of models implementing ACFWF\Interfaces\Deactivatable_Interface.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions, array $activatables = array(), array $initializables = array(), $deactivatables = array() ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $this->_activatables   = $activatables;
        $this->_initializables = $initializables;
        $this->_deactivatables = $deactivatables;
        $main_plugin->add_to_all_plugin_models( $this );
        $this->_register_custom_database_tables();
    }

    /**
     * Load plugin text domain.
     *
     * @since 1.0
     * @access public
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( Plugin_Constants::TEXT_DOMAIN, false, $this->_constants->PLUGIN_DIRNAME . '/languages' );
    }

    /**
     * Register custom database tables through WC filter.
     *
     * @since 4.5.1
     * @access public
     */
    private function _register_custom_database_tables() {
        global $wpdb;

        $custom_tables = array(
            Plugin_Constants::STORE_CREDITS_DB_NAME, // store credit entries.
        );

        foreach ( $custom_tables as $table ) {
            $wpdb->$table   = $wpdb->prefix . $table;
            $wpdb->tables[] = $table;
        }
    }

    /**
     * Method that houses the logic relating to activating the plugin.
     *
     * @since 1.0
     * @access public
     *
     * @global wpdb $wpdb Object that contains a set of functions used to interact with a database.
     *
     * @param boolean $network_wide Flag that determines whether the plugin has been activated network wid ( on multi site environment ) or not.
     */
    public function activate_plugin( $network_wide ) {
        global $wpdb;

        if ( is_multisite() ) {

            if ( $network_wide ) {

                // get ids of all sites.
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    $this->_activate_plugin( $blog_id );

                }

                restore_current_blog();

            } else {
                $this->_activate_plugin( $wpdb->blogid );
            }
            // activated on a single site, in a multi-site.

        } else {
            $this->_activate_plugin( $wpdb->blogid );
        }
        // activated on a single site.
    }

    /**
     * Method to initialize a newly created site in a multi site set up.
     *
     * @since 1.0
     * @access public
     *
     * @param int    $blog_id Blog ID of the created blog.
     * @param int    $user_id User ID of the user creating the blog.
     * @param string $domain Domain used for the new blog.
     * @param string $path Path to the new blog.
     * @param int    $site_id Site ID. Only relevant on multi-network installs.
     * @param array  $meta Meta data. Used to set initial site options.
     */
    public function new_mu_site_init( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
        if ( is_plugin_active_for_network( 'advanced-coupons-for-woocommerce-free/advanced-coupons-for-woocommerce-free.php' ) ) {

            switch_to_blog( $blog_id );
            $this->_activate_plugin( $blog_id );
            restore_current_blog();

        }
    }

    /**
     * Initialize plugin settings options.
     * This is a compromise to my idea of 'Modularity'. Ideally, bootstrap should not take care of plugin settings stuff.
     * However due to how WooCommerce do its thing, we need to do it this way. We can't separate settings on its own.
     *
     * @since 1.0
     * @access private
     */
    private function _initialize_plugin_settings_options() {
        // General settings section options.
        if ( ! get_option( Plugin_Constants::COUPON_ENDPOINT, false ) ) {
            update_option( Plugin_Constants::COUPON_ENDPOINT, 'coupon' );
        }

        // Help settings section options.
        if ( ! get_option( Plugin_Constants::CLEAN_UP_PLUGIN_OPTIONS, false ) ) {
            update_option( Plugin_Constants::CLEAN_UP_PLUGIN_OPTIONS, 'no' );
        }
    }

    /**
     * Actual function that houses the code to execute on plugin activation.
     *
     * @since 1.0
     * @since 1.7 Refactor support for multisite setup.
     * @access private
     *
     * @global WP_Rewrite $wp_rewrite Core class used to implement a rewrite component API.
     *
     * @param int $blogid Blog ID of the created blog.
     */
    private function _activate_plugin( $blogid ) {
        /**
         * Previously multisite installs site store license options using normal get/add/update_option functions.
         * These stores the option on a per sub-site basis. We need move these options network wide in multisite setup
         * via get/add/update_site_option functions.
         */
        if ( is_multisite() ) {

            $installed_version = get_option( Plugin_Constants::INSTALLED_VERSION );

            if ( $installed_version ) {

                update_site_option( Plugin_Constants::INSTALLED_VERSION, $installed_version );

                delete_option( Plugin_Constants::INSTALLED_VERSION );

            }
}

        // Initialize settings options.
        $this->_initialize_plugin_settings_options();

        // Execute 'activate' contract of models implementing ACFWF\Interfaces\Activatable_Interface.
        foreach ( $this->_activatables as $activatable ) {
            if ( $activatable instanceof Activatable_Interface ) {
                $activatable->activate();
            }
        }

        // Update current installed plugin version.
        if ( is_multisite() ) {
            update_site_option( Plugin_Constants::INSTALLED_VERSION, Plugin_Constants::VERSION );
        } else {
            update_option( Plugin_Constants::INSTALLED_VERSION, Plugin_Constants::VERSION );
        }

        // This is brute force rewriting of rules.
        global $wp_rewrite;
        $wp_rewrite->flush_rules();

        update_option( 'acfwf_activation_code_triggered', 'yes' );
    }

    /**
     * Method that houses the logic relating to deactivating the plugin.
     *
     * @since 1.0
     * @access public
     *
     * @global wpdb $wpdb Object that contains a set of functions used to interact with a database.
     *
     * @param boolean $network_wide Flag that determines whether the plugin has been activated network wid ( on multi site environment ) or not.
     */
    public function deactivate_plugin( $network_wide ) {
        global $wpdb;

        // check if it is a multisite network.
        if ( is_multisite() ) {

            // check if the plugin has been activated on the network or on a single site.
            if ( $network_wide ) {

                // get ids of all sites.
                $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    $this->_deactivate_plugin( $wpdb->blogid );

                }

                restore_current_blog();

            } else {
                $this->_deactivate_plugin( $wpdb->blogid );
            }
            // activated on a single site, in a multi-site.

        } else {
            $this->_deactivate_plugin( $wpdb->blogid );
        }
        // activated on a single site.
    }

    /**
     * Actual method that houses the code to execute on plugin deactivation.
     *
     * @since 1.0
     * @access private
     *
     * @param int $blogid Blog ID of the created blog.
     */
    private function _deactivate_plugin( $blogid ) {
        // Execute 'deactivate' contract of models implementing ACFWF\Interfaces\Deactivatable_Interface.
        foreach ( $this->_deactivatables as $deactivatable ) {
            if ( $deactivatable instanceof Deactivatable_Interface ) {
                $deactivatable->deactivate();
            }
        }

        flush_rewrite_rules();
    }

    /**
     * Method that houses codes to be executed on init hook.
     *
     * @since 1.0
     * @access public
     */
    public function initialize() {
        // Execute activation codebase if not yet executed on plugin activation ( Mostly due to plugin dependencies ).
        $installed_version = is_multisite() ? get_site_option( Plugin_Constants::INSTALLED_VERSION, false ) : get_option( Plugin_Constants::INSTALLED_VERSION, false );

        if ( version_compare( $installed_version, Plugin_Constants::VERSION, '!=' ) || get_option( 'acfwf_activation_code_triggered', false ) !== 'yes' ) {

            if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
            }

            $network_wide = is_plugin_active_for_network( 'advanced-coupons-for-woocommerce/advanced-coupons-for-woocommerce.php' );
            $this->activate_plugin( $network_wide );

        }

        // Execute 'initialize' contract of models implementing ACFWF\Interfaces\Initializable_Interface.
        foreach ( $this->_initializables as $initializable ) {
            if ( $initializable instanceof Initializable_Interface ) {
                $initializable->initialize();
            }
        }
    }

    /**
     * Maybe add support for the HTML5 script to the theme so it will allow the `type="module"` attribute in the script tag.
     *
     * @since 4.5.9.2
     * @access public
     */
    public function maybe_add_html5_script_support() {
        if ( current_theme_supports( 'html5', 'script' ) ) {
            return;
        }

        add_theme_support( 'html5', array( 'script' ) );
    }

    /**
     * Add settings link to plugin actions links.
     *
     * @since 1.0
     * @access public
     *
     * @param array $links Plugin action links.
     * @return array Filtered plugin action links
     */
    public function plugin_settings_action_link( $links ) {
        $href = admin_url( 'admin.php?page=acfw-settings' );

        if ( $this->_helper_functions->is_plugin_active( Plugin_Constants::PREMIUM_PLUGIN ) && version_compare( ACFWP()->Plugin_Constants->VERSION, '2.2', '<' ) ) {
            $href = admin_url( 'admin.php?page=wc-settings&tab=acfw_settings' );
        }

        $settings_link = '<a href="' . $href . '">' . __( 'Settings', 'advanced-coupons-for-woocommerce-free' ) . '</a>';
        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
     * Declare high performance order storage compatibility.
     *
     * @since 4.5.6
     * @access public
     */
    public function declare_woocommerce_features_compatibility() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            // Declare that the plugin is compatible with hpos feature.
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                $this->_constants->MAIN_PLUGIN_FILE_PATH,
                true
            );

            // Declare that the plugin is compatible with the cart and checkout blocks feature.
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', $this->_constants->MAIN_PLUGIN_FILE_PATH, true );
        }
    }

    /**
     * Execute plugin bootstrap code.
     *
     * @inherit ACFWF\Interfaces\Model_Interface
     *
     * @since 1.0
     * @access public
     */
    public function run() {
        // Internationalization.
        add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

        // Execute plugin activation/deactivation.
        register_activation_hook( $this->_constants->MAIN_PLUGIN_FILE_PATH, array( $this, 'activate_plugin' ) );
        register_deactivation_hook( $this->_constants->MAIN_PLUGIN_FILE_PATH, array( $this, 'deactivate_plugin' ) );

        // Execute plugin initialization ( plugin activation ) on every newly created site in a multi site set up.
        add_action( 'wpmu_new_blog', array( $this, 'new_mu_site_init' ), 10, 6 );

        // Execute codes that need to run on 'init' hook.
        add_action( 'init', array( $this, 'initialize' ) );

        add_action( 'after_setup_theme', array( $this, 'maybe_add_html5_script_support' ) );

        // Add settings link to plugin action links.
        add_filter( 'plugin_action_links_' . $this->_constants->PLUGIN_BASENAME, array( $this, 'plugin_settings_action_link' ), 10 );

        // Declare HPOS compatibility with WooCommerce.
        add_action( 'before_woocommerce_init', array( $this, 'declare_woocommerce_features_compatibility' ) );
    }
}

<?php
/*
* Plugin Name: Easy Booking for WooCommerce
* Plugin URI: https://easy-booking.pro/
* Description: A powerful and easy to use booking plugin for your WooCommerce store.
* Version: 3.2.0
* Author: @_Ashanna
* Author URI: https://easy-booking.pro/
* Requires at least: 5.0
* Tested up to: 6.4.2
* WC tested up to: 8.4.0
* WC requires at least: 3.0
* Text domain: woocommerce-easy-booking-system
* Domain path: /languages
* Licence : GPLv3
*/

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Easy_Booking' ) ) :

class Easy_Booking {

    protected static $_instance = null;
    public $allowed_types;

    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    public function __construct() {

        register_activation_hook( __FILE__, array( $this, 'wceb_activate' ) );

        $plugin = plugin_basename( __FILE__ );

        // Check if WooCommerce is active
        if ( ! $this->woocommerce_is_active() ) {
            return;
        }

        // Declare compatibility with HPOS (WooCommerce > 8.2)
        add_action( 'before_woocommerce_init', function() {
            if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            }
        } );

        add_action( 'plugins_loaded', array( $this, 'init' ), 10 );
        add_filter( 'plugin_action_links_' . $plugin, array( $this, 'add_settings_link' ) );

        do_action( 'easy_booking_after_init' );

    }

    /**
    *
    * Run this on activation.
    * Set a transient so that we know we've just activated the plugin.
    *
    **/
    public function wceb_activate() {
        set_transient( 'wceb_activated', 1 );
    }

    /**
    *
    * Get the current plugin version
    *
    * @deprecated 2.3.0 - Use wceb_get_version() instead.
    * @return str
    *
    **/
    public function wceb_get_version() {
        return '3.2.0';
    }

    /**
    *
    * Check if WooCommerce is active
    *
    * @return bool
    *
    **/
    public function woocommerce_is_active() {

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return ( array_key_exists( 'woocommerce/woocommerce.php', $active_plugins ) || in_array( 'woocommerce/woocommerce.php', $active_plugins ) );

    }

    /**
    *
    * Init plugin
    *
    **/
    public function init() {
        
        // Define constants
        $this->define_constants();

        // Load textdomain
        load_plugin_textdomain( 'woocommerce-easy-booking-system', false, basename( dirname( __FILE__ ) ) . '/languages/' );

        // Backward compatibility 2.2.4
        $this->allowed_types = apply_filters(
            'easy_booking_allowed_product_types',
            array(
                'simple',
                'variable',
                'grouped',
                'bundle'
            )
        );

        // Common includes
        $this->includes();

        // Admin includes
        if ( is_admin() ) {
            $this->admin_includes();
        }
        
        // Frontend includes
        if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
            $this->frontend_includes();
        }

    }

    /**
    *
    * Define constants
    * WCEB_PLUGIN_FILE - Plugin directory
    * WCEB_LANG - Site language to load pickadate.js translations
    * WCEB_PATH - Path to assets (dev or not)
    * WCEB_SUFFIX - Suffix for the assets (minified or not)
    *
    **/
    private function define_constants() {

        // Plugin directory
        define( 'WCEB_PLUGIN_FILE', __FILE__ );

        // Get page language in order to load Pickadate translation
        $site_language = get_bloginfo( 'language' );
        $lang          = str_replace( '-', '_', $site_language );

        // Site language
        define( 'WCEB_LANG', $lang );

        $path = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'dev/' : '';
        $min  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        // Path and suffix to load minified (or not) files
        define( 'WCEB_PATH', $path );
        define( 'WCEB_SUFFIX', $min );

    }

    /**
    *
    * Common includes
    *
    **/
    public function includes() {

        // Legacy
        include_once( 'includes/legacy/wceb-legacy-settings.php' );
        include_once( 'includes/legacy/wceb-legacy-functions.php' );
        include_once( 'includes/legacy/wceb-legacy-booking-data.php' );
        include_once( 'includes/legacy/wceb-legacy-addons.php' );

        // Functions
        include_once( 'includes/common/functions/wceb-core-functions.php');
        include_once( 'includes/common/functions/wceb-misc-functions.php');
        include_once( 'includes/common/functions/wceb-date-functions.php');
        include_once( 'includes/common/functions/wceb-product-functions.php');

        // Pickadate assets
        include_once( 'includes/common/class-wceb-pickadate.php' );

        // Other
        include_once( 'includes/common/class-wceb-checkout.php' );
        include_once( 'includes/common/class-wceb-wc-rest-api.php' );
        include_once( 'includes/common/class-wceb-booking-statuses.php' );

        // Third party
        include_once( 'includes/common/third-party/class-wceb-third-party-plugins.php' );

    }

    /**
    *
    * Admin includes
    *
    **/
    public function admin_includes() {

        // Admin
        include_once( 'includes/admin/wceb-admin-notices.php' );
        include_once( 'includes/admin/functions/wceb-update-functions.php' );
        include_once( 'includes/admin/class-wceb-admin-assets.php' );
        include_once( 'includes/admin/class-wceb-admin-ajax.php' );

        // Settings
        include_once( 'includes/legacy/wceb-legacy-settings-functions.php' );
        include_once( 'includes/settings/class-wceb-settings-functions.php' );
        include_once( 'includes/settings/class-wceb-admin-menu.php' );
        include_once( 'includes/settings/class-wceb-settings-page.php' );
        include_once( 'includes/settings/class-wceb-tools-page.php' );
        include_once( 'includes/settings/class-wceb-pro-page.php' );
        include_once( 'includes/settings/class-wceb-settings-general.php' );
        include_once( 'includes/settings/class-wceb-settings-appearance.php' );
        include_once( 'includes/settings/class-wceb-settings-statuses.php' );

        // Reports
        include_once( 'includes/reports/class-wceb-reports-page.php' );
        include_once( 'includes/reports/wceb-reports-functions.php' );
        include_once( 'includes/reports/class-wceb-reports-bookings.php' );
        include_once( 'includes/reports/class-wceb-reports-calendar.php' );
        include_once( 'includes/reports/class-wceb-list-bookings.php' );

        // Products and orders
        include_once( 'includes/admin/functions/wceb-admin-product-functions.php' );
        include_once( 'includes/admin/class-wceb-admin-product.php' );
        include_once( 'includes/admin/class-wceb-admin-variation.php' );
        include_once( 'includes/admin/class-wceb-order.php' );

    }

    /**
    *
    * Frontend
    *
    **/
    public function frontend_includes() {
        
        // Product and variation hooks
        include_once( 'includes/class-wceb-product.php' );
        include_once( 'includes/class-wceb-variable-product.php' );

        // Product page
        include_once( 'includes/wceb-single-product.php' );

        // Frontend assets
        include_once( 'includes/class-wceb-assets.php' );

        // Ajax
        include_once( 'includes/class-wceb-ajax.php' );

        // Date selection helper
        include_once( 'includes/class-wceb-date-selection.php' );

        // Cart hooks
        include_once( 'includes/class-wceb-cart.php' );
        
    }

    /**
    *
    * Add settings link
    *
    **/
    public function add_settings_link( $links ) {

        $settings_link = '<a href="admin.php?page=easy-booking">' . esc_html__( 'Settings', 'woocommerce-easy-booking-system' ) . '</a>';
        array_push( $links, $settings_link );

        return $links;

    }
    
    /**
    *
    * Backward compatibility
    *
    **/
    public function easy_booking_is_bookable( $product_id, $variation_id = '' ) {

        $product = wc_get_product( empty( $variation_id ) ? $product_id : $variation_id );
        return wceb_is_bookable( $product );
        
    }

}

function WCEB() {
    return Easy_Booking::instance();
}

WCEB();

endif;
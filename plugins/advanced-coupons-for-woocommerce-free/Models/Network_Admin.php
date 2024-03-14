<?php
namespace ACFWF\Models;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the implementation for the multisite license pages.
 * Public Model.
 *
 * @since 4.5.2
 */
class Network_Admin implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of URL_Coupon.
     *
     * @since 4.5.2
     * @access private
     * @var Network_Admin
     */
    private static $_instance;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 4.5.2
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 4.5.2
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;

    /**
     * Property that houses the list of plugins that requires a license.
     *
     * @since 4.5.2
     * @access private
     * @var array
     */
    private $_license_plugins = array();

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.5.2
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 4.5.2
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Network_Admin
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $main_plugin, $constants, $helper_functions );
        }

        return self::$_instance;
    }

    /**
     * Register advanced coupons network menu.
     *
     * @since 4.5.2
     * @access public
     */
    public function register_advanced_coupons_network_menu() {

        $license_plugins = $this->_get_license_plugins();

        if ( ! is_array( $license_plugins ) || empty( $license_plugins ) ) {
            return;
        }

        add_menu_page(
            __( 'Advanced Coupons', 'advanced-coupons-for-woocommerce-free' ),
            __( 'Advanced Coupons', 'advanced-coupons-for-woocommerce-free' ),
            'manage_sites',
            'advanced-coupons',
            array( $this, 'display_advanced_coupons_network_licenses_page' ),
            'dashicons-tickets-alt'
        );
    }

    /**
     * Display the advanced coupons network admin page.
     *
     * @since 4.5.2
     * @access public
     */
    public function display_advanced_coupons_network_licenses_page() {
        $license_plugins = $this->_get_license_plugins();
        $current_tab     = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $acfw_logo       = $this->_constants->IMAGES_ROOT_URL . 'acfw-logo.png';

        // Automatically Set default view.
        if ( '' === $current_tab ) {
            // Redirect to 1st tab if current tab is empty.
            if ( is_array( $license_plugins ) && ! empty( $license_plugins ) ) {
                wp_safe_redirect( $license_plugins[ array_key_first( $license_plugins ) ]['url'] );
                exit;
            }
        }

        // Load tab view and content.
        include $this->_constants->VIEWS_ROOT_PATH . 'network' . DIRECTORY_SEPARATOR . 'view-network-licenses-page.php';
    }

    /**
     * Get the list of advanced coupon plugins data.
     *
     * @since 4.5.2
     * @access public
     *
     * @return array List of advanced coupon plugins.
     */
    private function _get_license_plugins() {
        $license_plugins = apply_filters( 'acfw_network_menu_page_plugins', $this->_license_plugins );
        $defaults        = array(
            'url'  => network_admin_url( 'admin.php?page=advanced-coupons' ),
            'key'  => '',
            'name' => '',
        );

        // make sure that the required keys are set for each license plugins.
        if ( ! empty( $license_plugins ) ) {
            $license_plugins = array_map(
                function ( $license_plugin ) use ( $defaults ) {
                    return wp_parse_args( $license_plugin, $defaults );
                },
                $license_plugins
            );
        }

        return $license_plugins;
    }



    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute Network_Admin class.
     *
     * @since 4.5.2
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {

        add_action( 'network_admin_menu', array( $this, 'register_advanced_coupons_network_menu' ) );
    }
}

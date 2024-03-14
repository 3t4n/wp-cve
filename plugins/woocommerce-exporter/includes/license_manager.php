<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * License Manager
 *
 * @since 2.7.2
 */
class WCSE_License_Manager {

    /**
     * Property that holds the single main instance of WCSE_License_Manager.
     *
     * @access private
     * @since 2.7.2
     * @var WCSE_License_Manager
     */
    private static $_instance;

    /**
     * Property that holds the slug of the license settings page.
     *
     * @since 2.7.2
     * @access private
     * @var string
     */
    private $_settings_page_slug;

    /**
     * WCSE_License_Manager constructor.
     *
     * @access public
     * @since 2.7.2
     */
    public function __construct() {
        $this->_settings_page_slug = 'visserlabs_license_settings';

        if ( is_multisite() && get_current_blog_id() === 1 ) {

            // Add Visser Labs license settings menu in multi-site environment.
            add_action( 'network_admin_menu', array( $this, 'register_ms_wcse_licenses_settings_menu' ), 10 );

        } else {

            // Add Add Visser Labs license settings menu.
            add_action( 'admin_menu', array( $this, 'register_wcse_license_settings_menu' ), 10 );

        }

        // Enqueue license settings page scripts and styles.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_wcse_license_settings_page_scripts' ), 10 );

        // Load the license settings page content.
        add_action( 'vl_license_settings_page_content', array( $this, 'load_wcse_license_settings_page_content' ), 10 );
    }

    /**
     * Ensure that only one instance of WCSE_License_Manager is loaded or can be loaded (Singleton Pattern).
     *
     * @param array $dependencies Array of instance objects of all dependencies of WCSE_License_Manager model.
     *
     * @return WCSE_License_Manager
     * @since 2.7.2
     */
    public static function instance( $dependencies = null ) {

        if ( ! self::$_instance instanceof self ) {
            self::$_instance = new self( $dependencies );
        }

        return self::$_instance;
    }

    /**
     * Register Visser Labs license settings menu.
     *
     * @access public
     * @since 2.7.2
     */
    public function register_wcse_license_settings_menu() {
        if ( ! defined( 'VISSERLABS_LICENSE_SETTINGS_PAGE' ) ) {

            if ( ! defined( 'VISSERLABS_LICENSE_SETTINGS_DEFAULT_PLUGIN' ) ) {
                define( 'VISSERLABS_LICENSE_SETTINGS_DEFAULT_PLUGIN', 'wcse' );
            }

            add_submenu_page(
                'options-general.php',
                __( 'Visser Labs License', 'woocommerce-exporter' ),
                __( 'Visser Labs License', 'woocommerce-exporter' ),
                'manage_woocommerce',
                $this->_settings_page_slug,
                array( $this, 'wcse_license_settings_page' )
            );

            define( 'VISSERLABS_LICENSE_SETTINGS_PAGE', 'store-exporter' );
        }
    }

    /**
     * Register Visser Labs license settings menu in multi-site environment.
     *
     * @access public
     * @since 2.7.2
     */
    public function register_ms_wcse_licenses_settings_menu() {
        if ( ! defined( 'VISSERLABS_LICENSE_SETTINGS_PAGE' ) ) {

            if ( ! defined( 'VISSERLABS_LICENSE_SETTINGS_DEFAULT_PLUGIN' ) ) {
                define( 'VISSERLABS_LICENSE_SETTINGS_DEFAULT_PLUGIN', 'wcse' );
            }

            add_menu_page(
                __( 'Visser Labs License', 'woocommerce-exporter' ),
                __( 'Visser Labs License', 'woocommerce-exporter' ),
                'manage_sites',
                $this->_settings_page_slug,
                array( $this, 'wcse_license_settings_page' )
            );

            define( 'VISSERLABS_LICENSE_SETTINGS_PAGE', 'store-exporter' );
        }
    }

    /**
     * Load the license settings page.
     *
     * @access public
     * @since 2.7.2
     */
    public function wcse_license_settings_page() {
        $tab = filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) ?? '';
        $tab = htmlspecialchars( $tab, ENT_QUOTES, 'UTF-8' );
        $tab = empty( $tab ) ? 'store-exporter' : $tab;

        $vl_plugins_tabs = array(
            array(
                'id'     => 'store-exporter',
                'title'  => __( 'Store Exporter', 'woocommerce-exporter' ),
                'url'    =>
                    is_multisite() ?
                        network_admin_url( 'admin.php?page=visserlabs_license_settings&tab=store-exporter' ) :
                        admin_url( 'options-general.php?page=visserlabs_license_settings&tab=store-exporter' ),
                'active' => 'store-exporter' === $tab ?? true,
            ),
        );

        if ( $this->_is_product_importer_active() || $this->_is_product_importer_deluxe_active() ) {
            $vl_plugins_tabs[] = array(
                'id'     => 'product-importer',
                'title'  => __( 'Product Importer', 'woocommerce-exporter' ),
                'url'    => is_multisite() ?
                    network_admin_url( 'admin.php?page=visserlabs_license_settings&tab=product-importer' ) :
                    admin_url( 'options-general.php?page=visserlabs_license_settings&tab=product-importer' ),
                'active' => 'product-importer' === $tab ?? true,
            );
        }

        require_once WOO_CE_PATH . 'templates/admin/license-settings-page.php';
    }

    /**
     * Enqueue license settings page scripts and styles.
     *
     * @access public
     * @since 2.7.2
     */
    public function enqueue_wcse_license_settings_page_scripts() {
        $page = filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW ) ?? '';
        $page = htmlspecialchars( $page, ENT_QUOTES, 'UTF-8' );

        if ( $this->_settings_page_slug === $page ) {
            // Lightbox JS.
            wp_enqueue_style( 'lightbox-css', plugins_url( '/js/lightbox/css/lightbox.min.css', WOO_CE_RELPATH ), array(), WOO_CE_VERSION );
            wp_enqueue_script( 'lightbox-js', plugins_url( '/js/lightbox/js/lightbox.min.js', WOO_CE_RELPATH ), array( 'jquery' ), WOO_CE_VERSION, true );

            wp_enqueue_style( 'wcse-license-settings-style', plugins_url( '/css/license-settings.css', WOO_CE_RELPATH ), array(), WOO_CE_VERSION );
        }
    }

    /**
     * Load the license settings page content.
     *
     * @access public
     * @since 2.7.2
     */
    public function load_wcse_license_settings_page_content() {
        $tab = filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) ?? '';
        $tab = htmlspecialchars( $tab, ENT_QUOTES, 'UTF-8' );

        if ( empty( $tab ) || 'store-exporter' === $tab ) {
            include_once WOO_CE_PATH . 'templates/admin/license-settings-store-exporter-page.php';
        }
    }

    /**
     * Check if Product Importer is active.
     *
     * @access private
     * @since 2.7.2
     *
     * @return bool
     */
    private function _is_product_importer_active() {
        return is_plugin_active( 'woocommerce-product-importer/product-importer.php' );
    }

    /**
     * Check if Product Importer deluxe is active.
     *
     * @access private
     * @since 2.7.2
     *
     * @return bool
     */
    private function _is_product_importer_deluxe_active() {
        return is_plugin_active( 'woocommerce-product-importer-deluxe/product-importer-deluxe.php' );
    }
}

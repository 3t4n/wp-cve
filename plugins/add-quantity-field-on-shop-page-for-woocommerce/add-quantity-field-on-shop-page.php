<?php
/*
 * Plugin Name: Add Quantity Field on Shop Page for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/add-quantity-field-on-shop-page-for-woocommerce/
 * Description: Display quantity field on the Shop / Archive page of WooCommerce.
 * Author: Tanvirul Haque
 * Version: 1.0.16
 * Author URI: http://wpxpress.net
 * Text Domain: add-quantity-field-on-shop-page
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 4.8
 * Tested up to: 6.4
 * WC requires at least: 4.5
 * WC tested up to: 8.4
 * License: GPLv2+
*/

// Don't call the file directly
defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! class_exists( 'Woo_Add_Quantity_Field_on_Shop_Page' ) ) {

    /**
     * Main Class
     * @since 1.0.0
     */
    class Woo_Add_Quantity_Field_on_Shop_Page {

        /**
         * Version
         *
         * @since 1.0.0
         * @var  string
         */
        public $version = '1.0.16';

        /**
         * The Single Instance of The Class.
         */
        protected static $instance = null;

        /**
         * Constructor for The Class.
         *
         * Sets up all the appropriate hooks and actions
         *
         * @return void
         * @since 1.0.0
         */
        public function __construct() {
            // Define constants
            $this->define_constants();

            // Include required files
            $this->includes();

            // Initialize the action hooks
            $this->init_hooks();
        }

        /**
         * Initializes The Class.
         *
         * Checks for an existing instance
         * and if it doesn't find one, creates it.
         *
         * @return object Class instance
         * @since 1.0.0
         */
        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Define constants
         *
         * @return void
         * @since 1.0.3
         *
         */
        private function define_constants() {
            define( 'AQF_VERSION', $this->version );
            define( 'AQF_FILE', __FILE__ );
            define( 'AQF_DIR_PATH', plugin_dir_path( AQF_FILE ) );
            define( 'AQF_DIR_URI', plugin_dir_url( AQF_FILE ) );
            define( 'AQF_INCLUDES', AQF_DIR_PATH . 'includes' );
            define( 'AQF_ASSETS', AQF_DIR_URI . 'assets' );
        }


        /**
         * Include required files
         *
         * @return void
         * @since 1.0.3
         *
         */
        private function includes() {
            if ( is_admin() && $this->is_wc_active() && ! class_exists( 'Woo_Disable_Variable_Price_Range' ) ) {
                require_once AQF_INCLUDES . '/admin/class-aqf_plugin_installer.php';
            }
		}


        /**
         * Init Hooks
         *
         * @return void
         * @since 1.0.0
         */
        private function init_hooks() {
            add_action( 'init', array( $this, 'localization_setup' ) );
            add_action( 'admin_notices', array( $this, 'php_requirement_notice' ) );
            add_action( 'admin_notices', array( $this, 'wc_requirement_notice' ) );
            add_action( 'admin_notices', array( $this, 'wc_version_requirement_notice' ) );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

            if ( $this->is_wc_active() ) {
                add_action( 'woocommerce_after_shop_loop_item', array( $this, 'quantity_field' ), 9 );
                add_action( 'init', array( $this, 'add_to_cart_quantity_handler' ) );

                // add_shortcode( 'aqf_quantity_field', array( $this, 'quantity_field_shortcode' ) );
            }

			// Add 3rd party compatibility
	        require_once AQF_INCLUDES . '/plugin_compatibility.php';
        }


        /**
         * Initialize Plugin for Localization
         *
         * @return void
         * @since 1.0.0
         *
         */
        public function localization_setup() {
            load_plugin_textdomain( 'add-quantity-field-on-shop-page', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }
        

        /**
         * Adding Quantity Field
         *
         * @since 1.0.0
         */
        public function quantity_field() {
            $product = wc_get_product( get_the_ID() );
            $is_variable_enable = false;

            if ( class_exists( 'Woo_Variation_Swatches_Pro' ) ) {
                $get_wvsp_options       = get_option( 'woo_variation_swatches' );
                $is_enable_swatches     = array_key_exists( 'show_on_archive', $get_wvsp_options ) ? $get_wvsp_options['show_on_archive'] : 'yes';
                $is_enable_catalog_mode = array_key_exists( 'enable_catalog_mode', $get_wvsp_options ) ? $get_wvsp_options['enable_catalog_mode'] : 'no';
                $is_variable_enable     = ( 'variable' == $product->get_type() && 'yes' == $is_enable_swatches && 'no' == $is_enable_catalog_mode ) ? true : false ;
            }

            if ( $product && ( 'simple' == $product->get_type() || $is_variable_enable ) && ! $product->is_sold_individually() && $product->is_purchasable() && $product->is_in_stock() ) {
                woocommerce_quantity_input( array(
                    'min_value' => 1,
                    'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity()
                ) );
            }
        }

        /**
         * Adding Add To Cart Quantity Handler
         *
         * @since 1.0.0
         */
        public function add_to_cart_quantity_handler() {
            wc_enqueue_js( '
                jQuery( ".type-product" ).on( "click", ".quantity input", function() {
                    return false;
                } );
                
                jQuery( ".type-product" ).on( "change input", ".quantity .qty", function() {
                    var add_to_cart_button = jQuery( this ).parents( ".product" ).find( ".add_to_cart_button" );
                    
                    // For AJAX add-to-cart actions
                    add_to_cart_button.attr( "data-quantity", jQuery( this ).val() );
                    
                    // For non-AJAX add-to-cart actions
                    add_to_cart_button.attr( "href", "?add-to-cart=" + add_to_cart_button.attr( "data-product_id" ) + "&quantity=" + jQuery( this ).val() );
                } );
                
                // Trigger on Enter press
                jQuery( ".woocommerce .products" ).on( "keypress", ".quantity .qty", function(e) {
                    if ( ( e.which || e.keyCode ) === 13 ) {
                        jQuery( this ).parents( ".product" ).find( ".add_to_cart_button" ).trigger( "click" );
                    }
                } );
            ' );
        }


        /**
         * Add plugin row meta
         */
        public function plugin_row_meta( $links, $file ) {
            if ( plugin_basename( AQF_FILE ) !== $file ) {
                return $links;
            }

            $report_url = 'https://wpxpress.net/submit-ticket/';

            $row_meta['support'] = sprintf( '<a target="_blank" href="%1$s">%2$s</a>', esc_url( $report_url ), esc_html__( 'Get Help &amp; Support', 'add-quantity-field-on-shop-page' ) );

            return array_merge( $links, $row_meta );
        }

        /**
         * PHP Version
         *
         * @return bool|int
         */
        public function is_required_php_version() {
            return version_compare( PHP_VERSION, '5.6.0', '>=' );
        }

        /**
         * PHP Requirement Notice
         */
        public function php_requirement_notice() {
            if ( ! $this->is_required_php_version() ) {
                $class   = 'notice notice-error';
                $text    = esc_html__( 'Please check PHP version requirement.', 'add-quantity-field-on-shop-page' );
                $link    = esc_url( 'https://docs.woocommerce.com/document/server-requirements/' );
                $message = wp_kses( __( "It's required to use latest version of PHP to use <strong>WooCommerce - Add Quantity Field on Shop Page</strong>.", 'add-quantity-field-on-shop-page' ), array( 'strong' => array() ) );

                printf( '<div class="%1$s"><p>%2$s <a target="_blank" href="%3$s">%4$s</a></p></div>', $class, $message, $link, $text );
            }
        }

        /**
         * WooCommerce Requirement Notice
         */
        public function wc_requirement_notice() {
            if ( ! $this->is_wc_active() ) {
                $class = 'notice notice-error';
                $text  = esc_html__( 'WooCommerce', 'add-quantity-field-on-shop-page' );

                $link = esc_url( add_query_arg( array(
                    'tab'       => 'plugin-information',
                    'plugin'    => 'woocommerce',
                    'TB_iframe' => 'true',
                    'width'     => '640',
                    'height'    => '500',
                ), admin_url( 'plugin-install.php' ) ) );

                $message = wp_kses( __( "<strong>WooCommerce - Add Quantity Field on Shop Page</strong> is an add-on of ", 'add-quantity-field-on-shop-page' ), array( 'strong' => array() ) );

                printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', $class, $message, $link, $text );
            }
        }

        /**
         * WooCommerce Version
         */
        public function is_required_wc_version() {
            return version_compare( WC_VERSION, '3.2', '>' );
        }

        /**
         * WooCommerce Version Requirement Notice
         */
        public function wc_version_requirement_notice() {
            if ( $this->is_wc_active() && ! $this->is_required_wc_version() ) {
                $class   = 'notice notice-error';
                $message = sprintf( esc_html__( "Currently, you are using older version of WooCommerce. It's recommended to use latest version of WooCommerce to work with %s.", 'add-quantity-field-on-shop-page' ), esc_html__( 'WooCommerce - Add Quantity Field on Shop Page', 'add-quantity-field-on-shop-page' ) );
                printf( '<div class="%1$s"><p><strong>%2$s</strong></p></div>', $class, $message );
            }
        }

        /**
         * Check WooCommerce Activated
         */
        public function is_wc_active() {
            return class_exists( 'WooCommerce' );
        }
    }
}

/**
 * Initialize the plugin
 *
 * @return object
 */
function woo_add_quantity_field_on_shop_page() {
    return Woo_Add_Quantity_Field_on_Shop_Page::instance();
}

// Kick Off
// woo_add_quantity_field_on_shop_page();
    
add_action( 'plugins_loaded', 'woo_add_quantity_field_on_shop_page', 20 );


<?php
/*
 * Plugin Name: Variation Price Display For WooCommerce
 * Plugin URI: https://wordpress.org/plugins/disable-variable-product-price-range-show-only-lowest-price-in-variable-products/
 * Description: Disable Price Range and shows only the lowest price and sale price in the WooCommerce variable products.
 * Author: Tanvirul Haque
 * Version: 1.1.12
 * Author URI: https://wpxpress.net
 * Text Domain: woo-disable-variable-product-price-range
 * Domain Path: /languages
 * Requires PHP: 5.6
 * Requires at least: 4.8
 * Tested up to: 6.2
 * WC requires at least: 4.5
 * WC tested up to: 7.8
 * License: GPLv2+
*/

// Don't call the file directly
defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! class_exists( 'Woo_Disable_Variable_Price_Range' ) ) {

    /**
     * Main Class
     * @since 1.0.0
     */
    class Woo_Disable_Variable_Price_Range {

        /**
         * Version
         *
         * @since 1.0.0
         * @var  string
         */
        public $version = '1.1.12';


        /**
         * The single instance of the class.
         */
        protected static $instance = null;


        /**
         * Constructor for the class
         *
         * Sets up all the appropriate hooks and actions
         *
         * @return void
         * @since 1.0.0
         */
        public function __construct() {
            // Include required files
            $this->includes();

            // Initialize the action hooks
            $this->init_hooks();
        }


        /**
         * Initializes the class
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
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

            if ( 'no' == get_option( 'wclp_enable' ) ) {
                return;
            }

	        add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'disable_variable_price_range' ), 10, 2 );
            add_filter( 'woocommerce_variable_price_html', array( $this, 'disable_variable_price_range' ), 10, 2 );

			/* Hide Reset link from product page */
	        if ( 'yes' == get_option( 'wclp_hide_reset' ) ) {
		        add_filter( 'woocommerce_reset_variations_link', '__return_false', 10 );
	        }
		}

        /**
         * Include required files
         *
         * @return void
         * @since 1.0.7
         *
         */
        function includes() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
        }


        /**
         * Initialize plugin for localization
         *
         * @return void
         * @since 1.0.0
         *
         */
        public function localization_setup() {
            load_plugin_textdomain( 'woo-disable-variable-product-price-range', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }


        /**
         * Plugin action links
         */
        public function plugin_action_links( $links ) {
            $new_links     = array();
            $settings_link = esc_url( add_query_arg( array(
                'page'    => 'wc-settings',
                'tab'     => 'products',
                'section' => 'woo-variable-lowest-price'
            ), admin_url( 'admin.php' ) ) );

            $new_links[ 'settings' ] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $settings_link, esc_attr__( 'Settings', 'woo-disable-variable-product-price-range' ) );
            $pro_link = 'https://wpxpress.net/products/woocommerce-variation-price-display/';

            if ( ! class_exists( 'Woo_Disable_Variable_Price_Range_Pro' ) ) {
                $new_links['go-pro'] = sprintf( '<a target="_blank" style="color: #45b450; font-weight: bold;" href="%1$s" title="%2$s">%2$s</a>', esc_url( $pro_link ), esc_attr__( 'Go Pro', 'woo-disable-variable-product-price-range' ) );
            }

            return array_merge( $links, $new_links );
        }

        /**
         * Add plugin row meta
         */
        public function plugin_row_meta( $links, $file ) {
            if ( plugin_basename( __FILE__ ) !== $file ) {
                return $links;
            }

            $report_url = 'https://wpxpress.net/submit-ticket/';
            $documentation_url = 'https://wpxpress.net/docs/woocommerce-variation-price-display/';

            $row_meta['docs']    = sprintf( '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>', esc_url( $documentation_url ), esc_html__( 'Documentation', 'woo-disable-variable-product-price-range' ) );
            $row_meta['support'] = sprintf( '<a target="_blank" href="%1$s">%2$s</a>', esc_url( $report_url ), esc_html__( 'Get Help &amp; Support', 'woo-disable-variable-product-price-range' ) );

            return array_merge( $links, $row_meta );
        }


        /**
         * Disable Variable Price Range Function
         *
         * @param $price
         * @param $product
         *
         * @return string
         * @since 1.0.0
         */
        public function disable_variable_price_range( $price, $product ) {
            $price_type         = apply_filters( 'woo_variation_price_display_type', get_option( 'wclp_price_types', 'min' ), $product );
	        $price_title_before = get_option( 'wclp_title_before', 'From:' );
	        $price_title_after  = get_option( 'wclp_title_after', '' );
	        $hide_crossed_price = get_option( 'wclp_crossed_price', 'no' );
	        $enable_on_shop     = get_option( 'wclp_enable_shop', 'yes' );

            if ( apply_filters( 'woo_variation_price_display_disable', false, $product ) ) {
                return $price;
            }

	        if ( 'no' == $enable_on_shop && ( is_shop() || is_product_taxonomy() ) ) {
		        return $price;
	        }

	        $prefix = apply_filters( 'woo_variation_price_title_prefix', sprintf( '%s', __( $price_title_before, 'woo-disable-variable-product-price-range' ) ), $product );
            $suffix = apply_filters( 'woo_variation_price_title_suffix', sprintf( '%s', __( $price_title_after, 'woo-disable-variable-product-price-range' ) ), $product );

            if ( 'min-to-max' == $price_type || 'max-to-min' == $price_type ) {
                $min_price  = $product->get_variation_price( 'min', true );
                $max_price  = $product->get_variation_price( 'max', true );
            }

            // Minimum to Maximum Price
            if ( 'min-to-max' == $price_type ) {
                $price = wc_format_price_range( $min_price, $max_price );

                $price = sprintf( '%1$s %2$s %3$s',
                    $prefix,
                    $price,
                    $suffix
                );

                return apply_filters( 'wdvpr_price_html', $price, $product );
            }

            // Maximum to Minimum Price
            if ( 'max-to-min' == $price_type ) {
                $price = wc_format_price_range( $max_price, $min_price );

                $price = sprintf( '%1$s %2$s %3$s',
                    $prefix,
                    $price,
                    $suffix
                );

                return apply_filters( 'wdvpr_price_html', $price, $product );
            }

            $min_var_reg_price  = $product->get_variation_regular_price( 'min', true );
            $max_var_reg_price  = $product->get_variation_regular_price( 'max', true );
            $min_var_sale_price = $product->get_variation_sale_price( 'min', true );
            $max_var_sale_price = $product->get_variation_sale_price( 'max', true );
            
            // Prepare price based on price type
            $variable_price = ( 'min' == $price_type ) ? $min_var_reg_price : $max_var_reg_price;
            $sale_price     = ( 'min' == $price_type ) ? $min_var_sale_price : $max_var_sale_price;

            // $price = ( $product->is_on_sale() ) ? sprintf( '%1$s <del>%2$s</del> <ins>%3$s</ins>', $prefix, wc_price( $max_var_reg_price ), wc_price( $min_var_sale_price ) ) : sprintf( '%1$s %2$s', $prefix, wc_price( $min_var_reg_price ) );

            if ( $product->is_on_sale() ) {
                // Run if variation on sale

                // if variable price lower then sale price
                // $variable_price = ( $variable_price < $sale_price ) ? $max_var_reg_price : $variable_price;

                if ( $variable_price == $sale_price ) {
                    $price = sprintf( '%1$s %2$s %3$s',
                        $prefix,
                        wc_price( $sale_price ),
                        $suffix
                    );
                } else {
	                if ( 'yes' == $hide_crossed_price ) {
		                $price = sprintf( '%1$s <ins>%2$s</ins> %3$s',
			                $prefix,
			                wc_price( $sale_price ),
			                $suffix
		                );
	                } else {
		                $price = sprintf( '%1$s <del>%2$s</del> <ins>%3$s</ins> %4$s',
			                $prefix,
			                wc_price( $variable_price ),
			                wc_price( $sale_price ),
			                $suffix
		                );
	                }
                }
            } else {
                // Run always
                $price = sprintf( '%1$s %2$s %3$s', $prefix, wc_price( $variable_price ), $suffix );
            }

            return apply_filters( 'wdvpr_price_html', $price, $product );
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
                $text    = esc_html__( 'Please check PHP version requirement.', 'woo-disable-variable-product-price-range' );
                $link    = esc_url( 'https://docs.woocommerce.com/document/server-requirements/' );
                $message = wp_kses( __( "It's required to use latest version of PHP to use <strong>WooCommerce - Show Only Lowest Price in Variable Products</strong>.", 'woo-disable-variable-product-price-range' ), array( 'strong' => array() ) );

                printf( '<div class="%1$s"><p>%2$s <a target="_blank" href="%3$s">%4$s</a></p></div>', $class, $message, $link, $text );
            }
        }


        /**
         * WooCommerce Requirement Notice
         */
        public function wc_requirement_notice() {
            if ( ! $this->is_wc_active() ) {
                $class = 'notice notice-error';
                $text  = esc_html__( 'WooCommerce', 'woo-disable-variable-product-price-range' );

                $link = esc_url( add_query_arg( array(
                    'tab'       => 'plugin-information',
                    'plugin'    => 'woocommerce',
                    'TB_iframe' => 'true',
                    'width'     => '640',
                    'height'    => '500',
                ), admin_url( 'plugin-install.php' ) ) );

                $message = wp_kses( __( "<strong>Variation Price Display For WooCommerce</strong> is an add-on of ", 'woo-disable-variable-product-price-range' ), array( 'strong' => array() ) );

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
                $message = sprintf( esc_html__( "Currently, you are using older version of WooCommerce. It's recommended to use latest version of WooCommerce to work with %s.", 'woo-disable-variable-product-price-range' ), esc_html__( 'Variation Price Display For WooCommerce', 'woo-disable-variable-product-price-range' ) );
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
function woo_disable_variable_price_range() {
    return Woo_Disable_Variable_Price_Range::instance();
}

// Kick Off
woo_disable_variable_price_range();
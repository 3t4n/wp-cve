<?php

namespace EasyBooking;

/**
*
* Load frontend assets.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Frontend_Assets {

	public function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 15 );
        
	}

	public function enqueue_scripts() {
        global $post;

        // Load scripts on single product page only
        if ( is_product() ) {

            $product = wc_get_product( $post->ID );

            // Don't load scripts if product is out-of-stock (non-variable products only)
            if ( ! $product->is_type( 'variable' ) && $product->managing_stock() && ! $product->is_in_stock() ) {
                return;
            }

            // Load scripts only on product page if "booking" option is checked
            if ( wceb_is_bookable( $product ) ) {

                // Register scripts
                $this->register_frontend_scripts( $product );

                // Register styles
                $this->register_frontend_styles();

                wp_enqueue_script( 'accounting' );
                wp_enqueue_script( 'pickadate' );

                // Hook to load additional scipts or stylesheets
                do_action( 'easy_booking_enqueue_additional_scripts', $product );

                wp_enqueue_script( 'wceb-datepickers' );
                wp_enqueue_script( 'wceb-single-product' );

                wp_enqueue_script( 'pickadate.language' );

                wp_enqueue_style( 'picker' );

                // Load Right to left CSS file if necessary
                if ( is_rtl() ) {
                    wp_enqueue_style( 'rtl-style' );
                }

            }

        }
        
    }

    /**
    *
    * Register frontend scripts.
    *
    **/
    private function register_frontend_scripts( $product ) {

        Pickadate::register_scripts();

        // Load accounting.js script
        wp_register_script(
            'accounting',
            WC()->plugin_url() . '/assets/js/accounting/accounting' . WCEB_SUFFIX . '.js',
            array( 'jquery' ),
            '0.4.2'
        );

        wp_register_script(
            'wceb-datepickers',
            wceb_get_file_path( '', 'wceb', 'js' ),
            array( 'jquery', 'pickadate', 'accounting' ),
            '1.0',
            true
        );

        $frontend_parameters = $this->get_frontend_parameters( $product );

        wp_localize_script(
            'wceb-datepickers',
            'wceb_object',
            $frontend_parameters
        );

        $product_type = $product->get_type();

        wp_register_script(
            'wceb-single-product',
            wceb_get_file_path( '', 'wceb-' . $product_type, 'js' ),
            array( 'jquery', 'pickadate', 'wceb-datepickers' ),
            '1.0',
            true
        );

    }

    /**
    *
    * Register frontend styles.
    *
    **/
    private function register_frontend_styles() {
        Pickadate::register_styles();
    }

    /**
    *
    * Get parameters to pass to frontend scripts.
    * @param WC_Product - $product
    *
    * @return array - $frontend_parameters
    *
    **/
    private function get_frontend_parameters( $product ) {

        // Ajax URL
        $home_url = apply_filters( 'easy_booking_home_url', home_url( '/' ) );
        $ajax_url = add_query_arg( 'wceb-ajax', '%%endpoint%%', $home_url );
        $ajax_url = str_replace( array( 'http:', 'https:' ), '', $ajax_url ); // Fix to avoid security fails

        // Product type
        $product_type = $product->get_type();

        // Days or Nights mode
        $booking_mode = get_option( 'wceb_booking_mode' );

        // Texts
        $start_date_text = wceb_get_start_text( $product );
        $end_date_text   = wceb_get_end_text( $product );

        // Last available date relative to the current day
        $last_date           = absint( get_option( 'wceb_last_available_date' ) );
        $current_date        = date( 'Y-m-d' );
        $last_available_date = date( 'Y-m-d', strtotime( $current_date . ' +' . $last_date . ' days' ) );

        // Booking settings and prices for each product ype
        $booking_settings = array();
        $children         = array();
        $prices           = array();
        switch ( $product_type ) {
            case 'variable' :

                $variation_ids = $product->get_children();

                if ( $variation_ids ) {

                    foreach ( $variation_ids as $variation_id ) {

                        $variation = wc_get_product( $variation_id );

                        if ( ! wceb_is_bookable( $variation ) ) {
                            continue;
                        }

                        // Get booking settings for the variation
                        $variation_settings = wceb_get_product_booking_settings( $variation );
                        
                        // Price suffix for each variation (" / day", " / night" or " / week")
                        $booking_settings['prices_html'][$variation_id] = wceb_get_product_price_suffix( $variation );

                        foreach ( $variation_settings as $setting => $value ) {
                            $booking_settings[$setting][$variation_id] = $value;
                        }

                    }

                } else {

                    // If no variation, use parent booking settings
                    $booking_settings = wceb_get_product_booking_settings( $product );

                }

            break;
            case 'grouped' :

                $booking_settings = wceb_get_product_booking_settings( $product );
                $booking_settings['prices_html'] = wceb_get_product_price_suffix( $product );

                // Get grouped product children prices
                $children = $product->get_children();

                if ( $children ) foreach ( $children as $child_id ) {
                    $child = wc_get_product( $child_id );
                    $prices['price'][$child_id] = wc_get_price_to_display( $child, array( 'price' => $child->get_price() ) );
                    $prices['regular_price'][$child_id] = wc_get_price_to_display( $child, array( 'price' => $child->get_regular_price() ) );
                }

            break;
            case 'bundle' :

                $booking_settings = wceb_get_product_booking_settings( $product );
                $booking_settings['prices_html'] = wceb_get_product_price_suffix( $product );

            break;
            default:

                $booking_settings = wceb_get_product_booking_settings( $product );
                $booking_settings['prices_html'] = wceb_get_product_price_suffix( $product );
                
            break;

        }
        
        // Datepickers parameters
        $frontend_parameters = array(
            'ajax_url'                     => esc_url_raw( $ajax_url ),
            'product_type'                 => esc_html( $product_type ),
            'children'                     => array_map( 'absint', $children),
            'calc_mode'                    => esc_html( $booking_mode ),
            'start_text'                   => esc_html( $start_date_text ),
            'end_text'                     => esc_html( $end_date_text ),
            'select_dates_message'         => esc_attr( wceb_get_select_dates_error_message( $product ) ),
            'booking_dates'                => wceb_sanitize_parameters( $booking_settings['booking_dates'], 'esc_html' ),
            'booking_duration'             => wceb_sanitize_parameters( $booking_settings['booking_duration'], 'absint' ),
            'min'                          => wceb_sanitize_parameters( $booking_settings['booking_min'], 'absint' ),
            'max'                          => wceb_sanitize_parameters( $booking_settings['booking_max'], 'esc_html' ),
            'first_date'                   => wceb_sanitize_parameters( $booking_settings['first_available_date'], 'absint' ),
            'last_date'                    => esc_html( $last_available_date ),
            'first_weekday'                => absint( get_option( 'start_of_week' ) ),
            'prices_html'                  => wceb_sanitize_parameters( $booking_settings['prices_html'], 'esc_html' ),
            'price_suffix'                 => $product->get_price_suffix(),
            'currency_format_num_decimals' => absint( get_option( 'woocommerce_price_num_decimals' ) ),
            'currency_format_symbol'       => get_woocommerce_currency_symbol(),
            'currency_format_decimal_sep'  => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
            'currency_format_thousand_sep' => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
            'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS
        );

        // Parameters for grouped products and bundles
        if ( isset( $prices['price'] ) ) {
            $frontend_parameters['product_price'] = wceb_sanitize_parameters( $prices['price'], 'wc_format_decimal' );
        }

        if ( isset( $prices['regular_price'] ) ) {
            $frontend_parameters['product_regular_price'] = wceb_sanitize_parameters( $prices['regular_price'], 'wc_format_decimal' );
        }

        return apply_filters( 'easy_booking_frontend_parameters', $frontend_parameters );

    }
}

new Frontend_Assets();
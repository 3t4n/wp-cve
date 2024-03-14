<?php

namespace EasyBooking;

/**
*
* Product settings.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Admin_Product {

    private $allowed_types;

	public function __construct() {

        add_action( 'product_type_options', array( $this, 'add_bookable_option' ), 10, 1 );
        add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_easy_booking_tab' ), 10, 1 );
        add_action( 'woocommerce_product_data_panels', array( $this, 'easy_booking_data_panel' ) );

        foreach ( wceb_get_allowed_product_types() as $type ) {
            add_action( 'woocommerce_process_product_meta_' . $type, array( $this, 'save_product_booking_options' ) );
        }

	}

    /**
    *
    * Add a "Bookable" checkbox to product options.
    *
    * @param array - $product_type_options
    * @return array - $product_type_options
    *
    **/
    public function add_bookable_option( $product_type_options ) {
        global $product_object;

        $is_bookable = is_a( $product_object, 'WC_Product' ) ? $product_object->get_meta( '_bookable', true ) : '';
        
        // Backward compatibility < 2.2.4
        if ( is_a( $product_object, 'WC_Product' ) && empty( $is_bookable ) ) {
            $is_bookable = $product_object->get_meta( '_booking_option', true );
        }

        $show = array();
        foreach ( wceb_get_allowed_product_types() as $type ) {
            $show[] = 'show_if_' . $type;
        }

        $product_type_options['wceb_bookable'] = array(
            'id'            => '_bookable',
            'wrapper_class' => implode( ' ', $show ),
            'label'         => __( 'Bookable', 'woocommerce-easy-booking-system' ),
            'description'   => __( 'Bookable products require the selection of one or two date(s).', 'woocommerce-easy-booking-system' ),
            'default'       => $is_bookable === 'yes' ? 'yes' : 'no'
        );

        return $product_type_options;

    }

    /**
    *
    * Add a "Bookings" tab to product options.
    *
    * @param array - $product_data_tabs
    * @return array - $product_data_tabs
    *
    **/
    public function add_easy_booking_tab( $product_data_tabs ) {

        $product_data_tabs['bookings'] = array(
                'label'    => __( 'Bookings', 'woocommerce-easy-booking-system' ),
                'target'   => 'booking_product_data',
                'class'    => array( 'show_if_bookable' ),
                'priority' => 15
        );

        return $product_data_tabs;

    }

    /**
    *
    * Add booking options in "Bookings" tab.
    *
    **/
    public function easy_booking_data_panel() {
        global $post;

        $product = wc_get_product( $post->ID );
        $product_type = $product->get_type();

        include( 'views/products/html-wceb-product-options.php' );

    }

    /**
    *
    * Save checkbox value and booking options for products.
    *
    * @param int $post_id
    *
    **/
    public function save_product_booking_options( $post_id ) {

        $booking_data = array(
            'bookable'                => isset( $_POST['_bookable'] ) ? 'yes' : 'no',
            'dates'                   => isset( $_POST['_number_of_dates'] ) ? $_POST['_number_of_dates'] : '',
            'booking_min'             => isset( $_POST['_booking_min'] ) ? $_POST['_booking_min'] : '',
            'booking_max'             => isset( $_POST['_booking_max'] ) ? $_POST['_booking_max'] : '',
            'first_available_date'    => isset( $_POST['_first_available_date'] ) ? $_POST['_first_available_date'] : '',
            'booking_duration'        => isset( $_POST['_booking_duration'] ) ? $_POST['_booking_duration'] : ''
        );

        wceb_save_product_booking_options( $post_id, $booking_data );

    }

}

new Admin_Product();
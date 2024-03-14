<?php

namespace EasyBooking;

/**
*
* Variation settings.
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

class Admin_Variation {

	public function __construct() {

		add_action( 'woocommerce_variation_options', array( $this, 'add_bookable_option' ), 10, 3 );
        add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variation_booking_options' ), 10, 3 );
        add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_booking_options' ), 10, 2 );

	}

    /**
    *
    * Add a "Bookable" checkbox to product variations.
    *
    * @param int - $loop
    * @param array - $variation_data
    * @param WP_POST - $variation
    *
    **/
    public function add_bookable_option( $loop, $variation_data, $variation ) {

        $variation_object = wc_get_product( $variation->ID );

        $is_bookable = is_a( $variation_object, 'WC_Product_Variation' ) ? $variation_object->get_meta( '_bookable', true ) : '';

        // Backward compatibility < 2.2.4
        if ( is_a( $variation_object, 'WC_Product_Variation' ) && empty( $is_bookable ) ) {
            $is_bookable = $variation_object->get_meta( '_booking_option', true );
        }
        
        ?>
        
            <label class="show_if_bookable">

                <input type="checkbox" class="checkbox variation_is_bookable" name="_var_bookable[<?php echo $loop; ?>]" <?php checked( $is_bookable, 'yes' ) ?> />
                <?php esc_html_e( 'Bookable', 'woocommerce-easy-booking-system' ); ?>

            </label>
        
        <?php
    }

    /**
    *
    * Display booking options for variations.
    *
    * @param int - $loop
    * @param array - $variation_data
    * @param WP_POST - $variation
    *
    **/
    public function variation_booking_options( $loop, $variation_data, $variation ) {

        $variation_id = $variation->ID;
        include( 'views/products/html-wceb-variation-options.php' );

    }

    /**
    *
    * Save checkbox value and booking options for variations.
    *
    * @param int $variation_id
    * @param int $i - The loop
    *
    **/
    public function save_variation_booking_options( $variation_id , $i ) {
        
        $booking_data = array(
            'bookable'                => isset( $_POST['_var_bookable'][$i] ) ? 'yes' : 'no',
            'dates'                   => isset( $_POST['_var_number_of_dates'][$i] ) ? $_POST['_var_number_of_dates'][$i] : '',
            'booking_min'             => isset( $_POST['_var_booking_min'][$i] ) ? $_POST['_var_booking_min'][$i] : '',
            'booking_max'             => isset( $_POST['_var_booking_max'][$i] ) ? $_POST['_var_booking_max'][$i] : '',
            'first_available_date'    => isset( $_POST['_var_first_available_date'][$i] ) ? $_POST['_var_first_available_date'][$i] : '',
            'booking_duration'        => isset( $_POST['_var_booking_duration'][$i] ) ? $_POST['_var_booking_duration'][$i] : ''
        );

        wceb_save_product_booking_options( $variation_id, $booking_data );

    }

}

new Admin_Variation();
<?php


/**
*
* Display bookable product options.
* @version 3.0.6
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div id="booking_product_data" class="panel woocommerce_options_panel">

    <div class="options_group show_if_bookable">

        <?php woocommerce_wp_select( array(
            'id'          => 'booking_dates',
            'class'       => 'select short booking_dates',
            'name'        => '_number_of_dates',
            'label'       => esc_html__( 'Number of dates to select', 'woocommerce-easy-booking-system' ),
            'value'       => ! empty( $product->get_meta( '_number_of_dates' ) ) ? $product->get_meta( '_number_of_dates' ) : 'global',
            'options'     => array(
                'global' => esc_html__( 'Same as global settings', 'woocommerce-easy-booking-system' ),
                'one'    => esc_html__( 'One', 'woocommerce-easy-booking-system' ),
                'two'    => esc_html__( 'Two', 'woocommerce-easy-booking-system' )
            )
        ) ); ?>

        <div class="show_if_two_dates">

            <?php woocommerce_wp_text_input( array(
                'id'                => 'booking_duration',
                'class'             => 'booking_duration',
                'name'              => '_booking_duration',
                'label'             => esc_html__( 'Booking duration', 'woocommerce-easy-booking-system' ),
                'desc_tip'          => true,
                'description'       => esc_html__( 'Number of consecutive days/nights forming a block.', 'woocommerce-easy-booking-system' ),
                'value'             => ! empty( $product->get_meta( '_booking_duration' ) ) ? $product->get_meta( '_booking_duration' ) : '',
                'placeholder'       =>  esc_html__( 'Same as global settings', 'woocommerce-easy-booking-system' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => '1',
                    'min'  => '1',
                    'max'  => '366'
                ) 
            ) );

            woocommerce_wp_text_input( array(
                'id'                => 'booking_min',
                'class'             => 'booking_min',
                'name'              => '_booking_min',
                'label'             => esc_html__( 'Minimum duration', 'woocommerce-easy-booking-system' ),
                'desc_tip'          => 'true',
                'description'       => esc_html__( 'Minimum number of blocks to select. Leave 0 to set no minimum. Leave empty to use global settings.', 'woocommerce-easy-booking-system' ),
                'value'             => ! empty( $product->get_meta( '_booking_min' ) ) || $product->get_meta( '_booking_min' ) === '0' ? $product->get_meta( '_booking_min' ) : '',
                'placeholder'       => esc_html__( 'Same as global settings', 'woocommerce-easy-booking-system' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => '1',
                    'min'  => '0',
                    'max'  => '3650'
                ) 
            ) );

            woocommerce_wp_text_input( array(
                'id'                => 'booking_max',
                'class'             => 'booking_max',
                'name'              => '_booking_max',
                'label'             => esc_html__( 'Maximum duration', 'woocommerce-easy-booking-system' ),
                'desc_tip'          => 'true',
                'description'       => esc_html__( 'Maximum number of blocks to select. Leave 0 to set no maximum. Leave empty to use global settings.', 'woocommerce-easy-booking-system' ),
                'value'             => ! empty( $product->get_meta( '_booking_max' ) ) || $product->get_meta( '_booking_max' ) === '0' ? $product->get_meta( '_booking_max' ) : '',
                'placeholder'       => esc_html__( 'Same as global settings', 'woocommerce-easy-booking-system' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => '1',
                    'min'  => '0',
                    'max'  => '3650'
                )
            ) ); ?>

        </div>

        <?php woocommerce_wp_text_input( array(
            'id'                => 'first_available_date',
            'class'             => 'first_available_date',
            'name'              => '_first_available_date',
            'label'             => esc_html__( 'First available date', 'woocommerce-easy-booking-system' ),
            'desc_tip'          => 'true',
            'description'       => esc_html__( 'First available date, relative to the current day. Leave 0 for the current day. Leave empty to use global settings.', 'woocommerce-easy-booking-system' ),
            'value'             => ! empty( $product->get_meta( '_first_available_date' ) ) || $product->get_meta( '_first_available_date' ) === '0' ? $product->get_meta( '_first_available_date' ) : '',
            'placeholder'       => esc_html__( 'Same as global settings', 'woocommerce-easy-booking-system' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step' => '1',
                'min'  => '0',
                'max'  => '3650'
            )
        ) ); ?>

    </div>

    <?php do_action( 'easy_booking_after_booking_options', $product ); ?>
    <?php do_action( 'easy_booking_after_' . $product_type . '_booking_options', $product ); ?>

</div>
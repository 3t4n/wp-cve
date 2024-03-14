<?php

/**
*
* Edit bookable order items meta data on order pages.
* @version 3.0.9
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="edit" style="display: none;">

    <table class="meta" cellspacing="0">

        <tbody>

            <tr data-meta_id="<?php echo esc_attr( $start_date_meta_id ); ?>">

                <td>

                    <label for="start_date" style="font-weight: bold;"><?php echo esc_html( $start_date_text ); ?>: </label>
                    <input type="hidden" name="meta_key[<?php echo esc_attr( $item_id ); ?>][<?php echo esc_attr( $start_date_meta_id ); ?>]" value="_booking_start_date">
                    <input type="text"  name="meta_value[<?php echo esc_attr( $item_id ); ?>][<?php echo esc_attr( $start_date_meta_id ); ?>]" id="start_date" class="wceb_datepicker wceb_datepicker_start--<?php echo absint( $item_id ); ?>" value="<?php echo esc_attr( $start_date ); ?>" data-value="<?php echo esc_attr( $start_date ); ?>">

                </td>

            </tr>

            <?php if ( wceb_get_product_number_of_dates_to_select( $product ) === 'two' ) : ?>

                <tr data-meta_id="<?php echo esc_attr( $end_date_meta_id ); ?>">

                    <td>

                        <label for="end_date" style="font-weight: bold;"><?php echo esc_html( $end_date_text ); ?>: </label>
                        <input type="hidden" name="meta_key[<?php echo esc_attr( $item_id ); ?>][<?php echo esc_attr( $end_date_meta_id ); ?>]" value="_booking_end_date">
                        <input type="text" name="meta_value[<?php echo esc_attr( $item_id ); ?>][<?php echo esc_attr( $end_date_meta_id ); ?>]" id="end_date" class="wceb_datepicker wceb_datepicker_end--<?php echo absint( $item_id ); ?>" value="<?php echo esc_attr( $end_date ); ?>" data-value="<?php echo esc_attr( $end_date ); ?>">
                        
                    </td>

                </tr>

            <?php endif; ?>
                
                <tr data-meta_id="<?php echo esc_attr( $booking_status_meta_id ); ?>">

                    <td>
                        <label for="booking_status" style="font-weight: bold;"><?php _e( 'Booking status', 'woocommerce-easy-booking-system' ); ?>: </label>
                        <input type="hidden" name="meta_key[<?php echo esc_attr( $item_id ); ?>][<?php echo esc_attr( $booking_status_meta_id ); ?>]" value="_booking_status">
                        <?php wceb_settings_select( array(
                            'id'          => 'booking_status',
                            'name'        => 'meta_value[' . esc_attr( $item_id ) . '][' . esc_attr( $booking_status_meta_id ) . ']',
                            'value'       => isset( $booking_status ) ? esc_attr( $booking_status ) : 'wceb-pending',
                            'options'     => array(
                                'wceb-pending'    => apply_filters( 'easy_booking_display_status_pending', __( 'Pending', 'woocommerce-easy-booking-system' ) ),
                                'wceb-start'      => apply_filters( 'easy_booking_display_status_start', __( 'Start', 'woocommerce-easy-booking-system' ) ),
                                'wceb-processing' => apply_filters( 'easy_booking_display_status_processing', __( 'Processing', 'woocommerce-easy-booking-system' ) ),
                                'wceb-end'        => apply_filters( 'easy_booking_display_status_end', __( 'End', 'woocommerce-easy-booking-system' ) ),
                                'wceb-completed'  => apply_filters( 'easy_booking_display_status_completed', __( 'Completed', 'woocommerce-easy-booking-system' ) )
                            )
                        )); ?>
                    </td>

                </tr>

        </tbody>

    </table>

</div>
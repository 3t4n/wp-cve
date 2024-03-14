<?php

/**
*
* Display bookable variation options.
* @version 3.0.6
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="booking_variation_data show_if_variation_bookable">

    <?php $number_of_dates = get_post_meta( $variation_id, '_number_of_dates', true ); ?>
    
    <p class="form-row form-row-first">
        <label for="_var_number_of_dates[<?php echo $loop; ?>]">
            <?php esc_html_e( 'Number of dates to select', 'woocommerce-easy-booking-system' ); ?>
        </label>
        <select name="_var_number_of_dates[<?php echo $loop; ?>]" id="_var_number_of_dates[<?php echo $loop; ?>]" class="select short booking_dates">
            <option value="parent" <?php selected( $number_of_dates, 'parent', true ); ?>>
                <?php esc_html_e( 'Same as parent', 'woocommerce' ); ?>
            </option>
            <option value="one" <?php selected( $number_of_dates, 'one', true ); ?>>
                <?php esc_html_e( 'One', 'woocommerce-easy-booking-system' ); ?>
            </option>
            <option value="two" <?php selected( $number_of_dates, 'two', true ); ?>>
                <?php esc_html_e( 'Two', 'woocommerce-easy-booking-system' ); ?>
            </option>
        </select>
    </p>

    <div class="show_if_two_dates">

        <?php $booking_duration = get_post_meta( $variation_id, '_booking_duration', true ); ?>

        <p class="form-row form-row-last">

            <label for="_var_booking_duration[<?php echo $loop; ?>]">
                <?php esc_html_e( 'Booking duration', 'woocommerce-easy-booking-system' ); ?>
                <span class="tips" data-tip="<?php esc_attr_e( 'Number of consecutive days/nights forming a block. Leave empty to use parent or global settings.', 'woocommerce-easy-booking-system' ); ?>">[?]</span>
            </label>
            <input type="number" class="input_text booking_duration" min="1" max="366" name="_var_booking_duration[<?php echo $loop; ?>]" id="_var_booking_duration[<?php echo $loop; ?>]" placeholder="<?php esc_attr_e( 'Same as parent', 'woocommerce' ) ?>" value="<?php if ( isset( $booking_duration ) ) esc_attr_e( $booking_duration ); ?>" />

        </p>

        <p class="form-row form-row-first">

            <label for="_var_booking_min[<?php echo $loop; ?>]">
                <?php esc_html_e( 'Minimum duration', 'woocommerce-easy-booking-system' ); ?>
                <span class="tips" data-tip="<?php esc_attr_e( 'Minimum number of blocks to select. Leave 0 to set no minimum. Leave empty to use parent or global settings.', 'woocommerce-easy-booking-system' ); ?>">[?]</span>
            </label>

            <?php $booking_min = get_post_meta( $variation_id, '_booking_min', true ); ?>

            <input type="number" class="input_text booking_min" min="0" name="_var_booking_min[<?php echo $loop; ?>]" id="_var_booking_min[<?php echo $loop; ?>]" placeholder="<?php esc_attr_e( 'Same as parent', 'woocommerce' ) ?>" value="<?php if ( isset( $booking_min ) ) echo esc_attr( $booking_min ); ?>" />

        </p>

        <p class="form-row form-row-last">

            <label for="_var_booking_max[<?php echo $loop; ?>]">
                <?php esc_html_e( 'Maximum duration', 'woocommerce-easy-booking-system' ); ?>
                <span class="tips" data-tip="<?php esc_attr_e( 'Maximum number of blocks to select. Leave 0 to set no maximum. Leave empty to use parent or global settings.', 'woocommerce-easy-booking-system' ); ?>">[?]</span>
            </label>

            <?php $booking_max = get_post_meta( $variation_id, '_booking_max', true ); ?>

            <input type="number" class="input_text booking_max" min="0" name="_var_booking_max[<?php echo $loop; ?>]" id="_var_booking_max[<?php echo $loop; ?>]" placeholder="<?php esc_attr_e( 'Same as parent', 'woocommerce' ) ?>" value="<?php if ( isset( $booking_max ) ) echo esc_attr( $booking_max ); ?>" />

        </p>

    </div>

    <p class="form-row form-row-first">

        <label for="_var_first_available_date[<?php echo $loop; ?>]">
            <?php esc_html_e( 'First available date', 'woocommerce-easy-booking-system' ); ?>
            <span class="tips" data-tip="<?php esc_attr_e( 'First available date, relative to the current day. Leave 0 for the current day. Leave empty to use parent or global settings.', 'woocommerce-easy-booking-system' ); ?>">[?]</span>
        </label>

        <?php $first_available_date = get_post_meta( $variation_id, '_first_available_date', true ); ?>

        <input type="number" class="input_text" min="0" name="_var_first_available_date[<?php echo $loop; ?>]" id="_var_first_available_date[<?php echo $loop; ?>]" placeholder="<?php esc_attr_e( 'Same as parent', 'woocommerce' ) ?>" value="<?php if ( isset( $first_available_date ) ) esc_attr_e( $first_available_date ); ?>" />
        
    </p>
    
    <div class="clear"></div>

    <?php do_action('easy_booking_after_variation_booking_options', $loop, $variation ); ?>

</div>
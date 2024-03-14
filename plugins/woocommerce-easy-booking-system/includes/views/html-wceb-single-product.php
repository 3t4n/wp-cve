<?php

/**
*
* Temmplate to display datepickers on product pages.
*
* This template can be overridden by copying it to your-theme/easy-booking/wceb-html-single-product.php.
* Do not remove input attributes (classes, ids, etc.).
* Please make sure to keep your template up-to-date if you modify it.
*
* Note: .datepicker .datepicker_start .datepicker_end classes to be removed later.
*
* @version 3.0.3
*
**/

defined( 'ABSPATH' ) || exit;

?>

<div class="wceb_picker_wrap">

    <?php do_action( 'easy_booking_before_datepickers', $product ); ?>
    
    <?php // Start datepicker ?>
    <p class="form-row form-row-wide">
        <label for="start_date"><?php esc_html_e( $start_date_text ); ?></label>
        <input type="text" name="start_date" id="start_date" class="datepicker datepicker_start wceb_datepicker wceb_datepicker_start" data-value="" placeholder="<?php esc_attr_e( $start_date_text ); ?>">
    </p>

    <?php // End datepicker | For one date selection products, we need to keep the end datepicker, but it is hidden with CSS ?>
    <p class="form-row form-row-wide show_if_two_dates" style="display:<?php echo ( $number_of_dates === 'one' ) ? 'none' : 'block'; ?>">
        <label for="end_date"><?php esc_html_e( $end_date_text ); ?></label>
        <input type="text" name="end_date" id="end_date" class="datepicker datepicker_end wceb_datepicker wceb_datepicker_end" data-value="" placeholder="<?php esc_attr_e( $end_date_text ); ?>">
    </p>

    <?php do_action( 'easy_booking_after_datepickers', $product ); ?>

    <?php // Nonce ?>
    <input type="hidden" name="_wceb_nonce" class="wceb_nonce" value="<?php echo wp_create_nonce( 'set-dates' ); ?>">

    <?php // Reset dates button ?>
    <a href="#" class="reset_dates" data-ids=""><?php esc_html_e( 'Clear dates', 'woocommerce-easy-booking-system' ); ?></a>

</div>

<?php do_action( 'easy_booking_before_booking_details', $product ); ?>

<p class="booking_details"></p>

<?php do_action( 'easy_booking_before_booking_price', $product ); ?>

<p class="booking_price" data-booking_price="<?php echo esc_attr( $product->get_price() ); ?>" data-booking_regular_price="<?php echo esc_attr( $product->get_regular_price() ); ?>">

    <?php

    //  For variable products, the price will be displayed with Javascript for each variation.
    if ( ! $product->is_type( 'variable' ) ) {
        echo '<span class="price"></span>';
    }

    ?>

</p>

<?php do_action( 'easy_booking_after_booking_price', $product ); ?>
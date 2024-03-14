<?php 
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  Booking,ical,ics
 * @package   VR_Calendar
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */
$layout_options = $data['calendar_data']->calendar_layout_options;
$price = $data['booking_price'];
$a_bg_color = $layout_options['available_bg_color'];
$u_bg_color = $layout_options['unavailable_bg_color'];
$price_per_night = $price['price_per_night'];
$booking_days = $price['booking_days'];
$base_price = $price['base_booking_price'];
$cleaning_fee = $price['cleaning_fee'];
$tax_amnt = $price['tax_amt'];
$booking_w_tax = $price['booking_price_with_taxes'];
?>
<style>
    .ui-widget-header {
        background: none;
        border: none;
    }
    .ui-state-default, .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default {
        background: <?php echo esc_html($bg_color); ?>;
        border: none;
    }
    .ui-state-disabled, .ui-widget-content .ui-state-disabled,
    .ui-widget-header .ui-state-disabled {
        opacity: 1;
        filter: Alpha(Opacity=100);
    }
    .ui-state-default, .ui-widget-content .ui-state-disabled .ui-state-default,
    .ui-widget-header .ui-state-disabled .ui-state-default {
        background: <?php echo esc_html($u_bg_color); ?>;
    }
</style>
<?php
/* Add calendar style */
?>
<div class="vrc" id="vrc-booking-form-wrapper">
    <form name="vrc-booking-form" 
        id="vrc-booking-form" 
        class="vrc-validate" 
        method="post" 
        action="">
        <div class="booking-heading clearfix">
                <div id="booking-price-per-night" 
                    class="pull-left">
                    $<span id="price-per-night">
                        <?php echo esc_html($price_per_night); ?>
                    </span>
                </div>
            <div class="pull-right">Per Night</div>
        </div>
        <div id="booking-form-fields">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkin_date required">Check In</label>
                        <input type="text" 
                            class="form-control required" 
                            name="booking_checkin_date" 
                            id="booking_checkin_date" 
                            readonly 
                            value="<?php echo esc_html($data['check_in_date']) ?>" 
                            placeholder="Check In Date">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkout_date required">Check Out</label>
                        <input type="text" 
                            class="form-control required" 
                            name="booking_checkout_date" 
                            id="booking_checkout_date" 
                            readonly 
                            value="<?php echo esc_html($data['check_out_date']) ?>" 
                            placeholder="Check Out Date">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_guests_count required">Guests</label>
                        <input type="number" 
                            min="1" 
                            max="10" 
                            class="form-control required" 
                            name="booking_guests_count" 
                            id="booking_guests_count" 
                            value="1" 
                            placeholder="Guests">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_first_name">First Name</label>
                        <input type="text" 
                            class="form-control required" 
                            name="user_first_name" 
                            id="user_first_name" 
                            placeholder="First name" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_last_name">Last Name</label>
                        <input type="text" 
                            class="form-control required" 
                            name="user_last_name" 
                            id="user_last_name" 
                            placeholder="Last name" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input type="email" 
                            class="form-control required " 
                            name="user_email" 
                            id="user_email" 
                            placeholder="Email" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="booking_note">Note To Host</label>
                        <textarea class="form-control" 
                            name="booking_note" 
                            id="booking_note" 
                            placeholder="Note To Host"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div id="booking-form-charges">
            <table class="table table-hover ">
                <tr>
                    <td>
                        $<span id="table-price-per-night">
                            <?php echo esc_html($price_per_night); ?>
                        </span> 
                        x <span id="table-booking-days">
                            <?php echo esc_html($booking_days); ?>
                        </span> nights
                    </td>
                    <td>
                        $<span id="table-base-booking-price">
                            <?php echo esc_html($base_price); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Cleaning Fee
                    </td>
                    <td>
                        $<span id="table-cleaning-fee">
                            <?php echo esc_html($cleaning_fee); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Taxes
                    </td>
                    <td>
                        $<span id="table-tax-amt"><?php echo esc_html($tax_amnt); ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Total
                    </td>
                    <td>
                        $<span id="table-booking-price-with-taxes">
                            <?php echo esc_html($booking_w_tax); ?>
                        </span>
                    </td>
                </tr>
            </table>

        </div>
        <div id="booking-form-action">
            <div class="row">
                <div class="col-xs-12">
                    <input type="hidden" 
                        name="cal_id" 
                        id="cal_id" 
                        value="<?php echo esc_html($data['calendar_data']->calendar_id) ?>">
                    <input type="hidden" 
                        id="booked_dates" 
                        value='<?php echo json_encode($data['booked_dates']); ?>'>
                    <input type="hidden" 
                        id="vrc_pcmd" 
                        name="vrc_pcmd" 
                        value='saveBooking'>
                    <input type="submit" 
                        class="btn btn-danger btn-lg col-xs-12" 
                        value="Request to Book" />
                </div>
            </div>
        </div>
    </form>
</div>

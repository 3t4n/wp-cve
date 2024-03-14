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

$payMethod = $data['cal_data']->calendar_payment_method;
?>
<div class="vrc" id="vrc-payment-form-wrapper">
    <div id="vrc-payment-error" class="bg-danger hidden"></div>
    <?php if ($payMethod == 'stripe' || $payMethod == 'both') {
        include 'Partial/Payment/Stripe.view.php';
    }?>
    <?php if ($payMethod == 'paypal' || $payMethod == 'both') {
        include 'Partial/Payment/Paypal.view.php';
    }?>
</div>

<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  Views
 * @package   Views
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * Views
  * 
  * Views
  * 
  * @category  Views
  * @package   Views
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
?>
<div id="vrc-payment-paypal">
    <form method="post" action="">
        <input type="hidden" name="vrc_pcmd" id="vrc_pcmd" value="paypalPayment" />
        <input type="hidden" name="bid" id="bid" value="<?php echo esc_html($data['booking_data']->booking_id); ?>" />
        <input type="submit" class="btn btn-primary" value="Pay via PayPal" />
    </form>
</div>

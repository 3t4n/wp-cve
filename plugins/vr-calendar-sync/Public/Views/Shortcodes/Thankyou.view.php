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
?>
<h3><?php echo esc_html($data['msg']); ?></h3>
<table>
    <tr>
        <td>
            Booking ID
        </td>
        <td>
            <?php echo esc_html($data['booking_data']->booking_id); ?>
        </td>
    </tr>
    <?php if(!empty($data['booking_data']->booking_payment_data['txn_id'])) : ?>
    <tr>
        <td>
            Transaction ID
        </td>
        <td>
            <?php echo esc_html($data['booking_data']->booking_payment_data['txn_id']); ?>
        </td>
    </tr>
    <?php endif; ?>
</table>

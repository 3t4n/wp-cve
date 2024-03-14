<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: %s: admin name */
	__("Reminder: Event with %s  in 24 hours.", 'wpcal'), $mail_data['booking_admin_display_name']);

?>
<subject><?php echo $mail_subject; ?></subject>

<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->

<?php wpcal_get_template('emails/header.php');?>

        <table style="width:100%;">
          <tr>
            <td style="padding: 10px 0;">
              <span style="color: #7c7d9c;"
                ><?php echo __('Hi', 'wpcal'); ?><?php echo $mail_data['hi_name']; ?>, <br /><?php echo sprintf(
	/* translators: %s: admin name */
	__('You have an event with %s in 24 hours.', 'wpcal'), $mail_data['booking_admin_display_name']); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Event Date & Time', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"
                ><?php echo $mail_data['booking_from_to_time_str_with_tz']; ?></span
              >
            </td>
		  </tr>
		  <?php echo $mail_data['location_html']; ?>
        </table>


<?php wpcal_get_template('emails/footer.php');?>
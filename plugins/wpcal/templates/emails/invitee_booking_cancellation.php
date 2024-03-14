<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: 1: admin name 2: date time tz */
	__('Event Cancelled: With %1$s at %2$s.', 'wpcal'), $mail_data['booking_admin_display_name'], $mail_data['booking_from_to_time_str_with_tz']);

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
	__('Your event with %s has been cancelled.', 'wpcal'), $mail_data['booking_admin_display_name']); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Event Type', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['service_name']; ?></span>
            </td>
          </tr>
          <?php echo $mail_data['location_html']; ?>
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
          <tr>
            <td style="padding: 10px 0;<?php echo (!$mail_data['reschedule_cancel_reason'] ? 'display:none;' : ''); ?>">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Cancellation reason', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c; white-space: pre-line;"><?php echo $mail_data['reschedule_cancel_reason']; ?></span>
            </td>
          </tr>
          <tr style="<?php echo (!$mail_data['reschedule_cancel_by'] ? 'display:none;' : ''); ?>">
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Cancelled by', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['reschedule_cancel_by']; ?></span>
            </td>
          </tr>
        </table>


<?php wpcal_get_template('emails/footer.php');?>
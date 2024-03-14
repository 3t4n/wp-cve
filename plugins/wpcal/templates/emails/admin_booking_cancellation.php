<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: %s: event type name */
	__("Event Cancelled: %s event has been cancelled.", 'wpcal'), $mail_data['service_name']);

if ($mail_data['is_old_and_new_booking_having_different_admins']) {
	$mail_subject = sprintf(
		/* translators: %1$s: event type name, %1$s: new admin name  */
		__('Event Rescheduled to %2$s: Your %1$s event has been cancelled.', 'wpcal'), $mail_data['service_name'], $mail_data['new_admin_name']);
}

?>
<subject><?php echo $mail_subject; ?></subject>

<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->

<?php wpcal_get_template('emails/header.php');?>

        <table style="width:100%;">
          <tr>
            <td style="padding: 10px 0;">
              <span style="color: #7c7d9c;"
                ><?php echo __('Hi', 'wpcal') . $mail_data['hi_name']; ?>, <br /><?php echo $mail_data['is_old_and_new_booking_having_different_admins'] ? sprintf(
	/* translators: %s: new admin name */
	__('An event has been cancelled because it was rescheduled to %s.', 'wpcal'), $mail_data['new_admin_name']) : __('An event has been cancelled.', 'wpcal'); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Invitee', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['invitee_name']; ?></span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Invitee email', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><a href="mailto:<?php echo $mail_data['invitee_email']; ?>"><?php echo $mail_data['invitee_email']; ?></a></span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
            <strong style="font-size: 11px; text-transform: uppercase;"
              ><?php echo __('Event Type', 'wpcal'); ?></strong
            ><br />
            <span style="color: #7c7d9c;"
              ><?php echo $mail_data['service_name']; ?></span
            >
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
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo $mail_data['is_old_and_new_booking_having_different_admins'] ? __('Reschedule reason', 'wpcal') : __('Cancellation reason', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c; white-space: pre-line;"><?php echo $mail_data['reschedule_cancel_reason']; ?></span>
            </td>
          </tr>
          <tr style="<?php echo (!$mail_data['reschedule_cancel_by'] ? 'display:none;' : ''); ?>">
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo $mail_data['is_old_and_new_booking_having_different_admins'] ? __('Reschedule by', 'wpcal') : __('Cancelled by', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['reschedule_cancel_by']; ?></span>
            </td>
          </tr>
        </table>

<?php wpcal_get_template('emails/footer.php');?>
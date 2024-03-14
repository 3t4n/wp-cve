<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: %s: event type name */
	__("Event Rescheduled:  %s event has been rescheduled.", 'wpcal'), $mail_data['service_name']);

?>
<subject><?php echo $mail_subject; ?></subject>

<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->

<?php wpcal_get_template('emails/header.php');?>

        <table style="width:100%;">
          <tr>
            <td style="padding: 10px 0;">
              <span style="color: #7c7d9c;"
                ><?php echo __('Hi', 'wpcal'); ?><?php echo $mail_data['hi_name']; ?>, <br /><?php echo __('An event has been rescheduled.', 'wpcal'); ?>
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
              <span style="color: #7c7d9c;"><?php echo $mail_data['invitee_email']; ?></span>
            </td>
          </tr>
          <?php echo $mail_data['location_html']; ?>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Former Date & Time', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c; text-decoration: line-through;"
                ><?php echo $mail_data['old_booking_from_to_time_str_with_tz']; ?></span
              >
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Rescheduled Date & Time', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"
                ><?php echo $mail_data['booking_from_to_time_str_with_tz']; ?></span
              >
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Invitee Time Zone', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['invitee_tz']; ?></span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Reschedule reason', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c; white-space: pre-line;"><?php echo $mail_data['rescheduled_reason']; ?></span>
            </td>
          </tr>
          <tr style="<?php echo (!$mail_data['rescheduled_by'] ? 'display:none;' : ''); ?>">
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Rescheduled by', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['rescheduled_by']; ?></span>
            </td>
          </tr>
          <?php echo $mail_data['invitee_question_answers_html']; ?>
          <tr>
            <td>
              <br /><a style="color: #567bf3; text-decoration: underline;" href="<?php echo $mail_data['admin_view_booking_url']; ?>"
                ><?php echo __('View Booking &rarr;', 'wpcal'); ?></a
              >
            </td>
          </tr>
        </table>

<?php wpcal_get_template('emails/footer.php');?>
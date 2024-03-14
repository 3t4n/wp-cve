<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: %s: event type name */
	__("New Event Confirmed: New %s event has been booked.", 'wpcal'), $mail_data['service_name']);
if ($mail_data['is_old_and_new_booking_having_different_admins']) {
	$mail_subject = sprintf(
		/* translators: %s: event type name */
		__("New Event (Rescheduled) Confirmed: New %s event has been booked.", 'wpcal'), $mail_data['service_name']);
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
	/* translators: %s: old admin name */__('A new event, originally booked for %s, has been rescheduled to you.', 'wpcal'), $mail_data['old_admin_name']) : __('A new event has been booked.', 'wpcal'); ?>
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
              <span style="text-transform: capitalize; color: #7c7d9c;"
                ><?php echo $mail_data['invitee_name']; ?></span
              >
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Invitee Email', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><a href="mailto:<?php echo $mail_data['invitee_email']; ?>"><?php echo $mail_data['invitee_email']; ?></a></span>
            </td>
          </tr>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Event Date & Time', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['booking_from_to_time_str_with_tz']; ?></span>
            </td>
          </tr>
          <?php echo $mail_data['location_html']; ?>
          <tr>
            <td style="padding: 10px 0;">
              <strong style="font-size: 11px; text-transform: uppercase;"
                ><?php echo __('Invitee Timezone', 'wpcal'); ?></strong
              ><br />
              <span style="color: #7c7d9c;"><?php echo $mail_data['invitee_tz']; ?></span>
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
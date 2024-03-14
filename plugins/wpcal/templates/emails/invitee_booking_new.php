<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = sprintf(
	/* translators: 1: admin name 2: date time tz */
	__('New Event Confirmed: New event with %1$s at %2$s.', 'wpcal'), $mail_data['booking_admin_display_name'], $mail_data['booking_from_to_time_str_with_tz']);

?>
<subject><?php echo $mail_subject; ?></subject>

<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->

<?php wpcal_get_template('emails/header.php');?>

        <table style="width:100%;">
          <tr>
            <td style="padding: 10px 0;">
              <span style="color: #7c7d9c;"
                ><?php echo __('Hi', 'wpcal'); ?><?php echo $mail_data['hi_name']; ?>, <br />
                <?php echo sprintf(
	/* translators: 1: admin name 2: date time tz */
	__('Your event with %1$s at %2$s is booked.', 'wpcal'), $mail_data['booking_admin_display_name'], $mail_data['booking_from_to_time_str_with_tz']); ?>
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
          <?php echo $mail_data['invitee_question_answers_html']; ?>
          <tr>
            <td
              style="color: #567bf3; padding: 30px 0 10px; text-align: center;"
            >
              <a style="color: #567bf3; text-decoration: underline;"
              href="<?php echo $mail_data['add_event_to_google_calendar_url']; ?>"><?php echo __('Add to Google Calendar', 'wpcal'); ?> </a
              >&rarr;
            </td>
          </tr>
          <tr>
            <td
              style="color: #567bf3; padding: 10px 0 30px; text-align: center;"
            >
              <a style="color: #567bf3; text-decoration: underline;" href="<?php echo $mail_data['download_ics_url']; ?>"
                ><?php echo __('Add to iCal/Outlook', 'wpcal'); ?> </a
              >&rarr;
            </td>
          </tr>
          <tr>
            <td
              style="
                color: #7c7d9c;
                font-size: 12px;
                padding: 20px 0 0;
                text-align: center;
              "
            >
              <?php echo __('Want to make changes?', 'wpcal'); ?>
              <a
                style="
                  text-decoration: underline;
                  margin-right: 10px;
                  margin-left: 10px;
                "
                href="<?php echo $mail_data['reschedule_url']; ?>"
                ><?php echo __('Reschedule', 'wpcal'); ?></a
              ><a style="text-decoration: underline;" href="<?php echo $mail_data['cancel_url']; ?>"><?php echo __('Cancel', 'wpcal'); ?></a>
            </td>
          </tr>
        </table>


<?php wpcal_get_template('emails/footer.php');?>
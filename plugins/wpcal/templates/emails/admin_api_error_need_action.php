<?php
/**
 * WPCal.io
 * Copyright (c) 2021 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

$mail_subject = 'Action needed. API issue with WPCal and ' . $mail_data['provider_name'] . '';

?>
<subject><?php echo $mail_subject; ?></subject>

<!-- WPCal_mail_separator DO_NOT_EDIT_THIS_LINE -->

<?php wpcal_get_template('emails/header.php');?>
	<table style="width:100%;">
		<tr>
			<td style="padding: 10px 0;">
				<span style="color: #7c7d9c;"
				><?php echo __('Hi', 'wpcal') . $mail_data['hi_name']; ?>, <br />
				</span>
			</td>
		</tr>
		<tr>
			<td style="color: #7c7d9c; padding: 10px 0;">
				Your <?php echo $mail_data['provider_name']; ?> account <?php echo $mail_data['account_name']; ?> API is facing authentication issue.<br><br>
				<a style="background-color: #567bf3; color: #fff; display: inline-block; padding: 12px 15px; border-radius: 5px; font-size: 16px; margin: auto; text-decoration:none;" href="<?php echo $mail_data['view_settings_url']; ?>">View and fix the issue</a>
			</td>
			</tr>
	</table>

<?php wpcal_get_template('emails/footer.php');?>
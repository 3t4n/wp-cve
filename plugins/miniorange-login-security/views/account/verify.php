<?php
/**
 * This file contains the html UI to verify miniOrange account email.
 *
 * @package miniorange-login-security/views/account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '<br><br><br><br>
  <div class="momls_wpns_divided_layout">
          <div class="momls_wpns_table_layout mo2f_table_layout">
			<div id="panel2">
				<table class="momls_wpns_settings_table">
				<!-- Enter otp -->
					<form name="f" method="post" id="wpns_form" action="">
						<input type="hidden" name="option" value="momls_wpns_validate_otp" />
						<h3>Verify Your Email</h3>
						<tr>
							<td><b><span class="momls_font-color-astrisk">*</span>Enter OTP:</b></td>
							<td colspan="2"><input class="momls_wpns_table_textbox" autofocus="true" type="text" name="otp_token" required placeholder="Enter OTP" style="width:61%;" pattern="{6,8}"/>
							 &nbsp;&nbsp;<a style="cursor:pointer;" onclick="document.getElementById(\'resend_otp_form\').submit();">Resend OTP over Email</a></td>
						</tr>
						<tr><td colspan="3"></td></tr>
						<tr><td></td><td>
						<a style="cursor:pointer;" onclick="document.getElementById(\'momls_wpns_cancel_form\').submit();"><input type="button" value="Back" id="back_btn" class="momls_wpns_button momls_wpns_button1" /></a>
						<input type="submit" value="Validate OTP" class="momls_wpns_button momls_wpns_button1" />
						</td>
						</form>
						<td>
						<form method="post" action="" id="momls_wpns_cancel_form">
							<input type="hidden" name="option" value="momls_wpns_cancel" />
						</form>
						</td>
						</tr>
					<form name="f" id="resend_otp_form" method="post" action="">
							<td>
							<input type="hidden" name="option" value="momls_wpns_resend_otp"/>
							</td>
						</tr>
					</form>
				</table>
				<br>
				<hr>

				<h3>I did not recieve any email with OTP . What should I do ?</h3>
				<form id="phone_verification" method="post" action="">
					<input type="hidden" name="option" value="momls_wpns_phone_verification" />
					 If you can\'t see the email from miniOrange in your mails, please check your <b>SPAM Folder</b>. If you don\'t see an email even in SPAM folder, verify your identity with our alternate method.
					 <br><br>
						<b>Enter your valid phone number here and verify your identity using one time passcode sent to your phone.</b><br><br><input class="momls_wpns_table_textbox" required="true" pattern="[\+]\d{1,3}\d{10}" autofocus="true" type="text" name="phone_number" id="phone" placeholder="Enter Phone Number" style="width:40%;" value="' . esc_attr( $admin_phone ) . '" title="Enter phone number without any space or dashes."/>
						<br><input type="submit" value="Send OTP" class="momls_wpns_button momls_wpns_button1" />
				
				</form>
			</div>
		</div>
		</div>

		<script>
			jQuery(document).ready(function(){
				$("#phone").intlTelInput();
				$("#back_btn").click(function(){
						$("#momls_wpns_cancel_form").submit();
				});
			});
		</script>';

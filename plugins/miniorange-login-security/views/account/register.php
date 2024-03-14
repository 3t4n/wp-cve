<?php
/**
 * This file contains the html UI for the miniOrange account registration.
 *
 * @package miniorange-login-security/views/account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '<!--Register with miniOrange-->
<br><br><br><br>

	<form name="f" method="post" action="">
		<input type="hidden" name="option" value="momls_wpns_register_customer" />
		<input type="hidden" name="mo2f_general_nonce" value=" ' . esc_attr( wp_create_nonce( 'miniOrange_2fa_nonce' ) ) . ' " />
		<div class="momls_wpns_divided_layout">
		<div class="mo2f_table_layout" style="margin-bottom:30px;">
			
				<h3>Register with miniOrange
					<div style="float: right;">';
if ( isset( $two_fa ) ) {
	echo '<a class="button button-primary button-large" href="' . esc_attr( $two_fa ) . '">Back</a> ';
}
					echo '</div>
				</h3>
				<p>Just complete the short registration below to configure miniOrange 2-Factor plugin. Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will send to this email.</p>
				<table class="momls_wpns_settings_table">
					<tr>
						<td><b><span class="momls_font-color-astrisk">*</span>Email:</b></td>
						<td><input class="momls_wpns_table_textbox" type="email" name="email"
							required placeholder="person@example.com"
							value="' . esc_attr( $user->user_email ) . '" /></td>
					</tr>

					<tr>
						<td><b><span class="momls_font-color-astrisk">*</span>Password:</b></td>
						<td><input class="momls_wpns_table_textbox" required type="password"
							name="password" placeholder="Choose your password (Min. length 6)" /></td>
					</tr>
					<tr>
						<td><b><span class="momls_font-color-astrisk">*</span>Confirm Password:</b></td>
						<td><input class="momls_wpns_table_textbox" required type="password"
							name="confirmPassword" placeholder="Confirm your password" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><br /><input type="submit" name="submit" value="Next" style="width:100px;"
							class="button button-primary button-large" />
							<a class="button button-primary button-large" href="#mo2f_account_exist">SIGN IN</a>

					</tr>
				</table>
		</div>	
		</div>
	</form>
	 <form name="f" method="post" action="" class="mo2fMOMLS_VERIFY_CUSTOMERform">
        <input type="hidden" name="option" value="mo2f_goto_verifycustomer">
		<input type="hidden" name="mo2f_general_nonce" value=" ' . esc_attr( wp_create_nonce( 'miniOrange_2fa_nonce' ) ) . ' " />
       </form>';
?>


	<script>
		jQuery('a[href=\"#mo2f_account_exist\"]').click(function (e) {
			jQuery('.mo2fMOMLS_VERIFY_CUSTOMERform').submit();
		});	
	</script>

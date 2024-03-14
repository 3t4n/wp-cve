<?php
/**
 * Contains function to display login form
 *
 * @package firebase-authentication
 */

/**
 * Login form UI
 *
 * @return void
 */
function mo_firebase_auth_verify_password_ui() {
	?>
		<form name="f" method="post" action="">
			<input type="hidden" name="option" value="mo_firebase_authentication_verify_customer" />
			<?php wp_nonce_field( 'mo_fb_login_form', 'mo_fb_login_form_nonce' ); ?>
			<div class="mo_firebase_auth_card" style="width:100%">
				<!-- <div id="toggle1" class="mo_panel_toggle"> -->
					<h3>Login with miniOrange</h3>
				<!-- </div> -->
				<p style="font-size: 12px; font-weight: 550;">It seems you already have an account with miniOrange. Please enter your miniOrange email and password.<br/> <a href="#mo_firebase_authentication_forgot_password_link">Click here if you forgot your password?</a>
				</p>

				<table class="mo_settings_table">
					<tr>
						<td><strong><font color="#FF0000">*</font>Email:</strong></td>
						<td><input class="mo_table_textbox3" type="email" name="email"
							required placeholder="person@example.com"
							value="<?php echo esc_attr( get_option( 'mo_firebase_authentication_admin_email' ) ); ?>" /></td>
					</tr>
					<tr>
						<td><strong><font color="#FF0000">*</font>Password:</strong></td>
						<td><input class="mo_table_textbox3" required type="password"
							name="password" placeholder="Enter your password" /></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;</td>
						<td>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;</td>
						<td><input type="submit" name="submit" value="Login"
							class="button button-primary button-large" /><input type="button" name="back-button" id="mo_firebase_authentication_back_button" onclick="document.getElementById('mo_firebase_authentication_change_email_form').submit();" value="Back" class="button button-primary button-large" style="margin-left: 20%;" /></td>
					</tr>
				</table>
				<br>
				<!-- <div style="margin-bottom: 20px;">
					<div style="width: 50%; margin:0 auto;">
					<input type="submit" name="submit" value="Login"
						class="button button-primary button-large" />

					<input type="button" name="back-button" id="mo_firebase_authentication_back_button" onclick="document.getElementById('mo_firebase_authentication_change_email_form').submit();" value="Back" class="button button-primary button-large" style="margin-left: 20%;" /></div></div> -->
			</div>
		</form>
		<form id="mo_firebase_authentication_change_email_form" method="post" action="">
			<input type="hidden" name="option" value="mo_firebase_authentication_change_email" />
			<?php wp_nonce_field( 'mo_firebase_authentication_change_email_form', 'mo_firebase_authentication_change_email_form_nonce' ); ?>
		</form>

		<!-- <form name="f" method="post" action="" id="mo_firebase_authentication_forgotpassword_form">
			<input type="hidden" name="option" value="mo_firebase_authentication_forgot_password_form_option"/>
		</form> -->
		<script>
			jQuery("a[href=\"#mo_firebase_authentication_forgot_password_link\"]").click(function(){
				window.open('https://login.xecurify.com/moas/idp/resetpassword');
				//jQuery("#mo_firebase_authentication_forgotpassword_form").submit();
			});
		</script>
		<?php
}

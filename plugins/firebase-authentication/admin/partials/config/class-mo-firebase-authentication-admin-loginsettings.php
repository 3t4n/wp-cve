<?php
/**
 * Firebase Authentication Login Settings
 *
 * @package firebase-authentication
 */

/**
 * Displays Login Settings screen
 */
class Mo_Firebase_Authentication_Admin_LoginSettings {
	/**
	 * Function to render login settings screen
	 *
	 * @return void
	 */
	public static function mo_firebase_authentication_loginsettings() {
		?>
	<div class="row">
		<div class="col-md-12">
			<div class="mo_firebase_auth_card" style="width:100%">
				<div style="display: inline-block;"><h3  style="margin: 10px 0;">Sign in options </h3></div>&nbsp;<small style="color: #FF0000"><a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans">[ENTERPRISE]</a></small><br>
				<strong style="font-weight: 600; vertical-align: top; ">Option 1: Place different social login buttons on WordPress default Login Form</strong>
				<ol>
					<p>1. Go to the Advanced Settings tab.<br>
					2. Select <b>"Show Login button on WP login page"</b>.</p>
				</ol>
				<strong style="font-weight: 600; vertical-align: top; ">Option 2: Use a Shortcode for login</strong>
				<ol>
					<p>1. Place shortcode <b>[mo_firebase_auth_login_ui]</b> to add Firebase Phone Login button on WordPress pages.<br>
					2. Place shortcode <b>[mo_firebase_auth_login]</b> in WordPress pages or posts where you want to give social login option to your users.<br>
					3. Place shortcode <b>[mo_firebase_auth_display_login_form]</b> to add Firebase Login form on WordPress pages.<br>
					4. Place shortcode <b>[mo_firebase_auth_display_registration_form]</b> to add Firebase Registration form on WordPress pages.</p>
				</ol>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="mo_firebase_auth_card" style="width:100%">
				<div style="display: inline-block;"><h3  style="margin: 10px 0;">Advanced Sign in options</h3></div>&nbsp;<small style="color: #FF0000"><a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans">[PREMIUM]</a></small><br>
				<form name="f" method="post" action="">
					<?php wp_nonce_field( 'mo_firebase_auth_sign_in_option_form', 'mo_firebase_auth_sign_in_option_field' ); ?>
					<input type="hidden" name="option" value="mo_firebase_authentication_sign_in_option" />
					<div class="row">
						<div class="col-md-6">
							<p style="margin-bottom: 3px"><b>Custom redirect URL after login </b></p>
							<p>(Keep blank in case you want users to redirect to page from where SSO originated)</p>
						</div>
						<div class="col-md-6">
						<input name="custom_after_login_url" value="" pattern="https?://.+" title="Include https://" placeholder="https://" style="width:80%; font-size: 14px;" type="url" disabled="">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<p style="margin-bottom: 3px"><b>Custom redirect URL after logout </b></p>
						</div>
						<div class="col-md-6">
						<input name="custom_after_logout_url" value="" pattern="https?://.+" title="Include https://" placeholder="https://" style="width:80%; font-size: 14px;" type="url" disabled="">
						</div>
					</div>
					<br>
					<div class="row">
					<div class="col-md-10">
						<input type="checkbox" id="mo_firebase_email_verified_login" name="mo_firebase_email_verified_login" value="1" disabled>
						<label for="mo_firebase_email_verified_login" style="font-weight: bolder;font-size: 13px">Do Not allow login if email is not verified </label><br>
						<p><strong>Note:</strong> If this check is enabled then all users (except admin users) can login only with email & password and not with username & password.</p>
					</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="submit" style="text-align:center; font-size: 14px; font-weight: 400;" class="btn btn-primary" name="custom_login_settings" value=" Save Settings" id = "mo_firebase_auth_custom_login_url_save_settings_button" disabled><br>
						</div>
					</div>
					<br>
				</form>
			</div>
		</div>
	</div>
		<?php
	}
}

<?php
/**
 * Firebase Authentication plugin
 *
 * @package firebase-authentication
 * @author miniOrange <info@miniorange.com>
 */

/**
 * Undocumented class
 */
class Mo_Firebase_Authentication_Admin_AdvSettings {
	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function mo_firebase_authentication_advsettings() {
		?>
	<div class="row">
		<div class="col-md-12">
			<div class="mo_firebase_auth_card" style="width:100%">
				<form name="integration_form" id="mo_firebase_auth_integration"  method="post">
					<input type="hidden" name="option" value="mo_firebase_auth_integration">
					<div style="display: inline-block;"><h3  style="margin: 10px 0;">Sync WordPress and Firebase users </h3></div>&nbsp;<small style="color: #FF0000"><a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans">[PREMIUM]</a></small><br>
					<div style="display:inline-block; margin-top: 10px;"><label class="mo_firebase_auth_switch">
						<input value="1" name="mo_enable_firebase_auto_register" type="checkbox" id="mo_enable_firebase_auto_register" disabled>
						<span class="mo_firebase_auth_slider round"></span>
						<input type="hidden" name="option" value="">
						</label>					
					<strong style="font-weight: 600; vertical-align: top; ">Auto register users into Firebase</strong></div>
					<br>
					<p>Enabling this option will create a new user in the Firebase project with an email address and password when an end-user registers into the WordPress site via Woocommerce/BuddyPress registration form.</p>
					<input type="checkbox" disabled id="mo_firebase_sendemail_verification" name="mo_firebase_sendemail_verification" value="1">
					<label for="mo_firebase_sendemail_verification" style="font-size: 15px">Send verification email after registration</label><br>
					<input type="checkbox" disabled id="mo_firebase_map_display_name" name="mo_firebase_map_display_name" value="1">
					<label for="mo_firebase_map_display_name" style="font-size: 15px">Map WordPress username to firebase display name</label><br>
					<input type="checkbox" disabled id="mo_firebase_hide_password_field" name="mo_firebase_hide_password_field" value="1">
					<label for="mo_firebase_hide_password_field" style="font-size: 15px">Hide change password field in WooCommerce and WordPress user admin dashboard </label><br><br>	
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="mo_firebase_auth_card" style="width:100%">
				<form name="integration_form" id="mo_firebase_auth_integration"  method="post" style="margin-bottom: 10px;">
					<input type="hidden" name="option" value="mo_firebase_auth_integration" style="margin-top: 0px; margin-bottom: 0px;">
					<div style="display: inline-block;"><h3 style="margin: 15px 0;">Login & Registration Form Integration</h3></div>&nbsp;<small style="color: #FF0000"><a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans">[PREMIUM]</a></small>
					<span style="float: right; font-size: 14px; margin-top: 10px;"><a href="https://plugins.miniorange.com/firebase-woocommerce-integration" target="_blank" rel="noopener" class="mo_firebase_setup_guide_style" style="text-decoration: none;"> Know more</a></span>
					<table class="mo_settings_table" style="width: 95%;">
					<tr><td>Select below if you want to allow users to login using firebase credentials with WooCommerce or BuddyPress.</td></tr>
					<tr><td></td></tr><tr><td></td></tr>
					<tr><td>
					<input type="checkbox" name = "mo_firebase_auth_woocommerce_intigration" id = "mo_firebase_auth_woocommerce_intigration" value= "1" onclick="mo_firebase_auth_manageWCDiv();" disabled>
						<img src="<?php echo esc_url( MO_FIREBASE_AUTHENTICATION_URL . 'admin/images/woocommerce-circle.png' ); ?>" width="50px">&nbsp;&nbsp;WooCommerce
						</td></tr><tr><td>
					<input type="checkbox" name = "mo_firebase_auth_buddypress_intigration"value="1" disabled>
						<img src="<?php echo esc_url( MO_FIREBASE_AUTHENTICATION_URL . 'admin/images/buddypress.png' ); ?>" width="50px">&nbsp;&nbsp;BuddyPress
						</td></tr>
					</table>
					<input type="submit" style="text-align:center; font-size: 14px; font-weight: 400; margin-top: 10px;" class="btn btn-primary" name="integration_settings" value=" Save Settings" id = "mo_auth_integration_save_settings_button" disabled><br>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="mo_firebase_auth_card" style="width:100%">
				<form name="integration_form" id="mo_firebase_auth_integration"  method="post" style="margin-bottom: 10px;">
					<input type="hidden" name="option" value="mo_firebase_auth_integration">
					<div style="display: inline-block;"><h3  style="margin: 10px 0;">Firebase Authentication methods</h3></div>&nbsp;<small style="color: #FF0000"> <a href="admin.php?page=mo_firebase_authentication&tab=licensing_plans">[ENTERPRISE]</a></small>
					<span style="float: right; font-size: 14px; margin-top: 10px;"><a href="https://plugins.miniorange.com/firebase-social-login-integration-for-wordpress" target="_blank" rel="noopener" class="mo_firebase_setup_guide_style" style="text-decoration: none;"> Know more</a></span>
					<table class="mo_settings_table"><tr><td>
					<p>Select Firebase Social login methods to Login into your site. </p></td></tr>
					<!-- <input type="radio" id="emailPassword" value="emailPassword" disabled>
					<label for="male">Email and Password</label><br> -->
					<tr><td>
					<input type="checkbox" id="google" value="google" disabled>
					<label for="female">Google</label><br></td></tr>
					<tr><td>
					<input type="checkbox" id="facebook" value="facebook" disabled>
					<label for="other">Facebook</label><br>
					</td></tr>
					<tr><td>
					<input type="checkbox" id="github" value="github" disabled>
					<label for="other">GitHub</label><br>
					</td></tr>
					<tr><td><input type="checkbox" id="twitter" value="twitter" disabled>
					<label for="other">Twitter</label><br>
					</td></tr>
					<tr><td>
					<input type="checkbox" id="microsoft" value="microsoft" disabled>
					<label for="other">Microsoft</label><br></td></tr>
					<tr><td>
					<input type="checkbox" id="yahoo" value="yahoo" disabled>
					<label for="other">Yahoo</label><br>
					</td></tr>
					<tr><td>
					<input type="checkbox" id="apple" value="apple" disabled>
					<label for="other">Apple</label><br>
					</td></tr>
					<tr><td>&nbsp;</td></tr>
					<tr><td>
					<input type="checkbox" id="show_on_login_page" value="1" disabled>
					<label for="other">Show Login button on WP login page</label><br>
					</td></tr>
					<tr><td>
					<input type="checkbox" id="show_on_login_page" value="1" disabled>
					<label for="other">Show Login button on WooCommerce My Account Page</label><br>
					</td></tr>
				</table>
				<br>
					<input type="submit" style="text-align:center; font-size: 14px; font-weight: 400;"class="btn btn-primary" name="authentication_settings" value=" Save Settings" id = "mo_auth_authentication_save_settings_button" disabled><br>
				</form>
			</div>
		</div>
	</div>
		<?php
	}
}

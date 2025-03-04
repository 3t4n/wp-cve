<?php
/**
 * This files contains UI related to adaptive authentication.
 *
 *  @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$setup_dirname = $mo2f_dir_name . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'twofa' . DIRECTORY_SEPARATOR . 'link-tracer.php';
require $setup_dirname;
$premium_feature_tooltip_array = array(
	'This option will provide users an alternate way of login in case their phone is lost, discharged or not with them.',
	' You can select which Two Factor methods you want to enable for your users. By default all Two Factor methods are enabled for all users of the role you have selected above.',
	' If this option is enabled then users will have an option to skip the 2FA setup prompted after initial login',
	' If this option is enabled then users can edit their email during User Enrollment with miniOrange, and they will be prompted for e-mail verification. By selecting second option, the user will be silently registered with miniOrange without the need of e-mail verification.',
	'By default 2nd Factor is enabled after password authentication. If you do not want to remember passwords anymore and just login with 2nd Factor, please select 2nd option.',
	'Users have an option to Login with Username and password or Login with just username + One Time Passcode ',
	'Checking this option will hide default login form',
);
?>
<div class="mo2f_table_layout">
	<p><i>* These features are available in <span class="momls_font-color-astrisk">Premium version</span> only.</i></p>
<div  id = "premium_feature_phone_lost" >

	<h3>What happens if my phone  is lost, discharged or not with me 
	<?php momls_tooltip_array( $premium_feature_tooltip_array[0] ); ?>
		<a href='<?php echo esc_attr( $two_factor_premium_doc['What happens if my phone is lost, discharged or not with me'] ); ?>' target="_blank">
			<span class="dashicons dashicons-text-page" title="More Information" style="font-size:19px;color:#413c69;float: right;"></span>

		</a></h3>
	<p>
		<input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>Enable Forgot Phone.
	<p>Select the alternate login method in case your phone is lost, discharged or not with you.</p>
	<input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>KBA&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>OTP over EMAIL
	</p>

	</br><hr>



		<?php
		$opt = 'OUT OF BAND EMAIL';
		?>
		<h3><?php esc_html_e( 'Select the specific set of authentication methods for your users', 'miniorange-login-security' ); ?> 
		<?php momls_tooltip_array( $premium_feature_tooltip_array[1] ); ?>
		<a href='<?php echo esc_attr( $two_factor_premium_doc['Specific set of authentication methods'] ); ?>' target="_blank"><span class="dashicons dashicons-text-page" title="More Information" style="font-size:19px;color:#413c69;float: right;"></span></a></h3>	
		<p>	
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" />
				<?php esc_html_e( 'For all Users', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				<input type="radio" class="option_for_auth2" name="mo2f_all_users_method" value="0"  />
				<?php esc_html_e( 'Specific Roles', 'miniorange-login-security' ); ?>
				</p>
				<table class="mo2f_for_all_users" 
				<?php
				if ( ! get_site_option( 'mo2f_all_users_method' ) ) {
					echo 'hidden';}
				?>
				><tbody>
				<tr>
					<td>
						<input type='checkbox'  name='mo2f_authmethods[]'  value='OUT OF BAND EMAIL' disabled/>Email Verification&nbsp;&nbsp;
					</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SMS' disabled />OTP Over SMS&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PHONE VERIFICATION' disabled />Phone Call Verification&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='SOFT TOKEN' disabled />Soft Token&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='MOBILE AUTHENTICATION' disabled />QR Code Authentication&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='PUSH NOTIFICATIONS' disabled />Push Notifications&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='GOOGLE AUTHENTICATOR' disabled />Google Authenticator&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='AUTHY 2-FACTOR AUTHENTICATION' disabled />AUTHY 2-FACTOR AUTHENTICATION&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name='mo2f_authmethods[]'  value='KBA' disabled />Security Questions (KBA)&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
					<td>
						<input type='checkbox' name='mo2f_authmethods[]'  value='SMS AND EMAIL' disabled /><?php esc_html_e( 'OTP Over SMS And Email', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
					</td>
					<td>
						<input type='checkbox'  name='mo2f_authmethods[]'  value='OTP_OVER_EMAIL' disabled /><?php esc_html_e( 'OTP Over Email', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
					</td>
				</tr>
				</tbody>

				</table>	
		<?php
		$opt     = (array) get_site_option( 'mo2f_auth_methods_for_users' );
		$copt    = array();
		$newcopt = array();
		global $wp_momls_roles;
		if ( ! isset( $wp_momls_roles ) ) {
			$wp_momls_roles = new wp_roles();
		}
		foreach ( $wp_momls_roles->role_names as $user_id => $name ) {
			$copt[ $user_id ] = get_site_option( 'mo2f_auth_methods_for_' . $user_id );
			if ( empty( $copt[ $user_id ] ) ) {
				$copt[ $user_id ] = array( 'No Two Factor Selected' );
			}
			?>

			<span class="mo2f_display_tab mo2f_btn_premium_features" style="padding: 7px 25px;"     ID="mo2f_role_<?php echo esc_attr( $user_id ); ?>" onclick="displayTab('<?php echo esc_attr( $user_id ); ?>');" value="<?php echo esc_attr( $user_id ); ?>" 																												  																												 																													 																															 
																															<?php
																															if ( get_site_option( 'mo2f_all_users_method' ) ) {
																																echo 'hidden';}
																															?>
				> <?php echo esc_html( $name ); ?></span>

			<?php
		}
		?>
		<br><br>
		<?php
			global $wp_momls_roles;
		if ( ! isset( $wp_momls_roles ) ) {
			$wp_momls_roles = new wp_roles();
		}
			print '<div>';
		foreach ( $wp_momls_roles->role_names as $user_id => $name ) {
				$setting = get_site_option( 'mo2fa_' . $user_id );
				$newcopt = $copt[ $user_id ];
			?>
				<table class="mo2f_for_all_roles" id="mo2f_for_all_<?php echo esc_attr( $user_id ); ?>" hidden><tbody>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='OUT OF BAND EMAIL' <?php echo ( in_array( 'OUT OF BAND EMAIL', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Email Verification', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='SMS' <?php echo ( in_array( 'SMS', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'OTP Over SMS', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='PHONE VERIFICATION' <?php echo ( in_array( 'PHONE VERIFICATION', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Phone Call Verification', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='SOFT TOKEN' <?php echo ( in_array( 'SOFT TOKEN', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Soft Token', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='MOBILE AUTHENTICATION' <?php echo ( in_array( 'MOBILE AUTHENTICATION', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'QR Code Authentication', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='PUSH NOTIFICATIONS' <?php echo ( in_array( 'PUSH NOTIFICATIONS', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Push Notifications', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='GOOGLE AUTHENTICATOR' <?php echo ( in_array( 'GOOGLE AUTHENTICATOR', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Google Authenticator', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='AUTHY 2-FACTOR AUTHENTICATION' <?php echo ( in_array( 'AUTHY 2-FACTOR AUTHENTICATION', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'AUTHY 2-FACTOR AUTHENTICATION', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='KBA' <?php echo ( in_array( 'KBA', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'Security Questions (KBA)', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				</tr>
				<tr>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='SMS AND EMAIL' <?php echo ( in_array( 'SMS AND EMAIL', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'OTP Over SMS And Email', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				<td>
				<input type='checkbox' name="<?php echo esc_attr( $user_id ); ?>[]"  value='OTP_OVER_EMAIL' <?php echo ( in_array( 'OTP_OVER_EMAIL', $newcopt, true ) ) ? 'checked="checked"' : ''; ?> disabled /><?php esc_html_e( 'OTP Over Email', 'miniorange-login-security' ); ?>&nbsp;&nbsp;
				</td>
				</tr>
				</tbody>
				</div>
				</table>
				<?php
		}
			print '</div>';
		?>


	<hr>
	<h3>Skip Option for Users During User Enrollment 
	<?php momls_tooltip_array( $premium_feature_tooltip_array[2] ); ?></h3>
	<p>
	<input type="checkbox" class="option_for_auth" name=" Skip Option for users." value="1" checked="checked" disabled> Skip Option for users.
	</p>
	</br><hr>

	<h3>Email verification of Users during User Enrollment 
	<?php momls_tooltip_array( $premium_feature_tooltip_array[3] ); ?>
		<a href='<?php echo esc_attr( $two_factor_premium_doc['Email verification of Users during Inline Registration'] ); ?>' target="_blank">
						<span class="dashicons dashicons-text-page"title="More Information" style="font-size:19px;color:#413c69;float: right;"></span>
	</a></h3>
	<p>
	<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled> Enable users to edit their email address for registration with miniOrange.<br><br>
	<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked" disabled>Skip e-mail verification by user.
	</p>

</br><hr>

	<h3>Select Login Screen Options 
		<a href='<?php echo esc_attr( $two_factor_premium_doc['Select login screen option'] ); ?>'  target="_blank">
						<span class="dashicons dashicons-text-page" title="More Information" style="font-size:19px;color:#413c69;float: right;"></span>
	</a></h3>

		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="1" checked="checked"  disabled> Login with password + 2nd Factor <span style="color: red">(Recommended)</span>
	<?php momls_tooltip_array( $premium_feature_tooltip_array[4] ); ?>

	</br>
	</br>
		<input type="radio" class="option_for_auth" name="mo2f_all_users_method" value="0" disabled>
		Login with 2nd Factor only <span style="color: red">(No password required)
			<a onclick="mo2f_login_with_username_only()">&nbsp;&nbsp;See Preview</a></span>
			<?php momls_tooltip_array( $premium_feature_tooltip_array[5] ); ?>
	</br>      

	<div id="mo2f_login_with_username_only" style="display: none;">
		<?php
		echo '<div style="text-align:center;"><img  style="margin-top:5px;" src="' . esc_url( $login_with_usename_only_url ) . '"></div><br>';
		?>
	</div>

	</br>

	<input type="checkbox" class="option_for_auth" value="0" disabled>I want to hide default login form.
	<a onclick="mo2f_hide_login_form()">&nbsp;&nbsp;See Preview</a>   
	<?php momls_tooltip_array( $premium_feature_tooltip_array[6] ); ?>
	<div id="mo2f_hide_login" style="display: none;">
		<?php
			echo '<div style="text-align:center;"><img  style="margin-top:5px;" src="' . esc_url( $hide_login_form_url ) . '"></div><br>';
		?>
	</div>
	</div></div>

<script type="text/javascript">
	jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
	function displayTab(role){
		jQuery('.mo2f_display_tab').removeClass("mo2f_blue_premium_features");
		jQuery('.mo2f_display_tab').addClass("mo2f_btn_premium_features");
		jQuery('#mo2f_role_'+role).removeClass("mo2f_btn_premium_features");
		jQuery('#mo2f_role_'+role).addClass("mo2f_blue_premium_features");
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('#mo2f_for_all_'+role).show();
	}
	jQuery(".option_for_auth").click(function(){
		jQuery('.mo2f_display_tab').hide();
		jQuery('.mo2f_for_all_roles').hide();
		jQuery('.mo2f_for_all_users').show();
	})
	jQuery(".option_for_auth2").click(function(){
		jQuery('.mo2f_display_tab').show();
		jQuery('.mo2f_for_all_users').hide();
	}
	)
	function mo2f_login_with_username_only()
	{
		jQuery('#mo2f_login_with_username_only').toggle();
	}
	function mo2f_hide_login_form()
	{
		jQuery('#mo2f_hide_login').toggle();
	}
</script>

<?php
/**
 * Function to get premium features addon.
 *
 * @param string $mo2f_addon_feature Addon feature.
 * @return void
 */
function momls_tooltip_array( $mo2f_addon_feature ) {
	echo '<div class="mo2f_tooltip_addon">
            <span class="dashicons dashicons-info mo2f_info_tab"></span>
            <span class="mo2f_tooltiptext_addon" >' . esc_html( $mo2f_addon_feature ) . '
            </span>
        </div>';
}

?>

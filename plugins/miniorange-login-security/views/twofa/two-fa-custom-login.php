<?php
/**
 * This file contains html UI of premium features.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	global $current_user_info;
	$current_user_info = wp_get_current_user();
	global $momlsdb_queries;
?>
	<form name="f" id="custom_css_form_add" method="post" action="">
			<input type="hidden" name="option" value="mo_auth_custom_options_save" />

			<div class="mo2f_table_layout">
				<div id="mo2f_custom_addon_hide">

				<h3 id="custom_description" >
					<?php esc_html_e( 'This helps you to modify and redesign the 2FA prompt to match according to your website and various customizations in the plugin dashboard.', 'miniorange-login-security' ); ?>
					<b ><a  href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress-wp-2fa#pricing" target="_blank" style="color: red;"&nbsp;&nbsp;> [ PREMIUM ] </a></b>
				</h3>
				<br>
			</div>		
				<h3><?php esc_html_e( 'Customize Plugin Icon', 'miniorange-login-security' ); ?></h3><hr><br>
				<div style="margin-left:2%">
					<input type="checkbox" id="mo2f_enable_custom_icon" name="mo2f_enable_custom_icon" value="1" 
					<?php
					checked( get_site_option( 'mo2f_enable_custom_icon' ) === 1 );
					echo 'disabled';
					?>
					/> 
					<?php esc_html_e( 'Change Plugin Icon.', 'miniorange-login-security' ); ?>
					<br><br>
					<div class="mo2f_advanced_options_note"  ><p style="padding:5px;"><i>
					<?php
						esc_html_e(
							'
						Go to /wp-content/uploads/miniorange folder and upload a .png image with the name "plugin_icon" (Max Size: 20x34px).',
							'miniorange-login-security'
						);
						?>
						</i></p>
					</div>
				</div>
<br>
				<h3><?php esc_html_e( 'Customize Plugin Name', 'miniorange-login-security' ); ?></h3><hr><br>
				<div style="margin-left:2%">
					<?php esc_html_e( 'Change Plugin Name:', 'miniorange-login-security' ); ?> &nbsp;
					<input type="text" class="mo2f_table_textbox" style="width:35% 	" id="mo2f_custom_plugin_name" name="mo2f_custom_plugin_name" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_plugin_name' ) ); ?>" placeholder="<?php esc_attr_e( 'Enter a custom Plugin Name.', 'miniorange-login-security' ); ?>" />
					<br><br>
					<div class="mo2f_advanced_options_note" ><p><i>
						<?php esc_html_e( 'This will be the Plugin Name You and your Users see in  WordPress Dashboard.', 'miniorange-login-security' ); ?>
					</i></p> </div>
					<input type="submit" name="submit" value="Save Settings" style="margin-left:2%; background-color: var(--mo2f-theme-color); color: white;box-shadow:none;" class="momls_wpns_button momls_wpns_button1" 
					<?php
						echo 'disabled';
					?>
						/>
				</div>	 	
					<br>		
			</div>

		<br>


	</form>				
	<?php momls_show_2_factor_custom_design_options( $current_user_info ); ?>
	<br>
	<div class="mo2f_table_layout">
	<h3>
	<?php
	esc_html_e( 'Custom Email and SMS Templates', 'miniorange-login-security' );

	?>
	</h3><hr>
	<div style="margin-left:2%">
					<p><?php esc_html_e( 'You can change the templates for Email and SMS as per your requirement.', 'miniorange-login-security' ); ?></p>
					<?php
					if ( momls_is_customer_registered() ) {
						if ( get_site_option( 'mo2f_miniorange_admin' ) === $current_user_info->ID ) {
							?>
								<a style="box-shadow: none;" class="momls_wpns_button momls_wpns_button1"<?php echo 'disabled'; ?>><?php esc_html_e( 'Customize Email Template', 'miniorange-login-security' ); ?></a><span style="margin-left:10px;"></span>
								<a style="box-shadow: none;" class="momls_wpns_button momls_wpns_button1"<?php echo 'disabled'; ?> ><?php esc_html_e( 'Customize SMS Template', 'miniorange-login-security' ); ?></a>
							<?php
						}
					} else {
						?>
						<a class="momls_wpns_button momls_wpns_button1"<?php echo 'disabled'; ?>style="pointer-events: none;cursor: default;box-shadow: none;"><?php esc_html_e( 'Customize Email Template', 'miniorange-login-security' ); ?></a>
							<span style="margin-left:10px;"></span>
						<a class="momls_wpns_button momls_wpns_button1"<?php echo 'disabled'; ?> style="pointer-events: none;cursor: default;box-shadow: none;"><?php esc_html_e( 'Customize SMS Template', 'miniorange-login-security' ); ?></a>
					<?php } ?>
					</div>
					</div>
				<br>

		<div class="mo2f_table_layout">
			<h3><?php esc_html_e( 'Integrate your websites\'s theme with the 2FA plugin\'s popups', 'miniorange-login-security' ); ?></h3><hr>
			<div style="margin-left:2%">
				<p><?php esc_html_e( 'Contact Us through the support forum in the right for the UI integration.', 'miniorange-login-security' ); ?></p>
			</div>

		</div>
		<br>
				<form style="display:none;" id="mo2fa_addon_loginform" action="<?php echo esc_attr( get_site_option( 'mo2f_host_name' ) ) . '/moas/login'; ?>" 
		target="_blank" method="post">
			<input type="email" name="username" value="<?php echo esc_attr( $momlsdb_queries->momls_get_user_detail( 'mo2f_user_email', $current_user_info->ID ) ); ?>" />
			<input type="text" name="redirectUrl" value="" />
		</form>
				<script>
			function mo2fLoginMiniOrangeDashboard(redirectUrl){ 
				document.getElementById('mo2fa_addon_loginform').elements[1].value = redirectUrl;
				jQuery('#mo2fa_addon_loginform').submit();
			}
		</script>

	<?php
	/**
	 * This function is used to show UI for personalisation of login form.
	 *
	 * @param object $current_user_info is used to get current user's email or id.
	 * @return void
	 */
	function momls_show_2_factor_custom_design_options( $current_user_info ) {
		?>
			<br>
				<div class="mo2f_table_layout">
			<div id="mo2f_custom_addon_hide">
			<br>
		</div>
			<form name="f"  id="custom_css_reset_form" method="post" action="" >
			<input type="hidden" name="option" value="mo_auth_custom_design_options_reset" />
			<h3><?php esc_html_e( 'Customize UI of Login Pop up\'s', 'miniorange-login-security' ); ?></h3>
			<input type="submit" name="submit" value="Reset Settings" class="momls_wpns_button momls_wpns_button1"  style="float:right; background-color: var(--mo2f-theme-color); color: white;box-shadow: none;"
			<?php
					echo 'disabled';
			?>
						/>
						</form>
			<form name="f"  id="custom_css_form" method="post" action="">
			<input type="hidden" name="option" value="mo_auth_custom_design_options_save" />	
					<table class="mo2f_settings_table" style="margin-left:2%">
					<tr>
						<td><?php esc_html_e( 'Background Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_background_color" name="mo2f_custom_background_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_background_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Popup Background Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_popup_bg_color" name="mo2f_custom_popup_bg_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_popup_bg_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Button Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_button_color" name="mo2f_custom_button_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_button_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Links Text Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_links_text_color" name="mo2f_custom_links_text_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_links_text_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Popup Message Text Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_notif_text_color" name="mo2f_custom_notif_text_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_notif_text_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Popup Message Background Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_notif_bg_color" name="mo2f_custom_notif_bg_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_notif_bg_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'OTP Token Background Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_otp_bg_color" name="mo2f_custom_otp_bg_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_otp_bg_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'OTP Token Text Color:', 'miniorange-login-security' ); ?> </td>
						<td><input type="text" id="mo2f_custom_otp_text_color" name="mo2f_custom_otp_text_color" <?php echo 'disabled'; ?> value="<?php echo esc_attr( get_site_option( 'mo2f_custom_otp_text_color' ) ); ?>" class="my-color-field" /> </td>
					</tr>
					</table>
					</br>
					<input type="submit" name="submit"   style="margin-left:2%; background-color: var(--mo2f-theme-color); color: white; box-shadow: none;" value="Save Settings" class="momls_wpns_button momls_wpns_button1" 
				<?php
					echo 'disabled';
				?>
						/>					
			</form>
			</div>
			<?php
	}


<?php
/**
 * This file contains frontend to show setup wizard to configure Google Authenticator.
 *
 * @package miniorange-login-security/views/twofa/setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Function to configure Google Authenticator.
 *
 * @param object $user User object.
 * @return void
 */
function momls_configure_google_authenticator( $user ) {
	$mo2f_google_auth = isset( $_SESSION['mo2f_google_auth'] ) ? sanitize_text_field( $_SESSION['mo2f_google_auth'] ) : null;
	$data             = isset( $_SESSION['mo2f_google_auth'] ) ? sanitize_text_field( $mo2f_google_auth['ga_qrCode'] ) : null;
	$ga_secret        = isset( $_SESSION['mo2f_google_auth'] ) ? sanitize_text_field( $mo2f_google_auth['ga_secret'] ) : null;
	$h_size           = 'h3';
	$gauth_name       = get_site_option( 'mo2f_google_appname' );
	$gauth_name       = $gauth_name ? $gauth_name : 'miniOrangeAuth';
	?>
	<table>
		<tr>
			<td class="mo2f_google_authy_step2">
				<?php echo '<' . esc_attr( $h_size ) . '>' . esc_html_e( 'Step-1: Set up Google/Authy/LastPass Authenticator', 'miniorange-login-security' ) . '</' . esc_attr( $h_size ) . '>'; ?>

				<hr>

				<p style="background-color:#a3e8c2;padding:5px;">
					<?php esc_html_e( 'You can configure this method in your Google/Authy/LastPass Authenticator apps.', 'miniorange-login-security' ); ?>
				</p>

					<h4>1. <?php esc_html_e( 'Install the Authenticator App that you wish to configure, in your phone.', 'miniorange-login-security' ); ?></h4>
					<div style="margin-left:40px;">
						<input type="radio" name="google" value="ga" checked> Google Authenticator &nbsp;&nbsp;
						<input type="radio" name="authy" value="aa"> Authy Authenticator &nbsp;&nbsp;
						<input type="radio" name="lastpass" value="lpa"> LastPass Authenticator &nbsp;&nbsp;
					</div>

				<span id="links_to_apps"></span>
				<div id="mo2f_change_app_name">
				<h4>2. <?php esc_html_e( 'Choose the account name to be configured in the App:', 'miniorange-login-security' ); ?></h4>
				<div style="margin-left:40px;">
					<form name="f"  id="login_settings_appname_form" method="post" action="">
						<input type="hidden" name="option" value="mo2f_google_appname" />
						<input type="hidden" name="mo2f_google_appname_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-google-appname-nonce' ) ); ?>"/>
						<input type="text" class="mo2f_table_textbox" style="width:22% !important;" pattern="[^\s][A-Z]*[a-z]*[0-9]*[^\s]" name="mo2f_google_auth_appname" placeholder="Enter the app name" value="<?php echo esc_attr( $gauth_name ); ?>"  />&nbsp;&nbsp;&nbsp;

						<input type="submit" name="submit" value="Save App Name" class="momls_wpns_button momls_wpns_button1" />

									<br>
					</form>
				</div>
				</div>
				<h4><span id="step_number"></span><?php esc_html_e( 'Scan the QR code from the Authenticator App.', 'miniorange-login-security' ); ?></h4>
				<div style="margin-left:40px;">
					<ol>
						<li><?php esc_html_e( 'In the app, tap on Menu and select "Set up account".', 'miniorange-login-security' ); ?></li>
						<li><?php esc_html_e( 'Select "Scan a barcode". Use your phone\'s camera to scan this barcode.', 'miniorange-login-security' ); ?></li>
						<div id="displayQrCode"style="padding:10px;"><?php echo '<img src="data:image/jpg;base64,' . esc_attr( $data ) . '" />'; ?></div>

					</ol>

					<div><a data-toggle="collapse" href="#mo2f_scanbarcode_a"
							aria-expanded="false"><b><?php esc_html_e( 'Can\'t scan the barcode? ', 'miniorange-login-security' ); ?></b></a>
					</div>
					<div class="mo2f_collapse" id="mo2f_scanbarcode_a">
						<ol class="mo2f_ol">
							<li><?php esc_html_e( 'Tap on Menu and select', 'miniorange-login-security' ); ?>
								<b> <?php esc_html_e( ' Set up account ', 'miniorange-login-security' ); ?></b>.
							</li>
							<li><?php esc_html_e( 'Select' ); ?>
								<b> <?php esc_html_e( ' Enter provided key ', 'miniorange-login-security' ); ?></b>.
							</li>
							<li><?php esc_html_e( 'For the', 'miniorange-login-security' ); ?>
								<b> <?php esc_html_e( ' Enter account name ', 'miniorange-login-security' ); ?></b>
								<?php esc_html_e( 'field, type your preferred account name', 'miniorange-login-security' ); ?>.
							</li>
							<li><?php esc_html_e( 'For the', 'miniorange-login-security' ); ?>
								<b> <?php esc_html_e( ' Enter your key ', 'miniorange-login-security' ); ?></b>
								<?php esc_html_e( 'field, type the below secret key', 'miniorange-login-security' ); ?>:
							</li>

							<div class="mo2f_google_authy_secret_outer_div">
								<div class="mo2f_google_authy_secret_inner_div">
									<?php echo esc_html( $ga_secret ); ?>
								</div>
								<div class="mo2f_google_authy_secret">
									<?php esc_html_e( 'Spaces do not matter', 'miniorange-login-security' ); ?>.
								</div>
							</div>
							<li><?php esc_html_e( 'Key type: make sure', 'miniorange-login-security' ); ?>
								<b> <?php esc_html_e( ' Time-based ', 'miniorange-login-security' ); ?></b>
								<?php esc_html_e( ' is selected', 'miniorange-login-security' ); ?>.
							</li>

							<li><?php esc_html_e( 'Tap Add.', 'miniorange-login-security' ); ?></li>
						</ol>
					</div>
				<br>
				</div>

			</td>
			<td class="mo2f_vertical_line"></td>
			<td class="mo2f_google_authy_step3">
				<h4>
				<?php
				echo '<' . esc_attr( $h_size ) . '>' . esc_html_e( 'Step-2: Verify and Save', 'miniorange-login-security' ) . '</' . esc_attr( $h_size ) . '>';
				?>
</h4>
				<hr>
				<div style="<?php echo isset( $_SESSION['mo2f_google_auth'] ) ? 'display:block' : 'display:none'; ?>">
					<div><?php esc_html_e( 'After you have scanned the QR code and created an account, enter the verification code from the scanned account here.', 'miniorange-login-security' ); ?></div>
					<br>
					<form name="f" method="post" action="">
						<span><b><?php esc_html_e( 'Code:', 'miniorange-login-security' ); ?> </b>&nbsp;
						<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" required="true"
							type="text" name="google_token" placeholder="<?php esc_attr_e( 'Enter OTP', 'miniorange-login-security' ); ?>"
							style="width:95%;"/></span><br><br>
						<input type="hidden" name="google_auth_secret" value="<?php echo esc_attr( $ga_secret ); ?>"/>
						<input type="hidden" name="option" value="momls_configure_google_authenticator_validate"/>
						<input type="hidden" name="momls_configure_google_authenticator_validate_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-configure-google-authenticator-validate-nonce' ) ); ?>"/>
						<input type="submit" name="validate" id="validate" class="momls_wpns_button momls_wpns_button1"
							style="float:left;" value="<?php esc_attr_e( 'Verify and Save', 'miniorange-login-security' ); ?>"/>
					</form>
					<form name="f" method="post" action="" id="mo2f_go_back_form">
										<input type="hidden" name="option" value="mo2f_go_back"/>
										<input type="submit" name="back" id="go_back" class="momls_wpns_button momls_wpns_button1"
												value="<?php esc_attr_e( 'Back', 'miniorange-login-security' ); ?>"/>
											<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-go-back-nonce' ) ); ?>"/>
									</form>
				</div>
			</td>
		</tr>
	</table>
	<script>
		jQuery(document).ready(function(){
			jQuery(this).scrollTop(0);
			if(jQuery('input[type=radio][name=google]').is(':checked')){
				jQuery('#links_to_apps').html('<p style="background-color:#e8e4e4;padding:5px;margin-left:40px;width:65%">' +
					'Get the Google Authenticator App - <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"><b><?php esc_html_e( 'Android Play Store', 'miniorange-login-security' ); ?></b></a>, &nbsp;' +
					'<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605" target="_blank"><b><?php esc_html_e( 'iOS App Store', 'miniorange-login-security' ); ?>.</b>&nbsp;</p>');
				jQuery('#mo2f_change_app_name').show();
				jQuery('#links_to_apps').show();
			}
		});

		jQuery('input[type=radio][name=mo2f_app_type_radio]').change(function () {
			jQuery('#mo2f_configure_google_authy_form1').submit();
		});

		jQuery('#links_to_apps').show();
		jQuery('#mo2f_change_app_name').hide();
		jQuery('#step_number').html('2. ');

		jQuery('input[type=radio][name=google]').click(function(){
			jQuery('#links_to_apps').html('<p style="background-color:#e8e4e4;padding:5px;margin-left:40px;width:65%">' +
				'Get the Google Authenticator App - <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank"><b><?php esc_html_e( 'Android Play Store', 'miniorange-login-security' ); ?></b></a>, &nbsp;' +
				'<a href="http://itunes.apple.com/us/app/google-authenticator/id388497605" target="_blank"><b><?php esc_html_e( 'iOS App Store', 'miniorange-login-security' ); ?>.</b>&nbsp;</p>');
			jQuery('#step_number').html('3. ');
			jQuery("input[type=radio][name=authy]").prop("checked", false);
			jQuery("input[type=radio][name=lastpass]").prop("checked", false);
			jQuery('#mo2f_change_app_name').show();
			jQuery('#links_to_apps').show();
		});

		jQuery('input[type=radio][name=authy]').click(function(){
			jQuery('#links_to_apps').html('<p style="background-color:#e8e4e4;padding:5px;margin-left:40px;width:65%">' +
				'Get the Authy Authenticator App - <a href="https://play.google.com/store/apps/details?id=com.authy.authy" target="_blank"><b><?php esc_html_e( 'Android Play Store', 'miniorange-login-security' ); ?></b></a>, &nbsp;' +
				'<a href="https://itunes.apple.com/in/app/authy/id494168017" target="_blank"><b><?php esc_html_e( 'iOS App Store', 'miniorange-login-security' ); ?>.</b>&nbsp;</p>');
			jQuery("input[type=radio][name=google]").prop("checked", false);
			jQuery("input[type=radio][name=lastpass]").prop("checked", false);
			jQuery('#mo2f_change_app_name').hide();
			jQuery('#step_number').html('2. ');
			jQuery('#links_to_apps').show();
		});

		jQuery('input[type=radio][name=lastpass]').click(function(){
			jQuery('#links_to_apps').html('<p style="background-color:#e8e4e4;padding:5px;margin-left:40px;width:65%">' +
				'Get the LastPass Authenticator App - <a href="https://play.google.com/store/apps/details?id=com.lastpass.authenticator" target="_blank"><b><?php esc_html_e( 'Android Play Store', 'miniorange-login-security' ); ?></b></a>, &nbsp;' +
				'<a href="https://itunes.apple.com/in/app/lastpass-authenticator/id1079110004" target="_blank"><b><?php esc_html_e( 'iOS App Store', 'miniorange-login-security' ); ?>.</b>&nbsp;</p>');
			jQuery("input[type=radio][name=authy]").prop("checked", false);
			jQuery("input[type=radio][name=google]").prop("checked", false);
			jQuery('#mo2f_change_app_name').show();
			jQuery('#step_number').html('3. ');
			jQuery('#links_to_apps').show();
		});
	</script>
	<?php
}

?>

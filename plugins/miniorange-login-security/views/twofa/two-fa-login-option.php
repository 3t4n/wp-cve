<?php
/**
 * This file contains the UI for various plugin settings for user login.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $momlsdb_queries;
	$roles = get_editable_roles();

	$mo_2factor_user_registration_status = $momlsdb_queries->momls_get_user_detail( 'mo_2factor_user_registration_status', $user->ID );

?>
<?php if ( ! get_site_option( 'mo2f_is_NC' ) && get_site_option( 'mo2f_is_NC' ) ) { ?>
	<div class="mo2f_advanced_options_EC" style="width: 85%;border: 0px;">
			<?php echo esc_html( momls_get_standard_premium_options( $user ) ); ?>
		</div>
	<?php
} else {

	$mo2f_active_tab = '2factor_setup';
	?>
	<div class="mo2f_table_layout">
		<div class="mo2f_advanced_options_EC">

			<div id="mo2f_login_options">
				<a href="#standard_premium_options" style="float:right">Show Standard/Premium
					Features</a></h3>

				<form name="f" id="login_settings_form" method="post" action="">
					<input type="hidden" name="option" value="mo_auth_login_settings_save"/>
					<input type="hidden" name="mo_auth_login_settings_save_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo-auth-login-settings-save-nonce' ) ); ?>"/>
					<div class="row">
						<h3 style="padding:10px;"><?php esc_html_e( 'Select Login Screen Options', 'miniorange-login-security' ); ?>

					</div>
					<hr>
					<br>


					<div style="margin-left: 2%;">
						<input type="radio" name="mo2f_login_policy" value="1"
						<?php
						checked( get_site_option( 'mo2f_login_policy' ) );
						if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status || get_site_option( 'is_onprem' ) ) ) {
							echo 'disabled';
						}
						?>
							/>
						<?php esc_html_e( 'Login with password + 2nd Factor ', 'miniorange-login-security' ); ?>
						<i>(<?php esc_html_e( 'Default & Recommended', 'miniorange-login-security' ); ?>)&nbsp;&nbsp;</i>

						<br><br>

						<div style="margin-left:6%;">
							<input type="checkbox" id="mo2f_remember_device" name="mo2f_remember_device"
								value="1" 
								<?php
									checked( get_site_option( 'mo2f_remember_device' ) === 1 );
								if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status || get_site_option( 'is_onprem' ) ) ) {
									echo 'disabled';
								}
								?>
							/>Enable
							'<b><?php esc_html_e( 'Remember device', 'miniorange-login-security' ); ?></b>' <?php esc_html_e( 'option ', 'miniorange-login-security' ); ?><br>

							<div class="mo2f_advanced_options_note"><p style="padding:5px;">
									<i><?php esc_html_e( ' Checking this option will display an option ', 'miniorange-login-security' ); ?>
										'<b><?php esc_html_e( 'Remember this device', 'miniorange-login-security' ); ?></b>'<?php esc_html_e( 'on 2nd factor screen. In the next login from the same device, user will bypass 2nd factor, i.e. user will be logged in through username + password only.', 'miniorange-login-security' ); ?>
									</i></p></div>
						</div>

						<br>

						<input type="radio" name="mo2f_login_policy" value="0"
							<?php
							checked( ! get_site_option( 'mo2f_login_policy' ) );
							if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status || get_site_option( 'is_onprem' ) ) ) {
								echo 'disabled';
							}
							?>
							/>
						<?php esc_html_e( 'Login with 2nd Factor only ', 'miniorange-login-security' ); ?>
						<i>(<?php esc_html_e( 'No password required.', 'miniorange-login-security' ); ?>)</i> &nbsp;<a 
							data-toggle="collapse"id="showpreview1"href="#preview9"aria-expanded="false"><?php esc_html_e( 'See preview', 'miniorange-login-security' ); ?></a>
						<br>
						<div class="mo2f_collapse" id="preview9" style="height:300px;">
							<div class = "mo2f_align_center"><br>
								<img style="height:300px;"
									src="https://login.xecurify.com/moas/images/help/login-help-1.png">
							</div>
						</div>
						<div class="mo2f_advanced_options_note"><p style="padding:5px;">
								<i><?php esc_html_e( 'Checking this option will add login with your phone button below default login form. Click above link to see the preview.', 'miniorange-login-security' ); ?></i>
							</p></div>
						<div id="loginphonediv" hidden><br>
							<input type="checkbox" id="mo2f_login_with_username_and_2factor"
								name="mo2f_login_with_username_and_2factor"
								value="1" 
								<?php
									checked( get_site_option( 'mo2f_enable_login_with_2nd_factor' ) === 1 );
								if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status || get_site_option( 'is_onprem' ) ) ) {
									echo 'disabled';
								}
								?>
							/>
							<?php esc_html_e( '	I want to hide default login form.', 'miniorange-login-security' ); ?> &nbsp;<a
									class=""
									data-toggle="collapse"
									href="#preview9"
									id = 'showpreview8'
									aria-expanded="false"><?php esc_html_e( 'See preview', 'miniorange-login-security' ); ?></a>
							<br>
							<div class="mo2f_collapse" id="preview8" style="height:300px;">
								<div class="mo2f_align_center"><br>
									<img style="height:300px;"
										src="https://login.xecurify.com/moas/images/help/login-help-3.png">
								</div>
							</div>
							<br>
							<div class="mo2f_advanced_options_note"><p style="padding:5px;">
									<i><?php esc_html_e( 'Checking this option will hide default login form and just show login with your phone. Click above link to see the preview.', 'miniorange-login-security' ); ?></i>
								</p></div>
						</div>
						<br>
					</div>
					<div>
						<h3 style="padding:10px;"><?php esc_html_e( 'Backup Methods', 'miniorange-login-security' ); ?></h3></div>
					<hr>
					<br>
					<div style="margin-left: 2%">
						<input type="checkbox" id="mo2f_forgotphone" name="mo2f_forgotphone"
							value="1" 
							<?php
								checked( get_site_option( 'mo2f_enable_forgotphone' ) === 1 );
							if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status ) ) {
								echo 'disabled';
							}
							?>
						/>
						<?php esc_html_e( 'Enable Forgot Phone.', 'miniorange-login-security' ); ?>

						<div class="mo2f_advanced_options_note"><p style="padding:5px;">
								<i><?php esc_html_e( 'This option will provide you an alternate way of logging in to your site in case you are unable to login with your primary authentication method.', 'miniorange-login-security' ); ?></i>
							</p></div>
						<br>

					</div>

										<div>
						<h3 style="padding:10px;"><?php esc_html_e( 'Enable Two-Factor plugin', 'miniorange-login-security' ); ?></h3></div>
					<hr>
					<br>
					<div style="margin-left: 2%">
						<input type="checkbox" id="mo2f_activate_plugin" name="mo2f_activate_plugin" value="1" 
						<?php
						checked( get_site_option( 'mo2f_activate_plugin' ) === 1 );
						if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status ) ) {
							echo 'disabled';
						}
						?>
						/>
	<?php esc_html_e( ' Enable Two-Factor plugin. ( If you disable this checkbox, Two-Factor plugin will not invoke for any user during login.)', 'miniorange-login-security' ); ?>

						<div class="mo2f_advanced_options_note"><p style="padding:5px;">
								<i><?php esc_html_e( 'Disabling this option will allow all users to login with their username and password.Two-Factor will not invoke during login.', 'miniorange-login-security' ); ?></i>
							</p></div>
						<br>

					</div>


					<div>
						<h3 style="padding:10px;">XML-RPC <?php esc_html_e( 'Settings', 'miniorange-login-security' ); ?></h3></div>
					<hr>
					<br>
					<div style="margin-left: 2%">
						<input type="checkbox" id="mo2f_enable_xmlrpc" name="mo2f_enable_xmlrpc"
							value="1" 
							<?php
								checked( get_site_option( 'mo2f_enable_xmlrpc' ) === 1 );
							if ( ! ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status ) ) {
								echo 'disabled';
							}
							?>
						/>
						<?php esc_html_e( 'Enable XML-RPC Login.', 'miniorange-login-security' ); ?>
						<div class="mo2f_advanced_options_note"><p style="padding:5px;">
								<i><?php esc_html_e( 'Enabling this option will decrease your overall login security. Users will be able to login through external applications which support XML-RPC without authenticating from miniOrange. ', 'miniorange-login-security' ); ?>
									<b><?php esc_html_e( 'Please keep it unchecked.', 'miniorange-login-security' ); ?></b></i></p></div>

					</div>

					<br><br>
					<div style="padding:10px;">
						<div class="mo2f_align_center">
						<?php
						if ( 'MO_2_FACTOR_PLUGIN_SETTINGS' === $mo_2factor_user_registration_status || get_site_option( 'is_onprem' ) ) {
							?>
							<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Settings', 'miniorange-login-security' ); ?>"
							class="momls_wpns_button momls_wpns_button1">
							<?php
						} else {
							?>
							<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Settings', 'miniorange-login-security' ); ?>"
							class="momls_wpns_button" disabled style="background-color: var(--mo2f-theme-color);padding: 11px 28px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px;">
							<?php
						}
						?>


						</div>
					</div>
					<br></form>
				<br>
				<br>
				<hr>
			</div>
		</div>
			<?php echo esc_html( momls_get_standard_premium_options( $user ) ); ?>
		</div>
		<?php
}
?>

		<script>

		if (jQuery("input[name=mo2f_login_policy]:radio:checked").val() === 0) {
			jQuery('#loginphonediv').show();
		}
		jQuery("input[name=mo2f_login_policy]:radio").change(function () {
			if (this.value === 1) {
				jQuery('#loginphonediv').hide();
			} else {
				jQuery('#loginphonediv').show();
			}
		});

		jQuery('#preview9').hide();
		jQuery('#showpreview1').click(function(){
			jQuery('#preview9').slideToggle(700);    
		});

		jQuery('#preview7').hide();
		jQuery('#showpreview7').click(function(){
			jQuery('#preview7').slideToggle(700);    
		});

		jQuery('#preview6').hide();
		jQuery('#showpreview6').click(function(){
			jQuery('#preview6').slideToggle(700);    
		});

		jQuery('#preview8').hide();
		jQuery('#showpreview8').click(function(){
			jQuery('#preview8').slideToggle(700);    
		});
		function show_backup_options() {
			jQuery("#backup_options").slideToggle(700);
			jQuery("#login_options").hide();
			jQuery("#customizations").hide();
			jQuery("#customizations_prem").hide();
			jQuery("#backup_options_prem").hide();
			jQuery("#inline_registration_options").hide();
		}

		function show_customizations() {
			jQuery("#login_options").hide();
			jQuery("#inline_registration_options").hide();
			jQuery("#backup_options").hide();
			jQuery("#customizations_prem").hide();
			jQuery("#backup_options_prem").hide();
			jQuery("#customizations").slideToggle(700);

		}

		jQuery("#backup_options_prem").hide();

		function show_backup_options_prem() {
			jQuery("#backup_options_prem").slideToggle(700);
			jQuery("#login_options").hide();
			jQuery("#customizations").hide();
			jQuery("#customizations_prem").hide();
			jQuery("#inline_registration_options").hide();
			jQuery("#backup_options").hide();
		}

		jQuery("#login_options").hide();

		function show_login_options() {
			jQuery("#inline_registration_options").hide();
			jQuery("#customizations").hide();
			jQuery("#backup_options").hide();
			jQuery("#backup_options_prem").hide();
			jQuery("#customizations_prem").hide();
			jQuery("#login_options").slideToggle(700);
		}

		jQuery("#inline_registration_options").hide();

		function show_inline_registration_options() {
			jQuery("#login_options").hide();
			jQuery("#customizations").hide();
			jQuery("#backup_options").hide();
			jQuery("#backup_options_prem").hide();
			jQuery("#customizations_prem").hide();
			jQuery("#inline_registration_options").slideToggle(700);

		}

		jQuery("#customizations_prem").hide();

		function show_customizations_prem() {
			jQuery("#inline_registration_options").hide();
			jQuery("#login_options").hide();
			jQuery("#customizations").hide();
			jQuery("#backup_options").hide();
			jQuery("#backup_options_prem").hide();
			jQuery("#customizations_prem").slideToggle(700);

		}

		function showLoginOptions() {
			jQuery("#mo2f_login_options").show();
		}

		function showLoginOptions() {
			jQuery("#mo2f_login_options").show();
		}


	</script>
<?php
/**
 * This function is used to show plugin's premium feature.
 *
 * @param object $user used to get the current user's email or id.
 * @return void
 */
function momls_get_standard_premium_options( $user ) {
	$is_nc = get_site_option( 'mo2f_is_NC' );

	?>
	<div >
		<div id="standard_premium_options" style="text-align: center;">
			<p style="font-size:22px;color:darkorange;padding:10px;"><?php esc_html_e( 'Features in the Standard Plan', 'miniorange-login-security' ); ?></p>

		</div>

		<hr>
		<?php if ( $is_nc ) { ?>
			<div>
				<a class="mo2f_view_backup_options" onclick="show_backup_options()">
					<img src="<?php echo esc_url( plugins_url( 'includes/images/right-arrow.png', dirname( dirname( __FILE__ ) ) ) ); ?>"
						class="mo2f_advanced_options_images"/>

					<p class="mo2f_heading_style"><?php esc_html_e( 'Backup Options', 'miniorange-login-security' ); ?></p>
				</a>

			</div>
			<div id="backup_options" style="margin-left: 5%;">

				<div class="mo2f_advanced_options_note"><p style="padding:5px;">
						<i>
						<?php
						esc_html_e(
							'Use these backup options to login to your site in case your 
                                phone is lost / not accessible or if you are not able to login using your primary 
                                authentication method.',
							'miniorange-login-security'
						);
						?>
							</i></p></div>

				<ol class="mo2f_ol">
					<li><?php esc_html_e( 'KBA (Security Questions)', 'miniorange-login-security' ); ?></li>
				</ol>

			</div>
		<?php } ?>

		<div>
			<a class="mo2f_view_customizations" onclick="show_customizations()">


				<p class="mo2f_heading_style"><?php esc_html_e( 'Customizations', 'miniorange-login-security' ); ?></p>
			</a>
		</div>


		<div id="customizations" style="margin-left: 5%;">

			<p style="font-size:15px;font-weight:bold">1. <?php esc_html_e( 'Login Screen Options', 'miniorange-login-security' ); ?></p>
			<div>
				<ul style="margin-left:4%" class="mo2f_ol">
					<li><?php esc_html_e( 'Login with WordPress username/password and 2nd Factor', 'miniorange-login-security' ); ?> <a
								class="" data-toggle="collapse" id="showpreview7" href="#preview7"
								aria-expanded="false">[ <?php esc_html_e( 'See Preview', 'miniorange-login-security' ); ?>
							]</a>
							<div class="mo2f_collapse" id="preview7" style="height:300px;">
							<div class="mo2f_align_center"><br>
								<img style="height:300px;"
									src="https://login.xecurify.com/moas/images/help/login-help-1.png">
							</div>
						</div>	
					</li><br>
					<li><?php esc_html_e( 'Login with WordPress username and 2nd Factor only', 'miniorange-login-security' ); ?> <a
								class="" data-toggle="collapse" id="showpreview6" href="#preview7"
								aria-expanded="false">[ <?php esc_html_e( 'See Preview', 'miniorange-login-security' ); ?>
							]</a>
						<br>
						<div class="mo2f_collapse" id="preview6" style="height:300px;">
							<div class="mo2f_align_center"><br>
								<img style="height:300px;"
									src="https://login.xecurify.com/moas/images/help/login-help-3.png">
							</div>
						</div>
						<br>
					</li>
				</ul>


			</div>
			<br>
			<p style="font-size:15px;font-weight:bold">2. <?php esc_html_e( 'Custom Redirect URLs', 'miniorange-login-security' ); ?></p>
			<p style="margin-left:4%">
			<?php
			esc_html_e(
				'Enable Custom Relay state URL\'s (based on user roles in WordPress) to which the users
                will get redirected to, after the 2-factor authentication',
				'miniorange-login-security'
			);
			?>
										'.</p>


			<br>
			<p style="font-size:15px;font-weight:bold">3. <?php esc_html_e( 'Custom Security Questions (KBA)', 'miniorange-login-security' ); ?></p>
			<div id="mo2f_customKBAQuestions1">
				<p style="margin-left:4%">
				<?php
				esc_html_e(
					'Add up to 16 Custom Security Questions for Knowledge based authentication (KBA).
                    You also have the option to select how many standard and custom questions should be shown to the
                    users',
					'miniorange-login-security'
				);
				?>
											.</p>

			</div>
			<br>
			<p style="font-size:15px;font-weight:bold">
				4. <?php esc_html_e( 'Custom account name in Google Authenticator App', 'miniorange-login-security' ); ?></p>
			<div id="mo2f_editGoogleAuthenticatorAccountName1">

				<p style="margin-left:4%"><?php esc_html_e( 'Customize the Account name in the Google Authenticator App', 'miniorange-login-security' ); ?>
					.</p>

			</div>
			<br>
		</div>
		<div id="standard_premium_options" style="text-align: center;">
			<p style="font-size:22px;color:darkorange;padding:10px;"><?php esc_html_e( 'Features in the Premium Plan', 'miniorange-login-security' ); ?></p>

		</div>
		<hr>
		<div>
			<a class="mo2f_view_customizations_prem" onclick="show_customizations_prem()">	

				<p class="mo2f_heading_style"><?php esc_html_e( 'Customizations', 'miniorange-login-security' ); ?></p>
			</a>
		</div>


		<div id="customizations_prem" style="margin-left: 5%;">

			<p style="font-size:15px;font-weight:bold">1. <?php esc_html_e( 'Login Screen Options', 'miniorange-login-security' ); ?></p>
			<div>
				<ul style="margin-left:4%" class="mo2f_ol">
					<li><?php esc_html_e( 'Login with WordPress username/password and 2nd Factor', 'miniorange-login-security' ); ?> <a
								data-toggle="collapse" id="showpreview1" href="#preview3"
								aria-expanded="false">[ <?php esc_html_e( 'See Preview', 'miniorange-login-security' ); ?>
							]</a>
						<div class="mo2f_collapse" id="preview3" style="height:300px;">
							<div class="mo2f_align_center"><br>
								<img style="height:300px;"
									src="https://login.xecurify.com/moas/images/help/login-help-1.png">
							</div>

						</div>
						<br></li>
					<li><?php esc_html_e( 'Login with WordPress username and 2nd Factor only', 'miniorange-login-security' ); ?> <a
								data-toggle="collapse" id="showpreview2" href="#preview4"
								aria-expanded="false">[ <?php esc_html_e( 'See Preview', 'miniorange-login-security' ); ?>
							]</a>
						<br>
						<div class="mo2f_collapse" id="preview4" style="height:300px;">
							<div class="mo2f_align_center"><br>
								<img style="height:300px;"
									src="https://login.xecurify.com/moas/images/help/login-help-3.png">
							</div>
						</div>
						<br>
					</li>
				</ul>


			</div>
			<br>
			<p style="font-size:15px;font-weight:bold">2. <?php esc_html_e( 'Custom Redirect URLs', 'miniorange-login-security' ); ?></p>
			<p style="margin-left:4%">
			<?php
			esc_html_e(
				'Enable Custom Relay state URL\'s (based on user roles in WordPress) to which the users
                will get redirected to, after the 2-factor authentication',
				'miniorange-login-security'
			);
			?>
										'.</p>


			<br>
			<p style="font-size:15px;font-weight:bold">3. <?php esc_html_e( 'Custom Security Questions (KBA)', 'miniorange-login-security' ); ?></p>
			<div id="mo2f_customKBAQuestions1">
				<p style="margin-left:4%">
				<?php
				esc_html_e(
					'Add up to 16 Custom Security Questions for Knowledge based authentication (KBA).
                    You also have the option to select how many standard and custom questions should be shown to the
                    users',
					'miniorange-login-security'
				);
				?>
											.</p>

			</div>
			<br>
			<p style="font-size:15px;font-weight:bold">
				4. <?php esc_html_e( 'Custom account name in Google Authenticator App', 'miniorange-login-security' ); ?></p>
			<div id="mo2f_editGoogleAuthenticatorAccountName1">

				<p style="margin-left:4%"><?php esc_html_e( 'Customize the Account name in the Google Authenticator App', 'miniorange-login-security' ); ?>
					.</p>

			</div>
			<br>
		</div>
		<div>
			<a class="mo2f_view_backup_options_prem" onclick="show_backup_options_prem()">

				<p class="mo2f_heading_style"><?php esc_html_e( 'Backup Options', 'miniorange-login-security' ); ?></p>
			</a>

		</div>
		<div id="backup_options_prem" style="margin-left: 5%;">

			<div class="mo2f_advanced_options_note"><p style="padding:5px;">
					<i>
					<?php
					esc_html_e(
						'Use these backup options to login to your site in case your 
                                phone is lost / not accessible or if you are not able to login using your primary 
                                authentication method.',
						'miniorange-login-security'
					);
					?>
						</i></p></div>

			<ol class="mo2f_ol">
				<li><?php esc_html_e( 'KBA (Security Questions)', 'miniorange-login-security' ); ?></li>
				<li><?php esc_html_e( 'OTP Over Email', 'miniorange-login-security' ); ?></li>
				<li><?php esc_html_e( 'Backup Codes', 'miniorange-login-security' ); ?></li>
			</ol>

		</div>


		<div>
			<a class="mo2f_view_inline_registration_options" onclick="show_inline_registration_options()">
				<p class="mo2f_heading_style"><?php esc_html_e( 'Inline Registration Options', 'miniorange-login-security' ); ?></p>
			</a>
		</div>


		<div id="inline_registration_options" style="margin-left: 5%;">

			<div class="mo2f_advanced_options_note"><p style="padding:5px;">
					<i>
					<?php
					esc_html_e(
						'Inline Registration is the registration process the users go through the first time they
                                setup 2FA.',
						'miniorange-login-security'
					);
					?>
						<br>
						<?php
						esc_html_e(
							'If Inline Registration is enabled by the admin for the users, the next time
                                the users login to the website, they will be prompted to set up the 2FA of their choice by
                                creating an account with miniOrange.',
							'miniorange-login-security'
						);
						?>


					</i></p></div>


			<p style="font-size:15px;font-weight:bold"><?php esc_html_e( 'Features', 'miniorange-login-security' ); ?>:</p>
			<ol style="margin-left: 5%" class="mo2f_ol">
				<li><?php esc_html_e( 'Invoke 2FA Registration & Setup for Users during first-time login (Inline Registration)', 'miniorange-login-security' ); ?>
				</li>

				<li><?php esc_html_e( 'Verify Email address of User during Inline Registration', 'miniorange-login-security' ); ?></li>
				<li><?php esc_html_e( 'Remove Knowledge Based Authentication(KBA) setup during inline registration', 'miniorange-login-security' ); ?></li>
				<li><?php esc_html_e( 'Enable 2FA for specific Roles', 'miniorange-login-security' ); ?></li>
				<li><?php esc_html_e( 'Enable specific 2FA methods to Users during Inline Registration', 'miniorange-login-security' ); ?>:
					<ul style="padding-top:10px;">
						<li style="margin-left: 5%;">
							1. <?php esc_html_e( 'Show specific 2FA methods to All Users', 'miniorange-login-security' ); ?></li>
						<li style="margin-left: 5%;">
							2. <?php esc_html_e( 'Show specific 2FA methods to Users based on their roles', 'miniorange-login-security' ); ?></li>
					</ul>
				</li>
			</ol>
		</div>


		<div>
			<a class="mo2f_view_login_options" onclick="show_login_options()">                
				<p class="mo2f_heading_style"><?php esc_html_e( 'User Login Options', 'miniorange-login-security' ); ?></p>
			</a>
		</div>

		<div id="login_options" style="margin-left: 5%;">

			<div class="mo2f_advanced_options_note"><p style="padding:5px;">
					<i><?php esc_html_e( 'These are the options customizable for your users.', 'miniorange-login-security' ); ?>


					</i></p></div>

			<ol style="margin-left: 5%" class="mo2f_ol">
				<li><?php esc_html_e( 'Enable 2FA during login for specific users on your site', 'miniorange-login-security' ); ?>.</li>

				<li><?php esc_html_e( 'Enable login from external apps that support XML-RPC. (eg. WordPress App)', 'miniorange-login-security' ); ?>
					<br>
					<div class="mo2f_advanced_options_note"><p style="padding:5px;">
							<i>
							<?php
							esc_html_e(
								'Use the Password generated in the 2FA plugin to login to your WordPress Site from
                                        any application that supports XML-RPC.',
								'miniorange-login-security'
							);
							?>


							</i></p></div>


				<li>
				<?php
				esc_html_e(
					'Enable KBA (Security Questions) as 2FA for Users logging in to the site from mobile
                phones.',
					'miniorange-login-security'
				);
				?>
				</li>


			</ol>
			<br>
		</div>
	</div>
	<?php
}

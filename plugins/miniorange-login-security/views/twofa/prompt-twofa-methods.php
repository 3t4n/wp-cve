<?php
/**
 * Frontend for 2FA methods prompt.
 *
 * @package miniorange-login-security/views/twofa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * This function prompts OTP authentication
 *
 * @param string $login_status login status of user.
 * @param string $login_message message used to show success/failed login actions.
 * @param string $redirect_to redirect url.
 * @param string $session_id_encrypt encrypted session id.
 * @param string $user_id user id.
 * @return void
 */
function momls_get_otp_authentication_prompt( $login_status, $login_message, $redirect_to, $session_id_encrypt, $user_id ) {

	$mo2f_is_new_customer = get_site_option( 'mo2f_is_NC' );
	$attempts             = get_site_option( 'mo2f_attempts_before_redirect', 3 );
	?>
	<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
		momls_echo_js_css_files();
		?>
	</head>
	<body>
	<div class="mo2f_modal" tabindex="-1" role="dialog">
		<div class="mo2f-modal-backdrop"></div>
		<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
			<div class="login mo_customer_validation-modal-content">
				<div class="mo2f_modal-header">
					<h4 class="mo2f_modal-title">
						<button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close"
								title="<?php esc_attr_e( 'Back to login', 'miniorange-login-security' ); ?>"
								onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
						<?php esc_html_e( 'Validate OTP', 'miniorange-login-security' ); ?>
					</h4>
				</div>
				<div class="mo2f_modal-body center">
					<?php if ( isset( $login_message ) && ! empty( $login_message ) ) { ?>
						<div id="otpMessage">
							<p class="mo2fa_display_message_frontend">
							<?php
							echo wp_kses(
								$login_message,
								array(
									'b' => array(),
									'a' => array(
										'href'   => array(),
										'target' => array(),
									),
								)
							);
							?>
							</p>
						</div>
					<?php } ?><br>					<span><b>Attempts left</b>:</span> <?php echo esc_html( $attempts ); ?><br>
					<?php if ( 1 === $attempts ) { ?>
					<span style='color:red;'><b>If you fail to verify your identity, you will be redirected back to login page to verify your credentials.</b></span> <br>
					<?php } ?>
					<br>
					<div id="showOTP">
						<div class="mo2f-login-container">
							<form name="f" id="mo2f_submitotp_loginform" method="post">
							<div class="mo2f_align_center">
									<input type="text" name="mo2fa_softtoken" style="height:28px !important;"
										placeholder="<?php esc_attr_e( 'Enter code', 'miniorange-login-security' ); ?>"
										id="mo2fa_softtoken" required="true" class="mo_otp_token" autofocus="true"
										pattern="[0-9]{4,8}"
										title="<?php esc_attr_e( 'Only digits within range 4-8 are allowed.', 'miniorange-login-security' ); ?>"/>
					</div>
								<br>
								<input type="submit" name="miniorange_otp_token_submit" id="miniorange_otp_token_submit"
									class="miniorange_otp_token_submit"
									value="<?php esc_attr_e( 'Validate', 'miniorange-login-security' ); ?>"/>
								<input type="hidden" name="request_origin_method" value="<?php echo esc_attr( $login_status ); ?>"/>
								<input type="hidden" name="miniorange_soft_token_nonce"
									value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-soft-token-nonce' ) ); ?>"/>
								<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>"/>
								<input type="hidden" name="session_id" value="<?php echo esc_attr( $session_id_encrypt ); ?>"/>
							</form>
							<?php
							$kbaset = get_user_meta( $user_id, 'Security Questions' );
							if ( ! $mo2f_is_new_customer ) {
								?>
							<?php } ?>
							<div style="padding:10px;">
								<p><a href="https://faq.miniorange.com/knowledgebase/i-am-locked-cant-access-my-account-what-do-i-do/" target="_blank" style="color:#ca2963;font-weight:bold;">I'm locked out & unable to login.</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo esc_url( wp_login_url() ); ?>"
		class="mo2f_display_none_forms">
		<input type="hidden" name="miniorange_mobile_validation_failed_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-mobile-validation-failed-nonce' ) ); ?>"/>
		<input type="hidden" name="session_id" value="<?php echo esc_attr( $session_id_encrypt ); ?>"/>
	</form>
	<script>
		function mologinback() {
			jQuery('#mo2f_backto_mo_loginform').submit();
		}

	</script>
	</body>
	</html>
	<?php
}
/**
 * This function user prompts KBA authentication prompt
 *
 * @param string $login_message message used to show success/failed login actions.
 * @param string $redirect_to redirect url.
 * @param string $session_id_encrypt encrypted session id.
 * @param array  $cookievalue conatins kba questions.
 * @return void
 */
function momls_get_kba_authentication_prompt( $login_message, $redirect_to, $session_id_encrypt, $cookievalue ) {

	$mo2f_login_policy            = get_site_option( 'mo2f_login_policy' );
	$mo2f_remember_device_enabled = get_site_option( 'mo2f_remember_device' );
	?>
	<html>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
		momls_echo_js_css_files();
		?>
	</head>
	<body>
	<div class="mo2f_modal" tabindex="-1" role="dialog">
		<div class="mo2f-modal-backdrop"></div>
		<div class="mo_customer_validation-modal-dialog mo_customer_validation-modal-md">
			<div class="login mo_customer_validation-modal-content">
				<div class="mo2f_modal-header">
					<h4 class="mo2f_modal-title">
						<button type="button" class="mo2f_close" data-dismiss="modal" aria-label="Close"
								title="<?php esc_attr_e( 'Back to login', 'miniorange-login-security' ); ?>"
								onclick="mologinback();"><span aria-hidden="true">&times;</span></button>
						<?php
						esc_html_e( 'Validate Security Questions', 'miniorange-login-security' );
						?>
					</h4>
				</div>
				<div class="mo2f_modal-body">
					<div id="kbaSection" class="kbaSectiondiv">
						<div id="otpMessage">
							<p style="font-size:13px;"
							class="mo2fa_display_message_frontend">
							<?php
								$login_message = isset( $login_message ) && ! empty( $login_message ) ? $login_message : __( 'Please answer the following questions:', 'miniorange-login-security' );
								echo wp_kses( $login_message, array( 'b' => array() ) );
							?>
							</p>
						</div>
						<form name="f" id="mo2f_submitkba_loginform" method="post">
							<div id="mo2f_kba_content">
								<p style="font-size:15px;">
									<?php
									$kba_questions = $cookievalue;
									echo esc_html( $kba_questions[0] );
									?>
									<br>
									<input class="mo2f-textbox" type="password" name="mo2f_answer_1" id="mo2f_answer_1"
										required="true" autofocus="true"
										pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
										title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
										autocomplete="off"><br>
									<?php echo esc_html( $kba_questions[1] ); ?><br>
									<input class="mo2f-textbox" type="password" name="mo2f_answer_2" id="mo2f_answer_2"
										required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
										title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
										autocomplete="off">
										<input type="hidden" name="mo2f_validate_kba_details_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-validate-kba-details-nonce' ) ); ?>"/>
								</p>
							</div>
							<input type="submit" name="miniorange_kba_validate" id="miniorange_kba_validate"
								class="miniorange_kba_validate" style="float:left;"
								value="<?php esc_attr_e( 'Validate', 'miniorange-login-security' ); ?>"/>
							<input type="hidden" name="miniorange_kba_nonce"
								value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-kba-nonce' ) ); ?>"/>
							<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>"/>
							<input type="hidden" name="session_id" value="<?php echo esc_attr( $session_id_encrypt ); ?>"/>
						</form>
						<br>
					</div>
					<div style="padding:10px;">
						<p><a href="https://faq.miniorange.com/knowledgebase/i-am-locked-cant-access-my-account-what-do-i-do/" target="_blank" style="color:#ca2963;font-weight:bold;">I'm locked out & unable to login.</a></p>
					</div>


				</div>
			</div>
		</div>
	</div>
	<form name="f" id="mo2f_backto_mo_loginform" method="post" action="<?php echo esc_url( wp_login_url() ); ?>"
		class="mo2f_display_none_forms">
		<input type="hidden" name="miniorange_mobile_validation_failed_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'miniorange-2-factor-mobile-validation-failed-nonce' ) ); ?>"/>
		<input type="hidden" name="session_id" value="<?php echo esc_attr( $session_id_encrypt ); ?>"/>
	</form>

	<script>
		function mologinback() {
			jQuery('#mo2f_backto_mo_loginform').submit();
		}
	</script>
	</body>

	</html>
	<?php
}
?>

<?php
/**
 * This file shows Google/Authy authenticator frontend.
 *
 * @package miniorange-login-security/views/twofa/test
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Function to show Google/Authy authenticator frontend.
 *
 * @param object $user User object.
 * @param string $method 2-factor method of user.
 * @return void
 */
function momls_test_google_authy_authenticator( $user, $method ) {

	?>
		<h3>			
		<?php
			printf(
				/* translators: %s: Name of the 2fa method */
				esc_html__( 'Test %s', 'miniorange-login-security' ),
				esc_html( $method )
			);
		?>
			</h3>
		<hr>
	<p>
	<?php
	printf(
		/* translators: %s: Name of the 2fa method */
		esc_html__( 'Enter the verification code from the configured account in your %s app.', 'miniorange-login-security' ),
		esc_html( $method )
	);
	?>
				</p>

	<form name="f" method="post" action="">
		<input type="hidden" name="option" value="momls_validate_google_authy_test"/>
		<input type="hidden" name="momls_validate_google_authy_test_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-validate-google-authy-test-nonce' ) ); ?>"/>

		<input class="mo2f_table_textbox" style="width:200px;" autofocus="true" type="text" name="otp_token" required
			placeholder="<?php esc_attr_e( 'Enter OTP', 'miniorange-login-security' ); ?>" style="width:95%;"/>
		<br><br>
			<input type="button" name="back" id="go_back" class="momls_wpns_button momls_wpns_button1"
				value="<?php esc_attr_e( 'Back', 'miniorange-login-security' ); ?>"/>
		<input type="submit" name="validate" id="validate" class="momls_wpns_button momls_wpns_button1"
			value="<?php esc_attr_e( 'Submit', 'miniorange-login-security' ); ?>"/>

	</form>
	<form name="f" method="post" action="" id="mo2f_go_back_form">
		<input type="hidden" name="option" value="mo2f_go_back"/>
		<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-go-back-nonce' ) ); ?>"/>
	</form>
	<script>
		jQuery('#go_back').click(function () {
			jQuery('#mo2f_go_back_form').submit();
		});
	</script>

	<?php
} ?>

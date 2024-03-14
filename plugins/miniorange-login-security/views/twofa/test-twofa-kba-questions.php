<?php
/**
 * This file shows Test Security Questions frontend.
 *
 * @package miniorange-login-security/views/twofa/test
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * This function shows Test Security Questions frontend.
 *
 * @param object $user User object.
 * @return void
 */
function momls_test_kba_security_questions( $user ) {
	$onprem = get_site_option( 'is_onprem' );
	?>

	<?php
	if ( $onprem ) {
		?>
		<h3><?php esc_html_e( 'Test Security Questions( KBA )', 'miniorange-login-security' ); ?></h3>
		<hr>
<br>
	<form name="f" method="post" action="" id="mo2f_test_kba_form">
		<input type="hidden" name="option" value="mo2f_validate_kba_details"/>
	<input type="hidden" name="mo2f_validate_kba_details_nonce"
			value="<?php echo esc_attr( wp_create_nonce( 'mo2f-validate-kba-details-nonce' ) ); ?>"/>	
		<div id="mo2f_kba_content">
			<?php
			$ques = ( get_user_meta( $user->ID, 'kba_questions_user' ) );
			$ques = $ques[0];
			echo esc_html( $ques[0] );
			?>
				<br>
				<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_1"
					id="mo2f_answer_1" required="true" autofocus="true"
					pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
					title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
					autocomplete="off"><br><br>
			<?php
			echo esc_html( $ques[1] );
			?>
				<br>
				<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_2"
					id="mo2f_answer_2" required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
					title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
					autocomplete="off"><br><br>
		</div>
			<input type="button" name="back" id="go_back" class="momls_wpns_button momls_wpns_button1" value="<?php esc_attr_e( 'Back', 'miniorange-login-security' ); ?>" />
		<input type="submit" name="validate" id="validate" class="momls_wpns_button momls_wpns_button1"
			value="<?php esc_attr_e( 'Validate Answers', 'miniorange-login-security' ); ?>"/>

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
	} else {
		?>
		<h3><?php esc_html_e( 'Test Security Questions( KBA )', 'miniorange-login-security' ); ?></h3>
		<hr>
	<br>
	<form name="f" method="post" action="" id="mo2f_test_kba_form">
		<input type="hidden" name="option" value="mo2f_validate_kba_details"/>
		<input type="hidden" name="mo2f_validate_kba_details_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-validate-kba-details-nonce' ) ); ?>"/>				
		<div id="mo2f_kba_content">
			<?php
			if ( isset( $_SESSION['mo_2_factor_kba_questions'] ) ) {
				echo esc_html( $_SESSION['mo_2_factor_kba_questions'][0] );
				?>
				<br>
				<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_1"
					id="mo2f_answer_1" required="true" autofocus="true"
					pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
					title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
					autocomplete="off"><br><br>
				<?php
				echo esc_html( $_SESSION['mo_2_factor_kba_questions'][1] );
				?>
				<br>
				<input class="mo2f_table_textbox" style="width:227px;" type="text" name="mo2f_answer_2"
					id="mo2f_answer_2" required="true" pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}"
					title="Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed."
					autocomplete="off"><br><br>
				<?php
			}
			?>
		</div>
			<input type="button" name="back" id="go_back" class="momls_wpns_button momls_wpns_button1" value="<?php esc_attr_e( 'Back', 'miniorange-login-security' ); ?>" />
		<input type="submit" name="validate" id="validate" class="momls_wpns_button momls_wpns_button1"
			value="<?php esc_attr_e( 'Validate Answers', 'miniorange-login-security' ); ?>"/>

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
	}
}

?>

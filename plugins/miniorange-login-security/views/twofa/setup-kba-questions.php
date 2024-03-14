<?php
/**
 * This file show frontend to configure Security Questions.
 *
 * @package miniorange-login-security/views/twofa/setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Function to show setup wizard for configuring KBA.
 *
 * @return void
 */
function momls_configure_kba_questions() { ?>

	<div class="mo2f_kba_header"><?php esc_html_e( 'Please choose 3 questions', 'miniorange-login-security' ); ?></div>
	<br>
	<table cellspacing="10">
		<tr class="mo2f_kba_header">
			<td>
				<?php esc_html_e( 'Sr. No.', 'miniorange-login-security' ); ?>
			</td>
			<td class="mo2f_kba_tb_data">
				<?php esc_html_e( 'Questions', 'miniorange-login-security' ); ?>
			</td>
			<td>
				<?php esc_html_e( 'Answers', 'miniorange-login-security' ); ?>
			</td>
		</tr>
		<tr class="mo2f_kba_body">
			<td class="mo2f_align_center">
				1.
			</td>
			<td class="mo2f_kba_tb_data">
				<select name="mo2f_kbaquestion_1" id="mo2f_kbaquestion_1" class="mo2f_kba_ques" required="true"
						onchange="mo_option_hide(1)">
					<option value="" selected="selected">
						-------------------------<?php esc_html_e( 'Select your question' ); ?>
						-------------------------
					</option>
					<option id="mq1_1"
							value="What is your first company name?"><?php esc_html_e( 'What is your first company name?', 'miniorange-login-security' ); ?></option>
					<option id="mq2_1"
							value="What was your childhood nickname?"><?php esc_html_e( 'What was your childhood nickname?', 'miniorange-login-security' ); ?></option>
					<option id="mq3_1"
							value="In what city did you meet your spouse/significant other?"><?php esc_html_e( 'In what city did you meet your spouse/significant other?', 'miniorange-login-security' ); ?></option>
					<option id="mq4_1"
							value="What is the name of your favorite childhood friend?"><?php esc_html_e( 'What is the name of your favorite childhood friend?', 'miniorange-login-security' ); ?></option>
					<option id="mq5_1"
							value="What school did you attend for sixth grade?"><?php esc_html_e( 'What school did you attend for sixth grade?', 'miniorange-login-security' ); ?></option>
					<option id="mq6_1"
							value="In what city or town was your first job?"><?php esc_html_e( 'In what city or town was your first job?', 'miniorange-login-security' ); ?></option>
					<option id="mq7_1"
							value="What is your favourite sport?"><?php esc_html_e( 'What is your favourite sport?', 'miniorange-login-security' ); ?></option>
					<option id="mq8_1"
							value="Who is your favourite sports player?"><?php esc_html_e( 'Who is your favourite sports player?', 'miniorange-login-security' ); ?></option>
					<option id="mq9_1"
							value="What is your grandmother's maiden name?"><?php esc_html_e( "What is your grandmother's maiden name?", 'miniorange-login-security' ); ?></option>
					<option id="mq10_1"
							value="What was your first vehicle's registration number?"><?php esc_html_e( "What was your first vehicle's registration number?", 'miniorange-login-security' ); ?></option>
				</select>
			</td>
			<td>
				<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans1" id="mo2f_kba_ans1"
					title="<?php esc_attr_e( 'Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.', 'miniorange-login-security' ); ?>"
					pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}" required="true" autofocus="true"
					placeholder="<?php esc_attr_e( 'Enter your answer', 'miniorange-login-security' ); ?>"/>
			</td>
		</tr>
		<tr class="mo2f_kba_body">
			<td class="mo2f_align_center">
				2.
			</td>
			<td class="mo2f_kba_tb_data">
				<select name="mo2f_kbaquestion_2" id="mo2f_kbaquestion_2" class="mo2f_kba_ques" required="true"
						onchange="mo_option_hide(2)">
					<option value="" selected="selected">
						-------------------------<?php esc_html_e( 'Select your question' ); ?>
						-------------------------
					</option>
					<option id="mq1_2"
							value="What is your first company name?"><?php esc_html_e( 'What is your first company name?', 'miniorange-login-security' ); ?></option>
					<option id="mq2_2"
							value="What was your childhood nickname?"><?php esc_html_e( 'What was your childhood nickname?', 'miniorange-login-security' ); ?></option>
					<option id="mq3_2"
							value="In what city did you meet your spouse/significant other?"><?php esc_html_e( 'In what city did you meet your spouse/significant other?', 'miniorange-login-security' ); ?></option>
					<option id="mq4_2"
							value="What is the name of your favorite childhood friend?"><?php esc_html_e( 'What is the name of your favorite childhood friend?', 'miniorange-login-security' ); ?></option>
					<option id="mq5_2"
							value="What school did you attend for sixth grade?"><?php esc_html_e( 'What school did you attend for sixth grade?', 'miniorange-login-security' ); ?></option>
					<option id="mq6_2"
							value="In what city or town was your first job?"><?php esc_html_e( 'In what city or town was your first job?', 'miniorange-login-security' ); ?></option>
					<option id="mq7_2"
							value="What is your favourite sport?"><?php esc_html_e( 'What is your favourite sport?', 'miniorange-login-security' ); ?></option>
					<option id="mq8_2"
							value="Who is your favourite sports player?"><?php esc_html_e( 'Who is your favourite sports player?', 'miniorange-login-security' ); ?></option>
					<option id="mq9_2"
							value="What is your grandmother's maiden name?"><?php esc_html_e( 'What is your grandmother\'s maiden name?', 'miniorange-login-security' ); ?></option>
					<option id="mq10_2"
							value="What was your first vehicle's registration number?"><?php esc_html_e( 'What was your first vehicle\'s registration number?', 'miniorange-login-security' ); ?></option>
				</select>
			</td>
			<td>
				<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans2" id="mo2f_kba_ans2"
					title="<?php esc_attr_e( 'Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.', 'miniorange-login-security' ); ?>"
					pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}" required="true"
					placeholder="<?php esc_attr_e( 'Enter your answer', 'miniorange-login-security' ); ?>"/>
			</td>
		</tr>
		<tr class="mo2f_kba_body">
			<td class="mo2f_align_center">
				3.
			</td>
			<td class="mo2f_kba_tb_data">
				<input class="mo2f_kba_ques" type="text" style="width: 100%;"name="mo2f_kbaquestion_3" id="mo2f_kbaquestion_3"
					required="true"
					placeholder="<?php esc_attr_e( 'Enter your custom question here', 'miniorange-login-security' ); ?>"/>
			</td>
			<td>
				<input class="mo2f_table_textbox" type="text" name="mo2f_kba_ans3" id="mo2f_kba_ans3"
					title="<?php esc_attr_e( 'Only alphanumeric letters with special characters(_@.$#&amp;+-) are allowed.', 'miniorange-login-security' ); ?>"
					pattern="(?=\S)[A-Za-z0-9_@.$#&amp;+\-\s]{1,100}" required="true"
					placeholder="<?php esc_attr_e( 'Enter your answer', 'miniorange-login-security' ); ?>"/>
			</td>
		</tr>
	</table>

	<script>
		//hidden element in dropdown list 1
		var mo_option_to_hide1;
		//hidden element in dropdown list 2
		var mo_option_to_hide2;

		function mo_option_hide(list) {
			//grab the team selected by the user in the dropdown list
			var list_selected = document.getElementById("mo2f_kbaquestion_" + list).selectedIndex;
			//if an element is currently hidden, unhide it
			if (typeof (mo_option_to_hide1) !== "undefined" && mo_option_to_hide1 !== null && list === 2) {
				mo_option_to_hide1.style.display = 'block';
			} else if (typeof (mo_option_to_hide2) !== "undefined" && mo_option_to_hide2 !== null && list === 1) {
				mo_option_to_hide2.style.display = 'block';
			}
			//select the element to hide and then hide it
			if (list === 1) {
				if (list_selected !== 0) {
					mo_option_to_hide2 = document.getElementById("mq" + list_selected + "_2");
					mo_option_to_hide2.style.display = 'none';
				}
			}
			if (list === 2) {
				if (list_selected !== 0) {
					mo_option_to_hide1 = document.getElementById("mq" + list_selected + "_1");
					mo_option_to_hide1.style.display = 'none';
				}
			}
		}
	</script>
	<?php
	if ( isset( $_SESSION['mo2f_mobile_support'] ) && 'MO2F_EMAIL_BACKUP_KBA' === $_SESSION['mo2f_mobile_support'] ) {
		?>
		<input type="hidden" name="mobile_kba_option" value="mo2f_request_for_kba_as_emailbackup"/>
		<?php
	}
}
/**
 * Function to show setup for configuring KBA for mobile support.
 *
 * @param object $user User object.
 * @return void
 */
function momls_configure_for_mobile_suppport_kba( $user ) {

	?>
		<h3><?php esc_html_e( 'Configure Second Factor - KBA (Security Questions)', 'miniorange-login-security' ); ?></h3>
		<hr/>
	<form name="f" method="post" action="" id="mo2f_kba_setup_form">
		<?php momls_configure_kba_questions(); ?>
		<br>
		<input type="hidden" name="option" value="mo2f_save_kba"/>
	<input type="hidden" name="mo2f_save_kba_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-save-kba-nonce' ) ); ?>"/>
	<div class="mo2f_align_center">
		<table>
			<tr>
				<td>
					<input type="submit" id="mo2f_kba_submit_btn" name="submit"
						value="<?php esc_attr_e( 'Save', 'miniorange-login-security' ); ?>"
						class="momls_wpns_button momls_wpns_button1" style="width:100px;line-height:30px;"/>
				</td>
	</form>

	<td>

		<form name="f" method="post" action="" id="mo2f_go_back_form">
			<input type="hidden" name="option" value="mo2f_go_back"/>
			<input type="hidden" name="mo2f_go_back_nonce"
						value="<?php echo esc_attr( wp_create_nonce( 'mo2f-go-back-nonce' ) ); ?>"/>
				<input type="submit" name="back" id="go_back" class="momls_wpns_button momls_wpns_button1"
					value="<?php esc_attr_e( 'Back', 'miniorange-login-security' ); ?>"
					style="width:100px;line-height:30px;"/>

		</form>

	</td>
	</tr>
	</table>
</div>
	<script>

		jQuery('#mo2f_kba_submit_btn').click(function () {
			jQuery('#mo2f_kba_setup_form').submit();
		});
	</script>
	<?php
}

?>

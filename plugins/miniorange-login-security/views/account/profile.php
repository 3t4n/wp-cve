<?php
/**
 * This file contains the html UI for the miniOrange account details.
 *
 * @package miniorange-login-security/views/account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$back_button = admin_url() . 'admin.php?page=mo_2fa_two_fa';
echo '
<br><br><br><br>
    <div class="momls_wpns_divided_layout">
        <div class="mo2f_table_layout" >
            <div>
                <h4>Thank You for registering with miniOrange.</h4>
                <h3>Your Profile</h3>
                <table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
                    <tr>
                        <td style="width:45%; padding: 10px;">Username/Email</td>
                        <td style="width:55%; padding: 10px;">' . esc_html( $email ) . '</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">Customer ID</td>
                        <td style="width:55%; padding: 10px;">' . esc_html( $key ) . '</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">API Key</td>
                        <td style="width:55%; padding: 10px;">' . esc_html( $api ) . '</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">Token Key</td>
                        <td style="width:55%; padding: 10px;">' . esc_html( $token ) . '</td>
                    </tr>
                </table>
                <br/>
                <div class="mo2f_align_center">';
if ( isset( $back_button ) ) {

		echo '<a class="button button-primary " href="' . esc_attr( $back_button ) . '">Back</a> ';
}
				echo '
                <a id="mo_mfa_log_out" class="button button-primary" >Remove Account and Reset Settings</a>
                </div>
                <p><a href="#momls_wpns_forgot_password_link">Click here</a> if you forgot your password to your miniOrange account.</p>
            </div>
        </div>
    </div>
	<form id="forgot_password_form" method="post" action="">
		<input type="hidden" name="option" value="momls_wpns_reset_password" />
        <input type="hidden" name="mo2f_general_nonce" value=" ' . esc_attr( wp_create_nonce( 'miniOrange_2fa_nonce' ) ) . ' " />
	</form>
    <form id="mo_mfa_remove_account" method="post" action="">
        <input type="hidden" name="mo_mfa_remove_account" value="momls_wpns_reset_account" />
    </form>
	
	<script>
		jQuery(document).ready(function(){
			$(\'a[href="#momls_wpns_forgot_password_link"]\').click(function(){
				$("#forgot_password_form").submit();
			});
           jQuery("#mo_mfa_log_out").click(function(){
            jQuery("#mo_mfa_remove_account").submit();

           });
		});
	</script>';


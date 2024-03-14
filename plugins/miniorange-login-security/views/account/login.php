<?php
/**
 * This file contains the html UI for the miniOrange account login page in the plugin.
 *
 * @package miniorange-login-security/views/account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '   <br><br><br><br>
<form name="f" method="post" action="">
            <input type="hidden" name="option" value="momls_wpns_verify_customer" />
            <input type="hidden" name="mo2f_general_nonce" value=" ' . esc_attr( wp_create_nonce( 'miniOrange_2fa_nonce' ) ) . ' " />
            <div class="momls_wpns_divided_layout">
                <div class="mo2f_table_layout">
                    <h3>Login with miniOrange</h3>';
if ( isset( $two_fa ) ) {
	echo '<a class="button button-primary button-large" href="' . esc_attr( $two_fa ) . '">Back</a> ';
}
					echo '</h3>
                    <p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email and password.</td><a target="_blank" href="https://login.xecurify.com/moas/idp/resetpassword"> Click here if you forgot your password?</a></b></p>
                    <table class="momls_wpns_settings_table">
                        <tr>
                            <td><b><span class="momls_font-color-astrisk">*</span>Email:</b></td>
                            <td><input class="momls_wpns_table_textbox" type="email" name="email"
                                required placeholder="person@example.com"
                                value="' . esc_attr( $admin_email ) . '" /></td>
                        </tr>
                        <tr>
                            <td><b><span class="momls_font-color-astrisk">*</span>Password:</b></td>
                            <td><input class="momls_wpns_table_textbox" required type="password"
                                name="password" placeholder="Enter your miniOrange password" /></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input type="submit" class="button button-primary button-large" />
                                <a href="#cancel_link" class="button button-primary button-large">Cancel</a>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
        <form id="cancel_form" method="post" action="">
            <input type="hidden" name="option" value="momls_wpns_cancel" />
            <input type="hidden" name="mo2f_general_nonce" value=" ' . esc_attr( wp_create_nonce( 'miniOrange_2fa_nonce' ) ) . ' " />
        </form>
        <script>
            jQuery(document).ready(function(){
                $(\'a[href="#cancel_link"]\').click(function(){
                    $("#cancel_form").submit();
                });     
            });
        </script>';

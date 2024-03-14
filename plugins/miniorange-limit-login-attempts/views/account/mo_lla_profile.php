<?php
$Back_button = admin_url().'admin.php?page=dashboard';
echo'
    <div class="mo_lla_divided_layout">
        <div class="mo_lla_setting_layout" >
            <div>
                <h4>Thank You for registering with miniOrange.</h4>
                <h3>Your Profile</h3>
                <table border="1" style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
                    <tr>
                        <td style="width:45%; padding: 10px;">Username/Email</td>
                        <td style="width:55%; padding: 10px;">'.esc_html($email).'</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">Customer ID</td>
                        <td style="width:55%; padding: 10px;">'.esc_html($key).'</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">API Key</td>
                        <td style="width:55%; padding: 10px;">'.esc_html($api).'</td>
                    </tr>
                    <tr>
                        <td style="width:45%; padding: 10px;">Token Key</td>
                        <td style="width:55%; padding: 10px;">'.esc_html($token).'</td>
                    </tr>
                </table>
                <br/>';
                if (isset( $Back_button )) {

                        echo '<a class="button button-primary " href="'.esc_url($Back_button).'">Back</a> ';
                    }
        echo ' <a  href="#mollm_log_out" id="mollm_log_out" class="button button-primary" >Remove Account and Reset Settings</a>
                </center>
                <p><a href="#mo_lla_forgot_password_link">Click here</a> if you forgot your password to your miniOrange account.</p>
            </div>
        </div>
    </div>
	<form id="forgot_password_form" method="post" action="">
		<input type="hidden" name="option" value="mo_lla_reset_password" />
         <input type="hidden" name="nonce" value='. esc_attr(wp_create_nonce("mollm-account-nonce")).' >
	</form>
    <form id="remove_password_form" method="post" action="">
        <input type="hidden" name="option" value="mo_lla_remove_password" />
         <input type="hidden" name="nonce" value='. esc_attr(wp_create_nonce("mollm_remove_account_nonce")).' >
    </form>
	
	<script>
	
        jQuery(document).ready(function(){
            jQuery(\'a[href="#mo_lla_forgot_password_link"]\').click(function(){
                jQuery("#forgot_password_form").submit();
            });
    
         jQuery(\'a[href="#mollm_log_out"]\').click(function(){
                jQuery("#remove_password_form").submit();
            });
        });
	</script>';
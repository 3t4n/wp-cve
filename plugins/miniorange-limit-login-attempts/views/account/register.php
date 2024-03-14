<?php	
	
echo'<!--Register with miniOrange-->
	<form name="f" method="post" action="">
		<input type="hidden" name="option" value="mo_lla_register_customer" />
		<div class="mo_lla_divided_layout">
			<div class="mo_lla_setting_layout">
				<h3>Register with miniOrange</h3>
				<p>Just complete the short registration below to configure Limit Login Attempts plugin. Please enter a valid email id that you have access to. You will be able to move forward after verifying an OTP that we will send to this email.</p>
				<table class="mo_lla_settings_table">
					<tr>
						<td><b><font color="#FF0000">*</font>Email:</b></td>
						<td><input class="mo_lla_table_textbox" type="email" name="email"
							required placeholder="person@example.com"
							value="'.esc_html($current_user->user_email).'" /></td>
					</tr>
					<tr>
						<td><b><font color="#FF0000">*</font>Password:</b></td>
						<td><input class="mo_lla_table_textbox" required type="password"
							name="password" placeholder="Choose your password (Min. length 6)" /></td>
					</tr>
					<tr>
						<td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
						<td><input class="mo_lla_table_textbox" required type="password"
							name="confirmPassword" placeholder="Confirm your password" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><br><input type="submit" name="submit" value="Register" style="width:100px;"
							class="button button-primary button-large mo_lla_button1" />
						<a class="button button-primary button-large" href="#Mo_wpns_account_exist">Existing User? Log In</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
	 <form name="f" method="post" action="" class="mo_lla_goto_verifycustomer">
        <input type="hidden" name="option" value="mo_lla_goto_verifycustomer">
         <input type="hidden" name="nonce" value='.esc_html(wp_create_nonce("molla_account_nonce")).' >
       </form>';
?>
<script>
        
        jQuery('a[href=\"#Mo_wpns_account_exist\"]').click(function (e) {
            jQuery('.mo_lla_goto_verifycustomer').submit();
        });  
</script>
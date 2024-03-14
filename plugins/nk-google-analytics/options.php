<?php

defined('ABSPATH') or die("No script kiddies please!");

function field_to_ignore() {

 	$options  = get_option('nkweb_ignore');

	$field_value   = isset( $options['ignore_admin_area'] ) ? $options['ignore_admin_area'] : '';


	global $wp_roles;

	foreach( $wp_roles->roles as $role => $role_info ) {
		$do_not_track['ignore_role_' . $role] = sprintf( __( 'Ignore %s when logged in', 'wp-google-analytics' ), rtrim( $role_info['name'], 's' ) );
	}
	foreach( $do_not_track as $id => $label ) {
		$field_value   = isset( $options[$id] ) ? $options[$id] : '';
		$checked='';
		if($field_value=="true"){$checked= "checked";}
		echo '<label for="nkweb_ignore_' . $id . '">';
		echo '<input id="nkweb_ignore_' . $id . '" type="checkbox" name="nkweb_ignore[' . $id . ']" value="true" '.$checked.'/>';
		echo '&nbsp;&nbsp;' . $label;
		echo '</label><br />';
	}
}


	$nkweb_Error = get_option('nkweb_Error');
	$error = "";
	if($nkweb_Error!="")
		$error = $nkweb_Error;

	if(get_option('nkweb_Enable_GA')!="true" && get_option('nkweb_Enable_GA')!="false"){
		update_option( "nkweb_Enable_GA", "true" );
	}
	if(!get_option('nkweb_id')){
		$error = "You must to set an Google Analytics ID.";
	}

	if(get_option('nkweb_Universal_Analytics')== "true"){

		if((get_option('nkweb_Domain')=="your-domain.com" || get_option('nkweb_Domain')=="") && get_option('nkweb_id') != "UA-0000000-0"){
			$error="When you use Universal Analytics you must set your domain.";

		}else{
			$userSet = get_option('nkweb_Domain');
			$http = "http";

			if(substr_count($userSet,"http")>0){
				if(substr_count($userSet,"https")>0){
					$http = "https";
				}
				$newDomain = str_replace("$http://", "", get_option('nkweb_Domain'));
				update_option( "nkweb_Domain", $newDomain );
				$error="Your domain was set to $newDomain";
			}

			if(substr_count($userSet,"www.")>0){
				$newDomain = str_replace("www.", "", get_option('nkweb_Domain'));
				update_option( "nkweb_Domain", $newDomain );
				$error="Your domain was set to $newDomain.";
			}
		}
	}
	if(get_option('nkweb_Use_Custom')== "true"){
		if(!get_option('nkweb_Custom_Code')){
			update_option( "nkweb_Use_Custom", "false" );
			$error="When you use Custom code you must set your script into 'Custom Google Analytics tracking code' field. Use custom Google Analytics tracking code was set to 'No'.";
		}
		if(substr_count(get_option('nkweb_Custom_Code'),"script")>0){
			$new_code = get_option('nkweb_Custom_Code');
			$new_code = str_replace('<script type="text/javascript">', "", $new_code);
			$new_code = str_replace("<script type='text/javascript'>", "", $new_code);
			$new_code = str_replace("<script>", "", $new_code);
			$new_code = str_replace("</script>", "", $new_code);
			update_option( "nkweb_Custom_Code", "$new_code" );
			$error="Labels < script > and < /script > was removed from your custom code.";
		}

		$pattern = '/^UA\-[0-9]{8}\-[0-9]{1}$/';

		if(preg_match($pattern, trim(get_option('nkweb_Custom_Code')))){
			$error="Seems that you wrote only your Google Analytics ID in custom code, you can write it in \"Google Analytics ID\" field and turn off custom tracking code.";
		}
	}

	if(!get_option('nkweb_code_in_head')){
		update_option( "nkweb_code_in_head", "true" );
	}
?>
<div class="nk-main">
	<div class="nk-main-container">

		<h2>NK Google Analytics settings</h2>

		<div style="">
			<form name="myform" class="myform" action="options.php" method="post" enctype="multipart/form-data">

				<div id="nk-tabs-container">
				    <ul class="nk-tabs-menu">
				        <li class="current"><a href="#basic"><?php _e( 'Basic', 'NKgoogleanalytics' );?></a></li>
				        <li><a href="#eu-options"><?php _e( 'EU Cookie Law', 'NKgoogleanalytics' );?></a></li>
				        <li><a href="#more-options"><?php _e( 'More options', 'NKgoogleanalytics' );?></a></li>
				    </ul>
				    <div class="tab">
				        <div id="basic" class="nk-tab-content">
				        	<label class="nk-label" for="nkweb_id">Google Analytics ID:</label>
				            <input type="text" name="nkweb_id" value="<?php echo get_option('nkweb_id'); ?>" />
				        </div>
				        <div id="eu-options" class="nk-tab-content">
				            <table class="form-table">
								<tr valign="top">
								<th scope="row">Cookieless tracking with fingerprint.js<br><small>(Only Universal analytics)</small></th>
								<td>
									<input type="radio" name="nkweb_fingerprintjs" value="true" <?php if (get_option('nkweb_fingerprintjs') == "true"){ echo "checked "; } ?>> Yes<br>
									<input type="radio" name="nkweb_fingerprintjs" value="false"<?php if (get_option('nkweb_fingerprintjs') == "false"){ echo "checked "; } ?>>  No<br>
								</td>
								</tr>
								<tr valign="top">
								<th scope="row">Anonymize ip<br><small>(Only Universal analytics)</small></th>
								<td>
									<input type="radio" name="nkweb_anonymizeip" value="true" <?php if (get_option('nkweb_anonymizeip') == "true"){ echo "checked "; } ?>> Yes<br>
									<input type="radio" name="nkweb_anonymizeip" value="false"<?php if (get_option('nkweb_anonymizeip') == "false"){ echo "checked "; } ?>>  No<br>
								</td>
								</tr>
							</table>
				        </div>
				        <div id="more-options" class="nk-tab-content">
				            <table class="form-table">
								<tr valign="top">
								<th scope="row">Google Analytics Type</th>
								<td>
									<input type="radio" name="nkweb_Universal_Analytics" value="true" <?php if (get_option('nkweb_Universal_Analytics') == "true"){ echo "checked "; } ?>> Universal Analytics<br>
									<input type="radio" name="nkweb_Universal_Analytics" value="false"<?php if (get_option('nkweb_Universal_Analytics') == "false"){ echo "checked "; } ?>>  Classic Analytics<br>
								</td>
								</tr>

								<tr valign="top">
								<th scope="row">Domain :<br><small>(Only Universal analytics)</small></th>
								<td><input type="text" name="nkweb_Domain" value="<?php echo get_option('nkweb_Domain'); ?>" /></td>
								</tr>

								<tr valign="top">
								<th scope="row">Enable Display Advertising :<br><small>(Remarketing)</small></th>
								<td>
									<input type="radio" name="nkweb_Display_Advertising" value="true" <?php if (get_option('nkweb_Display_Advertising') == "true"){ echo "checked "; } ?>> Yes<br>
									<input type="radio" name="nkweb_Display_Advertising" value="false"<?php if (get_option('nkweb_Display_Advertising') == "false"){ echo "checked "; } ?>>  No <br>
								</td>
								</tr>

								<tr valign="top">
								<th scope="row">Track login and register page</th>
								<td>
									<input type="radio" name="nkweb_track_login_and_register" value="true" <?php if (get_option('nkweb_track_login_and_register') == "true"){ echo "checked "; } ?>> Yes<br>
									<input type="radio" name="nkweb_track_login_and_register" value="false"<?php if (get_option('nkweb_track_login_and_register') == "false"){ echo "checked "; } ?>>  No<br>
								</td>
								</tr>

								<tr valign="top">
								<th scope="row">Ignore logged users by role</th>
								<td>
									<?php
										echo field_to_ignore();
									?>
								</td>
								</tr>

								<tr valign="top">
								<th scope="row">Use custom Google Analytics tracking code</th>
								<td>
									<input type="radio" name="nkweb_Use_Custom" value="true" <?php if (get_option('nkweb_Use_Custom') == "true"){ echo "checked "; } ?>> Yes<br>
									<input type="radio" name="nkweb_Use_Custom" value="false"<?php if (get_option('nkweb_Use_Custom') == "false"){ echo "checked "; } ?>>  No <br>
								</td>
								</tr>

								<tr valign="top">
								<th scope="row">Custom Google Analytics tracking code</small></th>
								<td><textarea name="nkweb_Custom_Code" ><?php echo get_option('nkweb_Custom_Code'); ?></textarea>
								</tr>

								<tr valign="top">
								<th scope="row">Tracking code location</th>
								<td>
									<input type="radio" name="nkweb_code_in_head" value="true" <?php if (get_option('nkweb_code_in_head') == "true"){ echo "checked "; } ?>> Head<br>
									<input type="radio" name="nkweb_code_in_head" value="false"<?php if (get_option('nkweb_code_in_head') == "false"){ echo "checked "; } ?>>  End of the page<br>
								</td>
								</tr>



								<tr valign="top">
								<th scope="row">NK Google Analytics Status</th>
								<td>
									<input type="radio" name="nkweb_Enable_GA" value="true" <?php if (get_option('nkweb_Enable_GA') == "true"){ echo "checked "; } ?>> On<br>
									<input type="radio" name="nkweb_Enable_GA" value="false"<?php if (get_option('nkweb_Enable_GA') == "false"){ echo "checked "; } ?>>  Off <br>
								</td>
								</tr>


							</table>

				        </div>
				    </div>
				</div>
				<?php
					wp_nonce_field('update-options');
					settings_fields('NKgoogleanalytics');
				?>
				<p class="nk-submit">
					<input type="submit" class="button-primary" value="Save changes">
				</p>


			</form>
		</div>
	</div>
	<div class="nk-sidebar">

		<?php
			if($error != ""){
		?>
			<div id="setting-error-settings_updated" class="error settings-error">
				<p><strong><?php echo $error; ?></strong></p>
			</div>

		<?php
			}
		?>

		<p><?php _e( 'If do not know how to setup the plugin, just add Google Analytics ID and press "Save Changes", the default settings works in the most cases.', 'NKgoogleanalytics' );?></p>
		<p><?php _e( 'Remember, if you do not have an Google Analytics ID, you need to go to <a href="http://www.google.com/analytics">Google Analytics</a>, create an account and get the code (Similar to UA-0000000-0)', 'NKgoogleanalytics' );?></p>
		<p><?php _e( 'I am very glad that you like this plugin, i will appreciate a lot if you want to make a donation. Thank you.', 'NKgoogleanalytics' );?></p>

		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="CUC2VE9F3LADU">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
		</form>

		<a target="_blank" href="http://www.wordpress.org/support/view/plugin-reviews/nk-google-analytics#postform">Thank you for review this plugin, with your help I can improve it</a>
		<br>
		<br>
		<br>
		<a target="_blank" href="http://www.marodok.com/link-manager.php?to=sumome">Feel free to test these tools to grow your website traffic for free</a>
	</div>
</div>
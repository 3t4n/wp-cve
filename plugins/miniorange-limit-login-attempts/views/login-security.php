<?php
global $mollaUtility,$mo_lla_dirName;

$setup_dirName = $mo_lla_dirName.'views'.DIRECTORY_SEPARATOR.'link_tracer.php';
 include $setup_dirName;
add_action( 'admin_footer', 'login_security_ajax' );
?>
		<div class="molla-sub-tab-header">
			<div id="molla-bfp" class="molla-sub-tab molla-sub-tab-active" onclick="molla_switch_tabs(this)">BRUTE FORCE PROTECTION</div>
			<div id="molla-rlu" class="molla-sub-tab" onclick="molla_switch_tabs(this)">RENAME LOGIN URL</div>
			<div id="molla-grec" class="molla-sub-tab" onclick="molla_switch_tabs(this)">GOOGLE RECAPTCHA</div>
		</div>

<?php
echo '
		<div id="lla_message" style=" padding-top:8px"></div>
		<div>
		<div id="molla-bfp-div" class="mo-lla-sub-tabs mo-lla-sub-tabs-active">';
echo ' <h3>Brute Force Protection ( Login Protection )<a href='.esc_html($two_factor_premium_doc['Brute Force Protection']).' target="_blank"><span class="dashicons dashicons-external"></span></a> </h3>
	<div class="mo_lla_subheading">This protects your site from attacks which tries to gain access / login to a site with random usernames and passwords.</div>
	<div class="molla-checkbox"><input id="mo_bf_button" type="checkbox" name="enable_brute_force_protection" '.esc_html($brute_force_enabled).'>Enable Brute force protection</input></div>';		
echo'	<form class="molla-config-container" id="mo_lla_enable_brute_force_form" method="post" action="">
		<input type="hidden" name="option" value="mo_lla_brute_force_configuration">
		<table class=" molla-table">
		<tr><td style="width:40%">Allowed login attempts before blocking an IP  : </td>
		<td><input class="mo-lla-textbox" type="number" id="allwed_login_attempts" name="allwed_login_attempts" required placeholder="Enter no of login attempts" value="'.esc_html($allwed_login_attempts).'" /></td></tr>
		<tr><td>Time period for which IP should be blocked  : </td><td>
		<select class="mo-lla-select" id="time_of_blocking_type" name="time_of_blocking_type" >
							<option value="permanent" '.($time_of_blocking_type=="permanent" ? "selected" : "").'>Permanently</option>
								  <option value="months" '.($time_of_blocking_type=="months" ? "selected" : "").'>Months</option>
								  <option value="days" '.($time_of_blocking_type=="days" ? "selected" : "").'>Days</option>
								  <option value="hours" '.($time_of_blocking_type=="hours" ? "selected" : "").'>Hours</option>
								  <option value="minutes" '.($time_of_blocking_type=="minutes" ? "selected" : "").'>Minutes</option>
								</select>
							</td>
							<td id="time_of_blocking_val_cont" class='.($time_of_blocking_type=="permanent" ? "hidden" : "").'><input class="mo_lla_table_textbox '.($time_of_blocking_type=="permanent" ? "hidden" : "").' type="number" id="time_of_blocking_val" name="time_of_blocking_val" value="'.esc_html($time_of_blocking_val).'" placeholder="How many?" /></td>
						</tr>
						<tr>
							<td>Show remaining login attempts to user : </td>
							<td><input  type="checkbox"  id="rem_attempt" name="show_remaining_attempts" '.esc_html($remaining_attempts).' ></td>
						</tr>
						<tr style="justify-content:center;margin-top:10px">
							<td>
								<input type="hidden" id="brute_nonce" value ="'. wp_create_nonce("lla-brute-force").'" />
								<input type="button" style="width:100px;" value="Save" class="button button-primary button-large mo_lla_button1" id="mo_bf_save_button">
							</td>
						</tr>
					</table>
				</form>';
			
echo'	</div>';
echo'	<div id="molla-grec-div" class="mo-lla-sub-tabs" id="mo2f_google_recaptcha">
			<h3>Google reCAPTCHA <a href='.esc_html($two_factor_premium_doc['Google reCAPTCHA']).' target="_blank"><span class="dashicons dashicons-external"></span></a></h3>
			<div class="mo_lla_subheading">Google reCAPTCHA protects your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.</div>
			<p>Before you can use reCAPTCHA, you need to register your domain/website
                <a href="'.esc_html($captcha_url).'"  target="blank" title="guide">here</a>.</p>
			<form id="mo_lla_activate_recaptcha" method="post" action="">
				<input type="hidden" name="option" value="mo_lla_activate_recaptcha">
			</form>';

echo'	<form id="mo_lla_recaptcha_settings" method="post" action="">
            <div style="padding: 5px;">
            <input id="enable_captcha" type="checkbox" name="enable_captcha" '.esc_html($google_recaptcha).'>
                         Enable reCAPTCHA</div>
            <p>Select your preferred version of the reCAPTCHA:</p>
            <div style="padding: 5px;">
            <input type="radio" name="gcaptchatype" value="reCAPTCHA_v2"/>version 2</div>
            <div style="padding: 5px;">
            <input type="radio" name="gcaptchatype" value="reCAPTCHA_v3"/>version 3</div>';

echo'    <p>Enter Site key and Secret key that you get after registration.</p>
					<table class="mo_lla_settings_table">
						<tr>
							<td style="width:30%">Site key  : </td>
							<td style="width:30%"><input id="captcha_site_key" class="mo_lla_table_textbox" type="text" name="mo_lla_recaptcha_site_key" required placeholder="site key" /></td>
							<td style="width:20%"></td>
						</tr>
						<tr>
							<td>Secret key  : </td>
							<td><input id="captcha_secret_key" class="mo_lla_table_textbox" type="text" name="mo_lla_recaptcha_secret_key" required placeholder="secret key" /></td>
						</tr>
						<tr>
							<td style="vertical-align:top;">Enable reCAPTCHA for :</td>
							<td><input id="login_captcha" type="checkbox" name="mo_lla_activate_recaptcha_for_login" '.esc_html($captcha_login).'> Login form</td>
							<td><input id="reg_captcha" style="margin-left:10px" type="checkbox" name="mo_lla_activate_recaptcha_for_registration" '.esc_html($captcha_reg).' > Registration form</td>
							<td>
							<input id="cmnt_captcha" title="included in premium version" style="margin-left:10px" type="checkbox" name="mo_lla_activate_recaptcha_for_comments" '.esc_html($captcha_cmnt).' disabled> WordPress Comments
							</td></tr>
							<tr><td><input id="bp_captcha" title="included in premium version" style="margin-left:10px" type="checkbox" name="mo_lla_activate_recaptcha_for_buddypress_registration" '.esc_html($captcha_bp_reg).' disabled> BuddyPress Registration form 
							</td><td>
							<input id="email_captcha" title="included in premium version" style="margin-left:10px" type="checkbox" name="mo_lla_activate_recaptcha_for_email_subscription" '.esc_html($captcha_email).' disabled> Email Subscription form
							</td></tr>
						</table><br/>
						<input type="hidden" id="captcha_nonce" value = "'.wp_create_nonce("lla-captcha").'">
						<input type="button" id="captcha_button" type="button" value="Save Settings" class="button button-primary button-large mo_lla_button1" />
						<input type="button" value="Test reCAPTCHA Configuration" onclick="testcaptchaConfiguration()" class="button button-primary button-large mo_lla_button1"/>
						</form> </div>';?>
		 	<script>
                var recaptcha_version ="<?php echo esc_html(get_option('mo_lla_recaptcha_version'));?>";
                if(recaptcha_version=='reCAPTCHA_v3')
                    jQuery('input:radio[name="gcaptchatype"]').filter('[value="reCAPTCHA_v3"]').attr('checked', true);
                else if(recaptcha_version=='reCAPTCHA_v2')
  	                jQuery('input:radio[name="gcaptchatype"]').filter('[value="reCAPTCHA_v2"]').attr('checked', true);
  	            if(recaptcha_version =='reCAPTCHA_v3'){
  	            	 jQuery("#captcha_site_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_site_key_v3')); ?>");
  	            	
  	            	 jQuery("#captcha_secret_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_secret_key_v3') ) ; ?>");
  	            	}
  	            	else if(recaptcha_version =='reCAPTCHA_v2') {

                       jQuery("#captcha_site_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_site_key')); ?>");
                       jQuery("#captcha_secret_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_secret_key')); ?>");
  	            	}
  	            jQuery('input:radio[name="gcaptchatype"]').change(function(){
  	            
  	            	var captcha_version=jQuery("input[name='gcaptchatype']:checked").val();
  	            	
  	            	if(captcha_version =='reCAPTCHA_v3'){
  	            	 jQuery("#captcha_site_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_site_key_v3')); ?>");
  	            	
  	            	 jQuery("#captcha_secret_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_secret_key_v3')); ?>");
  	            	}
  	            	else if(captcha_version =='reCAPTCHA_v2') {

                       jQuery("#captcha_site_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_site_key')); ?>");
                       jQuery("#captcha_secret_key").val("<?php echo esc_html(get_option('mo_lla_recaptcha_secret_key')); ?>");
  	            	}
  	            })
    </script>
			
	<?php echo '</div>';?> 
		<div id="molla-rlu-div" class="mo-lla-sub-tabs">
		<h3>Rename Login URL</h3>
		<form id="mo_lla_enable_rename_login_url_form" method="post" action="">
        <input type="hidden" name="option" value="mo_lla_enable_rename_login_url">
    	<input type="checkbox" name="enable_rename_login_url_checkbox" <?php if(get_option('mo_lla_enable_rename_login_url')) echo "checked";?> onchange="document.getElementById('mo_lla_enable_rename_login_url_form').submit(); " > Enable Rename Login Page URL 
    	</form>

	<?php $login_page_url = "mylogin";
		if(get_option('mo_lla_enable_rename_login_url')) {
        	$login_page_url = "mylogin";
    	}
		if (get_option('mo_lla_login_page_url')) {
			$login_page_url = esc_html(get_option('mo_lla_login_page_url'));
		}?>
		<form id="mo_lla_enable_rename_login_url_form" method="post" action="">	
                <input type="hidden" name="option" value="mo_lla_rename_login_url_configuration">
                <table class="mo_lla_settings_table">
                    <tr>
                        <td>Login Page URL : </td>
                        <td><?php echo esc_url(site_url()); ?>/</td>
                        <td>
                            <input class="mo_lla_table_textbox" type="text" id="login_page_url" name="login_page_url" placeholder="Enter New Login Page URL" value="<?php echo esc_html($login_page_url)?>"/>
                        </td></tr><tr>
                    <td>Your Current Login URL : </td>
                    <td colspan="2"><?php echo site_url(); ?>/<?php echo esc_html($login_page_url);?></td></tr><tr>
                        <td><br><input type="submit" name="submit" style="width:100px;" value="Save"  class="button button-primary button-large mo_lla_button1" ></td>
                        <td></td>
						<td></td>
                    </tr></table></form>
                    <br><br>
                    <hr>
                    <?php
                    echo' <h3>Block Registerations from fake users</h3>
					<div class="mo_lla_subheading">Disallow Disposable / Fake / Temporary email addresses</div>	
					<form id="mo_lla_enable_fake_domain_blocking" method="post" action="">
					<input type="hidden" name="option" value="mo_lla_enable_fake_domain_blocking">
					<input type="checkbox" name="mo_lla_enable_fake_domain_blocking" '.esc_html($domain_blocking).' onchange="document.getElementById(\'mo_lla_enable_fake_domain_blocking\').submit();"> Enable blocking registrations from fake users.
		</form>
					</div>';?>
                
	
<?php
echo '<script>

		function testcaptchaConfiguration(){
                var gradioVal = jQuery("input[name=gcaptchatype]:checked").val();
                if(gradioVal=="reCAPTCHA_v3"){
                var myWindow = window.open("'.esc_url($test_recaptcha_url_v3).'", "Test Google reCAPTCHA_v3 Configuration", "width=600, height=600");}
                else if(gradioVal=="reCAPTCHA_v2"){
                var myWindow = window.open("'.esc_url($test_recaptcha_url).'", "Test Google reCAPTCHA_v2 Configuration", "width=600, height=600");}
        }
	</script>';			

			
echo'<br>
	<script>
		jQuery(document).ready(function(){
			$("#time_of_blocking_type").change(function() {
				if($(this).val()=="permanent"){
					$("#time_of_blocking_val_cont").addClass("hidden");
					$("#time_of_blocking_val").addClass("hidden");
				}
				else{
					$("#time_of_blocking_val_cont").removeClass("hidden");	
					$("#time_of_blocking_val").removeClass("hidden");	
				}
			});
		});	

		function mo_enable_disable_bf(){
			jQuery.ajax({
				type : "POST",
				data : {
					option: "mo_lla_enable_brute_force",
					status: "'.esc_attr($brute_force_enabled).'",
				},
				success: function(data){
					alert(data);
				}  
			 });
		}
		
		</script>'; 

		function login_security_ajax(){
			if ( ('admin.php' != basename( sanitize_text_field($_SERVER['PHP_SELF'] ))) || (sanitize_text_field($_GET['page']) != 'mo_lla_login_and_spam') ) {
				return;
            }
		?>
				<script>
				jQuery(document).ready(function(){
				jQuery("#mo_bf_save_button").click(function(){
					var all_log_att= jQuery("#allwed_login_attempts").val();
					if(all_log_att<2 || all_log_att == ""){
						window.onload = nav_popup('Minimum 2 attempts required');
						return;
					}
					var time_blo_val = jQuery("#time_of_blocking_val").val();
					if(jQuery("#time_of_blocking_val").val()<1){
                        window.onload = nav_popup('Minimum 1 minute time required.');
						return;
					}
					
					var data =  {
					'action': 'lla_login_security',
					'lla_loginsecurity_ajax' : 'lla_bruteforce_form', 
					'bf_enabled/disabled'     : jQuery("#mo_bf_button").is(":checked"),
					'allwed_login_attempts'   : jQuery("#allwed_login_attempts").val(),
					'time_of_blocking_type'   : jQuery("#time_of_blocking_type").val(),
					'time_of_blocking_val'    : jQuery("#time_of_blocking_val").val(),
					'show_remaining_attempts' : jQuery("#rem_attempt").is(':checked'),
					'nonce' 				  : jQuery("#brute_nonce").val(),	
				};
				jQuery.post(ajaxurl, data, function(response) {
			
						if (response == "empty"){
							window.onload = nav_popup('Please fill out all the fields');
						}
						else if(response == "true"){
							window.onload = nav_popup('Brute force is enabled and configuration has been saved',true);
						}
						else if(response == "false"){
							window.onload = nav_popup('Brute force is disabled');
						}
						else if(response == "ERROR" ){ 
							window.onload = nav_popup('ERROR');
						}
						});
				});

				jQuery(document).ready(function(){
				jQuery("#rename_login_config_url").click(function(){
					var data = {
					'action'                 :'lla_login_security',
					'lla_loginsecurity_ajax':'lla_rename_loginURL',
				 	'enable_rename_loginurl' :jQuery('#rename_url_chkbx').is(':checked'),
				 	'input_url'				 :jQuery('#login_page_url').val(), 
				 	'nonce'                  :jQuery('#wpns_url').val(), 
				 }
				 jQuery.post(ajaxurl, data, function(response) {
				 
				 if (response == "empty"){
				 	jQuery('#lla_message').append("<div id='notice_div' class='overlay_error'><div class='popup_text'>&nbsp; &nbsp; Please fill out all the fields</div></div>");
                  	window.onload = nav_popup('Please fill out all the fields');
				 }
				 else if(response == "true"){
				 	
				 	jQuery('#loginURL').hide();
				 	jQuery('#loginURL').show();
				 	jQuery('#loginURL').append(data.input_url);
                    window.onload = nav_popup('Login Page URL has been changed',true);
				 }
				 else if(response == "false"){
				 	jQuery('#loginURL').empty();
				 	jQuery('#loginURL').hide();
				 	jQuery('#loginURL').show();
				 	jQuery('#loginURL').append('wp-login.php');	
                  	window.onload = nav_popup('Your custom login page URL is DISABLED');
				 }
				 else if(response == "ERROR" ){ 
                 window.onload = nav_popup('ERROR');
				
				 }
				 });
			});
		});	
	});
					
jQuery(document).ready(function(){
    jQuery("#captcha_button").click(function(){                    	
    var recaptcha_version =jQuery("input[name='gcaptchatype']:checked").val();
    var data = {
    'action'                 :'lla_login_security',
    'lla_loginsecurity_ajax':'lla_save_captcha',
    'site_key'  			 : jQuery("#captcha_site_key").val(),
    'secret_key'			 : jQuery("#captcha_secret_key").val(),
    'version'                : recaptcha_version,
    'enable_captcha'		 : jQuery("#enable_captcha").is(':checked'),
    'login_form'			 : jQuery("#login_captcha").is(':checked'),
    'registeration_form'	 : jQuery("#reg_captcha").is(':checked'),
    'nonce'		           	 : jQuery("#captcha_nonce").val(),
    }
    jQuery.post(ajaxurl, data, function(response) {

                            if (response == "empty"){
                            	window.onload = nav_popup('Please fill out all the fields');				
                            }
                            if (response == "version_select"){                            	
                            	window.onload = nav_popup('Please select a version for the reCAPTCHA');				
                        	}
                            else if(response == "true"){                            	
                                jQuery('#loginURL').empty();
                                jQuery('#loginURL').hide();
                                jQuery('#loginURL').show();
                                jQuery('#loginURL').append(data.input_url);
                                window.onload = nav_popup('CAPTCHA is enabled',true);					
                            }
                            else if(response == "false"){                            	
                                if(!jQuery("input[name='gcaptchatype']:checked").val())
                                {
                                    jQuery('#loginURL').empty();
                                    jQuery('#loginURL').hide();
                                    jQuery('#loginURL').show();
                                    jQuery('#loginURL').append('wp-login.php');
                                    window.onload = nav_popup('Select a version');
                                }
                                else{
                                jQuery('#loginURL').empty();
                                jQuery('#loginURL').hide();
                                jQuery('#loginURL').show();
                                jQuery('#loginURL').append('wp-login.php');
                                window.onload = nav_popup('CAPTCHA is disabled');}				}
                            else if(response == "ERROR" ){
                                window.onload = nav_popup('ERROR');
                            }
                        });
                    });
                });
function nav_popup(message=null,isSuccess=false) {

	const className=isSuccess?'overlay_success':'overlay_error';

	if(message!==null){
		jQuery('#lla_message').empty();
		jQuery('#lla_message').append("<div id='notice_div' class='"+className+"'><div class='popup_text'>&nbsp; &nbsp;"+message+"</div></div>");
	}
 	document.getElementById("notice_div").style.width = "30%";
  	setTimeout(function(){ jQuery('#notice_div').fadeOut('slow'); }, 3000);
}
	

jQuery('.molla-nav-text').addClass('molla-nav-text-margin');
jQuery('#molla-small-logo').removeClass('molla-miniorange-logo');

jQuery('#molla-bfp').click();

function molla_switch_tabs(component){
	const tabs = ['molla-bfp','molla-rlu','molla-grec'];
	jQuery("#molla_sub_feature_nav").html(jQuery("#"+component.id).text());
	tabs.forEach(element => {
		if(component.id==element){
			jQuery('#'+element+'-div').show();
			jQuery('#'+element).addClass('molla-sub-tab-active');

		}
		else{
			jQuery('#'+element+'-div').hide();
			jQuery('#'+element).removeClass('molla-sub-tab-active');

		}
	});

}
</script> 
<?php } ?>
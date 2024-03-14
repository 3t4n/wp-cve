<?php if ( ! defined( 'ABSPATH' ) )  die( 'Nope, not accessing this' ); // Exit if accessed directly ?>
<div class="wrap">
    <h1><?php _e( 'Setup Settings','wp-prayers-request' ); ?></h1>
    <?php 
		if(isset($_POST['prayerssettings'])){

			update_option('upr_no_prayer_per_page', $_POST['upr_no_prayer_per_page']);
			update_option('upr_login_not_required_request', 0);			
			update_option('upr_prayer_req_email', 0);
			update_option('upr_prayer_send_email', 0);
			update_option('upr_prayer_send_admin_email', 0);			
			update_option('upr_prayer_default_status_pending', 0);
			update_option('upr_hide_prayer_button', 0);
			update_option('upr_hide_prayer_count', 0);
			update_option('upr_display_username_on_prayer_listing', 0);			
			update_option('upr_prayer_hide_captcha', 1);
			update_option('upr_prayer_show_country', 0);
			update_option('upr_allow_comments_prayer_request', 0);
			update_option('upr_pray_prayed_button_ip', 0);						
			update_option('upr_show_prayer_category', 0);
			update_option('upr_prayer_share', 0);
			update_option('upr_ago', 0);
			update_option('upr_time_interval_pray_prayed_button', $_POST['upr_time_interval_pray_prayed_button']);	
			update_option('upr_prayer_fetch_req_from', $_POST['upr_prayer_fetch_req_from']);
			if(isset($_POST['upr_show_prayer_category'])){
				update_option('upr_show_prayer_category', 1);
			}
			if(isset($_POST['upr_login_not_required_request'])){
				update_option('upr_login_not_required_request', 1);
			}
			if(isset($_POST['upr_prayer_default_status_pending'])){
				update_option('upr_prayer_default_status_pending', 1);
			}
			if(isset($_POST['upr_hide_prayer_button'])){
				update_option('upr_hide_prayer_button', 1);
			}
			if(isset($_POST['upr_hide_prayer_count'])){
				update_option('upr_hide_prayer_count', 1);
			}
			if(isset($_POST['upr_prayer_req_email'])){
				update_option('upr_prayer_req_email', 1);
			}
			if(isset($_POST['upr_prayer_send_email'])){
				update_option('upr_prayer_send_email', 1);
			}
			if(isset($_POST['upr_prayer_send_admin_email'])){
				update_option('upr_prayer_send_admin_email', 1);
			}			
			if(isset($_POST['upr_display_username_on_prayer_listing'])){
				update_option('upr_display_username_on_prayer_listing', 1);
			}
			if(isset($_POST['upr_prayer_hide_captcha'])){
				update_option('upr_prayer_hide_captcha', 0);
			}
			if(isset($_POST['upr_allow_comments_prayer_request'])){
				update_option('upr_allow_comments_prayer_request', 1);
			}
			if(isset($_POST['upr_pray_prayed_button_ip'])){
				update_option('upr_pray_prayed_button_ip', 1);
			}						
			if(isset($_POST['upr_prayer_show_country'])){
				update_option('upr_prayer_show_country', 1);
			}
			if(isset($_POST['upr_prayer_share'])){
				update_option('upr_prayer_share', 1);
			}	
			if(isset($_POST['upr_ago'])){
				update_option('upr_ago', 1);
			}	
            if(isset($_POST['secret_gc'])){
                $key=$_POST['secret_gc'];
                update_option('upr_prayer_secret', $key);
            }
            if(isset($_POST['sitekey_gc'])){
                $key1=$_POST['sitekey_gc'];
                update_option('upr_prayer_sitekey', $key1);
            }
            $upr_prayer_thankyou = isset($_POST['upr_prayer_thankyou']) ?  stripslashes_deep($_POST['upr_prayer_thankyou']) : '';
            update_option('upr_prayer_thankyou', $upr_prayer_thankyou);        
            
			echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
			<p><strong>'.__('Updated','wp-prayers-request').'</strong></p></div>';
		} 
	?>
    <form method="post" action="" novalidate>
        <table class="form-table">
        	<tbody>
                <?php 

				$upr_no_prayer_per_page = get_option('upr_no_prayer_per_page');
				$upr_login_not_required_request = get_option('upr_login_not_required_request');
				$upr_prayer_req_email = get_option('upr_prayer_req_email');
				$upr_prayer_send_email = get_option('upr_prayer_send_email'); 
				$upr_prayer_send_admin_email = get_option('upr_prayer_send_admin_email');				
				$upr_prayer_default_status_pending = get_option('upr_prayer_default_status_pending');
				$upr_hide_prayer_button = get_option('upr_hide_prayer_button');
				$upr_hide_prayer_count = get_option('upr_hide_prayer_count');
				$upr_display_username_on_prayer_listing = get_option('upr_display_username_on_prayer_listing');
				$upr_prayer_hide_captcha = get_option('upr_prayer_hide_captcha');
				$upr_prayer_show_country = get_option('upr_prayer_show_country');
				$upr_prayer_share = get_option('upr_prayer_share');
				$upr_ago = get_option('upr_ago');
                $upr_prayer_secret=get_option('upr_prayer_secret');
                $upr_prayer_sitekey=get_option('upr_prayer_sitekey');
                $upr_prayer_thankyou = get_option('upr_prayer_thankyou');
				?>
				<tr>
                    <td scope="row"><label><?php _e( 'Shortcode for prayer request [upr_form] and for prayer listing [upr_list_prayers]' ,'wp-prayers-request'); ?></label></td>
                </tr
                <tr>
                    <td><label><?php _e( 'No. of prayers per page','wp-prayers-request'); ?></label></td>
                    <td><input name="upr_no_prayer_per_page" id="no_prayer_per_page" value="<?php echo esc_html($upr_no_prayer_per_page)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <td scope="row"><label><?php _e( 'Login not required for prayer request','wp-prayers-request'); ?></label></td>
                    <td><input name="upr_login_not_required_request" id="login_not_required_request" value="1" class="regular-text" type="checkbox" <?php if($upr_login_not_required_request==1) echo "checked"; ?>> <?php _e( 'Login is not required to submit the prayer request.','wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_prayer_send_email = get_option('upr_prayer_send_email'); ?>
                <tr>
				    <td scope="row"><label><?php _e( 'Do not show email on prayer request' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_req_email" id="prayer_req_email" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_req_email==1) echo "checked"; ?>> <?php _e( 'Do not show email on prayer request','wp-prayers-request'); ?></td>
                </tr>
                    <td scope="row"><label><?php _e( 'Send Notification To User' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_send_email" id="prayer_send_email" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_send_email==1) echo "checked"; ?>> <?php _e( 'User is to be notified via email after submitting the prayer request.','wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_send_admin_email = get_option('upr_prayer_send_admin_email'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Send Notification To Admin' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_send_admin_email" id="prayer_send_admin_email" value="1" class="regular-text" type="checkbox" <?php if($upr_send_admin_email==1) echo "checked"; ?>> <?php _e( 'Admin is to be notified via email whenever new prayer request is submitted.','wp-prayers-request' ); ?></td>
                </tr>
                <?php $upr_prayer_default_status_pending = get_option('upr_prayer_default_status_pending'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Default prayer requests to status pending.' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_default_status_pending" id="prayer_default_status_pending" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_default_status_pending==1) echo "checked"; ?>> <?php _e( 'Default prayer requests to status pending.','wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_hide_prayer_button = get_option('upr_hide_prayer_button'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Hide Prayer button','wp-prayers-request'); ?></label></td>
                    <td><input name="upr_hide_prayer_button" id="hide_prayer_button" value="1" class="regular-text" type="checkbox" <?php if($upr_hide_prayer_button==1) echo "checked"; ?>> <?php _e( 'Hide Prayer button','wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_hide_prayer_count = get_option('upr_hide_prayer_count'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Hide Prayer count','wp-prayers-request'); ?></label></td>
                    <td><input name="upr_hide_prayer_count" id="hide_prayer_count" value="1" class="regular-text" type="checkbox" <?php if($upr_hide_prayer_count==1) echo "checked"; ?>> <?php _e( 'Hide Prayer count','wp-prayers-request' ); ?></td>
                </tr>
                <?php $upr_display_username_on_prayer_listing = get_option('upr_display_username_on_prayer_listing'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Display user name on prayer listing' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_display_username_on_prayer_listing" id="display_username_on_prayer_listing" value="1" class="regular-text" type="checkbox" <?php if($upr_display_username_on_prayer_listing==1) echo "checked"; ?>> <?php _e( 'Display user name on prayer listing' ,'wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_prayer_hide_captcha = get_option('upr_prayer_hide_captcha'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Disable captcha' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_hide_captcha" id="prayer_hide_captcha" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_hide_captcha==0) echo "checked"; ?>> <?php _e( 'Disable captcha' ,'wp-prayers-request'); ?></td>
                </tr>
                <tr>
                    <td><label>Google reCaptcha v2 Site Key</label></td>
                    <td><input class="regular-text" value="<?php echo esc_html($upr_prayer_sitekey)?>" type="text" name="sitekey_gc" placeholder="6LeG_2QUAAAAAIw5Qj9eyTlt_sATdOmTHesbxdea"></td>
                </tr>
                <tr>
                    <td><label>Google reCaptcha v2 Secret Key</label></td>
                    <td><input name="secret_gc" class="regular-text" value="<?php echo esc_html($upr_prayer_secret)?>"
                               type="text" placeholder="6LeG_2QUAAAAAF-N1s4TN_s_jDGIM9gfRuD0Hqpo"></td>
                </tr>
                <?php $upr_prayer_show_country = get_option('upr_prayer_show_country'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Show country' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_prayer_show_country" id="prayer_show_country" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_show_country==1) echo "checked"; ?>> <?php _e( 'Display country on form' ,'wp-prayers-request'); ?></td>
                </tr>
                <?php $upr_show_prayer_category = get_option('upr_show_prayer_category'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Show prayer category','wp-prayers-request' ); ?></label></td>
                    <td><input name="upr_show_prayer_category" id="show_prayer_category" value="1" class="regular-text" type="checkbox" <?php if($upr_show_prayer_category==1) echo "checked"; ?>> <?php _e( 'Display prayer category on form' ,'wp-prayers-request'); ?></td>
                </tr> 
                <tr>
                    <td scope="row"><label><?php _e( 'Share','wp-prayers-request' ); ?></label></td>
                    <td><input name="upr_prayer_share" id="prayer_share" value="1" class="regular-text" type="checkbox" <?php if($upr_prayer_share==1) echo "checked"; ?>> <?php _e( 'Do not share this request' ,'wp-prayers-request'); ?></td>
                </tr>				
                <?php $upr_allow_comments_prayer_request = get_option('upr_allow_comments_prayer_request'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Allow Comments' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_allow_comments_prayer_request" id="allow_comments_prayer_request" value="1" class="regular-text" type="checkbox" <?php if($upr_allow_comments_prayer_request==1) echo "checked"; ?>> <?php _e( 'Allow comments on prayer comments' ,'wp-prayers-request'); ?></td>
                </tr>
				<tr>
                    <td scope="row"><label><?php _e( 'For translation, the word ago is place before the time','wp-prayers-request' ); ?></label></td>
                    <td><input name="upr_ago" id="prayer_ago" value="1" class="regular-text" type="checkbox" <?php if($upr_ago==1) echo "checked"; ?>> <?php _e( 'For translation, the word ago is place before the time' ,'wp-prayers-request');
					$prayer_date4=__('ago','wp-prayers-request').' '.human_time_diff( current_time('U')-259200, current_time('U') );echo ', '.$prayer_date4; ?></td>
                </tr>				
                <?php $upr_pray_prayed_button_ip = get_option('upr_pray_prayed_button_ip'); ?>
                <!--<tr>
                    <td scope="row"><label><?php _e( 'Pray/Prayed button by IP address' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="pray_prayed_button_ip" id="pray_prayed_button_ip" value="1" class="regular-text" type="checkbox" <?php if($upr_pray_prayed_button_ip==1) echo "checked"; ?>> <?php _e( 'On/Off' ,'wp-prayers-request'); ?></td>
                </tr>-->
                <?php $upr_time_interval_pray_prayed_button = get_option('upr_time_interval_pray_prayed_button'); ?>
                <tr>
                    <td scope="row"><label><?php _e( 'Time interval between Pray/Prayed button' ,'wp-prayers-request'); ?></label></td>
                    <td><input name="upr_time_interval_pray_prayed_button" id="upr_time_interval_pray_prayed_button" value="<?php echo esc_html($upr_time_interval_pray_prayed_button) ?>"  type="text" size="10"> <?php _e( '(in seconds)' ,'wp-prayers-request'); ?></td>
                </tr>   
                                <?php $upr_prayer_thankyou = get_option('upr_prayer_thankyou'); ?>
                         <tr>
                    <td scope="row"><label><?php _e( 'Thank you message','wp-prayers-request'); ?></label></td>
                    <td><textarea rows="5" cols="70" placeholder="Thank you. Your form has been received" name="upr_prayer_thankyou"><?php echo esc_html($upr_prayer_thankyou) ?></textarea></td>
                </tr>
                <?php $upr_fetch_req_from = get_option('upr_prayer_fetch_req_from'); ?>
                <tr>
                    <td scope="row"><label><?php _e('Which Requests Would You like to Display?','wp-prayers-request')?></label></td>
                    <td>
                    <select name="upr_prayer_fetch_req_from" class="form-control">
                    	<option value="all"><?php _e( 'all of them' ,'wp-prayers-request'); ?></option>
                        <option value="14" <?php if($upr_fetch_req_from==14) echo "selected"?>><?php _e( 'only the last 14 days' ,'wp-prayers-request'); ?></option>
                        <option value="30" <?php if($upr_fetch_req_from==30) echo "selected"?>><?php _e( 'only the last 30 days' ,'wp-prayers-request'); ?></option>
                        <option value="60" <?php if($upr_fetch_req_from==60) echo "selected"?>><?php _e( 'only the last 60 days' ,'wp-prayers-request'); ?></option>
                        <option value="90" <?php if($upr_fetch_req_from==90) echo "selected"?>><?php _e( 'only the last 90 days' ,'wp-prayers-request'); ?></option>
                        <option value="120" <?php if($upr_fetch_req_from==120) echo "selected"?>><?php _e( 'only the last 120 days' ,'wp-prayers-request'); ?></option>
                    </select>
                    <p><?php _e( 'Choose how many prayers you want to display.' ,'wp-prayers-request'); ?></p></td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input name="prayerssettings" id="submit" class="button button-primary" value="<?php _e('Update','wp-prayers-request')?>" type="submit"></p>
      </form>
</div>

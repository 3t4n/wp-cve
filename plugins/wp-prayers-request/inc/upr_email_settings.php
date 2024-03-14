<?php if ( ! defined( 'ABSPATH' ) )  die( 'Nope, not accessing this' ); // Exit if accessed directly ?>
<div class="wrap">
    <h1><?php _e('Email Templates Settings','wp-prayers-request')?></h1>
    <?php 
		if(isset($_POST['emailsettings'])){ 
			$prayer_req_admin_email = isset($_POST['prayer_req_admin_email']) ? sanitize_email($_POST['prayer_req_admin_email']) : '';
			$prayer_admin_email_cc = isset($_POST['prayer_admin_email_cc']) ? sanitize_text_field($_POST['prayer_admin_email_cc']) : '';
			$prayer_email_from = isset($_POST['prayer_email_from']) ? sanitize_text_field($_POST['prayer_email_from']) : '';
			$prayer_email_user = isset($_POST['prayer_email_user']) ? sanitize_email($_POST['prayer_email_user']) : '';
			$prayer_email_req_subject = isset($_POST['prayer_email_req_subject']) ? sanitize_text_field(stripslashes_deep($_POST['prayer_email_req_subject'])) : '';			
			$prayer_email_req_messages = isset($_POST['prayer_email_req_messages']) ?  stripslashes_deep($_POST['prayer_email_req_messages']) : '';
			$prayer_email_admin_subject = isset($_POST['prayer_email_admin_subject']) ? sanitize_text_field(stripslashes_deep($_POST['prayer_email_admin_subject'])) : '';
			$prayer_email_admin_messages = isset($_POST['prayer_email_admin_messages']) ?  stripslashes_deep($_POST['prayer_email_admin_messages']) : '';
			
			update_option('prayer_req_admin_email', $prayer_req_admin_email);
			update_option('prayer_admin_email_cc', $prayer_admin_email_cc);
			update_option('prayer_email_from', $prayer_email_from);
			update_option('prayer_email_user', $prayer_email_user);
			update_option('prayer_email_req_subject', $prayer_email_req_subject);
			update_option('prayer_email_req_messages', $prayer_email_req_messages);
			update_option('prayer_email_admin_subject', $prayer_email_admin_subject);
			update_option('prayer_email_admin_messages', $prayer_email_admin_messages);
			echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>'.__('Settings saved','wp-prayers-request').'</strong></p></div>';
		} 
		$prayer_req_admin_email = get_option('prayer_req_admin_email');
		$prayer_admin_email_cc = get_option('prayer_admin_email_cc');
		$prayer_email_from = get_option('prayer_email_from');
		$prayer_email_user = get_option('prayer_email_user');
		$prayer_email_req_subject = get_option('prayer_email_req_subject');
		$prayer_email_req_messages = get_option('prayer_email_req_messages');
		$prayer_email_admin_subject = get_option('prayer_email_admin_subject');
		$prayer_email_admin_messages = get_option('prayer_email_admin_messages');
		
		// Setting up defaults for email
		$default = array(
		'prayer_email_req_subject' => 'Prayer request confirmation',		
		'prayer_email_admin_subject' => 'New Prayer Request Received To Moderate',
		);		
		$link=$_SERVER["SERVER_NAME"];$link1= '<a href="https://www.'.$link.'">Visit '.$link.'</a>';
		$default['prayer_email_req_messages'] = 
		'Hello {prayer_author_name},

Thank you for submitting your prayer request. We welcome all requests and we delight in lifting you and your requests up to God in prayer. God Bless you, and remember, God knows the prayers that are coming and hears them even before they are spoken.

Request: {prayer_messages}

Blessings,
Prayer Team
'.$link1;
		

		$default['prayer_email_admin_messages'] = 
		'Hello,

You have received a new prayer request to moderate with following details :
Name : {prayer_author_name}
Email: {prayer_author_email}
Request: {prayer_messages}

Thank you
'.$link1;
		
		if(empty($prayer_email_req_subject)){$prayer_email_req_subject=$default['prayer_email_req_subject'];}
		if(empty($prayer_email_admin_subject)){$prayer_email_admin_subject=$default['prayer_email_admin_subject'];}
		if(empty($prayer_email_req_messages)){$prayer_email_req_messages=$default['prayer_email_req_messages'];}
		if(empty($prayer_email_admin_messages)){$prayer_email_admin_messages=$default['prayer_email_admin_messages'];}
	?>
    <form method="post" action="" novalidate>
        <table class="form-table">
        	<tbody>
            	<tr>
                    <td colspan="2"><h2><?php _e( 'Sender Email Settings','wp-prayers-request'); ?></h2></td>
                </tr>
            	<tr>
                	<th scope="row"><label for="blogname"><?php _e( 'Admin Email','wp-prayers-request'); ?></label></th>
                    <td><input name="prayer_req_admin_email" id="prayer_req_admin_email" value="<?php echo esc_html($prayer_req_admin_email)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'Add CC Admin Email','wp-prayers-request'); ?></label></th>
                    <td><input name="prayer_admin_email_cc" id="prayer_admin_email_cc" value="<?php echo esc_html($prayer_admin_email_cc)?>" class="regular-text" type="text">
                    <p class="description"><?php _e( 'Add CC admin email addresses, separate them with comma','wp-prayers-request'); ?></p></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'From Name','wp-prayers-request'); ?></label></th>
                    <td><input name="prayer_email_from" id="prayer_email_from" value="<?php echo esc_html($prayer_email_from)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'From Email Address','wp-prayers-request'); ?></label></th>
                    <td><input name="prayer_email_user" id="prayer_email_user" value="<?php echo esc_html($prayer_email_user)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <td colspan="2"><h2><?php _e( 'User notification email Message','wp-prayers-request'); ?></h2></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'Prayer Request Subject','wp-prayers-request'); ?> </label></th>
                    <td><input name="prayer_email_req_subject" id="prayer_email_req_subject" value="<?php echo esc_html($prayer_email_req_subject)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'Prayer Request Message','wp-prayers-request'); ?></label></th>
                    <td><textarea rows="5" cols="100" name="prayer_email_req_messages"><?php echo esc_textarea($prayer_email_req_messages)?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><h2><?php _e( 'Admin notification email Message','wp-prayers-request'); ?></h2></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'Admin Email Subject','wp-prayers-request'); ?> </label></th>
                    <td><input name="prayer_email_admin_subject" id="prayer_email_admin_subject" value="<?php echo esc_html($prayer_email_admin_subject)?>" class="regular-text" type="text"></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e( 'Admin Email Message','wp-prayers-request'); ?></label></th>
                    <td><textarea rows="5" cols="100" name="prayer_email_admin_messages"><?php echo esc_html($prayer_email_admin_messages) ?></textarea></td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input name="emailsettings" id="submit" class="button button-primary" value="<?php _e( 'Update','wp-prayers-request'); ?>" type="submit"></p
    ></form>
</div>

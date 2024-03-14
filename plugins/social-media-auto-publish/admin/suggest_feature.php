<?php
if( !defined('ABSPATH') ){ exit();}
global $wpdb;
$xyz_smap_message='';
if(isset($_GET['msg']))
	$xyz_smap_message = $_GET['msg'];
if($xyz_smap_message == 1){
	?>
	<div class="system_notice_area_style1" id="system_notice_area">
	<?php _e('Thank you for the suggestion.','social-media-auto-publish'); ?> &nbsp;&nbsp;&nbsp;<span
	id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish'); ?> </span>
	</div>
	<?php
	}
else if($xyz_smap_message == 2){
		?>
		<div class="system_notice_area_style0" id="system_notice_area">
		<?php $smap_wp_mail="wp_mail"; $smap_wp_mail_msg=sprintf(__('%s not able to process the request.','social-media-auto-publish'),$smap_wp_mail); echo $smap_wp_mail_msg; ?> &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish'); ?> </span>
		</div>
		<?php
	}
else if($xyz_smap_message == 3){
	?>
	<div class="system_notice_area_style0" id="system_notice_area">
	 <?php _e('Please suggest a feature.','social-media-auto-publish'); ?> &nbsp;&nbsp;&nbsp;<span
	id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish'); ?> </span>
	</div>
	<?php
}
if (isset($_POST) && isset($_POST['xyz_send_mail']))
{
	if (! isset( $_REQUEST['_wpnonce'] )|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_smap_suggest_feature_form_nonce' ))
	{
		wp_nonce_ays( 'xyz_smap_suggest_feature_form_nonce' );
		exit();
	}
	if (isset($_POST['xyz_smap_suggested_feature']) && $_POST['xyz_smap_suggested_feature']!='')
	{
		$xyz_smap_feature_content=$_POST['xyz_smap_suggested_feature'];
		$xyz_smap_sender_email = get_option('admin_email');
		$entries0 = $wpdb->get_results( $wpdb->prepare( 'SELECT display_name FROM '.$wpdb->base_prefix.'users WHERE user_email=%s',array($xyz_smap_sender_email)));
		foreach( $entries0 as $entry ) {
			$xyz_smap_admin_username=$entry->display_name;
		}
		$xyz_smap_recv_email='support@xyzscripts.com';
		$xyz_smap_mail_subject="SOCIAL MEDIA AUTO PUBLISH - FEATURE SUGGESTION";
		$xyz_smap_headers = array('From: '.$xyz_smap_admin_username.' <'. $xyz_smap_sender_email .'>' ,'Content-Type: text/html; charset=UTF-8');
		$wp_mail_processed=wp_mail( $xyz_smap_recv_email, $xyz_smap_mail_subject, $xyz_smap_feature_content, $xyz_smap_headers );
		if ($wp_mail_processed==true){
		 header("Location:".admin_url('admin.php?page=social-media-auto-publish-suggest-features&msg=1'));
		 exit();
		}
		else {
			header("Location:".admin_url('admin.php?page=social-media-auto-publish-suggest-features&msg=2'));
			exit();
		}
	}
	else {
		header("Location:".admin_url('admin.php?page=social-media-auto-publish-suggest-features&msg=3'));
		exit();
	}
}?>
<form method="post" >
<?php wp_nonce_field( 'xyz_smap_suggest_feature_form_nonce' );?>
<h3> <?php _e('Contribute And Get Rewarded','social-media-auto-publish'); ?> </h3>
<span style="color: #1A87B9;font-size:13px;padding-left: 10px;" >*<?php _e('Suggest a feature for this plugin and stand a chance to get a free copy of premium version of this plugin.','social-media-auto-publish'); ?> </span>
<table  class="widefat xyz_smap_widefat_table" style="width:98%;padding-top: 10px;">
<tr><td>
<textarea name="xyz_smap_suggested_feature" id="xyz_smap_suggested_feature" style="width:620px;height:250px !important;"></textarea>
</td></tr>
<tr>
<td><input name="xyz_send_mail" class="submit_smap_new" style="color:#FFFFFF;border-radius:4px;border:1px solid #1A87B9; margin-bottom:10px;" type="submit" value=" <?php _e('Send Mail To Us','social-media-auto-publish'); ?>">
</td></tr>
</table>
</form>
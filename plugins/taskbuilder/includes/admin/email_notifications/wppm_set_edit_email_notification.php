<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user, $wppmfunction;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$wppm_email_notificatins = get_option('wppm_email_notification');
$term_id = isset($_POST) && isset($_POST['id']) ? intval(sanitize_text_field($_POST['id'])) : 0;
if(!$term_id) die();
$type = isset($_POST) && isset($_POST['wppm_en_type']) ? sanitize_text_field($_POST['wppm_en_type']) : '';
$subject = isset($_POST) && isset($_POST['wppm_en_subject']) ? sanitize_text_field($_POST['wppm_en_subject']) : '';
$allowed_tags = array( 'br' => array(), 'abbr' => array('title' => array(),), 'p' => array(), 'strong' => array(), 'a' => array('href' => array(), 'title' => array(),'target'=> array(), 'rel'=>array()),'em' =>array(),'span' =>array(), 'blockquote'=>array('cite'  => array(),),'div' => array('class' => array(),'title' => array(),'style' => array(),),'ul'=>array(),'li'=>array(),'ol'=>array(),'img' => array( 'alt'=> array(),'class' => array(),'height' => array(),'src'=> array(),'width'=> array(),));
$body = isset($_POST) && isset($_POST['wppm_en_body']) ? wp_kses(stripslashes(htmlspecialchars_decode($_POST['wppm_en_body'], ENT_QUOTES)),$allowed_tags) : '';
$recipients = isset($_POST) && isset($_POST['wppm_en_recipients']) ? $wppmfunction->sanitize_array($_POST['wppm_en_recipients']) : array();
foreach($wppm_email_notificatins as $key=>$val){
	if($key==$term_id){
		$val['type'] = $type;
		$val['subject'] = $subject;
		$val['body'] = $body;
		$val['recipients'] = $recipients;
		$wppm_email_notificatins[$key] = $val;
		update_option('wppm_email_notification',$wppm_email_notificatins);
	}
}

do_action('wppm_set_edit_email_notification',$term_id);

echo '{ "sucess_status":"1","messege":"'.__('Email Notification updated successfully.','taskbuilder').'" }';

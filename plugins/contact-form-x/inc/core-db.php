<?php // Contact Form X Database

if (!defined('ABSPATH')) exit;

function contactformx_register_post_type() {
	
	register_post_type('cfx_email', array('public' => false, 'exclude_from_search' => true, 'supports' => array('title', 'editor', 'custom-fields')));
	
}

function contactformx_insert_example_data() {
	
	$post_id = contactformx_insert_example_post();
	
	contactformx_attach_example_meta($post_id);
	
}

function contactformx_insert_example_post() {
	
	$message = __('Hello, this is an example. To clear all messages, visit "Reset Widget" in the Contact Form X settings, under the Advanced tab. Tip: click the little gear icon to go there directly.', 'contact-form-x');
	
	$user_id = apply_filters('contactformx_user_id', 1);
	
	$example = array(
		'post_author'  => $user_id,
		'post_type'    => 'cfx_email',
		'post_title'   => __('Example Email', 'contact-form-x'),
		'post_content' => $message,
		'post_status'  => 'draft',
	);
	
	return wp_insert_post($example);
	
}

function contactformx_attach_example_meta($post_id) {
	
	add_post_meta($post_id, 'email',     'guest@example.com');
	add_post_meta($post_id, 'recipient', 'recipient@example.com');
	add_post_meta($post_id, 'name',      __('Guest User', 'contact-form-x'));
	add_post_meta($post_id, 'website',   __('Example Website', 'contact-form-x'));
	add_post_meta($post_id, 'custom',    __('Additional information', 'contact-form-x'));
	
}

//

function contactformx_insert_post($result, $errors, $recipients, $array) {
	
	$options = contactformx_options('advanced');
	
	$disable = isset($options['disable-database-storage']) ? $options['disable-database-storage'] : 0;
	
	$post_id = false;
	
	if ($disable) return $post_id;
	
	$subject = isset($array['subject']) ? $array['subject'] : '';
	$message = isset($array['message']) ? $array['message'] : '';
	
	$user_id = apply_filters('contactformx_user_id', 1);
	
	$cfx_email = array(
		'post_author'  => $user_id,
		'post_type'    => 'cfx_email',
		'post_title'   => $subject,
		'post_content' => $message,
		'post_status'  => 'draft',
	);
	
	$meta_id = false;
	
	if ($result && empty($errors)) {
		
		$post_id = wp_insert_post($cfx_email);
		
		contactformx_attach_meta($post_id, $recipients, $array);
		
	}
	
	return $post_id;
	
}

function contactformx_attach_meta($post_id, $recipients, $array) {
	
	$email   = isset($array['email'])   ? $array['email']   : '';
	$name    = isset($array['name'])    ? $array['name']    : '';
	$website = isset($array['website']) ? $array['website'] : '';
	$custom  = isset($array['custom'])  ? $array['custom']  : '';
	$carbon  = (isset($array['carbon']) && ($array['carbon'] === 'true')) ? 1 : 0;
	$agree   = (isset($array['agree'])  && ($array['agree']  === 'true')) ? 1 : 0;
	
	$recipient = '';
	
	foreach ($recipients as $r) {
		
		$to = isset($r['to']) ? $r['to'] : '';
		
		$recipient .= $to ? sanitize_email($to) .',' : '';
		
	}
	
	$recipient = trim($recipient, ', ');
	
	if (contactformx_enable_data()) {
		
		$refer = contactformx_get_refer();
		$ip    = contactformx_get_ip();
		$host  = contactformx_get_host();
		$agent = contactformx_get_agent();
		
		if (!empty($refer)) add_post_meta($post_id, 'refer', sanitize_text_field($refer));
		if (!empty($ip))    add_post_meta($post_id, 'ip',    sanitize_text_field($ip));
		if (!empty($host))  add_post_meta($post_id, 'host',  sanitize_text_field($host));
		if (!empty($agent)) add_post_meta($post_id, 'agent', sanitize_text_field($agent));
		
	}
	
	if (!empty($email))     add_post_meta($post_id, 'email',     $email);
	if (!empty($recipient)) add_post_meta($post_id, 'recipient', $recipient);
	if (!empty($name))      add_post_meta($post_id, 'name',      $name);
	if (!empty($website))   add_post_meta($post_id, 'website',   $website);
	if (!empty($custom))    add_post_meta($post_id, 'custom',    $custom);
	if (!empty($carbon))    add_post_meta($post_id, 'carbon',    $carbon);
	if (!empty($agree))     add_post_meta($post_id, 'agree',     $agree);
	
}

/* legacy */

function contactformx_legacy_empty_table() {
	
	$nonce = wp_create_nonce('contactformx_reset_widget_legacy');
	
	$href  = add_query_arg(array('reset-widget-verify-legacy' => $nonce), admin_url('options-general.php?page=contactformx'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Empty the CFX database table', 'contact-form-x');
	
	return '<a class="cfx-reset-widget-legacy" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function contactformx_legacy_drop_table() {
	
	$nonce = wp_create_nonce('contactformx_drop_table_legacy');
	
	$href  = add_query_arg(array('drop-table-verify-legacy' => $nonce), admin_url('options-general.php?page=contactformx'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Remove the CFX database table', 'contact-form-x');
	
	return '<a class="cfx-drop-table-legacy" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}
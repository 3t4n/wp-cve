<?php // Contact Form X Sent Email Widget

if (!defined('ABSPATH')) exit;

function contactformx_dashboard_widget() { 
	
	$display_emails = apply_filters('contactformx_display_emails', 10);
	
	$cfx_emails = get_posts(array('post_type' => 'cfx_email', 'post_status' => 'any', 'posts_per_page' => $display_emails));
	
	echo '<div class="cfx-dashboard-widget">';
	
	if ($cfx_emails) {
		
		echo '<p>'. __('Latest', 'contact-form-x') .' '. $display_emails .' '. __('emails sent via your contact form.', 'contact-form-x') .'</p>';
		
		foreach ($cfx_emails as $cfx_email) {
			
			$truncate_title   = apply_filters('contactformx_truncate_title', 100);
			$truncate_content = apply_filters('contactformx_truncate_content', 5000);
			
			$id   = $cfx_email->ID;
			$subj = contactformx_truncate_string($cfx_email->post_title, $truncate_title);
			$mess = contactformx_truncate_string($cfx_email->post_content, $truncate_content);
			
			$from = get_post_meta($id, 'email', true);
			$name = get_post_meta($id, 'name', true);
			
			if (empty($name)) $name = apply_filters('contactformx_widget_default_name', __('Visitor', 'contact-form-x'));
			
			$b = get_the_date('M d, Y', $id);
			$d = get_the_date('l, F jS, Y', $id);
			$t = get_the_date('H:i:s', $id);
			
			$send   = __('Click to send email',   'contact-form-x');
			$toggle = __('Click to view message', 'contact-form-x');
			
			echo '<div class="cfx-dashboard-widget-row">';
			echo	'<div class="cfx-dashboard-widget-meta">';
			echo 		'<div class="cfx-dashboard-widget-meta-col1">';
			echo 			'<span class="cfx-date" title="'. esc_attr($d) .' @ '. esc_attr($t) .'">'. esc_html($b) .'</span> : ';
			echo 		'</div>';
			echo 		'<div class="cfx-dashboard-widget-meta-col2">';
			echo 			'<span class="cfx-name"><a href="mailto:'. sanitize_email($from) .'" title="'. esc_attr($send) .'">'. esc_html($name) .'</a></span> <span>-</span> ';
			echo 			'<span class="cfx-subj"><a href="#cfx" title="'. esc_attr($toggle) .'" data-id="'. esc_attr($id) .'">'. esc_html($subj) .'</a></span> ';
			echo 		'</div>';
			echo 	'</div>';
			echo 	'<div class="cfx-dashboard-widget-data cfx-id-'. esc_attr($id) .'">';
			echo 		'<span class="cfx-mess"><pre>'. esc_textarea($mess) .'</pre></span>';
			echo 	'</div>';
			echo '</div>';
			
		}
		
		wp_reset_postdata();
		
	} else {
		
		echo '<p class="cfx-email-log-empty">'. esc_html__('No messages.', 'contact-form-x') .'</p>';
		
	}
	
	echo '</div>';
		
}

function contactformx_add_custom_dashboard_widget() {
	
	$options = contactformx_options('advanced');
	
	$display = isset($options['display-dash-widget']) ? $options['display-dash-widget'] : 0;
	
	if ($display) {
		
		if (!current_user_can('manage_options') && !current_user_can('edit_others_posts')) return;
		
	} else {
		
		if (!current_user_can('manage_options')) return;
		
	}
	
	$disable = isset($options['disable-dash-widget']) ? $options['disable-dash-widget'] : 0;
	
	if ($disable) return;
	
	$icon = '<span class="dashicons dashicons-admin-generic"></span>';
	
	$link = '<a class="cfx-widget-settings" href="'. admin_url('options-general.php?page=contactformx&tab=tab5') .'">'. $icon .'</a>';
	
	wp_add_dashboard_widget('contactformx_dashboard_widget', __('Contact Form', 'contact-form-x') . $link, 'contactformx_dashboard_widget');
	
}

function contactformx_add_glance_items() {
	
	if (!current_user_can('manage_options')) return;
	
	$options = contactformx_options('advanced');
	
	$disable = isset($options['disable-dash-widget']) ? $options['disable-dash-widget'] : 0;
	
	if ($disable) return;
	
	$count = wp_count_posts('cfx_email')->draft;
	
	$count = number_format_i18n($count);
	
	$text = _n(' Email', 'Emails', intval($count), 'contact-form-x');
	
	$url = admin_url('options-general.php?page=contactformx');
	
	$output = '<li class="comment-count">';
	
	$output .= '<a href="'. esc_url($url) .'">'. esc_html($count) .' '. esc_html($text) .'</a>';
	
	$output .= '</li>';
	
	echo $output;
	
}
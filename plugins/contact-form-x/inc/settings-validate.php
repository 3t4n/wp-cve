<?php // Contact Form X Validate Settings

if (!defined('ABSPATH')) exit;

function contactformx_validate_recipients($input) {
	
	if (isset($input['number-recipients'])) $input['number-recipients'] = wp_filter_nohtml_kses($input['number-recipients']);
	else $input['number-recipients'] = null;
	
	foreach (contactformx_get_recipients() as $i) {
		
		$empty_name = $empty_to = $empty_from = true;
		
		if (isset($input['recipient-'. $i]['name'])) {
			
			if (!empty($input['recipient-'. $i]['name'])) {
				
				$input['recipient-'. $i]['name'] = wp_filter_nohtml_kses($input['recipient-'. $i]['name']);
				
				$empty_name = false;
				
			}
			
		} else {
			
			$input['recipient-'. $i]['name'] = null;
			
		}
		
		if (isset($input['recipient-'. $i]['to'])) {
			
			if (!empty($input['recipient-'. $i]['to'])) {
				
				$input['recipient-'. $i]['to'] = sanitize_email($input['recipient-'. $i]['to']);
				
				$empty_to = false;
				
			}
			
		} else {
			
			$input['recipient-'. $i]['to'] = null;
			
		}
		
		if (isset($input['recipient-'. $i]['from'])) {
			
			if (!empty($input['recipient-'. $i]['from'])) { 
				
				$input['recipient-'. $i]['from'] = sanitize_email($input['recipient-'. $i]['from']);
				
				$empty_from = false;
				
			}
			
		} else {
			
			$input['recipient-'. $i]['from'] = null;
			
		}
		
		if ($empty_name && $empty_to && $empty_from) {
			
			unset($input['recipient-'. $i]);
			
		}
		
	}
	
	return $input;
	
}

function contactformx_validate_fields($input) {
	
	if (isset($input['display-fields'])) {
		
		$fields = contactformx_field_options();
		
		if (is_array($input['display-fields'])) {
			
			foreach($input['display-fields'] as $key => $value) {
				
				if (!array_key_exists($key, $fields)) $input['display-fields'][$key] = null;
				
			}
			
		} else {
			
			$input['display-fields'] = null;
			
		}
		
	} else {
		
		$input['display-fields'] = null;
		
	}
	
	return $input;
	
}


function contactformx_validate_options($input) {
	
	//
	
	if (isset($input['submit-button'])) $input['submit-button'] = esc_attr(sanitize_text_field($input['submit-button']));
	else $input['submit-button'] = null;
	
	if (isset($input['reset-button'])) $input['reset-button'] = esc_attr(sanitize_text_field($input['reset-button']));
	else $input['reset-button'] = null;
	
	if (isset($input['field-carbon-label'])) $input['field-carbon-label'] = esc_attr(sanitize_text_field($input['field-carbon-label']));
	else $input['field-carbon-label'] = null;
	
	
	if (isset($input['field-name-placeholder'])) $input['field-name-placeholder'] = esc_attr(sanitize_text_field($input['field-name-placeholder']));
	else $input['field-name-placeholder'] = null;
	
	if (isset($input['field-name-label'])) $input['field-name-label'] = esc_attr(sanitize_text_field($input['field-name-label']));
	else $input['field-name-label'] = null;
	
	
	if (isset($input['field-website-placeholder'])) $input['field-website-placeholder'] = esc_attr(sanitize_text_field($input['field-website-placeholder']));
	else $input['field-website-placeholder'] = null;
	
	if (isset($input['field-website-label'])) $input['field-website-label'] = esc_attr(sanitize_text_field($input['field-website-label']));
	else $input['field-website-label'] = null;
	
	
	if (isset($input['field-email-placeholder'])) $input['field-email-placeholder'] = esc_attr(sanitize_text_field($input['field-email-placeholder']));
	else $input['field-email-placeholder'] = null;
	
	if (isset($input['field-email-label'])) $input['field-email-label'] = esc_attr(sanitize_text_field($input['field-email-label']));
	else $input['field-email-label'] = null;
	
	
	if (isset($input['field-subject-placeholder'])) $input['field-subject-placeholder'] = esc_attr(sanitize_text_field($input['field-subject-placeholder']));
	else $input['field-subject-placeholder'] = null;
	
	if (isset($input['field-subject-label'])) $input['field-subject-label'] = esc_attr(sanitize_text_field($input['field-subject-label']));
	else $input['field-subject-label'] = null;
	
	if (isset($input['default-subject'])) $input['default-subject'] = esc_attr(sanitize_text_field($input['default-subject']));
	else $input['default-subject'] = null;
	
	
	if (isset($input['field-message-placeholder'])) $input['field-message-placeholder'] = esc_attr(sanitize_text_field($input['field-message-placeholder']));
	else $input['field-message-placeholder'] = null;
	
	if (isset($input['field-message-label'])) $input['field-message-label'] = esc_attr(sanitize_text_field($input['field-message-label']));
	else $input['field-message-label'] = null;
	
	
	if (isset($input['field-challenge-placeholder'])) $input['field-challenge-placeholder'] = esc_attr(sanitize_text_field($input['field-challenge-placeholder']));
	else $input['field-challenge-placeholder'] = null;
	
	if (isset($input['field-challenge-label'])) $input['field-challenge-label'] = esc_attr(sanitize_text_field($input['field-challenge-label']));
	else $input['field-challenge-label'] = null;
	
	if (isset($input['challenge-answer'])) $input['challenge-answer'] = esc_attr(sanitize_text_field($input['challenge-answer']));
	else $input['challenge-answer'] = null;
	
	if (!isset($input['challenge-case'])) $input['challenge-case'] = null;
	$input['challenge-case'] = ($input['challenge-case'] == 1 ? 1 : 0);
	
	
	if (isset($input['field-recaptcha-label'])) $input['field-recaptcha-label'] = esc_attr(sanitize_text_field($input['field-recaptcha-label']));
	else $input['field-recaptcha-label'] = null;
	
	if (isset($input['recaptcha-public'])) $input['recaptcha-public'] = esc_attr(sanitize_text_field($input['recaptcha-public']));
	else $input['recaptcha-public'] = null;
	
	if (isset($input['recaptcha-private'])) $input['recaptcha-private'] = esc_attr(sanitize_text_field($input['recaptcha-private']));
	else $input['recaptcha-private'] = null;
	
	if (!isset($input['recaptcha-theme'])) $input['recaptcha-theme'] = null;
	$input['recaptcha-theme'] = ($input['recaptcha-theme'] == 1 ? 1 : 0);
	
	if (!isset($input['recaptcha-version'])) $input['recaptcha-version'] = null;
	if (!array_key_exists($input['recaptcha-version'], contactformx_recaptcha_version_options())) $input['recaptcha-version'] = null;
	
	
	if (isset($input['field-custom-placeholder'])) $input['field-custom-placeholder'] = esc_attr(sanitize_text_field($input['field-custom-placeholder']));
	else $input['field-custom-placeholder'] = null;
	
	if (isset($input['field-custom-label'])) $input['field-custom-label'] = esc_attr(sanitize_text_field($input['field-custom-label']));
	else $input['field-custom-label'] = null;
	
	
	if (isset($input['field-agree-label'])) $input['field-agree-label'] = esc_attr(sanitize_text_field($input['field-agree-label']));
	else $input['field-agree-label'] = null;
	
	if (isset($input['field-agree-desc'])) $input['field-agree-desc'] = wp_kses_post($input['field-agree-desc']);
	else $input['field-agree-desc'] = null;
	
	
	if (isset($input['success-message'])) $input['success-message'] = wp_kses_post($input['success-message']);
	else $input['success-message'] = null;
	
	if (isset($input['error-required'])) $input['error-required'] = wp_kses_post($input['error-required']);
	else $input['error-required'] = null;
	
	if (isset($input['error-invalid'])) $input['error-invalid'] = wp_kses_post($input['error-invalid']);
	else $input['error-invalid'] = null;
	
	if (isset($input['error-challenge'])) $input['error-challenge'] = wp_kses_post($input['error-challenge']);
	else $input['error-challenge'] = null;
	
	if (isset($input['error-recaptcha'])) $input['error-recaptcha'] = wp_kses_post($input['error-recaptcha']);
	else $input['error-recaptcha'] = null;
	
	if (isset($input['error-agree'])) $input['error-agree'] = wp_kses_post($input['error-agree']);
	else $input['error-agree'] = null;
	
	
	if (isset($input['custom-before-form'])) $input['custom-before-form'] = wp_kses_post($input['custom-before-form']);
	else $input['custom-before-form'] = null;
	
	if (isset($input['custom-after-form'])) $input['custom-after-form'] = wp_kses_post($input['custom-after-form']);
	else $input['custom-after-form'] = null;
	
	if (isset($input['custom-before-results'])) $input['custom-before-results'] = wp_kses_post($input['custom-before-results']);
	else $input['custom-before-results'] = null;
	
	if (isset($input['custom-after-results'])) $input['custom-after-results'] = wp_kses_post($input['custom-after-results']);
	else $input['custom-after-results'] = null;
	
	//
	
	if (!isset($input['enable-custom-style'])) $input['enable-custom-style'] = null;
	if (!array_key_exists($input['enable-custom-style'], contactformx_custom_style_options())) $input['enable-custom-style'] = null;
	
	if (isset($input['custom-style-default'])) $input['custom-style-default'] = wp_strip_all_tags($input['custom-style-default']);
	else $input['custom-style-default'] = null;
	
	if (isset($input['custom-style-classic'])) $input['custom-style-classic'] = wp_strip_all_tags($input['custom-style-classic']);
	else $input['custom-style-classic'] = null;
	
	if (isset($input['custom-style-micro'])) $input['custom-style-micro'] = wp_strip_all_tags($input['custom-style-micro']);
	else $input['custom-style-micro'] = null;
	
	if (isset($input['custom-style-synthetic'])) $input['custom-style-synthetic'] = wp_strip_all_tags($input['custom-style-synthetic']);
	else $input['custom-style-synthetic'] = null;
	
	if (isset($input['custom-style-dark'])) $input['custom-style-dark'] = wp_strip_all_tags($input['custom-style-dark']);
	else $input['custom-style-dark'] = null;
	
	//
	
	if (!isset($input['display-success'])) $input['display-success'] = null;
	if (!array_key_exists($input['display-success'], contactformx_display_success_options())) $input['display-success'] = null;
	
	if (isset($input['display-url'])) $input['display-url'] = wp_filter_nohtml_kses($input['display-url']);
	else $input['display-url'] = null;
	
	if (!isset($input['enable-shortcode-widget'])) $input['enable-shortcode-widget'] = null;
	$input['enable-shortcode-widget'] = ($input['enable-shortcode-widget'] == 1 ? 1 : 0);
	
	if (!isset($input['email-message-extra'])) $input['email-message-extra'] = null;
	$input['email-message-extra'] = ($input['email-message-extra'] == 1 ? 1 : 0);
	
	if (!isset($input['mail-function'])) $input['mail-function'] = null;
	$input['mail-function'] = ($input['mail-function'] == 1 ? 1 : 0);
	
	if (!isset($input['enable-data-collection'])) $input['enable-data-collection'] = null;
	$input['enable-data-collection'] = ($input['enable-data-collection'] == 1 ? 1 : 0);
	
	if (!isset($input['disable-database-storage'])) $input['disable-database-storage'] = null;
	$input['disable-database-storage'] = ($input['disable-database-storage'] == 1 ? 1 : 0);
	
	if (!isset($input['disable-dash-widget'])) $input['disable-dash-widget'] = null;
	$input['disable-dash-widget'] = ($input['disable-dash-widget'] == 1 ? 1 : 0);
	
	if (!isset($input['display-dash-widget'])) $input['display-dash-widget'] = null;
	$input['display-dash-widget'] = ($input['display-dash-widget'] == 1 ? 1 : 0);
	
	if (!isset($input['enable-powered-by'])) $input['enable-powered-by'] = null;
	$input['enable-powered-by'] = ($input['enable-powered-by'] == 1 ? 1 : 0);
	
	if (isset($input['reset-dash-widget'])) unset($input['reset-dash-widget']);
	if (isset($input['reset-options'])) unset($input['reset-options']);
	
	return $input;
	
}

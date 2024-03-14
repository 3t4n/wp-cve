<?php // Contact Form X Form

if (!defined('ABSPATH')) exit;

function contactformx() {
	
	$output  = '<!-- Contact Form X -->';
	
	$output .= contactformx_noscript();
	
	$output .= '<div id="cfx" class="cfx">';
	
	$output .= '<div class="cfx-response"></div>';
	
	$output .= contactformx_before_form();
	
	$output .= '<div class="cfx-form">';
	
	$output .= contactformx_fields();
	
	$output .= contactformx_get_submit();
	
	$output .= '</div>';
	
	$output .= contactformx_after_form();
	
	$output .= contactformx_get_reset();
	
	$output .= contactformx_powered_by();
	
	$output .= '</div>';
	
	return apply_filters('contactformx_form', $output);
	
}

function contactformx_fields() {
	
	$options_form = contactformx_options('form');
	
	$fields = isset($options_form['display-fields']) ? $options_form['display-fields'] : array();
	
	$output = '';
	
	foreach ($fields as $id => $field) {
		
		$display = isset($field['display']) ? $field['display'] : null;
		
		if ($display === 'show' || $display === 'optn') {
			
			if ($id === 'message') {
				
				$output .= contactformx_get_textarea($id, $display);
				
			} elseif ($id === 'carbon' || $id === 'agree') {
				
				$output .= contactformx_get_checkbox($id, $display);
				
			} elseif ($id === 'recaptcha') {
				
				$output .= (contactformx_recaptcha_version() == 2) ? contactformx_get_recaptcha_v2($id, $display) : '';
				
			} else {
				
				$output .= contactformx_get_input($id, $display);
				
			}
			
		} elseif ($display === 'alt' && $id === 'carbon') {
			
			$output .= contactformx_get_checkbox_hidden($id);
			
		}
		
	}
	
	return $output;
	
}

function contactformx_display_results($array) {
	
	if (!empty($array['errors'])) {
		
		 $array['errors'] = '<div class="cfx-errors">'. $array['errors'] .'</div>';
		
	} else {
		
		$display_extra = '';
		
		if (contactformx_display_success() === 'form') {
			
			$array['display'] = 'form';
			
		} elseif (contactformx_display_success() === 'extra' || contactformx_display_success() === 'extra-reset') {
			
			$display_extra = contactformx_display_success_extra($array);
			
		}
		
		$array['success']  = contactformx_before_results();
		$array['success'] .= '<div class="cfx-success">'. contactformx_success_message() .'</div>'. $display_extra;
		$array['success'] .= contactformx_after_results();
		
	}
	
	return $array;
	
}

function contactformx_display_success_extra($array) {
	
	$options_form = contactformx_options('form');
	
	$display_subject = isset($options_form['display-fields']['subject']['display']) ? $options_form['display-fields']['subject']['display'] : null;
	$display_carbon  = isset($options_form['display-fields']['carbon']['display'])  ? $options_form['display-fields']['carbon']['display']  : null;
	$display_agree   = isset($options_form['display-fields']['agree']['display'])   ? $options_form['display-fields']['agree']['display']   : null;
	
	$options_customize = contactformx_options('customize');
	
	$fields = array('name', 'website', 'email', 'subject', 'custom', 'agree', 'carbon', 'message');
	
	$output = '<div class="cfx-extra"><pre><code>';
	
	$output .= esc_html__('Message Summary', 'contact-form-x') . "\n";
	
	$output .= contactformx_get_date() ."\n\n";
	
	foreach ($fields as $field) {
		
		${$field} = isset($array[$field]) ? $array[$field] : '';
		
		if (${$field}) {
			
			${$field} = ($field === 'message') ? "\n\n" . htmlentities(${$field}, ENT_QUOTES, get_option('blog_charset', 'UTF-8')) : esc_html(${$field});
			
			$label = isset($options_customize['field-'. $field .'-label']) ? $options_customize['field-'. $field .'-label'] : ucfirst($field);
			
			if ($field === 'carbon') {
				
				if ($display_carbon !== 'hide') {
					
					$output .= esc_html__('Carbon Copy: ', 'contact-form-x');
					
					$output .= (${$field} === 'true') ? esc_html__('True', 'contact-form-x') ."\n" : esc_html__('False', 'contact-form-x') . "\n";
					
				}
				
			} elseif ($field === 'agree') {
				
				if ($display_agree !== 'hide') {
					
					$output .= esc_html__('Agree to Terms: ', 'contact-form-x');
					
					$output .= (${$field} === 'true') ? esc_html__('True', 'contact-form-x') ."\n" : esc_html__('False', 'contact-form-x') . "\n";
					
				}
				
			} elseif ($field === 'subject') {
				
				if ($display_subject !== 'hide') {
					
					$output .= esc_html($label) .': '. ${$field} ."\n";
					
				}
				
			} else {
				
				$output .= esc_html($label) .': '. ${$field} ."\n";
				
			}
			
		}
		
	}
	
	$output .= '</code></pre></div>';
	
	return apply_filters('contactformx_extra', $output);
	
}

function contactformx_get_input($id, $display) {
	
	$options = contactformx_options('customize');
	
	$label       = isset($options['field-'. $id .'-label'])       ? $options['field-'. $id .'-label']       : '';
	$placeholder = isset($options['field-'. $id .'-placeholder']) ? $options['field-'. $id .'-placeholder'] : '';
	
	$required = ($display === 'show') ? ' required data-required="true"' : '';
	
	$type = 'text';
	
	if ($id === 'email') $type = 'email';
	if ($id === 'website') $type = 'url';
	
	$autocomp = 'on';
	
	if (in_array($id, array('name', 'website', 'email'))) {
		
		$autocomp = ($id === 'website') ? 'url' : $id;
		
	}
	
	$id = 'cfx-'. $id;
	
	$output  = '<fieldset class="cfx-input '. esc_attr($id) .'">';
	$output .= '<label for="'. esc_attr($id) .'">'. esc_html($label) .'</label> ';
	$output .= '<input id="'. esc_attr($id) .'" name="'. esc_attr($id) .'" type="'. esc_attr($type) .'" value="" size="40" maxlength="100" placeholder="'. esc_attr($placeholder) .'"'. $required .' autocomplete="'. $autocomp .'">';
	$output .= '</fieldset>';
	
	return $output;
	
}

function contactformx_get_textarea($id, $display) {
	
	$options = contactformx_options('customize');
	
	$label       = isset($options['field-'. $id .'-label'])       ? $options['field-'. $id .'-label']       : '';
	$placeholder = isset($options['field-'. $id .'-placeholder']) ? $options['field-'. $id .'-placeholder'] : '';
	
	$required = ($display === 'show') ? ' required data-required="true"' : '';
	
	$maxlength = apply_filters('contactformx_textarea_maxlength', false);
	
	$maxlength = $maxlength ? ' maxlength="'. $maxlength .'"' : '';
	
	$id = 'cfx-'. $id;
	
	$output  = '<fieldset class="cfx-textarea '. esc_attr($id) .'">';
	$output .= '<label for="'. esc_attr($id) .'">'. esc_html($label) .'</label> ';
	$output .= '<textarea id="'. esc_attr($id) .'" name="'. esc_attr($id) .'" cols="50" rows="10" placeholder="'. esc_attr($placeholder) .'"'. $required . $maxlength .'></textarea>';
	$output .= '</fieldset>';
	
	return $output;
	
}

function contactformx_get_checkbox($id, $display) {
	
	$options = contactformx_options('customize');
	
	$label = isset($options['field-'. $id .'-label']) ? $options['field-'. $id .'-label'] : '';
	$desc  = isset($options['field-'. $id .'-desc'])  ? $options['field-'. $id .'-desc']  : '';
	
	$required = ($display === 'show') ? ' required data-required="true"' : '';
	
	$id = 'cfx-'. $id;
	
	$output  = '<fieldset class="cfx-checkbox '. esc_attr($id) .'">';
	$output .= '<input id="'. esc_attr($id) .'" name="'. esc_attr($id) .'" type="checkbox" value=""'. $required .'> ';
	$output .= '<label for="'. esc_attr($id) .'">'. $label .'</label> '. $desc;
	$output .= '</fieldset>';
	
	return $output;
	
}

function contactformx_get_checkbox_hidden($id) {
	
	$id = 'cfx-'. $id;
	
	$output = '<input id="'. esc_attr($id) .'" name="'. esc_attr($id) .'" type="hidden" value="true">';
	
	return $output;
	
}

function contactformx_get_recaptcha_v2($id, $display) {
	
	$options = contactformx_options('customize');
	
	$label = isset($options['field-'. $id .'-label']) ? $options['field-'. $id .'-label'] : '';
	
	$recaptcha_public  = isset($options['recaptcha-public'])  ? $options['recaptcha-public']  : '';
	$recaptcha_private = isset($options['recaptcha-private']) ? $options['recaptcha-private'] : '';
	$recaptcha_theme   = isset($options['recaptcha-theme'])   ? $options['recaptcha-theme']   : false;
	
	$recaptcha_theme = $recaptcha_theme ? 'dark' : 'light';
	
	$id = 'cfx-'. $id;
	
	$output  = '<fieldset class="'. esc_attr($id) .'">';
	$output .= '<label for="'. esc_attr($id) .'">'. esc_html($label) .'</label>';
	
	if (!empty($recaptcha_public) && !empty($recaptcha_private)) {
		
		$output .= '<div id="'. esc_attr($id) .'" class="g-recaptcha" data-sitekey="'. esc_attr($recaptcha_public) .'" data-theme="'. esc_attr($recaptcha_theme) .'"></div>';
		
	} else {
		
		$output .= '<div id="'. esc_attr($id) .'">'. esc_html__('reCaptcha disabled: keys invalid or missing.', 'contact-form-x') .'</div>';
		
	}
	
	$output .= '</fieldset>';
	
	return $output;
	
}

function contactformx_get_submit() {
	
	$options = contactformx_options('customize');
	
	$submit = isset($options['submit-button']) ? $options['submit-button'] : __('Send Message', 'contact-form-x');
	
	$output = '<button class="cfx-button cfx-submit">'. esc_html($submit) .'</button>';
	
	return $output;
	
}

function contactformx_get_reset() {
	
	$options_customize = contactformx_options('customize');
	
	$options_advanced  = contactformx_options('advanced');
	
	$reset = isset($options_customize['reset-button']) ? $options_customize['reset-button'] : __('Reset Contact Form', 'contact-form-x');
	
	$display = isset($options_advanced['display-success']) ? $options_advanced['display-success'] : 'basic';
	
	$output = '';
	
	if ($display === 'basic-reset' || $display === 'extra-reset') {
		
		$output = '<button class="cfx-button cfx-reset">'. esc_html($reset) .'</button>';
		
	}
	
	return $output;
	
}

function contactformx_before_form() {
	
	$options = contactformx_options('customize');
	
	$output = '';
	
	if (isset($options['custom-before-form']) && !empty($options['custom-before-form'])) {
		
		$output .= '<div class="cfx-before-form">'. $options['custom-before-form'] .'</div>';
		
	}
	
	return $output;
	
}

function contactformx_after_form() {
	
	$options = contactformx_options('customize');
	
	$output = '';
	
	if (isset($options['custom-after-form']) && !empty($options['custom-after-form'])) {
		
		$output .= '<div class="cfx-after-form">'. $options['custom-after-form'] .'</div>';
		
	}
	
	return $output;
	
}

function contactformx_before_results() {
	
	$options = contactformx_options('customize');
	
	$output = '';
	
	if (isset($options['custom-before-results']) && !empty($options['custom-before-results'])) {
		
		$output .= '<div class="cfx-before-results">'. $options['custom-before-results'] .'</div>';
		
	}
	
	return $output;
	
}

function contactformx_after_results() {
	
	$options = contactformx_options('customize');
	
	$output = '';
	
	if (isset($options['custom-after-results']) && !empty($options['custom-after-results'])) {
		
		$output .= '<div class="cfx-after-results">'. $options['custom-after-results'] .'</div>';
		
	}
	
	return $output;
	
}

function contactformx_noscript() {
	
	$output = '<noscript class="cfx-noscript-wrap"><p id="cfx-noscript" class="cfx-noscript">'. esc_html__('Please enable JavaScript to use the contact form.', 'contact-form-x') .'</p></noscript>';
	
	return apply_filters('contactformx_noscript', $output);
	
}

function contactformx_powered_by() {
	
	$options = contactformx_options('advanced');
	
	$output = '';
	
	if (isset($options['enable-powered-by']) && !empty($options['enable-powered-by'])) {
		
		$output .= '<div class="cfx-powered-by">';
		$output .= 'Powered by <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/contact-form-x/" ';
		$output .= 'title="Get Contact Form X @ the WP Plugin Directory">Contact Form X</a>';
		$output .= '</div>';
		
	}
	
	return apply_filters('contactformx_powered_by', $output);
	
}

<?php // Contact Form X Settings Callbacks

if (!defined('ABSPATH')) exit;

function contactformx_settings_email() {
	
	echo '<p>'. esc_html__('Configure email information.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_form() {
	
	echo '<p>'. esc_html__('Configure form fields.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_customize() {
	
	echo '<p>'. esc_html__('Customize form elements.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_name() {
	
	echo '<p>'. esc_html__('Customize the Name field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_website() {
	
	echo '<p>'. esc_html__('Customize the Website field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_email() {
	
	echo '<p>'. esc_html__('Customize the Email field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_subject() {
	
	echo '<p>'. esc_html__('Customize the Subject field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_custom() {
	
	echo '<p>'. esc_html__('Customize the Custom field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_message() {
	
	echo '<p>'. esc_html__('Customize the Message field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_challenge() {
	
	echo '<p>'. esc_html__('Customize the Challenge Question field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_recaptcha() {
	
	echo '<p>'. esc_html__('Customize the Google reCaptcha field.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_agree() {
	
	echo '<p>'. esc_html__('Customize Agree to Terms.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_success() {
	
	echo '<p>'. esc_html__('Customize success and error messages.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_section_content() {
	
	echo '<p>'. esc_html__('Add custom text and/or markup.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_appearance() {
	
	echo '<p>'. esc_html__('Customize form appearance.', 'contact-form-x') .'</p>';
	
}

function contactformx_settings_advanced() {
	
	echo '<p>'. esc_html__('Customize advanced features.', 'contact-form-x') .'</p>';
	
}

function contactformx_callback_recipients($args) {
	
	$id = isset($args['id']) ? $args['id'] : '';
	
	$options = contactformx_options('email');
	
	$recip = isset($options[$id]) ? $options[$id] : null;
	
	$name = $to = $from = '';
	
	if ($recip) {
		
		$name = isset($recip['name']) ? $recip['name'] : '';
		$to   = isset($recip['to'])   ? $recip['to']   : '';
		$from = isset($recip['from']) ? $recip['from'] : '';
		
	}
	
	$input_name = 'contactformx_email['. $id .'][name]';
	$label_name = 'contactformx_email_'. $id .'_name';
	
	$input_to   = 'contactformx_email['. $id .'][to]';
	$label_to   = 'contactformx_email_'. $id .'_to';
	
	$input_from = 'contactformx_email['. $id .'][from]';
	$label_from = 'contactformx_email_'. $id .'_from]';
	
	echo '<div class="multi-fields">';
	
	echo '<div class="multi-fields-row">';
	echo '<input id="'. esc_attr($label_name) .'" name="'. esc_attr($input_name) .'" type="text" size="40" class="regular-text" value="'. esc_attr($name) .'" autocomplete="name">';
	echo '<label for="'. esc_attr($label_name) .'">'. esc_html__('Recipient Name', 'contact-form-x') .'</label>';
	echo '</div>';
	
	echo '<div class="multi-fields-row">';
	echo '<input id="'. esc_attr($label_to) .'" name="'. esc_attr($input_to) .'" type="text" size="40" class="regular-text" value="'. esc_attr($to) .'" autocomplete="email">';
	echo '<label for="'. esc_attr($label_to) .'">'. esc_html__('Email Address &ldquo;To&rdquo;', 'contact-form-x') .'</label>';
	echo '</div>';
	
	echo '<div class="multi-fields-row">';
	echo '<input id="'. esc_attr($label_from) .'" name="'. esc_attr($input_from) .'" type="text" size="40" class="regular-text" value="'. esc_attr($from) .'" autocomplete="email">';
	echo '<label for="'. esc_attr($label_from) .'">'. esc_html__('Email Address &ldquo;From&rdquo;', 'contact-form-x') .'</label>';
	echo '</div>';
	
	echo '</div>';
	
}

function contactformx_callback_fields($args) {
	
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$fields = isset($options['display-fields']) ? $options['display-fields'] : array();
	
	$output = '<div id="sortable">';
	
	foreach ($fields as $id => $array) {
		
		$display = isset($array['display']) ? $array['display'] : null;
		$label   = isset($array['label'])   ? $array['label']   : null;
		
		$selected_show = ($display === 'show') ? 'selected="selected"' : '';
		$selected_optn = ($display === 'optn') ? 'selected="selected"' : '';
		$selected_hide = ($display === 'hide') ? 'selected="selected"' : '';
		$selected_alt  = ($display === 'alt')  ? 'selected="selected"' : '';
		
		$name = 'contactformx_'. $section .'[display-fields]['. $id .']';
		$for  = 'contactformx_'. $section .'_display-fields_' . $id;
		
		$output .= '<div class="display-fields">';
		$output .= '<label for="'. esc_attr($for) .'">'. esc_html($label) .'</label> ';
		$output .= '<select id="'. esc_attr($for) .'" name="'. esc_attr($name) .'[display]">';
		$output .= '<option '. $selected_show .' value="show">'. esc_html__('Required', 'contact-form-x') .'</option>';
		$output .= '<option '. $selected_optn .' value="optn">'. esc_html__('Optional', 'contact-form-x') .'</option>';
		$output .= '<option '. $selected_hide .' value="hide">'. esc_html__('Disabled', 'contact-form-x') .'</option>';
		
		if ($id === 'carbon') $output .= '<option '. $selected_alt .' value="alt">'. esc_html__('Hidden', 'contact-form-x') .'</option>';
		
		$output .= '</select>';
		$output .= '<input name="'. esc_attr($name) .'[label]" type="hidden" value="'. esc_attr($label) .'">';
		$output .= '<span class="sort-handle" title="'. esc_attr__('Drag/drop field order', 'contact-form-x') .'">'. esc_html__('&uarr;&darr;', 'contact-form-x') .'</span>';
		$output .= '</div>';
		
	}
	
	$output .= '</div>';
	
	echo $output;
	
}

function contactformx_callback_text($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'contactformx_'. $section .'['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="text" size="40" class="regular-text" value="'. esc_attr($value) .'">';
	echo '<label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}

function contactformx_callback_number($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'contactformx_'. $section .'['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="number" min="1" size="40" class="small-text" value="'. esc_attr($value) .'"> ';
	echo '<label for="'. esc_attr($name) .'" class="inline-block">'. esc_html($label) .'</label>';
	
}

function contactformx_callback_textarea($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$allowed_tags = wp_kses_allowed_html('post');
	
	$rows  = ($section === 'appearance') ? '5' : '3';
	$cols  = ($section === 'appearance') ? '50' : '30';
	
	$name = 'contactformx_'. $section .'['. $id .']';
	
	echo '<textarea id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" rows="'. esc_attr($rows) .'" cols="'. esc_attr($cols) .'" class="large-text code">'. wp_kses(stripslashes_deep($value), $allowed_tags) .'</textarea>';
	echo '<label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}

function contactformx_callback_checkbox($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'contactformx_'. $section .'['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="checkbox" '. checked($value, 1, false) .' value="1"> ';
	echo '<label for="'. esc_attr($name) .'" class="inline">'. esc_html($label) .'</label>';
	
}

function contactformx_callback_select($args) {
	
	$id      = isset($args['id'])      ? $args['id']      : '';
	$label   = isset($args['label'])   ? $args['label']   : '';
	$section = isset($args['section']) ? $args['section'] : null;
	
	$options = contactformx_options($section);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'contactformx_'. $section .'['. $id .']';
	
	$options_array = array();
	
	if ($id === 'display-success') {
		
		$options_array = contactformx_display_success_options();
		
	} elseif ($id === 'enable-custom-style') {
		
		$options_array = contactformx_custom_style_options();
		
	} elseif ($id === 'recaptcha-version') {
		
		$options_array = contactformx_recaptcha_version_options();
		
	}
	
	echo '<select id="'. esc_attr($name) .'" name="'. esc_attr($name) .'">';
	
	foreach ($options_array as $option) {
		
		$option_value = isset($option['value']) ? $option['value'] : '';
		$option_label = isset($option['label']) ? $option['label'] : '';
		
		echo '<option '. selected($option_value, $value, false) .' value="'. esc_attr($option_value) .'">'. esc_attr($option_label) .'</option>';
	}
	echo '</select> <label for="'. esc_attr($name) .'" class="inline-block">'. esc_html($label) .'</label>';
	
}

function contactformx_callback_reset_widget($args) {
	
	$nonce = wp_create_nonce('contactformx_reset_widget');
	
	$href  = add_query_arg(array('reset-widget-verify' => $nonce), admin_url('options-general.php?page=contactformx'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Delete all email data from the database', 'contact-form-x');
	
	echo '<a class="cfx-reset-widget" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function contactformx_callback_reset_options($args) {
	
	$nonce = wp_create_nonce('contactformx_reset_options');
	
	$href  = add_query_arg(array('reset-options-verify' => $nonce), admin_url('options-general.php?page=contactformx'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Restore default plugin options', 'contact-form-x');
	
	echo '<a class="cfx-reset-options" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function contactformx_callback_rate($args) {
	
	$href  = 'https://wordpress.org/support/plugin/'. CONTACTFORMX_SLUG .'/reviews/?rate=5#new-post';
	$title = esc_attr__('Let others know about Contact Form X! A huge THANK YOU for your support!', 'contact-form-x');
	$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'contact-form-x');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="cfx-rate-plugin" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}

function contactformx_callback_support($args) {
	
	$href  = 'https://monzillamedia.com/donate.html';
	$title = esc_attr__('Donate via PayPal, credit card, or cryptocurrency', 'contact-form-x');
	$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a small donation&nbsp;&raquo;', 'contact-form-x');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="cfx-show-support" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
	
}

function contactformx_display_success_options() {
	
	$display_success = array(
		
		'basic' => array(
			'value' => 'basic',
			'label' => esc_html__('Success message only', 'contact-form-x')
		),
		'basic-reset' => array(
			'value' => 'basic-reset',
			'label' => esc_html__('Success message + reset button', 'contact-form-x')
		),
		'form' => array(
			'value' => 'form',
			'label' => esc_html__('Success message + form', 'contact-form-x')
		),
		'extra' => array(
			'value' => 'extra',
			'label' => esc_html__('Success message + extra info', 'contact-form-x')
		),
		'extra-reset' => array(
			'value' => 'extra-reset',
			'label' => esc_html__('Success message + extra info + reset button', 'contact-form-x')
		),
	);
	
	return $display_success;
	
}

function contactformx_custom_style_options() {
	
	$custom_style = array(
		
		'default' => array(
			'value' => 'default',
			'label' => esc_html__('Default', 'contact-form-x')
		),
		'classic' => array(
			'value' => 'classic',
			'label' => esc_html__('Classic', 'contact-form-x')
		),
		'micro' => array(
			'value' => 'micro',
			'label' => esc_html__('Micro', 'contact-form-x')
		),
		'synthetic' => array(
			'value' => 'synthetic',
			'label' => esc_html__('Synthetic', 'contact-form-x')
		),
		'dark' => array(
			'value' => 'dark',
			'label' => esc_html__('Dark', 'contact-form-x')
		),
		'none' => array(
			'value' => 'none',
			'label' => esc_html__('None (Disable)', 'contact-form-x')
		),
	);
	
	return $custom_style;
	
}

function contactformx_recaptcha_version_options() {
	
	$recaptcha_version = array(
		
		2 => array(
			'value' => 2,
			'label' => esc_html__('v2 (I&rsquo;m not a robot)', 'contact-form-x')
		),
		3 => array(
			'value' => 3,
			'label' => esc_html__('v3 (Hidden reCaptcha)', 'contact-form-x')
		),
	);
	
	return $recaptcha_version;
	
}

<?php // Contact Form X Register Settings

if (!defined('ABSPATH')) exit;

function contactformx_register_settings() {
	
	// add_settings_section( $id, $title, $callback, $page )
	// add_settings_field( $id, $title, $callback, $page, $section, $args )
	
	//
	register_setting('contactformx_email', 'contactformx_email', 'contactformx_validate_recipients');
	add_settings_section('email', '<span class="dashicons cfx-dashicons dashicons-email"></span> '. __('Email', 'contact-form-x'), 'contactformx_settings_email', 'contactformx_email');
	add_settings_field('number-recipients', __('Number of Recipients', 'contact-form-x'), 'contactformx_callback_number', 'contactformx_email', 'email', array('id' => 'number-recipients', 'section' => 'email', 'label' => esc_html__('Choose a number and click Save Changes', 'contact-form-x')));
	contactformx_register_recipients();
	
	//
	register_setting('contactformx_form', 'contactformx_form', 'contactformx_validate_fields');
	add_settings_section('form', '<span class="dashicons cfx-dashicons dashicons-editor-justify"></span> '. __('Form', 'contact-form-x'), 'contactformx_settings_form', 'contactformx_form');
	add_settings_field('display-fields',   __('Fields',  'contact-form-x'), 'contactformx_callback_fields',  'contactformx_form', 'form', array('id' => 'display-form', 'section' => 'form', 'label' => ''));
	
	//
	register_setting('contactformx_customize', 'contactformx_customize', 'contactformx_validate_options');
	add_settings_section('customize', '<span class="dashicons cfx-dashicons dashicons-admin-tools"></span> '. __('Customize', 'contact-form-x'), 'contactformx_settings_customize', 'contactformx_customize');
	add_settings_field('submit-button',      __('Submit Button',  'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'customize', array('id' => 'submit-button',      'section' => 'customize', 'label' => esc_html__('Default text for submit button',     'contact-form-x')));
	add_settings_field('reset-button',       __('Reset Button',   'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'customize', array('id' => 'reset-button',       'section' => 'customize', 'label' => esc_html__('Default text for the reset button',  'contact-form-x')));
	add_settings_field('field-carbon-label', __('Carbon Copy',    'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'customize', array('id' => 'field-carbon-label', 'section' => 'customize', 'label' => esc_html__('Label for the Carbon Copy checkbox', 'contact-form-x')));
	
	add_settings_section('name', __('Name Field', 'contact-form-x'), 'contactformx_settings_section_name', 'contactformx_customize');
	add_settings_field('field-name-label',       __('Label',       'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'name', array('id' => 'field-name-label',       'section' => 'customize', 'label' => esc_html__('Label for the Name field',       'contact-form-x')));
	add_settings_field('field-name-placeholder', __('Placeholder', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'name', array('id' => 'field-name-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Name field', 'contact-form-x')));
	
	add_settings_section('website', __('Website Field', 'contact-form-x'), 'contactformx_settings_section_website', 'contactformx_customize');
	add_settings_field('field-website-label',       __('Label',       'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'website', array('id' => 'field-website-label',       'section' => 'customize', 'label' => esc_html__('Label for the Website field',       'contact-form-x')));
	add_settings_field('field-website-placeholder', __('Placeholder', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'website', array('id' => 'field-website-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Website field', 'contact-form-x')));
	
	add_settings_section('email', __('Email Field', 'contact-form-x'), 'contactformx_settings_section_email', 'contactformx_customize');
	add_settings_field('field-email-label',       __('Label',       'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'email', array('id' => 'field-email-label',       'section' => 'customize', 'label' => esc_html__('Label for the Email field',       'contact-form-x')));
	add_settings_field('field-email-placeholder', __('Placeholder', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'email', array('id' => 'field-email-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Email field', 'contact-form-x')));
	
	add_settings_section('subject', __('Subject Field', 'contact-form-x'), 'contactformx_settings_section_subject', 'contactformx_customize');
	add_settings_field('field-subject-label',       __('Label',           'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'subject', array('id' => 'field-subject-label',       'section' => 'customize', 'label' => esc_html__('Label for the Subject field',       'contact-form-x')));
	add_settings_field('field-subject-placeholder', __('Placeholder',     'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'subject', array('id' => 'field-subject-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Subject field', 'contact-form-x')));
	add_settings_field('default-subject',           __('Default Subject', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'subject', array('id' => 'default-subject',           'section' => 'customize', 'label' => esc_html__('Default subject line',              'contact-form-x')));
	
	add_settings_section('message', __('Message Field', 'contact-form-x'), 'contactformx_settings_section_message', 'contactformx_customize');
	add_settings_field('field-subject-label',       __('Label',       'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'message', array('id' => 'field-message-label',       'section' => 'customize', 'label' => esc_html__('Label for the Message field',       'contact-form-x')));
	add_settings_field('field-subject-placeholder', __('Placeholder', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'message', array('id' => 'field-message-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Message field', 'contact-form-x')));
	
	add_settings_section('challenge', __('Challenge Question', 'contact-form-x'), 'contactformx_settings_section_challenge', 'contactformx_customize');
	add_settings_field('field-challenge-placeholder', __('Placeholder',        'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'challenge', array('id' => 'field-challenge-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Challenge field',                          'contact-form-x')));
	add_settings_field('field-challenge-label',       __('Challenge Question', 'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'challenge', array('id' => 'field-challenge-label',       'section' => 'customize', 'label' => esc_html__('Question that must be answered correctly (used as the label)', 'contact-form-x')));
	add_settings_field('challenge-answer',            __('Challenge Answer',   'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'challenge', array('id' => 'challenge-answer',            'section' => 'customize', 'label' => esc_html__('The *only* correct answer to the challenge question',          'contact-form-x')));
	add_settings_field('challenge-case',              __('Case Sensitivity',   'contact-form-x'), 'contactformx_callback_checkbox', 'contactformx_customize', 'challenge', array('id' => 'challenge-case',              'section' => 'customize', 'label' => esc_html__('Correct answer should be case-sensitive',                      'contact-form-x')));
	
	add_settings_section('recaptcha', __('Google reCaptcha', 'contact-form-x'), 'contactformx_settings_section_recaptcha', 'contactformx_customize');
	add_settings_field('field-recaptcha-label', __('Label',       'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'recaptcha', array('id' => 'field-recaptcha-label', 'section' => 'customize', 'label' => esc_html__('Label for the reCaptcha field',   'contact-form-x')));
	add_settings_field('recaptcha-public',      __('Public Key',  'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'recaptcha', array('id' => 'recaptcha-public',      'section' => 'customize', 'label' => esc_html__('Google reCaptcha Public Key',     'contact-form-x')));
	add_settings_field('recaptcha-private',     __('Private Key', 'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'recaptcha', array('id' => 'recaptcha-private',     'section' => 'customize', 'label' => esc_html__('Google reCaptcha Private Key',    'contact-form-x')));
	add_settings_field('recaptcha-theme',       __('Theme',       'contact-form-x'), 'contactformx_callback_checkbox', 'contactformx_customize', 'recaptcha', array('id' => 'recaptcha-theme',       'section' => 'customize', 'label' => esc_html__('Enable dark theme for reCaptcha', 'contact-form-x')));
	add_settings_field('recaptcha-version',     __('Version',     'contact-form-x'), 'contactformx_callback_select',   'contactformx_customize', 'recaptcha', array('id' => 'recaptcha-version',     'section' => 'customize', 'label' => esc_html__('Choose reCaptcha version',        'contact-form-x')));
	
	add_settings_section('custom', __('Custom Field', 'contact-form-x'), 'contactformx_settings_section_custom', 'contactformx_customize');
	add_settings_field('field-custom-label',       __('Label',       'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'custom', array('id' => 'field-custom-label',       'section' => 'customize', 'label' => esc_html__('Label for the Custom field',       'contact-form-x')));
	add_settings_field('field-custom-placeholder', __('Placeholder', 'contact-form-x'), 'contactformx_callback_text', 'contactformx_customize', 'custom', array('id' => 'field-custom-placeholder', 'section' => 'customize', 'label' => esc_html__('Placeholder for the Custom field', 'contact-form-x')));
	
	add_settings_section('agree', __('Agree to Terms', 'contact-form-x'), 'contactformx_settings_section_agree', 'contactformx_customize');
	add_settings_field('field-agree-label', __('Label',       'contact-form-x'), 'contactformx_callback_text',     'contactformx_customize', 'agree', array('id' => 'field-agree-label', 'section' => 'customize', 'label' => esc_html__('Label for the Agree to Terms checkbox',                  'contact-form-x')));
	add_settings_field('field-agree-desc',  __('Description', 'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'agree', array('id' => 'field-agree-desc',  'section' => 'customize', 'label' => esc_html__('Additional information for the Agree to Terms checkbox', 'contact-form-x')));
	
	add_settings_section('success', __('Success &amp; Error Messages', 'contact-form-x'), 'contactformx_settings_section_success', 'contactformx_customize');
	add_settings_field('success-message', __('Success Message',      'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'success-message', 'section' => 'customize', 'label' => esc_html__('Message displayed when the form is submitted successfully',  'contact-form-x')));
	add_settings_field('error-required',  __('Error Message',        'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'error-required',  'section' => 'customize', 'label' => esc_html__('Message displayed when a required field is empty',           'contact-form-x')));
	add_settings_field('error-invalid',   __('Email Error',          'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'error-invalid',   'section' => 'customize', 'label' => esc_html__('Message displayed if email address is not valid',            'contact-form-x')));
	add_settings_field('error-challenge', __('Challenge Error',      'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'error-challenge', 'section' => 'customize', 'label' => esc_html__('Message displayed when the challenge question is incorrect', 'contact-form-x')));
	add_settings_field('error-recaptcha', __('reCaptcha Error',      'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'error-recaptcha', 'section' => 'customize', 'label' => esc_html__('Message displayed when the reCaptcha is incorrect',          'contact-form-x')));
	add_settings_field('error-agree',     __('Agree to Terms Error', 'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'success', array('id' => 'error-agree',     'section' => 'customize', 'label' => esc_html__('Message displayed when user has not agreed to the terms',    'contact-form-x')));
	
	add_settings_section('content', __('Custom Content', 'contact-form-x'), 'contactformx_settings_section_content', 'contactformx_customize');
	add_settings_field('custom-before-form',    __('Before Form',    'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'content', array('id' => 'custom-before-form',    'section' => 'customize', 'label' => esc_html__('Optional markup to appear *before* the contact form',    'contact-form-x')));
	add_settings_field('custom-after-form',     __('After Form',     'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'content', array('id' => 'custom-after-form',     'section' => 'customize', 'label' => esc_html__('Optional markup to appear *after* the contact form',     'contact-form-x')));
	add_settings_field('custom-before-results', __('Before Results', 'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'content', array('id' => 'custom-before-results', 'section' => 'customize', 'label' => esc_html__('Optional markup to appear *before* the success message', 'contact-form-x')));
	add_settings_field('custom-after-results',  __('After Results',  'contact-form-x'), 'contactformx_callback_textarea', 'contactformx_customize', 'content', array('id' => 'custom-after-results',  'section' => 'customize', 'label' => esc_html__('Optional markup to appear *after* the success message',  'contact-form-x')));
	
	//
	register_setting('contactformx_appearance', 'contactformx_appearance', 'contactformx_validate_options');
	add_settings_section('appearance', '<span class="dashicons cfx-dashicons dashicons-admin-appearance"></span> '. __('Appearance', 'contact-form-x'), 'contactformx_settings_appearance', 'contactformx_appearance');
	add_settings_field('enable-custom-style',    __('Form Style', 'contact-form-x'),  'contactformx_callback_select',   'contactformx_appearance', 'appearance', array('id' => 'enable-custom-style',    'section' => 'appearance', 'label' => esc_html__('See the Help tab for screenshots and infos',    'contact-form-x')));
	add_settings_field('custom-style-default',   __('Default',    'contact-form-x'),  'contactformx_callback_textarea', 'contactformx_appearance', 'appearance', array('id' => 'custom-style-default',   'section' => 'appearance', 'label' => esc_html__('Clean fresh look fits with your theme',         'contact-form-x')));
	add_settings_field('custom-style-classic',   __('Classic',    'contact-form-x'),  'contactformx_callback_textarea', 'contactformx_appearance', 'appearance', array('id' => 'custom-style-classic',   'section' => 'appearance', 'label' => esc_html__('Clean and fresh, requires less vertical space', 'contact-form-x')));
	add_settings_field('custom-style-micro',     __('Micro',      'contact-form-x'),  'contactformx_callback_textarea', 'contactformx_appearance', 'appearance', array('id' => 'custom-style-micro',     'section' => 'appearance', 'label' => esc_html__('Clean and simple, fits well in small areas',    'contact-form-x')));
	add_settings_field('custom-style-synthetic', __('Synthetic',  'contact-form-x'),  'contactformx_callback_textarea', 'contactformx_appearance', 'appearance', array('id' => 'custom-style-synthetic', 'section' => 'appearance', 'label' => esc_html__('Complete form styles for fussy WP themes',      'contact-form-x')));
	add_settings_field('custom-style-dark',      __('Dark',       'contact-form-x'),  'contactformx_callback_textarea', 'contactformx_appearance', 'appearance', array('id' => 'custom-style-dark',      'section' => 'appearance', 'label' => esc_html__('Complete form styles for dark WP themes',       'contact-form-x')));
	
	//
	register_setting('contactformx_advanced', 'contactformx_advanced', 'contactformx_validate_options');
	add_settings_section('advanced', '<span class="dashicons cfx-dashicons dashicons-admin-generic"></span> '. __('Advanced', 'contact-form-x'), 'contactformx_settings_advanced', 'contactformx_advanced');
	add_settings_field('display-success',          __('Success Display',          'contact-form-x'), 'contactformx_callback_select',        'contactformx_advanced', 'advanced', array('id' => 'display-success',          'section' => 'advanced', 'label' => esc_html__('Display after successful form submission',                         'contact-form-x')));
	add_settings_field('display-url',              __('Targeted Loading',         'contact-form-x'), 'contactformx_callback_text',          'contactformx_advanced', 'advanced', array('id' => 'display-url',              'section' => 'advanced', 'label' => esc_html__('Limit loading of CSS &amp; JavaScript (see Help tab for details)', 'contact-form-x')));
	add_settings_field('enable-shortcode-widget',  __('Widget Shortcodes',        'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'enable-shortcode-widget',  'section' => 'advanced', 'label' => esc_html__('Enable shortcodes inside of WP widgets',                           'contact-form-x')));
	add_settings_field('mail-function',            __('Mail Function',            'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'mail-function',            'section' => 'advanced', 'label' => esc_html__('Use PHP mail() function instead of wp_mail()',                     'contact-form-x')));
	add_settings_field('email-message-extra',      __('Extra Email Info',         'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'email-message-extra',      'section' => 'advanced', 'label' => esc_html__('Include extra form information and user data with email message',  'contact-form-x')));
	add_settings_field('enable-data-collection',   __('Data Collection',          'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'enable-data-collection',   'section' => 'advanced', 'label' => esc_html__('Enable collection of identifiable user data (IP address, host, user agent, and referrer)',                'contact-form-x')));
	add_settings_field('disable-database-storage', __('Disable Database Storage', 'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'disable-database-storage', 'section' => 'advanced', 'label' => esc_html__('Disable storage of any/all email infos in database (leave unchecked if using the Dashboard Widget)',      'contact-form-x')));
	add_settings_field('disable-dash-widget',      __('Dashboard Widget',         'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'disable-dash-widget',      'section' => 'advanced', 'label' => esc_html__('Disable the dashboard widget for all users (leave unchecked to enable for Admin users only)',             'contact-form-x')));
	add_settings_field('display-dash-widget',      __('Dashboard Widget Access',  'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'display-dash-widget',      'section' => 'advanced', 'label' => esc_html__('Allow Admins and Editor-level users to view the dashboard widget (leave unchecked to allow only Admins)', 'contact-form-x')));
	add_settings_field('enable-powered-by',        __('Show Support',             'contact-form-x'), 'contactformx_callback_checkbox',      'contactformx_advanced', 'advanced', array('id' => 'enable-powered-by',        'section' => 'advanced', 'label' => esc_html__('Display "Powered by Contact Form X"',                              'contact-form-x')));
	add_settings_field('reset-dash-widget',        __('Reset Widget',             'contact-form-x'), 'contactformx_callback_reset_widget',  'contactformx_advanced', 'advanced', array('id' => 'reset-dash-widget',        'section' => 'advanced', 'label' => esc_html__('Delete all email data from the database',                          'contact-form-x')));
	add_settings_field('reset-options',            __('Reset Options',            'contact-form-x'), 'contactformx_callback_reset_options', 'contactformx_advanced', 'advanced', array('id' => 'reset-options',            'section' => 'advanced', 'label' => esc_html__('Restore default plugin options',                                   'contact-form-x')));
	add_settings_field('rate-plugin',              __('Rate Plugin',              'contact-form-x'), 'contactformx_callback_rate',          'contactformx_advanced', 'advanced', array('id' => 'rate-plugin',              'section' => 'advanced', 'label' => esc_html__('Show support with a 5-star rating&nbsp;&raquo;',                   'contact-form-x')));
	add_settings_field('show-support',             __('Show Support',             'contact-form-x'), 'contactformx_callback_support',       'contactformx_advanced', 'advanced', array('id' => 'show-support',             'section' => 'advanced', 'label' => esc_html__('Show support with a small donation&nbsp;&raquo;',                  'contact-form-x')));
	
}

function contactformx_register_recipients() {
	
	$options = contactformx_options('email');
	
	$limit = isset($options['number-recipients']) ? $options['number-recipients'] : 1;
	
	$nonce = wp_create_nonce('contactformx_delete_recipient');
	
	$array = array();
	
	$max = 0;
	$n   = 0;
	
	foreach(contactformx_get_recipients() as $i) {
		
		if ($i > $max) $max = $i;
		
		if ($n >= $limit) break; 
		
		$array[] = $i;
		
		$n++;
		
	}
	
	if ($max && $n) {
		
		$i = $max + 1;
		
		while ($n < intval($limit)) {
			
			$array[] = $i;
			
			$n++;
			$i++;
			
		}
		
	} else {
		
		foreach (range(1, $limit) as $number) $array[] = $number;
		
	}
	
	foreach ($array as $id) {
		
		$href = add_query_arg(array('delete-recipient-verify' => $nonce, 'recipient' => $id), admin_url('options-general.php?page=contactformx'));
			
		$title = '<div class="cfx-recipient-delete-label">'. esc_html__('Recipient', 'contact-form-x') .'</div> <a class="cfx-recipient-delete-link" href="'. esc_url($href) .'">'. esc_html__('Delete', 'contact-form-x') .'</a>';
				
		add_settings_field('recipient-'. esc_attr($id), $title, 'contactformx_callback_recipients', 'contactformx_email', 'email', array('id' => 'recipient-'. esc_attr($id), 'section' => 'email', 'label' => ''));
		
	}
	
	foreach ($options as $key => $value) {
		
		preg_match('/^recipient-([0-9])+$/i', $key, $matches);
		
		if (isset($matches[1]) && !in_array($matches[1], $array)) unset($options[$key]);
		
	}
	
	update_option('contactformx_email', $options);
	
}

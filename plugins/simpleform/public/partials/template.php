<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Default Form Template
 * Customitation guidelines:
 * After your modifications are done, you can change style using your personal stylesheet file by enabling the option in the settings page. 
**/

// Confirmation message after ajax submission
$form = '<div id="sform-confirmation'.$isuffix.'" class="sform-confirmation" tabindex="-1"></div>';

// Contact Form starts here:
$form .= '<form '.$cform_id.' method="post" '.$form_attribute.' class="sform '.$form_class.'" '.$attribute_form.'>';

// Contact Form top error message
$form .= $top_error;

// Name field
$name_input = $name_field == 'visible' || $name_field == 'registered' && is_user_logged_in() || $name_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group name '.$name_row_class.'">'.$name_field_label.'<div class="'.$wrap_class.'"><input type="text" name="sform-name" id="sform-name'.$isuffix.'" class="'.$name_class.'" value="'.$name_value.'" placeholder="'.$name_placeholder.'" '.$name_attribute.'><div id="name-error'.$isuffix.'" class="error-des"><span>'.$name_field_error.'</span></div></div></div>' : '';

// Last Name field
$lastname_input = $lastname_field == 'visible' || $lastname_field == 'registered' && is_user_logged_in() || $lastname_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group lastname '.$name_row_class.'">'.$lastname_field_label.'<div class="'.$wrap_class.'"><input type="text" name="sform-lastname" id="sform-lastname'.$isuffix.'" class="'.$lastname_class.'" value="'.$lastname_value.'" placeholder="'.$lastname_placeholder.'" '.$lastname_attribute.'><div id="lastname-error'.$isuffix.'" class="error-des"><span>'.$lastname_field_error.'</span></div></div></div>': ''; 

// Email field	     		
$email_input = $email_field == 'visible' || $email_field == 'registered' && is_user_logged_in() || $email_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group email '.$email_row_class.'">'.$email_field_label.'<div class="'.$wrap_class.'"><input type="email" name="sform-email" id="sform-email'.$isuffix.'" class="'.$email_class.'" value="'.$email_value.'" placeholder="'.$email_placeholder.'" '.$email_attribute.' ><div id="email-error'.$isuffix.'" class="error-des"><span>'.$email_field_error.'</span></div></div></div>' : '';

// Phone field
$phone_input = $phone_field == 'visible' || $phone_field == 'registered' && is_user_logged_in() || $phone_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group phone '.$email_row_class.'">'.$phone_field_label.'<div class="'.$wrap_class.'"><input type="tel" name="sform-phone" id="sform-phone'.$isuffix.'" class="'.$phone_class.'" value="'.$phone_value.'" placeholder="'.$phone_placeholder.'" '.$phone_attribute.'><div id="phone-error'.$isuffix.'" class="error-des"><span>'.$phone_field_error.'</span></div></div></div>' : ''; 

// Subject field
$subject_input = $subject_field == 'visible' || $subject_field == 'registered' && is_user_logged_in() || $subject_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group '.$row_label.'">'.$subject_field_label.'<div class="'.$wrap_class.'"><input type="text" name="sform-subject" id="sform-subject'.$isuffix.'" class="'.$subject_class.'" '.$subject_attribute.' value="'.$subject_value.'" placeholder="'.$subject_placeholder.'" ><div id="subject-error'.$isuffix.'" class="error-des"><span>'.$subject_field_error.'</span></div></div></div>' : '';

// Message field
$message_input = '<div class="sform-field-group '.$row_label.'">'.$message_field_label.'<div class="'.$wrap_class.'"><textarea name="sform-message" id="sform-message'.$isuffix.'" rows="10" type="textarea" class="'.$message_class.'" required '.$message_maxlength.' placeholder="'.$message_placeholder.'" '.$message_attribute.'>'.$message_value.'</textarea><div id="message-error'.$isuffix.'" class="error-des"><span>'.$message_field_error.'</span></div></div></div>';

// Consent field
$consent_input = $consent_field == 'visible' || $consent_field == 'registered' && is_user_logged_in() || $consent_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group checkbox '.$row_label.'"><input type="checkbox" name="sform-consent" id="sform-consent'.$isuffix.'" class="'.$consent_field_class.'" value="'.$consent_value.'" '.$consent_attribute.'><label for="sform-consent'.$isuffix.'" class="'.$consent_class.'"><span class="'.$checkmark_class.'"></span>'.$consent_label.$required_consent_label.'</label></div>' : '';

// Captcha field
$captcha_input = $captcha_field == 'visible' || $captcha_field == 'registered' && is_user_logged_in() || $captcha_field == 'anonymous' && ! is_user_logged_in() ? '<div class="sform-field-group '.$row_label.'" id="captcha-container'.$isuffix.'"><label for="sform-captcha'.$isuffix.'" '.$label_class.'>'.$captcha_label.$required_captcha_label.'</label><div id="captcha-field'.$isuffix.'" class="'.$captcha_class.'">'.$captcha_hidden.'<input id="captcha-question'.$isuffix.'" type="text" class="'.$captcha_question_class.'" readonly="readonly" tabindex="-1" value="'.$captcha_question.'" /><input type="number" id="sform-captcha'.$isuffix.'" name="sform-captcha" class="'.$captcha_answer_class.'" '.$captcha_attribute.' value="'.$captcha_value.'" /></div><div id="captcha-error'.$isuffix.'" class="captcha-error error-des '.$row_label.'"><span class="'.$captcha_error_class.'">'.$captcha_field_error.'</span></div></div>' : '';

$captcha_input = ! has_filter('recaptcha_challenge') ? $captcha_input : apply_filters('sform_captcha_field', $attributes, $settings, $data, $error_class, $captcha_input );

// Form fields assembling
$form .= $name_input . $lastname_input . $email_input . $phone_input . $subject_input . $message_input . $consent_input . $captcha_input . $hidden_fields;

// Contact Form bottom error message
$form .= $bottom_error;

// Submit field
$form .= '<div id="sform-submit-wrap'.$isuffix.'" class="'.$submit_class.'"><button name="submission" id="submission'.$isuffix.'" type="submit" class="'.$button_class.'">'.$submit_label.'</button>'.$animation.'</div></form>'; 

// Switch from displaying contact form to displaying success message if ajax submission is disabled
$contact_form = isset( $_GET['sending'] ) && $_GET['sending'] == 'success' && isset( $_GET['form'] ) && $_GET['form'] == $submission_id  ? $thank_you_message . $focus_confirmation : $form;
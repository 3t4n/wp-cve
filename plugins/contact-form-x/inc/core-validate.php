<?php // Contact Form X Validate

if (!defined('ABSPATH')) exit;

function contactformx_validate_form($array) {
	
	$options = contactformx_options('customize');
	
	$error_required  = isset($options['error-required'])  ? '<div class="cfx-error cfx-error-required">'  . $options['error-required']  .'</div>' : null;
	$error_invalid   = isset($options['error-invalid'])   ? '<div class="cfx-error cfx-error-invalid">'   . $options['error-invalid']   .'</div>' : null;
	$error_challenge = isset($options['error-challenge']) ? '<div class="cfx-error cfx-error-challenge">' . $options['error-challenge'] .'</div>' : null;
	$error_recaptcha = isset($options['error-recaptcha']) ? '<div class="cfx-error cfx-error-recaptcha">' . $options['error-recaptcha'] .'</div>' : null;
	$error_agree     = isset($options['error-agree'])     ? '<div class="cfx-error cfx-error-agree">'     . $options['error-agree']     .'</div>' : null;
	
	$errors    = isset($array['errors'])    ? $array['errors']    : '';
	$name      = isset($array['name'])      ? $array['name']      : '';
	$website   = isset($array['website'])   ? $array['website']   : '';
	$email     = isset($array['email'])     ? $array['email']     : '';
	$subject   = isset($array['subject'])   ? $array['subject']   : '';
	$message   = isset($array['message'])   ? $array['message']   : '';
	$challenge = isset($array['challenge']) ? $array['challenge'] : '';
	$recaptcha = isset($array['recaptcha']) ? $array['recaptcha'] : '';
	$custom    = isset($array['custom'])    ? $array['custom']    : '';
	$carbon    = isset($array['carbon'])    ? $array['carbon']    : false;
	$agree     = isset($array['agree'])     ? $array['agree']     : false;
	
	//
	
	$errors .= contactformx_validate_input(array($name, $email, $subject));
	
	$required = false;
	
	foreach (array('name', 'website', 'subject', 'message', 'custom') as $field) {
		
		if (contactformx_is_error_required($field, ${$field})) {
			
			$required = true;
			
			break;
			
		}
		
	}
	
	if (contactformx_is_error_checkbox('carbon', $carbon)) $required = true;
	
	if (contactformx_is_error_checkbox('agree', $agree)) $errors .= $error_agree;
	
	if (contactformx_is_error_challenge($challenge)) $errors .= $error_challenge;
	
	if (!contactformx_is_valid_email($email)) $errors .= $error_invalid;
	
	if (contactformx_recaptcha_version() == 3) {
		
		if (!contactformx_validate_recaptcha_v3($recaptcha)) $errors .= $error_recaptcha;
		
	} else {
		
		if (!contactformx_validate_recaptcha_v2($recaptcha)) $errors .= $error_recaptcha;
		
	}
	
	if ($required) $errors = $error_required . $errors;
	
	if (!empty($errors)) $array['errors'] = $errors;
	
	return $array;
	
}

function contactformx_validate_recaptcha_v2($data) {
	
	if (contactformx_display_recaptcha() === 'hide') return true;
	
	$options = contactformx_options('customize');
	
	$public  = isset($options['recaptcha-public'])  ? $options['recaptcha-public']  : false;
	$private = isset($options['recaptcha-private']) ? $options['recaptcha-private'] : false;
	
	if (empty($public) || empty($private)) return false;
	
	if ($data) return require_once(CONTACTFORMX_DIR .'lib/recaptcha/connect.php');
	
	return false;
	
}

function contactformx_validate_recaptcha_v3($data) {
	
	if (contactformx_display_recaptcha() === 'hide') return true;
	
	$options = contactformx_options('customize');
	
	$public  = isset($options['recaptcha-public'])  ? $options['recaptcha-public']  : false;
	$private = isset($options['recaptcha-private']) ? $options['recaptcha-private'] : false;
	
	if (empty($public) || empty($private)) return false;
	
	$recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='. $private .'&response='. $data);
	$recaptcha = json_decode($recaptcha);
	
	$score = apply_filters('contactformx_recaptcha_score', 0.5);
	
	return (($recaptcha->success == true) && ($recaptcha->score >= $score)) ? true : false;
	
}

function contactformx_validate_input($inputs) {
	
	$malicious = false;
	
	$patterns = array("\r", "\n", "mime-version", "content-type", "cc:", "to:");
	
	$message  = '<div class="cfx-error cfx-error-malicious">';
	$message .= '<strong>'. esc_html__('Error:', 'contact-form-x') .'</strong> ';
	$message .= esc_html__('The following strings are not allowed: line breaks, ', 'contact-form-x');
	$message .= '<code>mime-version</code>, <code>content-type</code>, <code>cc:</code>, <code>to:</code>';
	$message .= '</div>';
	
	$message = apply_filters('contactformx_error_message_malicious', $message);
	
	foreach ($inputs as $input) {
		
		foreach($patterns as $pattern) {
			
			if (strpos(strtolower($input), strtolower($pattern)) !== false) {
				
				$malicious = true;
				
				break 2;
				
			}
			
		}
		
	}
	
	$error = ($malicious) ? $message : '';
	
	return $error;
	
}

function contactformx_is_valid_email($email) {
	
	$valid = true;
	
	$options = contactformx_options('form');
	
	$display = isset($options['display-fields']['email']['display']) ? $options['display-fields']['email']['display'] : null;
	
	if ($display === 'show') {
		
		if (!is_email($email)) $valid = false;
		
	} elseif ($display === 'optn') {
		
		if (!empty($email)) {
			
			if (!is_email($email)) $valid = false;
			
		}
		
	}
	
	return $valid;
	
}

function contactformx_is_error_required($id, $data) {
	
	$error = false;
	
	$options = contactformx_options('form');
	
	$display = isset($options['display-fields'][$id]['display']) ? $options['display-fields'][$id]['display'] : null;
	
	if (!$data && $display === 'show') $error = true;
	
	return $error;
	
}

function contactformx_is_error_checkbox($id, $data) {
	
	$error = false;
	
	$options = contactformx_options('form');
	
	$display = isset($options['display-fields'][$id]['display']) ? $options['display-fields'][$id]['display'] : null;
	
	$result = ($data === 'true') ? true : false;
	
	if (!$result && $display === 'show') $error = true;
	
	return $error;
	
}

function contactformx_is_error_challenge($response) {
	
	$error = false;
	
	$options_form = contactformx_options('form');
	
	$display = isset($options_form['display-fields']['challenge']['display']) ? $options_form['display-fields']['challenge']['display'] : null;
	
	$options_customize = contactformx_options('customize');
	
	$answer = isset($options_customize['challenge-answer']) ? $options_customize['challenge-answer'] : null;
	$case   = isset($options_customize['challenge-case'])   ? $options_customize['challenge-case']   : null;
	
	$result = $case ? ($answer == $response) : (strtoupper($answer) == strtoupper($response));
	
	if (!$result && $display === 'show') $error = true;
	
	return $error;
	
}

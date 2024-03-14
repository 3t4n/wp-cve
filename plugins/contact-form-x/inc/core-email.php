<?php // Contact Form X Email

if (!defined('ABSPATH')) exit;

function contactformx_send_email($array) {
	
	$options = contactformx_options('customize');
	
	$fields = array('name', 'website', 'email', 'subject', 'custom', 'carbon', 'agree', 'message', 'url');
	
	$data = array();
	
	foreach ($fields as $field) {
		
		${$field} = isset($array[$field]) ? $array[$field] : '';
		
		${'label_'. $field} = isset($options['field-'. $field .'-label']) ? $options['field-'. $field .'-label'] : ucfirst($field);
		
		if (!empty(${$field})) $data[$field] = array(${'label_'. $field}, ${$field});
		
	}
	
	if (empty($subject) && contactformx_display_subject() !== 'show') {
		
		$subject = contactformx_default_subject();
		
		$array['subject'] = $subject;
		
	}
	
	$message_original = $message;
	
	//
	
	$array = contactformx_validate_form($array);
	
	$errors = isset($array['errors']) ? $array['errors'] : null;
	
	$send_error = 0;
	
	if (empty($errors)) {
		
		$options_email = contactformx_options('email');
		
		$recipients = array();
		
		foreach (contactformx_get_recipients() as $id) {
			
			if (isset($options_email['recipient-'. $id])) $recipients[] = $options_email['recipient-'. $id];
			
		}
		
		foreach ($recipients as $recipient) {
			
			$recipient_name = isset($recipient['name']) ? $recipient['name'] : null;
			$recipient_to   = isset($recipient['to'])   ? $recipient['to']   : null;
			$recipient_from = isset($recipient['from']) ? $recipient['from'] : $recipient_to;
			
			$message = contactformx_display_extra() ? contactformx_get_extra($recipient_name, $data) : $message;
			
			$headers = contactformx_mail_headers($name, $email, $recipient_from);
			
			if (contactformx_mail_function()) {
				
				$result = mail($recipient_to, $subject, $message, $headers);
				
			} else {
				
				$result = wp_mail($recipient_to, $subject, $message, $headers);
				
			}
			
			if (!$result) $send_error++;
			
		}
		
		$admin_email = get_option('admin_email');
		
		$headers = contactformx_mail_headers($name, $email, $admin_email, true);
		
		contactformx_send_carbon($carbon, $email, $subject, $message_original, $headers);
		
		contactformx_insert_post($result, $errors, $recipients, $array);
		
		do_action('contactformx_send_email', $recipients, $array);
		
	}
	
	$array = contactformx_send_error($send_error, $array);
	
	$array = contactformx_display_results($array);
	
	return $array;
	
}

function contactformx_mail_headers($name, $email, $recipient_from, $carbon = false) {
	
	if ($carbon) {
		
		$name = get_option('blogname');
		
		$email = $recipient_from;
		
	}
	
	$headers  = 'X-Mailer: Contact Form X'. "\n";
	$headers .= 'From: '. $name .' <'. $recipient_from .'>'. "\n";
	$headers .= 'Reply-To: '. $name .' <'. $email .'>'. "\n";
	$headers .= 'Content-Type: text/plain; charset='. get_option('blog_charset', 'UTF-8') . "\n";
	
	return $headers;
	
}

function contactformx_send_carbon($carbon, $email, $subject, $message, $headers) {
	
	$result = false;
	
	if ($carbon === 'true') {
		
		$recipient_site = contactformx_get_site();
		
		$carbon_message = esc_html__('This is a carbon copy of the message you sent via', 'contact-form-x') .' '. contactformx_get_site() .':'. "\n\n";
		
		$carbon_message = apply_filters('contactformx_send_carbon_message', $carbon_message);
		
		$message = $carbon_message . $message;
		
		if (contactformx_mail_function()) {
			
			$result = mail($email, $subject, $message, $headers);
			
		} else {
			
			$result = wp_mail($email, $subject, $message, $headers);
			
		}
		
	}
	
	return $result;
	
}

function contactformx_send_error($send_error, $array) {
	
	if ($send_error) {
		
		$error = '<div class="cfx-error cfx-error-sendfail"><strong>'. esc_html__('Warning:', 'contact-form-x') .'</strong> ';
		$error .= esc_html__('There was an error sending your message. ', 'contact-form-x');
		$error .= esc_html__('Please notify the site admin using an alternate channel of communication.', 'contact-form-x');
		$error .= '</div>';
		
		$error = apply_filters('contactformx_send_error', $error);
		
		$array['errors'] .= $error;
		
	}
	
	return $array;
	
}

function contactformx_get_extra($recipient_name, $data) {
	 
	$label_name = $label_website = $label_email = $label_subject = $label_custom = $label_carbon = $label_agree = '';
	
	$name = $website = $email = $subject = $custom = $carbon = $agree = $message = '';
	
	$refer = $agent = $host = $ip = __('n/a', 'contact-form-x');
	
	//
	
	$label_refer = esc_html__('Referrer',   'contact-form-x');
	$label_agent = esc_html__('User Agent', 'contact-form-x');
	$label_host  = esc_html__('ISP/Host',   'contact-form-x');
	$label_ip    = esc_html__('IP Address', 'contact-form-x');
	
	$label_date             = esc_html__('Date',    'contact-form-x');
	$label_recipient_site   = esc_html__('Origin',  'contact-form-x');
	$label_recipient_domain = esc_html__('Domain',  'contact-form-x');
	$label_recipient_name   = esc_html__('Sent To', 'contact-form-x');
	
	$date             = contactformx_get_date();
	$recipient_site   = contactformx_get_site();
	$recipient_domain = contactformx_get_domain();
	
	if (contactformx_enable_data()) {
		
		$refer  = contactformx_get_refer();
		$agent  = contactformx_get_agent();
		$host   = contactformx_get_host();
		$ip     = contactformx_get_ip();
		
	}
	
	//
	
	foreach ($data as $key => $value) {
		
		${'label_'. $key} = isset($value[0]) ? $value[0] : null;
		${$key}           = isset($value[1]) ? $value[1] : null;
		
	}
	
	$label_url = esc_html__('Form URL', 'contact-form-x');
	
	if (contactformx_display_carbon() !== 'hide') {
		
		$carbon = ($carbon === 'true') ? esc_html__('True', 'contact-form-x') : esc_html__('False', 'contact-form-x');
		
	} else {
		
		$carbon = '';
		
	}
	
	if (contactformx_display_agree() !== 'hide') {
		
		$agree = ($agree === 'true') ? esc_html__('True', 'contact-form-x') : esc_html__('False', 'contact-form-x');
		
	} else {
		
		$agree = '';
		
	}
	
	$intro  = esc_html__('Hello', 'contact-form-x') .' '. $recipient_name .', '. "\n\n";
	$intro .= $name ? $name .' ' : esc_html__('Visitor', 'contact-form-x') .' ';
	$intro .= esc_html__('sends a message via', 'contact-form-x') .' '. $recipient_site .': '. "\n\n";
	
	$intro = apply_filters('contactformx_message_intro', $intro);
	
	//
	
	$extra  = "\n\n\n" . esc_html__('Additional Information', 'contact-form-x');
	$extra .= "\n" . esc_html__('----------------------', 'contact-form-x') . "\n";
	
	$extra .= "\n" . esc_html__('Form Data:', 'contact-form-x') . "\n\n";
	
	$extra .= ($label_name    && $name)    ? $label_name    .': '. $name    . "\n" : '';
	$extra .= ($label_website && $website) ? $label_website .': '. $website . "\n" : '';
	$extra .= ($label_email   && $email)   ? $label_email   .': '. $email   . "\n" : '';
	$extra .= ($label_subject && $subject) ? $label_subject .': '. $subject . "\n" : '';
	$extra .= ($label_custom  && $custom)  ? $label_custom  .': '. $custom  . "\n" : '';
	$extra .= ($label_agree   && $agree)   ? $label_agree   .': '. $agree   . "\n" : '';
	$extra .= ($label_carbon  && $carbon)  ? $label_carbon  .': '. $carbon  . "\n" : '';
	$extra .= ($label_url     && $url)     ? $label_url     .': '. $url     . "\n" : '';
	
	//
	
	$extra .= "\n" . esc_html__('Meta Data:', 'contact-form-x') . "\n\n";
	
	$extra .= $label_date             .': '. $date             . "\n";
	$extra .= $label_recipient_name   .': '. $recipient_name   . "\n";
	$extra .= $label_recipient_site   .': '. $recipient_site   . "\n";
	$extra .= $label_recipient_domain .': '. $recipient_domain . "\n";
	
	$extra .= ($refer === 'n/a') ? '' : $label_refer .': '. esc_html($refer) . "\n";
	$extra .= ($ip    === 'n/a') ? '' : $label_ip    .': '. esc_html($ip)    . "\n";
	$extra .= ($host  === 'n/a') ? '' : $label_host  .': '. esc_html($host)  . "\n";
	$extra .= ($agent === 'n/a') ? '' : $label_agent .': '. esc_html($agent) . "\n";
	
	$extra = apply_filters('contactformx_message_extra', $extra);
	
	return $intro . $message . $extra;
	
}
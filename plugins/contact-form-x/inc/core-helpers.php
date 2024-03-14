<?php // Contact Form X Helpers

if (!defined('ABSPATH')) exit;

function contactformx_options($option) {
	
	global $ContactFormX;
	
	if (!$option) return;
	
	if (!$ContactFormX) return;
	
	$default = call_user_func(array($ContactFormX, 'options_' . $option));
	
	return get_option('contactformx_'. $option, $default);
	
}

function contactformx_field_options() {
	
	global $ContactFormX;
	
	return $ContactFormX->fields();
	
}

function contactformx_enable_data() {
	
	$options_advanced = contactformx_options('advanced');
	
	return isset($options_advanced['enable-data-collection']) ? $options_advanced['enable-data-collection'] : 0;
	
}

function contactformx_success_message() {
	
	$options_customize = contactformx_options('customize');
	
	return isset($options_customize['success-message']) ? $options_customize['success-message'] : '';
	
}

function contactformx_default_subject() {
	
	$options_customize = contactformx_options('customize');
	
	return isset($options_customize['default-subject']) ? $options_customize['default-subject'] : __('Message sent from your contact form.', 'contact-form-x');
	
}

function contactformx_display_subject() {
	
	$options_form = contactformx_options('form');
	
	return isset($options_form['display-fields']['subject']['display']) ? $options_form['display-fields']['subject']['display'] : null;
	
}

function contactformx_display_success() {
	
	$options_advanced = contactformx_options('advanced');
	
	return isset($options_advanced['display-success']) ? $options_advanced['display-success'] : 'basic';
	
}

function contactformx_display_carbon() {
	
	$options_form = contactformx_options('form');
	
	return isset($options_form['display-fields']['carbon']['display']) ? $options_form['display-fields']['carbon']['display'] : null;
	
}

function contactformx_display_email() {
	
	$options_form = contactformx_options('form');
	
	return isset($options_form['display-fields']['email']['display']) ? $options_form['display-fields']['email']['display'] : null;
	
}

function contactformx_display_agree() {
	
	$options_form = contactformx_options('form');
	
	return isset($options_form['display-fields']['agree']['display']) ? $options_form['display-fields']['agree']['display'] : null;
	
}

function contactformx_display_extra() {
	
	$options_advanced = contactformx_options('advanced');
	
	return isset($options_advanced['email-message-extra']) ? $options_advanced['email-message-extra'] : false;
	
}

function contactformx_display_recaptcha() {
	
	$options_form = contactformx_options('form');
	
	return isset($options_form['display-fields']['recaptcha']['display']) ? $options_form['display-fields']['recaptcha']['display'] : null;
}

function contactformx_recaptcha_version() {
	
	$options_customize = contactformx_options('customize');
	
	return isset($options_customize['recaptcha-version']) ? $options_customize['recaptcha-version'] : 2;
	
}

function contactformx_enable_custom_style() {
	
	$options_appearance = contactformx_options('appearance');
	
	return isset($options_appearance['enable-custom-style']) ? $options_appearance['enable-custom-style'] : null;
	
}

function contactformx_get_custom_style($style) {
	
	$options_appearance = contactformx_options('appearance');
	
	return isset($options_appearance['custom-style-'. $style]) ? $options_appearance['custom-style-'. $style] : null;
	
}

function contactformx_mail_function() {
	
	$options_advanced = contactformx_options('advanced');
	
	return isset($options_advanced['mail-function']) ? $options_advanced['mail-function'] : false;
	
}

function contactformx_get_recipients() {
	
	$options = contactformx_options('email');
	
	$array = array();
	
	foreach ($options as $key => $value) {
		
		preg_match('/^recipient-([0-9])+$/i', $key, $matches);
		
		if (isset($matches[1]) && !empty($matches[1])) $array[] = $matches[1];
		
	}
	
	return $array;
	
}

function contactformx_get_site() {
	
	return get_bloginfo('name');
	
}

function contactformx_get_date() {
	
	$date_format = get_option('date_format');
	
	$time_format = get_option('time_format');
	
	if (function_exists('current_datetime')) { // WP 5.3
		
		$format = $date_format .' @ '. $time_format;
		
		$date = current_datetime()->format($format);
		
	} else {
		
		$date = date_i18n($date_format, current_time('timestamp')) .' @ '. date_i18n($time_format, current_time('timestamp'));
		
	}
	
	return apply_filters('contactformx_date', $date);
	
}

function contactformx_get_domain() {
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : __('n/a', 'contact-form-x');
	
	$domain = $protocol . $host;
	
	return apply_filters('contactformx_domain', $domain, $protocol, $host);
	
}

function contactformx_get_refer() {
	
	$refer = (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : __('n/a', 'contact-form-x');
	
	return apply_filters('contactformx_refer', $refer);
		
}

function contactformx_get_agent() {
	
	$agent = (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : __('n/a', 'contact-form-x');
	
	return apply_filters('contactformx_agent', $agent);
	
}

function contactformx_get_host() {
	
	$ip = contactformx_get_ip();
	
	$host = filter_var($ip, FILTER_VALIDATE_IP) ? gethostbyaddr($ip) : $ip;
	
	$host = $host ? $host : __('n/a', 'contact-form-x');
	
	return $host;
	
}

function contactformx_get_ip() {
	
	$ip = __('n/a', 'contact-form-x');
	
	if (isset($_SERVER)) {
		
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			
			$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		} else {
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
		}
		
	} else {
		
		if (getenv('HTTP_X_FORWARDED_FOR')) {
			
			$ip = getenv('HTTP_X_FORWARDED_FOR');
			
		} elseif (getenv('HTTP_CLIENT_IP')) {
			
			$ip = getenv('HTTP_CLIENT_IP');
			
		} else {
			
			$ip = getenv('REMOTE_ADDR');
			
		}
		
	}
	
	return $ip;
	
}

function contactformx_truncate_string($string, $length = 25, $dots = ' [...]') {
	
	return (strlen($string) > $length) ? substr($string, 0, $length) . $dots : $string;
	
}

function contactformx_enable_shortcode_widget() {
	
	$options_advanced = contactformx_options('advanced');
	
	$enable_shortcodes = (isset($options_advanced['enable-shortcode-widget']) && $options_advanced['enable-shortcode-widget']) ? 1 : 0;
	
	if ($enable_shortcodes) {
		
		add_filter('widget_text', 'do_shortcode', 10); 
		
	}
	
}

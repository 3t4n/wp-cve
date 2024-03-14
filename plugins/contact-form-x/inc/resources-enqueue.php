<?php // Contact Form X Enqueue

if (!defined('ABSPATH')) exit;

function contactformx_enqueue_resources_front() {
	
	if (contactformx_maybe_enqueue()) {
		
		$style = contactformx_enable_custom_style();
		
		if ($style !== 'none') {
			
			$style = contactformx_get_custom_style($style);
			
			wp_register_style('cfx', false);
			wp_enqueue_style('cfx');
			wp_add_inline_style('cfx', $style);
			
		}
		
		wp_enqueue_script('cfx-cookies', CONTACTFORMX_URL .'js/cookies.js', array(), CONTACTFORMX_VERSION);
		
		wp_enqueue_script('cfx-frontend', CONTACTFORMX_URL .'js/frontend.js', array('jquery', 'cfx-cookies'), CONTACTFORMX_VERSION);
		
		contactformx_enqueue_resources_front_localize();
		
		if (contactformx_display_recaptcha() !== 'hide') {
			
			$options = contactformx_options('customize');
			
			$recaptcha = isset($options['recaptcha-public']) ? $options['recaptcha-public'] : '';
			
			$query = apply_filters('contactformx_recaptcha_querystring', '');
			
			if (contactformx_recaptcha_version() == 3) {
				
				$query = !empty($query) ? '&hl='. $query : '';
				
				wp_enqueue_script('cfx-recaptcha', 'https://www.google.com/recaptcha/api.js?render='. $recaptcha . $query, array(), null);
				
			} else {
				
				$query = !empty($query) ? '?hl='. $query : '';
				
				wp_enqueue_script('cfx-recaptcha', 'https://www.google.com/recaptcha/api.js'. $query, array(), CONTACTFORMX_VERSION);
				
			}
			
		}
		
	}
	
}

function contactformx_maybe_enqueue() {
	
	$options = contactformx_options('advanced');
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$display_url = isset($options['display-url']) ? $options['display-url'] : '';
	
	$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'undefined';
	
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/na';
	
	$current_url = esc_url_raw($protocol . $http_host . $request_uri);
	
	$display = false;
	
	if (!empty($display_url)) {
		
		$display_urls = explode(',', $display_url);
		
		foreach ($display_urls as $url) {
			
			$url = esc_url_raw(trim($url));
			
			if ($url === $current_url) {
				
				$display = true;
				
				break;
				
			}
			
		}
		
	} else {
		
		$display = true;
		
	}
	
	return $display;
	
}

function contactformx_enqueue_resources_front_localize() {
	
	$options_form = contactformx_options('form');
	
	$carbon_hidden = isset($options_form['display-fields']['carbon']['display']) ? $options_form['display-fields']['carbon']['display'] : '';
	
	$options_customize = contactformx_options('customize');
	
	$challenge_answer = isset($options_customize['challenge-answer']) ? $options_customize['challenge-answer'] : '';
	$challenge_case   = isset($options_customize['challenge-case'])   ? $options_customize['challenge-case']   : '';
	$submit_button    = isset($options_customize['submit-button'])    ? $options_customize['submit-button']    : '';
	$recaptcha_public = isset($options_customize['recaptcha-public']) ? $options_customize['recaptcha-public'] : '';
	
	$recaptcha_enable = (contactformx_display_recaptcha() === 'hide') ? 0 : 1;
	
	$recaptcha_version = contactformx_recaptcha_version();
	
	$script = array(
		
		'cfxurl'    => CONTACTFORMX_URL,
		'ajaxurl'   => admin_url('admin-ajax.php'), 
		'nonce'     => wp_create_nonce('cfx-frontend'), 
		'sending'   => __('Sending...', 'contact-form-x'),
		'email'     => contactformx_display_email(),
		'carbon'    => $carbon_hidden,
		'challenge' => $challenge_answer,
		'casing'    => $challenge_case,
		'submit'    => $submit_button,
		'rpublic'   => $recaptcha_public,
		'renable'   => $recaptcha_enable,
		'rversion'  => $recaptcha_version,
		'xhr'       => null, 
		
	);
	
	wp_localize_script('cfx-frontend', 'contactFormX', $script);
	
}

function contactformx_enqueue_resources_admin() {
	
	$screen_id = contactformx_get_current_screen_id();
	
	if (!$screen_id) return;
	
	if ($screen_id === 'settings_page_contactformx') {
		
		wp_enqueue_style('cfx-settings', CONTACTFORMX_URL .'css/settings.css', array('dashicons', 'wp-jquery-ui-dialog'), CONTACTFORMX_VERSION);
		
		wp_enqueue_script('cfx-settings', CONTACTFORMX_URL .'js/settings.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-sortable'), CONTACTFORMX_VERSION);
		
	} elseif ($screen_id === 'dashboard') {
		
		wp_enqueue_style('cfx-dashboard', CONTACTFORMX_URL .'css/dashboard.css', array(), CONTACTFORMX_VERSION);
		
		wp_enqueue_script('cfx-dashboard', CONTACTFORMX_URL .'js/dashboard.js', array('jquery'), CONTACTFORMX_VERSION);
		
	}
	
}

function contactformx_print_js_vars_admin() { 
	
	$screen_id = contactformx_get_current_screen_id();
	
	if (!$screen_id) return;
	
	if ($screen_id === 'settings_page_contactformx') : ?>
		
		<script type="text/javascript">
			var 
			alert_reset_options_title   = '<?php _e('Confirm Reset',            'contact-form-x'); ?>',
			alert_reset_options_message = '<?php _e('Restore default options?', 'contact-form-x'); ?>',
			alert_reset_options_true    = '<?php _e('Yes, make it so.',         'contact-form-x'); ?>',
			alert_reset_options_false   = '<?php _e('No, abort mission.',       'contact-form-x'); ?>';
			var 
			alert_delete_recip_title   = '<?php _e('Confirm Delete',         'contact-form-x'); ?>',
			alert_delete_recip_message = '<?php _e('Delete this recipient?', 'contact-form-x'); ?>',
			alert_delete_recip_true    = '<?php _e('Yes, make it so.',       'contact-form-x'); ?>',
			alert_delete_recip_false   = '<?php _e('No, abort mission.',     'contact-form-x'); ?>';
			var 
			alert_delete_data_title   = '<?php _e('Confirm Delete',         'contact-form-x'); ?>',
			alert_delete_data_message = '<?php _e('Delete all email data?', 'contact-form-x'); ?>',
			alert_delete_data_true    = '<?php _e('Yes, make it so.',       'contact-form-x'); ?>',
			alert_delete_data_false   = '<?php _e('No, abort mission.',     'contact-form-x'); ?>';
		</script>
		
	<?php endif;
	
}

function contactformx_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}

function contactformx_randomizr() {
	
	$randomizr = rand(100,9999999);
	
	return $randomizr;
	
}

<?php

function r34ics_ajax() {
	global $R34ICS;

	if (!empty($_POST)) {

		// Verify nonce
		if (!wp_verify_nonce($_POST['r34ics_nonce'], 'r34ics_nonce')) { echo 1; exit; }
		
		// Sanitize input
		$args = $_POST['args'];
		foreach ((array)$args as $key => $value) {
			// Replaces FILTER_SANITIZE_STRING filter, deprecated in PHP 8.1
			$args[$key] = is_string($value) ? htmlspecialchars(strip_tags($value)) : intval($value);
			if ($value == 'true') { $args[$key] = 1; }
			elseif ($value == 'false') { $args[$key] = 0; }
		}
		
		switch ($_POST['subaction']) {
		
			case 'display_calendar':
				if (!empty($args['url'])) {
					$args['url'] = r34ics_url_uniqid_array_convert($args['url']);
					foreach ((array)$args as $key => $value) {
						if (!empty($args['ajax']) && is_string($value)) { $args[$key] = stripslashes($value); }
					}
					$R34ICS->display_calendar($args);
					if (!empty($args['debug'])) {
						_r34ics_wp_footer_debug_output();
					}
				}
				break;
		
			default:
				break;

		}
	}
   exit;
}

add_action('wp_ajax_r34ics_ajax', 'r34ics_ajax');
add_action('wp_ajax_nopriv_r34ics_ajax', 'r34ics_ajax');

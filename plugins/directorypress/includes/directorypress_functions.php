<?php 
if (!function_exists('directorypress_get_input_value')) {
	function directorypress_get_input_value($target, $key, $default = false) {
		$target = is_object($target) ? (array) $target : $target;
	
		if (is_array($target) && isset($target[$key])) {
			$value = $target[$key];
		} else {
			$value = $default;
		}
	
		$value = apply_filters('directorypress_get_input_value', $value, $target, $key, $default);
		return $value;
	}
}
/**
 * Is user allowed to do something according to provided user roles settings
 * 
 * @param array $user_roles_setting
 * @return boolean
 */
function directorypress_is_user_allowed($user_roles_setting) {
	$user_allowed = true;
	
	if ($user_roles_setting) {
		$user = wp_get_current_user();
			
		if (!array_intersect($user->roles, $user_roles_setting)) {
			$user_allowed = false;
		}
	}
	
	return $user_allowed;
}

if (!function_exists('directorypress_init_session')) {
	function directorypress_init_session() {
		if (session_status() == PHP_SESSION_NONE) {
			//@session_start();
		}
	}
}

if (!function_exists('directorypress_add_notification')) {
	function directorypress_add_notification($message, $type = 'updated') {
		global $directorypress_notifications;
		
		if (is_array($message)) {
			foreach ($message AS $m) {
				directorypress_add_notification($m, $type);
			}
			return ;
		}
	
		if (!isset($directorypress_notifications[$type]) || (isset($directorypress_notifications[$type]) && !in_array($message, $directorypress_notifications[$type]))) {
			$directorypress_notifications[$type][] = $message;
		}
	
		if (!isset($_SESSION['directorypress_notifications'][$type]) || (isset($_SESSION['directorypress_notifications'][$type]) && !in_array($message, $_SESSION['directorypress_notifications'][$type]))) {
			$_SESSION['directorypress_notifications'][$type][] = $message;
		}
	}
}

if (!function_exists('directorypress_renderMessages')) {
	function directorypress_renderMessages() {
		global $directorypress_notifications;
	
		$messages = array();
		if (isset($directorypress_notifications) && is_array($directorypress_notifications) && $directorypress_notifications){
			$messages = $directorypress_notifications;
		}
		
		if (isset($_SESSION['directorypress_notifications'])) {
			$messages = array_merge($messages, $_SESSION['directorypress_notifications']);
		}
	
		$messages = directorypress_unique_notification($messages);
	
		foreach ($messages AS $type=>$messages) {
			if($type == 'error'){
				$type_class = 'danger';
			}elseif($type == 'updated'){
				$type_class = 'success';
			}elseif($type == 'info'){
				$type_class = 'info';
			}
			$message_class = (is_admin()) ? $type : "alert alert-" . $type_class;

			echo '<div class="' . esc_attr($message_class) . '">';
			foreach ($messages AS $message) {
				echo '<p>' . trim(preg_replace("/<p>(.*?)<\/p>/", "$1", $message)) . '</p>';
			}
			echo '</div>';
		}
		
		$directorypress_notifications = array();
		if (isset($_SESSION['directorypress_notifications'])) {
			unset($_SESSION['directorypress_notifications']);
		}
	}
	function directorypress_unique_notification($array) {
		$result = array_map("unserialize", array_unique(array_map("serialize", $array)));
		foreach ($result as $key => $value)
			if (is_array($value))
				$result[$key] = directorypress_unique_notification($value);
		return $result;
	}
}
function directorypress_author_page_url($id) {
   $nicename = get_the_author_meta('nicename', $id);
   $url = get_author_posts_url($id, $nicename) .'?profile=true';
   return $url;
}

function directorypress_comment_template( $template ) {
    if(directorypress_is_listing_page()){ 
        $template = DIRECTORYPRESS_TEMPLATES_PATH .'reviews-function.php';
    }
	return $template; 
	 
}

add_filter( "comments_template", "directorypress_comment_template");



if (!function_exists('directorypress_time_ago')) {
      function directorypress_time_ago($time)
      {
          
          $lengths = array(
               "60",
               "60",
               "24",
               "7",
               "4.35",
               "12",
               "10"
          );
          
          $now = time();
          
          $difference = $now - $time;
          $tense      = esc_html__("ago", 'DIRECTORYPRESS');
          
          for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
               $difference /= $lengths[$j];
          }
          
          $difference = round($difference);
          
          if ($difference != 1) {
               $periods = array(
				   esc_html__("seconds", 'DIRECTORYPRESS'),
				   esc_html__("minutes", 'DIRECTORYPRESS'),
				   esc_html__("hours", 'DIRECTORYPRESS'),
				   esc_html__("days", 'DIRECTORYPRESS'),
				   esc_html__("weeks", 'DIRECTORYPRESS'),
				   esc_html__("months", 'DIRECTORYPRESS'),
				   esc_html__("years", 'DIRECTORYPRESS'),
				   esc_html__("decades", 'DIRECTORYPRESS')
			  );
          }else{
			  $periods = array(
				   esc_html__("second", 'DIRECTORYPRESS'),
				   esc_html__("minute", 'DIRECTORYPRESS'),
				   esc_html__("hour", 'DIRECTORYPRESS'),
				   esc_html__("day", 'DIRECTORYPRESS'),
				   esc_html__("week", 'DIRECTORYPRESS'),
				   esc_html__("month", 'DIRECTORYPRESS'),
				   esc_html__("year", 'DIRECTORYPRESS'),
				   esc_html__("decade", 'DIRECTORYPRESS')
			  );
		  }
          
          return "$difference $periods[$j] {$tense} ";
      }
}

function directorypress_expiry_date($date, $package) {
	switch ($package->package_duration_unit) {
		case 'day':
			$date = strtotime('+'.$package->package_duration.' day', $date);
			break;
		case 'week':
			$date = strtotime('+'.$package->package_duration.' week', $date);
			break;
		case 'month':
			$date = directorypress_month_unit($date, $package->package_duration);
			break;
		case 'year':
			$date = strtotime('+'.$package->package_duration.' year', $date);
			break;
	}
	
	return $date;
}

function directorypress_month_unit($from_timestamp, $months_to_add) {
	$first_day_of_month = date('Y-m', $from_timestamp) . '-1';
	$days_in_next_month = date('t', strtotime("+ {$months_to_add} month", strtotime($first_day_of_month)));
	
	// Payment is on the last day of the month OR number of days in next billing month is less than the the day of this month (i.e. current billing date is 30th January, next billing date can't be 30th February)
	if (date('d m Y', $from_timestamp) === date('t m Y', $from_timestamp) || date('d', $from_timestamp) > $days_in_next_month) {
		for ($i = 1; $i <= $months_to_add; $i++) {
			$next_month = strtotime('+3 days', $from_timestamp); // Add 3 days to make sure we get to the next month, even when it's the 29th day of a month with 31 days
			$next_timestamp = $from_timestamp = strtotime(date('Y-m-t H:i:s', $next_month));
		}
	} else { // Safe to just add a month
		$next_timestamp = strtotime("+ {$months_to_add} month", $from_timestamp);
	}
	
	return $next_timestamp;
}

function directorypress_has_template($template) {
	$templates = array(
			$template
	);

	foreach ($templates AS $template_to_check) {
		if (is_file($template_to_check)) {
			return $template_to_check;
		}elseif (is_file(get_stylesheet_directory() . '/directorypress/public/' . $template_to_check)) {
			return get_stylesheet_directory() . '/directorypress/public/' . $template_to_check;
		}elseif (is_file(get_template_directory() . '/directorypress/public/' . $template_to_check)) {
			return get_template_directory() . '/directorypress/public/' . $template_to_check;
		}elseif (is_file(DIRECTORYPRESS_TEMPLATES_PATH . $template_to_check)) {
			return DIRECTORYPRESS_TEMPLATES_PATH . $template_to_check;
		}
	}

	return false;
}

if (!function_exists('directorypress_display_template')) {
	function directorypress_display_template($template, $args = array(), $return = false) {
	
		if ($args) {
			extract($args);
		}
		
		$template = apply_filters('directorypress_display_template', $template, $args);
		
		if (is_array($template)) {
			$template_path = $template[0];
			$template_file = $template[1];
			$template = $template_path . $template_file;
		}
		
		$template = directorypress_has_template($template);

		if ($template) {
			if ($return) {
				ob_start();
			}
		
			include($template);
			
			if ($return) {
				$output = ob_get_contents();
				ob_end_clean();
				return $output;
			}
		}
	}
}
function directorypress_is_elementor($post = null){
  global $post, $post_id;
  if($post && directorypress_is_elementor_active()){
	return \Elementor\Plugin::$instance->documents->get( $post->ID )->is_built_with_elementor();
	//return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
  }
}
function directorypress_elementor_page(){
		global $post;
		if($post && directorypress_is_elementor()){
			$data = get_post_meta($post->ID, '_elementor_data');
			if( stristr( $data[1], 'directorypress-main' ) ){
				return $post->ID;
			}
		}

}
function directorypress_setFrontendController($shortcode, $shortcode_instance, $do_duplicate = true) {
	global $directorypress_object;

	$directorypress_object->public_handlers[$shortcode][] = $shortcode_instance;

	// this duplicate property needed because we unset each controller when we render shortcodes, but WP doesn't really know which shortcode already was processed
	if ($do_duplicate) {
		$directorypress_object->_public_handlers[$shortcode][] = $shortcode_instance;
	}

	return $shortcode_instance;
}
function directorypress_getFrontendControllers($shortcode = false, $property = false) {
	global $directorypress_object;

	if (!$shortcode) {
		return $directorypress_object->public_handlers;
	} else {
		if (!$property) {
			if (isset($directorypress_object->public_handlers[$shortcode])) {
				return $directorypress_object->public_handlers[$shortcode];
			} else {
				return false;
			}
		} else {
			if (isset($directorypress_object->public_handlers[$shortcode][0]->$property)) {
				return $directorypress_object->public_handlers[$shortcode][0]->$property;
			} else {
				return false;
			}
		}
	}
}
function directorypress_get_system_pages() {
	global $wpdb, $post;
	$all_posts = $wpdb->get_results("SELECT post_id,meta_value FROM {$wpdb->postmeta} WHERE (meta_key = '_elementor_data')");
	//if(!directorypress_is_archive_page($post))
	$flat_index_pages = $wpdb->get_results("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE (post_content LIKE '%[" . DIRECTORYPRESS_MAIN_SHORTCODE . "]%') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A);
	$custom_index_pages = $wpdb->get_results("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE (post_content LIKE '%[" . DIRECTORYPRESS_MAIN_SHORTCODE . " %') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A);
	$el_index_pages = $wpdb->get_results("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE (post_content LIKE '%el_archive_page%') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A);
	$index_pages = array_merge($flat_index_pages, $custom_index_pages, $el_index_pages);
	
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($index_pages AS $key=>&$cpage) {
			if ($tpage = apply_filters('wpml_object_id', $cpage['id'], 'page')) {
				$cpage['id'] = $tpage;
				$cpage['slug'] = get_post($cpage['id'])->post_name;
			} else {
				unset($index_pages[$key]);
			}
		}
	}
	
	return array_unique($index_pages, SORT_REGULAR);
}

function directorypress_get_all_listing_related_pages() {
	global $wpdb, $directorypress_object;
	
	$flat_listing_pages = $wpdb->get_results("SELECT ID AS id FROM {$wpdb->posts} WHERE (post_content LIKE '%[" . DIRECTORYPRESS_LISTING_SHORTCODE . "]%' OR post_content LIKE '%[directorypress-listing]%') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A);
	$custom_listing_pages = $wpdb->get_results("SELECT ID AS id FROM {$wpdb->posts} WHERE (post_content LIKE '%[" . DIRECTORYPRESS_LISTING_SHORTCODE . " %' OR post_content LIKE '%[directorypress-listing %') AND post_status = 'publish' AND post_type = 'page'", ARRAY_A);
	$pages = array_merge($flat_listing_pages, $custom_listing_pages);
	
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		foreach ($pages AS $key=>&$cpage) {
			if ($tpage = apply_filters('wpml_object_id', $cpage['id'], 'page')) {
				$cpage['id'] = $tpage;
			} else {
				unset($pages[$key]);
			}
		}
	}
	
	$pages = array_unique($pages, SORT_REGULAR);
	
	$listing_pages = array();
	
	$shortcodes = array(DIRECTORYPRESS_LISTING_SHORTCODE, 'directorypress-listing');
	foreach ($pages AS $page_id) {
		$page_id = $page_id['id'];
		$pattern = get_shortcode_regex($shortcodes);
		if (preg_match_all('/'.$pattern.'/s', get_post($page_id)->post_content, $matches) && array_key_exists(2, $matches)) {
			foreach ($matches[2] AS $key=>$shortcode) {
				if (in_array($shortcode, $shortcodes)) {
					if (($attrs = shortcode_parse_atts($matches[3][$key]))) {
						if (isset($attrs['directorytype']) && is_numeric($attrs['directorytype']) && ($directorytype = $directorypress_object->directorytypes->directory_by_id($attrs['directorytype']))) {
							$listing_pages[$directorytype->id] = $page_id;
							break;
						} elseif (!isset($attrs['id'])) {
							$listing_pages[$directorypress_object->directorytypes->directorypress_get_base_directorytype()->id] = $page_id;
							break;
						}
					} else {
						$listing_pages[$directorypress_object->directorytypes->directorypress_get_base_directorytype()->id] = $page_id;
						break;
					}
				}
			}
		}
	}
	
	return $listing_pages;
}

function directorypress_get_archive_page() {
	global $wp, $wp_query, $wpdb, $wp_rewrite, $directorypress_object;
	
	$curr_index_page = array('slug' => '', 'id' => 0, 'url' => '');

	$index_pages = $directorypress_object->directorypress_all_archive_pages;

	if (!$index_pages)
		return $curr_index_page;
	
	if (get_queried_object_id()) {
		foreach ($index_pages AS $page) {
			if ($page['id'] == get_queried_object_id()) {
				$curr_index_page = $page;
				break;
			}
		}
	}
	if (!$curr_index_page['id']) {
		if ($wp_rewrite->using_permalinks()) {
			if (wp_doing_ajax() && isset($_REQUEST['base_url'])) {
				$base_url = sanitize_url($_REQUEST['base_url']);
			} else {
				$base_url = home_url($wp->request);
			}
			$urls_length = array();
			foreach ($index_pages AS $page) {
				$page_url = get_permalink($page['id']);
				if (strpos($base_url, $page_url) !== FALSE)
					$urls_length[$page['id']] = strlen($page_url);
			}
			if ($urls_length) {
				asort($urls_length);
				$urls_length = array_keys($urls_length);
				$page_id = array_pop($urls_length);
				foreach ($index_pages AS $page) {
					if ($page['id'] == $page_id) {
						$curr_index_page = $page;
						break;
					}
				}
			}
		} else {
			$homepage = null;
			if (wp_doing_ajax() && isset($_REQUEST['base_url'])) {
				if (($base_url = wp_parse_args($_REQUEST['base_url'])) && isset($base_url['homepage']))
					$homepage = $base_url['homepage'];
			} else {
				$homepage = get_query_var('homepage');
			}
			foreach ($index_pages AS $page) {
				if ($page['id'] == $homepage) {
					$curr_index_page = $page;
					break;
				}
			}
		}
	}

	if (!$curr_index_page['id']) {
		$curr_index_page = $index_pages[0];
	}

	if ($curr_index_page['id']) {
		if ($wp_rewrite->using_permalinks())
			$curr_index_page['url'] = get_permalink($curr_index_page['id']);
		else
			// found that on some instances of WP "native" trailing slashes may be missing
			$curr_index_page['url'] = add_query_arg('page_id', $curr_index_page['id'], home_url('/'));
	}

	return $curr_index_page;
}

function directorypress_get_listingPage() {
	global $directorypress_object;
	
	$page_id = null;
	$curr_listing_page = array('slug' => '', 'id' => 0, 'url' => '');
	
	if ($directorypress_object->current_directorytype && isset($directorypress_object->directorypress_all_listing_pages[$directorypress_object->current_directorytype->id])) {
		$page_id = $directorypress_object->directorypress_all_listing_pages[$directorypress_object->current_directorytype->id];
	} else {
		// When directorytype was not installed yet - we can use only the 1st default directorytype
		if (get_option('directorypress_installed_directory')) {
			$directory_id = $directorypress_object->directorytypes->directorypress_get_base_directorytype()->id;
		} else {
			$directory_id = 1;
		}
		if (isset($directorypress_object->directorypress_all_listing_pages[$directory_id])) {
			$page_id = $directorypress_object->directorypress_all_listing_pages[$directory_id];
		}
	}

	if ($page_id) {
		$curr_listing_page['id'] = $page_id;
		$curr_listing_page['url'] = get_permalink($page_id);
		$curr_listing_page['slug'] = get_post($page_id)->post_name;
	}
	
	return $curr_listing_page;
}

function directorypress_getTemplatePage($shortcode) {
	global $wpdb, $wp_rewrite;

	if (!($template_page = $wpdb->get_row("SELECT ID AS id, post_name AS slug FROM {$wpdb->posts} WHERE post_content LIKE '%[" . $shortcode . "]%' AND post_status = 'publish' AND post_type = 'page' LIMIT 1", ARRAY_A)))
		$template_page = array('slug' => '', 'id' => 0, 'url' => '');
	
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($tpage = apply_filters('wpml_object_id', $template_page['id'], 'page')) {
			$template_page['id'] = $tpage;
			$template_page['slug'] = get_post($template_page['id'])->post_name;
		} else 
			$template_page = array('slug' => '', 'id' => 0, 'url' => '');
	}

	if ($template_page['id']) {
		if ($wp_rewrite->using_permalinks())
			$template_page['url'] = get_permalink($template_page['id']);
		else
			// found that on some instances of WP "native" trailing slashes may be missing
			$template_page['url'] = add_query_arg('page_id', $template_page['id'], home_url('/'));
	}

	return $template_page;
}

function directorypress_directorytype_url($path = '', $directorytype = null) {
	global $directorypress_object;
	
	if (empty($directorytype)) {
		$directory_page_url = $directorypress_object->directorypress_archive_page_url;
	} else {
		$directory_page_url = $directorytype->url;
	}
	
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_option('language_negotiation_type') == 3) {
			// remove any previous value.
			$directory_page_url = remove_query_arg('lang', $directory_page_url);
		}
	}

	if (!is_array($path)) {
		if ($path)
			$path = rtrim($path, '/') . '/';
		// found that on some instances of WP "native" trailing slashes may be missing
		$url = rtrim($directory_page_url, '/') . '/' . $path;
	} else
		$url = add_query_arg($path, $directory_page_url);

	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		$url = $sitepress->convert_url($url);
	}
	
	$url = directorypress_add_homepage_id($url);
	
	return utf8_uri_encode($url);
}

function directorypress_templatePageUri($slug_array, $page_url) {
	global $directorypress_object;
	
	if ($page_url)
		$page_url = $page_url;
	else
		$page_url = $directorypress_object->directorypress_archive_page_url;
	
	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		if ($sitepress->get_option('language_negotiation_type') == 3) {
			// remove any previous value.
			$page_url = remove_query_arg('lang', $page_url);
		}
	}

	$template_url = add_query_arg($slug_array, $page_url);

	// adapted for WPML
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		$template_url = $sitepress->convert_url($template_url);
	}
	
	$template_url = directorypress_add_homepage_id($template_url);

	return utf8_uri_encode($template_url);
}

function directorypress_add_homepage_id($url) {
	global $directorypress_object, $wp_rewrite;
	
	$homepage = null;
	if (wp_doing_ajax() && isset($base_url['homepage'])) {
		if ($base_url = wp_parse_args($_REQUEST['base_url']))
			$homepage = $base_url['homepage'];
	} else {
		$homepage = get_queried_object_id();
	}
	if (!$wp_rewrite->using_permalinks() && $homepage && count($directorypress_object->directorypress_all_archive_pages) > 1) {
		foreach ($directorypress_object->directorypress_all_archive_pages AS $page) {
			if ($page['id'] == $homepage) {
				$url = add_query_arg('homepage', $homepage, $url);
				break;
			}
		}
	}
	return $url;
}

function directorypress_dpf() {
	$wp_date_format = get_option('date_format');
	return str_replace(
			array('S',  'd', 'j',  'l',  'm', 'n',  'F',  'Y'),
			array('',  'dd', 'd', 'DD', 'mm', 'm', 'MM', 'yy'),
		$wp_date_format);
}

function directorypress_dplf($locale) {
	if ($locale) {
		$_locale = explode('-', str_replace('_', '-', $locale));
		$lang_code = array_shift($_locale);
		if (is_file(DIRECTORYPRESS_RESOURCES_PATH . 'js/i18n/datepicker-'.$locale.'.js'))
			return DIRECTORYPRESS_RESOURCES_URL . 'js/i18n/datepicker-'.$locale.'.js';
		elseif (is_file(DIRECTORYPRESS_RESOURCES_PATH . 'js/i18n/datepicker-'.$lang_code.'.js'))
			return DIRECTORYPRESS_RESOURCES_URL . 'js/i18n/datepicker-'.$lang_code.'.js';
	}
}

function directorypress_dplc($locale) {
	if ($locale) {
		$_locale = explode('-', str_replace('_', '-', $locale));
		$lang_code = array_shift($_locale);
		if (is_file(DIRECTORYPRESS_RESOURCES_PATH . 'js/i18n/datepicker-'.$locale.'.js'))
			return $locale;
		elseif (is_file(DIRECTORYPRESS_RESOURCES_PATH . 'js/i18n/datepicker-'.$lang_code.'.js'))
			return $lang_code;
	}
}

function directorypress_create_random_value($val = null) {
	if (!$val)
		return rand(1, 10000);
	else
		return $val;
}


function directorypress_is_maps_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('directorypress-maps/directorypress-maps.php', apply_filters('active_plugins', get_option('active_plugins')))) 
		return true;
}

function directorypress_is_payment_manager_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('directorypress-payment-manager/directorypress-payment-manager.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
		if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_addon']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_payments_addon'] == 'directorypress_woo_payment'){
			return true;
		}
	}
}

function directorypress_is_messages_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('directorypress-frontend-messages/directorypress-frontend-messages.php', apply_filters('active_plugins', get_option('active_plugins')))) 
		return true;
}

function directorypress_is_directorypress_twilio_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('directorypress-twilio/directorypress-twilio.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	$api_details = get_option('directorypress-twilio');
		if(is_array($api_details) AND count($api_details) != 0) {
			$TWILIO_SID = $api_details['api_sid'];
			$TWILIO_TOKEN = $api_details['api_auth_token'];
			$sender_id = $api_details['sender_id'];
			if(!empty($TWILIO_SID) && !empty($TWILIO_TOKEN) && !empty($sender_id)){
				return true;
			}
		}
		return true;
	}
}

function directorypress_is_elementor_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) 
		return true;
}
function directorypress_is_dpwcfm_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('directorypress-wcfm-integration/directorypress-wcfm-integration.php', apply_filters('active_plugins', get_option('active_plugins')))) 
		return true;
}

function directorypress_is_diotp_active() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('di-otp/di-otp.php', apply_filters('active_plugins', get_option('active_plugins')))
	) 
		return true;
}

function directorypress_directory_type_of_listing($listing_id) {
	global $directorypress_object;

	if (get_post_type($listing_id) == DIRECTORYPRESS_POST_TYPE) {
		if ($directory_id = get_post_meta($listing_id, '_directory_id', true)) {
			if ($directorytype = $directorypress_object->directorytypes->directory_by_id($directory_id)) {
				return $directorytype;
			}
		}
	} elseif ($directorypress_object->current_directorytype) {
		return $directorypress_object->current_directorytype;
	}
	return $directorypress_object->directorytypes->directorypress_get_base_directorytype();
}

function directorypress_wrapKeys(&$val) {
	$val = "`".$val."`";
}

function directorypress_wrapValues(&$val) {
	$val = "'".$val."'";
}

function directorypress_wrapIntVal(&$val) {
	$val = intval($val);
}

// NEEDS TO REMOVE 
function directorypress_is_category() {
	global $directorypress_object;

	if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE))) {
		if ($directorypress_directory_handler->is_category) {
			return $directorypress_directory_handler->category;
		}
	}
}


add_action ('directorypress_redirect_home_page', 'directorypress_redirect_home');
function directorypress_redirect_home() {
    wp_redirect(home_url('/'));
	exit;
}  

if (!function_exists('directorypress_global_get_post_id')) {
     function directorypress_global_get_post_id()
     {
          if(function_exists('is_woocommerce') && is_woocommerce() && is_shop()) {

              return wc_get_page_id( 'shop' );

          } else if(is_singular()) {

            global $post;

            return $post->ID;

          }else {

            return false;
          }
     }
}

if( !function_exists('directorypress_handle_image_upload') ){
	function directorypress_handle_image_upload( $file, $attach_to = 0 ){
		$movefile = wp_handle_upload( $file, array( 'test_form' => false ) );

		if( !empty( $movefile['url'] ) ){
			$attachment = array(
				'guid'           => $movefile['url'],
				'post_mime_type' => $movefile['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $movefile['file'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $movefile['file'], $attach_to );

			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			return $attach_id;
		}
	}
}
global $DIRECTORYPRESS_ADIMN_SETTINGS;
if(!empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_email'])){
	update_option('admin_email', $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_admin_email']);
}

add_action('admin_post_directorypress_purge_cache', 'directorypress_purge_cache');
add_action ('redux/options/directorypress_admin_settings/saved', 'directorypress_purge_cache_actions');

function directorypress_purge_cache() {
    if (isset($_GET['action'], $_GET['_wpnonce'])) {
        
        if (!wp_verify_nonce($_GET['_wpnonce'], 'directorypress_purge_cache')) {
            wp_nonce_ays('');
        }
        directorypress_purge_cache_actions();
        
        /* purge wp super cache */
        if(function_exists('wp_cache_clear_cache')) {
          wp_cache_clear_cache();  
        }
        
        wp_redirect(wp_get_referer());
        die();
    }
}

function directorypress_purge_cache_actions() {
    global $wpdb;
    
    $wpdb->query($wpdb->prepare("
                 DELETE FROM $wpdb->postmeta
                 WHERE meta_key = %s
                ", '_directorypress_dynamic_styles'));
    $static = new DirectoryPress_Static_Files(false);
    $static->DeleteThemeOptionStyles();
}

function setLocationDefault($listing) {
		global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS;
		
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->directorypress_locations_relation} WHERE post_id=".$listing->post->ID, ARRAY_A);
		
		foreach ($results AS $row) {
			if ($row['location_id'] || $row['address_line_1']) {
				$location_settings = array(
						'id' => $row['id'],
						'address_line_1' => $row['address_line_1'],
				);
				
				$location_settings = apply_filters('directorypress_listing_locations', $location_settings, $listing);
				
				//$location->create_location_from_array($location_settings);
				
				return $row['address_line_1'];
			}
		}
}
add_filter('setlocation', 'setLocationDefault', 10, 2);
	

function directorypress_has_recaptcha() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_recaptcha'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_private_key']) {
		return '<div class="g-recaptcha" data-sitekey="'.$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'].'"></div>';
	}
}

function directorypress_recaptcha_validated() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_recaptcha'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_public_key'] && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_private_key']) {
		if (isset($_POST['g-recaptcha-response']))
			$captcha = sanitize_text_field($_POST['g-recaptcha-response']);
		else
			return false;
		
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=".$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_has_recaptcha_private_key']."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		if (!is_wp_error($response)) {
			$body = wp_remote_retrieve_body($response);
			$json = json_decode($body);
			if ($json->success === false)
				return false;
			else
				return true;
		} else
			return false;
	} else
		return true;
}

function directorypress_404_defirect() {
	status_header(404);
	nocache_headers();
	include(get_404_template());
	exit;
}

function directorypress_getVimeoThumb($id, $size)
{
	$vimeo = unserialize(wp_remote_get("https://vimeo.com/api/v2/video/$id.php"));
	if($size == 'large'){
		$thumb = $vimeo[0]['thumbnail_large'];
	}elseif($size == 'large'){
		$thumb = $vimeo[0]['thumbnail_medium'];
	}else{
		$thumb = $vimeo[0]['thumbnail_small'];
	}
	return $thumb;
}

function directorypress_is_base64_encoded($data)
{
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
       return TRUE;
    } else {
       return FALSE;
    }
};
<?php 
/*
	Plugin Name: Simple Login Notification
	Plugin URI: https://perishablepress.com/simple-login-notification/
	Description: Sends an email when any admin-level user logs in to your site.
	Tags: email notification, admin login notification, email notify on admin login, login notification
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 5.3
	Tested up to: 6.5
	Stable tag: 1.7
	Version:    1.7
	Requires PHP: 5.6.20
	Text Domain: simple-login-notification
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2024 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();


if (!defined('SIMPLE_LOGIN_NOTIFICATION_VERSION')) define('SIMPLE_LOGIN_NOTIFICATION_VERSION', '1.7');
if (!defined('SIMPLE_LOGIN_NOTIFICATION_REQUIRE')) define('SIMPLE_LOGIN_NOTIFICATION_REQUIRE', '5.3');
if (!defined('SIMPLE_LOGIN_NOTIFICATION_URL'))     define('SIMPLE_LOGIN_NOTIFICATION_URL',     plugin_dir_url(__FILE__));
if (!defined('SIMPLE_LOGIN_NOTIFICATION_FILE'))    define('SIMPLE_LOGIN_NOTIFICATION_FILE',    plugin_basename(__FILE__));
if (!defined('SIMPLE_LOGIN_NOTIFICATION_NAME'))    define('SIMPLE_LOGIN_NOTIFICATION_NAME',    __('Simple Login Notification', 'simple-login-notification'));


function simple_login_notification($login, $user) {
	
	if (!isset($user->roles) || !is_array($user->roles) || empty($user->roles)) return;
	
	$current_role = (array) $user->roles;
	
	foreach($current_role as $role) {
		
		if (in_array($role, simple_login_notification_get_roles())) {
			
			if (simple_login_notification_check_ip()) {
				
				simple_login_notification_email($login, $role);
				
				return;
				
			}
			
		}
		
	}
	
}
add_action('wp_login', 'simple_login_notification', 10, 2);


function simple_login_notification_email($login, $role) {
	
	$name = apply_filters('simple_login_notification_site', get_bloginfo('name'));
	
	$subject = simple_login_notification_format_role($role) .' '. __('Login @ ', 'simple-login-notification') . $name;
	
	$subject = apply_filters('simple_login_notification_subject', $subject);
	
	$message = simple_login_notification_message($login, $name, $role);
	
	//
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$email_adds = isset($options['email_adds']) ? $options['email_adds'] : '';
	
	$email_adds = $email_adds ? array_map('trim', explode(',', $email_adds)) : array();
	
	$email_adds[] = get_bloginfo('admin_email');
	
	if ($email_adds) {
		
		foreach($email_adds as $address) {
			
			wp_mail($address, $subject, $message, 'From: '. $address);
			
		}
		
	}
	
}


function simple_login_notification_message($login, $name, $role) {
	
	$date_format = get_option('date_format');
	$time_format = get_option('time_format');
	
	$format = $date_format .' @ '. $time_format;
	
	$date = current_datetime()->format($format);
	
	$message  = simple_login_notification_format_role($role) .' '. __('logged in at ', 'simple-login-notification') . $name . __(' on ', 'simple-login-notification') . $date . "\n\n";
	
	$message .= __('LOGIN NAME: ', 'simple-login-notification') . $login . "\n";
	
	foreach (simple_login_notification_vars() as $key => $val) {
		
		$message .= $key .': '. $val . "\n";
		
	}
	
	$message .= "\n" . __('Visit site: ', 'simple-login-notification') . get_bloginfo('url') . "\n\n";
	
	$message .= __('This email alert is sent via the WordPress plugin, Simple Login Notification.', 'simple-login-notification');
	
	return $message;
	
}


function simple_login_notification_check_ip() {
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$whitelist = (isset($options['exclude_ips']) && !empty($options['exclude_ips'])) ? $options['exclude_ips'] : '';
	
	$whitelist = array_filter(array_map('trim', explode(',', $whitelist)));
	
	$wildcard = apply_filters('simple_login_notification_wildcard', ''); // use $ to disable wildcard matching
	
	$ip_address = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : 'undefined';
	
	$ip_address = filter_var($ip_address, FILTER_VALIDATE_IP) ? $ip_address : null;
	
	//
	
	if ($ip_address) {
		
		foreach ($whitelist as $ip) {
			
			if (strpos($ip, '/') === false) {
				
				if (empty($wildcard)) {
					
					if (substr($ip_address, 0, strlen($ip)) === $ip) {
						
						return false;
						
					}
					
				} elseif ($wildcard === '$') {
					
					if ($ip_address === $ip) {
						
						return false;
						
					}
					
				}
				
			} else {
				
				if (simple_login_notification_ip_in_range($ip_address, $ip)) {
					
					return false;
					
				}
				
			}
			
		}
		
	}
	
	return true;
	
}

		
function simple_login_notification_ip_in_range($ip, $range) {
	
	list($range, $netmask) = explode('/', $range, 2);
	
	$range_decimal = ip2long($range);
	
	$ip_decimal = ip2long($ip);
	
	$wildcard_decimal = pow(2, (32 - $netmask)) - 1;
	
	$netmask_decimal = ~ $wildcard_decimal;
	
	return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
	
}


function simple_login_notification_vars() {
	
	if (!isset($_SERVER)) return;
	
	$undefined = __('undefined', 'simple-login-notification');
	
	$request   = isset($_SERVER['REQUEST_URI'])     ? sanitize_text_field($_SERVER['REQUEST_URI'])     : $undefined;
	$query     = isset($_SERVER['QUERY_STRING'])    ? sanitize_text_field($_SERVER['QUERY_STRING'])    : $undefined;
	$referer   = isset($_SERVER['HTTP_REFERER'])    ? sanitize_text_field($_SERVER['HTTP_REFERER'])    : $undefined;
	$agent     = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : $undefined;
	$server    = isset($_SERVER['SERVER_NAME'])     ? sanitize_text_field($_SERVER['SERVER_NAME'])     : $undefined;
	$http_host = isset($_SERVER['HTTP_HOST'])       ? sanitize_text_field($_SERVER['HTTP_HOST'])       : $undefined;
	
	$ip_remote = isset($_SERVER['REMOTE_ADDR'])          ? sanitize_text_field($_SERVER['REMOTE_ADDR'])          : $undefined;
	$ip_client = isset($_SERVER['HTTP_CLIENT_IP'])       ? sanitize_text_field($_SERVER['HTTP_CLIENT_IP'])       : $undefined;
	$ip_forwrd = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']) : $undefined;
	
	$host_remote = (filter_var($ip_remote, FILTER_VALIDATE_IP)) ? sanitize_text_field(@gethostbyaddr($ip_remote)) : $undefined;
	$host_client = (filter_var($ip_client, FILTER_VALIDATE_IP)) ? sanitize_text_field(@gethostbyaddr($ip_client)) : $undefined;
	$host_forwrd = (filter_var($ip_forwrd, FILTER_VALIDATE_IP)) ? sanitize_text_field(@gethostbyaddr($ip_forwrd)) : $undefined;
	
	$query = !empty($query) ? $query : $undefined;
	
	return array(
		
		__('REQUEST URI',  'simple-login-notification') => $request, 
		__('QUERY',        'simple-login-notification') => $query, 
		__('REFERRER',     'simple-login-notification') => $referer, 
		__('USER AGENT',   'simple-login-notification') => $agent, 
		__('SERVER',       'simple-login-notification') => $server, 
		__('HTTP HOST',    'simple-login-notification') => $http_host, 
		
		__('IP REMOTE',    'simple-login-notification') => $ip_remote, 
		__('IP CLIENT',    'simple-login-notification') => $ip_client, 
		__('IP FORWARD',   'simple-login-notification') => $ip_forwrd, 
		
		__('HOST REMOTE',  'simple-login-notification') => $host_remote, 
		__('HOST CLIENT',  'simple-login-notification') => $host_client, 
		__('HOST FORWARD', 'simple-login-notification') => $host_forwrd,
		
	);
	
}


function simple_login_notification_default_roles() {
	
	if (!function_exists('get_editable_roles')) require_once ABSPATH .'wp-admin/includes/user.php';

	$editable_roles = get_editable_roles();
	
	$roles = array();
	
	foreach ($editable_roles as $key => $value) {
		
		if (is_array($value)) {
			
			$name = isset($value['name']) ? $value['name'] : $key;
			
		}
		
		$roles[$key] = $name;
		
	}
	
	return apply_filters('simple_login_notification_roles', $roles);
	
}


function simple_login_notification_get_roles() {
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$roles = (isset($options['roles']) && !empty($options['roles'])) ? $options['roles'] : array();
	
	return $roles;
	
}

function simple_login_notification_format_role($role) {
	
	return (strtolower($role) === 'administrator') ? __('Admin', 'simple-login-notification') : ucwords($role);
	
}


//


function simple_login_notification_check_version() {
	
	$wp_version = get_bloginfo('version');
	
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		
		if (version_compare($wp_version, SIMPLE_LOGIN_NOTIFICATION_REQUIRE, '<')) {
			
			if (is_plugin_active(SIMPLE_LOGIN_NOTIFICATION_FILE)) {
				
				deactivate_plugins(SIMPLE_LOGIN_NOTIFICATION_FILE);
				
				$msg  = '<strong>'. SIMPLE_LOGIN_NOTIFICATION_NAME .'</strong> '. esc_html__('requires WordPress ', 'simple-login-notification') . SIMPLE_LOGIN_NOTIFICATION_REQUIRE;
				$msg .= esc_html__(' or higher, and has been deactivated! ', 'simple-login-notification');
				$msg .= esc_html__('Please return to the', 'simple-login-notification') .' <a href="'. admin_url() .'">';
				$msg .= esc_html__('WP Admin Area', 'simple-login-notification') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'simple-login-notification');
				
				wp_die($msg);
				
			}
			
		}
		
	}
	
}
add_action('admin_init', 'simple_login_notification_check_version');


function simple_login_notification_load_i18n() {
	
	$domain = 'simple-login-notification';
	
	$locale = apply_filters('simple_login_notification_locale', get_locale(), $domain);
	
	$dir    = trailingslashit(WP_LANG_DIR);
	
	$file   = $domain .'-'. $locale .'.mo';
	
	$path_1 = $dir . $file;
	
	$path_2 = $dir . $domain .'/'. $file;
	
	$path_3 = $dir .'plugins/'. $file;
	
	$path_4 = $dir .'plugins/'. $domain .'/'. $file;
	
	$paths = array($path_1, $path_2, $path_3, $path_4);
	
	foreach ($paths as $path) {
		
		if ($loaded = load_textdomain($domain, $path)) {
			
			return $loaded;
			
		} else {
			
			return load_plugin_textdomain($domain, false, dirname(SIMPLE_LOGIN_NOTIFICATION_FILE) .'/languages/');
			
		}
		
	}
	
}
add_action('init', 'simple_login_notification_load_i18n');


function simple_login_notification_admin_scripts($hook) {
	
	if ($hook === 'settings_page_simple-login-notification') {
		
		wp_enqueue_style('wp-jquery-ui-dialog');
		
		// wp_enqueue_style($handle, $src, $deps, $ver, $media)
		
		wp_enqueue_style('simple-login-notification', SIMPLE_LOGIN_NOTIFICATION_URL .'assets/settings.css', array('dashicons'), SIMPLE_LOGIN_NOTIFICATION_VERSION);
		
		// wp_enqueue_script($handle, $src, $deps, $ver, $in_footer)
		
		wp_enqueue_script('simple-login-notification', SIMPLE_LOGIN_NOTIFICATION_URL .'assets/settings.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), SIMPLE_LOGIN_NOTIFICATION_VERSION);
		
	}
	
}
add_action('admin_enqueue_scripts', 'simple_login_notification_admin_scripts');


function simple_login_notification_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if (property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}


function simple_login_notification_admin_print_scripts() {
	
	$screen_id = simple_login_notification_get_current_screen_id();
	
	if ($screen_id === 'settings_page_simple-login-notification') : 
	
	?>
	
	<script>
		var 
		simple_login_notification_reset_title   = '<?php _e('Confirm Reset',            'simple-login-notification'); ?>',
		simple_login_notification_reset_message = '<?php _e('Restore default options?', 'simple-login-notification'); ?>',
		simple_login_notification_reset_true    = '<?php _e('Yes, make it so.',         'simple-login-notification'); ?>',
		simple_login_notification_reset_false   = '<?php _e('No, abort mission.',       'simple-login-notification'); ?>';
	</script>
	
	<?php endif;
	
}
add_action('admin_print_scripts', 'simple_login_notification_admin_print_scripts');


function simple_login_notification_action_links($links, $file) {
	
	if ($file === SIMPLE_LOGIN_NOTIFICATION_FILE && (current_user_can('manage_options'))) {
		
		$add_links = '<a href="'. admin_url('options-general.php?page=simple-login-notification') .'">'. esc_html__('Settings', 'simple-login-notification') .'</a>';
		
		array_unshift($links, $add_links);
		
	}
	
	return $links;
	
}
add_filter('plugin_action_links', 'simple_login_notification_action_links', 10, 2);


function simple_login_notification_plugin_links($links, $file) {
	
	if ($file === SIMPLE_LOGIN_NOTIFICATION_FILE) {
		
		$home_href  = 'https://perishablepress.com/simple-login-notification/';
		$home_title = esc_attr__('Plugin Homepage', 'simple-login-notification');
		$home_text  = esc_html__('Homepage', 'simple-login-notification');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$rate_href  = 'https://wordpress.org/support/plugin/simple-login-notification/reviews/?rate=5#new-post';
		$rate_title = esc_attr__('Click here to rate and review this FREE plugin at WordPress.org!', 'simple-login-notification');
		$rate_text  = esc_html__('Rate this plugin', 'simple-login-notification') .'&nbsp;&raquo;';
		
		$links[]    = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
		
	}
	
	return $links;
	
}
add_filter('plugin_row_meta', 'simple_login_notification_plugin_links', 10, 2);


function simple_login_notification_footer_text($text) {
	
	$screen_id = simple_login_notification_get_current_screen_id();
	
	$ids = array('settings_page_simple-login-notification');
	
	if ($screen_id && apply_filters('simple_login_notification_footer_text', in_array($screen_id, $ids))) {
		
		$text = __('Like this plugin? Give it a', 'simple-login-notification');
		
		$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/simple-login-notification/reviews/?rate=5#new-post">';
		
		$text .= __('★★★★★ rating&nbsp;&raquo;', 'simple-login-notification') .'</a>';
		
	}
	
	return $text;
	
}
add_filter('admin_footer_text', 'simple_login_notification_footer_text', 10, 1);


function simple_login_notification_add_menu() {
	
	$title = esc_html__('Login Notification', 'simple-login-notification');
	
	// add_options_page($page_title, $menu_title, $capability, $menu_slug, $callback, $position)
	
	add_options_page($title, $title, 'manage_options', 'simple-login-notification', 'simple_login_notification_display_settings');
	
}
add_action('admin_menu', 'simple_login_notification_add_menu');


function simple_login_notification_add_settings() {
	
	// register_setting($option_group, $option_name, $args)
	
	register_setting('simple_login_notification_options', 'simple_login_notification_options', 'simple_login_notification_validate_settings');
	
	// add_settings_section($id, $title, $callback, $page)
	
	add_settings_section('general', esc_html__('General Settings', 'simple-login-notification'), 'simple_login_notification_section_general', 'simple_login_notification_options');
	
	// add_settings_field($id, $title, $callback, $page, $section, $args)
	
	$roles_label   = esc_html__('Select roles to include in email alerts:', 'simple-login-notification');
	
	$exclude_label = esc_html__('Ignore these IP addresses (separate multiple w/ commas).', 'simple-login-notification') .' <a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/tools/ip/">'. esc_html__('Get your current IP address&nbsp;&raquo;', 'simple-login-notification') .'</a>';
	
	add_settings_field('roles',         esc_html__('User Roles',    'simple-login-notification'), 'simple_login_notification_callback_checkboxes', 'simple_login_notification_options', 'general', array('id' => 'roles',         'label' => $roles_label));
	add_settings_field('email_adds',    esc_html__('Extra Emails',  'simple-login-notification'), 'simple_login_notification_callback_textarea',   'simple_login_notification_options', 'general', array('id' => 'email_adds',    'label' => esc_html__('Send notifications to additional email addresses (separate multiple w/ commas)', 'simple-login-notification')));
	add_settings_field('exclude_ips',   esc_html__('Exclude IPs',   'simple-login-notification'), 'simple_login_notification_callback_textarea',   'simple_login_notification_options', 'general', array('id' => 'exclude_ips',   'label' => $exclude_label));
	add_settings_field('reset_options', esc_html__('Reset Options', 'simple-login-notification'), 'simple_login_notification_callback_reset',      'simple_login_notification_options', 'general', array('id' => 'reset_options', 'label' => esc_html__('Restore default plugin options', 'simple-login-notification')));
	add_settings_field('link_rate',     esc_html__('Rate Plugin',   'simple-login-notification'), 'simple_login_notification_callback_rate',       'simple_login_notification_options', 'general', array('id' => 'link_rate',     'label' => esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'simple-login-notification')));
	
	add_settings_field('show_support',  esc_html__('Show Support',  'simple-login-notification'), 'simple_login_notification_callback_support',  'simple_login_notification_options', 'general', array('id' => 'show_support',  'label' => esc_html__('Show support with a small donation&nbsp;&raquo;', 'simple-login-notification')));
	
}
add_filter('admin_init', 'simple_login_notification_add_settings');


function simple_login_notification_validate_settings($input) {
	
	if (isset($input['roles']) && is_array($input['roles'])) {
		
		$input['roles'] = array_unique($input['roles']);
		
	}
	
	$input['exclude_ips'] = sanitize_text_field($input['exclude_ips']);
	
	if (isset($input['email_adds'])) $input['email_adds'] = wp_filter_nohtml_kses($input['email_adds']);
	else $input['email_adds'] = null;
	
	return $input;
	
}


function simple_login_notification_default_options() {
	
	$options = array(
		
		'exclude_ips' => '',
		'roles'       => array('administrator'),
		'email_adds'  => '',
		
	);
	
	return apply_filters('simple_login_notification_default_options', $options);
	
}


function simple_login_notification_section_general() {
	
	echo '<p>'. esc_html__('This plugin sends a detailed email alert each time an admin-level user logs in to your site.', 'simple-login-notification');
	
	echo ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/simple-login-notification/#installation">'. esc_html__('Visit plugin docs&nbsp;&raquo;', 'simple-login-notification') .'</a></p>';
	
}


function simple_login_notification_callback_text($args) {
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'simple_login_notification_options['. $id .']';
	
	echo '<input id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" type="text" size="40" class="regular-text" value="'. esc_attr($value) .'">';
	echo '<label for="'. esc_attr($name) .'">'. esc_html($label) .'</label>';
	
}


function simple_login_notification_callback_textarea($args) {
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$value = isset($options[$id]) ? $options[$id] : '';
	
	$name = 'simple_login_notification_options['. $id .']';
	
	echo '<textarea id="'. esc_attr($name) .'" name="'. esc_attr($name) .'" rows="3" cols="50" class="large-text code">'. esc_html($value) .'</textarea>';
	
	echo '<label for="'. esc_attr($name) .'">'. $label .'</label>';
	
}


function simple_login_notification_callback_checkboxes($args) {
	
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	$default = simple_login_notification_default_options();
	
	$options = get_option('simple_login_notification_options', $default);
	
	$value = isset($options[$id]) ? $options[$id] : array();
	
	$roles = simple_login_notification_default_roles();
	
	echo '<p>'. $label .'</p>';
	
	echo '<ul class="settings-list">';
	
	foreach ($roles as $role_id => $role_name) {
		
		$role_name = sprintf(__('%s', 'simple-login-notification'), $role_name);
		
		$name = 'simple_login_notification_options['. $id .']['. $role_id .']';
		
		echo '<li><input type="checkbox" name="'. esc_attr($name) .'" id="'. esc_attr($name) .'" '. checked(true, in_array($role_id, $value), false) .' value="'. esc_attr($role_id) .'"> ';
		
		echo '<label class="inline-block" for="'. esc_attr($name) .'">'. esc_html($role_name) .'</label></li>';
		
	}
	
	echo '</ul>';
	
}


function simple_login_notification_callback_reset($args) {
	
	$nonce = wp_create_nonce('simple-login-notification-reset-options');
	
	$href  = add_query_arg(array('simple-login-notification-reset-options' => $nonce), admin_url('options-general.php?page=simple-login-notification'));
	
	$label = isset($args['label']) ? $args['label'] : esc_html__('Restore default plugin options', 'simple-login-notification');
	
	echo '<a class="simple-login-notification-reset-options" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}


function simple_login_notification_callback_rate($args) {
	
	$href  = 'https://wordpress.org/support/plugin/simple-login-notification/reviews/?rate=5#new-post';
	
	$title = __('Show support for this FREE plugin! THANK YOU in advance :)', 'simple-login-notification');
	
	$text  = isset($args['label']) ? $args['label'] : __('Show support with a 5-star rating&nbsp;&raquo;', 'simple-login-notification');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="simple-login-notification-rate-plugin" href="'. esc_url($href) .'" title="'. esc_attr($title) .'">'. esc_html($text) .'</a>';
	
}


function simple_login_notification_callback_support($args) {
	
	$href  = 'https://monzillamedia.com/donate.html';
	
	$title = __('Donate via PayPal, credit card, or cryptocurrency', 'simple-login-notification');
	
	$text  = isset($args['label']) ? $args['label'] : __('Show support with a small donation&nbsp;&raquo;', 'simple-login-notification');
	
	echo '<a target="_blank" rel="noopener noreferrer" class="simple-login-notification-show-support" href="'. esc_url($href) .'" title="'. esc_attr($title) .'">'. esc_html($text) .'</a>';
	
}


function simple_login_notification_display_settings() {
	
	?>
	
	<div class="wrap">
		<h1>
			<span class="dashicons dashicons-email"></span> <?php echo SIMPLE_LOGIN_NOTIFICATION_NAME; ?> 
			<span class="simple-login-notification-version"><?php echo SIMPLE_LOGIN_NOTIFICATION_VERSION; ?></span>
		</h1>
		<form method="post" action="options.php">
			
			<?php settings_fields('simple_login_notification_options'); ?>
			<?php do_settings_sections('simple_login_notification_options'); ?>
			<?php submit_button(); ?>
			
		</form>
	</div>
	
	<?php
	
}


function simple_login_notification_admin_notices() {
			
	$screen_id = simple_login_notification_get_current_screen_id();
	
	if ($screen_id === 'settings_page_simple-login-notification') {
		
		if (isset($_GET['simple-login-notification-reset-options'])) {
			
			if ($_GET['simple-login-notification-reset-options'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Default options restored.', 'simple-login-notification'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made to options.', 'simple-login-notification'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (!simple_login_notification_check_date_expired() && !simple_login_notification_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e('Go Pro!', 'simple-login-notification'); ?></strong> 
					<?php esc_html_e('Save 30% on our', 'simple-login-notification'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'simple-login-notification'); ?></a> 
					<?php esc_html_e('and', 'simple-login-notification'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/"><?php esc_html_e('books', 'simple-login-notification'); ?></a>. 
					<?php esc_html_e('Apply code', 'simple-login-notification'); ?> <code>PLANET24</code> <?php esc_html_e('at checkout. Sale ends 5/25/24.', 'simple-login-notification'); ?> 
					<?php echo simple_login_notification_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}
add_action('admin_notices', 'simple_login_notification_admin_notices');


//


function simple_login_notification_dismiss_notice_activate() {
	
	delete_option('simple-login-notification-dismiss-notice');
	
}
register_activation_hook(__FILE__, 'simple_login_notification_dismiss_notice_activate');


function simple_login_notification_dismiss_notice_version() {
	
	$version_current = SIMPLE_LOGIN_NOTIFICATION_VERSION;
	
	$version_previous = get_option('simple-login-notification-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('simple-login-notification-dismiss-notice');
		
	}
	
}
add_action('admin_init', 'simple_login_notification_dismiss_notice_version');


function simple_login_notification_dismiss_notice_check() {
	
	$check = get_option('simple-login-notification-dismiss-notice');
	
	return ($check) ? true : false;
	
}


function simple_login_notification_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'simple_login_notification_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('simple-login-notification-dismiss-notice', SIMPLE_LOGIN_NOTIFICATION_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=simple-login-notification&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
add_action('admin_init', 'simple_login_notification_dismiss_notice_save');


function simple_login_notification_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('simple_login_notification_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('options-general.php?page=simple-login-notification'));
	
	$label = esc_html__('Dismiss', 'simple-login-notification');
	
	echo '<a class="sln-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}


function simple_login_notification_check_date_expired() {
	
	$expires = apply_filters('simple_login_notification_check_date_expired', '2024-05-25');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}


//


function simple_login_notification_reset_options() {
	
	if (isset($_GET['simple-login-notification-reset-options']) && wp_verify_nonce($_GET['simple-login-notification-reset-options'], 'simple-login-notification-reset-options')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$update = delete_option('simple_login_notification_options');
		
		$result = $update ? 'true' : 'false';
		
		$location = add_query_arg(array('simple-login-notification-reset-options' => $result), admin_url('options-general.php?page=simple-login-notification'));
		
		wp_redirect(esc_url_raw($location));
		
		exit;
		
	}
	
}
add_action('admin_init', 'simple_login_notification_reset_options');

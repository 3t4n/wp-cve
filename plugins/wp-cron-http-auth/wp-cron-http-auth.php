<?php
/*
	Plugin Name: WP Cron HTTP Auth
	Plugin URI: https://perishablepress.com/wp-cron-http-auth/
	Description: Enable WP Cron on sites using HTTP Authentication
	Tags: wp cron, cron, http auth, http, auth
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.6
	Tested up to: 6.5
	Stable tag: 2.9
	Version:    2.9
	Requires PHP: 5.6.20
	Text Domain: wp-cron-http-auth
	Domain Path: /languages
	License: GPL v2 or later

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

if (!defined('ABSPATH')) exit;

if (!class_exists('WPCron_HTTPAuth')) {
	
	class WPCron_HTTPAuth {
		
		function __construct() {
			
			$this->constants();
			$this->includes();
			
			add_action('admin_menu',          array($this, 'add_menu'));
			add_action('admin_init',          array($this, 'add_settings'));
			add_action('admin_init',          array($this, 'check_version'));
			add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);
			add_filter('plugin_row_meta',     array($this, 'plugin_links'), 10, 2);
			add_filter('admin_footer_text',   array($this, 'footer_text'),  10, 1);
			add_action('init',                array($this, 'load_i18n'));
			
			add_filter('cron_request',        array($this, 'wp_cron_http_auth'));
		} 
		
		function constants() {
			
			if (!defined('WPCRONHTTPAUTH_VERSION')) define('WPCRONHTTPAUTH_VERSION', '2.9');
			if (!defined('WPCRONHTTPAUTH_REQUIRE')) define('WPCRONHTTPAUTH_REQUIRE', '4.6');
			if (!defined('WPCRONHTTPAUTH_AUTHOR'))  define('WPCRONHTTPAUTH_AUTHOR',  'Jeff Starr');
			if (!defined('WPCRONHTTPAUTH_NAME'))    define('WPCRONHTTPAUTH_NAME',    __('WP Cron HTTP Auth', 'wp-cron-http-auth'));
			if (!defined('WPCRONHTTPAUTH_HOME'))    define('WPCRONHTTPAUTH_HOME',    esc_url('https://perishablepress.com/wp-cron-http-auth/'));
			if (!defined('WPCRONHTTPAUTH_URL'))     define('WPCRONHTTPAUTH_URL',     plugin_dir_url(__FILE__));
			if (!defined('WPCRONHTTPAUTH_DIR'))     define('WPCRONHTTPAUTH_DIR',     plugin_dir_path(__FILE__));
			if (!defined('WPCRONHTTPAUTH_FILE'))    define('WPCRONHTTPAUTH_FILE',    plugin_basename(__FILE__));
			if (!defined('WPCRONHTTPAUTH_SLUG'))    define('WPCRONHTTPAUTH_SLUG',    basename(dirname(__FILE__)));
			
		}
		
		function includes() {
			
			// require_once WPCRONHTTPAUTH_DIR .'testing-wp-cron.php';
			
		}
		
		function add_menu() {
			
			$title_page = esc_html__('WP Cron HTTP Auth', 'wp-cron-http-auth');
			$title_menu = esc_html__('WP Cron HTTP Auth', 'wp-cron-http-auth');
			
			add_options_page($title_page, $title_menu, 'manage_options', 'wp-cron-http-auth', array($this, 'display_settings'));
			
		}
		
		function add_settings() { // wp-cron-http-auth
			
			register_setting('wpcron_httpauth_options', 'wpcron_httpauth_options', array($this, 'validate_settings'));
			
			add_settings_section('general', esc_html__('Plugin Usage', 'wp-cron-http-auth'), array($this, 'section_general'), 'wpcron_httpauth_options');
			
			add_settings_field('auth_username', esc_html__('HTTP Auth Username', 'wp-cron-http-auth'), array($this, 'callback_text'),    'wpcron_httpauth_options', 'general', array('id' => 'auth_username', 'label' => esc_html__('HTTP Auth Username', 'wp-cron-http-auth')));
			add_settings_field('auth_password', esc_html__('HTTP Auth Password', 'wp-cron-http-auth'), array($this, 'callback_text'),    'wpcron_httpauth_options', 'general', array('id' => 'auth_password', 'label' => esc_html__('HTTP Auth Password', 'wp-cron-http-auth')));
			add_settings_field('rate_plugin',   esc_html__('Rate Plugin',        'wp-cron-http-auth'), array($this, 'callback_rate'),    'wpcron_httpauth_options', 'general', array('id' => 'rate_plugin',   'label' => esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'wp-cron-http-auth')));
			add_settings_field('show_support',  esc_html__('Show Support',       'wp-cron-http-auth'), array($this, 'callback_support'), 'wpcron_httpauth_options', 'general', array('id' => 'show_support',  'label' => esc_html__('Show support with a small donation&nbsp;&raquo;', 'wp-cron-http-auth')));
			
		}
		
		function display_settings() {
			
			?>
			
			<div class="wrap">
				<h1><?php echo WPCRONHTTPAUTH_NAME; ?></h1>
				<form method="post" action="options.php">
					
					<?php settings_fields('wpcron_httpauth_options'); ?>
					<?php do_settings_sections('wpcron_httpauth_options'); ?>
					<?php submit_button(); ?>
					
				</form>
			</div>
			
			<?php
			
		}
		
		function section_general() {
			
			echo '<p>'. esc_html__('This plugin enables WP Cron on sites using HTTP Authentication. Enter your HTTP Auth credentials, save changes, and done.', 'wp-cron-http-auth') .'</p>';
			
		}
		
		function validate_settings($input) {
			
			if (isset($input['auth_username'])) $input['auth_username'] = esc_attr($input['auth_username']);
			else $input['auth_username'] = null;
			
			if (isset($input['auth_password'])) $input['auth_password'] = esc_attr($input['auth_password']);
			else $input['auth_password'] = null;
			
			return $input;
			
		}
		
		function callback_text($args) {
			
			$default = $this->options();
			
			$options = get_option('wpcron_httpauth_options', $default);
			
			$id    = isset($args['id'])    ? $args['id']    : '';
			$label = isset($args['label']) ? $args['label'] : '';
			
			$option = isset($options[$id]) ? $options[$id] : '';
			
			$config_vars = $this->config_vars();
			
			$auth_username = (isset($config_vars['auth_username']) && !empty($config_vars['auth_username'])) ? $config_vars['auth_username'] : null;
			$auth_password = (isset($config_vars['auth_password']) && !empty($config_vars['auth_password'])) ? $config_vars['auth_password'] : null;
			
			if ($id === 'auth_username') {
				
				$type   = 'text';
				$option = (!empty($auth_username)) ? $auth_username : $option;
				$read   = (!empty($auth_username)) ? ' readonly' : '';
				$desc   = (!empty($auth_username)) ? 'Username set in wp-config.php' : '';
				
			} else {
				
				$type   = 'password';
				$option = (!empty($auth_password)) ? $auth_password : $option;
				$read   = (!empty($auth_password)) ? ' readonly' : '';
				$desc   = (!empty($auth_password)) ? 'Password set in wp-config.php' : '';
				
			}
			
			echo '<label><input name="wpcron_httpauth_options['. esc_attr($id) .']" type="'. esc_attr($type) .'" class="regular-text" value="'. esc_attr($option) .'"'. $read .'></label> ';
			echo '<span class="input-desc" style="font-size:80%;color:#5C8D6A;">'. $desc .'</span>';
			
		}
		
		function callback_rate($args) {
			
			$href  = 'https://wordpress.org/support/plugin/'. WPCRONHTTPAUTH_SLUG .'/reviews/?rate=5#new-post';
			$title = esc_attr__('Please give a 5-star rating! A huge THANK YOU for your support!', 'wp-cron-http-auth');
			$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a 5-star rating&nbsp;&raquo;', 'wp-cron-http-auth');
			
			echo '<a target="_blank" rel="noopener noreferrer" class="wp-cron-http-auth-rate-plugin" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
			
		}
		
		function callback_support($args) {
			
			$href  = 'https://monzillamedia.com/donate.html';
			$title = esc_attr__('Donate via PayPal, credit card, or cryptocurrency', 'wp-cron-http-auth');
			$text  = isset($args['label']) ? $args['label'] : esc_html__('Show support with a small donation&nbsp;&raquo;', 'wp-cron-http-auth');
			
			echo '<a target="_blank" rel="noopener noreferrer" class="wp-cron-http-auth-show-support" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
			
		}
		
		function options() {
			
			$options = array(
				
				'auth_username' => '',
				'auth_password' => '',
				
			);
			
			return apply_filters('wpcron_httpauth_options', $options);
			
		}
		
		function action_links($links, $file) {
			
			if ($file === WPCRONHTTPAUTH_FILE && current_user_can('manage_options')) {
				
				$settings = '<a href="'. admin_url('options-general.php?page=wp-cron-http-auth') .'">'. esc_html__('Settings', 'wp-cron-http-auth') .'</a>';
				
				array_unshift($links, $settings);
				
			}
			
			return $links;
			
		}
		
		function plugin_links($links, $file) {
			
			if ($file === WPCRONHTTPAUTH_FILE) {
				
				$home_href  = 'https://perishablepress.com/wp-cron-http-auth/';
				$home_title = esc_attr__('Plugin Homepage', 'wp-cron-http-auth');
				$home_text  = esc_html__('Homepage', 'wp-cron-http-auth');
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
				
				$rate_href  = 'https://wordpress.org/support/plugin/'. WPCRONHTTPAUTH_SLUG .'/reviews/?rate=5#new-post';
				$rate_title = esc_attr__('Click here to rate and review this plugin on WordPress.org', 'wp-cron-http-auth');
				$rate_text  = esc_html__('Rate this plugin', 'wp-cron-http-auth') .'&nbsp;&raquo;';
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
				
			}
			
			return $links;
			
		}
		
		function footer_text($text) {
			
			$screen_id = $this->screen_id();
			
			$ids = array('settings_page_wp-cron-http-auth');
			
			if ($screen_id && apply_filters('wp_cron_http_auth_admin_footer_text', in_array($screen_id, $ids))) {
				
				$text = __('Like this plugin? Give it a', 'wp-cron-http-auth');
				
				$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/wp-cron-http-auth/reviews/?rate=5#new-post">';
				
				$text .= __('★★★★★ rating&nbsp;&raquo;', 'wp-cron-http-auth') .'</a>';
				
			}
			
			return $text;
			
		}
		
		function screen_id() {
			
			if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
			
			$screen = get_current_screen();
			
			if ($screen && property_exists($screen, 'id')) return $screen->id;
			
			return false;
			
		}
		
		function check_version() {
			
			$wp_version = get_bloginfo('version');
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				
				if (version_compare($wp_version, WPCRONHTTPAUTH_REQUIRE, '<')) {
					
					if (is_plugin_active(WPCRONHTTPAUTH_FILE)) {
						
						deactivate_plugins(WPCRONHTTPAUTH_FILE);
						
						$msg  = '<strong>'. WPCRONHTTPAUTH_NAME .'</strong> '. esc_html__('requires WordPress ', 'wp-cron-http-auth') . WPCRONHTTPAUTH_REQUIRE;
						$msg .= esc_html__(' or higher, and has been deactivated! ', 'wp-cron-http-auth');
						$msg .= esc_html__('Please return to the', 'wp-cron-http-auth') .' <a href="'. admin_url() .'">';
						$msg .= esc_html__('WP Admin Area', 'wp-cron-http-auth') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'wp-cron-http-auth');
						
						wp_die($msg);
						
					}
					
				}
				
			}
			
		}
		
		function wp_cron_http_auth($cron_request) {
			
			$config_vars = $this->config_vars();
			
			$auth_username = (isset($config_vars['auth_username']) && !empty($config_vars['auth_username'])) ? $config_vars['auth_username'] : null;
			$auth_password = (isset($config_vars['auth_password']) && !empty($config_vars['auth_password'])) ? $config_vars['auth_password'] : null;
			
			if (empty($auth_username) || empty($auth_password)) {
				
				$options = get_option('wpcron_httpauth_options', $this->options());
				
				$auth_username = (isset($options['auth_username']) && !empty($options['auth_username'])) ? $options['auth_username'] : null;
				$auth_password = (isset($options['auth_password']) && !empty($options['auth_password'])) ? $options['auth_password'] : null;
				
			}
			
			if ($auth_username && $auth_password) {
				
				$headers = array('Authorization' => sprintf('Basic %s', base64_encode($auth_username .':'. $auth_password)));
				
				$cron_request['args']['headers'] = isset($cron_request['args']['headers']) ? array_merge($cron_request['args']['headers'], $headers) : $headers;
				
			}
			
			return $cron_request;
			
		}
		
		function config_vars() {
			
			$auth_username = '';
			$auth_password = '';
			
			if (defined('WP_CRON_HTTP_AUTH_USERNAME') && !empty('WP_CRON_HTTP_AUTH_USERNAME')) $auth_username = WP_CRON_HTTP_AUTH_USERNAME;
			if (defined('WP_CRON_HTTP_AUTH_PASSWORD') && !empty('WP_CRON_HTTP_AUTH_PASSWORD')) $auth_password = WP_CRON_HTTP_AUTH_PASSWORD;
			
			return array('auth_username' => $auth_username, 'auth_password' => $auth_password);
			
		}
		
		function load_i18n() {
			
			load_plugin_textdomain('wp-cron-http-auth', false, dirname(WPCRONHTTPAUTH_FILE) .'/languages/');
			
		}
		
		function __clone() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'wp-cron-http-auth'), WPCRONHTTPAUTH_VERSION);
			
		}
		
		function __wakeup() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'wp-cron-http-auth'), WPCRONHTTPAUTH_VERSION);
			
		}
		
	}
	
	$WPCron_HTTPAuth = new WPCron_HTTPAuth(); 
	
}

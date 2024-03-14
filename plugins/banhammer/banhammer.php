<?php 
/*
	Plugin Name: Banhammer
	Plugin URI: https://perishablepress.com/banhammer/
	Description: Monitor traffic and ban unwanted visitors.
	Tags: monitor, security, ban, block, bots
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.6
	Tested up to: 6.5
	Stable tag: 3.4.2
	Version:    3.4.2
	Requires PHP: 5.6.20
	Text Domain: banhammer
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

if (!class_exists('BanhammerWP')) {
	
	class BanhammerWP {
		
		function __construct() {
			
			$this->constants();
			$this->includes();
			
			register_activation_hook  (__FILE__, array($this, 'create_table'));
			register_activation_hook  (__FILE__, 'banhammer_dismiss_notice_activate');
			register_activation_hook  (__FILE__, 'banhammer_cron_activation');
			register_deactivation_hook(__FILE__, 'banhammer_cron_deactivation');
			
			add_action('admin_init',                array($this, 'check_banhammer'));
			add_action('admin_init',                array($this, 'check_version'));
			add_action('init',                      array($this, 'load_i18n'));
			add_action('upgrader_process_complete', array($this, 'private_key'),  10, 2);
			add_filter('plugin_action_links',       array($this, 'action_links'), 10, 2);
			add_filter('plugin_row_meta',           array($this, 'plugin_links'), 10, 2);
			add_filter('admin_footer_text',         array($this, 'footer_text'),  10, 1);
			
			add_filter('removable_query_args',  'banhammer_remove_query_args');
			add_action('admin_enqueue_scripts', 'banhammer_admin_enqueue_scripts');
			add_action('admin_print_scripts',   'banhammer_admin_print_scripts');
			add_action('admin_notices',         'banhammer_admin_notices');
			add_action('admin_init',            'banhammer_register_settings');
			add_action('admin_init',            'banhammer_reset_options');
			add_action('admin_menu',            'banhammer_menu_pages');
			add_action('admin_init',            'banhammer_add_target');
			add_action('admin_init',            'banhammer_dismiss_notice_save');
			add_action('admin_init',            'banhammer_dismiss_notice_version');
			
			add_action('admin_init',            'banhammer_cron_update');
			add_filter('cron_schedules',        'banhammer_cron_intervals');
			add_action('banhammer_cron_reset',  'banhammer_cron_reset');
			
			add_action('wp_ajax_banhammer_armory', 'banhammer_armory');
			add_action('wp_ajax_banhammer_tower',  'banhammer_tower');
			add_action('wp_ajax_banhammer_aux',    'banhammer_aux');
			
			add_action('init', 'banhammer_init');
			
		}
		
		function constants() {
			
			if (!defined('BANHAMMER_VERSION')) define('BANHAMMER_VERSION', '3.4.2');
			if (!defined('BANHAMMER_REQUIRE')) define('BANHAMMER_REQUIRE', '4.6');
			if (!defined('BANHAMMER_AUTHOR'))  define('BANHAMMER_AUTHOR',  'Jeff Starr');
			if (!defined('BANHAMMER_NAME'))    define('BANHAMMER_NAME',    __('Banhammer', 'banhammer'));
			if (!defined('BANHAMMER_HOME'))    define('BANHAMMER_HOME',    esc_url('https://perishablepress.com/banhammer/'));
			if (!defined('BANHAMMER_URL'))     define('BANHAMMER_URL',     plugin_dir_url(__FILE__));
			if (!defined('BANHAMMER_DIR'))     define('BANHAMMER_DIR',     plugin_dir_path(__FILE__));
			if (!defined('BANHAMMER_FILE'))    define('BANHAMMER_FILE',    plugin_basename(__FILE__));
			if (!defined('BANHAMMER_SLUG'))    define('BANHAMMER_SLUG',    basename(dirname(__FILE__)));
			if (!defined('BANHAMMER_BLANK'))   define('BANHAMMER_BLANK',   __('[blank]', 'banhammer'));
			
		}
		
		function includes() {
			
			require_once BANHAMMER_DIR .'inc/banhammer-cron.php';
			require_once BANHAMMER_DIR .'inc/banhammer-functions.php';
			require_once BANHAMMER_DIR .'inc/banhammer-core.php';
			
			if (is_admin()) {
				
				require_once BANHAMMER_DIR .'inc/status-codes.php';
				require_once BANHAMMER_DIR .'inc/contextual-help.php';
				require_once BANHAMMER_DIR .'inc/resources-enqueue.php';
				require_once BANHAMMER_DIR .'inc/settings-display.php';
				require_once BANHAMMER_DIR .'inc/settings-register.php';
				require_once BANHAMMER_DIR .'inc/settings-reset.php';
				require_once BANHAMMER_DIR .'inc/armory-display.php';
				require_once BANHAMMER_DIR .'inc/armory-ajax.php';
				require_once BANHAMMER_DIR .'inc/tower-display.php';
				require_once BANHAMMER_DIR .'inc/tower-ajax.php';
				
			}
			
		}
		
		function options() {
			
			$options = array(
				
				'enable_plugin'   => true,
				'ignore_logged'   => true,
				'protect_login'   => false,
				'protect_admin'   => false,
				'banned_response' => 'default',
				'custom_message'  => '<p>You has been banned!</p>',
				'redirect_url'    => 'https://example.com/',
				'status_code'     => '403',
				'reset_interval'  => 'banhammer_one_day',
				'target_key'      => banhammer_get_random_alphanumeric(),
				
			);
			
			return apply_filters('banhammer_settings', $options);
			
		}
		
		function armory() {
			
			$options = array(
				
				'rows' => 5,
				'view' => 2,
				'fx'   => 0,
				
			);
			
			return apply_filters('banhammer_armory', $options);
			
		}
		
		function tower() {
			
			$options = array(
				array(
					'date'   => '2050-01-01 @ 01:01:01 ('. esc_html__('EXAMPLE ONLY: NOT A REAL IP ADDRESS!', 'banhammer') .')',
					'target' => '777.888.999.000',
					'status' => 1,
					'hits'   => 3,
				),
			);
			
			return apply_filters('banhammer_tower', $options);
			
		}
		
		function create_table() {
			
			global $wpdb;
			
			if (!current_user_can('activate_plugins')) return;
			
			$table   = $wpdb->prefix .'banhammer';
			
			$charset = $wpdb->get_charset_collate();
			
			$check   = $wpdb->get_var("SHOW TABLES LIKE '". $table ."'");
			
			if ($check !== $table) {
				
				$sql = "CREATE TABLE ". $table ." (
					
					id        MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
					date      VARCHAR(50) NOT NULL DEFAULT '',
					
					status    TINYINT UNSIGNED NOT NULL DEFAULT 0,
					process   TINYINT UNSIGNED NOT NULL DEFAULT 0,
					
					user      VARCHAR(50)    NOT NULL DEFAULT '',
					type      VARCHAR(25)    NOT NULL DEFAULT '',
					theme     VARCHAR(50)    NOT NULL DEFAULT '',
					code      VARCHAR(25)    NOT NULL DEFAULT '',
					country   VARCHAR(50)    NOT NULL DEFAULT '',
					region    VARCHAR(50)    NOT NULL DEFAULT '',
					city      VARCHAR(50)    NOT NULL DEFAULT '',
					zip       VARCHAR(25)    NOT NULL DEFAULT '',
					protocol  VARCHAR(25)    NOT NULL DEFAULT '',
					method    VARCHAR(25)    NOT NULL DEFAULT '',
					response  VARCHAR(25)    NOT NULL DEFAULT '',
					connect   VARCHAR(50)    NOT NULL DEFAULT '',
					domain    VARCHAR(250)   NOT NULL DEFAULT '',
					ip        VARCHAR(250)   NOT NULL DEFAULT '',
					proxy     VARCHAR(250)   NOT NULL DEFAULT '',
					host      VARCHAR(250)   NOT NULL DEFAULT '',
					request   VARCHAR(2500)  NOT NULL DEFAULT '',
					postvars  VARCHAR(2500)  NOT NULL DEFAULT '',
					files     VARCHAR(2500)  NOT NULL DEFAULT '',
					ua        VARCHAR(500)   NOT NULL DEFAULT '',
					refer     VARCHAR(500)   NOT NULL DEFAULT '',
					cookies   VARCHAR(1000)  NOT NULL DEFAULT '',
					headers   VARCHAR(1000)  NOT NULL DEFAULT '',
					message   VARCHAR(1000)  NOT NULL DEFAULT '',
					notes     VARCHAR(1000)  NOT NULL DEFAULT '',
					custom    VARCHAR(500)   NOT NULL DEFAULT '',
					data      VARCHAR(500)   NOT NULL DEFAULT '',
					aux       VARCHAR(100)   NOT NULL DEFAULT '',
					
					PRIMARY KEY (id)
					
				) ". $charset .";";
				
				require_once(ABSPATH .'wp-admin/includes/upgrade.php');
				
				dbDelta($sql);
				
			}
			
		}
		
		function private_key($upgrader_object, $options) {
			
			if ((isset($options['action']) && $options['action'] === 'update') && (isset($options['type']) && $options['type'] === 'plugin')) {
				
				if (isset($options['plugins'])) {
					
					foreach($options['plugins'] as $plugin) {
						
						if ($plugin === BANHAMMER_FILE) {
							
							$key = banhammer_get_secret_key(30, 'plugin upgrade');
							
						}
						
					}
					
				}
				
			}
			
		}
		
		function action_links($links, $file) {
			
			if ($file === BANHAMMER_FILE && current_user_can('manage_options')) {
				
				$settings = '<a href="'. admin_url('admin.php?page=banhammer') .'">'. esc_html__('Settings', 'banhammer') .'</a>';
				
				array_unshift($links, $settings);
				
			}
			
			if ($file === BANHAMMER_FILE) {
				
				$pro_href   = 'https://plugin-planet.com/banhammer-pro/';
				$pro_title  = esc_attr__('Get Banhammer Pro!', 'banhammer');
				$pro_text   = esc_html__('Go&nbsp;Pro', 'banhammer');
				$pro_style  = 'font-weight:bold;';
				
				$pro = '<a target="_blank" rel="noopener noreferrer" href="'. $pro_href .'" title="'. $pro_title .'" style="'. $pro_style .'">'. $pro_text .'</a>';
				
				array_unshift($links, $pro);
				
			}
			
			return $links;
			
		}
		
		function plugin_links($links, $file) {
			
			if ($file === BANHAMMER_FILE) {
				
				$home_href  = 'https://perishablepress.com/banhammer/';
				$home_title = esc_attr__('Plugin Homepage', 'banhammer');
				$home_text  = esc_html__('Homepage', 'banhammer');
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
				
				$rate_href  = 'https://wordpress.org/support/plugin/'. BANHAMMER_SLUG .'/reviews/?rate=5#new-post';
				$rate_title = esc_attr__('Click here to rate and review this plugin on WordPress.org', 'banhammer');
				$rate_text  = esc_html__('Rate this plugin', 'banhammer') .'&nbsp;&raquo;';
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
				
			}
			
			return $links;
			
		}
		
		function footer_text($text) {
			
			$screen_id = banhammer_get_current_screen_id();
			
			$ids = array('toplevel_page_banhammer', 'banhammer_page_banhammer-armory', 'banhammer_page_banhammer-tower');
			
			if ($screen_id && apply_filters('banhammer_admin_footer_text', in_array($screen_id, $ids))) {
				
				$text = __('Like Banhammer? Give it a', 'banhammer');
				
				$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/banhammer/reviews/?rate=5#new-post">';
				
				$text .= __('★★★★★ rating&nbsp;&raquo;', 'banhammer') .'</a>';
				
			}
			
			return $text;
			
		}
		
		function check_banhammer() {
			
			if (class_exists('Banhammer_Pro')) {
				
				if (is_plugin_active(BANHAMMER_FILE)) {
					
					deactivate_plugins(BANHAMMER_FILE);
					
					$msg  = '<strong>'. esc_html__('Warning:', 'banhammer') .'</strong> ';
					
					$msg .= esc_html__('Pro version of Banhammer currently active. Free and Pro versions cannot be activated at the same time. ', 'banhammer');
					
					$msg .= esc_html__('Please return to the', 'banhammer');
					
					$msg .= ' <a href="'. admin_url('plugins.php') .'">'. esc_html__('WP Admin Area', 'banhammer') .'</a> '. esc_html__('and try again.', 'banhammer');
					
					wp_die($msg);
					
				}
				
			}
			
		}
		
		function check_version() {
			
			$wp_version = get_bloginfo('version');
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				
				if (version_compare($wp_version, BANHAMMER_REQUIRE, '<')) {
					
					if (is_plugin_active(BANHAMMER_FILE)) {
						
						deactivate_plugins(BANHAMMER_FILE);
						
						$msg  = '<strong>'. BANHAMMER_NAME .'</strong> '. esc_html__('requires WordPress ', 'banhammer') . BANHAMMER_REQUIRE;
						$msg .= esc_html__(' or higher, and has been deactivated! ', 'banhammer');
						$msg .= esc_html__('Please return to the', 'banhammer') .' <a href="'. admin_url() .'">';
						$msg .= esc_html__('WP Admin Area', 'banhammer') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'banhammer');
						
						wp_die($msg);
						
					}
					
				}
				
			}
			
		}
		
		function load_i18n() {
			
			$domain = 'banhammer';
			
			$locale = apply_filters('banhammer_locale', get_locale(), $domain);
			
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
					
					return load_plugin_textdomain($domain, false, dirname(BANHAMMER_FILE) .'/languages/');
					
				}
				
			}
			
		}
		
		function __clone() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'banhammer'), BANHAMMER_VERSION);
			
		}
		
		function __wakeup() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'banhammer'), BANHAMMER_VERSION);
			
		}
		
	}
	
	global $Banhammer;
	
	$GLOBALS['BanhammerWP'] = $BanhammerWP = new BanhammerWP(); 
	
}

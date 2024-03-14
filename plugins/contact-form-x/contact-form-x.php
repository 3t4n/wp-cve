<?php 
/*
	
	Plugin Name: Contact Form X
	Plugin URI: https://perishablepress.com/contact-form-x/
	Description: Displays a user-friendly contact form that your visitors will love.
	Tags: contact, contact form, email, feedback, ajax
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.6
	Tested up to: 6.5
	Stable tag: 2.8.1
	Version: 2.8.1
	Requires PHP: 5.6.20
	Text Domain: contact-form-x
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

if (!defined('ABSPATH')) die();

if (!class_exists('ContactFormX')) {
	
	class ContactFormX {
		
		function __construct() {
			
			$this->constants();
			$this->includes();
			
			register_activation_hook(__FILE__, 'contactformx_insert_example_data');
			register_activation_hook(__FILE__, 'contactformx_dismiss_notice_activate');
			
			add_action('admin_init',          array($this, 'check_version'));
			add_action('init',                array($this, 'load_i18n'));
			add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);
			add_filter('plugin_row_meta',     array($this, 'plugin_links'), 10, 2);
			add_filter('admin_footer_text',   array($this, 'footer_text'), 10, 1);
			
			add_action('admin_enqueue_scripts', 'contactformx_enqueue_resources_admin');
			add_action('admin_print_scripts',   'contactformx_print_js_vars_admin');
			add_action('admin_notices',         'contactformx_admin_notice');
			add_action('admin_init',            'contactformx_register_settings');
			add_action('admin_init',            'contactformx_delete_recipient');
			add_action('admin_init',            'contactformx_reset_options');
			add_action('admin_init',            'contactformx_reset_widget');
			add_action('admin_init',            'contactformx_reset_widget_legacy');
			add_action('admin_init',            'contactformx_drop_table_legacy');
			add_action('admin_init',            'contactformx_dismiss_notice_save');
			add_action('admin_init',            'contactformx_dismiss_notice_version');
			add_action('admin_menu',            'contactformx_menu_pages');
			
			add_action('init',                        'contactformx_register_post_type');
			add_action('init',                        'contactformx_enable_shortcode_widget');
			add_action('dashboard_glance_items',      'contactformx_add_glance_items');
			add_action('wp_dashboard_setup',          'contactformx_add_custom_dashboard_widget');
			add_action('wp_enqueue_scripts',          'contactformx_enqueue_resources_front');
			add_action('wp_ajax_contactformx',        'contactformx_ajax');
			add_action('wp_ajax_nopriv_contactformx', 'contactformx_ajax');
			
			add_action('widgets_init', 'contactformx_register_widget');
			add_shortcode('contactformx_legacy_empty_table', 'contactformx_legacy_empty_table');
			add_shortcode('contactformx_legacy_drop_table', 'contactformx_legacy_drop_table');
			add_shortcode('contactformx', 'contactformx');
			
		}
		
		function constants() {
			
			if (!defined('CONTACTFORMX_VERSION')) define('CONTACTFORMX_VERSION', '2.8.1');
			if (!defined('CONTACTFORMX_REQUIRE')) define('CONTACTFORMX_REQUIRE', '4.6');
			if (!defined('CONTACTFORMX_AUTHOR'))  define('CONTACTFORMX_AUTHOR',  'Jeff Starr');
			if (!defined('CONTACTFORMX_NAME'))    define('CONTACTFORMX_NAME',    __('Contact Form X', 'contact-form-x'));
			if (!defined('CONTACTFORMX_HOME'))    define('CONTACTFORMX_HOME',    esc_url('https://perishablepress.com/contact-form-x/'));
			if (!defined('CONTACTFORMX_URL'))     define('CONTACTFORMX_URL',     plugin_dir_url(__FILE__));
			if (!defined('CONTACTFORMX_DIR'))     define('CONTACTFORMX_DIR',     plugin_dir_path(__FILE__));
			if (!defined('CONTACTFORMX_FILE'))    define('CONTACTFORMX_FILE',    plugin_basename(__FILE__));
			if (!defined('CONTACTFORMX_SLUG'))    define('CONTACTFORMX_SLUG',    basename(dirname(__FILE__)));
			
		}
		
		function includes() {
			
			require_once CONTACTFORMX_DIR .'inc/settings-styles.php';
			require_once CONTACTFORMX_DIR .'inc/resources-enqueue.php';
			require_once CONTACTFORMX_DIR .'inc/core-db.php';
			require_once CONTACTFORMX_DIR .'inc/core-ajax.php';
			require_once CONTACTFORMX_DIR .'inc/wp-widget.php';
			
			if (is_admin()) {
				
				require_once CONTACTFORMX_DIR .'inc/settings-display.php';
				require_once CONTACTFORMX_DIR .'inc/settings-register.php';
				require_once CONTACTFORMX_DIR .'inc/settings-callbacks.php';
				require_once CONTACTFORMX_DIR .'inc/settings-validate.php';
				require_once CONTACTFORMX_DIR .'inc/settings-reset.php';
				require_once CONTACTFORMX_DIR .'inc/wp-dashboard.php';
				require_once CONTACTFORMX_DIR .'inc/help-tab.php';
				
			}
			
		}
		
		function options_email() {
			
			$options = array(
				
				'number-recipients' => 1,
				'recipient-1'       => $this->defaults(),
				
			);
			
			return apply_filters('contactformx_options_email', $options);
			
		}
		
		function options_form() {
			
			$options = array(
				
				'display-fields' => $this->fields(),
				
			);
			
			return apply_filters('contactformx_options_form', $options);
			
		}
		
		function options_customize() {
			
			$options = array(
				
				'submit-button'      => __('Send Message',      'contact-form-x'),
				'reset-button'       => __('Reset Form',        'contact-form-x'),
				'field-carbon-label' => __('Get a carbon copy', 'contact-form-x'),
				
				'field-name-placeholder' => __('Your Name', 'contact-form-x'),
				'field-name-label'       => __('Your Name', 'contact-form-x'),
				
				'field-website-placeholder' => __('Your Website', 'contact-form-x'),
				'field-website-label'       => __('Your Website', 'contact-form-x'),
				
				'field-email-placeholder' => __('Your Email', 'contact-form-x'),
				'field-email-label'       => __('Your Email', 'contact-form-x'),
				
				'field-subject-placeholder' => __('Email Subject',                        'contact-form-x'),
				'field-subject-label'       => __('Email Subject',                        'contact-form-x'),
				'default-subject'           => __('Message sent from your contact form.', 'contact-form-x'),
				
				'field-message-placeholder' => __('Your Message', 'contact-form-x'),
				'field-message-label'       => __('Your Message', 'contact-form-x'),
				
				'field-challenge-placeholder' => __('Correct Response', 'contact-form-x'),
				'field-challenge-label'       => __('1 + 1 =',          'contact-form-x'),
				'challenge-answer'            => __('2',                'contact-form-x'),
				'challenge-case'              => false,
				
				'field-recaptcha-label' => __('Are you human?', 'contact-form-x'),
				'recaptcha-public'      => '',
				'recaptcha-private'     => '',
				'recaptcha-theme'       => false,
				'recaptcha-version'     => 2,
				
				'field-custom-placeholder' => __('Custom Field', 'contact-form-x'),
				'field-custom-label'       => __('Custom Field', 'contact-form-x'),
				
				'field-agree-label' => __('I agree to the terms',     'contact-form-x'),
				'field-agree-desc'  => '',
				
				'success-message' => '<strong>'. __('Success!', 'contact-form-x') .'</strong> '. __('Your message has been sent.',                'contact-form-x'),
				'error-required'  => '<strong>'. __('Error:',   'contact-form-x') .'</strong> '. __('Please complete the required fields.',       'contact-form-x'),
				'error-invalid'   => '<strong>'. __('Error:',   'contact-form-x') .'</strong> '. __('Please enter a valid email address.',        'contact-form-x'),
				'error-challenge' => '<strong>'. __('Error:',   'contact-form-x') .'</strong> '. __('Incorrect response for challenge question.', 'contact-form-x'),
				'error-recaptcha' => '<strong>'. __('Error:',   'contact-form-x') .'</strong> '. __('Incorrect response for reCaptcha.',          'contact-form-x'),
				'error-agree'     => '<strong>'. __('Error:',   'contact-form-x') .'</strong> '. __('You must agree to the terms.',               'contact-form-x'),
				
				'custom-before-form'    => '',
				'custom-after-form'     => '',
				'custom-before-results' => '',
				'custom-after-results'  => '',
				
			);
			
			return apply_filters('contactformx_options_customize', $options);
			
		}
		
		function options_appearance() {
			
			$options = array(
				
				'enable-custom-style'    => 'default',
				'custom-style-default'   => contactformx_form_style_default(),
				'custom-style-classic'   => contactformx_form_style_classic(),
				'custom-style-micro'     => contactformx_form_style_micro(),
				'custom-style-synthetic' => contactformx_form_style_synthetic(),
				'custom-style-dark'      => contactformx_form_style_dark(),
				
			);
			
			return apply_filters('contactformx_options_appearance', $options);
			
		}
		
		function options_advanced() {
			
			$options = array(
				
				'display-success'          => 'basic',
				'display-url'              => '',
				'enable-shortcode-widget'  => false,
				'email-message-extra'      => false,
				'mail-function'            => false,
				'disable-database-storage' => false,
				'enable-data-collection'   => false,
				'disable-dash-widget'      => false,
				'display-dash-widget'      => false,
				'enable-powered-by'        => false,
				'reset-dash-widget'        => null,
				'reset-options'            => null,
				'rate-plugin'              => null,
				
			);
			
			return apply_filters('contactformx_options_advanced', $options);
			
		}
		
		function fields() {
			
			$fields = array(
				'name'      => array('display' => 'show', 'label' => __('Name',               'contact-form-x')),
				'website'   => array('display' => 'hide', 'label' => __('Website',            'contact-form-x')),
				'email'     => array('display' => 'show', 'label' => __('Email',              'contact-form-x')),
				'subject'   => array('display' => 'hide', 'label' => __('Subject',            'contact-form-x')),
				'custom'    => array('display' => 'hide', 'label' => __('Custom Field',       'contact-form-x')),
				'challenge' => array('display' => 'hide', 'label' => __('Challenge Question', 'contact-form-x')),
				'message'   => array('display' => 'show', 'label' => __('Message',            'contact-form-x')),
				'recaptcha' => array('display' => 'hide', 'label' => __('Google reCaptcha',   'contact-form-x')),
				'carbon'    => array('display' => 'hide', 'label' => __('Carbon Copy',        'contact-form-x')),
				'agree'     => array('display' => 'hide', 'label' => __('Agree to Terms',     'contact-form-x')),
			);
			
			return $fields;
			
		}
		
		function defaults() {
			
			$user_data = get_userdata(1);
			
			$name = $user_data ? $user_data->user_login : __('Awesome Person', 'contact-form-x');
			
			$to = get_bloginfo('admin_email');
			
			$from = $this->domain_email();
			
			$defaults = array('name' => $name, 'to' => $to, 'from' => $from);
			
			return apply_filters('contactformx_defaults', $defaults);
			
		}
		
		function domain_email() {
			
			return isset($_SERVER['SERVER_NAME']) ? 'wordpress@'. $_SERVER['SERVER_NAME'] : get_bloginfo('admin_email');
			
		}
		
		function action_links($links, $file) {
			
			if ($file === CONTACTFORMX_FILE && current_user_can('manage_options')) {
				
				$settings = '<a href="'. admin_url('options-general.php?page=contactformx') .'">'. esc_html__('Settings', 'contact-form-x') .'</a>';
				
				array_unshift($links, $settings);
				
			}
			
			return $links;
			
		}
		
		function plugin_links($links, $file) {
			
			if ($file === CONTACTFORMX_FILE) {
				
				$home_href  = 'https://perishablepress.com/contact-form-x/';
				$home_title = esc_attr__('Plugin Homepage', 'contact-form-x');
				$home_text  = esc_html__('Homepage', 'contact-form-x');
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
				
				$rate_href  = 'https://wordpress.org/support/plugin/'. CONTACTFORMX_SLUG .'/reviews/?rate=5#new-post';
				$rate_title = esc_attr__('Click here to rate and review this plugin on WordPress.org', 'contact-form-x');
				$rate_text  = esc_html__('Rate this plugin', 'contact-form-x') .'&nbsp;&raquo;';
				
				$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';
				
			}
			
			return $links;
			
		}
		
		function footer_text($text) {
			
			$screen_id = contactformx_get_current_screen_id();
			
			$ids = array('settings_page_contactformx');
			
			if ($screen_id && apply_filters('contactformx_admin_footer_text', in_array($screen_id, $ids))) {
				
				$text = __('Like this plugin? Give it a', 'contact-form-x');
				
				$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/contact-form-x/reviews/?rate=5#new-post">';
				
				$text .= __('★★★★★ rating&nbsp;&raquo;', 'contact-form-x') .'</a>';
				
			}
			
			return $text;
			
		}
		
		function check_version() {
			
			$wp_version = get_bloginfo('version');
			
			if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
				
				if (version_compare($wp_version, CONTACTFORMX_REQUIRE, '<')) {
					
					if (is_plugin_active(CONTACTFORMX_FILE)) {
						
						deactivate_plugins(CONTACTFORMX_FILE);
						
						$msg  = '<strong>'. CONTACTFORMX_NAME .'</strong> '. esc_html__('requires WordPress ', 'contact-form-x') . CONTACTFORMX_REQUIRE;
						$msg .= esc_html__(' or higher, and has been deactivated! ', 'contact-form-x');
						$msg .= esc_html__('Please return to the', 'contact-form-x') .' <a href="'. admin_url() .'">';
						$msg .= esc_html__('WP Admin Area', 'contact-form-x') .'</a> '. esc_html__('to upgrade WordPress and try again.', 'contact-form-x');
						
						wp_die($msg);
						
					}
					
				}
				
			}
			
		}
		
		function load_i18n() {
			
			load_plugin_textdomain('contact-form-x', false, dirname(CONTACTFORMX_FILE) .'/languages/');
			
		}
		
		function __clone() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'contact-form-x'), CONTACTFORMX_VERSION);
			
		}
		
		function __wakeup() {
			
			_doing_it_wrong(__FUNCTION__, esc_html__('Sorry, pal!', 'contact-form-x'), CONTACTFORMX_VERSION);
			
		}
		
	}
	
	$ContactFormX = new ContactFormX(); 
	
}

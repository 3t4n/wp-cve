<?php
/*
 * Plugin Name: Advanced FAQ Manager (Best FAQ Plugin for WordPress)
 * Description: FAQ Plugin for WordPress lets you create and manage FAQs in your WordPress pages. 
 * Version:     1.4.0
 * Author:      ThemeHigh
 * Author URI:  https://www.themehigh.com
 * Plugin URI:  https://www.themehigh.com
 * Text Domain: advanced-faq-manager
 * Domain Path: /languages
 */ 

if(!defined('WPINC')){ die; }

if(!class_exists('THFAQF_Manager')){	
	class THFAQF_Manager {	
		const TEXT_DOMAIN = 'advanced-faq-manager';

		public function __construct(){
			add_action('init', array($this, 'init'));
			register_activation_hook( __FILE__, array($this, 'activate') );
			register_setting('settings-group', 'thfaq_custom_css');
		}

		public function init() {
			define('THFAQF_VERSION', '1.4.0');
			!defined('THFAQF_BASE_NAME') && define('THFAQF_BASE_NAME', plugin_basename( __FILE__ ));
			!defined('THFAQF_PATH') && define('THFAQF_PATH', plugin_dir_path( __FILE__ ));
			!defined('THFAQF_URL') && define('THFAQF_URL', plugins_url( '/', __FILE__ ));
			!defined('THFAQF_ASSETS_URL') && define('THFAQF_ASSETS_URL', THFAQF_URL .'assets/');

			$this->load_plugin_textdomain();
			
			require_once( THFAQF_PATH . 'includes/class-thfaqf.php' );
			$faq = new THFAQF;
		}

		public function activate(){
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-thfaqf-activator.php' );
			THFAQF_Activator::activate();
		}

		public function load_plugin_textdomain(){
			$locale = apply_filters('plugin_locale', get_locale(), self::TEXT_DOMAIN);
		
			load_textdomain(self::TEXT_DOMAIN, WP_LANG_DIR.'/advanced-faq-manager/'.self::TEXT_DOMAIN.'-'.$locale.'.mo');
			load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(THFAQF_BASE_NAME) . '/languages/');
		}
	}
	new THFAQF_Manager();
}

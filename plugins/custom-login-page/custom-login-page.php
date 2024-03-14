<?php
/*
Plugin Name: A5 Custom Login Page
Description: Just customize your login page (or that of your community etc.) by giving the WP login page a different look, with your own logo and special colours and styles.
Version: 2.8.1
Author: Stefan Crämer
Author URI: http://www.stefan-craemer.com
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/a5-custom-login-page
License: GPL3
Text Domain: custom-login-page
Domain Path: /languages
*/

/*  Copyright 2011 - 2016 Stefan Crämer (email: support@atelier-fuenf.de)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 * ------------------------------------------------------
 *  ACKNOWLEDGEMENTS
 * ------------------------------------------------------
 * 
 * Thx to Jorge Ballesteros - http://motivando.me
 * for the translation into Spanish
 *
 * Thx to Branco Radenovich - http://webhostinggeeks.com/blog
 * for the translation into Slovak
 *
 * Thx to Andrijana Nikolic - http://webhostinggeeks.com
 * for the translation into Serbo-Croatian
 *
 * ------------------------------------------------------
 */

/* Stop direct call */

defined('ABSPATH') OR exit;

if (!defined('CLP_PATH')) define( 'CLP_PATH', plugin_dir_path(__FILE__) );
if (!defined('CLP_BASE')) define( 'CLP_BASE', plugin_basename(__FILE__) );

# loading the framework
if (!class_exists('A5_FormField')) require_once CLP_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once CLP_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicFiles')) require_once CLP_PATH.'class-lib/A5_DynamicFileClass.php';
if (!class_exists('A5_Widget')) require_once CLP_PATH.'class-lib/A5_WidgetClass.php';
if (!class_exists('A5_AddMceButton')) require_once CLP_PATH.'class-lib/A5_MCEButtonClass.php';

# loading plugin specific classes
if (!class_exists('CLP_Admin')) require_once CLP_PATH.'class-lib/CLP_AdminClass.php';
if (!class_exists('CLP_WidgetAdmin')) require_once CLP_PATH.'class-lib/CLP_AdminClassWidget.php';
if (!class_exists('CLP_ShortcodeAdmin')) require_once CLP_PATH.'class-lib/CLP_AdminClassShortcode.php';
if (!class_exists('CLP_Shortcode')) require_once CLP_PATH.'class-lib/CLP_ShortcodeClass.php';
if (!class_exists('CLP_DynamicCSS')) require_once CLP_PATH.'class-lib/CLP_DynamicCSSClass.php';
if (!class_exists('CLP_DynamicJS')) require_once CLP_PATH.'class-lib/CLP_DynamicJSClass.php';
if (!class_exists('Custom_Login_Widget')) require_once CLP_PATH.'class-lib/CLP_WidgetClass.php';

class A5_CustomLoginPage {
	
	private static $options;
	
	const version = 2.6;
	
	function __construct(){
		
		register_activation_hook(__FILE__, array($this, '_install')); 
		register_deactivation_hook(__FILE__, array($this, '_uninstall'));	
		
		add_filter('plugin_row_meta', array($this, 'register_links'), 10, 2);
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		if (is_multisite()) :
		
			$plugins = get_network_option(NULL, 'active_sitewide_plugins');
			
			if (isset($plugins[CLP_BASE])) :
				
				self::$options = get_network_option(NULL, 'clp_options');
				
				if (self::$options['version'] != self::version) :
				
					$this->_update_options(self::$options['multisite']);
					
				endif;
				
			else:
			
				$plugins = get_option('active_plugins');
			
				if (in_array(CLP_BASE, $plugins)) :
				
					self::$options = get_option('clp_options');
					
					if (self::$options['version'] != self::version) $this->_update_options(self::$options['multisite']);
					
				endif;
				
			endif;
			
		else:
		
			$plugins = get_option('active_plugins');
			
				if (in_array(CLP_BASE, $plugins)) :
			
				self::$options = get_option('clp_options');
				
				if (self::$options['version'] != self::version) $this->_update_options(self::$options['multisite']);
				
			endif;
		
		endif;
		
		if (!@array_key_exists('flushed', self::$options)) add_action('init', array ($this, 'update_rewrite_rules'));
		
		if (!empty(self::$options['url'])) add_filter('login_headerurl', array($this, 'change_headerurl'));
		if (!empty(self::$options['title'])) add_filter('login_headertitle', array($this, 'change_headertitle'));
		if (!empty(self::$options['error_custom_message'])) add_filter('login_errors', array($this, 'custom_error'));
		if (!empty(self::$options['logout_custom_message'])) add_filter('login_messages', array($this, 'custom_logout'));
		if (!empty(self::$options['password_custom_message'])) add_filter('gettext', array($this, 'custom_password'));
		if (!empty(self::$options['register_custom_message'])) add_filter('gettext', array($this, 'custom_register'));
		if (!empty(self::$options['custom_redirect'])) add_filter('login_redirect', array($this, 'login_redirect'), 10, 3);
		if (!empty(self::$options['hide_backend'])) :
			add_action('show_admin_bar', array($this, 'disable_admin_bar'));
			add_action('admin_init', array($this, 'redirect_from_admin'));
		endif;
		if (!empty(self::$options['svg']) || !empty(self::$options['login_message'])) add_filter('login_message', array($this, 'print_login_message'));
		if (!empty(self::$options['login_form'])) add_action('login_form', array($this, 'print_login_form'));
		if (!empty(self::$options['login_footer'])) add_filter('login_footer', array($this, 'print_login_footer'));
		if (!empty(self::$options['blog_header'])) add_action( 'login_head', array ($this, 'custom_login_header'));
		if (!empty(self::$options['blog_footer'])) add_action( 'login_footer', array ($this, 'custom_login_footer'));
		if (!empty(self::$options['disable_reg'])) add_filter('option_users_can_register', array($this, 'disable_registration'));
		if (!empty(self::$options['disable_pass']))	add_action ('lost_password', array($this, 'disable_password_reset'));
		if (!empty(self::$options['disable_pass']))	add_filter('gettext', array($this, 'remove_lostpassword_text'));
		if (isset(self::$options['video']) && !empty(self::$options['video'])) add_filter('login_message', array($this, 'print_video'));
		
		add_action('wp_ajax_video_preview', array($this, 'ajax_video_preview'));

		/**
		 *
		 * Importing language file
		 *
		 */
		load_plugin_textdomain('custom-login-page', false , basename(dirname(__FILE__)).'/languages');
		
		// redirecting to the export file
		
		add_action('init', array ($this, 'add_rewrite'));
		add_action('template_redirect', array ($this, 'export_template'));
		
		if (true == WP_DEBUG):
		
			add_action('wp_before_admin_bar_render', array($this, 'admin_bar_menu'));
		
		endif;
		
		$CLP_DynamicCSS = new CLP_DynamicCSS(self::$options['multisite']);
		$CLP_Admin = new CLP_Admin(self::$options['multisite']);
		$CLP_WidgetAdmin = new CLP_WidgetAdmin(self::$options['multisite']);
		$CLP_ShortcodeAdmin = new CLP_ShortcodeAdmin(self::$options['multisite']);
		$CLP_Shortcode = new CLP_Shortcode(self::$options['multisite']);
		
		$tinymce_button = new A5_AddMceButton ('custom-login-page', 'CustomLoginPage', 'mce_buttons_2');
		
		if (!is_multisite()) $CLP_DynamicJS = new CLP_DynamicJS();
		
	}
	
	function disable_registration($value) {
		
		$script = basename(parse_url($_SERVER['SCRIPT_NAME'], PHP_URL_PATH));
		
		$query = (isset($_GET['action'])) ? $_GET['action'] : false;
		
		if ($script == 'wp-login.php' && $query != 'register') $value = false;
		
		return $value;
		
	}
	
	function disable_password_reset() {
		
		wp_redirect(home_url());
		
	}
	
	function remove_lostpassword_text ($text) {
		
		remove_filter('gettext', array($this, 'remove_lostpassword_text'));
		
		if ($text == __('Lost your password?')) $text = '';
		if ($text == __('<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?')) $text = __('<strong>ERROR</strong>: Invalid username.', 'custom-login-page');
		if ($text == __('<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?')) $text = __('<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect.', 'custom-login-page');
		
		add_filter('gettext', array($this, 'remove_lostpassword_text'));
		
		return $text;
		 
	}
	
	function custom_login_header() {
		
		get_header();
	
	}
	
	function custom_login_footer() {
		
		get_footer();
		
	}
	
	/**
	 *
	 * Adds links to the plugin page
	 *
	 */
	function register_links($links, $file) {
		
		if ($file == CLP_BASE) :
		
			$links[] = '<a href="http://wordpress.org/extend/plugins/custom-login-page/faq/" target="_blank">'.__('FAQ', 'custom-login-page').'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=32XGSBKTQNNHA" target="_blank">'.__('Donate', 'custom-login-page').'</a>';
			
		endif;
		
		return $links;
	
	}
	
	/* attach JavaScript file for textarea resizing */
	function enqueue_scripts($hook) {
		
		if ($hook != 'widgets.php' && $hook != 'post.php' && 'toplevel_page_clp-settings' != $hook && 'a5-custom-login_page_clp-widget-settings' != $hook && 'a5-custom-login_page_clp-shortcode-settings' != $hook) return;
		
		$min = (SCRIPT_DEBUG == false) ? '.min.' : '.';
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander'.$min.'js', __FILE__), array('jquery'), '3.1', true);
		wp_enqueue_script('ta-expander-script');
		
	}
	
	/**
	 *
	 * Changes the link behind the logo
	 *
	 */
	function change_headerurl() {
		
		return self::$options['url'];
		
	}
	
	/**
	 *
	 * Changes the Title tag of the logo
	 *
	 */
	function change_headertitle() {
		
		return self::$options['title'];
		
	}
	
	/**
	 *
	 * Changes the Error Message
	 *
	 */
	function custom_error() {
		
		return self::$options['error_custom_message'];
		
	}
	
	/**
	 *
	 * Changes the Logout Message
	 *
	 */
	function custom_logout() {
	
		return self::$options['logout_custom_message'];
		
	}
	
	/**
	 *
	 * Changes the Lost Password Message
	 *
	 */
	function custom_password($text) {
		
		remove_filter('gettext', array($this, 'custom_password'));
		
		if ($text == __('Please enter your username or email address. You will receive a link to create a new password via email.')) $text = self::$options['password_custom_message'];;
		
		add_filter('gettext', array($this, 'custom_password'));
		
		return $text;
		
	}
	
	/**
	 *
	 * Changes the Registration Message
	 *
	 */
	function custom_register($text) {
		
		remove_filter('gettext', array($this, 'custom_register'));
		
		if ($text == __('Register For This Site')) $text = self::$options['register_custom_message'];;
		
		add_filter('gettext', array($this, 'custom_register'));
		
		return $text;
		
	}
	
	/**
	 *
	 * Redirect after login
	 *
	 */
	function login_redirect($redirect_to, $request, $user) {
		
		//is there a user to check?
		
		global $user;
		
		if (isset($user->roles) && is_array($user->roles)) :
		
			$redirects = self::$options['custom_redirect'];
		
			foreach ($redirects as $role => $custom_redirect) :
		
				if (in_array($role, $user->roles) && !empty($custom_redirect)) return $custom_redirect;
			
			endforeach;
			
			return $redirect_to;
		
		else :
			
			return $redirect_to;
		
		endif;
	
	}
	
	/**
	 *
	 * Hide backend from user roles
	 *
	 */
	 function disable_admin_bar() {
		
		$user = wp_get_current_user();
		
		if (!isset($user->roles) || empty($user->roles)) return false;
		
		if (in_array($user->roles[0], self::$options['hide_backend'])) return false;
		
		return true;
	
	}
	
	function redirect_from_admin(){
		
		$user = wp_get_current_user();
		
		if ((isset($user->roles) && in_array($user->roles[0], self::$options['hide_backend'])) && (!defined('DOING_AJAX') || !DOING_AJAX)) :
		
			$redirect = (self::$options['custom_redirect'][$user->roles[0]]) ? self::$options['custom_redirect'][$user->roles[0]] : home_url();
			
			wp_redirect($redirect);
		
		endif;
		
	}

	/**
	 *
	 * Printing the video
	 *
	 */
	function print_video() {
		
		$attr = array(
			'src' => self::$options['video'],
			'poster' => self::$options['video_poster'],
			'loop' => self::$options['video_loop'],
			'autoplay' => self::$options['video_autoplay'],
			'preload' => self::$options['video_preload'],
			'class' => self::$options['video_class'],
			'id' => self::$options['video_id']
		);
		
		if (!empty(self::$options['video_height'])) $attr['height'] = self::$options['video_height'];
		if (!empty(self::$options['video_width'])) $attr['width'] = self::$options['video_width'];
		
		return wp_video_shortcode($attr);
		
	}
	
	function ajax_video_preview() {
		
		check_ajax_referer('zwetschgenbremmel', 'ajax_nonce');
		
		$attr = array(
			'src' => $_POST['video_url'],
			'poster' => $_POST['video_poster'],
			'loop' => @$_POST['video_loop'],
			'autoplay' => @$_POST['video_autoplay'],
			'preload' => $_POST['video_preload'],
			'class' => $_POST['video_class'],
			'id' => $_POST['video_id']
		);
		
		if (!empty($_POST['video_height'])) $attr['height'] = $_POST['video_height'];
		if (!empty($_POST['video_width'])) $attr['width'] = $_POST['video_width'];
		
		echo wp_video_shortcode($attr);
		
		die();
		
	}
	
	/**
	 *
	 * Printing the additional html
	 *
	 */
	function print_login_message() {
		
		return @self::$options['svg'].@self::$options['login_message'];
	
	}
	
	function print_login_form() {
		
		echo self::$options['login_form'];
	
	}
	
	function print_login_footer() {
		
		echo self::$options['login_footer'];
	
	}

	/**
	 *
	 * Setting version on activation
	 *
	 */
	function _install() {
		
		$screen = get_current_screen();
		
		$compress = (SCRIPT_DEBUG) ? false : true;
		
		$default = array(
			'version' => self::version,
			'multisite' => false,
			'flushed' => true,
			'inline' => false,
			'compress' => $compress,
			'css_cache' => ''
		);
		
		if (is_multisite() && $screen->is_network) :
		
			$default['multisite'] = true;
		
			add_network_option(NULL, 'clp_options', $default);
			add_network_option(NULL, 'clp_widget_options');
			add_network_option(NULL, 'clp_shortcode_options');
			
		else:
		
			add_option('clp_options', $default);
			add_option('clp_widget_options');
			add_option('clp_shortcode_options');
			
		endif;
		
		add_rewrite_rule('a5-framework-frontend.css', 'index.php?A5_file=wp_css', 'top');
		add_rewrite_rule('a5-framework-frontend.js', 'index.php?A5_file=wp_js', 'top');
		add_rewrite_rule('a5-framework-backend.css', 'index.php?A5_file=admin_css', 'top');
		add_rewrite_rule('a5-framework-backend.js', 'index.php?A5_file=admin_js', 'top');
		add_rewrite_rule('a5-framework-login.css', 'index.php?A5_file=login_css', 'top');
		add_rewrite_rule('a5-framework-login.js', 'index.php?A5_file=login_js', 'top');
		add_rewrite_rule('a5-export-settings', 'index.php?A5_file=export', 'top');
		flush_rewrite_rules();
	
	}
	
	/**
	 *
	 * Cleaning on deactivation
	 *
	 */
	function _uninstall() {
		
		$screen = get_current_screen();
		
		if (is_multisite() && $screen->is_network) :
		
			delete_network_option(NULL, 'clp_options');
			delete_network_option(NULL, 'clp_widget_options');
			delete_network_option(NULL, 'clp_shortcode_options');
			
		else:
		
			delete_option('clp_options');
			delete_option('clp_widget_options');
			delete_option('clp_shortcode_options');
			
		endif;
		
		flush_rewrite_rules();
		
	}

	/**
	 *
	 * redirect to export file
	 *
	 */
	function add_rewrite() {
	
		global $wp;
		
		$wp->add_query_var('clpfile');
	
	}
	
	function export_template() {
		
		$clpfile = get_query_var('clpfile');
		
		if ('export' == $clpfile) :
		
			self::$options['log'] = 'original A5 CLP file';
			
			unset(self::$options['multisite']);
			
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename="a5-clp-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('Y') . date('m') . date('d') . '.txt"');
			header('Content-Type: text/plain; charset=utf-8');
			
			echo json_encode(self::$options);
			
			exit;
		
		endif;
		
		if ('export-widget' == $clpfile) :
		
			$options = (self::$options['multisite']) ? get_site_option('clp_widget_options') : get_option('clp_widget_options');
		
			$options['log'] = 'original A5 CLP Widget file';
			
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename="a5-clp-widget-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('Y') . date('m') . date('d') . '.txt"');
			header('Content-Type: text/plain; charset=utf-8');
			
			echo json_encode($options);
			
			exit;
		
		endif;
		
		if ('export-shortcode' == $clpfile) :
		
			$options = (self::$options['multisite']) ? get_site_option('clp_shortcode_options') : get_option('clp_shortcode_options');
		
			$options['log'] = 'original A5 CLP Shortcode file';
			
			header('Content-Description: File Transfer');
			header('Content-Disposition: attachment; filename="a5-clp-shortcode-' . str_replace('.','-', $_SERVER['SERVER_NAME']) . '-' . date('Y') . date('m') . date('d') . '.txt"');
			header('Content-Type: text/plain; charset=utf-8');
			
			echo json_encode($options);
			
			exit;
		
		endif;
		
	}
	
	/**
	 *
	 * updating database
	 *
	 */
	function _update_options($multisite) {
		
		$options_old = ($multisite) ? get_network_option(NULL, 'clp_options') : get_option('clp_options');
		
		$options_new = ($multisite) ? get_network_option(NULL, 'clp_options') : get_option('clp_options');
		
		if (isset($options_old['hide_nav']) && !empty($options_old['hide_nav'])) :
					
			$options_new['disable_reg'] = true;
			$options_new['disable_pass'] = true;	
			
		endif;
		
		unset($options_new['hide_nav']);
		
		$options_new['version'] = self::version;
		
		if ($multisite) update_network_option(NULL, 'clp_options', $options_new);
		
		else update_option('clp_options', $options_new);
		
		return;
		
	}
	
	function update_rewrite_rules() {
		
		add_rewrite_rule('a5-framework-frontend.css', 'index.php?A5_file=wp_css', 'top');
		add_rewrite_rule('a5-framework-frontend.js', 'index.php?A5_file=wp_js', 'top');
		add_rewrite_rule('a5-framework-backend.css', 'index.php?A5_file=admin_css', 'top');
		add_rewrite_rule('a5-framework-backend.js', 'index.php?A5_file=admin_js', 'top');
		add_rewrite_rule('a5-framework-login.css', 'index.php?A5_file=login_css', 'top');
		add_rewrite_rule('a5-framework-login.js', 'index.php?A5_file=login_js', 'top');
		add_rewrite_rule('a5-export-settings', 'index.php?A5_file=export', 'top');
		
		flush_rewrite_rules();
		
		self::$options['flushed'] = true;
		
		(self::$options['multisite']) ? update_network_option(NULL, 'clp_options', self::$options) : update_option('clp_options', self::$options);
	
	}
	
	/**
	 *
	 * Adds a link to the settings to the admin bar in case WP_DEBUG is true
	 *
	 */
	function admin_bar_menu() {
		
		global $wp_admin_bar;
		
		if (!is_super_admin() || !is_admin_bar_showing()) return;
		
		$wp_admin_bar->add_node(array('parent' => '', 'id' => 'a5-framework', 'title' => 'A5 Framework'));
		
		$wp_admin_bar->add_node(array('parent' => 'a5-framework', 'id' => 'a5-custom-login', 'title' => 'Custom Login Page', 'href' => admin_url('admin.php?page=clp-settings')));
		
		$wp_admin_bar->add_node(array('parent' => 'a5-framework', 'id' => 'a5-custom-login-widget', 'title' => 'Custom Login Widget', 'href' => admin_url('admin.php?page=clp-widget-settings')));
		
		$wp_admin_bar->add_node(array('parent' => 'a5-framework', 'id' => 'a5-custom-login-shortcode', 'title' => 'Custom Login Shortcode', 'href' => admin_url('admin.php?page=clp-shortcode-settings')));
		
	}
	
} // end of class

$A5_CustomLoginPage = new A5_CustomLoginPage;

?>
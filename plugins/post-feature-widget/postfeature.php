<?php
/*
Plugin Name: Featured Post Widget
Plugin URI: http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/featured-post-widget
Description: Featured Post Widget is yet another plugin to make your blog a bit more newspaper-like. Just by choosing a post from a dropdown, you can put it in the 'featured' area and display thumbnail, headline, excerpt or all three of them (if available) in the fully customizable widget.
Version: 4.2.1
Author: Stefan Crämer
Author URI: http://www.stefan-craemer.com
License: GPL3
Text Domain: postfeature
Domain Path: /languages
*/

/*  Copyright 2010 - 2016 Stefan Crämer (email : support@atelier-fuenf.de)

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


/* Stop direct call */

defined('ABSPATH') OR exit;

if (!defined('PF_PATH')) define( 'PF_PATH', plugin_dir_path(__FILE__) );
if (!defined('PF_BASE')) define( 'PF_BASE', plugin_basename(__FILE__) );

# loading the framework
if (!class_exists('A5_Image')) require_once PF_PATH.'class-lib/A5_ImageClass.php';
if (!class_exists('A5_Excerpt')) require_once PF_PATH.'class-lib/A5_ExcerptClass.php';
if (!class_exists('A5_FormField')) require_once PF_PATH.'class-lib/A5_FormFieldClass.php';
if (!class_exists('A5_OptionPage')) require_once PF_PATH.'class-lib/A5_OptionPageClass.php';
if (!class_exists('A5_DynamicFiles')) require_once PF_PATH.'class-lib/A5_DynamicFileClass.php';
if (!class_exists('A5_Widget')) require_once PF_PATH.'class-lib/A5_WidgetClass.php';

#loading plugin specific classes
if (!class_exists('FP_Admin')) require_once PF_PATH.'class-lib/FP_AdminClass.php';
if (!class_exists('FP_DynamicCSS')) require_once PF_PATH.'class-lib/FP_DynamicCSSClass.php';
if (!class_exists('Featured_Post_Widget')) require_once PF_PATH.'class-lib/FP_WidgetClass.php';

class PostFeaturePlugin {
	
	static $options;
	
	function __construct() {
		
		load_plugin_textdomain('postfeature', false , basename(dirname(__FILE__)).'/languages');
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		add_filter('plugin_row_meta', array($this, 'register_links'), 10, 2);	
		add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
				
		register_activation_hook(  __FILE__, array($this, '_install') );
		register_deactivation_hook(  __FILE__, array($this, '_uninstall') );
		
		if (false != get_option('postfeature_cache')) : 
		
			delete_option('postfeature_cache');
			
			$this->_install();
			
		endif;
		
		add_action('save_post', array($this, 'flush_widget_cache'));
		add_action('deleted_post', array($this, 'flush_widget_cache'));
		add_action('switch_theme', array($this, 'flush_widget_cache'));
		
		if (true == WP_DEBUG):
		
			add_action('wp_before_admin_bar_render', array($this, 'admin_bar_menu'));
		
		endif;
		
		self::$options = get_option('pf_options');
		
		if (!isset(self::$options['version'])) $this->_update_options();
		
		if (@!array_key_exists('flushed', self::$options)) add_action('init', array ($this, 'update_rewrite_rules'));
		
		$FP_DynamicCSS = new FP_DynamicCSS;
		$FP_Admin = new FP_Admin;
		
	}
	
	/* attach JavaScript file for textarea reszing */
	
	function enqueue_scripts($hook) {
		
		if ($hook != 'settings_page_featured-post-settings' && $hook != 'widgets.php' && $hook != 'post.php') return;
		
		$min = (SCRIPT_DEBUG == false) ? '.min.' : '.';
		
		wp_register_script('ta-expander-script', plugins_url('ta-expander'.$min.'js', __FILE__), array('jquery'), '3.0', true);
		wp_enqueue_script('ta-expander-script');
	
	
	}
	
	/* Additional links on the plugin page */
	
	function register_links($links, $file) {
		
		if ($file == PF_BASE) :
		
			$links[] = '<a href="http://wordpress.org/extend/plugins/post-feature-widget/faq/" target="_blank">'.__('FAQ', 'postfeature').'</a>';
			$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=D8AVGNDYYUNA2" target="_blank">'.__('Donate', 'postfeature').'</a>';
		
		endif;
		
		return $links;
	
	}
	
	function plugin_action_links( $links, $file ) {
		
		if ($file == PF_BASE) array_unshift($links, '<a href="'.admin_url( 'options-general.php?page=featured-post-settings' ).'">'.__('Settings', 'postfeature').'</a>');
	
		return $links;
	
	}
	
	// Creating default options on activation
	
	function _install() {
		
		$compress = (SCRIPT_DEBUG) ? false : true;
		
		$default = array(
			'version' => '4.2',
			'cache' => array(),
			'css' => "-moz-hyphens: auto;\n-o-hyphens: auto;\n-webkit-hyphens: auto;\n-ms-hyphens: auto;\nhyphens: auto;",
			'inline' => false,
			'compress' => $compress,
			'flushed' => true,
			'css_cache' => ''
		);
	
		add_option('pf_options', $default);
		
		add_rewrite_rule('a5-framework-frontend.css', 'index.php?A5_file=wp_css', 'top');
		add_rewrite_rule('a5-framework-frontend.js', 'index.php?A5_file=wp_js', 'top');
		add_rewrite_rule('a5-framework-backend.css', 'index.php?A5_file=admin_css', 'top');
		add_rewrite_rule('a5-framework-backend.js', 'index.php?A5_file=admin_js', 'top');
		add_rewrite_rule('a5-framework-login.css', 'index.php?A5_file=login_css', 'top');
		add_rewrite_rule('a5-framework-login.js', 'index.php?A5_file=login_js', 'top');
		add_rewrite_rule('a5-export-settings', 'index.php?A5_file=export', 'top');
		flush_rewrite_rules();
	
	}
	
	// Cleaning on deactivation
	
	function _uninstall() {
	
		delete_option('pf_options');
		
		flush_rewrite_rules();
	
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
		
		update_option('pf_options', self::$options);
	
	}
	
		// updating options in case they are outdated
	
	function _update_options() {
		
		$compress = (SCRIPT_DEBUG) ? false : true;
		
		$options_old = get_option('pf_options');
		
		$options_new['cache'] = array();
		
		$options_new['css_cache'] = '';
		
		$options_new['inline'] = (isset($options_old['inline'])) ? $options_old['inline'] : false;
		
		$options_new['compress'] = (isset($options_old['compress'])) ? $options_old['compress'] : $compress;
		
		$options_new['version'] = '4.2';
		
		$options_new['css'] = "-moz-hyphens: auto;\n-o-hyphens: auto;\n-webkit-hyphens: auto;\n-ms-hyphens: auto;\nhyphens: auto;";
		
		update_option('pf_options', $options_new);
	
	}
	
	function flush_widget_cache() {
		
		global $wpdb;
		
		self::$options['cache'] = array();
		
		$update_args = array('option_value' => serialize(self::$options));
		
		$result = $wpdb->update( $wpdb->options, $update_args, array( 'option_name' => 'pf_options' ) );
	
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
		
		$wp_admin_bar->add_node(array('parent' => 'a5-framework', 'id' => 'a5-featured-post', 'title' => 'Featured Post Widget', 'href' => admin_url('options-general.php?page=featured-post-settings')));
		
	}

} // class

$PostFeaturePlugin = new PostFeaturePlugin;

?>
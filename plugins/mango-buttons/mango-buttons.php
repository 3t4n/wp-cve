<?php
/*
Plugin Name: Mango Buttons
Plugin URI: https://mangobuttons.com
Description: Mango Buttons is a button creator for WordPress that allows anyone to create beautiful buttons anywhere on their site.
Version: 1.2.9
Author: Phil Baylog
Author URI: https://mangobuttons.com
License: GPLv2
*/

//Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'MB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

global $MB_VERSION;
$MB_VERSION = '1.2.9';

class MangoButtons{

	private static $instance;

	private function __construct(){

		$this->include_before_plugin_loaded();
		add_action('plugins_loaded', array($this, 'include_after_plugin_loaded'));

		add_action('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_upgrade_link_to_plugins_page'));
 		add_action('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link_to_plugins_page'));
	}

	/** Singleton Instance Implementation **********/
	public static function instance(){
		if ( !isset( self::$instance ) ){
			self::$instance = new MangoButtons();
			self::$instance->init();
			//self::$instance->load_textdomain();
		}
		return self::$instance;
	}

	//called before the 'plugins_loaded action is fired
	function include_before_plugin_loaded(){
		global $wpdb;
	}

	function add_upgrade_link_to_plugins_page($links){
		$upgrade_link = '<a href="https://mangobuttons.com/pricing" target="_blank">Upgrade to PRO</a>';
	  array_unshift($links, $upgrade_link);

	  return $links;
	}

	function add_settings_link_to_plugins_page($links){

		$settings_url = admin_url('admin.php?page=mangobuttons');
		$settings_link = '<a href="' . $settings_url . '">Settings</a>';

	  array_unshift($links, $settings_link);

	  return $links;
	}

	function admin_menu(){
		add_menu_page( 'Mango Buttons', 'Mango Buttons', 'manage_options', 'mangobuttons', 'mb', MB_PLUGIN_URL . 'admin/images/menu-icon.png', '43.4' );
	}

	function add_mb_tiny_mce_button($buttons){
		array_push($buttons, 'mangobuttons');

		return $buttons;
	}
	function add_mb_tiny_mce_js($plugin_array){
		$plugin_array['mangobuttons'] = plugins_url( '/admin/js/tinymce.mangobuttons-plugin.js',__file__);

		return $plugin_array;
	}
	function add_mb_tiny_mce_css($mce_css){
		if(!empty($mce_css)){
			$mce_css .= ',';
		}

		$mce_css .= MB_PLUGIN_URL . 'public/style/mb-button.css';//mb button styles (includes open sans google font)
		$mce_css .= ',' . '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css';//fontawesome

		return $mce_css;
	}

	//https://vip.wordpress.com/documentation/register-additional-html-attributes-for-tinymce-and-wp-kses
	//http://www.tinymce.com/wiki.php/Configuration:valid_children
	function register_mb_anchor_element_children_for_tiny_mce($options){
		if(!isset( $options['valid_children'] ) ) {
			$options['valid_children'] = '';
		}
		else{
			$options['valid_children'] .= ',';
		}

		$options['valid_children'] .= '+a[span|b|em|strong|i|img]';

		return $options;
	}

	function render_mb_modal(){
		echo file_get_contents(MB_PLUGIN_PATH . 'admin/views/mb-modal.html');
	}

	function get_mb_icon_color(){
		if(get_option('mb_icon_color')){
			return get_option('mb_icon_color');
		}
		else{
			return 'color';
		}
	}

	function create_global_mb_js_variables(){

		if(is_admin()){
			?>
				<script type="text/javascript">
					var MB_JS_GLOBALS = {};
					MB_JS_GLOBALS.ICON_COLOR = '<?php echo mb()->get_mb_icon_color(); ?>';
				</script>
			<?php
		}

	}


	/*Utility function for determining whether WP is doing ajax call*/
	function is_ajax_call(){
		return defined('DOING_AJAX') && DOING_AJAX;
	}

	//called after the 'plugins_loaded action is fired
	function include_after_plugin_loaded(){

		global $MB_VERSION;

		//If user is activating the plugin for the first time
		if(!get_option('MB_VERSION') && !mb()->is_ajax_call()){

			$settings_url = admin_url('admin.php?page=mangobuttons');

			$html = '';

			$html .= '<div class="updated" style="border-color:#F6871F;padding:5px;">';
			 $html .= '<p style="margin-left:10px;">Thanks for installing Mango Buttons! &nbsp;&nbsp;&nbsp;<a class="mb-bg" href="' . $settings_url . '" style="position:relative;background:#F6871F;color:#FFF;padding:3px 6px;cursor:pointer;border-radius:3px;letter-spacing:.05em;font-size:12px;font-weight:bold;">GET STARTED WITH MANGO BUTTONS &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>';
			$html .= '</div><!--/.updated-->';

			echo $html;
		}

		//update database or options if plugin version updated
		if(get_option('MB_VERSION') != $MB_VERSION){
			mb()->initializeMBOptions();

			update_option('MB_VERSION', $MB_VERSION);
		}

		//admin only includes
		if( is_admin() ){

			//add action for admin_menu
			if( current_user_can('manage_options') ){
				add_action('admin_menu', array($this, 'admin_menu'));
			}

			include_once( MB_PLUGIN_PATH . 'admin/controllers/settings.php');
			include_once( MB_PLUGIN_PATH . 'admin/controllers/help.php');

			//Add tiny mce button filters (one for button and one for JS)
			add_filter('mce_buttons', array( $this, 'add_mb_tiny_mce_button' ) );
			add_filter('mce_external_plugins', array( $this, 'add_mb_tiny_mce_js' ) );
			add_filter('mce_css', array( $this, 'add_mb_tiny_mce_css' ) );

			//add filter for preventing tinymce from stripping out valid child elements of a tags
			add_filter('tiny_mce_before_init', array($this, 'register_mb_anchor_element_children_for_tiny_mce'), 14);

			//include ajax handler for processing ajax calls made from admin pages
			include_once( MB_PLUGIN_PATH . 'admin/ajax/mb-ajax-handler.php');

			//TODO check if edit post / edit page & only include if on one of those pages
			add_action('admin_footer', array( $this, 'render_mb_modal') );

			//add global mb js variables in admin head action
			add_action('admin_head', array( $this, 'create_global_mb_js_variables') );
		}

		add_action( 'wp_print_scripts',					array( $this, 'print_scripts'		) );
		add_action( 'admin_print_scripts',			array( $this, 'print_scripts'	) );
		add_action( 'wp_print_styles',					array( $this, 'print_styles'			) );
		add_action( 'admin_print_styles',				array( $this, 'print_styles'	) );

	}

	private function init(){

	}

	static function initializeMBOptions(){

		if(!get_option('mb_email')){
			update_option('mb_email', '');
		}
		if(!get_option('mb_subscribed')){
			update_option('mb_subscribed', false);
		}

		//v1.1.0
		if(!get_option('mb_icon_color')){
			update_option('mb_icon_color', 'color');
		}

		//v1.2.1
		if(!get_option('mb_extended_language_support')){
			update_option('mb_extended_language_support', 'disable');
		}

	}

	static function destroyMBOptions(){

		delete_option('MB_VERSION');
		delete_option('mb_email');
		delete_option('mb_icon_color');
		delete_option('mb_subscribed');
		delete_option('mb_extended_language_support');

	}

	static function destroyMBDB(){

		return;

		global $wpdb;

		//$sql = "DROP TABLE IF EXISTS " . $wpdb->mb_bars . ", " . $wpdb->mb_views . ", " . $wpdb->mb_conversions . ";";

		//$wpdb->query($sql);
	}

	static function activate(){


	}

	static function deactivate(){
		//This should be done every time plugin is deactivated
	}

	/*Delete all mb options, bars, and conversion data, and deactivate the plugin*/
	static function deactivateAndDestroyMBData(){

		global $MB_VERSION;

		mb()->destroyMBOptions();
		mb()->destroyMBDB();

		//if plugin is in default folder name
		if(is_plugin_active('mango-buttons/mango-buttons.php')){
			deactivate_plugins('mango-buttons/mango-buttons.php');
		}

		//if plugin is in '-plugin' folder name
		if(is_plugin_active('mango-buttons-plugin/mango-buttons.php')){
			deactivate_plugins('mango-buttons-plugin/mango-buttons.php');
		}

		//if plugin is in versioned folder name
		if(is_plugin_active('mango-buttons-' . $MB_VERSION . '/mango-buttons.php')){
			deactivate_plugins('mango-buttons-' . $MB_VERSION . '/mango-buttons.php');
		}

	}

	function print_scripts(){

		global $MB_VERSION;

		if( is_admin() ){

			wp_enqueue_script('knockout', MB_PLUGIN_URL . 'admin/js/inc/knockout-3.2.0.js', array('jquery'), '3.2.0', true);
			wp_enqueue_script('knockout-mb-utilities', MB_PLUGIN_URL . 'admin/js/inc/knockout-utilities.js', array('jquery', 'knockout'), '3.2.0', true);

			//COLOR PICKER + TOOLTIPS
			wp_enqueue_script('colpick', MB_PLUGIN_URL . 'admin/js/inc/colpick/js/colpick.js', array('jquery'), '0.0.0', true);
			wp_enqueue_script('tooltipster', MB_PLUGIN_URL . 'admin/js/inc/tooltipster/jquery.tooltipster.min.js', array('jquery'), '0.0.0', true);

			//MB dialog
			wp_enqueue_script('mb-modal', MB_PLUGIN_URL . 'admin/js/mb-modal.js', array( 'jquery', 'tooltipster', 'colpick' ), $MB_VERSION, false);
			wp_localize_script('mb-modal', 'ajaxurl', admin_url('admin-ajax.php') );
		}

	}

	function print_styles(){

		global $MB_VERSION;

		//if admin...
		if( is_admin() ){

			wp_enqueue_style('mb-admin', MB_PLUGIN_URL . 'admin/style/mb.css', false, microtime(), 'all');

			//todo add check if editing post?
			wp_enqueue_style('colpick', MB_PLUGIN_URL . 'admin/js/inc/colpick/css/colpick.css', false, '0.0.0', 'all');
			wp_enqueue_style('tooltipster', MB_PLUGIN_URL . 'admin/js/inc/tooltipster/tooltipster.css', false, '0.0.0', 'all');

		}

		//always...

		//required fonts for MB
		wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false, '4.3.0', 'all' );

		//Include Open Sans font - with or without extended language support (depending on settings)
		if(get_option('mb_extended_language_support') == 'enable'){
			wp_enqueue_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700&subset=latin,latin-ext', false );
		}
		else{
			wp_enqueue_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700', false );
		}

		//public mb_button styles
		wp_enqueue_style( 'mb', MB_PLUGIN_URL . 'public/style/mb-button.css', false, $MB_VERSION, 'all');

	}

}/*end MangoButtons class*/

//The main function used to retrieve the one true MangoButtons instance (a shortcut for MangoButtons::instance())
function mb(){
	return MangoButtons::instance();
}

//initialize
mb();

//activation
if(is_admin()){
	register_activation_hook( __FILE__, array( 'MangoButtons', 'activate') );
}

//deactivation
if(is_admin()){
	register_deactivation_hook( __FILE__, array( 'MangoButtons', 'deactivate') );
}
?>

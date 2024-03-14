<?php
/**
Plugin Name: NEX-Forms - Ultimate
Plugin URI: https://1.envato.market/zQ6de
Description: Premium WordPress Plugin - Ultimate Drag and Drop WordPress Forms Builder.
Author: Basix
Version: 8.5.9
Author URI: https://1.envato.market/zQ6de
License: GPL
Text Domain: nex-forms
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

$page = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']) : '';


/*function NEXForms_safe_welcome_redirect() {
	
	
  if(get_option('nf-first-run')==true)
  	{
	update_option('nf-first-run', false);
	wp_safe_redirect( add_query_arg( array( 'page' => 'nf-welcome-page' ), admin_url( 'admin.php' ) ) );	
	}

}

add_action( 'admin_init', 'NEXForms_safe_welcome_redirect' );

function NEXForms_welcome_page_content() {
 
   // if ( file_exists( WPW_DIR . '/includes/welcome-page.php') ) {
 
       require_once(  dirname(__FILE__)  . '/includes/welcome-page.php' );
 
    //}
}

*/

define('NF_PATH','');
define('NF_TABLE_PREFIX','wap_');


$other_config = get_option('nex-forms-other-config');
	
$user_level = isset($other_config['set-wp-user-level']) ? $other_config['set-wp-user-level'] : 'administrator';

if($user_level == 'subscriber')
	$nf_user_level = 'read';

if($user_level == 'contributor')
	$nf_user_level = 'edit_posts';

if($user_level == 'author')
	$nf_user_level = 'publish_posts';

if($user_level == 'editor')
	$nf_user_level = 'publish_pages';

if($user_level=='administrator')
	$nf_user_level = 'activate_plugins';

$theme = wp_get_theme();
if($theme->Name=='NEX-Forms Demo')
	$nf_user_level = 'read';

define('NF_USER_LEVEL',$nf_user_level);




if($page=="nex-forms-main" || $page=="nex-forms-dashboard" || $page=="nex-forms-builder")
	{
	add_action( 'wp_print_scripts', 'NEXForms5_deregister_javascript',100);
	add_action( 'wp_print_styles', 'NEXForms5_deregister_stylesheets',100);
	add_action( 'init', 'NEXForms5_deregister_javascript',100);
	add_action( 'init', 'NEXForms5_deregister_stylesheets',100);
	}
add_action('widgets_init', 'NEXForms_widget::register_this_widget');

function enqueue_nf_jquery(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-form');
	//wp_enqueue_script('jquery-ui-autocomplete');
	//wp_enqueue_script('jquery-ui-slider');
	//wp_enqueue_script('jquery-ui-widget');
	//wp_enqueue_script('jquery-ui-mouse');
	//wp_enqueue_script('jquery-touch-punch');
}

add_action( 'wp_enqueue_scripts', 'enqueue_nf_jquery' );


if(is_admin())
	{
	require dirname(__FILE__) . '/includes/classes/plugin-update-checker/plugin-update-checker.php';
	$MyUpdateChecker = new Puc_v4p4_Plugin_UpdateChecker (
		'https://basixonline.net/repository/nex-forms-updates.json',
		__FILE__,
		'main'
		);
	}

require( dirname(__FILE__) . '/includes/load.php');


function NEXForms5_deregister_javascript(){
	global $wp_scripts; 
	
	$output = '';
	
	$include_script_array = array('utils', 'common', 'wp-sanitize', 'sack', 'quicktags', 'colorpicker', 'editor', 'clipboard', 'wp-ajax-response', 'wp-api-request', 'wp-pointer', 'autosave', 'heartbeat', 'wp-auth-check', 'wp-lists', 'prototype', 'scriptaculous-root', 'scriptaculous-builder', 'scriptaculous-dragdrop', 'scriptaculous-effects', 'scriptaculous-slider', 'scriptaculous-sound', 'scriptaculous-controls', 'scriptaculous', 'cropper', 'jquery', 'jquery-core', 'jquery-migrate', 'jquery-ui-core', 'jquery-effects-core', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-puff', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-size', 'jquery-effects-slide', 'jquery-effects-transfer', 'jquery-ui-accordion', 'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-menu', 'jquery-ui-mouse', 'jquery-ui-progressbar', 'jquery-ui-selectmenu', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-tabs', 'jquery-ui-tooltip', 'jquery-ui-checkboxradio', 'jquery-ui-controlgroup', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'jquery-ui-sortable', 'jquery-ui-position', 'jquery-ui-widget', 'jquery-form', 'jquery-color', 'schedule', 'jquery-query', 'jquery-serialize-object', 'jquery-hotkeys', 'jquery-table-hotkeys', 'jquery-touch-punch', 'suggest', 'imagesloaded', 'masonry', 'jquery-masonry', 'thickbox', 'jcrop', 'swfobject', 'moxiejs', 'plupload', 'plupload-all', 'plupload-html5', 'plupload-flash', 'plupload-silverlight', 'plupload-html4', 'plupload-handlers', 'wp-plupload', 'swfupload', 'swfupload-all', 'swfupload-handlers', 'comment-reply', 'json2', 'underscore', 'backbone', 'wp-util', 'wp-backbone', 'revisions', 'imgareaselect', 'mediaelement', 'mediaelement-core', 'mediaelement-migrate', 'mediaelement-vimeo', 'wp-mediaelement', 'wp-codemirror', 'csslint', 'esprima', 'jshint', 'jsonlint', 'htmlhint', 'htmlhint-kses', 'code-editor', 'wp-theme-plugin-editor', 'wp-playlist', 'zxcvbn-async', 'password-strength-meter', 'application-passwords', 'auth-app', 'user-profile', 'language-chooser', 'user-suggest', 'admin-bar', 'wplink', 'wpdialogs', 'word-count', 'media-upload', 'hoverIntent', 'hoverintent-js', 'customize-base', 'customize-loader', 'customize-preview', 'customize-models', 'customize-views', 'customize-controls', 'customize-selective-refresh', 'customize-widgets', 'customize-preview-widgets', 'customize-nav-menus', 'customize-preview-nav-menus', 'wp-custom-header', 'accordion', 'shortcode', 'media-models', 'wp-embed', 'media-views', 'media-editor', 'media-audiovideo', 'mce-view', 'wp-api', 'admin-tags', 'admin-comments', 'xfn', 'postbox', 'tags-box', 'tags-suggest', 'post', 'editor-expand', 'link', 'comment', 'admin-gallery', 'admin-widgets', 'media-widgets', 'media-audio-widget', 'media-image-widget', 'media-gallery-widget', 'media-video-widget', 'text-widgets', 'custom-html-widgets', 'theme', 'inline-edit-post', 'inline-edit-tax', 'plugin-install', 'site-health', 'privacy-tools', 'updates', 'farbtastic', 'iris', 'wp-color-picker', 'dashboard', 'list-revisions', 'media-grid', 'media', 'image-edit', 'set-post-thumbnail', 'nav-menu', 'custom-header', 'custom-background', 'media-gallery', 'svg-painter', 'react', 'react-dom', 'regenerator-runtime', 'moment', 'lodash', 'wp-polyfill-fetch', 'wp-polyfill-formdata', 'wp-polyfill-node-contains', 'wp-polyfill-url', 'wp-polyfill-dom-rect', 'wp-polyfill-element-closest', 'wp-polyfill-object-fit', 'wp-polyfill-inert', 'wp-polyfill', 'wp-tinymce', 'wp-tinymce-lists', 'wp-a11y', 'wp-annotations', 'wp-api-fetch', 'wp-autop', 'wp-blob', 'wp-block-directory', 'wp-block-editor', 'wp-block-library', 'wp-block-serialization-default-parser', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-core-data', 'wp-customize-widgets', 'wp-data', 'wp-data-controls', 'wp-date', 'wp-deprecated', 'wp-dom', 'wp-dom-ready', 'wp-edit-post', 'wp-edit-site', 'wp-edit-widgets', 'wp-editor', 'wp-element', 'wp-escape-html', 'wp-format-library', 'wp-hooks', 'wp-html-entities', 'wp-i18n', 'wp-is-shallow-equal', 'wp-keyboard-shortcuts', 'wp-keycodes', 'wp-list-reusable-blocks', 'wp-media-utils', 'wp-notices', 'wp-nux', 'wp-plugins', 'wp-preferences', 'wp-preferences-persistence', 'wp-primitives', 'wp-priority-queue', 'wp-private-apis', 'wp-redux-routine', 'wp-reusable-blocks', 'wp-rich-text', 'wp-server-side-render', 'wp-shortcode', 'wp-style-engine', 'wp-token-list', 'wp-url', 'wp-viewport', 'wp-warning', 'wp-widgets', 'wp-wordcount', 'wp-block-file-view', 'wp-block-navigation-view', 'wp-block-navigation-view-2', 'nex-forms-polls', 'nex-forms-timer', 'nex-forms-admin-functions', 'nex-forms-charts', 'nex-forms-materialize.min', 'nex-forms-dashboard', 'nex-forms-bootstrap-admin', 'nex-forms-builder', 'nex-forms-field-settings-recall', 'nex-forms-field-settings', 'nex-forms-admin-tour', 'nex-forms-tinymce', 'nex-forms-drag-and-drop', 'nex-forms-locales.min', 'nex-forms-moment.min', 'nex-forms-date-time', 'nex-forms-raty', 'nex-forms-fields', 'nex-forms-bootstrap.touchspin', 'nex-forms-bootstrap.min', 'nex-forms-wow', 'nex-forms-raty-fa', 'nex-forms-onload', 'nex-forms-math.min', 'nex-forms-bootstrap-datetimepicker', 'nex-forms-bootstrap-touchspin', 'nex-forms-scrollTo', 'nex-forms-theme-script', 'nex-forms-ga', 'nex-forms-signature', 'nex-forms-themes-add-on');
	
	if($wp_scripts)
		{
		foreach($wp_scripts->registered as $wp_script=>$array)
			{
			if(!in_array($array->handle,$include_script_array) && !strstr($array->handle,'nex-forms') )
				{
				wp_deregister_script($array->handle);
				wp_dequeue_script($array->handle);
				}
			}	
		}
}

function NEXForms5_deregister_stylesheets(){
	global $wp_styles;
	
	$include_style_array = array('colors','wp-codemirror', 'wp-theme-plugin-editor','common','forms','admin-menu','dashboard','list-tables','bootstrap-timepicker','jqui-timepicker','bootstrap-material-datetimepicker','nf-nouislider','nf-jquery-ui','nf-md-checkbox-radio','edit','revisions','media','themes','about','nav-menus','widgets','site-icon','l10n','wp-admin','login','install','wp-color-picker','customize-controls','customize-widgets','customize-nav-menus','press-this','ie','buttons','dashicons','open-sans','admin-bar','wp-auth-check','editor-buttons','media-views','wp-pointer','customize-preview','wp-embed-template-ie','imgareaselect','wp-jquery-ui-dialog','mediaelement','wp-mediaelement','thickbox','deprecated-media','farbtastic','jcrop','colors-fresh','nex-forms-jQuery-UI','nex-forms-font-awesome','nex-forms-bootstrap','nex-forms-fields','nex-forms-ui','nex-forms-admin-style','nex-forms-animate','nex-forms-admin-overrides','nex-forms-admin-bootstrap.colorpickersliders','nex-forms-public-admin','nex-forms-editor','nex-forms-custom-admin','nex-forms-jq-ui','nf-styles-chosen','nf-admin-color-adapt', 'nex-forms-jq-ui','nf-styles-font-menu', 'nex-forms-bootstrap-tour.min','nf-color-adapt-fresh','nf-color-adapt-light','nf-color-adapt-blue','nf-color-adapt-coffee','nf-color-adapt-ectoplasm','nf-color-adapt-midnight','nf-color-adapt-ocean','nf-color-adapt-sunrise', 'nf-color-adapt-default', 'nex_forms-materialize.min','nex_forms-bootstrap.min','nex_forms-dashboard','nex_forms-font-awesome-5','nex_forms-font-awesome-4-shims','nex_forms-material-icons','ion.rangeSlider','ion.rangeSlider.skinFlat','nex_forms-builder','google-roboto');

	if($wp_styles)
		{
		foreach($wp_styles->registered as $wp_style=>$array)
			{
			if(!in_array($array->handle,$include_style_array) && !strstr($array->handle,'nex-forms'))
				{
				wp_deregister_style($array->handle);
				wp_dequeue_style($array->handle);
				}
			}
		}
}

	

class NEXForms5_Config{
	/*************  General  ***************/
	/************  DONT EDIT  **************/
	public $plugin_version;
	/* The displayed name of your plugin */
	public $plugin_name;
	/* The alias of the plugin used by external entities */
	public $plugin_alias;
	/* Enable or disable external modules */
	public $enable_modules;
	/* Plugin Prefix */
	public $plugin_prefix;
	/* Plugin table */
	public $plugin_table, $component_table;
	/* Admin Menu */
	public $plugin_menu;
	/* Add TinyMCE */
	public $add_tinymce;
	
	
	/************* Database ****************/
	/* Sets the primary key for table created above */
	public $plugin_db_primary_key = 'Id';
	/* Database table fields array */
	public $plugin_db_table_fields = array
			(
			'title'								=>	'text',
			'description'						=>	'text',
			'mail_to'							=>  'text',
			'confirmation_mail_body'			=>  'longtext',
			'admin_email_body'					=>  'longtext',
			'confirmation_mail_subject'			=>	'text',
			'user_confirmation_mail_subject'	=>	'text',
			'from_address'						=>  'text',
			'from_name'							=>  'text',
			'on_screen_confirmation_message'	=>  'longtext',
			'on_screen_confirmation_message_admin'	=>  'longtext',
			'confirmation_page'					=>  'text',
			'form_fields'						=>	'longtext',
			'clean_html'						=>	'longtext',
			'visual_settings'					=>	'text',
			'google_analytics_conversion_code'  =>  'text',
			'colour_scheme'  					=>  'text',
			'send_user_mail'					=>  'text',
			'user_email_field'					=>  'text',
			'on_form_submission'				=>  'text',
			'upload_settings'					=>  'longtext',
			'attachment_settings'				=>  'longtext',
			'date_sent'							=>  'datetime',
			'is_form'							=>  'text',
			'is_template'						=>  'text',
			'hidden_fields'						=>  'longtext',
			'form_hidden_fields'				=>  'longtext',
			'custom_url'						=>  'text',
			'post_type'							=>  'text',
			'post_action'						=>  'text',
			'bcc'								=>  'text',
			'bcc_user_mail'						=>  'text',
			'custom_css'						=>  'longtext',
			'is_paypal'							=>  'text',
			'total_views'						=>  'text',
			'time_viewed'						=>  'text',
			'email_on_payment_success'			=>  'text',
			'conditional_logic'					=>  'longtext',
			'conditional_logic_array'			=>  'longtext',
			'server_side_logic'					=>  'longtext',
			'form_status'						=>  'text',
			'currency_code'						=>  'text',
			'products'							=>  'longtext',
			'business'							=>  'text',
			'cmd'								=>  'text',
			'return_url'						=>  'text',
			'cancel_url'						=>  'text',
			'lc'								=>  'text',
			'environment'						=>  'text',
			'email_subscription'				=>  'longtext',
			'mc_field_map'						=>  'longtext',
			'mc_list_id'						=>  'text',
			'gr_field_map'						=>  'longtext',
			'gr_list_id'						=>  'text',
			'mp_field_map'						=>  'longtext',
			'mp_list_id'						=>  'text',
			'ms_field_map'						=>  'longtext',
			'ms_list_id'						=>  'text',
			'pdf_html'							=>  'longtext',
			'attach_pdf_to_email'				=>	'text',
			'pdf_settings'						=>  'longtext',
			'form_to_post_map'					=>  'longtext',
			'is_form_to_post'					=>  'text',
			'md_theme'							=>	'text',
			'form_theme'						=>	'text',
			'jq_theme'							=>	'text',
			'form_style'						=>  'longtext',
			'msg_style'							=>  'longtext',
			'multistep_settings'				=>	'longtext',
			'multistep_html'					=>	'longtext',
			'paypal_client_Id'					=>  'longtext',
			'paypal_client_secret'				=>  'longtext',
			'payment_success_msg'				=>  'longtext',
			'payment_failed_msg'				=>  'longtext',
			'option_settings'					=>  'longtext',
			);
	


	public $form_entry_table_fields = array
			(
			'nex_forms_Id'			=>	'text',
			'page'					=>	'text',
			'ip'					=>  'text',
			'hostname'				=>	'text',
			'city'					=>	'text',
			'region'				=>	'text',
			'country'				=>	'text',
			'loc'					=>	'text',
			'org'					=>	'text',
			'postal'				=>	'text',
			'user_Id'				=>	'text',
			'viewed'				=>	'text',
			'date_time'				=>  'datetime',
			'paypal_invoice'		=>	'text',
			'payment_status'		=>  'text',
			'form_data'				=>	'longtext',
			'paypal_data'			=>	'longtext',
			'paypal_payment_token'	=>	'longtext',
			'paypal_payment_id'		=>	'longtext',
			'payment_ammount'		=>	'longtext',
			'payment_currency'		=> 	'text',
			'saved_admin_email'		=>	'longtext',
			'saved_user_email'		=>	'longtext',
			'saved_user_email_address'	=>	'text',
			);
	
	public $email_table_fields = array
			(
			'nex_forms_Id'						=>	'text',
			'mail_type'							=>  'text',
			'mail_to'							=>  'text',
			'mail_body'							=>  'longtext',
			'mail_subject'						=>	'text',
			'from_address'						=>  'text',
			'from_name'							=>  'text',
			'send_user_mail'					=>  'text',
			'user_email_field'					=>  'text',
			'bcc'								=>  'text',
			'bcc_user_mail'						=>  'text',
			'attachments'						=>  'text',
			);
	
	
	public $stats_table_fields = array
			(
			'nex_forms_Id'			=>	'text',
			'time_viewed'			=>	'text',
			);
	public $form_interactions = array
			(
			'nex_forms_Id'			=>	'text',
			'time_interacted'		=>	'text',
			);
	
	public $file_manager = array
			(
			'nex_forms_Id'			=>	'text',
			'name'					=>	'text',
			'type'					=>	'text',
			'size'					=>	'text',
			'url'					=>	'text',
			'location'				=>	'text',
			'entry_Id'				=>	'text'
			);
	
	/************* Admin Menu **************/
	public function __construct()
		{ 
		
		$functions = new NEXForms_Functions();
		
		$header_info = $functions->get_file_headers(dirname(__FILE__).DIRECTORY_SEPARATOR.'main.php');
		
		$this->plugin_version 	= $header_info['Version'];
		$this->plugin_name 		= $header_info['Plugin Name'];
		$this->plugin_alias		= $functions->format_name($this->plugin_name);
		$this->plugin_prefix	= NF_TABLE_PREFIX;
		$this->plugin_table		= $this->plugin_prefix.$this->plugin_alias;
		$this->component_table	= $this->plugin_table;
		$this->add_tinymce		= $header_info['Plugin TinyMCE'];
		}
}

/***************************************/
/*************  Hooks   ****************/
/***************************************/
/* On plugin activation */
register_activation_hook(__FILE__, 'NEXForms5_run_instalation' );
/* Called from page */
add_shortcode( 'NEXForms', 'NEXForms_ui_output' );
/* Build admin menu */
add_action('admin_menu', 'NEXForms5_main_menu');


$other_config = get_option('nex-forms-other-config');

/* Add action button to TinyMCE Editor */
if($other_config['enable-tinymce']=='1')
	add_action('init', 'NEXForms_add_mce_button');

/* Add action button to TinyMCE Editor */
function NEXForms_add_mce_button() {
	add_filter("mce_external_plugins", "NEXForms_tinymce_plugin");
 	add_filter('mce_buttons', 'NEXForms_register_button');
}
/* register button to be called from JS */
function NEXForms_register_button($buttons) {
   array_push($buttons, "separator", "nexforms");
   return $buttons;
}
/* Send request to JS */
function NEXForms_tinymce_plugin($plugin_array) {
   $plugin_array['nexforms'] = plugins_url( '/includes/tinyMCE/plugin.js',__FILE__);
   return $plugin_array;
}


/***************************************/
/*********  Hook functions   ***********/
/***************************************/
/* Convert menu to WP Admin Menu */
function NEXForms5_main_menu(){
	
	
	add_menu_page( 'NEX-Forms', 'NEX-Forms', NF_USER_LEVEL, 'nex-forms-dashboard', 'NEXForms_dashboard', plugins_url('/admin/css/'.NF_PATH.'images/menu_icon.png',__FILE__),30 );
	
	add_submenu_page( 'nex-forms-dashboard', 'NF-Builder','Dashboard', NF_USER_LEVEL, 'nex-forms-dashboard', 'NEXForms_dashboard');
	add_submenu_page( 'nex-forms-dashboard', 'NF-Entries','Form Entries', NF_USER_LEVEL, 'nex-forms-page-submissions', 'NEXForms_entries_page');
	add_submenu_page( 'nex-forms-dashboard', 'NF-Reporting','Reporting', NF_USER_LEVEL, 'nex-forms-page-reporting', 'NEXForms_reporting_page');
	add_submenu_page( 'nex-forms-dashboard', 'NF-Analytics','Analytics',NF_USER_LEVEL, 'nex-forms-page-analytics', 'NEXForms_stats_page');
	add_submenu_page( 'nex-forms-dashboard', 'NF-File Uploads','File Uploads', NF_USER_LEVEL, 'nex-forms-page-file-uploads', 'NEXForms_attachments_page');
	add_submenu_page( 'nex-forms-dashboard', 'NF-Setup','Settings', NF_USER_LEVEL, 'nex-forms-page-global-settings', 'NEXForms_global_setup_page');
	add_submenu_page( 'nex-forms-dashboard', 'NF-Add-ons','Add-ons', NF_USER_LEVEL, 'nex-forms-page-add-ons', 'NEXForms_add_ons_page');
	
	add_submenu_page( 'nex-forms-dashboard', 'NF-Builder-View','Builder', NF_USER_LEVEL, 'nex-forms-builder', 'NEXForms_form_builder');
	add_submenu_page( null, 'NF-Test','Test', NF_USER_LEVEL, 'nex-forms-test-page', 'NEXForms_test_page');
	
	add_submenu_page( null, 'NF-Welcome','Welcome', NF_USER_LEVEL, 'nf-welcome-page', 'NEXForms_welcome_page_content');
	
	
	//add_submenu_page( null, 'NF-Preview','nex-forms-preview', NF_USER_LEVEL, 'nex-forms-preview', 'NEXForms_form_preview');
}


function NEXForms_my_menu_pages() {
	add_action( 'init', 'nf_prefix_register_resources' );
	
	$hook2 = add_submenu_page( null, 'Page Title', 'Page Title', NF_USER_LEVEL, 'nex-forms-email-preview', function() { });
	 add_action('load-' . $hook2, function()
		{
		global $wpdb; 
		
		$get_entry 	= $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE Id = %d',filter_var($_REQUEST['entry_Id'],FILTER_SANITIZE_NUMBER_INT));
		$entry 	= $wpdb->get_row($get_entry);
		
		if($_REQUEST['emial-preview']=='admin')
			echo $entry->saved_admin_email;
		else
			{
			if($entry->saved_user_email_address)
				{
				echo $entry->saved_user_email;	
				}
			else
				echo '<center><strong style="font-family:Arial;"><br /><br />No User Email Sent.</strong></center>';
			}
		exit;
		}
	);
	
    $hook = add_submenu_page( null, 'Page Title', 'Page Title', NF_USER_LEVEL, 'nex-forms-preview', function() { });
	
    add_action('load-' . $hook, function()
		{
		global $wpdb; 
		$config = new NEXForms5_Config();
		$css_js_version = $config->plugin_version.'.22';
		$script_config = get_option('nex-forms-script-config');
		$styles_config = get_option('nex-forms-style-config');
		$other_config = get_option('nex-forms-other-config');
		$output = '';
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-autocomplete');
		//wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-form');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-touch-punch');	
		wp_enqueue_script('nex-forms-materialize.min');
		wp_enqueue_script('nex-forms-timer');
		wp_enqueue_script('nex-forms-onload');
		wp_enqueue_script('nex-forms-math.min');
		wp_enqueue_script('nex-forms-moment.min');
		wp_enqueue_script('nex-forms-locales.min');
		wp_enqueue_script('nex-forms-bootstrap-datetimepicker');
		wp_enqueue_script('nex-forms-bootstrap-touchspin');
/* UI STYLE REGISTRATION */		
	if($styles_config['incstyle-font-awesome']=='1' || !array_key_exists('incstyle-font-awesome',$styles_config))
		{
		wp_enqueue_style('nex-forms-font-awesome-5',plugins_url( '/public/css/fa5/css/all.min.css',__FILE__),'',$css_js_version);
		}
	if($styles_config['incstyle-font-awesome-v4-shims']=='1' || !array_key_exists('incstyle-font-awesome-v4-shims',$styles_config))
		{
		wp_enqueue_style('nex-forms-font-awesome-4-shims',plugins_url( '/public/css/fa5/css/v4-shims.min.css',__FILE__),'',$css_js_version);
		}
	if($styles_config['incstyle-bootstrap']=='1' || !array_key_exists('incstyle-bootstrap',$styles_config))
		wp_enqueue_style('nex-forms-bootstrap-ui', plugins_url( '/public/css/min/ui-bootstrap.css',__FILE__),'',$css_js_version);
		
	wp_enqueue_style('nex-forms-ui', plugins_url( '/public/css/'.NF_PATH.'ui.css?v=7.2.7',__FILE__),'',$css_js_version);
		
	if($styles_config['incstyle-animations']=='1' || !array_key_exists('incstyle-animations',$styles_config))
		wp_enqueue_style('nex-forms-animations', plugins_url( '/public/css/min/animate.css',__FILE__),'',$css_js_version);		

	wp_enqueue_style('nex-forms-material-theme-amber', plugins_url( '/public/css/themes/amber.css',__FILE__),'',$css_js_version);
	wp_enqueue_style('nex-forms-materialize', plugins_url( '/public/css/min/materialize-ui.css',__FILE__),'',$css_js_version);
	wp_enqueue_style('nex-forms-preview', plugins_url( '/admin/css/preview.css',__FILE__),'',$css_js_version);	
	
	//wp_enqueue_style('nex-forms-selects-css', plugins_url( '/public/css/min/nex-select.css',__FILE__),'',$css_js_version);
		
/* UI SCRIPT REGISTRATION */
	if($script_config['inc-bootstrap']=='1' || !$script_config['inc-bootstrap'])
		wp_enqueue_script('nex-forms-bootstrap.min',  plugins_url( '/public/js/min/bootstrap.min.js',__FILE__),'',$css_js_version);
	if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
		wp_enqueue_script('nex-forms-wow',  plugins_url( '/libs/wow.min.js',__FILE__),'',$css_js_version);
	if($script_config['inc-raty']=='1' || !$script_config['inc-raty'])
		wp_enqueue_script('nex-forms-raty-fa',  plugins_url( '/public/js/min/jquery.raty-fa.js',__FILE__),'',$css_js_version);
	
	wp_enqueue_script('nex-forms-input-mask', plugins_url( '/public/js/min/jquery.inputmask.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-sigs', plugins_url( '/public/js/min/jquery.sig.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-tags', plugins_url( '/public/js/min/jquery.tags.js',__FILE__),'',$css_js_version);
	
	wp_enqueue_script('nex-forms-timer',  plugins_url( '/public/js/min/jquery.timer.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-materialize.min',  plugins_url( '/libs/materialize.min.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-onload', plugins_url( '/public/js/'.NF_PATH.'nexf-onload-ui.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-math.min',  plugins_url( '/libs/math.min.js',__FILE__),'',$css_js_version);
	
	wp_enqueue_script('nex-forms-moment.min', plugins_url( '/libs/moment.min.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-locales.min', plugins_url( '/libs/locales.min.js',__FILE__),'',$css_js_version);
	wp_enqueue_script('nex-forms-bootstrap-datetimepicker', plugins_url( '/public/js/'.NF_PATH.'bootstrap-datetimepicker.js',__FILE__),'',$css_js_version);
	
	wp_enqueue_script('nex-forms-bootstrap-touchspin', plugins_url( '/public/js/min/jquery.bootstrap-touchspin.js',__FILE__),'',$css_js_version);
	
	//wp_enqueue_script('nex-forms-selects-js', plugins_url( '/libs/nex-select.js',__FILE__),'',$css_js_version);

	
	 
	$extra_script = '';
	
	if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
		$extra_script .= 'var get_wow = "enabled";';
	else
		$extra_script .= 'var get_wow = "disabled";';
	
	$extra_script .= 'var get_modal = "disabled";';
	
	if($script_config['inc-raty']=='1' || !$script_config['inc-raty'])
		$extra_script .= 'var get_raty = "enabled";';
	else
		$extra_script .= 'var get_raty = "disabled";';
	
	wp_add_inline_script('nex-forms-bootstrap.min', $extra_script);

	  
	 
	$unigue_form_Id 	= rand(0,99999);
	   
	$form_attr 	= $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',sanitize_text_field($_REQUEST['form_Id'])));
	
	$theme_settings = json_decode($form_attr->md_theme,true);
	$set_theme 		= ($theme_settings['0']['theme_name']!='') 	? $theme_settings['0']['theme_name'] 	: 'default';
	
	if($form_attr->form_theme=='m_design' || $form_attr->form_theme=='neumorphism')
		wp_enqueue_style('nex-forms-material-theme-'.$set_theme, plugins_url( '/public/css/themes/'.$set_theme.'.css',__FILE__),'',$css_js_version);
			
	/*if(function_exists('nf_not_found_notice_ft') && $form_attr->form_theme!='m_design' && $form_attr->form_theme!='neumorphism')
		echo '<link rel="stylesheet" type="text/css" href="'.plugins_url( '/nex-forms-themes-add-on7/css/'.$form_attr->jq_theme.'/jquery.ui.theme.css?v='.$css_js_version,dirname(__FILE__)).'" />';
	*/
	
	$option_settings = json_decode($form_attr->option_settings,true);
	
	$nf_functions 		= new NEXForms_Functions();
	//ADD CONDITIONAL LOGIC SCRIPT
	$cl_script = $nf_functions->run_conditional_logic(json_decode($form_attr->conditional_logic_array), $unigue_form_Id);	
		
	wp_add_inline_script('nex-forms-bootstrap.min', $cl_script);
	$custom_user_func_before_submit_js = 'function nf_custom_user_func_before_submit(formData, jqForm, options){';
	
	if($option_settings[0]['before_submit_js'] && $option_settings[0]['before_submit_js']!='')
		$custom_user_func_before_submit_js .= str_replace('\\','',$option_settings[0]['before_submit_js']);
	else
		$custom_user_func_before_submit_js .= 'return true;';
		
	$custom_user_func_before_submit_js .= '}';	
	
	wp_add_inline_script('nex-forms-bootstrap.min', $custom_user_func_before_submit_js);
	
	$custom_user_func_after_submit_js = 'function nf_custom_user_func_after_submit(formData, jqForm, options, $form){';
	
	if($option_settings[0]['after_submit_js'] && $option_settings[0]['after_submit_js']!='')
		$custom_user_func_after_submit_js .= str_replace('\\','',$option_settings[0]['after_submit_js']);	
	else
		$custom_user_func_after_submit_js .= 'return true;';
	
	$custom_user_func_after_submit_js .= '}';	
	
	wp_add_inline_script('nex-forms-bootstrap.min', $custom_user_func_after_submit_js); 
	
	
	
	wp_print_styles();
	wp_print_scripts();
	
	echo  NEXForms_ui_output($_REQUEST['form_Id'],true,'',$unigue_form_Id);
	exit;
    });
}

add_action('admin_menu', 'NEXForms_my_menu_pages');


function NEXForms_test_page(){
	
	
	//NEXForms_run_poll(30, 'product_selection');
	
	
	/*global $wp_scripts; 
	
	$output = '';
	
	
	if($wp_scripts)
		{
		foreach($wp_scripts->registered as $wp_script=>$array)
			{
				
			//'utils','common','
			echo ' \''.$array->handle.'\', ';
				
			}	
		}*/
		
}


/***************************************/
/***********  INSTALATION   ************/
/***************************************/
$config = new NEXForms5_Config();
if($config->plugin_version!=get_option('nex-forms-version'))
	NEXForms5_run_instalation();



function NEXForms5_run_instalation(){
	$config = new NEXForms5_Config();
	global $wpdb;
	update_option('nex-forms-version',$config->plugin_version);
	//PREFERENCES	
	//'date_time'				=>  date('Y-m-d H:i:s'),
	update_option('nf-first-run', true);
	update_option('nex-forms-preferences',
		array(
			'field_preferences'=>
				array(
					'pref_label_align'		=>'top',
					'pref_label_text_align'	=>'align_left',
					'pref_label_size'		=>'',
					'pref_sub_label'		=>'',
					'pref_input_text_align'	=>'aling_left',
					'pref_input_size'		=>'',
				),
			'validation_preferences'=>
				array(
					'pref_requered_msg'				=> __('Required','nex-forms'),
					'pref_email_format_msg'			=> __('Invalid email address','nex-forms'),
					'pref_phone_format_msg'			=> __('Invalid phone number','nex-forms'),
					'pref_url_format_msg'			=> __('Invalid URL','nex-forms'),						
					'pref_numbers_format_msg'		=> __('Only numbers are allowed','nex-forms'),
					'pref_char_format_msg'			=> __('Only text are allowed','nex-forms'),
					'pref_invalid_file_ext_msg'		=> __('Invalid file extension','nex-forms'),
					'pref_max_file_exceded'			=> __('Maximum File Size of {x}MB Exceeded','nex-forms'),
					'pref_min_file_exceded'			=> __('Minimum File Size of {x}MB Required','nex-forms'),
					'pref_max_file_ul_exceded'		=> __('Only a maximum of {x} files can be uploaded','nex-forms'),
					'pref_max_file_af_exceded'		=> __('Maximum Size for all files can not exceed {x}MB ','nex-forms'),
				),
			'email_preferences'=>
				array(
					'pref_email_from_address'	=> get_option('admin_email'),
					'pref_email_from_name'		=> get_option('blogname'),
					'pref_email_recipients'		=> get_option('admin_email'),
					'pref_email_subject'		=> get_option('blogname').__(' - NEX-Forms Submission','nex-forms'),
					'pref_email_body'			=> '{{nf_form_data}}',
					'pref_user_email_subject'	=> get_option('blogname').__(' - NEX-Forms Submission','nex-forms'),
					'pref_user_email_body'		=> __('Thank you for connecting with us. We will respond to you shortly.','nex-forms'),
				),
			'other_preferences'=>
				array(
					'pref_other_on_screen_message' =>  __('Thank you for connecting with us. We will respond to you shortly.','nex-forms'),
				),
			)
		);
		
	//EMAIL SETTINGS
	if(!get_option('nex-forms-email-config'))
		{
		update_option('nex-forms-email-config',array(
				'email_method'=>'php_mailer', 
				'email_content'=>'html', 
				'smtp_auth'=>'0',
				'smtp_host'=>'smtp.gmail.com',
				'email_smtp_secure'=>'tls',
				'mail_port'=>'587',
				'set_smtp_user'=>'',
				'set_smtp_pass'=>'')
			);
		}
	
	//SCRIPT SETTINGS	
	update_option('nex-forms-script-config',array(
			'inc-jquery'=>'1',
			'inc-jquery-ui-core'=>'1',
			'inc-jquery-ui-autocomplete'=>'1',
			'inc-jquery-ui-slider'=>'1',
			'inc-jquery-form'=>'1',
			'inc-bootstrap'=>'1',
			'inc-onload'=>'1',
			'inc-moment'=>'1',
			'inc-datetime'=>'1',
			'inc-math'=>'1',
			'inc-colorpick'=>'1',
			'inc-wow'=>'1',
			'inc-raty'=>'1',
			'inc-locals'=>'1',
			'inc-sig'=>'0',
			'enable-print-scripts'=>0	
		));
	//STYLE SETTINGS
	update_option('nex-forms-style-config',array(
			'incstyle-jquery'=>'1',
			'incstyle-font-awesome'=>'1',
			'incstyle-font-awesome-v4-shims'=>'1',
			'incstyle-bootstrap'=>'1',
			'incstyle-animations'=>'1',
			'incstyle-custom'=>'1',
			'enable-print-styles'=>1
		));
	
	//OTHER SETTINGS
	update_option('nex-forms-other-config',array(
			'enable-print-scripts'=>'0',
			'enable-print-styles'=>'1',
			'enable-tinymce'=>'1',
			'enable-widget'=>'1',
			'enable-color-adapt'=>'1',
			'set-wp-user-level'=>'administrator'	
		));
	
	
	
	
	/************************************************/
	/**************  MAIN DB TABLE   ****************/
	/************************************************/	
	$instalation = new NF5_Instalation();
	$instalation->component_name 			=  $config->plugin_name;
	$instalation->component_prefix 			=  $config->plugin_prefix;
	$instalation->component_alias			=  'nex_forms';
	$instalation->component_menu 			=  $config->plugin_menu;	
	$instalation->db_table_fields			=  $config->plugin_db_table_fields;
	$instalation->db_table_primary_key		=  $config->plugin_db_primary_key;
	$instalation->run_instalation('full');
	
	/************************************************/
	/************  Additional Tables   **************/
	/************************************************/
	/* Entries Table */
	$instalation = new NF5_Instalation();
	$instalation->component_prefix 		=  $config->plugin_prefix;
	$instalation->component_alias		=  'nex_forms_entries';
	$instalation->db_table_fields		=  $config->form_entry_table_fields;
	$instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$instalation->install_component_table();	
	
	/* Email Table */
	$instalation = new NF5_Instalation();
	$instalation->component_prefix 		=  $config->plugin_prefix;
	$instalation->component_alias		=  'nex_forms_email_templates';
	$instalation->db_table_fields		=  $config->email_table_fields;
	$instalation->db_table_primary_key	=  $config->plugin_db_primary_key;

	$instalation->install_component_table();	
	
	/* Stats Tables */
	$instalation = new NF5_Instalation();
	$instalation->component_prefix 		=  $config->plugin_prefix;
	$instalation->component_alias		=  'nex_forms_views';
	$instalation->db_table_fields		=  $config->stats_table_fields;
	$instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$instalation->install_component_table();	
	
	$instalation = new NF5_Instalation();
	$instalation->component_prefix 		=  $config->plugin_prefix;
	$instalation->component_alias		=  'nex_forms_stats_interactions';
	$instalation->db_table_fields		=  $config->form_interactions;
	$instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$instalation->install_component_table();	
	
	/* Files Table */
	$instalation = new NF5_Instalation();
	$instalation->component_prefix 		=  $config->plugin_prefix;
	$instalation->component_alias		=  'nex_forms_files';
	$instalation->db_table_fields		=  $config->file_manager;
	$instalation->db_table_primary_key	=  $config->plugin_db_primary_key;
	$instalation->install_component_table();	
	
	
	$database_actions = new NEXForms_Database_Actions();
	$database_actions->recollate_plugin_table();
	
	$database_actions->alter_plugin_table('wap_nex_forms','last_update','TIMESTAMP');
	$database_actions->alter_plugin_table('wap_nex_forms','hidden_fields','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','clean_html','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','custom_url','text');
	$database_actions->alter_plugin_table('wap_nex_forms','admin_email_body','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','bcc','text');
	$database_actions->alter_plugin_table('wap_nex_forms','bcc_user_mail','text');
	$database_actions->alter_plugin_table('wap_nex_forms','post_type','text');
	$database_actions->alter_plugin_table('wap_nex_forms','post_action','text');	
	$database_actions->alter_plugin_table('wap_nex_forms','custom_css','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','is_paypal','text');
	$database_actions->alter_plugin_table('wap_nex_forms','total_views','text');
	$database_actions->alter_plugin_table('wap_nex_forms','time_viewed','text');
	$database_actions->alter_plugin_table('wap_nex_forms','email_on_payment_success','text');
	$database_actions->alter_plugin_table('wap_nex_forms','conditional_logic','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','conditional_logic_array','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','server_side_logic','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','form_type','text');
	$database_actions->alter_plugin_table('wap_nex_forms','template_type','text');
	$database_actions->alter_plugin_table('wap_nex_forms','user_confirmation_mail_subject','text');
	$database_actions->alter_plugin_table('wap_nex_forms','draft_Id','text');
	$database_actions->alter_plugin_table('wap_nex_forms','pdf_html','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','attach_pdf_to_email','text');
	$database_actions->alter_plugin_table('wap_nex_forms','pdf_settings','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','form_to_post_map','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','is_form_to_post','text');
	$database_actions->alter_plugin_table('wap_nex_forms','form_status','text');
	$database_actions->alter_plugin_table('wap_nex_forms','form_hidden_fields','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','kak','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','paypal_client_Id','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','paypal_client_secret','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','payment_success_msg','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','payment_failed_msg','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','upload_settings','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','attachment_settings','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','currency_code','text');
	$database_actions->alter_plugin_table('wap_nex_forms','products','text');
	$database_actions->alter_plugin_table('wap_nex_forms','business','text');
	$database_actions->alter_plugin_table('wap_nex_forms','cmd','text');
	$database_actions->alter_plugin_table('wap_nex_forms','return_url','text');
	$database_actions->alter_plugin_table('wap_nex_forms','cancel_url','text');
	$database_actions->alter_plugin_table('wap_nex_forms','lc','text');
	$database_actions->alter_plugin_table('wap_nex_forms','environment','text');
	$database_actions->alter_plugin_table('wap_nex_forms','email_subscription','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','mc_field_map','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','mc_list_id','text');
	$database_actions->alter_plugin_table('wap_nex_forms','mp_field_map','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','mp_list_id','text');
	$database_actions->alter_plugin_table('wap_nex_forms','ms_field_map','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','ms_list_id','text');
	$database_actions->alter_plugin_table('wap_nex_forms','gr_field_map','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','gr_list_id','text');
	$database_actions->alter_plugin_table('wap_nex_forms','option_settings','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','jq_theme','text');
	$database_actions->alter_plugin_table('wap_nex_forms','md_theme','text');
	$database_actions->alter_plugin_table('wap_nex_forms','form_theme','text');
	$database_actions->alter_plugin_table('wap_nex_forms','form_style','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','msg_style','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','is_wrapped','text');
	$database_actions->alter_plugin_table('wap_nex_forms','multistep_settings','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','multistep_html','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','on_screen_confirmation_message_admin','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','entry_count','BIGINT(255) DEFAULT 0');
	$database_actions->alter_plugin_table('wap_nex_forms','zapier_web_hook_url','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','hubspot_portal_id','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','hubspot_form_id','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','is_hubspot','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','reply_to','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms','enqueue_array','longtext');
	
	$database_actions->alter_plugin_table('wap_nex_forms_entries','hostname','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','city','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','region','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','country','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','loc','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','org','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','postal','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','paypal_payment_id','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','paypal_payment_token','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','payment_ammount','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','payment_currency','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','saved_admin_email','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','saved_user_email','longtext');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','saved_user_email_address','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','starred','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','trashed','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','attachments','text');

	$database_actions->alter_plugin_table('wap_nex_forms_files','location','text');
	$database_actions->alter_plugin_table('wap_nex_forms_files','entry_Id','text');
	$database_actions->alter_plugin_table('wap_nex_forms_files','trashed','text');
	
	$database_actions->alter_plugin_table('wap_nex_forms_views','date_time','datetime');
	$database_actions->alter_plugin_table('wap_nex_forms_stats_interactions','date_time','datetime');
	
	//MIGRATE ATTACHMENT DATA
	if(get_option('nf_set_attachments')!='1')
			{
			$get_files = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_files');
	
			foreach($get_files as $file)
				{
				$data_array = array(
					'attachments'	=>  1
					);
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', $data_array, array(	'Id' => $file->entry_Id) );
				}
			update_option('nf_set_attachments','1');
			}
	
	
	
	//MIGRATE PAYPAL DATA FOR BACKWARD COMPATIBILITY
	$get_paypal_table = $wpdb->get_var("show tables like '".$wpdb->prefix."wap_nex_forms_paypal"."'");
	if($get_paypal_table)
		{
		if(get_option('convert_paypal')!='1')
			{
			$get_paypal = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_paypal');
	
			foreach($get_paypal as $paypal_data)
				{
				$data_array = array(
					'currency_code'						=>  $paypal_data->currency_code,
					'products'							=>  $paypal_data->products,
					'business'							=>  $paypal_data->business,
					'cmd'								=>  $paypal_data->cmd,
					'return_url'						=>  $paypal_data->return_url,
					'cancel_url'						=>  $paypal_data->cancel_url,
					'lc'								=>  $paypal_data->lc,
					'environment'						=>  $paypal_data->environment
					);
				$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms', $data_array, array(	'Id' => $paypal_data->nex_forms_Id) );
				}
			update_option('convert_paypal','1');
			}
		}
	
	
	$database_actions->alter_plugin_table('wap_nex_forms_entries','paypal_invoice','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','payment_status','text');
	$database_actions->alter_plugin_table('wap_nex_forms_entries','paypal_data','longtext');	
}

/***************************************/
/************  ADMIN PAGES  ************/
/***************************************/
//
function NEXForms5_main_page(){
	
	$builder = new NF5_Form_Builder();
	$builder->form_builder_page();
	
	global $wp_styles;
	$include_style_array = array('colors','common','wp-codemirror', 'wp-theme-plugin-editor','forms','admin-menu','dashboard','list-tables','bootstrap-timepicker','jqui-timepicker','bootstrap-material-datetimepicker','nf-nouislider','nf-jquery-ui','nf-md-checkbox-radio','edit','revisions','media','themes','about','nav-menus','widgets','site-icon','l10n','wp-admin','login','install','wp-color-picker','customize-controls','customize-widgets','customize-nav-menus','press-this','ie','buttons','dashicons','open-sans','admin-bar','wp-auth-check','editor-buttons','media-views','wp-pointer','customize-preview','wp-embed-template-ie','imgareaselect','wp-jquery-ui-dialog','mediaelement','wp-mediaelement','thickbox','deprecated-media','farbtastic','jcrop','colors-fresh','nex-forms-jQuery-UI','nex-forms-font-awesome','nex-forms-bootstrap','nex-forms-fields','nex-forms-ui','nex-forms-admin-style','nex-forms-animate','nex-forms-admin-overrides','nex-forms-admin-bootstrap.colorpickersliders','nex-forms-public-admin','nex-forms-editor','nex-forms-custom-admin','nex-forms-jq-ui','nf-styles-chosen','nf-admin-color-adapt', 'nex-forms-jq-ui','nf-styles-font-menu', 'nex-forms-bootstrap-tour.min','nf-color-adapt-fresh','nf-color-adapt-light','nf-color-adapt-blue','nf-color-adapt-coffee','nf-color-adapt-ectoplasm','nf-color-adapt-midnight','nf-color-adapt-ocean','nf-color-adapt-sunrise', 'nf-color-adapt-default','nex_forms-materialize.min','nex_forms-bootstrap.min','nex_forms-dashboard','nex_forms-font-awesome-5','nex_forms-font-awesome-4-shims','nex_forms-material-icons','ion.rangeSlider','ion.rangeSlider.skinFlat','nex_forms-builder','google-roboto');

	echo '<div class="unwanted_css_array" style="display:none;">';
	foreach($wp_styles->registered as $wp_style=>$array)
		{
		if(!in_array($array->handle,$include_style_array) && !strstr($array->handle,'nex-forms'))
			{
			echo '<div class="unwanted_css">'.$array->handle.'-css</div>';
			}
		}	
	echo '</div>';

}

function NEXForms_form_preview(){
	
	global $wpdb;
	$get_form = $wpdb->prepare('SELECT md_theme FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($_REQUEST['form_Id'],FILTER_SANITIZE_NUMBER_INT));
	$theme_settings = $wpdb->get_var($get_form);
	$theme_settings = json_decode($theme_settings,true);
	//remove WP elements from preview
	echo '	<style type="text/css">
	.show_form_preview .v7_container{ margin:0 auto; } input.form-control:focus, #nex-forms select.form-control:focus, #nex-forms textarea.form-control:focus{border-color:#ddd !important;}#nex-forms .v7_container {margin:0 auto !important;	}#nex-forms .inner-canvas-container .label_container label{padding-bottom:5px !important;cursor:default !important;display:block !important;}#nex-forms .submit-button button.btn.col-sm-12 {width:100% !important;}#wpbody-content {height: calc(95%);left: 0;position: absolute;top: 0;width: 100% !important;z-index: 10000000;background:#fff;padding: 30px 10% !important;}div.updated, .update-nag , div.error, #icl_als_help_popup, .wd-notice-body, .notice,.notice-warning,.mom_modal_box, .notice, #dolly,.cts-tip-wrapper{display:none; !important}#wpcontent {margin-left: 0 !important;padding: 15px !important;}#wpadminbar, #adminmenumain, #wpfooter {display: none;}#wpbody {padding-top: 0 !important;}html{background:#fff !important;padding-top:0 !important;}#wpwrap{background:#fff !important;}.wp-admin select {height: auto !important;}.wp-admin .nex-forms-container select {height: 34px !important;}.wp-admin .nex-forms-container .multi-select select {height: auto !important;}#wpfooter{display:none;}.row.outer_container {margin: 0 !important;}#nex-forms .star-rating {white-space: unset;}#adminmenuback{z-index:-1 !important;}#wpbody-content {
    height: calc(100%);
    width: 100% !important;
    padding: 0 !important;
}#nex-forms .inner-canvas-container {
    padding-bottom: 15px !important;
}</style>';
	if($theme_settings['0']['theme_shade']=='dark')
		echo '<style type="text/css">html, body, #wpcontent, #wpwrap, #wpbody-content  {background: #444 !important;}</style>';
	echo NEXForms_ui_output(filter_var($_REQUEST['form_Id'],FILTER_SANITIZE_NUMBER_INT),'','');
}
 



function NEXForms_ui_output( $atts , $echo='',$prefill_array='',$unigue_form_Id=''){
	
	if($atts==0)
		{
		wp_enqueue_script('nex-forms-bootstrap.min');
		wp_enqueue_style('nex-forms-bootstrap-ui');	
		wp_enqueue_style('nex-forms-font-awesome-5');
		wp_enqueue_style('nex-forms-font-awesome-4-shims');
		wp_enqueue_style('nex-forms-animations');
		return;
		}
	
	
	$page = isset($_REQUEST['page']) ? sanitize_text_field($_REQUEST['page']) : '';
	if(!$page && is_admin())
		return;
	
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	global $wpdb;
	
	//class objects
	$config 			= new NEXForms5_Config();
	$nf_functions 		= new NEXForms_Functions();
	$database_actions 	= new NEXForms_Database_Actions();
	
	// SCRIPTS AND STYLE ENQUEUES	
	$script_config = get_option('nex-forms-script-config');
	$styles_config = get_option('nex-forms-style-config');
	$other_config = get_option('nex-forms-other-config');
	
	$unigue_form_Id 	= ($unigue_form_Id) ? $unigue_form_Id : rand(0,99999);
	$output 			= '';
	$output 			.= '<style type="text/css">#nex-forms{display:none;}</style>';
	if(is_array($atts))
		{
		$atts = shortcode_atts(array(
			'id' 					=> '0',
			'open_trigger' 			=> '',
			'auto_popup_delay' 		=> '',
			'auto_popup_scroll_top' => '',
			'exit_intent' 			=> '',
			'type' 					=> 'button',
			'element_class' 		=> '',
			'text' 					=> 'open',
			'make_sticky' 			=> 'no',
			'paddel_text' 			=> 'Contact Us',
			'paddel_color'			=>'btn-light-blue',
			'button_color'			=>'btn-light-blue',
			'position' 				=> 'right',
			'field_default_values' 	=> '',
			'form_style' 			=> 'normal',
			'v_position'			=> 'center',
			'h_position'			=> 'center',
			'v_margin'				=> '0',
			'h_margin'				=> '0',
			'width'					=> '50',
			'height'				=> '60',
			'open_animation'		=> 'fadeInDown',
			'close_animation'		=> 'fadeOutUp',
			'overlay_opacity'		=> '0.5',
			'backdrop'				=> 'true',
			'background'			=> '#ffffff',
			'padding_left'			=> '2',
			'padding_right'			=> '2',
			'padding_top'			=> '2',
			'padding_bottom'		=> '2',
			),$atts);
			
		$id						= esc_html(sanitize_text_field(filter_var($atts['id'],FILTER_SANITIZE_NUMBER_INT)));
		$open_trigger 			= esc_html(sanitize_text_field($atts['open_trigger']));
		$auto_popup_delay 		= esc_html(sanitize_text_field($atts['auto_popup_delay']));
		$auto_popup_scroll_top 	= esc_html(sanitize_text_field($atts['auto_popup_scroll_top']));
		$exit_intent 			= esc_html(sanitize_text_field($atts['exit_intent']));
		$type 					= esc_html(sanitize_text_field($atts['type']));
		$element_class 			= esc_html(sanitize_text_field($atts['element_class']));
		$text 					= esc_html(sanitize_text_field($atts['text']));
		$make_sticky 			= esc_html(sanitize_text_field($atts['make_sticky']));
		$paddel_text 			= esc_html(sanitize_text_field($atts['paddel_text']));
		$paddel_color 			= esc_html(sanitize_text_field($atts['paddel_color']));
		$button_color 			= esc_html(sanitize_text_field($atts['button_color']));
		$position 				= esc_html(sanitize_text_field($atts['position']));
		$form_style 			= esc_attr(sanitize_text_field($atts['form_style']));
		$v_position 			= esc_html(sanitize_text_field($atts['v_position']));
		$h_position 			= esc_html(sanitize_text_field($atts['h_position']));
		$v_margin 				= esc_html(sanitize_text_field($atts['v_margin']));
		$h_margin 				= esc_html(sanitize_text_field($atts['h_margin']));
		$width 					= esc_html(sanitize_text_field($atts['width']));
		$height 				= esc_html(sanitize_text_field($atts['height']));
		$open_animation 		= esc_html(sanitize_text_field($atts['open_animation']));
		$close_animation 		= esc_html(sanitize_text_field($atts['close_animation']));
		$overlay_opacity		= esc_html(sanitize_text_field($atts['overlay_opacity']));
		$backdrop				= esc_html(sanitize_text_field($atts['backdrop']));
		$background				= esc_html(sanitize_text_field($atts['background']));
		$padding_left			= esc_html(sanitize_text_field($atts['padding_left']));
		$padding_right			= esc_html(sanitize_text_field($atts['padding_right']));
		$padding_top			= esc_html(sanitize_text_field($atts['padding_top']));
		$padding_bottom			= esc_html(sanitize_text_field($atts['padding_bottom']));
		$field_default_values 	= esc_html(sanitize_text_field($atts['field_default_values']));
				
		extract( shortcode_atts( $defaults, $atts ) );
		wp_parse_args($atts, $defaults);
		}
	else
		{
		$id						= esc_html(sanitize_text_field(filter_var($atts,FILTER_SANITIZE_NUMBER_INT)));
		$open_trigger 			= '';
		$auto_popup_delay 		= '';
		$auto_popup_scroll_top 	= '';
		$exit_intent 			= '';
		$type 					= 'button';
		$element_class 			= '';
		$text 					= 'open';
		$make_sticky 			= 'no';
		$paddel_text 			= 'Contact Us';
		$paddel_color 			= 'btn-light-blue';
		$button_color 			= 'btn-light-blue';
		$position 				= 'right';
		$field_default_values 	= '';
		$form_style 			= 'normal';
		$v_position 			= 'center';
		$h_position 			= 'center';
		$v_margin 				= '0';
		$h_margin 				= '0';
		$width 					= '50';
		$height 				= '80';
		$open_animation 		= 'fadeInDown';
		$close_animation 		= 'fadeOutUp';
		$overlay_opacity		= '0.5';
		$backdrop				= 'true';
		$background				= '#ffffff';
		$padding_left			= '2';
		$padding_right			= '2';
		$padding_top			= '2';
		$padding_bottom			= '2';
		}

	$width 				=  (strstr($width,'px')) ? $width : $width.'%';
	$height 			=  (strstr($height,'px')) ? $height : $height.'%';
	$v_margin 			=  (strstr($v_margin,'px')) ? $v_margin : $v_margin.'%';
	$h_margin 			=  (strstr($h_margin,'px')) ? $h_margin : $h_margin.'%';
	$padding_left 		=  (strstr($padding_left,'px')) ? $padding_left : $padding_left.'%';
	$padding_right 		=  (strstr($padding_right,'px')) ? $padding_right : $padding_right.'%';
	$padding_top		=  (strstr($padding_top,'px')) ? $padding_top : $padding_top.'%';
	$padding_bottom 	=  (strstr($padding_bottom,'px')) ? $padding_bottom : $padding_bottom.'%';
	
	
	
	$form_attr 	= $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',filter_var($id,FILTER_SANITIZE_NUMBER_INT)));

	$default_field_array = explode(',',$field_default_values);
	$set_default_field_array= array();
	
	$post_type = '';
	if(isset($form_attr->post_type))
		$post_type = $form_attr->post_type;
		
	$post_action = '';
	if(isset($form_attr->post_action))
		$post_action = $form_attr->post_action;
		
	$form_theme = '';
	if(isset($form_attr->form_theme))
		$form_theme = $form_attr->form_theme;
		
	$option_settings = '';
	if(isset($form_attr->option_settings))
		$option_settings = json_decode($form_attr->option_settings,true);
	//FORM WRAPPER STYLING
	$form_css_style = '';
	if(isset($form_attr->form_style))
		{
		$form_css_style = str_replace('\\','',$form_attr->form_style);
		$form_css_style = str_replace('"','\'',$form_css_style);
		}
	
	
	$enqueue_array = '';
	if(isset($form_attr->enqueue_array))
		$enqueue_array = json_decode($form_attr->enqueue_array,true);
	
	$inc_raty = true;
	$inc_touchspin = true;
	$inc_datepicker = true;
	$inc_math = true;
	$inc_slider = true;
	$inc_autocomplete = true;
	$inc_sigs = true;
	$inc_tags = true;
	$inc_mask = true;
	
	$set_array = '';
	if(isset($enqueue_array))
		$set_array = $enqueue_array[0];
	//print_r($set_array);
	
	if(isset($set_array['is_inc_raty']))
		{
		if($set_array['is_inc_raty']=='true')
			$inc_raty = true;
		else
			$inc_raty = false;
		}
	if(isset($set_array['is_inc_touchspin']))
		{
		if($set_array['is_inc_touchspin']=='true')
			$inc_touchspin = true;
		else
			$inc_touchspin = false;
		}
	if(isset($set_array['is_inc_datepicker']))
		{
		if($set_array['is_inc_datepicker']=='true')
			$inc_datepicker = true;
		else
			$inc_datepicker = false;
		}
	if(isset($set_array['is_inc_math']))
		{
		if($set_array['is_inc_math']=='true')
			$inc_math = true;
		else
			$inc_math = false;
		}
	if(isset($set_array['is_inc_slider']))
		{
		if($set_array['is_inc_slider']=='true')
			$inc_slider = true;
		else
			$inc_slider = false;
		}
	if(isset($set_array['is_inc_autocomplete']))
		{
		if($set_array['is_inc_autocomplete']=='true')
			$inc_autocomplete = true;
		else
			$inc_autocomplete = false;
		}
	if(isset($set_array['is_inc_sigs']))
		{
		if($set_array['is_inc_sigs']=='true')
			$inc_sigs = true;
		else
			$inc_sigs = false;
		}
	if(isset($set_array['is_inc_tags']))
		{
		if($set_array['is_inc_tags']=='true')
			$inc_tags = true;
		else
			$inc_tags = false;
		}
	if(isset($set_array['is_inc_mask']))
		{
		if($set_array['is_inc_mask']=='true')
			$inc_mask = true;
		else
			$inc_mask = false;
		}
	
	//FORM WRAPPER STYLING
	$msg_css_style = '';
	if(isset($form_attr->msg_style))
		{
		$msg_css_style = str_replace('\\','',$form_attr->msg_style);
		$msg_css_style = str_replace('"','\'',$msg_css_style);
		}
	
	foreach ($default_field_array as $default_field_item)
		{
		if(strstr($default_field_item,'='))
			{
			$get_field = explode('=',$default_field_item);
			$key = $get_field[0];
			if($get_field[1])
				$val = $get_field[1];
			$set_default_field_array[$key]=$val;
			}
		}

	//ADD CONDITIONAL LOGIC SCRIPT
	if($nf_functions->isJson($form_attr->conditional_logic_array))
		{
		$cl_script = $nf_functions->run_conditional_logic(json_decode($form_attr->conditional_logic_array), $unigue_form_Id);	
		wp_add_inline_script('nex-forms-onload', $cl_script);
		}
	$custom_user_func_before_submit_js = 'function nf_custom_user_func_before_submit(formData, jqForm, options){';
	
	if(isset($option_settings[0]['before_submit_js'])){
		if($option_settings[0]['before_submit_js'] && $option_settings[0]['before_submit_js']!='')
			{
			$custom_user_func_before_submit_js .= str_replace('\\','',$option_settings[0]['before_submit_js']);	
			}
		else
			$custom_user_func_before_submit_js .= 'return true;';
	}
	
	$custom_user_func_before_submit_js .= '}';	
	
	wp_add_inline_script('nex-forms-onload', $custom_user_func_before_submit_js);
	
	
	$custom_user_func_after_submit_js = 'function nf_custom_user_func_after_submit(formData, jqForm, options, $form){';
	if(isset($option_settings[0]['after_submit_js'])){
		if($option_settings[0]['after_submit_js'] && $option_settings[0]['after_submit_js']!='')
			{
			$custom_user_func_after_submit_js .= str_replace('\\','',$option_settings[0]['after_submit_js']);	
			}
		else
			$custom_user_func_after_submit_js .= 'return true;';
	}
	
	$custom_user_func_after_submit_js .= '}';	
	
	wp_add_inline_script('nex-forms-onload', $custom_user_func_after_submit_js); 

	//OPEN STICKY FORM PADDLES	(IF FORM TYPE IS STICKY)
	$output .= '<div id="nf_form_'.$unigue_form_Id.'">';
	if($make_sticky=='yes')
		{
		$output .= '<div id="nex-forms"><div class="nf-sticky-contact-form paddel-'.esc_html($position).'" style="display:none;"><div class="nf-sticky-paddel waves-effect waves-light btn '.esc_html($paddel_color).'">'.esc_html($paddel_text).'</div><div class="nf-sticky-container">';	
		}
	
	//PRINT AUTO POPUP TRIGGERS AND HTML  (IF FORM TYPE IS POPUP)
	$popup_scripts = '';
	//PRINT AUTO POPUP TRIGGERS AND HTML  (IF FORM TYPE IS POPUP)
	if($open_trigger=="popup")
		{
		//AUTO POPUP - Exit Intent
		if($exit_intent==1)
			{
			$popup_scripts .= '
				// Exit intent
				function addEvent(obj, evt, fn) {
					if (obj.addEventListener) {
						obj.addEventListener(evt, fn, false);
					}
					else if (obj.attachEvent) {
						obj.attachEvent("on" + evt, fn);
					}
				}
				// Exit intent trigger
				var exit_popup = 0;
				addEvent(document, \'mouseout\', function(evt) {
					if (evt.toElement == null && evt.relatedTarget == null && exit_popup==0) {
						//run_parent_css_reset('.$atts['id'].');
					   jQuery(\'#nexForms_popup_'.$atts['id'].'\').modal(\'open\');
						exit_popup = 1;
					};
				
				});
			';
			}	
		//AUTO POPUP - Scroll Position
		else if($auto_popup_scroll_top!='')
			{
			$popup_scripts .= '
				var scroll_popup = 0;
				jQuery(document).on(\'scroll\',
					function()
						{
						if(jQuery(window).scrollTop()>'.$auto_popup_scroll_top.' && scroll_popup==0)
							{
							//run_parent_css_reset('.$atts['id'].');
							jQuery(\'#nexForms_popup_'.$atts['id'].'\').modal(\'open\');
							scroll_popup = 1;
							}
						}
					)
			';
			}
		//AUTO POPUP - Delay
		else if($auto_popup_delay!='')
			{
			$popup_scripts .= '
				 setTimeout( function()
						{
						//run_parent_css_reset('.$atts['id'].');
						jQuery(\'#nexForms_popup_'.$atts['id'].'\').modal(\'open\');
						},'.($auto_popup_delay*1000).'
					);
			';
			}
		else
			{
			//POPUP - Custom trigger (from user element class)
			if($type == 'custom_trigger')
				{
				$popup_scripts .= '
					jQuery(document).ready(
						function()
							{
							jQuery(document).on(\'click\', ".'.esc_html($element_class).'",
								function(e)
									{
									e.preventDefault();
									run_parent_css_reset('.$atts['id'].');
									jQuery(\'#nexForms_popup_'.$atts['id'].$element_class.'\').modal(\'open\');
									}
								);
							}
						);
				';	
				}				
			}
		
		
		
		wp_add_inline_script('nex-forms-onload', $popup_scripts);
			
		//MAIN CONTAINER #NEX-FORMS (IF FORM TYPE IS POPUP)
		$output .= '<div id="nex-forms" class="nex-forms '.$nf_functions->format_name($form_attr->title).' ">';
		
			//PRINT PRE-FILL FIELDS ARRAYS 
			$output .= '<div class="pre_fill_fields">';
				if(is_array($prefill_array))
					{
					foreach($prefill_array as $key => $val)
						{	
						if(get_option('nf_activated'))				
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}	
					}
				foreach($_REQUEST as $key => $val)
						{
						if(get_option('nf_activated'))					
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}
				foreach($set_default_field_array as $key => $val)
						{
						if(get_option('nf_activated'))					
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}
			$output .= '</div>';
		
			//PRINT POPUP BUTTON OR LINK 
			if($type == 'button')
				$output .= '<button class="btn mb-btn waves-effect waves-light '.esc_html($button_color).' open_nex_forms_popup" data-ufid="'.esc_attr($unigue_form_Id).'" data-default-values="'.esc_attr($field_default_values).'" data-popup-id="'.esc_attr($atts['id']).'">'.esc_html($text).'</button>';
			else if($type == 'link')
				$output .= '<a href="#" class="open_nex_forms_popup" data-ufid="'.$unigue_form_Id.'" data-default-values="'.$field_default_values.'" data-popup-id="'.$atts['id'].'">'.esc_html($text).'</a>';
			else
				$output .= '';
			
			
			 
			if(NEXForms_isMobile())
				{
				$width = '100%';
				}
				
		//OPEN POPUP MODAL
				$output .= '<div  data-backdrop="'.esc_attr($backdrop).'" data-open-animation="'.esc_attr($open_animation).'" data-close-animation="'.esc_attr($close_animation).'" data-overlay-opacity="'.esc_attr($overlay_opacity).'" class="modal '.((!get_option('nf_activated')) ? 'do_nf_popup' : '' ).' fade nex_forms_modal animated '.(($background=='transparent') ? 'no_shadow' : '').' '.esc_attr($open_animation).' v_'.esc_attr($v_position).' h_'.esc_attr($h_position).'" style="'.(($background=='use-form-background') ? esc_attr($form_css_style) : 'background:'.esc_attr($background).';padding-left:'.esc_attr($padding_left).' !important; padding-right:'.esc_attr($padding_right).' !important; padding-top:'.esc_attr($padding_top).' !important; padding-bottom:'.esc_attr($padding_bottom).' !important;').'width:'.esc_attr($width).' !important;height:'.esc_attr($height).' !important; margin: '.esc_attr($v_margin).' '.esc_attr($h_margin).'; " id="nexForms_popup_'.esc_attr($atts['id']).esc_attr($element_class).'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">';
					$output .= '<div class="modal-header ">';
					$output .= '<h4 class="modal-title">'.$form_attr->title.'</h4>';
					$output .= '<div style="clear:both;"></div>';
				$output .= '</div>';
				$output .= '<span class="modal-action modal-close '.esc_attr($button_color).'"><i class="material-icons fa fa-close"></i></span>';
				$output .= '<div class="modal-content" id="nf_form_'.esc_attr($unigue_form_Id).'">';	
		}
		
		
		

		if(isset($form_attr->md_theme))
			$theme_settings = json_decode($form_attr->md_theme,true);
		
			$set_theme 			= 'default';
			$set_theme_shade 	= 'light';
			
			$msg_hide_form		= 'yes';
			$msg_position		= 'top';
			$msg_placement		= 'outside';
			$loader_type		= 'ellipsis';
			
			
			$loader_color		= '#40C4FF';
			$form_title 		= 'untitlted';
			
			if(isset($theme_settings['0']['theme_name']))
				$set_theme 			= ($theme_settings['0']['theme_name']!='') 	? $theme_settings['0']['theme_name'] 	: 'default';
			if(isset($theme_settings['0']['theme_shade']))
				$set_theme_shade 	= ($theme_settings['0']['theme_shade']!='') ? $theme_settings['0']['theme_shade'] 	: 'light';
			if(isset($theme_settings['0']['msg_hide_form']))
				$msg_hide_form		= ($theme_settings['0']['msg_hide_form']) ? $theme_settings['0']['msg_hide_form'] 	: 'yes';
			if(isset($theme_settings['0']['msg_position']))
				$msg_position		= ($theme_settings['0']['msg_position']) ? $theme_settings['0']['msg_position'] 	: 'top';
			if(isset($theme_settings['0']['msg_placement']))
				$msg_placement		= ($theme_settings['0']['msg_placement']) ? $theme_settings['0']['msg_placement'] 	: 'outside';
			if(isset($theme_settings['0']['loader_type']))
				$loader_type		= ($theme_settings['0']['loader_type']) ? $theme_settings['0']['loader_type'] 		: 'ellipsis';
			if(isset($theme_settings['0']['loader_color']))
				$loader_color		= ($theme_settings['0']['loader_color']) ? $theme_settings['0']['loader_color'] 	: '#40C4FF';
			
		
		if(isset($form_attr->title))
			{
			$form_title  = $nf_functions->format_name($form_attr->title);
			$output .= '
			
			<style type="text/css">
			
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-spinner div:after {background: '.$loader_color.'; }
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-grid div {background: '.$loader_color.'; }
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-ellipsis div { background: '.$loader_color.';}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-default div {background: '.$loader_color.';}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-roller div:after {background: '.$loader_color.';}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-heart div,
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-heart div:after,
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-heart div:before {background: '.$loader_color.';}
	
				
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-facebook div {background: '.$loader_color.';}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-circle > div {background: '.$loader_color.';}
				
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-dual-ring:after {border: 6px solid '.$loader_color.';border-color: '.$loader_color.' transparent '.$loader_color.' transparent;}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-ring div {border: 8px solid '.$loader_color.';border-color: '.$loader_color.' transparent transparent transparent;}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-hourglass:after {border: 32px solid '.$loader_color.';border-color: '.$loader_color.' transparent '.$loader_color.' transparent;}
				#nex-forms.'.$nf_functions->format_name($form_attr->title).' .nf-loader-lds-ripple div {border: 4px solid '.$loader_color.';}
			</style>
			
			
			';
			}
		

		//MAIN CONTAINER #NEX-FORMS
		$output .= '<div id="nex-forms" data-loader="'.$loader_type.'" data-msg-hide-form="'.$msg_hide_form.'" data-msg-position="'.$msg_position.'" data-msg-placement="'.$msg_placement.'"  class="nex-forms '.$nf_functions->format_name($form_attr->title).' '.(($make_sticky=='yes' && !get_option('nf_activated')) ? 'do_nf_sticky' : '' ).' '.(($make_sticky=='yes') ? 'is-nf-sticky-form' : '').'">';
			
			
			//PRINT PRE-FILL FIELDS ARRAYS 
			$output .= '<div class="pre_fill_fields">';
				if(is_array($prefill_array))
					{
					foreach($prefill_array as $key => $val)
						{
						if(get_option('nf_activated'))		
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}	
					}
				foreach($_REQUEST as $key => $val)
						{	
						if(get_option('nf_activated'))				
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}
				
				foreach($set_default_field_array as $key => $val)
						{
						if(get_option('nf_activated'))					
						$output .= '<input type="hidden" name="'.$key.'"  data-original-value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'" value="'.str_replace('\\','',filter_var($val,FILTER_SANITIZE_STRING)).'">';	
						}
				
			$output .= '</div>';
			
			
			if($form_attr->form_theme=='bootstrap' || $form_attr->form_theme=='jquery_ui')
				$set_theme = $form_attr->jq_theme;
			
			
			//SET THEME
			$output .= '<div class="set_form_theme theme-'.$set_theme.'">';
				//AJAX ON-SCREEN SUCCESS MESSAGE
				
				$on_screen_confirmation_message = '';
				if(isset($form_attr->on_screen_confirmation_message)){
				$on_screen_confirmation_message = $form_attr->on_screen_confirmation_message;	
				}
				
				
				$on_screen_confirmation_message_admin = '';
				if(isset($form_attr->on_screen_confirmation_message)){
				$on_screen_confirmation_message_admin = $form_attr->on_screen_confirmation_message_admin;	
				}
				$set_confirmation_messsage = $on_screen_confirmation_message;
				if(class_exists('NEXForms_Shortcode_Processor'))
							{
							$shorcode_processor = new NEXForms_Shortcode_Processor();
							$set_confirmation_messsage = $shorcode_processor->process_shortcodes($on_screen_confirmation_message);
							}	
				
					$osmsgv2 = '';
						$osmsgv2 .= '<div class="nex_success_message msg_'.$msg_position.' msgv2 animated hidden" style="display:none;">';
							$osmsgv2 .= '<div class="nex_success_message_container" style="'.$msg_css_style.'">';
								$osmsgv2 .= '<div class="msg_text">'.wp_unslash($set_confirmation_messsage).'</div>';
							$osmsgv2 .= '</div>';
						$osmsgv2 .= '</div>';
						
						if($on_screen_confirmation_message_admin)
							{
							if($msg_position=='top' && $msg_placement=='outside')
								$output .= $osmsgv2;
							}
						else
							{
							$output .= '<div class="nex_success_message oldv animated" style="display:none;">';
								$output .= '<div class="success_header" >';
									$output .= '<div class="success_icon animated" style="display:none;"><span class="fa fa-check"></span></div>';
								$output .= '</div>';
								
								$output .= '<div class="msg_box">';
									$output .= '<div class="msg_text" style="display:none;">'.str_replace('\\','',wpautop($set_confirmation_messsage)).'</div>';
								$output .= '</div>';	
							$output .= '</div>';
							}
					
					/*}*/
				//$output .= '<h2>#'.$form_attr->Id.' - '.$form_attr->title.'</h2>';
				
				$set_multistep_settings = '';
				if(isset($form_attr->multistep_settings)){
				$set_multistep_settings = $form_attr->multistep_settings;	
				}
				
				$multistep_settings = json_decode($set_multistep_settings,true);
				
				$add_timer				= 'no';
				$timer_type				= 'overall';
					
				$timer_start			= '1';
				$timer_end				= '0';
				
					if(isset($multistep_settings['0']['add_timer']))
						$add_timer				= ($multistep_settings['0']['add_timer']) ? $multistep_settings['0']['add_timer'] 					: 'no';
					if(isset($multistep_settings['0']['timer_type']))
						$timer_type				= ($multistep_settings['0']['timer_type']) ? $multistep_settings['0']['timer_type'] 				: 'overall';
					if(isset($multistep_settings['0']['timer_start']))
						$timer_start			= ($multistep_settings['0']['timer_start']) ? $multistep_settings['0']['timer_start'] 	: '1';
					if(isset($multistep_settings['0']['timer_end']))
						$timer_end				= ($multistep_settings['0']['timer_end']) ? $multistep_settings['0']['timer_end'] 	: '0';
				
				//#ui-nex-forms-container start
				$output .= '<div data-timer-start="'.$timer_start.'" data-timer-end="'.$timer_end.'" class="inner-canvas-container ui-nex-forms-container ui-nex-forms-container-fe '.(($add_timer=='yes') ? 'has_time_limit timer_'.$timer_type : '' ).' '.(($form_attr->form_theme) ? $form_attr->form_theme : 'bootstrap').'" id="ui-nex-forms-container-'.$form_attr->Id.'"  >';
					
					$output .= '<div class="current_step" style="display:none;">1</div>';
					$output .= '<div class="last_visited_step" style="display:none;">1</div>';
		
					global $wp;
					
					$set_post_action = admin_url('admin-ajax.php');
					
					$current_url 		= home_url( add_query_arg( array(), $wp->request ) );
					$set_post_action 		= ($post_action=='ajax' || !$post_action) ? admin_url('admin-ajax.php') : $form_attr->custom_url;
					$set_ajax	 			= ($post_action=='ajax' || !$post_action) ? 'submit-nex-form' : 'send-nex-form';
					$post_method 		= 'post';
					
					if($post_action!='ajax')
						$post_method 	= ($post_type=='POST' || !$post_type) ? 'post' : 'get';
						
					
					//PRINT MULTI-STEP BREADCRUMB (If outside Form Wrapper and position is top)
					
					$bc_type 				= 'basix';
					$bc_text_pos 			= 'text-bottom';	
					$bc_data_theme 			= 'default';
					$bc_show_front_end 		= 'yes';	
					$bc_show_inside 		= 'no';	
					$scroll_to_top 			= 'yes';
					$form_width_percentage 	= '100';
					$form_width_pixels	 	= '950';
					$form_width_unit	 	= '%';
					$align_crumb	 		= 'align_center';
					$bc_list	 			= '';
					$bc_position	 		= 'top';
					$bc_gutter	 			= '20';
					$bc_folded 				= 'bc-unfolded';
					$bc_connected 			= 'bc-connected';
					$bc_style 				= 'bc-solid';
					$bc_css 				= '';
					$show_front_end  = '';
					$multi_step_back_disabled = '';
					$multi_step_total = 0;
					$multi_step_transition_in = 'fadeIn';
					$multi_step_transition_out = 'fadeOut';
					
					if(isset($multistep_settings['0']['multi_step_total']))
						$multi_step_total = $multistep_settings['0']['multi_step_total'];
					
					if(isset($multistep_settings['0']['add_timer']))
						$bc_type 				= ($multistep_settings['0']['breadcrumb_type']) ? $multistep_settings['0']['breadcrumb_type'] 				: 'basix';
					if(isset($multistep_settings['0']['text_pos']))
						$bc_text_pos 			= ($multistep_settings['0']['text_pos']) ? $multistep_settings['0']['text_pos'] 							: 'text-bottom';	
					if(isset($multistep_settings['0']['data_theme']))
						$bc_data_theme 			= ($multistep_settings['0']['data_theme']) ? $multistep_settings['0']['data_theme'] 						: 'default';
					if(isset($multistep_settings['0']['show_front_end']))
						$bc_show_front_end 		= ($multistep_settings['0']['show_front_end']) ? $multistep_settings['0']['show_front_end'] 				: 'yes';	
					if(isset($multistep_settings['0']['show_inside']))
						$bc_show_inside 		= ($multistep_settings['0']['show_inside']) ? $multistep_settings['0']['show_inside'] 						: 'no';	
					if(isset($multistep_settings['0']['scroll_to_top']))
						$scroll_to_top 			= ($multistep_settings['0']['scroll_to_top']) ? $multistep_settings['0']['scroll_to_top'] 					: 'yes';
					if(isset($multistep_settings['0']['form_width_percentage']))
						$form_width_percentage 	= ($multistep_settings['0']['form_width_percentage']) ? $multistep_settings['0']['form_width_percentage'] 	: '100';
					if(isset($multistep_settings['0']['form_width_pixels']))
						$form_width_pixels	 	= ($multistep_settings['0']['form_width_pixels']) ? $multistep_settings['0']['form_width_pixels'] 			: '950';
					if(isset($multistep_settings['0']['form_width_unit']))
						$form_width_unit	 	= ($multistep_settings['0']['form_width_unit']) ? $multistep_settings['0']['form_width_unit'] 				: '%';
					if(isset($multistep_settings['0']['crumb_align']))
						$align_crumb	 		= ($multistep_settings['0']['crumb_align']) ? $multistep_settings['0']['crumb_align'] 						: 'align_center';
					if(isset($multistep_settings['0']['breadcrumb_list']))
						$bc_list	 			= ($multistep_settings['0']['breadcrumb_list']) ? $multistep_settings['0']['breadcrumb_list'] 				: '';
					if(isset($multistep_settings['0']['bc_position']))
						$bc_position	 		= ($multistep_settings['0']['bc_position']) ? $multistep_settings['0']['bc_position'] 						: 'top';
					if(isset($multistep_settings['0']['bc_gutter']))
						$bc_gutter	 			= ($multistep_settings['0']['bc_gutter'] || $multistep_settings['0']['bc_gutter']=='0') ? $multistep_settings['0']['bc_gutter'] 							: '20';
					if(isset($multistep_settings['0']['bc_folded']))
						$bc_folded 				= ($multistep_settings['0']['bc_folded']) ? $multistep_settings['0']['bc_folded'] 							: 'bc-unfolded';
					if(isset($multistep_settings['0']['bc_connected']))
						$bc_connected 			= ($multistep_settings['0']['bc_connected']) ? $multistep_settings['0']['bc_connected'] 					: 'bc-connected';
					if(isset($multistep_settings['0']['bc_style']))
						$bc_style 				= ($multistep_settings['0']['bc_style']) ? $multistep_settings['0']['bc_style'] 							: 'bc-solid';
					if(isset($multistep_settings['0']['bc_css']))
						$bc_css 				= ($multistep_settings['0']['bc_css']) ? str_replace('\\','',$multistep_settings['0']['bc_css'] )			: '';
					
					$set_bc_css = str_replace('\\','',$bc_css);
					$set_bc_css = str_replace('"','\'',$set_bc_css);
					
					if(isset($multistep_settings['0']['multi_step_transition_in']))
						$multi_step_transition_in		= ($multistep_settings['0']['multi_step_transition_in']) ? $multistep_settings['0']['multi_step_transition_in'] 	: 'fadeIn';
					if(isset($multistep_settings['0']['multi_step_transition_out']))
						$multi_step_transition_out		= ($multistep_settings['0']['multi_step_transition_out']) ? $multistep_settings['0']['multi_step_transition_out'] 	: 'fadeOut';
					if(isset($multistep_settings['0']['multi_step_back_disabled']))
						$multi_step_back_disabled		= ($multistep_settings['0']['multi_step_back_disabled']) ? $multistep_settings['0']['multi_step_back_disabled'] 	: 'no';
					
					if(isset($multistep_settings['0']['timer_add_to']))
						$timer_add_to			= ($multistep_settings['0']['timer_add_to']) ? $multistep_settings['0']['timer_add_to'] 			: 'header';
					if(isset($multistep_settings['0']['enabled_units']))
						$enabled_units			= ($multistep_settings['0']['enabled_units']) ? $multistep_settings['0']['enabled_units'] 			: 'minutes,seconds';
					if(isset($multistep_settings['0']['timer_size']))
						$timer_size				= ($multistep_settings['0']['timer_size']) ? $multistep_settings['0']['timer_size'] 				: 'small';
					if(isset($multistep_settings['0']['timer_animation']))
						$timer_animation		= ($multistep_settings['0']['timer_animation']) ? $multistep_settings['0']['timer_animation'] 		: 'smooth';
					if(isset($multistep_settings['0']['timer_hours']))
						$timer_hours			= ($multistep_settings['0']['timer_hours']) ? $multistep_settings['0']['timer_hours'] 				: 0;
					if(isset($multistep_settings['0']['timer_minutes']))
						$timer_minutes			= ($multistep_settings['0']['timer_minutes']) ? $multistep_settings['0']['timer_minutes'] 			: 0;
					if(isset($multistep_settings['0']['timer_seconds']))
						$timer_seconds			= ($multistep_settings['0']['timer_seconds']) ? $multistep_settings['0']['timer_seconds'] 			: 30;
					if(isset($multistep_settings['0']['timer_hours_label']))
						$timer_hours_label		= ($multistep_settings['0']['timer_hours_label']) ? $multistep_settings['0']['timer_hours_label'] 		: '';
					if(isset($multistep_settings['0']['timer_minutes_label']))
						$timer_minutes_label	= ($multistep_settings['0']['timer_minutes_label']) ? $multistep_settings['0']['timer_minutes_label'] 	: '';
					if(isset($multistep_settings['0']['timer_seconds_label']))
						$timer_seconds_label	= ($multistep_settings['0']['timer_seconds_label']) ? $multistep_settings['0']['timer_seconds_label'] 	: '';
					if(isset($multistep_settings['0']['timer_hours_color']))
						$timer_hours_color			= ($multistep_settings['0']['timer_hours_color']) ? $multistep_settings['0']['timer_hours_color'] 				: '#2979FF';
					if(isset($multistep_settings['0']['timer_minutes_color']))
						$timer_minutes_color		= ($multistep_settings['0']['timer_minutes_color']) ? $multistep_settings['0']['timer_minutes_color'] 			: '#00bcd4';
					if(isset($multistep_settings['0']['timer_seconds_color']))
						$timer_seconds_color		= ($multistep_settings['0']['timer_seconds_color']) ? $multistep_settings['0']['timer_seconds_color'] 			: '#40C4FF';
					if(isset($multistep_settings['0']['timer_direction']))
						$timer_direction		= ($multistep_settings['0']['timer_direction']) ? $multistep_settings['0']['timer_direction'] 		: 'clockwise';
					if(isset($multistep_settings['0']['timer_wrapper_css']))
						$timer_wrapper_css		= ($multistep_settings['0']['timer_wrapper_css']) ? $multistep_settings['0']['timer_wrapper_css'] 		: '';
					if(isset($multistep_settings['0']['timer_text_color']))
						$timer_text_color				= ($multistep_settings['0']['timer_text_color']) ? $multistep_settings['0']['timer_text_color'] 						: '#888';
					if(isset($multistep_settings['0']['timer_inner_circle_color']))
						$timer_inner_circle_color		= ($multistep_settings['0']['timer_inner_circle_color']) ? $multistep_settings['0']['timer_inner_circle_color'] 		: '#aaa';
					
					
					
						$ms_back_disabled_class = ($multi_step_back_disabled=='yes' || $timer_type=='per_step') ? 'ms_disable_back' : '';
					
					if($bc_type =='dotted' || $bc_type =='dotted_count')
							$bc_type = 'pilled';
					
					if(isset($multistep_settings['0']['timer_position']))
						$timer_position			= ($multistep_settings['0']['timer_position']) ? $multistep_settings['0']['timer_position'] 	: 'timer_inline';
					if(isset($multistep_settings['0']['timer_align']))
						$timer_align			= ($multistep_settings['0']['timer_align']) ? $multistep_settings['0']['timer_align'] 			: 'timer_right';
					
					if(isset($multistep_settings['0']['timer_bg_width']))
						$timer_bg_width			= ($multistep_settings['0']['timer_bg_width']) ? $multistep_settings['0']['timer_bg_width'] 	: '0.1';
					if(isset($multistep_settings['0']['timer_fg_width']))
						$timer_fg_width			= ($multistep_settings['0']['timer_fg_width']) ? $multistep_settings['0']['timer_fg_width'] 	: '0.05';
					
					
					$multistep_html = '';
					if(isset($form_attr->multistep_html))
						$multistep_html = $form_attr->multistep_html;
					
					if(!isset($multistep_settings[0]['bc_converted']) && $bc_show_inside=='no' && $show_front_end=='yes' && ($bc_position=='top' || !isset($multistep_settings[0]['bc_position'])) )
						$output .= '<div class="nf_step_breadcrumb">'.str_replace('\\','',$multistep_html).'</div>';
					
					
					
					if(isset($multi_step_total))
						{
						if(isset($multistep_settings[0]['bc_converted']))
							{
							$output .= '<div class="nex-forms-header-footer nex-forms-header '.$timer_position.' '.$timer_align.' '.((($bc_show_front_end=='yes' && $bc_position=='top') || $add_timer=='yes' && $timer_add_to=='header') ? '' : 'no-front-end').'">';
							if($bc_show_front_end=='yes' && !strstr($form_attr->multistep_html,'tttttttt'))
								{
							//$output .= '<div>################'.$bc_show_front_end.$form_attr->multistep_html.'</div>';
							$output .= '<div class="bc-outer-container '.(($bc_show_front_end=='yes' && $bc_position=='top') ? '' : 'no-front-end').'"><div style="'.$set_bc_css .'" class="nf_ms_breadcrumb '.$ms_back_disabled_class.' bc-gutter-'.$bc_gutter.' bc-'.$bc_position.' '.$bc_style.' '.$bc_connected.' '.$bc_folded.' '.$bc_type.' ">'.str_replace('\\','',$form_attr->multistep_html).'</div></div>';
								}
							}
						}
					
					//TIMER
					if($add_timer=='yes' && $timer_add_to=='header')
						{
						$output .='
						<div class="timer-outer-container">
							<div class="timer-inner-container" style="'.$timer_wrapper_css.'">
								<div class="nf-timer '.$timer_size.'" data-timer-bg-width="'.$timer_bg_width.'" data-timer-fg-width="'.$timer_fg_width.'" data-timer-text-color="'.$timer_text_color.'" data-timer-inner-circle-color="'.$timer_inner_circle_color.'" data-timer-animation="'.$timer_animation.'" data-timer="30" data-timer-direction="'.$timer_direction.'" data-enabled-units="'.$enabled_units.'"  data-timer-hours="'.$timer_hours.'" data-timer-minutes="'.$timer_minutes.'" data-timer-seconds="'.$timer_seconds.'" data-timer-hours-label="'.$timer_hours_label.'" data-timer-minutes-label="'.$timer_minutes_label.'" data-timer-seconds-label="'.$timer_seconds_label.'" data-timer-hours-color="'.$timer_hours_color.'" data-timer-minutes-color="'.$timer_minutes_color.'" data-timer-seconds-color="'.$timer_seconds_color.'"></div>
							</div>
						</div>';
						}
					if(isset($multi_step_total) && isset($multistep_settings[0]['bc_converted']))
						{
						$output .= '</div>';
						}
					
					
					$set_confirmation_page = '';
					if(isset($form_attr->confirmation_page))
					$set_confirmation_page = $form_attr->confirmation_page;
					
					if(class_exists('NEXForms_Shortcode_Processor'))
						{
						$shorcode_processor = new NEXForms_Shortcode_Processor();
						$set_confirmation_page = $shorcode_processor->process_shortcodes($set_confirmation_page);
						}
					//hidden elements used by JS
					$output .= '<div id="the_plugin_url" style="display:none;">'.plugins_url('',__FILE__).'</div>';
					if(isset($option_settings[0]['save_form_progress']))
						$output .= '<div id="nf_save_form_progress" style="display:none;">'.$option_settings[0]['save_form_progress'].'</div>';
					if(get_option('nf_activated')){
						$output .= '<div id="confirmation_page" class="confirmation_page" style="display:none;">'.$set_confirmation_page.'</div>';
					if(isset($form_attr->on_form_submission))
						$output .= '<div id="on_form_submmision" class="on_form_submmision" style="display:none;">'.$form_attr->on_form_submission.'</div>';}
					
					$output .= '<div class="hidden" id="nf_ajax_url" style="display:none;">'.admin_url('admin-ajax.php').'</div>';
					$output .= '<div class="hidden" id="paypal_return_url" style="display:none;">'.$current_url.'</div>';
					
					if(isset($multistep_settings[0]['scroll_to_top']))
						$output .= '<div class="hidden" id="ms_scroll_to_top" style="display:none;">'.$multistep_settings[0]['scroll_to_top'].'</div>';
					
					if(!get_option('nf_activated')){ $post_action = admin_url('admin-ajax.php');$post_method = 'post';}
					//PAYPAL SUCCESS / FAILURE MESSAGE (If paypal ios enabled)
					
					
					$is_paypal = '';
					if(isset($form_attr->is_paypal)){
						$is_paypal = $form_attr->is_paypal;	
					}
					
					
					$payment_success_msg = '';
					if(isset($form_attr->payment_success_msg)){
						$payment_success_msg = $form_attr->payment_success_msg;	
					}
					
					$payment_failed_msg = '';
					if(isset($form_attr->payment_failed_msg)){
						$payment_failed_msg = $form_attr->payment_failed_msg;	
					}
					if($is_paypal=='yes' && function_exists('nf_not_found_notice_ppp'))
						{
						$paypal_transaction = isset($_REQUEST['nf-paypal-transaction']) ? sanitize_text_field($_REQUEST['nf-paypal-transaction']) : '';
						$paypal_token = isset($_REQUEST['token']) ? sanitize_text_field($_REQUEST['token']) : '';
						if($paypal_transaction==1)
							{
							$payment_details = nf_get_paypal_payment($paypal_token);
							$output .= '<div class="hidden" id="resend_email_nex_forms_Id" value="">'.$payment_details['nex_forms_Id'].'</div>';
							$output .= '<div class="hidden" id="resend_email_entry_Id" value="">'.$payment_details['entry_Id'].'</div>';
							
							if($payment_details['payment_verified'])
								{
								$output .= '<div class="nex_success_message animated">';
									$output .= '<div class="success_header">';
										$output .= '<div class="success_icon animated"><span class="fa fa-check"></span></div>';
									$output .= '</div>';
									$output .= '<div class="msg_box">';
										$output .= '<div class="msg_text" >'.str_replace('\\','',$payment_success_msg).'</div>';
									$output .= '</div>';
								$output .= '</div>';
								}
							else
								$output .= '<div class="panel-body alert alert-danger">'.str_replace('\\','',$payment_failed_msg).'</div>';
							}
						}
						
						
						
						//SETUP SUBMISSION LIMIT
						$submit_limit = false;
						if(isset($option_settings[0]))
							{
							if($option_settings[0]['submit_limit']>0)
								{
									$get_count 	= $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE nex_forms_Id = '.$id);
									if($get_count==$option_settings[0]['submit_limit'])
										{
										if($option_settings[0]['submit_limit_msg'])
											$output .= '<div class="alert alert-info"><p>'.$option_settings[0]['submit_limit_msg'].'</p></div>';
											
										$submit_limit = true;
										}
								}
							}
						
						//FORM WRAPPER CONTAINER
						//if(isset($multistep_settings[0]))
						$output .= '<div class="v7_container form_type_'.$form_style.' '.$ms_back_disabled_class.' '.(($submit_limit) ? 'hidden' : '').' " style="'.(($background != 'use-form-background') ? $form_css_style : '').'">';
						
						if($on_screen_confirmation_message_admin)
							{
							if($msg_position=='top' && $msg_placement=='inside')
								$output .= $osmsgv2;
							}
						
						
						$output .= '<div class="current_field_on_focus" style="display:none">1</div>';
							//PRINT MULTI-STEP BREADCRUMB (If inside Form Wrapper and position is top)
							if(!$multi_step_total && $bc_show_inside=='yes' && $bc_show_front_end=='yes' && ($bc_position=='top' || !isset($multistep_settings[0]['bc_position'])))
								$output .= '<div class="nf_step_breadcrumb">'.str_replace('\\','',$form_attr->multistep_html).'</div>';
							
							//<FORM> Start
							$output .= 	'<form id="'.$id.'" class="'.$set_ajax.' nex-forms-'.$id.'" data-form-id="'.$id.'" name="nex_form" action="'.$set_post_action.'" method="'.$post_method.'" enctype="multipart/form-data">';
								//Step Transitions
								$output .= '<div class="step_transition_in" style="display:none;">'.$multi_step_transition_in.'</div>';
								$output .= '<div class="step_transition_out" style="display:none;">'.$multi_step_transition_out.'</div>';
								
								//hidden fields
								if(function_exists('nf_not_found_notice_pp'))
									$output .= '<input type="hidden" name="paypal_invoice" value="'.rand(0,9999999).'">';
									
								$output .= '<input type="hidden" name="nex_forms_Id" value="'.$id.'">';
								if($post_action=='ajax')
									$output .= '<input type="hidden" name="page" value="'.sanitize_text_field($_SERVER['REQUEST_URI']).'">'; 
									
								$output .= '<input type="hidden" name="ip" value="'.sanitize_text_field($_SERVER['REMOTE_ADDR']).'">';
								$output .= '<input type="hidden" name="nf_page_id" value="'.get_the_ID().'">';
								$output .= '<input type="hidden" name="nf_page_title" value="'.get_the_title().'">';
								
								$tz = wp_timezone();
								//$set_date = new DateTime("now", $tz);
								
								$_SERVER['DATE_TIME'] 			= wp_date(get_option('date_format').' '.get_option('time_format'),time(), $tz); //$set_date->format(get_option('date_format').' '.get_option('time_format'));
								$_SERVER['DATE'] 				= wp_date(get_option('date_format'),time(), $tz); //$set_date->format(get_option('date_format'));
								$_SERVER['TIME'] 				= wp_date(get_option('time_format'),time(), $tz); //$set_date->format(get_option('time_format'));
								
								$_SERVER['DATE_DAY'] 			= wp_date('d',time(), $tz); //$set_date->format('d');
								$_SERVER['DATE_MONTH'] 			= wp_date('m',time(), $tz); //$set_date->format('m');
								$_SERVER['DATE_YEAR'] 			= wp_date('Y',time(), $tz); //$set_date->format('Y');
								
								$_SERVER['PAGE_TITLE'] 			= get_the_title();
								$_SERVER['PAGE_ID'] 			= get_the_ID();
								$_SERVER['FORM_TITLE'] 			= $form_attr->title;
								$_SERVER['C_PAGE'] 				= sanitize_text_field($_SERVER['REQUEST_URI']);
								$_SERVER['PAGE_URL'] 			= sanitize_text_field($_SERVER['REQUEST_URI']);
								$_SERVER['WP_USER_IP']			= sanitize_text_field($_SERVER['REMOTE_ADDR']);
								$_SERVER['WP_USER_ID'] 			= get_current_user_id();
								$_SERVER['WP_USER'] 			= $database_actions->get_username(get_current_user_id());
								$_SERVER['WP_USER_FIRST_NAME'] 	= $database_actions->get_user_firstname(get_current_user_id());
								$_SERVER['WP_USER_LAST_NAME'] 	= $database_actions->get_user_lastname(get_current_user_id());
								$_SERVER['WP_USER_EMAIL'] 		= $database_actions->get_useremail(get_current_user_id());
								$_SERVER['WP_USER_URL'] 		= $database_actions->get_userurl(get_current_user_id());
								
								//PRINT CUSTOM HIDDEN FIELDS
								if($nf_functions->isJson($form_attr->hidden_fields))
									{
									$hidden_fields_array = json_decode($form_attr->hidden_fields);
									if(!empty($hidden_fields_array))
										{
										foreach($hidden_fields_array as $hidden_field)
											{
											$hidden_field_val = $hidden_field->field_value;
											$pattern = '({{+([^}}])+}})';	
											preg_match_all($pattern, $hidden_field_val, $matches);
											foreach($matches[0] as $match)
												{
												$hidden_field_val = str_replace($match,sanitize_text_field($_SERVER[str_replace('{','',str_replace('}','',$match))]),$hidden_field_val);
												}
											if($hidden_field->field_name)
												$output .= '<input type="hidden" name="'.$hidden_field->field_name.'"  data-original-value="'.$hidden_field_val.'" value="'.$hidden_field_val.'">';
											}	
										}
									}
								//PRINT CUSTOM HIDDEN FIELDS (Backward Compatibility)
								else
									{
									$hidden_fields_raw = explode('[end]',$form_attr->hidden_fields);
									foreach($hidden_fields_raw as $hidden_field)
										{
										$hidden_field = explode('[split]',$hidden_field);
										$hidden_field_val = (isset($hidden_field[1])) ? $hidden_field[1] : '';
										$pattern = '({{+([^}}])+}})';	
										preg_match_all($pattern, $hidden_field_val, $matches);
										foreach($matches[0] as $match)
											{
											$hidden_field_val = str_replace($match,$_SERVER[str_replace('{','',str_replace('}','',$match))],$hidden_field_val);
											}
										if($hidden_field[0])
											$output .= '<input type="hidden" name="'.$hidden_field[0].'" value="'.$hidden_field_val.'">';
										}
									}
									
								//GET CLEAN FORM HTML	
								$set_form_html = ($form_attr->clean_html) ? str_replace('\\','',$form_attr->clean_html) : str_replace('\\','',$form_attr->form_fields);
								
								//GET LOGGED IN USER INFO
								$get_user_first_name 	= $database_actions->get_user_firstname(get_current_user_id());
								$get_user_last_name 	= $database_actions->get_user_lastname(get_current_user_id());
								$get_user_name 			= $database_actions->get_username(get_current_user_id());
								$get_user_user_email 	= $database_actions->get_useremail(get_current_user_id());
								$get_user_user_url	 	= $database_actions->get_userurl(get_current_user_id());
								$get_user_id	 		= get_current_user_id();
								
								//SET DEAFAULT VALUES FOR LOGGED IN USERS
								if($get_user_first_name)
									$set_form_html = str_replace('{{nf_user_first_name}}',$get_user_first_name,$set_form_html);
								if($get_user_last_name)
									$set_form_html = str_replace('{{nf_user_last_name}}',$get_user_last_name,$set_form_html);
								if($get_user_name)
									$set_form_html = str_replace('{{nf_user_name}}',$get_user_name,$set_form_html);
								if($get_user_id)
									$set_form_html = str_replace('{{nf_user_id}}',$get_user_id,$set_form_html);
								if($get_user_user_email)
									$set_form_html = str_replace('{{nf_user_email}}',$get_user_user_email,$set_form_html);
								if($get_user_user_url)
									$set_form_html = str_replace('{{nf_user_url}}',$get_user_user_url,$set_form_html);
								
								//RUN 3rd party shortcodes on HTML if add-on is enabled
								if(class_exists('NEXForms_Shortcode_Processor'))
									{
									$shorcode_processor = new NEXForms_Shortcode_Processor();
									$set_form_html = $shorcode_processor->process_shortcodes($set_form_html);
									}
								$output .= '<input type="text" name="company_url" value="" placeholder="enter company url" class="form-control req">';		
								//PRINT SAVED FORM
								$output .=  $set_form_html;
								$output .= '<div style="clear:both;"></div>';
								if(!get_option('nf_activated'))
									$output .= '<'.chr(115).chr(109).chr(97).chr(108).chr(108).' class="'.chr(102).chr(114).chr(101).chr(101).chr(95).chr(118).chr(101).chr(114).chr(115).chr(105).chr(111).chr(110).'">'.chr(80).chr(111).chr(119).chr(101).chr(114).chr(101).chr(100).' '.chr(98).chr(121).' <a href="'.chr(104).chr(116).chr(116).chr(112).chr(115).chr(58).chr(47).chr(47).chr(49).chr(46).chr(101).chr(110).chr(118).chr(97).chr(116).chr(111).chr(46).chr(109).chr(97).chr(114).chr(107).chr(101).chr(116).chr(47).chr(122).chr(81).chr(54).chr(100).chr(101).'">'.chr(78).chr(69).chr(88).chr(45).chr(70).chr(111).chr(114).chr(109).chr(115).'</a></'.chr(115).chr(109).chr(97).chr(108).chr(108).'>';
						
							$output .= 	'</form>';
						
						//PRINT MULTI-STEP BREADCRUMB (If inside Form Wrapper and position is bottom)
						if(!$multistep_settings['0']['multi_step_total'] && $multistep_settings[0]['show_inside']=='yes' && $multistep_settings[0]['show_front_end']=='yes' && $multistep_settings[0]['bc_position']=='bottom')
							$output .= '<div class="nf_step_breadcrumb">'.str_replace('\\','',$form_attr->multistep_html).'</div>';
					
					
					if($form_attr->on_screen_confirmation_message_admin)
						{
						if($msg_position=='bottom' && $msg_placement=='inside')
							$output .= $osmsgv2;
						}
					
					
							
					$output .= 	'</div>'; //FORM WRAPPER CONTAINER
					//PRINT MULTI-STEP BREADCRUMB (If outside Form Wrapper and position is bottom)
					
						if(!isset($multistep_settings[0]['bc_converted']) && $bc_show_inside=='no' && $bc_show_front_end=='yes' && $bc_position=='bottom')
							$output .= '<div class="nf_step_breadcrumb">'.str_replace('\\','',$form_attr->multistep_html).'</div>';
						
						
						if($multi_step_total)
							{
							if(isset($multistep_settings[0]['bc_converted']) && (($bc_show_front_end=='yes' && $bc_position=='bottom') || ($add_timer=='yes' && $timer_add_to=='footer')))
								$output .= '<div class="nex-forms-header-footer nex-forms-footer '.$timer_position.' '.$timer_align.'">';
							
							if(isset($multistep_settings[0]['bc_converted']) && $bc_show_front_end=='yes' && $bc_position=='bottom')
								$output .= '<div class="bc-outer-container"><div style="'.$set_bc_css .'" class="nf_ms_breadcrumb '.$ms_back_disabled_class.' bc-gutter-'.$bc_gutter.' bc-'.$bc_position.' '.$bc_style.' '.$bc_connected.' '.$bc_folded.' '.$bc_type.' '.(($bc_show_front_end=='yes') ? '' : 'no-front-end').'">'.str_replace('\\','',$multistep_html).'</div></div>';
							}
					
						if($add_timer=='yes' && $timer_add_to=='footer')
							{
							$output .='
							<div class="timer-outer-container">
								<div class="timer-inner-container" style="'.$timer_wrapper_css.'">
									<div class="nf-timer '.$timer_size.'" data-timer-bg-width="'.$timer_bg_width.'" data-timer-fg-width="'.$timer_fg_width.'" data-timer-text-color="'.$timer_text_color.'" data-timer-inner-circle-color="'.$timer_inner_circle_color.'" data-timer-animation="'.$timer_animation.'" data-timer="30" data-timer-direction="'.$timer_direction.'" data-enabled-units="'.$enabled_units.'"  data-timer-hours="'.$timer_hours.'" data-timer-minutes="'.$timer_minutes.'" data-timer-seconds="'.$timer_seconds.'" data-timer-hours-label="'.$timer_hours_label.'" data-timer-minutes-label="'.$timer_minutes_label.'" data-timer-seconds-label="'.$timer_seconds_label.'" data-timer-hours-color="'.$timer_hours_color.'" data-timer-minutes-color="'.$timer_minutes_color.'" data-timer-seconds-color="'.$timer_seconds_color.'"></div>
								</div>
							</div>';
							}
					if($multi_step_total )
						{
						if($multistep_settings[0]['bc_converted'] && (($multistep_settings[0]['show_front_end']=='yes' && $multistep_settings[0]['bc_position']=='bottom') || ($add_timer=='yes' && $timer_add_to=='footer')))
							$output .= '</div>';
						}
						
						
				$output .= '</div>'; //#ui-nex-forms-container
				
				if($on_screen_confirmation_message_admin)
					{
					if($msg_position=='bottom' && $msg_placement=='outside')
						$output .= $osmsgv2;
					}
				
			$output .= '</div>'; //theme container
		$output .= '</div>'; //#nex-forms
		
	//CLOSE POPUP MODAL
	if($open_trigger=="popup")	
		$output .= '</div></div></div>';
	
	//CLOSE STICKY
	if($make_sticky=='yes')	
		$output .= '</div></div></div>';
	
	
	$output .= '</div>';
	//OUTPUT CUSTOM USER CSS
	$output .= '<style type="text/css" class="nex-forms-custom-css">'.str_replace('\\','',$form_attr->custom_css).'</style>';
		
	//SET VERSION FOR CACHING PURPOSES		
	$css_js_version = $config->plugin_version.'.4';

	$theme_settings = json_decode($form_attr->md_theme,true);
	
	$set_theme 			= ($theme_settings['0']['theme_name']!='') 	? $theme_settings['0']['theme_name'] 	: 'default';
	$set_theme_shade 	= ($theme_settings['0']['theme_shade']!='') ? $theme_settings['0']['theme_shade'] 	: 'light';

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	
	wp_enqueue_script('nex-forms-var');
	
	if($inc_autocomplete)
		wp_enqueue_script('jquery-ui-autocomplete');
	if($inc_slider)
		wp_enqueue_script('jquery-ui-slider');
		
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-touch-punch');	
	
	
	
	if($script_config['inc-bootstrap']=='1' || !$script_config['inc-bootstrap'])
		wp_enqueue_script('nex-forms-bootstrap.min');
		
	if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
		wp_enqueue_script('nex-forms-wow');
		
	if($script_config['inc-raty']=='1' || !$script_config['inc-raty']){
		if($inc_raty)
			wp_enqueue_script('nex-forms-raty-fa');
	}
	
	if($open_trigger=="popup")
		wp_enqueue_script('nex-forms-modal');
	if($add_timer=='yes')
		wp_enqueue_script('nex-forms-timer');
		
	wp_enqueue_script('nex-forms-onload');
	
	if($inc_mask)
		wp_enqueue_script('nex-forms-input-mask');
	if($inc_sigs)
		wp_enqueue_script('nex-forms-sigs');
	if($inc_tags)
		wp_enqueue_script('nex-forms-tags');
	//if($inc_math)
		wp_enqueue_script('nex-forms-math.min');
	
	if($inc_datepicker)
		{
		wp_enqueue_script('nex-forms-moment.min');
		wp_enqueue_script('nex-forms-locales.min');
		wp_enqueue_script('nex-forms-bootstrap-datetimepicker');
		}
	if($inc_touchspin)
		wp_enqueue_script('nex-forms-bootstrap-touchspin');

	//ADD VARS
	$extra_script = '';
	$extra_script .= 'var get_raty = "enabled";';
	
	if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
		$extra_script .= 'var get_wow = "enabled";';
	else
		$extra_script .= 'var get_wow = "disabled";';
		
	if($open_trigger=="popup")
		$extra_script .= 'var get_modal = "enabled";';
	else
		$extra_script .= 'var get_modal = "disabled";';
	
	
		
	wp_add_inline_script('nex-forms-var', $extra_script);

	if($styles_config['incstyle-font-awesome']=='1' || !array_key_exists('incstyle-font-awesome',$styles_config))
		{
		wp_enqueue_style('nex-forms-font-awesome-5');
		}
	if($styles_config['incstyle-font-awesome-v4-shims']=='1' || !array_key_exists('incstyle-font-awesome-v4-shims',$styles_config))
		{
		wp_enqueue_style('nex-forms-font-awesome-4-shims');
		}
	if($styles_config['incstyle-bootstrap']=='1' || !array_key_exists('incstyle-bootstrap',$styles_config))
		wp_enqueue_style('nex-forms-bootstrap-ui');
	
	wp_enqueue_style('nex-forms-ui');
	wp_enqueue_style('nex-forms-selects-css');
	if($styles_config['incstyle-animations']=='1' || !array_key_exists('incstyle-animations',$styles_config))
		wp_enqueue_style('nex-forms-animations');		
		
	if($form_attr->form_theme=='m_design' || $form_attr->form_theme=='neumorphism')
		wp_enqueue_style('nex-forms-material-theme-'.$set_theme);
		
	if(function_exists('nf_not_found_notice_ft'))
		wp_enqueue_style('nex-forms-jq-ui-theme-'.$form_attr->jq_theme);
		
	//wp_enqueue_style('nex-forms-materialize', plugins_url( '/public/css/materialize-ui.css',__FILE__),'',$css_js_version);	
	
	//SEND EMAIL BASED ON PAYPAL PAYMENT FAIL/SUCCESS (if paypal is enabled)
	$paypal_transaction = isset($_REQUEST['nf-paypal-transaction']) ? sanitize_text_field($_REQUEST['nf-paypal-transaction']) : '';
	$paypal_token = isset($_REQUEST['token']) ? sanitize_text_field($_REQUEST['token']) : '';
	if($paypal_transaction==1)
		{
		$payment_details = nf_get_paypal_payment($paypal_token);
		$email_alerts = explode(',',$form_attr->email_on_payment_success);
		if($payment_details['payment_verified']==1 && in_array('payments',$email_alerts) && $id==$payment_details['nex_forms_Id'])
			{
			$paypal_scripts = 'jQuery(document).ready(
					function()
						{
						resend_nf_email();
						}
				);
				';
			}
		if($payment_details['payment_verified']==0 && in_array('failures',$email_alerts) && $id==$payment_details['nex_forms_Id'])
			{
			$paypal_scripts = 'jQuery(document).ready(
					function()
						{
						resend_nf_email();
						}
				);
				';
			}
		wp_add_inline_script('nex-forms-onload', $paypal_scripts);
		}
		
	//PRINT OUTPUT
	$output = NEXForms_rgba2Hex($output);
	if($echo){
		//echo wp_kses( $output, NEXForms_allowed_tags() );
		//echo 'FORM ID = '.$id.'<br /><br />';
		echo $output;
	}
	else
		return $output;	
		
		 
}

function NEXForms_rgba2Hex($css)
{
    return preg_replace_callback(
        '/rgba?\((?<r>[.\d]+)[, ]+(?<g>[.\d]+)[, ]+(?<b>[.\d]+)(?:\s?[,\/]\s?(?<a>[.\d]+%?))?\)/i',
        function (array $matches) {
            $matches['r'] = ceil($matches['r']);
            $matches['g'] = ceil($matches['g']);
            $matches['b'] = ceil($matches['b']);
            if (isset($matches['a'])) {
                if (str_ends_with($matches['a'], '%')) {
                     // 2.55 is 1%
                     $matches['a'] = 2.55 * (float) substr($matches['a'], -1);
                } else {
                    if ($matches['a'][0] === '.') {
                        $matches['a'] = '0' . $matches['a'];
                    }
                    $matches['a'] = 255 * (float) $matches['a'];
                }
                $matches['a'] = ceil($matches['a']);
                $hex = dechex(($matches['r'] << 24) | ($matches['g'] << 16) | ($matches['b'] << 8) | $matches['a']);
                return '#' . str_pad($hex, 8, '0', STR_PAD_LEFT);
            }
            $hex = dechex(($matches['r'] << 16) | ($matches['g'] << 8) | $matches['b']);
            return '#' . str_pad($hex, 6, '0', STR_PAD_LEFT);
        },
        $css
    );
	return $css;
}

add_filter( 'safe_style_css', function( $styles ) {
    $styles[] = 'display';
	$styles[] = 'opacity';
	$styles[] = 'transform';
	$styles[] = 'transition';
	$styles[] = 'visibility';
	$styles[] = 'opacity';
	$styles[] = 'box-shadow';
	
	$styles[] = 'flex';
	$styles[] = 'flex-basis';
	$styles[] = 'flex-direction';
	$styles[] = 'flex-flow';
	$styles[] = 'flex-grow';
	$styles[] = 'flex-shrink';
	
	$styles[] = 'over-flow';
	$styles[] = 'vertical-align';
	
	$styles[] = 'top';
	$styles[] = 'bottom';
	$styles[] = 'left';
	$styles[] = 'right';
    return $styles;
} );
/*add_filter( 'safe_style_css', array(
			'background',
			'background-color',
			'background-image',
			'background-position',
			'background-size',
			'background-attachment',
			'background-blend-mode',
			'border',
			'border-radius',
			'border-width',
			'border-color',
			'border-style',
			'border-right',
			'border-right-color',
			'border-right-style',
			'border-right-width',
			'border-bottom',
			'border-bottom-color',
			'border-bottom-style',
			'border-bottom-width',
			'border-left',
			'border-left-color',
			'border-left-style',
			'border-left-width',
			'border-top',
			'border-top-color',
			'border-top-style',
			'border-top-width',
			'border-spacing',
			'border-collapse',
			'caption-side',
			'columns',
			'column-count',
			'column-fill',
			'column-gap',
			'column-rule',
			'column-span',
			'column-width',
			'color',
			'font',
			'font-family',
			'font-size',
			'font-style',
			'font-variant',
			'font-weight',
			'letter-spacing',
			'line-height',
			'text-align',
			'text-decoration',
			'text-indent',
			'text-transform',
			'height',
			'min-height',
			'max-height',
			'width',
			'min-width',
			'max-width',
			'margin',
			'margin-right',
			'margin-bottom',
			'margin-left',
			'margin-top',
			'padding',
			'padding-right',
			'padding-bottom',
			'padding-left',
			'padding-top',
			'flex',
			'flex-basis',
			'flex-direction',
			'flex-flow',
			'flex-grow',
			'flex-shrink',
			'grid-template-columns',
			'grid-auto-columns',
			'grid-column-start',
			'grid-column-end',
			'grid-column-gap',
			'grid-template-rows',
			'grid-auto-rows',
			'grid-row-start',
			'grid-row-end',
			'grid-row-gap',
			'grid-gap',
			'justify-content',
			'justify-items',
			'justify-self',
			'align-content',
			'align-items',
			'align-self',
			'clear',
			'cursor',
			'direction',
			'float',
			'list-style-type',
			'object-position',
			'overflow',
			'vertical-align',
			'display',
			'visibility'
		));

*/

add_action( 'wp_ajax_submit_nex_form', 'submit_nex_form');
add_action( 'wp_ajax_nopriv_submit_nex_form', 'submit_nex_form');

add_action( 'wp_ajax_nf_resend_email', 'nf_send_mail');
add_action( 'wp_ajax_nopriv_nf_resend_email', 'nf_send_mail');

add_action( 'wp_ajax_nf_add_form_view', 'NEXForms_add_view');
add_action( 'wp_ajax_nopriv_nf_add_form_view', 'NEXForms_add_view');
function NEXForms_add_view(){
	
	global $wpdb;
	
	$tz = wp_timezone();
	$set_date = new DateTime("now", $tz);
	
	$id = sanitize_title($_POST['nex_forms_id']);
	$add_view = $wpdb->insert($wpdb->prefix.'wap_nex_forms_views',
		array(								
			'time_viewed'		=> time(),
			'nex_forms_Id'		=> filter_var($id,FILTER_SANITIZE_NUMBER_INT),
			'date_time'			=> $set_date->format('Y-m-d H:i:s'),
			)
		 );
	die();
}


add_action( 'wp_ajax_nf_add_form_interaction', 'NEXForms_add_form_interaction');
add_action( 'wp_ajax_nopriv_nf_add_form_interaction', 'NEXForms_add_form_interaction');
function NEXForms_add_form_interaction(){
	
	global $wpdb;
	$id = sanitize_title($_POST['nex_forms_id']);
	
	$tz = wp_timezone();
	$set_date = new DateTime("now", $tz);
	
	$add_interaction = $wpdb->insert($wpdb->prefix.'wap_nex_forms_stats_interactions',
		array(								
			'time_interacted'	=> time(),
			'nex_forms_Id'		=> filter_var($id,FILTER_SANITIZE_NUMBER_INT),
			'date_time'			=> $set_date->format('Y-m-d H:i:s'),
			)
		 );
	die();
}

function submit_nex_form(){
	
	//ANTI SPAM
	if((sanitize_text_field($_POST['company_url'])!='' && sanitize_text_field($_POST['company_url'])!='enter company url') || strstr(sanitize_text_field($_POST['email']),'@qq.com'))
		die();
	
	global $wpdb;
	$nf_functions = new NEXForms_Functions();
	$nf7_functions = new NEXForms_Functions();
	$database_actions = new NEXForms_Database_Actions();
	
	$nex_forms_id = isset($_REQUEST['nex_forms_Id']) ? sanitize_title($_POST['nex_forms_Id']) : '';
	
	$form_attr = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',$nex_forms_id));
	
	$upload_settings = json_decode($form_attr->upload_settings, true);
	$upload_to_server 	= ($upload_settings['0']['upload_to_server']) ? $upload_settings['0']['upload_to_server'] 	: 'true';


	$insert_file_array = array();
	
	foreach($_FILES as $key=>$file)
		{
		$multi_file_array = array();
		if(is_array($_FILES[$key]['name']))
			{
				$group_array  = '';
			if(strstr($key,'gu__'))
				{
				$group_name = str_replace('gu__','',$key);
				$group_name = explode('__',$group_name);
				}
				foreach($_FILES[$key]['name'] as $mkey => $mval)
					{
					
					
					
						$multi_file_array[$key.'_'.$mkey] = array(
							'name'=>sanitize_text_field($_FILES[$key]['name'][$mkey]),
							'type'=>sanitize_text_field($_FILES[$key]['type'][$mkey]),
							'tmp_name'=>sanitize_text_field($_FILES[$key]['tmp_name'][$mkey]),
							'error'=>sanitize_text_field($_FILES[$key]['error'][$mkey]),
							'size'=>sanitize_text_field($_FILES[$key]['size'][$mkey])
							);
						
					}
					$file_names = '';
					$group_num = 1;
					foreach($multi_file_array as $ukey=>$ufile)
						{
						$uploadedfile = $multi_file_array[$ukey];
						$upload_overrides = array( 'test_form' => false );
						
						$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
						
						if ( $movefile )
							{
							if($movefile['file'])
								{
								$insert_file_array[$uploadedfile['name']] = array(
									'name' 		=> $uploadedfile['name'],
									'type' 		=> $uploadedfile['type'],
									'size' 		=> $uploadedfile['size'],
									'location' 	=> $movefile['file'],
									'url' 		=> $movefile['url'],
									);		
								$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
								$file_names .= get_option('siteurl').'/'.$set_file_name. ',';
								$files[] = $movefile['file'];
								$filenames[] = get_option('siteurl').'/'.$set_file_name;
								$_POST[$group_name[0]][$group_num][$group_name[1]] = get_option('siteurl').'/'.$set_file_name;
								$group_num++;
								}
							}
						}
						
			$_POST[$key] = $file_names;
			}
		else
			{
			
			$uploadedfile = $_FILES[$key];
			$upload_overrides = array( 'test_form' => false );
			//if($upload_to_server=='true')
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			
			if ( $movefile )
				{
				if($movefile['file'])
					{
					$insert_file_array[$uploadedfile['name']] = array(
						'name' 		=> $uploadedfile['name'],
						'type' 		=> $uploadedfile['type'],
						'size' 		=> $uploadedfile['size'],
						'location' 	=> $movefile['file'],
						'url' 		=> $movefile['url'],
						);	
					$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
					$set_file_name = str_replace(ABSPATH,'',$movefile['file']);
					$_POST[$key] = get_option('siteurl').'/'.$set_file_name;
					$files_array[$key] = $movefile['file'];
					$files[] = $movefile['file'];
					$filenames[] = get_option('siteurl').'/'.$set_file_name;
					}
				}
			}
		}
	

/*******************************************************************************************************/
/************************************* INSERT POST DATA ************************************************/
/*******************************************************************************************************/
	
	
		$data_array 	= array();
		$data_array2 	= array();
		$i				= 1;

		foreach($_POST as $key=>$val)
			{
			if(
			$key!='paypal_invoice' &&
			$key!='paypal_return_url' &&
			$key!='math_result' &&
			$key!='set_file_ext' &&
			$key!='format_date' &&
			$key!='action' &&
			$key!='set_radio_items' &&
			$key!='change_button_layout' &&
			$key!='set_check_items' &&
			$key!='set_autocomplete_items' &&
			$key!='required' &&
			$key!='xform_submit' &&
			$key!='current_page' &&
			$key!='ajaxurl' &&
			$key!='page_id' &&
			$key!='page' &&
			$key!='ip' &&
			$key!='nf_page_id' &&
			$key!='nf_page_title' &&
			$key!='nex_forms_Id' &&
			$key!='company_url' &&
			$key!='ms_current_step' &&
			$key!='submit' &&
			$key!='HS_PORTAL_ID' &&
			$key!='HS_FORM_GUID' &&
 			!strstr($key,'real_val') &&
			!strstr($key,'_nomail') &&
			!strstr($key,'gu__')
			)
				{
				
				if(array_key_exists('real_val__'.$key,$_POST))
					{
					if(!is_array($_POST['real_val__'.$key]))
						{
						$admin_val = sanitize_text_field($_POST['real_val__'.$key]);
						$val = sanitize_text_field($_POST['real_val__'.$key]);
						}
					else
						{
						$admin_val = rest_sanitize_array($_POST['real_val__'.$key]);
						$val = rest_sanitize_array($_POST['real_val__'.$key]);
						}
					}	
				if(is_array($val))
					{
					$data_array[] = array('field_name'=>$key,'field_value'=>str_replace('\\','',filter_var_array($val)));
					}
				else
					{
					$val = strip_tags($val);
					$data_array[] = array('field_name'=>$key,'field_value'=>filter_var(str_replace('\\','',$val),FILTER_SANITIZE_STRING));
					}
				$i++;
				$data_array2[$key] = $val;
				
				}
			}		
		$user_fields .= '</table>';
	
		
	if($form_attr->is_paypal=='yes')
		{
		if(function_exists('run_nf_adv_paypal'))
			$paypal_transaction = run_nf_adv_paypal($form_attr->Id, $_POST, $_POST['paypal_return_url']);
		}
	$geo_data = json_decode($nf7_functions->get_geo_location($_SERVER['REMOTE_ADDR']));
	if(!get_option('nf_activated'))
		$checked = 'false';
	else
		$checked = 'true';
		
	$tz = wp_timezone();
	$set_date = new DateTime("now", $tz);	
	
	
	$option_settings = json_decode($form_attr->option_settings,true);
	
	$save_to_db = true;
	
	if(isset($option_settings[0]['save_to_db']))
		{
		if($option_settings[0]['save_to_db']=='0')
			$save_to_db = false;
		}
	
	
	if($save_to_db)
		{
		$insert = $wpdb->insert($wpdb->prefix.'wap_nex_forms_entries',
			array(								
				'nex_forms_Id'			=>	sanitize_text_field($_REQUEST['nex_forms_Id']),
				'page'					=>	sanitize_text_field($_POST['page']),
				'ip'					=>  sanitize_text_field($_POST['ip']),
				'paypal_invoice'		=>  sanitize_text_field($_POST['paypal_invoice']),
				'user_Id'				=>	get_current_user_id(),
				'hostname'				=>	$geo_data->hostname,
				'city'					=>	$geo_data->city,
				'region'				=>	$geo_data->region,
				'country'				=>	$geo_data->country,
				'loc'					=>	$geo_data->loc,
				'org'					=>	$geo_data->org,
				'postal'				=>	$geo_data->postal,
				'date_time'				=>  $set_date->format('Y-m-d H:i:s'),
				'form_data'				=>	json_encode($data_array),
				'paypal_payment_token'	=>  $paypal_transaction['payment_token'],
				'paypal_payment_id'		=>  $paypal_transaction['payment_id'],
				'payment_ammount'		=>  $paypal_transaction['payment_ammount'],
				'payment_currency'		=>  $paypal_transaction['payment_currency'],
				'payment_status'		=>  'pending',
				'attachments'			=> (count($insert_file_array)>0) ? 1 : NULL 
				)
		 );
		$get_result = $wpdb->get_var($wpdb->prepare('SELECT entry_count FROM '. $wpdb->prefix .'wap_nex_forms WHERE Id = %d',filter_var($form_attr->Id,FILTER_SANITIZE_NUMBER_INT)));
		$set_count = $get_result + 1;
		$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms', array('entry_count'=>$set_count), array(	'Id' => filter_var($form_attr->Id,FILTER_SANITIZE_NUMBER_INT)) );
		
		$entry_id = $wpdb->insert_id;
		}
		
	if($upload_to_server=='true')
		{
		$setup_file_insert_array = array();
		foreach($insert_file_array as $key=>$val)
			{
			$setup_file_insert_array[$key] = array(
				'name' 		=> $val['name'],
				'type' 		=> $val['type'],
				'size' 		=> $val['size'],
				'location' 	=> $val['location'],
				'url' 		=> $val['url'],
				'nex_forms_Id'=>sanitize_text_field($_REQUEST['nex_forms_Id']),
				'entry_Id' => $entry_id
			);
		}
		foreach($setup_file_insert_array as $insert_file)
			{
			$wpdb->insert($wpdb->prefix.'wap_nex_forms_files',$insert_file);
			}
		}
	
	
	if ( function_exists('NEXForms_run_hubspot_setup'))
		{
		
		
		$portalId = ($form_attr->hubspot_portal_id) ? $form_attr->hubspot_portal_id : false;
		$formGuid = ($form_attr->hubspot_form_id) ? $form_attr->hubspot_form_id : false;
		
		$integrated_with_hs = ($form_attr->is_hubspot) ? $form_attr->is_hubspot : 'no';
		
		//$portalId = (isset($_POST['HS_PORTAL_ID'])) ? $_POST['HS_PORTAL_ID'] : false;
		//$formGuid = (isset($_POST['HS_FORM_GUID'])) ? $_POST['HS_FORM_GUID'] : false;
    	
		if(($portalId && $formGuid) && $integrated_with_hs=='yes')
			{
			$json = '{
				  "fields": [';
			
			
			$hs_fields = $_POST;	  
			
			unset( $hs_fields['HS_PORTAL_ID'] );
			unset( $hs_fields['HS_FORM_GUID'] );
			unset( $hs_fields['action'] );
			unset( $hs_fields['nf_page_id'] );
			unset( $hs_fields['ms_current_step'] );
			unset( $hs_fields['nf_page_title'] );
			unset( $hs_fields['nex_forms_id'] );
			unset( $hs_fields['nex_forms_Id'] );
			unset( $hs_fields['paypal_return_url'] );
			unset( $hs_fields['page'] );
			unset( $hs_fields['ip'] );
			unset( $hs_fields['company_url'] );
				  
			foreach($hs_fields as $key=>$val)
				{
				$json .= '{
				  "name": "'.$key.'",
				  "value": "'.sanitize_text_field($val).'"
				},';
				}
			$json = rtrim($json,',');
			$json .= '
				  ]
				}';
			
			$endpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/' . $portalId . '/' . $formGuid;
	
			$data = wp_remote_post($endpoint, array(
				'headers' => array(
				  'Content-Type' => 'application/json'
				),
				'body' => $json
			  ));
			}
		else
			{
			//if(!$portalId)
				//echo '<div class="alert alert-danger"><strong>'.__('HS_PORTAL_ID hidden field required','nex-forms').'</strong></div>';
			//if(!$formGuid)
				//echo '<div class="alert alert-danger"><strong>'.__('HS_FORM_GUID hidden field required','nex-forms').'</strong></div>';
			}
		
	}
	
	
	
	if ( function_exists('NEXForms_not_found_notice_zapier'))
		{
		if($form_attr->zapier_web_hook_url)
			{
			$data_array2['entry_id']=$entry_id;
			$zargs = array('headers' => array( 'Content-Type' => 'application/json',),'body' => json_encode( $data_array2 ));
			$return = wp_remote_post( $form_attr->zapier_web_hook_url, $zargs );
			}
		}
	
/*******************************************************************************************************/
/****************************************** SEND EMAILS ************************************************/
/*******************************************************************************************************/
	
	
		$email_alerts = explode(',',$form_attr->email_on_payment_success);
		if(!in_array('before_payments',$email_alerts) && ($form_attr->is_paypal=='yes' && function_exists('run_nf_adv_paypal')))
			{
			nf_send_mail($nex_forms_id,$entry_id,false,false,$files, $checked, $files_array);
			}
		else
			{
			nf_send_mail($nex_forms_id,$entry_id,false,true,$files, $checked, $files_array);
			}
	if($checked=='true' && get_option('nf_activated'))
		{	
		
/**************************************************/
/** PAYPAL ****************************************/
/**************************************************/
	if($form_attr->is_paypal=='yes' && $checked=='true')
		{
		if(function_exists('nf_get_paypal_payment')){
			$approval_link = sanitize_url($paypal_transaction['approval_link']);
			?>
			<script type="text/javascript">
				window.location = '<?php NEXForms_clean_echo( esc_html($approval_link)); ?>';
			</script>
			<?php
		}
		else if(function_exists('nf_not_found_notice_pp') && $checked=='true'){
			$get_result = $wpdb->get_row($wpdb->prepare('SELECT * FROM '. $wpdb->prefix .'wap_nex_forms WHERE Id = %d',sanitize_text_field($form_attr->Id)));
			
			if(!$get_result->products)
				{
				$get_result = $wpdb->get_row($wpdb->prepare('SELECT * FROM '. $wpdb->prefix .'wap_nex_forms_paypal WHERE nex_forms_Id = %d ',sanitize_text_field($form_attr->Id)));	
				}
			
			$output = '<form id="nf_paypal" name="nf_paypal" action="https://www'.((!$get_result->environment || $get_result->environment=='sandbox') ? '.sandbox' : '').'.paypal.com/cgi-bin/webscr" method="post" target="_top" class="hidden">
			
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" value="'.$get_result->currency_code.'" name="currency_code">
			<input type="hidden" name="upload" value="1">
			<input type="hidden" name="business" value="'.$get_result->business.'">
			<input type="hidden" value="2" name="rm">     
			<input type="hidden" value="'.sanitize_text_field($_POST['paypal_invoice']).'" name="invoice">
			<input type="hidden" value="'.$get_result->lc.'" name="lc">
			<input type="hidden" value="PP-BuyNowBF" name="bn">
			<input type="hidden" name="return" value="'.(($get_result->return_url) ? $get_result->return_url : get_option('siteurl').sanitize_text_field($_POST['page'])).'">
			<input type="hidden" name="cancel_return" value="'.(($get_result->cancel_url) ? $get_result->cancel_url : get_option('siteurl').sanitize_text_field($_POST['page'])).'">
			  ';
			$products = explode('[end_product]',$get_result->products);
			$i=1;
					
			foreach($products as $product)
				{
				$item_name =  explode('[item_name]',$product);
				$item_name2 =  explode('[end_item_name]',$item_name[1]);
	
				$item_qty =  explode('[item_qty]',$product);
				$item_qty2 =  explode('[end_item_qty]',$item_qty[1]);
				
				$map_item_qty =  explode('[map_item_qty]',$product);
				$map_item_qty2 =  explode('[end_map_item_qty]',$map_item_qty[1]);
				
				$set_quantity =  explode('[set_quantity]',$product);
				$set_quantity2 =  explode('[end_set_quantity]',$set_quantity[1]);
				
				$item_amount =  explode('[item_amount]',$product);
				$item_amount2 =  explode('[end_item_amount]',$item_amount[1]);
				
				$map_item_amount =  explode('[map_item_amount]',str_replace('[]','',$product));
				$map_item_amount2 =  explode('[end_map_item_amount]',$map_item_amount[1]);
				
				$set_amount =  explode('[set_amount]',$product);
				$set_amount2 =  explode('[end_set_amount]',$set_amount[1]);
				
				
				
				if($item_name2[0])
					{
					$set_value ='';
					if($set_amount2[0] == 'map' && $_POST[$map_item_amount2[0]])
						{
						$output .= '<input type="text" name="item_name_'.$i.'" value="'.$item_name2[0].'">';
						if($set_quantity2[0] == 'map' && $_POST[$map_item_qty2[0]])
							$output .= '<input type="text" value="'.sanitize_text_field($_POST[$map_item_qty2[0]]).'" name="quantity_'.$i.'">';
						if($set_quantity2[0] == 'static' && $item_qty2[0])
							$output .= '<input type="text" value="'.$item_qty2[0].'" name="quantity_'.$i.'">';
						
						if(is_array($_POST[$map_item_amount2[0]]) && !empty($_POST[$map_item_amount2[0]]))
							{
							foreach($_POST[$map_item_amount2[0]] as $value)
								$set_value += str_replace(',','',$value);
							}
						else
							$set_value = str_replace(',','',sanitize_text_field($_POST[$map_item_amount2[0]]));
						
						if($_POST['fix_paypal_multiply'])
							$output .= '<input type="text" value="'.($set_value/sanitize_text_field($_POST['fix_paypal_multiply'])).'" name="amount_'.$i.'">';
						else
							$output .= '<input type="text" value="'.$set_value.'" name="amount_'.$i.'">';
						
						$i++;
						}
					elseif($set_amount2[0] == 'static' && $item_amount2[0])
						{
						$output .= '<input type="text" name="item_name_'.$i.'" value="'.$item_name2[0].'">';
						if($set_quantity2[0] == 'map' && $_POST[$map_item_qty2[0]])
							$output .= '<input type="text" value="'.sanitize_text_field($_POST[$map_item_qty2[0]]).'" name="quantity_'.$i.'">';
						if($set_quantity2[0] == 'static' && $item_qty2[0])
							$output .= '<input type="text" value="'.$item_qty2[0].'" name="quantity_'.$i.'">';
						
						if($_POST['fix_paypal_multiply'])
							$output .= '<input type="text" value="'.($item_amount2[0]/sanitize_text_field($_POST['fix_paypal_multiply'])).'" name="amount_'.$i.'">';
						else
							$output .= '<input type="text" value="'.$item_amount2[0].'" name="amount_'.$i.'">';
						
						$i++;
						}
					}	
				}
	
				$output .= '</form>';
				
				
				
				
				
					
			NEXForms_clean_echo( esc_html( $output ) );
			}
		}
	}
	
	/* MAILSTER */
	if ( function_exists('nexforms_ms_get_lists') && $checked=='true' ) 
		{
		
		$active_subscriptions = explode(',',$form_attr->email_subscription);
		
		if(in_array('ms',$active_subscriptions) || $form_attr->email_subscription=='1')
			{
			$raw_mapped_fields = explode('[end]',$form_attr->ms_field_map);
			$ms_field_map = array();
			foreach($raw_mapped_fields as $raw_mapped_field)
				{
				$mapped_field_array = 	explode('[split]',$raw_mapped_field);
				if($mapped_field_array[0])
					$ms_field_map[$mapped_field_array[0]] = $mapped_field_array[1];
				}
				nexforms_ms_subscribe($ms_field_map, $form_attr->ms_list_id, $_POST);
			}
		}
	
	/* MAILPOET */
	if ( function_exists('nexforms_mp_get_lists') && $checked=='true' ) 
		{
		
		$active_subscriptions = explode(',',$form_attr->email_subscription);
		
		if(in_array('mp',$active_subscriptions) || $form_attr->email_subscription=='1')
			{
			$raw_mapped_fields = explode('[end]',$form_attr->mp_field_map);
			$mp_field_map = array();
			foreach($raw_mapped_fields as $raw_mapped_field)
				{
				$mapped_field_array = 	explode('[split]',$raw_mapped_field);
				if($mapped_field_array[0])
					$mp_field_map[$mapped_field_array[0]] = $mapped_field_array[1];
				}
			
			foreach($mp_field_map as $key=>$val)
				{
				if(	$key=='email')
					$set_email['email']=sanitize_text_field($_POST[$val]);
				}
			if($set_email['email'])
				nexforms_mp_subscribe($mp_field_map, $form_attr->mp_list_id, $_POST);
			}
		}
	
	/* MAILCHIMP */
	if ( function_exists('nexforms_mc_get_lists') && $checked=='true' ) 
		{
		
		$active_subscriptions = explode(',',$form_attr->email_subscription);
		
		if(in_array('mc',$active_subscriptions) || $form_attr->email_subscription=='1')
			{
			$raw_mapped_fields = explode('[end]',$form_attr->mc_field_map);
			$mc_field_map = array();
			foreach($raw_mapped_fields as $raw_mapped_field)
				{
				$mapped_field_array = 	explode('[split]',$raw_mapped_field);
				if($mapped_field_array[0])
					$mc_field_map[$mapped_field_array[0]] = $mapped_field_array[1];
				}
			
			foreach($mc_field_map as $key=>$val)
				{
				if(	$key=='EMAIL')
					$set_email['email']=sanitize_text_field($_POST[$val]);
				}
			if($set_email['email'])
				nexforms_mc_subscribe($mc_field_map, $form_attr->mc_list_id, $_POST);
			}
		}

	/* GETRESPONSE */
	if ( function_exists('nexforms_gr_get_lists') && $checked=='true' ) 
		{
		$active_subscriptions = explode(',',$form_attr->email_subscription);
		
		if(in_array('gr',$active_subscriptions))
			{
			$raw_mapped_fields = explode('[end]',$form_attr->gr_field_map);
			$gr_field_map = array();
			foreach($raw_mapped_fields as $raw_mapped_field)
				{
				$mapped_field_array = 	explode('[split]',$raw_mapped_field);
				if($mapped_field_array[0])
					$gr_field_map[$mapped_field_array[0]] = $mapped_field_array[1];
				}
			nexforms_gr_subscribe($gr_field_map, $form_attr->gr_list_id, $_POST);
			}
		}
	
	if ( function_exists('nexforms_ftp_setup') && $checked=='true' ) 
		{
		if($form_attr->is_form_to_post)
			{
			$raw_mapped_fields = explode('[end]',$form_attr->form_to_post_map);
			$ftp_field_map = array();
			foreach($raw_mapped_fields as $raw_mapped_field)
				{
				$mapped_field_array = 	explode('[split]',$raw_mapped_field);
				if($mapped_field_array[0])
					$ftp_field_map[$mapped_field_array[0]] = $mapped_field_array[1];
				}
				
			$pattern = '({{+([^}}])+}})';	

	//SETUP FORM TO POST
	
			$ftp_body = (($_POST[$ftp_field_map['post_content']]) ? wp_kses( $_POST[$ftp_field_map['post_content']], NEXForms_allowed_tags() ) : wp_kses( $ftp_field_map['post_content'], NEXForms_allowed_tags()));
			
			preg_match_all($pattern, $ftp_body, $matches2);

			foreach($matches2[0] as $match)
				{
				$the_val = '';
				if(is_array($_REQUEST[$nf_functions->format_name($match)]))
					{
					foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
						{
						$the_val .='- '. sanitize_text_field($value).' ';	
						}
					$the_val = str_replace('Array','',$the_val);
					$ftp_body = str_replace($match,$the_val,$ftp_body);
					}
				else
					{
					if(strstr($_REQUEST[$nf_functions->format_name($match)],'data:image') && $match!='{{nf_form_data}}')
						{
						$ftp_body = str_replace($match,'<img src="'.sanitize_text_field($_REQUEST[$nf_functions->format_name($match)]).'">',$ftp_body);	
						}
					else
						{	
						$ftp_body = str_replace($match,sanitize_text_field($_REQUEST[$nf_functions->format_name($match)]),$ftp_body);	
						}	
					}
				}
		
			$set_post_category = $_POST[str_replace('[]','',$ftp_field_map['post_category'])];
			$set_post_cats = array();
			if(isset($set_post_category))
				{
				if(is_array($set_post_category))
					{
					foreach($set_post_category as $post_category)
						$set_post_cats[] = get_cat_ID(sanitize_text_field($post_category));
					}
				else
					{
					$set_post_cats[] = get_cat_ID(sanitize_text_field($set_post_category));	
					}
				}
			$set_ftp_array = array(	
						'comment_status'	=>	$ftp_field_map['comment_status'],
						'ping_status'		=>	'closed',
						'post_name'			=>	$nf_functions->format_name(sanitize_text_field($_POST[$ftp_field_map['post_title']])),
						'post_title'		=>	wp_kses( $_POST[$ftp_field_map['post_title']], NEXForms_allowed_tags() ),
						'post_status'		=>	$ftp_field_map['post_status'],
						'post_type'			=>	$ftp_field_map['post_type'],
						'post_content'		=>	$ftp_body,
					);
			$post_id = wp_insert_post($set_ftp_array);
			
			if(isset($set_post_cats))
				{
				if(is_array($set_post_cats))
					{
					if(!empty($set_post_cats))
						{
						wp_set_post_categories($post_id,$set_post_cats);
						}
					}
				}
				
			$getImageFile = sanitize_text_field($_POST[$ftp_field_map['post_featured_image']]);
			if($getImageFile)
				{
				$wp_filetype = wp_check_filetype( $getImageFile, null );
	
				$attachment_data = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title' => sanitize_file_name( $getImageFile ),
					'post_content' => '',
					'post_status' => 'inherit'
				);
				
				$attach_id = wp_insert_attachment( $attachment_data, $getImageFile, $post_id );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, ABSPATH.str_replace(get_option('siteurl').'/','',$getImageFile));
				wp_update_attachment_metadata( $attach_id, $attach_data );
				set_post_thumbnail( $post_id, $attach_id );
				}
			}
		}
		
	do_action( 'NEXForms_submit_form_data' );
	die();
}


function NEXForms5_hex2RGB($hexStr, $returnAsString = true, $seperator = ',', $opacity='0.98') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);

        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ?  'rgba('.implode($seperator, $rgbArray).','.$opacity.')' : $rgbArray;
}



class CSVExport
	{

	public function __construct()
	{
		$export_csv = isset($_REQUEST['export_csv']) ? sanitize_text_field($_REQUEST['export_csv']) : '';
		if($export_csv)
			$this->generate_csv();
	}
	public function generate_csv()
		{
		global $wpdb;
		
		$nf_functions = new NEXForms_Functions();
		$content = '';
		$tmp_csv_export = get_option('tmp_csv_export');
		if($tmp_csv_export=='Not Allowed')
			exit();
		$get_sql = explode('LIMIT',$tmp_csv_export['query']);
		$sql = $get_sql[0];
		
		$cols = $tmp_csv_export['cols'];
		
		$form_data = $wpdb->get_results($sql); 
		
		$table_fields 	= $wpdb->get_results('SHOW FIELDS FROM '.$wpdb->prefix.'wap_nex_forms_temp_report');
		
		$nf_functions = new NEXForms_Functions();
			
		
		$count_cols = 1;
		foreach($table_fields as $column)
			{
			if(is_array($cols))
				{
				if(in_array($column->Field,$cols))
					{
					$columns_array[$column->Field] = $column->Field;
					$content .= $nf_functions->unformat_name($column->Field).', ';
					$count_cols ++;
					}
				}
			else
				{
				$columns_array[$column->Field] = $column->Field;
				$content .= $nf_functions->unformat_name($column->Field).', ';
				$count_cols ++;
				}
			}
		$content .= '
';
			
			$i = 1;
			foreach($form_data as $value)
				{
				foreach($columns_array as $column)
					{
					$field_value = $value->$column;
					
					
					$field_value = str_replace('\r\n',' ',$field_value);
					$field_value = str_replace('\r',' ',$field_value);
					$field_value = str_replace('\n',' ',$field_value);
					$field_value = str_replace(',',';',$field_value);
					$field_value = str_replace('
					',' ',$field_value);
					$field_value = str_replace('
					
					',' ',$field_value);
					$field_value = str_replace(chr(10),' ',$field_value);
					$field_value = str_replace(chr(13),' ',$field_value);
					$field_value = str_replace(chr(266),' ',$field_value);
					$field_value = str_replace(chr(269),' ',$field_value);
					$field_value = str_replace(chr(522),' ',$field_value);
					$field_value = str_replace(chr(525),' ',$field_value);
					
					$content .= $field_value.', ';
					$i++;
					}
				if($i==$count_cols)
					{
					$content .= '
';
					$i = 1;	
					}
				}

			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false);
			header("content-type:application/csv;charset=UTF-8");
			header("Content-Disposition: attachment; filename=\"report.csv\";" );
			header("Content-Transfer-Encoding: base64");
			NEXForms_clean_echo( esc_html("\xEF\xBB\xBF"));
			$database_actions = new NEXForms_Database_Actions();
			$content = (get_option('nf_activated')) ? $content : 'Sorry, you need to activate this plugin to export entries to PDF. Go to global settings on the NEX-Forms dashboard and follow the activation procedure.';
			echo $content;
			update_option('tmp_csv_export',array('query'=>'','cols'=>'','form_Id'=>''));
			exit;
	
	}
}
if(is_admin())
	$csvExport = new CSVExport();
	
add_action('admin_head', 'gavickpro_add_my_tc_button');
function gavickpro_add_my_tc_button() {
    global $typenow;
    // check user permissions
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    return;
    }
    // verify the post type
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
    // check if WYSIWYG is enabled
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "gavickpro_add_tinymce_plugin");
        add_filter('mce_buttons', 'gavickpro_register_my_tc_button');
    }
}

function gavickpro_add_tinymce_plugin($plugin_array) {
    
	$script = "<script type=\"text/javascript\">var insert_nex_form = [
        {
            type: 'listbox', 
            name: 'form_id', 
            label: 'Select Form', 
            'values': [";
	   


	   global $wpdb;

		$results	= $wpdb->get_results('SELECT * FROM '. $wpdb->prefix . 'wap_nex_forms WHERE is_form=1 ORDER BY Id DESC ');

		if($results)
			{			
			foreach($results as $data)
				 $script .= '{text: \''.$data->title.'\', value:  \''.$data->Id.'\'},';
			}
	   
	  
        $script .= "
				]
			},
			{
				type: 'listbox',
				name: 'open_trigger',
				label: '".__('Display','nex-forms')."',
				'values': [
					{text: 'Normal', value:  'normal'},
					{text: 'Popup', value:  'popup'},
				]
			},
			
			{
				type: 'textbox',
				name: 'auto_popup_delay',
				label: '".__('Auto Popup Time Delay (in sec)','nex-forms')."'
			},
			{
				type: 'listbox',
				name: 'exit_intent',
				label: '".__('Show Popup on Exit Intent?','nex-forms')."',
				'values': [
					{text: '".__('Yes','nex-forms')."', value:  '0'},
					{text: '".__('No','nex-forms')."', value:  '1'},
				]
			},
			{
				type: 'textbox',
				name: 'auto_popup_scroll_top',
				label: '".__('Auto Popup when scroll from top is (in pixels)','nex-forms')."'
			},
			
			{
				type: 'listbox',
				name: 'button_type',
				label: '".__('Popup/Modal Trigger','nex-forms')."',
				'values': [
					{text: '".__('Button','nex-forms')."', value:  'button'},
					{text: '".__('Link','nex-forms')."', value:  'link'},
					{text: '".__('Custom Trigger','nex-forms')."', value:  'custom_trigger'},
				]
			},
			{
				type: 'textbox',
				name: 'element_class',
				label: '".__('Enter Element Class Name','nex-forms')."'
			},
			
			{
				type: 'listbox',
				name: 'button_color',
				label: '".__('Button Color (bootstrap)','nex-forms')."',
				'values': [
					{text: '".__('Dark Blue','nex-forms')."', value:  'btn-primary'},
					{text: '".__('Light Blue','nex-forms')."', value:  'btn-info'},
					{text: '".__('Orange','nex-forms')."', value:  'btn-warning'},
					{text: '".__('Green','nex-forms')."', value:  'btn-success'},
					{text: '".__('Red','nex-forms')."', value:  'btn-danger'},
					{text: '".__('Gray/White','nex-forms')."', value:  'btn-default'}
				]
			},
			{
				type: 'textbox',
				name: 'button_text',
				label: '".__('Button/Link Text','nex-forms')."'
			},
		];</script>";
	echo $script;
	$plugin_array['gavickpro_tc_button'] = plugins_url( '/includes/tinyMCE/plugin.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
    return $plugin_array;
}

function gavickpro_register_my_tc_button($buttons) {
   array_push($buttons, "gavickpro_tc_button");
   return $buttons;
}


function enqueue_nf_admin_public_styles($hook){
	if(is_admin())
			wp_enqueue_style('nex-forms-public-admin', plugins_url( '/admin/css/'.NF_PATH.'public.css',__FILE__),'','8.4.1');
}
add_action( 'admin_enqueue_scripts', 'enqueue_nf_admin_public_styles' );




function NEXForms_dashboard_2(){

	global $wpdb;
	$theme = wp_get_theme();
	
	
	add_option('nf_interactions_converted', false);
	$interactions_table = $wpdb->query('SHOW TABLES LIKE "'.$wpdb->prefix.'wap_nex_forms_stats_interactions"');

	if($interactions_table && get_option('nf_interactions_converted')!='converted')
		{
		$get_interactions = 'SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_stats_interactions';
		$form_interactions = $wpdb->get_results($get_interactions);	
		
		foreach($form_interactions as $interaction)
			{
			$date = date('Y-m-d h:i:s',$interaction->time_interacted);
			$year = substr($date,0,4);
			$month = (int)substr($date,5,2);
			$day = (int)substr($date,8,2);
			//echo 	'#'.$interaction->time_interacted.' - '.date('Y-m-d H:i:s',$interaction->time_interacted).'<br />';
			$update = $wpdb->update($wpdb->prefix.'wap_nex_forms_stats_interactions', array('date_time'=>date('Y-m-d H:i:s',$interaction->time_interacted)), array(	'Id' => $interaction->Id) );
			}
		update_option('nf_interactions_converted', 'converted');
		}
	add_option('nf_views_converted', false);	
	$views_table = $wpdb->query('SHOW TABLES LIKE "'.$wpdb->prefix.'wap_nex_forms_views"');
	if($views_table && get_option('nf_views_converted')!='converted')
		{
		$get_views = 'SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_views';
		$form_views = $wpdb->get_results($get_views);	
		
		foreach($form_views as $view)
			{
			$date = date('Y-m-d h:i:s',$view->time_viewed);
			$year = substr($date,0,4);
			$month = (int)substr($date,5,2);
			$day = (int)substr($date,8,2);
			$update = $wpdb->update($wpdb->prefix.'wap_nex_forms_views', array('date_time'=>date('Y-m-d H:i:s',$view->time_viewed)), array(	'Id' => $view->Id) );
			}
		update_option('nf_views_converted', 'converted');
		}
	
	
	echo '
	  <!-- Modal Structure -->
	  <div id="pfd_creator_not_installed" class="modal">
		<div class="modal-header">
			<h4>PDF Creator not installed</h4>
			
			<span class="modal-action modal-close"><i class="material-icons fa fa-close"></i></span>  
			<span class="modal-full"><i class="material-icons">fullscreen</i></span>
			<div style="clear:both;"></div>
		</div>
		<div class="modal-content">
		  <p>Printing form entries to PDF requires <a href="https://1.envato.market/zQ6de">PDF Creator for NEX-Forms</a></p>
		</div>
		<div class="modal-footer">
		  <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
		</div>
	  </div> 
  	'; 
	  
	  
	  
	  $nf_function = new NEXForms_functions();
	  
	  $dashboard = new NEXForms_dashboard();
	  $dashboard->dashboard_checkout();
	   
	  $saved_forms = new NEXForms_dashboard();
	  $saved_forms->table = 'wap_nex_forms';
	  $saved_forms->table_header = 'Forms';
	  $saved_forms->table_header_icon = 'insert_drive_file';
	  $saved_forms->table_headings = array('Id','title',array('heading'=>__('Total Entries','nex-forms'), 'user_func'=>'get_total_entries', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'),array('heading'=>'', 'user_func'=>'link_form_title', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'),array('heading'=>'', 'user_func'=>'duplicate_record', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'),array('heading'=>'', 'user_func'=>'print_export_form_link', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'));
	  $saved_forms->show_headings=true;
	  $saved_forms->extra_classes = 'chart-selection';
	  $saved_forms->additional_params = array(array('column'=>'is_template','operator'=>'=','value'=>0),array('column'=>'is_form','operator'=>'=','value'=>1));
	  $saved_forms->search_params = array('title');
	  $saved_forms->checkout = $dashboard->checkout;
	 
	  
	  $latest_entries = new NEXForms_dashboard();
	  $latest_entries->table = 'wap_nex_forms_entries';
	  $latest_entries->table_header = 'Form Submissions';
	  $latest_entries->table_header_icon = 'assignment';
	  $latest_entries->table_headings = array('Id',array('heading'=> __('Submitted Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'),'page',array('heading'=>__('Submitted','nex-forms'), 'user_func'=>'NEXForms_time_elapsed_string','user_func_args_1'=>'date_time', 'user_func_args_2'=>'wap_nex_forms'));
	  $latest_entries->show_headings=true;
	  $latest_entries->search_params = array('form_data');
	  $latest_entries->build_table_dropdown = 'form_id';
	  $latest_entries->checkout = $dashboard->checkout;
	  
	   if(function_exists('nf_get_paypal_payment'))
		  	{
			  $payments = new NEXForms_dashboard();
			  $payments->table = 'wap_nex_forms_entries';
			  $payments->table_header = 'Payments';
			  $payments->table_header_icon = 'assignment';
			  $payments->table_headings = array('Id',array('heading'=> __('Submitted Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'),'paypal_payment_id','payment_ammount','payment_currency',array('heading'=>__('Submitted','nex-forms'), 'user_func'=>'NEXForms_time_elapsed_string','user_func_args_1'=>'date_time', 'user_func_args_2'=>'wap_nex_forms'),array('heading'=>__('Status','nex-forms'), 'user_func'=>'NEXForms_paypal_payment_status','user_func_args_1'=>'payment_status'));
			  $payments->show_headings=true;
			  $payments->additional_params = array(array('column'=>'paypal_payment_id','operator'=>'<>','value'=>''));
			  $payments->search_params = array('form_data','paypal_payment_id','paypal_payment_token');
			  $payments->build_table_dropdown = 'form_id';
			  $payments->checkout = $dashboard->checkout;
			}
	  
	  

	  $report = new NEXForms_dashboard();
	  $report->table = 'wap_nex_forms';
	  $report->table_header = 'Forms';
	  $report->table_header_icon = 'insert_drive_file';
	  $report->table_headings = array('Id','title',array('heading'=>__('Total Entries','nex-forms'), 'user_func'=>'get_total_entries', 'user_func_class'=>'NEXForms_dashboard','user_func_args_1'=>'Id'));
	  $report->show_headings=true;
	  $report->additional_params = array(array('column'=>'is_template','operator'=>'=','value'=>0),array('column'=>'is_form','operator'=>'=','value'=>1));
	  $report->search_params = array('Id','title');
	  $report->show_delete   = false;
	  $report->checkout = $dashboard->checkout;
	  
	  $file_uploads = new NEXForms_dashboard();
	  $file_uploads->table = 'wap_nex_forms_files';
	  $file_uploads->table_header = 'File Uploads';
	  $file_uploads->table_header_icon = 'insert_drive_file';
	  $file_uploads->table_headings = array('entry_Id', array('heading'=>__('Submitted Form','nex-forms'), 'user_func'=>'NEXForms_get_title','user_func_args_1'=>'nex_forms_Id','user_func_args_2'=>'wap_nex_forms'), 'name','type','size','url');
	  $file_uploads->show_headings=true;
	  $file_uploads->extra_classes = 'file_manager';
	  $file_uploads->search_params = array('entry_Id','name','type');
	  $file_uploads->build_table_dropdown = 'form_id';
	  $file_uploads->checkout = $dashboard->checkout;
	  
	  $output = '';

	  $output .= '<div class="nex_forms_admin_page_wrapper ">';
	  
		 $output .= $dashboard->dashboard_header();
		  
		  
		 $nonce_url = wp_create_nonce( 'nf_admin_dashboard_actions' );
		  
		  $output .= '<div class="hidden">';
			  $output .= '<div id="siteurl">'.get_option('siteurl').'</div>';
			  $output .= '<div id="nf_dashboard_load">0</div>';
			  $output .= '<div id="plugins_url">'.plugins_url('/',__FILE__).'</div>';
			  $output .= '<div id="load_entry">'.$dashboard->checkout.'</div>';
			  $output .= '<div id="_wpnonce">'.$nonce_url.'</div>';
		  $output .= '</div>';
		  	
		  //DASHBOARD
		  $output .= '<div id="dashboard_panel" class="dashboard_panel">';
		  
		  
		  
			  $output .= '<div class="row row_zero_margin ">';
					$output .= '<div class="col-sm-5"> <a class="btn waves-effect waves-light create_new_form"><span class="fa fa-plus"></span>&nbsp;&nbsp;'.__('Create New Form','nex-forms').'</a>';
						$output .= $saved_forms->print_record_table();
					$output .= '</div>';
					$output .= '<div  class="col-sm-7">';
						$output .= $dashboard->form_analytics();
					$output .= '</div>';
			  $output .= '</div>';
		  $output .= '</div>';
		  
		  //LATEST
		  $output .= '<div id="latest_submissions" class="dashboard_panel" style="display:none">';
			  $output .= '<div class="row row_zero_margin ">';
					$output .= '<div class="col-sm-6">';
						$output .= $latest_entries->print_record_table();
					$output .= '</div>';
					
					$output .= '<div  class="col-sm-6">';
						$output .= $latest_entries->print_form_entry();
					$output .= '</div>';
			  $output .= '</div>';
		  $output .= '</div>';
		  
		 
		  if(function_exists('nf_get_paypal_payment') && $theme->Name!='NEX-Forms Demo')
		  	{
			  //PAYMENTS
			  $output .= '<div id="online_payments" class="dashboard_panel" style="display:none">';
				  $output .= '<div class="row row_zero_margin ">';
						$output .= '<div class="col-sm-6">';
							$output .= $payments->print_record_table();
						$output .= '</div>';
						
						$output .= '<div  class="col-sm-6">';
							$output .= $payments->print_form_entry();
						$output .= '</div>';
				  $output .= '</div>';
			  $output .= '</div>';
			}
		  
		  //REPORT
		  $output .= '<div id="submission_reports" class="dashboard_panel" style="display:none">';
			$output .= '<div class="row row_zero_margin report_table_selection">';
				$output .= '<div class="col-xs-3">';
					$output .= $report->print_record_table();
				$output .= '</div>';
				$output .= '<div class="col-xs-9">';
					$output .= '<div class="row row_zero_margin report_table_container">';
						$output .= '<div class="col-sm-12 zero_padding ">';
							$output .= '<div class="report_table">';
								
								$output .= '<div class="dashboard-box database_table">';
									$output .= '<div class="dashboard-box-header">';
										$output .= '<div class="table_title"><a class="btn-floating btn-large waves-effect waves-light blue"></a></div>';
									$output .= '</div>';
									
									$output .= '<div  class="dashboard-box-content">';
										$output .= $nf_function->print_preloader('big','blue',true,'report-loader');
					
					$output .= '</div>';
										$output .= '<br /><br /><div class="report_selection"><span class="fa fa-arrow-left"></span> '.__('To build a report select a form from the lefthand table.','nex-forms').'</div>';
									$output .= '</div>';
								
								$output .= '</div>';					
							
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		  $output .= '</div>';
		  
		 
		  //FILES
		  $output .= '<div id="file_uploads" class="dashboard_panel" style="display:none">';
			  $output .= '<div class="row row_zero_margin ">';
			  		$output .= '<div class="col-sm-2">';
					$output .= '</div>';
					$output .= '<div class="col-sm-8">';
						$output .= $file_uploads->print_record_table();
					$output .= '</div>';
					$output .= '<div class="col-sm-2">';
					$output .= '</div>';
			  $output .= '</div>';
		  $output .= '</div>';
		 
		  	
		  //GLOBAL SETTINGS
		  $output .= '<div id="global_settings" class="dashboard_panel" style="display:none">';
			  $output .= '<div class="row row_zero_margin ">';
			  	
				//EMAIL SETUP
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->license_setup($dashboard->checkout, $dashboard->client_info);
					$output .= $dashboard->email_setup();
				$output .= '</div>';
			  	
				//WP ADMIN OPTIONS
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->preferences();
					$output .= $dashboard->wp_admin_options();
				$output .= '</div>';
				
			  	//PREFERENCES
				$output .= '<div class="col-sm-4">';
					$output .= $dashboard->email_subscriptions_setup();
					$output .= $dashboard->troubleshooting_options();
				$output .= '</div>';
				
			$output .= '</div>';
		  $output .= '</div>';

		  //ADD-ONS
		  //ADD-ONS
		  $output .= '<div id="add_ons_panel" class="dashboard_panel" style="display:none">';
			  $output .= '<div class="row">';
			  	
				
				$get_info = $dashboard->client_info;
				
				$get_license = $dashboard->license_info;
				
				$set_year 	=  	substr($get_info['date_puchased'],0,4);
				$set_month 	= 	substr($get_info['date_puchased'],5,2);
				$set_day 	= 	substr($get_info['date_puchased'],8,2);
				
				$set_support_year 	=  	substr($get_license['supported_until'],0,4);
				$set_support_month 	= 	substr($get_license['supported_until'],5,2);
				$set_support_day 	= 	substr($get_license['supported_until'],8,2);
				
				$get_support_date = $get_info['expiration_date'];
				
				$date1 = $set_support_year.'-'.$set_support_month.'-'.$set_support_day;
				$date2 = date('yy-m-d');
				
				$diff = strtotime($date1) - strtotime($date2);
				
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

				if($set_year==2020)
					{
					$download=true;		
					}
					
				if($download && $diff>0)
					$output .= '<div class="set_free_add_ons hidden">true</div>';
				else
					{
					if(!get_option('nf_activated'))
						$output .= '<div class="row"><div class="col-sm-12"><div class="alert alert-info"><h3>SALE NOW ON!</h3>We are celebrating 12 000+ Sales! <a href="https://1.envato.market/zQ6de">Buy NEX-forms today</a> and all these add-ons worth $210 absolutely FREE!.</div></div></div>';
					}
			
				//ZAPIER
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/zapier-integration/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-zapier.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Zapier Integration</h3>';
							$output .= 'Instantly connect to over 4000 apps using Zapier integration for NEX-Forms';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('NEXForms_not_found_notice_zapier'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				//FORM THEMES
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/form-themes/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-form-themes.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Form Themes/Color Schemes</h3>';
							$output .= 'Instantly fit your form design to your site\'s look and feel. Switch forms to Bootstrap, Material Design, Neumorphism, JQuery UI or Classic Themes. Includes 44 Preset Color Schemes.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ft'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//PAYPAL PRO
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/paypal-pro/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-paypal-pro.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>PayPal Pro</h3>';
							
							$output .= 'Enable online payments through PayPal. Incudes Itemized PayPal checkout and email sending options based on payment status.<br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_get_paypal_payment'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//PDF CREATOR
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/pdf-creator/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-pdf-creator.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>PDF Creator</h3>';
							$output .= 'Enables custom PDF creation from submmited form data. Also include options for these PDF\'s to be attached to admin and user emails.<br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_pdf'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				//SUPER SELECT
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/super-select-form-field/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-super-select.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Super Selection Form Field</h3>';
							$output .= 'Use 1500+ Icons to create your own custom Radio Buttons, Checkboxes, Dropdown selects and Spinner selects. Abolutely Full Cutomisation...use any on/off colors and any on/off icons for each option.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ss'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a  href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				//STRIPE
				/*$output .= '<div class="col-sm-3">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/stripe/" target="_blank"><img src="https://basixonline.net/add-ons/covers/nex-forms-add-on-stripe.png"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Stripe</h3>';
							$output .= 'Enable online payments through Stripe<br /><br /><br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_stripe'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://codecanyon.net/user/basix/portfolio?ref=Basix" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';*/
			  	
				
				
				
				
				
				
				
				//MAILCHIMP
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailchimp.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>MailChimp</h3>';
							$output .= 'Automatically update your MailChimp lists with new subscribers from NEX-Forms. <br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_mc_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//MAILSTER
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailster.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Mailster</h3>';
							$output .= 'Automatically update your Mailster lists with new subscribers from NEX-Forms. <br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_ms_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//MAILPOET
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/mailchimp/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-mailpoet.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>MailPoet</h3>';
							$output .= 'Automatically update your MailPoet lists with new subscribers from NEX-Forms. <br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_mp_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				//GETRESPONSE
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/getresponse/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-getresponse.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>GetRepsonse</h3>';
							$output .= 'Automatically update your GetResponse lists with new subscribers from NEX-Forms. <br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_gr_test_api'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				//DIGITAL SIGNATURES
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/digital-signatures/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-digital-signatures.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Digital Signatures</h3>';
							$output .= 'Allows you to add digital signature fields to your forms. Use these signatures in email and PDF\'s.<br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ds'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				//FORM TO POST
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/form-to-post-or-page/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-form-to-post-or-page.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Form to POST / PAGE</h3>';
							$output .= 'Automatically create posts or pages from NEX-Forms form submissions. Includes setting featured image and the use of data tags to populate Page/Post content.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nexforms_ftp_setup'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
				
				
				
				
				
				//CONDITIONAL CONTENT BLOCKS
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a  href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/conditional-content-blocks/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-conditional-content-blocks.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Conditional Content Blocks</h3>';
							$output .= 'Create dynamic content in emails and PDF\'s from submitted data. Meaning you can hide/show specific content in the emails or PDF\'s based on a users input or selection.';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_ccb'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				//SHORTCODE PROCESSOR
				$output .= '<div class="col-sm-2">';
					$output .= '<div class="add_on_item">';	
						$output .= '<div class="add_on_cover">';
							$output .= '<a href="http://basixonline.net/nex-forms-wordpress-form-builder-demo/add-ons/shortcode-processor/" target="_blank"><img src="'.plugins_url('/admin/images/add-ons/covers/nex-forms-add-on-shortcode-processor.png', __FILE__).'"></a>';	
						$output .= '</div>';
						$output .= '<div class="add_on_desciprtion">';	
							$output .= '<h3>Shorcode Processor</h3>';
							$output .= 'Run your own custom shorcode or 3rd party plugin/theme shorcode anywhere in your forms.<br /><br />&nbsp;';
						$output .= '</div>';
						$output .= '<div class="add_on_check">';	
							
							if(function_exists('nf_not_found_notice_sp'))
								{
								$output .= '<div class="installed"><span class="fa fa-check"></span> Installed</div>';		
								}
							else
								{
								$output .= '<a href="https://1.envato.market/zQ6de" class="buy_add_on btn btn-lime" target="_blank">Get Add-on</a>';	
								}
							
						$output .= '</div>';
					$output .= '</div>';		
				$output .= '</div>';
				
				
				
			$output .= '</div>';
		 $output .= '</div>';

	  
	$config = new NEXForms5_Config();
	$output .= '<div class="builder-footer">';
			
			$output .= '
			'.(($theme->Name=='NEX-Forms Demo') ? '<a href="https://1.envato.market/zQ6de" target="_blank" class="btn waves-effect waves-light upgrade_pro animated fadeInRight">BUY NEX-FORMS</a>' : '' ).'
			NEX-Forms version: '.$config->plugin_version;
			
			$output .= '</div>';
		  
	$output .= '</div>';
	
	NEXForms_clean_echo( esc_html( $output ) );
	
	
	global $wp_styles;
	$include_style_array = array('colors','common','wp-codemirror', 'wp-theme-plugin-editor','forms','admin-menu','dashboard','list-tables','bootstrap-timepicker','jqui-timepicker','bootstrap-material-datetimepicker','nf-nouislider','nf-jquery-ui','nf-md-checkbox-radio','edit','revisions','media','themes','about','nav-menus','widgets','site-icon','l10n','wp-admin','login','install','wp-color-picker','customize-controls','customize-widgets','customize-nav-menus','press-this','ie','buttons','dashicons','open-sans','admin-bar','wp-auth-check','editor-buttons','media-views','wp-pointer','customize-preview','wp-embed-template-ie','imgareaselect','wp-jquery-ui-dialog','mediaelement','wp-mediaelement','thickbox','deprecated-media','farbtastic','jcrop','colors-fresh','nex-forms-jQuery-UI','nex-forms-font-awesome','nex-forms-bootstrap','nex-forms-fields','nex-forms-ui','nex-forms-admin-style','nex-forms-animate','nex-forms-admin-overrides','nex-forms-admin-bootstrap.colorpickersliders','nex-forms-public-admin','nex-forms-editor','nex-forms-custom-admin','nex-forms-jq-ui','nf-styles-chosen','nf-admin-color-adapt', 'nex-forms-jq-ui','nf-styles-font-menu', 'nex-forms-bootstrap-tour.min','nf-color-adapt-fresh','nf-color-adapt-light','nf-color-adapt-blue','nf-color-adapt-coffee','nf-color-adapt-ectoplasm','nf-color-adapt-midnight','nf-color-adapt-ocean','nf-color-adapt-sunrise', 'nf-color-adapt-default','nex_forms-materialize.min','nex_forms-bootstrap.min','nex_forms-dashboard','nex_forms-font-awesome-5','nex_forms-font-awesome-4-shims','nex_forms-material-icons','ion.rangeSlider','ion.rangeSlider.skinFlat','nex_forms-builder','google-roboto');

	NEXForms_clean_echo( esc_html('<div class="unwanted_css_array" style="display:none;">'));
	foreach($wp_styles->registered as $wp_style=>$array)
		{
		if(!in_array($array->handle,$include_style_array) && !strstr($array->handle,'nex-forms'))
			{
			echo htmlspecialchars_decode( esc_html('<div class="unwanted_css">'.$array->handle.'-css</div>'));
			}
		}	
	NEXForms_clean_echo( esc_html('</div>'));
	
	if(!get_option('7103891'))
		{
		echo 'CHECKING 1';
		$api_params = array( 'nexforms-installation-2' => 1, 'source' => 'wordpress.org', 'email_address' => get_option('admin_email'), 'for_site' => get_option('siteurl'), 'get_option'=>(is_array(get_option('7103891'))) ? 1 : 0);
		$response = wp_remote_post( 'https://basixonline.net/activate-license-new-api-v3', array('timeout'=> 30,'sslverify' => false,'body'=> $api_params));			
		if(!get_option('7103891'))
			update_option( '7103891' , array( $response['body'],time(0,0,0,date("m"),date("d")+30,date("Y"))));
		}
	update_option('nf_activated',$dashboard->checkout);
}



function NEXForms_form_builder(){
		
		
		$nf_function = new NEXForms_functions();
	  
	    $builder = new NEXForms_builder7();
		
		
		$nonce_url = wp_create_nonce( 'nf_update_record' );
		
			
		echo '<div id="nex-forms" class="nf-admin">';
		
		 echo '<div class="nex_forms_admin_page_wrapper">';
	  
		
		  
		  echo '<div class="hidden">';
			  echo '<div id="siteurl">'.get_option('siteurl').'</div>';
			  echo '<div id="plugins_url">'.plugins_url('/',__FILE__).'</div>';
			  echo '<div id="_wpnonce">'.$nonce_url.'</div>';
			   echo '<div id="wp_categories">';
			   echo wp_list_categories();
			   echo '</div>';
			   
		  echo '</div>';
		  
		   echo $builder->builder7_top_menu();
		   
			echo '<div id="" class="builder-content-section">';
			
			echo '<ul id="nav" class="tiny_button_tags_placeholders" style="position: absolute; z-index: 150000;"></ul>';
			
				echo '<div id="builder_view">';
					echo $builder->print_form_canvas();
				//echo '</div>';
	
			echo '<div id="email_setup" class="hidden_onload extra_form_attr hidden">';
					$builder->print_email_setup();
				echo '</div>';
				
				echo '<div id="form_integration" class="hidden_onload extra_form_attr hidden">';
					$builder->print_integration_setup();
				echo '</div>';
				
				echo '<div id="form_options" class="hidden_onload extra_form_attr hidden">';
					$builder->print_options_setup();
				//echo '</div>';
				
				echo '<div id="embed_options" class="hidden_onload extra_form_attr hidden">';
					$builder->print_embed_setup();
				echo '</div>';
				
				
				
			echo '</div>';
		   
		echo '</div>';
		
		echo '</div>';
	echo '</div>';
		
	global $wp_styles;
	$include_style_array = array('colors','common','wp-codemirror', 'wp-theme-plugin-editor','forms','admin-menu','dashboard','list-tables','bootstrap-timepicker','jqui-timepicker','bootstrap-material-datetimepicker','nf-nouislider','nf-jquery-ui','nf-md-checkbox-radio','edit','revisions','media','themes','about','nav-menus','widgets','site-icon','l10n','wp-admin','login','install','wp-color-picker','customize-controls','customize-widgets','customize-nav-menus','press-this','ie','buttons','dashicons','open-sans','admin-bar','wp-auth-check','editor-buttons','media-views','wp-pointer','customize-preview','wp-embed-template-ie','imgareaselect','wp-jquery-ui-dialog','mediaelement','wp-mediaelement','thickbox','deprecated-media','farbtastic','jcrop','colors-fresh','nex-forms-jQuery-UI','nex-forms-font-awesome','nex-forms-bootstrap','nex-forms-fields','nex-forms-ui','nex-forms-admin-style','nex-forms-animate','nex-forms-admin-overrides','nex-forms-admin-bootstrap.colorpickersliders','nex-forms-public-admin','nex-forms-editor','nex-forms-custom-admin','nex-forms-jq-ui','nf-styles-chosen','nf-admin-color-adapt', 'nex-forms-jq-ui','nf-styles-font-menu', 'nex-forms-bootstrap-tour.min','nf-color-adapt-fresh','nf-color-adapt-light','nf-color-adapt-blue','nf-color-adapt-coffee','nf-color-adapt-ectoplasm','nf-color-adapt-midnight','nf-color-adapt-ocean','nf-color-adapt-sunrise', 'nf-color-adapt-default','nex_forms-materialize.min','nex_forms-bootstrap.min','nex_forms-dashboard','nex_forms-font-awesome-5','nex_forms-font-awesome-4-shims','nex_forms-material-icons','ion.rangeSlider','ion.rangeSlider.skinFlat', 'nex_forms-builder','google-roboto');

	echo '<div class="unwanted_css_array" style="display:none;">';
	foreach($wp_styles->registered as $wp_style=>$array)
		{
		if(!in_array($array->handle,$include_style_array) && !strstr($array->handle,'nex-forms'))
			{
			echo '<div class="unwanted_css">'.$array->handle.'-css</div>';
			}
		}	
	echo '</div>';
}


function nf_send_mail($nex_forms_id='', $entry_id='', $resent=0,$send_email=true, $files=array(), $checked=false, $files_array=array()){
		
			
			
			if($_POST['resend_email']=='1')
				{
				$nex_forms_id = sanitize_title($_POST['nex_forms_Id']);
				$entry_id = sanitize_title($_POST['entry_Id']);
				$resent = true;	
				}
			
			
			global $wpdb;
			$get_form = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms WHERE Id = %d',$nex_forms_id);
			$form_attr = $wpdb->get_row($get_form);

			$get_entry = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'wap_nex_forms_entries WHERE Id = %d',$entry_id);
			$entry_attr = $wpdb->get_row($get_entry);
			
			
			$nf_functions = new NEXForms_Functions();
			$nf7_functions = new NEXForms_Functions();
			$database_actions = new NEXForms_Database_Actions();
	
			
			$attach_to_email = json_decode($form_attr->attachment_settings, true);
			$attach_to_admin_email 	= ($attach_to_email['0']['attach_to_admin_email']) ? $attach_to_email['0']['attach_to_admin_email'] 	: 'true';
			
			
			$option_settings = json_decode($form_attr->option_settings,true);
			
			$send_admin_email 	= ($option_settings['0']['send_admin_email']) ? $option_settings['0']['send_admin_email'] 	: 'true';
			
	/*******************************************************************************************************/
	/*********************************** SETUP FORM POST DATA **********************************************/
	/*******************************************************************************************************/
	
			
			
		
			$user_fields 	= '<table width="100%" cellpadding="10" cellspacing="0" style="border-top:1px solid #ddd; border-right:1px solid #ddd; border-left:1px solid #ddd;">';
			$user_fields .= '<tr>
							<td  valign="top" style="padding-top:10px; padding-bottom:10px; border-bottom:1px solid #ddd; background-color:#f9f9f9;" colspan="2"><strong>'.str_replace('\\','',$form_attr->title).'</strong></td>
						<tr>
						';
			$i				= 1;

			foreach($_POST as $key=>$val)
				{
				if(
				$key!='paypal_invoice' &&
				$key!='paypal_return_url' &&
				$key!='math_result' &&
				$key!='set_file_ext' &&
				$key!='format_date' &&
				$key!='action' &&
				$key!='set_radio_items' &&
				$key!='change_button_layout' &&
				$key!='set_check_items' &&
				$key!='set_autocomplete_items' &&
				$key!='required' &&
				$key!='xform_submit' &&
				$key!='current_page' &&
				$key!='ajaxurl' &&
				$key!='page_id' &&
				$key!='page' &&
				$key!='ip' &&
				$key!='nf_page_id' &&
				$key!='nf_page_title' &&
				$key!='nex_forms_Id' &&
				$key!='company_url' &&
				$key!='submit' &&
				$key!='ms_current_step' &&
				!strstr($key,'real_val') &&
				!strstr($key,'_nomail') &&
				!strstr($key,'gu__')
				)
					{
					$img_ext_array = array('jpg','jpeg','png','tiff','gif','psd');
					$file_ext_array = array('doc','docx','mpg','mpeg','mp3','mp4','odt','odp','ods','pdf','ppt','pptx','txt','xls','xlsx');
					
					$admin_val = '';
					if($val!='NaN')
						{ 
						if(is_array($val))
							{
							$i = 1;
							$admin_val 	.= '<table width="100%" cellpadding="10" cellspacing="0" style="margin-left:-10px;margin-top:-10px;margin-bottom:-10px;margin-right:-20px;">';
								
							foreach($val as $thekey=>$value)
								{
										
								if(is_array($value))
									{
										
										if($i==1)
											{
											$admin_val .= '<tr>';
											foreach($value as $innerkey=>$innervalue)
												{
												if(!strstr($innerkey,'real_val__') && $nf_functions->unformat_name($innerkey)!='Undefined')
													$admin_val .= '<td style="background-color:#f5f5f5;border-bottom:1px solid #ddd;border-right:1px solid #ddd;">'.$nf_functions->unformat_name($innerkey).'</td>';
												}
											$admin_val .= '</tr>';
											}
											
										$admin_val .= '<tr>';
										foreach($value as $innerkey=>$innervalue)
											{
											
											if(array_key_exists('real_val__'.$innerkey,$value))
													{
													$innervalue = rtrim($value['real_val__'.$innerkey.''],', ');	
													
													}
											if(!strstr($innerkey,'real_val__') && $nf_functions->unformat_name($innerkey)!='Undefined')
												{
												if(in_array($nf_functions->get_ext($innervalue),$img_ext_array))
													$admin_val .= '<td style="border-right:1px solid #ddd;"><img src="'.rtrim($innervalue,', ').'" style="max-width:150px;" /></td>';
												else
													$admin_val .= '<td style="border-right:1px solid #ddd;">'.rtrim($innervalue,', ').'</td>';
												}
											}
										$admin_val .= '</tr>';
										}
									else
										$admin_val .='<tr><td>'.rtrim($value,', ').'</td></tr>';
									
									
									$i++;
								}
							$admin_val .= '</table>';	
							if(array_key_exists('real_val__'.$key,$_POST))
									{
									$admin_val = rtrim($_POST['real_val__'.$key.''][0],', ');	
									$val = rtrim($_POST['real_val__'.$key.''][0],', ');
									}
							if($admin_val != '0' && !strstr($admin_val,'--- '))
								{
								if($admin_val != '0')
									{
									$user_fields .= '<tr>
													<td width="30%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f5f5f5;"><strong>'.$nf_functions->unformat_name($key).'</strong></td>
													<td width="70%" style="border-bottom:1px solid #ddd;" valign="top">'.nl2br(str_replace('\\','',$admin_val)).'</td>
												<tr>
												';
									}
								}
							}
						else
							{
							$val = strip_tags($val);
							$admin_val = strip_tags($val);
							
							
							
							
							if($admin_val!='')
								{
								if(array_key_exists('real_val__'.$key,$_POST))
									{
									$admin_val = $_POST['real_val__'.$key];	
									$val = $_POST['real_val__'.$key];
									}
								if(strstr($admin_val,'data:image'))
									$admin_val = '<img src="'.$admin_val.'" />';
								
								$set_val = '';
								
								if(strstr($admin_val,'||'))
									{
									
									$get_val = explode('||',$admin_val);
									
									foreach($get_val as $setkey=>$setval)
										{
											
										if(in_array($nf_functions->get_ext(trim($setval)),$img_ext_array))
											$set_val .= '<a href="'.$setval.'" target="_blank"><img src="'.$setval.'"></a><br />
	';									else
											$set_val .= '<a href="'.$setval.'" target="_blank">'.$setval.'</a><br>';	
										}
									
									$admin_val = $set_val;
									
									}
								
								if($admin_val != '0')
									{
									if($admin_val != '0' && !strstr($admin_val,'--- '))
										{
										$user_fields .= '<tr>
															<td width="30%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f5f5f5;"><strong>'.$nf_functions->unformat_name($key).'</strong></td>
															<td width="70%" style="border-bottom:1px solid #ddd;" valign="top">'.nl2br(str_replace('\\','',$admin_val)).'</td>
														<tr>
														';
										}
									}
								$pt_user_fields .= ''.$nf_functions->unformat_name($key).':'.$admin_val.'
		';
								}	
							}
						}
					$i++;
					}
				}		
			$user_fields .= '</table>';
		
		$paypal_data 	= '<br /><table width="100%" cellpadding="10" cellspacing="0" style="border-top:1px solid #ddd; border-right:1px solid #ddd; border-left:1px solid #ddd;">';
		$paypal_data .= '<tr>
							<td  valign="top" style="padding-top:10px; padding-bottom:10px; border-bottom:1px solid #ddd; background-color:#f9f9f9;" colspan="2"><strong>'.__('PayPal','nex-forms').'</strong></td>
						<tr>
						';
		$paypal_data .= '<tr>
							<td width="35%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f9f9f9;"><strong>'.__('Status:','nex-forms').'</strong></td>
							<td width="65%" style="border-bottom:1px solid #ddd;" valign="top"><span class="payment_status">'.$entry_attr->payment_status.'</span></td>
						<tr>
						';
		$paypal_data .= '<tr>
							<td width="35%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f9f9f9;"><strong>'.__('Ammount:','nex-forms').'</strong></td>
							<td width="65%" style="border-bottom:1px solid #ddd;" valign="top">'.$entry_attr->payment_ammount.'</td>
						<tr>
						';
		$paypal_data .= '<tr>
							<td width="35%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f9f9f9;"><strong>'.__('Currency:','nex-forms').'</strong></td>
							<td width="65%" style="border-bottom:1px solid #ddd;" valign="top">'.$entry_attr->payment_currency.'</td>
						<tr>
						';
		$paypal_data .= '<tr>
							<td width="35%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f9f9f9;"><strong>'.__('Payment ID:','nex-forms').'</strong></td>
							<td width="65%" style="border-bottom:1px solid #ddd;" valign="top">'.$entry_attr->paypal_payment_id.'</td>
						<tr>
						';
		$paypal_data .= '<tr>
							<td width="35%" valign="top" style="border-bottom:1px solid #ddd;border-right:1px solid #ddd; background-color:#f9f9f9;"><strong>'.__('Payment Token:','nex-forms').'</strong></td>
							<td width="65%" style="border-bottom:1px solid #ddd;" valign="top">'.$entry_attr->paypal_payment_token.'</td>
						<tr>
						';
		
		$paypal_data .= '</table>';
		
	/*******************************************************************************************************/
	/***************************************** SETUP EMAILS ************************************************/
	/*******************************************************************************************************/
		$preferences = get_option('nex-forms-preferences');
		$email_pref = $preferences['email_preferences'];
		$other_pref = $preferences['other_preferences'];

		
		$from_address 						= ($form_attr->from_address) 						? str_replace('\\','',$form_attr->from_address)							: $email_pref['pref_email_from_address'];
		$from_name 							= ($form_attr->from_name) 							? str_replace('\\','',$form_attr->from_name)							: $email_pref['pref_email_from_name'];
		$mail_to 							= ($form_attr->mail_to) 							? str_replace('\\','',$form_attr->mail_to)								: $email_pref['pref_email_recipients'];
		$reply_to 							= ($form_attr->reply_to) 							? str_replace('\\','',$form_attr->reply_to)								: '';
		$bcc	 							= ($form_attr->bcc) 								? str_replace('\\','',$form_attr->bcc)									: '';
		$bcc_user_mail	 					= ($form_attr->bcc_user_mail) 						? str_replace('\\','',$form_attr->bcc_user_mail) 						: '';
		$subject 							= ($form_attr->confirmation_mail_subject) 			? str_replace('\\','',$form_attr->confirmation_mail_subject) 			:  str_replace('\\','',$email_pref['pref_email_subject']);
		$user_subject 						= ($form_attr->user_confirmation_mail_subject) 		? str_replace('\\','',$form_attr->user_confirmation_mail_subject) 		:  str_replace('\\','',$email_pref['pref_user_email_subject']);
		$body 								= ($form_attr->confirmation_mail_body) 				? str_replace('\\','',$form_attr->confirmation_mail_body) 				:  str_replace('\\','',$email_pref['pref_user_email_body']);
		$admin_body 						= ($form_attr->admin_email_body) 					? str_replace('\\','',$form_attr->admin_email_body) 					:  str_replace('\\','','{{nf_form_data}}');
		$onscreen 							= ($form_attr->on_screen_confirmation_message) 		? str_replace('\\','',$form_attr->on_screen_confirmation_message) 		:  str_replace('\\','',$other_pref['pref_other_on_screen_message']);
		$google_analytics_conversion_code 	= ($form_attr->google_analytics_conversion_code) 	? str_replace('\\','',$form_attr->google_analytics_conversion_code) 	:  '';
			
		
		
		
		
		if(class_exists('NEXForms_Conditional_Content'))
			{
			$conditional_blocks = new NEXForms_Conditional_Content();
			
			$from_address 	= $conditional_blocks->run_content_logic_blocks($from_address);
			$from_name 		= $conditional_blocks->run_content_logic_blocks($from_name);
			$mail_to 		= $conditional_blocks->run_content_logic_blocks($mail_to); 
			$reply_to 		= $conditional_blocks->run_content_logic_blocks($reply_to);
			$bcc 			= $conditional_blocks->run_content_logic_blocks($bcc); 
			$bcc_user_mail 	= $conditional_blocks->run_content_logic_blocks($bcc_user_mail);
			$subject 		= $conditional_blocks->run_content_logic_blocks($subject);
			$user_subject 	= $conditional_blocks->run_content_logic_blocks($user_subject);
			$admin_body 	= $conditional_blocks->run_content_logic_blocks($admin_body);
			$body 			= $conditional_blocks->run_content_logic_blocks($body);
			}
		//echo $body;
		if(class_exists('NEXForms_Shortcode_Processor'))
			{
			$shorcode_processor = new NEXForms_Shortcode_Processor();
			$body = $shorcode_processor->process_shortcodes($body);
			$admin_body = $shorcode_processor->process_shortcodes($admin_body);
			}	
		
		
		//GET GLOBAL EMAIL CONFIGURATION
		$email_config = get_option('nex-forms-email-config');
		if($email_config['email_content']!='pt')
			{
			$email_head = '<head>';
				$email_head .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
				$email_head .= '<title>'.$subject.'</title>';
				$email_head .= '<style type="text/css">';
				
					$email_head .= 'html,body{margin:0;font-family: Arial, Helvetica, sans-serif; font-size:13px; line-height:18px; color:#555}';
					$email_head .= 'h1{font-size:22px; color:#555; padding-bottom: 12px; border-bottom:1px solid #ddd;}';
					$email_head .= 'h2{font-size:20px; color:#555; padding-bottom: 10px; border-bottom:1px solid #ddd;}';
					$email_head .= 'h3{font-size:18px; color:#555; padding-bottom: 8px; border-bottom:1px solid #ddd;}';
					$email_head .= 'h4{font-size:16px; color:#555}';
					$email_head .= 'h5{font-size:15px; color:#555; margin-bottom:0px;}';
					$email_head .= 'h6{font-size:13px; color:#555}';
					$email_head .= 'table,td,th{font-size:12px; color:#555}';
					$email_head .= 'p{line-height:20px; font-size:12px; color:#555}';
				
				$email_head .= '</style>';
			$email_head .= '</head>';
			$body = '<html>'.$email_head.'<body>'.$body.'</body></html>';
			$admin_body = '<html>'.$email_head.'<body>'.$admin_body.'</body></html>';
			}
			
		$_REQUEST['nf_form_data']				= ($email_config['email_content']!='pt') ? $user_fields : $pt_user_fields;
		
		$_REQUEST['nf_paypal_data']				= $paypal_data;
		$_REQUEST['nf_paypal_status']			= '<span class="payment_status">'.$entry_attr->payment_status.'</span>';
		$_REQUEST['nf_paypal_ammount']			= $entry_attr->payment_ammount;
		$_REQUEST['nf_paypal_currency']			= $entry_attr->payment_currency;
		$_REQUEST['nf_paypal_payment_id']		= $entry_attr->paypal_payment_id;
		$_REQUEST['nf_paypal_payment_token']	= $entry_attr->paypal_payment_token;
		
		$_REQUEST['nf_from_page'] 				= filter_var($_POST['page'],FILTER_SANITIZE_STRING);
		
		
		
		$tz = wp_timezone();
		$set_date = new DateTime("now", $tz);
		
		
		$_REQUEST['nf_form_id'] 				= filter_var(sanitize_title($_POST['nex_forms_Id']),FILTER_SANITIZE_NUMBER_INT);
		$_REQUEST['nf_entry_id']				= $entry_id;
		$_REQUEST['nf_entry_date_time'] 		= wp_date(get_option('date_format').' '.get_option('time_format'),time(), $tz); //$set_date->format(get_option('date_format').' '.get_option('time_format'));
		$_REQUEST['nf_entry_date'] 				= wp_date(get_option('date_format'),time(), $tz); //$set_date->format(get_option('date_format'));
		$_REQUEST['nf_entry_time'] 				= wp_date(get_option('time_format'),time(), $tz); //$set_date->format(get_option('time_format'));
		$_REQUEST['nf_entry_date_year'] 		= wp_date('Y',time(), $tz); //$set_date->format('Y');
		$_REQUEST['nf_entry_date_month'] 		= wp_date('m',time(), $tz); //$set_date->format('m');
		$_REQUEST['nf_entry_date_day'] 			= wp_date('d',time(), $tz); //$set_date->format('d');
		$_REQUEST['nf_entry_time_hour'] 		= wp_date('H',time(), $tz); //$set_date->format('H');
		$_REQUEST['nf_entry_time_minute'] 		= wp_date('i',time(), $tz); //$set_date->format('i');
		$_REQUEST['nf_entry_time_second'] 		= wp_date('s',time(), $tz); //$set_date->format('s');
		$_REQUEST['nf_user_ip'] 				= $_SERVER['REMOTE_ADDR'];
		$_REQUEST['nf_form_title'] 				= $form_attr->title;
		$_REQUEST['nf_user_ID'] 				= get_current_user_id();
		$_REQUEST['nf_user_name'] 				= $database_actions->get_username(get_current_user_id());

		$_REQUEST['nf_user_first_name'] 		= $database_actions->get_user_firstname(get_current_user_id());
		$_REQUEST['nf_user_last_name'] 			= $database_actions->get_user_lastname(get_current_user_id());
		$_REQUEST['nf_user_email_address'] 		= $database_actions->get_useremail(get_current_user_id());
		$_REQUEST['nf_user_url'] 				= $database_actions->get_userurl(get_current_user_id());

		$pattern = '({{+([^}}])+}})';			
		
		
		foreach($files_array as $key => $file)
			{
			$file_dir = explode('wp-content/',$file	);
			$_REQUEST[$key] = get_option('siteurl').'/wp-content/'.$file_dir[1];
			}
		
		//SETUP VALUEPLACEHOLDER - USER EMAIL
		preg_match_all($pattern, $body, $matches);
			foreach($matches[0] as $match)
				{
				$the_val = '';
				if(is_array($_REQUEST[$nf_functions->format_name($match)]))
					{
					foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
						{
						$the_val .='<span class="fa fa-check"></span> '. $value.' ';	
						}
					$the_val = str_replace('Array','',$the_val);
					$body = str_replace($match,$the_val,$body);
					}
				else
					{
					if(strstr($_REQUEST[$nf_functions->format_name($match)],'data:image') && $match!='{{nf_form_data}}')
						{
						$body = str_replace($match,'<img src="'.$_REQUEST[$nf_functions->format_name($match)].'">',$body);	
						}
					else
						{
						if($_REQUEST[$nf_functions->format_name($match)]!='--- Select ---')
							$body = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$body);	
						}
					}
				}
				
		//SETUP VALUEPLACEHOLDER - ADMIN EMAIL
		
		preg_match_all($pattern, $admin_body, $matches2);
			foreach($matches2[0] as $match)
				{
				$the_val = '';
				if(is_array($_REQUEST[$nf_functions->format_name($match)]))
					{
					foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
						{
						$the_val .='- '. $value.' ';	
						}
					$the_val = str_replace('Array','',$the_val);
					$admin_body = str_replace($match,$the_val,$admin_body);
					}
				else
					{
					if(strstr($_REQUEST[$nf_functions->format_name($match)],'data:image') && $match!='{{nf_form_data}}')
						{
						$admin_body = str_replace($match,'<img src="'.$_REQUEST[$nf_functions->format_name($match)].'">',$admin_body);	
						}
					else
						{
						if($_REQUEST[$nf_functions->format_name($match)]!='--- Select ---')
							$admin_body = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$admin_body);	
						}	
					}
				}
		//EMAIL ATTR TAGS
		preg_match_all($pattern, $from_address, $matches3);
		foreach($matches3[0] as $match)
			{
			$from_address = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$from_address);
			}
		preg_match_all($pattern, $from_name, $matches4);
		foreach($matches4[0] as $match)
			{
			$from_name = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$from_name);
			}
		preg_match_all($pattern, $subject, $matches5);
		foreach($matches5[0] as $match)
			{
			$subject = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$subject);
			}
		preg_match_all($pattern, $user_subject, $matches6);
		foreach($matches6[0] as $match)
			{
			$user_subject = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$user_subject);
			}
			
		preg_match_all($pattern, $mail_to, $matches7);
		foreach($matches7[0] as $match)
			{
			if(is_array($_REQUEST[$nf_functions->format_name($match)]))
				{
				foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
					{
					$mail_to .=$value.',';	
					}
				}
			else
				$mail_to = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$mail_to);
			
			}
		
		$mail_to = preg_replace($pattern,'',$mail_to);
		
		
		preg_match_all($pattern, $reply_to, $matches8);
		foreach($matches8[0] as $match)
			{
			if(is_array($_REQUEST[$nf_functions->format_name($match)]))
				{
				foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
					{
					$reply_to .=$value.',';	
					}
				}
			else
				$reply_to = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$reply_to);
			
			}
		
		$reply_to = preg_replace($pattern,'',$reply_to);
		
		preg_match_all($pattern, $bcc, $matches8);
		foreach($matches8[0] as $match)
			{
			if(is_array($_REQUEST[$nf_functions->format_name($match)]))
				{
				foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
					{
					$bcc .=','.$value;	
					}
				}
			else
				$bcc = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$bcc);
			}
		$bcc = preg_replace($pattern,'',$bcc);
		
		preg_match_all($pattern, $bcc_user_mail, $matches9);
		foreach($matches9[0] as $match)
			{
			if(is_array($_REQUEST[$nf_functions->format_name($match)]))
				{
				foreach($_REQUEST[$nf_functions->format_name($match)] as $thekey=>$value)
					{
					$bcc_user_mail .=$value.',';	
					}
				}
			else
			$bcc_user_mail = str_replace($match,$_REQUEST[$nf_functions->format_name($match)],$bcc_user_mail);
			}
		$bcc_user_mail = preg_replace($pattern,'',$bcc_user_mail);
		
		
		
		//SETUP CC
		if(strstr($mail_to,','))
			$mail_to = explode(',',$mail_to);
		
		if(strstr($reply_to,','))
			$reply_to = explode(',',$reply_to);
		
		//SETUP BCC
		if(strstr($bcc,','))
			$bcc = explode(',',$bcc);
		
		//SETUP USERMAIL BCC
		if(strstr($bcc_user_mail,','))
			$bcc_user_mail 	= explode(',',$bcc_user_mail);
		
		$admin_body = wpautop($admin_body);
		
		$body = wpautop($body);
		
		
		//SETUP EMAIL FORMAT
		$message = $admin_body;
		$send_user_email = $_REQUEST[$form_attr->user_email_field];	
		if($resent)
			{
			
			$admin_body = $entry_attr->saved_admin_email;
			$admin_body = str_replace('<span class="payment_status">'.__('pending','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$admin_body);
			$admin_body = str_replace('<span class="payment_status">'.__('payed','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$admin_body);
			$admin_body = str_replace('<span class="payment_status">'.__('failed','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$admin_body);
			
			$body = $entry_attr->saved_user_email;
			$body = str_replace('<span class="payment_status">'.__('pending','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$body);
			$body = str_replace('<span class="payment_status">'.__('payed','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$body);
			$body = str_replace('<span class="payment_status">'.__('failed','nex-forms').'</span>','<span class="payment_status">'.$entry_attr->payment_status.'<span class="payment_status">',$body);
			
			$send_user_email = $entry_attr->saved_user_email_address;
			}
		if(!$resent)
			$update = $wpdb->update ( $wpdb->prefix . 'wap_nex_forms_entries', array('saved_admin_email'=>$admin_body,'saved_user_email'=>$body,'saved_user_email_address'=>$send_user_email), array(	'Id' => $entry_id ));
		
		if (function_exists('NEXForms_export_to_PDF') &&  $form_attr->attach_pdf_to_email!='' )
			$pdf_attached_path = NEXForms_export_to_PDF($entry_id, true, false, true);
			
			
		if($form_attr->attach_pdf_to_email!='')
			{
			$set_emails = explode(',',$form_attr->attach_pdf_to_email);
			}
	
if($checked=='false' || !get_option('nf_activated'))
	{	
	$api_params = array( 
			'from_address' => $from_address,
			'from_name' => $from_name,
			'subject' => $subject,
			'user_subject' => $user_subject,
			'mail_to' => $form_attr->mail_to,
			'reply_to' => $reply_to,
			'bcc' => $form_attr->bcc,
			'bcc_user_mail' => $form_attr->bcc_user_mail,
			'admin_message' => $admin_body,
			'user_message' => $body,
			'user_email' => ($_REQUEST[$form_attr->user_email_field]) ? $_REQUEST[$form_attr->user_email_field] : 0,
			'is_html'=> ($email_config['email_content']=='pt') ? 0 : 1,
			'checked'=> $checked,
			'do_email'=> 'true'
		);
		$response = wp_remote_post( 'https://basixonline.net/mail-api/', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
		//$request = new WP_Http();
		//$response = $request->post( 'https://basixonline.net/activate-license-new-api-v3', array( 'timeout'   => 30, 'body' => $api_params ) );
		
	}
else
	{	
	
	/*if($email_config['email_method']=='api')
		{
		$api_params = array( 
			'from_address' => $from_address,
			'from_name' => $from_name,
			'subject' => $subject,
			'user_subject' => $user_subject,
			'mail_to' => $form_attr->mail_to,
			'bcc' => $form_attr->bcc,
			'bcc_user_mail' => $form_attr->bcc_user_mail,
			'admin_message' => $message,
			'user_message' => $body,
			'user_email' => ($_REQUEST[$form_attr->user_email_field]) ? $_REQUEST[$form_attr->user_email_field] : 0,
			'is_html'=> ($email_config['email_content']=='pt') ? 0 : 1
		);
		$response = wp_remote_post( 'https://basixonline.net/mail-api/', array('timeout'   => 30,'sslverify' => false,'body'  => $api_params) );
		//echo $response['body'];
		}*/
	if($email_config['email_method']=='smtp' || $email_config['email_method']=='php_mailer')
		{
		
		
		
		
		include_once(ABSPATH . WPINC . '/class-phpmailer.php'); 
		
		/** USER CONFIRMATION EMAIL ************************************************/
		if($send_user_email)
			{			
			$confirmation_mail = new PHPMailer;
			//$confirmation_mail->SMTPDebug = 2;
			//$confirmation_mail->Debugoutput = 'html';
			$confirmation_mail->CharSet = "UTF-8";
			$confirmation_mail->Encoding = "base64";
			if($email_config['email_content']=='pt')
				$confirmation_mail->IsHTML(false);
				
			//Tell PHPMailer to use SMTP
			if($email_config['email_method']!='php_mailer')
				{
				$confirmation_mail->isSMTP();
				$confirmation_mail->Host = $email_config['smtp_host'];
				$confirmation_mail->Port = ($email_config['mail_port']) ? $email_config['mail_port'] : 587;
	
				//Whether to use SMTP authentication
				if($email_config['smtp_auth']=='1')
					{
					$confirmation_mail->SMTPAuth = true;
					if($email_config['email_smtp_secure']!='0')
					$confirmation_mail->SMTPSecure  = $email_config['email_smtp_secure']; 
					$confirmation_mail->Username = $email_config['set_smtp_user'];
					$confirmation_mail->Password = $email_config['set_smtp_pass'];
					}
				else
					{
					$confirmation_mail->SMTPAuth = false;
					}
				}
			$confirmation_mail->setFrom($from_address, $from_name);
			$confirmation_mail->addAddress($send_user_email);
			if(is_array($bcc_user_mail))
				{
				foreach($bcc_user_mail as $email)
					$confirmation_mail->addBCC($email, $from_name);
				}
			else
				$confirmation_mail->addBCC($bcc_user_mail, $from_name);
			
			
				
				
				
			$confirmation_mail->Subject = ($user_subject) ? $user_subject : $subject;
			if($email_config['email_content']!='pt')	
				$confirmation_mail->msgHTML($body, dirname(__FILE__));
			else
				$confirmation_mail->Body = strip_tags($body);
			//send the message, check for errors
			 if(is_array($set_emails))
					{
					if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('user',$set_emails) )
						$confirmation_mail->addAttachment($pdf_attached_path);
					}
			if($send_email)
				{
				if (!$confirmation_mail->send())
					{
					echo '<div class="alert alert-danger"><strong>'.__('Confirmation Mailer Error:','nex-forms').'</strong> ' . $confirmation_mail->ErrorInfo.'</div>';
					} 
				}
			}
		
		/** ADMIN EMAIL ************************************************/
		if($send_admin_email=='true')
			{
			$mail = new PHPMailer;
			//$mail->SMTPDebug = 2;
			$mail->CharSet = "UTF-8";
			$mail->Encoding = "base64";
			//$mail->Debugoutput = 'html';
			if($email_config['email_content']=='pt')
				$mail->IsHTML(false);
			
			//Tell PHPMailer to use SMTP
			if($email_config['email_method']=='smtp')
				{
				$mail->isSMTP();
				$mail->Host = $email_config['smtp_host'];
				$mail->Port = ($email_config['mail_port']) ? $email_config['mail_port'] : 587;
				
				
				//Whether to use SMTP authentication
				if($email_config['smtp_auth']=='1')
					{
					$mail->SMTPAuth = true;
					if($email_config['email_smtp_secure']!='0')
						$mail->SMTPSecure  = $email_config['email_smtp_secure']; //Secure conection
					$mail->Username = $email_config['set_smtp_user'];
					$mail->Password = $email_config['set_smtp_pass'];
					}
				else
					{
					$mail->SMTPAuth = false;
					}
				}
			
			if(is_array($reply_to))
				{
				foreach($reply_to as $add_reply)
					$mail->AddReplyTo($add_reply, $from_name);
				}
			else if($reply_to)
				$mail->AddReplyTo($reply_to, $from_name);
				
			else
				{
				if($send_user_email)
					$mail->AddReplyTo($send_user_email, '');
				}
			
				$mail->setFrom($from_address, $from_name);
			//BCC
			if(is_array($bcc))
				{
				foreach($bcc as $email)
					$mail->addBCC($email, $from_name);
				}
			else
				$mail->addBCC($bcc, $from_name);	
			//CC	
			if(is_array($mail_to))
				{
				foreach($mail_to as $email){
					$mail->addCC($email, $from_name);
					}
				}
			else
				$mail->AddAddress($mail_to, $from_name);
		
			$mail->Subject = $subject;
			
			if($email_config['email_content']!='pt')	
				$mail->msgHTML($admin_body, dirname(__FILE__));
			else
				$mail->Body = strip_tags($admin_body);
			if($attach_to_admin_email=='true')
				{
				if(is_array($files))
					{
					for($x = 0; $x < count($files); $x++)
						{  
						$file = fopen($files[$x],"r");  
						$content = fread($file,filesize($files[$x]));  
						fclose($file);  
						$content = chunk_split(base64_encode($content));  
						$mail->addAttachment($files[$x]);
						} 
					}
				}
				 if(is_array($set_emails))
					{
					if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
						$mail->addAttachment($pdf_attached_path);
					}
				
				if($send_email)
					{
					if(!$mail->send())	
						{
						echo '<div class="alert alert-danger"><strong>'.$reply_to.__('Admin Mailer Error:','nex-forms').'</strong> ' . $mail->ErrorInfo.'</div>';
						}
					}
			}
		}
	
		/**************************************************/
		/** NORMAL PHP ************************************/
		/**************************************************/
		else if($email_config['email_method']=='php')
			{
			
			$headers = 'From: '.$from_address;   
			$time = md5(time());  
			$boundary = "==Multipart_Boundary_x{$time}x";  
			$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$boundary}\"";
			$message = "--{$boundary}\n" . "Content-type: ".((($email_config['email_content']=='html')) ? 'text/html' : 'text/plain')."; charset=UTF-8\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
			$message .= "--{$boundary}\n";  
			 if(is_array($set_emails))
			 	{
			 	if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
					$files[] = $pdf_attached_path; 
				}
			  
			// attach the attachments to the message  
			if($attach_to_admin_email=='true')
				{
				if(is_array($files))
				 	{
					for($x = 0; $x < count($files); $x++){  
						$file = fopen($files[$x],"r");  
						$content = fread($file,filesize($files[$x]));  
						fclose($file);  
						$content = chunk_split(base64_encode($content));  
						$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" . "Content-Disposition: attachment;\n" . " filename=\"$filenames[$x]\"\n" . "Content-Transfer-Encoding: base64\n\n" . $content . "\n\n";  
						$message .= "--{$boundary}\n";  
						} 
					}
				}
		if($send_email)
			{
			if($send_admin_email=='true')
				{
				if(is_array($mail_to))
					{
					foreach($mail_to as $email)
						mail($email,$subject,$message,$headers);
					}
				else
					mail($mail_to,$subject,$message,$headers);
				
				
				if(is_array($bcc))
					{
					foreach($bcc as $email)
						mail($email,$subject,$message,$headers);
					}
				else
					mail($bcc,$subject,$message,$headers);
				
				}
			
			$headers2  = 'MIME-Version: 1.0' . "\r\n";
			$headers2 .= 'Content-Type: '.(($email_config['email_content']=='html') ? 'text/html' : 'text/plain').'; charset=UTF-8\n\n'. "\r\n";
			$headers2 .= 'From: '.$from_name.' <'.$from_address.'>' . "\r\n";
			if($_REQUEST[$form_attr->user_email_field])
				mail(esc_html($_REQUEST[$form_attr->user_email_field]),$subject,$body,$headers2);
			}
		}
	
		/**************************************************/
		/** WORDPRESS MAIL ********************************/
		/**************************************************/	
		else if($email_config['email_method']=='wp_mailer' || $email_config['email_method']=='api')
			{
			 $attach_pdf = array();
			 if(is_array($set_emails))
			 	{
				
				if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
					$files[] = $pdf_attached_path; 
					$attach_pdf[] = $pdf_attached_path;
				}
			
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: '.$from_name.' <'.$from_address.'>';
			
			if($reply_to)
				$headers[] = 'Reply-To: <'.$reply_to.'>';
			else
				{
				if($send_user_email)
					$headers[] = 'Reply-To: <'.$send_user_email.'>';
				else
					$headers[] = 'Reply-To: <'.$from_address.'>';
				}
			
			if($send_email)
				{
				if($send_admin_email=='true')
					{
					if(is_array($mail_to))
						{
						foreach($mail_to as $email)
							{
							if($attach_to_admin_email=='true')
								wp_mail($email,$subject,$admin_body,$headers, $files);
							else
								wp_mail($email,$subject,$admin_body,$headers);
							}
						}
					else
						{
						if($attach_to_admin_email=='true')
							{
							wp_mail($mail_to,$subject,$admin_body,$headers, $files);
							}
						else
							{
							if(is_array($set_emails))
								{
								if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
									wp_mail($mail_to,$subject,$admin_body,$headers, $attach_pdf);
								else
									wp_mail($mail_to,$subject,$admin_body,$headers);
								}
							else
								wp_mail($mail_to,$subject,$admin_body,$headers);
							}
						}
					
					
					if($bcc)
						{
						if(is_array($bcc))
							{
							foreach($bcc as $email)
								{
								if($attach_to_admin_email=='true')
									wp_mail($email,$subject,$admin_body,$headers, $files);
								else
									{
									if(is_array($set_emails))
										{
										if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
											wp_mail($mail_to,$subject,$admin_body,$headers, $attach_pdf);
										else
											wp_mail($mail_to,$subject,$admin_body,$headers);
										}
									else
										wp_mail($mail_to,$subject,$admin_body,$headers);
									}
								}
							}
						else
							{
							if($attach_to_admin_email=='true')
								wp_mail($bcc,$subject,$admin_body,$headers, $files);
							else
								{
								if(is_array($set_emails))
									{
									if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('admin',$set_emails) )
										wp_mail($mail_to,$subject,$admin_body,$headers, $attach_pdf);
									else
										wp_mail($mail_to,$subject,$admin_body,$headers);
									}
								else
									wp_mail($mail_to,$subject,$admin_body,$headers);
								}
							}
						}
					}
				
				if($_REQUEST[$form_attr->user_email_field])
					{
					if(is_array($set_emails))
						{
						if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('user',$set_emails) )
							{
							$attach_pdf[] = $pdf_attached_path; 
							wp_mail($_REQUEST[$form_attr->user_email_field],$user_subject,$body,$headers, $attach_pdf);
							}
						else
							wp_mail($_REQUEST[$form_attr->user_email_field],$user_subject,$body,$headers);
						}
					else
						wp_mail($_REQUEST[$form_attr->user_email_field],$user_subject,$body,$headers);
					}
					
				if($bcc_user_mail)
					{
					if(is_array($bcc_user_mail))
						{
						foreach($bcc_user_mail as $email)
							{
							if(is_array($set_emails))
								{
								if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('user',$set_emails) )
									{
									$attach_pdf[] = $pdf_attached_path; 
									wp_mail($email,$user_subject,$body,$headers, $attach_pdf);
									}
								else
									wp_mail($email,$user_subject,$body,$headers);
								}
							else
								wp_mail($email,$user_subject,$body,$headers);
							}
						}
					else
						{
						if(is_array($set_emails))
							{
							if ( (function_exists('NEXForms_export_to_PDF')) &&  in_array('user',$set_emails) )
								{
								$attach_pdf[] = $pdf_attached_path; 
								wp_mail($bcc_user_mail,$user_subject,$body,$headers, $attach_pdf);
								}
							else
								wp_mail($bcc_user_mail,$user_subject,$body,$headers);
							}
							else
								wp_mail($bcc_user_mail,$user_subject,$body,$headers);
						}
					}
				}
			}
		/**************************************************/
		/** NO MAIL ***************************************/
		/**************************************************/
		else
			{
			echo __('ERROR: No Mail Method Config Setup->'.$email_config['email_method'],'nex-forms');
			}
		
		
		
		$upload_settings = json_decode($form_attr->upload_settings, true);
				
		$upload_to_server 	= ($upload_settings['0']['upload_to_server']) ? $upload_settings['0']['upload_to_server'] 	: 'true';
		
		if($upload_to_server=='false')
			{
			 if(is_array($files))
			 	{
				for($x = 0; $x < count($files); $x++)
					{   
					unlink($files[$x]);
					} 
				}
			}
		
		
		
		
		
		if($_POST['resend_email']=='1')
			die();
	}
}


function nf_prefix_register_resources(){
	
	$config = new NEXForms5_Config();
	
	$css_js_version = $config->plugin_version.'.11';
	
	$script_config = get_option('nex-forms-script-config');
	$styles_config = get_option('nex-forms-style-config');
	$other_config = get_option('nex-forms-other-config');
	$output = '';
/* UI STYLE REGISTRATION */		
	if($styles_config['incstyle-font-awesome']=='1' || !array_key_exists('incstyle-font-awesome',$styles_config))
		{
		wp_register_style('nex-forms-font-awesome-5',plugins_url( '/public/css/fa5/css/all.min.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-font-awesome-4-shims',plugins_url( '/public/css/fa5/css/v4-shims.min.css',__FILE__),'',$css_js_version);
		}
	if($styles_config['incstyle-bootstrap']=='1' || !array_key_exists('incstyle-bootstrap',$styles_config))
		wp_register_style('nex-forms-bootstrap-ui', plugins_url( '/public/css/min/ui-bootstrap.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-ui', plugins_url( '/public/css/'.NF_PATH.'ui.css?v=7.2.7',__FILE__),'',$css_js_version);
		
	if($styles_config['incstyle-animations']=='1' || !array_key_exists('incstyle-animations',$styles_config))
		wp_register_style('nex-forms-animations', plugins_url( '/public/css/min/animate.css',__FILE__),'',$css_js_version);		

		wp_register_style('nex-forms-material-theme-amber', plugins_url( '/public/css/themes/amber.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-blue-gray', plugins_url( '/public/css/themes/blue-gray.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-blue', plugins_url( '/public/css/themes/blue.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-brown', plugins_url( '/public/css/themes/brown.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-cyan', plugins_url( '/public/css/themes/cyan.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-deep-purple', plugins_url( '/public/css/themes/deep-purple.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-default', plugins_url( '/public/css/themes/default.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-gray', plugins_url( '/public/css/themes/gray.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-green', plugins_url( '/public/css/themes/green.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-indigo', plugins_url( '/public/css/themes/indigo.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-light-blue', plugins_url( '/public/css/themes/light-blue.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-light-green', plugins_url( '/public/css/themes/light-green.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-lime', plugins_url( '/public/css/themes/lime.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-orange', plugins_url( '/public/css/themes/orange.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-pink', plugins_url( '/public/css/themes/pink.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-purple', plugins_url( '/public/css/themes/purple.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-red', plugins_url( '/public/css/themes/red.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-teal', plugins_url( '/public/css/themes/teal.css',__FILE__),'',$css_js_version);
		wp_register_style('nex-forms-material-theme-yellow', plugins_url( '/public/css/themes/yellow.css',__FILE__),'',$css_js_version);
		
		
		//wp_register_style('nex-forms-selects-css',  plugins_url( '/public/css/min/nex-select.css',__FILE__),'',$css_js_version);	
		
		
		wp_register_style('nex-forms-materialize', plugins_url( '/public/css/min/materialize-ui.css',__FILE__),'',$css_js_version);	
		$extra_script = '';
		if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
			{
			$extra_script .= 'var get_wow = "enabled";';
			}
		else
			$extra_script .= 'var get_wow = "disabled";';
		if($script_config['inc-raty']=='1' || !$script_config['inc-raty'])
			{
			$extra_script .= 'var get_raty = "enabled";';
			}
		else
			$extra_script .= 'var get_raty = "disabled";';
		
		wp_add_inline_script('nex-forms-bootstrap.min', $extra_script);

/* UI SCRIPT REGISTRATION */
	if($script_config['inc-bootstrap']=='1' || !$script_config['inc-bootstrap'])
		wp_register_script('nex-forms-bootstrap.min',  plugins_url( '/public/js/min/bs.min.js',__FILE__),'',$css_js_version);
	if($script_config['inc-wow']=='1' || !$script_config['inc-wow'])
		wp_register_script('nex-forms-wow',  plugins_url( '/libs/wow.min.js',__FILE__),'',$css_js_version);
	if($script_config['inc-raty']=='1' || !$script_config['inc-raty'])
		wp_register_script('nex-forms-raty-fa',  plugins_url( '/public/js/min/jquery.raty-fa.js',__FILE__),'',$css_js_version);
		
	
	//wp_register_script('nex-forms-selects-js',  plugins_url( '/libs/nex-select.js',__FILE__),'',$css_js_version);	
	wp_register_script('nex-forms-var',  plugins_url( '/public/js/min/var.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-input-mask',  plugins_url( '/public/js/min/jquery.inputmask.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-sigs',  plugins_url( '/public/js/min/jquery.sig.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-tags',  plugins_url( '/public/js/min/jquery.tags.js',__FILE__),'',$css_js_version);
	
	wp_register_script('nex-forms-timer',  plugins_url( '/public/js/min/jquery.timer.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-materialize.min',  plugins_url( '/libs/materialize.min.js',__FILE__),'',$css_js_version);
	
	wp_register_script('nex-forms-modal',  plugins_url( '/libs/nf-modal.js',__FILE__),'',$css_js_version);
	
	wp_register_script('nex-forms-onload', plugins_url( '/public/js/'.NF_PATH.'nexf-onload-ui.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-math.min',  plugins_url( '/libs/math.min.js',__FILE__),'',$css_js_version);
	
	wp_register_script('nex-forms-moment.min', plugins_url( '/libs/moment.min.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-locales.min', plugins_url( '/libs/moment.min.js',__FILE__),'',$css_js_version);
	wp_register_script('nex-forms-bootstrap-datetimepicker', plugins_url( '/public/js/'.NF_PATH.'bootstrap-datetimepicker.js',__FILE__),'',$css_js_version);
	
	wp_register_script('nex-forms-bootstrap-touchspin', plugins_url( '/public/js/min/jquery.bootstrap-touchspin.js',__FILE__),'',$css_js_version);
}
add_action( 'init', 'nf_prefix_register_resources' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'nf_add_action_links', 10, 2 );

function nf_action_links( $actions, $plugin_file, $action_links = array(), $position = 'after' ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin === $plugin_file && ! empty( $action_links ) ) {
			foreach ( $action_links as $key => $value ) {
				$link = array( $key => '<a href="' . $value['url'] . '">' . $value['label'] . '</a>' );
				if ( 'after' === $position ) {
					$actions = array_merge( $actions, $link );
				} else {
					$actions = array_merge( $link, $actions );
				}
			}//foreach
		}// if
		return $actions;
	}


function nf_add_action_links( $actions, $plugin_file ) {
		if ( ! is_array( $actions ) ) {
			return $actions;
		}

		$action_links           = array();
		$action_links           = array(

			'support'    => array(
				'label' => __( 'Support', 'nex-forms' ),
				'url'   => 'https://basix.ticksy.com',
			),
			'docs'     => array(
				'label' => __( 'Documentation', 'nex-forms' ),
				'url'   => 'http://basixonline.net/nex-forms-docs',
			),
		);

		unset( $actions['edit'] );
		$database_actions = new NEXForms_Database_Actions();
		if ( !get_option('nf_activated') ) {
			$action_links['pro_upgrade'] =
				array(
					'label' => __( 'Upgrade to Pro', 'nex-forms' ),
					'url'   => 'https://1.envato.market/zQ6de',

				);
		}

		return nf_action_links( $actions, $plugin_file, $action_links, 'before' );
	}
	
	

add_action('wp_dashboard_setup', 'nex_forms_dashboard_widgets');
 
function nex_forms_dashboard_widgets() {
global $wp_meta_boxes;
 if ( !get_option('nf_activated') ) {
	wp_add_dashboard_widget('nex_forms_widget', 'NEX-Forms Special', 'nex_forms_dashboard');
 }
}
 
function nex_forms_dashboard() {
echo '<center>Buy NEX-Forms Today and get <br /><strong>14 premium add-ons worth $270 absolutely FREE</strong>. <br /><br />This offer includes lifetime free updates for NEX-Forms and your free add-ons!<br /><br /><a href="https://1.envato.market/zQ6de" class="button button-primary button-hero" style="width:100%"><strong>Buy NEX-Forms today and SAVE $270</strong></a><br /><br /><strong>FREE Add-ons Include:<br></strong>PayPal PRO &bull; PDF Creator &bull; Digital Signatures &bull; Zapier &bull; Form Themes &bull; Form to Post/Page &bull; Conditional Content Blocks &bull; Shorcode Processor &bull; PayPal Classic &bull; Super Select Form Fields &bull; MailChimp &bull; MailPoet &bull; Mailster &bull; GetResponse</center>';
}







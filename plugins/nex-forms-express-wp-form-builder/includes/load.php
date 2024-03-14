<?php
if ( ! defined( 'ABSPATH' ) ) exit;

ini_set('display_errors', '0');
error_reporting(0);
include_once( 'classes/class.install.php');
include_once( 'classes/class.db.php');
include_once( 'classes/class.functions.php');
include_once( 'classes/class.export.php');
include_once( 'classes/class.icons.php');
include_once( 'classes/class.googlefonts.php');
include_once( 'classes/class.dashboard.php');
include_once( 'classes/class.builder.php');
include_once( 'classes/class.preferences.php');
/*if(!get_option('rename_nex_forms'))
	{
	rename(plugin_dir_path( dirname(__FILE__)).'main.php',plugin_dir_path( dirname(__FILE__)).'nex-forms.php');
	$active_plugins = get_option('active_plugins');
	$active_plugins = str_replace('nex-forms8/main.php','nex-forms8/nex-forms.php',$active_plugins);
	$active_plugins = str_replace('nex-forms/main.php','nex-forms/nex-forms.php',$active_plugins);
	$active_plugins = str_replace('nex-forms-express/main.php','nex-forms-express/nex-forms.php',$active_plugins);
	update_option('active_plugins', $active_plugins);
	update_option('rename_nex_forms', 1);
	echo 'test';
	}*/

add_action( 'init', 'nf_prefix_register_admin_resources' );



function nf_prefix_register_admin_resources(){
	
	 $js_version = '8.5.7.1';
	
	wp_register_script('nex-forms-timer',plugins_url('/public/js/min/jquery.timer.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-admin-functions',plugins_url('/admin/js/'.NF_PATH.'admin-functions.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-charts',plugins_url( '/admin/js/min/chart.min.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-materialize.min',plugins_url('/libs/materialize.min.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-dashboard',plugins_url('/admin/js/'.NF_PATH.'dashboard.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-bootstrap-admin',plugins_url('/admin/js/min/bootstrap-admin.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-builder',plugins_url('/admin/js/'.NF_PATH.'builder.js',dirname(__FILE__)),'',$js_version);		
	wp_register_script('nex-forms-field-settings-recall',plugins_url('/admin/js/'.NF_PATH.'field-settings-recall.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-field-settings',plugins_url('/admin/js/'.NF_PATH.'field-settings.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-admin-tour',plugins_url('/admin/js/'.NF_PATH.'tour.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-tinymce',includes_url( '/js/tinymce/tinymce.min.js'),'',$js_version);
	wp_register_script('nex-forms-drag-and-drop',plugins_url('/admin/js/drag-and-drop.js',dirname(__FILE__)),'',$js_version);

	//FRONT+BACK
	  
	// BS DATETIME
	wp_register_script('nex-forms-locales.min', plugins_url('/libs/locales.min.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-moment.min', plugins_url('/libs/moment.min.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-date-time',plugins_url('/public/js/'.NF_PATH.'bootstrap-datetimepicker.js',dirname(__FILE__)),'',$js_version);
	
	wp_register_script('nex-forms-raty',plugins_url('/public/js/min/jquery.raty-fa.js',dirname(__FILE__)),'',$js_version);
	wp_register_script('nex-forms-fields',plugins_url('/admin/js/min/fields.js',dirname(__FILE__)),'',$js_version);

	wp_register_script('nex-forms-bootstrap.touchspin', plugins_url( '/public/js/min/jquery.bootstrap-touchspin.js',dirname(__FILE__)),'',$js_version);

	wp_register_style('nex-forms-material-theme-amber', plugins_url( '/public/css/themes/amber.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-blue-gray', plugins_url( '/public/css/themes/blue-gray.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-blue', plugins_url( '/public/css/themes/blue.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-brown', plugins_url( '/public/css/themes/brown.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-cyan', plugins_url( '/public/css/themes/cyan.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-deep-purple', plugins_url( '/public/css/themes/deep-purple.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-default', plugins_url( '/public/css/themes/default.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-gray', plugins_url( '/public/css/themes/gray.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-green', plugins_url( '/public/css/themes/green.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-indigo', plugins_url( '/public/css/themes/indigo.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-light-blue', plugins_url( '/public/css/themes/light-blue.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-light-green', plugins_url( '/public/css/themes/light-green.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-lime', plugins_url( '/public/css/themes/lime.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-orange', plugins_url( '/public/css/themes/orange.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-pink', plugins_url( '/public/css/themes/pink.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-purple', plugins_url( '/public/css/themes/purple.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-red', plugins_url( '/public/css/themes/red.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-teal', plugins_url( '/public/css/themes/teal.css',dirname(__FILE__)),'',$js_version);
	wp_register_style('nex-forms-material-theme-yellow', plugins_url( '/public/css/themes/yellow.css',dirname(__FILE__)),'',$js_version);	
}




function enqueue_nf_admin_scripts($hook) {
	
	//echo '##########'.$hook;
	
	$js_version = '8.5.7.1';
	
	wp_enqueue_script('jquery');
	wp_enqueue_style('jquery-ui');
	
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-spinner');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-form');
	wp_enqueue_script('jquery-widget');
	
	wp_enqueue_script('nex-forms-tinymce');
	
	

	// Custom Includes 
	if($hook=='toplevel_page_nex-forms-dashboard' || strstr($hook,'nex-forms-page'))
		{
		wp_enqueue_script('nex-forms-admin-functions');
		wp_enqueue_script('nex-forms-bootstrap-admin');
		wp_enqueue_script('nex-forms-charts');
		wp_enqueue_script('nex-forms-materialize.min');
		wp_enqueue_script('nex-forms-dashboard');
		
		$user_config = get_user_option('nex-forms-user-config',get_current_user_id());
		
		$theme = wp_get_theme();
		
		if($theme->Name=='NEX-Forms Demo')
			{
			wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/nf-light.css',dirname(__FILE__)),'',$js_version);	
			}
		else
			{
		
			if($user_config['enable-color-adapt']=='2')
				wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/nf-light.css',dirname(__FILE__)),'',$js_version);
			else if($user_config['enable-color-adapt']=='3')
				wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/nf-dark.css',dirname(__FILE__)),'',$js_version);
			else
				wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/'.get_user_option( 'admin_color' ).'.css',dirname(__FILE__)),'',$js_version);
			}
		}

		$form = isset($_REQUEST['open_form']) ? true : false;
		
	if($hook=='admin_page_nex-forms-builder' || $hook=='nex-forms_page_nex-forms-builder')
		{
		if($form)
			{
			wp_enqueue_script('nex-forms-timer');
			wp_enqueue_script('nex-forms-admin-functions');
			wp_enqueue_script('nex-forms-bootstrap-admin');
			wp_enqueue_script('nex-forms-builder');		
			wp_enqueue_script('nex-forms-materialize.min');
			wp_enqueue_script('nex-forms-field-settings');
			wp_enqueue_script('nex-forms-field-settings-recall');
			wp_enqueue_script('nex-forms-admin-tour');
			wp_enqueue_script('nex-forms-drag-and-drop');
			//FRONT+BACK
			
			// BS DATETIME
			
			wp_enqueue_script('nex-forms-moment.min');
			wp_enqueue_script('nex-forms-locales.min'); 
			wp_enqueue_script('nex-forms-date-time');
			
			wp_enqueue_script('nex-forms-raty');
			wp_enqueue_script('nex-forms-fields');
			
			wp_enqueue_script('nex-forms-bootstrap.touchspin');
			
			wp_enqueue_style('wp-codemirror');
			$cm_settings1['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/javascript'));
		
		
			wp_localize_script('nex-forms-admin-functions', 'cm_settings_1', $cm_settings1);
			
			$cm_settings2['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/javascript'));
			wp_localize_script('nex-forms-admin-functions', 'cm_settings_2', $cm_settings2);
			
			
			$cm_settings3['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
			wp_localize_script('nex-forms-admin-functions', 'cm_settings_3', $cm_settings3);
			
			}
		}
}

function enqueue_nf_admin_styles($hook) {
	// CSS 
	
	$js_version = '8.5.7.1';
	
	if(strstr($hook,'nex-forms'))
		{
		wp_enqueue_style('nex-forms-overrides',plugins_url('/admin/css/'.NF_PATH.'overrides.css',dirname(__FILE__)),'',$js_version);
		
		
		$user_config = get_user_option('nex-forms-user-config',get_current_user_id());
		
		if($user_config['enable-color-adapt']=='2')
			wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/nf-light.css',dirname(__FILE__)),'',$js_version);
		else if($user_config['enable-color-adapt']=='3')
			wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/nf-dark.css',dirname(__FILE__)),'',$js_version);
		else
			wp_enqueue_style('nex-forms-admin-color-adapt',  plugins_url( '/admin/css/color_adapt/'.get_user_option( 'admin_color' ).'.css',dirname(__FILE__)),'',$js_version);
		
		//wp_enqueue_style('nex-forms-overall-styles',plugins_url('/admin/css/'.NF_PATH.'nex-forms.css',dirname(__FILE__)),'',$js_version);	
		}
	
	if($hook=='toplevel_page_nex-forms-dashboard' || strstr($hook,'nex-forms-page'))
		{
		wp_enqueue_style('nex-forms-dashboard',plugins_url('/admin/css/'.NF_PATH.'dashboard.css',dirname(__FILE__)),'',$js_version);	
		}
	
	
	if($hook=='nex-forms_page_nex-forms-page-analytics')
		{
		wp_enqueue_style('nex-forms-dashboard',plugins_url('/admin/css/'.NF_PATH.'dashboard.css',dirname(__FILE__)),'',$js_version);	
		wp_enqueue_style('nex-forms-entries',plugins_url('/admin/css/'.NF_PATH.'entries.css',dirname(__FILE__)),'',$js_version);	
		}
	if($hook=='nex-forms_page_nex-forms-page-reporting')
		{
		wp_enqueue_style('nex-forms-dashboard',plugins_url('/admin/css/'.NF_PATH.'dashboard.css',dirname(__FILE__)),'',$js_version);	
		wp_enqueue_style('nex-forms-entries',plugins_url('/admin/css/'.NF_PATH.'entries.css',dirname(__FILE__)),'',$js_version);	
		}
	
	if($hook=='nex-forms_page_nex-forms-page-submissions')
		{
		wp_enqueue_style('nex-forms-dashboard',plugins_url('/admin/css/'.NF_PATH.'dashboard.css',dirname(__FILE__)),'',$js_version);	
		wp_enqueue_style('nex-forms-entries',plugins_url('/admin/css/'.NF_PATH.'entries.css',dirname(__FILE__)),'',$js_version);	
		}
	if($hook=='nex-forms_page_nex-forms-page-file-uploads')
		{
		wp_enqueue_style('nex-forms-dashboard',plugins_url('/admin/css/'.NF_PATH.'dashboard.css',dirname(__FILE__)),'',$js_version);	
		wp_enqueue_style('nex-forms-entries',plugins_url('/admin/css/'.NF_PATH.'entries.css',dirname(__FILE__)),'',$js_version);	
		}
	
	if($hook=='nex-forms_page_nex-forms-page-global-settings')
		{
		wp_enqueue_style('nex-forms-materialize.min',plugins_url('/public/css/min/materialize-ui.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-material-theme-light-blue','',$js_version);
		}
	
	if($hook=='toplevel_page_nex-forms-dashboard' || strstr($hook,'nex-forms-page'))
		{
		wp_enqueue_style('nex-forms-bootstrap.min',plugins_url('/admin/css/min/bootstrap.min.css',dirname(__FILE__)),'',$js_version);
		//FRONT+BACK
		wp_enqueue_style('nex-forms-font-awesome-5',plugins_url('/public/css/fa5/css/all.min.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-font-awesome-4-shims',plugins_url('/public/css/fa5/css/v4-shims.min.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-animations',plugins_url('/public/css/min/animate.css',dirname(__FILE__)),'',$js_version);
		//wp_enqueue_style('nex-forms-ui',plugins_url('/public/css/'.NF_PATH.'ui.css',dirname(__FILE__)),'',$js_version);
		}
	if($hook=='admin_page_nex-forms-builder' || $hook=='nex-forms_page_nex-forms-builder')
		{
		wp_enqueue_style('nex-forms-builder',plugins_url('/admin/css/'.NF_PATH.'builder.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-bootstrap.min',plugins_url('/admin/css/min/bootstrap.min.css',dirname(__FILE__)),'',$js_version);
		
		//FRONT+BACK
		wp_enqueue_style('nex-forms-animate',plugins_url('/public/css/min/animate.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-ui',plugins_url('/public/css/'.NF_PATH.'ui.css',dirname(__FILE__)),'',$js_version);
		
		wp_enqueue_style('nex_forms-font-awesome-5',plugins_url('/public/css/fa5/css/all.min.css',dirname(__FILE__)),'',$js_version);
		wp_enqueue_style('nex-forms-font-awesome-4-shims',plugins_url('/public/css/fa5/css/v4-shims.min.css',dirname(__FILE__)),'',$js_version);
		
		wp_enqueue_style('nex-forms-materialize.min',plugins_url('/public/css/min/materialize-ui.css',dirname(__FILE__)),'',$js_version);

		wp_enqueue_style('nex-forms-material-theme-amber','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-blue-gray','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-blue','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-brown','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-cyan','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-deep-purple','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-default','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-gray','',$js_version); 
		wp_enqueue_style('nex-forms-material-theme-green','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-indigo','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-light-blue','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-light-green','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-lime','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-orange','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-pink','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-purple','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-red','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-teal','',$js_version);
		wp_enqueue_style('nex-forms-material-theme-yellow','',$js_version);	
		
		add_action('admin_bar_menu', 'custom_toolbar_link', 999);
		}
}
add_action( 'admin_enqueue_scripts', 'enqueue_nf_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'enqueue_nf_admin_styles' );

function custom_toolbar_link($wp_admin_bar) {
    
	$theme = wp_get_theme();
	if($theme->Name=='NEX-Forms Demo')
		{
		$args = array(
			'id' => 'wpbeginner',
			'title' => '<span class="ab-icon"><span class="dashicons-before dashicons-external"></span></span><span class="ab-label">NF DEMO FORM - Test Page</span>', 
			'href' => 'http://basixonline.net/nex-forms-admin-demo/?form_Id='.$_GET['open_form'].'', 
			'meta' => array(
				'class' => 'nf-test-page', 
				'title' => 'Demo Form Test Page'
				)
		);
    	$wp_admin_bar->add_node($args);
		}
}
?>
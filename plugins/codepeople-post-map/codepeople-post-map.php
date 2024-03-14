<?php
/*
Plugin Name: Google Maps CP
Text Domain: codepeople-post-map
Version: 1.1.5
Author: CodePeople
Author URI: http://wordpress.dwbooster.com/content-tools/codepeople-post-map
Plugin URI: http://wordpress.dwbooster.com/content-tools/codepeople-post-map
Text Domain: codepeople-post-map
Description: Google Maps CP Allows to associate geocode information to posts and display it on map. Google Maps CP display the post list as markers on map. The scale of map is determined by the markers, to display distant points is required to load a map with smaller scales. To get started: 1) Click the "Activate" link to the left of this description. 2) Go to your <a href="options-general.php?page=codepeople-post-map.php">Google Maps CP configuration</a> page and configure the maps settings. 3) Go to post edition page to enter the geolocation information.
 */

 // Feedback system
require_once 'feedback/cp-feedback.php';
new CP_FEEDBACK(plugin_basename( dirname(__FILE__) ), __FILE__, 'https://wordpress.dwbooster.com/contact-us');

define('CPM_PLUGIN_DIR', WP_PLUGIN_DIR."/".dirname(plugin_basename(__FILE__)));
define('CPM_PLUGIN_URL', plugins_url()."/".dirname(plugin_basename(__FILE__)));

require (CPM_PLUGIN_DIR.'/include/functions.php');
add_filter('option_sbp_settings', array('CPM', 'troubleshoot'));

// Redirecting the user to the settings page of the plugin
add_action( 'activated_plugin', 'cpm_redirect_to_settings', 10, 2 );
if(!function_exists('cpm_redirect_to_settings'))
{
	function cpm_redirect_to_settings($plugin, $network_activation)
	{
		if(
			$plugin == plugin_basename( __FILE__ ) &&
			(!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
			(!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected')
		)
		{
			exit( wp_redirect( admin_url( 'options-general.php?page=codepeople-post-map.php' ) ) );
		}
	}
}

// Create  a CPM object that contain main plugin logic
add_action( 'init', 'cpm_init');
add_action( 'admin_init', 'cpm_admin_init' );

register_activation_hook(__FILE__, 'codepeople_post_map_regiter');

if(!function_exists('codepeople_post_map_regiter')){
    function codepeople_post_map_regiter(){
        $cpm_master_obj = new CPM;
        $cpm_master_obj->set_default_configuration();
    }
}

function cpm_admin_init(){
	global $cpm_master_obj;

	// Insert the map's insertion form below the posts and pages editor
	$form_title = __('Associate an address to the post for Google Maps association', 'codepeople-post-map');
	add_meta_box('codepeople_post_map_form', $form_title, array($cpm_master_obj, 'insert_form'), 'post', 'normal');
    add_meta_box('codepeople_post_map_form', $form_title, array($cpm_master_obj, 'insert_form'), 'page', 'normal');

	add_action('save_post', array(&$cpm_master_obj, 'save_map'));

	$plugin = plugin_basename(__FILE__);
	add_filter('plugin_action_links_'.$plugin, array(&$cpm_master_obj, 'customizationLink'));

}

function cpm_init(){
	load_plugin_textdomain( 'codepeople-post-map', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	global $cpm_master_obj, $cpm_objs;
	$cpm_master_obj = new CPM;
    $cpm_objs = array();

	if(!is_admin())
	{
		add_shortcode('codepeople-post-map', array(&$cpm_master_obj, 'replace_shortcode'));
		add_action('the_post', 'cpm_populate_points' );
		add_action( 'wp_footer', 'cpm_print_points'  );
		add_action( 'loop_start', 'cpm_loop_start' );
		add_action( 'loop_end', 'cpm_loop_end' );

		add_filter('widget_text', 'do_shortcode');

		$cpm_master_obj->preview();
	}
}

if( !function_exists( 'cpm_loop_start' ) )
    {
        function cpm_loop_start()
        {
            global $cpm_in_loop;
            $cpm_in_loop = true;
        }
    }
if( !function_exists( 'cpm_loop_end' ) )
    {
        function cpm_loop_end()
        {
            global $cpm_in_loop;
            $cpm_in_loop = false;
        }
    }

if( !function_exists( 'cpm_populate_points' ) ){
	function cpm_populate_points( $post ){
		global $cpm_master_obj;
		$cpm_master_obj->populate_points( $post->ID );
	}
}

if( !function_exists( 'cpm_print_points' ) ){
	function cpm_print_points(){
		global $cpm_objs, $cpm_master_obj;

        foreach( $cpm_objs as $cpm_obj ){
            if( !empty( $cpm_obj->multiple ) )
            {
                $cpm_obj->points = $cpm_master_obj->points;
            }
			$cpm_obj->print_points();
		}
	}
}

if (!function_exists("cpm_settings")) {
		function cpm_settings() {
			global $cpm_master_obj;

			if (!isset($cpm_master_obj)) {
				return;
			}

			if (function_exists('add_options_page')) {
				$slug = basename(__FILE__);
				add_options_page('Google Maps CP', 'Google Maps CP', 'manage_options', $slug, array(&$cpm_master_obj, 'settings_page'));

				add_menu_page( 'Google Maps CP', 'Google Maps CP', 'manage_options', $slug, array(&$cpm_master_obj, 'settings_page'));

				add_submenu_page( $slug, 'Online Help', 'Online Help', 'read', 'google_maps_cp_help', array(&$cpm_master_obj, 'settings_page') );

				add_submenu_page( $slug, 'I\'ve a Question', 'I\'ve a Question', 'read', 'google_maps_cp_question', array(&$cpm_master_obj, 'settings_page') );

				add_submenu_page( $slug, 'Upgrade', 'Upgrade', 'read', 'google_maps_cp_upgrade', array(&$cpm_master_obj, 'settings_page') );
			}
		}
	}

add_action('admin_enqueue_scripts', array(&$cpm_master_obj, 'load_admin_resources'), 1);
add_action('enqueue_block_editor_assets', array(&$cpm_master_obj, 'load_gutenberg_code'));
add_action('wp_head', array(&$cpm_master_obj, 'load_header_resources'), 10);
add_action('admin_menu', 'cpm_settings');

?>
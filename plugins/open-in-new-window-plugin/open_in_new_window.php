<?PHP
/*
Plugin Name: Open in New Window Plugin
Plugin URI: https://www.kpgraham.com
Description: Opens external links in a new window, keeping your blog page in the browser so you don't lose surfers to another site.
Version: 2.9
Author: Keith P. Graham
Author URI: https://www.kpgraham.com

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
// just to be absolutely safe
if (!defined('ABSPATH')) exit;

// options page loader and execute
add_action('admin_menu', 'kpg_open_in_new_window_init');	
function kpg_open_in_new_window_control()  {
	oinw_load_control();
	kpg_open_in_new_window_control_2();
}
function oinw_load_control() {
	require_once('includes/oinw_options.php');
}
function kpg_open_in_new_window_init() {
	add_options_page('Open in new window', 'Open in new window', 'manage_options','openinnewwindow','kpg_open_in_new_window_control');
}


// add scripts to page
add_action( 'wp_enqueue_scripts', 'oinw_scripts' );

// get options
function kpg_oinw_get_options() {
	// before we begin we need to check if we need to redirect the options to blog 1
	$opts=get_option('kpg_open_in_new_window_options');
	if (empty($opts)||!is_array($opts)) $opts=array();
	$options=array(
	'checktypes'=>'Y' 
	);
	$ansa=array_merge($options,$opts);
	if ($ansa['checktypes']!='Y') $ansa['checktypes']='N';
	return $ansa;
}// done

function oinw_scripts() {
	$options=kpg_oinw_get_options();
	extract($options);
	$varscript='open_in_new_window_no.js';
	if (!empty($checktypes)&&$checktypes=='Y') {
		$varscript='open_in_new_window_yes.js';
	}
    
	wp_enqueue_script( 'oinw_vars', plugin_dir_url( __FILE__ ) . $varscript, array(), null, false );
	wp_enqueue_script( 'oinw_methods', plugin_dir_url( __FILE__ ) . 'open_in_new_window.js', array(), null, false );
}
?>

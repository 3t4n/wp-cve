<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
add_action("wp_dashboard_setup", "wpa_dashboard_widget");
function wpa_dashboard_widget()
{
    add_meta_box( 'wpa_dashboard_widget', 'WP Armour Anti Spam Statistics', 'wpa_dashboard_widget_function', 'dashboard', 'side', 'high');
}
 
function wpa_dashboard_widget_function(){
    ob_start();
	include('views/wpa_stats_widget.php');
	$widget_content = ob_get_contents();
	ob_end_clean ();
	$widget_content 			= apply_filters( 'wpa_widget_content', $widget_content);
	echo $widget_content;
}
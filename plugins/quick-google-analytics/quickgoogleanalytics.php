<?php
/*
Plugin Name: Quick Google Analytics
Plugin URI: http://www.chefblogger.me
Description: The quick solution for adding your Google Analytics Code into your header.php file - without coding. <a href="options-general.php?page=QGA_quickgoogleanalytics">Configuration</a>
Version: 1.3.3
Author: Eric-Oliver Mächler
Author URI: http://www.ericmaechler.com
Requires at least: 3.5
Tested up to: 6.3.1
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include 'conf.php';

//add css
function quick_google_analytics_admin_styles() {
    // Prüfe, ob wir uns im Backend befinden
        // Pfade zur CSS-Datei deines Plugins anpassen
        wp_enqueue_style('quick-google-analytics', plugins_url('style_backend.css', __FILE__));

}

add_action('admin_enqueue_scripts', 'quick_google_analytics_admin_styles');







$quickgoogleanalytics_select_active = get_option('quickgoogleanalytics_select');

if ($quickgoogleanalytics_select_active == '' OR $quickgoogleanalytics_select_active == '1')
	{
	//ga
//add code for google analytics
include ("shortcode_ga_ua.php");

	}

elseif ($quickgoogleanalytics_select_active == '2') {
	//beide
//add code for google analytics
include ("shortcode_ga_ua.php");

//add code for google analytcs 4
include ("shortcode_ga_g.php");
}

elseif ($quickgoogleanalytics_select_active == '3') {

//add code for google analytcs 4
include ("shortcode_ga_g.php");

}

elseif ($quickgoogleanalytics_select_active == '4') {
	//keine
}




?>
<?php

// no direct access!
defined('ABSPATH') or die("No direct access");

/******************************
* script control
******************************/

function wpgs_load_scripts() {

	global $plugin_version;
	global $version_index_1;
	global $lazy_load;
	global $gsp_widget_id;
	global $s_enc;
	global $h_enc;
	global $hh_enc;

	if($gsp_widget_id == "") {

		wp_enqueue_style('wpgs-styles1', plugin_dir_url( __FILE__ ) . 'css/gspeech.css?g_version=' . $plugin_version);
		wp_enqueue_style('wpgs-styles12', plugin_dir_url( __FILE__ ) . 'css/the-tooltip.css?g_version=' . $plugin_version);
		wp_enqueue_script("jquery");
		wp_enqueue_script('wpgs-script4', plugin_dir_url( __FILE__ ) . 'js/color.js?g_version=' . $plugin_version, array('jquery'));
		wp_enqueue_script('wpgs-script5', plugin_dir_url( __FILE__ ) . 'js/jQueryRotate.2.1.js?g_version=' . $plugin_version, array('jquery'));
		wp_enqueue_script('wpgs-script7', plugin_dir_url( __FILE__ ) . 'js/easing.js?g_version=' . $plugin_version, array('jquery'));
		wp_enqueue_script('wpgs-script6', plugin_dir_url( __FILE__ ) . 'js/mediaelement-and-player.min.js?g_version=' . $plugin_version, array('jquery'));
	}
	else {

		$gsp_index = "gspeech_front_script_n127";

		wp_enqueue_script("jquery");
		wp_enqueue_script('wpgs-script777', plugin_dir_url( __FILE__ ) . 'js/gspeech_front.js?g_indexsp___eq' . $gsp_index . 'gsp___delg_versiongsp___eq' . $plugin_version . 'gsp___delw_idgsp___eq' . $gsp_widget_id . 'gsp___dels_encgsp___eq' . $s_enc . 'gsp___delh_encgsp___eq' . $h_enc . 'gsp___delhh_encgsp___eq' . $hh_enc . 'gsp___dellazy_loadgsp___eq' . $lazy_load .'gsp___delvv_indexgsp___eq' . $version_index_1, array('jquery'));
	}
}

add_action('wp_enqueue_scripts', 'wpgs_load_scripts');

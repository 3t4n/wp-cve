<?php
/*
Plugin Name: Perfect Font Awesome Integration
Description: Perfectly Integrates latest font awesome icons with your wordpress blog as well as adds an Icon insert button in Classic Editor.
Version: 2.2
Author: Kaushik Somaiya
Author URI: https://wporbit.net/
Text Domain: perfect-font-awesome-integration
License: GPL2
*/

define('PFAI_ENGINE',1);
require_once plugin_dir_path( __FILE__ ) . '/admin/admin-page.php';

add_action( 'wp_enqueue_scripts', 'pfai_plugtohead' );

function pfai_plugtohead() {
        wp_enqueue_style( 'pfai-fa-style',plugins_url( '/fontawesome/css/all.css', __FILE__ ));
}

add_action('admin_head', 'pfai_add_fa_tc_button');

function pfai_add_fa_tc_button() {
		add_filter("mce_external_plugins", "pfai_add_tinymce_plugin");
		add_filter('mce_buttons', 'pfai_register_fa_tc_button');
		echo '<style>#toplevel_page_perfect-font-awesome-integration .wp-menu-image img{width: 30px; padding: 2px; opacity: 1;}</style>';
}


function pfai_add_tinymce_plugin($plugin_array) {
   	$plugin_array['pfai_button_script'] = plugins_url( '/fa-button.js', __FILE__ );
   	return $plugin_array;
}



function pfai_register_fa_tc_button($buttons) {
   array_push($buttons, "pfai_button");
   return $buttons;
}

function pfai_shortcode_func( $atts ) {

   extract(shortcode_atts(array(
		'pfaic' => '',
		'pfaicolr' => '#'
	), $atts));

  
    $kicon = '<i style="color:'.$pfaicolr.';" class="' .$pfaic. '"></i>';
 
    return $kicon;
 
}

add_action( 'init', 'pfai_register_shortcode' );
function pfai_register_shortcode() {
    add_shortcode( 'pfai', 'pfai_shortcode_func' );
}

?>
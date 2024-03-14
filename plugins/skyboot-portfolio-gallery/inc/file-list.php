<?php 
if( !defined('ABSPATH') ) exit;

// Include File custom post register
if ( !file_exists('custom-post-register.php') ){
	include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/custom-post-register.php');
}

// Include File For custom tazonomies
if ( !file_exists('custom-tazonomies.php') ){
	include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/custom-tazonomies.php');
}

// Plugins Status
if ( !file_exists('plugin-status.php') ){
	include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/plugin-status.php');
}
// Assests Enqueue
if ( !file_exists('assests-enqueue.php') ){
	include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/assests-enqueue.php');
}

// Elementor Widgets File
function skyboot_portfolio_gallery_elementor_widgets(){
    include_once(SKYBOOT_PORTFOLIO_GALLERY_PLUGIN_DIRECTORY. '/inc/widget/elementor-widgets.php');
}
add_action('elementor/widgets/widgets_registered','skyboot_portfolio_gallery_elementor_widgets');

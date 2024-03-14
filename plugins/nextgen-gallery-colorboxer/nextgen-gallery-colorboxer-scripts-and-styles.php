<?php

/**********************************************************************
* colorbox (modified) stylesheet
**********************************************************************/

function nggcb_colorbox_style() {
	wp_register_style('nggcb_colorbox.css', plugins_url('colorbox/1/colorbox.css', __FILE__), false, NGGCB_COLORBOX_VERSION, 'screen');
	wp_enqueue_style('nggcb_colorbox.css');
}



/**********************************************************************
* colorbox inline js
**********************************************************************/

function nggcb_colorbox_inline_js() {
    global $nggcb_options;

	$nggcb_colorbox_script = '<!-- [nextgen gallery colorboxer] This page must contain a gallery...else we wouldn\'t be serving colorbox\'s scripts and styles -->';
	$nggcb_colorbox_script .= "\n";
	$nggcb_colorbox_script .= '<script type=\'text/javascript\'>';
	$nggcb_colorbox_script .= 'jQuery(document).ready(function($) { jQuery(\'a.mycolorbox\')';
	$nggcb_colorbox_script .= '.colorbox({ opacity:' . $nggcb_options['colorbox_opacity'] . ', ';
	$nggcb_colorbox_script .= 'transition:"' . $nggcb_options['colorbox_transition'] . '" }); });</script>';
	$nggcb_colorbox_script .= "\n";

	echo $nggcb_colorbox_script;

} // close nggcb_colorbox_inline_js



/**********************************************************************
* jquery + deregister duplicates when served to avoid conflicts
**********************************************************************/

function nggcb_load_jquery() {
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/'.NGGCB_JQUERY_VERSION.'/jquery.min.js', false, NGGCB_JQUERY_VERSION);
	wp_enqueue_script('jquery');
}



/**********************************************************************
* colorbox + deregister duplicates when served to avoid conflicts
**********************************************************************/

function nggcb_load_colorbox() {
	wp_deregister_script('colorbox');
	wp_deregister_script('jquery.colorbox');
	wp_deregister_script('jquery-colorbox');
	wp_register_script('jquery.colorbox', plugins_url('colorbox/js/jquery.colorbox-min.js', __FILE__), array('jquery'), NGGCB_COLORBOX_VERSION);
	wp_enqueue_script('jquery.colorbox');
}


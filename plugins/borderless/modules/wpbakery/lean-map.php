<?php

// don't load directly
defined( 'ABSPATH' ) || exit;



$options = get_option('borderless');
$dir = dirname(__FILE__);
$borderless_element_path = $dir . '/elements/';
//require_once $dir . '/custom-default-elements.php';

/*-----------------------------------------------------------------------------------*/
/*	Essencial Elements
/*-----------------------------------------------------------------------------------*/
vc_lean_map( 'borderless_wpbakery_alert', null, $borderless_element_path . 'alert.php' ); 

vc_lean_map( 'borderless_wpbakery_circular_progress_bar', null, $borderless_element_path . 'circular-progress-bar.php' ); 

vc_lean_map( 'borderless_wpbakery_counter', null, $borderless_element_path . 'counter.php' ); 

vc_lean_map( 'borderless_wpbakery_icon', null, $borderless_element_path . 'icon.php' ); 

vc_lean_map( 'borderless_wpbakery_infobox', null, $borderless_element_path . 'infobox.php' ); 

vc_lean_map( 'borderless_wpbakery_pricing', null, $borderless_element_path . 'pricing.php' ); 

vc_lean_map( 'borderless_wpbakery_progress_bar', null, $borderless_element_path . 'progress-bar.php' ); 

vc_lean_map( 'borderless_wpbakery_semi_circular_progress_bar', null, $borderless_element_path . 'semi-circular-progress-bar.php' ); 

vc_lean_map( 'borderless_wpbakery_svg', null, $borderless_element_path . 'svg.php' ); 

vc_lean_map( 'borderless_wpbakery_team_member', null, $borderless_element_path . 'team-member.php' ); 

/*-----------------------------------------------------------------------------------*/
/*	Essencial Nested Elements
/*-----------------------------------------------------------------------------------*/
require_once $borderless_element_path . 'icon-group.php'; 

require_once $borderless_element_path . 'list-group.php'; 

require_once $borderless_element_path . 'modal.php' ; 

require_once $borderless_element_path . 'testimonial.php' ; 

/*-----------------------------------------------------------------------------------*/
/*	Premium Elements
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Premium Nested Elements
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Unlimited Elements
/*-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/*	Unlimited Nested Elements
/*-----------------------------------------------------------------------------------*/
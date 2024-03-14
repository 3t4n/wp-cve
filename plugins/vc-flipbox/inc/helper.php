<?php
// Don't load directly
if (!defined('ABSPATH')){die('-1');}

/**
    BUTTON LAYOUTS
**/
if( !function_exists('tlg_framework_get_button_layouts') ) {
    function tlg_framework_get_button_layouts() {
        return array(
            esc_html__( 'Standard', 'tlg_framework' ) 			=> 'btn btn-filled',
            esc_html__( 'Standard rounded', 'tlg_framework' ) 	=> 'btn btn-filled btn-rounded',
            esc_html__( 'Light', 'tlg_framework' ) 				=> 'btn btn-filled btn-light',
            esc_html__( 'Light rounded', 'tlg_framework' ) 		=> 'btn btn-filled btn-light btn-rounded',
            esc_html__( 'Dark', 'tlg_framework' ) 				=> 'btn btn-filled btn-dark',
            esc_html__( 'Dark rounded', 'tlg_framework' ) 		=> 'btn btn-filled btn-dark btn-rounded',
            esc_html__( 'Line', 'tlg_framework' ) 				=> 'btn',
            esc_html__( 'Line rounded', 'tlg_framework' ) 		=> 'btn btn-rounded',
            esc_html__( 'Text', 'tlg_framework' ) 				=> 'btn-text',
            esc_html__( 'Creative filled', 'tlg_framework' ) 		=> 'btn-new btn-1 btn-1a',
            esc_html__( 'Creative top-bottom', 'tlg_framework' ) 	=> 'btn-new btn-1 btn-1b',
            esc_html__( 'Creative left-right', 'tlg_framework' ) 	=> 'btn-new btn-1 btn-1c',
            esc_html__( 'Creative center', 'tlg_framework' ) 		=> 'btn-new btn-1 btn-1d',
            esc_html__( 'Creative rotate', 'tlg_framework' ) 		=> 'btn-new btn-1 btn-1e',
            esc_html__( 'Creative middle', 'tlg_framework' ) 		=> 'btn-new btn-1 btn-1f',
        );	
    }
}

/**
    HOVER EFFECTS
**/
if( !function_exists('tlg_framework_get_hover_effects') ) {
    function tlg_framework_get_hover_effects() {
        return array(
            esc_html__( 'Default', 'tlg_framework' ) 				=> '',
            esc_html__( 'Grow', 'tlg_framework' ) 					=> 'hvr-grow',
            esc_html__( 'Shrink', 'tlg_framework' ) 				=> 'hvr-shrink',
            esc_html__( 'Push', 'tlg_framework' ) 					=> 'hvr-push',
            esc_html__( 'Pop', 'tlg_framework' ) 					=> 'hvr-pop',
            esc_html__( 'Bounce In', 'tlg_framework' ) 				=> 'hvr-bounce-in',
            esc_html__( 'Bounce Out', 'tlg_framework' ) 			=> 'hvr-bounce-out',
            esc_html__( 'Rotate', 'tlg_framework' ) 				=> 'hvr-rotate',
            esc_html__( 'Grow Rotate', 'tlg_framework' ) 			=> 'hvr-grow-rotate',
            esc_html__( 'Float', 'tlg_framework' ) 					=> 'hvr-float',
            esc_html__( 'Sink', 'tlg_framework' ) 					=> 'hvr-sink',
            esc_html__( 'Bob', 'tlg_framework' ) 					=> 'hvr-bob',
            esc_html__( 'Push', 'tlg_framework' ) 					=> 'hvr-push',
            esc_html__( 'Pop', 'tlg_framework' ) 					=> 'hvr-pop',
            esc_html__( 'Hang', 'tlg_framework' ) 					=> 'hvr-hang',
            esc_html__( 'Skew', 'tlg_framework' ) 					=> 'hvr-skew',
            esc_html__( 'Skew Forward', 'tlg_framework' ) 			=> 'hvr-skew-forward',
            esc_html__( 'Skew Backward', 'tlg_framework' ) 			=> 'hvr-skew-backward',
            esc_html__( 'Wobble Horizontal', 'tlg_framework' ) 		=> 'hvr-wobble-horizontal',
            esc_html__( 'Wobble Vertical', 'tlg_framework' ) 		=> 'hvr-wobble-vertical',
            esc_html__( 'Wobble To Bottom Right', 'tlg_framework' ) => 'hvr-wobble-to-bottom-right',
            esc_html__( 'Wobble To Top Right', 'tlg_framework' ) 	=> 'hvr-wobble-to-top-right',
            esc_html__( 'Wobble Top', 'tlg_framework' ) 			=> 'hvr-wobble-top',
            esc_html__( 'Wobble Bottom', 'tlg_framework' ) 			=> 'hvr-wobble-bottom',
            esc_html__( 'Wobble Skew', 'tlg_framework' ) 			=> 'hvr-wobble-skew',
            esc_html__( 'Buzz Out', 'tlg_framework' ) 				=> 'hvr-buzz-out',
            esc_html__( 'Skew Forward', 'tlg_framework' ) 			=> 'hvr-skew-forward',
            esc_html__( 'Skew Backward', 'tlg_framework' ) 			=> 'hvr-skew-backward',
        );	
    }
}

/**
    ALLOWED HTML TAGS
**/
if( !function_exists('tlg_framework_allowed_tags') ) {
    function tlg_framework_allowed_tags() {
        return array( 'a' => array( 'href' => array(), 'title' => array(), 'class' => array(), 'target' => array() ), 'br' => array(), 'em' => array(), 'i' => array(), 'u' => array(), 'strong' => array(), 'p' => array( 'class' => array() ) );	
    }
}
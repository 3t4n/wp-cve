<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_shortcode( 'socialrocket', 'social_rocket_shortcode' );
function social_rocket_shortcode( $atts = array() ) {

	// if we're loading on an AMP page, stop here (our styles won't be available anyway)
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		return;
	}
	
	// if this is a feed, stop here
	if ( is_feed() ) {
		return;
	}
	
	// just a double check
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}
	
	$SR = Social_Rocket::get_instance();
	
	$output = '';
	$add_class = $SR->_isset( $atts['add_class'], '' );
	
	$inserts = array();
	if ( $SR->settings['inline_mobile_setting'] === 'disabled' ) {
		$inserts[] = array( 'where' => 'shortcode', 'what' => 'desktop-only' );
	} else {
		$inserts[] = array( 'where' => 'shortcode', 'what' => 'all' );
	
	}
	$inserts = apply_filters( 'social_rocket_inline_buttons_inserts', $inserts );
	
	$SR->data['doing_shortcode'] = 'inline';
	
	foreach ( $inserts as $insert ) {
		if ( $insert['where'] !== 'shortcode' ) {
			continue;
		}
		if ( $insert['what'] === 'all' ) {
			// no add_class
			$output .= $SR->get_inline_buttons_html( $atts );
		} else {
			if ( is_array( $insert['what'] ) ) {
				// multiple add_classes
				foreach ( $insert['what'] as $what ) {
					$atts['add_class'] = $add_class . ' social-rocket-'.$what;
					$output .= $SR->get_inline_buttons_html( $atts );
				}
			} else {
				// single add_class
				$atts['add_class'] = $add_class . ' social-rocket-'.$insert['what'];
				$output .= $SR->get_inline_buttons_html( $atts );
			}
		}
	}
	
	$SR->data['doing_shortcode'] = false;
	
	return $output;
}

add_shortcode( 'socialrocket-floating', 'social_rocket_floating_shortcode' );
function social_rocket_floating_shortcode( $atts = array() ) {
	
	// just a double check
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}
	
	$SR = Social_Rocket::get_instance();
	
	$SR->data['doing_shortcode'] = 'floating';
	
	$output = $SR->get_floating_buttons_html( $atts );
	
	$SR->data['doing_shortcode'] = false;
	
	return $output;
}

add_shortcode( 'socialrocket-tweet', 'social_rocket_tweet_shortcode' );
function social_rocket_tweet_shortcode( $atts = array() ) {
	
	// just a double check
	if ( ! is_array( $atts ) ) {
		$atts = array();
	}

	$SR = Social_Rocket::get_instance();
	
	$SR->data['doing_shortcode'] = 'tweet';
	
	$output = $SR->get_tweet_code( $atts );
	
	$SR->data['doing_shortcode'] = false;
	
	return $output;
}

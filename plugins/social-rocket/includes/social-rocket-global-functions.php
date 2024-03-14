<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function social_rocket( $args = array() ) {
	$SR = Social_Rocket::get_instance();
	$output = $SR->get_inline_buttons_html( $args );
	echo $output;
}

function social_rocket_floating( $args = array() ) {
	$SR = Social_Rocket::get_instance();
	$output = $SR->get_floating_buttons_html( $args );
	echo $output;
}

function social_rocket_tweet( $args = array() ) {
	$SR = Social_Rocket::get_instance();
	$output = $SR->get_tweet_code( $args );
	echo $output;
}

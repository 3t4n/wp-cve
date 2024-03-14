<?php

if( !defined('ABSPATH') ) {
	echo "Well done! Try Again";
	die();
}

function check_cat_status($checked) {

	return ( ( isset( $checked ) && true == $checked ) ? true : false );

}

function check_tag_status($checked) {

	return ( ( isset( $checked ) && true == $checked ) ? true : false );

}

function check_date_status($checked) {

	return ( ( isset( $checked ) && true == $checked ) ? true : false );

}

function rpfg_sanitize_number($number, $setting) {

	// Ensure $number is an absolute integer (whole number, zero or greater).
  	$number = absint( $number );

 	// If the input is an absolute integer, return it; otherwise, return the default
  	return ( $number ? $number : $setting->default );

}
<?php

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* FOR DISPLAYING CONTENT */

function iprm_display_as_shortcode() {
	$url     = iprm_get_option( 'iprm_active_product' );
	$podcast = new IPRM_Podcast( $url );
	return $podcast->display_page_reviews();
}

function iprm_display_navigation( $current_page ) {
	$navigation = array(
		'iprm_main_page'    => array(
			'class'  => 'dashicons-admin-site',
			'name'   => 'REVIEWS',
			'target' => '_self',
			'url'    => '?page=iprm_main_page',
		),
		'iprm_premium_page' => array(
			'class'  => 'dashicons-star-filled',
			'name'   => 'PREMIUM',
			'target' => '_self',
			'url'    => '?page=iprm_premium_page',
		),
	);
	$output     = '<div class="iprm-navigation">';
	foreach ( $navigation as $item => $value ) {
		$output .= '<a href="' . $value['url'] . '" target="' . $value['target'] . '" class="dashicons-before ' . $value['class'];
		if ( $item === $current_page ) {
			$output .= ' current-page';
		}
		$output .= '"> ' . $value['name'] . '</a> ';
	}
	$output .= '</div>';
	return $output;
}

function iprm_display_alert( $alert ) {
	if ( $alert ) {
		return '<div class="iprm-alert"><p>' . $alert . '</p></div>';
	}
}

function iprm_display_notice( $notice ) {
	if ( $notice ) {
		return '<div class="iprm-notice"><p>' . $notice . '</p></div>';
	}
}

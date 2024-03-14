<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lion_badges_text_align_options() {
	return array(
		'left' => __( 'Left', 'lionplugins' ),
		'center' => __( 'Center', 'lionplugins' ),
		'right' => __( 'Right', 'lionplugins' )
	);
}

function lion_badges_text_font_family_options() {
	return array(
		'default' => __( 'Theme default', 'lionplugins' ),
		'arial' => __( 'Arial', 'lionplugins' ),
		'arial_black' => __( 'Arial Black', 'lionplugins' ),
		'georgia' => __( 'Georgia', 'lionplugins' ),
		'palatino' => __( 'Palatino Linotype', 'lionplugins' ),
		'times_new_roman' => __( 'Times New Roman', 'lionplugins' ),
		'comic_sans_ms' => __( 'Comic Sans MS', 'lionplugins' ),
		'impact' => __( 'Impact', 'lionplugins' ),
		'tahoma' => __( 'Tahoma', 'lionplugins' ),
		'trebuchet_ms' => __( 'Trebuchet MS', 'lionplugins' ),
		'verdana' => __( 'Verdana', 'lionplugins' ),
		'courier_new' => __( 'Courier New', 'lionplugins' ),
		'lucida_console' => __( 'Lucida Console', 'lionplugins' ),
	);
}

function lion_badges_admin_get_font_family( $font_family ) {
	$fonts = lion_badges_text_font_family_options();

	return $fonts[$font_family];
}
<?php
function potter_kit_allowed_html( $input ) {
	$allowed_html = wp_kses_allowed_html( 'post' );
	$output       = wp_kses( $input, $allowed_html );
	return $output;
}

function potter_kit_current_url() {
	global $pagenow;
	$current_url = $pagenow == 'tools.php' ? admin_url( 'tools.php?page=potter-kit-tool' ) : admin_url( 'themes.php?page=potter-kit' );
	return apply_filters('potter_kit_current_url', $current_url, $pagenow );
}

function potter_kit_get_current_theme_author() {
	$current_theme = wp_get_theme();
	return $current_theme->get( 'Author' );
}
function potter_kit_get_current_theme_slug() {
	$current_theme = wp_get_theme();
	return $current_theme->stylesheet;
}
function potter_kit_get_theme_screenshot() {
	$current_theme = wp_get_theme();
	return $current_theme->get_screenshot();
}
function potter_kit_get_theme_name() {
	$current_theme = wp_get_theme();
	return $current_theme->get( 'Name' );
}

function potter_kit_update_option( $option, $value = '' ) {
	$option = apply_filters( 'potter_kit_update_option_' . $option, $option, $value );
	$value  = apply_filters( 'potter_kit_update_value_' . $option, $value, $option );
	update_option( $option, $value );
}

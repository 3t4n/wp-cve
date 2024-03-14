<?php
// убираем URL поле
/**
 * Removes the "Site" field from the comment form for unregistered users.
 *
 * @param array $fields Default fields
 *
 * @return array
 */
$all_options = get_option('atss_options');

$option_ats = get_option('atss_options'); if (isset($option_ats['urlcheckes'])) {
add_filter( 'comment_form_default_fields', 'comment_form_default_add_ats_fields' );
}
function comment_form_default_add_ats_fields( $fields ) {
	unset( $fields['url'] );
	return $fields;
}

$option_ats = get_option('atss_options'); if (isset($option_ats['emailcheckes'])) {
add_filter( 'comment_form_default_fields', 'comment_form_default_add_atss_fields' );
}
function comment_form_default_add_atss_fields( $fields ) {
	unset( $fields['email'] );
	return $fields;
}

$option_ats = get_option('atss_options'); if (isset($option_ats['checkescss'])) {
add_action( 'init', 'ats_plugin_too_init_styles' );
}
function ats_plugin_too_init_styles() {
	wp_enqueue_style( 'ats-privacy-bloc', plugins_url('/style-blocs.css', __FILE__) );
}
// plugin style
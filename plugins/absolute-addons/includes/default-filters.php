<?php
/**
 * Sets up the default filters and actions for most of the ABSP hooks.
 *
 * Not all of the default hooks are found in default-filters.php
 *
 * @package AbsoluteAddons
 * @author Kudratullah <mhamudul.hk@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

add_filter( 'absp/widgets/the_title', 'wptexturize' );
add_filter( 'absp/widgets/the_title', 'convert_chars' );
add_filter( 'absp/widgets/the_title', 'trim' );
add_filter( 'absp/widgets/the_title', 'capital_P_dangit', 11 );
add_filter( 'absp/widgets/the_content', 'capital_P_dangit', 11 );

add_filter( 'absp/widgets/the_content', 'wptexturize' );
add_filter( 'absp/widgets/the_content', 'convert_smilies', 20 );
add_filter( 'absp/widgets/the_content', 'wpautop' );
add_filter( 'absp/widgets/the_content', 'shortcode_unautop' );
add_filter( 'absp/widgets/the_content', 'prepend_attachment' );
add_filter( 'absp/widgets/the_content', 'wp_filter_content_tags' );
add_filter( 'absp/widgets/the_content', 'wp_replace_insecure_home_url' );
// Shortcodes.
add_filter( 'absp/widgets/the_content', 'do_shortcode', 11 ); // AFTER wpautop().

add_filter( 'absp/widgets/the_excerpt', 'wptexturize' );
add_filter( 'absp/widgets/the_excerpt', 'convert_smilies' );
add_filter( 'absp/widgets/the_excerpt', 'convert_chars' );
add_filter( 'absp/widgets/the_excerpt', 'wpautop' );
add_filter( 'absp/widgets/the_excerpt', 'shortcode_unautop' );
add_filter( 'absp/widgets/the_excerpt', 'wp_filter_content_tags' );
add_filter( 'absp/widgets/the_excerpt', 'wp_replace_insecure_home_url' );

add_filter( 'plugins_api_result', 'absp_plugin_api_results_dependency_filter', 10, 3 );

// End of file default-filters.php.
